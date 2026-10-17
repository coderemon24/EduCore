<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\School;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Student Management
            ['name' => 'View Students', 'slug' => 'students.view', 'group' => 'students', 'description' => 'View student roster, enrollment details, and profiles.'],
            ['name' => 'Create Students', 'slug' => 'students.create', 'group' => 'students', 'description' => 'Admit and register new student candidates.'],
            ['name' => 'Edit Students', 'slug' => 'students.edit', 'group' => 'students', 'description' => 'Modify student personal information and enrollment records.'],
            ['name' => 'Delete Students', 'slug' => 'students.delete', 'group' => 'students', 'description' => 'Soft delete or permanently purge student profiles.'],
            ['name' => 'Promote Students', 'slug' => 'students.promote', 'group' => 'students', 'description' => 'Execute individual or bulk grade-level promotions.'],

            // Staff & Faculty Management
            ['name' => 'View Staff', 'slug' => 'staff.view', 'group' => 'staff', 'description' => 'View staff and teacher directory and profiles.'],
            ['name' => 'Create Staff', 'slug' => 'staff.create', 'group' => 'staff', 'description' => 'Onboard new faculty and administrative personnel.'],
            ['name' => 'Edit Staff', 'slug' => 'staff.edit', 'group' => 'staff', 'description' => 'Update staff employment terms and contact information.'],
            ['name' => 'Delete Staff', 'slug' => 'staff.delete', 'group' => 'staff', 'description' => 'Delete staff records from institutional roster.'],
            ['name' => 'Manage Staff Status', 'slug' => 'staff.manage_status', 'group' => 'staff', 'description' => 'Activate, deactivate, or suspend staff access.'],

            // Academic Structure
            ['name' => 'View Academic Structure', 'slug' => 'academic.view', 'group' => 'academic', 'description' => 'View classes, sections, subjects, and sessions.'],
            ['name' => 'Manage Classes', 'slug' => 'academic.classes', 'group' => 'academic', 'description' => 'Create, modify, and manage academic grade levels.'],
            ['name' => 'Manage Sections', 'slug' => 'academic.sections', 'group' => 'academic', 'description' => 'Configure sections and class teacher appointments.'],
            ['name' => 'Manage Subjects', 'slug' => 'academic.subjects', 'group' => 'academic', 'description' => 'Catalog curriculum courses and subjects.'],
            ['name' => 'Manage Assignments', 'slug' => 'academic.assignments', 'group' => 'academic', 'description' => 'Assign subject instructors and marks curriculum.'],

            // Roles & Permissions
            ['name' => 'View Roles', 'slug' => 'roles.view', 'group' => 'roles', 'description' => 'View institutional access roles and permission matrices.'],
            ['name' => 'Manage Roles', 'slug' => 'roles.manage', 'group' => 'roles', 'description' => 'Create and customize roles and grant permission sets.'],

            // Institution Settings
            ['name' => 'Manage Institution Settings', 'slug' => 'settings.school.edit', 'group' => 'settings', 'description' => 'Modify school profile, contact information, and logo.'],
        ];

        foreach ($permissions as $permData) {
            Permission::updateOrCreate(
                ['slug' => $permData['slug']],
                $permData
            );
        }

        $allPermissionIds = Permission::pluck('id')->toArray();
        $teacherPermissionIds = Permission::whereIn('slug', [
            'students.view',
            'staff.view',
            'academic.view',
        ])->pluck('id')->toArray();

        $schools = School::all();

        foreach ($schools as $school) {
            // 1. School Administrator Role
            $adminRole = Role::updateOrCreate(
                ['school_id' => $school->id, 'slug' => 'school_admin'],
                [
                    'name' => 'School Administrator',
                    'description' => 'Full administrative access to institution management, academic structures, students, and staff.',
                    'is_system' => true,
                ]
            );
            $adminRole->permissions()->sync($allPermissionIds);

            // 2. Teacher Role
            $teacherRole = Role::updateOrCreate(
                ['school_id' => $school->id, 'slug' => 'teacher'],
                [
                    'name' => 'Teacher / Faculty',
                    'description' => 'Access to student directory, academic schedules, and assigned curriculum classes.',
                    'is_system' => true,
                ]
            );
            $teacherRole->permissions()->sync($teacherPermissionIds);

            // 3. Accountant Role
            $accountantRole = Role::updateOrCreate(
                ['school_id' => $school->id, 'slug' => 'accountant'],
                [
                    'name' => 'Accountant',
                    'description' => 'Access to student fee records, invoices, and payment registries.',
                    'is_system' => true,
                ]
            );
            $accountantRole->permissions()->sync(
                Permission::whereIn('slug', ['students.view', 'staff.view'])->pluck('id')->toArray()
            );

            // 4. Staff Member Role
            $staffRole = Role::updateOrCreate(
                ['school_id' => $school->id, 'slug' => 'staff_member'],
                [
                    'name' => 'General Staff',
                    'description' => 'Standard institutional access for non-academic operational personnel.',
                    'is_system' => true,
                ]
            );
            $staffRole->permissions()->sync(
                Permission::whereIn('slug', ['staff.view'])->pluck('id')->toArray()
            );

            // Default School Admin User for First School
            if ($school->id === $schools->first()->id) {
                $schoolUser = User::updateOrCreate(
                    ['email' => 'schooladmin@educore.test'],
                    [
                        'school_id' => $school->id,
                        'name' => 'Principal Marcus Vance',
                        'password' => Hash::make('password'),
                        'status' => 'active',
                        'phone' => '+1 (555) 234-5678',
                    ]
                );

                $schoolUser->roles()->sync([$adminRole->id]);

                Staff::updateOrCreate(
                    ['school_id' => $school->id, 'staff_code' => 'EMP-0001'],
                    [
                        'user_id' => $schoolUser->id,
                        'first_name' => 'Marcus',
                        'last_name' => 'Vance',
                        'gender' => 'Male',
                        'email' => 'schooladmin@educore.test',
                        'phone' => '+1 (555) 234-5678',
                        'is_teaching' => false,
                        'department' => 'Administration',
                        'designation' => 'Principal & Head of School',
                        'qualification' => 'Ph.D. in Educational Leadership',
                        'experience_years' => 15,
                        'joining_date' => '2020-08-01',
                        'basic_salary' => 8500.00,
                        'contract_type' => 'permanent',
                        'status' => 'active',
                    ]
                );
            }
        }
    }
}
