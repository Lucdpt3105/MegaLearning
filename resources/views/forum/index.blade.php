@extends('layouts.app')

@section('content')

<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
    <div class="mb-6 grid grid-cols-3 items-center">
        <div></div>
        <h1 class="text-center text-3xl font-extrabold tracking-tight text-gray-900">FORUM</h1>
        <div class="flex justify-end">
            <form method="GET" action="{{ route('forum.index') }}" class="inline-flex items-center gap-2">
                <label for="sort" class="text-sm text-gray-600">Sort</label>
                <select id="sort" name="sort" class="w-44 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="latest"   @selected(request('sort','latest') === 'latest')>Latest</option>
                        <option value="votes"    @selected(request('sort') === 'votes')>Most votes</option>
                        <option value="answers" @selected(request('sort') === 'answers')>Most answers</option>
                        <option value="my_post"  @selected(request('sort') === 'my_post')>My posts</option>
                </select>
                @foreach(request()->except('sort','page') as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700 shadow-sm">{{ session('success') }}</div>
    @endif

    <div id="forum-list" class="space-y-4">
        @include('forum._list', ['questions' => $questions])
    </div>
</div>

@can('create', App\Models\ForumQuestion::class)
    <a href="{{ route('forum.create') }}"
       class="fixed right-6 top-1/2 z-40 inline-flex -translate-y-1/2 items-center gap-2 rounded-full bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg ring-1 ring-indigo-500/20 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        New post
    </a>
@endcan
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sortSelect = document.querySelector('select[name="sort"]');
    const listEl = document.getElementById('forum-list');

    async function fetchList(url) {
        try {
            const u = new URL(url, window.location.origin);
            u.searchParams.set('partial', '1');
            const res = await fetch(u.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) throw new Error('Network error');
            const html = await res.text();
            listEl.innerHTML = html;
        } catch (e) {
            console.error(e);
        }
    }

        if (sortSelect) {
            sortSelect.addEventListener('change', function (e) {
                const url = `{{ route('forum.index') }}` + `?sort=${encodeURIComponent(this.value)}`;
                fetchList(url);
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set('sort', this.value);
                history.replaceState({}, '', newUrl);
            });
        }

    // Delegate pagination clicks to load via AJAX
    document.addEventListener('click', function(e){
        const a = e.target.closest('#forum-list .pagination a');
        if (a) {
            e.preventDefault();
            fetchList(a.href);
            const newUrl = new URL(a.href);
            newUrl.searchParams.set('sort', sortSelect ? sortSelect.value : (new URL(window.location.href)).searchParams.get('sort') || 'latest');
            history.replaceState({}, '', newUrl);
        }
    });
});
</script>
@endpush

