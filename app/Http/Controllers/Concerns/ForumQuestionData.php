<?php

namespace App\Http\Controllers\Concerns;

use App\Models\ForumAnswer;
use App\Models\ForumQuestion;
use Illuminate\Http\Request;

trait ForumQuestionData
{
    protected function buildAnswerTree(array $grouped, int $parentId, int $depth): array
    {
        $result = [];
        foreach ($grouped[$parentId] ?? [] as $answer) {
            $result[] = [
                'model' => $answer,
                'depth' => $depth,
                'children' => $this->buildAnswerTree($grouped, $answer->getKey(), $depth + 1),
            ];
        }
        return $result;
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

    protected function questionBaseQuery(Request $request)
    {
        $sort = $request->query('sort', 'latest');
        $query = ForumQuestion::query()
            ->with(['user:id,name', 'answers' => function($q) {
                $q->with('user:id,name')->whereNull('parent_id')->latest()->take(3);
            }])
            ->withSum(['votes as votes_sum' => function ($q) { $q->whereNull('forum_answer_id'); }], 'value')
            ->withCount('answers as answers_count');
        
        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        switch ($sort) {
            case 'votes':
                $query->orderByDesc('votes_sum')->orderByDesc('created_at');
                break;
            case 'answers':
                $query->orderByDesc('answers_count')->orderByDesc('created_at');
                break;
            case 'my_post':
                $query->where('user_id', auth()->id())->orderByDesc('created_at');
                break;
            default:
                $query->orderByDesc('created_at');
        }
        return [$query, $sort];
    }

    protected function toggleQuestionVote(ForumQuestion $question, int $value): int
    {
        $vote = $question->votes()->where('user_id', auth()->id())->whereNull('forum_answer_id')->first();
        if ($vote) {
            if ((int)$vote->value === $value) { $vote->delete(); return 0; }
            $vote->update(['value' => $value]);
            return $value;
        }
        $question->votes()->create(['user_id' => auth()->id(), 'value' => $value]);
        return $value;
    }

    protected function toggleAnswerVote(ForumAnswer $answer, ForumQuestion $question, int $value): int
    {
        if ($answer->forum_question_id !== $question->getKey()) abort(404);
        $vote = $answer->votes()->where('user_id', auth()->id())->first();
        if ($vote) {
            if ((int)$vote->value === $value) { $vote->delete(); return 0; }
            $vote->update(['value' => $value]);
            return $value;
        }
        $answer->votes()->create(['user_id' => auth()->id(), 'value' => $value]);
        return $value;
    }

    protected function assembleShowData(ForumQuestion $question): array
    {
        $question->load(['user:id,name']);
        $answers = ForumAnswer::where('forum_question_id', $question->getKey())
            ->with('user:id,name')
            ->withSum('votes as votes_sum', 'value')
            ->orderBy('forum_answer_id')
            ->get();
        $byParent = [];
        foreach ($answers as $a) { $byParent[$a->parent_id ?? 0][] = $a; }
        $tree = $this->buildAnswerTree($byParent, 0, 0);
        $questionVotes = (int)$question->votes()->whereNull('forum_answer_id')->sum('value');
        $myVote = (int)($question->votes()->whereNull('forum_answer_id')->where('user_id', auth()->id())->value('value') ?? 0);
        return [
            'question' => $question,
            'answersTree' => $tree,
            'answers_count' => $answers->count(),
            'votes_sum' => $questionVotes,
            'my_vote' => $myVote,
        ];
    }
}
