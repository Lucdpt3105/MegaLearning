<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class QuestionManagementController extends Controller
{
    /**
     * Quản lý tất cả câu hỏi (từ teacher + admin)
     */
    public function index(Request $request)
    {
        $query = Question::with(['subject', 'creator', 'answers']);

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('created_by', $request->teacher_id);
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('question_text', 'like', '%' . $request->search . '%')
                  ->orWhere('explanation', 'like', '%' . $request->search . '%');
            });
        }

        $questions = $query->latest()->paginate(20);
        
        // Data for filters
        $teachers = User::role('teacher')->get();
        $subjects = Subject::all();

        return view('admin.questions.index', compact('questions', 'teachers', 'subjects'));
    }

    /**
     * Hiển thị chi tiết câu hỏi
     */
    public function show(Question $question)
    {
        $question->load(['subject', 'creator', 'answers', 'exams']);

        return view('admin.questions.show', compact('question'));
    }

    /**
     * Form tạo câu hỏi mới
     */
    public function create()
    {
        $subjects = Subject::all();
        return view('admin.questions.create', compact('subjects'));
    }

    /**
     * Lưu câu hỏi mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,essay,short_answer,fill_blank',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|numeric|min:0',
            'explanation' => 'nullable|string',
            'answers' => 'required|array|min:1',
            'answers.*.answer_text' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
        ]);

        $question = Question::create([
            'subject_id' => $validated['subject_id'],
            'question_text' => $validated['question_text'],
            'type' => $validated['type'],
            'difficulty' => $validated['difficulty'],
            'points' => $validated['points'],
            'explanation' => $validated['explanation'],
            'created_by' => auth()->id(),
        ]);

        // Create answers
        foreach ($validated['answers'] as $answerData) {
            $question->answers()->create($answerData);
        }

        return redirect()->route('admin.questions.show', $question)
            ->with('success', 'Tạo câu hỏi thành công!');
    }

    /**
     * Form sửa câu hỏi
     */
    public function edit(Question $question)
    {
        $question->load('answers');
        $subjects = Subject::all();

        return view('admin.questions.edit', compact('question', 'subjects'));
    }

    /**
     * Cập nhật câu hỏi
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,essay,short_answer,fill_blank',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|numeric|min:0',
            'explanation' => 'nullable|string',
            'answers' => 'required|array|min:1',
            'answers.*.answer_text' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
        ]);

        $question->update([
            'subject_id' => $validated['subject_id'],
            'question_text' => $validated['question_text'],
            'type' => $validated['type'],
            'difficulty' => $validated['difficulty'],
            'points' => $validated['points'],
            'explanation' => $validated['explanation'],
        ]);

        // Update answers: delete old, create new
        $question->answers()->delete();
        foreach ($validated['answers'] as $answerData) {
            $question->answers()->create($answerData);
        }

        return redirect()->route('admin.questions.show', $question)
            ->with('success', 'Cập nhật câu hỏi thành công!');
    }

    /**
     * Xóa câu hỏi
     */
    public function destroy(Question $question)
    {
        // Check if question is used in any exam
        if ($question->exams()->count() > 0) {
            return back()->with('error', 'Không thể xóa câu hỏi đang được sử dụng trong đề thi!');
        }

        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Xóa câu hỏi thành công!');
    }

    /**
     * Bulk delete questions
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
        ]);

        $questions = Question::whereIn('id', $validated['question_ids'])->get();
        
        $deleted = 0;
        $skipped = 0;

        foreach ($questions as $question) {
            if ($question->exams()->count() > 0) {
                $skipped++;
            } else {
                $question->delete();
                $deleted++;
            }
        }

        $message = "Đã xóa {$deleted} câu hỏi.";
        if ($skipped > 0) {
            $message .= " Bỏ qua {$skipped} câu hỏi đang được sử dụng.";
        }

        return back()->with('success', $message);
    }

    /**
     * Display questions by subject
     */
    public function bySubject(Request $request, Subject $subject)
    {
        $query = Question::with(['topic', 'answers'])
            ->where('subject_id', $subject->id)
            ->where('in_question_bank', true);

        // Filter by topic
        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        // Filter by bloom level
        if ($request->filled('bloom_level')) {
            $query->where('bloom_level', $request->bloom_level);
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(15);
        $topics = \App\Models\Topic::where('subject_id', $subject->id)->orderBy('order')->get();

        return view('admin.questions.by-subject', compact('questions', 'subject', 'topics'));
    }

    /**
     * Export questions to Excel
     */
    public function export(Subject $subject)
    {
        $filename = 'questions_' . $subject->code . '_' . date('Y-m-d') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\QuestionsExport($subject->id), $filename);
    }

    /**
     * Import questions from Excel
     */
    public function import(Request $request, Subject $subject)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120'
        ]);

        try {
            $beforeCount = Question::where('subject_id', $subject->id)
                ->where('in_question_bank', true)
                ->count();
            
            $import = new \App\Imports\QuestionsImport($subject->id);
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));
            
            $afterCount = Question::where('subject_id', $subject->id)
                ->where('in_question_bank', true)
                ->count();
            
            $importedCount = $afterCount - $beforeCount;
            
            return redirect()->route('admin.questions.by-subject', $subject)
                ->with('success', "Import thành công! Đã thêm {$importedCount} câu hỏi vào ngân hàng.");
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Dòng ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return redirect()->route('admin.questions.by-subject', $subject)
                ->with('error', 'Import thất bại. Lỗi: ' . implode(' | ', $errors));
        } catch (\Exception $e) {
            return redirect()->route('admin.questions.by-subject', $subject)
                ->with('error', 'Import thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template
     */
    public function downloadTemplate()
    {
        $filename = 'template_import_questions.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\QuestionTemplateExport(), $filename);
    }
}
