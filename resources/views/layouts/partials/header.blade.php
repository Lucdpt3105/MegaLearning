<header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10 shadow-sm">
    <!-- Search Bar -->
    <div class="flex-1 max-w-2xl" id="search-container">
        <div class="relative">
            <input 
                type="text" 
                id="global-search-input"
                placeholder="Search for quizzes, courses, or topics..."
                class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white transition"
                autocomplete="off"
            >
            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>

            <!-- Loading Spinner -->
            <div id="search-loading" class="absolute right-4 top-3.5 hidden">
                <svg class="animate-spin h-5 w-5 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <!-- Search Results Dropdown -->
            <div 
                id="search-results-dropdown"
                class="absolute top-full mt-2 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-96 overflow-y-auto z-50 hidden"
            >
                <!-- Results will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <!-- Right Section -->
    <div class="flex items-center space-x-4 ml-6">
        <!-- Notifications -->
        <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
        </button>

        <!-- Messages -->
        <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-green-500 rounded-full"></span>
        </button>

        <!-- Divider -->
        <div class="w-px h-8 bg-gray-200"></div>

        <!-- User Profile -->
        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 hover:bg-gray-50 rounded-lg px-3 py-2 transition">
            @auth
                @if(Auth::user()->avatar)
                    <img 
                        src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                        alt="{{ Auth::user()->name }}" 
                        class="w-10 h-10 rounded-full ring-2 ring-purple-100 object-cover"
                    >
                @else
                    <div class="w-10 h-10 rounded-full ring-2 ring-purple-100 bg-linear-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="text-left">
                    <p class="font-semibold text-sm text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->roles->first()?->name ?? 'User') }}</p>
                </div>
            @else
                <img 
                    src="https://ui-avatars.com/api/?name=Guest&background=6366f1&color=fff&bold=true" 
                    alt="Guest" 
                    class="w-10 h-10 rounded-full ring-2 ring-purple-100"
                >
                <div class="text-left">
                    <p class="font-semibold text-sm text-gray-800">Guest</p>
                    <p class="text-xs text-gray-500">Not logged in</p>
                </div>
            @endauth
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </a>
    </div>
</header>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('global-search-input');
    const searchLoading = document.getElementById('search-loading');
    const searchResults = document.getElementById('search-results-dropdown');
    let searchTimeout = null;

    // Handle search input
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        
        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // Hide results if query is empty
        if (query.length === 0) {
            searchResults.classList.add('hidden');
            return;
        }

        // Debounce search for 300ms
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!document.getElementById('search-container').contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });

    // Close dropdown on Escape key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            searchResults.classList.add('hidden');
        }
    });

    // Show dropdown on focus if there's text
    searchInput.addEventListener('focus', function(e) {
        if (e.target.value.trim().length > 0 && searchResults.innerHTML !== '') {
            searchResults.classList.remove('hidden');
        }
    });

    function performSearch(query) {
        searchLoading.classList.remove('hidden');

        fetch(`{{ route('search') }}?query=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            searchLoading.classList.add('hidden');
            
            if (data.success) {
                displayResults(data.results, data.total, query);
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            searchLoading.classList.add('hidden');
        });
    }

    function displayResults(results, total, query) {
        if (total === 0) {
            searchResults.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    No results found for "${escapeHtml(query)}"
                </div>
            `;
            searchResults.classList.remove('hidden');
            return;
        }

        let html = '';

        // Exams Section
        if (results.exams && results.exams.length > 0) {
            html += `<div class="border-b border-gray-100">
                <div class="px-4 py-2 bg-gray-50 text-xs font-semibold text-gray-600 uppercase">Exams / Quizzes</div>`;
            results.exams.forEach(exam => {
                html += `
                    <a href="${exam.url}" class="block px-4 py-3 hover:bg-purple-50 transition">
                        <div class="font-medium text-gray-800">${escapeHtml(exam.title)}</div>
                        <div class="text-sm text-gray-500">${escapeHtml(exam.subject)}</div>
                    </a>`;
            });
            html += `</div>`;
        }

        // Subjects Section
        if (results.subjects && results.subjects.length > 0) {
            html += `<div class="border-b border-gray-100">
                <div class="px-4 py-2 bg-gray-50 text-xs font-semibold text-gray-600 uppercase">Subjects / Courses</div>`;
            results.subjects.forEach(subject => {
                html += `
                    <a href="${subject.url}" class="block px-4 py-3 hover:bg-purple-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="font-medium text-gray-800">${escapeHtml(subject.title)}</div>
                            <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded">${escapeHtml(subject.code)}</span>
                        </div>
                        <div class="text-sm text-gray-500">Teacher: ${escapeHtml(subject.teacher)}</div>
                    </a>`;
            });
            html += `</div>`;
        }

        // Topics Section
        if (results.topics && results.topics.length > 0) {
            html += `<div class="border-b border-gray-100">
                <div class="px-4 py-2 bg-gray-50 text-xs font-semibold text-gray-600 uppercase">Topics</div>`;
            results.topics.forEach(topic => {
                html += `
                    <a href="${topic.url}" class="block px-4 py-3 hover:bg-purple-50 transition">
                        <div class="font-medium text-gray-800">${escapeHtml(topic.title)}</div>
                        <div class="text-sm text-gray-500">${escapeHtml(topic.subject)}</div>
                    </a>`;
            });
            html += `</div>`;
        }

        // Documents Section
        if (results.documents && results.documents.length > 0) {
            html += `<div class="border-b border-gray-100">
                <div class="px-4 py-2 bg-gray-50 text-xs font-semibold text-gray-600 uppercase">Documents</div>`;
            results.documents.forEach(document => {
                html += `
                    <a href="${document.url}" class="block px-4 py-3 hover:bg-purple-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="font-medium text-gray-800">${escapeHtml(document.title)}</div>
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded uppercase">${escapeHtml(document.file_type)}</span>
                        </div>
                        <div class="text-sm text-gray-500">${escapeHtml(document.subject)}</div>
                    </a>`;
            });
            html += `</div>`;
        }

        // Forum Questions Section
        if (results.forum_questions && results.forum_questions.length > 0) {
            html += `<div>
                <div class="px-4 py-2 bg-gray-50 text-xs font-semibold text-gray-600 uppercase">Forum Questions</div>`;
            results.forum_questions.forEach(question => {
                html += `
                    <a href="${question.url}" class="block px-4 py-3 hover:bg-purple-50 transition">
                        <div class="font-medium text-gray-800">${escapeHtml(question.title)}</div>
                        <div class="text-sm text-gray-500">By ${escapeHtml(question.author)}</div>
                    </a>`;
            });
            html += `</div>`;
        }

        searchResults.innerHTML = html;
        searchResults.classList.remove('hidden');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>
@endpush
