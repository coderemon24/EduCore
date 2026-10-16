# Tasks: Student Management

## 1. Models & Database Migrations

- [x] 1.1 Create migration for `students` table with `school_id`, `admission_number` (unique per school), `admission_date`, `first_name`, `last_name`, `gender`, `date_of_birth`, `blood_group`, `religion`, `email`, `phone`, `photo`, `current_address`, `permanent_address`, `status`, and composite indexes.
- [x] 1.2 Create migration for `guardians` table with `school_id`, `name`, `phone`, `email`, `occupation`, `address`, `national_id`, and `photo`.
- [x] 1.3 Create migration for `guardian_student` pivot table with `school_id`, `student_id`, `guardian_id`, `relationship`, `is_primary`, and `is_emergency_contact`.
- [x] 1.4 Create migration for `student_enrollments` table with `school_id`, `student_id`, `academic_year_id`, `academic_class_id`, `section_id`, `academic_group_id`, `roll_number`, `status`, `enrolled_at`, `promoted_at`, `remarks`, and performance lookup indexes.
- [x] 1.5 Implement `App\Models\Student` model with `BelongsToSchool`, fillable attributes, date casting, accessors (`full_name`, `photo_url`), and relationships (`enrollments`, `currentEnrollment`, `guardians`, `primaryGuardian`).
- [x] 1.6 Implement `App\Models\Guardian` model with `BelongsToSchool`, relationships (`students`), and contact accessors.
- [x] 1.7 Implement `App\Models\StudentEnrollment` model with `BelongsToSchool` and relationships (`student`, `academicYear`, `academicClass`, `section`, `academicGroup`).
- [x] 1.8 Create Model Factories (`StudentFactory`, `GuardianFactory`, `StudentEnrollmentFactory`) and update database seeders with realistic demo student records.

## 2. Layout & Blade Architecture

