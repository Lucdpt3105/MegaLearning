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

<div class="bg-gray-50 min-h-screen py-6">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-12 gap-6">
            <!-- Left Sidebar -->
            <aside class="col-span-3 space-y-4">
                <!-- Trending Topics -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Trending Topics
                    </h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-sm text-gray-600 hover:text-indigo-600 flex items-center">
                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                            Laravel Best Practices
                        </a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-indigo-600 flex items-center">
                            <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                            Vue.js 3 Tips
                        </a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-indigo-600 flex items-center">
                            <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                            Database Optimization
                        </a></li>
                        <li><a href="#" class="text-sm text-gray-600 hover:text-indigo-600 flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            API Development
                        </a></li>
                    </ul>
                </div>

                <!-- Popular Tags -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4">Popular Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-full hover:bg-indigo-100 cursor-pointer">Laravel</span>
                        <span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full hover:bg-green-100 cursor-pointer">PHP</span>
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full hover:bg-blue-100 cursor-pointer">JavaScript</span>
                        <span class="px-3 py-1 bg-purple-50 text-purple-700 text-xs font-medium rounded-full hover:bg-purple-100 cursor-pointer">Database</span>
                        <span class="px-3 py-1 bg-pink-50 text-pink-700 text-xs font-medium rounded-full hover:bg-pink-100 cursor-pointer">API</span>
                        <span class="px-3 py-1 bg-orange-50 text-orange-700 text-xs font-medium rounded-full hover:bg-orange-100 cursor-pointer">Frontend</span>
                    </div>
                </div>

                <!-- I'm Following -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4">I'm Following 
                        <span class="text-sm font-normal text-gray-500">16</span>
                    </h3>
                    <div class="grid grid-cols-5 gap-2">
                        @php
                            $followingSeeds = [42, 73, 91, 28, 56, 19, 84, 37, 65, 11, 94, 23, 67, 45, 88, 31];
                        @endphp
                        @foreach($followingSeeds as $seed)
                        <div class="relative group cursor-pointer">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $seed }}" 
                                 alt="User" 
                                 class="w-10 h-10 rounded-full ring-2 ring-white hover:ring-indigo-500 transition">
                            <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="col-span-6">
                <!-- Create Post Card -->
                @can('create', App\Models\ForumQuestion::class)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4">
                    <div class="flex items-center gap-3">
                        @php
                            $currentUserId = auth()->user()->id ?? 1;
                            $currentUserName = auth()->user()->name ?? 'User';
                        @endphp
                        <img src="https://randomuser.me/api/portraits/{{ $currentUserId % 2 == 0 ? 'women' : 'men' }}/{{ $currentUserId }}.jpg" 
                             alt="{{ $currentUserName }}" 
                             class="w-10 h-10 rounded-full shrink-0 ring-2 ring-gray-100 object-cover">
                        <a href="{{ route('forum.create') }}" class="flex-1 bg-gray-50 hover:bg-gray-100 rounded-full px-5 py-3 text-gray-500 cursor-pointer transition">
                            Share what's on your mind, {{ $currentUserName }}...
                        </a>
                    </div>
                    <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('forum.create') }}" class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Write Article
                        </a>
                        <button class="flex items-center gap-2 text-gray-600 hover:text-green-600 text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Photo
                        </button>
                        <button class="flex items-center gap-2 text-gray-600 hover:text-red-600 text-sm font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Video
                        </button>
                    </div>
                </div>
                @endcan

                <!-- Filter Tabs -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
                    <div class="flex items-center justify-between p-4 border-b border-gray-100">
                        <div class="flex gap-6">
                            <button class="text-sm font-semibold {{ request('sort', 'latest') === 'latest' ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}" onclick="changeSort('latest')">
                                All Updates
                            </button>
                            <button class="text-sm font-semibold {{ request('sort') === 'votes' ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}" onclick="changeSort('votes')">
                                Most Voted
                            </button>
                            <button class="text-sm font-semibold {{ request('sort') === 'my_post' ? 'text-indigo-600 border-b-2 border-indigo-600 pb-1' : 'text-gray-600 hover:text-gray-900' }}" onclick="changeSort('my_post')">
                                My Posts
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" placeholder="Search Feed..." class="px-4 py-1.5 text-sm border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Posts Feed -->
                <div id="forum-list" class="space-y-4">
                    @include('forum._list', ['questions' => $questions])
                </div>
            </main>

            <!-- Right Sidebar -->
            <aside class="col-span-3 space-y-4">
                <!-- Active Members -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-bold text-gray-900 mb-4">Recently Active Members</h3>
                    <div class="space-y-3">
                        @php
                            $recentUsers = \App\Models\User::latest('updated_at')->take(6)->get();
                            if($recentUsers->isEmpty()) {
                                $recentUsers = collect([
                                    (object)['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'updated_at' => now()->subMinutes(5)],
                                    (object)['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'updated_at' => now()->subMinutes(12)],
                                    (object)['id' => 3, 'name' => 'Mike Johnson', 'email' => 'mike@example.com', 'updated_at' => now()->subMinutes(25)],
                                    (object)['id' => 4, 'name' => 'Sarah Williams', 'email' => 'sarah@example.com', 'updated_at' => now()->subHours(1)],
                                    (object)['id' => 5, 'name' => 'Tom Brown', 'email' => 'tom@example.com', 'updated_at' => now()->subHours(2)],
                                    (object)['id' => 6, 'name' => 'Emily Davis', 'email' => 'emily@example.com', 'updated_at' => now()->subHours(3)],
                                ]);
                            }
                        @endphp
                        @foreach($recentUsers as $user)
                        <div class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 rounded-lg p-2 -mx-2 transition" 
                             onclick="showUserProfile({{ $user->id }}, '{{ $user->name }}', '{{ $user->email ?? '' }}')">
                            <img src="https://randomuser.me/api/portraits/{{ $user->id % 2 == 0 ? 'women' : 'men' }}/{{ $user->id }}.jpg" 
                                 alt="{{ $user->name }}" 
                                 class="w-10 h-10 rounded-full shrink-0 ring-2 ring-gray-100 object-cover">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        </div>
                        @endforeach
                    </div>
                    <a href="#" class="block mt-4 text-center text-sm text-indigo-600 hover:text-indigo-700 font-medium">See All</a>
                </div>

                <!-- Stats Widget -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg p-5 text-white">
                    <h3 class="font-bold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        Community Stats
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-2xl font-bold">{{ $questions->total() }}</p>
                            <p class="text-sm text-indigo-100">Total Posts</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold">{{ \App\Models\User::count() }}</p>
                            <p class="text-sm text-indigo-100">Active Members</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold">{{ \App\Models\ForumAnswer::count() }}</p>
                            <p class="text-sm text-indigo-100">Total Replies</p>
                        </div>
                    </div>
                </div>

                <!-- Following Grid -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-900">Following</h3>
                        <span class="text-sm text-gray-500">{{ $recentUsers->count() }}</span>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($recentUsers->take(8) as $user)
                        <div class="relative group cursor-pointer" 
                             onclick="showUserProfile({{ $user->id }}, '{{ $user->name }}', '{{ $user->email ?? '' }}')">
                            <img src="https://randomuser.me/api/portraits/{{ $user->id % 2 == 0 ? 'women' : 'men' }}/{{ $user->id }}.jpg" 
                                 alt="{{ $user->name }}" 
                                 class="w-full aspect-square rounded-lg ring-2 ring-gray-100 hover:ring-indigo-500 transition object-cover"
                                 title="{{ $user->name }}">
                        </div>
                        @endforeach
                    </div>
                    <a href="#" class="block mt-3 text-center text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All</a>
                </div>
            </aside>
        </div>
    </div>
</div>

<!-- User Profile Modal -->
<div id="userProfileModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="closeUserProfile(event)">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden" onclick="event.stopPropagation()">
        <!-- Cover Image -->
        <div class="h-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 relative">
            <button onclick="closeUserProfile()" class="absolute top-4 right-4 w-8 h-8 bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm rounded-full flex items-center justify-center text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Profile Content -->
        <div class="px-6 pb-6">
            <!-- Avatar -->
            <div class="flex justify-center -mt-16 mb-4">
                <img id="modalUserAvatar" src="" alt="" class="w-32 h-32 rounded-full ring-4 ring-white object-cover shadow-lg">
            </div>

            <!-- User Info -->
            <div class="text-center mb-6">
                <h2 id="modalUserName" class="text-2xl font-bold text-gray-900 mb-1">User Name</h2>
                <p id="modalUserEmail" class="text-gray-500 mb-3">user@example.com</p>
                
                <!-- Stats -->
                <div class="flex justify-center gap-6 mb-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900" id="modalUserPosts">24</p>
                        <p class="text-xs text-gray-500">Posts</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900" id="modalUserFollowers">1.2K</p>
                        <p class="text-xs text-gray-500">Followers</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-900" id="modalUserFollowing">328</p>
                        <p class="text-xs text-gray-500">Following</p>
                    </div>
                </div>

                <!-- Bio -->
                <div class="bg-gray-50 rounded-xl p-4 mb-4">
                    <p id="modalUserBio" class="text-sm text-gray-700 leading-relaxed">
                        Passionate learner and educator. Love sharing knowledge and helping others grow. 📚✨
                    </p>
                </div>

                <!-- Additional Info -->
                <div class="space-y-2 text-left">
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span id="modalUserRole">Student</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span id="modalUserLocation">Vietnam</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span id="modalUserJoined">Joined November 2024</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Follow
                </button>
                <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Message
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
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

    // Delegate pagination clicks to load via AJAX
    document.addEventListener('click', function(e){
        const a = e.target.closest('#forum-list .pagination a');
        if (a) {
            e.preventDefault();
            fetchList(a.href);
            const newUrl = new URL(a.href);
            const currentSort = new URLSearchParams(window.location.search).get('sort') || 'latest';
            newUrl.searchParams.set('sort', currentSort);
            history.replaceState({}, '', newUrl);
        }
    });
});

