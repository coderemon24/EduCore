# Design: Attendance & Timetable Management

## Context

EduCore SIS is built on Laravel 12, MySQL/MariaDB, and Tailwind CSS v4. The platform currently maintains multi-tenant isolation via the `BelongsToSchool` trait and global scopes, with academic sessions (`AcademicYear`), grade levels (`AcademicClass`), sections (`Section`), subject catalog (`Subject`), syllabus mapping (`ClassSubjectAssignment`), student rosters (`Student`, `StudentEnrollment`), and faculty (`Staff`).

This design introduces daily attendance tracking for students and staff, class timetable scheduling with multi-variable conflict detection, and academic holiday calendars.

## Goals / Non-Goals

**Goals:**
- **High-Throughput Roll-Call**: Allow teachers/administrators to mark an entire section roster (30–60 students) in seconds with single-action bulk controls and atomic transactional persistence.
- **Zero Duplicate Attendance**: Strict composite unique constraints on `(school_id, student_id, attendance_date)` and `(school_id, staff_id, attendance_date)`.
- **Query Optimization**: Design index coverage so roster queries, monthly register aggregations, and conflict checks execute under 5ms.
- **Robust Conflict Prevention**: Real-time validation preventing overlapping timetable slots for teachers, classrooms, and student sections.
- **Auditability**: Track changes and corrections to student attendance statuses with timestamps, modifier identity, and audit logs.
- **Anti-Slop UI**: Clean, high-density, accessible Blade interfaces with 44px+ touch targets on mobile and zero decorative visual slop.

**Non-Goals:**
- Biometric hardware integration (RFID/fingerprint scanner hardware APIs are out of scope for this change; manual and web check-in are supported).
- Automated AI algorithmic timetable generation (solvers/genetic algorithms). The system provides conflict-guarded manual and template-assisted scheduling.
- Period-by-period student roll call (daily roll call per section is the implemented standard).

## Decisions

### 1. Database Schema & Indexing Strategy

```
student_attendances
├── id (bigint unsigned PK)
├── school_id (FK -> schools.id)
├── academic_year_id (FK -> academic_years.id)
├── academic_class_id (FK -> academic_classes.id)
├── section_id (FK -> sections.id)
├── student_id (FK -> students.id)
├── attendance_date (date)
├── status (enum: 'present', 'absent', 'late', 'half_day', 'excused')
├── remarks (varchar 255, nullable)
├── recorded_by_id (FK -> users/admins)
├── updated_by_id (FK -> users/admins, nullable)
├── created_at / updated_at
└── Constraints:
    - UNIQUE(school_id, student_id, attendance_date)
    - INDEX(school_id, section_id, attendance_date) [Roster lookup]
    - INDEX(school_id, student_id, attendance_date, status) [Individual student metrics]
    - INDEX(school_id, academic_class_id, attendance_date) [Grade level reporting]

staff_attendances
├── id (bigint unsigned PK)
├── school_id (FK -> schools.id)
├── staff_id (FK -> staff.id)
├── attendance_date (date)
├── status (enum: 'present', 'absent', 'late', 'half_day', 'on_leave')
├── check_in_time (time, nullable)
├── check_out_time (time, nullable)
├── remarks (varchar 255, nullable)
├── recorded_by_id (FK)
├── updated_by_id (FK)
└── Constraints:
    - UNIQUE(school_id, staff_id, attendance_date)
    - INDEX(school_id, attendance_date, status)

timetable_slots
├── id (bigint unsigned PK)
├── school_id (FK -> schools.id)
├── academic_year_id (FK -> academic_years.id)
├── academic_class_id (FK -> academic_classes.id)
├── section_id (FK -> sections.id)
├── subject_id (FK -> subjects.id)
├── teacher_id (FK -> staff.id, nullable)
├── day_of_week (enum: 'monday','tuesday','wednesday','thursday','friday','saturday','sunday')
├── start_time (time)
├── end_time (time)
├── room_number (varchar 50, nullable)
├── type (enum: 'regular', 'lab', 'practical', 'break')
└── Constraints:
    - INDEX(school_id, academic_year_id, section_id, day_of_week, start_time)
    - INDEX(school_id, academic_year_id, teacher_id, day_of_week, start_time)
    - INDEX(school_id, academic_year_id, room_number, day_of_week, start_time)

academic_holidays
├── id (bigint unsigned PK)
├── school_id (FK -> schools.id)
├── academic_year_id (FK -> academic_years.id)
├── title (varchar 191)
├── start_date (date)
├── end_date (date)
├── type (enum: 'holiday', 'weekend', 'exam_break', 'unplanned')
├── description (text, nullable)
└── Constraints:
    - INDEX(school_id, start_date, end_date)

attendance_audit_logs
├── id (bigint unsigned PK)
├── school_id (FK -> schools.id)
├── attendable_type (varchar 100)
├── attendable_id (bigint unsigned)
├── action (enum: 'created', 'updated')
├── old_status (varchar 50, nullable)
├── new_status (varchar 50)
├── reason (varchar 255, nullable)
├── user_id (bigint unsigned nullable)
├── ip_address (varchar 45, nullable)
└── created_at (timestamp)
```

