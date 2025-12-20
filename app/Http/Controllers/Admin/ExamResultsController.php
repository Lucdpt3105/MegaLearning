<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSubmission;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ExamResultsController extends Controller
{
    /**
     * Display all exam results
     */
    public function index(Request $request)
    {
        $query = ExamSubmission::with(['exam.subject', 'exam.classRoom', 'student', 'grader'])
            ->where('status', 'submitted');

        // Filter by exam
        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->whereHas('exam', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        // Filter by grading status
        if ($request->filled('grading_status')) {
            $query->where('grading_status', $request->grading_status);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Search by student name
        if ($request->filled('search')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $submissions = $query->latest('submitted_at')->paginate(20);

        // Data for filters
        $exams = Exam::with('subject')->get();
        $subjects = Subject::all();
        $students = User::role('student')->get();

        // Statistics
        $stats = [
            'total' => ExamSubmission::where('status', 'submitted')->count(),
            'graded' => ExamSubmission::whereIn('grading_status', ['graded', 'auto_graded'])->count(),
            'pending' => ExamSubmission::where('grading_status', 'pending')->where('status', 'submitted')->count(),
            'average_score' => ExamSubmission::whereIn('grading_status', ['graded', 'auto_graded'])
                ->whereNotNull('score')
                ->avg('score'),
        ];

        return view('admin.exam-results.index', compact('submissions', 'exams', 'subjects', 'students', 'stats'));
    }

    /**
     * Show detailed results for a submission
     */
    public function show(ExamSubmission $submission)
    {
        $submission->load([
            'exam.subject',
            'exam.classRoom',
            'exam.questions.answers',
            'student',
            'grader'
        ]);

        return view('admin.exam-results.show', compact('submission'));
    }
}
