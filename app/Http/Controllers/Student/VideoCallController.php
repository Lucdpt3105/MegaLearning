<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\VideoCall;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VideoCallController extends Controller
{
    /**
     * Display list of video calls for enrolled classes
     */
    public function index()
    {
        $student = Auth::user();
        
        // Get enrolled class IDs
        $enrolledClassIds = $student->enrolledClasses()
            ->where('class_enrollments.status', 'active')
            ->pluck('class_rooms.id')
            ->toArray();

        // Get upcoming video calls
        $upcomingCalls = VideoCall::with(['classRoom.subject', 'classRoom.teacher', 'host'])
            ->whereIn('class_room_id', $enrolledClassIds)
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Get ongoing video calls
        $ongoingCalls = VideoCall::with(['classRoom.subject', 'classRoom.teacher', 'host'])
            ->whereIn('class_room_id', $enrolledClassIds)
            ->where('status', 'in_progress')
            ->orderBy('started_at', 'desc')
            ->get();

        // Get past video calls
        $pastCalls = VideoCall::with(['classRoom.subject', 'classRoom.teacher', 'host', 'attendance' => function($query) use ($student) {
                $query->where('attendance.student_id', $student->id);
            }])
            ->whereIn('class_room_id', $enrolledClassIds)
            ->where('status', 'ended')
            ->orderBy('ended_at', 'desc')
            ->take(10)
            ->get();

        // Get stats
        $stats = [
            'upcoming' => $upcomingCalls->count(),
            'today' => $upcomingCalls->filter(function($call) {
                return $call->scheduled_at->isToday();
            })->count(),
            'attended' => Attendance::where('attendance.student_id', $student->id)
                ->whereNotNull('attendance.video_call_id')
                ->where('attendance.status', 'present')
                ->count(),
        ];

        return view('student.video-calls.index', compact(
            'upcomingCalls',
            'ongoingCalls',
            'pastCalls',
            'stats'
        ));
    }

    /**
     * Show video call details
     */
    public function show($id)
    {
        $student = Auth::user();
        
        $videoCall = VideoCall::with([
            'classRoom.subject',
            'classRoom.teacher',
            'host',
            'attendance' => function($query) use ($student) {
                $query->where('attendance.student_id', $student->id);
            }
        ])->findOrFail($id);

        // Check if student is enrolled in this class
        $isEnrolled = $student->enrolledClasses()
            ->where('class_rooms.id', $videoCall->class_room_id)
            ->where('class_enrollments.status', 'active')
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'Bạn không có quyền truy cập cuộc họp này');
        }

        // Get student's attendance record
        $attendance = $videoCall->attendance->first();

        return view('student.video-calls.show', compact('videoCall', 'attendance'));
    }

    /**
     * Join video call
     */
    public function join($id)
    {
        $student = Auth::user();
        
        $videoCall = VideoCall::with(['classRoom.subject', 'classRoom.teacher', 'host'])
            ->findOrFail($id);

        // Check if student is enrolled
        $isEnrolled = $student->enrolledClasses()
            ->where('class_rooms.id', $videoCall->class_room_id)
            ->where('class_enrollments.status', 'active')
            ->exists();

        if (!$isEnrolled) {
            return redirect()->back()->with('error', 'Bạn không có quyền tham gia cuộc họp này');
        }

        // Check if meeting is available
        if ($videoCall->status === 'cancelled') {
            return redirect()->back()->with('error', 'Cuộc họp này đã bị hủy');
        }

        if ($videoCall->status === 'ended') {
            return redirect()->back()->with('error', 'Cuộc họp này đã kết thúc');
        }

        // Update meeting status if scheduled and start time has passed
        if ($videoCall->status === 'scheduled' && $videoCall->scheduled_at <= now()) {
            $videoCall->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        // Record attendance
        $attendance = Attendance::firstOrCreate([
            'video_call_id' => $videoCall->id,
            'student_id' => $student->id,
            'class_room_id' => $videoCall->class_room_id,
            'date' => now()->toDateString(),
        ], [
            'status' => 'present',
            'checked_in_at' => now(),
            'notes' => 'Joined via student portal',
        ]);

        // Update checked_in_at if already exists
        if (!$attendance->wasRecentlyCreated && !$attendance->checked_in_at) {
            $attendance->update([
                'checked_in_at' => now(),
                'status' => 'present',
            ]);
        }

        // Add student to participants list
        $participants = json_decode($videoCall->participants, true) ?? [];
        if (!in_array($student->id, $participants)) {
            $participants[] = $student->id;
            $videoCall->update(['participants' => json_encode($participants)]);
        }

        // Get all participants
        $participantUsers = \App\Models\User::whereIn('id', $participants)->get();

        // Redirect to meeting room
        return view('student.video-calls.room', [
            'videoCall' => $videoCall,
            'attendance' => $attendance,
            'participants' => $participantUsers,
        ]);
    }

    /**
     * Leave video call
     */
    public function leave($id)
    {
        $student = Auth::user();
        $videoCall = VideoCall::findOrFail($id);

        // Update attendance
        $attendance = Attendance::where('attendance.video_call_id', $videoCall->id)
            ->where('attendance.student_id', $student->id)
            ->first();

        if ($attendance && !$attendance->checked_out_at) {
            // Calculate duration in minutes
            $duration = $attendance->checked_in_at 
                ? now()->diffInMinutes($attendance->checked_in_at) 
                : 0;
            
            $attendance->update([
                'checked_out_at' => now(),
                'duration' => $duration,
            ]);
        }

        return redirect()->route('student.video-calls.index')
            ->with('success', 'Bạn đã rời khỏi cuộc họp');
    }
}
