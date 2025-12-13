<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MegaLearning - Online Learning Platform')</title>
    
    <!-- Google Fonts - Inter & Nunito -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Inter', 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content Area -->
        <div id="mainContent" class="flex-1 flex flex-col overflow-hidden transition-all duration-500 ease-in-out" style="margin-left: 14rem;">
            <!-- Header -->
            @include('layouts.partials.header')

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Adjust main content margin when sidebar toggles
        window.addEventListener('sidebarToggle', function(event) {
            const mainContent = document.getElementById('mainContent');
            if (event.detail.collapsed) {
                mainContent.style.marginLeft = '3.5rem';
            } else {
                mainContent.style.marginLeft = '14rem';
            }
        });
        
        // Set initial margin based on saved state
        document.addEventListener('DOMContentLoaded', function() {
            const savedState = localStorage.getItem('sidebarCollapsed');
            const mainContent = document.getElementById('mainContent');
            if (savedState === 'true') {
                mainContent.style.marginLeft = '3.5rem';
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
