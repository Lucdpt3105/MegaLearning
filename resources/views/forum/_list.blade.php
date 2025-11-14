@forelse($questions as $q)
<!-- {{ $q }} -->
<article class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md">
    <header class="mb-2">
        <h2 class="text-xl font-semibold text-gray-900">
            <a href="{{ route('forum.show', $q->forum_question_id) }}" class="hover:text-indigo-600">{{ $q->title }}</a>
        </h2>
    </header>
    <p class="text-gray-700">{{ \Illuminate\Support\Str::limit($q->content, 180) }}</p>
    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-500">
        <span>👤 {{ optional($q->user)->name }}</span>
        <span>👍 {{ $q->votes_sum ?? 0 }}</span>
        <span>💬 {{ $q->answers_count ?? 0 }}</span>
        <span>🕓 {{ $q->created_at->diffForHumans() }}</span>
        @if($q->updated_at)
            <span>🔄 edited: {{ $q->updated_at->diffForHumans() }}</span>
        @endif
        
    </div>
    <div class="mt-4 flex items-center gap-2">
        @can('update', $q)
            <a href="{{ route('forum.edit', $q->forum_question_id) }}" class="inline-flex items-center rounded-md bg-yellow-500 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">edit</a>
        @endcan
        @can('delete', $q)
            <form action="{{ route('forum.destroy', $q->forum_question_id) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                @csrf @method('DELETE')
                <button class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">declete</button>
            </form>
        @endcan
    </div>
</article>
@empty
<p class="text-center text-gray-500">No posts yet.</p>
@endforelse

@if(method_exists($questions, 'links'))
    <div class="mt-4">
        {{ $questions->appends(request()->query())->links() }}
    </div>
@endif
