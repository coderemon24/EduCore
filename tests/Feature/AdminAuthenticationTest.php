<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin login page renders successfully with minimalist, school-focused UI.
     */
    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSeeText('Admin Sign In');
        $response->assertSeeText('EduCore Academy');
        $response->assertSeeText('School Management System');
        $response->assertSee('admin-login.js');
        $response->assertSee('toggle-password-btn');
        $response->assertDontSee('Uptime 99.98%');
        $response->assertDontSee('Enterprise Tier');
    }

    /**
     * Test unauthenticated users accessing admin dashboard are redirected to login.
     */
    public function test_unauthenticated_admin_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }

    /**
     * Test admin can authenticate using email address.
     */
    public function test_admin_can_authenticate_using_email(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@educore.test',
            'username' => 'superadmin',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/admin/login', [
            'login' => 'admin@educore.test',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertRedirect('/admin/dashboard');
    }

    /**
     * Test admin can authenticate using username.
     */
    public function test_admin_can_authenticate_using_username(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'dean@educore.test',
            'username' => 'academicdean',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/admin/login', [
            'login' => 'academicdean',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertRedirect('/admin/dashboard');
    }

    /**
     * Test admin cannot authenticate with invalid password.
     */
    public function test_admin_cannot_authenticate_with_invalid_password(): void
    {
        Admin::factory()->create([
            'email' => 'admin@educore.test',
            'username' => 'adminuser',
            'password' => Hash::make('password'),
        ]);

        $response = $this->from('/admin/login')->post('/admin/login', [
            'login' => 'adminuser',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('admin');
        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors('login');
    }

    /**
     * Test rate limiting triggers after 5 failed attempts.
     */
    public function test_admin_login_rate_limiting(): void
    {
        Admin::factory()->create([
            'email' => 'admin@educore.test',
            'username' => 'adminuser',
            'password' => Hash::make('password'),
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->from('/admin/login')->post('/admin/login', [
                'login' => 'adminuser',
                'password' => 'wrong-password',
            ]);
        }

        // 6th attempt should trigger lockout
        $response = $this->from('/admin/login')->post('/admin/login', [
            'login' => 'adminuser',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('admin');
        $response->assertSessionHasErrors('login');
        $errors = session('errors')->get('login');
        $this->assertTrue(str_contains($errors[0], 'Too many login attempts'));
    }

    /**
     * Test admin can securely logout from admin guard.
     */
    public function test_admin_can_logout(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post('/admin/logout');

        $this->assertGuest('admin');
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test authenticated admin visiting login page is redirected to dashboard.
     */
    public function test_authenticated_admin_visiting_login_redirects_to_dashboard(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/login');

        $response->assertRedirect('/admin/dashboard');
    }
}
