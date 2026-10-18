# Proposal: Sync Guardians & Parents Sidebar with No-Slop UI

## Why

In the administrative portal (`/admin/dashboard`), the **Parents** sidebar menu item is currently an inoperative placeholder triggering a JavaScript popup alert (`Swal.fire`). Although the database schema already includes a `guardians` catalog and a `guardian_student` pivot table populated through student admissions and database seeders, there is no controller, route group, permission gate, or Blade interface to view, search, manage, or audit student guardians.

Administrators currently have no centralized place to:
1. Access guardian and parent emergency contacts, phone numbers, and residential addresses.
2. View all enrolled wards/children affiliated with a specific parent or family.
3. Edit guardian contact information or manage primary and emergency contact flags.
4. Seamlessly navigate from the admin sidebar to a dedicated Parents & Guardians directory.

This change replaces the dummy sidebar action with a fully synchronized, responsive, high-density **Parents & Guardians Directory** adhering strictly to institutional **No-Slop UI** standards.

## What Changes

1. **Sidebar Navigation Synchronization**:
   - Replace the dead `onclick` modal on the sidebar's **Parents** menu item with a functional route link (`admin.guardians.index`).
   - Implement route-active state detection (`request()->routeIs('admin.guardians.*')`) with the standardized purple active badge and tooltip styling.
   - Retain intuitive label and icon representation under the *Core Management* section.

2. **Administrative Controller & Routing**:
   - Create `App\Http\Controllers\Admin\GuardianController` implementing `index`, `show`, `edit`, `update`, and `destroy` (soft delete / restore / force-delete) actions.
   - Register routes in `routes/admin.php` under `Route::prefix('guardians')->name('guardians.')` with permission middleware.

3. **Role & Permission Registration**:
   - Add institutional permissions (`guardians.view`, `guardians.manage`, `guardians.delete`) in `database/seeders/RolePermissionSeeder.php` and assign them to administrator and staff roles.

4. **High-Density No-Slop Blade Interface**:
   - Develop `resources/views/admin/guardians/index.blade.php`:
     - Multi-facet keyword search (guardian name, phone, email, child name, admission number).
     - Status tabs (All, Active, Archived/Trashed) with live counters.
     - Table columns: Guardian (Avatar/Initials, Name, National ID), Contact (Phone with click-to-call, Email with mailto), Associated Students (Student badge, Class & Section, Relationship chip, Primary tag, Emergency flag), and Quick Actions.
     - Zero AI-slop design: 1px crisp slate-200 borders, high information density, authentic typography, no cheesy gradients or floating glassmorphism.
   - Develop `resources/views/admin/guardians/show.blade.php` (or slide-out drawer) and `resources/views/admin/guardians/edit.blade.php` for viewing and managing family records.

## Capabilities

### New Capabilities
- `guardian-management`: Institutional guardian and parent directory, ward relationship management, emergency contact catalog, and administrator CRUD workflows.

### Modified Capabilities
<!-- None: student-management requirements remain intact; this complements it with a dedicated guardian capability. -->

## Impact

- **Routes**: New `/admin/guardians` route resource in `routes/admin.php`.
- **Controllers**: `App\Http\Controllers\Admin\GuardianController`.
- **Views**:
  - `resources/views/layouts/admin-partial/sidebar.blade.php` (updated sidebar link).
  - `resources/views/admin/guardians/index.blade.php` (new directory view).
  - `resources/views/admin/guardians/edit.blade.php` (edit contact details).
  - `resources/views/admin/guardians/show.blade.php` (profile and ward details).
- **Permissions**: `guardians.view`, `guardians.manage`, `guardians.delete` added to `RolePermissionSeeder`.
- **Database**: Reuses existing `guardians` and `guardian_student` tables; no destructive migrations required.
