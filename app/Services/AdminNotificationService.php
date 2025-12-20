<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Str;

class AdminNotificationService
{
    /**
     * Gửi thông báo cho tất cả admin
     */
    public function notifyAdmins($title, $message, $type = 'general', $url = null, $additionalData = [])
    {
        $admins = User::role('admin')->get();
        
        $notificationsCreated = 0;
        foreach ($admins as $admin) {
            try {
                Notification::create([
                    'id' => Str::uuid(),
                    'type' => $type,
                    'notifiable_type' => User::class,
                    'notifiable_id' => $admin->id,
                    'data' => json_encode(array_merge([
                        'title' => $title,
                        'message' => $message,
                        'url' => $url,
                    ], $additionalData)),
                ]);
                $notificationsCreated++;
            } catch (\Exception $e) {
                \Log::error('Error creating admin notification: ' . $e->getMessage());
            }
        }
        
        return $notificationsCreated;
    }

    /**
     * Thông báo khi có học sinh mới đăng ký
     */
    public function notifyNewStudentRegistration($student)
    {
        return $this->notifyAdmins(
            '🎓 Học sinh mới đăng ký',
            "Học sinh {$student->name} ({$student->email}) vừa đăng ký tài khoản.",
            'user_registration',
            route('admin.users.show', $student->id),
            [
                'user_id' => $student->id,
                'user_name' => $student->name,
                'user_email' => $student->email,
            ]
        );
    }

    /**
     * Thông báo khi có giáo viên mới đăng ký
     */
    public function notifyNewTeacherRegistration($teacher)
    {
        return $this->notifyAdmins(
            '👨‍🏫 Giáo viên mới đăng ký',
            "Giáo viên {$teacher->name} ({$teacher->email}) vừa đăng ký tài khoản.",
            'user_registration',
            route('admin.users.show', $teacher->id),
            [
                'user_id' => $teacher->id,
                'user_name' => $teacher->name,
                'user_email' => $teacher->email,
            ]
        );
    }

    /**
     * Thông báo khi có bài nộp mới
     */
    public function notifyNewExamSubmission($submission)
    {
        $exam = $submission->exam;
        $student = $submission->student ?? auth()->user();
        
        return $this->notifyAdmins(
            '📝 Bài nộp mới',
            "Học sinh {$student->name} vừa nộp bài thi \"{$exam->title}\"",
            'exam_submission',
            route('admin.courses.index'),
            [
                'submission_id' => $submission->id,
                'exam_id' => $exam->id,
                'exam_title' => $exam->title,
                'student_id' => $student->id,
                'student_name' => $student->name,
            ]
        );
    }

    /**
     * Thông báo khi giáo viên tạo lớp mới
     */
    public function notifyNewClassCreated($classRoom)
    {
        $teacher = $classRoom->teacher ?? auth()->user();
        
        return $this->notifyAdmins(
            '📚 Lớp học mới',
            "Giáo viên {$teacher->name} vừa tạo lớp học \"{$classRoom->name}\"",
            'class_created',
            route('admin.courses.edit', $classRoom->id),
            [
                'class_id' => $classRoom->id,
                'class_name' => $classRoom->name,
                'teacher_id' => $teacher->id,
                'teacher_name' => $teacher->name,
            ]
        );
    }

    /**
     * Thông báo khi giáo viên tạo bài thi mới
     */
    public function notifyNewExamCreated($exam, $teacher, $classRoom = null)
    {
        $className = $classRoom ? $classRoom->name : 'N/A';
        
        return $this->notifyAdmins(
            '📋 Bài thi mới',
            "Giáo viên {$teacher->name} vừa tạo bài thi \"{$exam->title}\" cho lớp {$className}",
            'exam_created',
            route('admin.courses.index'),
            [
                'exam_id' => $exam->id,
                'exam_title' => $exam->title,
                'teacher_id' => $teacher->id,
                'teacher_name' => $teacher->name,
                'class_name' => $className,
            ]
        );
    }

    /**
     * Thông báo khi học sinh tham gia lớp
     */
    public function notifyStudentEnrolled($enrollment, $student, $classRoom)
    {
        return $this->notifyAdmins(
            '✅ Học sinh tham gia lớp',
            "Học sinh {$student->name} vừa tham gia lớp \"{$classRoom->name}\"",
            'student_enrolled',
            route('admin.courses.edit', $classRoom->id),
            [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'class_id' => $classRoom->id,
                'class_name' => $classRoom->name,
            ]
        );
    }

    /**
     * Thông báo khi có câu hỏi diễn đàn mới
     */
    public function notifyNewForumQuestion($question, $user)
    {
        return $this->notifyAdmins(
            '💬 Câu hỏi diễn đàn mới',
            "{$user->name} vừa đặt câu hỏi: \"{$question->title}\"",
            'forum_question',
            '#', // route to forum if exists
            [
                'question_id' => $question->id,
                'question_title' => $question->title,
                'user_id' => $user->id,
                'user_name' => $user->name,
            ]
        );
    }

    /**
     * Thông báo khi có tài liệu mới được upload
     */
    public function notifyNewDocumentUploaded($document)
    {
        $uploader = $document->uploader ?? auth()->user();
        
        return $this->notifyAdmins(
            '📄 Tài liệu mới',
            "{$uploader->name} vừa upload tài liệu \"{$document->title}\"",
            'document_uploaded',
            route('admin.files.index'),
            [
                'document_id' => $document->id,
                'document_title' => $document->title,
                'uploader_id' => $uploader->id,
                'uploader_name' => $uploader->name,
            ]
        );
    }

    /**
     * Thông báo hệ thống quan trọng
     */
    public function notifySystemAlert($title, $message, $url = null)
    {
        return $this->notifyAdmins(
            "⚠️ {$title}",
            $message,
            'system_alert',
            $url
        );
    }
}