- [x] 2.1 Update [sidebar.blade.php](file:///c:/laragon/www/laravel-school/resources/views/layouts/admin-partial/sidebar.blade.php) to add a collapsible "Student Management" accordion matching "Academic Structure", including desktop icon-collapse flyouts and active route indicators for `admin.students.*`.
- [x] 2.2 Register student management admin routes in [routes/admin.php](file:///c:/laragon/www/laravel-school/routes/admin.php) under `admin.students.` (resource routes for students, individual promotion routes, and bulk promotion routes).
- [x] 2.3 Ensure directory `public/uploads/students/photos/` exists and has appropriate writable permissions and web access defaults.

## 3. Controllers & Business Logic

- [x] 3.1 Implement `App\Services\StudentAdmissionService` containing logic for sequential admission number proposal (`ADM-{YEAR}-{SEQ}`) and atomic enrollment transitions.
- [x] 3.2 Create Form Requests:
  - `StoreStudentRequest`: Validates personal details, unique admission number per school, valid tenant academic year/class/section, guardian information, and image file rules.
  - `UpdateStudentRequest`: Validates student edits while safely ignoring current student's admission number.
  - `PromoteStudentRequest`: Validates individual target session, class, section, and roll number.
  - `BulkPromoteStudentsRequest`: Validates array of student IDs and target session/class/section within tenant.
- [x] 3.3 Implement `App\Http\Controllers\Admin\StudentController`:
  - `index`: Directory with search query, multi-facet filtering (year, class, section, status, gender), eager-loading, and pagination.
  - `create`: Prepares admission form with suggested admission number, current session, and academic classes.
  - `store`: Handles transactional creation of student, photo upload, guardian linkage, and initial enrollment.
  - `show`: Detailed profile view with personal overview and academic enrollment history.
  - `edit`: Populates edit form with existing personal and guardian data.
  - `update`: Handles profile updates, photo replacements, and guardian modifications.
  - `destroy`: Safely soft-deletes or archives student and cleans up file storage.
- [x] 3.4 Implement `App\Http\Controllers\Admin\StudentPromotionController`:
  - `promote`: Displays individual student promotion/transfer form.
  - `processPromote`: Executes individual transition inside `DB::transaction`.
  - `bulkIndex`: Renders bulk class promotion interface.
  - `getStudentsByClassSection`: AJAX API to retrieve enrollments for the source class and section.
  - `processBulk`: Executes batch promotion inside `DB::transaction`.

## 4. Blade Views & Dynamic Interactions

- [x] 4.1 Create `resources/views/admin/students/index.blade.php`:
  - Header with stats summary badges (Total Students, Active Students, Male/Female split).
  - Search input and faceted filter dropdowns (Session, Class, Section, Gender, Status).
  - Responsive table with student photo avatar, admission number badge, full name, class/section pill, roll number, guardian phone, status indicator, and quick actions menu.
  - Pagination links and empty state when no students match filters.
- [x] 4.2 Create `resources/views/admin/students/create.blade.php`:
  - Organized 4-card layout: Academic Placement, Student Demographics, Guardian Information, Residential Addresses.
  - Real-time photo preview using Dropify.
  - Working class-to-section dependent dropdown powered by `academic-dependent-dropdown.js`.
  - Form validation error alerts and submit button loading state.
- [x] 4.3 Create `resources/views/admin/students/show.blade.php`:
  - Identity header card with photo, admission number, roll, class, and quick action buttons (Edit, Promote/Transfer).
  - Tab 1: Personal Profile, Guardian Information, and Address Cards.
  - Tab 2: Chronological Academic Enrollment Timeline displaying historical sessions, grades, sections, and promotion statuses.
- [x] 4.4 Create `resources/views/admin/students/edit.blade.php`:
  - Pre-populated form fields and Dropify existing photo preview.
- [x] 4.5 Create `resources/views/admin/students/promote.blade.php`:
  - Side-by-side comparison of Current Enrollment vs. Target Academic Session, Class, Section, and Roll Number.
  - Confirmation dialog with SweetAlert2 before final submission.
- [x] 4.6 Create `resources/views/admin/students/promotions/index.blade.php`:
  - Source selection card (Year, Class, Section) with dynamic student checklist table.
  - Destination selection card (Target Year, Target Class, Target Section).
  - Select all / Deselect all checkboxes, action preview, and batch submit button.

## 5. Verification & Testing

- [x] 5.1 Create `tests/Feature/StudentManagementTest.php` to verify:
  - Student admission persists student, guardian, and initial `current` enrollment.
  - Duplicate admission number in the same school is rejected with validation error.
  - Identical admission number in different schools is accepted (multi-tenant isolation).
  - Uploaded photo is validated, saved to disk, and replaced on update.
  - Individual student promotion updates previous enrollment to `promoted` and creates new `current` enrollment.
  - Bulk class promotion transitions all selected students within a database transaction.
  - Multi-facet search and filtering correctly restrict student directory records.
  - Cross-tenant data isolation prevents School A from viewing or modifying School B's students.
- [x] 5.2 Run database migrations and test suite via `php artisan test --filter=StudentManagementTest`.
- [x] 5.3 Format all PHP code using Laravel Pint (`vendor/bin/pint`).

## 6. Soft Deletes & Bulk Operations

- [x] 6.1 Create database migration `add_soft_deletes_to_guardians_and_enrollments_tables` adding `deleted_at` to `guardians` and `student_enrollments`.
- [x] 6.2 Add `SoftDeletes` trait to `Guardian` and `StudentEnrollment` Eloquent models.
- [x] 6.3 Update `StudentController` with status counts, `trashed` query scoping, `restore`, `forceDelete`, and atomic `bulkAction` (delete, restore, force_delete).
- [x] 6.4 Register routes in `routes/admin.php` for `bulk-action`, `{student}/restore`, and `{student}/force-delete`.
- [x] 6.5 Update `resources/views/admin/students/index.blade.php`:
  - Add status tabs (`All`, `Active`, `Inactive`, `Archived / Trashed`) with count badges.
  - Add Trashed archive context notification banner.
  - Add multi-select checkboxes to table header and row entries.
  - Add dynamic Bulk Action Toolbar with Move to Trash, Restore Selected, Permanently Purge, and Deselect All.
  - Add single row actions for Restore and Purge when viewing trashed records.
- [x] 6.6 Update `resources/views/admin/students/show.blade.php` with Trashed banner and Move to Trash / Restore / Permanently Purge header actions.
- [x] 6.7 Extend `StudentManagementTest.php` with 7 feature tests verifying single/bulk soft delete, restore, force delete, and trashed directory rendering.

