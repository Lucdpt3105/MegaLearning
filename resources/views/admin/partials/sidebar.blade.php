<aside class="w-64 bg-gradient-to-b from-indigo-600 to-purple-700 text-white flex flex-col">
    <!-- Logo -->
    <div class="p-6 border-b border-indigo-500">
        <h1 class="text-2xl font-bold">🎓 MegaLearning</h1>
        <p class="text-indigo-200 text-sm mt-1">Admin Panel</p>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto p-4 space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 backdrop-blur' : 'hover:bg-white/10' }}">
            <span class="mr-3 text-xl">📊</span>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Content Management -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-indigo-200 uppercase tracking-wider mb-2">Content Management</p>
            
            <a href="{{ route('admin.subjects.index') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.subjects.*') ? 'bg-white/20 backdrop-blur' : 'hover:bg-white/10' }}">
                <span class="mr-3 text-xl">📚</span>
                <span class="font-medium">Môn học</span>
            </a>

            <a href="{{ route('admin.topics.index') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.topics.*') ? 'bg-white/20 backdrop-blur' : 'hover:bg-white/10' }}">
                <span class="mr-3 text-xl">📖</span>
                <span class="font-medium">Chủ đề</span>
            </a>

            <a href="{{ route('admin.questions.index') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.questions.*') ? 'bg-white/20 backdrop-blur' : 'hover:bg-white/10' }}">
                <span class="mr-3 text-xl">❓</span>
                <span class="font-medium">Câu hỏi</span>
            </a>

            <a href="{{ route('admin.exams.index') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.exams.*') ? 'bg-white/20 backdrop-blur' : 'hover:bg-white/10' }}">
                <span class="mr-3 text-xl">📝</span>
                <span class="font-medium">Đề thi</span>
            </a>
        </div>

        <!-- System -->
        <div class="pt-4">
            <p class="px-4 text-xs font-semibold text-indigo-200 uppercase tracking-wider mb-2">System</p>
            
            <a href="{{ route('admin.students.index') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.students.*') || request()->routeIs('admin.teachers.*') || request()->routeIs('admin.admins.*') ? 'bg-white/20 backdrop-blur' : 'hover:bg-white/10' }}">
                <span class="mr-3 text-xl">👥</span>
                <span class="font-medium">Users</span>
            </a>

            <!-- Statistics Dashboard -->
            <a href="{{ route('admin.statistics.index') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.statistics.*') ? 'bg-white/20 backdrop-blur' : 'hover:bg-white/10' }}">
                <span class="mr-3 text-xl">📈</span>
                <span class="font-medium">Thống kê</span>
            </a>

            <a href="#" class="flex items-center px-4 py-3 rounded-lg transition hover:bg-white/10">
                <span class="mr-3 text-xl">⚙️</span>
                <span class="font-medium">Settings</span>
            </a>
        </div>
    </nav>

    <!-- User Info -->
    <div class="p-4 border-t border-indigo-500">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-indigo-300 flex items-center justify-center text-indigo-700 font-bold">
                A
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">Admin User</p>
                <p class="text-xs text-indigo-200">admin@mega.com</p>
            </div>
        </div>
    </div>
</aside>
