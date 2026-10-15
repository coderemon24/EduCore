# Design: School Admin Dashboard UI & Template Architecture

## Context

The application is built on Laravel 12 with Vite 7 and Tailwind CSS v4 (`@tailwindcss/vite`). The user requires a strict template mastering directory structure, a dedicated route file (`routes/admin.php`) registered via `bootstrap/app.php`, an `AdminController` to serve the view, a collapsible sidebar with icon-only tooltips, SweetAlert2 integration, and a high-information-density dashboard inspired by modern academic SIS systems.

## Goals / Non-Goals

**Goals:**
- Implement dedicated `routes/admin.php` registered in `bootstrap/app.php` using Laravel 12's `then:` routing callback.
- Build `App\Http\Controllers\Admin\AdminController` to serve the admin dashboard landing page.
- Establish strict Blade template mastering: `resources/views/admin.blade.php` with partials located at `resources/views/layouts/admin-partial/` (`head.blade.php`, `navbar.blade.php`, `sidebar.blade.php`, `footer.blade.php`) and content injection via `@yield('admin-content')`.
- Create a fully collapsible sidebar (260px expanded down to 72px collapsed) that toggles via the navbar hamburger menu and presents floating tooltips on icon hover when collapsed.
- Install and configure SweetAlert2 for modern alerts and interactive dialogue prompts.
- Render high-utility dashboard components: 4 soft-tinted KPI cards (Students, Teachers, Parents, Earnings), comparative exam results bar chart, student ratio donut chart, star students data table, and bulletins/notices feed.
- Reference the institution logo from `public/admin-assets/images/laravel-school-transparent.png`.

**Non-Goals:**
- Full database CRUD models or multi-guard authentication mechanisms (scoped to landing page and dashboard functionality for this change).
- External heavy charting libraries (charting is implemented with lightweight, crisp, responsive SVG components).

## Decisions

### 1. Route Registration via `bootstrap/app.php` `then:` Callback
- **Decision**: In `bootstrap/app.php`, append a `then:` closure to `->withRouting()` that attaches `base_path('routes/admin.php')` wrapped in `'web'` middleware.
- **Rationale**: This is the idiomatic Laravel 12 convention for modular secondary route files, keeping `routes/admin.php` strictly isolated from general public web routes.
- **Alternative Considered**: Grouping admin routes inside `routes/web.php` with `Route::prefix('admin')` — rejected because user explicitly required an isolated `routes/admin.php` registered in `bootstrap/app.php`.

### 2. Blade Template Hierarchy & Partial Segregation
- **Decision**: 
  - Master file: `resources/views/admin.blade.php` (contains HTML skeleton, includes partials, and declares `@yield('admin-content')`).
  - Partials folder: `resources/views/layouts/admin-partial/` containing:
    - `head.blade.php`
    - `navbar.blade.php`
    - `sidebar.blade.php`
    - `footer.blade.php`
  - Admin view folder: `resources/views/admin/` containing `dashboard.blade.php` extending `admin.blade.php`.
- **Rationale**: Exactly adheres to the user's template mastering requirements and provides modular reusability for upcoming CRUD pages.

### 3. Collapsible Sidebar with Floating Hover Tooltips
- **Decision**: 
  - The sidebar wrapper uses a data attribute (`data-collapsed="false"`) or class-based toggle (`is-collapsed`).
  - When expanded: `w-64` (256px), text labels visible, chevron icons visible.
  - When collapsed: `w-20` (80px), text labels hidden (`hidden`), logo switches to compact mark or centered icon.
  - Floating Tooltips: Every navigation link contains an absolute-positioned tooltip container (`absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs rounded-md shadow-lg pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap`). This is enabled specifically when the parent sidebar has the collapsed state active.
- **Rationale**: Meets the user's specific request for icon-only display with titles visible on hover, without requiring heavy UI dependencies.

### 4. KPI Cards & Visual Styling
- **Decision**: Inspired by the reference design:
  - Students Card: Soft violet/indigo pill (`bg-violet-50 text-violet-700 border-violet-100`).
  - Teachers Card: Soft sky blue pill (`bg-sky-50 text-sky-700 border-sky-100`).
  - Parents Card: Soft peach/amber pill (`bg-amber-50 text-amber-700 border-amber-100`).
  - Earnings/Fees Card: Soft mint/emerald pill (`bg-emerald-50 text-emerald-700 border-emerald-100`).
- **Rationale**: Vibrant, accessible, human-crafted aesthetics that conform to the "no-slop" protocol.

### 5. SweetAlert2 Setup
- **Decision**: Install `sweetalert2` via npm, import in `resources/js/app.js`, expose on `window.Swal`, and configure custom toast and modal presets.
- **Rationale**: Enables instant rich feedback for actions like "+ New Admission", "Export", and "View Details" without reload.

## Risks / Trade-offs

- **[Tailwind v4 Dynamic Classes]** → Dynamic class names constructed at runtime can be skipped by Tailwind v4's compiler.
  - *Mitigation*: Write full static utility classes in Blade templates so the Vite compiler extracts all necessary styles reliably.
- **[Sidebar Collapse Transition Layout Shift]** → Expanding or collapsing the sidebar could cause abrupt content reflow.
  - *Mitigation*: Apply `transition-all duration-200 ease-in-out` on the sidebar width and main content margin offset.
