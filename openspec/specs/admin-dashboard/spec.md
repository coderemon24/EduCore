# admin-dashboard Specification

## Purpose
Provides an institutional School Management System administrative interface with modular Blade layout architecture, dedicated admin routing, collapsible sidebar navigation with tooltips, SweetAlert2 notifications, and KPI-rich dashboard telemetry.

## Requirements

### Requirement: Dedicated Admin Route File & Bootstrapping
The application MUST register and route administrative requests through a dedicated `routes/admin.php` file isolated from the default web route file, with administrative routes protected by authentication middleware.

#### Scenario: Admin routes registered in bootstrap/app.php
- **WHEN** the Laravel application boots
- **THEN** `routes/admin.php` is registered via the `then:` routing callback in `bootstrap/app.php` with web middleware
- **AND** all routes inside `routes/admin.php` are grouped under prefix `admin` and named route prefix `admin.`.

#### Scenario: Admin dashboard URL access
- **WHEN** an authenticated administrator visits `/admin/dashboard`
- **THEN** `App\Http\Controllers\Admin\AdminController@index` handles the request and returns HTTP 200 with the admin dashboard view
- **AND** unauthenticated visitors attempting to access `/admin/dashboard` MUST be redirected to `/admin/login`.

### Requirement: Admin Master Blade Template & Modular Partials
The admin layout must follow strict template mastering conventions located directly at `resources/views/admin.blade.php` and modularize reusable structural components inside `resources/views/layouts/admin-partial/`.

#### Scenario: Master template renders required partials and content yield
- **WHEN** any admin page extending `admin.blade.php` is rendered
- **THEN** it includes `layouts.admin-partial.head`, `layouts.admin-partial.navbar`, `layouts.admin-partial.sidebar`, and `layouts.admin-partial.footer`
- **AND** it injects the page view through the directive `@yield('admin-content')`.

---

### Requirement: Collapsible Sidebar Navigation with Hover Tooltips
The admin sidebar must support state toggling between full expanded mode and compact collapsed mode upon clicking the navbar hamburger button.

#### Scenario: Expanded state displays full branding and labels
- **WHEN** the sidebar is in expanded state
- **THEN** it displays the school logo from `public/admin-assets/images/laravel-school-transparent.png`, navigation category titles, text labels, and section chevrons with a width of 260px (`w-64`).

#### Scenario: Collapsed state displays icons and hover tooltips
- **WHEN** the hamburger menu is clicked to collapse the sidebar
- **THEN** the sidebar width shrinks to compact icon-only mode (~72px / `w-20`)
- **AND** text labels and sub-chevrons are hidden
- **AND** hovering over any navigation item displays a floating tooltip with the menu title and badge positioned to the right of the icon.

---

### Requirement: High-Information-Density School KPI Dashboard UI
The dashboard landing page MUST display authentic academic indicators, comparative analytics, and student records following strict no-slop institutional design standards, completely avoiding pseudo-scientific jargon, arbitrary pastel color clashes, and fake inline scripts.

#### Scenario: Primary KPI metrics rendering
- **WHEN** the dashboard page loads
- **THEN** four primary metrics cards are displayed: Total Students, Teachers & Faculty, Registered Parents, and Fee Collections
- **AND** cards MUST utilize cohesive, solid 1px borders (`border-slate-200`) and subtle, semantic status badges rather than mismatched pastel border tints
- **AND** cards MUST avoid arbitrary all-caps sub-label typography with exaggerated letter tracking.

#### Scenario: Comparative exam results and distribution charts
- **WHEN** viewing the analytics grid
- **THEN** the performance overview displays academic indicators comparing student performance with clear institutional labels
- **AND** student distribution demographics are rendered with clean semantic progress elements and authentic typography instead of brittle, hardcoded SVG math.

#### Scenario: Star students data table rendering
- **WHEN** viewing the Star Students section
- **THEN** a clean data table renders top performers with student avatar/initials, name, grade, roll ID, marks, percentage badge, and academic year
- **AND** dummy non-functional checkboxes MUST be omitted
- **AND** action triggers MUST avoid inline `onclick="Swal.fire(...)"` handlers and instead link to legitimate profiles or use data attributes handled by modular JavaScript.

---

### Requirement: Prohibition of AI Slop Artifacts in Dashboard Blade Template
The dashboard Blade view MUST adhere strictly to the "No-Slop UI" design protocol, eliminating artificial buzzwords, mock inline JavaScript popups, and visual clutter.

#### Scenario: Authentic institutional terminology
- **WHEN** reading page titles, section headers, and breadcrumbs
- **THEN** text MUST use direct, human-crafted terms ("Dashboard Overview", "School Administration", "Academic Performance")
- **AND** MUST avoid hyperbolic AI buzzwords ("Academic Telemetry", "Financial Realization", "Curriculum Milestone Targets").

#### Scenario: Clean script delegation
- **WHEN** interacting with dashboard action buttons (e.g. Export, Admission, Student Profile)
- **THEN** interactions MUST be handled unobtrusively via external modular scripts (`admin-actions.js`) using semantic data attributes or direct URL navigation
- **AND** no inline `onclick="window.Swal && ..."` scripts MUST be present within `dashboard.blade.php`.

---

### Requirement: SweetAlert2 Integration for Administrative Actions
The administrative dashboard must bundle and initialize SweetAlert2 for user feedback and confirmation dialogues.

#### Scenario: SweetAlert2 client-side availability
- **WHEN** the admin layout is loaded in the browser
- **THEN** `window.Swal` is available globally
- **AND** interactive action buttons (such as quick admissions, fee collection, or item deletion) trigger custom-styled SweetAlert2 modals.

---

### Requirement: Minimalist and Clean Navbar Architecture
The administrative navbar must eliminate visual clutter ("hijibiji") and adopt a "minimum text, maximum understanding" layout.

#### Scenario: Minimal navbar layout rendering
- **WHEN** the administrative portal header renders
- **THEN** it displays only essential controls: hamburger sidebar toggle, compact search input with keyboard shortcut hint (`⌘K`), quick-action trigger, clean notification bell with a static indicator dot, and an avatar profile button
- **AND** it omits redundant textual session badges, verbose user role labels, and bulky page-specific action buttons.

#### Scenario: Static non-pulsing alert indicator
- **WHEN** there are unread administrative notifications
- **THEN** the notification bell displays a solid, static indicator badge
- **AND** no infinite CSS pulse or ping animations are rendered.

---

### Requirement: Feature-Wise Modular JavaScript Assets
Administrative layout and dashboard interactions must be structured into dedicated, modular JavaScript files stored in `public/admin-assets/js/` rather than hardcoded inline within Blade templates.

#### Scenario: Script modularization and separation of concerns
- **WHEN** the admin master layout `admin.blade.php` is rendered
- **THEN** it includes `admin-sidebar.js` for sidebar collapse and mobile drawer management
- **AND** it includes `admin-search.js` for global keyboard shortcut focus handling
- **AND** it includes `admin-actions.js` for SweetAlert2 modal dialogues and interactive prompts
- **AND** no inline JavaScript exceeding layout bootstrapping is embedded in `admin.blade.php`.

#### Scenario: Sidebar state persistence across page loads
- **WHEN** a user toggles the sidebar state in desktop mode
- **THEN** `admin-sidebar.js` saves the preference to `localStorage` under `educore_sidebar_collapsed`
- **AND** restores this state on subsequent navigation.
