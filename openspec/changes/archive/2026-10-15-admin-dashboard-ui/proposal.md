# Proposal: School Admin Dashboard UI & Template Architecture

## Why

The school management platform requires an authoritative administrative interface for institutional leadership (principals, academic heads, and administrators). A robust, high-information-density dashboard serves as the central control room for academic telemetry, student admissions, faculty metrics, financial realization, and daily operations.

Previous scaffolding lacked the specific modular layout hierarchy requested by the project architecture (namely: `resources/views/admin.blade.php` as master layout, partials isolated under `resources/views/layouts/admin-partial/`, dedicated `routes/admin.php` registered in `bootstrap/app.php`, and `Admin/AdminController.php`). Furthermore, the UI needs to be upgraded to a premium, non-slop design inspired by modern academic SIS systems, featuring a collapsible icon-only sidebar with hover tooltips, SweetAlert2 integration, and authentic data visualizations.

## What Changes

1. **Routing & Bootstrapping**:
   - Create a dedicated admin route file at `routes/admin.php` with route group prefix `'admin'` and name `'admin.'`.
   - Register `routes/admin.php` cleanly in `bootstrap/app.php` using the Laravel 12 `then:` routing closure.
2. **Controller Architecture**:
   - Create `app/Http/Controllers/Admin/AdminController.php` to serve the admin dashboard landing page with rich domain telemetry (students, faculty, attendance, earnings, star students, exam comparisons).
3. **Template Mastering & Modular Partials**:
   - Create `resources/views/admin.blade.php` directly inside `resources/views/` as the master layout.
   - Scaffold the partials directory at `resources/views/layouts/admin-partial/` containing:
     - `head.blade.php` (meta, Google Fonts, Tailwind Vite assets, SweetAlert2)
     - `navbar.blade.php` (hamburger toggle, global search, notification badges, admin profile)
     - `sidebar.blade.php` (collapsible navigation with brand logo from `admin-assets/images/laravel-school-transparent.png`, icons, and hover tooltips)
     - `footer.blade.php` (institutional copyright, system status indicator)
   - Establish `@yield('admin-content')` in `admin.blade.php`.
   - Create `resources/views/admin/dashboard.blade.php` extending `admin.blade.php`.
4. **Sidebar Collapsible Mechanics**:
   - Sidebar supports full expanded mode (`w-64`) and compact icon-only mode (`w-20` / `w-[72px]`).
   - In collapsed mode, labels hide and hovering over menu items presents a tooltip with the menu title and badge.
5. **UI & Aesthetics**:
   - Implement clean institutional palette (Violet, Sky, Peach, Emerald pastel accents for KPI metric cards; clean white cards with hairline slate borders).
   - Star Students data table with roll IDs, marks, percentage, year, and action triggers.
   - Exam Results comparative bimodal bar chart and student gender ratio donut chart.
   - Install and configure SweetAlert2 for notifications and interactive confirmation dialogues.
   - Use logo from `public/admin-assets/images/laravel-school-transparent.png`.

## Capabilities

### New Capabilities
- `admin-dashboard`: Administrative dashboard portal and layout infrastructure, including dedicated `admin.php` routing, `AdminController`, master Blade template with `admin-partial` directory, collapsible sidebar with hover tooltips, SweetAlert2 integration, and school metrics dashboard UI.

### Modified Capabilities
None.

## Impact

- **Routes**: New route file `routes/admin.php` registered in `bootstrap/app.php`.
- **Controllers**: `app/Http/Controllers/Admin/AdminController.php`.
- **Views**: `resources/views/admin.blade.php`, `resources/views/layouts/admin-partial/*`, `resources/views/admin/dashboard.blade.php`.
- **Dependencies**: `sweetalert2` installed via npm and configured in `resources/js/app.js`.
- **Assets**: Brand assets referenced from `public/admin-assets/images/`.
