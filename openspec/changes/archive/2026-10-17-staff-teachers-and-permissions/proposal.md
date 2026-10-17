# Proposal: Staff, Teachers & School Permissions Management

## Why

The EduCore School Management System currently features platform-level Super Admin capabilities (`admins` table, `admin` guard), institutional multi-tenancy (`schools` table), academic hierarchies (`academic_years`, `academic_classes`, `sections`, `subjects`), and complete Student Information System (`students` table). However:
1. There is no staff or teacher directory, profiles, or employment tracking. The sidebar currently displays placeholder alert dialogs for "Teachers & Faculty".
2. School-level administrators, teachers, and non-teaching personnel cannot log into their respective school portals because authentication is limited to global Super Admins in the `admins` table.
3. Class-subject assignments and section management lack teacher assignment links.
4. There is no school-scoped Role and Permission system to govern access permissions (e.g. differentiating a Principal from a Subject Teacher or School Accountant).

Implementing staff/teacher management with school-level access control enables full institutional operations while strictly preserving the isolation of the platform Super Admin.

## What Changes

- **School User Accounts & Dual-Guard Authentication**: Enable school users to authenticate at `/admin/login` using the `users` table (`web` guard) with `school_id`, status verification (`active`), and auto-binding of `current_school_id` into the container and session. Keep platform Super Admins (`admins` table, `admin` guard) completely isolated.
- **Staff & Teacher Profiles**: Implement a unified `staff` domain model catering to both teaching and non-teaching personnel with employment info (designation, department, staff code, joining date, qualification, basic salary, contract type), personal contact data, and profile photos stored directly in `public/uploads/staff/`.
- **Teacher Academic Assignments**: Associate teachers with class sections (as Class Teachers) and link teachers to the curriculum matrix (`class_subject_assignments`).
- **School Roles & Permissions (RBAC)**: Introduce lightweight, tenant-scoped roles and permissions (`roles`, `permissions`, `role_user`, `permission_role` tables) with domain groupings (Students, Academic Structure, Staff & Teachers, Fees, Reports, Institution Settings). Provide backend middleware/policy enforcement (`school.permission:perm-name`) and UI toggles.
- **Administrative Portal UI**:
  - Teacher & Staff Directory with search, filters (department, designation, type: teaching vs non-teaching, status), pagination, and status badge indicators.
  - Staff Onboarding (Create), Edit, and comprehensive Details/Profile views with employment telemetry and assigned classes/subjects.
  - Role and Permission Management interface with intuitive, grouped permission checkboxes.
  - Account status controls (activate/deactivate) and password management.
  - Role-aware navigation visibility in the sidebar and top navigation (hiding Super Admin controls from school staff).

## Capabilities

### New Capabilities
- `staff-management`: Comprehensive staff and teacher directory, profile management (teaching and non-teaching), employment and qualification details, contact records, photo uploads, and status lifecycle.
- `school-permissions`: School-level Role-Based Access Control (RBAC), tenant-scoped role creation, granular permission matrix grouped by functional modules, and authorization middleware/policies.
- `teacher-assignments`: Teacher class-section leadership (Class Teacher) and class-subject curriculum matrix assignment with workload visibility.

### Modified Capabilities
- `admin-auth`: Extend the administrative login gateway (`/admin/login`, `AdminAuthentication`, `LoginRequest`) to support dual-guard authentication (platform `admin` guard for Super Admins and `web` guard for school-level `User` accounts), binding `current_school_id` on login and enforcing account activation status.

## Impact

- **Database**:
  - Add `school_id`, `status`, `phone`, and `avatar` to `users` table with composite indexes.
  - Add `staff` table with employment, personal, and educational attributes.
  - Add `roles`, `permissions`, `permission_role`, and `role_user` tables with `school_id` foreign keys (except system permissions).
  - Add nullable `teacher_id` foreign key to `class_subject_assignments` and `sections`.
- **Authentication & Middleware**:
  - Update `LoginRequest` and `LoginController` to attempt authentication across guards (`admin` for Super Admins, `web` for School Users).
  - Update `AdminAuthentication` to allow authenticated school users while setting `current_school_id`.
  - Add `EnsureSchoolPermission` middleware to guard role-specific operations.
- **UI & Views**:
  - Wire `/admin/staff` and `/admin/teachers` routes with full Blade CRUD views.
  - Wire `/admin/roles` for school permission assignment.
  - Update `resources/views/layouts/admin-partial/sidebar.blade.php` to conditionally render menus according to user permissions and school context.
