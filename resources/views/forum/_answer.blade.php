@php
    $answer = $node['model'];
    $depth = $node['depth'];
    $children = $node['children'];
    $indent = min($depth * 4, 32); // cap indentation
    $mlClass = 'ml-['.$indent.'px]'; // arbitrary value via JIT (Tailwind needs enabled arbitrary values)
    $myVote = (int)($answer->votes()->where('user_id', auth()->id())->value('value') ?? 0);
    $votesSum = (int)$answer->votes_sum;
@endphp
@php
    // visual tweaks: for nested answers add subtle left bar and lighter background
    $isChild = $depth > 0;
    $cardClasses = 'rounded-lg border bg-white p-4 shadow-sm';
    $cardClasses .= $isChild ? ' border-l-4 border-indigo-200 bg-indigo-50/70' : ' border-gray-200';
@endphp
<div class="relative" style="margin-left: {{$indent}}px" data-answer-id="{{$answer->getKey()}}" data-depth="{{$depth}}">
    <div class="{{$cardClasses}}">
        <!-- {{ $answer }} -->
        <div class="flex items-start gap-4">
            <div class="flex flex-col items-center" 
                 data-answer-vote-box
                 data-up-url="{{ route('forum.answer.vote.up', [$answer->forum_question_id, $answer->getKey()]) }}"
                 data-down-url="{{ route('forum.answer.vote.down', [$answer->forum_question_id, $answer->getKey()]) }}"
                 data-csrf="{{ csrf_token() }}"
                 data-my-vote="{{$myVote}}"
                 data-answer-id="{{$answer->getKey()}}">
                <button type="button" class="answer-vote-up inline-flex items-center rounded px-2 py-1 text-xs font-semibold shadow focus:outline-none focus:ring-2">▲</button>
                <span class="answer-vote-count my-0.5 text-base font-semibold">{{$votesSum}}</span>
                <button type="button" class="answer-vote-down inline-flex items-center rounded px-2 py-1 text-xs font-semibold shadow focus:outline-none focus:ring-2">▼</button>
            </div>
            <div class="flex-1">
                <div class="mb-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500">
                    <span>👤 {{ optional($answer->user)->name ?? 'Unknown' }}</span>
                    <span>
                        🕓 {{ $answer->created_at->diffForHumans() }}
                    </span>
                </div>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $answer->answer_content }}</p>
                <div class="mt-3 flex items-center gap-2">
                    <button type="button" class="toggle-reply inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500" data-target="#reply-form-{{$answer->getKey()}}">Reply</button>
                    @if($answer->user_id === auth()->id() || auth()->user()->can('admin'))
                        <form method="POST" action="{{ route('forum.answer.destroy', [$answer->forum_question_id, $answer->getKey()]) }}" onsubmit="return confirm('Delete this answer and its replies?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-2 py-1 text-xs font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">Delete</button>
                        </form>
                    @endif
                </div>
                <div id="reply-form-{{$answer->getKey()}}" class="hidden mt-3">
                    <form method="POST" action="{{ route('forum.answer.store', $answer->forum_question_id) }}" class="space-y-2 js-answer-form" data-parent-id="{{$answer->getKey()}}">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{$answer->getKey()}}" />
                        <textarea name="answer_content" rows="3" required placeholder="Your reply" class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-xs text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" class="cancel-reply inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-200">Cancel</button>
                            <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(!empty($children))
        <div class="mt-3 space-y-4 children-wrapper">
            @foreach($children as $child)
                @include('forum._answer', ['node'=>$child])
            @endforeach
        </div>
    @endif
</div>
