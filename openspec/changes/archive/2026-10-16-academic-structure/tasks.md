# Tasks

## 1. Layout & Blade Architecture (Sidebar Dropdown & Navigation)

- [x] 1.1 Update [sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php) under "Academic & Operations" to replace static alert links with a collapsible "Academic Structure" dropdown menu containing sub-links (Academic Years, Classes & Grades, Sections, Subjects, Assign Subjects, Groups/Streams).
- [x] 1.2 Update [admin-sidebar.js](file:///c:/laragon/www/laravel-school/public/admin-assets/js/admin-sidebar.js) with accordion toggle handling, smooth 180-degree chevron rotation, auto-expansion on `admin.academic.*` routes, and hover flyout handling when the sidebar is collapsed into desktop icon mode.
- [x] 1.3 Add dynamic route highlighting, active state badge styling, and clean 1px borders adhering to the `no-slop-ui` design standard.
- [x] 1.4 Update [navbar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/navbar.blade.php) Academic Calendar switcher to display the active academic session.

## 2. Database Migrations & Performance Indexes

- [x] 2.1 Create migration for `academic_years` table (`school_id`, `name`, `start_date`, `end_date`, `is_current`, `status`, `notes`) with unique constraint on `[school_id, name]` and composite index on `[school_id, is_current, status]`.
- [x] 2.2 Create migration for `academic_classes` table (`school_id`, `name`, `numeric_code`, `order_index`, `description`, `status`) with unique constraint on `[school_id, name]` and composite index on `[school_id, status, order_index]`.
- [x] 2.3 Create migration for `sections` table (`school_id`, `academic_class_id`, `name`, `capacity`, `room_number`, `status`) with foreign keys, unique constraint on `[school_id, academic_class_id, name]`, and index on `[school_id, academic_class_id, status]`.
- [x] 2.4 Create migration for `subjects` table (`school_id`, `name`, `code`, `type`, `credit_hours`, `status`, `description`) with unique constraints on `[school_id, code]` and `[school_id, name]`, and index on `[school_id, status, type]`.
- [x] 2.5 Create migration for `class_subject_assignments` table (`school_id`, `academic_class_id`, `subject_id`, `academic_year_id`, `type`, `full_marks`, `pass_marks`) with unique constraint on `[school_id, academic_class_id, subject_id, academic_year_id]` and index on `[school_id, academic_class_id, academic_year_id]`.
- [x] 2.6 Create migration for `academic_groups` table (`school_id`, `name`, `code`, `status`) with unique constraint on `[school_id, name]` and index on `[school_id, status]`.
- [x] 2.7 Run `php artisan migrate` and verify all 6 tables and composite performance indexes are created.

## 3. Eloquent Models, Scopes & Business Logic

- [x] 3.1 Create `AcademicYear` model utilizing `BelongsToSchool` trait, date casting, and an atomic `setAsCurrent()` method using database transactions.
- [x] 3.2 Create `AcademicClass` model utilizing `BelongsToSchool` trait, relations (`sections`, `subjects`), and scopes for ordering.
- [x] 3.3 Create `Section` model utilizing `BelongsToSchool` trait and `belongsTo(AcademicClass::class)`.
- [x] 3.4 Create `Subject` model utilizing `BelongsToSchool` trait and `belongsToMany(AcademicClass::class)`.
- [x] 3.5 Create `ClassSubjectAssignment` model with relationships to `AcademicClass`, `Subject`, and `AcademicYear`.
- [x] 3.6 Create `AcademicGroup` model with `BelongsToSchool` trait.
- [x] 3.7 Implement `AcademicSeeder` with realistic sample data (Academic years, Grades 1-12, Sections A/B, core subjects, science/commerce streams) for tenant initialization.

## 4. Controllers, Form Requests & Dependent Dropdown APIs

- [x] 4.1 Create tenant-aware Form Requests in `app/Http/Requests/Admin/Academic/` with unique tenant-aware validation rules and cross-school integrity checks.
- [x] 4.2 Create `AcademicYearController` for listing, creating, editing, and atomically toggling the current academic year.
- [x] 4.3 Create `AcademicClassController` for class management with single-query `withCount(['sections', 'subjects'])` aggregations.
- [x] 4.4 Create `SectionController` for section CRUD with class filtering.
- [x] 4.5 Create `SubjectController` for subject catalog management.
- [x] 4.6 Create `ClassSubjectAssignmentController` for curriculum assignment matrix management with bulk assign capabilities.
- [x] 4.7 Create `AcademicGroupController` for stream management.
- [x] 4.8 Create lightweight dependent dropdown JSON endpoints (`/admin/academic/classes/{class}/sections` and `/admin/academic/classes/{class}/subjects`).
- [x] 4.9 Register all academic routes under `admin.academic.*` in [routes/admin.php](file:///c:/laragon/www/laravel-school/routes/admin.php).

## 5. Blade Views & Interactive Frontend (No-Slop UI Protocol)

- [x] 5.1 Build `resources/views/admin/academic/years/` views (index with active badge and quick current-year toggle, create, edit).
- [x] 5.2 Build `resources/views/admin/academic/classes/` views (index with section count pills and subject count indicators, create, edit).
- [x] 5.3 Build `resources/views/admin/academic/sections/` views (index with class filter dropdown, create, edit).
- [x] 5.4 Build `resources/views/admin/academic/subjects/` views (index with type badges, code chips, create, edit).
- [x] 5.5 Build `resources/views/admin/academic/assign-subjects/` views (curriculum assignment matrix, multi-select subject assignment with marks configuration).
- [x] 5.6 Build `resources/views/admin/academic/groups/` views (stream management).
- [x] 5.7 Implement [academic-dependent-dropdown.js](file:///c:/laragon/www/laravel-school/public/admin-assets/js/academic-dependent-dropdown.js) for instant dynamic class-to-section dropdown loading.
- [x] 5.8 Integrate Select2 for all select elements, SweetAlert2 for delete confirmations, and ToastMagic for notifications.

## 6. Verification & Automated Testing

- [x] 6.1 Create automated Feature test `tests/Feature/AcademicStructureTest.php` verifying CRUD operations, tenant isolation, cross-school assignment prevention, active year atomic switching, and duplicate prevention.
- [x] 6.2 Execute PHPUnit test suite to verify 100% test pass rate.
- [x] 6.3 Run `vendor/bin/pint` to ensure full compliance with Laravel Pint formatting conventions.
