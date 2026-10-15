# Tasks

## 1. Routing & Controller Setup

- [x] 1.1 Create `routes/admin.php` with prefix `admin` and name `admin.`
- [x] 1.2 Register `routes/admin.php` in `bootstrap/app.php` using the `then:` routing callback
- [x] 1.3 Create `App\Http\Controllers\Admin\AdminController` to provide telemetry and render the admin dashboard

## 2. Blade Layout & Modular Partials

- [x] 2.1 Create `resources/views/layouts/admin-partial/head.blade.php` with typography, asset directives, and styles
- [x] 2.2 Create `resources/views/layouts/admin-partial/navbar.blade.php` with hamburger toggle, search, notifications, and profile
- [x] 2.3 Create `resources/views/layouts/admin-partial/sidebar.blade.php` with logo, navigation links, and hover tooltips for collapsed mode
- [x] 2.4 Create `resources/views/layouts/admin-partial/footer.blade.php` with copyright and system status
- [x] 2.5 Create master layout `resources/views/admin.blade.php` with `@yield('admin-content')` and sidebar collapse state management

## 3. Dashboard View & UI Components

- [x] 3.1 Create `resources/views/admin/dashboard.blade.php` extending `admin.blade.php` with breadcrumbs and action triggers
- [x] 3.2 Implement KPI telemetry cards (Students, Teachers, Parents, Earnings) with pastel accents
- [x] 3.3 Implement comparative exam results chart and student gender ratio donut chart
- [x] 3.4 Implement Star Students data table and institutional notice board widget

## 4. SweetAlert2 Integration & Asset Compilation

- [x] 4.1 Install and configure SweetAlert2 in `resources/js/app.js` and expose on `window.Swal`
- [x] 4.2 Wire SweetAlert2 notification dialogues to quick action buttons in dashboard and navbar
- [x] 4.3 Build frontend assets using Vite (`npm run build`)

## 5. Verification & Quality Assurance

- [x] 5.1 Run route list and automated tests to verify admin routing and HTTP 200 response on `/admin/dashboard`
- [x] 5.2 Run Laravel Pint code styling verification and verify sidebar collapse and tooltips