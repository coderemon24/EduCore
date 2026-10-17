<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SchoolPermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected School $otherSchool;

    protected Admin $superAdmin;

    protected User $limitedUser;

    protected User $managerUser;

    protected Permission $viewStaffPerm;

    protected Permission $createStaffPerm;

    protected Permission $viewRolesPerm;

    protected Permission $manageRolesPerm;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Oakridge Academy',
            'code' => 'OAK-01',
            'status' => 'active',
        ]);

        $this->otherSchool = School::create([
            'name' => 'Pinecrest Academy',
            'code' => 'PIN-02',
            'status' => 'active',
        ]);

        $this->superAdmin = Admin::factory()->create([
            'role' => 'super_admin',
        ]);

        $this->viewStaffPerm = Permission::create(['name' => 'View Staff', 'slug' => 'staff.view', 'group' => 'staff']);
        $this->createStaffPerm = Permission::create(['name' => 'Create Staff', 'slug' => 'staff.create', 'group' => 'staff']);
        $this->viewRolesPerm = Permission::create(['name' => 'View Roles', 'slug' => 'roles.view', 'group' => 'roles']);
        $this->manageRolesPerm = Permission::create(['name' => 'Manage Roles', 'slug' => 'roles.manage', 'group' => 'roles']);

        // Limited Role (View Staff only)
        $viewerRole = Role::create([
            'school_id' => $this->school->id,
            'name' => 'Staff Viewer',
            'slug' => 'staff_viewer',
            'is_system' => false,
        ]);
        $viewerRole->permissions()->sync([$this->viewStaffPerm->id]);

        $this->limitedUser = User::create([
            'school_id' => $this->school->id,
            'name' => 'Limited User',
            'email' => 'viewer@oakridge.edu',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $this->limitedUser->roles()->sync([$viewerRole->id]);

        // Manager Role (Roles View & Manage + Staff View & Create)
        $managerRole = Role::create([
            'school_id' => $this->school->id,
            'name' => 'HR Manager',
            'slug' => 'hr_manager',
            'is_system' => false,
        ]);
        $managerRole->permissions()->sync([
            $this->viewStaffPerm->id,
            $this->createStaffPerm->id,
            $this->viewRolesPerm->id,
            $this->manageRolesPerm->id,
        ]);

        $this->managerUser = User::create([
            'school_id' => $this->school->id,
            'name' => 'Manager User',
            'email' => 'manager@oakridge.edu',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $this->managerUser->roles()->sync([$managerRole->id]);
    }

    /**
     * Test Super Admin bypasses all school permission checks.
     */
    public function test_super_admin_bypasses_all_permission_checks(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')->get('/admin/roles');
        $response->assertStatus(200);

        $createResponse = $this->actingAs($this->superAdmin, 'admin')->get('/admin/roles/create');
        $createResponse->assertStatus(200);

        $staffCreateResponse = $this->actingAs($this->superAdmin, 'admin')->get('/admin/staff/create');
        $staffCreateResponse->assertStatus(200);
    }

    /**
     * Test school user with granted permission can access protected route.
     */
    public function test_school_user_with_permission_can_access_route(): void
    {
        // Limited user has staff.view
        $response = $this->actingAs($this->limitedUser, 'web')->get('/admin/staff');
        $response->assertStatus(200);
    }

    /**
     * Test school user without permission is denied with 403 Forbidden.
     */
    public function test_school_user_without_permission_receives_403_access_denied(): void
    {
        // Limited user lacks staff.create permission
        $response = $this->actingAs($this->limitedUser, 'web')->get('/admin/staff/create');
        $response->assertStatus(403);
        $response->assertSee('Access Denied');

        // Limited user lacks roles.view permission
        $roleResponse = $this->actingAs($this->limitedUser, 'web')->get('/admin/roles');
        $roleResponse->assertStatus(403);
    }

    /**
     * Test school manager can create and update custom roles with permissions.
     */
    public function test_manager_can_create_and_update_custom_role(): void
    {
        $createPayload = [
            'name' => 'Library Officer',
            'slug' => 'library_officer',
            'description' => 'Oversees book inventory and student checkouts',
            'permissions' => [$this->viewStaffPerm->id],
        ];

        $postResponse = $this->actingAs($this->managerUser, 'web')->post('/admin/roles', $createPayload);
        $postResponse->assertRedirect('/admin/roles');

        $createdRole = Role::where('slug', 'library_officer')->first();
        $this->assertNotNull($createdRole);
        $this->assertEquals($this->school->id, $createdRole->school_id);
        $this->assertTrue($createdRole->permissions->contains($this->viewStaffPerm->id));

        // Update role
        $updatePayload = [
            'name' => 'Senior Library Officer',
            'slug' => 'senior_library_officer',
            'description' => 'Updated description',
            'permissions' => [$this->viewStaffPerm->id, $this->createStaffPerm->id],
        ];

        $putResponse = $this->actingAs($this->managerUser, 'web')->put("/admin/roles/{$createdRole->id}", $updatePayload);
        $putResponse->assertRedirect('/admin/roles');

        $freshRole = $createdRole->fresh();
        $this->assertEquals('Senior Library Officer', $freshRole->name);
        $this->assertEquals('senior_library_officer', $freshRole->slug);
        $this->assertTrue($freshRole->permissions->contains($this->createStaffPerm->id));
    }

    /**
     * Test cannot delete a system-defined role.
     */
    public function test_system_core_role_cannot_be_deleted(): void
    {
        $systemRole = Role::create([
            'school_id' => $this->school->id,
            'name' => 'Principal Core',
            'slug' => 'principal_core',
            'is_system' => true,
        ]);

        $response = $this->actingAs($this->managerUser, 'web')->delete("/admin/roles/{$systemRole->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('roles', ['id' => $systemRole->id]);
    }

    /**
     * Test tenant boundary prevents accessing another school's roles.
     */
    public function test_cannot_access_or_edit_roles_of_another_school(): void
    {
        $otherSchoolRole = Role::create([
            'school_id' => $this->otherSchool->id,
            'name' => 'Pinecrest Dean',
            'slug' => 'pinecrest_dean',
            'is_system' => false,
        ]);

        // Manager from Oakridge attempts to edit Pinecrest's role
        $response = $this->actingAs($this->managerUser, 'web')->get("/admin/roles/{$otherSchoolRole->id}/edit");

        // Returns 404 because role is not in manager's school tenant
        $response->assertStatus(404);
    }
}
