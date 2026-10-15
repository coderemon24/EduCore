/**
 * EduCore SIS - Admin Sidebar Controller
 * Handles sidebar collapse, mobile drawer, hover tooltips, and localStorage persistence.
 */
(() => {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
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
        const isCollapsed = localStorage.getItem(STORAGE_KEY) === 'true';
        if (isCollapsed && window.innerWidth >= 1024) {
            applyCollapsedState(true);
        }

        // 2. Desktop & Mobile Toggle Handler
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    sidebar.classList.toggle('-translate-x-full');
                    if (backdrop) backdrop.classList.toggle('hidden');
                } else {
                    const currentlyCollapsed = body.classList.contains('sidebar-collapsed');
                    applyCollapsedState(!currentlyCollapsed);
                    localStorage.setItem(STORAGE_KEY, (!currentlyCollapsed).toString());
                }
            });
        }

        // 3. Mobile Drawer Close Button & Backdrop Click
        const closeMobileDrawer = () => {
            sidebar.classList.add('-translate-x-full');
            if (backdrop) backdrop.classList.add('hidden');
        };

        if (mobileCloseBtn) {
            mobileCloseBtn.addEventListener('click', closeMobileDrawer);
        }

        if (backdrop) {
            backdrop.addEventListener('click', closeMobileDrawer);
        }

        // 4. Initial Mobile State & Resize Handler
        if (window.innerWidth < 1024) {
            sidebar.classList.add('-translate-x-full');
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                if (backdrop) backdrop.classList.add('hidden');
                const saved = localStorage.getItem(STORAGE_KEY) === 'true';
                applyCollapsedState(saved);
            } else {
                body.classList.remove('sidebar-collapsed');
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                sidebar.classList.add('-translate-x-full');
            }
        });
    });
})();
