<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Exam;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $notifications = Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', auth()->id())
            ->latest()
            ->paginate(20);

        if ($request->wantsJson() || $request->has('json')) {
            return response()->json($notifications);
        }

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        $count = Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Ensure the notification belongs to the authenticated user
        if ($notification->notifiable_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Send notification to students (for teachers)
     */
    public function sendToStudents(Request $request)
    {
        try {
            $request->validate([
                'exam_id' => 'required|exists:exams,id',
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'type' => 'required|in:exam_reminder,exam_update,general',
            ]);

            $exam = Exam::findOrFail($request->exam_id);

            // Check if the teacher owns this exam
            if ($exam->created_by !== auth()->id()) {
                return back()->with('error', 'Bạn không có quyền gửi thông báo cho đề thi này.');
            }

            // Get students from the class room
            $students = [];
            if ($exam->class_room_id) {
                $classRoom = ClassRoom::with('students')->find($exam->class_room_id);
                if ($classRoom) {
                    $students = $classRoom->students;
                }
            }

            if (empty($students) || $students->count() === 0) {
                return back()->with('error', 'Không tìm thấy học sinh nào trong lớp học này.');
            }

            // Create notifications for each student
            $notificationsCreated = 0;
            foreach ($students as $student) {
                try {
                    Notification::create([
                        'id' => Str::uuid(),
                        'type' => $request->type,
                        'notifiable_type' => User::class,
                        'notifiable_id' => $student->id,
                        'data' => json_encode([
                            'title' => $request->title,
                            'message' => $request->message,
                            'exam_id' => $exam->id,
                            'exam_title' => $exam->title,
                            'teacher_name' => auth()->user()->name,
                            'url' => route('student.exams.show', $exam->id),
                        ]),
                    ]);
                    $notificationsCreated++;
                } catch (\Exception $e) {
                    \Log::error('Error creating notification for student ' . $student->id . ': ' . $e->getMessage());
                }
            }

            if ($notificationsCreated === 0) {
                return back()->with('error', 'Không thể tạo thông báo. Vui lòng kiểm tra lại.');
            }

            return back()->with('success', "✅ Đã gửi thông báo thành công tới {$notificationsCreated} học sinh!");
        } catch (\Exception $e) {
            \Log::error('Error in sendToStudents: ' . $e->getMessage());
            return back()->with('error', '❌ Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        
        // Ensure the notification belongs to the authenticated user
        if ($notification->notifiable_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
