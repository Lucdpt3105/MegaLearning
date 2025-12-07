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
</head>
<body class="min-h-screen bg-slate-50 font-['Poppins',system-ui,sans-serif] text-slate-800 antialiased">

    <div class="min-h-screen flex">

        {{-- SIDEBAR DESKTOP --}}
        <aside class="hidden md:flex md:flex-col md:w-64 bg-gradient-to-b from-indigo-600 to-purple-600 text-white">
            {{-- Logo --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-5 py-4 hover:bg-white/10 transition">
                <img src="{{ asset('images/logo.svg') }}" alt="MegaLearning Logo"
                     class="h-9 w-auto filter brightness-0 invert">
                <span class="text-lg font-semibold tracking-tight">MegaLearning</span>
            </a>

            {{-- Menu --}}
            <nav class="flex-1 mt-4 px-2 space-y-2 text-sm">

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-xl font-medium
                          {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 shadow-md' : 'hover:bg-white/10' }}">
                    <i data-feather="home" class="w-4 h-4"></i>
                    <span>Bảng điều khiển</span>
                </a>

                {{-- Người dùng --}}
                <div class="space-y-1">
                    <div class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wide
                                text-indigo-100/80">
                        <span class="flex items-center gap-2">
                            <i data-feather="users" class="w-4 h-4"></i>
                            <span>Người dùng</span>
                        </span>
                    </div>
                    <div class="space-y-0.5 pl-8 text-xs text-indigo-100/90">
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.students.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Học sinh</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.teachers.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Giáo viên</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.admins.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Quản trị viên</span>
                        </a>
                    </div>
                </div>

                {{-- Khóa học --}}
                <div class="space-y-1">
                    <div class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wide
                                text-indigo-100/80">
                        <span class="flex items-center gap-2">
                            <i data-feather="book-open" class="w-4 h-4"></i>
                            <span>Khóa học</span>
                        </span>
                    </div>
                    <div class="space-y-0.5 pl-8 text-xs text-indigo-100/90">
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.index') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Danh sách khóa học</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.courses.create') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Thêm khóa học</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Danh mục</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.lessons.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Bài học</span>
                        </a>
                    </div>
                </div>

                {{-- Hệ thống Thi --}}
                <div class="space-y-1">
                    <div class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wide
                                text-indigo-100/80">
                        <span class="flex items-center gap-2">
                            <i data-feather="edit-3" class="w-4 h-4"></i>
                            <span>Hệ thống thi</span>
                        </span>
                    </div>
                    <div class="space-y-0.5 pl-8 text-xs text-indigo-100/90">
                        <a href="{{ route('admin.exams.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.exams.index') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Danh sách bài thi</span>
                        </a>
                        <a href="{{ route('admin.exams.create') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.exams.create') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Tạo bài thi</span>
                        </a>
                        <a href="{{ route('admin.questions.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.questions.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Ngân hàng câu hỏi</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Kết quả thi</span>
                        </a>
                    </div>
                </div>

                {{-- Diễn đàn --}}
                <div class="space-y-1">
                    <div class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wide
                                text-indigo-100/80">
                        <span class="flex items-center gap-2">
                            <i data-feather="message-circle" class="w-4 h-4"></i>
                            <span>Diễn đàn</span>
                        </span>
                    </div>
                    <div class="space-y-0.5 pl-8 text-xs text-indigo-100/90">
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Chủ đề thảo luận</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Bài viết</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Kiểm duyệt</span>
                        </a>
                    </div>
                </div>

                {{-- Họp nhóm --}}
                <div class="space-y-1">
                    <div class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wide
                                text-indigo-100/80">
                        <span class="flex items-center gap-2">
                            <i data-feather="video" class="w-4 h-4"></i>
                            <span>Họp nhóm</span>
                        </span>
                    </div>
                    <div class="space-y-0.5 pl-8 text-xs text-indigo-100/90">
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Phòng họp</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Lịch họp</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.*y') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Lịch sử họp</span>
                        </a>
                    </div>
                </div>

                {{-- Tài liệu --}}
                <div class="space-y-1">
                    <div class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wide
                                text-indigo-100/80">
                        <span class="flex items-center gap-2">
                            <i data-feather="file-text" class="w-4 h-4"></i>
                            <span>Tài liệu</span>
                        </span>
                    </div>
                    <div class="space-y-0.5 pl-8 text-xs text-indigo-100/90">
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Quản lý file</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.index') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Tải lên</span>
                        </a>
                    </div>
                </div>

                {{-- Báo cáo --}}
                <div class="space-y-1">
                    <div class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wide
                                text-indigo-100/80">
                        <span class="flex items-center gap-2">
                            <i data-feather="bar-chart-2" class="w-4 h-4"></i>
                            <span>Báo cáo</span>
                        </span>
                    </div>
                    <div class="space-y-0.5 pl-8 text-xs text-indigo-100/90">
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.index') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Thống kê học sinh</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.index') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Thống kê khóa học</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.index') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Thống kê bài thi</span>
                        </a>
                    </div>
                </div>

                {{-- Cài đặt --}}
                <div class="space-y-1">
                    <div class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wide
                                text-indigo-100/80">
                        <span class="flex items-center gap-2">
                            <i data-feather="settings" class="w-4 h-4"></i>
                            <span>Cài đặt</span>
                        </span>
                    </div>
                    <div class="space-y-0.5 pl-8 text-xs text-indigo-100/90">
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.index') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Cài đặt chung</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.index') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Email</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-2 py-1.5 rounded-lg
                                  {{ request()->routeIs('admin.users.index') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-200"></span>
                            <span>Thanh toán</span>
                        </a>
                    </div>
                </div>

            </nav>

            {{-- Footer sidebar --}}
            <div class="px-4 py-3 text-[11px] text-indigo-100/80 border-t border-white/10">
                <span>MegaLearning Admin</span>
            </div>
        </aside>

        {{-- MOBILE SIDEBAR (simple icon bar) --}}
        <aside class="md:hidden w-16 flex flex-col items-center bg-gradient-to-b from-indigo-600 to-purple-600 text-white py-4 space-y-4">
            <a href="{{ route('admin.dashboard') }}"
               class="p-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : '' }}">
                <i data-feather="home" class="w-5 h-5"></i>
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="p-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.users.*') ? 'bg-white/20' : '' }}">
                <i data-feather="users" class="w-5 h-5"></i>
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="p-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('admin.courses.*') ? 'bg-white/20' : '' }}">
                <i data-feather="book-open" class="w-5 h-5"></i>
            </a>
            <a href="{{ route('forum.index') }}"
               class="p-2 rounded-lg hover:bg-white/10 {{ request()->routeIs('forum.*') ? 'bg-white/20' : '' }}">
                <i data-feather="message-circle" class="w-5 h-5"></i>
            </a>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col min-h-screen">

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
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-600">
                            A
                        </div>
                        <div class="hidden md:flex flex-col leading-tight">
                            <span class="text-xs font-medium text-slate-800">Admin</span>
                            <span class="text-[11px] text-slate-400">Quản trị viên</span>
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
