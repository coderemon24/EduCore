# Tasks

## 1. Models & Migrations

- [x] 1.1 Create `App\Enums\SchoolStatus` backed enum with values `active`, `inactive`, `suspended`, and `pending`.
- [x] 1.2 Create database migration `database/migrations/2026_10_16_000000_create_schools_table.php` with all required columns, indexes (`slug`, `code`, `status`), and soft delete support.
- [x] 1.3 Create `App\Models\School` Eloquent model with fillable attributes, status casting, soft deletes, and automatic slug generation in `booted()` callback.
- [x] 1.4 Run `php artisan migrate` and verify the table structure and indexes via `php artisan migrate:status`.

## 2. Tenant Isolation Scoping & Enums

- [x] 2.1 Implement `App\Models\Scopes\SchoolScope` global scope that constrains Eloquent queries to the current active `school_id` when tenant context exists.
- [x] 2.2 Implement `App\Models\Concerns\BelongsToSchool` model trait that binds `SchoolScope` and automatically assigns `school_id` during model creation.
- [x] 2.3 Verify `School` model defines a `hasMany` relationship to `App\Models\User`.

## 3. Database Seeders & Factories

- [x] 3.1 Create `database/factories/SchoolFactory.php` supporting realistic test attributes and distinct status states.
- [x] 3.2 Create `database/seeders/SchoolSeeder.php` using `School::updateOrCreate()` for the initial default school ("EduCore Academy", slug `'educore-academy'`).
- [x] 3.3 Register `SchoolSeeder::class` in `database/seeders/DatabaseSeeder.php`.
- [x] 3.4 Execute `php artisan db:seed --class=SchoolSeeder` and confirm the seeded school record.

## 4. Verification & Testing

- [x] 4.1 Create `tests/Feature/SchoolManagementTest.php` to verify:
  - `schools` table schema, unique slug enforcement, and soft delete functionality.
  - Automatic slug generation when a school is created without a slug.
  - `SchoolStatus` enum casting.
  - `BelongsToSchool` trait and `SchoolScope` query filtering and bypass mechanisms.
- [x] 4.2 Execute the test suite using `php -d extension=pdo_sqlite vendor/bin/phpunit` to ensure 100% pass rate.
- [x] 4.3 Verify that all 14 existing Super Admin authentication and dashboard tests (`AdminAuthenticationTest` and `AdminDashboardTest`) pass without regression.
- [x] 4.4 Run `vendor/bin/pint --test` to ensure strict compliance with Laravel coding standards.
