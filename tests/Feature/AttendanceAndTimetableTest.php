<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\AcademicHoliday;
use App\Models\AcademicYear;
use App\Models\Admin;
use App\Models\School;
use App\Models\Section;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use App\Models\TimetableSlot;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceAndTimetableTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected School $otherSchool;

    protected Admin $superAdmin;

    protected AcademicYear $year;

    protected AcademicClass $class;

    protected Section $section;

    protected Student $student1;

    protected Student $student2;

    protected Staff $teacher;

    protected Subject $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'St. Jude Academy',
            'code' => 'STJ-01',
            'status' => 'active',
        ]);

        $this->otherSchool = School::create([
            'name' => 'Horizon Academy',
            'code' => 'HRZ-02',
            'status' => 'active',
        ]);

        $this->superAdmin = Admin::factory()->create([
            'role' => 'super_admin',
        ]);

        $this->year = AcademicYear::create([
            'school_id' => $this->school->id,
            'name' => '2026-2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_current' => true,
            'status' => 'active',
        ]);

        $this->class = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Grade 10',
            'numeric_code' => 10,
            'order_index' => 10,
            'status' => 'active',
        ]);

        $this->section = Section::create([
            'school_id' => $this->school->id,
            'academic_class_id' => $this->class->id,
            'name' => 'Section A',
            'capacity' => 40,
            'status' => 'active',
        ]);

        $this->subject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'Mathematics',
            'code' => 'MATH-10',
            'type' => 'theory',
            'credit_hours' => 4,
            'status' => 'active',
        ]);

        $this->teacher = Staff::create([
            'school_id' => $this->school->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'male',
            'staff_code' => 'STF-001',
            'email' => 'john.doe@stjude.edu',
            'phone' => '+1234567890',
            'is_teaching' => true,
            'designation' => 'Senior Mathematics Teacher',
            'status' => 'active',
            'joining_date' => '2024-01-01',
        ]);

        $this->student1 = Student::create([
            'school_id' => $this->school->id,
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'admission_number' => 'ADM-2026-001',
            'admission_date' => '2026-01-05',
            'gender' => 'female',
            'date_of_birth' => '2010-05-14',
            'status' => 'active',
        ]);

        $this->student2 = Student::create([
            'school_id' => $this->school->id,
            'first_name' => 'Bob',
            'last_name' => 'Jones',
            'admission_number' => 'ADM-2026-002',
            'admission_date' => '2026-01-05',
            'gender' => 'male',
            'date_of_birth' => '2010-08-20',
            'status' => 'active',
        ]);

        // Enroll students into Section A
        StudentEnrollment::create([
            'school_id' => $this->school->id,
            'student_id' => $this->student1->id,
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'roll_number' => 1,
            'status' => 'current',
            'enrolled_at' => '2026-01-10',
        ]);

        StudentEnrollment::create([
            'school_id' => $this->school->id,
            'student_id' => $this->student2->id,
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'roll_number' => 2,
            'status' => 'current',
            'enrolled_at' => '2026-01-10',
        ]);
    }

    /**
     * Test bulk roll-call submission and duplicate prevention on repeat saves.
     */
    public function test_student_attendance_bulk_marking_and_duplicate_prevention(): void
    {
        $today = Carbon::today()->toDateString();

        $postData = [
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'attendance_date' => $today,
            'records' => [
                [
                    'student_id' => $this->student1->id,
                    'status' => 'present',
                    'remarks' => null,
                ],
                [
                    'student_id' => $this->student2->id,
                    'status' => 'absent',
                    'remarks' => 'Unexcused absence',
                ],
            ],
        ];

        // 1. Initial Attendance Save
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.attendance.students.store'), $postData);

        $response->assertRedirect();
        $this->assertDatabaseCount('student_attendances', 2);

        $this->assertDatabaseHas('student_attendances', [
            'school_id' => $this->school->id,
            'student_id' => $this->student1->id,
            'status' => 'present',
        ]);

        $this->assertDatabaseHas('student_attendances', [
            'school_id' => $this->school->id,
            'student_id' => $this->student2->id,
            'status' => 'absent',
        ]);

        $savedRecord1 = StudentAttendance::where('student_id', $this->student1->id)->first();
        $this->assertNotNull($savedRecord1);
        $this->assertEquals($today, $savedRecord1->attendance_date->toDateString());

        // 2. Re-submit on the same date with updated status (Bob changed from absent to excused)
        $postData['records'][1]['status'] = 'excused';
        $postData['records'][1]['remarks'] = 'Doctor note provided';

        $repeatResponse = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.attendance.students.store'), $postData);

        $repeatResponse->assertRedirect();

        // Must still have exactly 2 rows (duplicate prevention)
        $this->assertDatabaseCount('student_attendances', 2);

        $this->assertDatabaseHas('student_attendances', [
            'student_id' => $this->student2->id,
            'status' => 'excused',
            'remarks' => 'Doctor note provided',
        ]);

        // 3. Confirm audit log recorded the correction
        $this->assertDatabaseHas('attendance_audit_logs', [
            'school_id' => $this->school->id,
            'action' => 'updated',
            'old_status' => 'absent',
            'new_status' => 'excused',
        ]);
    }

    /**
     * Test single attendance correction endpoint with audit logging.
     */
    public function test_single_attendance_status_correction_creates_audit_log(): void
    {
        $today = Carbon::today()->toDateString();

        $attendance = StudentAttendance::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'student_id' => $this->student1->id,
            'attendance_date' => $today,
            'status' => 'absent',
        ]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->put(route('admin.attendance.students.update', $attendance->id), [
                'status' => 'present',
                'reason' => 'Arrived after roll-call closed',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('student_attendances', [
            'id' => $attendance->id,
            'status' => 'present',
        ]);

        $this->assertDatabaseHas('attendance_audit_logs', [
            'attendable_id' => $attendance->id,
            'old_status' => 'absent',
            'new_status' => 'present',
            'reason' => 'Arrived after roll-call closed',
        ]);
    }

    /**
     * Test timetable slot creation and section overlap conflict prevention.
     */
    public function test_timetable_slot_section_conflict_detection(): void
    {
        // Slot 1: Monday 09:00 - 10:00 for Section A
        TimetableSlot::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'subject_id' => $this->subject->id,
            'day_of_week' => 'monday',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'type' => 'regular',
        ]);

        // Attempt overlapping slot for same Section A: Monday 09:30 - 10:30
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.timetables.store'), [
                'academic_year_id' => $this->year->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'subject_id' => $this->subject->id,
                'day_of_week' => 'monday',
                'start_time' => '09:30',
                'end_time' => '10:30',
                'type' => 'regular',
            ]);

        $response->assertSessionHasErrors('conflict');
        $this->assertDatabaseCount('timetable_slots', 1);
    }

    /**
     * Test timetable teacher overlap conflict prevention across different sections.
     */
    public function test_timetable_slot_teacher_conflict_detection(): void
    {
        $sectionB = Section::create([
            'school_id' => $this->school->id,
            'academic_class_id' => $this->class->id,
            'name' => 'Section B',
            'capacity' => 40,
            'status' => 'active',
        ]);

        // Slot 1: Section A with Teacher John Doe (09:00 - 10:00)
        TimetableSlot::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id,
            'day_of_week' => 'tuesday',
            'start_time' => '09:00',
            'end_time' => '10:00',
            'type' => 'regular',
        ]);

        // Slot 2 attempt: Section B with SAME Teacher John Doe at overlapping time (09:15 - 10:15)
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.timetables.store'), [
                'academic_year_id' => $this->year->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $sectionB->id,
                'subject_id' => $this->subject->id,
                'teacher_id' => $this->teacher->id,
                'day_of_week' => 'tuesday',
                'start_time' => '09:15',
                'end_time' => '10:15',
                'type' => 'regular',
            ]);

        $response->assertSessionHasErrors('conflict');
        $this->assertDatabaseCount('timetable_slots', 1);
    }

    /**
     * Test timetable room collision conflict prevention.
     */
    public function test_timetable_slot_room_conflict_detection(): void
    {
        $sectionB = Section::create([
            'school_id' => $this->school->id,
            'academic_class_id' => $this->class->id,
            'name' => 'Section B',
            'capacity' => 40,
            'status' => 'active',
        ]);

        // Slot 1: Room 204 booked by Section A from 11:00 - 12:00
        TimetableSlot::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'subject_id' => $this->subject->id,
            'room_number' => 'Room 204',
            'day_of_week' => 'wednesday',
            'start_time' => '11:00',
            'end_time' => '12:00',
            'type' => 'regular',
        ]);

        // Slot 2 attempt: Section B attempts to book Room 204 at overlapping time (11:30 - 12:30)
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.timetables.store'), [
                'academic_year_id' => $this->year->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $sectionB->id,
                'subject_id' => $this->subject->id,
                'room_number' => 'Room 204',
                'day_of_week' => 'wednesday',
                'start_time' => '11:30',
                'end_time' => '12:30',
                'type' => 'regular',
            ]);

        $response->assertSessionHasErrors('conflict');
        $this->assertDatabaseCount('timetable_slots', 1);
    }

    /**
     * Test daily staff attendance recording.
     */
    public function test_staff_attendance_daily_recording(): void
    {
        $today = Carbon::today()->toDateString();

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.attendance.staff.store'), [
                'attendance_date' => $today,
                'records' => [
                    [
                        'staff_id' => $this->teacher->id,
                        'status' => 'present',
                        'check_in_time' => '08:25',
                        'check_out_time' => '15:30',
                        'remarks' => 'On duty',
                    ],
                ],
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('staff_attendances', [
            'school_id' => $this->school->id,
            'staff_id' => $this->teacher->id,
            'status' => 'present',
        ]);

        $savedStaff = StaffAttendance::where('staff_id', $this->teacher->id)->first();
        $this->assertNotNull($savedStaff);
        $this->assertEquals($today, $savedStaff->attendance_date->toDateString());
        $this->assertStringStartsWith('08:25', $savedStaff->check_in_time);
    }

    /**
     * Test academic holiday creation and detection.
     */
    public function test_academic_holiday_creation_and_api_detection(): void
    {
        $holiday = AcademicHoliday::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->year->id,
            'title' => 'National Spring Holiday',
            'start_date' => '2026-03-20',
            'end_date' => '2026-03-25',
            'type' => 'holiday',
            'description' => 'Spring break',
        ]);

        $this->assertDatabaseHas('academic_holidays', [
            'id' => $holiday->id,
            'title' => 'National Spring Holiday',
        ]);

        // API check date
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->getJson(route('admin.academic.holidays.check-date', [
                'academic_year_id' => $this->year->id,
                'date' => '2026-03-22',
            ]));

        $response->assertOk()
            ->assertJson([
                'is_holiday' => true,
                'holiday' => [
                    'title' => 'National Spring Holiday',
                ],
            ]);
    }

    /**
     * Test monthly attendance register view.
     */
    public function test_monthly_attendance_register_renders_cleanly(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.attendance.reports.index', [
                'academic_year_id' => $this->year->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'month' => 5,
                'year' => 2026,
            ]));

        $response->assertOk()
            ->assertSee('Monthly Attendance Register')
            ->assertSee($this->student1->full_name)
            ->assertSee($this->student2->full_name);
    }

    /**
     * Test staff attendance ledger renders with personnel type filters.
     */
    public function test_staff_attendance_page_renders_cleanly(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.attendance.staff.index', ['type' => 'teaching']));

        $response->assertOk()
            ->assertSee('Faculty & Staff Attendance')
            ->assertSee($this->teacher->full_name);
    }

    /**
     * Test class timetable and faculty schedule index views render cleanly.
     */
    public function test_timetable_pages_render_cleanly(): void
    {
        // 1. Class Timetables view
        $classResponse = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.timetables.index', [
                'academic_class_id' => $this->class->id,
                'section_id' => $this->section->id,
            ]));

        $classResponse->assertOk()
            ->assertSee('Class Timetables');

        // 2. Faculty Schedules view
        $teacherResponse = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.timetables.teachers', [
                'teacher_id' => $this->teacher->id,
            ]));

        $teacherResponse->assertOk()
            ->assertSee('Faculty Schedules');
    }
}
