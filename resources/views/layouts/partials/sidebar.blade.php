<aside id="sidebar" class="sidebar-container bg-gray-900 border-r border-gray-800 shrink-0 overflow-y-auto transition-all duration-300 flex flex-col sticky top-0 h-screen w-56">
    <!-- Logo -->
    @php
        $dashboardUrl = route('login'); // Default to login if not authenticated
        if (Auth::check()) {
            if (Auth::user()->hasRole('admin')) {
                $dashboardUrl = route('admin.dashboard');
            } elseif (Auth::user()->hasRole('teacher')) {
                $dashboardUrl = route('teacher.dashboard');
            } elseif (Auth::user()->hasRole('student')) {
                $dashboardUrl = route('student.dashboard');
            }
        }
    @endphp
    <a href="{{ $dashboardUrl }}" class="block p-4 mt-12">
        <div class="logo-container">
            <div class="w-10 h-10 flex items-center justify-center shrink-0">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div class="logo-text">
                <h1 class="text-base font-semibold text-white whitespace-nowrap">MegaLearning</h1>
                <p class="text-xs text-gray-400 whitespace-nowrap">E-Learning Platform</p>
            </div>
        </div>
    </a>

    <!-- Navigation -->
    <nav class="nav-container px-3 py-4 space-y-1 overflow-y-auto flex-1">
        @auth
            @if(auth()->user()->hasRole('teacher'))
                <!-- Dashboard -->
                <a href="{{ route('teacher.dashboard') }}" class="sidebar-nav-item {{ request()->is('teacher/dashboard*') ? 'active' : '' }}">
                    <div class="nav-icon-wrapper">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="nav-text">Tổng quan</span>
                </a>

                <!-- Subjects Dropdown -->
                <button class="sidebar-section-header dropdown-toggle" data-section="subjects">
                    <span class="section-emoji">📚</span>
                    <span class="section-text">Môn học</span>
                    <svg class="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div class="dropdown-content" data-section="subjects">
                    <a href="{{ route('teacher.subjects.index') }}" class="sidebar-nav-item {{ request()->is('teacher/subjects*') ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="nav-text">Môn học</span>
                    </a>
                </div>

                <!-- Classes Dropdown -->
                <button class="sidebar-section-header dropdown-toggle" data-section="classes">
                    <span class="section-emoji">🎓</span>
                    <span class="section-text">Lớp học</span>
                    <svg class="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div class="dropdown-content" data-section="classes">
                    <a href="{{ route('teacher.video-calls.index') }}" class="sidebar-nav-item {{ request()->is('teacher/classes*') || request()->is('teacher/video-calls*') ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="nav-text">Lớp học</span>
                    </a>

                    <a href="{{ route('teacher.students') }}" class="sidebar-nav-item {{ request()->is('teacher/students*') ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span class="nav-text">Học sinh</span>
                    </a>
                </div>

                <!-- Documents Dropdown -->
                <button class="sidebar-section-header dropdown-toggle" data-section="documents">
                    <span class="section-emoji">📁</span>
                    <span class="section-text">Tài liệu</span>
                    <svg class="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div class="dropdown-content" data-section="documents">
                    <a href="{{ route('teacher.documents.index') }}" class="sidebar-nav-item {{ request()->is('teacher/documents*') ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="nav-text">Tài liệu</span>
                    </a>
                </div>

                <!-- Exams Dropdown -->
                <button class="sidebar-section-header dropdown-toggle" data-section="exams">
                    <span class="section-emoji">📝</span>
                    <span class="section-text">Kiểm tra</span>
                    <svg class="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div class="dropdown-content" data-section="exams">
                    <a href="{{ route('teacher.questions.index') }}" class="sidebar-nav-item {{ request()->is('teacher/questions*') ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="nav-text">Câu hỏi</span>
                    </a>

                    <a href="{{ route('teacher.exams.index') }}" class="sidebar-nav-item {{ request()->is('teacher/exams*') ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <span class="nav-text">Đề thi</span>
                    </a>

                    <a href="{{ route('teacher.grading.index') }}" class="sidebar-nav-item {{ request()->is('teacher/grading*') ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="nav-text">Chấm điểm</span>
                    </a>
                </div>

                <!-- Communication Dropdown -->
                <button class="sidebar-section-header dropdown-toggle" data-section="communication">
                    <span class="section-emoji">💬</span>
                    <span class="section-text">Giao tiếp</span>
                    <svg class="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div class="dropdown-content" data-section="communication">
                    <a href="{{ route('forum.index') }}" class="sidebar-nav-item {{ request()->is('forum*') ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                        </div>
                        <span class="nav-text">Diễn đàn</span>
                    </a>

                    <a href="{{ route('chat') }}" class="sidebar-nav-item {{ request()->is('chat*') ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <span class="nav-text">Chat</span>
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
                            <span class="nav-badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                        @endif
                    </a>
                </div>

                <!-- Reports Dropdown -->
                <button class="sidebar-section-header dropdown-toggle" data-section="reports">
                    <span class="section-emoji">📊</span>
                    <span class="section-text">Báo cáo</span>
                    <svg class="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div class="dropdown-content" data-section="reports">
                    <a href="{{ route('teacher.reports.index') }}" class="sidebar-nav-item {{ request()->is('teacher/reports*') ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="nav-text">Thống kê</span>
                    </a>
                </div>

            @elseif(auth()->user()->hasRole('student'))
                <!-- Student Navigation -->
                <a href="{{ route('student.dashboard') }}" class="sidebar-nav-item {{ request()->is('student/dashboard*') ? 'active' : '' }}">
                    <div class="nav-icon-wrapper">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="nav-text">Dashboard</span>
                </a>

                <a href="{{ route('student.courses.index') }}" class="sidebar-nav-item {{ request()->is('student/courses*') ? 'active' : '' }}">
                    <div class="nav-icon-wrapper">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="nav-text">Lớp học</span>
                </a>

                <a href="{{ route('student.exams.index') }}" class="sidebar-nav-item {{ request()->is('student/exams*') ? 'active' : '' }}">
                    <div class="nav-icon-wrapper">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="nav-text">Bài thi</span>
                </a>

                <a href="{{ route('student.video-calls.index') }}" class="sidebar-nav-item {{ request()->is('student/video-calls*') ? 'active' : '' }}">
                    <div class="nav-icon-wrapper">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="nav-text">Họp Online</span>
                    @php
                        $enrolledClassIds = auth()->user()->enrolledClasses()
                            ->where('class_enrollments.status', 'active')
                            ->pluck('class_rooms.id')
                            ->toArray();
                        
                        $upcomingMeetings = !empty($enrolledClassIds) 
                            ? \App\Models\VideoCall::whereIn('class_room_id', $enrolledClassIds)
                                ->where('status', 'scheduled')
                                ->where('scheduled_at', '>', now())
                                ->where('scheduled_at', '<=', now()->addHours(24))
                                ->count()
                            : 0;
                    @endphp
                    @if($upcomingMeetings > 0)
                        <span class="nav-badge">{{ $upcomingMeetings }}</span>
                    @endif
                </a>

                <a href="{{ route('student.grades.index') }}" class="sidebar-nav-item {{ request()->is('student/grades*') ? 'active' : '' }}">
                    <div class="nav-icon-wrapper">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="nav-text">Điểm số</span>
                </a>

                <a href="{{ route('forum.index') }}" class="sidebar-nav-item {{ request()->is('forum*') ? 'active' : '' }}">
                    <div class="nav-icon-wrapper">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                    </div>
                    <span class="nav-text">Diễn đàn</span>
                </a>

                <a href="{{ route('chat') }}" class="sidebar-nav-item {{ request()->is('chat*') ? 'active' : '' }}">
                    <div class="nav-icon-wrapper">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <span class="nav-text">Chat</span>
                </a>

            @else
                <!-- Default Navigation -->
                <a href="/" class="sidebar-nav-item active">
                    <div class="nav-icon-wrapper">
                        <svg class="nav-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="nav-text">Dashboard</span>
                </a>
            @endif
        @endauth
    </nav>

    <!-- Profile Section (Bottom) -->
    <div class="profile-section border-t border-gray-800">
        <button id="profileToggle" class="profile-toggle">
            @if(Auth::check() && Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile" class="profile-avatar">
            @elseif(Auth::check())
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=4f46e5&color=fff" alt="Profile" class="profile-avatar">
            @else
                <img src="https://ui-avatars.com/api/?name=User&background=4f46e5&color=fff" alt="Profile" class="profile-avatar">
            @endif
            <div class="profile-details">
                <h4 class="profile-name">{{ Auth::check() ? Auth::user()->name : 'User' }}</h4>
                <a href="{{ route('profile.edit') }}" class="profile-link">Xem hồ sơ</a>
            </div>
        </button>
        
        <div id="profileMenu" class="profile-menu">
            <a href="{{ route('profile.edit') }}" class="profile-menu-item">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Hồ sơ</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="profile-menu-item text-red-400 hover:text-red-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Đăng xuất</span>
                </button>
            </form>
        </div>
    </div>
</aside>
