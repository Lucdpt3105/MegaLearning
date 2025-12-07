document.addEventListener('DOMContentLoaded', function () {
    // Feather icons
    if (window.feather) {
        feather.replace();
    }

    // Toggle submenu sidebar
    document.querySelectorAll('[data-sidebar-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var key = btn.getAttribute('data-sidebar-toggle');
            if (!key) return;

            var body = document.querySelector('[data-sidebar-body="' + key + '"]');
            if (!body) return;

            body.classList.toggle('hidden');

            var chev = document.querySelector('[data-sidebar-chevron="' + key + '"]');
            if (chev) {
                chev.classList.toggle('rotate-90');
            }
        });
    });
});
