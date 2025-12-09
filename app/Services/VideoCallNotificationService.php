<?php

namespace App\Services;

use App\Models\VideoCall;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VideoCallInvitation;

class VideoCallNotificationService
{
    /**
     * Notify students about new video call
     */
    public function notifyStudents(VideoCall $videoCall, array $studentIds = [])
    {
        $classRoom = $videoCall->classRoom;
        
        // If no specific students, get all enrolled students
        if (empty($studentIds)) {
            $studentIds = $classRoom->students()
                ->where('class_enrollments.status', 'active')
                ->pluck('users.id')
                ->toArray();
        }

        // Get student users
        $students = User::whereIn('id', $studentIds)->get();

        // Send notification to each student
        foreach ($students as $student) {
            // You can use Laravel notifications or database notifications
            // For now, we'll create a simple database notification
            \DB::table('notifications')->insert([
                'id' => \Str::uuid(),
                'type' => 'App\Notifications\VideoCallInvitation',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $student->id,
                'data' => json_encode([
                    'video_call_id' => $videoCall->id,
                    'title' => $videoCall->title,
                    'class_name' => $classRoom->name,
                    'subject_name' => $classRoom->subject->name,
                    'scheduled_at' => $videoCall->scheduled_at->toISOString(),
                    'host_name' => $videoCall->host->name,
                    'message' => "Bạn có một cuộc họp mới: {$videoCall->title}",
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return count($students);
    }

    /**
     * Notify about meeting start
     */
    public function notifyMeetingStarted(VideoCall $videoCall)
    {
        $classRoom = $videoCall->classRoom;
        $studentIds = $classRoom->students()
            ->where('class_enrollments.status', 'active')
            ->pluck('users.id')
            ->toArray();

        $students = User::whereIn('id', $studentIds)->get();

        foreach ($students as $student) {
            \DB::table('notifications')->insert([
                'id' => \Str::uuid(),
                'type' => 'App\Notifications\VideoCallStarted',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $student->id,
                'data' => json_encode([
                    'video_call_id' => $videoCall->id,
                    'title' => $videoCall->title,
                    'class_name' => $classRoom->name,
                    'message' => "Cuộc họp '{$videoCall->title}' đã bắt đầu!",
                    'action_url' => route('student.video-calls.join', $videoCall->id),
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return count($students);
    }

    /**
     * Get unread video call notifications count
     */
    public function getUnreadCount($userId)
    {
        return \DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', 'App\Models\User')
            ->whereIn('type', [
                'App\Notifications\VideoCallInvitation',
                'App\Notifications\VideoCallStarted',
            ])
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Get upcoming video calls for notification badge
     */
    public function getUpcomingCallsCount($userId)
    {
        $student = User::find($userId);
        if (!$student) {
            return 0;
        }

        $enrolledClassIds = $student->enrolledClasses()
            ->where('class_enrollments.status', 'active')
            ->pluck('class_rooms.id')
            ->toArray();

        if (empty($enrolledClassIds)) {
            return 0;
        }

        return VideoCall::whereIn('class_room_id', $enrolledClassIds)
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->where('scheduled_at', '<=', now()->addHours(24))
            ->count();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId)
    {
        return \DB::table('notifications')
            ->where('id', $notificationId)
            ->where('notifiable_id', $userId)
            ->update(['read_at' => now()]);
    }

    /**
     * Mark all video call notifications as read
     */
    public function markAllAsRead($userId)
    {
        return \DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->whereIn('type', [
                'App\Notifications\VideoCallInvitation',
                'App\Notifications\VideoCallStarted',
            ])
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
