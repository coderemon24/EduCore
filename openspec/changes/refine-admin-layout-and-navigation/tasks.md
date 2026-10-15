# Tasks

## 1. Layout & Blade Architecture

- [x] 1.1 Restructure `resources/views/admin.blade.php` root container with `h-screen overflow-hidden flex` on desktop, pinning the sidebar and assigning `#admin-main-content` an independent `flex-1 h-screen overflow-y-auto` scroll container.
- [x] 1.2 Update `resources/views/layouts/admin-partial/sidebar.blade.php` container classes to `h-screen flex flex-col shrink-0` with internal `flex-1 overflow-y-auto` navigation scroll container that activates smoothly when items exceed viewport height.
- [x] 1.3 Update `resources/views/layouts/admin-partial/head.blade.php` and `resources/views/layouts/admin.blade.php` to support independent scroll containers, slim scrollbars, global cursor pointer rules, and collapsed sidebar styling.

## 2. Sidebar Menu Optimization & Typography

- [x] 2.1 Set navigation item links in `sidebar.blade.php` to 14px font size (`text-sm`) with `cursor-pointer`, scaled matching SVG icons (20px / `h-5 w-5`), and touch-friendly padding.
- [x] 2.2 Optimize and streamline all sidebar menu labels to concise, clean terms: "Overview", "Students", "Teachers", "Parents", "Classes", "Attendance", "Examinations", "Fees & Billing", "Notice Board", "Settings".
- [x] 2.3 Remove all `.nav-badge` badge spans and indicator tags completely from the sidebar navigation items.
- [x] 2.4 Update collapsed mode floating tooltips (`.nav-item-tooltip`) in `sidebar.blade.php` to reflect the optimized labels without badges.

## 3. Navbar Academic Calendar Switcher

- [x] 3.1 Implement the Academic Calendar session widget in `resources/views/layouts/admin-partial/navbar.blade.php` displaying the current active academic year (e.g., "2025–2026 Academic Year • Term 1") with calendar iconography and a dropdown toggle.
- [x] 3.2 Build the interactive calendar switcher dropdown listing available academic years and terms (e.g. "2025–2026 (Active)", "2024–2025 (Archived)", "2026–2027 (Upcoming)") with quick selection actions.

## 4. Navbar Profile Dropdown with Name & Designation

- [x] 4.1 Redesign the profile button in `resources/views/layouts/admin-partial/navbar.blade.php` into a composite trigger displaying the user avatar alongside the user's Full Name ("Arthur Vance") and Designation ("Super Administrator" / "Principal") on the right.
- [x] 4.2 Build the interactive user profile dropdown menu containing user details, "Profile", "Change Password", and "Logout" action triggers.
- [x] 4.3 Add click-outside and keyboard (Escape) listener logic to close navbar dropdowns seamlessly.

## 5. Interactive Behaviors & JavaScript Wiring

- [x] 5.1 Update `public/admin-assets/js/admin-sidebar.js` to ensure sidebar collapse/expand transitions and localStorage persistence smoothly synchronize with the fixed flex layout.
- [x] 5.2 Wire Academic Calendar session switching and Profile dropdown interactions into `public/admin-assets/js/admin-actions.js` with SweetAlert2 notifications and session updates.

## 6. Verification & Visual QA

- [x] 6.1 Test layout scrolling on desktop viewports: verify the sidebar stays fixed while the main dashboard body scrolls independently.
- [x] 6.2 Test vertical height constraint on sidebar: verify the internal navigation container scrolls smoothly when viewport height is reduced.
- [x] 6.3 Verify all sidebar menu item labels are concise, badges are completely absent, and text font size is at least 16px (`text-base`).
- [x] 6.4 Test navbar Academic Calendar switcher and Profile dropdown: verify open/close triggers, selection changes, and logout confirmation.
