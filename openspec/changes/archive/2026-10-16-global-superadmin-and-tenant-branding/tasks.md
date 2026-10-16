# Tasks

## 1. Models & Migrations

- [x] 1.1 Create database migration adding `favicon` (varchar 255, nullable) after `logo` in `schools` table and execute `php artisan migrate`.
- [x] 1.2 Prepare categorized directories under `public/uploads/schools/logos` and `public/uploads/schools/favicons` (removing any reliance on `php artisan storage:link`).
- [x] 1.3 Update [School.php](file:///c:/laragon/www/laravel-school/app/Models/School.php) Eloquent model `$fillable` array to include `favicon`, and implement `logo_url` and `favicon_url` accessors checking `public_path()` and returning `asset(...)` with defaults.

## 2. Controllers & Business Logic

- [x] 2.1 Update [StoreSchoolRequest.php](file:///c:/laragon/www/laravel-school/app/Http/Requests/Admin/StoreSchoolRequest.php) to validate `logo` (`image|mimes:jpeg,png,jpg,webp,svg|max:2048`) and `favicon` (`file|mimes:ico,png,svg|max:1024`).
- [x] 2.2 Update [UpdateSchoolRequest.php](file:///c:/laragon/www/laravel-school/app/Http/Requests/Admin/UpdateSchoolRequest.php) with matching binary upload validation rules.
- [x] 2.3 Refactor [SchoolController.php](file:///c:/laragon/www/laravel-school/app/Http/Controllers/Admin/SchoolController.php) `store` method to move uploaded `logo` and `favicon` directly to `public/uploads/schools/logos/` and `public/uploads/schools/favicons/` and store relative paths in DB.
- [x] 2.4 Refactor [SchoolController.php](file:///c:/laragon/www/laravel-school/app/Http/Controllers/Admin/SchoolController.php) `update` method to handle file replacement, delete obsolete asset files using `File::delete(public_path(...))`, and update database paths.

## 3. Layout & Blade Architecture (Tenant-Agnostic Super Admin)

- [x] 3.1 Update [AppServiceProvider.php](file:///c:/laragon/www/laravel-school/app/Providers/AppServiceProvider.php) view composer so Super Administrators without a resolved `current_school_id` are not forced into `School::first()`.
- [x] 3.2 Update [navbar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/navbar.blade.php) to render a "Platform Administration • Global Super Admin" badge when operating in Super Admin platform mode.
- [x] 3.3 Update [sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php) header brand text to present platform-level branding for Super Admin while presenting institutional branding for school-scoped admins.
- [x] 3.4 Update [head.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/head.blade.php) to render dynamic `<link rel="icon">` utilizing the active or inspected school's `favicon_url`.

## 4. Blade Views & Interactions (Dropify Real-time Preview UX)

- [x] 4.1 Integrate Dropify / rich jQuery file uploader assets (CSS and JS) supporting drag-and-drop, real-time image preview, file removal, and file size checks.
- [x] 4.2 Update [create.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/schools/create.blade.php) with `enctype="multipart/form-data"` and Dropify-powered real-time preview file upload inputs for logo and favicon.
- [x] 4.3 Update [edit.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/schools/edit.blade.php) with multipart encoding, pre-loaded current image previews (`data-default-file`), and real-time replacement controls.
- [x] 4.4 Update [index.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/schools/index.blade.php) and [show.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/schools/show.blade.php) to display custom uploaded logos and favicons seamlessly.

## 5. Verification & Testing

- [x] 5.1 Verify Super Admin navigation reflects global platform status across dashboard, schools directory, and settings without tenant lock-in.
- [x] 5.2 Test creating an institution tenant with direct logo and favicon uploads into `public/uploads/schools/...`, verifying real-time Dropify preview, file persistence, and database relative paths.
- [x] 5.3 Test editing an institution tenant with new image uploads, verifying replacement and disk cleanup of replaced files via `File::delete(public_path(...))`.
- [x] 5.4 Run Laravel Pint (`./vendor/bin/pint`) to ensure code formatting and PSR-12 compliance.
