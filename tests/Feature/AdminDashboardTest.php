<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    /**
     * Test admin root redirects to dashboard.
     */
    public function test_admin_root_redirects_to_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/dashboard');
    }

    /**
     * Test admin dashboard page renders successfully with telemetry.
     */
    public function test_admin_dashboard_renders_successfully(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSeeText('Institutional Overview', false);
        $response->assertSeeText('Total Enrolled Students', false);
        $response->assertSeeText('Teachers', false);
        $response->assertSeeText('Star Students (Academic Merit Roll)', false);
        $response->assertSeeText('Sophia Montgomery', false);
        $response->assertSee('admin-sidebar', false);
        $response->assertSee('sidebar-toggle-btn', false);
    }
}
