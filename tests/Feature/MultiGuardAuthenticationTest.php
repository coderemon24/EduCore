<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MultiGuardAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Springfield High',
            'code' => 'SPH-01',
            'status' => 'active',
        ]);
    }

    /**
     * Test Super Admin authenticates via the 'admin' guard.
     */
    public function test_super_admin_can_authenticate_via_admin_guard(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'superadmin@educore.test',
            'username' => 'superadmin',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        $response = $this->post('/admin/login', [
            'login' => 'superadmin@educore.test',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertGuest('web');
        $response->assertRedirect('/admin/dashboard');

        // Can access dashboard
        $dashResponse = $this->actingAs($admin, 'admin')->get('/admin/dashboard');
        $dashResponse->assertStatus(200);
    }

    /**
     * Test school user authenticates via the 'web' guard with tenant context resolution.
     */
    public function test_school_user_can_authenticate_via_web_guard_with_tenant_resolution(): void
    {
        $role = Role::create([
            'school_id' => $this->school->id,
            'name' => 'School Admin',
            'slug' => 'school_admin',
            'is_system' => true,
        ]);

        $user = User::create([
            'school_id' => $this->school->id,
            'name' => 'Jane Principal',
            'email' => 'principal@springfield.edu',
            'password' => Hash::make('secret456'),
            'status' => 'active',
        ]);
        $user->roles()->sync([$role->id]);

        $response = $this->post('/admin/login', [
            'login' => 'principal@springfield.edu',
            'password' => 'secret456',
        ]);

        $this->assertAuthenticatedAs($user, 'web');
        $this->assertGuest('admin');
        $response->assertRedirect('/admin/dashboard');
        $response->assertSessionHas('current_school_id', $this->school->id);

        // Access dashboard under tenant context
        $dashResponse = $this->actingAs($user, 'web')->get('/admin/dashboard');
        $dashResponse->assertStatus(200);
        $this->assertEquals($this->school->id, session('current_school_id'));
    }

    /**
     * Test inactive or suspended school user is locked out.
     */
    public function test_inactive_school_user_is_prevented_from_logging_in(): void
    {
        User::create([
            'school_id' => $this->school->id,
            'name' => 'Inactive Teacher',
            'email' => 'inactive@springfield.edu',
            'password' => Hash::make('password123'),
            'status' => 'inactive',
        ]);

        $response = $this->from('/admin/login')->post('/admin/login', [
            'login' => 'inactive@springfield.edu',
            'password' => 'password123',
        ]);

        $this->assertGuest('web');
        $this->assertGuest('admin');
        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors('login');
    }

    /**
     * Test suspended school user is locked out.
     */
    public function test_suspended_school_user_is_prevented_from_logging_in(): void
    {
        User::create([
            'school_id' => $this->school->id,
            'name' => 'Suspended Staff',
            'email' => 'suspended@springfield.edu',
            'password' => Hash::make('password123'),
            'status' => 'suspended',
        ]);

        $response = $this->from('/admin/login')->post('/admin/login', [
            'login' => 'suspended@springfield.edu',
            'password' => 'password123',
        ]);

        $this->assertGuest('web');
        $this->assertGuest('admin');
        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors('login');
    }

    /**
     * Test logout clears session for both guards.
     */
    public function test_logout_clears_authenticated_guard_session(): void
    {
        $user = User::create([
            'school_id' => $this->school->id,
            'name' => 'Logout Test User',
            'email' => 'logout@springfield.edu',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $this->actingAs($user, 'web');
        $response = $this->post('/admin/logout');

        $this->assertGuest('web');
        $this->assertGuest('admin');
        $response->assertRedirect('/admin/login');
    }
}
