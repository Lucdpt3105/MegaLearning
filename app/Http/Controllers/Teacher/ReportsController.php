<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\ClassRoom;
use App\Models\Document;
use App\Models\VideoCall;
use App\Models\Grade;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Teacher Reports & Analytics Controller
 * Quản lý thống kê kết quả (Gradebook)
 */
class ReportsController extends Controller
{
    /**
     * Main reports dashboard
     */
    public function index()
    {
        $teacher = Auth::user();
        
        // Get teacher's subjects
        $subjects = Subject::where('teacher_id', $teacher->id)
            ->withCount(['classRooms', 'exams', 'documents'])
            ->get();

        // Overview statistics
        $stats = [
            'total_subjects' => $subjects->count(),
            'total_classes' => ClassRoom::whereHas('subject', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })->count(),
            'total_students' => DB::table('class_enrollments')
                ->join('class_rooms', 'class_enrollments.class_room_id', '=', 'class_rooms.id')
                ->join('subjects', 'class_rooms.subject_id', '=', 'subjects.id')
                ->where('subjects.teacher_id', $teacher->id)
                ->distinct('class_enrollments.student_id')
                ->count('class_enrollments.student_id'),
            'total_exams' => Exam::whereHas('subject', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })->count(),
            'total_submissions' => ExamSubmission::whereHas('exam.subject', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })->count(),
            'pending_grading' => ExamSubmission::whereHas('exam.subject', function($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })->where('grading_status', 'pending')->count(),
        ];

        return view('teacher.reports.index', compact('subjects', 'stats'));
    }

    /**
     * Xem kết quả tổng hợp của lớp
     * Class performance overview
     */
    public function classPerformance(Request $request, $classRoomId)
    {
        $classRoom = ClassRoom::with(['subject', 'enrollments.student'])
            ->findOrFail($classRoomId);

        // Verify teacher owns this class
        if ($classRoom->subject->teacher_id !== Auth::id()) {
            abort(403);
        }

        // Get all exams for this class's subject
        $exams = Exam::where('subject_id', $classRoom->subject_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all students with their exam scores
        $students = $classRoom->enrollments->map(function($enrollment) use ($exams) {
            $student = $enrollment->student;
            $scores = [];
            $totalScore = 0;
            $completedExams = 0;

            foreach ($exams as $exam) {
                $submission = ExamSubmission::where('student_id', $student->id)
                    ->where('exam_id', $exam->id)
                    ->where('grading_status', 'completed')
                    ->first();

                if ($submission) {
                    $scores[] = $submission->total_score;
                    $totalScore += $submission->total_score;
                    $completedExams++;
                } else {
                    $scores[] = null;
                }
            }

            return [
                'student' => $student,
                'scores' => $scores,
                'average' => $completedExams > 0 ? round($totalScore / $completedExams, 2) : 0,
                'completed_exams' => $completedExams,
            ];
        })->sortByDesc('average')->values();

        // Class statistics
        $classStats = [
            'highest_score' => $students->max('average'),
            'lowest_score' => $students->filter(fn($s) => $s['average'] > 0)->min('average') ?? 0,
            'average_score' => round($students->avg('average'), 2),
            'pass_rate' => $students->count() > 0 
                ? round($students->filter(fn($s) => $s['average'] >= 5)->count() / $students->count() * 100, 1)
                : 0,
        ];

        return view('teacher.reports.class-performance', compact('classRoom', 'exams', 'students', 'classStats'));
    }

    /**
     * Xem kết quả của từng học sinh
     * Individual student performance
     */
    public function studentPerformance($classRoomId, $studentId)
    {
        $classRoom = ClassRoom::with('subject')->findOrFail($classRoomId);
        
        // Verify teacher owns this class
        if ($classRoom->subject->teacher_id !== Auth::id()) {
            abort(403);
        }

        $student = \App\Models\User::findOrFail($studentId);

        // Get all submissions for this student
        $submissions = ExamSubmission::with('exam')
            ->where('student_id', $studentId)
            ->whereHas('exam', function($q) use ($classRoom) {
                $q->where('subject_id', $classRoom->subject_id);
            })
            ->orderBy('submitted_at', 'desc')
            ->get();

        // Student statistics
        $studentStats = [
            'total_exams' => $submissions->count(),
            'completed' => $submissions->where('grading_status', 'completed')->count(),
            'pending' => $submissions->where('grading_status', 'pending')->count(),
            'average_score' => round($submissions->where('grading_status', 'completed')->avg('total_score'), 2),
            'highest_score' => $submissions->where('grading_status', 'completed')->max('total_score'),
            'lowest_score' => $submissions->where('grading_status', 'completed')->min('total_score'),
        ];

        // Attendance
        $attendance = Attendance::where('class_room_id', $classRoomId)
            ->where('student_id', $studentId)
            ->get();

        $attendanceStats = [
            'total_sessions' => $attendance->count(),
            'present' => $attendance->where('status', 'present')->count(),
            'absent' => $attendance->where('status', 'absent')->count(),
            'late' => $attendance->where('status', 'late')->count(),
            'rate' => $attendance->count() > 0 
                ? round($attendance->where('status', 'present')->count() / $attendance->count() * 100, 1)
                : 0,
        ];

        return view('teacher.reports.student-performance', compact(
            'classRoom', 
            'student', 
            'submissions', 
            'studentStats',
            'attendance',
            'attendanceStats'
        ));
    }

    /**
     * Exam analysis
     */
    public function examAnalysis($examId)
    {
        $exam = Exam::with('subject')->findOrFail($examId);
        
        // Verify teacher owns this exam
        if ($exam->subject->teacher_id !== Auth::id()) {
            abort(403);
        }

        $submissions = ExamSubmission::where('exam_id', $examId)
            ->where('grading_status', 'completed')
            ->with('student')
            ->get();

        $examStats = [
            'total_submissions' => $submissions->count(),
            'average_score' => round($submissions->avg('total_score'), 2),
            'highest_score' => $submissions->max('total_score'),
            'lowest_score' => $submissions->min('total_score'),
            'pass_count' => $submissions->filter(fn($s) => $s->total_score >= 5)->count(),
            'pass_rate' => $submissions->count() > 0 
                ? round($submissions->filter(fn($s) => $s->total_score >= 5)->count() / $submissions->count() * 100, 1)
                : 0,
        ];

        // Score distribution
        $scoreDistribution = [
            '9-10' => $submissions->filter(fn($s) => $s->total_score >= 9)->count(),
            '8-8.9' => $submissions->filter(fn($s) => $s->total_score >= 8 && $s->total_score < 9)->count(),
            '7-7.9' => $submissions->filter(fn($s) => $s->total_score >= 7 && $s->total_score < 8)->count(),
            '6-6.9' => $submissions->filter(fn($s) => $s->total_score >= 6 && $s->total_score < 7)->count(),
            '5-5.9' => $submissions->filter(fn($s) => $s->total_score >= 5 && $s->total_score < 6)->count(),
            '<5' => $submissions->filter(fn($s) => $s->total_score < 5)->count(),
        ];

        return view('teacher.reports.exam-analysis', compact('exam', 'submissions', 'examStats', 'scoreDistribution'));
    }

    /**
     * Subject overview
     */
    public function subjectOverview($subjectId)
    {
        $subject = Subject::with(['classRooms', 'exams', 'documents'])
            ->findOrFail($subjectId);
        
        // Verify teacher owns this subject
        if ($subject->teacher_id !== Auth::id()) {
            abort(403);
        }

        // Get all students enrolled
        $totalStudents = DB::table('class_enrollments')
            ->join('class_rooms', 'class_enrollments.class_room_id', '=', 'class_rooms.id')
            ->where('class_rooms.subject_id', $subjectId)
            ->distinct('class_enrollments.student_id')
            ->count('class_enrollments.student_id');

        // Video call stats
        $videoCallStats = VideoCall::whereHas('classRoom', function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            })
            ->selectRaw('COUNT(*) as total_calls, SUM(duration) as total_duration, AVG(duration) as avg_duration')
            ->first();

        // Document stats
        $documentStats = Document::where('subject_id', $subjectId)
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN approval_status = "approved" THEN 1 ELSE 0 END) as approved')
            ->first();

        $subjectStats = [
            'total_students' => $totalStudents,
            'total_classes' => $subject->classRooms->count(),
            'total_exams' => $subject->exams->count(),
            'total_documents' => $documentStats->total ?? 0,
            'approved_documents' => $documentStats->approved ?? 0,
            'total_video_calls' => $videoCallStats->total_calls ?? 0,
            'total_call_duration' => $videoCallStats->total_duration ?? 0,
            'avg_call_duration' => round($videoCallStats->avg_duration ?? 0, 1),
        ];

        return view('teacher.reports.subject-overview', compact('subject', 'subjectStats'));
    }

    /**
     * Export gradebook to Excel
     */
    public function exportGradebook($classRoomId)
    {
        // TODO: Implement Excel export using Laravel Excel
        return response()->json(['message' => 'Export functionality will be implemented']);
    }

    /**
     * Print gradebook
     */
    public function printGradebook($classRoomId)
    {
        $classRoom = ClassRoom::with(['subject', 'enrollments.student'])
            ->findOrFail($classRoomId);

        // Verify teacher owns this class
        if ($classRoom->subject->teacher_id !== Auth::id()) {
            abort(403);
        }

        $exams = Exam::where('subject_id', $classRoom->subject_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $students = $classRoom->enrollments->map(function($enrollment) use ($exams) {
            $student = $enrollment->student;
            $scores = [];

            foreach ($exams as $exam) {
                $submission = ExamSubmission::where('student_id', $student->id)
                    ->where('exam_id', $exam->id)
                    ->where('grading_status', 'completed')
                    ->first();

                $scores[] = $submission ? $submission->total_score : null;
            }

            return [
                'student' => $student,
                'scores' => $scores,
            ];
        });

        return view('teacher.reports.print-gradebook', compact('classRoom', 'exams', 'students'));
    }
}
