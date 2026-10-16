# Spec Delta: multi-tenant-schools

## MODIFIED Requirements

### Requirement: Dedicated Schools Database Schema Structure
The application MUST provide a database migration creating and maintaining the `schools` table with strict indexing, unique constraints, and dedicated columns for institutional branding assets including both a primary crest/logo and a browser favicon.

#### Scenario: Schools migration execution
- **WHEN** database migrations are executed via `php artisan migrate`
- **THEN** the `schools` table is created or updated with the following columns:
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
  - `favicon` (string, nullable)
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
The application MUST provide an `App\Models\School` model representing the institution, supporting attribute mass assignment, type-safe status casting, automatic slug generation, soft deletion, and branding media URL accessors backed by direct `public/uploads/` storage.

#### Scenario: Automatic slug derivation on model creation
- **WHEN** creating a `School` model without an explicit `slug` attribute
- **THEN** the model automatically derives a URL-safe kebab-case slug from the `name` attribute prior to insertion.

#### Scenario: Type-safe status casting with SchoolStatus enum
- **WHEN** retrieving or setting the `status` attribute on `School`
- **THEN** the attribute is cast to the `App\Enums\SchoolStatus` backed enum (`Active`, `Inactive`, `Suspended`, `Pending`).

#### Scenario: Soft delete preservation
- **WHEN** a `School` instance is deleted
- **THEN** the record is soft-deleted by updating `deleted_at` rather than physically removing the row from the database.

#### Scenario: Branding media URL accessors with robust defaults
- **WHEN** accessing `$school->logo_url`
- **THEN** the model checks if the relative path in `public_path($school->logo)` exists and returns `asset($school->logo)`, or falls back to `asset('admin-assets/images/laravel-school-transparent.png')` if null/missing
- **AND** when accessing `$school->favicon_url`
- **THEN** the model checks if the relative path in `public_path($school->favicon)` exists and returns `asset($school->favicon)`, or falls back to `asset('admin-assets/images/laravel-school-transparent.png')` or default system icon.

---

### Requirement: Super Admin Multi-School Directory Interface
The application MUST provide a dedicated administrative school directory at `GET /admin/schools` displaying all academic institutions across the platform, restricted to users with the `super_admin` role, without forcing the Super Admin into a tenant-bound context.

#### Scenario: Super Admin views school listing
- **WHEN** an authenticated user with `role === 'super_admin'` visits `/admin/schools`
- **THEN** the system displays a table listing all registered schools including institution logo thumbnail, name, unique code, contact details, established year, status badge, and created date with pagination
- **AND** the navigation topbar/sidebar displays "Global Platform Admin" status rather than falsely indicating an active single school.

#### Scenario: Status filter navigation
- **WHEN** the Super Admin selects a status filter tab (`all`, `active`, `pending`, `suspended`, `inactive`)
- **THEN** the table reloads showing only institutions matching the selected status parameter.

#### Scenario: Non-super-admin access restriction
- **WHEN** an authenticated administrator whose role is not `super_admin` attempts to access `/admin/schools`
- **THEN** the system denies access with HTTP 403 Forbidden.

---

## ADDED Requirements

### Requirement: Super Admin School Direct Branding Media Upload
The application MUST allow Super Administrators to upload binary media files directly for an institution's `logo` and `favicon` into categorized subdirectories under `public/uploads/` during tenant onboarding (`POST /admin/schools`) and updates (`PUT /admin/schools/{school}`) with rich real-time image preview in the UI.

#### Scenario: Validation of uploaded branding media
- **WHEN** submitting the school creation or edit form with `logo` or `favicon` files
- **THEN** `logo` is validated as an image (`jpeg, png, jpg, webp, svg`) up to 2048 KB
- **AND** `favicon` is validated as an image/icon file (`ico, png, svg`) up to 1024 KB
- **AND** invalid file formats or excessive sizes are rejected with HTTP 422 and actionable feedback.

#### Scenario: Categorized public/uploads storage without symbolic links
- **WHEN** a valid school creation request contains uploaded `logo` and `favicon` files
- **THEN** the controller creates directories `public/uploads/schools/logos` and `public/uploads/schools/favicons` if they do not exist
- **AND** moves the uploaded files directly into those categorized directories with unique timestamps and hashes
- **AND** persists relative paths (`uploads/schools/logos/...` and `uploads/schools/favicons/...`) in the database without requiring `php artisan storage:link`.

#### Scenario: File replacement and disk cleanup on school update
- **WHEN** a Super Admin updates an existing school and provides a new `logo` or `favicon`
- **THEN** the new file is stored in `public/uploads/schools/...`
- **AND** any previously stored custom file at `public_path($oldPath)` is safely deleted using `File::delete()`
- **AND** the database record is updated with the new relative asset path.

#### Scenario: Real-time image preview via Dropify-style UI
- **WHEN** a user selects or drops a file into the logo or favicon upload input
- **THEN** the UI immediately displays a rich real-time image preview with file details (name, size) and a clear/remove action button prior to form submission.

---

### Requirement: Tenant-Agnostic Super Admin Platform View Presentation
The administrative interface MUST differentiate between platform-level Super Administrators and tenant-scoped administrators in navigation elements and layout view composers.

#### Scenario: Super Admin layout presentation
- **WHEN** an authenticated administrator with `role === 'super_admin'` navigates the admin portal
- **THEN** the top navigation bar displays a platform badge indicating global administrative authority instead of locking to a specific school
- **AND** the Super Admin can seamlessly navigate into any school's management interface without prior tenant session switching.

#### Scenario: Dynamic favicon rendering in head layout
- **WHEN** rendering the administrative HTML layout
- **THEN** the `<link rel="icon">` dynamically resolves to the active institutional `favicon_url` (or the default system icon if operating in global mode).
