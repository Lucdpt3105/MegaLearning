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
     * Log hoạt động (<<extend>>)
     * Detailed activity logs
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user');

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

        // Sắp xếp theo thời gian mới nhất
        $query->orderBy('created_at', 'DESC')->orderBy('id', 'DESC');

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
}
