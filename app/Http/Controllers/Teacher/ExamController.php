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
            'class_room_id' => 'nullable|exists:class_rooms,id',
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

            // Check if totals are equal (REQUIRED)
            if ($totalByType !== $totalByLevel) {
                $level1 = $request->input('auto_gen_level_1', 0);
                $level2 = $request->input('auto_gen_level_2', 0);
                $level3 = $request->input('auto_gen_level_3', 0);
                $level4 = $request->input('auto_gen_level_4', 0);
                $multipleChoice = $request->input('auto_gen_multiple_choice', 0);
                $essay = $request->input('auto_gen_essay', 0);
                
                return redirect()->back()
                    ->withInput()
                    ->with('error_popup', "⚠️ Lỗi số lượng câu hỏi!\n\n📊 Tổng số câu theo LOẠI: {$totalByType} câu\n   • Trắc nghiệm: {$multipleChoice} câu\n   • Tự luận: {$essay} câu\n\n📈 Tổng số câu theo MỨC ĐỘ: {$totalByLevel} câu\n   • Mức 1 (Nhận biết): {$level1} câu\n   • Mức 2 (Thông hiểu): {$level2} câu\n   • Mức 3 (Vận dụng): {$level3} câu\n   • Mức 4 (Vận dụng cao): {$level4} câu\n\n❌ HAI TỔNG SỐ PHẢI BẰNG NHAU!\n\nTổng câu theo loại ({$totalByType}) " . ($totalByType > $totalByLevel ? '>' : '<') . " Tổng câu theo mức độ ({$totalByLevel})\n\n💡 Mẹo: Nếu muốn {$totalByLevel} câu, hãy điều chỉnh:\n   • Trắc nghiệm + Tự luận = {$totalByLevel} câu");
            }

            // Check if total is valid
            if ($totalByLevel == 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error_popup', '❌ Vui lòng chọn ít nhất một câu hỏi theo mức độ (Mức 1, 2, 3, hoặc 4).\n\nBạn phải nhập số lượng câu hỏi cho ít nhất một mức độ!');
            }

            if ($totalByType == 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error_popup', '❌ Vui lòng chọn ít nhất một câu hỏi theo loại (Trắc nghiệm hoặc Tự luận).\n\nBạn phải nhập số lượng câu hỏi cho ít nhất một loại!');
            }
        }

        // Check subject ownership
        $subject = Subject::findOrFail($validated['subject_id']);
        if ($subject->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $exam = Exam::create([
            'subject_id' => $validated['subject_id'],
            'class_room_id' => $validated['class_room_id'] ?? null,
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
        ]);

        // Auto-generate questions if requested
        if ($request->boolean('auto_generate')) {
            $this->autoGenerateQuestions($exam, $request);
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
        ]);

        $maxOrder = $exam->questions()->max('exam_questions.order') ?? 0;

        foreach ($validated['question_ids'] as $questionId) {
            // Get points for this specific question
            $points = $request->input("points.{$questionId}", 1);
            
            $exam->questions()->attach($questionId, [
                'order' => ++$maxOrder,
                'points' => $points,
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
        
        // 1. Select Multiple Choice questions distributed by level
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
                    $questions = $this->getQuestionsForCriteria(
                        $exam->subject_id,
                        $bloomLevel,
                        'multiple_choice',
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
            
            // If insufficient MC questions, show error
            if ($mcCollected < $criteria['multiple_choice']) {
                $details = implode("; ", $mcDetails);
                return redirect()->back()
                    ->with('error_popup', "Không đủ câu trắc nghiệm!\n\nCần: {$criteria['multiple_choice']} câu\nCó: $mcCollected câu\n\nChi tiết: $details")
                    ->withInput();
            }
        }

        // 2. Select Essay questions distributed by level
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
                    $questions = $this->getQuestionsForCriteria(
                        $exam->subject_id,
                        $bloomLevel,
                        'essay',
                        $criteria['topics'],
                        $count,
                        array_keys($selectedQuestions)
                    );
                    
                    $found = $questions->count();
                    $essayCollected += $found;
                    $essayDetails[] = "Level $bloomLevel: cần $count, có $found";
                    
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
            
            // If insufficient Essay questions, show error
            if ($essayCollected < $criteria['essay']) {
                $details = implode("; ", $essayDetails);
                return redirect()->back()
                    ->with('error_popup', "Không đủ câu tự luận!\n\nCần: {$criteria['essay']} câu\nCó: $essayCollected câu\n\nChi tiết: $details")
                    ->withInput();
            }
        }

        // Check if NO questions were selected
        if (empty($selectedQuestions)) {
            // Get total available questions for debugging
            $totalAvailable = Question::where('subject_id', $exam->subject_id)
                ->where('in_question_bank', true)
                ->count();
            
            $byType = Question::where('subject_id', $exam->subject_id)
                ->where('in_question_bank', true)
                ->select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray();
            
            $byLevel = Question::where('subject_id', $exam->subject_id)
                ->where('in_question_bank', true)
                ->select('bloom_level', DB::raw('count(*) as count'))
                ->groupBy('bloom_level')
                ->pluck('count', 'bloom_level')
                ->toArray();
            
            $errorMsg = "❌ KHÔNG TÌM THẤY CÂU HỎI NÀO!\n\n";
            $errorMsg .= "📊 Thống kê ngân hàng câu hỏi:\n";
            $errorMsg .= "   • Tổng số câu: {$totalAvailable} câu\n\n";
            
            if (!empty($byType)) {
                $errorMsg .= "📝 Theo loại:\n";
                foreach ($byType as $type => $count) {
                    $typeName = $type === 'multiple_choice' ? 'Trắc nghiệm' : ($type === 'essay' ? 'Tự luận' : $type);
                    $errorMsg .= "   • {$typeName}: {$count} câu\n";
                }
                $errorMsg .= "\n";
            }
            
            if (!empty($byLevel)) {
                $errorMsg .= "📈 Theo mức độ:\n";
                foreach ([1, 2, 3, 4] as $level) {
                    $count = $byLevel[$level] ?? 0;
                    $errorMsg .= "   • Mức {$level}: {$count} câu\n";
                }
                $errorMsg .= "\n";
            }
            
            $errorMsg .= "💡 GIẢI PHÁP:\n";
            if ($totalAvailable == 0) {
                $errorMsg .= "   1. Vào Ngân hàng câu hỏi và tạo câu hỏi mới\n";
                $errorMsg .= "   2. Đảm bảo câu hỏi được đánh dấu 'Thêm vào ngân hàng'\n";
            } else {
                $errorMsg .= "   1. Kiểm tra lại số lượng câu bạn yêu cầu\n";
                $errorMsg .= "   2. Đảm bảo có đủ câu hỏi theo từng loại và mức độ\n";
                $errorMsg .= "   3. Thử bỏ chọn 'Chương/Bài' để lấy từ tất cả chương\n";
            }
            
            \Log::error('No questions selected for exam', [
                'exam_id' => $exam->id,
                'subject_id' => $exam->subject_id,
                'criteria' => $criteria,
                'available' => $totalAvailable,
                'by_type' => $byType,
                'by_level' => $byLevel
            ]);
            
            return redirect()->back()
                ->with('error_popup', $errorMsg)
                ->withInput();
        }

        // Calculate points to distribute based on exam's total_points and question difficulty
        if (!empty($selectedQuestions)) {
            $totalQuestions = count($selectedQuestions);
            $totalPoints = $exam->total_points; // Use exam's actual total points
            
            // Get bloom level for each question to calculate weighted points
            $questionObjects = Question::whereIn('id', array_keys($selectedQuestions))->get()->keyBy('id');
            
            // Calculate weight based on bloom level (higher level = more points)
            // Level 1 (Remember): 1.0x, Level 2 (Understand): 1.2x, Level 3 (Apply): 1.5x, Level 4+ (Analyze/Create): 2.0x
            $levelWeights = [1 => 1.0, 2 => 1.2, 3 => 1.5, 4 => 2.0];
            $totalWeight = 0;
            $questionWeights = [];
            
            foreach ($selectedQuestions as $qId => $qData) {
                $question = $questionObjects->get($qId);
                $bloomLevel = $question ? min($question->bloom_level, 4) : 1;
                $weight = $levelWeights[$bloomLevel] ?? 1.0;
                
                // Essay questions get 1.5x multiplier on top of bloom level
                if ($qData['type'] === 'essay') {
                    $weight *= 1.5;
                }
                
                $questionWeights[$qId] = $weight;
                $totalWeight += $weight;
            }
            
            // Distribute points proportionally based on weights
            $finalQuestions = [];
            $distributedTotal = 0;
            
            foreach ($selectedQuestions as $qId => $qData) {
                $weight = $questionWeights[$qId];
                $points = round(($weight / $totalWeight) * $totalPoints, 2);
                
                // Ensure minimum 0.1 points per question
                $points = max(0.1, $points);
                
                $finalQuestions[$qId] = [
                    'order' => $qData['order'],
                    'points' => $points
                ];
                
                $distributedTotal += $points;
            }
            
            // Adjust total to exactly match exam's total_points
            $difference = round($totalPoints - $distributedTotal, 2);
            if (abs($difference) > 0.01) {
                // Distribute difference to questions proportionally
                $adjustmentPerQuestion = $difference / $totalQuestions;
                
                foreach ($finalQuestions as $qId => &$qData) {
                    $qData['points'] = round($qData['points'] + $adjustmentPerQuestion, 2);
                    $qData['points'] = max(0.1, $qData['points']); // Ensure minimum
                }
                
                // Final adjustment to last question to ensure exact total
                $actualTotal = array_sum(array_column($finalQuestions, 'points'));
                $finalDifference = round($totalPoints - $actualTotal, 2);
                $lastKey = array_key_last($finalQuestions);
                $finalQuestions[$lastKey]['points'] = round(
                    $finalQuestions[$lastKey]['points'] + $finalDifference,
                    2
                );
            }
            
            \Log::info('Point Distribution:', [
                'total_questions' => $totalQuestions,
                'total_points' => $totalPoints,
                'distributed' => array_sum(array_column($finalQuestions, 'points')),
                'details' => $finalQuestions
            ]);
            
            // Attach questions to exam
            $exam->questions()->attach($finalQuestions);
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

        if ($type) {
            $query->where('type', $type);
        }

        if (!empty($topics)) {
            $query->whereIn('topic_id', $topics);
        }

        if (!empty($exclude)) {
            $query->whereNotIn('id', $exclude);
        }

        $results = $query->inRandomOrder()
            ->limit($limit)
            ->get();
        
        // Debug logging
        \Log::info('Question Query:', [
            'subject_id' => $subjectId,
            'bloom_level' => $bloomLevel,
            'type' => $type,
            'topics' => $topics,
            'limit' => $limit,
            'excluded_count' => count($exclude),
            'found' => $results->count(),
            'sql' => $query->toSql()
        ]);
        
        return $results;
    }
}

