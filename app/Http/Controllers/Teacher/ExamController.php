<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Question;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource (UC-GV-030)
     */
    public function index(Request $request)
    {
        $query = Exam::with(['subject', 'creator', 'classRoom', 'questions'])
            ->where('created_by', Auth::id());

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $exams = $query->latest()->paginate(15);
        $subjects = Subject::where('teacher_id', Auth::id())->get();

        return view('teacher.exams.index', compact('exams', 'subjects'));
    }

    /**
     * Show the form for creating a new resource (UC-GV-032)
     */
    public function create()
    {
        $subjects = Subject::where('teacher_id', Auth::id())->get();
        $classRooms = ClassRoom::whereHas('enrollments', function($q) {
            $q->whereHas('classRoom.subject', function($q2) {
                $q2->where('teacher_id', Auth::id());
            });
        })->distinct()->get();

        return view('teacher.exams.create', compact('subjects', 'classRooms'));
    }

    /**
     * Store a newly created resource in storage (UC-GV-032)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'class_room_id' => 'required|exists:class_rooms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:quiz,midterm,final,practice',
            'duration' => 'required|integer|min:1',
            'total_points' => 'required|numeric|min:0',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'passing_score' => 'nullable|numeric|min:0',
            'shuffle_questions' => 'boolean',
            'shuffle_answers' => 'boolean',
            'show_results_immediately' => 'boolean',
            'allow_review' => 'boolean',
            // Security fields
            'require_access_code' => 'boolean',
            'access_code' => 'nullable|string|max:20',
            'restrict_to_class' => 'boolean',
            'detect_cheating' => 'boolean',
            'detect_tab_switch' => 'boolean',
            'detect_device_change' => 'boolean',
            'lock_on_exit' => 'boolean',
            'max_exit_time' => 'nullable|integer|min:5|max:300',
            'require_camera' => 'boolean',
            'require_screen_recording' => 'boolean',
            // Auto-generate fields
            'auto_generate' => 'boolean',
            'auto_gen_level_1' => 'nullable|integer|min:0',
            'auto_gen_level_2' => 'nullable|integer|min:0',
            'auto_gen_level_3' => 'nullable|integer|min:0',
            'auto_gen_level_4' => 'nullable|integer|min:0',
            'auto_gen_multiple_choice' => 'nullable|integer|min:0',
            'auto_gen_essay' => 'nullable|integer|min:0',
            'auto_gen_topics' => 'nullable|array',
        ]);

        // Validate auto-generate criteria
        if ($request->boolean('auto_generate')) {
            $totalByLevel = ($request->input('auto_gen_level_1', 0) ?? 0) + 
                           ($request->input('auto_gen_level_2', 0) ?? 0) + 
                           ($request->input('auto_gen_level_3', 0) ?? 0) + 
                           ($request->input('auto_gen_level_4', 0) ?? 0);
            
            $totalByType = ($request->input('auto_gen_multiple_choice', 0) ?? 0) + 
                          ($request->input('auto_gen_essay', 0) ?? 0);

            // NEW: Check if totals are EXACTLY equal
            if ($totalByType !== $totalByLevel) {
                return redirect()->back()
                    ->withInput()
                    ->with('error_popup', "⚠️ Lỗi số lượng câu hỏi!\n\nTổng số câu theo loại: {$totalByType} câu\n(Trắc nghiệm: {$request->input('auto_gen_multiple_choice', 0)} + Tự luận: {$request->input('auto_gen_essay', 0)})\n\nTổng số câu theo mức độ: {$totalByLevel} câu\n(Mức 1: {$request->input('auto_gen_level_1', 0)} + Mức 2: {$request->input('auto_gen_level_2', 0)} + Mức 3: {$request->input('auto_gen_level_3', 0)} + Mức 4: {$request->input('auto_gen_level_4', 0)})\n\n❌ Tổng số câu theo loại PHẢI BẰNG tổng số câu theo mức độ!\n\nVui lòng điều chỉnh lại cho khớp.");
            }

            // Check if total is valid
            if ($totalByLevel == 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error_popup', 'Vui lòng chọn ít nhất một câu hỏi theo mức độ (Mức 1, 2, 3, hoặc 4).');
            }

            if ($totalByType == 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error_popup', 'Vui lòng chọn ít nhất một câu hỏi theo loại (Trắc nghiệm hoặc Tự luận).');
            }
        }

        // Check subject ownership
        $subject = Subject::findOrFail($validated['subject_id']);
        if ($subject->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $exam = Exam::create([
            'subject_id' => $validated['subject_id'],
            'class_room_id' => $validated['class_room_id'],
            'created_by' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'duration' => $validated['duration'],
            'total_points' => $validated['total_points'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'passing_score' => $validated['passing_score'] ?? null,
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'shuffle_answers' => $request->boolean('shuffle_answers'),
            'show_results_immediately' => $request->boolean('show_results_immediately', true),
            'allow_review' => $request->boolean('allow_review', true),
            'status' => 'draft',
            // Security settings
            'require_access_code' => $request->boolean('require_access_code'),
            'access_code' => $request->boolean('require_access_code') ? $validated['access_code'] : null,
            'restrict_to_class' => $request->boolean('restrict_to_class', true),
            'detect_cheating' => $request->boolean('detect_cheating'),
            'detect_tab_switch' => $request->boolean('detect_tab_switch'),
            'detect_device_change' => $request->boolean('detect_device_change'),
            'lock_on_exit' => $request->boolean('lock_on_exit'),
            'max_exit_time' => $request->boolean('lock_on_exit') ? $validated['max_exit_time'] : null,
            'require_camera' => $request->boolean('require_camera'),
            'require_screen_recording' => $request->boolean('require_screen_recording'),
        ]);

        // Auto-generate questions if requested
        if ($request->boolean('auto_generate')) {
            try {
                $questionCount = $this->autoGenerateQuestions($exam, $request);
                
                // Check if no questions were added
                if ($questionCount === 0) {
                    $exam->delete();
                    return redirect()->back()
                        ->withInput()
                        ->with('error_popup', 'Không thể tạo đề thi! Không có câu hỏi nào được chọn.\n\nVui lòng kiểm tra lại tiêu chí tự động tạo câu hỏi.');
                }
            } catch (\Exception $e) {
                // Delete the exam if auto-generation fails
                $exam->delete();
                return redirect()->back()
                    ->withInput()
                    ->with('error_popup', $e->getMessage());
            }
        }

        return redirect()->route('teacher.exams.show', $exam)
            ->with('success', $request->boolean('auto_generate') 
                ? 'Đề thi đã được tạo và câu hỏi đã được tự động thêm! Hãy kiểm tra và điều chỉnh nếu cần.' 
                : 'Đề thi đã được tạo thành công! Bây giờ hãy thêm câu hỏi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
        $exam->load([
            'subject',
            'classRoom',
            'creator',
            'questions' => function ($query) {
                $query->orderBy('exam_questions.order');
            },
            'questions.topic',
            'questions.answers'
        ]);

        // Get available questions from question bank for this subject
        $availableQuestions = Question::where('subject_id', $exam->subject_id)
            ->where('in_question_bank', true)
            ->whereNotIn('id', $exam->questions->pluck('id'))
            ->with(['topic', 'answers'])
            ->orderBy('difficulty')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.exams.show', compact('exam', 'availableQuestions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exam $exam)
    {
        if ($exam->status === 'published' && $exam->submissions()->exists()) {
            return redirect()
                ->route('teacher.exams.show', $exam)
                ->with('error', 'Không thể chỉnh sửa đề thi đã có học sinh làm bài.');
        }

        $subjects = Subject::all();
        $classRooms = ClassRoom::where('status', 'active')->get();

        return view('teacher.exams.edit', compact('exam', 'subjects', 'classRooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        if ($exam->status === 'published' && $exam->submissions()->exists()) {
            return redirect()
                ->route('teacher.exams.show', $exam)
                ->with('error', 'Không thể chỉnh sửa đề thi đã có học sinh làm bài.');
        }

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'class_room_id' => 'nullable|exists:class_rooms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:quiz,midterm,final,practice',
            'duration' => 'required|integer|min:1',
            'total_points' => 'required|numeric|min:0',
            'passing_score' => 'required|numeric|min:0|lte:total_points',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'shuffle_questions' => 'boolean',
            'shuffle_answers' => 'boolean',
            'show_results_immediately' => 'boolean',
            'allow_review' => 'boolean',
        ]);

        $exam->update($validated);

        return redirect()
            ->route('teacher.exams.show', $exam)
            ->with('success', 'Đã cập nhật đề thi thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        if ($exam->submissions()->exists()) {
            return redirect()
                ->route('teacher.exams.index')
                ->with('error', 'Không thể xóa đề thi đã có học sinh làm bài.');
        }

        $exam->delete();

        return redirect()
            ->route('teacher.exams.index')
            ->with('success', 'Đã xóa đề thi thành công!');
    }

    /**
     * Add questions from question bank to exam.
     */
    public function addQuestions(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
            'points' => 'required|array',
            'points.*' => 'numeric|min:0',
        ]);

        $maxOrder = $exam->questions()->max('exam_questions.order') ?? 0;

        foreach ($validated['question_ids'] as $index => $questionId) {
            $exam->questions()->attach($questionId, [
                'order' => ++$maxOrder,
                'points' => $validated['points'][$index],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()
            ->route('teacher.exams.show', $exam)
            ->with('success', 'Đã thêm ' . count($validated['question_ids']) . ' câu hỏi vào đề thi!');
    }

    /**
     * Create custom question directly in exam.
     */
    public function createQuestion(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'type' => 'required|in:multiple_choice,essay',
            'content' => 'required|string',
            'answers' => 'required_if:type,multiple_choice|array',
            'explanation' => 'nullable|string',
            'points' => 'required|numeric|min:0',
        ]);

        $maxOrder = $exam->questions()->max('exam_questions.order') ?? 0;

        // Create a placeholder question in questions table
        $question = Question::create([
            'content' => $validated['content'],
            'type' => $validated['type'],
            'subject_id' => $exam->subject_id,
            'created_by' => auth()->id(),
            'difficulty' => 'medium',
            'bloom_level' => 'remember',
            'in_question_bank' => false, // Not in question bank, exam-specific
            'explanation' => $validated['explanation'],
        ]);

        // Create answers if multiple choice
        if ($validated['type'] === 'multiple_choice' && isset($validated['answers'])) {
            foreach ($validated['answers'] as $index => $answerData) {
                $question->answers()->create([
                    'content' => $answerData['text'] ?? $answerData['content'] ?? '',
                    'is_correct' => isset($answerData['is_correct']) ? (bool)$answerData['is_correct'] : ($index == ($validated['correct_answer'] ?? 0)),
                    'order' => $index + 1,
                ]);
            }
        }

        // Attach to exam with custom order and points
        $exam->questions()->attach($question->id, [
            'order' => ++$maxOrder,
            'points' => $validated['points'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('teacher.exams.show', $exam)
            ->with('success', 'Đã thêm câu hỏi tùy chỉnh vào đề thi!');
    }

    /**
     * Remove question from exam.
     */
    public function removeQuestion(Exam $exam, $examQuestionId)
    {
        $exam->questions()->detach($examQuestionId);

        // Reorder remaining questions
        $questions = $exam->questions()->orderBy('exam_questions.order')->get();
        foreach ($questions as $index => $question) {
            $exam->questions()->updateExistingPivot($question->id, ['order' => $index + 1]);
        }

        return redirect()
            ->route('teacher.exams.show', $exam)
            ->with('success', 'Đã xóa câu hỏi khỏi đề thi!');
    }

    /**
     * Reorder questions in exam.
     */
    public function reorderQuestions(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
        ]);

        foreach ($validated['question_ids'] as $order => $questionId) {
            $exam->questions()->updateExistingPivot($questionId, ['order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Publish exam to make it available to students.
     */
    public function publish(Exam $exam)
    {
        if ($exam->questions()->count() === 0) {
            return redirect()
                ->route('teacher.exams.show', $exam)
                ->with('error', 'Không thể xuất bản đề thi chưa có câu hỏi!');
        }

        // Check if total points match
        $currentTotal = $exam->questions()->sum('exam_questions.points');
        if (abs($currentTotal - $exam->total_points) > 0.01) {
            return redirect()
                ->route('teacher.exams.show', $exam)
                ->with('error', "Tổng điểm các câu hỏi ({$currentTotal}) phải bằng tổng điểm đề thi ({$exam->total_points})!");
        }

        $exam->update(['status' => 'published']);

        return redirect()
            ->route('teacher.exams.show', $exam)
            ->with('success', 'Đã xuất bản đề thi thành công!');
    }

    /**
     * Send notification to students about exam.
     */
    public function sendNotification(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        // Get enrolled students
        $students = $exam->classRoom 
            ? $exam->classRoom->students 
            : User::where('role', 'student')->get();

        // Send notification to each student
        foreach ($students as $student) {
            // TODO: Implement notification system
            // Notification::send($student, new ExamNotification($exam, $validated['message']));
        }

        return redirect()
            ->route('teacher.exams.show', $exam)
            ->with('success', 'Đã gửi thông báo đến ' . $students->count() . ' học sinh!');
    }

    /**
     * Import questions from Excel file.
     */
    public function importFromExcel(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // TODO: Implement Excel import for exam questions
        // Similar to QuestionController's import but add to specific exam

        return redirect()
            ->back()
            ->with('success', 'Đang phát triển tính năng import Excel cho đề thi!');
    }

    /**
     * Update points for a specific question in exam
     */
    public function updateQuestionPoints(Request $request, Exam $exam, $questionId)
    {
        $validated = $request->validate([
            'points' => 'required|numeric|min:0',
        ]);

        $exam->questions()->updateExistingPivot($questionId, [
            'points' => $validated['points'],
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật điểm thành công!',
        ]);
    }

    /**
     * Distribute points evenly across all questions
     */
    public function distributePoints(Exam $exam)
    {
        $questions = $exam->questions()->orderBy('exam_questions.order')->get();
        
        if ($questions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có câu hỏi nào để phân điểm!',
            ]);
        }

        $totalQuestions = $questions->count();
        $totalPoints = $exam->total_points;
        
        // Calculate base points per question
        $basePoints = floor(($totalPoints * 10) / $totalQuestions) / 10;
        $remainder = round($totalPoints - ($basePoints * $totalQuestions), 1);
        
        // Distribute points
        $pointsData = [];
        foreach ($questions as $index => $question) {
            $points = $basePoints;
            
            // Add 0.1 to first questions to account for remainder
            if ($remainder > 0 && $index < ($remainder * 10)) {
                $points += 0.1;
            }
            
            $points = round($points, 1);
            
            $exam->questions()->updateExistingPivot($question->id, [
                'points' => $points,
                'updated_at' => now(),
            ]);
            
            $pointsData[] = [
                'question_id' => $question->id,
                'points' => $points,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã phân đều điểm thành công!',
            'points' => $pointsData,
        ]);
    }

    /**
     * Auto-generate questions based on criteria
     */
    protected function autoGenerateQuestions(Exam $exam, Request $request)
    {
        $criteria = [
            'level_1' => $request->input('auto_gen_level_1', 0),
            'level_2' => $request->input('auto_gen_level_2', 0),
            'level_3' => $request->input('auto_gen_level_3', 0),
            'level_4' => $request->input('auto_gen_level_4', 0),
            'multiple_choice' => $request->input('auto_gen_multiple_choice', 0),
            'essay' => $request->input('auto_gen_essay', 0),
            'topics' => $request->input('auto_gen_topics', []),
        ];

        // Save criteria for reference
        $exam->update([
            'is_auto_generated' => true,
            'auto_gen_criteria' => $criteria,
        ]);

        $selectedQuestions = [];
        $order = 1;

        // NEW APPROACH: Select by TYPE first, then distribute by LEVEL
        
        $insufficientQuestions = [];
        
        // 1. Select Multiple Choice questions (multiple_choice + true_false) distributed by level
        if ($criteria['multiple_choice'] > 0) {
            $mcByLevel = $this->distributeQuestionsByLevel(
                $criteria['multiple_choice'],
                $criteria['level_1'],
                $criteria['level_2'],
                $criteria['level_3'],
                $criteria['level_4']
            );
            
            \Log::info('MC Distribution:', ['distribution' => $mcByLevel, 'total_needed' => $criteria['multiple_choice']]);
            
            $mcCollected = 0;
            $mcDetails = [];
            
            foreach ($mcByLevel as $bloomLevel => $count) {
                if ($count > 0) {
                    // NEW: Get questions from BOTH multiple_choice AND true_false
                    $questions = $this->getQuestionsForCriteria(
                        $exam->subject_id,
                        $bloomLevel,
                        ['multiple_choice', 'true_false'], // Array of types
                        $criteria['topics'],
                        $count,
                        array_keys($selectedQuestions)
                    );
                    
                    $found = $questions->count();
                    $mcCollected += $found;
                    $mcDetails[] = "Level $bloomLevel: cần $count, có $found";
                    
                    \Log::info("MC Level $bloomLevel:", ['requested' => $count, 'found' => $found]);
                    
                    if ($found < $count) {
                        $insufficientQuestions[] = "Trắc nghiệm Level $bloomLevel: cần $count câu nhưng chỉ tìm thấy $found câu";
                    }
                    
                    foreach ($questions as $question) {
                        $selectedQuestions[$question->id] = [
                            'order' => $order++,
                            'points' => 1, // Will be recalculated
                            'type' => 'multiple_choice'
                        ];
                    }
                }
            }
            
            // If insufficient MC questions, throw error
            if ($mcCollected < $criteria['multiple_choice']) {
                $details = implode("; ", $mcDetails);
                throw new \Exception("Không đủ câu trắc nghiệm!\n\nCần: {$criteria['multiple_choice']} câu\nCó: $mcCollected câu\n\nChi tiết: $details");
            }
        }

        // 2. Select Essay questions (essay + fill_blank) distributed by level
        if ($criteria['essay'] > 0) {
            $essayByLevel = $this->distributeQuestionsByLevel(
                $criteria['essay'],
                $criteria['level_1'],
                $criteria['level_2'],
                $criteria['level_3'],
                $criteria['level_4']
            );
            
            $essayCollected = 0;
            $essayDetails = [];
            
            foreach ($essayByLevel as $bloomLevel => $count) {
                if ($count > 0) {
                    // NEW: Get questions from BOTH essay AND fill_blank
                    $questions = $this->getQuestionsForCriteria(
                        $exam->subject_id,
                        $bloomLevel,
                        ['essay', 'fill_blank'], // Array of types
                        $criteria['topics'],
                        $count,
                        array_keys($selectedQuestions)
                    );
                    
                    $found = $questions->count();
                    $essayCollected += $found;
                    $essayDetails[] = "Level $bloomLevel: cần $count, có $found";
                    
                    \Log::info("Essay Level $bloomLevel:", ['requested' => $count, 'found' => $found]);
                    
                    if ($found < $count) {
                        $insufficientQuestions[] = "Tự luận Level $bloomLevel: cần $count câu nhưng chỉ tìm thấy $found câu";
                    }
                    
                    foreach ($questions as $question) {
                        $selectedQuestions[$question->id] = [
                            'order' => $order++,
                            'points' => 1, // Will be recalculated
                            'type' => 'essay'
                        ];
                    }
                }
            }
            
            // If insufficient Essay questions, throw error
            if ($essayCollected < $criteria['essay']) {
                $details = implode("; ", $essayDetails);
                throw new \Exception("Không đủ câu tự luận!\n\nCần: {$criteria['essay']} câu\nCó: $essayCollected câu\n\nChi tiết: $details");
            }
        }

        // Calculate points to distribute evenly to reach 10 total
        if (!empty($selectedQuestions)) {
            $totalQuestions = count($selectedQuestions);
            $totalPoints = 10; // Target total points
            
            // Calculate base points per question
            $basePoints = floor(($totalPoints * 10) / $totalQuestions) / 10; // Round to 1 decimal
            $remainder = round($totalPoints - ($basePoints * $totalQuestions), 1);
            
            // Distribute points
            $questionIndex = 0;
            $finalQuestions = [];
            
            foreach ($selectedQuestions as $qId => $qData) {
                $points = $basePoints;
                
                // Add remainder to first few questions
                if ($questionIndex < round($remainder * 10)) {
                    $points += 0.1;
                }
                
                $finalQuestions[$qId] = [
                    'order' => $qData['order'],
                    'points' => round($points, 1)
                ];
                
                $questionIndex++;
            }
            
            // Verify total is exactly 10
            $actualTotal = array_sum(array_column($finalQuestions, 'points'));
            if (abs($actualTotal - 10) > 0.01) {
                // Adjust last question to make it exactly 10
                $lastKey = array_key_last($finalQuestions);
                $finalQuestions[$lastKey]['points'] = round(
                    $finalQuestions[$lastKey]['points'] + (10 - $actualTotal), 
                    1
                );
            }
            
            // Attach questions to exam
            $exam->questions()->attach($finalQuestions);
            
            \Log::info('Total questions attached:', [
                'exam_id' => $exam->id,
                'count' => count($finalQuestions),
                'total_points' => array_sum(array_column($finalQuestions, 'points'))
            ]);
        }

        return count($selectedQuestions);
    }

    /**
     * Distribute total questions across difficulty levels proportionally
     */
    protected function distributeQuestionsByLevel($totalNeeded, $level1, $level2, $level3, $level4)
    {
        $totalByLevel = $level1 + $level2 + $level3 + $level4;
        
        if ($totalByLevel == 0) {
            return [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        }
        
        // If total needed matches total by level, use exact distribution
        if ($totalNeeded == $totalByLevel) {
            return [
                1 => $level1,
                2 => $level2,
                3 => $level3,
                4 => $level4,
            ];
        }
        
        // Calculate proportional distribution using floor first
        $distribution = [
            1 => $level1 > 0 ? floor(($level1 / $totalByLevel) * $totalNeeded) : 0,
            2 => $level2 > 0 ? floor(($level2 / $totalByLevel) * $totalNeeded) : 0,
            3 => $level3 > 0 ? floor(($level3 / $totalByLevel) * $totalNeeded) : 0,
            4 => $level4 > 0 ? floor(($level4 / $totalByLevel) * $totalNeeded) : 0,
        ];
        
        // Distribute remainder to levels that had questions, prioritizing higher levels
        $currentTotal = array_sum($distribution);
        $remainder = $totalNeeded - $currentTotal;
        
        if ($remainder > 0) {
            // Sort by original count (descending) to distribute remainder fairly
            $levelCounts = [1 => $level1, 2 => $level2, 3 => $level3, 4 => $level4];
            arsort($levelCounts);
            
            foreach ($levelCounts as $level => $count) {
                if ($remainder > 0 && $count > 0) {
                    $distribution[$level]++;
                    $remainder--;
                }
            }
        }
        
        // Sort back by level
        ksort($distribution);
        
        return $distribution;
    }
    /**
     * Get questions matching specific criteria
     */
    protected function getQuestionsForCriteria($subjectId, $bloomLevel = null, $type = null, $topics = [], $limit = 10, $exclude = [])
    {
        $query = Question::where('subject_id', $subjectId)
            ->where('in_question_bank', true);
            // Removed: ->where('created_by', Auth::id())
            // Allow selecting from all questions in the question bank, not just own questions

        if ($bloomLevel) {
            if ($bloomLevel == 4) {
                // Level 4+ means bloom_level >= 4
                $query->where('bloom_level', '>=', 4);
            } else {
                $query->where('bloom_level', $bloomLevel);
            }
        }

        // NEW: Support array of types for multiple_choice+true_false or essay+fill_blank
        if ($type) {
            if (is_array($type)) {
                $query->whereIn('type', $type);
            } else {
                $query->where('type', $type);
            }
        }

        if (!empty($topics)) {
            $query->whereIn('topic_id', $topics);
        }

        if (!empty($exclude)) {
            $query->whereNotIn('id', $exclude);
        }

        return $query->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}

