# Tasks

## 1. Routing & Authorization Gates

- [x] 1.1 Register Super Admin school management routes in `routes/admin.php` under `admin.auth` middleware (`index`, `show`, `status`).
- [x] 1.2 Register institutional settings routes in `routes/admin.php` under `admin.auth` middleware (`GET /admin/settings/school`, `PUT /admin/settings/school`).
- [x] 1.3 Add Super Admin authorization barrier/middleware to prevent non-super-admin access to `/admin/schools*`.

## 2. Form Requests & Controllers

- [x] 2.1 Create `App\Http\Requests\Admin\UpdateSchoolSettingRequest` with comprehensive validation rules for institutional fields.
- [x] 2.2 Create `App\Http\Requests\Admin\UpdateSchoolStatusRequest` validating status against backed `App\Enums\SchoolStatus`.
- [x] 2.3 Implement `App\Http\Controllers\Admin\SchoolSettingController` to view and update the active institution's profile.
- [x] 2.4 Implement `App\Http\Controllers\Admin\SchoolController` to manage the platform school directory, detailed inspection, and status transitions.

## 3. Blade Views & Institutional UI Components

- [x] 3.1 Create `resources/views/admin/settings/school.blade.php` adhering to No-Slop UI standards with structured cards for General Info, Contact, Localization, and Branding.
- [x] 3.2 Create `resources/views/admin/schools/index.blade.php` with high-information-density table, status filter tabs, search filter, and pagination.
- [x] 3.3 Create `resources/views/admin/schools/show.blade.php` featuring institutional metadata summary, contact details, regional settings, and status switcher.

## 4. Sidebar & Layout Navigation Wiring

- [x] 4.1 Wire the "Schools & Campuses" item in `resources/views/layouts/admin-partial/sidebar.blade.php` to `route('admin.schools.index')` with active link highlighting.
- [x] 4.2 Wire the "Settings" item in `resources/views/layouts/admin-partial/sidebar.blade.php` to `route('admin.settings.school')` with active link highlighting.
- [x] 4.3 Verify that existing login (`login.blade.php`) and dashboard (`dashboard.blade.php`) templates remain strictly unmodified.

## 5. Verification & Testing

- [x] 5.1 Seed multiple sample schools using `SchoolFactory` with varied statuses (`active`, `pending`, `suspended`, `inactive`) and test status tab filtering.
- [x] 5.2 Validate school profile editing and database persistence via `/admin/settings/school`.
- [x] 5.3 Test Super Admin status mutation via `PATCH /admin/schools/{school}/status` and verify SweetAlert2 feedback.
- [x] 5.4 Run Laravel Pint and PHPUnit suite to verify code formatting and regression-free test passes.
