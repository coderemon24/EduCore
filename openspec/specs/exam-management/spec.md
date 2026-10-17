# Exam Management Specification

## Purpose
Enables schools to configure exam sessions, tie them to academic years, assign subjects to classes with custom full and pass marks, configure dynamic assessment components per subject (Written, MCQ, Practical, CA), and manage exam publication lifecycles.

## Requirements

### Requirement: Exam Session Creation and Lifecycle Management
The system MUST allow authorized administrative staff (`exams.manage`) to create, update, and manage exam sessions scoped to the active school tenant and academic year.

#### Scenario: Creating a new exam session
- **WHEN** an administrator provides an exam title (e.g., "Half Yearly Examination 2026"), exam type (`terminal`, `half_yearly`, `final`, `class_test`, `model_test`), start date, end date, and associates an active academic year and grading scale
- **THEN** the system stores the exam record in `draft` status under the active school tenant.

#### Scenario: Preventing overlapping duplicate exam names in the same academic year
- **WHEN** an administrator attempts to create an exam with a title that already exists in the same academic year for that school
- **THEN** the system rejects the submission with a validation error indicating the exam title must be unique within the academic session.

#### Scenario: Advancing exam status
- **WHEN** an administrator transitions an exam through its lifecycle (`draft` -> `scheduled` -> `ongoing` -> `marks_entry` -> `results_calculated` -> `published`)
- **THEN** the system updates the exam status and enforces operational constraints (e.g. disallowing marks entry before `marks_entry` status or after `published` without correction authorization).

### Requirement: Exam Subject Configuration with Dynamic Components
The system MUST allow administrators to assign subjects to classes for an exam and define custom full marks, pass marks, and dynamic assessment components per subject.

#### Scenario: Configuring subject marks with dynamic components
- **WHEN** an administrator assigns a subject (e.g. Physics) to Class 9 for an exam and defines components: Written (Max: 50, Pass: 17), MCQ (Max: 25, Pass: 8), and Practical (Max: 25, Pass: 8)
- **THEN** the system records the `ExamSubject` with `full_marks = 100`, `pass_marks = 33`, and creates child `ExamSubjectComponent` records with their individual max and pass marks.

#### Scenario: Configuring single-component subjects
- **WHEN** an administrator assigns a subject (e.g. Physical Education) that only has Continuous Assessment (CA: 50)
- **THEN** the system creates the subject with `full_marks = 50` and the single dynamic CA component without requiring extraneous theory or practical inputs.

#### Scenario: Component sum validation
- **WHEN** an administrator submits dynamic components whose `max_marks` sum does not match the configured subject `full_marks`
- **THEN** the system rejects the configuration with a validation error requiring the component maximums to equal the overall full marks.

#### Scenario: Preventing duplicate subject assignment in the same exam and class
- **WHEN** an administrator attempts to assign the same subject twice to the same class within one exam
- **THEN** the system blocks the operation via database unique constraint `[exam_id, academic_class_id, subject_id]`.
