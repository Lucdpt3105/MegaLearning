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
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'shuffle_questions' => 'boolean',
            'shuffle_answers' => 'boolean',
            'show_results_immediately' => 'boolean',
            'allow_review' => 'boolean',
            'instructions' => 'nullable|string',
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
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'passing_score' => $validated['passing_score'] ?? null,
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'shuffle_answers' => $request->boolean('shuffle_answers'),
            'show_results_immediately' => $request->boolean('show_results_immediately', true),
            'allow_review' => $request->boolean('allow_review', true),
            'status' => 'draft',
            'instructions' => $validated['instructions'] ?? null,
        ]);

        return redirect()->route('teacher.exams.show', $exam)
            ->with('success', 'Đề thi đã được tạo thành công! Bây giờ hãy thêm câu hỏi.');
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
}
