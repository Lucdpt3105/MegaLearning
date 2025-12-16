<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="MegaLearning Admin Dashboard">
    <meta name="keywords" content="admin,dashboard,megalearning">
    <meta name="author" content="MegaLearning">

    <title>@yield('title', 'Admin Dashboard') - MegaLearning</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS custom cho admin (Tailwind layer custom) --}}
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">

    {{-- Tailwind / app CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
    <script src="https://unpkg.com/alpinejs" defer></script>

</head>
<body class="min-h-screen bg-slate-50 font-['Poppins',system-ui,sans-serif] text-slate-800 antialiased">

    <div class="min-h-screen flex">

        {{-- SIDEBAR MODERN DESIGN (matching teacher/student style) --}}
        <aside id="sidebar" class="sidebar-container bg-gray-900 border-r border-gray-800 shrink-0 overflow-hidden flex flex-col">
            {{-- Logo --}}
            <a href="{{ route('admin.dashboard') }}" class="block p-4 mt-12 hover:bg-transparent">
                <div class="logo-container">
                    <div class="w-10 h-10 flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div class="logo-text">
                        <h1 class="text-base font-semibold text-white whitespace-nowrap">MegaLearning</h1>
                        <p class="text-xs text-gray-400 whitespace-nowrap">Admin Panel</p>
                    </div>
                </div>
            </a>

            {{-- Menu --}}
            <nav class="nav-container px-3 py-4 space-y-1 overflow-y-auto flex-1">
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->is('admin') || request()->is('admin/dashboard*') ? 'active' : '' }}">
                    <i data-feather="home" class="w-5 h-5"></i>
                    <span class="nav-text">Tổng quan</span>
                </a>

                {{-- Divider --}}
                <div class="sidebar-divider">Người dùng</div>
                
                <a href="{{ route('admin.students.index') }}" class="sidebar-nav-item {{ request()->is('admin/students*') ? 'active' : '' }}">
                    <i data-feather="users" class="w-5 h-5"></i>
                    <span class="nav-text">Học sinh</span>
                </a>
                
                <a href="{{ route('admin.teachers.index') }}" class="sidebar-nav-item {{ request()->is('admin/teachers*') ? 'active' : '' }}">
                    <i data-feather="user-check" class="w-5 h-5"></i>
                    <span class="nav-text">Giáo viên</span>
                </a>
                
                <a href="{{ route('admin.admins.index') }}" class="sidebar-nav-item {{ request()->is('admin/admins*') ? 'active' : '' }}">
                    <i data-feather="shield" class="w-5 h-5"></i>
                    <span class="nav-text">Quản trị viên</span>
                </a>

                {{-- Divider --}}
                <div class="sidebar-divider">Lớp học</div>
                
                <a href="{{ route('admin.courses.index') }}" class="sidebar-nav-item {{ request()->is('admin/courses*') && !request()->is('admin/courses/create') ? 'active' : '' }}">
                    <i data-feather="book" class="w-5 h-5"></i>
                    <span class="nav-text">Danh sách lớp học</span>
                </a>
                
                <a href="{{ route('admin.courses.create') }}" class="sidebar-nav-item {{ request()->is('admin/courses/create') ? 'active' : '' }}">
                    <i data-feather="plus-circle" class="w-5 h-5"></i>
                    <span class="nav-text">Thêm lớp học</span>
                </a>
                
                <a href="{{ route('admin.subjects.index') }}" class="sidebar-nav-item {{ request()->is('admin/subjects*') ? 'active' : '' }}">
                    <i data-feather="layers" class="w-5 h-5"></i>
                    <span class="nav-text">Môn học</span>
                </a>

                {{-- Divider --}}
                <div class="sidebar-divider">Bài thi & Câu hỏi</div>
                
                <a href="{{ route('admin.exams.index') }}" class="sidebar-nav-item {{ request()->is('admin/exams*') && !request()->is('admin/exams/create') ? 'active' : '' }}">
                    <i data-feather="file-text" class="w-5 h-5"></i>
                    <span class="nav-text">Danh sách bài thi</span>
                </a>
                
                <a href="{{ route('admin.questions.index') }}" class="sidebar-nav-item {{ request()->is('admin/questions*') ? 'active' : '' }}">
                    <i data-feather="help-circle" class="w-5 h-5"></i>
                    <span class="nav-text">Ngân hàng câu hỏi</span>
                </a>
                
                <a href="{{ route('admin.exam-results.index') }}" class="sidebar-nav-item {{ request()->is('admin/exam-results*') ? 'active' : '' }}">
                    <i data-feather="bar-chart-2" class="w-5 h-5"></i>
                    <span class="nav-text">Kết quả thi</span>
                </a>

                {{-- Divider --}}
                <div class="sidebar-divider">Thống kê</div>

                <a href="{{ route('admin.statistics.participation') }}" class="sidebar-nav-item {{ request()->is('admin/statistics/participation*') ? 'active' : '' }}">
                    <i data-feather="activity" class="w-5 h-5"></i>
                    <span class="nav-text">Tham gia hoạt động</span>
                </a>

                <a href="{{ route('admin.statistics.activity-logs') }}" class="sidebar-nav-item {{ request()->is('admin/statistics/activity-logs*') ? 'active' : '' }}">
                    <i data-feather="file-text" class="w-5 h-5"></i>
                    <span class="nav-text">Log hoạt động</span>
                </a>

                {{-- Divider --}}
                <div class="sidebar-divider">Quản lý khác</div>
                
                <a href="{{ route('admin.meetings.rooms') }}" class="sidebar-nav-item {{ request()->is('admin/meetings/rooms*') ? 'active' : '' }}">
                    <i data-feather="video" class="w-5 h-5"></i>
                    <span class="nav-text">Phòng họp</span>
                </a>
                
                <a href="{{ route('admin.meetings.history') }}" class="sidebar-nav-item {{ request()->is('admin/meetings/history*') ? 'active' : '' }}">
                    <i data-feather="clock" class="w-5 h-5"></i>
                    <span class="nav-text">Lịch sử họp</span>
                </a>
                
                <a href="{{ route('admin.forums.topics') }}" class="sidebar-nav-item {{ request()->is('admin/forums/topics*') ? 'active' : '' }}">
                    <i data-feather="message-square" class="w-5 h-5"></i>
                    <span class="nav-text">Diễn đàn</span>
                </a>
                
                <a href="{{ route('admin.files.index') }}" class="sidebar-nav-item {{ request()->is('admin/files*') ? 'active' : '' }}">
                    <i data-feather="file" class="w-5 h-5"></i>
                    <span class="nav-text">Quản lý tệp</span>
                </a>

                {{-- Divider --}}
                <div class="sidebar-divider">Hệ thống</div>
                
                <a href="{{ route('admin.settings') }}" class="sidebar-nav-item {{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <i data-feather="settings" class="w-5 h-5"></i>
                    <span class="nav-text">Cài đặt</span>
                </a>
            </nav>

            {{-- Profile --}}
            <div class="profile-section border-t border-gray-800 p-4">
                <button id="profileToggle" class="profile-toggle w-full flex items-center gap-3 hover:bg-gray-800 p-2 rounded-lg transition-colors">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Admin" class="profile-avatar w-8 h-8 rounded-full">
                    <div class="profile-details text-left">
                        <p class="profile-name text-sm font-medium text-white">Admin</p>
                        <p class="profile-link text-xs text-gray-400">Xem hồ sơ</p>
                    </div>
                </button>
                
                {{-- Profile Menu --}}
                <div id="profileMenu" class="profile-menu hidden absolute bottom-16 left-4 w-56 bg-gray-800 rounded-lg shadow-xl border border-gray-700 overflow-hidden z-50">
                    <a href="{{ route('admin.profile') }}" class="profile-menu-item block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                        <i data-feather="user" class="inline-block w-4 h-4 mr-2"></i> Hồ sơ
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="profile-menu-item w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-700 hover:text-red-300">
                            <i data-feather="log-out" class="inline-block w-4 h-4 mr-2"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div id="main-content" class="flex-1 flex flex-col min-h-screen transition-all duration-500 ease-in-out ml-56">

            {{-- HEADER --}}
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8">
                <div class="flex flex-col">
                    <h1 class="text-base md:text-lg font-semibold text-slate-900">
                        @yield('page-title', 'Bảng điều khiển')
                    </h1>
                    @hasSection('page-description')
                        <p class="text-xs text-slate-500 mt-0.5">
                            @yield('page-description')
                        </p>
                    @endif
                </div>

            <div class="flex items-center gap-4">

                {{-- Search với Alpine.js --}}
                <div class="hidden md:block w-72 relative" x-data="adminSearch()" @click.away="showResults = false">
                    <form @submit.prevent="goToFullSearch()" class="relative">
                        <div class="flex items-center gap-2 bg-slate-900 rounded-full px-3 py-1.5">
                            <span class="text-slate-400">
                                <i data-feather="search" class="w-4 h-4"></i>
                            </span>
                            <input
                                type="text"
                                name="q"
                                x-model="query"
                                @input.debounce.300ms="search()"
                                @focus="showResults = true"
                                placeholder="Tìm kiếm..."
                                class="w-full bg-transparent border-none outline-none text-xs text-slate-100 placeholder:text-slate-400"
                                autocomplete="off"
                            >
                            <template x-if="loading">
                                <svg class="animate-spin h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                        </div>
                    </form>

                    {{-- Dropdown kết quả tìm kiếm --}}
                    <div x-show="showResults && results.length > 0"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute top-full mt-2 w-96 bg-white rounded-lg shadow-xl border border-slate-200 overflow-hidden z-50"
                         style="display: none;">
                        
                        <div class="max-h-96 overflow-y-auto">
                            <template x-for="result in results" :key="result.url">
                                <a :href="result.url" class="block px-4 py-3 hover:bg-slate-50 transition">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
                                            <i :data-feather="result.icon" class="w-4 h-4 text-slate-600"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm font-medium text-slate-900 truncate" x-text="result.title"></p>
                                                <span class="flex-shrink-0 text-xs px-2 py-0.5 bg-slate-100 text-slate-600 rounded" x-text="result.badge"></span>
                                            </div>
                                            <p class="text-xs text-slate-500 truncate mt-0.5" x-text="result.subtitle"></p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>

                        <div class="border-t border-slate-100 px-4 py-2 bg-slate-50">
                            <button @click="goToFullSearch()" class="text-xs text-slate-600 hover:text-slate-900 font-medium">
                                Xem tất cả kết quả →
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Notification với Alpine.js --}}
                <div class="relative" x-data="adminNotification()" x-init="init()">
                    <button type="button" @click="toggleDropdown()"
                            class="relative inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-slate-900 text-slate-100 hover:bg-slate-800 transition">
                        <i data-feather="bell" class="w-4 h-4"></i>
                        <span x-show="unreadCount > 0" 
                              x-text="unreadCount"
                              class="absolute -bottom-1 left-1/2 -translate-x-1/2 text-[10px] leading-none px-1.5 py-0.5 rounded-md bg-red-100 text-red-700 font-semibold">
                        </span>
                    </button>

                    {{-- Dropdown thông báo --}}
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.away="open = false"
                         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-slate-200 overflow-hidden z-50"
                         style="display: none;">
                        
                        {{-- Header --}}
                        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-slate-900">Thông báo</h3>
                            <button @click="markAllAsRead()" class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                                Đánh dấu đã đọc
                            </button>
                        </div>

                        {{-- Loading --}}
                        <template x-if="loading">
                            <div class="py-8 text-center">
                                <svg class="animate-spin h-6 w-6 text-slate-400 mx-auto" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </template>

                        {{-- Danh sách thông báo --}}
                        <div class="max-h-96 overflow-y-auto">
                            <template x-if="!loading && notifications.length === 0">
                                <div class="py-8 px-4 text-center">
                                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i data-feather="bell" class="w-6 h-6 text-slate-400"></i>
                                    </div>
                                    <p class="text-sm text-slate-600">Không có thông báo mới</p>
                                </div>
                            </template>

                            <template x-if="!loading && notifications.length > 0">
                                <div>
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div @click="markAsRead(notification.id, notification.data?.url)"
                                             :class="notification.read_at ? 'bg-white' : 'bg-blue-50'"
                                             class="px-4 py-3 hover:bg-slate-50 cursor-pointer transition border-b border-slate-100">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                                    <i data-feather="bell" class="w-4 h-4 text-blue-600"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-slate-900" x-text="notification.data?.title || 'Thông báo'"></p>
                                                    <p class="text-xs text-slate-600 mt-0.5 line-clamp-2" x-text="notification.data?.message || ''"></p>
                                                    <p class="text-xs text-slate-400 mt-1" x-text="formatTime(notification.created_at)"></p>
                                                </div>
                                                <template x-if="!notification.read_at">
                                                    <div class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0 mt-1"></div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Footer --}}
                        <div class="px-4 py-2 border-t border-slate-100 bg-slate-50">
                            <a href="{{ route('notifications.index') }}" class="block text-center text-xs text-slate-600 hover:text-slate-900 font-medium">
                                Xem tất cả thông báo
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Avatar --}}
{{-- PROFILE DROPDOWN --}}
<div class="relative" x-data="{ open: false }">

    {{-- BUTTON: Avatar + Name --}}
    <button @click="open = !open"
            class="flex items-center gap-2 focus:outline-none select-none">

        {{-- Avatar --}}
        <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center 
                    text-xs font-semibold text-slate-700">
            {{ strtoupper(Auth::user()->name[0] ?? 'A') }}
        </div>

        {{-- Name --}}
        <div class="hidden md:flex flex-col leading-tight text-left">
            <span class="text-xs font-medium text-slate-800">{{ Auth::user()->name }}</span>
            <span class="text-[11px] text-slate-400 capitalize">
                {{ Auth::user()->roles->first()->name ?? 'Admin' }}
            </span>
        </div>

        <i data-feather="chevron-down" class="w-4 h-4 text-slate-500"></i>
    </button>

    {{-- DROPDOWN MENU --}}
    <div x-show="open"
         x-transition
         @click.outside="open = false"
         class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-slate-200 py-1 z-50">

        {{-- Hồ sơ cá nhân --}}
        <a href="{{ route('admin.profile') }}"
           class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 
                  hover:bg-slate-100 rounded-lg transition">
            <i data-feather="user" class="w-4 h-4"></i>
            Hồ sơ cá nhân
        </a>

        {{-- Cài đặt --}}
        <a href="{{ route('admin.settings') }}"
           class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 
                  hover:bg-slate-100 rounded-lg transition">
            <i data-feather="settings" class="w-4 h-4"></i>
            Cài đặt
        </a>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button
                class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600
                       hover:bg-red-50 rounded-lg transition">
                <i data-feather="log-out" class="w-4 h-4"></i>
                Đăng xuất
            </button>
        </form>

    </div>
