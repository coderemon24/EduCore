<?php

namespace Tests\Feature;

use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventPageTest extends TestCase
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
            'established_year' => 1968,
        ]);
    }

    /**
     * Test events index route returns 200 HTTP response with required view data.
     */
    public function test_events_index_route_is_accessible_and_returns_200(): void
    {
        $response = $this->get(route('frontend.events.index'));

        $response->assertStatus(200);
        $response->assertViewIs('frontend.events.index');
        $response->assertViewHas([
            'school',
            'eventsList',
            'categoriesWithCounts',
            'statusCounts',
            'upcomingHighlights',
            'activeCategory',
            'activeStatus',
            'searchQuery',
        ]);
    }

    /**
     * Test brand, breadcrumbs, and hero banner render correctly.
     */
    public function test_events_index_renders_brand_and_hero_banner(): void
    {
        $response = $this->get(route('frontend.events.index'));

        $response->assertStatus(200);
        $response->assertSee('সোনাতলা উচ্চ বিদ্যালয়');
        $response->assertSee('ইভেন্ট ও আয়োজন');
        $response->assertSee('বাৎসরিক ইভেন্ট ক্যালেন্ডার');
    }

    /**
     * Test status filter properly filters upcoming events.
     */
    public function test_events_status_filter_isolates_upcoming_events(): void
    {
        $response = $this->get(route('frontend.events.index', ['status' => 'upcoming']));

        $response->assertStatus(200);
        $response->assertViewHas('activeStatus', 'upcoming');
        $response->assertSee('বার্ষিক ক্রীড়া প্রতিযোগিতা');
    }

    /**
     * Test status filter properly filters completed events.
     */
    public function test_events_status_filter_isolates_completed_events(): void
    {
        $response = $this->get(route('frontend.events.index', ['status' => 'completed']));

        $response->assertStatus(200);
        $response->assertViewHas('activeStatus', 'completed');
        $response->assertSee('মাতৃভাষা দিবস');
    }

    /**
     * Test category filter properly isolates matching category.
     */
    public function test_events_category_filter_isolates_matching_category(): void
    {
        $response = $this->get(route('frontend.events.index', ['category' => 'sports']));

        $response->assertStatus(200);
        $response->assertViewHas('activeCategory', 'sports');
        $response->assertSee('ক্রীড়া ও সহশিক্ষা');
    }

    /**
     * Test invalid category filter gracefully displays results without breaking.
     */
    public function test_events_category_filter_with_invalid_category_handles_gracefully(): void
    {
        $response = $this->get(route('frontend.events.index', ['category' => 'invalid-category-name']));

        $response->assertStatus(200);
        $response->assertViewHas('activeCategory', 'invalid-category-name');
    }

    /**
     * Test keyword search accurately filters event results.
     */
    public function test_events_search_filters_by_query_string(): void
    {
        $response = $this->get(route('frontend.events.index', ['q' => 'রোবটিক্স']));

        $response->assertStatus(200);
        $response->assertViewHas('searchQuery', 'রোবটিক্স');
        $response->assertSee('বিজ্ঞান মেলা, রোবটিক্স');
    }

    /**
     * Adversarial Test: malicious SQL strings, scripts, or whitespace in search query do not break.
     */
    public function test_events_search_adversarial_queries_do_not_throw_exceptions(): void
    {
        $adversarialQueries = [
            "' OR 1=1 --",
            '<script>alert("xss")</script>',
            "   ",
            "%",
            "\\",
            "UNION SELECT null, username, password FROM users --",
            str_repeat('X', 500),
        ];

        foreach ($adversarialQueries as $query) {
            $response = $this->get(route('frontend.events.index', ['q' => $query]));
            $response->assertStatus(200);
        }
    }

    /**
     * Test single event show view returns 200 for valid event slug.
     */
    public function test_events_show_page_returns_200_for_valid_slug(): void
    {
        $validSlug = 'annual-sports-competition-2026';
        $response = $this->get(route('frontend.events.show', ['slug' => $validSlug]));

        $response->assertStatus(200);
        $response->assertViewIs('frontend.events.show');
        $response->assertViewHas(['school', 'event', 'relatedEvents', 'upcomingEvents']);
        $response->assertSee('৫৮তম বার্ষিক ক্রীড়া প্রতিযোগিতা');
        $response->assertSee('বিদ্যালয় কেন্দ্রীয় খেলার মাঠ');
    }

    /**
     * Test single event show view returns 404 for invalid or non-existent event slug.
     */
    public function test_events_show_page_returns_404_for_invalid_slug(): void
    {
        $response = $this->get(route('frontend.events.show', ['slug' => 'non-existent-event-slug-404']));

        $response->assertStatus(404);
    }

    /**
     * Test single event show page renders program schedule and metadata.
     */
    public function test_events_show_page_renders_schedule_and_meta(): void
    {
        $validSlug = 'annual-sports-competition-2026';
        $response = $this->get(route('frontend.events.show', ['slug' => $validSlug]));

        $response->assertStatus(200);
        $response->assertSee('জাতীয় সংগীত ও মশাল প্রজ্বালন');
        $response->assertSee('ট্র্যাক অ্যান্ড ফিল্ড');
        $response->assertSee('ক্রীড়া ও শারীরিক শিক্ষা কমিটি');
    }
}
