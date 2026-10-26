<?php

namespace Tests\Feature;

use App\Models\AcademicHoliday;
use App\Models\AcademicYear;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicHolidayPageTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected AcademicYear $academicYear;

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

        $this->academicYear = AcademicYear::create([
            'school_id' => $this->school->id,
            'name' => '2025–2026',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'is_current' => true,
            'status' => 'active',
        ]);
    }

    /**
     * Test public academic holidays page renders successfully.
     */
    public function test_public_academic_holidays_page_renders_successfully(): void
    {
        $response = $this->get(route('frontend.academic.holidays'));

        $response->assertStatus(200);
        $response->assertViewIs('frontend.academic.holidays');
        $response->assertViewHas(['school', 'academicYears', 'selectedYear', 'holidays', 'calendarEvents', 'telemetry']);
    }

    /**
     * Test public academic holidays displays scheduled holidays with proper reason.
     */
    public function test_public_academic_holidays_page_displays_holidays_with_proper_reason(): void
    {
        $holiday = AcademicHoliday::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
            'title' => 'জাতীয় শোক দিবস ও বিশেষ প্রার্থনা',
            'start_date' => '2025-08-15',
            'end_date' => '2025-08-15',
            'type' => 'holiday',
            'description' => 'জাতীয় শোক দিবস উপলক্ষে সকল শ্রেণি ও প্রশাসনিক কার্যক্রম স্থগিত থাকবে। বিশেষ মিলাদ ও দোয়ার আয়োজন করা হবে।',
        ]);

        $response = $this->get(route('frontend.academic.holidays', [
            'academic_year_id' => $this->academicYear->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('জাতীয় শোক দিবস ও বিশেষ প্রার্থনা');
        $response->assertSee('জাতীয় শোক দিবস উপলক্ষে সকল শ্রেণি ও প্রশাসনিক কার্যক্রম স্থগিত থাকবে');
    }

    /**
     * Test calendar JSON payload contains structured date spans and details.
     */
    public function test_calendar_json_payload_contains_correct_date_spans_and_details(): void
    {
        $holiday = AcademicHoliday::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
            'title' => 'গ্রীষ্মকালীন অবকাশ',
            'start_date' => '2025-06-01',
            'end_date' => '2025-06-10',
            'type' => 'holiday',
            'description' => 'গ্রীষ্মকালীন ছুটি উপলক্ষে শিক্ষাপ্রতিষ্ঠান ১০ দিন বন্ধ থাকবে।',
        ]);

        $response = $this->get(route('frontend.academic.holidays'));

        $response->assertStatus(200);
        $events = $response->viewData('calendarEvents');
        $this->assertIsArray($events);

        $matched = collect($events)->firstWhere('title', 'গ্রীষ্মকালীন অবকাশ');
        $this->assertNotNull($matched);
        $this->assertEquals('2025-06-01', $matched['start_date']);
        $this->assertEquals('2025-06-10', $matched['end_date']);
        $this->assertEquals(10, $matched['duration_days']);
        $this->assertEquals('গ্রীষ্মকালীন ছুটি উপলক্ষে শিক্ষাপ্রতিষ্ঠান ১০ দিন বন্ধ থাকবে।', $matched['description']);
    }

    /**
     * Test academic year filtering works accurately.
     */
    public function test_academic_year_filtering_works_correctly(): void
    {
        $year2026 = AcademicYear::withoutGlobalScopes()->updateOrCreate(
            ['school_id' => $this->school->id, 'name' => '2026–2027'],
            [
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'is_current' => false,
                'status' => 'inactive',
            ]
        );

        $h2025 = AcademicHoliday::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
            'title' => 'অনন্য ২০২৫ ছুটি',
            'start_date' => '2025-05-01',
            'end_date' => '2025-05-01',
            'type' => 'holiday',
            'description' => '২০২৫ সালের জন্য প্রযোজ্য ছুটি।',
        ]);

        $h2026 = AcademicHoliday::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'academic_year_id' => $year2026->id,
            'title' => 'অনন্য ২০২৬ ছুটি',
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-01',
            'type' => 'holiday',
            'description' => '২০২৬ সালের জন্য প্রযোজ্য ছুটি।',
        ]);

        // Querying year 2026 should display h2026 and not h2025
        $response = $this->get(route('frontend.academic.holidays', [
            'academic_year_id' => $year2026->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('অনন্য ২০২৬ ছুটি');
        $response->assertDontSee('অনন্য ২০২৫ ছুটি');
    }

    /**
     * Test holiday events JSON endpoint returns formatted data.
     */
    public function test_holiday_events_json_endpoint_returns_events_for_session(): void
    {
        $response = $this->getJson(route('frontend.academic.holidays.events', [
            'academic_year_id' => $this->academicYear->id,
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'count',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'start_date',
                    'end_date',
                    'type',
                    'type_label',
                    'duration_days',
                    'description',
                ],
            ],
        ]);
    }

    /**
     * Test empty state renders cleanly when an academic session has no holidays.
     */
    public function test_empty_state_renders_cleanly_when_no_holidays_exist(): void
    {
        $emptyYear = AcademicYear::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'name' => '2030–2031 (Empty Session)',
            'start_date' => '2030-01-01',
            'end_date' => '2030-12-31',
            'is_current' => false,
            'status' => 'inactive',
        ]);

        $response = $this->get(route('frontend.academic.holidays', [
            'academic_year_id' => $emptyYear->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('কোনো নির্ধারিত ছুটি পাওয়া যায়নি');
        $this->assertEquals(0, $response->viewData('telemetry')['total_events']);
    }

    /**
     * Test tenant boundary prevents holiday leaks across different schools.
     */
    public function test_tenant_isolation_prevents_holiday_crossover_between_schools(): void
    {
        $otherSchool = School::withoutGlobalScopes()->create([
            'name' => 'Foreign School Campus',
            'code' => 'FOR-999',
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

        $foreignHoliday = AcademicHoliday::withoutGlobalScopes()->create([
            'school_id' => $otherSchool->id,
            'academic_year_id' => $otherYear->id,
            'title' => 'গোপন দ্বিতীয় ক্যাম্পাসের ছুটি',
            'start_date' => '2025-07-20',
            'end_date' => '2025-07-20',
            'type' => 'holiday',
            'description' => 'এই ছুটি অন্য স্কুলের এবং প্রধান স্কুলে প্রদর্শিত হবে না।',
        ]);

        // Main school request
        $response = $this->get(route('frontend.academic.holidays', [
            'academic_year_id' => $this->academicYear->id,
        ]));

        $response->assertStatus(200);
        $response->assertDontSee('গোপন দ্বিতীয় ক্যাম্পাসের ছুটি');
    }

    /**
     * Test adversarial invalid academic year ID falls back gracefully without error.
     */
    public function test_adversarial_invalid_year_id_falls_back_gracefully(): void
    {
        $response = $this->get(route('frontend.academic.holidays', [
            'academic_year_id' => 99999999,
        ]));

        $response->assertStatus(200);
        $this->assertNotNull($response->viewData('selectedYear'));
    }

    /**
     * Test cross-month date span duration is calculated accurately.
     */
    public function test_cross_month_and_multi_day_holiday_duration_calculation(): void
    {
        $crossMonthHoliday = AcademicHoliday::withoutGlobalScopes()->create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->academicYear->id,
            'title' => 'মার্চ-এপ্রিল সমন্বিত অবকাশ',
            'start_date' => '2025-03-25',
            'end_date' => '2025-04-05',
            'type' => 'holiday',
            'description' => 'মার্চ থেকে এপ্রিল পর্যন্ত বিস্তৃত ১২ দিনের ছুটি।',
        ]);

        $duration = $crossMonthHoliday->start_date->diffInDays($crossMonthHoliday->end_date) + 1;
        $this->assertEquals(12, $duration);
    }
}
