# Design: Notice Board & Announcements Module

## Context

See [proposal.md](proposal.md) for background motivation and scope.

The EduCore School Management platform operates on Laravel 12 with multi-tenant scoping governed by the `BelongsToSchool` trait and `SchoolScope`. The application utilizes role-based access control (RBAC) via the `roles` and `permissions` tables, enforced by the `school.permission` middleware. The user interface uses Blade templates and Tailwind CSS v4 without frontend bloating or generic AI aesthetic tropes. The sidebar navigation currently links "Notice Board" to a placeholder JavaScript alert.

## Goals / Non-Goals

**Goals:**
- Provide complete CRUD workflows for school notices (Directory, Create, Edit, View, and Delete).
- Support audience segmentation: All Users, Teachers & Staff, Students, Parents/Guardians, or Class-Specific targeting.
- Implement responsive filtering by publication status (`all`, `published`, `draft`, `archived`, `trash`) and notice type.
- Implement bulk actions (move to trash, restore, delete forever) with SweetAlert2 confirmation dialogs.
- Provide safe document attachment uploads (PDF, DOCX, JPG, PNG up to 5MB) scoped by tenant.
- Replace placeholder navigation in the admin sidebar with live route integration and active state styling.

**Non-Goals:**
- External SMS/WhatsApp or mass email broadcasting services (deferred to a dedicated communication gateway module).
- Heavy external WYSIWYG editor dependencies (keep simple, clean textarea formatting without unverified third-party scripts).
- Push notification service workers.

## Decisions

### 1. Multi-Tenant Eloquent Model Architecture
- **Decision**: Implement `App\Models\Notice` using the `BelongsToSchool` trait and `SoftDeletes`.
- **Rationale**: Guarantees that any query automatically filters by `school_id`, preventing cross-tenant data leakage. Automatically injects `school_id` upon record creation.
- **Alternatives Considered**: Manual tenant scoping in controller queries. Rejected due to high risk of missed tenant filters in future endpoints.

### 2. Notice Categorization & Audience Segmentation
- **Decision**: Store `type` (`general`, `academic`, `exam`, `holiday`, `event`, `urgent`) and `target_audience` (`all`, `teachers`, `students`, `guardians`, `class_specific`) as string columns with model constants/enums.
- **Rationale**: Provides clear semantic categorization for institutional bulletins while allowing flexible querying. When `target_audience` is set to `class_specific`, `academic_class_id` is validated to ensure it belongs to the current school institution.
- **Alternatives Considered**: Separate tables for audience pivots. Rejected as over-engineering for single-target circulars.

### 3. Attachment Handling & Storage
- **Decision**: Store uploaded attachments under `public/uploads/notices/{school_id}/` using the configured public disk. Store the stored path in `attachment_path` and the original uploaded name in `attachment_name`.
- **Rationale**: Segregates uploads by tenant institution on disk. Keeping the original name allows displaying user-friendly download badges (e.g., `Exam_Routine_2026.pdf`).
- **File Types Allowed**: `pdf`, `doc`, `docx`, `jpg`, `jpeg`, `png` (max 5120 KB).

### 4. Bulk Actions & SweetAlert2 Modals
- **Decision**: Replicate the proven bulk action pattern found in `GuardianController` and `StudentController`. The UI renders row checkboxes, a dynamic floating action bar, and triggers SweetAlert2 modals before submitting POST requests to `admin.notices.bulk-action`.
- **Rationale**: Consistent UX across the admin portal. Prevents accidental mass deletions through explicit confirmation dialogs.

### 5. Seamless AJAX Live Filtering & Search
- **Decision**: Implement debounced AJAX requests on the search input and filter dropdowns. The controller detects AJAX requests and returns the rendered `admin.notices.partials.table` Blade partial.
- **Rationale**: Instant responsive search without full page reloads, falling back gracefully to standard full-page HTTP requests when JavaScript is disabled.

## Risks / Trade-offs

- **[Risk] Orphaned attachment files upon notice deletion**  
  → *Mitigation*: When a notice is permanently purged (`forceDelete`), an Eloquent model `deleting` event listener or controller cleanup unlinks the physical file from disk.

- **[Risk] Cross-tenant class association in class-specific notices**  
  → *Mitigation*: The FormRequest validates that `academic_class_id` exists in the `academic_classes` table with matching `school_id`.

- **[Risk] Unescaped HTML in notice content**  
  → *Mitigation*: Content is rendered using standard Blade escaping `{{ $notice->content }}` with `nl2br(e($notice->content))` to preserve line breaks safely without XSS vulnerability.

## Migration Plan

1. Create migration `2026_10_18_120000_create_notices_table.php`.
2. Run database migration (`php artisan migrate`).
3. Seed `notices` permissions in `RolePermissionSeeder.php` and attach to default roles.
4. Verify table schema and indexes.
