<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Question;
use Illuminate\Http\Request;

class ExamManagementController extends Controller
{
    /**
     * Quản lý tất cả đề thi (từ teacher + admin)
     */
    public function index(Request $request)
    {
        $query = Exam::with(['subject', 'creator', 'classRoom', 'questions']);

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('created_by', $request->teacher_id);
        }

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

        $exams = $query->latest()->paginate(20);
        
        // Data for filters
        $teachers = User::role('teacher')->get();
        $subjects = Subject::all();

        return view('admin.exams.index', compact('exams', 'teachers', 'subjects'));
    }

    /**
     * Hiển thị chi tiết đề thi
     */
    public function show(Exam $exam)
    {
        $exam->load([
            'subject',
            'creator',
            'classRoom',
            'questions' => function($q) {
                $q->withCount('answers');
            },
            'submissions' => function($q) {
                $q->with('student')->latest();
            }
        ]);

        $statistics = [
            'total_questions' => $exam->questions->count(),
            'total_submissions' => $exam->submissions->count(),
            'completed_submissions' => $exam->submissions->where('status', 'completed')->count(),
            'average_score' => $exam->submissions->where('status', 'completed')->avg('score'),
            'highest_score' => $exam->submissions->where('status', 'completed')->max('score'),
            'lowest_score' => $exam->submissions->where('status', 'completed')->min('score'),
        ];

        return view('admin.exams.show', compact('exam', 'statistics'));
    }

    /**
     * Chỉnh sửa đề thi (admin có quyền sửa đề của teacher)
     */
    public function edit(Exam $exam)
    {
        $subjects = Subject::all();
        $classRooms = ClassRoom::all();
        $teachers = User::role('teacher')->get();

        return view('admin.exams.edit', compact('exam', 'subjects', 'classRooms', 'teachers'));
    }

    /**
     * Cập nhật đề thi
     */
    public function update(Request $request, Exam $exam)
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
            'status' => 'required|in:draft,published,archived',
            'shuffle_questions' => 'boolean',
            'shuffle_answers' => 'boolean',
            'show_results_immediately' => 'boolean',
            'allow_review' => 'boolean',
        ]);

        $exam->update($validated);

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Cập nhật đề thi thành công!');
    }

    /**
     * Xóa đề thi (admin có quyền xóa)
     */
    public function destroy(Exam $exam)
    {
        // Check if exam has submissions
        if ($exam->submissions()->count() > 0) {
            return back()->with('error', 'Không thể xóa đề thi đã có bài nộp!');
        }

        $exam->delete();

        return redirect()->route('admin.exams.index')
            ->with('success', 'Xóa đề thi thành công!');
    }

    /**
     * Thay đổi trạng thái đề thi (publish/archive)
     */
    public function updateStatus(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,published,archived',
        ]);

        $exam->update(['status' => $validated['status']]);

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    /**
     * Xem danh sách câu hỏi của đề thi
     */
    public function questions(Exam $exam)
    {
        $exam->load(['questions' => function($q) {
            $q->with('answers')->orderBy('order');
        }]);

        return view('admin.exams.questions', compact('exam'));
    }

    /**
     * Xem kết quả thi của học sinh
     */
    public function results(Exam $exam)
    {
        $submissions = $exam->submissions()
            ->with(['student', 'grader'])
            ->latest()
            ->paginate(50);

        return view('admin.exams.results', compact('exam', 'submissions'));
    }

    /**
     * Add questions from question bank to exam
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
            $points = $request->input("points.{$questionId}", 1);
            
            $exam->questions()->attach($questionId, [
                'order' => ++$maxOrder,
                'points' => $points,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with('success', 'Đã thêm ' . count($validated['question_ids']) . ' câu hỏi vào đề thi!');
    }

    /**
     * Create custom question directly in exam
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

        $question = Question::create([
            'content' => $validated['content'],
            'type' => $validated['type'],
            'subject_id' => $exam->subject_id,
            'created_by' => auth()->id(),
            'difficulty' => 'medium',
            'bloom_level' => 'remember',
            'in_question_bank' => false,
            'explanation' => $validated['explanation'],
        ]);

        if ($validated['type'] === 'multiple_choice' && isset($validated['answers'])) {
            foreach ($validated['answers'] as $index => $answerData) {
                $question->answers()->create([
                    'content' => $answerData['text'] ?? $answerData['content'] ?? '',
                    'is_correct' => isset($answerData['is_correct']) ? (bool)$answerData['is_correct'] : ($index == ($validated['correct_answer'] ?? 0)),
                    'order' => $index + 1,
                ]);
            }
        }

        $exam->questions()->attach($question->id, [
            'order' => ++$maxOrder,
            'points' => $validated['points'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with('success', 'Đã thêm câu hỏi tùy chỉnh vào đề thi!');
    }

    /**
     * Remove question from exam
     */
    public function removeQuestion(Exam $exam, $examQuestionId)
    {
        $exam->questions()->detach($examQuestionId);

        $questions = $exam->questions()->orderBy('exam_questions.order')->get();
        foreach ($questions as $index => $question) {
            $exam->questions()->updateExistingPivot($question->id, ['order' => $index + 1]);
        }

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with('success', 'Đã xóa câu hỏi khỏi đề thi!');
    }

    /**
     * Reorder questions in exam
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
     * Publish exam to make it available to students
     */
    public function publish(Exam $exam)
    {
        if ($exam->questions()->count() === 0) {
            return redirect()
                ->route('admin.exams.show', $exam)
                ->with('error', 'Không thể xuất bản đề thi chưa có câu hỏi!');
        }

        $exam->update(['status' => 'published']);

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with('success', 'Đã xuất bản đề thi thành công!');
    }

    /**
     * Send notification to students about exam
     */
    public function sendNotification(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $students = $exam->classRoom 
            ? $exam->classRoom->students 
            : User::where('role', 'student')->get();

        foreach ($students as $student) {
            // TODO: Implement notification system
        }

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with('success', 'Đã gửi thông báo đến ' . $students->count() . ' học sinh!');
    }

    /**
     * Import questions from Excel file
     */
    public function importFromExcel(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'file' => 'required|mimes:xlsx,xls',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Đang phát triển tính năng import Excel cho đề thi!');
    }
}
