<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Admin;
use App\Models\Guardian;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected School $school;

    protected AcademicYear $academicYear;

    protected AcademicClass $academicClass;

    protected Section $section;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::factory()->create([
            'name' => 'EduCore High School',
            'status' => 'active',
        ]);

        app()->instance('current_school_id', $this->school->id);

        $this->admin = Admin::factory()->create([
            'full_name' => 'Principal Skinner',
            'designation' => 'Academic Administrator',
        ]);

        $this->academicYear = AcademicYear::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'name' => '2026–2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_current' => true,
            'status' => 'active',
        ]);

        $this->academicClass = AcademicClass::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'name' => 'Grade 9',
            'numeric_code' => 9,
            'order_index' => 9,
            'status' => 'active',
        ]);

        $this->section = Section::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'academic_class_id' => $this->academicClass->id,
            'name' => 'A',
            'capacity' => 40,
            'status' => 'active',
        ]);
    }

    protected function tearDown(): void
    {
        app()->forgetInstance('current_school_id');
        parent::tearDown();
    }

    /**
     * Test sidebar contains the Student Management accordion.
     */
    public function test_admin_sidebar_contains_student_management_accordion(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSeeText('Student Management', false);
        $response->assertSeeText('Student Directory', false);
        $response->assertSeeText('New Admission', false);
        $response->assertSeeText('Promotions & Transfers', false);
    }

    /**
     * Test student directory page renders successfully with seeded data.
     */
    public function test_student_directory_renders_successfully(): void
    {
        $student = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-2026-0099',
            'admission_date' => '2026-01-10',
            'first_name' => 'Lucas',
            'last_name' => 'Scott',
            'gender' => 'male',
            'status' => 'active',
        ]);

        StudentEnrollment::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'student_id' => $student->id,
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'section_id' => $this->section->id,
            'roll_number' => '10',
            'status' => 'current',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.students.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Lucas Scott');
        $response->assertSeeText('ADM-2026-0099');
        $response->assertSeeText('Grade 9');
    }

    /**
     * Test student admission creates student, guardian, and initial enrollment in one transaction.
     */
    public function test_student_admission_creates_student_guardian_and_enrollment(): void
    {
        $data = [
            'first_name' => 'Benjamin',
            'last_name' => 'Franklin',
            'gender' => 'male',
            'admission_number' => 'ADM-2026-0101',
            'admission_date' => '2026-02-01',
            'date_of_birth' => '2010-01-17',
            'blood_group' => 'O+',
            'religion' => 'Christianity',
            'email' => 'benjamin@example.com',
            'phone' => '+15551234567',
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'section_id' => $this->section->id,
            'roll_number' => '101',
            'guardian_name' => 'Josiah Franklin',
            'guardian_phone' => '+15559876543',
            'guardian_relation' => 'father',
            'guardian_email' => 'josiah@example.com',
            'current_address' => 'Milk Street, Boston, MA',
        ];

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.students.store'), $data);

        $response->assertStatus(302);

        $student = Student::withoutGlobalScopes()->where('admission_number', 'ADM-2026-0101')->first();
        $this->assertNotNull($student);
        $this->assertEquals('Benjamin', $student->first_name);
        $this->assertEquals('active', $student->status);

        // Assert guardian created and linked
        $guardian = Guardian::withoutGlobalScopes()->where('phone', '+15559876543')->first();
        $this->assertNotNull($guardian);
        $this->assertEquals('Josiah Franklin', $guardian->name);
        $this->assertTrue($student->guardians()->where('guardian_id', $guardian->id)->exists());

        // Assert enrollment created
        $enrollment = StudentEnrollment::withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->where('status', 'current')
            ->first();
        $this->assertNotNull($enrollment);
        $this->assertEquals($this->academicClass->id, $enrollment->academic_class_id);
        $this->assertEquals($this->section->id, $enrollment->section_id);
        $this->assertEquals('101', $enrollment->roll_number);
    }

    /**
     * Test duplicate admission number in the same school is rejected.
     */
    public function test_duplicate_admission_number_in_same_school_is_rejected(): void
    {
        Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-DUP-01',
            'admission_date' => '2026-01-01',
            'first_name' => 'Existing',
            'gender' => 'female',
            'status' => 'active',
        ]);

        $data = [
            'first_name' => 'Duplicate',
            'gender' => 'male',
            'admission_number' => 'ADM-DUP-01',
            'admission_date' => '2026-02-01',
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
        ];

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.students.store'), $data);

        $response->assertSessionHasErrors(['admission_number']);
    }

    /**
     * Test identical admission numbers are allowed in different schools (multi-tenant scope).
     */
    public function test_identical_admission_number_allowed_in_different_schools(): void
    {
        $schoolB = School::factory()->create([
            'name' => 'School B',
            'status' => 'active',
        ]);

        // Student in School A
        Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-SHARED-01',
            'admission_date' => '2026-01-01',
            'first_name' => 'Student A',
            'gender' => 'male',
            'status' => 'active',
        ]);

        // Switch to School B
        app()->instance('current_school_id', $schoolB->id);

        $academicYearB = AcademicYear::withoutGlobalScopes()->create([
            'school_id' => $schoolB->id,
            'name' => '2026–2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_current' => true,
            'status' => 'active',
        ]);

        $classB = AcademicClass::withoutGlobalScopes()->create([
            'school_id' => $schoolB->id,
            'name' => 'Grade 9',
            'order_index' => 1,
            'status' => 'active',
        ]);

        $data = [
            'first_name' => 'Student B',
            'gender' => 'female',
            'admission_number' => 'ADM-SHARED-01',
            'admission_date' => '2026-02-01',
            'academic_year_id' => $academicYearB->id,
            'academic_class_id' => $classB->id,
        ];

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.students.store'), $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('students', [
            'school_id' => $schoolB->id,
            'admission_number' => 'ADM-SHARED-01',
            'first_name' => 'Student B',
        ]);
    }

    /**
     * Test photo upload validates and saves to disk.
     */
    public function test_photo_upload_validates_and_stores_file(): void
    {
        $file = UploadedFile::fake()->image('student_avatar.jpg', 200, 200);

        $data = [
            'first_name' => 'Clara',
            'last_name' => 'Barton',
            'gender' => 'female',
            'admission_number' => 'ADM-2026-PHOTO',
            'admission_date' => '2026-01-15',
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'photo' => $file,
        ];

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.students.store'), $data);

        $response->assertStatus(302);

        $student = Student::withoutGlobalScopes()->where('admission_number', 'ADM-2026-PHOTO')->first();
        $this->assertNotNull($student->photo);
        $this->assertTrue(File::exists(public_path($student->photo)));

        // Clean up test file
        File::delete(public_path($student->photo));
    }

    /**
     * Test individual promotion updates old enrollment to promoted and creates new current enrollment.
     */
    public function test_individual_promotion_preserves_history(): void
    {
        $nextYear = AcademicYear::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'name' => '2027–2028',
            'start_date' => '2027-01-01',
            'end_date' => '2027-12-31',
            'is_current' => false,
            'status' => 'active',
        ]);

        $nextClass = AcademicClass::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'name' => 'Grade 10',
            'numeric_code' => 10,
            'order_index' => 10,
            'status' => 'active',
        ]);

        $student = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-PROMOTE-01',
            'admission_date' => '2026-01-10',
            'first_name' => 'Harry',
            'last_name' => 'Potter',
            'gender' => 'male',
            'status' => 'active',
        ]);

        $priorEnrollment = StudentEnrollment::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'student_id' => $student->id,
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'section_id' => $this->section->id,
            'roll_number' => '7',
            'status' => 'current',
            'enrolled_at' => '2026-01-10',
        ]);

        $promoteData = [
            'action_type' => 'promotion',
            'academic_year_id' => $nextYear->id,
            'academic_class_id' => $nextClass->id,
            'section_id' => null,
            'roll_number' => '12',
            'remarks' => 'Passed Year 9 Annual Exams.',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.students.promote.store', $student), $promoteData);

        $response->assertStatus(302);

        // Verify previous enrollment is now promoted
        $priorEnrollment->refresh();
        $this->assertEquals('promoted', $priorEnrollment->status);
        $this->assertNotNull($priorEnrollment->promoted_at);

        // Verify new current enrollment exists
        $newEnrollment = StudentEnrollment::withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->where('status', 'current')
            ->first();

        $this->assertNotNull($newEnrollment);
        $this->assertEquals($nextYear->id, $newEnrollment->academic_year_id);
        $this->assertEquals($nextClass->id, $newEnrollment->academic_class_id);
        $this->assertEquals('12', $newEnrollment->roll_number);
    }

    /**
     * Test bulk class promotion advances multiple students in one transaction.
     */
    public function test_bulk_promotion_advances_multiple_students(): void
    {
        $nextYear = AcademicYear::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'name' => '2027–2028',
            'start_date' => '2027-01-01',
            'end_date' => '2027-12-31',
            'is_current' => false,
            'status' => 'active',
        ]);

        $nextClass = AcademicClass::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'name' => 'Grade 10',
            'numeric_code' => 10,
            'order_index' => 10,
            'status' => 'active',
        ]);

        $students = [];
        for ($i = 1; $i <= 3; $i++) {
            $s = Student::withoutGlobalScopes()->create([
                'school_id' => $this->school->id,
                'admission_number' => "ADM-BULK-0{$i}",
                'admission_date' => '2026-01-10',
                'first_name' => "CohortStudent{$i}",
                'gender' => 'male',
                'status' => 'active',
            ]);

            StudentEnrollment::withoutGlobalScopes()->create([
                'school_id' => $this->school->id,
                'student_id' => $s->id,
                'academic_year_id' => $this->academicYear->id,
                'academic_class_id' => $this->academicClass->id,
                'section_id' => $this->section->id,
                'roll_number' => (string) $i,
                'status' => 'current',
            ]);

            $students[] = $s;
        }

        $bulkData = [
            'student_ids' => collect($students)->pluck('id')->toArray(),
            'target_academic_year_id' => $nextYear->id,
            'target_academic_class_id' => $nextClass->id,
            'remarks' => 'Batch Year 9 Promotion',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.students.promotions.store'), $bulkData);

        $response->assertStatus(302);

        // All 3 should have a new current enrollment in nextClass
        foreach ($students as $s) {
            $current = StudentEnrollment::withoutGlobalScopes()
                ->where('student_id', $s->id)
                ->where('status', 'current')
                ->first();

            $this->assertNotNull($current);
            $this->assertEquals($nextClass->id, $current->academic_class_id);
            $this->assertEquals($nextYear->id, $current->academic_year_id);

            // Prior enrollment must be promoted
            $promoted = StudentEnrollment::withoutGlobalScopes()
                ->where('student_id', $s->id)
                ->where('status', 'promoted')
                ->first();
            $this->assertNotNull($promoted);
        }
    }

    /**
     * Test roster API returns JSON of enrolled students for class and section.
     */
    public function test_roster_api_returns_enrolled_students(): void
    {
        $student = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-ROSTER-01',
            'admission_date' => '2026-01-10',
            'first_name' => 'Hermione',
            'last_name' => 'Granger',
            'gender' => 'female',
            'status' => 'active',
        ]);

        StudentEnrollment::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'student_id' => $student->id,
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'section_id' => $this->section->id,
            'roll_number' => '1',
            'status' => 'current',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->getJson(route('admin.students.promotions.roster', [
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'section_id' => $this->section->id,
        ]));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'admission_number' => 'ADM-ROSTER-01',
            'name' => 'Hermione Granger',
        ]);
    }

    /**
     * Test cross-tenant isolation prevents accessing another school's student.
     */
    public function test_cross_tenant_isolation_prevents_accessing_other_school_student(): void
    {
        $schoolB = School::factory()->create([
            'name' => 'Other School',
            'status' => 'active',
        ]);

        $studentB = Student::withoutGlobalScopes()->create([
            'school_id' => $schoolB->id,
            'admission_number' => 'ADM-OTHER-01',
            'admission_date' => '2026-01-01',
            'first_name' => 'Private Student',
            'gender' => 'female',
            'status' => 'active',
        ]);

        // Attempting to view studentB while current_school_id is School A
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.students.show', $studentB));

        $response->assertStatus(404);
    }

    /**
     * Test single student soft delete archives student and their enrollments.
     */
    public function test_student_soft_delete_archives_student_and_enrollments(): void
    {
        $student = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-DEL-01',
            'admission_date' => '2026-01-01',
            'first_name' => 'Oliver',
            'last_name' => 'Twist',
            'gender' => 'male',
            'status' => 'active',
        ]);

        $enrollment = StudentEnrollment::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'student_id' => $student->id,
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'section_id' => $this->section->id,
            'roll_number' => '55',
            'status' => 'current',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.students.destroy', $student));

        $response->assertStatus(302);
        $this->assertSoftDeleted('students', ['id' => $student->id]);
        $this->assertSoftDeleted('student_enrollments', ['id' => $enrollment->id]);
    }

    /**
     * Test trashed directory renders soft-deleted students.
     */
    public function test_trashed_directory_renders_soft_deleted_students(): void
    {
        $student = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-TRASHED-01',
            'admission_date' => '2026-01-01',
            'first_name' => 'Pip',
            'last_name' => 'Pirrip',
            'gender' => 'male',
            'status' => 'active',
        ]);
        $student->delete();

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.students.index', ['status' => 'trashed']));

        $response->assertStatus(200);
        $response->assertSeeText('Pip Pirrip');
        $response->assertSeeText('ADM-TRASHED-01');
        $response->assertSeeText('Viewing Archived / Trashed Student Profiles');
    }

    /**
     * Test restoring soft-deleted student and enrollments.
     */
    public function test_restore_soft_deleted_student_and_enrollments(): void
    {
        $student = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-RESTORE-01',
            'admission_date' => '2026-01-01',
            'first_name' => 'David',
            'last_name' => 'Copperfield',
            'gender' => 'male',
            'status' => 'active',
        ]);

        $enrollment = StudentEnrollment::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'student_id' => $student->id,
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'section_id' => $this->section->id,
            'status' => 'current',
        ]);

        $student->enrollments()->delete();
        $student->delete();

        $this->assertSoftDeleted('students', ['id' => $student->id]);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.students.restore', $student));

        $response->assertStatus(302);
        $this->assertNotSoftDeleted('students', ['id' => $student->id]);
        $this->assertNotSoftDeleted('student_enrollments', ['id' => $enrollment->id]);
    }

    /**
     * Test permanent purge (force delete) removes student, detaches guardians, and purges enrollments.
     */
    public function test_force_delete_purges_student_from_database(): void
    {
        $student = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-PURGE-01',
            'admission_date' => '2026-01-01',
            'first_name' => 'Ebenezer',
            'last_name' => 'Scrooge',
            'gender' => 'male',
            'status' => 'active',
        ]);

        $guardian = Guardian::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'name' => 'Jacob Marley',
            'phone' => '+15550001111',
        ]);

        $student->guardians()->attach($guardian->id, [
            'school_id' => $this->school->id,
            'relationship' => 'guardian',
            'is_primary' => true,
        ]);

        $enrollment = StudentEnrollment::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'student_id' => $student->id,
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'section_id' => $this->section->id,
            'status' => 'current',
        ]);

        $student->delete();

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.students.force-delete', $student));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
        $this->assertDatabaseMissing('student_enrollments', ['id' => $enrollment->id]);
        $this->assertDatabaseMissing('guardian_student', ['student_id' => $student->id]);
    }

    /**
     * Test bulk soft delete moves multiple selected students to trash.
     */
    public function test_bulk_soft_delete_action(): void
    {
        $s1 = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-BULK-01',
            'admission_date' => '2026-01-01',
            'first_name' => 'Bulk Student One',
            'gender' => 'male',
            'status' => 'active',
        ]);

        $s2 = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-BULK-02',
            'admission_date' => '2026-01-01',
            'first_name' => 'Bulk Student Two',
            'gender' => 'female',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.students.bulk-action'), [
            'action' => 'delete',
            'ids' => [$s1->id, $s2->id],
        ]);

        $response->assertStatus(302);
        $this->assertSoftDeleted('students', ['id' => $s1->id]);
        $this->assertSoftDeleted('students', ['id' => $s2->id]);
    }

    /**
     * Test bulk restore restores multiple selected students from trash.
     */
    public function test_bulk_restore_action(): void
    {
        $s1 = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-BREST-01',
            'admission_date' => '2026-01-01',
            'first_name' => 'Restorable One',
            'gender' => 'male',
            'status' => 'active',
        ]);
        $s1->delete();

        $s2 = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-BREST-02',
            'admission_date' => '2026-01-01',
            'first_name' => 'Restorable Two',
            'gender' => 'female',
            'status' => 'active',
        ]);
        $s2->delete();

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.students.bulk-action'), [
            'action' => 'restore',
            'ids' => [$s1->id, $s2->id],
        ]);

        $response->assertStatus(302);
        $this->assertNotSoftDeleted('students', ['id' => $s1->id]);
        $this->assertNotSoftDeleted('students', ['id' => $s2->id]);
    }

    /**
     * Test bulk force delete permanently purges multiple selected students.
     */
    public function test_bulk_force_delete_action(): void
    {
        $s1 = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-BPURGE-01',
            'admission_date' => '2026-01-01',
            'first_name' => 'Purgeable One',
            'gender' => 'male',
            'status' => 'active',
        ]);
        $s1->delete();

        $s2 = Student::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-BPURGE-02',
            'admission_date' => '2026-01-01',
            'first_name' => 'Purgeable Two',
            'gender' => 'female',
            'status' => 'active',
        ]);
        $s2->delete();

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.students.bulk-action'), [
            'action' => 'force_delete',
            'ids' => [$s1->id, $s2->id],
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('students', ['id' => $s1->id]);
        $this->assertDatabaseMissing('students', ['id' => $s2->id]);
    }
}
