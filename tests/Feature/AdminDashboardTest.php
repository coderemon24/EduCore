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

    /**
     * Test clean minimalist navbar and modular script inclusion.
     */
    public function test_admin_layout_uses_modular_scripts_and_clean_navbar(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);

        // Assert modular JS files are linked
        $response->assertSee('admin-assets/js/admin-sidebar.js', false);
        $response->assertSee('admin-assets/js/admin-search.js', false);
        $response->assertSee('admin-assets/js/admin-actions.js', false);

        // Assert clean navbar elements are present
        $response->assertSee('global-search-input', false);
        $response->assertSee('quick-action-menu-btn', false);
        $response->assertSee('btn-notifications', false);
        $response->assertSee('user-profile-menu-btn', false);

        // Assert AI slop and clutter are absent from navbar
        $response->assertDontSee('Academic Year 2026-2027', false);
        $response->assertDontSee('animate-ping', false);
    }
}
