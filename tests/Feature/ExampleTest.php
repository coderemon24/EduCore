<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Institutional Overview');
    }

    public function test_root_redirects_to_admin_dashboard(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/admin/dashboard');
    }
}
