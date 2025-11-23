<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Subject;
use App\Models\Topic;
use App\Exports\QuestionsExport;
use App\Exports\QuestionTemplateExport;
use App\Imports\QuestionsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subjects = Subject::where('teacher_id', Auth::id())
            ->withCount(['questions' => function ($query) {
                $query->where('in_question_bank', true);
            }])
            ->get();

        return view('teacher.questions.subjects', compact('subjects'));
    }

    /**
     * Display questions by subject
     */
    public function bySubject(Request $request, Subject $subject)
    {
        // Check ownership
        if ($subject->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $query = Question::with(['topic', 'answers'])
            ->where('subject_id', $subject->id)
            ->where('in_question_bank', true)
            ->where('created_by', Auth::id());

        // Filter by topic/chapter
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
        $topics = Topic::where('subject_id', $subject->id)->orderBy('order')->get();

        return view('teacher.questions.by-subject', compact('questions', 'subject', 'topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subjects = Subject::where('teacher_id', Auth::id())->get();
        return view('teacher.questions.create', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'content' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,essay,fill_blank',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|numeric|min:0',
            'explanation' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'answers' => 'required_if:type,multiple_choice,true_false|array|min:2',
            'answers.*.content' => 'required_with:answers|string',
            'answers.*.is_correct' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Upload image if exists
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('questions', 'public');
            }

            // Create question
            $question = Question::create([
                'subject_id' => $validated['subject_id'],
                'content' => $validated['content'],
                'type' => $validated['type'],
                'difficulty' => $validated['difficulty'],
                'points' => $validated['points'],
                'explanation' => $validated['explanation'],
                'image_url' => $imagePath,
                'created_by' => Auth::id(),
                'in_question_bank' => true,
            ]);

            // Create answers
            if (isset($validated['answers'])) {
                foreach ($validated['answers'] as $index => $answerData) {
                    Answer::create([
                        'question_id' => $question->id,
                        'content' => $answerData['content'],
                        'is_correct' => $answerData['is_correct'] ?? false,
                        'order' => $index + 1,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('teacher.questions.index')
                ->with('success', 'Câu hỏi đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        $question->load(['subject', 'creator', 'answers']);
        
        // Check ownership
        if ($question->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('teacher.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        // Check ownership
        if ($question->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $question->load('answers');
        $subjects = Subject::where('teacher_id', Auth::id())->get();

        return view('teacher.questions.edit', compact('question', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        // Check ownership
        if ($question->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'content' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,essay,fill_blank',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'required|numeric|min:0',
            'explanation' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'answers' => 'required_if:type,multiple_choice,true_false|array|min:2',
            'answers.*.content' => 'required_with:answers|string',
            'answers.*.is_correct' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Upload new image if exists
            if ($request->hasFile('image')) {
                // Delete old image
                if ($question->image_url) {
                    Storage::disk('public')->delete($question->image_url);
                }
                $validated['image_url'] = $request->file('image')->store('questions', 'public');
            }

            // Update question
            $question->update([
                'subject_id' => $validated['subject_id'],
                'content' => $validated['content'],
                'type' => $validated['type'],
                'difficulty' => $validated['difficulty'],
                'points' => $validated['points'],
                'explanation' => $validated['explanation'] ?? $question->explanation,
                'image_url' => $validated['image_url'] ?? $question->image_url,
            ]);

            // Delete old answers and create new ones
            $question->answers()->delete();
            
            if (isset($validated['answers'])) {
                foreach ($validated['answers'] as $index => $answerData) {
                    Answer::create([
                        'question_id' => $question->id,
                        'content' => $answerData['content'],
                        'is_correct' => $answerData['is_correct'] ?? false,
                        'order' => $index + 1,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('teacher.questions.index')
                ->with('success', 'Câu hỏi đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        // Check ownership
        if ($question->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image if exists
        if ($question->image_url) {
            Storage::disk('public')->delete($question->image_url);
        }

        $question->delete();

        return redirect()->route('teacher.questions.index')
            ->with('success', 'Câu hỏi đã được xóa thành công!');
    }

    /**
     * Export questions to Excel
     */
    public function export(Subject $subject)
    {
        // Check ownership
        if ($subject->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $filename = 'questions_' . $subject->code . '_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new QuestionsExport($subject->id), $filename);
    }

    /**
     * Import questions from Excel
     */
    public function import(Request $request, Subject $subject)
    {
        // Check ownership
        if ($subject->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120'
        ]);

        try {
            // Count questions before import
            $beforeCount = Question::where('subject_id', $subject->id)
                ->where('in_question_bank', true)
                ->count();
            
            $import = new QuestionsImport($subject->id);
            Excel::import($import, $request->file('file'));
            
            // Count questions after import
            $afterCount = Question::where('subject_id', $subject->id)
                ->where('in_question_bank', true)
                ->count();
            
            $importedCount = $afterCount - $beforeCount;
            
            \Log::info('Import completed for subject: ' . $subject->id . ', imported: ' . $importedCount);
            
            return redirect()->route('teacher.questions.by-subject', $subject)
                ->with('success', "Import thành công! Đã thêm {$importedCount} câu hỏi vào ngân hàng.");
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Dòng ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return redirect()->route('teacher.questions.by-subject', $subject)
                ->with('error', 'Import thất bại. Lỗi: ' . implode(' | ', $errors));
        } catch (\Exception $e) {
            \Log::error('Import failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->route('teacher.questions.by-subject', $subject)
                ->with('error', 'Import thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template
     */
    public function downloadTemplate()
    {
        $filename = 'template_import_questions.xlsx';
        
        return Excel::download(new QuestionTemplateExport(), $filename);
    }
}
