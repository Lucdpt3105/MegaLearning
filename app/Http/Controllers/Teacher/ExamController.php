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

        // Get questions by Bloom level
        foreach (['level_1' => 1, 'level_2' => 2, 'level_3' => 3, 'level_4' => 4] as $key => $bloomLevel) {
            $count = $criteria[$key];
            if ($count > 0) {
                $questions = $this->getQuestionsForCriteria(
                    $exam->subject_id,
                    $bloomLevel,
                    null,
                    $criteria['topics'],
                    $count
                );
                
                foreach ($questions as $question) {
                    $selectedQuestions[$question->id] = ['order' => $order++, 'points' => 1];
                }
            }
        }

        // Get questions by type (if not already selected by level)
        if ($criteria['multiple_choice'] > 0) {
            $existing = count($selectedQuestions);
            $needed = max(0, $criteria['multiple_choice'] - $existing);
            
            if ($needed > 0) {
                $questions = $this->getQuestionsForCriteria(
                    $exam->subject_id,
                    null,
                    'multiple_choice',
                    $criteria['topics'],
                    $needed,
                    array_keys($selectedQuestions)
                );
                
                foreach ($questions as $question) {
                    if (!isset($selectedQuestions[$question->id])) {
                        $selectedQuestions[$question->id] = ['order' => $order++, 'points' => 1];
                    }
                }
            }
        }

        if ($criteria['essay'] > 0) {
            $questions = $this->getQuestionsForCriteria(
                $exam->subject_id,
                null,
                'essay',
                $criteria['topics'],
                $criteria['essay'],
                array_keys($selectedQuestions)
            );
            
            foreach ($questions as $question) {
                if (!isset($selectedQuestions[$question->id])) {
                    $selectedQuestions[$question->id] = ['order' => $order++, 'points' => 2];
                }
            }
        }

        // Attach questions to exam
        if (!empty($selectedQuestions)) {
            $exam->questions()->attach($selectedQuestions);
        }

        return count($selectedQuestions);
    }

    /**
     * Get questions matching specific criteria
     */
    protected function getQuestionsForCriteria($subjectId, $bloomLevel = null, $type = null, $topics = [], $limit = 10, $exclude = [])
    {
        $query = Question::where('subject_id', $subjectId)
            ->where('in_question_bank', true)
            ->where('created_by', Auth::id());

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

        return $query->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}

