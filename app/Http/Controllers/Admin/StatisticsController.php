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
 * UC-ADM-050: Theo dõi thống kê
 * Statistics Dashboard Controller
 */
class StatisticsController extends Controller
{
    /**
     * UC-ADM-050: Dashboard Thống kê chính
     * Display the main statistics dashboard
     */
    public function index()
    {
        // UC-ADM-052: Log đăng nhập (luôn hiển thị - <<include>>)
        $loginStats = $this->getLoginStats();

        // Thống kê tổng quan
        $overviewStats = $this->getOverviewStats();

        return view('admin.statistics.index', compact('loginStats', 'overviewStats'));
    }

    /**
     * UC-ADM-052: Log đăng nhập
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
     * UC-ADM-053: Log hoạt động (<<extend>>)
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
     * UC-ADM-054: Thống kê thời lượng (<<extend>>)
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
     * UC-ADM-055: Thống kê số người tham gia (<<extend>>)
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
     * Related to UC-SYS-004: Thống kê điểm số và xếp hạng
     */
    public function rankings(Request $request)
    {
        $query = StudentRanking::with(['student:id,name,email', 'classRoom:id,name', 'subject:id,name'])
            ->orderByDesc('gpa');

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
}
