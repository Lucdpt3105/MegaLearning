<header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between sticky top-0 z-30 shadow-sm">
    <div class="flex items-center gap-4 flex-1 max-w-2xl">
        <!-- Hamburger Toggle Button -->
        <button id="sidebarToggle" class="flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors shrink-0">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Search Bar -->
        <div class="flex-1" x-data="globalSearch()" @click.away="showSuggestions = false">
        <form action="{{ route('search.index') }}" method="GET" class="relative">
            <input 
                type="text" 
                name="q"
                x-model="searchQuery"
                @input.debounce.300ms="fetchSuggestions()"
                @focus="showSuggestions = true"
                @keydown.escape="showSuggestions = false"
                placeholder="Tìm kiếm lớp học, tài liệu, diễn đàn..."
                class="w-full pl-10 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
                autocomplete="off"
            >
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            
            <!-- Clear button -->
            <button 
                type="button"
                x-show="searchQuery.length > 0"
                @click="searchQuery = ''; suggestions = []; showSuggestions = false"
                class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Suggestions Dropdown -->
            <div 
                x-show="showSuggestions && (suggestions.length > 0 || loading)"
                x-transition
                class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-2xl border border-gray-200 max-h-96 overflow-y-auto z-50"
            >
                <!-- Loading State -->
                <template x-if="loading">
                    <div class="p-4 text-center text-gray-500">
                        <svg class="animate-spin h-5 w-5 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm mt-2">Đang tìm kiếm...</p>
                    </div>
                </template>

                <!-- Suggestions List -->
                <template x-if="!loading && suggestions.length > 0">
                    <ul>
                        <template x-for="(item, index) in suggestions" :key="index">
                            <li>
                                <a 
                                    :href="item.url"
                                    class="flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0"
                                >
                                    <span class="text-2xl" x-text="item.icon"></span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="item.title"></p>
                                        <p class="text-xs text-gray-500" x-text="item.subtitle"></p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </li>
                        </template>
                        <!-- View All Results -->
                        <li>
                            <button 
                                type="submit"
                                class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-semibold transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span>Xem tất cả kết quả</span>
                            </button>
                        </li>
                    </ul>
                </template>
            </div>
        </form>
    </div>    </div>
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
        <div x-data="notificationDropdown()" @click.away="open = false" class="relative">
            <button @click="open = !open; if(open) loadNotifications()" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1"></span>
            </button>

            <!-- Notification Dropdown -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-[420px] bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden z-50"
                 style="display: none;">
                
                <!-- Header -->
                <div class="bg-white px-5 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">Thông Báo</h3>
                        <button @click="markAllAsRead()" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                            Đánh dấu đã đọc
                        </button>
                    </div>
                </div>

                <!-- Notifications List -->
                <div class="max-h-[480px] overflow-y-auto">
                    <template x-if="loading">
                        <div class="py-12 px-6 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3">
                                <svg class="animate-spin h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-500">Đang tải thông báo...</p>
                        </div>
                    </template>

                    <template x-if="!loading && notifications.length === 0">
                        <div class="py-16 px-6 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-50 rounded-full mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-1">Chưa có thông báo</h4>
                            <p class="text-xs text-gray-500 max-w-[280px] mx-auto">Chúng tôi sẽ thông báo khi có điều mới</p>
                        </div>
                    </template>

                    <template x-if="!loading && notifications.length > 0">
                        <div class="divide-y divide-gray-100">
                            <template x-for="notification in notifications" :key="notification.id">
                                <div @click="if(notification.data && notification.data.url) { markAsRead(notification.id); setTimeout(() => { window.location.href = notification.data.url; }, 100); }" 
                                     :class="notification.read_at ? 'bg-white' : 'bg-blue-50/30'"
                                     class="px-5 py-4 hover:bg-gray-50 cursor-pointer transition-all duration-150 group">
                                    <div class="flex items-start space-x-3">
                                        <!-- Icon based on type -->
                                        <div :class="{
                                            'bg-blue-100 text-blue-600': notification.type === 'exam_reminder',
                                            'bg-amber-100 text-amber-600': notification.type === 'exam_update',
                                            'bg-emerald-100 text-emerald-600': notification.type === 'general'
                                        }" class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center">
                                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <p class="text-sm font-semibold text-gray-900 leading-snug" x-text="notification.data && notification.data.title ? notification.data.title : 'Thông báo'"></p>
                                                <template x-if="!notification.read_at">
                                                    <span class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full mt-1.5"></span>
                                                </template>
                                            </div>
                                            <p class="text-xs text-gray-600 mt-1 line-clamp-2 leading-relaxed" x-text="notification.data && notification.data.message ? notification.data.message : ''"></p>
                                            <div class="flex items-center mt-2 space-x-2 text-xs text-gray-500">
                                                <span class="font-medium" x-text="formatTime(notification.created_at)"></span>
                                                <template x-if="notification.data.exam_title">
                                                    <span>• <span x-text="notification.data.exam_title"></span></span>
                                                </template>
                                            </div>
                                        </div>
                                        <template x-if="!notification.read_at">
                                            <div class="w-2 h-2 bg-indigo-600 rounded-full flex-shrink-0"></div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Footer -->
                <div class="bg-white px-5 py-3 border-t border-gray-100">
                    <a href="{{ route('notifications.index') }}" class="block text-center text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                        Xem tất cả thông báo
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div x-data="chatDropdown()" @click.away="open = false" class="relative">
            <button @click="open = !open; if(open) loadRooms()" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 bg-green-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1"></span>
            </button>

            <!-- Chat Dropdown -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-[420px] bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden z-50"
                 style="display: none;">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Tin Nhắn</h3>
                        <a href="{{ route('chat') }}" class="text-sm font-medium text-white/90 hover:text-white transition-colors">
                            Xem tất cả
                        </a>
                    </div>
                </div>

                <!-- Chat Rooms List -->
                <div class="max-h-[480px] overflow-y-auto">
                    <template x-if="loading">
                        <div class="py-12 px-6 text-center">
                            <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-100 rounded-full mb-4">
                                <svg class="animate-spin h-7 w-7 text-gray-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <p class="text-base font-medium text-gray-500">Đang tải...</p>
                        </div>
                    </template>

                    <template x-if="!loading && rooms.length === 0">
                        <div class="py-16 px-6 text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-full mb-5">
                                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <h4 class="text-base font-semibold text-gray-900 mb-2">Chưa có tin nhắn</h4>
                            <p class="text-sm text-gray-500 mb-4">Bắt đầu trò chuyện ngay!</p>
                            <a href="{{ route('chat') }}" class="inline-block px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm">
                                Mở Chat
                            </a>
                        </div>
                    </template>

                    <template x-if="!loading && rooms.length > 0">
                        <div class="divide-y divide-gray-100">
                            <template x-for="room in rooms" :key="room.id">
                                <a :href="`{{ route('chat') }}?room=${room.id}`" 
                                   class="flex items-start gap-3 px-6 py-4 hover:bg-gray-50 transition-colors cursor-pointer">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0 relative">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-base">
                                            <span x-text="getRoomInitial(room)"></span>
                                        </div>
                                        <template x-if="room.unread_count > 0">
                                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                                                <span x-text="room.unread_count > 9 ? '9+' : room.unread_count"></span>
                                            </span>
                                        </template>
                                    </div>
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2 mb-1.5">
                                            <p class="text-base font-semibold text-gray-900 truncate" x-text="getRoomName(room)"></p>
                                            <span class="text-xs text-gray-500 flex-shrink-0" x-text="formatChatTime(room.updated_at)"></span>
                                        </div>
                                        <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed" x-text="getLastMessage(room)"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-5 py-3 border-t border-gray-200">
                    <a href="{{ route('chat') }}" class="block text-center text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                        Mở ứng dụng Chat
                    </a>
                </div>
            </div>
        </div>

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

