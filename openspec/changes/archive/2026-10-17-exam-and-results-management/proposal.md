# Proposal

## Why

Educational institutions require a robust, flexible, and tamper-resistant examination and evaluation platform. Schools currently manage academic sessions, classes, subjects, and student enrollments, but lack an integrated workflow to schedule examinations, configure dynamic subject assessment components (such as Written, MCQ, Practical, and Continuous Assessment), record student marks efficiently, calculate authoritative results and class ranks, enforce publication controls, and generate print-ready institutional mark sheets/report cards.

Without this capability, institutions must rely on error-prone offline spreadsheets, leading to calculation discrepancies, duplicated data entry, lack of auditability for post-publication grade corrections, and generic, unbranded report cards. Introducing this system unifies exam management with tenant isolation, strict role permissions, and a high-speed marks entry workflow.

## What Changes

1. **Exam Sessions & Configuration**:
   - Create and schedule exam sessions (e.g., Half-Yearly, Final Examination, Class Tests) tied to an academic year.
   - Configure exam subjects per class with custom full marks, pass marks, and dynamic assessment components (e.g. Mathematics with Written: 70, MCQ: 30; Physics with Written: 50, MCQ: 25, Practical: 25; Physical Education with CA: 50).
   - Support paper combination brackets (e.g. Bengali 1st + 2nd Paper, English 1st + 2nd Paper) where applicable.

2. **Configurable Grading Scales**:
   - Support multiple grading systems per school (e.g. National GPA 5.0 scale, Letter Grade A-F scale).
   - Define custom grade tiers: percentage ranges, grade letters, grade points (GPA), pass/fail thresholds, and qualitative remarks.
   - Configurable grading rules, such as `fail_any_fails_all` (if a student fails any mandatory subject, overall GPA drops to 0.00 / Grade 'F').

3. **High-Efficiency Marks Entry Matrix**:
   - Provide a table-based, keyboard-friendly data entry screen (`Exam + Class + Section + Subject`) allowing teachers to enter marks rapidly using `Tab`/`Enter` without individual page loads.
   - Dynamic component input columns generated based on the subject's configured components.
   - Distinguish and handle statuses distinctly: `present`, `absent`, `exempt`, and `incomplete`.
   - Client-side and server-side validation against component maximum marks with real-time percentage and grade preview.
   - Duplicate prevention via unique database constraints.

4. **Result Calculation & Publication Workflow**:
   - Automated batch result calculation: computes total marks obtained, subject-level pass/fail, class/section highest marks, overall percentage, GPA, letter grade, and merit ranks (`rank_in_class`, `rank_in_section`).
   - Publication state machine: `draft` -> `calculated` -> `approved` -> `published`.
   - Published result protection: marks entry is locked once published. Post-publication modifications require an authorized correction workflow that records previous marks, new marks, reason, and editor to an audit log.

5. **Print-Ready Institutional Mark Sheet & Report Card**:
   - Report card layout matching the institutional standard:
     - Header with school logo, school title, exam title, and passport student photo.
     - 3-column student metadata panel (Name, Father, Mother, Student ID, DOB, Shift, Board, Class, Group, Section, Roll No, GPA, Grade).
     - Subject-wise grade & mark sheet table showing Code, Subject, dynamic components (Written, MCQ, CA, Practical), Combined Totals, Highest Marks in Exam, Grade Point, and Letter Grade.
     - Bottom analytical panels: Attendance summary (month-by-month present vs. working days), Extra activities/co-curricular evaluation, and GPA grading legend scale.
     - Triple signature verification blocks: Guardian, Class Teacher, and Head Master / Principal.
     - Integration with Marksheet & ID Card Settings allowing background template uploads or border/watermark rendering.
     - PDF download via `Spatie\LaravelPdf` and clean browser print preview.

## Capabilities

### New Capabilities
- `exam-management`: Exam sessions, exam types, academic year scheduling, dynamic subject component configuration (Written, MCQ, Practical, CA per subject), maximum and pass mark setup, publishing workflow, and post-publish correction locking.
- `grading-and-results`: Configurable grading scales and tiers (GPA, letter grades, percentage thresholds, fail-any-fails-all flag), keyboard-friendly batch marks entry matrix, result calculations (totals, highest marks, GPA, percentage, ranks), publication controls, and correction audit logging.
- `report-card-generation`: Official institutional mark sheet and report card generation matching the user's specific mark sheet format (subject-wise components, combined papers, highest marks, attendance summary, extra activities, GPA grading legend, signature blocks, and background image integration from Marksheet & ID Card Settings).

### Modified Capabilities
*(None. Existing capabilities remain unaffected, building on top of the established academic foundation and multi-tenant isolation.)*

## Impact

- **Database**: New tables for `grading_scales`, `grade_tiers`, `exams`, `exam_subjects`, `exam_subject_components`, `exam_marks`, `exam_mark_components`, `exam_mark_histories`, `exam_results`, and `marksheet_settings`.
- **Authorization**: New permissions registered in `RolePermissionSeeder`: `exams.view`, `exams.manage`, `exams.grades.manage`, `exams.marks.entry`, `exams.results.calculate`, `exams.results.publish`, `exams.marks.correct`, and `exams.reports.generate`.
- **UI & Navigation**: New "Exams & Results" dropdown group in the admin sidebar navigation (`layouts/admin-partial/sidebar.blade.php`), with responsive active-state handling.
- **Dependencies**: Leverages existing `Spatie\LaravelPdf` package and Tailwind CSS v4 styling.
