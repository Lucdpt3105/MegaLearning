<?php

namespace App\Http\Controllers;

use App\Models\ForumQuestion;
use App\Models\Vote;
use App\Models\ForumAnswer;
use Illuminate\Http\Request;

class ForumQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // map policy methods to controller resource methods, expects route param {forumQuestion}
        $this->authorizeResource(ForumQuestion::class, 'forumQuestion');
    }
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'latest');

        $query = ForumQuestion::query()
            ->with('user:id,name')
            ->withSum(['votes as votes_sum' => function ($q) {
                $q->whereNull('forum_answer_id');
            }], 'value')
            // Count ALL answers (including nested), not just top-level
            ->withCount('answers as answers_count');

        switch ($sort) {
            case 'votes':
                $query->orderByDesc('votes_sum')
                    ->orderByDesc('created_at');
                break;

            case 'answers':
                $query->orderByDesc('answers_count')
                    ->orderByDesc('created_at');
                break;
            case 'my_post':
                $query->where('user_id', auth()->id())
                    ->orderByDesc('created_at');
                break;
            default: // latest
                $query->orderByDesc('created_at');
        }

        $questions = $query->paginate(10)->appends(['sort' => $sort]);

        if ($request->ajax() || $request->boolean('partial')) {
            return response()->view('forum._list', compact('questions'));
        }

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

        return redirect()->route('forum.index', ['sort' => 'my_post'])->with('success', 'Successfully created!');
    }

    public function show(ForumQuestion $forumQuestion)
    {
        $forumQuestion->load(['user:id,name']);
        // Eager load answers (flat) with user and votes sum
        $answers = ForumAnswer::where('forum_question_id', $forumQuestion->getKey())
            ->with('user:id,name')
            ->withSum('votes as votes_sum', 'value')
            ->orderBy('forum_answer_id')
            ->get();
        $answers_count = $answers->count();
        $votes_sum = $forumQuestion->votes()->whereNull('forum_answer_id')->sum('value');
        $my_vote = (int) ($forumQuestion->votes()
            ->whereNull('forum_answer_id')
            ->where('user_id', auth()->id())
            ->value('value') ?? 0);
        $question = $forumQuestion;
        // store previous URL to enable consistent back navigation
        if (url()->previous() !== route('forum.edit', $forumQuestion->getKey()) &&
            url()->previous() !== route('forum.show', $forumQuestion->getKey())) {
            $this->storeReturnTo();
        }
        // Build tree structure for nested answers
        $byParent = [];
        foreach ($answers as $a) {
            $byParent[$a->parent_id ?? 0][] = $a;
        }
        $nestedAnswers = $this->buildAnswerTree($byParent, 0, 0);
        return view('forum.show', [
            'question' => $question,
            'answersTree' => $nestedAnswers,
            'answers_count' => $answers_count,
            'votes_sum' => $votes_sum,
            'my_vote' => $my_vote,
        ]);
    }

    public function storeAnswer(Request $request, ForumQuestion $forumQuestion)
    {
        $request->validate([
            'answer_content' => 'required|string|min:1'
        ]);
        $parentId = $request->input('parent_id');
        if ($parentId) {
            $parent = ForumAnswer::where('forum_answer_id', $parentId)
                ->where('forum_question_id', $forumQuestion->getKey())
                ->first();
            if (!$parent) {
                return redirect()->route('forum.show', $forumQuestion->getKey())
                    ->with('error', 'Invalid parent answer');
            }
        }
        $answer = ForumAnswer::create([
            'forum_question_id' => $forumQuestion->getKey(),
            'user_id' => auth()->id(),
            'answer_content' => $request->answer_content,
            'parent_id' => $parentId,
        ]);
        if ($request->ajax()) {
            $depth = $this->computeAnswerDepth($answer);
            $html = view('forum._answer', [
                'node' => [
                    'model' => $answer->load('user:id,name')->loadSum('votes as votes_sum', 'value'),
                    'depth' => $depth,
                    'children' => []
                ]
            ])->render();
            return response()->json([
                'ok' => true,
                'answer_id' => $answer->getKey(),
                'parent_id' => $parentId,
                'depth' => $depth,
                'html' => $html,
            ], 201);
        }
        return redirect()->route('forum.show', $forumQuestion->getKey())
            ->with('success-answer', 'Answer posted successfully');
    }

    public function voteUp(ForumQuestion $forumQuestion)
    {
        $current = $this->toggleVote($forumQuestion, 1);
        if (request()->ajax() || request()->wantsJson()) {
            $sum = (int) $forumQuestion->votes()->whereNull('forum_answer_id')->sum('value');
            return response()->json(['votes_sum' => $sum, 'my_vote' => $current]);
        }
        return back();
    }

    public function voteDown(ForumQuestion $forumQuestion)
    {
        $current = $this->toggleVote($forumQuestion, -1);
        if (request()->ajax() || request()->wantsJson()) {
            $sum = (int) $forumQuestion->votes()->whereNull('forum_answer_id')->sum('value');
            return response()->json(['votes_sum' => $sum, 'my_vote' => $current]);
        }
        return back();
    }

    protected function toggleVote(ForumQuestion $forumQuestion, int $value): int
    {
        $vote = $forumQuestion->votes()
            ->where('user_id', auth()->id())
            ->whereNull('forum_answer_id')
            ->first();
        if ($vote) {
            if ((int)$vote->value === $value) {
                // same click toggles off
                $vote->delete();
                return 0;
            }
            $vote->update(['value' => $value]);
            return $value;
        }
        $forumQuestion->votes()->create([
            'user_id' => auth()->id(),
            'value' => $value,
        ]);
        return $value;
    }

    public function voteAnswerUp(ForumQuestion $forumQuestion, ForumAnswer $forumAnswer)
    {
        $current = $this->toggleAnswerVote($forumQuestion, $forumAnswer, 1);
        if (request()->ajax() || request()->wantsJson()) {
            $sum = (int) $forumAnswer->votes()->sum('value');
            return response()->json(['answer_id' => $forumAnswer->getKey(), 'votes_sum' => $sum, 'my_vote' => $current]);
        }
        return back();
    }

    public function voteAnswerDown(ForumQuestion $forumQuestion, ForumAnswer $forumAnswer)
    {
        $current = $this->toggleAnswerVote($forumQuestion, $forumAnswer, -1);
        if (request()->ajax() || request()->wantsJson()) {
            $sum = (int) $forumAnswer->votes()->sum('value');
            return response()->json(['answer_id' => $forumAnswer->getKey(), 'votes_sum' => $sum, 'my_vote' => $current]);
        }
        return back();
    }

    protected function toggleAnswerVote(ForumQuestion $forumQuestion, ForumAnswer $forumAnswer, int $value): int
    {
        // ensure answer belongs to question
        if ($forumAnswer->forum_question_id !== $forumQuestion->getKey()) {
            abort(404);
        }
        $vote = $forumAnswer->votes()->where('user_id', auth()->id())->first();
        if ($vote) {
            if ((int)$vote->value === $value) {
                $vote->delete();
                return 0;
            }
            $vote->update(['value' => $value]);
            return $value;
        }
        // IMPORTANT: Do NOT set forum_question_id here to avoid violating
        // unique(user_id, forum_question_id) when the user already voted on the question.
        // The relation will set forum_answer_id automatically.
        $forumAnswer->votes()->create([
            'user_id' => auth()->id(),
            'value' => $value,
        ]);
        return $value;
    }

    protected function buildAnswerTree(array $byParent, int $parentId, int $depth): array
    {
        $result = [];
        foreach ($byParent[$parentId] ?? [] as $answer) {
            $result[] = [
                'model' => $answer,
                'depth' => $depth,
                'children' => $this->buildAnswerTree($byParent, $answer->getKey(), $depth + 1)
            ];
        }
        return $result;
    }

    public function edit(ForumQuestion $forumQuestion)
    {
        $this->storeReturnTo();
        $question = $forumQuestion;
        return view('forum.edit', compact('question'));
    }

    public function update(Request $request, ForumQuestion $forumQuestion)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $forumQuestion->update($request->only('title', 'content'));

        $redirectTo = session('forum_return_to', route('forum.index'));
        $this->deleteReturnTo();

        return redirect($redirectTo)->with('success', 'Successfully updated!');
    }

    public function destroy(ForumQuestion $forumQuestion)
    {
        $forumQuestion->delete();
        return back()->with('success', 'Successfully deleted!');
    }

    public function storeReturnTo()
    {
        session(['forum_return_to' => url()->previous()]);
    }
    public function deleteReturnTo()
    {
        session()->forget('forum_return_to');
    }

    public function back()
    {
        $url = session('forum_return_to', route('forum.index'));
        $this->deleteReturnTo();
        return redirect($url);
    }

    public function destroyAnswer(ForumQuestion $forumQuestion, ForumAnswer $forumAnswer)
    {
        // ensure the answer belongs to the question
        if ($forumAnswer->forum_question_id !== $forumQuestion->getKey()) {
            abort(404);
        }
        // authorization: only owner can delete
        if ($forumAnswer->user_id !== auth()->id()) {
            abort(403);
        }
        $this->deleteAnswerRecursive($forumAnswer);
        if (request()->ajax()) {
            return response()->json(['ok' => true, 'deleted' => true, 'answer_id' => $forumAnswer->getKey()]);
        }
        return redirect()->route('forum.show', $forumQuestion->getKey())
            ->with('success-answer', 'Answer deleted');
    }

    protected function deleteAnswerRecursive(ForumAnswer $answer): void
    {
        // load children lazily to avoid large eager loads
        $children = $answer->children()->get();
        foreach ($children as $child) {
            $this->deleteAnswerRecursive($child);
        }
        $answer->delete();
    }

    protected function computeAnswerDepth(ForumAnswer $answer): int
    {
        $depth = 0;
        $current = $answer;
        while ($current->parent_id) {
            $depth++;
            $current = ForumAnswer::find($current->parent_id);
            if (!$current) break;
        }
        return $depth;
    }
}
