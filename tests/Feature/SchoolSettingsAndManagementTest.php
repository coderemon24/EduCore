<?php

namespace Tests\Feature;

use App\Enums\SchoolStatus;
use App\Models\Admin;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolSettingsAndManagementTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superAdmin;

    private Admin $regularAdmin;

    private School $defaultSchool;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultSchool = School::factory()->create([
            'name' => 'EduCore Academy',
            'code' => 'EDU-001',
            'status' => SchoolStatus::Active,
        ]);

        $this->superAdmin = Admin::factory()->create([
            'role' => 'super_admin',
            'username' => 'superadmin',
            'email' => 'super@example.com',
        ]);

        $this->regularAdmin = Admin::factory()->create([
            'role' => 'admin',
            'username' => 'staffadmin',
            'email' => 'staff@example.com',
        ]);
    }

    /**
     * Test guest cannot access school settings.
     */
    public function test_guest_is_redirected_from_school_settings(): void
    {
        $response = $this->get(route('admin.settings.school'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Test authenticated admin can view school settings.
     */
    public function test_authenticated_admin_can_view_school_settings(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.settings.school'));

        $response->assertOk();
        $response->assertSee('Institution Profile &amp; Settings', false);
        $response->assertSee($this->defaultSchool->name);
    }

    /**
     * Test admin can update school settings with valid attributes.
     */
    public function test_admin_can_update_school_settings(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->put(route('admin.settings.school.update'), [
                'name' => 'Oakridge Preparatory Campus',
                'email' => 'info@oakridge.test',
                'phone' => '+1 (555) 999-1234',
                'address' => '100 University Avenue',
                'city' => 'Cambridge',
                'state' => 'Massachusetts',
                'country' => 'United States',
                'postal_code' => '02138',
                'website' => 'https://oakridge.test',
                'established_year' => 1995,
                'timezone' => 'America/New_York',
                'currency' => 'USD',
            ]);

        $response->assertRedirect(route('admin.settings.school'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('schools', [
            'id' => $this->defaultSchool->id,
            'name' => 'Oakridge Preparatory Campus',
            'email' => 'info@oakridge.test',
            'city' => 'Cambridge',
            'established_year' => 1995,
        ]);
    }

    /**
     * Test school settings validation fails on missing required name.
     */
    public function test_school_settings_validation_requires_name(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->put(route('admin.settings.school.update'), [
                'name' => '',
                'timezone' => 'America/New_York',
                'currency' => 'USD',
            ]);

        $response->assertSessionHasErrors(['name']);
    }

    /**
     * Test Super Admin can access schools directory.
     */
    public function test_super_admin_can_access_schools_directory(): void
    {
        School::factory()->count(3)->create();

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.schools.index'));

        $response->assertOk();
        $response->assertSee('Academic Institutions &amp; Tenants', false);
        $response->assertSee($this->defaultSchool->name);
    }

    /**
     * Test non-super admin cannot access schools directory.
     */
    public function test_non_super_admin_is_forbidden_from_schools_directory(): void
    {
        $response = $this->actingAs($this->regularAdmin, 'admin')
            ->get(route('admin.schools.index'));

        $response->assertForbidden();
    }

    /**
     * Test status filtering in schools directory.
     */
    public function test_schools_directory_filters_by_status(): void
    {
        $activeSchool = School::factory()->create(['name' => 'Active High', 'status' => SchoolStatus::Active]);
        $suspendedSchool = School::factory()->suspended()->create(['name' => 'Suspended Academy']);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.schools.index', ['status' => 'suspended']));

        $response->assertOk();
        $response->assertSee('Suspended Academy');
        $response->assertDontSee('Active High');
    }

    /**
     * Test keyword searching in schools directory.
     */
    public function test_schools_directory_searches_by_keyword(): void
    {
        School::factory()->create(['name' => 'Beacon Hill College', 'code' => 'BHC-101']);
        School::factory()->create(['name' => 'Pinecrest Elementary', 'code' => 'PCE-202']);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.schools.index', ['search' => 'Beacon']));

        $response->assertOk();
        $response->assertSee('Beacon Hill College');
        $response->assertDontSee('Pinecrest Elementary');
    }

    /**
     * Test Super Admin can inspect school details.
     */
    public function test_super_admin_can_view_school_details(): void
    {
        $school = School::factory()->create(['name' => 'St. Jude International']);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.schools.show', $school));

        $response->assertOk();
        $response->assertSee('St. Jude International');
        $response->assertSee('Official Contacts', false);
    }

    /**
     * Test Super Admin can update school status with form redirect.
     */
    public function test_super_admin_can_update_school_status(): void
    {
        $school = School::factory()->create(['status' => SchoolStatus::Active]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->patch(route('admin.schools.status.update', $school), [
                'status' => 'suspended',
            ]);

        $response->assertRedirect();
        $this->assertSame(SchoolStatus::Suspended, $school->fresh()->status);
    }

    /**
     * Test Super Admin can update school status with JSON response for SweetAlert2.
     */
    public function test_super_admin_can_update_school_status_via_json(): void
    {
        $school = School::factory()->create(['status' => SchoolStatus::Pending]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->patchJson(route('admin.schools.status.update', $school), [
                'status' => 'active',
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'status' => 'active',
            'label' => 'Active',
        ]);
        $this->assertSame(SchoolStatus::Active, $school->fresh()->status);
    }

    /**
     * Test invalid status update is rejected with validation error.
     */
    public function test_invalid_status_update_is_rejected(): void
    {
        $school = School::factory()->create();

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->patch(route('admin.schools.status.update', $school), [
                'status' => 'non_existent_status',
            ]);

        $response->assertSessionHasErrors(['status']);
    }

    /**
     * Test Super Admin can access create school page.
     */
    public function test_super_admin_can_access_create_school_page(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.schools.create'));

        $response->assertOk();
        $response->assertSee('Onboard New Academic Tenant');
    }

    /**
     * Test Super Admin can store new school tenant.
     */
    public function test_super_admin_can_store_new_school_tenant(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.schools.store'), [
                'name' => 'Wellington International School',
                'code' => 'WIS-900',
                'status' => 'active',
                'email' => 'contact@wellington.test',
                'phone' => '+1 (555) 777-8899',
                'city' => 'Chicago',
                'country' => 'United States',
                'timezone' => 'America/Chicago',
                'currency' => 'USD',
            ]);

        $school = School::where('code', 'WIS-900')->first();
        $this->assertNotNull($school);
        $this->assertSame('Wellington International School', $school->name);
        $response->assertRedirect(route('admin.schools.show', $school));
        $response->assertSessionHas('success');
    }

    /**
     * Test Super Admin can access edit school page.
     */
    public function test_super_admin_can_access_edit_school_page(): void
    {
        $school = School::factory()->create();

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.schools.edit', $school));

        $response->assertOk();
        $response->assertSee('Edit Academic Institution');
        $response->assertSee($school->name);
    }

    /**
     * Test Super Admin can update school details via edit form.
     */
    public function test_super_admin_can_update_school_details(): void
    {
        $school = School::factory()->create(['name' => 'Original Name']);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->put(route('admin.schools.update', $school), [
                'name' => 'Updated Academic Institute',
                'code' => $school->code,
                'status' => 'active',
                'timezone' => 'America/New_York',
                'currency' => 'USD',
            ]);

        $response->assertRedirect(route('admin.schools.show', $school));
        $this->assertSame('Updated Academic Institute', $school->fresh()->name);
    }

    /**
     * Test Super Admin can soft delete a school tenant.
     */
    public function test_super_admin_can_soft_delete_school_tenant(): void
    {
        $school = School::factory()->create(['name' => 'To Be Soft Deleted']);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->delete(route('admin.schools.destroy', $school));

        $response->assertRedirect(route('admin.schools.index'));
        $this->assertSoftDeleted('schools', ['id' => $school->id]);
    }

    /**
     * Test Super Admin can restore a soft-deleted school tenant.
     */
    public function test_super_admin_can_restore_soft_deleted_school(): void
    {
        $school = School::factory()->create(['name' => 'Archived School']);
        $school->delete();
        $this->assertSoftDeleted('schools', ['id' => $school->id]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.schools.restore', $school));

        $response->assertRedirect();
        $this->assertNotSoftDeleted('schools', ['id' => $school->id]);
    }

    /**
     * Test Super Admin can permanently force-delete a school tenant.
     */
    public function test_super_admin_can_permanently_force_delete_school(): void
    {
        $school = School::factory()->create(['name' => 'Permanently Purged']);
        $schoolId = $school->id;
        $school->delete();

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->delete(route('admin.schools.force-delete', $school));

        $response->assertRedirect(route('admin.schools.index', ['status' => 'trashed']));
        $this->assertDatabaseMissing('schools', ['id' => $schoolId]);
    }

    /**
     * Test Super Admin can view trashed tab in schools directory.
     */
    public function test_super_admin_can_view_trashed_tab(): void
    {
        $activeSchool = School::factory()->create(['name' => 'Active Campus']);
        $trashedSchool = School::factory()->create(['name' => 'Archived Campus']);
        $trashedSchool->delete();

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.schools.index', ['status' => 'trashed']));

        $response->assertOk();
        $response->assertSee('Archived Campus');
        $response->assertDontSee('Active Campus');
        $response->assertSee('Restore');
        $response->assertSee('Purge');
    }
}
