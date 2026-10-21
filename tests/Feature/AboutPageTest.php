<?php

namespace Tests\Feature;

use App\Models\AboutPageContent;
use App\Models\Admin;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'সোনাতলা উচ্চ বিদ্যালয়',
            'slug' => 'sonatala-high-school',
            'code' => '৮১০৭',
            'status' => 'active',
            'email' => 'sonatala@test.com',
            'established_year' => 1968,
        ]);

        $this->admin = Admin::create([
            'username' => 'admin',
            'full_name' => 'System Admin',
            'email' => 'admin@educore.test',
            'password' => bcrypt('password123'),
            'role' => 'super_admin',
        ]);
    }

    public function test_public_about_page_renders_successfully_with_fallbacks(): void
    {
        $response = $this->get(route('frontend.about'));

        $response->assertStatus(200);
        $response->assertSee('সোনাতলা উচ্চ বিদ্যালয়');
        $response->assertSee('ইতিহাস ও ঐতিহ্য');
        $response->assertSee('১৯৬৮');
        $response->assertSee('আমাদের লক্ষ্য');
    }

    public function test_public_about_page_renders_custom_seo_tags(): void
    {
        AboutPageContent::create([
            'school_id' => $this->school->id,
            'title' => 'কাস্টম বিদ্যালয় পরিচিতি',
            'history_heading' => 'আমাদের অতীত ও বর্তমান',
            'history_content' => 'সোনাতলা উচ্চ বিদ্যালয়ের সংক্ষিপ্ত পটভূমি বিবরণী।',
            'meta_title' => 'এসইও টাইটেল — সোনাতলা স্কুল',
            'meta_description' => 'এই বিবরণীটি গুগলে প্রদর্শিত হবে।',
            'meta_keywords' => 'সোনাতলা, পাবনা, স্কুল',
        ]);

        $response = $this->get(route('frontend.about'));

        $response->assertStatus(200);
        $response->assertSee('এসইও টাইটেল — সোনাতলা স্কুল');
        $response->assertSee('এই বিবরণীটি গুগলে প্রদর্শিত হবে।');
        $response->assertSee('সোনাতলা, পাবনা, স্কুল');
    }

    public function test_guest_admin_redirected_from_admin_about_page(): void
    {
        $response = $this->get(route('admin.frontend.pages.about'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_can_access_about_page_management(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.frontend.pages.about'));

        $response->assertStatus(200);
        $response->assertSee('About Page');
        $response->assertSee('Frontend');
    }

    public function test_admin_can_update_about_page_content_and_repeaters(): void
    {
        $payload = [
            'title' => 'সোনাতলা মডেল হাই স্কুল পরিচিতি',
            'subtitle' => 'আধুনিক শিক্ষার অগ্রদূত',
            'established_year' => '১৯৬৮',
            'history_heading' => 'গৌরবোজ্জ্বল সুবর্ণ জয়ন্তী',
            'history_content' => '১৯৬৮ সাল থেকে সফলতার সাথে পরিচালিত হয়ে আসছে।',
            'vision_title' => 'আমাদের স্বপ্ন',
            'vision_text' => 'ডিজিটাল সোনার বাংলাদেশ বিনির্মাণ।',
            'mission_title' => 'আমাদের কর্মপরিকল্পনা',
            'mission_text' => 'গুণগত শিক্ষা নিশ্চিতকরণ।',
            'milestones' => [
                [
                    'year' => '১৯৬৮',
                    'title' => 'প্রতিষ্ঠা',
                    'description' => 'বিদ্যালয়টির শুভ উদ্বোধন।',
                ],
                [
                    'year' => '২০২৪',
                    'title' => 'স্মার্ট রূপান্তর',
                    'description' => 'স্মার্ট ক্লাসরুম চালু।',
                ],
            ],
            'statistics' => [
                [
                    'label' => 'শিক্ষার্থী',
                    'value' => '১৫০০+',
                    'note' => 'নিয়মিত ছাত্র-ছাত্রী',
                ],
            ],
            'at_a_glance' => [
                [
                    'key' => 'EIIN',
                    'value' => '১২৫৬৯৩',
                ],
            ],
            'committee_members' => [
                [
                    'name' => 'জনাব মো: রফিকুল ইসলাম',
                    'designation' => 'সভাপতি',
                    'role' => 'সমাজসেবক',
                ],
            ],
            'facilities' => [
                [
                    'title' => 'কম্পিউটার ল্যাব',
                    'description' => 'উন্নত ল্যাব',
                    'icon' => 'computer',
                ],
            ],
            'meta_title' => 'সোনাতলা হাই স্কুল পরিচিতি',
            'meta_description' => 'পাবনার সাঁথিয়া উপজেলার ঐতিহ্যবাহী বিদ্যালয়।',
            'meta_keywords' => 'স্কুল, সাঁথিয়া, পাবনা',
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.frontend.pages.about.update'), $payload);

        $response->assertRedirect(route('admin.frontend.pages.about'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('about_page_contents', [
            'school_id' => $this->school->id,
            'title' => 'সোনাতলা মডেল হাই স্কুল পরিচিতি',
            'established_year' => '১৯৬৮',
            'meta_title' => 'সোনাতলা হাই স্কুল পরিচিতি',
        ]);
    }

    public function test_admin_can_upload_images_with_validation(): void
    {
        $fakeUploadDir = public_path('uploads/about');
        if (! File::isDirectory($fakeUploadDir)) {
            File::makeDirectory($fakeUploadDir, 0755, true);
        }

        $imageFile = UploadedFile::fake()->image('campus_photo.jpg', 800, 600);

        $payload = [
            'title' => 'ছবি সহ পরিচিতি',
            'history_image_file' => $imageFile,
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.frontend.pages.about.update'), $payload);

        $response->assertRedirect(route('admin.frontend.pages.about'));
        $response->assertSessionHas('success');

        $record = AboutPageContent::where('school_id', $this->school->id)->first();
        $this->assertNotNull($record);
        $this->assertNotNull($record->history_image);
        $this->assertFileExists(public_path($record->history_image));

        // Cleanup
        if (File::exists(public_path($record->history_image))) {
            File::delete(public_path($record->history_image));
        }
    }
}
