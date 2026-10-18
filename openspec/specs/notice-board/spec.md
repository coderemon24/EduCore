# notice-board Specification

## Purpose
Provides a tenant-scoped institutional notice board and communication bulletin system for schools to publish, schedule, categorize, and distribute announcements to specific audiences (all users, teachers, students, parents, or specific academic classes) with document attachment support and bulk deletion safeguards.

## Requirements

### Requirement: Tenant-Scoped Notice Data Model and Persistence
The system MUST persist institutional notices in a tenant-isolated relational table (`notices`) with foreign key associations to `schools`, `users` (author), and optionally `academic_classes`, enforcing tenant isolation across all lifecycle events via global scopes and the `BelongsToSchool` trait.

#### Scenario: Notice creation with tenant attribution
- **WHEN** an authenticated institutional user creates a notice
- **THEN** `school_id` is automatically set to the active tenant's school ID
- **AND** `created_by` is set to the authenticated user's ID
- **AND** `title`, `slug`, `content`, `type`, `target_audience`, and `status` are stored securely.

#### Scenario: Cross-tenant notice isolation
- **WHEN** an administrator queries notices for School A
- **THEN** notices belonging to School B are excluded from all query results, counts, and administrative views.

---

### Requirement: Role-Based Authorization and Access Control for Notices
The system MUST enforce granular permissions (`notices.view`, `notices.create`, `notices.edit`, `notices.delete`) through the `school.permission` authorization middleware across all notice routes.

#### Scenario: Authorized user accesses notice creation
- **WHEN** an administrator with the `notices.create` permission accesses the notice creation endpoint
- **THEN** the request succeeds with HTTP 200 and the creation form is rendered.

#### Scenario: Unauthorized user blocked from deleting notices
- **WHEN** an authenticated user lacking `notices.delete` attempts to delete or bulk delete notices
- **THEN** the request is rejected with HTTP 403 Access Denied.

---

### Requirement: Notice Directory Management, Filtering, and Bulk Deletion
The administrative notice directory at `/admin/notices` MUST provide real-time search, category and audience filtering, status tabs (`all`, `published`, `draft`, `archived`, `trash`), and bulk action workflows (move to trash, restore, and permanent deletion) protected by SweetAlert confirmation dialogs.

#### Scenario: Filtering notices by audience and status
- **WHEN** an administrator filters notices by `target_audience = 'guardians'` and `status = 'published'`
- **THEN** the list dynamically updates to display only matching published notices targeted to parents and guardians.

#### Scenario: Executing bulk deletion to trash
- **WHEN** an administrator selects multiple notice rows and confirms the "Move to Trash" bulk action
- **THEN** the selected notices are soft-deleted from the active list
- **AND** a success toast message indicates the exact count of notices moved to trash.

#### Scenario: Permanent purge from trash
- **WHEN** an administrator confirms a permanent deletion bulk action in the trash tab
- **THEN** associated attachment files on disk are unlinked
- **AND** the records are permanently removed from the database.

---

### Requirement: Notice Composition, Scheduling, and Attachment Handling
The system MUST support composing notices with optional document attachments (PDF, DOCX, JPG, PNG up to 5MB), publishing dates (`published_at`, `expires_at`), pin-to-top flags (`is_pinned`), and audience selection (including class-specific targeting).

#### Scenario: Composing a class-specific notice
- **WHEN** an administrator selects `target_audience = 'class_specific'`
- **THEN** an academic class selector becomes required
- **AND** the notice is associated with the selected `academic_class_id`.

#### Scenario: Uploading circular document attachment
- **WHEN** a valid PDF circular is uploaded during notice creation
- **THEN** the file is stored safely in tenant-isolated public disk storage
- **AND** `attachment_path` and original `attachment_name` are persisted to the database.

---

### Requirement: Notice Presentation and Circular Preview
The system MUST render notice details at `/admin/notices/{notice}` in a clean, institutional bulletin layout showing publication metadata, audience badge, priority indicators, full announcement content, and downloadable attachments.

#### Scenario: Viewing notice bulletin details
- **WHEN** an authorized user navigates to a notice details page
- **THEN** the system displays the notice title, author name, publication date, type badge, target audience badge, and rendered body text
- **AND** provides a direct download link if an attachment is present
- **AND** increments the `view_count` counter.
