/**
 * Collapsible Sidebar Component
 * React-inspired sidebar with smooth animations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const profileToggle = document.getElementById('profileToggle');
    const profileMenu = document.getElementById('profileMenu');
    
    // Load saved state from localStorage
    const savedState = localStorage.getItem('sidebarCollapsed');
    let isCollapsed = savedState === 'true';
    
    // Apply initial state
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
        sidebarToggle.classList.add('collapsed');
    }
    
    // Sidebar Toggle Function
    function toggleSidebar() {
        isCollapsed = !isCollapsed;
        
        sidebar.classList.toggle('collapsed');
        sidebarToggle.classList.toggle('collapsed');
        
        // Save state to localStorage
        localStorage.setItem('sidebarCollapsed', isCollapsed);
        
        // Close profile menu if sidebar is being collapsed
        if (isCollapsed && profileMenu.classList.contains('active')) {
            profileMenu.classList.remove('active');
        }
        
        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('sidebarToggle', { 
            detail: { collapsed: isCollapsed } 
        }));
    }
    
    // Profile Menu Toggle Function
    function toggleProfileMenu(event) {
        event.stopPropagation();
        profileMenu.classList.toggle('active');
    }
    
    // Click outside to close profile menu
    document.addEventListener('click', function(event) {
        if (profileMenu && profileMenu.classList.contains('active')) {
            const isClickInsideProfile = event.target.closest('.profile-section');
            if (!isClickInsideProfile) {
                profileMenu.classList.remove('active');
            }
        }
    });
    
    // Add event listeners
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
    
    if (profileToggle) {
        profileToggle.addEventListener('click', toggleProfileMenu);
    }
    
    // Add tooltips to nav items for collapsed state
    const navItems = document.querySelectorAll('.sidebar-nav-item');
    navItems.forEach(item => {
        const navText = item.querySelector('.nav-text');
        if (navText) {
            item.setAttribute('data-tooltip', navText.textContent.trim());
        }
    });
    
    // ===========================
    // DROPDOWN FUNCTIONALITY
    // ===========================
    
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    const dropdownContents = document.querySelectorAll('.dropdown-content');
    
    // Load saved dropdown states from localStorage
    const savedDropdownStates = JSON.parse(localStorage.getItem('dropdownStates') || '{}');
    
    // Apply saved states
    dropdownToggles.forEach(toggle => {
        const sectionName = toggle.getAttribute('data-section');
        const isOpen = savedDropdownStates[sectionName] !== false; // Default open
        
        if (isOpen) {
            toggle.classList.add('active');
            const content = document.querySelector(`.dropdown-content[data-section="${sectionName}"]`);
            if (content) {
                content.classList.add('active');
            }
        }
    });
    
    // Toggle dropdown on click
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const sectionName = this.getAttribute('data-section');
            const dropdownContent = document.querySelector(`.dropdown-content[data-section="${sectionName}"]`);
            
            // Toggle active state
            this.classList.toggle('active');
            dropdownContent.classList.toggle('active');
            
            // Save state to localStorage
            savedDropdownStates[sectionName] = this.classList.contains('active');
            localStorage.setItem('dropdownStates', JSON.stringify(savedDropdownStates));
        });
    });
    
    // Close all dropdowns when sidebar is collapsed
    window.addEventListener('sidebarToggle', function(event) {
        if (event.detail.collapsed) {
            // Don't close dropdowns, just hide them via CSS
        }
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        // Ctrl/Cmd + B to toggle sidebar
        if ((event.ctrlKey || event.metaKey) && event.key === 'b') {
            event.preventDefault();
            toggleSidebar();
        }
        
        // Escape to close profile menu
        if (event.key === 'Escape' && profileMenu.classList.contains('active')) {
            profileMenu.classList.remove('active');
        }
    });
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Auto-collapse on small screens
            if (window.innerWidth < 768 && !isCollapsed) {
                toggleSidebar();
            }
        }, 250);
    });
    
    // Smooth scroll for nav items
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            // Add ripple effect
            const ripple = document.createElement('span');
            ripple.className = 'nav-ripple';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Add active state animation
    const activeNavItem = document.querySelector('.sidebar-nav-item.active');
    if (activeNavItem) {
        activeNavItem.style.animation = 'fadeIn 0.3s ease';
    }
    
    console.log('✅ Collapsible Sidebar initialized');
});

// CSS for ripple effect (add to app.css if needed)
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .nav-ripple {
        position: absolute;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.3);
        width: 100px;
        height: 100px;
        margin-top: -50px;
        margin-left: -50px;
        animation: ripple 0.6s;
        pointer-events: none;
    }
    
    @keyframes ripple {
        from {
            opacity: 1;
            transform: scale(0);
        }
        to {
            opacity: 0;
            transform: scale(2);
        }
    }
`;
document.head.appendChild(style);