// Change sort function
function changeSort(sortValue) {
    const url = `{{ route('forum.index') }}?sort=${encodeURIComponent(sortValue)}`;
    const listEl = document.getElementById('forum-list');
    
    fetch(url + '&partial=1', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            listEl.innerHTML = html;
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.set('sort', sortValue);
            history.replaceState({}, '', newUrl);
            location.reload(); // Reload to update active tab
        })
        .catch(e => console.error(e));
}

// User Profile Modal Functions
function showUserProfile(userId, userName, userEmail) {
    const modal = document.getElementById('userProfileModal');
    const genderType = userId % 2 === 0 ? 'women' : 'men';
    
    // Fetch random user data from API
    fetch(`https://randomuser.me/api/?seed=${userId}`)
        .then(res => res.json())
        .then(data => {
            const user = data.results[0];
            
            // Update modal with real data
            document.getElementById('modalUserAvatar').src = `https://randomuser.me/api/portraits/${genderType}/${userId}.jpg`;
            document.getElementById('modalUserName').textContent = userName;
            document.getElementById('modalUserEmail').textContent = userEmail || user.email;
            document.getElementById('modalUserPosts').textContent = Math.floor(Math.random() * 50) + 10;
            document.getElementById('modalUserFollowers').textContent = (Math.random() * 2000 + 100).toFixed(0);
            document.getElementById('modalUserFollowing').textContent = Math.floor(Math.random() * 500) + 50;
            document.getElementById('modalUserBio').textContent = `${user.location.city} based ${userId % 2 === 0 ? 'educator' : 'developer'}. Passionate about learning and sharing knowledge with the community. 📚✨`;
            document.getElementById('modalUserRole').textContent = userId % 3 === 0 ? 'Teacher' : 'Student';
            document.getElementById('modalUserLocation').textContent = `${user.location.city}, ${user.location.country}`;
            document.getElementById('modalUserJoined').textContent = `Joined ${new Date(user.registered.date).toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}`;
            
            // Show modal with animation
            modal.classList.remove('hidden');
            setTimeout(() => modal.querySelector('div > div').classList.add('scale-100'), 10);
        })
        .catch(err => {
            console.error('Error fetching user data:', err);
            // Fallback to basic data
            document.getElementById('modalUserAvatar').src = `https://randomuser.me/api/portraits/${genderType}/${userId}.jpg`;
            document.getElementById('modalUserName').textContent = userName;
            document.getElementById('modalUserEmail').textContent = userEmail;
            modal.classList.remove('hidden');
        });
}

