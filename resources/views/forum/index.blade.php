@extends('layouts.app')

@push('styles')
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
</style>
@endpush

@section('content')

<div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-center text-3xl font-extrabold tracking-tight text-gray-900">FORUM Q&A</h1>
        <p class="text-center text-gray-600 mt-2">Đặt câu hỏi và thảo luận với cộng đồng</p>
    </div>

    <!-- Search and Filter Bar -->
    <div class="mb-6 bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('forum.index') }}" class="space-y-4">
            <!-- Search Box -->
            <div class="relative">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    🔍 Tìm kiếm trong diễn đàn
                </label>
                <input 
                    type="text" 
                    name="search" 
                    id="search"
                    value="{{ request('search') }}"
                    placeholder="Tìm theo tiêu đề, nội dung, tác giả..."
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                <svg class="absolute left-3 top-11 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Actions Row -->
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button 
                        type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Tìm kiếm
                    </button>
                    
                    @if(request('search'))
                        <a 
                            href="{{ route('forum.index', ['sort' => request('sort')]) }}"
                            class="inline-flex items-center px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-all duration-200"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Xóa tìm kiếm
                        </a>
                    @endif
                </div>

                <!-- Sort Dropdown -->
                <div class="flex items-center gap-2">
                    <label for="sort" class="text-sm text-gray-600">Sắp xếp:</label>
                    <select id="sort" name="sort" class="w-44 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="latest" @selected(request('sort','latest') === 'latest')>Mới nhất</option>
                        <option value="votes" @selected(request('sort') === 'votes')>Nhiều vote</option>
                        <option value="answers" @selected(request('sort') === 'answers')>Nhiều câu trả lời</option>
                        <option value="my_post" @selected(request('sort') === 'my_post')>Bài của tôi</option>
                    </select>
                </div>
            </div>

            <!-- Search Results Info -->
            @if(request('search'))
                <div class="flex items-center space-x-2 text-sm text-gray-600 pt-2 border-t">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>
                        Tìm kiếm "<strong class="text-indigo-600">{{ request('search') }}</strong>"
                        - Tìm thấy <strong>{{ $questions->total() }}</strong> kết quả
                    </span>
                </div>
            @endif
        </form>
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
        Đăng bài mới
    </a>
@endcan
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sortSelect = document.querySelector('select[name="sort"]');
    const searchInput = document.querySelector('input[name="search"]');
    const listEl = document.getElementById('forum-list');

    // Get current search parameter
    function getCurrentSearch() {
        return searchInput ? searchInput.value : '';
    }

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
            const currentSearch = getCurrentSearch();
            let url = `{{ route('forum.index') }}` + `?sort=${encodeURIComponent(this.value)}`;
            
            // Preserve search parameter
            if (currentSearch) {
                url += `&search=${encodeURIComponent(currentSearch)}`;
            }
            
            fetchList(url);
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.set('sort', this.value);
            if (currentSearch) {
                newUrl.searchParams.set('search', currentSearch);
            }
            history.replaceState({}, '', newUrl);
        });
    }

    // Delegate pagination clicks to load via AJAX
    document.addEventListener('click', function(e){
        const a = e.target.closest('#forum-list .pagination a');
        if (a) {
            e.preventDefault();
            const currentSearch = getCurrentSearch();
            const newUrl = new URL(a.href);
            
            // Ensure both sort and search are preserved
            newUrl.searchParams.set('sort', sortSelect ? sortSelect.value : (new URL(window.location.href)).searchParams.get('sort') || 'latest');
            if (currentSearch) {
                newUrl.searchParams.set('search', currentSearch);
            }
            
            fetchList(newUrl.toString());
            history.replaceState({}, '', newUrl);
        }
    });
});
</script>
@endpush

