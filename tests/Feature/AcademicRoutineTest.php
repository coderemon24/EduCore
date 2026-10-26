<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\TimetableSlot;
use Database\Seeders\AcademicSeeder;
use Database\Seeders\ClassRoutineSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SchoolSeeder;
use Database\Seeders\StaffSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AcademicRoutineTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;
    protected AcademicYear $year;
    protected AcademicClass $class6;
    protected AcademicClass $class9;
    protected Section $sectionA;
    protected Section $sectionB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            SchoolSeeder::class,
            RolePermissionSeeder::class,
            StaffSeeder::class,
            AcademicSeeder::class,
            ClassRoutineSeeder::class,
        ]);

        $this->school = School::first();
        $this->year = AcademicYear::withoutGlobalScopes()->where('school_id', $this->school->id)->where('is_current', true)->first();
        $this->class6 = AcademicClass::withoutGlobalScopes()->where('school_id', $this->school->id)->where('numeric_code', 6)->first();
        $this->class9 = AcademicClass::withoutGlobalScopes()->where('school_id', $this->school->id)->where('numeric_code', 9)->first();
        $this->sectionA = Section::withoutGlobalScopes()->where('academic_class_id', $this->class6->id)->first();
        $this->sectionB = Section::withoutGlobalScopes()->where('academic_class_id', $this->class6->id)->orderByDesc('id')->first();
    }

    public function test_routine_page_renders_successfully(): void
    {
        $response = $this->get(route('frontend.academic.routine'));

        $response->assertStatus(200);
        $response->assertSee('ক্লাস ও পরীক্ষার সময়সূচি');
    }

    public function test_routine_filters_by_class_id(): void
    {
        // Request Grade 6 routine
        $response6 = $this->get(route('frontend.academic.routine', [
            'class_id' => $this->class6->id,
            'year_id' => $this->year->id,
        ]));
        $response6->assertStatus(200);
        $response6->assertViewHas('selectedClass', fn ($c) => $c->id === $this->class6->id);

        // Request Grade 9 routine
        $response9 = $this->get(route('frontend.academic.routine', [
            'class_id' => $this->class9->id,
            'year_id' => $this->year->id,
        ]));
        $response9->assertStatus(200);
        $response9->assertViewHas('selectedClass', fn ($c) => $c->id === $this->class9->id);

        // Grade 9 has science electives (Physics, Chemistry)
        $slots9 = $response9->viewData('timetableSlots');
        $this->assertTrue($slots9->contains(fn ($s) => in_array($s->subject->code, ['PHY-201', 'CHM-201'])));
    }

    public function test_routine_filters_by_academic_year(): void
    {
        $response = $this->get(route('frontend.academic.routine', [
            'year_id' => $this->year->id,
            'class_id' => $this->class6->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('selectedYear', fn ($y) => $y->id === $this->year->id);
    }

    public function test_routine_filters_by_section(): void
    {
        $response = $this->get(route('frontend.academic.routine', [
            'class_id' => $this->class6->id,
            'section_id' => $this->sectionA->id,
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('selectedSection', fn ($s) => $s->id === $this->sectionA->id);

        $slots = $response->viewData('timetableSlots');
        foreach ($slots as $slot) {
            $this->assertEquals($this->sectionA->id, $slot->section_id);
        }
    }

    public function test_routine_filters_by_day_of_week(): void
    {
        $response = $this->get(route('frontend.academic.routine', [
            'class_id' => $this->class6->id,
            'day' => 'saturday',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('selectedDay', 'saturday');

        $slots = $response->viewData('timetableSlots');
        foreach ($slots as $slot) {
            $this->assertEquals('saturday', $slot->day_of_week);
        }
    }

    public function test_routine_handles_adversarial_invalid_inputs_gracefully(): void
    {
        // Non-existent class, year, and invalid day string
        $response = $this->get(route('frontend.academic.routine', [
            'class_id' => 999999,
            'year_id' => 999999,
            'section_id' => 999999,
            'day' => 'invalid_day_name_123',
        ]));

        // Must NOT throw 500 error, must fallback safely
        $response->assertStatus(200);
        $response->assertViewHas('selectedDay', 'all');
    }

    public function test_routine_tenant_isolation_prevents_cross_school_access(): void
    {
        // Create second school and slot
        $otherSchool = School::withoutGlobalScopes()->create([
            'name' => 'Secondary Test Academy',
            'slug' => 'secondary-test-academy',
            'code' => 'SCH-9999',
            'status' => 'active',
        ]);

        $otherYear = AcademicYear::withoutGlobalScopes()->create([
            'school_id' => $otherSchool->id,
            'name' => '2025–2026',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'is_current' => true,
            'status' => 'active',
        ]);

        $otherClass = AcademicClass::withoutGlobalScopes()->create([
            'school_id' => $otherSchool->id,
            'name' => 'Class X',
            'numeric_code' => 10,
            'status' => 'active',
        ]);

        $otherSection = Section::withoutGlobalScopes()->create([
            'school_id' => $otherSchool->id,
            'academic_class_id' => $otherClass->id,
            'name' => 'Section Secret',
            'status' => 'active',
        ]);

        $otherSubject = Subject::withoutGlobalScopes()->create([
            'school_id' => $otherSchool->id,
            'name' => 'Secret Curriculum',
            'code' => 'SEC-999',
            'type' => 'theory',
            'status' => 'active',
        ]);

        $foreignSlot = TimetableSlot::withoutGlobalScopes()->create([
            'school_id' => $otherSchool->id,
            'academic_year_id' => $otherYear->id,
            'academic_class_id' => $otherClass->id,
            'section_id' => $otherSection->id,
            'subject_id' => $otherSubject->id,
            'day_of_week' => 'saturday',
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'room_number' => 'Secret Room',
        ]);

        // Access routine for primary school
        $response = $this->get(route('frontend.academic.routine', [
            'class_id' => $this->class6->id,
        ]));

        $response->assertStatus(200);
        $slots = $response->viewData('timetableSlots');

        // Confirm primary school slots never contain foreign slot
        $this->assertFalse($slots->contains('id', $foreignSlot->id));
        $response->assertDontSee('Secret Curriculum');
    }

    public function test_routine_eager_loading_prevents_n_plus_one_queries(): void
    {
        DB::enableQueryLog();

        $response = $this->get(route('frontend.academic.routine', [
            'class_id' => $this->class6->id,
            'day' => 'all',
        ]));

        $response->assertStatus(200);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $this->assertLessThan(25, count($queries), 'Potential N+1 detected on routine page query execution.');
    }
}