<script>
// Notification Dropdown Component
function notificationDropdown() {
    return {
        open: false,
        loading: false,
        notifications: [],
        unreadCount: 0,
        
        init() {
            this.loadUnreadCount();
            // Poll for new notifications every 5 seconds
            setInterval(() => {
                this.loadUnreadCount();
            }, 5000);
        },
        
        async loadNotifications() {
            this.loading = true;
            try {
                const response = await fetch('{{ route("notifications.index") }}?json=1', {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                this.notifications = data.data || [];
            } catch (error) {
                console.error('Error loading notifications:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async loadUnreadCount() {
            try {
                const response = await fetch('{{ route("notifications.unread-count") }}', {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                this.unreadCount = data.count || 0;
            } catch (error) {
                console.error('Error loading unread count:', error);
            }
        },
        
        async markAsRead(notificationId) {
            try {
                await fetch(`{{ url('/notifications') }}/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                });
                this.loadNotifications();
                this.loadUnreadCount();
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },
        
        async markAllAsRead() {
            try {
                await fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                });
                this.loadNotifications();
                this.loadUnreadCount();
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        },
        
        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);
            
            if (diffMins < 1) return 'Vừa xong';
            if (diffMins < 60) return `${diffMins} phút trước`;
            if (diffHours < 24) return `${diffHours} giờ trước`;
            if (diffDays < 7) return `${diffDays} ngày trước`;
            
            return date.toLocaleDateString('vi-VN', { 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric' 
            });
        }
    }
}

// Chat Dropdown Component
function chatDropdown() {
    return {
        open: false,
        loading: false,
        rooms: [],
        unreadCount: 0,
        currentUserId: {{ auth()->id() ?? 0 }},
        
        init() {
            this.loadUnreadCount();
            // Poll for new messages every 10 seconds
            setInterval(() => {
                this.loadUnreadCount();
                if (this.open) {
                    this.loadRooms();
                }
            }, 10000);
            
            // Listen for real-time chat messages via Laravel Echo
            if (typeof window.Echo !== 'undefined' && this.currentUserId > 0) {
                window.Echo.private(`chat.user.${this.currentUserId}`)
                    .listen('NewChatMessage', (e) => {
                        console.log('New chat message received:', e);
                        // Update unread count immediately
                        this.loadUnreadCount();
                        // Reload rooms if dropdown is open
                        if (this.open) {
                            this.loadRooms();
                        }
                    });
            }
        },
        
        async loadRooms() {
            this.loading = true;
            try {
                const response = await fetch('/api/v1/chat/rooms', {
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.rooms = data.data.slice(0, 5); // Show only 5 recent rooms
                }
            } catch (error) {
                console.error('Error loading chat rooms:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async loadUnreadCount() {
            try {
                const response = await fetch('/api/v1/chat/unread-count', {
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.unreadCount = data.count || 0;
                }
            } catch (error) {
                console.error('Error loading unread count:', error);
            }
        },
        
        getRoomName(room) {
            if (room.room_type === 'private' && room.members && room.members.length > 0) {
                const otherUser = room.members.find(m => m.id !== this.currentUserId);
                return otherUser ? otherUser.name : room.room_name;
            }
            return room.room_name || 'Chat';
        },
        
        getRoomInitial(room) {
            const name = this.getRoomName(room);
            return name.charAt(0).toUpperCase();
        },
        
        getLastMessage(room) {
            if (room.latest_message) {
                const text = room.latest_message.message_text;
                return text.length > 50 ? text.substring(0, 50) + '...' : text;
            }
            return 'Chưa có tin nhắn';
        },
        
        formatChatTime(timestamp) {
            if (!timestamp) return '';
            const date = new Date(timestamp);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);
            
            if (diffMins < 1) return 'Vừa xong';
            if (diffMins < 60) return `${diffMins}p`;
            if (diffHours < 24) return `${diffHours}h`;
            if (diffDays < 7) return `${diffDays}d`;
            
            return date.toLocaleDateString('vi-VN', { 
                day: '2-digit', 
                month: '2-digit'
            });
        }
    }
}
</script>
