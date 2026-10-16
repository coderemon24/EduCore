# multi-tenant-schools Specification

## Purpose
Provides the foundational multi-tenant data architecture for the School Management System, introducing the `School` entity, row-level tenant query isolation conventions, unique institutional slug identification, and status lifecycle management.

## Requirements

### Requirement: Dedicated Schools Database Schema Structure
The application MUST provide a database migration creating an isolated `schools` table with strict indexing and unique constraints to represent academic institutions independently.

#### Scenario: Schools migration execution
- **WHEN** database migrations are executed via `php artisan migrate`
- **THEN** the `schools` table is created with the following columns:
  - `id` (bigint unsigned, auto-increment, primary key)
  - `name` (string)
  - `slug` (string, unique, indexed)
  - `code` (string, 32 chars, nullable, unique)
  - `status` (string, 24 chars, default 'active', indexed)
  - `email` (string, nullable)
  - `phone` (string, 32 chars, nullable)
  - `address` (text, nullable)
  - `city` (string, 100 chars, nullable)
  - `state` (string, 100 chars, nullable)
  - `country` (string, 100 chars, nullable)
  - `postal_code` (string, 20 chars, nullable)
  - `logo` (string, nullable)
  - `website` (string, nullable)
  - `established_year` (unsigned smallint, nullable)
  - `timezone` (string, 50 chars, default 'UTC')
  - `currency` (string, 10 chars, default 'USD')
  - `deleted_at` (timestamp, nullable for soft deletes)
  - `created_at` and `updated_at` (timestamps).

#### Scenario: Unique constraint enforcement
- **WHEN** inserting a school record with an existing `slug` or `code`
- **THEN** the database enforces a unique constraint violation exception.

---

### Requirement: School Eloquent Model and Lifecycle Conventions
The application MUST provide an `App\Models\School` model representing the institution, supporting attribute mass assignment, type-safe status casting, automatic slug generation, and soft deletion.

#### Scenario: Automatic slug derivation on model creation
- **WHEN** creating a `School` model without an explicit `slug` attribute
- **THEN** the model automatically derives a URL-safe kebab-case slug from the `name` attribute prior to insertion.

#### Scenario: Type-safe status casting with SchoolStatus enum
- **WHEN** retrieving or setting the `status` attribute on `School`
- **THEN** the attribute is cast to the `App\Enums\SchoolStatus` backed enum (`Active`, `Inactive`, `Suspended`, `Pending`).

#### Scenario: Soft delete preservation
- **WHEN** a `School` instance is deleted
- **THEN** the record is soft-deleted by updating `deleted_at` rather than physically removing the row from the database.

---

### Requirement: Row-Level Tenant Isolation via BelongsToSchool Trait and SchoolScope
The application MUST provide a reusable model trait (`BelongsToSchool`) and global scope (`SchoolScope`) to enforce row-level tenant query isolation and automated `school_id` assignment on future school-owned domain models.

#### Scenario: Tenant query isolation filtering
- **WHEN** an active tenant context exists and a model using `BelongsToSchool` is queried
- **THEN** `SchoolScope` automatically appends `WHERE school_id = ?` to the query, preventing cross-tenant data leakage.

#### Scenario: Super Admin global query bypass
- **WHEN** an administrative query is executed without tenant context or with `withoutGlobalScope(SchoolScope::class)`
- **THEN** query results are not constrained to a single school, allowing Super Admins to view platform-wide records.

#### Scenario: Automatic school_id assignment on model creation
- **WHEN** creating a new model instance using `BelongsToSchool` without explicitly providing `school_id`
- **THEN** the model event automatically populates `school_id` from the resolved tenant context.

---

### Requirement: Default Institution Database Seeder
The application MUST provide an idempotent database seeder (`SchoolSeeder`) that initializes a default institutional tenant ("EduCore Academy") with complete profile fields.

#### Scenario: Idempotent seeder execution
- **WHEN** `php artisan db:seed --class=SchoolSeeder` is executed multiple times
- **THEN** the default school with slug `'educore-academy'` is created or updated without duplicate key collisions.

---

### Requirement: Non-Destructive Two-Phase Tenancy Backfill Pattern
The database migration strategy MUST ensure that introducing `school_id` into existing or future records occurs through a backward-compatible sequence that prevents data loss.

#### Scenario: Safe nullable-first schema introduction
- **WHEN** adding `school_id` to an existing database table
- **THEN** the column is introduced as nullable first with a foreign key constraint referencing `schools(id)`
- **AND** existing rows are backfilled with an active school ID before any non-null constraint is applied.
