# Tasks

## 1. Database Migrations & Eloquent Models

- [x] 1.1 Create migration for `grading_scales` and `grade_tiers` with `school_id`, `fail_any_fails_all` flag, percentage boundaries, grade points, and pass thresholds.
- [x] 1.2 Create migration for `exams`, `exam_subjects`, and `exam_subject_components` supporting dynamic subject assessment components (Written, MCQ, Practical, CA) with unique constraint `[exam_id, academic_class_id, subject_id]`.
- [x] 1.3 Create migration for `exam_marks`, `exam_mark_components`, and `exam_mark_histories` supporting `attendance_status` (`present`, `absent`, `exempt`, `incomplete`), component-level marks, and post-publish correction audit logging with unique constraint `[exam_subject_id, student_id]`.
- [x] 1.4 Create migration for `exam_results` consolidating total marks, subject pass/fail flags, highest marks, GPA, letter grade, and merit ranks (`rank_in_class`, `rank_in_section`).
- [x] 1.5 Create migration for `marksheet_settings` storing school mark sheet template preferences, custom background template uploads, crest, and headmaster signature assets.
- [x] 1.6 Implement Eloquent models (`GradingScale`, `GradeTier`, `Exam`, `ExamSubject`, `ExamSubjectComponent`, `ExamMark`, `ExamMarkComponent`, `ExamMarkHistory`, `ExamResult`, `MarksheetSetting`) with `BelongsToSchool`, relationships, scopes, and casts.

## 2. Seeders, Permissions & Authorization Gates

- [x] 2.1 Update `RolePermissionSeeder` with new exam permissions (`exams.view`, `exams.manage`, `exams.grades.manage`, `exams.marks.entry`, `exams.results.calculate`, `exams.results.publish`, `exams.marks.correct`, `exams.reports.generate`) and seed default permission mappings.
- [x] 2.2 Create `ExamSeeder` seeding default standard GPA 5.0 grading scale with national tiers, sample exam session, class subject configurations, and mock student marks.

## 3. Services & Core Business Logic

- [x] 3.1 Implement `GradingService` to evaluate percentage thresholds, map marks to grade tiers, and compute GPA with `fail_any_fails_all` logic.
- [x] 3.2 Implement `ExamResultService` to compute student aggregated marks, identify class-highest scores per subject, calculate cohort ranks, and handle attendance/co-curricular metrics.
- [x] 3.3 Implement `ExamMarkCorrectionService` to process post-publication mark overrides with mandatory audit logging to `exam_mark_histories` and automated result recalculation.

## 4. Controllers & Form Requests

- [x] 4.1 Implement `GradingScaleController` with CRUD for grading systems and grade tier configurations.
- [x] 4.2 Implement `ExamController` with lifecycle management (`draft` -> `scheduled` -> `marks_entry` -> `published`) and `ExamSubjectController` for dynamic component assignment.
- [x] 4.3 Implement `ExamMarkController` for table-based marks entry, batch saving with validation, and status toggling (`present`, `absent`, `exempt`).
- [x] 4.4 Implement `ExamResultController` for batch calculation, tabulation review, merit list ranking, and publication locks.
- [x] 4.5 Implement `MarksheetSettingController` and report card download/stream actions using `Spatie\LaravelPdf\Facades\Pdf`.
- [x] 4.6 Register admin routes under `routes/admin.php` protected by permission middleware.

## 5. Blade Views & Interactive UI Screens

- [x] 5.1 Update `resources/views/layouts/admin-partial/sidebar.blade.php` to add the **"Exams & Results"** collapsible dropdown menu with active states.
- [x] 5.2 Build Grading Scales management views (`admin.exams.grades.index`, `admin.exams.grades.create`, `admin.exams.grades.edit`).
- [x] 5.3 Build Exam setup and subject component configuration views (`admin.exams.index`, `admin.exams.create`, `admin.exams.subjects`).
- [x] 5.4 Build high-efficiency table-based marks entry screen (`admin.exams.marks.entry`) with keyboard navigation, live percentage/grade preview, and component max indicators.
- [x] 5.5 Build Results Tabulation & Merit List view (`admin.exams.results.index`) with class/section filters, rank displays, and publication confirmation modals.
- [x] 5.6 Build Marksheet & ID Card Settings view (`admin.exams.settings.marksheet`) for background template and signature uploads.
- [x] 5.7 Build official Mark Sheet / Report Card layout (`admin.exams.report-cards.marksheet`) matching the Joypurhat Government Girls' High School standard (student meta, subject components, highest marks, attendance matrix, extra activities, GPA legend, signatures, and background image overlay).

## 6. Verification & Testing

- [x] 6.1 Write feature tests for grading scale evaluation, `fail_any_fails_all` behavior, and component mark bounds.
- [x] 6.2 Write feature tests for table marks entry batch saving, non-attendance handling (`absent`/`exempt`), and duplicate prevention.
- [x] 6.3 Write feature tests for result calculation, highest mark calculation, rank sorting, publication locking, and post-publish correction audit logging.
- [x] 6.4 Validate report card rendering in both HTML preview and PDF generation using `Spatie\LaravelPdf`.
- [x] 6.5 Execute linting with Laravel Pint and run test suite to verify 100% passing tests.