function closeUserProfile(event) {
    if (event && event.target.id !== 'userProfileModal') return;
    const modal = document.getElementById('userProfileModal');
    modal.classList.add('hidden');
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeUserProfile();
    }
});

// Toggle Like Function with AJAX
function toggleLike(questionId) {
    fetch(`/forum/${questionId}/vote/up`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const btn = document.getElementById(`like-btn-${questionId}`);
        const votesCount = document.querySelector(`.votes-count-${questionId}`);
        const votesDisplay = document.querySelector(`.votes-display-${questionId}`);
        
        // Update vote count
        votesCount.textContent = data.votes_sum;
        
        // Update button appearance based on vote status
        if (data.my_vote === 1) {
            // Liked state
            btn.classList.add('bg-blue-50', 'text-blue-600');
            btn.classList.remove('text-gray-600');
            btn.querySelector('span').textContent = 'Liked';
            btn.querySelector('svg').setAttribute('fill', 'currentColor');
        } else {
            // Unliked state
            btn.classList.remove('bg-blue-50', 'text-blue-600');
            btn.classList.add('text-gray-600');
            btn.querySelector('span').textContent = 'Like';
            btn.querySelector('svg').setAttribute('fill', 'none');
        }
        
        // Update votes display with icon
        if (data.votes_sum > 0) {
            votesDisplay.innerHTML = `
                <svg class="w-4 h-4 text-blue-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                </svg>
                <span class="votes-count-${questionId}">${data.votes_sum}</span>
                <span class="ml-1">${Math.abs(data.votes_sum) === 1 ? 'Like' : 'Likes'}</span>
            `;
        } else {
            votesDisplay.innerHTML = `
                <span class="votes-count-${questionId}">0</span>
                <span class="ml-1">Likes</span>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update like. Please try again.');
    });
}

// Toggle Comment Form
function toggleCommentForm(questionId) {
    const form = document.getElementById(`comment-form-${questionId}`);
    const commentsSection = document.getElementById(`comments-section-${questionId}`);
    
    // Show comments section if has comments
    if (commentsSection && !commentsSection.classList.contains('hidden')) {
        // Comments visible, just toggle form
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
            document.getElementById(`quick-comment-${questionId}`).focus();
        } else {
            form.classList.add('hidden');
        }
    } else {
        // Show both comments and form
        if (commentsSection) commentsSection.classList.remove('hidden');
        form.classList.remove('hidden');
        document.getElementById(`quick-comment-${questionId}`).focus();
    }
}

// Cancel Comment
function cancelComment(questionId) {
    const form = document.getElementById(`comment-form-${questionId}`);
    form.classList.add('hidden');
    document.getElementById(`quick-comment-${questionId}`).value = '';
}

// Submit Quick Comment with AJAX
function submitQuickComment(event, questionId) {
    event.preventDefault();
    const form = event.target;
    const textarea = document.getElementById(`quick-comment-${questionId}`);
    const content = textarea.value.trim();
    
    if (!content) {
        alert('Please enter a comment');
        return;
    }
    
    const formData = new FormData(form);
    
    fetch(`/forum/${questionId}/answers`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                try {
                    const json = JSON.parse(text);
                    throw new Error(json.message || 'Failed to post comment');
                } catch(e) {
                    throw new Error('Failed to post comment. Please try again.');
                }
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.ok) {
            // Update comment count
            const commentCount = document.querySelector(`.comments-count-${questionId}`);
            commentCount.textContent = `${data.answers_count} ${data.answers_count === 1 ? 'Comment' : 'Comments'}`;
            
            // Add new comment to the list
            const commentsList = document.getElementById(`comments-list-${questionId}`);
            const commentsSection = document.getElementById(`comments-section-${questionId}`);
            
            if (commentsList && data.html) {
                // Create comment element from response HTML
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;
                const commentElement = tempDiv.firstElementChild;
                
                // Extract user info and create simple comment display
                const userId = {{ auth()->id() }};
                const userName = '{{ auth()->user()->name }}';
                const newComment = document.createElement('div');
                newComment.className = 'flex gap-2 animate-fadeIn';
                newComment.innerHTML = `
                    <img src="https://randomuser.me/api/portraits/${userId % 2 === 0 ? 'women' : 'men'}/${userId}.jpg" 
                         alt="${userName}" 
                         class="w-8 h-8 rounded-full shrink-0 object-cover">
                    <div class="flex-1 bg-gray-50 rounded-lg px-3 py-2">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-semibold text-gray-900">${userName}</span>
                            <span class="text-xs text-gray-500">Just now</span>
                        </div>
                        <p class="text-sm text-gray-700">${content}</p>
                    </div>
                `;
                commentsList.insertBefore(newComment, commentsList.firstChild);
            }
            
            // Show comments section
            if (commentsSection) {
                commentsSection.classList.remove('hidden');
            }
            
            // Clear and hide form
            textarea.value = '';
            cancelComment(questionId);
            
            // Show success message
            const successMsg = document.createElement('div');
            successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            successMsg.innerHTML = `
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Comment posted successfully!</span>
                </div>
            `;
            document.body.appendChild(successMsg);
            setTimeout(() => successMsg.remove(), 3000);
        } else {
            throw new Error('Failed to post comment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Failed to post comment. Please try again.');
    });
}

// Share Post Function
function sharePost(questionId, title) {
    const url = `${window.location.origin}/forum/${questionId}`;
    
    // Try to use Web Share API if available (mobile)
    if (navigator.share) {
        navigator.share({
            title: title,
            text: `Check out this post: ${title}`,
            url: url
        }).catch(err => console.log('Share cancelled'));
    } else {
        // Fallback: Copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            // Show success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-purple-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2';
            notification.innerHTML = `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                </svg>
                <span>Link copied to clipboard!</span>
            `;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }).catch(err => {
            alert('Failed to copy link. Please try manually.');
        });
    }
}
</script>
@endpush