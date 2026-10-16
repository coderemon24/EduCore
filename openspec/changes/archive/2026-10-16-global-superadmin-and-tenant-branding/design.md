# Design

## Context
See [proposal.md](file:///c:/laragon/www/laravel-school/openspec/changes/global-superadmin-and-tenant-branding/proposal.md) for background and motivation.

Currently, the application uses row-level multi-tenancy enforced by `BelongsToSchool` and `SchoolScope`. The `admins` table has a `role` column (`super_admin`, `admin`, `member`) and does not possess a `school_id`. However, the view composer in [AppServiceProvider.php](file:///c:/laragon/www/laravel-school/app/Providers/AppServiceProvider.php) defaults `$currentSchool` to `School::first()` when `current_school_id` is missing in the container. As a result, the admin navbar and sidebar always display "EduCore Academy", creating the incorrect perception that Super Admins are restricted to a single tenant.

Furthermore, [schools table](file:///c:/laragon/www/laravel-school/database/migrations/2026_10_16_000000_create_schools_table.php) only has a `logo` column (persisting a static string path), and forms in [create.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/schools/create.blade.php) lack file upload inputs and multipart encoding. To maximize portability and remove deployment complexity, all media assets will be stored directly under `public/uploads/` in categorized folders without requiring symbolic links (`storage:link`). Forms will integrate Dropify / rich jQuery file uploader widgets for interactive real-time visual previews.

## Goals / Non-Goals

**Goals:**
- Provide a clean, tenant-agnostic administrative experience for Super Administrators, displaying "Platform Management" rather than binding to any single school by default.
- Allow Super Admins to freely view, create, edit, update lifecycle status, and delete any institutional tenant without tenant context switching hurdles.
- Enable direct binary image uploads for school `logo` and `favicon` during tenant onboarding and profile updates.
- Store uploaded branding assets directly in categorized subdirectories under `public/uploads/` (`public/uploads/schools/logos/` and `public/uploads/schools/favicons/`) without needing `php artisan storage:link`.
- Expose convenient `$school->logo_url` and `$school->favicon_url` accessors with system default fallbacks.
- Integrate a rich Dropify / jQuery drag-and-drop file uploader with real-time image preview, file removal, size feedback, and pre-loaded images in edit mode.
- Dynamically render the school's favicon in the browser tab.

**Non-Goals:**
- Relying on Laravel's `storage/app/public` symlinks (`php artisan storage:link` is explicitly avoided).
- Separate database per tenant or subdomain DNS multi-tenancy (row-level isolation via `SchoolScope` remains standard).
- Tenant user impersonation tokens (Super Admin manages all schools directly via platform administrative routes).

## Decisions

### Decision 1: Decoupling Super Admin in AppServiceProvider and Navigation Layouts
- **Choice**: In [AppServiceProvider.php](file:///c:/laragon/www/laravel-school/app/Providers/AppServiceProvider.php), if the authenticated admin has `role === 'super_admin'` and no explicit `current_school_id` has been set, do not assign `School::first()` as `$currentSchool`. Instead, leave `$currentSchool` as `null` and provide a platform admin indicator.
- **Navigation Presentation**:
  - In [navbar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/navbar.blade.php), when in Super Admin platform mode, show a badge: `[Platform Administration • Global Super Admin]` with a platform icon.
  - In [sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php), show "EduCore Platform • SaaS Management" when in global mode.

### Decision 2: Categorized Direct File Uploads in `public/uploads/` (No Symlink)
- **Choice**: Store files directly in `public/uploads/schools/logos/` and `public/uploads/schools/favicons/`.
- **Destination & Directory Creation**:
  ```php
  $destinationPath = public_path('uploads/schools/logos');
  if (!File::isDirectory($destinationPath)) {
      File::makeDirectory($destinationPath, 0755, true, true);
  }
  $filename = 'logo_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
  $file->move($destinationPath, $filename);
  $relativePath = 'uploads/schools/logos/' . $filename;
  ```
- **Column Persistence**: Store relative paths (`uploads/schools/logos/...` and `uploads/schools/favicons/...`) in `logo` and `favicon` columns.
- **Accessors in Model**:
  ```php
  public function getLogoUrlAttribute(): string
  {
      return $this->logo && file_exists(public_path($this->logo))
          ? asset($this->logo)
          : asset('admin-assets/images/laravel-school-transparent.png');
  }

  public function getFaviconUrlAttribute(): string
  {
      return $this->favicon && file_exists(public_path($this->favicon))
          ? asset($this->favicon)
          : asset('admin-assets/images/laravel-school-transparent.png');
  }
  ```
- **Cleanup on Update**: When updating with a new file, if `$school->logo` or `$school->favicon` exists on disk at `public_path(...)`, delete it with `File::delete(public_path(...))` to prevent orphan files.

### Decision 3: Dropify / Rich jQuery File Uploader with Real-time Preview
- **Choice**: Integrate a rich Dropify file uploader in [create.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/schools/create.blade.php) and [edit.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/schools/edit.blade.php).
- **Features Provided**:
  - Drag and drop box with custom institutional iconography and clear instructional text.
  - Instant real-time preview of selected image prior to submitting the form.
  - Built-in "Remove" button allowing users to cancel a chosen image or clear the selection.
  - File size and format checking with immediate visual alert on client-side.
  - Pre-loading existing logo and favicon images via `data-default-file="{{ $school->logo_url }}"` in edit mode.

## Risks / Trade-offs

- **[Risk] Directory permissions in production environment**:
  → *Mitigation*: Ensure `File::makeDirectory(..., 0755, true, true)` creates parent folders automatically if missing.
- **[Risk] Existing views assuming `$currentSchool` is never null**:
  → *Mitigation*: Audit [dashboard.blade.php](file:///c:/laragon/www/laravel-school/resources/views/admin/dashboard.blade.php), [navbar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/navbar.blade.php), and [sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php) using optional chaining `{{ $currentSchool?->name ?? 'EduCore SIS' }}` and conditional checks for platform mode.
- **[Risk] Malicious file uploads**:
  → *Mitigation*: Strictly validate file types (`image` rule, specific MIME extensions: `jpeg,png,jpg,webp,svg` for logo and `ico,png,svg` for favicon) and constrain max file size (2MB for logo, 1MB for favicon).

## Migration Plan
1. Generate migration `2026_10_16_000001_add_favicon_to_schools_table.php` adding nullable `favicon` column.
2. Run `php artisan migrate`.
3. Update `School` model with `$fillable`, `$appends`, and direct `public_path()` asset accessors.
4. Update `StoreSchoolRequest` and `UpdateSchoolRequest`.
5. Update `SchoolController` `store()` and `update()` methods with categorized `public/uploads` storage and cleanup logic.
6. Integrate Dropify / rich uploader assets and configure file inputs in `create.blade.php` and `edit.blade.php`.
7. Update navigation views (`navbar.blade.php`, `sidebar.blade.php`, `head.blade.php`).
8. Verification via browser inspection.
