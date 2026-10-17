# school-permissions Specification

## Purpose
Provides tenant-scoped Role-Based Access Control (RBAC) for the EduCore School Management System, allowing individual schools to define roles, assign modular permissions across academic and administrative domains, and enforce authorization gates without interfering with global Super Admin authority.

## Requirements

### Requirement: Tenant-Scoped Role and Permission Database Schema Structure
The application MUST maintain relational tables for `roles`, `permissions`, `permission_role`, and `role_user`, ensuring roles are scoped to the institution (`school_id`), while permissions represent standardized granular capabilities grouped by functional domains.

#### Scenario: Role and permission schema migration execution
- **WHEN** database migrations are executed
- **THEN** the following tables are created:
  - `roles`: `id`, `school_id` (foreignId constrained to `schools`), `name`, `slug`, `description` (nullable), `is_system` (boolean default false), `created_at`, `updated_at`. Unique constraint on `['school_id', 'slug']`.
  - `permissions`: `id`, `name`, `slug` (unique), `group` (string, e.g. 'students', 'staff', 'academic', 'roles', 'settings'), `description` (nullable), `created_at`, `updated_at`.
  - `permission_role`: `permission_id` (foreignId constrained cascade), `role_id` (foreignId constrained cascade), primary key on `['permission_id', 'role_id']`.
  - `role_user`: `role_id` (foreignId constrained cascade), `user_id` (foreignId constrained to `users` cascade), primary key on `['role_id', 'user_id']`.

---

### Requirement: Standard System Permission Catalog and Default Role Seeding
The application MUST provide a comprehensive catalog of domain permissions grouped logically, and an idempotent seeder initializing default institutional roles (`School Admin`, `Teacher`, `Accountant`, `Staff Member`) for each school.

#### Scenario: Seeded permission groupings
- **WHEN** permission seeding executes
- **THEN** permissions are created under explicit groups:
  - `students`: `students.view`, `students.create`, `students.edit`, `students.delete`, `students.promote`
  - `staff`: `staff.view`, `staff.create`, `staff.edit`, `staff.delete`, `staff.manage_status`
  - `academic`: `academic.view`, `academic.classes`, `academic.sections`, `academic.subjects`, `academic.assignments`
  - `roles`: `roles.view`, `roles.manage`
  - `settings`: `settings.school.edit`.

#### Scenario: Default school admin role receives full school permissions
- **WHEN** a default school role is seeded
- **THEN** `School Admin` role is linked to all school-level permissions
- **AND** `Teacher` role receives `academic.view`, `students.view`, and teaching assignment permissions
- **AND** system Super Admin accounts bypass school permission restrictions automatically.

---

### Requirement: Role Management Interface with Grouped Permission Checkbox Matrix
The application MUST provide role management views at `GET /admin/roles`, `GET /admin/roles/create`, and `GET /admin/roles/{role}/edit` presenting a clean, accessible permission selector grouped by module with bulk "Select All" toggles.

#### Scenario: Render permission matrix in create/edit role form
- **WHEN** an administrator visits the role creation or edit page
- **THEN** permissions are visually arranged in organized cards grouped by module (`Student Management`, `Staff & Faculty`, `Academic Hierarchy`, `Roles & Access`, `Settings`)
- **AND** each card offers a group-level "Select All" toggle for fast configuration
- **AND** saving persists the selected permission IDs to `permission_role`.

#### Scenario: Protecting immutable system roles
- **WHEN** viewing a role where `is_system = true`
- **THEN** deletion is disabled with a clear badge indicating it is a required system role.

---

### Requirement: Backend Permission Verification Middleware and Access-Denied Handling
The application MUST enforce permissions across administrative routes and controllers using an authorization middleware (`school.permission:perm-name`), displaying an accessible HTTP 403 access-denied state if unauthorized.

#### Scenario: Authorized user accesses protected action
- **WHEN** a school user with `staff.create` permission accesses `GET /admin/staff/create`
- **THEN** the request proceeds with HTTP 200.

#### Scenario: Unauthorized user blocked from protected action
- **WHEN** a school user lacking `roles.manage` attempts to access `POST /admin/roles`
- **THEN** the system denies access with HTTP 403 and renders a polished institutional "Access Denied / Insufficient Permissions" view without exposing platform internals.
