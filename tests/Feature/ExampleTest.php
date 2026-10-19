<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Institutional Overview');
    }

    public function test_root_renders_frontend_portal(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('সোনাতলা উচ্চ বিদ্যালয়');
    }
}