</div>
            </div>
            </header>

            {{-- MAIN WRAPPER / CONTENT --}}
            <main class="flex-1 p-4 md:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- JS: Feather + custom admin --}}
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="{{ asset('assets/js/admin.js') }}"></script>

    {{-- Alpine.js Components --}}
    <script>
        // Admin Search Component
        function adminSearch() {
            return {
                query: '',
                results: [],
                showResults: false,
                loading: false,

                search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }

                    this.loading = true;

                    fetch(`/admin/search?q=${encodeURIComponent(this.query)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.results = data.results || [];
                        this.loading = false;
                        // Re-initialize feather icons for new results
                        this.$nextTick(() => feather.replace());
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        this.loading = false;
                    });
                },

                goToFullSearch() {
                    if (this.query.length > 0) {
                        window.location.href = `/admin/search/full?q=${encodeURIComponent(this.query)}`;
                    }
                }
            }
        }

        // Admin Notification Component
        function adminNotification() {
            return {
                open: false,
                loading: false,
                notifications: [],
                unreadCount: 0,

                init() {
                    this.loadUnreadCount();
                    // Poll every 30 seconds
                    setInterval(() => {
                        this.loadUnreadCount();
                    }, 30000);
                },

                toggleDropdown() {
                    this.open = !this.open;
                    if (this.open && this.notifications.length === 0) {
                        this.loadNotifications();
                    }
                },

                loadUnreadCount() {
                    fetch('/notifications/unread-count', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.unreadCount = data.count || 0;
                    })
                    .catch(error => console.error('Error loading unread count:', error));
                },

                loadNotifications() {
                    this.loading = true;

                    fetch('/notifications?json=1', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.notifications = data.data || [];
                        this.loading = false;
                        // Re-initialize feather icons
                        this.$nextTick(() => feather.replace());
                    })
                    .catch(error => {
                        console.error('Error loading notifications:', error);
                        this.loading = false;
                    });
                },

                markAsRead(id, url) {
                    fetch(`/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update notification status
                            const notification = this.notifications.find(n => n.id === id);
                            if (notification) {
                                notification.read_at = new Date().toISOString();
                            }
                            this.loadUnreadCount();
                            
                            // Redirect if URL exists
                            if (url) {
                                setTimeout(() => {
                                    window.location.href = url;
                                }, 100);
                            }
                        }
                    })
                    .catch(error => console.error('Error marking as read:', error));
                },

                markAllAsRead() {
                    fetch('/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.notifications.forEach(n => {
                                n.read_at = new Date().toISOString();
                            });
                            this.unreadCount = 0;
                        }
                    })
                    .catch(error => console.error('Error marking all as read:', error));
                },

                formatTime(dateString) {
                    const date = new Date(dateString);
                    const now = new Date();
                    const diffMs = now - date;
                    const diffMins = Math.floor(diffMs / 60000);
                    const diffHours = Math.floor(diffMs / 3600000);
                    const diffDays = Math.floor(diffMs / 86400000);

                    if (diffMins < 1) return 'Vừa xong';
                    if (diffMins < 60) return `${diffMins} phút trước`;
                    if (diffHours < 24) return `${diffHours} giờ trước`;
                    if (diffDays < 7) return `${diffDays} ngày trước`;
                    
                    return date.toLocaleDateString('vi-VN');
                }
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
