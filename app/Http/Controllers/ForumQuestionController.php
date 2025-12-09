<?php

namespace App\Http\Controllers;

use App\Models\ForumQuestion;
use App\Models\Vote;
use App\Models\ForumAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Concerns\ForumQuestionData;

class ForumQuestionController extends Controller
{
    use ForumQuestionData;
    public function __construct()
    {
        $this->middleware('auth');
        // map policy methods to controller resource methods, expects route param {forumQuestion}
        $this->authorizeResource(ForumQuestion::class, 'forumQuestion');
    }
    public function index(Request $request)
    {
        [$query, $sort] = $this->questionBaseQuery($request);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(),
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/forum'), $imageName);
            $data['image_path'] = 'images/forum/' . $imageName;
        }

        // Handle tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $tags = array_filter($tags); // Remove empty values
            $data['tags'] = $tags;
        }

        ForumQuestion::create($data);

        return redirect()->route('forum.index', ['sort' => 'my_post'])->with('success', 'Successfully created!');
    }

    public function show(ForumQuestion $forumQuestion)
    {
        $data = $this->assembleShowData($forumQuestion);
        $question = $data['question'];
        $answers_count = $data['answers_count'];
        $votes_sum = $data['votes_sum'];
        $my_vote = $data['my_vote'];
        $nestedAnswers = $data['answersTree'];
        // store previous URL to enable consistent back navigation
        if (url()->previous() !== route('forum.edit', $forumQuestion->getKey()) &&
            url()->previous() !== route('forum.show', $forumQuestion->getKey())) {
            $this->storeReturnTo();
        }
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
        $this->authorize('create', \App\Models\ForumAnswer::class);
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
            $answersCount = ForumAnswer::where('forum_question_id', $forumQuestion->getKey())->count();
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
                'answers_count' => $answersCount,
            ], 201);
        }
        return redirect()->route('forum.show', $forumQuestion->getKey())
            ->with('success-answer', 'Answer posted successfully');
    }

    public function voteUp(ForumQuestion $forumQuestion)
    {
        $current = $this->toggleQuestionVote($forumQuestion, 1);
        if (request()->ajax() || request()->wantsJson()) {
            $sum = (int) $forumQuestion->votes()->whereNull('forum_answer_id')->sum('value');
            return response()->json(['votes_sum' => $sum, 'my_vote' => $current]);
        }
        return back();
    }

    public function voteDown(ForumQuestion $forumQuestion)
    {
        $current = $this->toggleQuestionVote($forumQuestion, -1);
        if (request()->ajax() || request()->wantsJson()) {
            $sum = (int) $forumQuestion->votes()->whereNull('forum_answer_id')->sum('value');
            return response()->json(['votes_sum' => $sum, 'my_vote' => $current]);
        }
        return back();
    }


    public function voteAnswerUp(ForumQuestion $forumQuestion, ForumAnswer $forumAnswer)
    {
        $current = $this->toggleAnswerVote($forumAnswer, $forumQuestion, 1);
        if (request()->ajax() || request()->wantsJson()) {
            $sum = (int) $forumAnswer->votes()->sum('value');
            return response()->json(['answer_id' => $forumAnswer->getKey(), 'votes_sum' => $sum, 'my_vote' => $current]);
        }
        return back();
    }

    public function voteAnswerDown(ForumQuestion $forumQuestion, ForumAnswer $forumAnswer)
    {
        $current = $this->toggleAnswerVote($forumAnswer, $forumQuestion, -1);
        if (request()->ajax() || request()->wantsJson()) {
            $sum = (int) $forumAnswer->votes()->sum('value');
            return response()->json(['answer_id' => $forumAnswer->getKey(), 'votes_sum' => $sum, 'my_vote' => $current]);
        }
        return back();
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
        // authorization via policy (admin: all, others: owner only)
        $this->authorize('delete', $forumAnswer);
        $this->deleteAnswerRecursive($forumAnswer);
        $answersCount = ForumAnswer::where('forum_question_id', $forumQuestion->getKey())->count();
        if (request()->ajax()) {
            return response()->json([
                'ok' => true,
                'deleted' => true,
                'answer_id' => $forumAnswer->getKey(),
                'answers_count' => $answersCount,
            ]);
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

}
