# Design

## Context

The application is built on Laravel 12 (`bootstrap/app.php` single-entry architecture) and PHP 8.2.12 on MySQL 8.0.30. Authentication is governed by a dedicated `admin` guard using the `admins` table and `Admin` model. Currently, only 1 seeded Super Admin exists (`admin@gmail.com`), with 0 regular users in the `users` table, and all dashboard metrics are rendered via static in-memory arrays.

See [proposal.md](proposal.md) for full motivation and problem statement.

## Goals / Non-Goals

**Goals:**
- Provide a robust `schools` table and `School` model with unique slugs, institutional code, and status lifecycles.
- Establish architectural conventions for `school_id` foreign keys, compound indexes, and tenant-scoped uniqueness.
- Implement a reusable `BelongsToSchool` trait and `SchoolScope` global query scope to prevent cross-tenant data leaks.
- Create an idempotent `SchoolSeeder` and `SchoolFactory` for testing.
- Fully preserve existing Super Admin authentication, the `admins` table, routes, and admin dashboard without regression.

**Non-Goals:**
- No subscription billing, payment plans, invoices, or payment gateways.
- No dynamic domain/subdomain routing or `school_domains` table at this stage.
- No UI redesign, frontend layout changes, or refactoring of existing admin dashboard Blade templates.
- No modifications to the existing `admins` table or authentication guards.

## Decisions

### 1. Tenancy Model: Single Database with Row-Level Discrimination
- **Choice**: Shared database schema with a `school_id` foreign key discriminator column on tenant entities.
- **Rationale**: Multi-database or multi-schema tenancy introduces heavy operational complexity (dynamic connection switching, connection pooling issues, migration orchestration across dozens of databases). Single-database row-level discrimination is lightweight, performant on MySQL 8.0/InnoDB, and natively supported by Laravel Eloquent scopes.
- **Alternatives Considered**:
  - *Database-per-tenant*: High infrastructure cost and connection management overhead in Laragon/cPanel/standard hosting.
  - *Schema-per-tenant (PostgreSQL schemas)*: Incompatible with MySQL 8.0 standard setup.

### 2. Tenant Scoping: `SchoolScope` Global Scope & `BelongsToSchool` Trait
- **Choice**: Enforce query filtering at the Eloquent model layer using a global scope (`SchoolScope`) and trait (`BelongsToSchool`).
- **Rationale**: Developers will not need to remember to write `->where('school_id', ...)` on every query. The scope automatically appends the constraint when a tenant context exists, and automatically injects `school_id` on model creation.
- **Super Admin Bypass**: Platform Super Admins can explicitly query across all institutions by calling `Model::withoutGlobalScope(SchoolScope::class)`.
- **Alternatives Considered**:
  - *Manual query filtering in controllers/repositories*: Highly error-prone; a single omitted `where` clause causes catastrophic data leaks.

### 3. Tenant Identification & Unique Slugs
- **Choice**: Unique `slug` (indexed string) automatically derived from the school name via `Str::slug()` on creation if not provided.
- **Rationale**: URL-friendly slugs allow clean RESTful routing (e.g., `/schools/educore-academy` or path-based tenant resolution) without exposing sequential database primary keys.
- **Alternatives Considered**:
  - *UUIDs as primary key*: Slower index performance on MySQL InnoDB B-trees; auto-increment integer `id` with a unique `slug` provides the best balance of speed and clean URLs.

### 4. Status Lifecycle: PHP 8.2 Backed Enum
- **Choice**: `App\Enums\SchoolStatus` with cases `Active = 'active'`, `Inactive = 'inactive'`, `Suspended = 'suspended'`, `Pending = 'pending'`.
- **Rationale**: Provides compile-time type safety, IDE autocompletion, and native Eloquent casting, preventing invalid status strings in the database.

### 5. Multi-Phase Migration Safety Pattern
- **Choice**: When future migrations add `school_id` to existing tables (e.g., `users`), follow a 3-phase rollout:
  1. Add `school_id` as nullable foreign key.
  2. Backfill existing records with a default school ID.
  3. Enforce `NOT NULL` constraint only after verifying zero orphaned rows.

## Risks / Trade-offs

- **[Risk: Cross-Tenant Data Leakage via Raw DB Queries]**  
  → *Mitigation*: Global scope covers Eloquent. Document architectural rules that domain operations must utilize Eloquent or explicitly pass `school_id` in `DB::table()` queries.
- **[Risk: Accidental Tenant Deletion Cascading Annihilation]**  
  → *Mitigation*: Enable `SoftDeletes` on `School`. A deleted school record retains `deleted_at`, allowing recovery without destructive foreign key cascade deletion.
- **[Risk: Slug Collision on Identical School Names]**  
  → *Mitigation*: Model creation hook checks for slug existence and appends a numeric suffix if necessary.

## Migration Plan

1. Create migration `2026_10_16_000000_create_schools_table.php` and execute `php artisan migrate`.
2. Seed default institution via `php artisan db:seed --class=SchoolSeeder`.
3. Run test suite: `php -d extension=pdo_sqlite vendor/bin/phpunit`.
4. Rollback safety: Migration `down()` cleanly drops `schools` table without affecting `admins` or `users`.
