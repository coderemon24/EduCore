# Spec Delta: staff-attendance

## Purpose

The staff-attendance capability provides faculty and school personnel daily presence tracking, check-in/out timestamps, leave tracking, and tenant-scoped attendance ledgers.

## ADDED Requirements

### Requirement: Daily Staff Attendance Ledger
The system MUST provide a daily staff attendance interface allowing administrators to view and record attendance for faculty and non-teaching personnel.

#### Scenario: Marking staff attendance
- **WHEN** an administrator views the staff attendance roster for a specific date
- **THEN** the system MUST display all active staff members with options to set status (`present`, `absent`, `late`, `half_day`, `on_leave`) and optional check-in/check-out timestamps.

### Requirement: Staff Attendance Uniqueness and School Isolation
The system MUST enforce strict tenant isolation and guarantee that no staff member has duplicate attendance entries for the same date.

#### Scenario: Preventing duplicate daily staff attendance
- **WHEN** an attendance entry for a staff member on date D already exists
- **THEN** the system MUST update the existing row upon resubmission rather than creating duplicate records.
