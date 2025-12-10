document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather Icons
    if (window.feather) {
        feather.replace();
    }

    // Elements
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const profileToggle = document.getElementById('profileToggle');
    const profileMenu = document.getElementById('profileMenu');
    
    // Sidebar Toggle Logic
    if (sidebarToggle && sidebar) {
        // Check localStorage
        const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            if (mainContent) {
                mainContent.classList.remove('ml-56');
                mainContent.classList.add('ml-14');
            }
        }

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            const collapsed = sidebar.classList.contains('collapsed');
            
            // Update Main Content Margin
            if (mainContent) {
                if (collapsed) {
                    mainContent.classList.remove('ml-56');
                    mainContent.classList.add('ml-14');
                } else {
                    mainContent.classList.remove('ml-14');
                    mainContent.classList.add('ml-56');
                }
            }

            // Save state
            localStorage.setItem('sidebar-collapsed', collapsed);
            
            // Refresh Feather Icons
            if (window.feather) {
                feather.replace();
            }
        });
    }

    // Profile Menu Toggle
    if (profileToggle && profileMenu) {
        profileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (!profileToggle.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });
    }
});

