# Proposal: Student Management

## Why

Educational institutions operating on the EduCore multi-tenant platform require a complete Student Information System (SIS) to handle student admissions, durable demographic profiles, family/guardian contact networks, and academic enrollments across academic years, classes, and sections. Currently, the administrative sidebar displays a placeholder modal for students, and no student-specific schema or user interfaces exist.

Students change classes every year and occasionally transfer sections or status (active, promoted, transferred, graduated, suspended). Overwriting existing records causes loss of academic history, prevents auditability, and undermines downstream systems like grade sheets, fee ledgers, and attendance archives.

This change introduces a comprehensive Student Management module with row-level multi-tenancy, historical enrollment tracking, guardian management, safe profile photo uploads, dependent dropdowns, full search/filtering, and both individual and bulk class promotion/transfer workflows.

## What Changes

1. **Database Schema & Eloquent Models**:
   - `students`: Core student profile (admission number unique per school, admission date, personal demographics, contact info, photo path, status).
   - `guardians`: Parent/guardian demographic and contact catalog.
   - `guardian_student`: Pivot table linking students with guardians, capturing relationship type (`father`, `mother`, `legal_guardian`, `other`), primary contact flag, and emergency contact flag.
   - `student_enrollments`: Historical academic enrollment linking a student to an academic year, class, section, optional academic group/stream, roll number, and enrollment status (`current`, `promoted`, `transferred`, `graduated`, `repeated`).

2. **Multi-Tenancy & Integrity Safeguards**:
   - Every model implements `BelongsToSchool` and `SchoolScope`.
   - Strict composite indexing: `(school_id, admission_number)` unique constraint, and composite indexes on `(school_id, status)` and `(school_id, academic_year_id, academic_class_id)`.
   - All multi-record mutations (admissions, promotions, transfers, guardian links) execute inside atomic database transactions (`DB::transaction`).
   - Cross-tenant validation rules preventing cross-school class, section, or student foreign key injection.

3. **Admission Number Generation**:
   - Hybrid numbering system: Automatically generates formatted institutional admission numbers (e.g., `ADM-2026-0001`) with next sequence lookup per school and year, while allowing administrative manual override.

4. **Sidebar Navigation Dropdown**:
   - Interactive, collapsible "Student Management" accordion in [sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php) matching the behavior of "Academic Structure", including smooth desktop collapse, icon flyouts, and active route state highlighting.
   - Sub-items: *Student Directory*, *Student Admission*, and *Promotions & Transfers*.

5. **Administrative User Interfaces**:
   - **Student Directory** (`/admin/students`): Fast listing with real-time search (name, admission number, roll, phone), multi-facet filtering (academic year, class, section, gender, status), pagination, student badges, and quick actions.
   - **Student Admission Form** (`/admin/students/create`): Multi-card organized form (Enrollment Details, Personal Demographics, Guardian Information, Residential Addresses) with Dropify drag-and-drop avatar preview and dynamic class-to-section dependent dropdowns.
   - **Student Profile View** (`/admin/students/{student}`): Comprehensive profile displaying institutional badges, personal info, guardian contacts, and an interactive **Enrollment & Academic History Timeline**.
   - **Student Edit Form** (`/admin/students/{student}/edit`): Edit profile, personal details, contact info, and guardian relationships while keeping enrollment progression intact.
   - **Promotion & Transfer Module**:
     - *Individual Student Promotion/Transfer* (`/admin/students/{student}/promote`): Transition an individual student to a new academic year, class, section, and roll number with audit notes.
     - *Bulk Class Promotion* (`/admin/students/promotions`): Batch tool to select a source session/class/section, list enrolled students with checkboxes, specify the target session/class/section, and promote in a single transaction.

## Capabilities

### New Capabilities
- `student-management`: Student profile management, guardian networks, admission number scoping, temporal enrollment tracking, profile photo uploads, dependent dropdowns, directory search and filtering, and individual/bulk promotion workflows.

### Modified Capabilities
<!-- None. Existing capabilities (academic-structure, multi-tenant-schools) remain intact and provide the foreign key foundations. -->

## Impact

- **Database**: 4 new migration tables (`students`, `guardians`, `guardian_student`, `student_enrollments`) with composite foreign keys and school indexes.
- **Backend Architecture**: New Eloquent models (`Student`, `Guardian`, `StudentEnrollment`), Admin controllers (`StudentController`, `StudentPromotionController`), Form Requests, and multi-tenant authorization gates.
- **Frontend & Navigation**: Update admin sidebar with "Student Management" dropdown; add Blade view templates in `resources/views/admin/students/`.
- **Media & File Storage**: Direct file storage under `public/uploads/students/photos/` with SHA/timestamp hashing and automatic cleanup on replacement or deletion.
- **Dependencies**: Reuses existing `Select2`, `ToastMagic`, `SweetAlert2`, `Dropify`, and `academic-dependent-dropdown.js`.
