# staff-management Specification Delta

## Purpose
Provides comprehensive staff and teacher profile management, employment tracking, institutional directory interfaces, and status lifecycle operations across teaching and non-teaching faculty for the EduCore School Management System.

## ADDED Requirements

### Requirement: Dedicated Staff Database Schema Structure
The application MUST maintain a `staff` database table isolating institutional faculty records, constrained to a tenant school via `school_id` foreign key, with optional linkage to a login user account via `user_id`, and dedicated columns for employment terms, academic classification, and contact info.

#### Scenario: Staff table schema execution
- **WHEN** database migrations are executed
- **THEN** the `staff` table is created with the following columns:
  - `id` (bigint unsigned, auto-increment, primary key)
  - `school_id` (foreignId constrained to `schools` with cascadeOnDelete)
  - `user_id` (foreignId nullable constrained to `users` with nullOnDelete)
  - `staff_code` (string, 32 chars, unique per school)
  - `first_name` (string, 100 chars)
  - `last_name` (string, 100 chars, nullable)
  - `gender` (string, 20 chars)
  - `date_of_birth` (date, nullable)
  - `email` (string, 100 chars, nullable)
  - `phone` (string, 32 chars, nullable)
  - `emergency_contact_name` (string, 100 chars, nullable)
  - `emergency_contact_phone` (string, 32 chars, nullable)
  - `is_teaching` (boolean, default true, indexed)
  - `department` (string, 100 chars, nullable, indexed)
  - `designation` (string, 100 chars)
  - `qualification` (string, 255 chars, nullable)
  - `experience_years` (unsigned smallint, nullable)
  - `joining_date` (date)
  - `basic_salary` (decimal 10,2, nullable)
  - `contract_type` (string, 30 chars, default 'permanent')
  - `status` (string, 24 chars, default 'active', indexed)
  - `photo` (string, nullable)
  - `current_address` (text, nullable)
  - `permanent_address` (text, nullable)
  - `deleted_at` (timestamp, nullable for soft deletes)
  - `created_at` and `updated_at` (timestamps)
- **AND** composite indexes exist on `['school_id', 'staff_code']`, `['school_id', 'status']`, and `['school_id', 'is_teaching']`.

---

### Requirement: Staff Directory and Institutional Faculty Listing
The application MUST provide intuitive, high-information-density directory views at `GET /admin/staff` and `GET /admin/teachers` supporting instant search, faceted filters, status tabs, and responsive pagination.

#### Scenario: Filter directory by employment type
- **WHEN** navigating to `GET /admin/teachers`
- **THEN** the listing automatically filters to records where `is_teaching = true`
- **AND** when navigating to `GET /admin/staff?type=non_teaching`
- **THEN** the listing displays only non-teaching administrative and operational staff.

#### Scenario: Real-time search across staff attributes
- **WHEN** the user inputs a search keyword into the directory search field
- **THEN** the system matches against `staff_code`, `first_name`, `last_name`, `email`, `phone`, `department`, and `designation` within the current tenant school.

#### Scenario: Status filtering with tab counts
- **WHEN** the user views the directory
- **THEN** tab counts are rendered for `all`, `active`, `inactive`, and `trashed` staff records
- **AND** selecting a status tab limits results to that status.

---

### Requirement: Staff Onboarding, Profile Editing, and Direct Avatar Storage
The application MUST support creating and updating staff records with strict validation of unique staff code within the school, optional linked user account creation, and binary photo uploads directly to `public/uploads/staff/` without symlink requirements.

#### Scenario: Creating a new staff member with a linked user account
- **WHEN** an authorized administrator submits valid data to `POST /admin/staff` with "Create Login Account" enabled
- **THEN** the application creates an authenticatable `User` row in `users` with `school_id`, the provided email, and hashed password
- **AND** creates the `Staff` record linked to that `User`
- **AND** redirects to the staff details view with a success notification.

#### Scenario: Updating an existing staff profile and photo replacement
- **WHEN** updating a staff member with a new photo upload at `PUT /admin/staff/{staff}`
- **THEN** the new image is validated (max 2048 KB, jpg/png/webp) and saved to `public/uploads/staff/`
- **AND** any prior photo file is deleted from disk
- **AND** the staff record is updated.

---

### Requirement: Staff Account Status Lifecycle and Access Revocation
The application MUST provide action endpoints to activate, deactivate, and soft-delete staff records, immediately toggling their institutional login access.

#### Scenario: Deactivating a staff member
- **WHEN** an authorized admin submits `PATCH /admin/staff/{staff}/status` with `status: 'inactive'`
- **THEN** the `Staff` status is updated to `inactive`
- **AND** if a linked `User` exists, the user's `status` is simultaneously marked `inactive`
- **AND** any subsequent authentication attempt by this user is immediately rejected.
