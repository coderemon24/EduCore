<?php

namespace Database\Seeders;

use App\Models\ClassSubjectAssignment;
use App\Models\Role;
use App\Models\School;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::first();

        if (! $school) {
            return;
        }

        $teacherRole = Role::where('school_id', $school->id)->where('slug', 'teacher')->first();

        $staffData = [
            [
                'staff_code' => 'TCH-1001',
                'first_name' => 'Eleanor',
                'last_name' => 'Pemberton',
                'gender' => 'Female',
                'email' => 'eleanor.pemberton@educore.test',
                'phone' => '+1 (555) 301-8841',
                'is_teaching' => true,
                'department' => 'Mathematics',
                'designation' => 'Senior Mathematics Teacher',
                'qualification' => 'M.Sc. in Applied Mathematics',
                'experience_years' => 11,
                'joining_date' => '2021-08-15',
                'basic_salary' => 5200.00,
                'contract_type' => 'permanent',
                'status' => 'active',
            ],
            [
                'staff_code' => 'TCH-1002',
                'first_name' => 'David',
                'last_name' => 'Sterling',
                'gender' => 'Male',
                'email' => 'david.sterling@educore.test',
                'phone' => '+1 (555) 302-9952',
                'is_teaching' => true,
                'department' => 'Science',
                'designation' => 'Physics Instructor',
                'qualification' => 'B.Sc. in Physics, B.Ed.',
                'experience_years' => 8,
                'joining_date' => '2022-01-10',
                'basic_salary' => 4800.00,
                'contract_type' => 'permanent',
                'status' => 'active',
            ],
            [
                'staff_code' => 'TCH-1003',
                'first_name' => 'Sarah',
                'last_name' => 'Jenkins',
                'gender' => 'Female',
                'email' => 'sarah.jenkins@educore.test',
                'phone' => '+1 (555) 303-1123',
                'is_teaching' => true,
                'department' => 'English',
                'designation' => 'English Language Specialist',
                'qualification' => 'M.A. in English Literature',
                'experience_years' => 6,
                'joining_date' => '2023-08-20',
                'basic_salary' => 4500.00,
                'contract_type' => 'permanent',
                'status' => 'active',
            ],
            [
                'staff_code' => 'TCH-1004',
                'first_name' => 'Michael',
                'last_name' => 'Chang',
                'gender' => 'Male',
                'email' => 'michael.chang@educore.test',
                'phone' => '+1 (555) 304-4455',
                'is_teaching' => true,
                'department' => 'Computer Science',
                'designation' => 'STEM & Robotics Instructor',
                'qualification' => 'B.S. in Computer Science',
                'experience_years' => 5,
                'joining_date' => '2024-02-01',
                'basic_salary' => 4600.00,
                'contract_type' => 'permanent',
                'status' => 'active',
            ],
            [
                'staff_code' => 'STF-2001',
                'first_name' => 'Rachel',
                'last_name' => 'Kowalski',
                'gender' => 'Female',
                'email' => 'rachel.kowalski@educore.test',
                'phone' => '+1 (555) 401-7788',
                'is_teaching' => false,
                'department' => 'Finance',
                'designation' => 'Chief Bursar & Accountant',
                'qualification' => 'CPA, B.B.A. in Accounting',
                'experience_years' => 9,
                'joining_date' => '2021-06-01',
                'basic_salary' => 5000.00,
                'contract_type' => 'permanent',
                'status' => 'active',
            ],
            [
                'staff_code' => 'STF-2002',
                'first_name' => 'Thomas',
                'last_name' => 'Miller',
                'gender' => 'Male',
                'email' => 'thomas.miller@educore.test',
                'phone' => '+1 (555) 402-3344',
                'is_teaching' => false,
                'department' => 'Library',
                'designation' => 'Head Librarian',
                'qualification' => 'Master of Library and Information Science',
                'experience_years' => 12,
                'joining_date' => '2019-09-01',
                'basic_salary' => 4200.00,
                'contract_type' => 'permanent',
                'status' => 'active',
            ],
        ];

        foreach ($staffData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'school_id' => $school->id,
                    'name' => "{$data['first_name']} {$data['last_name']}",
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'phone' => $data['phone'],
                ]
            );

            if ($data['is_teaching'] && $teacherRole) {
                $user->roles()->syncWithoutDetaching([$teacherRole->id]);
            }

            $staff = Staff::updateOrCreate(
                ['school_id' => $school->id, 'staff_code' => $data['staff_code']],
                array_merge($data, [
                    'school_id' => $school->id,
                    'user_id' => $user->id,
                ])
            );
        }

        // Link Class Teachers to existing Sections if available
        $eleanor = Staff::where('school_id', $school->id)->where('staff_code', 'TCH-1001')->first();
        $david = Staff::where('school_id', $school->id)->where('staff_code', 'TCH-1002')->first();
        $sarah = Staff::where('school_id', $school->id)->where('staff_code', 'TCH-1003')->first();

        $sections = Section::where('school_id', $school->id)->take(3)->get();
        if ($sections->count() > 0 && $eleanor) {
            $sections[0]->update(['class_teacher_id' => $eleanor->id]);
        }
        if ($sections->count() > 1 && $david) {
            $sections[1]->update(['class_teacher_id' => $david->id]);
        }
        if ($sections->count() > 2 && $sarah) {
            $sections[2]->update(['class_teacher_id' => $sarah->id]);
        }

        // Link Subject Instructors to class_subject_assignments if available
        $assignments = ClassSubjectAssignment::where('school_id', $school->id)->take(4)->get();
        if ($assignments->count() > 0 && $eleanor) {
            $assignments[0]->update(['teacher_id' => $eleanor->id]);
        }
        if ($assignments->count() > 1 && $david) {
            $assignments[1]->update(['teacher_id' => $david->id]);
        }
        if ($assignments->count() > 2 && $sarah) {
            $assignments[2]->update(['teacher_id' => $sarah->id]);
        }
    }
}
