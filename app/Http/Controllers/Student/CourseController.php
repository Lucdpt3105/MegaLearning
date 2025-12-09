<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display all courses the student is enrolled in
     * UC-STUDENT-001: View My Courses
     */
    public function index()
    {
        $student = Auth::user();
        
        // Get all enrolled classes with related data
        $enrolledClasses = $student->enrolledClasses()
            ->with([
                'subject',
                'teacher',
                'enrollments' => function($query) {
                    $query->where('class_enrollments.status', 'active');
                }
            ])
            ->withCount([
                'students' => function($query) {
                    $query->where('class_enrollments.status', 'active');
                }
            ])
            ->where('class_rooms.status', 'active')
            ->orderBy('class_rooms.start_date', 'desc')
            ->get();

        // Group by subject for better organization
        $coursesBySubject = $enrolledClasses->groupBy('subject.name');

        // Get enrollment statistics
        $stats = [
            'total_courses' => $enrolledClasses->count(),
            'active_courses' => $enrolledClasses->where('pivot_status', 'active')->count(),
            'total_subjects' => $enrolledClasses->pluck('subject_id')->unique()->count(),
        ];

        return view('student.courses.index', compact('enrolledClasses', 'coursesBySubject', 'stats'));
    }

    /**
     * Display details of a specific course
     * UC-STUDENT-002: View Course Details
     */
    public function show($id)
    {
        $student = Auth::user();
        
        // Get the class room with enrollment verification
        $classRoom = ClassRoom::with([
            'subject.topics.questions',
            'teacher',
            'enrollments' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            },
            'videoCalls' => function($query) {
                $query->orderBy('scheduled_at', 'desc')->take(5);
            }
        ])
        ->withCount([
            'students' => function($query) {
                $query->where('class_enrollments.status', 'active');
            }
        ])
        ->findOrFail($id);

        // Check if student is enrolled
        $enrollment = $classRoom->enrollments->first();
        if (!$enrollment || $enrollment->status !== 'active') {
            abort(403, 'You are not enrolled in this course');
        }

        // Get upcoming video calls
        $upcomingCalls = $classRoom->videoCalls()
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Get course progress
        $totalTopics = $classRoom->subject->topics->count();
        $completedTopics = 0; // TODO: Implement progress tracking
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
     * Display course materials (topics and questions)
     * UC-STUDENT-003: View Course Materials
     */
    public function materials($id)
    {
        $student = Auth::user();
        
        $classRoom = ClassRoom::with([
            'subject.topics' => function($query) {
                $query->withCount('questions');
            },
            'enrollments' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }
        ])
        ->findOrFail($id);

        // Verify enrollment
        $enrollment = $classRoom->enrollments->first();
        if (!$enrollment || $enrollment->status !== 'active') {
            abort(403, 'You are not enrolled in this course');
        }

        $topics = $classRoom->subject->topics;

        return view('student.courses.materials', compact('classRoom', 'topics', 'enrollment'));
    }

    /**
     * Display course schedule
     * UC-STUDENT-004: View Course Schedule
     */
    public function schedule($id)
    {
        $student = Auth::user();
        
        $classRoom = ClassRoom::with([
            'enrollments' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            },
            'videoCalls' => function($query) {
                $query->orderBy('scheduled_at', 'desc');
            }
        ])
        ->findOrFail($id);

        // Verify enrollment
        $enrollment = $classRoom->enrollments->first();
        if (!$enrollment || $enrollment->status !== 'active') {
            abort(403, 'You are not enrolled in this course');
        }

        $videoCalls = $classRoom->videoCalls;

        return view('student.courses.schedule', compact('classRoom', 'videoCalls', 'enrollment'));
    }

    /**
     * Browse available courses to enroll
     * UC-STUDENT-005: Browse Available Courses
     */
    public function browse(Request $request)
    {
        $student = Auth::user();
        
        // Get enrolled class IDs
        $enrolledClassIds = $student->enrolledClasses()->pluck('class_rooms.id');

        // Start query
        $query = ClassRoom::with([
            'subject',
            'teacher'
        ])
        ->withCount([
            'students' => function($query) {
                $query->where('class_enrollments.status', 'active');
            }
        ])
        ->where('status', 'active')
        ->whereNotIn('id', $enrolledClassIds)
        ->where(function($query) {
            // Only show classes that are not full
            $query->whereRaw('(SELECT COUNT(*) FROM class_enrollments WHERE class_room_id = class_rooms.id AND status = "active") < max_students');
        });

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('subject', function($subQ) use ($search) {
                      $subQ->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('teacher', function($teacherQ) use ($search) {
                      $teacherQ->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Apply subject filter
        if ($request->has('subject_id') && $request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        $availableClasses = $query->orderBy('start_date', 'desc')->get();

        // Group by subject
        $coursesBySubject = $availableClasses->groupBy('subject.name');

        // Get all subjects for filter dropdown
        $subjects = Subject::orderBy('name')->get();

        return view('student.courses.browse', compact(
            'availableClasses', 
            'coursesBySubject', 
            'subjects'
        ));
    }

    /**
     * Enroll student in a course
     * UC-STUDENT-006: Enroll in Course
     */
    public function enroll($id)
    {
        $student = Auth::user();
        $classRoom = ClassRoom::withCount([
            'students' => function($query) {
                $query->where('class_enrollments.status', 'active');
            }
        ])->findOrFail($id);

        // Check if already enrolled
        $existingEnrollment = $classRoom->enrollments()
            ->where('student_id', $student->id)
            ->first();

        if ($existingEnrollment) {
            if ($existingEnrollment->status === 'active') {
                return redirect()->back()->with('error', 'Bạn đã đăng ký khóa học này rồi!');
            } else {
                // Reactivate dropped enrollment
                $existingEnrollment->update([
                    'status' => 'active',
                    'enrolled_at' => now(),
                    'dropped_at' => null,
                ]);
                return redirect()->route('student.courses.index')->with('success', 'Đăng ký lại khóa học thành công!');
            }
        }

        // Check if class is full
        if ($classRoom->students_count >= $classRoom->max_students) {
            return redirect()->back()->with('error', 'Khóa học đã đầy, không thể đăng ký!');
        }

        // Check if class is active
        if ($classRoom->status !== 'active') {
            return redirect()->back()->with('error', 'Khóa học không còn hoạt động!');
        }

        // Create enrollment
        $classRoom->enrollments()->create([
            'student_id' => $student->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        return redirect()->route('student.courses.index')->with('success', 'Đăng ký khóa học thành công!');
    }
}
