# Spec Delta: student-attendance

## Purpose

The student-attendance capability enables daily student roll-call marking, attendance status tracking, bulk transactional updates, enrollment validation, and sensitive correction audit logging within institutional tenants.

## ADDED Requirements

### Requirement: Daily Student Roll-Call Interface
The system MUST provide an administrative interface allowing teachers and administrators to load a section's student roster for a selected date and record attendance in bulk.

#### Scenario: Loading student roster for roll-call
- **WHEN** an authenticated user selects an academic class, section, and date
- **THEN** the system MUST display all students currently active and enrolled in that section with their roll number, name, admission number, and existing attendance status (or default empty/present).

#### Scenario: Bulk submission of section attendance
- **WHEN** an authenticated user marks statuses and submits the attendance sheet
- **THEN** the system MUST persist all student attendance records within a single database transaction, ensuring no partial or corrupted updates.

### Requirement: Controlled Attendance Status Values and Uniqueness
The system MUST enforce controlled status values (`present`, `absent`, `late`, `half_day`, `excused`) and strictly prevent duplicate attendance entries for the same student on the same date.

#### Scenario: Preventing duplicate attendance records
- **WHEN** an attendance record already exists for student S on date D and a new record is saved
- **THEN** the system MUST update the existing record rather than inserting a duplicate row, honoring the unique tenant-student-date constraint.

#### Scenario: Status validation
- **WHEN** an invalid status string is submitted
- **THEN** the system MUST reject the request with HTTP 422 validation error.

### Requirement: Cross-Tenant and Enrollment Integrity Validation
The system MUST validate that every student marked in an attendance submission belongs to the active school tenant and has an active enrollment in the target academic year, class, and section.

#### Scenario: Marking attendance for non-enrolled student
- **WHEN** a submission contains a student ID that is not actively enrolled in the specified section and academic year
- **THEN** the system MUST reject the record and abort the transaction.

### Requirement: Attendance Correction Audit Trail
The system MUST maintain an immutable audit trail when an existing attendance record's status is modified.

#### Scenario: Auditing status correction
- **WHEN** a user modifies an existing attendance record from `absent` to `excused` or `present`
- **THEN** the system MUST log the previous status, new status, modifier user ID, IP address, and optional reason to `attendance_audit_logs`.

### Requirement: Monthly Attendance Register and Reporting
The system MUST provide a monthly attendance register showing days 1 through 31 for each enrolled student, summary percentage rates, and class-level analytics.

#### Scenario: Generating monthly register
- **WHEN** a user selects a class, section, month, and year
- **THEN** the system MUST compute total present days, absent days, late marks, excused absences, and overall attendance percentage for each student using an indexed aggregate query.
