<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\Notice;
use App\Models\Permission;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NoticeBoardTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected School $otherSchool;

    protected User $adminUser;

    protected User $viewerUser;

    protected User $otherSchoolUser;

    protected AcademicClass $academicClass;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

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

        $this->academicClass = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Grade 10',
            'order_index' => 1,
            'status' => 'active',
        ]);

        // Permissions
        $viewPerm = Permission::create(['name' => 'View Notices', 'slug' => 'notices.view', 'group' => 'notices']);
        $createPerm = Permission::create(['name' => 'Create Notices', 'slug' => 'notices.create', 'group' => 'notices']);
        $editPerm = Permission::create(['name' => 'Edit Notices', 'slug' => 'notices.edit', 'group' => 'notices']);
        $deletePerm = Permission::create(['name' => 'Delete Notices', 'slug' => 'notices.delete', 'group' => 'notices']);

        // Admin Role (Full Notice Access)
        $adminRole = Role::create([
            'school_id' => $this->school->id,
            'name' => 'School Admin',
            'slug' => 'school_admin',
            'is_system' => true,
        ]);
        $adminRole->permissions()->sync([$viewPerm->id, $createPerm->id, $editPerm->id, $deletePerm->id]);

        $this->adminUser = User::create([
            'school_id' => $this->school->id,
            'name' => 'Admin User',
            'email' => 'admin@oakridge.edu',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $this->adminUser->roles()->sync([$adminRole->id]);

        // Viewer Role (View Only)
        $viewerRole = Role::create([
            'school_id' => $this->school->id,
            'name' => 'Notice Viewer',
            'slug' => 'notice_viewer',
            'is_system' => false,
        ]);
        $viewerRole->permissions()->sync([$viewPerm->id]);

        $this->viewerUser = User::create([
            'school_id' => $this->school->id,
            'name' => 'Viewer User',
            'email' => 'viewer@oakridge.edu',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $this->viewerUser->roles()->sync([$viewerRole->id]);

        // Other School User
        $otherRole = Role::create([
            'school_id' => $this->otherSchool->id,
            'name' => 'Other Admin',
            'slug' => 'other_admin',
            'is_system' => true,
        ]);
        $otherRole->permissions()->sync([$viewPerm->id, $createPerm->id, $editPerm->id, $deletePerm->id]);

        $this->otherSchoolUser = User::create([
            'school_id' => $this->otherSchool->id,
            'name' => 'Pinecrest Admin',
            'email' => 'admin@pinecrest.edu',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $this->otherSchoolUser->roles()->sync([$otherRole->id]);
    }

    public function test_user_can_view_notice_index_with_permission(): void
    {
        Notice::create([
            'school_id' => $this->school->id,
            'created_by' => $this->adminUser->id,
            'title' => 'Welcome to Spring Term',
            'content' => 'Classes will commence on Monday.',
            'type' => Notice::TYPE_ACADEMIC,
            'target_audience' => Notice::AUDIENCE_ALL,
            'status' => Notice::STATUS_PUBLISHED,
        ]);

        $response = $this->actingAs($this->viewerUser, 'web')
            ->get(route('admin.notices.index'));

        $response->assertOk()
            ->assertSee('Welcome to Spring Term')
            ->assertSee('Notice Board &amp; Bulletins', false);
    }

    public function test_user_without_permission_cannot_access_create_notice(): void
    {
        $response = $this->actingAs($this->viewerUser, 'web')
            ->get(route('admin.notices.create'));

        $response->assertStatus(403);
    }

    public function test_authorized_user_can_create_notice_with_attachment(): void
    {
        $file = UploadedFile::fake()->create('routine.pdf', 500, 'application/pdf');

        $payload = [
            'title' => 'Midterm Exam Routine',
            'content' => 'Please download the attached routine for exam dates.',
            'type' => Notice::TYPE_EXAM,
            'target_audience' => Notice::AUDIENCE_CLASS_SPECIFIC,
            'academic_class_id' => $this->academicClass->id,
            'is_pinned' => '1',
            'status' => Notice::STATUS_PUBLISHED,
            'published_at' => now()->toDateString(),
            'attachment' => $file,
        ];

        $response = $this->actingAs($this->adminUser, 'web')
            ->post(route('admin.notices.store'), $payload);

        $response->assertRedirect(route('admin.notices.index'));

        $this->assertDatabaseHas('notices', [
            'school_id' => $this->school->id,
            'title' => 'Midterm Exam Routine',
            'type' => Notice::TYPE_EXAM,
            'target_audience' => Notice::AUDIENCE_CLASS_SPECIFIC,
            'academic_class_id' => $this->academicClass->id,
            'is_pinned' => true,
            'status' => Notice::STATUS_PUBLISHED,
            'attachment_name' => 'routine.pdf',
        ]);

        $notice = Notice::where('title', 'Midterm Exam Routine')->first();
        $this->assertNotNull($notice->attachment_path);
        Storage::disk('public')->assertExists($notice->attachment_path);
    }

    public function test_multi_tenant_isolation_prevents_cross_school_leakage(): void
    {
        // Oakridge Notice
        Notice::create([
            'school_id' => $this->school->id,
            'created_by' => $this->adminUser->id,
            'title' => 'Oakridge Secret Announcement',
            'content' => 'Exclusive to Oakridge students.',
            'status' => Notice::STATUS_PUBLISHED,
        ]);

        // Pinecrest Notice
        Notice::create([
            'school_id' => $this->otherSchool->id,
            'created_by' => $this->otherSchoolUser->id,
            'title' => 'Pinecrest Sports Meet',
            'content' => 'Exclusive to Pinecrest.',
            'status' => Notice::STATUS_PUBLISHED,
        ]);

        // When Pinecrest Admin views index, Oakridge notice must not be shown
        $response = $this->actingAs($this->otherSchoolUser, 'web')
            ->get(route('admin.notices.index'));

        $response->assertOk()
            ->assertSee('Pinecrest Sports Meet')
            ->assertDontSee('Oakridge Secret Announcement');
    }

    public function test_can_toggle_pinned_status(): void
    {
        $notice = Notice::create([
            'school_id' => $this->school->id,
            'created_by' => $this->adminUser->id,
            'title' => 'Important Holiday Circular',
            'content' => 'School is closed tomorrow.',
            'is_pinned' => false,
            'status' => Notice::STATUS_PUBLISHED,
        ]);

        $response = $this->actingAs($this->adminUser, 'web')
            ->post(route('admin.notices.toggle-pin', $notice));

        $response->assertRedirect();
        $this->assertTrue($notice->fresh()->is_pinned);

        // Toggle back to unpinned
        $this->actingAs($this->adminUser, 'web')
            ->post(route('admin.notices.toggle-pin', $notice));

        $this->assertFalse($notice->fresh()->is_pinned);
    }

    public function test_bulk_delete_and_restore_workflow(): void
    {
        $notice1 = Notice::create([
            'school_id' => $this->school->id,
            'title' => 'Notice 1',
            'content' => 'Content 1',
            'status' => Notice::STATUS_PUBLISHED,
        ]);

        $notice2 = Notice::create([
            'school_id' => $this->school->id,
            'title' => 'Notice 2',
            'content' => 'Content 2',
            'status' => Notice::STATUS_PUBLISHED,
        ]);

        // Bulk Move to Trash
        $response = $this->actingAs($this->adminUser, 'web')
            ->post(route('admin.notices.bulk-action'), [
                'action' => 'delete',
                'ids' => [$notice1->id, $notice2->id],
            ]);

        $response->assertRedirect();
        $this->assertSoftDeleted('notices', ['id' => $notice1->id]);
        $this->assertSoftDeleted('notices', ['id' => $notice2->id]);

        // Bulk Restore
        $restoreResponse = $this->actingAs($this->adminUser, 'web')
            ->post(route('admin.notices.bulk-action'), [
                'action' => 'restore',
                'ids' => [$notice1->id, $notice2->id],
            ]);

        $restoreResponse->assertRedirect();
        $this->assertNull($notice1->fresh()->deleted_at);
        $this->assertNull($notice2->fresh()->deleted_at);
    }

    public function test_force_delete_unlinks_attachment_file(): void
    {
        $file = UploadedFile::fake()->create('flyer.png', 200, 'image/png');
        $storedPath = $file->store("uploads/notices/{$this->school->id}", 'public');

        $notice = Notice::create([
            'school_id' => $this->school->id,
            'created_by' => $this->adminUser->id,
            'title' => 'Event Flyer',
            'content' => 'Attached is the event poster.',
            'attachment_path' => $storedPath,
            'attachment_name' => 'flyer.png',
            'status' => Notice::STATUS_PUBLISHED,
        ]);

        Storage::disk('public')->assertExists($storedPath);

        $response = $this->actingAs($this->adminUser, 'web')
            ->delete(route('admin.notices.force-delete', $notice->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('notices', ['id' => $notice->id]);
        Storage::disk('public')->assertMissing($storedPath);
    }

    public function test_home_page_renders_notice_board_and_removes_headmaster_desk(): void
    {
        $response = $this->get(route('frontend.home'));

        $response->assertStatus(200);
        $response->assertSee('নোটিশ বোর্ড');
        $response->assertSee('সকল নোটিশ');
        $response->assertSee('দেখুন');
        $response->assertDontSee('প্রধান শিক্ষকের বাণী');
        $response->assertDontSee('Headmaster Desk');
    }

    public function test_notice_board_displays_notices_with_view_links(): void
    {
        Notice::create([
            'school_id' => $this->school->id,
            'created_by' => $this->adminUser->id,
            'title' => 'নির্বাচনী পরীক্ষা ২০২৬ খ্রি. রুটিন।',
            'slug' => 'election-exam-routine-2026-test',
            'content' => 'পরীক্ষার বিস্তারিত রুটিন ও নির্দেশিকা।',
            'type' => 'general',
            'status' => Notice::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);

        $response = $this->get(route('frontend.home'));

        $response->assertStatus(200);
        $response->assertSee('নির্বাচনী পরীক্ষা ২০২৬ খ্রি. রুটিন।');
        $response->assertSee(route('frontend.notices.show', 'election-exam-routine-2026-test'));
        $response->assertSee('সাধারণ');
    }

    public function test_public_notice_show_page_renders_successfully(): void
    {
        $notice = Notice::create([
            'school_id' => $this->school->id,
            'created_by' => $this->adminUser->id,
            'title' => 'নির্বাচনী পরীক্ষা- ২০২৬ খ্রি. দশম শ্রেণী ভোকেশনাল',
            'slug' => 'election-exam-2026-class-10-vocational-test',
            'content' => 'ভোকেশনাল শাখার নির্বাচনী পরীক্ষার নোটিশ।',
            'type' => 'exam',
            'status' => Notice::STATUS_PUBLISHED,
            'published_at' => now(),
        ]);

        $response = $this->get(route('frontend.notices.show', $notice->slug));

        $response->assertStatus(200);
        $response->assertSee('নির্বাচনী পরীক্ষা- ২০২৬ খ্রি. দশম শ্রেণী ভোকেশনাল');
        $response->assertSee('ভোকেশনাল শাখার নির্বাচনী পরীক্ষার নোটিশ।');
    }
}
