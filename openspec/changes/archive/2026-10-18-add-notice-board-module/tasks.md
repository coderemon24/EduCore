# Tasks

## 1. Models & Migrations

- [x] 1.1 Create migration `2026_10_18_120000_create_notices_table.php` with foreign keys to `schools`, `users`, and `academic_classes`, composite indexes, soft deletes, and audience fields.
- [x] 1.2 Run database migrations (`php artisan migrate`) and verify table creation in MySQL.
- [x] 1.3 Create `App\Models\Notice` model incorporating `BelongsToSchool`, `SoftDeletes`, audience/type constants, author and class relationships, and search/filter scopes.
- [x] 1.4 Update `database/seeders/RolePermissionSeeder.php` to define the `notices` permission group (`notices.view`, `notices.create`, `notices.edit`, `notices.delete`), attach them to default roles, and run the seeder.

## 2. Layout & Blade Architecture

- [x] 2.1 Update `resources/views/layouts/admin-partial/sidebar.blade.php` to replace the mock SweetAlert Notice Board link with live route `admin.notices.index` and authorization check.
- [x] 2.2 Ensure active route detection (`request()->routeIs('admin.notices.*')`) and responsive sidebar badge styling.

## 3. Controllers & Business Logic

- [x] 3.1 Create Form Requests `App\Http\Requests\Admin\Notice\StoreNoticeRequest` and `UpdateNoticeRequest` with validation for title, content, audience, conditional class requirement, dates, and file attachments (max 5MB).
- [x] 3.2 Create `App\Http\Controllers\Admin\NoticeController` implementing `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`, `togglePin`, and `bulkAction`.
- [x] 3.3 Register resource routes, `toggle-pin`, and `bulk-action` endpoints in `routes/admin.php` protected by `school.permission` middleware.

## 4. Blade Views & Interactions

- [x] 4.1 Create `resources/views/admin/notices/index.blade.php` with metric summary cards, status tabs (All, Published, Draft, Archived, Trash), audience/type filters, search bar, and floating bulk action bar.
- [x] 4.2 Create `resources/views/admin/notices/partials/table.blade.php` for high-density tabular presentation, status badges, pin icons, and AJAX partial reload.
- [x] 4.3 Create `resources/views/admin/notices/create.blade.php` with audience selector, conditional class dropdown, date pickers, pin toggle, clean content editor, and document attachment upload.
- [x] 4.4 Create `resources/views/admin/notices/edit.blade.php` with pre-filled inputs, existing attachment preview/replace controls, and update actions.
- [x] 4.5 Create `resources/views/admin/notices/show.blade.php` with institutional circular preview layout, sender attribution, audience tags, incremented view count, and download badge.
- [x] 4.6 Implement SweetAlert2 confirmation dialogs and bulk selection JavaScript for single delete, trash, restore, and permanent deletion.

## 5. Verification & Testing

- [x] 5.1 Verify multi-tenant isolation: confirm queries and operations are strictly scoped to the active tenant school.
- [x] 5.2 Verify RBAC permission gates: confirm users without `notices.create` or `notices.delete` are blocked with HTTP 403.
- [x] 5.3 Verify bulk deletion, restore, and permanent purge flows with SweetAlert2 modals.
- [x] 5.4 Verify file upload validation and file unlinking upon permanent deletion.
- [x] 5.5 Run code formatting with Laravel Pint (`vendor/bin/pint`) to verify code standard compliance.
