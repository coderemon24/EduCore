<?php

namespace Tests\Feature;

use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendPagesRenderTest extends TestCase
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

    public function test_all_public_frontend_pages_render_with_status_200_and_max_w_7xl(): void
    {
        $routes = [
            'frontend.home',
            'frontend.about',
            'frontend.notices.index',
            'frontend.teachers.index',
            'frontend.academic.classes',
            'frontend.academic.routine',
            'frontend.contact',
        ];

        foreach ($routes as $routeName) {
            $response = $this->get(route($routeName));
            $response->assertStatus(200);
            $response->assertSee('max-w-7xl');
        }
    }
}
