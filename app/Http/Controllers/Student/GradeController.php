<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamSubmission;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    /**
     * Display all grades for the student
     */
    public function index()
    {
        $student = Auth::user();
        
        // Get all graded submissions with exam and class info
        $submissions = ExamSubmission::where('student_id', $student->id)
            ->whereIn('grading_status', ['graded', 'auto_graded'])
            ->with(['exam.subject', 'exam.classRoom'])
            ->orderBy('submitted_at', 'desc')
            ->get();
        
        // Group by class
        $gradesByClass = $submissions->groupBy(function($submission) {
            return $submission->exam->classRoom->id;
        })->map(function($classSubmissions) {
            $classRoom = $classSubmissions->first()->exam->classRoom;
            
            return [
                'class' => $classRoom,
                'submissions' => $classSubmissions,
                'average_score' => $classSubmissions->avg('score'),
                'total_exams' => $classSubmissions->count(),
                'passed_exams' => $classSubmissions->filter(function($sub) {
                    return $sub->score >= $sub->exam->passing_score;
                })->count()
            ];
        });
        
        // Overall statistics
        $stats = [
            'total_exams' => $submissions->count(),
            'average_score' => $submissions->avg('score'),
            'highest_score' => $submissions->max('score'),
            'lowest_score' => $submissions->min('score'),
            'passed_count' => $submissions->filter(function($sub) {
                return $sub->score >= $sub->exam->passing_score;
            })->count()
        ];
        
        return view('student.grades.index', compact('gradesByClass', 'stats', 'submissions'));
    }

    /**
     * Show details of a specific graded submission
     */
    public function show($submissionId)
    {
        $student = Auth::user();
        
        $submission = ExamSubmission::with([
            'exam.subject',
            'exam.classRoom',
            'exam.questions.answers',
            'grader'
        ])
        ->where('id', $submissionId)
        ->where('student_id', $student->id)
        ->firstOrFail();
        
        $exam = $submission->exam;
        
        // Check if allowed to review
        if (!$exam->allow_review && $submission->grading_status !== 'graded') {
            return redirect()->route('student.grades.index')
                ->with('error', 'Không được phép xem chi tiết bài làm!');
        }
        
        // Map student answers with questions
        $questions = $exam->questions->map(function($question) use ($submission) {
            $studentAnswer = $submission->answers[$question->id] ?? null;
            
            // Handle different answer types
            if ($question->type === 'essay' || $question->type === 'fill_blank' || $question->type === 'true_false') {
                // Check if answer is JSON (from auto-generated exams)
                if (is_string($studentAnswer) && is_array(json_decode($studentAnswer, true))) {
                    $decoded = json_decode($studentAnswer, true);
                    $question->student_answer = $decoded['answer'] ?? $studentAnswer;
                } else {
                    $question->student_answer = is_array($studentAnswer) ? ($studentAnswer['answer'] ?? json_encode($studentAnswer)) : ($studentAnswer ?? '');
                }
            } else {
                $question->student_answer = $studentAnswer;
            }
            
            if ($question->type === 'multiple_choice') {
                $correctAnswer = $question->answers->where('is_correct', true)->first();
                $question->correct_answer_id = $correctAnswer ? $correctAnswer->id : null;
                $question->is_correct = $studentAnswer == $question->correct_answer_id;
                
                // Get student's selected answer
                $selectedAnswer = $question->answers->where('id', $studentAnswer)->first();
                $question->selected_answer = $selectedAnswer;
            }
            
            return $question;
        });
        
        // Calculate statistics
        $correctCount = 0;
        $totalQuestions = 0;
        
        foreach ($questions as $question) {
            if ($question->type === 'multiple_choice') {
                $totalQuestions++;
                if (isset($question->is_correct) && $question->is_correct) {
                    $correctCount++;
                }
            }
        }
        
        $stats = [
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'accuracy' => $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0,
            'time_spent_minutes' => $submission->time_spent ? round($submission->time_spent / 60, 1) : 0,
            'passed' => $submission->score >= $exam->passing_score
        ];
        
        return view('student.grades.show', compact('submission', 'exam', 'questions', 'stats'));
    }
}
