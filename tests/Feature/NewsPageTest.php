<?php

namespace Tests\Feature;

use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsPageTest extends TestCase
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
     * Test news index route returns 200 HTTP response.
     */
    public function test_news_index_route_is_accessible_and_returns_200(): void
    {
        $response = $this->get(route('frontend.news.index'));

        $response->assertStatus(200);
        $response->assertViewIs('frontend.news.index');
        $response->assertViewHas(['school', 'newsList', 'categoriesWithCounts', 'recentNews']);
    }

    /**
     * Test institutional brand, breadcrumb and hero banner render correctly.
     */
    public function test_news_index_renders_brand_and_hero_banner(): void
    {
        $response = $this->get(route('frontend.news.index'));

        $response->assertStatus(200);
        $response->assertSee('সোনাতলা উচ্চ বিদ্যালয়');
        $response->assertSee('সংবাদ ও মিডিয়া');
        $response->assertSee('max-w-7xl');
    }

    /**
     * Test category filter properly isolates stories in that category.
     */
    public function test_news_category_filter_isolates_matching_category(): void
    {
        $response = $this->get(route('frontend.news.index', ['category' => 'science']));

        $response->assertStatus(200);
        $response->assertViewHas('activeCategory', 'science');
        $response->assertSee('বিজ্ঞান ও উদ্ভাবন');
    }

    /**
     * Test invalid category filter gracefully displays all stories without breaking.
     */
    public function test_news_category_filter_with_invalid_category_handles_gracefully(): void
    {
        $response = $this->get(route('frontend.news.index', ['category' => 'non-existent-category']));

        $response->assertStatus(200);
        $response->assertViewHas('activeCategory', 'non-existent-category');
    }

    /**
     * Test keyword search accurately filters news results.
     */
    public function test_news_search_filters_by_query_string(): void
    {
        $response = $this->get(route('frontend.news.index', ['q' => 'রোবটিক্স']));

        $response->assertStatus(200);
        $response->assertViewHas('searchQuery', 'রোবটিক্স');
        $response->assertSee('স্টেম অলিম্পিয়াড');
    }

    /**
     * Adversarial Test: malicious SQL strings, scripts, or whitespace in search query do not break.
     */
    public function test_news_search_adversarial_queries_do_not_throw_exceptions(): void
    {
        $adversarialQueries = [
            "' OR 1=1 --",
            '<script>alert("xss")</script>',
            "   ",
            "%",
            "\\",
            "SELECT * FROM users",
            str_repeat('A', 500),
        ];

        foreach ($adversarialQueries as $query) {
            $response = $this->get(route('frontend.news.index', ['q' => $query]));
            $response->assertStatus(200);
        }
    }

    /**
     * Test news show view returns 200 for valid article slug.
     */
    public function test_news_show_page_returns_200_for_valid_slug(): void
    {
        $validSlug = 'national-stem-olympiad-championship-2026';
        $response = $this->get(route('frontend.news.show', ['slug' => $validSlug]));

        $response->assertStatus(200);
        $response->assertViewIs('frontend.news.show');
        $response->assertViewHas(['school', 'news', 'relatedNews', 'recentNews']);
        $response->assertSee('জাতীয় বিজ্ঞান ও স্টেম অলিম্পিয়াডে সোনাতলা উচ্চ বিদ্যালয়ের প্রথম স্থান অর্জন');
        $response->assertSee('বিজ্ঞান ও প্রযুক্তি ক্লাব');
    }

    /**
     * Test news show view returns 404 for invalid or non-existent article slug.
     */
    public function test_news_show_page_returns_404_for_invalid_slug(): void
    {
        $response = $this->get(route('frontend.news.show', ['slug' => 'completely-bogus-slug-404']));

        $response->assertStatus(404);
    }

    /**
     * Test news show page renders related news and meta info.
     */
    public function test_news_show_page_renders_related_news_and_meta(): void
    {
        $validSlug = 'sheikh-russel-digital-lab-modernization-2026';
        $response = $this->get(route('frontend.news.show', ['slug' => $validSlug]));

        $response->assertStatus(200);
        $response->assertSee('ক্যাম্পাস ও উন্নয়ন');
        $response->assertSee('ডিজিটাল কম্পিউটার ল্যাব');
    }
}
