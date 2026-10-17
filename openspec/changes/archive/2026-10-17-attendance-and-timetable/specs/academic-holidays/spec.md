# Spec Delta: academic-holidays

## Purpose

The academic-holidays capability maintains official institutional holidays, seasonal breaks, and non-working days for an academic year, integrating with attendance workflows to prevent invalid roll-calls.

## ADDED Requirements

### Requirement: Academic Holiday Management
The system MUST provide CRUD capabilities for defining holidays, non-working days, and seasonal breaks tied to a specific academic year and school.

#### Scenario: Creating an institutional holiday
- **WHEN** an administrator creates a holiday with title, date range, type, and description
- **THEN** the system MUST store the holiday scoped to the active school tenant and academic year.

### Requirement: Holiday Verification in Attendance
The system MUST notify or prevent roll-call when an attendance date coincides with a designated holiday.

#### Scenario: Attendance on holiday date
- **WHEN** a user initiates attendance for a date configured as a school holiday
- **THEN** the system MUST display a clear holiday advisory banner informing the user of the scheduled holiday.
