# Spec Delta: admin-dashboard

## Purpose
Provides an institutional School Management System administrative interface with modular Blade layout architecture, dedicated admin routing, collapsible sidebar navigation with tooltips, SweetAlert2 notifications, and KPI-rich dashboard telemetry.

## ADDED Requirements

### Requirement: Dedicated Admin Route File & Bootstrapping
The application must register and route administrative requests through a dedicated `routes/admin.php` file isolated from the default web route file.

#### Scenario: Admin routes registered in bootstrap/app.php
- **WHEN** the Laravel application boots
- **THEN** `routes/admin.php` is registered via the `then:` routing callback in `bootstrap/app.php` with web middleware
- **AND** all routes inside `routes/admin.php` are grouped under prefix `admin` and named route prefix `admin.`.

#### Scenario: Admin dashboard URL access
- **WHEN** an authenticated user or administrator visits `/admin/dashboard`
- **THEN** `App\Http\Controllers\Admin\AdminController@index` handles the request and returns HTTP 200 with the admin dashboard view.

---

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
The dashboard landing page must display authentic academic indicators, comparative analytics, and student records following clean, non-slop design standards.

#### Scenario: Primary KPI metrics rendering
- **WHEN** the dashboard page loads
- **THEN** four primary metrics cards are displayed: Total Students (e.g. 15.00K), Teachers (e.g. 2.00K), Parents (e.g. 5.6K), and Fee Earnings (e.g. $19.3K) using soft pastel accents (Violet, Sky, Peach, Emerald).

#### Scenario: Comparative exam results and distribution charts
- **WHEN** viewing the analytics grid
- **THEN** a monthly comparative bar chart compares student vs. teacher performance across Jan–Dec
- **AND** a student distribution radial/donut chart illustrates the male/female ratio with total headcount.

#### Scenario: Star students data table rendering
- **WHEN** viewing the Star Students section
- **THEN** a table renders recent top performers with checkboxes, student avatar, name, student ID, marks scored, percentage badge, and academic year.

---

### Requirement: SweetAlert2 Integration for Administrative Actions
The administrative dashboard must bundle and initialize SweetAlert2 for user feedback and confirmation dialogues.

#### Scenario: SweetAlert2 client-side availability
- **WHEN** the admin layout is loaded in the browser
- **THEN** `window.Swal` is available globally
- **AND** interactive action buttons (such as quick admissions, fee collection, or item deletion) trigger custom-styled SweetAlert2 modals.
