<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\Document;
use App\Models\ClassRoom;
use App\Models\StudentRanking;
use App\Models\VideoCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Theo dõi thống kê
 * Statistics Dashboard Controller
 */
class StatisticsController extends Controller
{
    /**
     * Dashboard Thống kê chính
     * Display the main statistics dashboard
     */
    public function index()
    {
        // Log đăng nhập (luôn hiển thị - <<include>>)
        $loginStats = $this->getLoginStats();

        // Thống kê tổng quan
        $overviewStats = $this->getOverviewStats();

        return view('admin.statistics.index', compact('loginStats', 'overviewStats'));
    }

    /**
     * Log đăng nhập
     * Get login statistics (24 hours)
     */
    public function getLoginStats()
    {
        $last24Hours = Carbon::now()->subDay();

        $successfulLogins = ActivityLog::where('action', 'login')
            ->where('created_at', '>=', $last24Hours)
            ->whereNull('description') // Successful login has no error description
            ->count();

        $failedLogins = ActivityLog::where('action', 'login_failed')
            ->where('created_at', '>=', $last24Hours)
            ->count();

        // Lượt đăng nhập theo giờ (24 giờ qua)
        $hourlyLogins = ActivityLog::where('action', 'login')
            ->where('created_at', '>=', $last24Hours)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Top users đăng nhập nhiều nhất
        $topUsers = ActivityLog::where('action', 'login')
            ->where('created_at', '>=', $last24Hours)
            ->select('user_id', DB::raw('COUNT(*) as login_count'))
            ->groupBy('user_id')
            ->orderByDesc('login_count')
            ->limit(10)
            ->with('user:id,name,email')
            ->get();

        return [
            'successful' => $successfulLogins,
            'failed' => $failedLogins,
            'hourly' => $hourlyLogins,
            'top_users' => $topUsers,
            'total' => $successfulLogins + $failedLogins,
            'success_rate' => $successfulLogins + $failedLogins > 0 
                ? round(($successfulLogins / ($successfulLogins + $failedLogins)) * 100, 2) 
                : 0,
        ];
    }

    /**
     * Log hoạt động (<<extend>>)
     * Detailed activity logs
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')
            ->orderByDesc('created_at');

        // Filters
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('entity_type')) {
            $query->where('entity_type', $request->entity_type);
        }

        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);

        // Activity types for filter dropdown
        $actionTypes = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $entityTypes = ActivityLog::select('entity_type')
            ->whereNotNull('entity_type')
            ->distinct()
            ->orderBy('entity_type')
            ->pluck('entity_type');

        return view('admin.statistics.activity-logs', compact('logs', 'actionTypes', 'entityTypes'));
    }

    /**
     * Thống kê thời lượng (<<extend>>)
     * Average usage duration statistics
     */
    public function usageDuration(Request $request)
    {
        $period = $request->get('period', '7days'); // 7days, 30days, 90days

        $days = match($period) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            default => 7,
        };

        $startDate = Carbon::now()->subDays($days);

