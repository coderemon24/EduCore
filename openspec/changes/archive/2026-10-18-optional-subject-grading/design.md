# Design: Optional (4th) Subject Assignment, Fail Isolation, and GPA Point Distribution

## Context
See [proposal.md](proposal.md) for background and motivation.

Currently, `StudentEnrollment` holds class, section, group, and roll number for a session, but lacks a foreign key for the student's designated optional subject. In `GradingService::calculateOverallResult()`, subject evaluations are processed blindly: all subjects count equally, any failure triggers `failed` status when `fail_any_fails_all = true`, and total points are divided by all subjects count.

In `ReportCardController::buildMarksheetData()` and `resources/views/admin/exams/report-cards/partials/card.blade.php`, subjects are listed from `ExamSubject` without distinguishing compulsory from optional subjects.

## Goals / Non-Goals

**Goals:**
- Add `optional_subject_id` nullable foreign key to `student_enrollments` referencing `subjects(id)` with index.
- Allow optional subject assignment on student creation/edit, and provide a batch assignment action for whole classes/sections.
- In `GradingService::calculateOverallResult()`, differentiate compulsory and optional subjects:
  - If a student fails an optional subject, it does **not** contribute to the `failedCount` that triggers `fail_any_fails_all`.
  - Calculate bonus points: $\text{Bonus} = \max(0, \text{Optional GP} - 2.00)$.
  - Divide $(\sum \text{Compulsory GP} + \text{Bonus})$ by the count of applicable compulsory subjects.
  - Cap final GPA at 5.00.
- In `card.blade.php` (and PDF export), render `(Optional)` next to the 4th subject name.

**Non-Goals:**
- Adding extra summary boxes or analytical cards to the marksheet (user explicitly requested no extra summary box).
- Supporting multiple simultaneous 4th subjects for a single student (standard curriculum allows exactly one 4th/optional subject per student).

## Decisions

### 1. Storage of Optional Subject on `student_enrollments`
- **Choice**: Add `optional_subject_id` to the `student_enrollments` table.
- **Rationale**: An optional subject is specific to an academic year, class, and curriculum level (e.g., General Math is compulsory in Class 9, but Higher Math is optional). Storing it on `student_enrollments` ensures historical integrity across academic years and student promotions.
- **Fallback**: If `enrollment->optional_subject_id` is null, the system can fallback to checking if the class has a single assigned subject in `class_subject_assignments` with `type = 'optional'`.

### 2. Result Calculation Logic in `GradingService` & `ExamResultService`
- **Choice**:
  Each subject evaluation in `$subjectEvaluations` will carry an `is_optional: bool` flag.
  In `GradingService::calculateOverallResult`:
  ```php
  $compulsoryPoints = 0.00;
  $compulsoryCount = 0;
  $failedCompulsoryCount = 0;
  $optionalBonus = 0.00;
  $failedOptionalCount = 0;

  foreach ($subjectEvaluations as $eval) {
      if (!empty($eval['is_exempt'])) continue;

      if (!empty($eval['is_optional'])) {
          if (empty($eval['is_passed'])) {
              $failedOptionalCount++;
          }
          $point = (float) ($eval['point'] ?? 0.00);
          $optionalBonus += max(0.00, $point - 2.00);
      } else {
          $compulsoryCount++;
          $compulsoryPoints += (float) ($eval['point'] ?? 0.00);
          if (empty($eval['is_passed'])) {
              $failedCompulsoryCount++;
          }
      }
  }

  // fail_any_fails_all evaluates ONLY compulsory failures
  if ($failAnyFailsAll && $failedCompulsoryCount > 0) {
      return [
          'gpa' => 0.00,
          'grade' => $failingTier ? $failingTier->grade : 'F',
          'result_status' => 'failed',
          'failed_count' => $failedCompulsoryCount + $failedOptionalCount,
      ];
  }

  $divisor = max(1, $compulsoryCount);
  $gpa = min(5.00, round(($compulsoryPoints + $optionalBonus) / $divisor, 2));
  ```
- **Alternatives Considered**: Modifying `ExamSubject` to add `is_optional`. Rejected because in Classes 9-10, Higher Math may be optional for Student A, but Agriculture is optional for Student B within the exact same exam cohort. Therefore, optional status is student-enrollment-specific.

### 3. Class-Wide Batch Assignment UI and Action
- **Choice**:
  Add an endpoint and modal in Academic Structure / Assign Subjects or Student Directory (`POST /admin/students/batch-assign-optional-subject`).
  Inputs: `academic_year_id`, `academic_class_id`, `section_id` (optional), `optional_subject_id`, `overwrite_existing` (boolean).
- **Rationale**: Gives administrators the speed to assign an entire class or section cohort with one click while preserving the ability for students to customize their individual choice.

### 4. Marksheet Template Rendering
- **Choice**:
  In `ReportCardController::buildMarksheetData()`, identify `optionalSubjectId = $enrollment?->optional_subject_id`.
  Pass `optionalSubjectId` to the view.
  In `card.blade.php`:
  ```blade
  <td class="subject-name-td">
      {{ strtoupper($subj->subject?->name ?: 'Subject') }}
      @if($subj->subject_id === $optionalSubjectId)
          <span style="font-size: 8.5px; font-weight: normal; color: #64748b; font-style: italic;">(Optional)</span>
      @endif
  </td>
  ```
- **Rationale**: Minimal, precise, and matches standard mark sheets without adding clutter or extra summary boxes.

## Risks / Trade-offs

- **[Risk] Existing Calculated Results**: Exams that have already been calculated will need recalculation to update GPA and pass/fail status for students with optional subjects.
  $\rightarrow$ **Mitigation**: Results are recalculable via the existing `calculateExamResults()` action in `ExamResultController`.
- **[Risk] Cohort without individual selection**: If a school hasn't selected optional subjects per student, all subjects default to compulsory, which preserves existing behavior without breaking backwards compatibility.
