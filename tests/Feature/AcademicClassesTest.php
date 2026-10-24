<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\School;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicClassesTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'সোনাতলা উচ্চ বিদ্যালয়',
            'slug' => 'sonatala-high-school',
            'code' => '৮১০৭',
            'status' => 'active',
            'email' => 'sonatala@test.com',
            'established_year' => 1968,
        ]);
    }

    public function test_public_academic_classes_page_renders_successfully(): void
    {
        $response = $this->get(route('frontend.academic.classes'));

        $response->assertStatus(200);
        $response->assertViewIs('frontend.academic.classes');
        $response->assertSee('শ্রেণিসমূহ ও পাঠ্যসূচি');
        $response->assertSee('সোনাতলা উচ্চ বিদ্যালয়');
    }

    public function test_public_academic_classes_displays_classes_sections_and_subjects(): void
    {
        $class = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'অষ্টম শ্রেণি (Class 8)',
            'numeric_code' => 8,
            'order_index' => 3,
            'status' => 'active',
        ]);

        $teacher = Staff::create([
            'school_id' => $this->school->id,
            'staff_code' => 'TCH-008',
            'first_name' => 'মোঃ আনিসুর',
            'last_name' => 'রহমান',
            'gender' => 'male',
            'joining_date' => '2021-01-01',
            'designation' => 'সিনিয়র শিক্ষক',
            'department' => 'গণিত বিভাগ',
            'qualification' => 'বি.এসসি (অনার্স), এম.এসসি',
            'phone' => '+৮৮০ ১৭১১-১২৩৪৫৬',
            'email' => 'anisur@sonatala.edu.bd',
            'is_teaching' => true,
            'status' => 'active',
        ]);

        $section = Section::create([
            'school_id' => $this->school->id,
            'academic_class_id' => $class->id,
            'class_teacher_id' => $teacher->id,
            'name' => 'ক',
            'capacity' => 60,
            'room_number' => '২০৪',
            'status' => 'active',
        ]);

        $subject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'বাংলা ১ম পত্র',
            'code' => '১০১',
            'type' => 'theory',
            'status' => 'active',
        ]);

        $class->subjects()->attach($subject->id, [
            'school_id' => $this->school->id,
            'type' => 'compulsory',
            'full_marks' => 100,
            'pass_marks' => 33,
        ]);

        $response = $this->get(route('frontend.academic.classes'));

        $response->assertStatus(200);
        $response->assertSee('অষ্টম শ্রেণি (Class 8)');
        $response->assertSee('শাখা: ক');
        $response->assertSee('মোঃ আনিসুর রহমান');
        $response->assertSee('বাংলা ১ম পত্র');
        $response->assertSee('আবশ্যিক');
    }

    public function test_academic_classes_supports_class_id_query_filter(): void
    {
        $class1 = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'ষষ্ঠ শ্রেণি',
            'numeric_code' => 6,
            'order_index' => 1,
            'status' => 'active',
        ]);

        $class2 = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'নবম শ্রেণি',
            'numeric_code' => 9,
            'order_index' => 4,
            'status' => 'active',
        ]);

        $response = $this->get(route('frontend.academic.classes', ['class_id' => $class2->id]));

        $response->assertStatus(200);
        $response->assertViewHas('selectedClassId', $class2->id);
    }

    public function test_academic_classes_renders_clean_empty_state_when_no_classes_exist(): void
    {
        $response = $this->get(route('frontend.academic.classes'));

        $response->assertStatus(200);
        $response->assertSee('বর্তমানে কোনো শ্রেণির তথ্য উপলব্ধ নেই');
    }

    public function test_navbar_dropdown_includes_academic_classes_link(): void
    {
        $response = $this->get(route('frontend.home'));

        $response->assertStatus(200);
        $response->assertSee(route('frontend.academic.classes'));
        $response->assertSee('শ্রেণিসমূহ');
    }
}
