document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const hamburgerToggle = document.getElementById('hamburgerToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const profileToggle = document.getElementById('profileToggle');
    const profileMenu = document.getElementById('profileMenu');

    // Toggle sidebar visibility
    function toggleSidebar() {
        if (sidebar) {
            sidebar.classList.toggle('hidden');
        }
    }

    // Hamburger button click
    if (hamburgerToggle) {
        hamburgerToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
    }

    // Initialize Feather Icons
    if (window.feather) {
        feather.replace();
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
