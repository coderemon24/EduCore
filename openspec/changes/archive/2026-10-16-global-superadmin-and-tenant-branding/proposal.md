# Proposal

## Why
In a multi-tenant academic SaaS platform, a `super_admin` operates at the global infrastructure/platform level rather than as a member of any single school. Currently, the admin navigation layout ([navbar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/navbar.blade.php) and [sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php)) binds to the first school in the database (`School::first()`) whenever `current_school_id` is unset. This incorrectly forces the Super Admin into an active tenant context ("EduCore Academy") rather than presenting a platform-wide view.

Additionally, onboarding new schools currently restricts institutional branding (`logo`) to static text path strings, and the database schema lacks a dedicated `favicon` column entirely. Administrators require direct file upload capabilities for both institutional crests/logos and browser tab favicons during school creation and updates. To maximize reliability across hosting environments without relying on `php artisan storage:link` symbolic links, all media files will be saved in categorized directories directly under `public/uploads/` (e.g. `public/uploads/schools/logos/` and `public/uploads/schools/favicons/`). In the UI forms, administrators require interactive, real-time image preview and file management using a rich jQuery dropzone/dropify uploader.

## What Changes
1. **Decouple Super Admin from Active Tenant Context**:
   - Provide explicit platform-level navigation for authenticated `super_admin` users. When logged in as Super Admin, top-level navigation and sidebar headers will present a "Global Platform Administration" status rather than falsely displaying a default school.
   - Super Administrators maintain unrestricted platform-wide ability to create, inspect, edit, update lifecycle status, archive (soft delete), restore, and permanently purge any institutional tenant without needing to be scoped to that school.
   - Tenant-scoped administrators (school principals, staff) will remain strictly isolated to their assigned `school_id`.

2. **Categorized Direct Uploads under `public/uploads` (No Symlink Required)**:
   - Eliminate symbolic link requirements (`php artisan storage:link`). All tenant branding files will be uploaded directly to `public/uploads/schools/logos/` and `public/uploads/schools/favicons/`.
   - Store relative public asset paths in the database (e.g. `uploads/schools/logos/logo_1729000000.png` and `uploads/schools/favicons/favicon_1729000000.ico`).
   - Add a `favicon` column (`nullable string`) to the `schools` table via migration.
   - Model accessors `logo_url` and `favicon_url` verify local file existence in `public_path()` and return direct `asset(...)` URLs with system default fallbacks.
   - Handle file replacement and disk cleanup directly via `File::delete(public_path(...))` upon school updates.

3. **Real-time Image Preview with Rich jQuery Uploader (Dropify-style)**:
   - Implement rich dropzone file inputs with instant real-time image preview, file removal, drag-and-drop feedback, file size indicator, and MIME validation in [create.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/schools/create.blade.php) and [edit.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/schools/edit.blade.php).
   - In edit mode, pre-populate existing school logos and favicons in the upload view so administrators immediately see current branding.
   - Dynamically load the active or inspected school's `favicon_url` into the administrative HTML `<head>` tag.

## Capabilities

### Modified Capabilities
- `multi-tenant-schools`: Expands the `schools` schema with `favicon`, establishes direct categorized file upload handling into `public/uploads/` without symbolic link requirements, integrates rich real-time dropzone/dropify preview in tenant forms, provides direct public asset model accessors with fallbacks, and clarifies the platform-wide tenant-agnostic context for Super Administrators across administrative views.

## Impact
- **Database Schema**: New migration adding `favicon` (varchar 255, nullable) after `logo` to the `schools` table.
- **File System / Media**: Categorized folders under `public/uploads/schools/logos/` and `public/uploads/schools/favicons/`. No `php artisan storage:link` required.
- **Eloquent Models**: [School.php](file:///c:/laragon/www/laravel-school/app/Models/School.php) updated to mass-assign `favicon` and expose `logo_url` and `favicon_url` attribute accessors via `asset(...)`.
- **Form Requests**: [StoreSchoolRequest.php](file:///c:/laragon/www/laravel-school/app/Http/Requests/Admin/StoreSchoolRequest.php) and [UpdateSchoolRequest.php](file:///c:/laragon/www/laravel-school/app/Http/Requests/Admin/UpdateSchoolRequest.php) updated to validate uploaded file binaries (`image`/`file`, mimes, max size).
- **Controllers**: [SchoolController.php](file:///c:/laragon/www/laravel-school/app/Http/Controllers/Admin/SchoolController.php) updated to move uploaded files to `public/uploads/schools/*` and delete previous files on update using `File::delete(public_path(...))`.
- **Views & UI**:
  - [create.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/schools/create.blade.php) & [edit.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/schools/edit.blade.php) upgraded with `enctype="multipart/form-data"` and interactive Dropify-style real-time image preview uploaders.
  - [navbar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/navbar.blade.php) & [sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php) updated so Super Admins see "Global Platform Admin" while school-scoped views show specific tenant context.
  - [head.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/head.blade.php) updated with dynamic favicon link.
