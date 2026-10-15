<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'full_name' => 'System Administrator',
                'bio' => 'Lead Administrator for EduCore School Management Portal.',
                'designation' => 'Super Administrator',
                'role' => 'super_admin',
                'profile_image' => 'admin-assets/images/default-avatar.png',
                'cover_image' => null,
            ]
        );
    }
}
