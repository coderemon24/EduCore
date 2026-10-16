# Proposal

## Why

The EduCore School Management System currently operates with a single administrative tier (`admins` table) and hardcoded dashboard metrics, lacking an institutional data model. To enable the application to transition smoothly into a multi-tenant Software-as-a-Service (SaaS) platform in the future, the foundational data architecture must introduce the concept of an individual `School` institution and establish conventions for tenant-owned records.

Preparing for multi-tenancy now prevents costly, breaking schema refactors later when student information, faculty rosters, classes, and fees are introduced. By establishing row-level tenant isolation conventions, school profile fields, status lifecycles, and backfill patterns early—without the premature overhead of subscription billing or domain routing—the system achieves architectural readiness while preserving existing Super Admin authentication and workflows.

## What Changes

- Introduce a `schools` database table and `App\Models\School` Eloquent model with soft deletes, timestamps, unique slug generation, institutional code, status lifecycle, and essential institutional profile fields (contact, address, timezone, currency).
- Define `App\Enums\SchoolStatus` (backed enum: `active`, `inactive`, `suspended`, `pending`) for type-safe status transitions.
- Establish architectural conventions for `school_id` foreign keys, tenant-scoped compound indexes, and institutional uniqueness constraints across future domain entities.
- Implement a reusable tenant isolation trait (`App\Models\Concerns\BelongsToSchool`) and global scope (`App\Models\Scopes\SchoolScope`) to enforce automatic query filtering and auto-assignment of `school_id`.
- Introduce `SchoolSeeder` to seed the initial default institution ("EduCore Academy") and link it into `DatabaseSeeder`.
- Provide a `SchoolFactory` for realistic test fixtures and integration testing.
- Fully preserve existing Super Admin authentication, `admins` table schema, `/admin` routes, and dashboard without regression.

## Capabilities

### New Capabilities
- `multi-tenant-schools`: Core institutional tenancy foundation, including `School` model, `schools` schema, slug-based institutional identity, status lifecycle management, tenant-scoped query isolation (`BelongsToSchool`, `SchoolScope`), and database seeders.

### Modified Capabilities
<!-- None: Existing admin-auth and admin-dashboard capabilities remain fully preserved without requirement changes. -->

## Impact

- **Database**: Adds the `schools` table with indexes on `slug`, `code`, and `status`.
- **Models & Scopes**: Adds `School`, `SchoolStatus` enum, `BelongsToSchool` trait, and `SchoolScope`.
- **Existing Admins & Auth**: Completely unaffected; `admins` table and `admin` guard remain global/platform-level.
- **Dependencies**: No external packages required; builds natively on Laravel 12 and PHP 8.2+ features.
