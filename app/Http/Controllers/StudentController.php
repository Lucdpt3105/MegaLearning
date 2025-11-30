<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\ExamSubmission;
use App\Models\ClassEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Student Dashboard
     */
    public function dashboard()
    {
        $student = Auth::user();
        
        // Get enrolled classes
        $enrolledClasses = $student->enrolledClasses()
            ->where('class_enrollments.status', 'active')
            ->with(['subject'])
            ->get();
        
        $enrolledClassIds = $enrolledClasses->pluck('id');
        
        // Get submissions statistics
        $submissions = ExamSubmission::where('student_id', $student->id)
            ->whereIn('grading_status', ['graded', 'auto_graded'])
            ->get();
        
        // Calculate stats
        $stats = [
            'total_courses' => $enrolledClasses->count(),
            'completed_exams' => $submissions->count(),
            'average_score' => $submissions->avg('score') ?? 0,
            'total_subjects' => $enrolledClasses->unique('subject_id')->count(),
        ];
        
        // Get available exams (published, in enrolled classes, not yet taken or can retake)
        $availableExams = Exam::whereIn('class_room_id', $enrolledClassIds)
            ->where('status', 'published')
            ->with(['subject', 'classRoom'])
            ->get()
            ->filter(function($exam) use ($student) {
                $submissionCount = $exam->submissions()->where('student_id', $student->id)->count();
                return $exam->allow_retake || $submissionCount == 0;
            })
            ->take(5);
        
        // Get recent submissions
        $recentSubmissions = ExamSubmission::where('student_id', $student->id)
            ->with(['exam.subject', 'exam.classRoom'])
            ->orderBy('submitted_at', 'desc')
            ->take(5)
            ->get();
        
        // Get performance by subject
        $performanceBySubject = $submissions->groupBy(function($submission) {
            return $submission->exam->subject->name ?? 'Unknown';
        })->map(function($subjectSubmissions) {
            return [
                'count' => $subjectSubmissions->count(),
                'average' => $subjectSubmissions->avg('score'),
                'highest' => $subjectSubmissions->max('score'),
            ];
        });
        
        return view('student.dashboard', compact(
            'stats',
            'enrolledClasses',
            'availableExams',
            'recentSubmissions',
            'performanceBySubject'
        ));
    }
    
    /**
     * Alias for dashboard (backward compatibility)
     */
    public function welcome()
    {
        return $this->dashboard();
    }
}
