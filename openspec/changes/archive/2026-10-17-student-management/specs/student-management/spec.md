# Spec Delta: student-management

## Purpose
The student-management capability delivers institutional Student Information System (SIS) operations, including multi-tenant student admission, demographic profiles, family and guardian networks, historical enrollment preservation across sessions and classes, profile photo management, searchable student directory, and individual/bulk promotion and transfer workflows.

## ADDED Requirements

### Requirement: Multi-Tenant Student Schema and Domain Model
The application MUST maintain a `students` table and `Student` Eloquent model scoped to the active tenant institution via `BelongsToSchool` and `SchoolScope`.

#### Scenario: Student creation under active school
- **WHEN** an administrator admits a new student
- **THEN** the student record is saved with the active tenant's `school_id`, full name, admission date, gender, date of birth, contact details, and status defaults to `active`.

#### Scenario: Cross-school student isolation
- **WHEN** School A and School B manage students
- **THEN** queries for students in School A never return, display, or modify students belonging to School B.

---

### Requirement: Admission Number Generation and Scope-Specific Uniqueness
The application MUST enforce institutional uniqueness on `admission_number` scoped to `school_id`, providing an auto-generated suggested pattern (`ADM-{YEAR}-{SEQ}`) with manual administrator override.

#### Scenario: Auto-generating the next admission number
- **WHEN** an administrator opens the student admission form for the current academic year 2026
- **THEN** the system suggests the next sequential admission number (e.g., `ADM-2026-0001`) based on existing records for that school and year.

#### Scenario: Manual admission number input
- **WHEN** an administrator manually overrides the suggested admission number with an institutional paper ledger number
- **THEN** the system accepts and saves the custom admission number if it is unique within that school.

#### Scenario: Blocking duplicate admission numbers within the same school
- **WHEN** an administrator submits an admission number that already exists within that school
- **THEN** the system rejects the submission with a validation error indicating that the admission number is already taken.

#### Scenario: Allowing identical admission numbers in different schools
- **WHEN** School A and School B both assign admission number `ADM-2026-0001` to their respective students
- **THEN** both records are successfully saved because uniqueness is scoped to `(school_id, admission_number)`.

---

### Requirement: Guardian Catalog and Many-to-Many Student Pivot
The application MUST maintain a `guardians` catalog and a `guardian_student` pivot table to support parent/guardian relationships with relationship type (`father`, `mother`, `legal_guardian`, `other`), primary contact designation, and emergency contact flags.

#### Scenario: Creating a guardian during admission
- **WHEN** admitting a student with guardian details provided
- **THEN** the guardian record is saved or linked under that school, and the `guardian_student` pivot record is created with the designated relationship and contact preferences.

#### Scenario: Reusing existing guardian for siblings
- **WHEN** admitting a sibling of an existing student and selecting an existing guardian
- **THEN** the system links the sibling to the same guardian without duplicating the parent record.

---

### Requirement: Temporal Enrollment Lifecycle and Historical Preservation
The application MUST maintain a `student_enrollments` table recording every academic enrollment with links to `academic_year_id`, `academic_class_id`, `section_id`, optional `academic_group_id`, roll number, and status (`current`, `promoted`, `transferred`, `graduated`, `repeated`). Modifying a student's class MUST preserve prior enrollment rows.

#### Scenario: Initial enrollment on admission
- **WHEN** a student is admitted into Class 9 Section A for Academic Year 2026
- **THEN** a `student_enrollments` row is created with status `current`, `enrolled_at = admission_date`, and linked to the specified academic class and section.

#### Scenario: Preserving historical enrollment on class transition
- **WHEN** a student transitions to another class or academic year
- **THEN** the previous enrollment record status is updated to `promoted` or `transferred` with a completion timestamp, and a new enrollment record is created with status `current`, preserving the full historical ledger.

#### Scenario: Atomic multi-table execution
- **WHEN** admitting or promoting a student
- **THEN** all database operations across `students`, `guardians`, `guardian_student`, and `student_enrollments` are executed in a single transaction, rolling back entirely if any query fails.

---

### Requirement: Profile Photo Upload, Dropify Integration, and Storage Validation
The application MUST provide safe student profile photo upload, validated for image MIME types and file size, stored in `public/uploads/students/photos/`, and rendered with real-time drag-and-drop preview.

#### Scenario: Uploading a valid profile photo
- **WHEN** an administrator uploads a student photo (`jpeg, png, jpg, webp` up to 2048 KB)
- **THEN** the file is moved to `public/uploads/students/photos/` with a cryptographically unique filename, and the relative path is stored in `students.photo`.

#### Scenario: Rejecting invalid file upload
- **WHEN** an administrator uploads a non-image file or an image exceeding 2MB
- **THEN** the system rejects the submission with HTTP 422 and a validation message.

#### Scenario: Replacing photo on student update
- **WHEN** an administrator uploads a new photo for an existing student with a previous photo
- **THEN** the new photo is stored, the database column is updated, and the previous file is removed from disk.

---

### Requirement: Searchable Student Directory with Multi-Facet Filters and Pagination
The application MUST provide a student directory at `GET /admin/students` supporting keyword search across multiple fields, faceted dropdown filters, eager loading, and responsive pagination.

