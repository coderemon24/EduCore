<?php

namespace Database\Seeders;

use App\Enums\SchoolStatus;
use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        School::updateOrCreate(
            ['slug' => 'educore-academy'],
            [
                'name' => 'EduCore Academy',
                'code' => 'EDU-001',
                'status' => SchoolStatus::Active,
                'email' => 'contact@educore.test',
                'phone' => '+1 (555) 019-2834',
                'address' => '742 Evergreen Terrace, Academic District',
                'city' => 'Springfield',
                'state' => 'Oregon',
                'country' => 'United States',
                'postal_code' => '97477',
                'logo' => 'admin-assets/images/laravel-school-transparent.png',
                'website' => 'https://educore.test',
                'established_year' => 2010,
                'timezone' => 'America/New_York',
                'currency' => 'USD',
            ]
        );
    }
}
