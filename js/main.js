/* =========================================================
   Forces Academy LMS — main.js
   Two independent, self-contained features:
   1. Mobile off-canvas sidebar toggle
   2. Password visibility toggle (eye icon)
   Neither touches form submission, field names, or PHP logic —
   purely presentational behavior.
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    // ---------- 1. Mobile sidebar off-canvas ----------
    var sidebar = document.querySelector('.fa-sidebar');
    var toggleBtn = document.querySelector('.fa-sidebar-toggle');
    var overlay = document.querySelector('.fa-sidebar-overlay');

    if (sidebar && toggleBtn && overlay) {
        function openSidebar() {
            sidebar.classList.add('is-open');
            overlay.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('is-open');
            overlay.classList.remove('is-open');
            document.body.style.overflow = '';
        }

        toggleBtn.addEventListener('click', function () {
            if (sidebar.classList.contains('is-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        overlay.addEventListener('click', closeSidebar);

        // Close the drawer automatically if the viewport is resized back to desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth > 767.98) {
                closeSidebar();
            }
        });
    }

    // ---------- 2. Password visibility toggle ----------
    var toggles = document.querySelectorAll('.fa-password-toggle');

    toggles.forEach(function (btn) {
        var wrap = btn.closest('.fa-password-wrap');
        if (!wrap) return;
        var input = wrap.querySelector('input');
        if (!input) return;

        btn.addEventListener('click', function () {
            var isVisible = input.type === 'text';
            input.type = isVisible ? 'password' : 'text';
            btn.classList.toggle('is-visible', !isVisible);
            btn.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
        });
    });

});