        // Thống kê thời gian sử dụng trung bình theo vai trò
        $usageByRole = User::select('users.id', 'users.name', 'users.email', 'model_has_roles.role_id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->leftJoin('activity_logs', function($join) use ($startDate) {
                $join->on('users.id', '=', 'activity_logs.user_id')
                     ->where('activity_logs.created_at', '>=', $startDate);
            })
            ->select(
                'roles.name as role_name',
                DB::raw('COUNT(DISTINCT users.id) as user_count'),
                DB::raw('COUNT(activity_logs.id) as total_actions'),
                DB::raw('ROUND(COUNT(activity_logs.id) / COUNT(DISTINCT users.id), 2) as avg_actions_per_user')
            )
            ->groupBy('roles.name')
            ->get();

        // Thời gian sử dụng theo ngày
        $dailyUsage = ActivityLog::where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_actions'),
                DB::raw('COUNT(DISTINCT user_id) as active_users')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top active users
        $topActiveUsers = ActivityLog::where('created_at', '>=', $startDate)
            ->select('user_id', DB::raw('COUNT(*) as action_count'))
            ->groupBy('user_id')
            ->orderByDesc('action_count')
            ->limit(20)
            ->with('user:id,name,email')
            ->get();

        return view('admin.statistics.usage-duration', compact(
            'usageByRole', 
            'dailyUsage', 
            'topActiveUsers', 
            'period'
        ));
    }

    /**
     * Thống kê số người tham gia (<<extend>>)
     * Participation statistics by subject
     */
    public function participation(Request $request)
    {
        // Thống kê theo môn học
        $subjectStats = Subject::select('subjects.*')
            ->withCount([
                'classRooms',
                'classRooms as total_students' => function($query) {
                    $query->select(DB::raw('COUNT(DISTINCT class_enrollments.student_id)'))
                          ->join('class_enrollments', 'class_rooms.id', '=', 'class_enrollments.class_room_id');
                },
                'exams',
                'documents'
            ])
            ->with(['teacher:id,name,email'])
            ->get();

        // Thống kê tham gia video call
        $videoCallStats = VideoCall::select(
                'class_room_id',
                DB::raw('COUNT(*) as total_calls'),
                DB::raw('SUM(duration) as total_duration'),
                DB::raw('AVG(duration) as avg_duration')
            )
            ->groupBy('class_room_id')
            ->with('classRoom.subject:id,name')
            ->get()
            ->map(function ($stat) {
                // Calculate max participants from JSON participants field
                $maxParticipants = VideoCall::where('class_room_id', $stat->class_room_id)
                    ->whereNotNull('participants')
                    ->get()
                    ->map(function ($call) {
                        $participants = is_string($call->participants) 
                            ? json_decode($call->participants, true) 
                            : $call->participants;
                        return is_array($participants) ? count($participants) : 0;
                    })
                    ->max() ?? 0;
                
                $stat->max_participants = $maxParticipants;
                return $stat;
            });

        // Thống kê đề thi và bài nộp
        $examParticipation = Exam::select('exams.*')
            ->withCount('submissions as total_submissions')
            ->with('subject:id,name')
            ->orderByDesc('total_submissions')
            ->limit(20)
            ->get();

        // Tỷ lệ hoàn thành bài thi
        $completionRate = ExamSubmission::select(
                'grading_status',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('grading_status')
            ->get();

        return view('admin.statistics.participation', compact(
            'subjectStats',
            'videoCallStats',
            'examParticipation',
            'completionRate'
        ));
    }

    /**
     * Overview statistics for dashboard
     */
    private function getOverviewStats()
    {
        return [
            'total_users' => User::count(),
            'total_teachers' => User::role('teacher')->count(),
            'total_students' => User::role('student')->count(),
            'total_subjects' => Subject::count(),
            'total_classes' => ClassRoom::count(),
            'total_exams' => Exam::count(),
            'total_documents' => Document::count(),
            'total_submissions' => ExamSubmission::count(),
            'active_users_today' => ActivityLog::whereDate('created_at', today())
                ->distinct('user_id')
                ->count('user_id'),
        ];
    }

    /**
     * Get student rankings
     * Related to Thống kê điểm số và xếp hạng
     */
    public function rankings(Request $request)
    {
        $query = StudentRanking::with(['student:id,name,email', 'classRoom:id,name', 'subject:id,name'])
            ->orderByDesc('gpa')
            ->orderBy('rank');

        // Filters
        if ($request->has('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $rankings = $query->paginate(50);

        $classRooms = ClassRoom::select('id', 'name')->orderBy('name')->get();
        $subjects = Subject::select('id', 'name')->orderBy('name')->get();

        return view('admin.statistics.rankings', compact('rankings', 'classRooms', 'subjects'));
    }

    /**
     * Recalculate rankings manually
     */
    public function recalculateRankings(Request $request)
    {
        try {
            $rankingService = app(\App\Services\RankingService::class);
            
            if ($request->has('class_room_id')) {
                // Tính cho một lớp cụ thể
                $updated = $rankingService->calculateClassRoomRanking($request->class_room_id);
                $message = "Đã cập nhật xếp hạng cho {$updated} học sinh trong lớp này";
            } else {
                // Tính cho tất cả
                $updated = $rankingService->calculateAllRankings();
                $message = "Đã cập nhật xếp hạng cho {$updated} học sinh";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'total_updated' => $updated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export statistics to Excel/PDF
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'overview');
        
        // TODO: Implement export functionality using Laravel Excel
        return response()->json([
            'message' => 'Export functionality will be implemented',
            'type' => $type
        ]);
    }

    /**
     * Thống kê học sinh chi tiết
     * Student Statistics - detailed performance analysis
     */
    public function studentStatistics(Request $request)
    {
        $query = User::role('student')
            ->with(['studentRankings', 'examSubmissions', 'classEnrollments.classRoom.subject']);

        // Filters
        if ($request->has('class_room_id')) {
            $query->whereHas('classEnrollments', function($q) use ($request) {
                $q->where('class_room_id', $request->class_room_id);
            });
        }

        if ($request->has('subject_id')) {
            $query->whereHas('classEnrollments.classRoom', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(20);

        // Thống kê chi tiết cho mỗi học sinh
        $students->getCollection()->transform(function ($student) use ($request) {
            // Lọc submissions theo class/subject nếu có
            $submissionsQuery = $student->examSubmissions()
                ->where('status', 'submitted');

            if ($request->has('class_room_id')) {
                $submissionsQuery->whereHas('exam.classRoom', function($q) use ($request) {
                    $q->where('id', $request->class_room_id);
                });
            }

            if ($request->has('subject_id')) {
                $submissionsQuery->whereHas('exam.subject', function($q) use ($request) {
                    $q->where('id', $request->subject_id);
                });
            }

            $submissions = $submissionsQuery->get();

            $student->total_submissions = $submissions->count();
            $student->graded_submissions = $submissions->where('grading_status', 'graded')->count();
            $student->pending_submissions = $submissions->where('grading_status', 'pending')->count();
            
            $gradedScores = $submissions->where('grading_status', 'graded')->whereNotNull('score');
            $student->average_score = $gradedScores->count() > 0 
                ? round($gradedScores->avg('score'), 2) 
                : null;
            
            $student->highest_score = $gradedScores->count() > 0 
                ? $gradedScores->max('score') 
                : null;
            
            $student->lowest_score = $gradedScores->count() > 0 
                ? $gradedScores->min('score') 
                : null;

            // Số lớp đã tham gia
            $student->enrolled_classes = $student->classEnrollments->count();

            // Tỷ lệ hoàn thành (có điểm/tổng bài thi)
            $student->completion_rate = $student->total_submissions > 0
                ? round(($student->graded_submissions / $student->total_submissions) * 100, 1)
                : 0;

            return $student;
        });

        // Thống kê tổng quan
        $overallStats = [
            'total_students' => User::role('student')->count(),
            'active_students' => User::role('student')
                ->whereHas('examSubmissions', function($q) {
                    $q->where('created_at', '>=', Carbon::now()->subMonth());
                })
                ->count(),
            'average_submissions' => ExamSubmission::whereHas('student', function($q) {
                $q->role('student');
            })->count() / max(User::role('student')->count(), 1),
            'average_score_all' => ExamSubmission::whereHas('student', function($q) {
                    $q->role('student');
                })
                ->where('grading_status', 'graded')
                ->whereNotNull('score')
                ->avg('score'),
        ];

        // Top performers
        $topPerformers = User::role('student')
            ->withAvg(['examSubmissions as avg_score' => function($q) {
                $q->where('grading_status', 'graded')->whereNotNull('score');
            }], 'score')
            ->having('avg_score', '>', 0)
            ->orderByDesc('avg_score')
            ->limit(10)
            ->get();

        // Distribution điểm số
        $scoreDistribution = ExamSubmission::whereHas('student', function($q) {
                $q->role('student');
            })
            ->where('grading_status', 'graded')
            ->whereNotNull('score')
            ->selectRaw('
                CASE 
                    WHEN score >= 9 THEN "9-10"
                    WHEN score >= 8 THEN "8-9"
                    WHEN score >= 7 THEN "7-8"
                    WHEN score >= 6 THEN "6-7"
                    WHEN score >= 5 THEN "5-6"
                    ELSE "0-5"
                END as score_range,
                COUNT(*) as count
            ')
            ->groupBy('score_range')
            ->orderBy('score_range', 'DESC')
            ->get();

        // Data for filters
        $classRooms = ClassRoom::select('id', 'name', 'subject_id')
            ->with('subject:id,name')
            ->orderBy('name')
            ->get();
        
        $subjects = Subject::select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.statistics.student-statistics', compact(
            'students',
            'overallStats',
            'topPerformers',
            'scoreDistribution',
            'classRooms',
            'subjects'
        ));
    }
}
