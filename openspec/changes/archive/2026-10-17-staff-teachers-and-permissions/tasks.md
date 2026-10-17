# Tasks: Staff, Teachers & School Permissions Implementation

## 1. Models & Migrations

- [x] 1.1 Create migration to update `users` table: add `school_id` foreign key referencing `schools(id)`, `status` (default 'active'), `phone`, and `avatar` with composite indexes `['school_id', 'status']`.
- [x] 1.2 Create migration for `staff` table: `id`, `school_id`, `user_id` (nullable), `staff_code`, `first_name`, `last_name`, `gender`, `date_of_birth`, `email`, `phone`, `emergency_contact_name`, `emergency_contact_phone`, `is_teaching`, `department`, `designation`, `qualification`, `experience_years`, `joining_date`, `basic_salary`, `contract_type`, `status`, `photo`, `current_address`, `permanent_address`, `deleted_at`, timestamps, and performance indexes.
- [x] 1.3 Create migration for RBAC tables: `roles` (`school_id`, `name`, `slug`, `description`, `is_system`), `permissions` (`name`, `slug`, `group`, `description`), `permission_role`, and `role_user`.
- [x] 1.4 Create migration to add `class_teacher_id` to `sections` and `teacher_id` to `class_subject_assignments` referencing `staff(id)` with `nullOnDelete()`.
- [x] 1.5 Create/update Eloquent models: `Staff` (using `BelongsToSchool`, `SoftDeletes`), `Role`, `Permission`, update `User` with relationships (`staff`, `roles`, `hasPermission`), update `School` (`staff`, `roles`), `Section` (`classTeacher`), and `ClassSubjectAssignment` (`teacher`).

## 2. Authentication, Authorization & Seeders

- [x] 2.1 Update `LoginRequest` and `LoginController` to support multi-guard authentication: check `admin` guard for platform Super Admins, check `web` guard for school `User` accounts, validate active status, and store `current_school_id` in session and container.
- [x] 2.2 Update `AdminAuthentication` middleware to authorize both `admin` and `web` guards, ensuring `app('current_school_id')` is initialized for school users.
- [x] 2.3 Create `EnsureSchoolPermission` middleware (`school.permission:{slug}`) checking user permissions or Super Admin bypass, and register middleware alias in `bootstrap/app.php`.
- [x] 2.4 Create `RolePermissionSeeder` with system permissions (students, staff, academic, roles, settings) and default institutional roles (`School Admin`, `Teacher`, `Accountant`, `Staff`), and create `StaffSeeder` for default staff records.

## 3. Controllers & Business Logic

- [x] 3.1 Create Form Requests: `StoreStaffRequest`, `UpdateStaffRequest`, `UpdateStaffStatusRequest`, `StoreRoleRequest`, and `UpdateRoleRequest` validating tenant ownership, unique codes, and permissions.
- [x] 3.2 Create `StaffController` implementing directory listing, filtering (teaching vs non-teaching, department, status), search, creation with optional `User` account generation, edit, show, photo upload to `public/uploads/staff/`, and status toggle.
- [x] 3.3 Create `RoleController` implementing role listing, creation, editing, deletion protection on system roles, and permission matrix synchronization.
- [x] 3.4 Update `SectionController` and `ClassSubjectAssignmentController` to support assigning and validating teachers scoped to the tenant school.
- [x] 3.5 Register administrative routes under `routes/admin.php` for staff, teachers, roles, and status updates with appropriate permission middleware.

## 4. Blade Views & UI Implementation

- [x] 4.1 Update `resources/views/layouts/admin-partial/sidebar.blade.php` to wire "Teachers & Faculty", "Staff Directory", and "Roles & Permissions", and hide platform-only controls from school users.
- [x] 4.2 Create Staff & Teacher Directory view (`resources/views/admin/staff/index.blade.php`) with search, department/designation/type filters, status tab counts, and pagination.
- [x] 4.3 Create Staff Onboarding & Edit forms (`resources/views/admin/staff/create.blade.php`, `resources/views/admin/staff/edit.blade.php`) with Dropify photo preview and login credential generation.
- [x] 4.4 Create Staff / Teacher Profile view (`resources/views/admin/staff/show.blade.php`) displaying employment telemetry, personal details, academic assignments card, and status management.
- [x] 4.5 Create Role Management views (`resources/views/admin/roles/index.blade.php`, `create.blade.php`, `edit.blade.php`) with organized permission checkboxes grouped by module and "Select All" toggles.
- [x] 4.6 Create polished institutional Access Denied view (`resources/views/admin/errors/403.blade.php`).

## 5. Verification & Testing

- [x] 5.1 Write and run `MultiGuardAuthenticationTest` covering Super Admin login, school user login with tenant resolution, inactive user lockout, and logout.
- [x] 5.2 Write and run `StaffManagementTest` covering staff creation, photo upload, search, filters, tenant isolation, and status updates.
- [x] 5.3 Write and run `SchoolPermissionsTest` covering role management, permission checking, access-denied handling, and cross-tenant boundary security.
- [x] 5.4 Write and run `TeacherAssignmentTest` covering class teacher and subject teacher assignment and tenant validation.
- [x] 5.5 Run full test suite (`php artisan test`) and compile frontend assets (`npm run build`).
