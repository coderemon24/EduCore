# teacher-assignments Specification

## Purpose
Provides academic workload assignment capabilities linking teaching faculty to classrooms as Class Teachers and linking subject instructors to the curriculum syllabus matrix within each institutional tenant.

## Requirements

### Requirement: Database Schema Foreign Keys for Teacher Assignments
The application MUST introduce foreign key columns referencing `staff.id` on academic structures to record class teachers and subject instructors.

#### Scenario: Schema modification on sections and subject assignments
- **WHEN** migrations are executed
- **THEN** table `sections` adds a nullable `class_teacher_id` referencing `staff.id` on delete set null
- **AND** table `class_subject_assignments` adds a nullable `teacher_id` referencing `staff.id` on delete set null
- **AND** indexes are created for `['school_id', 'class_teacher_id']` and `['school_id', 'teacher_id']`.

---

### Requirement: Cross-Tenant Validation on Teacher Assignments
The application MUST strictly verify that assigned teachers belong to the active tenant school when establishing class teacher or subject teacher associations.

#### Scenario: Rejection of cross-tenant teacher assignment
- **WHEN** an admin assigns a teacher from School A to a section in School B
- **THEN** validation fails with HTTP 422 stating the selected teacher is invalid for this institution.

#### Scenario: Assigning a valid school teacher to a section
- **WHEN** an admin selects an active teaching staff member for `class_teacher_id` in `PUT /admin/academic/sections/{section}`
- **THEN** the assignment updates successfully and the section roster reflects the assigned class teacher.

---

### Requirement: Teacher Workload and Academic Assignment Visibility
The application MUST display all active class and subject assignments on the teacher's profile view (`GET /admin/teachers/{staff}` or `GET /admin/staff/{staff}`).

#### Scenario: Viewing teacher assignments on profile page
- **WHEN** an authorized user views a teacher's profile
- **THEN** an "Academic Assignments" card displays:
  - Sections where this teacher is appointed as Class Teacher
  - Classes and subjects where this teacher is assigned as Subject Instructor
  - Total teaching load count summary.
