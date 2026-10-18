# Spec Delta: grading-and-results

## MODIFIED Requirements

### Requirement: Multi-Tenant Configurable Grading Scales
The system MUST allow institutions to define custom grading scales with tiered grade points, letter grades, percentage intervals, and configurable failure propagation rules (`fail_any_fails_all`), ensuring failure in optional (4th) subjects does not trigger overall exam failure.

#### Scenario: Evaluating `fail_any_fails_all` with optional subject failure
- **WHEN** an exam's grading scale has `fail_any_fails_all = true`, a student passes all compulsory subjects, but scores below the pass mark (`Grade: F`, GP `0.00`) in their assigned optional subject
- **THEN** the optional subject displays `F` and GP `0.00` individually, but the overall exam result remains `passed` with GPA computed based on the compulsory subjects.

#### Scenario: Evaluating `fail_any_fails_all` with compulsory subject failure
- **WHEN** an exam's grading scale has `fail_any_fails_all = true` and a student fails at least one compulsory subject
- **THEN** the student's overall exam result is marked as `failed`, with overall GPA set to `0.00` and overall Grade `F`, regardless of scores in other subjects.

### Requirement: Result Calculation, Class Ranking, and Publication Lock
The system MUST compute aggregated marks, calculate subject-wise highest scores across the exam cohort, assign class and section ranks, enforce publication locks, and apply the National GPA 5.0 optional subject bonus point distribution.

#### Scenario: Computing overall GPA with optional subject bonus points (Rule of 2.00)
- **WHEN** an exam cohort's results are calculated and a student has an assigned optional subject where grade point exceeds 2.00
- **THEN** the system calculates bonus points as $\max(0, \text{Optional GP} - 2.00)$, adds the bonus points to the sum of compulsory subject grade points, divides by the total number of compulsory subjects only, and caps the resulting GPA at 5.00.

#### Scenario: Optional subject achieving Grade C (2.00) or below
- **WHEN** a student scores Grade C (GP 2.00), Grade D (GP 1.00), or Grade F (GP 0.00) in their optional subject
- **THEN** zero bonus points are added ($\max(0, \text{GP} - 2.00) = 0.00$), no points are deducted, and overall GPA is determined solely by the compulsory subjects divided by compulsory subject count.

#### Scenario: Achieving overall Grade A+ (GPA 5.00) through optional subject bonus
- **WHEN** a student obtains some Grade A scores in compulsory subjects (e.g. compulsory GPA 4.83) and scores Grade A+ (GP 5.00) in the optional subject yielding +3.00 bonus points
- **THEN** the added bonus points raise the total grade point sum such that divided by compulsory subjects it meets or exceeds 5.00, resulting in final GPA 5.00 and overall Grade A+.
