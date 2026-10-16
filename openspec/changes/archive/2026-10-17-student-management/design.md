# Design: Student Management

## Context

The application is built on Laravel 12 (PHP 8.2+) with row-level multi-tenancy enforced by the `BelongsToSchool` trait and `SchoolScope` bound to `app('current_school_id')`. Academic structures (`academic_years`, `academic_classes`, `sections`, `academic_groups`) are in place. The admin interface uses Tailwind CSS v4, Blade components, Select2 for searchable selectors, Dropify for image uploads, and ToastMagic for notifications.

Currently, students and parent records do not exist. To avoid historical data loss when students advance to higher grades or change sections, student identity must be decoupled from temporal academic enrollment.

## Goals / Non-Goals

**Goals:**
- Provide a robust domain model separating durable student identity (`students`) from time-bound academic enrollment records (`student_enrollments`).
- Support guardian networks via a dedicated `guardians` catalog and `guardian_student` pivot to handle siblings without duplicate parent entries.
- Implement an auto-suggested admission number format (`ADM-{YEAR}-{SEQ}`) with manual administrator override, guaranteed unique per school.
- Build high-density, institutional UI views: Student Directory, Multi-Section Admission Form, Student Profile with Academic Timeline, and Promotion/Transfer interfaces.
- Deliver both single-student promotion/transfer and a bulk class promotion tool.
- Integrate an interactive "Student Management" sidebar accordion matching the "Academic Structure" accordion.

**Non-Goals:**
- Student or Parent self-service web portal login accounts (student/parent auth will be addressed in a dedicated portal change).
- Admission fee invoice generation or online gateway collection (deferred to fee management module).
- Biometric hardware integration for attendance (deferred to attendance tracking).

## Decisions

### 1. Data Model: Durable Student vs. Temporal Enrollment
- **Choice**: Separate `students` and `student_enrollments`.
  - `students`: Contains unchanging demographic data (`admission_number`, `admission_date`, `first_name`, `last_name`, `gender`, `date_of_birth`, `blood_group`, `religion`, `email`, `phone`, `photo`, `address`, `status`).
  - `student_enrollments`: Contains session-bound placement (`academic_year_id`, `academic_class_id`, `section_id`, `academic_group_id`, `roll_number`, `status`, `enrolled_at`, `promoted_at`, `remarks`).
- **Rationale**: When a student is promoted from Class 9 to Class 10, their past report cards, attendance logs, and fee ledgers must reference Class 9. Modifying the student's class directly in a single flat table destroys history.
- **Alternatives Considered**: Storing `academic_class_id` directly on `students` with a change log table. Rejected because foreign keys in past ledgers would point to an evolving state rather than an immutable historical record.

### 2. Family & Guardian Network: Pivot Table Architecture
- **Choice**: Dedicated `guardians` catalog linked to students via `guardian_student` pivot (`student_id`, `guardian_id`, `relationship`, `is_primary`, `is_emergency_contact`).
- **Rationale**: Families frequently enroll multiple siblings in the same school. Storing guardian details directly in each student row causes data duplication and conflicting phone numbers when a parent updates their contact info.
- **Alternatives Considered**: Flat guardian columns on `students` table. Rejected due to inability to link siblings or support multiple parents/guardians (e.g. father, mother, local guardian).

### 3. Admission Number Sequence Engine
- **Choice**: Service helper `StudentAdmissionService::generateNextAdmissionNumber($schoolId, $academicYear)` that inspects the highest numerical suffix in `ADM-{YEAR}-{0001}` for that school and proposes the next number in the form, allowing manual override.
- **Database Constraint**: `UNIQUE (school_id, admission_number)` guarantees no duplicate admission numbers even if two admins submit concurrently.
- **Alternatives Considered**: Global database auto-increment ID as admission number. Rejected because schools require institutional formats matching their paper ledgers.

### 4. Promotion & Transfer Workflows: Single & Bulk
- **Choice**:
  - **Single Promotion** (`/admin/students/{student}/promote`): Form showing current enrollment, target year/class/section/roll, and execution within `DB::transaction`.
  - **Bulk Promotion** (`/admin/students/promotions`): Source class/section selector with AJAX student roster loader, checkboxes, destination class/section selector, and bulk transition in `DB::transaction`.
- **Workflow State Transition**:
  - Old enrollment status transitions: `current` → `promoted` (or `transferred`).
  - New enrollment created with status: `current`.
  - Overall student status updated to `active` (or `transferred`).

### 5. Media Handling & Direct File Storage
- **Choice**: Public disk storage in `public/uploads/students/photos/` with cryptographically random names (`student_{uniqid}_{hash}.{ext}`).
- **Dropify Integration**: Dropify initialization in Blade views for instant image preview, format validation, and removal before submit.
- **Cleanup**: Delete prior photo using `File::delete(public_path($oldPhoto))` when replaced or student deleted.

### 6. Sidebar Accordion Navigation
- **Choice**: Replicate the CSS/JS accordion pattern from `sidebar-academic-toggle`:
  - ID: `sidebar-students-dropdown-wrapper`
  - Toggle: `sidebar-students-toggle`
  - Submenu: `sidebar-students-submenu`
  - Items:
    1. Student Directory (`admin.students.index`)
    2. Student Admission (`admin.students.create`)
    3. Promotions & Transfers (`admin.students.promotions.index`)

## Risks / Trade-offs

- **[Risk] Concurrent admission number submission collisions**
  → *Mitigation*: Enforce `unique(['school_id', 'admission_number'])` at database level and wrap creation in `DB::transaction()` with friendly validation exception handling.
- **[Risk] Cross-tenant class or section injection via forged POST requests**
  → *Mitigation*: Form Requests validate that `academic_class_id` and `section_id` belong to the active `current_school_id`:
    `Rule::exists('academic_classes', 'id')->where('school_id', $schoolId)`.
- **[Risk] Orphaned enrollments or half-created students during upload errors**
  → *Mitigation*: Process file upload only after request validation passes; if the DB transaction fails, delete any newly uploaded image from disk.
- **[Risk] N+1 performance degradation on large student rosters**
  → *Mitigation*: Directory controller eager-loads `currentEnrollment.academicClass`, `currentEnrollment.section`, and `guardians` with composite indexes on `(school_id, status)` and `(school_id, academic_year_id, academic_class_id)`.
