<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\AcademicGroup;
use App\Models\AcademicYear;
use App\Models\Admin;
use App\Models\ClassSubjectAssignment;
use App\Models\School;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicStructureTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected School $school;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::factory()->create([
            'name' => 'EduCore Academy',
            'status' => 'active',
        ]);

        app()->instance('current_school_id', $this->school->id);

        $this->admin = Admin::factory()->create([
            'full_name' => 'Principal Skinner',
            'designation' => 'Academic Administrator',
        ]);
    }

    protected function tearDown(): void
    {
        app()->forgetInstance('current_school_id');
        parent::tearDown();
    }

    /**
     * Test admin dashboard sidebar contains the Academic Structure accordion.
     */
    public function test_admin_sidebar_contains_academic_structure_accordion(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSeeText('Academic Structure', false);
        $response->assertSeeText('Academic Years', false);
        $response->assertSeeText('Classes & Grades', false);
        $response->assertSeeText('Sections', false);
        $response->assertSeeText('Subjects Catalog', false);
        $response->assertSeeText('Assign Subjects', false);
        $response->assertSeeText('Groups / Streams', false);
    }

    /**
     * Test academic years CRUD and atomic current year switching.
     */
    public function test_academic_years_crud_and_atomic_switching(): void
    {
        $year1 = AcademicYear::create([
            'school_id' => $this->school->id,
            'name' => '2025-2026',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'is_current' => true,
            'status' => 'active',
        ]);

        $year2 = AcademicYear::create([
            'school_id' => $this->school->id,
            'name' => '2026-2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_current' => false,
            'status' => 'active',
        ]);

        $this->assertTrue($year1->fresh()->is_current);
        $this->assertFalse($year2->fresh()->is_current);

        // Toggle year2 to become current
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.academic.years.set-current', $year2));

        $response->assertRedirect(route('admin.academic.years.index'));

        // Verify atomic switch: year2 is now current, year1 is no longer current
        $this->assertFalse($year1->fresh()->is_current);
        $this->assertTrue($year2->fresh()->is_current);

        // Test duplicate year name in same school is rejected
        $createResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.academic.years.store'), [
                'name' => '2026-2027',
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'status' => 'active',
            ]);

        $createResponse->assertSessionHasErrors(['name']);
    }

    /**
     * Test academic classes CRUD and ordering scope.
     */
    public function test_academic_classes_crud_and_ordering(): void
    {
        $classB = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Grade 10',
            'order_index' => 10,
            'status' => 'active',
        ]);

        $classA = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Grade 1',
            'order_index' => 1,
            'status' => 'active',
        ]);

        $ordered = AcademicClass::ordered()->pluck('name')->toArray();
        $this->assertSame(['Grade 1', 'Grade 10'], $ordered);

        // Attempt duplicate class name in same school
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.academic.classes.store'), [
                'name' => 'Grade 1',
                'order_index' => 2,
                'status' => 'active',
            ]);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Test section uniqueness within a class and school.
     */
    public function test_section_uniqueness_within_class_and_school(): void
    {
        $class1 = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Grade 7',
            'order_index' => 7,
            'status' => 'active',
        ]);

        $class2 = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Grade 8',
            'order_index' => 8,
            'status' => 'active',
        ]);

        $section1 = Section::create([
            'school_id' => $this->school->id,
            'academic_class_id' => $class1->id,
            'name' => 'Section A',
            'capacity' => 40,
            'status' => 'active',
        ]);

        // Same section name in same class should fail
        $duplicateResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.academic.sections.store'), [
                'academic_class_id' => $class1->id,
                'name' => 'Section A',
                'capacity' => 35,
                'status' => 'active',
            ]);

        $duplicateResponse->assertSessionHasErrors(['name']);

        // Same section name in a DIFFERENT class should succeed
        $differentClassResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.academic.sections.store'), [
                'academic_class_id' => $class2->id,
                'name' => 'Section A',
                'capacity' => 35,
                'status' => 'active',
            ]);

        $differentClassResponse->assertRedirect(route('admin.academic.sections.index', ['class_id' => $class2->id]));
        $this->assertDatabaseHas('sections', [
            'school_id' => $this->school->id,
            'academic_class_id' => $class2->id,
            'name' => 'Section A',
        ]);
    }

    /**
     * Test subject catalog and unique subject codes within school.
     */
    public function test_subject_catalog_and_unique_codes(): void
    {
        $subject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'Advanced Physics',
            'code' => 'PHY-201',
            'type' => 'theory',
            'credit_hours' => 3.0,
            'status' => 'active',
        ]);

        // Duplicate code in same school should fail
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.academic.subjects.store'), [
                'name' => 'Physics Honors',
                'code' => 'PHY-201',
                'type' => 'theory',
                'status' => 'active',
            ]);

        $response->assertSessionHasErrors(['code']);
    }

    /**
     * Test curriculum assignment and prevention of cross-school data contamination.
     */
    public function test_curriculum_assignment_and_cross_school_prevention(): void
    {
        $schoolB = School::factory()->create([
            'name' => 'Other Foreign School',
            'status' => 'active',
        ]);

        $classA = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Class 9',
            'order_index' => 9,
            'status' => 'active',
        ]);

        $subjectA = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'English Grammar',
            'code' => 'ENG-101',
            'type' => 'theory',
            'status' => 'active',
        ]);

        $subjectB = Subject::create([
            'school_id' => $schoolB->id,
            'name' => 'Foreign Subject',
            'code' => 'FOR-999',
            'type' => 'theory',
            'status' => 'active',
        ]);

        // Attempting to assign foreign school's subject must fail validation
        $crossSchoolResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.academic.assign-subjects.store'), [
                'academic_class_id' => $classA->id,
                'subject_ids' => [$subjectB->id],
                'type' => 'compulsory',
                'full_marks' => 100,
                'pass_marks' => 33,
            ]);

        $crossSchoolResponse->assertSessionHasErrors(['subject_ids.0']);

        // Assigning valid school subject succeeds
        $validResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.academic.assign-subjects.store'), [
                'academic_class_id' => $classA->id,
                'subject_ids' => [$subjectA->id],
                'type' => 'compulsory',
                'full_marks' => 100,
                'pass_marks' => 33,
            ]);

        $validResponse->assertRedirect(route('admin.academic.assign-subjects.index', ['class_id' => $classA->id]));
        $this->assertDatabaseHas('class_subject_assignments', [
            'school_id' => $this->school->id,
            'academic_class_id' => $classA->id,
            'subject_id' => $subjectA->id,
            'type' => 'compulsory',
        ]);
    }

    /**
     * Test dependent dropdown JSON endpoints.
     */
    public function test_dependent_dropdown_endpoints(): void
    {
        $class = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Class 5',
            'order_index' => 5,
            'status' => 'active',
        ]);

        $section = Section::create([
            'school_id' => $this->school->id,
            'academic_class_id' => $class->id,
            'name' => 'Lily',
            'capacity' => 30,
            'status' => 'active',
        ]);

        $subject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'General Science',
            'code' => 'SCI-05',
            'type' => 'theory',
            'status' => 'active',
        ]);

        ClassSubjectAssignment::create([
            'school_id' => $this->school->id,
            'academic_class_id' => $class->id,
            'subject_id' => $subject->id,
            'type' => 'compulsory',
            'full_marks' => 100,
            'pass_marks' => 40,
        ]);

        // Get sections JSON
        $sectionsResponse = $this->actingAs($this->admin, 'admin')
            ->getJson("/admin/academic/classes/{$class->id}/sections");

        $sectionsResponse->assertStatus(200);
        $sectionsResponse->assertJsonFragment([
            'id' => $section->id,
            'name' => 'Lily',
        ]);

        // Get subjects JSON
        $subjectsResponse = $this->actingAs($this->admin, 'admin')
            ->getJson("/admin/academic/classes/{$class->id}/subjects");

        $subjectsResponse->assertStatus(200);
        $subjectsResponse->assertJsonFragment([
            'id' => $subject->id,
            'name' => 'General Science',
        ]);
    }

    /**
     * Test academic groups and streams CRUD.
     */
    public function test_academic_groups_streams_crud(): void
    {
        $group = AcademicGroup::create([
            'school_id' => $this->school->id,
            'name' => 'Science Stream',
            'code' => 'SCI',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('academic_groups', [
            'id' => $group->id,
            'name' => 'Science Stream',
            'school_id' => $this->school->id,
        ]);

        // Duplicate group name in same school should fail
        $duplicateResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.academic.groups.store'), [
                'name' => 'Science Stream',
                'code' => 'SCI2',
                'status' => 'active',
            ]);

        $duplicateResponse->assertSessionHasErrors(['name']);

        // Delete group
        $deleteResponse = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.academic.groups.destroy', $group));

        $deleteResponse->assertRedirect(route('admin.academic.groups.index'));
        $this->assertDatabaseMissing('academic_groups', [
            'id' => $group->id,
        ]);
    }
}
