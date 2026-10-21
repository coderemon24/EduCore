<?php

namespace Database\Seeders;

use App\Models\AboutPageContent;
use App\Models\School;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::first();

        if (! $school) {
            $school = School::create([
                'name' => 'সোনাতলা উচ্চ বিদ্যালয়',
                'slug' => 'sonatala-high-school',
                'code' => '৮১০৭',
                'status' => 'active',
                'email' => 'sonatala1968@gmail.com',
                'phone' => '+880 1712-345678',
                'address' => 'সোনাতলা, সাঁথিয়া, পাবনা',
                'city' => 'সাঁথিয়া',
                'state' => 'পাবনা',
                'country' => 'বাংলাদেশ',
                'established_year' => 1968,
            ]);
        }

        // Create or update default about page content for this school
        $defaultData = AboutPageContent::forSchool($school->id)->toArray();
        unset($defaultData['id'], $defaultData['banner_image_url'], $defaultData['history_image_url'], $defaultData['og_image_url'], $defaultData['created_at'], $defaultData['updated_at']);

        AboutPageContent::updateOrCreate(
            ['school_id' => $school->id],
            $defaultData
        );
    }
}
