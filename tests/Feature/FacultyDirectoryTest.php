<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacultyDirectoryTest extends TestCase
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

    public function test_public_teachers_directory_renders_successfully(): void
    {
        $response = $this->get(route('frontend.teachers.index'));

        $response->assertStatus(200);
        $response->assertSee('সোনাতলা উচ্চ বিদ্যালয়');
        $response->assertSee('শিক্ষকমণ্ডলী');
        $response->assertSee('প্রধান শিক্ষক');
    }

    public function test_public_teachers_directory_displays_dynamic_staff_records(): void
    {
        Staff::create([
            'school_id' => $this->school->id,
            'staff_code' => 'TCH-001',
            'first_name' => 'আফজাল',
            'last_name' => 'হোসেন',
            'gender' => 'male',
            'joining_date' => '2020-01-01',
            'designation' => 'সহকারী শিক্ষক (পদার্থবিজ্ঞান)',
            'department' => 'বিজ্ঞান বিভাগ',
            'qualification' => 'এম.এসসি (পদার্থবিজ্ঞান)',
            'phone' => '+৮৮০ ১৭১১-২২৩৩৪৪',
            'email' => 'afzal@sonatala.edu.bd',
            'is_teaching' => true,
            'status' => 'active',
        ]);

        $response = $this->get(route('frontend.teachers.index'));

        $response->assertStatus(200);
        $response->assertSee('আফজাল হোসেন');
        $response->assertSee('সহকারী শিক্ষক (পদার্থবিজ্ঞান)');
        $response->assertSee('বিজ্ঞান বিভাগ');
    }

    public function test_public_teachers_directory_filters_by_department(): void
    {
        Staff::create([
            'school_id' => $this->school->id,
            'staff_code' => 'TCH-002',
            'first_name' => 'কামরুল',
            'last_name' => 'হাসান',
            'gender' => 'male',
            'joining_date' => '2020-01-01',
            'designation' => 'সহকারী শিক্ষক (গণিত)',
            'department' => 'বিজ্ঞান বিভাগ',
            'qualification' => 'বি.এসসি (গণিত)',
            'is_teaching' => true,
            'status' => 'active',
        ]);

        Staff::create([
            'school_id' => $this->school->id,
            'staff_code' => 'TCH-003',
            'first_name' => 'আনিসুর',
            'last_name' => 'রহমান',
            'gender' => 'male',
            'joining_date' => '2020-01-01',
            'designation' => 'সহকারী শিক্ষক (ইতিহাস)',
            'department' => 'মানবিক বিভাগ',
            'qualification' => 'এম.এ (ইতিহাস)',
            'is_teaching' => true,
            'status' => 'active',
        ]);

        $response = $this->get(route('frontend.teachers.index', ['department' => 'বিজ্ঞান বিভাগ']));

        $response->assertStatus(200);
        $response->assertSee('কামরুল হাসান');
        $response->assertDontSee('আনিসুর রহমান');
    }

    public function test_public_teachers_directory_searches_by_keyword(): void
    {
        Staff::create([
            'school_id' => $this->school->id,
            'staff_code' => 'TCH-004',
            'first_name' => 'মোজাম্মেল',
            'last_name' => 'হক',
            'gender' => 'male',
            'joining_date' => '2020-01-01',
            'designation' => 'সহকারী শিক্ষক (ভূগোল)',
            'department' => 'সামাজিক বিজ্ঞান',
            'qualification' => 'এম.এ (ভূগোল)',
            'is_teaching' => true,
            'status' => 'active',
        ]);

        $response = $this->get(route('frontend.teachers.index', ['q' => 'মোজাম্মেল']));

        $response->assertStatus(200);
        $response->assertSee('মোজাম্মেল হক');

        $emptyResponse = $this->get(route('frontend.teachers.index', ['q' => 'অবাস্তব_নাম']));
        $emptyResponse->assertStatus(200);
    }
}
