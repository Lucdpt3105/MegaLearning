<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    /**
     * Display a listing of topics
     */
    public function index(Request $request)
    {
        $query = Topic::with(['subject'])
            ->withCount('questions');

        // Filter by subject
        if ($request->has('subject_id') && $request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('subject', function($subQ) use ($search) {
                      $subQ->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'order');
        $sortOrder = $request->get('order', 'asc');
        
        if ($sortBy === 'questions') {
            $query->orderBy('questions_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $topics = $query->paginate(15);
        $subjects = Subject::orderBy('name')->get();

        return view('teacher.topics.index', compact('topics', 'subjects'));
    }

    /**
     * Show the form for creating a new topic
     */
    public function create(Request $request)
    {
        $subjects = Subject::orderBy('name')->get();
        $selectedSubject = $request->get('subject_id');

        return view('teacher.topics.create', compact('subjects', 'selectedSubject'));
    }

    /**
     * Store a newly created topic
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'duration' => 'nullable|integer|min:1',
            'resources' => 'nullable|array',
            'resources.*' => 'nullable|url',
        ], [
            'subject_id.required' => 'Vui lòng chọn môn học',
            'subject_id.exists' => 'Môn học không tồn tại',
            'name.required' => 'Tên chủ đề là bắt buộc',
            'name.max' => 'Tên chủ đề không được quá 255 ký tự',
            'order.integer' => 'Thứ tự phải là số nguyên',
            'order.min' => 'Thứ tự phải lớn hơn hoặc bằng 0',
            'duration.integer' => 'Thời lượng phải là số nguyên',
            'duration.min' => 'Thời lượng phải lớn hơn 0',
            'resources.array' => 'Tài nguyên phải là danh sách',
            'resources.*.url' => 'Mỗi tài nguyên phải là URL hợp lệ',
        ]);

        // Auto-calculate order if not provided
        if (!isset($validated['order'])) {
            $validated['order'] = Topic::where('subject_id', $validated['subject_id'])
                ->max('order') + 1;
        }

        // Clean resources (remove empty entries)
        if (isset($validated['resources'])) {
            $validated['resources'] = array_filter($validated['resources']);
        }

        $topic = Topic::create($validated);

        return redirect()
            ->route('teacher.topics.show', $topic->id)
            ->with('success', 'Chủ đề đã được tạo thành công!');
    }

    /**
     * Display the specified topic
     */
    public function show($id)
    {
        $topic = Topic::with(['subject', 'questions' => function($q) {
            $q->withCount('answers')
              ->orderBy('created_at', 'desc');
        }])
        ->withCount('questions')
        ->findOrFail($id);

        // Statistics by Bloom's level
        $bloomStats = $topic->questions()
            ->selectRaw('bloom_level, COUNT(*) as count')
            ->groupBy('bloom_level')
            ->pluck('count', 'bloom_level')
            ->toArray();

        // Statistics by difficulty
        $difficultyStats = $topic->questions()
            ->selectRaw('difficulty, COUNT(*) as count')
            ->groupBy('difficulty')
            ->pluck('count', 'difficulty')
            ->toArray();

        return view('teacher.topics.show', compact('topic', 'bloomStats', 'difficultyStats'));
    }

    /**
     * Show the form for editing the topic
     */
    public function edit($id)
    {
        $topic = Topic::with('subject')->findOrFail($id);
        $subjects = Subject::orderBy('name')->get();

        return view('teacher.topics.edit', compact('topic', 'subjects'));
    }

    /**
     * Update the specified topic
     */
    public function update(Request $request, $id)
    {
        $topic = Topic::findOrFail($id);

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'duration' => 'nullable|integer|min:1',
            'resources' => 'nullable|array',
            'resources.*' => 'nullable|url',
        ], [
            'subject_id.required' => 'Vui lòng chọn môn học',
            'subject_id.exists' => 'Môn học không tồn tại',
            'name.required' => 'Tên chủ đề là bắt buộc',
            'name.max' => 'Tên chủ đề không được quá 255 ký tự',
            'order.integer' => 'Thứ tự phải là số nguyên',
            'order.min' => 'Thứ tự phải lớn hơn hoặc bằng 0',
            'duration.integer' => 'Thời lượng phải là số nguyên',
            'duration.min' => 'Thời lượng phải lớn hơn 0',
            'resources.array' => 'Tài nguyên phải là danh sách',
            'resources.*.url' => 'Mỗi tài nguyên phải là URL hợp lệ',
        ]);

        // Clean resources (remove empty entries)
        if (isset($validated['resources'])) {
            $validated['resources'] = array_filter($validated['resources']);
        }

        $topic->update($validated);

        return redirect()
            ->route('teacher.topics.show', $topic->id)
            ->with('success', 'Chủ đề đã được cập nhật thành công!');
    }

    /**
     * Remove the specified topic
     */
    public function destroy($id)
    {
        $topic = Topic::withCount('questions')->findOrFail($id);

        // Check if topic has questions
        if ($topic->questions_count > 0) {
            return back()->with('error', "Không thể xóa chủ đề vì còn {$topic->questions_count} câu hỏi liên quan. Vui lòng xóa hoặc chuyển các câu hỏi trước.");
        }

        $subjectId = $topic->subject_id;
        $topic->delete();

        return redirect()
            ->route('teacher.topics.index', ['subject_id' => $subjectId])
            ->with('success', 'Chủ đề đã được xóa thành công!');
    }

    /**
     * Reorder topics within a subject
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:topics,id',
            'orders.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['orders'] as $item) {
            Topic::where('id', $item['id'])
                ->where('subject_id', $validated['subject_id'])
                ->update(['order' => $item['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật thứ tự thành công!'
        ]);
    }

    /**
     * Bulk delete topics
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'topic_ids' => 'required|array',
            'topic_ids.*' => 'exists:topics,id',
        ]);

        $topics = Topic::withCount('questions')
            ->whereIn('id', $validated['topic_ids'])
            ->get();

        $cannotDelete = [];
        $deleted = 0;

        foreach ($topics as $topic) {
            if ($topic->questions_count > 0) {
                $cannotDelete[] = "{$topic->name} ({$topic->questions_count} câu hỏi)";
            } else {
                $topic->delete();
                $deleted++;
            }
        }

        if (count($cannotDelete) > 0) {
            return back()->with('warning', "Đã xóa {$deleted} chủ đề. Không thể xóa: " . implode(', ', $cannotDelete));
        }

        return back()->with('success', "Đã xóa {$deleted} chủ đề thành công!");
    }
}
