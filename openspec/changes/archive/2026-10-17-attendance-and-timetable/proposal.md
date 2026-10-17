# Proposal: Attendance & Timetable Management

## Why

Educational institutions operating on the EduCore SIS require structured, high-speed daily operations for roll-call attendance, schedule planning, and institutional calendar control. 

Currently, schools can manage academic years, classes, sections, subjects, teachers, and enrolled students, but:
1. **Attendance Tracking is Missing**: Administrators and teachers have no mechanism to take student roll-call, verify daily presence/absence, record excuses, or analyze monthly attendance rates.
2. **Staff Attendance is Missing**: There is no facility to track faculty attendance or check-in/out times.
3. **Class Scheduling is Missing**: Timetable scheduling is absent, making it impossible to map class periods to subjects, teachers, and physical classrooms, and creating high risk of double-booking rooms or faculty.
4. **Holidays & Non-Working Days are Unrecorded**: The academic calendar lacks official holiday tracking, making it difficult to prevent attendance entry on non-working days.

Introducing Attendance and Timetable capabilities completes the operational loop between academic structure, faculty assignments, and student records, providing high-speed administrative workflows without UI slop or database performance bottlenecks.

## What Changes

- **Student Attendance Management**:
  - High-speed daily roll-call marking interface filterable by Academic Year, Class, Section, and Date.
  - Controlled attendance statuses (`present`, `absent`, `late`, `half_day`, `excused`).
  - Single-click bulk marking actions ("Mark All Present", "Mark All Absent") with mobile-optimized tap targets.
  - Strict multi-tenant isolation and enrollment validation (ensuring student belongs to the section and active academic year).
  - Prevention of duplicate roll-call entries per student and date via composite unique database constraints.
  - Transactional bulk saving with audit logging of sensitive attendance status corrections (`attendance_audit_logs`).
  - Monthly attendance register matrix (students vs. days 1–31) and analytical summary reports.

- **Staff Attendance Management**:
  - Daily staff roll-call ledger for teaching and non-teaching personnel.
  - Controlled statuses (`present`, `absent`, `late`, `half_day`, `on_leave`) with optional check-in/out timestamps.
  - Unique daily entries per staff member with multi-tenant scoping.

- **Class Timetable & Scheduling**:
  - Weekly schedule matrix (Monday–Sunday) configured by section, subject, teacher, time window, and room.
  - Built-in conflict prevention engine detecting room double-booking, teacher overlaps, and section collisions.
  - Clear, actionable conflict diagnostics during slot creation and modification.
  - Visual weekly timetable matrix for classroom display and personal faculty schedule lookup.

- **Academic Holidays & Calendar**:
  - Institutional calendar managing public holidays, breaks, and non-working days.
  - Integration with attendance workflows to flag or prevent roll-call on designated holidays.

- **Navigation & UI Architecture**:
  - Expand the static Attendance menu item in the administrative sidebar into an active accordion dropdown.
  - Add Timetable & Schedule navigation items under Academic & Operations.
  - Adhere to the strict anti-slop design system (high information density, crisp 1px borders, zero arbitrary blur/glow).

## Capabilities

### New Capabilities
- `student-attendance`: Daily student roll-call capture, bulk submission, status controls, enrollment validation, and correction audit logging.
- `staff-attendance`: Daily faculty and administrative staff presence tracking, check-in/out logging, and status ledger.
- `class-timetable`: Weekly class and period scheduling, teacher-subject-room mapping, and conflict prevention engine.
- `academic-holidays`: School non-working day and holiday scheduling with calendar integration.

### Modified Capabilities
- `academic-structure`: Extend navigation and academic associations to connect sections with timetable slots and academic calendar constraints.

## Impact

- **Database**:
  - New tables: `student_attendances`, `staff_attendances`, `attendance_audit_logs`, `timetable_slots`, `academic_holidays`.
  - Composite indexes ensuring fast lookup and duplicate prevention across multi-tenant schools.
- **Backend & Models**:
  - New Eloquent models: `StudentAttendance`, `StaffAttendance`, `AttendanceAuditLog`, `TimetableSlot`, `AcademicHoliday`.
  - Dedicated controllers: `StudentAttendanceController`, `StaffAttendanceController`, `AttendanceReportController`, `TimetableSlotController`, `AcademicHolidayController`.
  - Service/conflict validator: `TimetableConflictService` for detecting overlapping slot allocations.
  - School tenant scoping via `BelongsToSchool` trait and authorization via `school.permission` middleware.
- **Frontend / Blade**:
  - Interactive daily attendance marking views with responsive touch targets.
  - Monthly attendance register table and summary dashboard widgets.
  - Weekly timetable matrix view and slot modal/drawer with real-time conflict feedback.
  - Sidebar accordion integration in `sidebar.blade.php`.
