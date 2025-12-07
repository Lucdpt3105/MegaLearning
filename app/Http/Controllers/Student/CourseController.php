<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * UC-STUDENT-001: Danh sách khóa học của học sinh
     */
    public function index()
    {
        $student = Auth::user();

        $enrolledClasses = $student->enrolledClasses()
            ->with([
                'subject',
                'teacher',
                'enrollments' => fn($q) => $q->where('student_id', $student->id)
            ])
            ->withCount([
                'students as active_students_count' => fn($q) => $q->where('class_enrollments.status', 'active')
            ])
            ->where('class_rooms.status', 'active')
            ->orderBy('start_date', 'desc')
            ->get();

        // Group by subject name
        $coursesBySubject = $enrolledClasses->groupBy(fn($class) =>
            $class->subject->name ?? 'Khác'
        );

        // Stats
        $stats = [
            'total_courses' => $enrolledClasses->count(),
            'active_courses' => $enrolledClasses->filter(fn($c) =>
                $c->pivot->status === 'active'
            )->count(),
            'total_subjects' => $enrolledClasses->pluck('subject_id')->unique()->count(),
        ];

        return view('student.courses.index', compact('enrolledClasses', 'coursesBySubject', 'stats'));
    }

    /**
     * UC-STUDENT-002: Chi tiết khóa học
     */
    public function show($id)
    {
        $student = Auth::user();

        $classRoom = ClassRoom::with([
            'subject.topics.questions',
            'teacher',
            'enrollments' => fn($q) => $q->where('student_id', $student->id),
            'videoCalls' => fn($q) => $q->orderBy('scheduled_at', 'desc')->take(5)
        ])
        ->withCount([
            'students as active_students_count' =>
                fn($q) => $q->where('class_enrollments.status', 'active')
        ])
        ->findOrFail($id);

        $enrollment = $classRoom->enrollments->first();

        if (!$enrollment || $enrollment->status !== 'active') {
            abort(403, 'Bạn không được phép xem khóa học này.');
        }

        $upcomingCalls = $classRoom->videoCalls()
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at')
            ->get();

        $totalTopics = $classRoom->subject->topics->count();
        $completedTopics = 0; // TODO: sau này sẽ dùng progress table
        $progressPercentage = $totalTopics > 0 ? round(($completedTopics / $totalTopics) * 100) : 0;

        return view('student.courses.show', compact(
            'classRoom',
            'enrollment',
            'upcomingCalls',
            'totalTopics',
            'completedTopics',
            'progressPercentage'
        ));
    }

    /**
     * UC-STUDENT-003: Tài liệu khóa học
     */
    public function materials($id)
    {
        $student = Auth::user();

        $classRoom = ClassRoom::with([
            'subject.topics' => fn($q) => $q->withCount('questions'),
            'enrollments' => fn($q) => $q->where('student_id', $student->id)
        ])
        ->findOrFail($id);

        $enrollment = $classRoom->enrollments->first();
        if (!$enrollment || $enrollment->status !== 'active') {
            abort(403, 'Bạn không được phép xem tài liệu khóa học này.');
        }

        $topics = $classRoom->subject->topics;

        return view('student.courses.materials', compact('classRoom', 'topics', 'enrollment'));
    }

    /**
     * UC-STUDENT-004: Lịch học
     */
    public function schedule($id)
    {
        $student = Auth::user();

        $classRoom = ClassRoom::with([
            'enrollments' => fn($q) => $q->where('student_id', $student->id),
            'videoCalls' => fn($q) => $q->orderBy('scheduled_at', 'desc')
        ])
        ->findOrFail($id);

        $enrollment = $classRoom->enrollments->first();
        if (!$enrollment || $enrollment->status !== 'active') {
            abort(403, 'Bạn không được phép xem lịch khóa học này.');
        }

        return view('student.courses.schedule', [
            'classRoom' => $classRoom,
            'videoCalls' => $classRoom->videoCalls,
            'enrollment' => $enrollment,
        ]);
    }

    /**
     * UC-STUDENT-005: Duyệt khóa học
     */
    public function browse()
    {
        $student = Auth::user();

        $enrolledClassIds = $student->enrolledClasses()->pluck('class_rooms.id');

        $availableClasses = ClassRoom::with(['subject', 'teacher'])
            ->withCount(['students as active_students_count' =>
                fn($q) => $q->where('class_enrollments.status', 'active')
            ])
            ->where('status', 'active')
            ->whereNotIn('id', $enrolledClassIds)
            ->whereRaw('(SELECT COUNT(*) FROM class_enrollments 
                         WHERE class_room_id = class_rooms.id 
                         AND status = "active") < max_students')
            ->orderBy('start_date', 'desc')
            ->get();

        $coursesBySubject = $availableClasses->groupBy(fn($class) =>
            $class->subject->name ?? 'Khác'
        );

        return view('student.courses.browse', compact('availableClasses', 'coursesBySubject'));
    }

    /**
     * UC-STUDENT-006: Đăng ký khóa học
     */
    public function enroll($id)
    {
        $student = Auth::user();

        $classRoom = ClassRoom::withCount(['students as active_students_count' =>
            fn($q) => $q->where('class_enrollments.status', 'active')
        ])
        ->findOrFail($id);

        $existing = $classRoom->enrollments()
            ->where('student_id', $student->id)
            ->first();

        if ($existing && $existing->status === 'active') {
            return back()->with('error', 'Bạn đã đăng ký khóa học này rồi!');
        }

        if ($classRoom->active_students_count >= $classRoom->max_students) {
            return back()->with('error', 'Khóa học đã đầy!');
        }

        if ($existing && $existing->status === 'dropped') {
            $existing->update([
                'status' => 'active',
                'enrolled_at' => now(),
                'dropped_at' => null,
            ]);
        } else {
            $classRoom->enrollments()->create([
                'student_id' => $student->id,
                'status' => 'active',
                'enrolled_at' => now(),
            ]);
        }

        return redirect()->route('student.courses.index')
            ->with('success', 'Đăng ký khóa học thành công!');
    }
}
