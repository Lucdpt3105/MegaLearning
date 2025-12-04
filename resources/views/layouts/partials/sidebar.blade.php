<aside class="w-72 bg-gradient-to-b from-white via-blue-50/30 to-purple-50/30 border-r border-gray-200 flex-shrink-0 overflow-y-auto shadow-xl">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="flex items-center space-x-3">
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-2xl transform hover:scale-110 hover:rotate-6 transition-all duration-300">
                <span class="text-3xl">🎓</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-white">MegaLearning</h1>
                <p class="text-xs text-blue-100 font-medium">E-Learning Platform</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-2">
        @auth
            @if(auth()->user()->hasRole('teacher'))
                <!-- Teacher Navigation -->
                
                <!-- Dashboard -->
                <a href="{{ route('teacher.dashboard') }}" class="nav-item {{ request()->is('teacher/dashboard*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-blue-500 to-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Tổng quan</span>
                        <span class="nav-desc">Dashboard & Analytics</span>
                    </div>
                </a>

                <!-- Subjects Section -->
                <div class="nav-section-header">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">📚</span>
                        <span>Môn học</span>
                    </div>
                </div>

                <a href="{{ route('teacher.subjects.index') }}" class="nav-item {{ request()->is('teacher/subjects*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-purple-500 to-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Môn học của tôi</span>
                        <span class="nav-desc">Quản lý môn học</span>
                    </div>
                </a>

                <!-- Classes Section -->
                <div class="nav-section-header">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">🎓</span>
                        <span>Lớp học & Học sinh</span>
                    </div>
                </div>

                <a href="{{ route('teacher.video-calls.index') }}" class="nav-item {{ request()->is('teacher/classes*') || request()->is('teacher/video-calls*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-green-500 to-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Lớp học trực tuyến</span>
                        <span class="nav-desc">Video call & Classes</span>
                    </div>
                </a>

                <!-- Documents Section -->
                <div class="nav-section-header">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">📁</span>
                        <span>Tài liệu</span>
                    </div>
                </div>

                <a href="/teacher/documents" class="nav-item {{ request()->is('teacher/documents*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-orange-500 to-orange-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Tài liệu học tập</span>
                        <span class="nav-desc">Upload & Manage Docs</span>
                    </div>
                </a>

                <!-- Students Section -->
                <div class="nav-section-header">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">👥</span>
                        <span>Học sinh</span>
                    </div>
                </div>

                <a href="{{ route('teacher.students.index') }}" class="nav-item {{ request()->is('teacher/students*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-emerald-500 to-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Quản lý học sinh</span>
                        <span class="nav-desc">Manage Students</span>
                    </div>
                </a>

                <!-- Exams Section -->
                <div class="nav-section-header">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">📝</span>
                        <span>Kiểm tra & Đề thi</span>
                    </div>
                </div>

                <a href="{{ route('teacher.questions.index') }}" class="nav-item {{ request()->is('teacher/questions*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-pink-500 to-pink-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Ngân hàng Câu hỏi</span>
                        <span class="nav-desc">Question Bank</span>
                    </div>
                </a>

                <a href="/teacher/exams" class="nav-item {{ request()->is('teacher/exams*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-red-500 to-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Quản lý Đề thi</span>
                        <span class="nav-desc">Exams & Tests</span>
                    </div>
                </a>

                <a href="{{ route('teacher.grading.index') }}" class="nav-item {{ request()->is('teacher/grading*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-emerald-500 to-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Chấm điểm</span>
                        <span class="nav-desc">Grading & Scoring</span>
                    </div>
                </a>

                <!-- Communication Section -->
                <div class="nav-section-header">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">💬</span>
                        <span>Giao tiếp</span>
                    </div>
                </div>

                <a href="/teacher/forum" class="nav-item {{ request()->is('teacher/forum*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-indigo-500 to-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Diễn đàn Q&A</span>
                        <span class="nav-desc">Forum & Discussion</span>
                    </div>
                </a>

                <a href="/chat" class="nav-item {{ request()->is('chat*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-violet-500 to-violet-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Chat</span>
                        <span class="nav-desc">Instant Messaging</span>
                    </div>
                    @php
                        $unreadCount = 0;
                        if (Auth::check()) {
                            $userId = Auth::id();
                            $roomIds = \App\Models\ChatRoom::whereHas('members', function($query) use ($userId) {
                                $query->where('user_id', $userId);
                            })->pluck('id');
                            
                            $unreadCount = \App\Models\ChatMessage::whereIn('room_id', $roomIds)
                                ->where('user_id', '!=', $userId)
                                ->whereNotExists(function($query) use ($userId) {
                                    $query->select(DB::raw(1))
                                        ->from('chat_message_reads')
                                        ->whereColumn('chat_message_reads.message_id', 'chat_messages.id')
                                        ->where('chat_message_reads.user_id', $userId);
                                })
                                ->count();
                        }
                    @endphp
                    @if($unreadCount > 0)
                        <span class="nav-badge bg-red-500">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                    @endif
                </a>

                <!-- Reports Section -->
                <div class="nav-section-header">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">📊</span>
                        <span>Báo cáo</span>
                    </div>
                </div>

                <a href="{{ route('teacher.reports.index') }}" class="nav-item {{ request()->is('teacher/reports*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-cyan-500 to-cyan-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Thống kê</span>
                        <span class="nav-desc">Reports & Analytics</span>
                    </div>
                </a>

            @elseif(auth()->user()->hasRole('student'))
                <!-- Student Navigation -->
                <a href="/student/dashboard" class="nav-item {{ request()->is('student/dashboard*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-blue-500 to-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Dashboard</span>
                        <span class="nav-desc">Overview</span>
                    </div>
                </a>

                <a href="/student/courses" class="nav-item {{ request()->is('student/courses*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-purple-500 to-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Khóa học</span>
                        <span class="nav-desc">My Courses</span>
                    </div>
                </a>

                <a href="/student/exams" class="nav-item {{ request()->is('student/exams*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-red-500 to-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Bài thi</span>
                        <span class="nav-desc">Exams & Tests</span>
                    </div>
                </a>

                <a href="/student/grades" class="nav-item {{ request()->is('student/grades*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-green-500 to-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Điểm số</span>
                        <span class="nav-desc">My Grades</span>
                    </div>
                </a>

                <a href="/chat" class="nav-item {{ request()->is('chat*') ? 'active' : '' }}">
                    <div class="nav-icon bg-gradient-to-br from-violet-500 to-violet-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Chat</span>
                        <span class="nav-desc">Messages</span>
                    </div>
                </a>

            @else
                <!-- Default Navigation -->
                <a href="/" class="nav-item active">
                    <div class="nav-icon bg-gradient-to-br from-blue-500 to-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">Dashboard</span>
                        <span class="nav-desc">Overview</span>
                    </div>
                </a>
            @endif
        @endauth

        <a href="/forum" class="sidebar-link {{ request()->is('forum*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span>Forum</span>
        </a>

        <!-- Settings Section -->
        <div class="nav-section-header mt-6">
            <div class="flex items-center space-x-2">
                <span class="text-2xl">⚙️</span>
                <span>Cài đặt</span>
            </div>
        </div>

        <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->is('profile*') ? 'active' : '' }}">
            <div class="nav-icon bg-gradient-to-br from-gray-600 to-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="nav-content">
                <span class="nav-label">Hồ sơ của tôi</span>
                <span class="nav-desc">Profile Settings</span>
            </div>
        </a>

        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="nav-item w-full text-left hover:bg-red-50 group">
                <div class="nav-icon bg-gradient-to-br from-red-500 to-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <div class="nav-content">
                    <span class="nav-label group-hover:text-red-600">Đăng xuất</span>
                    <span class="nav-desc">Logout</span>
                </div>
            </button>
        </form>
    </nav>
</aside>
