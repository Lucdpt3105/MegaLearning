<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ExamSubmission;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradingController extends Controller
{
    /**
     * Task 7.1: Xem danh sách bài thi chờ chấm (UC-GV-080)
     */
    public function index(Request $request)
    {
        $query = ExamSubmission::with(['exam.subject', 'student', 'exam.classRoom'])
            ->whereHas('exam', function($q) {
                $q->where('created_by', Auth::id());
            })
            ->where('status', 'submitted');

        // Filter by grading status
        if ($request->filled('grading_status')) {
            $query->where('grading_status', $request->grading_status);
        }

        // Filter by exam
        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        // Filter by class
        if ($request->filled('class_room_id')) {
            $query->whereHas('exam', function($q) use ($request) {
                $q->where('class_room_id', $request->class_room_id);
            });
        }

        $submissions = $query->latest('submitted_at')->paginate(20);

        // Get teacher's exams for filter
        $exams = Exam::where('created_by', Auth::id())
            ->with('subject')
            ->get();

        // Get statistics
        $stats = [
            'pending' => ExamSubmission::whereHas('exam', function($q) {
                    $q->where('created_by', Auth::id());
                })
                ->where('grading_status', 'pending')
                ->count(),
            'graded' => ExamSubmission::whereHas('exam', function($q) {
                    $q->where('created_by', Auth::id());
                })
                ->where('grading_status', 'graded')
                ->count(),
            'auto_graded' => ExamSubmission::whereHas('exam', function($q) {
                    $q->where('created_by', Auth::id());
                })
                ->where('grading_status', 'auto_graded')
                ->count(),
        ];

        return view('teacher.grading.index', compact('submissions', 'exams', 'stats'));
    }

    /**
     * Task 7.2 & 7.3: Xem chi tiết bài làm để chấm (UC-GV-082, UC-GV-083)
     */
    public function show(ExamSubmission $submission)
    {
        // Check ownership
        if ($submission->exam->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $submission->load(['exam.questions.answers', 'student', 'grader']);

        // Get exam questions with student answers
        $examQuestions = $submission->exam->questions()
            ->withPivot(['order', 'points', 'custom_type', 'custom_content', 'custom_answers'])
            ->orderBy('exam_questions.order')
            ->get();

        $studentAnswers = $submission->answers ?? [];

        // Calculate auto-gradeable score (multiple choice, true/false, fill_blank)
        $autoScore = 0;
        $totalAutoPoints = 0;
        $manualQuestions = [];

        foreach ($examQuestions as $question) {
            $questionId = $question->id;
            $points = $question->pivot->points;
            $type = $question->pivot->custom_type ?? $question->type;

            if (in_array($type, ['multiple_choice', 'true_false', 'fill_blank'])) {
                $totalAutoPoints += $points;
                
                if (isset($studentAnswers[$questionId])) {
                    $studentAns = is_array($studentAnswers[$questionId]) 
                        ? ($studentAnswers[$questionId]['answer'] ?? $studentAnswers[$questionId]) 
                        : $studentAnswers[$questionId];
                    
                    $isCorrect = false;
                    
                    if ($type === 'multiple_choice') {
                        // Lấy đáp án đúng từ database (is_correct = 1)
                        $correctAnswerFromDB = $question->answers->where('is_correct', 1)->first();
                        
                        if ($correctAnswerFromDB && $studentAns) {
                            if (is_numeric($studentAns)) {
                                // So sánh trực tiếp answer_id
                                $isCorrect = ((int)$studentAns === $correctAnswerFromDB->id);
                            } else {
                                // Tìm vị trí của đáp án đúng trong danh sách đã sort
                                $allAnswersSorted = $question->answers->sortBy('order')->values();
                                $correctLetter = null;
                                
                                foreach ($allAnswersSorted as $index => $ans) {
                                    if ($ans->id === $correctAnswerFromDB->id) {
                                        $correctLetter = chr(65 + $index); // A, B, C, D
                                        break;
                                    }
                                }
                                
                                if ($correctLetter) {
                                    $isCorrect = (strtoupper(trim($studentAns)) === $correctLetter);
                                }
                            }
                        }
                    } else {
                        // true_false hoặc fill_blank
                        if ($type === 'true_false') {
                            // Lấy đáp án đúng từ database (is_correct = 1)
                            $correctAnswerFromDB = $question->answers->where('is_correct', 1)->first();
                            if ($correctAnswerFromDB) {
                                // So sánh answer_id (giống multiple_choice)
                                $isCorrect = ((int)$studentAns === $correctAnswerFromDB->id);
                            }
                        } else {
                            // fill_blank
                            $customAnswers = $question->pivot->custom_answers;
                            if (is_string($customAnswers)) {
                                $customAnswers = json_decode($customAnswers, true);
                            }
                            $correctAnswer = $customAnswers['correct_answer'] ?? $question->correct_answer ?? null;
                            $isCorrect = $correctAnswer && strtolower(trim($studentAns)) === strtolower(trim($correctAnswer));
                        }
                    }
                    
                    if ($isCorrect) {
                        $autoScore += $points;
                    }
                }
            } else {
                // Essay questions need manual grading
                $manualQuestions[] = $question;
            }
        }

        return view('teacher.grading.show', compact(
            'submission',
            'examQuestions',
            'studentAnswers',
            'autoScore',
            'totalAutoPoints',
            'manualQuestions'
        ));
    }

    /**
     * Task 7.4: Kích hoạt chấm tự động (UC-GV-084)
     */
    public function autoGrade(ExamSubmission $submission)
    {
        // Check ownership
        if ($submission->exam->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($submission->grading_status === 'graded') {
            return redirect()
                ->back()
                ->with('error', 'Bài thi đã được chấm điểm!');
        }

        $examQuestions = $submission->exam->questions()
            ->withPivot(['order', 'points', 'custom_type', 'custom_content', 'custom_answers'])
            ->with('answers')
            ->get();

        $studentAnswers = $submission->answers ?? [];
        $totalScore = 0;
        $hasEssay = false;

        foreach ($examQuestions as $question) {
            $questionId = $question->id;
            $points = $question->pivot->points;
            $type = $question->pivot->custom_type ?? $question->type;

            if (in_array($type, ['multiple_choice', 'true_false', 'fill_blank'])) {
                if (isset($studentAnswers[$questionId])) {
                    $studentAns = is_array($studentAnswers[$questionId]) 
                        ? ($studentAnswers[$questionId]['answer'] ?? $studentAnswers[$questionId]) 
                        : $studentAnswers[$questionId];
                    
                    $isCorrect = false;
                    
                    if ($type === 'multiple_choice') {
                        // Lấy đáp án đúng từ database (is_correct = 1)
                        $correctAnswerFromDB = $question->answers->where('is_correct', 1)->first();
                        
                        if ($correctAnswerFromDB && $studentAns) {
                            if (is_numeric($studentAns)) {
                                // So sánh trực tiếp answer_id
                                $isCorrect = ((int)$studentAns === $correctAnswerFromDB->id);
                            } else {
                                // Tìm vị trí của đáp án đúng trong danh sách đã sort
                                $allAnswersSorted = $question->answers->sortBy('order')->values();
                                $correctLetter = null;
                                
                                foreach ($allAnswersSorted as $index => $ans) {
                                    if ($ans->id === $correctAnswerFromDB->id) {
                                        $correctLetter = chr(65 + $index); // A, B, C, D
                                        break;
                                    }
                                }
                                
                                if ($correctLetter) {
                                    $isCorrect = (strtoupper(trim($studentAns)) === $correctLetter);
                                }
                            }
                        }
                    } else {
                        // true_false hoặc fill_blank
                        if ($type === 'true_false') {
                            // Lấy đáp án đúng từ database (is_correct = 1)
                            $correctAnswerFromDB = $question->answers->where('is_correct', 1)->first();
                            if ($correctAnswerFromDB) {
                                // So sánh answer_id (giống multiple_choice)
                                $isCorrect = ((int)$studentAns === $correctAnswerFromDB->id);
                            }
                        } else {
                            // fill_blank
                            $customAnswers = $question->pivot->custom_answers;
                            if (is_string($customAnswers)) {
                                $customAnswers = json_decode($customAnswers, true);
                            }
                            $correctAnswer = $customAnswers['correct_answer'] ?? $question->correct_answer ?? null;
                            $isCorrect = $correctAnswer && strtolower(trim($studentAns)) === strtolower(trim($correctAnswer));
                        }
                    }
                    
                    if ($isCorrect) {
                        $totalScore += $points;
                    }
                }
            } else {
                $hasEssay = true;
            }
        }

        // Update submission
        $submission->update([
            'score' => $totalScore,
            'grading_status' => $hasEssay ? 'partially_graded' : 'auto_graded',
            'graded_by' => Auth::id(),
            'graded_at' => now(),
        ]);

        $message = $hasEssay 
            ? "Đã chấm tự động phần trắc nghiệm: {$totalScore} điểm. Vui lòng chấm phần tự luận."
            : "Chấm tự động hoàn tất! Điểm: {$totalScore}/{$submission->exam->total_points}";

        return redirect()
            ->route('teacher.grading.show', $submission)
            ->with('success', $message);
    }

    /**
     * Task 7.5: Nhập điểm và nhận xét (UC-GV-082)
     */
    public function grade(Request $request, ExamSubmission $submission)
    {
        // Check ownership
        if ($submission->exam->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent re-grading if already graded
        if ($submission->grading_status === 'graded') {
            return redirect()
                ->back()
                ->with('error', 'Bài thi này đã được chấm điểm. Không thể chấm lại!');
        }

        $validated = $request->validate([
            'essay_scores' => 'nullable|array',
            'essay_scores.*' => 'nullable|numeric|min:0',
            'feedback' => 'nullable|string|max:1000',
            'final_score' => 'nullable|numeric|min:0|max:' . $submission->exam->total_points,
        ]);

        DB::beginTransaction();
        try {
            // Calculate auto score from multiple_choice and true_false
            $examQuestions = $submission->exam->questions()
                ->withPivot(['custom_type', 'points'])
                ->get();
            
            $studentAnswers = $submission->answers ?? [];
            $autoScore = 0;
            
            foreach ($examQuestions as $question) {
                $questionId = $question->id;
                $type = $question->pivot->custom_type ?? $question->type;
                $points = $question->pivot->points;
                $studentAns = $studentAnswers[$questionId] ?? null;
                
                if (!$studentAns || !in_array($type, ['multiple_choice', 'true_false'])) {
                    continue;
                }
                
                $isCorrect = false;
                
                if ($type === 'multiple_choice') {
                    $correctAnswerFromDB = $question->answers->where('is_correct', 1)->first();
                    if ($correctAnswerFromDB) {
                        if (is_numeric($studentAns)) {
                            $isCorrect = ((int)$studentAns === $correctAnswerFromDB->id);
                        } else {
                            $allAnswersSorted = $question->answers->sortBy('order')->values();
                            $correctLetter = null;
                            foreach ($allAnswersSorted as $index => $ans) {
                                if ($ans->id === $correctAnswerFromDB->id) {
                                    $correctLetter = chr(65 + $index);
                                    break;
                                }
                            }
                            if ($correctLetter) {
                                $isCorrect = (strtoupper(trim($studentAns)) === $correctLetter);
                            }
                        }
                    }
                } elseif ($type === 'true_false') {
                    $correctAnswerFromDB = $question->answers->where('is_correct', 1)->first();
                    if ($correctAnswerFromDB) {
                        $isCorrect = ((int)$studentAns === $correctAnswerFromDB->id);
                    }
                }
                
                if ($isCorrect) {
                    $autoScore += $points;
                }
            }
            
            // Calculate final score
            $essayScore = array_sum($validated['essay_scores'] ?? []);
            $finalScore = $validated['final_score'] ?? ($autoScore + $essayScore);
            
            // Validate: Final score must be >= auto score
            if ($finalScore < $autoScore) {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', "Điểm tổng ({$finalScore}) không được thấp hơn điểm tự động ({$autoScore})!");
            }

            // Update submission
            $submission->update([
                'score' => $finalScore,
                'feedback' => $validated['feedback'] ?? null,
                'grading_status' => 'graded',
                'graded_by' => Auth::id(),
                'graded_at' => now(),
            ]);

            // Save essay scores in answers array
            if (!empty($validated['essay_scores'])) {
                $answers = $submission->answers ?? [];
                foreach ($validated['essay_scores'] as $questionId => $score) {
                    if (!isset($answers[$questionId])) {
                        $answers[$questionId] = [];
                    }
                    if (is_array($answers[$questionId])) {
                        $answers[$questionId]['score'] = $score;
                    } else {
                        $answers[$questionId] = [
                            'answer' => $answers[$questionId],
                            'score' => $score,
                        ];
                    }
                }
                $submission->update(['answers' => $answers]);
            }

            DB::commit();

            return redirect()
                ->route('teacher.grading.index')
                ->with('success', "Đã chấm điểm bài thi của {$submission->student->name}! Điểm: {$finalScore}/{$submission->exam->total_points}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Lỗi khi lưu điểm: ' . $e->getMessage());
        }
    }

    /**
     * Bulk auto-grade multiple submissions
     */
    public function bulkAutoGrade(Request $request)
    {
        $validated = $request->validate([
            'submission_ids' => 'required|array',
            'submission_ids.*' => 'exists:exam_submissions,id',
        ]);

        $graded = 0;
        $errors = [];

        foreach ($validated['submission_ids'] as $submissionId) {
            try {
                $submission = ExamSubmission::findOrFail($submissionId);
                
                // Check ownership
                if ($submission->exam->created_by !== Auth::id()) {
                    continue;
                }

                $this->autoGrade($submission);
                $graded++;
            } catch (\Exception $e) {
                $errors[] = "Submission #{$submissionId}: " . $e->getMessage();
            }
        }

        if ($graded > 0) {
            return redirect()
                ->back()
                ->with('success', "Đã chấm tự động {$graded} bài thi!");
        }

        return redirect()
            ->back()
            ->with('error', 'Không thể chấm tự động: ' . implode(', ', $errors));
    }
}
