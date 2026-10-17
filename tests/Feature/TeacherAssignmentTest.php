<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Admin;
use App\Models\ClassSubjectAssignment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\School;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TeacherAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected School $otherSchool;

    protected Admin $superAdmin;

    protected User $schoolAdminUser;

    protected AcademicClass $class;

    protected Subject $subject;

    protected AcademicYear $year;

    protected Staff $teacher;

    protected Staff $nonTeachingStaff;

    protected Staff $otherSchoolTeacher;

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

        $perm = Permission::create(['name' => 'Manage Academic', 'slug' => 'academic.manage', 'group' => 'academic']);
        $role = Role::create([
            'school_id' => $this->school->id,
            'name' => 'Academic Admin',
            'slug' => 'academic_admin',
            'is_system' => true,
        ]);
        $role->permissions()->sync([$perm->id]);

        $this->schoolAdminUser = User::create([
            'school_id' => $this->school->id,
            'name' => 'Academic Officer',
            'email' => 'officer@stjude.edu',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $this->schoolAdminUser->roles()->sync([$role->id]);

        $this->class = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Grade 10',
            'numeric_grade' => 10,
            'status' => 'active',
        ]);

        $this->subject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'Mathematics',
            'code' => 'MATH-101',
            'type' => 'theory',
            'status' => 'active',
        ]);

        $this->year = AcademicYear::create([
            'school_id' => $this->school->id,
            'name' => '2026-2027 Session',
            'start_date' => '2026-09-01',
            'end_date' => '2027-06-30',
            'is_current' => true,
            'status' => 'active',
        ]);

        // Teaching Faculty in same school
        $this->teacher = Staff::create([
            'school_id' => $this->school->id,
            'staff_code' => 'TCH-STJ-01',
            'first_name' => 'Alan',
            'last_name' => 'Turing',
            'gender' => 'Male',
            'email' => 'turing@stjude.edu',
            'department' => 'Mathematics',
            'designation' => 'Senior Teacher',
            'is_teaching' => true,
            'joining_date' => '2025-01-01',
            'contract_type' => 'permanent',
            'status' => 'active',
        ]);

        // Non-Teaching Staff in same school
        $this->nonTeachingStaff = Staff::create([
            'school_id' => $this->school->id,
            'staff_code' => 'ACC-STJ-02',
            'first_name' => 'Accountant',
            'last_name' => 'Bob',
            'gender' => 'Male',
            'email' => 'bob@stjude.edu',
            'department' => 'Finance',
            'designation' => 'Chief Bursar',
            'is_teaching' => false,
            'joining_date' => '2025-01-01',
            'contract_type' => 'permanent',
            'status' => 'active',
        ]);

        // Teacher in another school
        $this->otherSchoolTeacher = Staff::create([
            'school_id' => $this->otherSchool->id,
            'staff_code' => 'TCH-HRZ-99',
            'first_name' => 'Other',
            'last_name' => 'Teacher',
            'gender' => 'Female',
            'email' => 'other@horizon.edu',
            'department' => 'Science',
            'designation' => 'Teacher',
            'is_teaching' => true,
            'joining_date' => '2025-01-01',
            'contract_type' => 'permanent',
            'status' => 'active',
        ]);
    }

    /**
     * Test assigning a teaching staff member as class teacher to a section.
     */
    public function test_can_assign_teaching_staff_as_class_teacher_to_section(): void
    {
        $payload = [
            'academic_class_id' => $this->class->id,
            'name' => 'Section A',
            'capacity' => 35,
            'room_number' => 'Room 101',
            'class_teacher_id' => $this->teacher->id,
            'status' => 'active',
        ];

        $response = $this->actingAs($this->schoolAdminUser, 'web')->post('/admin/academic/sections', $payload);
        $response->assertSessionHasNoErrors();

        $section = Section::where('name', 'Section A')->where('academic_class_id', $this->class->id)->first();
        $this->assertNotNull($section);
        $this->assertEquals($this->teacher->id, $section->class_teacher_id);
        $this->assertEquals('Alan', $section->classTeacher->first_name);

        // Verify reverse relation from staff
        $this->assertTrue($this->teacher->classTeacherSections->contains($section->id));
    }

    /**
     * Test validation rejects non-teaching staff as class teacher.
     */
    public function test_cannot_assign_non_teaching_staff_as_class_teacher(): void
    {
        $payload = [
            'academic_class_id' => $this->class->id,
            'name' => 'Section B',
            'class_teacher_id' => $this->nonTeachingStaff->id,
            'status' => 'active',
        ];

        $response = $this->from('/admin/academic/sections/create')
            ->actingAs($this->schoolAdminUser, 'web')
            ->post('/admin/academic/sections', $payload);

        $response->assertSessionHasErrors('class_teacher_id');
        $this->assertDatabaseMissing('sections', ['name' => 'Section B']);
    }

    /**
     * Test validation rejects teacher from a different school as class teacher.
     */
    public function test_cannot_assign_teacher_from_different_school_as_class_teacher(): void
    {
        $payload = [
            'academic_class_id' => $this->class->id,
            'name' => 'Section C',
            'class_teacher_id' => $this->otherSchoolTeacher->id,
            'status' => 'active',
        ];

        $response = $this->from('/admin/academic/sections/create')
            ->actingAs($this->schoolAdminUser, 'web')
            ->post('/admin/academic/sections', $payload);

        $response->assertSessionHasErrors('class_teacher_id');
        $this->assertDatabaseMissing('sections', ['name' => 'Section C']);
    }

    /**
     * Test assigning teacher to curriculum subject assignment.
     */
    public function test_can_assign_teacher_to_class_subject_assignment(): void
    {
        $payload = [
            'academic_class_id' => $this->class->id,
            'subject_ids' => [$this->subject->id],
            'academic_year_id' => $this->year->id,
            'teacher_id' => $this->teacher->id,
            'type' => 'compulsory',
            'full_marks' => 100,
            'pass_marks' => 40,
        ];

        $response = $this->actingAs($this->schoolAdminUser, 'web')->post('/admin/academic/assign-subjects', $payload);
        $response->assertSessionHasNoErrors();

        $assignment = ClassSubjectAssignment::where('academic_class_id', $this->class->id)
            ->where('subject_id', $this->subject->id)
            ->first();

        $this->assertNotNull($assignment);
        $this->assertEquals($this->teacher->id, $assignment->teacher_id);
        $this->assertEquals('Alan', $assignment->teacher->first_name);

        // Verify reverse relation
        $this->assertTrue($this->teacher->subjectAssignments->contains($assignment->id));
    }

    /**
     * Test validation rejects teacher from different school for subject assignment.
     */
    public function test_cannot_assign_cross_school_teacher_to_subject(): void
    {
        $payload = [
            'academic_class_id' => $this->class->id,
            'subject_ids' => [$this->subject->id],
            'academic_year_id' => $this->year->id,
            'teacher_id' => $this->otherSchoolTeacher->id,
            'type' => 'compulsory',
            'full_marks' => 100,
            'pass_marks' => 40,
        ];

        $response = $this->from('/admin/academic/assign-subjects/create')
            ->actingAs($this->schoolAdminUser, 'web')
            ->post('/admin/academic/assign-subjects', $payload);

        $response->assertSessionHasErrors('teacher_id');
    }
}
