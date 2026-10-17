# Spec Delta: grading-and-results

## Purpose
Enables schools to establish flexible grading scales, record student marks via a high-speed matrix interface, evaluate dynamic component thresholds, compute consolidated student results and ranks, enforce publication locks, and audit post-publication mark corrections.

## ADDED Requirements

### Requirement: Multi-Tenant Configurable Grading Scales
The system MUST allow institutions to define custom grading scales with tiered grade points, letter grades, percentage intervals, and configurable failure propagation rules (`fail_any_fails_all`).

#### Scenario: Creating a GPA 5.0 scale with grade tiers
- **WHEN** an administrator creates a grading scale with `name = "National GPA 5.0"` and tiers: 80-100% (A+, 5.00), 70-79% (A, 4.00), 60-69% (A-, 3.50), 50-59% (B, 3.00), 40-49% (C, 2.00), 33-39% (D, 1.00), Below 33% (F, 0.00)
- **THEN** the system persists the scale and tiers under the school tenant for assignment to academic exams.

#### Scenario: Evaluating `fail_any_fails_all` rule
- **WHEN** an exam's grading scale has `fail_any_fails_all = true` and a student fails one mandatory subject
- **THEN** the student's overall exam result is marked as `failed`, with overall GPA set to `0.00` and overall Grade `F`, regardless of high scores in other subjects.

#### Scenario: Evaluating without `fail_any_fails_all`
- **WHEN** an exam's grading scale has `fail_any_fails_all = false` and a student fails a subject
- **THEN** the overall GPA is computed as the average of all subject grade points, and the failed subject count is highlighted.

---

### Requirement: High-Speed Table Marks Entry
The system MUST provide a high-efficiency table-based marks entry screen allowing authorized teachers or administrators (`exams.marks.entry`) to record marks for an entire class section in a single grid without individual student page reloads.

#### Scenario: Keyboard-driven batch marks entry
- **WHEN** a teacher selects an Exam, Class, Section, and Subject, and enters component scores using keyboard navigation (`Tab` or `Enter`)
- **THEN** the system performs real-time client-side validation against component maximum marks, updates total marks and grade previews dynamically, and saves the batch atomically on submission.

#### Scenario: Distinct handling of non-attendance statuses
- **WHEN** a student is flagged as `absent`, `exempt`, or `incomplete`
- **THEN** the system disables mark inputs for that student, records `marks_obtained = null`, treats `absent` as 0 marks / failed, and omits `exempt` subjects from GPA calculation denominators.

#### Scenario: Enforcing component and total maximum marks
- **WHEN** a user enters a mark exceeding the configured component ceiling (e.g., 55 entered for a Written component capped at 50)
- **THEN** the system displays an immediate inline validation error and rejects the server-side save.

#### Scenario: Preventing duplicate mark records
- **WHEN** multiple concurrent submissions or rapid re-submissions occur for the same student, exam, and subject
- **THEN** the database unique constraint `[exam_subject_id, student_id]` prevents duplicate row creation and safely updates existing records.

---

### Requirement: Result Calculation, Class Ranking, and Publication Lock
The system MUST compute aggregated marks, calculate subject-wise highest scores across the exam cohort, assign class and section ranks, enforce publication locks, and maintain an audit log for corrections.

#### Scenario: Batch result calculation
- **WHEN** an authorized officer (`exams.results.calculate`) triggers result calculation for an exam
- **THEN** the system calculates total marks obtained, overall percentage, GPA, grade, highest mark obtained per subject across the exam cohort, and assigns `rank_in_class` and `rank_in_section` sorted by `result_status = 'passed' DESC`, `gpa DESC`, `total_marks_obtained DESC`.

#### Scenario: Result publication lock
- **WHEN** an authorized administrator (`exams.results.publish`) publishes the results of an exam
- **THEN** the exam status updates to `published`, and regular marks entry screens are locked against edits.

#### Scenario: Authorized post-publication mark correction with audit trail
- **WHEN** an administrator with `exams.marks.correct` modifies a mark on a published exam
- **THEN** the system requires a mandatory correction reason, updates the mark, logs the change to `exam_mark_histories` (capturing previous mark, new mark, editor ID, timestamp, and reason), and automatically re-evaluates the student's result and rankings.
