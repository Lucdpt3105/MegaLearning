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
            ->with('answers')
            ->withPivot(['order', 'points', 'custom_type', 'custom_content', 'custom_answers'])
            ->orderBy('exam_questions.order')
            ->get();

        $studentAnswers = $submission->answers ?? [];

        // Calculate auto-gradeable score (multiple choice, true/false)
        $autoScore = 0;
        $totalAutoPoints = 0;
        $manualQuestions = [];

        // Load answers for each question
        foreach ($examQuestions as $question) {
            $question->load('answers');
        }
        
        foreach ($examQuestions as $question) {
            $questionId = $question->id;
            $points = $question->pivot->points;
            $type = $question->type;

            if (in_array($type, ['multiple_choice', 'true_false'])) {
                $totalAutoPoints += $points;
                
                if (isset($studentAnswers[$questionId])) {
                    $studentAnswerId = $studentAnswers[$questionId];
                    
                    // Find the correct answer from question's answers
                    $correctAnswer = $question->answers->where('is_correct', true)->first();
                    
                    if ($correctAnswer && (string)$studentAnswerId === (string)$correctAnswer->id) {
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
            ->get();

        $studentAnswers = $submission->answers ?? [];
        $totalScore = 0;
        $hasEssay = false;

        foreach ($examQuestions as $question) {
            $questionId = $question->id;
            $points = $question->pivot->points;
            $type = $question->pivot->custom_type ?? $question->type;

            if (in_array($type, ['multiple_choice', 'true_false'])) {
                if (isset($studentAnswers[$questionId])) {
                    // Decode custom_answers if it's a JSON string
                    $customAnswers = $question->pivot->custom_answers;
                    if (is_string($customAnswers)) {
                        $customAnswers = json_decode($customAnswers, true);
                    }
                    $correctAnswer = ($customAnswers['correct_answer'] ?? null) ?? $question->correct_answer;
                    
                    if ($studentAnswers[$questionId] == $correctAnswer) {
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

        $validated = $request->validate([
            'essay_scores' => 'nullable|array',
            'essay_scores.*' => 'nullable|numeric|min:0',
            'feedback' => 'nullable|string|max:1000',
            'final_score' => 'nullable|numeric|min:0',
        ]);

        // Validate essay scores don't exceed max points for each question
        if (!empty($validated['essay_scores'])) {
            $examQuestions = $submission->exam->questions()->withPivot('points')->get()->keyBy('id');
            
            foreach ($validated['essay_scores'] as $questionId => $score) {
                $question = $examQuestions->get($questionId);
                if ($question) {
                    $maxPoints = $question->pivot->points;
                    
                    if ($score > $maxPoints) {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', "❌ Lỗi chấm điểm!\n\nCâu hỏi #{$questionId}: Điểm bạn nhập ({$score}) vượt quá điểm tối đa ({$maxPoints}).\n\n💡 Vui lòng nhập điểm từ 0 đến {$maxPoints}.");
                    }
                }
            }
        }

        // Validate final score doesn't exceed exam's total points
        $maxExamPoints = $submission->exam->total_points;
        if (isset($validated['final_score']) && $validated['final_score'] > $maxExamPoints) {
            return redirect()->back()
                ->withInput()
                ->with('error', "❌ Lỗi điểm tổng!\n\nĐiểm tổng bạn nhập ({$validated['final_score']}) vượt quá tổng điểm đề thi ({$maxExamPoints}).\n\n💡 Vui lòng nhập điểm từ 0 đến {$maxExamPoints}.");
        }

        DB::beginTransaction();
        try {
            // Calculate total score
            $autoScore = $submission->score ?? 0; // From auto-grading
            $essayScore = array_sum($validated['essay_scores'] ?? []);
            $finalScore = $validated['final_score'] ?? ($autoScore + $essayScore);
            
            // Final check: ensure final score doesn't exceed max
            $finalScore = min($finalScore, $maxExamPoints);

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
