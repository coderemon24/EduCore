# Design: Staff, Teachers & School Permissions Architecture

## Context

The application is built on Laravel 12 on PHP 8.2 with row-level multi-tenancy enforced by the `BelongsToSchool` trait and `SchoolScope` bound to `app('current_school_id')`.
Currently:
- Platform Super Admins authenticate through `Auth::guard('admin')` against the `admins` table, which has no `school_id` and has access to all tenant institutions (`/admin/schools`).
- The `users` table exists from standard Laravel skeleton but has no `school_id`, status, or profile relationship.
- There are no staff or teacher models, and `class_subject_assignments` and `sections` have no teacher foreign keys.
- The admin dashboard uses Tailwind CSS v4, Blade templates, Select2, Dropify, and ToastMagic.

## Goals / Non-Goals

**Goals:**
- Provide complete staff and faculty management (teaching & non-teaching profiles, employment terms, contact information, photos).
- Establish school-level user authentication via `users` table with tenant isolation, account status verification (`active`), and automatic `current_school_id` container binding.
- Implement native, lightweight tenant-scoped Role-Based Access Control (RBAC) with a grouped permission matrix and backend middleware enforcement.
- Support academic assignments: Class Teacher assignment on sections and Subject Teacher assignment on the curriculum matrix.
- Guarantee strict separation: Platform Super Admin controls (`/admin/schools`) remain inaccessible to school staff, and school staff access only their school's records.

**Non-Goals:**
- Merging platform Super Admins into the `users` table or granting school admins platform-level access.
- Introducing third-party package dependencies like `spatie/laravel-permission` (native Laravel Eloquent relationships and Gate/middleware suffice and maintain zero bloat).
- Student/Parent portal authentication (reserved for dedicated portal changes).

## Decisions

### 1. Dual-Guard Authentication Architecture
- **Choice**: Multi-guard authentication on the `/admin/login` gateway.
  - When submitting credentials:
    1. If `Auth::guard('admin')->attempt(...)` succeeds, the user is a platform administrator. `current_school_id` remains unset (or null) to allow platform-wide management.
    2. If `admin` fails, attempt `Auth::guard('web')->attempt(...)`. If successful, verify that the `User` has `status === 'active'`. If inactive, log out immediately and return a validation error.
    3. Bind the authenticated user's `school_id` to `session(['current_school_id' => $user->school_id])` and `app()->instance('current_school_id', $user->school_id)`.
- **Alternatives Considered**:
  - *Adding `school_id` to `admins` table*: Rejected because it mixes platform infrastructure accounts with school employees, making global permissions brittle.
  - *Separate login URL for schools (`/school/login`)*: Rejected to preserve a single administrative entrance for school operators and simplify UX.

### 2. Unified `Staff` Model with `is_teaching` Flag
- **Choice**: Single `staff` table and `Staff` model covering both faculty teachers and non-teaching personnel.
  - A boolean column `is_teaching` (indexed) distinguishes teachers from non-teaching staff (administrators, accountants, librarians).
  - Routes `/admin/teachers` and `/admin/staff` query the same model with appropriate default scopes/filters.
  - An optional `user_id` foreign key connects a staff profile to an authenticatable `User` row for staff who require dashboard access.
- **Alternatives Considered**:
  - *Separate `teachers` and `staff` tables*: Rejected because 90% of employment, contact, salary, document, and personal data fields would be duplicated.

### 3. Native Tenant-Scoped RBAC
- **Choice**: Create four normalized tables: `roles` (`school_id`), `permissions` (system-wide capability slugs), `permission_role`, and `role_user`.
  - Roles belong to a specific school tenant (`school_id`), allowing each school to customize permissions or create custom roles.
  - System seeds standard permissions organized into clean modules: `students`, `staff`, `academic`, `roles`, `settings`.
  - An authorization middleware `school.permission:{permission}` checks `$user->hasPermission($permission)`.
  - Super Admin automatically bypasses all permission gates.
- **Alternatives Considered**:
  - *Hardcoded role strings*: Rejected because schools cannot assign custom capabilities to specific staff designations.

### 4. Direct Public Uploads for Staff Avatars
- **Choice**: Binary file uploads are saved directly to `public/uploads/staff/` with random hash filenames, matching the pattern established for school logos (`public/uploads/schools/logos`) and student photos.
- **Rationale**: Avoids Windows/Laragon symlink issues associated with `php artisan storage:link`.

### 5. UI Structure & Institutional Design System
- **Choice**: Create clean Blade layouts under `resources/views/admin/staff/`, `resources/views/admin/teachers/`, and `resources/views/admin/roles/` using Tailwind CSS v4, crisp 1px borders, semantic color badges, and zero decorative AI fluff.
- **Navigation Guarding**: In `resources/views/layouts/admin-partial/sidebar.blade.php`, display "Schools & Campuses (Tenant)" only to Super Admins (`Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'super_admin'`). For school users, display only authorized modules.

## Risks / Trade-offs

- **[Risk] Session Tenant Drift on Page Refresh**
  → *Mitigation*: `AdminAuthentication` middleware checks if `Auth::guard('web')->check()` is true and ensures `app('current_school_id')` is refreshed from `session('current_school_id')` on every incoming request.

- **[Risk] Cross-Tenant Staff Assignment to Classes or Sections**
  → *Mitigation*: Form Requests (`SectionRequest`, `ClassSubjectAssignmentRequest`) validate that `class_teacher_id` and `teacher_id` exist in the `staff` table where `school_id = app('current_school_id')` and `is_teaching = 1`.

- **[Risk] Accidental Deletion of Critical System Roles**
  → *Mitigation*: The `roles` table has an `is_system` boolean flag. Controllers and UI disable delete actions on system roles like `School Admin`.
