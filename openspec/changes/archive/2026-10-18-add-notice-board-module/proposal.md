# Proposal: Add Notice Board & Announcements Module

## Why

Currently, the School Management System lacks an official announcement and bulletin board system. In the admin navigation sidebar, the "Notice Board" item is only a placeholder displaying a temporary JavaScript alert (`Swal.fire`). 

Educational institutions require a reliable, multi-tenant communication channel to broadcast circulars, academic notices, examination schedules, event invitations, and urgent alerts to specific stakeholders (All, Teachers/Staff, Students, Parents/Guardians, or specific Academic Classes). 

Implementing this module provides immediate operational value, replaces the placeholder navigation link with a fully realized administrative workflow, and ensures seamless tenant isolation and role-based authorization.

## What Changes

1. **Database Schema & Eloquent Model**:
   - Create a new `notices` table scoped to `school_id` with foreign key constraints to `schools`, `users` (author), and optionally `academic_classes`.
   - Add columns for `title`, `slug`, `content`, `type` (general, academic, exam, holiday, event, urgent), `target_audience` (all, teachers, students, guardians, class_specific), `is_pinned`, `status` (draft, published, archived), `published_at`, `expires_at`, `attachment_path`, and `attachment_name`.
   - Provide soft deletion (`SoftDeletes`) and comprehensive composite indexes for performance.
   - Implement the `Notice` model incorporating the `BelongsToSchool` trait and tenant query scopes.

2. **Role-Based Access Control & Permissions**:
   - Introduce the `notices` permission group with: `notices.view`, `notices.create`, `notices.edit`, and `notices.delete`.
   - Seed default permissions across standard institutional roles (`School Admin`, `Teacher`, `Accountant`, `Staff Member`).
   - Protect notice management routes with the `school.permission` middleware.

3. **User Interface & Workflows**:
   - **Directory Page (`/admin/notices`)**: High-density responsive list with metric counters, status tabs (All, Published, Draft, Archived, Trash), audience filters, debounced live search, and a floating bulk action bar.
   - **Bulk Actions**: Checkbox selection with SweetAlert2 confirmation dialogs to move notices to trash, restore, or permanently delete in bulk.
   - **Create & Edit Views (`/admin/notices/create`, `/admin/notices/{notice}/edit`)**: Clean forms with audience selector, dynamic class selector for class-specific announcements, publishing date pickers, pinned bulletin toggle, and document/PDF attachment uploader.
   - **Detail / Preview View (`/admin/notices/{notice}`)**: Institutional circular format displaying author details, target audience badge, formatted content, and downloadable attachments.
   - **Sidebar Navigation**: Connect the sidebar "Notice Board" item to active route `admin.notices.index` with permission gate.

## Capabilities

### New Capabilities
- `notice-board`: Tenant-scoped notice board and announcements management system providing bulletin creation, audience targeting, file attachments, publication schedules, and bulk deletion workflows.

### Modified Capabilities
None.

## Impact

- **Database**: Adds `notices` table migration and updates permission seeders.
- **Routing & Controllers**: Adds `routes/admin.php` resource routes under `/admin/notices` with `NoticeController`.
- **Views**: Creates `resources/views/admin/notices/` directory (`index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`, and `partials/table.blade.php`).
- **Sidebar Layout**: Updates `resources/views/layouts/admin-partial/sidebar.blade.php` to link to live notice routes.
- **Dependencies**: Uses existing storage disk for document attachments and existing SweetAlert2 library for deletion confirmation.
