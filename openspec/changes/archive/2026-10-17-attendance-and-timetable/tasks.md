# Tasks: Attendance & Timetable Management

## 1. Models & Database Migrations

- [x] 1.1 Create database migration for `student_attendances`, `staff_attendances`, and `attendance_audit_logs` with multi-column performance indexes and uniqueness constraints.
- [x] 1.2 Create database migration for `timetable_slots` and `academic_holidays` with foreign keys and time-overlap indexing.
- [x] 1.3 Run `php artisan migrate` and verify table structures in MySQL/MariaDB.
- [x] 1.4 Implement Eloquent model `StudentAttendance` with `BelongsToSchool`, relationships to Student, Class, Section, AcademicYear, and status scopes.
- [x] 1.5 Implement Eloquent model `StaffAttendance` with `BelongsToSchool`, relationships to Staff, and date/status scopes.
- [x] 1.6 Implement Eloquent model `TimetableSlot` with relationships to Class, Section, Subject, Teacher, and AcademicYear.
- [x] 1.7 Implement Eloquent model `AcademicHoliday` with date-range query scopes.
- [x] 1.8 Implement Eloquent model `AttendanceAuditLog` with polymorphic or direct association to attendance records.
- [x] 1.9 Seed attendance, timetable, and holiday management permissions in `RolePermissionSeeder` and assign to `school_admin` and `teacher` roles.

## 2. Controllers & Business Logic

- [x] 2.1 Implement `TimetableConflictService` to detect teacher, room, and section collisions with descriptive error diagnostics.
- [x] 2.2 Implement `StudentAttendanceController` with roster filtering (`index`), bulk roll-call save (`store`), and single-student correction with audit trail.
- [x] 2.3 Implement Form Requests `StoreStudentAttendanceRequest` and `UpdateStudentAttendanceRequest` validating enrollment, date, status, and tenant scope.
- [x] 2.4 Implement `StaffAttendanceController` with staff roster listing, daily status recording, and check-in/out timestamp support.
- [x] 2.5 Implement `AttendanceReportController` with monthly attendance register matrix generation and export/summary aggregations.
- [x] 2.6 Implement `TimetableSlotController` with weekly matrix retrieval, slot creation/edit with `TimetableConflictService` pre-check, and deletion.
- [x] 2.7 Implement `AcademicHolidayController` with holiday CRUD and active holiday checking for attendance dates.
- [x] 2.8 Register all attendance, timetable, and holiday routes under `routes/admin.php` with authorization middleware (`school.permission`).

## 3. Layout & Navigation Architecture

- [x] 3.1 Update `resources/views/layouts/admin-partial/sidebar.blade.php` to replace the static Attendance button with a collapsible accordion dropdown containing Student Attendance, Staff Attendance, Reports, and Holidays.
- [x] 3.2 Add Timetable and Faculty Schedule navigation items under the Academic & Operations section in the sidebar.
- [x] 3.3 Ensure mobile responsiveness, active-link indicators, and collapsed-sidebar tooltips function seamlessly.

## 4. Blade Views & User Interactions

- [x] 4.1 Create `resources/views/admin/attendance/students/index.blade.php` featuring academic filter bar (Year, Class, Section, Date), holiday warning alerts, and summary stats counter.
- [x] 4.2 Build the high-density roll-call student roster table with "Mark All Present" / "Mark All Absent" fast-action toolbar, accessible status pill toggles (Present, Absent, Late, Excused), and inline remarks.
- [x] 4.3 Create `resources/views/admin/attendance/reports/index.blade.php` with the monthly attendance register heatmap matrix (days 1–31) and student attendance percentage indicators.
- [x] 4.4 Create `resources/views/admin/attendance/staff/index.blade.php` for daily staff attendance logging with check-in/check-out fields.
- [x] 4.5 Create `resources/views/admin/timetables/index.blade.php` featuring the weekly timetable schedule matrix (Monday–Sunday columns, time periods in rows) and filter by Class/Section or Teacher.
- [x] 4.6 Build the timetable slot creation/edit modal/drawer with live conflict feedback banners and period preset helpers.
- [x] 4.7 Create `resources/views/admin/academic/holidays/index.blade.php` for managing school holidays and non-working days.

## 5. Verification & Testing

- [x] 5.1 Test duplicate roll-call prevention: verify that multiple submissions on the same date update existing rows without database errors.
- [x] 5.2 Test timetable conflict prevention: verify teacher, room, and section overlap collisions are properly rejected with clear error messages.
- [x] 5.3 Test cross-tenant isolation and enrollment validation: verify attendance cannot be marked for students from other schools or sections.
- [x] 5.4 Test attendance audit logging: confirm changes from absent to excused are logged with user ID and timestamp.
- [x] 5.5 Verify responsive UI layouts on mobile and desktop viewports, ensuring clean typography, contrast, and zero AI-slop aesthetic artifacts.
- [x] 5.6 Run Laravel Pint code styling and automated feature tests.
