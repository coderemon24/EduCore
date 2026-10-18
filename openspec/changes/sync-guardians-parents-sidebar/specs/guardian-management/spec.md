# Spec Delta: guardian-management

## Purpose
Provides an administrative directory and lifecycle management for school guardians, family contact points, ward relationships, and navigation synchronization with the admin sidebar.

## ADDED Requirements

### Requirement: Sidebar Navigation Integration for Parents & Guardians
The admin dashboard sidebar MUST link the "Parents" menu item directly to `route('admin.guardians.index')`, eliminating the dead SweetAlert modal, and visually highlight the item when visiting any guardian management route.

#### Scenario: Navigating to Parents from Sidebar
- **WHEN** an administrator clicks "Parents" in the dashboard sidebar
- **THEN** the browser navigates directly to `GET /admin/guardians` without triggering any JavaScript alert dialog.

#### Scenario: Sidebar Active State on Guardian Routes
- **WHEN** the current route matches `admin.guardians.*`
- **THEN** the Parents sidebar menu item renders with active violet styling (`bg-violet-50 text-violet-700 font-semibold border-l-2 border-violet-600`).

---

### Requirement: Searchable High-Density Guardian Directory
The application MUST provide a guardian directory at `GET /admin/guardians` supporting keyword search, status filtering, eager-loaded student associations, and responsive pagination adhering to institutional No-Slop UI standards.

#### Scenario: Searching guardians by name or child
- **WHEN** an administrator enters a keyword matching a guardian's name, phone, email, or a student's name/admission number
- **THEN** the directory returns matching guardian records along with their linked students, relationship badges, and contact details.

#### Scenario: Switching status filter tabs
- **WHEN** an administrator selects the "Archived" status tab
- **THEN** the directory renders soft-deleted guardian records with restore and permanent delete actions.

#### Scenario: Preventing N+1 queries on student associations
- **WHEN** rendering 25 guardian rows on the directory page
- **THEN** all associated students, pivot attributes (`relationship`, `is_primary`, `is_emergency_contact`), and current class/section enrollments are eager loaded in constant bounded queries.

---

### Requirement: Guardian Details and Ward Relationship Profile
The application MUST provide a profile view at `GET /admin/guardians/{guardian}` displaying the guardian's contact telemetry, occupation, national ID, address, and an interconnected cards/roster of all enrolled wards.

#### Scenario: Inspecting guardian profile and family wards
- **WHEN** an administrator views a guardian's profile
- **THEN** the page renders complete guardian demographics alongside every associated student, displaying class, section, roll number, primary/emergency contact flags, and direct links to each student profile.

---

### Requirement: Guardian Contact and Information Editing
The application MUST provide an editing interface at `GET /admin/guardians/{guardian}/edit` and validate updates via `PUT /admin/guardians/{guardian}`.

#### Scenario: Updating guardian contact information
- **WHEN** an administrator updates a guardian's phone number, email, address, or occupation with valid data
- **THEN** the record updates in the database, displays a success Toast notification, and redirects back to the directory or profile.

#### Scenario: Validating guardian update inputs
- **WHEN** an administrator submits an empty name or invalid email format
- **THEN** the submission fails with HTTP 422, preserving input values and rendering inline field errors.

---

### Requirement: Safe Soft Deletion, Restoration, and Permanent Purge
The application MUST support soft deletion, restoration, and permanent removal of guardian records without compromising historical student data.

#### Scenario: Moving a guardian to trash
- **WHEN** an administrator moves a guardian to trash
- **THEN** `deleted_at` is set on the guardian record, preserving the record and removing them from active directory listings.

#### Scenario: Restoring an archived guardian
- **WHEN** an administrator clicks restore on a trashed guardian
- **THEN** `deleted_at` is reset to null and the guardian reappears in active listings.

---

### Requirement: Institutional Role & Permission Enforcement
The application MUST protect all guardian management endpoints using dedicated institutional permissions (`guardians.view`, `guardians.manage`, `guardians.delete`).

#### Scenario: Unauthorized access attempt
- **WHEN** a user without `guardians.view` attempts to access `GET /admin/guardians`
- **THEN** the application denies access with HTTP 403 Forbidden.
