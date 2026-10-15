# Design: Clean Admin UI & Modular JavaScript Architecture

## Context

See `proposal.md` for background and motivation. The layout currently has over 200 lines of inline JavaScript in `resources/views/admin.blade.php` and an overcrowded ("hijibiji") navbar in `resources/views/layouts/admin-partial/navbar.blade.php` containing 8 disparate elements, bulky buttons, and pulsating animated indicators.

## Goals / Non-Goals

**Goals:**
- Separate client-side behavior into modular, feature-scoped JavaScript files under `public/admin-assets/js/`.
- Redesign `resources/views/layouts/admin-partial/navbar.blade.php` following "minimum text, maximum understanding" principles.
- Eliminate AI slop clichés (pulsing animations, repetitive badges, and duplicate action buttons).
- Maintain 100% functionality for sidebar toggling, search shortcut (`⌘K`), and SweetAlert2 interactive actions.

**Non-Goals:**
- Reworking backend models or database queries.
- Rewriting the dashboard telemetry cards or chart rendering logic.

## Decisions

### 1. Modular Feature Scripts under `public/admin-assets/js/`
- **Decision**: Split the script logic into three discrete files:
  1. `public/admin-assets/js/admin-sidebar.js`: Desktop toggle (`w-64` vs `w-20`), mobile drawer translate transitions, backdrop click-away, and `localStorage` state management (`educore_sidebar_collapsed`).
  2. `public/admin-assets/js/admin-search.js`: Global keyboard listener for `⌘K` / `Ctrl+K`, search input auto-focus and escape blur.
  3. `public/admin-assets/js/admin-actions.js`: SweetAlert2 dialog bindings for student admission prompt, fee lookup prompt, and notification alerts.
- **Rationale**: Keeps `admin.blade.php` strictly semantic HTML, allows modular browser caching, and enables clear unit debugging.
- **Alternatives Considered**: Bundling everything into `resources/js/app.js` with Vite. Rejected because vanilla administrative interactions in `admin-assets/` are directly editable and don't require bundling overhead for minor tweaks.

### 2. Streamlined Navbar Layout
- **Decision**:
  - **Left Section**: Minimal hamburger toggle (`#sidebar-toggle-btn`) and a clean, quiet search bar with shortcut key badge (`⌘K`).
  - **Right Section**:
    - Quick Action icon button (`+`) or clean action trigger.
    - Notification bell with a static 6px dot (`bg-rose-500` without `animate-ping`).
    - Minimal avatar circle (initials `AD` or photo) with simple chevron, omitting multi-line names and roles.
- **Rationale**: Removes visual clutter, eliminates duplicate action triggers, and achieves high contrast with breathing room.

### 3. Loading with Asset Versioning
- **Decision**: In `resources/views/admin.blade.php`, load scripts at the bottom with `defer` and optional cache-busting:
  ```html
  <script src="{{ asset('admin-assets/js/admin-sidebar.js') }}" defer></script>
  <script src="{{ asset('admin-assets/js/admin-search.js') }}" defer></script>
  <script src="{{ asset('admin-assets/js/admin-actions.js') }}" defer></script>
  ```
- **Rationale**: Non-blocking page render with clean separation.

## Risks / Trade-offs

- **[Browser Caching of Public Assets]** → In development, browser caching might serve older versions of the scripts.
  - *Mitigation*: Scripts will be written clean and modular, and version query strings (`?v=1.0`) can be attached if needed.
- **[SweetAlert2 Availability Timing]** → If `admin-actions.js` runs before Vite's `app.js` initializes `window.Swal`, modal calls could fail.
  - *Mitigation*: `admin-actions.js` will verify `window.Swal` presence or register click listeners inside `DOMContentLoaded`.
