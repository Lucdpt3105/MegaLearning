<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ForumQuestion;
use Illuminate\Http\Request;

class ForumQuestionController extends Controller
{
    // Danh sách bài viết
    public function index(Request $request)
    {
        return ForumQuestion::with('user:id,name')
            ->withCount('user')
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    // Xem chi tiết bài viết
    public function show($id)
    {
        $question = ForumQuestion::with('user:id,name')->findOrFail($id);
        $question->increment('views');
        return $question;
    }

    // Tạo bài viết
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $data['user_id'] = $request->user()->id;
        $question = ForumQuestion::create($data);
        return response()->json($question, 201);
    }

    // Cập nhật bài viết
    public function update(Request $request, $id)
    {
        $question = ForumQuestion::findOrFail($id);
        $this->authorize('update', $question);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $question->update($data);
        return $question;
    }

    // Xóa bài viết
    public function destroy($id)
    {
        $question = ForumQuestion::findOrFail($id);
        $this->authorize('delete', $question);

        $question->delete();
        return response()->noContent();
    }
}
