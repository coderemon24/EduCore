# Design: Exam, Grading & Results Architecture

## Context

The application runs on Laravel 12 with PHP 8.2+ and Tailwind CSS v4. The existing schema provides core academic models: `School`, `AcademicYear`, `AcademicClass`, `Section`, `Subject`, `ClassSubjectAssignment`, and `StudentEnrollment` with multi-tenant scoping via `BelongsToSchool`. PDF generation is standardized using `Spatie\LaravelPdf` (DOMPDF driver) for invoices and receipts.

This design introduces a complete examination, dynamic component grading, result tabulation, and institutional mark sheet generation system without duplicating existing student or curriculum structures.

## Goals / Non-Goals

**Goals:**
- Provide dynamic, subject-wise assessment components (`ExamSubjectComponent` - e.g. Written, MCQ, Practical, CA) configured per subject and class.
- Support configurable grading scales and tiers with customizable failure propagation (`fail_any_fails_all`).
- Deliver a fast, keyboard-friendly (`Tab`/`Enter`) table marks entry interface with live validation and auto-calculated grade previews.
- Maintain a tamper-evident audit history (`ExamMarkHistory`) for any post-publication mark revisions.
- Calculate comprehensive student results with subject-level pass/fail, class highest marks, GPA, letter grades, and merit ranks.
- Generate pixel-accurate, print-ready student mark sheets matching the Joypurhat Government Girls' High School layout, supporting uploaded custom background templates from Marksheet Settings.
- Guarantee tenant isolation and strict role-based permission gates.

**Non-Goals:**
- Online student exam taking or real-time computerized proctoring.
- Automated SMS/Push notifications to parents on result release (handled by separate communication modules).

## Decisions

### 1. Database Schema & Dynamic Component Model

```
+------------------+         +--------------------------+
|   exam_subjects  | 1 --- * |  exam_subject_components |
+------------------+         +--------------------------+
| id               |         | id                       |
| exam_id          |         | exam_subject_id          |
| academic_class_id|         | name (Written, MCQ, etc) |
| subject_id       |         | max_marks (e.g. 50.00)   |
| full_marks       |         | pass_marks (e.g. 17.00)  |
| pass_marks       |         | order_index              |
+------------------+         +--------------------------+
        |                                 |
        | 1                               | 1
        |                                 |
        v *                               v *
+------------------+         +--------------------------+
|    exam_marks    | 1 --- * |   exam_mark_components   |
+------------------+         +--------------------------+
| id               |         | id                       |
| exam_subject_id  |         | exam_mark_id             |
| student_id       |         | exam_subject_comp_id     |
| marks_obtained   |         | marks_obtained           |
| attendance_status|         +--------------------------+
+------------------+
```

*Rationale*: Having `exam_subject_components` and `exam_mark_components` allows schools to configure arbitrary evaluation formats per subject (e.g., Mathematics having 70 Written + 30 MCQ, Physics having 50 Written + 25 MCQ + 25 Practical, and Physical Education having 50 CA) while preserving strict foreign key constraints and mathematical validation.

### 2. Configurable Grading Scale & Failure Propagation

- `grading_scales`: `id`, `school_id`, `name`, `fail_any_fails_all` (boolean), `is_default`.
- `grade_tiers`: `id`, `grading_scale_id`, `grade`, `point`, `min_percentage`, `max_percentage`, `is_pass`, `remarks`.

*Rationale*: Different boards or schools treat failing a single subject differently. When `fail_any_fails_all = true`, failing any mandatory subject overrides the student's result to `result_status = 'failed'`, `gpa = 0.00`, and `grade = 'F'`, while still preserving their subject-wise marks.

### 3. Keyboard-Optimized Table Marks Entry Grid

- Built with high-density tabular HTML, generating dynamic input columns for each component of the selected subject.
- Client-side vanilla JS provides instant arrow/Tab navigation between inputs, live percentage computation, component max mark boundary enforcement, and `Absent`/`Exempt` toggle shortcuts.
- Form submissions are processed inside database transactions (`DB::transaction`) to ensure atomicity.

### 4. Authoritative Result Tabulation & Ranking Engine (`ExamResultService`)

- Aggregates all subject marks for each student in an exam cohort.
- Queries and caches the **Highest Mark in Exam** for each subject across the cohort for mark sheet display.
- Calculates `rank_in_class` and `rank_in_section` using deterministic sorting:
  1. `result_status = 'passed'` first
  2. `gpa DESC`
  3. `total_marks_obtained DESC`
  4. `roll_number ASC`

### 5. Mark Sheet & ID Card Settings with Background Template Support

- New `marksheet_settings` table (or system setting key): stores default background image upload (`public/uploads/marksheet_templates/`), school crest/logo, principal signature image, and layout options (e.g., toggle attendance matrix, toggle extra activities panel).
- Report Card Blade Template (`resources/views/admin/exams/report-cards/marksheet.blade.php`):
  - Strict CSS `@page { size: A4 portrait; margin: 8mm; }` with print-optimized styles.
  - If a background image is present, the page container sets `background-image: url(...)` with high-resolution scaling; text, tables, and borders are layered transparently on top.
  - If no background is uploaded, a classic double institutional border with crest headers and signature lines renders natively.
- PDF generation uses `Pdf::view(...)` with DOMPDF driver, matching existing invoice/receipt pipelines.

### 6. Role Permissions & Publication Guard

- Registered permissions:
  - `exams.view`
  - `exams.manage`
  - `exams.grades.manage`
  - `exams.marks.entry`
  - `exams.results.calculate`
  - `exams.results.publish`
  - `exams.marks.correct`
  - `exams.reports.generate`
- Middleware and policy checks prevent mark edits when `exam.status == 'published'`. If an authorized user with `exams.marks.correct` submits a modification, it writes to `exam_mark_histories` with `old_marks`, `new_marks`, and `reason`, and triggers automatic result recalculation for that student.

## Risks / Trade-offs

- **[Risk] High-volume bulk marks entry timeout**: A class of 80 students across 10 subjects has 800+ records.
  - *Mitigation*: The marks entry screen processes one `Class + Section + Subject` at a time (e.g. 30-50 student rows per submit), keeping payload size under 50KB and execution time under 100ms.
- **[Risk] Float rounding discrepancies in GPA and percentages**: Cumulative decimal precision issues.
  - *Mitigation*: Store all marks, percentages, and grade points as `DECIMAL(5, 2)` or `DECIMAL(4, 2)` in MySQL and use `bcadd`/`round(..., 2)` in PHP calculations.
- **[Risk] Slow PDF rendering for entire class batches**:
  - *Mitigation*: Use lightweight native HTML/CSS in Blade without heavy JavaScript dependencies so DOMPDF renders pages rapidly. For large cohorts (100+ students), stream individual section PDFs or chunk generation.
