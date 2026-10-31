<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Admin;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\FeeInvoice;
use App\Models\Guardian;
use App\Models\Role;
use App\Models\School;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentEnrollment;
use App\Models\TimetableSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class PortalAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected function setUp(): void
    {
        parent::setUp();

        if (is_dir(base_path('../EduCore-frontend/resources/views'))) {
            View::addLocation(base_path('../EduCore-frontend/resources/views'));
        }

        $this->school = School::create([
            'name' => 'Sonatala High School',
            'slug' => 'sonatala-high-school',
            'code' => '8107',
            'status' => 'active',
            'email' => 'info@sonatalahighschool.edu.bd',
        ]);
    }

    public function test_portal_login_screen_renders_successfully(): void
    {
        $response = $this->get(route('frontend.portal.login'));

        $response->assertStatus(200);
    }

    public function test_student_can_authenticate_with_email_and_admission_number(): void
    {
        $user = User::create([
            'school_id' => $this->school->id,
            'name' => 'Alexander Wright',
            'email' => 'alexander.wright@student.educore.edu',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'phone' => '+15552345678',
        ]);

        $role = Role::create([
            'school_id' => $this->school->id,
            'name' => 'Student',
            'slug' => 'student',
            'is_system' => true,
        ]);
        $user->roles()->sync([$role->id]);

        $student = Student::create([
            'school_id' => $this->school->id,
            'user_id' => $user->id,
            'admission_number' => 'ADM-2026-0001',
            'admission_date' => '2026-01-10',
            'first_name' => 'Alexander',
            'last_name' => 'Wright',
            'gender' => 'male',
            'status' => 'active',
        ]);

        // 1. Authenticate with email
        $response = $this->post(route('frontend.portal.login.store'), [
            'login' => 'alexander.wright@student.educore.edu',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('frontend.portal.dashboard'));
        $this->assertAuthenticatedAs($user, 'web');

        // Logout
        $this->post(route('frontend.portal.logout'));
        $this->assertGuest('web');

        // 2. Authenticate with admission number
        $response = $this->post(route('frontend.portal.login.store'), [
            'login' => 'ADM-2026-0001',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('frontend.portal.dashboard'));
        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_guardian_can_authenticate_with_email_and_phone(): void
    {
        $user = User::create([
            'school_id' => $this->school->id,
            'name' => 'Robert Wright',
            'email' => 'robert.wright@guardian.test',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'phone' => '+15559876543',
        ]);

        $role = Role::create([
            'school_id' => $this->school->id,
            'name' => 'Guardian',
            'slug' => 'guardian',
            'is_system' => true,
        ]);
        $user->roles()->sync([$role->id]);

        $guardian = Guardian::create([
            'school_id' => $this->school->id,
            'user_id' => $user->id,
            'name' => 'Robert Wright',
            'phone' => '+15559876543',
            'email' => 'robert.wright@guardian.test',
        ]);

        // Login with phone identifier
        $response = $this->post(route('frontend.portal.login.store'), [
            'login' => '+15559876543',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('frontend.portal.dashboard'));
        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_teacher_can_authenticate_with_email_and_staff_code(): void
    {
        $user = User::create([
            'school_id' => $this->school->id,
            'name' => 'Eleanor Pemberton',
            'email' => 'eleanor.pemberton@educore.test',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $role = Role::create([
            'school_id' => $this->school->id,
            'name' => 'Teacher',
            'slug' => 'teacher',
            'is_system' => true,
        ]);
        $user->roles()->sync([$role->id]);

        Staff::create([
            'school_id' => $this->school->id,
            'user_id' => $user->id,
            'staff_code' => 'TCH-1001',
            'first_name' => 'Eleanor',
            'last_name' => 'Pemberton',
            'gender' => 'Female',
            'designation' => 'Senior Mathematics Teacher',
            'joining_date' => '2023-08-15',
            'is_teaching' => true,
            'status' => 'active',
        ]);

        // Login with staff code
        $response = $this->post(route('frontend.portal.login.store'), [
            'login' => 'TCH-1001',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('frontend.portal.dashboard'));
        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_login_rate_limiting_triggers_after_five_failed_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('frontend.portal.login.store'), [
                'login' => 'invalid.user@educore.test',
                'password' => 'wrong-pass',
            ]);
        }

        $response = $this->post(route('frontend.portal.login.store'), [
            'login' => 'invalid.user@educore.test',
            'password' => 'wrong-pass',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertStringContainsString('seconds', session('errors')->first('login'));
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        $user = User::create([
            'school_id' => $this->school->id,
            'name' => 'Inactive Student',
            'email' => 'inactive@student.educore.edu',
            'password' => Hash::make('password123'),
            'status' => 'inactive',
        ]);

        $response = $this->post(route('frontend.portal.login.store'), [
            'login' => 'inactive@student.educore.edu',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertStringContainsString('inactive', session('errors')->first('login'));
        $this->assertGuest('web');
    }

    public function test_admin_login_rejects_regular_user_with_isolation_error(): void
    {
        User::create([
            'school_id' => $this->school->id,
            'name' => 'Regular User',
            'email' => 'regular@educore.test',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $response = $this->post(route('admin.login.store'), [
            'login' => 'regular@educore.test',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertStringContainsString('strictly reserved for Super Administrators', session('errors')->first('login'));
        $this->assertGuest('admin');
        $this->assertGuest('web');
    }

    public function test_portal_dashboard_requires_web_authentication(): void
    {
        $response = $this->get(route('frontend.portal.dashboard'));

        $response->assertRedirect('/portal/login');
    }

    public function test_portal_logout_clears_session_and_redirects(): void
    {
        $user = User::create([
            'school_id' => $this->school->id,
            'name' => 'Active Student',
            'email' => 'active@student.educore.edu',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $response = $this->actingAs($user, 'web')->post(route('frontend.portal.logout'));

        $response->assertRedirect(route('frontend.portal.login'));
        $this->assertGuest('web');
    }
}

