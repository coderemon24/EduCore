<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin root redirects to dashboard for authenticated administrator.
     */
    public function test_admin_root_redirects_to_dashboard(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin');

        $response->assertRedirect('/admin/dashboard');
    }

    /**
     * Test admin dashboard page renders successfully with telemetry.
     */
    public function test_admin_dashboard_renders_successfully(): void
    {
        $admin = Admin::factory()->create([
            'full_name' => 'Dr. Robert Edwards',
            'designation' => 'Dean of Academic Affairs',
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSeeText('Institutional Overview', false);
        $response->assertSeeText('Total Enrolled Students', false);
        $response->assertSeeText('Teachers', false);
        $response->assertSeeText('Star Students (Academic Merit Roll)', false);
        $response->assertSeeText('Sophia Montgomery', false);
        $response->assertSee('admin-sidebar', false);
        $response->assertSee('sidebar-toggle-btn', false);
        $response->assertSeeText('Dr. Robert Edwards', false);
        $response->assertSeeText('Dean of Academic Affairs', false);
    }

    /**
     * Test clean minimalist navbar and modular script inclusion.
     */
    public function test_admin_layout_uses_modular_scripts_and_clean_navbar(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/dashboard');

        $response->assertStatus(200);

        // Assert modular JS files are linked
        $response->assertSee('admin-assets/js/admin-sidebar.js', false);
        $response->assertSee('admin-assets/js/admin-search.js', false);
        $response->assertSee('admin-assets/js/admin-actions.js', false);

        // Assert clean navbar elements are present
        $response->assertSee('global-search-input', false);
        $response->assertSee('btn-notifications', false);
        $response->assertSee('user-profile-menu-btn', false);
    }

    /**
     * Test admin dashboard layout synchronizes with active school identity.
     */
    public function test_admin_dashboard_syncs_with_active_school_identity(): void
    {
        $admin = Admin::factory()->create();
        $school = School::factory()->create([
            'name' => 'St. Jude International Academy',
            'code' => 'SJA-100',
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSeeText('St. Jude International Academy', false);
        $response->assertSeeText('Schools & Campuses', false);
    }
}