*Rationale for upsert pattern vs insert/update*:
In student roll-call, an administrator might mark 35 students, and later return to adjust two who arrived late. Using `upsert` or single-transaction update-or-create ensures idempotency and avoids database deadlocks.

### 2. Timetable Conflict Detection Engine (`TimetableConflictService`)

A dedicated service encapsulates the mathematical interval overlap check:
```php
$conflicts = TimetableSlot::query()
    ->where('school_id', $schoolId)
    ->where('academic_year_id', $yearId)
    ->where('day_of_week', $dayOfWeek)
    ->when($excludeSlotId, fn($q) => $q->where('id', '!=', $excludeSlotId))
    ->where(function ($query) use ($startTime, $endTime) {
        $query->where('start_time', '<', $endTime)
              ->where('end_time', '>', $startTime);
    })
    ->where(function ($query) use ($teacherId, $roomNumber, $sectionId) {
        $query->where('section_id', $sectionId);
        if ($teacherId) {
            $query->orWhere('teacher_id', $teacherId);
        }
        if (!empty($roomNumber)) {
            $query->orWhere('room_number', $roomNumber);
        }
    })
    ->with(['academicClass', 'section', 'subject', 'teacher'])
    ->get();
```

*Detailed Diagnostics*: If conflicts exist, the service formats actionable explanations:
- *"Teacher collision: Mr. John Smith is already teaching Class 10-A (Mathematics) during this time window."*
- *"Room collision: Room 204 is booked by Class 9-B (Physics) until 10:30."*
- *"Section collision: This section already has a registered slot (English Literature) during this period."*

### 3. Fast Roll-Call UI Pattern (Anti-Slop)

- **Toolbar Controls**: Sticky bar with quick select:
  - `[Mark All Present]` [Green border button]
  - `[Mark All Absent]` [Rose border button]
  - `[Clear]` [Slate border button]
  - Real-time stat badge counter updating immediately via vanilla JS without roundtrip delays.
- **Compact Table Structure**:
  - Roll #, Avatar/Initials, Student Name, Admission #, Status Pills (radio group with clear focus outlines: Present, Absent, Late, Half-Day, Excused), Remarks input.
- **Form Submission**: Single POST request passing `records[student_id][status]` and `records[student_id][remarks]`. Executed inside a `DB::transaction()` block.

### 4. Sidebar Dropdown Navigation

Update `resources/views/layouts/admin-partial/sidebar.blade.php`:
- Replace the static placeholder `Attendance` alert button with a functional accordion dropdown:
  - `Student Attendance` (`admin.attendance.students.index`)
  - `Attendance Register & Reports` (`admin.attendance.reports.index`)
  - `Staff Attendance` (`admin.attendance.staff.index`)
  - `Holidays Calendar` (`admin.academic.holidays.index`)
- Add `Timetables` submenu or link under Academic & Operations:
  - `Class Timetables` (`admin.timetables.index`)
  - `Teacher Schedules` (`admin.timetables.teachers`)

## Risks / Trade-offs

- **[Risk] High Volume in `student_attendances`** → A school with 1,000 students generates ~200,000 attendance records per year.
  - *Mitigation*: Multi-column composite indexes covering `(school_id, section_id, attendance_date)` and `(school_id, student_id, attendance_date)`. Partitioning can be applied later if needed; composite indexing easily handles millions of rows in MySQL.
- **[Risk] Concurrent Attendance Marking by Two Staff Members** → Race conditions could overwrite newer corrections.
  - *Mitigation*: Wrap in database transactions with `attendance_audit_logs` tracking user identity and timestamp of each edit.
- **[Risk] Timetable Time Zone / Format Ambiguity** → Different time formats (12-hour AM/PM vs. 24-hour military time).
  - *Mitigation*: Store as standard SQL `TIME` (HH:MM:SS) in backend; format cleanly in frontend view using Blade carbon helpers.

## Migration Plan

1. Create migration for tables: `student_attendances`, `staff_attendances`, `attendance_audit_logs`, `timetable_slots`, `academic_holidays`.
2. Run `php artisan migrate`.
3. Add seeders / permission slugs for attendance and timetable management (`attendance.view`, `attendance.mark`, `attendance.reports`, `timetable.view`, `timetable.manage`, `holidays.manage`).
4. Implement Eloquent models with relations and scopes.
5. Implement controllers, requests, conflict service, and Blade views.
6. Verify via automated tests and browser navigation.
