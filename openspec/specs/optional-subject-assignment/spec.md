# Optional Subject Assignment Specification

## Purpose
Enables schools to allocate 4th / optional subjects to students, supporting both individual student-level selection during admission/enrollment and whole-class batch assignments.

## Requirements

### Requirement: Individual Student Optional Subject Assignment
The system MUST allow administrative users to select and store a designated 4th/optional subject for an individual student within their current academic enrollment.

#### Scenario: Selecting an optional subject during student admission or profile edit
- **WHEN** an administrator creates or edits a student's enrollment record for a class
- **THEN** the form displays an "Optional / 4th Subject" dropdown populated with subjects assigned to that class where `type = 'optional'` or elective subjects, and persists the choice to the student's enrollment.

#### Scenario: Unassigning or changing an optional subject
- **WHEN** an administrator changes or clears the student's designated optional subject
- **THEN** the system updates `optional_subject_id` on the enrollment record and ensures subsequent exam result calculations reflect the updated assignment.

### Requirement: Batch Class-Wide Optional Subject Assignment
The system MUST allow administrative users to assign a single 4th/optional subject to all enrolled students of a selected class or section in a single atomic operation.

#### Scenario: Assigning a default 4th subject to an entire class
- **WHEN** an administrator selects an Academic Year, Class, Section (or "All Sections"), selects an optional subject, and executes "Assign to Class"
- **THEN** the system updates the `optional_subject_id` for all active student enrollments in that cohort within a database transaction.

#### Scenario: Preserving individual custom assignments during optional override
- **WHEN** an administrator performs a batch assignment with "Only for students without an optional subject" option checked
- **THEN** the system only updates students whose current `optional_subject_id` is null, leaving custom pre-selected subjects untouched.
