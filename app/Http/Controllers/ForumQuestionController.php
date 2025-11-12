<?php

namespace App\Http\Controllers;

use App\Models\ForumQuestion;
use Illuminate\Http\Request;

class ForumQuestionController extends Controller
{
    public function index()
    {
        // Lấy danh sách bài viết mới nhất
        $questions = ForumQuestion::with('user')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('forum.index', compact('questions'));
    }

    public function create()
    {
        return view('forum.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        ForumQuestion::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('forum.index')->with('success', 'Đăng bài thành công!');
    }

    public function show($id)
    {
        $question = ForumQuestion::with('user')->findOrFail($id);
        $question->increment('views');
        return view('forum.show', compact('question'));
    }

    public function edit($id)
    {
        $question = ForumQuestion::findOrFail($id);
        $this->authorize('update', $question);
        return view('forum.edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $question = ForumQuestion::findOrFail($id);
        $this->authorize('update', $question);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $question->update($request->only('title', 'content'));
        return redirect()->route('forum.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $question = ForumQuestion::findOrFail($id);
        $this->authorize('delete', $question);
        $question->delete();

        return redirect()->route('forum.index')->with('success', 'Đã xóa bài viết.');
    }
}
