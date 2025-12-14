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

    {{-- TOGGLE BUTTON (Hamburger) --}}
    <button id="sidebarToggle" class="fixed top-4 left-4 z-[100] text-gray-400 hover:text-white focus:outline-none p-2 rounded-md transition-colors duration-200">
        <div class="hamburger-icon">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </div>
    </button>

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
                <div class="sidebar-divider">Báo cáo</div>
                
                <a href="{{ route('admin.reports.students') }}" class="sidebar-nav-item {{ request()->is('admin/reports/students*') ? 'active' : '' }}">
                    <i data-feather="users" class="w-5 h-5"></i>
                    <span class="nav-text">Thống kê học sinh</span>
                </a>
                
                <a href="{{ route('admin.reports.courses') }}" class="sidebar-nav-item {{ request()->is('admin/reports/courses*') ? 'active' : '' }}">
                    <i data-feather="book-open" class="w-5 h-5"></i>
                    <span class="nav-text">Thống kê lớp học</span>
                </a>
                
                <a href="{{ route('admin.statistics.index') }}" class="sidebar-nav-item {{ request()->is('admin/statistics*') ? 'active' : '' }}">
                    <i data-feather="pie-chart" class="w-5 h-5"></i>
                    <span class="nav-text">Thống kê chi tiết</span>
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

                {{-- Search --}}
                <form class="hidden md:block w-72">
                    <div class="flex items-center gap-2 bg-slate-900 rounded-full px-3 py-1.5">
                        <span class="text-slate-400">
                            <i data-feather="search" class="w-4 h-4"></i>
                        </span>
                        <input
                            type="text"
                            name="q"
                            placeholder="Tìm kiếm..."
                            class="w-full bg-transparent border-none outline-none text-xs text-slate-100 placeholder:text-slate-400"
                        >
                    </div>
                </form>

                {{-- Notification --}}
                <button type="button"
                        class="relative inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-slate-900 text-slate-100 hover:bg-slate-800 transition">
                    <i data-feather="bell" class="w-4 h-4"></i>
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 text-[10px] leading-none px-1.5 py-0.5 rounded-md bg-red-100 text-red-700 font-semibold">
                        3
                    </span>
                </button>

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

    @stack('scripts')
</body>
</html>
