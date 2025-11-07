<header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
    <div class="flex items-center justify-between">
        <!-- Page Title -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
            <p class="text-sm text-gray-500 mt-1">@yield('page-description', 'Welcome to admin panel')</p>
        </div>

        <!-- Actions -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <span class="text-xl">🔔</span>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- Search -->
            <div class="relative">
                <input type="text" 
                       placeholder="Search..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            </div>
        </div>
    </div>
</header>
