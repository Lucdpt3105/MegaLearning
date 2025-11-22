<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ForumQuestion;
use App\Models\ForumAnswer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Concerns\ForumQuestionData;

class ForumQuestionController extends Controller
{
    use ForumQuestionData;
    // Danh sách bài viết
    public function index(Request $request)
    {
        [$query] = $this->questionBaseQuery($request);
        $questions = $query->paginate(10);
        return response()->json([
            'data' => $questions->items(),
            'meta' => [
                'current_page' => $questions->currentPage(),
                'last_page' => $questions->lastPage(),
                'total' => $questions->total(),
            ]
        ]);
    }

    // Xem chi tiết bài viết
    public function show($id)
    {
        $question = ForumQuestion::findOrFail($id);
        $question->increment('views');
        $data = $this->assembleShowData($question);
        return response()->json(['data' => $data]);
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
        return response()->json(['data' => $question], 201);
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
        return response()->json(['data' => $question]);
    }

    // Xóa bài viết
    public function destroy($id)
    {
        $question = ForumQuestion::findOrFail($id);
        $this->authorize('delete', $question);

        $question->delete();
        return response()->noContent();
    }

    // Tạo câu trả lời
    public function storeAnswer(Request $request, $id)
    {
        $question = ForumQuestion::findOrFail($id);
        $this->authorize('create', ForumAnswer::class);
        $payload = $request->validate([
            'answer_content' => 'required|string|min:1',
            'parent_id' => 'nullable|integer'
        ]);
        $parentId = $payload['parent_id'] ?? null;
        if ($parentId) {
            $parent = ForumAnswer::where('forum_answer_id', $parentId)
                ->where('forum_question_id', $question->getKey())
                ->first();
            if (!$parent) {
                return response()->json(['error' => 'Invalid parent answer'], 422);
            }
        }
        $answer = ForumAnswer::create([
            'forum_question_id' => $question->getKey(),
            'user_id' => auth()->id(),
            'answer_content' => $payload['answer_content'],
            'parent_id' => $parentId,
        ]);
        $depth = $this->computeAnswerDepth($answer);
        return response()->json([
            'data' => [
                'answer' => $answer->load('user:id,name')->loadSum('votes as votes_sum', 'value'),
                'depth' => $depth
            ]
        ], 201);
    }

    // Vote câu hỏi lên
    public function voteUp($id)
    {
        $question = ForumQuestion::findOrFail($id);
        $current = $this->toggleQuestionVote($question, 1);
        return response()->json([
            'data' => [
                'votes_sum' => (int)$question->votes()->whereNull('forum_answer_id')->sum('value'),
                'my_vote' => $current
            ]
        ]);
    }

    // Vote câu hỏi xuống
    public function voteDown($id)
    {
        $question = ForumQuestion::findOrFail($id);
        $current = $this->toggleQuestionVote($question, -1);
        return response()->json([
            'data' => [
                'votes_sum' => (int)$question->votes()->whereNull('forum_answer_id')->sum('value'),
                'my_vote' => $current
            ]
        ]);
    }

    // Vote trả lời lên
    public function voteAnswerUp($questionId, $answerId)
    {
        $question = ForumQuestion::findOrFail($questionId);
        $answer = ForumAnswer::findOrFail($answerId);
        $current = $this->toggleAnswerVote($answer, $question, 1);
        return response()->json([
            'data' => [
                'answer_id' => $answer->getKey(),
                'votes_sum' => (int)$answer->votes()->sum('value'),
                'my_vote' => $current
            ]
        ]);
    }

    // Vote trả lời xuống
    public function voteAnswerDown($questionId, $answerId)
    {
        $question = ForumQuestion::findOrFail($questionId);
        $answer = ForumAnswer::findOrFail($answerId);
        $current = $this->toggleAnswerVote($answer, $question, -1);
        return response()->json([
            'data' => [
                'answer_id' => $answer->getKey(),
                'votes_sum' => (int)$answer->votes()->sum('value'),
                'my_vote' => $current
            ]
        ]);
    }

    // Xóa trả lời (đệ quy)
    public function destroyAnswer($questionId, $answerId)
    {
        $question = ForumQuestion::findOrFail($questionId);
        $answer = ForumAnswer::findOrFail($answerId);
        if ($answer->forum_question_id !== $question->getKey()) {
            return response()->json(['error' => 'Answer does not belong to question'], 404);
        }
        $this->authorize('delete', $answer);
        $this->deleteAnswerRecursive($answer);
        return response()->json(['data' => ['deleted' => true, 'answer_id' => $answerId]]);
    }

    protected function deleteAnswerRecursive(ForumAnswer $answer): void
    {
        $children = $answer->children()->get();
        foreach ($children as $child) { $this->deleteAnswerRecursive($child); }
        $answer->delete();
    }
}
