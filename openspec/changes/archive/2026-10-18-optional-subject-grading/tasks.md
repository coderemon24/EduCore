# Tasks

## 1. Database Migrations & Eloquent Models

- [x] 1.1 Create migration to add nullable `optional_subject_id` foreign key referencing `subjects(id)` with index to `student_enrollments`.
- [x] 1.2 Update `StudentEnrollment` model with `optional_subject_id` fillable attribute, casts, and `optionalSubject(): BelongsTo` relationship.
- [x] 1.3 Run migration and verify schema integrity.

## 2. Business Logic & Grading Services

- [x] 2.1 Update `GradingService::calculateOverallResult()` to distinguish between compulsory and optional subject evaluations:
  - Isolate optional subject failures so they do not trigger `fail_any_fails_all` overall exam failure.
  - Apply the National GPA 5.0 bonus point rule ($\max(0, \text{Optional GP} - 2.00)$).
  - Compute GPA dividing by the count of applicable compulsory subjects only, capping at 5.00.
- [x] 2.2 Update `ExamResultService::calculateExamResults()` to detect each student's designated optional subject from their enrollment (or class-level default), tagging each evaluation with `is_optional`.
- [x] 2.3 Verify rank ordering and highest mark metrics remain correct when optional subjects are present.

## 3. Administrative UI & Batch Assignment

- [x] 3.1 Update Student Admission form (`resources/views/admin/students/create.blade.php`) and edit form (`resources/views/admin/students/edit.blade.php`) to include the "Optional / 4th Subject" select dropdown populated based on the selected class.
- [x] 3.2 Update `StudentAdmissionService` and `StudentController` to persist `optional_subject_id` upon student creation and update.
- [x] 3.3 Add batch optional subject assignment route, controller action, and UI modal allowing administrators to assign a single 4th subject across a whole class/section cohort.

## 4. Marksheet & PDF Export Integration

- [x] 4.1 Update `ReportCardController::buildMarksheetData()` to resolve and pass `optionalSubjectId` for the student.
- [x] 4.2 Update `resources/views/admin/exams/report-cards/partials/card.blade.php` to render `(Optional)` directly next to the subject name in the SUBJECT column without adding extra summary boxes.
- [x] 4.3 Verify print preview and Spatie LaravelPdf download render the `(Optional)` tag cleanly in A4 layout.

## 5. Verification & Automated Testing

- [x] 5.1 Add PHPUnit tests in `tests/Feature/ExamGradingAndResultsTest.php` covering:
  - Student passing compulsory subjects but failing optional subject maintains `passed` overall status and accurate GPA.
  - Student scoring in optional subject receives $\max(0, GP - 2.00)$ bonus points boosting overall GPA to 5.00 (A+).
  - Student with C or D grade in optional subject receives zero bonus points without negative deduction.
- [x] 5.2 Add PHPUnit tests for individual and batch optional subject assignment on student enrollments.
- [x] 5.3 Run full test suite and `vendor/bin/pint` to ensure code formatting and quality standards.
