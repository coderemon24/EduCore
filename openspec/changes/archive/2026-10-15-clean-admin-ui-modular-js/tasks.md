# Tasks

## 1. Feature-Wise JavaScript Extraction

- [x] 1.1 Create `public/admin-assets/js/admin-sidebar.js` for sidebar collapse, mobile drawer, tooltips, and localStorage persistence
- [x] 1.2 Create `public/admin-assets/js/admin-search.js` for keyboard shortcut (`⌘K` / `Ctrl+K`) and search input focus handling
- [x] 1.3 Create `public/admin-assets/js/admin-actions.js` for SweetAlert2 modal prompts (admission modal, fee lookup, export, alerts)

## 2. Clean Navbar Redesign & AI Slop Removal

- [x] 2.1 Refactor `resources/views/layouts/admin-partial/navbar.blade.php` into a minimal, non-hijibiji layout (toggle, compact search, action icon, static bell, avatar)
- [x] 2.2 Remove pulsing animations (`animate-ping`, `animate-pulse`) and redundant duplicate action buttons

## 3. Master Layout & Script Integration

- [x] 3.1 Remove monolithic inline `<script>` from `resources/views/admin.blade.php`
- [x] 3.2 Include `admin-sidebar.js`, `admin-search.js`, and `admin-actions.js` via `asset()` with `defer` in `resources/views/admin.blade.php`

## 4. Verification & Quality Assurance

- [x] 4.1 Update `tests/Feature/AdminDashboardTest.php` to verify clean navbar elements and modular script tags
- [x] 4.2 Run PHPUnit tests and Laravel Pint code styling verification
