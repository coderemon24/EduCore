/**
 * EduCore SIS - Admin Search Controller
 * Handles global search shortcut (⌘K / Ctrl+K), auto-focus, quick navigation suggestions, and keyboard dismissals.
 */
(() => {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('global-search-input');
        const searchResults = document.getElementById('search-quick-results');

        if (!searchInput) return;

        // Open quick results on focus or input
        const showResults = () => {
            if (searchResults) searchResults.classList.remove('hidden');
        };

        const hideResults = () => {
            if (searchResults) searchResults.classList.add('hidden');
        };

        searchInput.addEventListener('focus', showResults);
        searchInput.addEventListener('input', showResults);

        // Global keyboard shortcut: Cmd+K or Ctrl+K to focus search
        window.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
                showResults();
            } else if (e.key === 'Escape') {
                hideResults();
                if (document.activeElement === searchInput) {
                    searchInput.blur();
                }
            }
        });

        // Click outside search container closes quick results
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && (!searchResults || !searchResults.contains(e.target))) {
                hideResults();
            }
        });
    });
})();
