/**
 * EduCore SIS - Admin Sidebar Controller
 * Handles sidebar collapse, mobile drawer, dropdown accordions, hover tooltips, and localStorage persistence.
 */
(function() {
    'use strict';

    // Global Dropdown Toggle Function (callable directly or via event)
    window.toggleAcademicSidebarDropdown = function(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        const body = document.getElementById('admin-body') || document.body;
        const sidebar = document.getElementById('admin-sidebar');
        const academicToggle = document.getElementById('sidebar-academic-toggle');
        const academicSubmenu = document.getElementById('sidebar-academic-submenu');
        const academicChevron = document.getElementById('academic-dropdown-chevron');

        if (!academicSubmenu) return;

        // If sidebar is currently collapsed (icon mode w-20), uncollapse it so submenu is fully visible
        if (body && body.classList.contains('sidebar-collapsed')) {
            body.classList.remove('sidebar-collapsed');
            if (sidebar) {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
            }
            try {
                localStorage.setItem('educore_sidebar_collapsed', 'false');
            } catch (_) {}
        }

        // Check if currently hidden
        const isHidden = academicSubmenu.classList.contains('hidden') || 
                         window.getComputedStyle(academicSubmenu).display === 'none' ||
                         academicSubmenu.style.display === 'none';

        if (isHidden) {
            academicSubmenu.classList.remove('hidden');
            academicSubmenu.classList.add('block');
            academicSubmenu.style.display = 'block';
            if (academicChevron) {
                academicChevron.classList.add('rotate-180');
                academicChevron.classList.remove('rotate-0');
            }
            if (academicToggle) {
                academicToggle.setAttribute('aria-expanded', 'true');
            }
        } else {
            academicSubmenu.classList.add('hidden');
            academicSubmenu.classList.remove('block');
            academicSubmenu.style.display = 'none';
            if (academicChevron) {
                academicChevron.classList.remove('rotate-180');
                academicChevron.classList.add('rotate-0');
            }
            if (academicToggle) {
                academicToggle.setAttribute('aria-expanded', 'false');
            }
        }
    };

    function initSidebar() {
        const body = document.getElementById('admin-body') || document.body;
        const sidebar = document.getElementById('admin-sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle-btn');
        const mobileCloseBtn = document.getElementById('mobile-sidebar-close-btn');
        const backdrop = document.getElementById('sidebar-backdrop');

        if (!sidebar) return;

        const STORAGE_KEY = 'educore_sidebar_collapsed';

        function applyCollapsedState(collapsed) {
            if (collapsed) {
                body.classList.add('sidebar-collapsed');
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
            } else {
                body.classList.remove('sidebar-collapsed');
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
            }
        }

        // 1. Restore persisted collapsed state on desktop
        try {
            const isCollapsed = localStorage.getItem(STORAGE_KEY) === 'true';
            if (isCollapsed && window.innerWidth >= 1024) {
                applyCollapsedState(true);
            }
        } catch (_) {}

        // 2. Desktop & Mobile Toggle Handler
        if (toggleBtn) {
            toggleBtn.onclick = function(e) {
                e.preventDefault();
                if (window.innerWidth < 1024) {
                    sidebar.classList.toggle('-translate-x-full');
                    if (backdrop) backdrop.classList.toggle('hidden');
                } else {
                    const currentlyCollapsed = body.classList.contains('sidebar-collapsed');
                    applyCollapsedState(!currentlyCollapsed);
                    try {
                        localStorage.setItem(STORAGE_KEY, (!currentlyCollapsed).toString());
                    } catch (_) {}
                }
            };
        }

        // 3. Mobile Drawer Close Button & Backdrop Click
        const closeMobileDrawer = function(e) {
            if (e) e.preventDefault();
            sidebar.classList.add('-translate-x-full');
            if (backdrop) backdrop.classList.add('hidden');
        };

        if (mobileCloseBtn) {
            mobileCloseBtn.onclick = closeMobileDrawer;
        }

        if (backdrop) {
            backdrop.onclick = closeMobileDrawer;
        }

        // 4. Initial Mobile State & Resize Handler
        if (window.innerWidth < 1024) {
            sidebar.classList.add('-translate-x-full');
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                if (backdrop) backdrop.classList.add('hidden');
                try {
                    const saved = localStorage.getItem(STORAGE_KEY) === 'true';
                    applyCollapsedState(saved);
                } catch (_) {}
            } else {
                body.classList.remove('sidebar-collapsed');
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                sidebar.classList.add('-translate-x-full');
            }
        });

        // 5. Sidebar Dropdown Accordion (Academic Structure)
        const academicToggle = document.getElementById('sidebar-academic-toggle');
        if (academicToggle) {
            academicToggle.onclick = function(e) {
                window.toggleAcademicSidebarDropdown(e);
            };
        }
    }

    // Global Click Delegation (Catch any click on academic toggle or its children)
    document.addEventListener('click', function(e) {
        const toggle = e.target.closest('#sidebar-academic-toggle');
        if (toggle) {
            window.toggleAcademicSidebarDropdown(e);
        }
    });

    // Run on DOM ready or immediately if already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebar);
    } else {
        initSidebar();
    }
})();
