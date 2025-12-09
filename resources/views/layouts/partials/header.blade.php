<header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between sticky top-0 z-10">
    <!-- Search Bar -->
    <div class="flex-1 max-w-2xl">
        <div class="relative">
            <input 
                type="text" 
                placeholder="Search for quizzes, courses, or topics..."
                class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition"
            >
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    <!-- Right Section -->
    <div class="flex items-center space-x-4 ml-6">
        <!-- Video Call Notifications -->
        @auth
        @if(auth()->user()->hasRole('student'))
            @php
                $notificationService = app(\App\Services\VideoCallNotificationService::class);
                $upcomingCount = $notificationService->getUpcomingCallsCount(auth()->id());
            @endphp
            @if($upcomingCount > 0)
            <a href="{{ route('student.video-calls.index') }}" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition group">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse">{{ $upcomingCount }}</span>
                <div class="absolute right-0 top-full mt-2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                    {{ $upcomingCount }} cuộc họp sắp diễn ra
                </div>
            </a>
            @endif
        @endif
        @endauth
        
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
