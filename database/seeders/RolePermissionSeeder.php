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

            // Parents & Guardians Management
            ['name' => 'View Parents & Guardians', 'slug' => 'guardians.view', 'group' => 'guardians', 'description' => 'View parent and guardian directory, family contacts, and ward associations.'],
            ['name' => 'Manage Parents & Guardians', 'slug' => 'guardians.manage', 'group' => 'guardians', 'description' => 'Edit guardian contact information, address, and occupation details.'],
            ['name' => 'Delete Guardians', 'slug' => 'guardians.delete', 'group' => 'guardians', 'description' => 'Soft delete, restore, or permanently purge guardian records.'],
            // Academic Structure
            ['name' => 'View Academic Structure', 'slug' => 'academic.view', 'group' => 'academic', 'description' => 'View classes, sections, subjects, and sessions.'],
            ['name' => 'Manage Classes', 'slug' => 'academic.classes', 'group' => 'academic', 'description' => 'Create, modify, and manage academic grade levels.'],
            ['name' => 'Manage Sections', 'slug' => 'academic.sections', 'group' => 'academic', 'description' => 'Configure sections and class teacher appointments.'],
            ['name' => 'Manage Subjects', 'slug' => 'academic.subjects', 'group' => 'academic', 'description' => 'Catalog curriculum courses and subjects.'],
            ['name' => 'Manage Assignments', 'slug' => 'academic.assignments', 'group' => 'academic', 'description' => 'Assign subject instructors and marks curriculum.'],

            // Attendance Management
            ['name' => 'View Attendance', 'slug' => 'attendance.view', 'group' => 'attendance', 'description' => 'View student and staff attendance records and registers.'],
            ['name' => 'Mark Student Attendance', 'slug' => 'attendance.students.mark', 'group' => 'attendance', 'description' => 'Record and modify daily student roll-call.'],
            ['name' => 'Mark Staff Attendance', 'slug' => 'attendance.staff.mark', 'group' => 'attendance', 'description' => 'Record faculty and staff daily attendance.'],
            ['name' => 'View Attendance Reports', 'slug' => 'attendance.reports', 'group' => 'attendance', 'description' => 'Generate monthly attendance register and reports.'],

            // Timetables & Schedules
            ['name' => 'View Timetables', 'slug' => 'timetables.view', 'group' => 'timetables', 'description' => 'View weekly class timetables and teacher schedules.'],
            ['name' => 'Manage Timetables', 'slug' => 'timetables.manage', 'group' => 'timetables', 'description' => 'Create, edit, and schedule timetable slots.'],

            // Academic Holidays & Calendar
            ['name' => 'Manage Holidays', 'slug' => 'academic.holidays', 'group' => 'academic', 'description' => 'Configure school holidays and non-working days.'],

            // Roles & Permissions
            ['name' => 'View Roles', 'slug' => 'roles.view', 'group' => 'roles', 'description' => 'View institutional access roles and permission matrices.'],
            ['name' => 'Manage Roles', 'slug' => 'roles.manage', 'group' => 'roles', 'description' => 'Create and customize roles and grant permission sets.'],

            // Institution Settings
            ['name' => 'Manage Institution Settings', 'slug' => 'settings.school.edit', 'group' => 'settings', 'description' => 'Modify school profile, contact information, and logo.'],

            // Fees & Accounts Management
            ['name' => 'View Fees & Accounts', 'slug' => 'fees.view', 'group' => 'fees', 'description' => 'View invoices, dues directory, fee structures, and collection history.'],
            ['name' => 'Manage Fee Structures', 'slug' => 'fees.structure.manage', 'group' => 'fees', 'description' => 'Create and configure fee categories and class fee rates.'],
            ['name' => 'Generate Invoices', 'slug' => 'fees.invoice.create', 'group' => 'fees', 'description' => 'Issue single and bulk student fee invoices.'],
            ['name' => 'Collect Fee Payments', 'slug' => 'fees.collect', 'group' => 'fees', 'description' => 'Accept offline fee payments and issue receipts.'],
            ['name' => 'Authorize Fee Discounts', 'slug' => 'fees.discount', 'group' => 'fees', 'description' => 'Apply authorized fee discounts and waivers to invoices.'],
            ['name' => 'Reverse Payments', 'slug' => 'fees.reverse', 'group' => 'fees', 'description' => 'Authorize and execute safe payment reversals and voids.'],
            ['name' => 'View Financial Reports', 'slug' => 'fees.reports', 'group' => 'fees', 'description' => 'Access daily collection registers, method summaries, and dues reports.'],

            // Examinations & Grading Management
            ['name' => 'View Exams & Results', 'slug' => 'exams.view', 'group' => 'exams', 'description' => 'View exam schedules, marks entries, results tabulation, and report cards.'],
            ['name' => 'Manage Exams & Schedules', 'slug' => 'exams.manage', 'group' => 'exams', 'description' => 'Create, schedule, and configure exams and subject assessment components.'],
            ['name' => 'Manage Grading Scales', 'slug' => 'exams.grades.manage', 'group' => 'exams', 'description' => 'Configure institutional grading scales, grade tiers, and GPA rules.'],
            ['name' => 'Enter Student Marks', 'slug' => 'exams.marks.entry', 'group' => 'exams', 'description' => 'Record, update, and submit student marks via high-speed table entry.'],
            ['name' => 'Calculate Results & Ranks', 'slug' => 'exams.results.calculate', 'group' => 'exams', 'description' => 'Execute automated result tabulation and compute class/section ranks.'],
            ['name' => 'Publish Exam Results', 'slug' => 'exams.results.publish', 'group' => 'exams', 'description' => 'Officially publish exam results and lock marks from unauthorized modifications.'],
            ['name' => 'Correct Published Marks', 'slug' => 'exams.marks.correct', 'group' => 'exams', 'description' => 'Authorize post-publication mark revisions with mandatory audit logging.'],
            ['name' => 'Generate Report Cards', 'slug' => 'exams.reports.generate', 'group' => 'exams', 'description' => 'Preview, print, and export official student mark sheets and report cards.'],
        ];

        foreach ($permissions as $permData) {
            Permission::updateOrCreate(
                ['slug' => $permData['slug']],
                $permData
            );
        }

        $allPermissionIds = Permission::pluck('id')->toArray();
        $accountantPermissionIds = Permission::whereIn('slug', [
            'students.view',
            'staff.view',
            'guardians.view',
            'fees.view',
            'fees.structure.manage',
            'fees.invoice.create',
            'fees.collect',
            'fees.discount',
            'fees.reverse',
            'fees.reports',
        ])->pluck('id')->toArray();
        $teacherPermissionIds = Permission::whereIn('slug', [
            'students.view',
            'staff.view',
            'academic.view',
            'attendance.view',
            'attendance.students.mark',
            'timetables.view',
            'exams.view',
            'exams.marks.entry',
            'exams.reports.generate',
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
            $accountantRole->permissions()->sync($accountantPermissionIds);

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
