# Tasks

## 1. Layout & Sidebar Synchronization

- [x] 1.1 Update `resources/views/layouts/admin-partial/sidebar.blade.php` to link the Parents menu item directly to `route('admin.guardians.index')`.
- [x] 1.2 Implement active route matching (`request()->routeIs('admin.guardians.*')`) with standard violet highlight styling and tooltip text.
- [x] 1.3 Remove the static `onclick` SweetAlert placeholder from the Parents sidebar navigation item.

## 2. Roles, Permissions & Routing

- [x] 2.1 Register `guardians.view`, `guardians.manage`, and `guardians.delete` permissions in `database/seeders/RolePermissionSeeder.php` and attach to administrative roles.
- [x] 2.2 Register the `admin/guardians` route resource in `routes/admin.php` with permission middleware and restore/force-delete endpoints.

## 3. Controllers & Business Logic

- [x] 3.1 Create `App\Http\Controllers\Admin\GuardianController` implementing `index`, `show`, `edit`, `update`, `destroy`, `restore`, and `forceDelete`.
- [x] 3.2 Implement multi-facet keyword search (guardian name, phone, email, student name, admission number) and status filtering (All, Active, Archived).
- [x] 3.3 Configure eager loading for `students` and their nested `currentEnrollment.academicClass` and `currentEnrollment.section` to eliminate N+1 queries.
- [x] 3.4 Implement server-side validation for guardian updates (phone format, email, name, national ID).

## 4. Blade Views & No-Slop UI Implementation

- [x] 4.1 Build `resources/views/admin/guardians/index.blade.php` with crisp 1px borders, status filter tab counters, student ward relationship chips, click-to-contact links, and pagination.
- [x] 4.2 Build `resources/views/admin/guardians/show.blade.php` featuring high-density demographic details and a roster card for all enrolled family wards.
- [x] 4.3 Build `resources/views/admin/guardians/edit.blade.php` providing focused, accessible form controls for editing guardian information.

## 5. Verification & Testing

- [x] 5.1 Run `php artisan db:seed --class=RolePermissionSeeder` to register the new permissions in the database.
- [x] 5.2 Verify sidebar navigation directly opens the Parents & Guardians directory and activates the sidebar item.
- [x] 5.3 Verify search, status tabs, student chips, detail view, edit validation, and soft-delete/restore operations.
- [x] 5.4 Run Laravel Pint on all newly created/modified PHP files.
