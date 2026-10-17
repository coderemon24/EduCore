<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use App\Models\School;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StaffManagementTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected School $otherSchool;

    protected Admin $superAdmin;

    protected User $schoolAdminUser;

    protected Role $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'EduCore West Campus',
            'code' => 'EC-WEST',
            'status' => 'active',
        ]);

        $this->otherSchool = School::create([
            'name' => 'Other East Campus',
            'code' => 'EC-EAST',
            'status' => 'active',
        ]);

        $this->superAdmin = Admin::factory()->create([
            'role' => 'super_admin',
        ]);

        // Create all permissions
        $permissions = [
            'staff.view' => Permission::create(['name' => 'View Staff', 'slug' => 'staff.view', 'group' => 'staff']),
            'staff.create' => Permission::create(['name' => 'Create Staff', 'slug' => 'staff.create', 'group' => 'staff']),
            'staff.edit' => Permission::create(['name' => 'Edit Staff', 'slug' => 'staff.edit', 'group' => 'staff']),
            'staff.delete' => Permission::create(['name' => 'Delete Staff', 'slug' => 'staff.delete', 'group' => 'staff']),
            'staff.manage_status' => Permission::create(['name' => 'Manage Status', 'slug' => 'staff.manage_status', 'group' => 'staff']),
        ];

        $this->adminRole = Role::create([
            'school_id' => $this->school->id,
            'name' => 'School Admin',
            'slug' => 'school_admin',
            'is_system' => true,
        ]);
        $this->adminRole->permissions()->sync(collect($permissions)->pluck('id'));

        $this->schoolAdminUser = User::create([
            'school_id' => $this->school->id,
            'name' => 'School Admin Person',
            'email' => 'admin@ecwest.edu',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $this->schoolAdminUser->roles()->sync([$this->adminRole->id]);
    }

    /**
     * Test viewing the staff directory.
     */
    public function test_can_view_staff_directory(): void
    {
        Staff::create([
            'school_id' => $this->school->id,
            'staff_code' => 'STF-001',
            'first_name' => 'Robert',
            'last_name' => 'Langdon',
            'gender' => 'Male',
            'email' => 'rlangdon@ecwest.edu',
            'department' => 'History',
            'designation' => 'Professor',
            'is_teaching' => true,
            'joining_date' => '2025-01-15',
            'contract_type' => 'permanent',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->schoolAdminUser, 'web')->get('/admin/staff');

        $response->assertStatus(200);
        $response->assertSee('Robert Langdon');
        $response->assertSee('STF-001');
        $response->assertSee('History');
    }

    /**
     * Test filtering staff by teaching type, department, and search keyword.
     */
    public function test_can_filter_and_search_staff(): void
    {
        Staff::create([
            'school_id' => $this->school->id,
            'staff_code' => 'TCH-101',
            'first_name' => 'Isaac',
            'last_name' => 'Newton',
            'gender' => 'Male',
            'email' => 'newton@ecwest.edu',
            'department' => 'Physics',
            'designation' => 'Lead Teacher',
            'is_teaching' => true,
            'joining_date' => '2024-09-01',
            'contract_type' => 'permanent',
            'status' => 'active',
        ]);

        Staff::create([
            'school_id' => $this->school->id,
            'staff_code' => 'ADM-202',
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'gender' => 'Female',
            'email' => 'ada@ecwest.edu',
            'department' => 'Computing',
            'designation' => 'Systems Analyst',
            'is_teaching' => false,
            'joining_date' => '2025-02-01',
            'contract_type' => 'permanent',
            'status' => 'active',
        ]);

        // Filter: teaching only
        $resTeaching = $this->actingAs($this->schoolAdminUser, 'web')->get('/admin/staff?type=teaching');
        $resTeaching->assertSee('Isaac Newton');
        $resTeaching->assertDontSee('Ada Lovelace');

        // Filter: non-teaching
        $resNonTeaching = $this->actingAs($this->schoolAdminUser, 'web')->get('/admin/staff?type=non_teaching');
        $resNonTeaching->assertSee('Ada Lovelace');
        $resNonTeaching->assertDontSee('Isaac Newton');

        // Search by keyword
        $resSearch = $this->actingAs($this->schoolAdminUser, 'web')->get('/admin/staff?search=Physics');
        $resSearch->assertSee('Isaac Newton');
        $resSearch->assertDontSee('Ada Lovelace');
    }

    /**
     * Test creating a staff member with photo upload and user account provisioning.
     */
    public function test_can_create_staff_member_with_photo_upload_and_user_account(): void
    {
        $photo = UploadedFile::fake()->image('staff_portrait.jpg', 200, 200);

        $payload = [
            'staff_code' => 'STF-NEW-01',
            'first_name' => 'Marie',
            'last_name' => 'Curie',
            'gender' => 'Female',
            'email' => 'marie.curie@ecwest.edu',
            'phone' => '+15551234567',
            'is_teaching' => 1,
            'department' => 'Chemistry',
            'designation' => 'Research Faculty',
            'joining_date' => '2025-01-01',
            'contract_type' => 'permanent',
            'status' => 'active',
            'photo' => $photo,
            'create_user_account' => 1,
            'password' => 'secretcurie123',
            'role_id' => $this->adminRole->id,
        ];

        $response = $this->actingAs($this->schoolAdminUser, 'web')->post('/admin/staff', $payload);

        $response->assertSessionHasNoErrors();

        $staff = Staff::where('email', 'marie.curie@ecwest.edu')->first();
        $this->assertNotNull($staff);
        $this->assertEquals('STF-NEW-01', $staff->staff_code);
        $this->assertNotNull($staff->photo);
        $this->assertFileExists(public_path($staff->photo));

        // Verify linked user account created
        $this->assertNotNull($staff->user_id);
        $user = User::find($staff->user_id);
        $this->assertNotNull($user);
        $this->assertEquals('Marie Curie', $user->name);
        $this->assertTrue(Hash::check('secretcurie123', $user->password));
        $this->assertTrue($user->roles->contains($this->adminRole->id));

        // Cleanup uploaded test file
        if (File::exists(public_path($staff->photo))) {
            File::delete(public_path($staff->photo));
        }
    }

    /**
     * Test status update toggles both staff and linked user account.
     */
    public function test_can_update_staff_status_and_sync_with_user(): void
    {
        $user = User::create([
            'school_id' => $this->school->id,
            'name' => 'Toggle User',
            'email' => 'toggle@ecwest.edu',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);

        $staff = Staff::create([
            'school_id' => $this->school->id,
            'user_id' => $user->id,
            'staff_code' => 'STF-TOGGLE',
            'first_name' => 'Toggle',
            'last_name' => 'Test',
            'gender' => 'Male',
            'email' => 'toggle@ecwest.edu',
            'department' => 'Admin',
            'designation' => 'Staff',
            'is_teaching' => false,
            'joining_date' => '2025-01-01',
            'contract_type' => 'permanent',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->schoolAdminUser, 'web')
            ->patch("/admin/staff/{$staff->id}/status", [
                'status' => 'suspended',
            ]);

        $response->assertRedirect();
        $this->assertEquals('suspended', $staff->fresh()->status);
        $this->assertEquals('suspended', $user->fresh()->status);
    }

    /**
     * Test tenant isolation prevents school user from viewing another school's staff.
     */
    public function test_tenant_isolation_prevents_cross_school_access(): void
    {
        $otherStaff = Staff::create([
            'school_id' => $this->otherSchool->id,
            'staff_code' => 'STF-OTHER-01',
            'first_name' => 'Stranger',
            'last_name' => 'Person',
            'gender' => 'Male',
            'email' => 'stranger@other.edu',
            'department' => 'Science',
            'designation' => 'Teacher',
            'is_teaching' => true,
            'joining_date' => '2025-01-01',
            'contract_type' => 'permanent',
            'status' => 'active',
        ]);

        // Attempt to view other school staff under schoolAdminUser (bound to West Campus)
        $response = $this->actingAs($this->schoolAdminUser, 'web')->get("/admin/staff/{$otherStaff->id}");

        // Due to SchoolScope, other school staff cannot be located (404)
        $response->assertStatus(404);
    }

    /**
     * Test soft deletion and restoration of staff.
     */
    public function test_soft_delete_and_restore_staff(): void
    {
        $staff = Staff::create([
            'school_id' => $this->school->id,
            'staff_code' => 'STF-DEL-01',
            'first_name' => 'Delete',
            'last_name' => 'Me',
            'gender' => 'Male',
            'email' => 'delete@ecwest.edu',
            'department' => 'Facilities',
            'designation' => 'Maintenance',
            'is_teaching' => false,
            'joining_date' => '2025-01-01',
            'contract_type' => 'permanent',
            'status' => 'active',
        ]);

        // Move to trash
        $delResponse = $this->actingAs($this->schoolAdminUser, 'web')->delete("/admin/staff/{$staff->id}");
        $delResponse->assertRedirect();
        $this->assertSoftDeleted('staff', ['id' => $staff->id]);

        // Restore
        $resResponse = $this->actingAs($this->schoolAdminUser, 'web')->post("/admin/staff/{$staff->id}/restore");
        $resResponse->assertRedirect();
        $this->assertNotSoftDeleted('staff', ['id' => $staff->id]);
    }
}
