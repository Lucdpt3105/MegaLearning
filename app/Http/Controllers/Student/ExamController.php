<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExamController extends Controller
{
    /**
     * Display a list of available exams for the student
     */
    public function index()
    {
        $student = Auth::user();
        
        // Get all enrolled classes
        $enrolledClassIds = $student->enrolledClasses()
            ->where('class_enrollments.status', 'active')
            ->pluck('class_rooms.id');
        
        // Get exams for enrolled classes
        $exams = Exam::whereIn('class_room_id', $enrolledClassIds)
            ->where('status', 'published')
            ->with(['subject', 'classRoom', 'submissions' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($exam) {
                $now = Carbon::now();
                // Handle NULL start_time/end_time
                $exam->is_upcoming = $exam->start_time ? $exam->start_time > $now : false;
                $exam->is_ongoing = $exam->start_time && $exam->end_time 
                    ? ($exam->start_time <= $now && $exam->end_time >= $now) 
                    : true; // If no time set, assume always available
                $exam->is_finished = $exam->end_time ? $exam->end_time < $now : false;
                $exam->submission_count = $exam->submissions->count();
                $exam->can_take = $exam->is_ongoing && 
                    ($exam->allow_retake || $exam->submission_count == 0) &&
                    ($exam->max_attempts == 0 || $exam->submission_count < $exam->max_attempts);
                return $exam;
            });
        
        return view('student.exams.index', compact('exams'));
    }

    /**
     * Show exam details and instructions
     */
    public function show($id)
    {
        $student = Auth::user();
        $exam = Exam::with(['subject', 'classRoom', 'submissions' => function($query) use ($student) {
            $query->where('student_id', $student->id)->orderBy('attempt_number', 'desc');
        }])->findOrFail($id);
        
        // Check if student is enrolled in the class
        $isEnrolled = $student->enrolledClasses()
            ->where('class_rooms.id', $exam->class_room_id)
            ->where('class_enrollments.status', 'active')
            ->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('student.exams.index')
                ->with('error', 'Bạn không có quyền truy cập bài kiểm tra này!');
        }
        
        $now = Carbon::now();
        // Handle NULL start_time/end_time
        $exam->is_upcoming = $exam->start_time ? $exam->start_time > $now : false;
        $exam->is_ongoing = $exam->start_time && $exam->end_time 
            ? ($exam->start_time <= $now && $exam->end_time >= $now) 
            : true; // If no time set, assume always available
        $exam->is_finished = $exam->end_time ? $exam->end_time < $now : false;
        $exam->submission_count = $exam->submissions->count();
        $exam->can_take = $exam->is_ongoing && 
            ($exam->allow_retake || $exam->submission_count == 0) &&
            ($exam->max_attempts == 0 || $exam->submission_count < $exam->max_attempts);
        
        return view('student.exams.show', compact('exam'));
    }

    /**
     * Start taking the exam
     */
    public function take($id, Request $request)
    {
        $student = Auth::user();
        $exam = Exam::with(['questions' => function($query) {
            $query->with('answers');
        }, 'classRoom'])->findOrFail($id);
        
        // Validate access
        $isEnrolled = $student->enrolledClasses()
            ->where('class_rooms.id', $exam->class_room_id)
            ->where('class_enrollments.status', 'active')
            ->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('student.exams.index')
                ->with('error', 'Bạn không có quyền truy cập bài kiểm tra này!');
        }
        
        $now = Carbon::now();
        // Only check time if start_time and end_time are set
        if ($exam->start_time && $exam->end_time) {
            if ($exam->start_time > $now || $exam->end_time < $now) {
                return redirect()->route('student.exams.show', $id)
                    ->with('error', 'Bài kiểm tra không trong thời gian làm bài!');
            }
        }
        
        // Check access code if required
        if ($exam->require_access_code) {
            if ($request->method() === 'GET') {
                return view('student.exams.access-code', compact('exam'));
            }
            
            $request->validate([
                'access_code' => 'required|string'
            ]);
            
            if ($request->access_code !== $exam->access_code) {
                return back()->with('error', 'Mã truy cập không đúng!');
            }
        }
        
        // Check submission count
        $submissionCount = ExamSubmission::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->count();
        
        if (!$exam->allow_retake && $submissionCount > 0) {
            return redirect()->route('student.exams.show', $id)
                ->with('error', 'Bạn đã làm bài kiểm tra này rồi!');
        }
        
        if ($exam->max_attempts > 0 && $submissionCount >= $exam->max_attempts) {
            return redirect()->route('student.exams.show', $id)
                ->with('error', 'Bạn đã hết số lần làm bài!');
        }
        
        // Check for in-progress submission
        $inProgressSubmission = ExamSubmission::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->first();
        
        if (!$inProgressSubmission) {
            // Create new submission
            $attemptNumber = $submissionCount + 1;
            $inProgressSubmission = ExamSubmission::create([
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'attempt_number' => $attemptNumber,
                'started_at' => now(),
                'status' => 'in_progress',
                'answers' => []
            ]);
        }
        
        // Shuffle questions if enabled
        $questions = $exam->questions;
        if ($exam->shuffle_questions) {
            $questions = $questions->shuffle();
        }
        
        // Shuffle answers if enabled
        if ($exam->shuffle_answers) {
            $questions = $questions->map(function($question) {
                if ($question->answers && $question->answers->count() > 0) {
                    $question->shuffled_answers = $question->answers->shuffle();
                } else {
                    $question->shuffled_answers = $question->answers;
                }
                return $question;
            });
        } else {
            $questions = $questions->map(function($question) {
                $question->shuffled_answers = $question->answers;
                return $question;
            });
        }
        
        $submission = $inProgressSubmission;
        $timeRemaining = $exam->duration * 60; // Convert to seconds
        
        if ($submission->started_at) {
            $elapsed = Carbon::parse($submission->started_at)->diffInSeconds(now());
            $timeRemaining = max(0, ($exam->duration * 60) - $elapsed);
        }
        
        // Round to integer seconds
        $timeRemaining = (int) $timeRemaining;
        
        return view('student.exams.take', compact('exam', 'questions', 'submission', 'timeRemaining'));
    }

    /**
     * Submit the exam
     */
    public function submit($id, Request $request)
    {
        $student = Auth::user();
        $exam = Exam::with('questions.answers')->findOrFail($id);
        
        // Find in-progress submission
        $submission = ExamSubmission::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('status', 'in_progress')
            ->first();
        
        if (!$submission) {
            return redirect()->route('student.exams.index')
                ->with('error', 'Không tìm thấy bài làm của bạn!');
        }
        
        $answers = $request->input('answers', []);
        $timeSpent = Carbon::parse($submission->started_at)->diffInSeconds(now());
        
        // Auto-grade if possible
        $score = null;
        $gradingStatus = 'pending';
        
        if ($exam->type === 'multiple_choice' || $exam->type === 'mixed') {
            $score = $this->calculateScore($exam, $answers);
            $gradingStatus = 'auto_graded';
        }
        
        // Update submission
        $submission->update([
            'answers' => $answers,
            'submitted_at' => now(),
            'time_spent' => $timeSpent,
            'status' => 'submitted',
            'score' => $score,
            'grading_status' => $gradingStatus
        ]);
        
        if ($exam->show_results_immediately && $gradingStatus === 'auto_graded') {
            return redirect()->route('student.exams.result', $submission->id)
                ->with('success', 'Đã nộp bài thành công!');
        }
        
        return redirect()->route('student.exams.index')
            ->with('success', 'Đã nộp bài thành công! Kết quả sẽ được công bố sau.');
    }

    /**
     * Show exam result
     */
    public function result($submissionId)
    {
        $student = Auth::user();
        $submission = ExamSubmission::with(['exam.questions.answers'])
            ->where('id', $submissionId)
            ->where('student_id', $student->id)
            ->firstOrFail();
        
        $exam = $submission->exam;
        
        if (!$exam->show_results_immediately && $submission->grading_status !== 'graded') {
            return redirect()->route('student.exams.index')
                ->with('error', 'Kết quả chưa được công bố!');
        }
        
        // Map answers
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
            }
            
            return $question;
        });
        
        return view('student.exams.result', compact('submission', 'exam', 'questions'));
    }

    /**
     * Calculate score for auto-gradable questions
     */
    private function calculateScore($exam, $answers)
    {
        $totalPoints = 0;
        $earnedPoints = 0;
        
        foreach ($exam->questions as $question) {
            // Get points from pivot table (exam_questions)
            $points = $question->pivot->points ?? 1;
            
            // Only count multiple choice questions towards total for auto-grading
            if ($question->type === 'multiple_choice') {
                $totalPoints += $points;
                
                // Get student's answer for this question
                $studentAnswer = $answers[$question->id] ?? null;
                
                // Find the correct answer
                $correctAnswer = $question->answers->where('is_correct', true)->first();
                
                // Check if student answered correctly
                if ($correctAnswer && $studentAnswer && $studentAnswer == $correctAnswer->id) {
                    $earnedPoints += $points;
                }
            }
        }
        
        // If no multiple choice questions, return 0
        if ($totalPoints == 0) {
            return 0;
        }
        
        // Calculate final score proportional to total exam points
        // Example: If MC questions worth 6 points out of 10 total, and student got 4/6
        // Score = (4/6) * 6 = 4.0
        return round($earnedPoints, 2);
    }
}