#### Scenario: Multi-facet search in directory
- **WHEN** an administrator searches by keyword (name, admission number, roll number, or phone) or filters by Academic Year, Class, Section, Gender, or Status
- **THEN** the directory displays matching students matching all active criteria with badges for class, section, roll, and status.

#### Scenario: N+1 query prevention on directory
- **WHEN** the student directory renders 25 students per page
- **THEN** `currentEnrollment`, `academicClass`, `section`, and `guardians` are eager loaded in bounded constant queries.

---

### Requirement: Admission Form with Logical Sections and Dynamic Dependent Dropdowns
The application MUST provide an admission form at `GET /admin/students/create` organized into clear visual cards (Enrollment, Personal Demographics, Guardian Information, Residential Addresses), utilizing `academic-dependent-dropdown.js` for dynamic section selection.

#### Scenario: Selecting a class updates available sections
- **WHEN** an administrator selects a Class in the admission form
- **THEN** the Section select element asynchronously populates only sections belonging to that selected class in the current school.

#### Scenario: Validation failure retains inputs and dynamic options
- **WHEN** a submission fails server validation
- **THEN** the form re-renders displaying field-level error messages, retains all previously entered input values, and keeps the dependent section dropdown populated.

---

### Requirement: Student Profile View and Academic Progression Timeline
The application MUST provide a comprehensive profile view at `GET /admin/students/{student}` displaying student identity, status badges, contact numbers, primary guardians, and a chronological enrollment timeline.

#### Scenario: Viewing student academic history
- **WHEN** an administrator views a student's profile
- **THEN** the page displays a timeline of all past and current enrollments, showing academic session, class, section, roll number, enrollment dates, and progression status.

---

### Requirement: Individual Student Promotion and Transfer
The application MUST provide an individual promotion/transfer interface at `/admin/students/{student}/promote` enabling administrators to promote or transfer a student with confirmation.

#### Scenario: Promoting an individual student
- **WHEN** an administrator promotes a student to the next academic year and class
- **THEN** the system atomically updates the previous enrollment status to `promoted`, creates the new enrollment record with status `current`, and displays ToastMagic notification feedback.

---

### Requirement: Bulk Class Promotion Tool
The application MUST provide a batch class promotion interface at `GET /admin/students/promotions` allowing administrators to promote groups of students in a single operation.

#### Scenario: Promoting an entire class section
- **WHEN** an administrator selects source Academic Year 2025, Class 9, Section A, selects enrolled students via checkboxes, selects target Academic Year 2026, Class 10, Section A, and confirms promotion
- **THEN** the system updates each selected student's previous enrollment to `promoted` and creates corresponding new `current` enrollments for the target class/section within a single database transaction.

---

### Requirement: Sidebar Navigation Dropdown Integration
The admin sidebar MUST include a collapsible "Student Management" accordion under *Core Management* matching the behavior of "Academic Structure", replacing the static placeholder modal.

#### Scenario: Expanding Student Management dropdown
- **WHEN** an administrator clicks "Student Management" in the sidebar
- **THEN** the submenu expands smoothly with links to *Student Directory*, *Student Admission*, and *Promotions & Transfers*, rotating the chevron icon.

#### Scenario: Highlight active student route
- **WHEN** navigating any route matching `admin.students.*`
- **THEN** the Student Management sidebar accordion renders expanded and the matching sub-item is visually highlighted.

---

### Requirement: Comprehensive Soft Deletion, Restoration, and Permanent Purge Lifecycle
The application MUST support safe soft-deletion across student management entities (`students`, `guardians`, and `student_enrollments`), providing an archived "Trashed" filter view, single/bulk restoration, and permanent database/disk purging.

#### Scenario: Soft-deleting a student
- **WHEN** an administrator clicks "Move to Trash" on an active student
- **THEN** the student and their associated enrollments are soft-deleted with timestamps, preserving the records in the database.

#### Scenario: Viewing trashed students
- **WHEN** an administrator clicks the "Archived / Trashed" filter tab
- **THEN** the directory displays only soft-deleted student records with an informational banner, and provides "Restore" and "Permanently Purge" actions instead of edit/promote.

#### Scenario: Restoring a soft-deleted student
- **WHEN** an administrator restores an archived student
- **THEN** the student and their associated enrollments are restored (`deleted_at = null`) and reappear on the active directory.

#### Scenario: Permanently purging a student
- **WHEN** an administrator permanently purges a soft-deleted student
- **THEN** their stored photo is removed from disk, guardian pivot associations are detached, and the student and enrollment records are permanently removed from the database.

---

### Requirement: Bulk Selection and Multi-Record Management
The student directory MUST provide multi-select checkboxes and an interactive bulk action toolbar supporting batch operations.

#### Scenario: Bulk soft delete
- **WHEN** an administrator selects multiple active students via table checkboxes and clicks "Move to Trash"
- **THEN** all selected students and their enrollments are soft-deleted in a single atomic transaction.

#### Scenario: Bulk restore
- **WHEN** an administrator selects multiple trashed students and clicks "Restore Selected"
- **THEN** all selected students are restored to the active roster.

#### Scenario: Bulk permanent purge
- **WHEN** an administrator selects multiple trashed students and clicks "Permanently Purge"
- **THEN** all selected students, photos, and enrollment records are permanently removed within a transaction after confirmation.

