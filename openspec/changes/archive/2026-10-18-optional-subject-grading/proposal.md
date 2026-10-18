# Proposal: Optional (4th) Subject Assignment, Fail Isolation, and GPA Point Distribution

## Why
In the Bangladesh National Curriculum & Secondary Education Board (NCTB / SSC / HSC / School) grading system, students have compulsory subjects and an optional subject (commonly referred to as the 4th Subject or ৪র্থ বিষয়, e.g., Higher Mathematics, Agriculture, Biology, Computer Science).

Currently in EduCore SIS:
1. `GradingService` and `ExamResultService` treat all subjects as compulsory. When `fail_any_fails_all = true`, failing in an optional subject causes the student to fail the entire examination (`GPA: 0.00, Grade: F, Status: failed`), which contradicts institutional standards.
2. GPA is calculated as a naive simple average (`totalPoints / applicableCount`). In standard GPA 5.0 grading, optional subjects contribute bonus grade points above 2.00 (`max(0, GP - 2.00)`), the divisor is strictly the count of compulsory subjects, and the final GPA is capped at 5.00.
3. Schools need the flexibility to assign optional subjects at the individual student level (e.g. in Class 9-10 where students select different electives like Higher Math vs. Agriculture) or assign a single optional subject to an entire class cohort in batch.
4. Mark sheets currently show all subjects identically without highlighting which subject is the student's optional (4th) subject.

## What Changes
1. **Optional Subject Assignment Mechanism**:
   - Provide the ability to assign a 4th/optional subject to individual students (stored on student enrollment or a dedicated student optional subject mapping).
   - Provide a batch tool to assign a single 4th subject across an entire class or section cohort in one click.
   - Filter available optional subjects from the assigned subjects for that class where `type = 'optional'` or from the class curriculum.

2. **Grading & Result Calculation Updates**:
   - **Fail Isolation**: If a student fails (`marks < pass_marks` or `Grade: F`) in their designated optional subject, that subject shows `F` and GP `0.00` on their report card, but it does **NOT** increment `failed_count` for overall exam failure. The student's overall exam status remains `passed` if all compulsory subjects are passed.
   - **Bonus Point Distribution (Rule of 2.00)**:
     - For the optional subject, grade point above 2.00 is calculated: $\text{Bonus GP} = \max(0, \text{Optional GP} - 2.00)$.
     - $\text{Total GP} = \sum(\text{Compulsory Subject GPs}) + \text{Bonus GP}$.
     - $\text{Final GPA} = \min(5.00, \text{round}(\text{Total GP} / \text{Compulsory Subjects Count}, 2))$.
     - If the student scores $A+$ in compulsory subjects and passes or fails the optional subject, GPA cannot be reduced below compulsory achievements. Extra points allow students with some $A$ grades in compulsory subjects to achieve overall $A+$ (GPA 5.00).

3. **Marksheet & Report Card Presentation**:
   - In the student mark sheet subject table, append a clean `(Optional)` indicator directly next to the subject name for the student's 4th subject.
   - Retain existing summary layouts without introducing unnecessary summary boxes.

## Capabilities

### New Capabilities
- `optional-subject-assignment`: Manage student 4th/optional subject allocations with support for individual student selection and cohort-wide bulk assignment for a class/section.

### Modified Capabilities
- `grading-and-results`: Modify result calculation and grading logic to exclude optional subjects from `fail_any_fails_all` propagation and implement the National GPA 5.0 optional point distribution ($\max(0, GP - 2.00)$ bonus with compulsory divisor and 5.00 cap).
- `report-card-generation`: Render the `(Optional)` designation tag alongside the 4th subject in the marksheet subject breakdown table.

## Impact
- **Database Schema**: Add `optional_subject_id` to `student_enrollments` (or pivot/table) with indexing, allowing student-level and class-level assignment.
- **Backend Services**:
  - `App\Services\GradingService`: Update `calculateOverallResult()` to accept compulsory and optional evaluations distinctly, apply the 2.00 threshold bonus point rule, and ignore optional subject fails in `fail_any_fails_all`.
  - `App\Services\ExamResultService`: Retrieve the student's designated optional subject during result calculation and tag evaluations accordingly.
- **Controllers & UI**:
  - Add optional subject selection in Student Admission / Edit enrollment forms.
  - Add a bulk assignment interface in Academic Structure / Student Management to assign a 4th subject to an entire class or section.
  - Update `ReportCardController` and `resources/views/admin/exams/report-cards/partials/card.blade.php` to display `(Optional)` next to the subject name.
- **Tests**: Add automated tests covering fail isolation, bonus point calculation, A+ achievement with optional points, and batch assignment.
