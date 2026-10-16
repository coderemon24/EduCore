<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
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

        $academicYear = AcademicYear::withoutGlobalScopes()
            ->where('school_id', $school->id)
            ->where('is_current', true)
            ->first() ?? AcademicYear::withoutGlobalScopes()->where('school_id', $school->id)->first();

        $classes = AcademicClass::withoutGlobalScopes()
            ->where('school_id', $school->id)
            ->with('sections')
            ->get();

        if (! $academicYear || $classes->isEmpty()) {
            return;
        }

        $sampleStudents = [
            [
                'admission_number' => 'ADM-2026-0001',
                'admission_date' => '2026-01-10',
                'first_name' => 'Alexander',
                'last_name' => 'Wright',
                'gender' => 'male',
                'date_of_birth' => '2010-04-15',
                'blood_group' => 'O+',
                'religion' => 'Christianity',
                'email' => 'alexander.wright@student.educore.edu',
                'phone' => '+1 (555) 234-5678',
                'guardian_name' => 'Robert Wright',
                'guardian_phone' => '+1 (555) 987-6543',
                'guardian_relation' => 'father',
                'roll_number' => '101',
            ],
            [
                'admission_number' => 'ADM-2026-0002',
                'admission_date' => '2026-01-12',
                'first_name' => 'Sophia',
                'last_name' => 'Chen',
                'gender' => 'female',
                'date_of_birth' => '2010-09-22',
                'blood_group' => 'A+',
                'religion' => 'Buddhism',
                'email' => 'sophia.chen@student.educore.edu',
                'phone' => '+1 (555) 345-6789',
                'guardian_name' => 'David Chen',
                'guardian_phone' => '+1 (555) 876-5432',
                'guardian_relation' => 'father',
                'roll_number' => '102',
            ],
            [
                'admission_number' => 'ADM-2026-0003',
                'admission_date' => '2026-01-15',
                'first_name' => 'Liam',
                'last_name' => 'O\'Connor',
                'gender' => 'male',
                'date_of_birth' => '2011-02-18',
                'blood_group' => 'B+',
                'religion' => 'Christianity',
                'email' => 'liam.oconnor@student.educore.edu',
                'phone' => '+1 (555) 456-7890',
                'guardian_name' => 'Mary O\'Connor',
                'guardian_phone' => '+1 (555) 765-4321',
                'guardian_relation' => 'mother',
                'roll_number' => '103',
            ],
            [
                'admission_number' => 'ADM-2026-0004',
                'admission_date' => '2026-01-18',
                'first_name' => 'Zainab',
                'last_name' => 'Khan',
                'gender' => 'female',
                'date_of_birth' => '2011-06-30',
                'blood_group' => 'AB+',
                'religion' => 'Islam',
                'email' => 'zainab.khan@student.educore.edu',
                'phone' => '+1 (555) 567-8901',
                'guardian_name' => 'Tariq Khan',
                'guardian_phone' => '+1 (555) 654-3210',
                'guardian_relation' => 'father',
                'roll_number' => '104',
            ],
            [
                'admission_number' => 'ADM-2026-0005',
                'admission_date' => '2026-01-20',
                'first_name' => 'Ethan',
                'last_name' => 'Miller',
                'gender' => 'male',
                'date_of_birth' => '2012-11-05',
                'blood_group' => 'A-',
                'religion' => 'Christianity',
                'email' => 'ethan.miller@student.educore.edu',
                'phone' => '+1 (555) 678-9012',
                'guardian_name' => 'Sarah Miller',
                'guardian_phone' => '+1 (555) 543-2109',
                'guardian_relation' => 'mother',
                'roll_number' => '105',
            ],
        ];

        $firstClass = $classes->first();
        $firstSection = $firstClass?->sections->first();

        foreach ($sampleStudents as $data) {
            $student = Student::withoutGlobalScopes()->updateOrCreate(
                ['school_id' => $school->id, 'admission_number' => $data['admission_number']],
                [
                    'admission_date' => $data['admission_date'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'gender' => $data['gender'],
                    'date_of_birth' => $data['date_of_birth'],
                    'blood_group' => $data['blood_group'],
                    'religion' => $data['religion'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'current_address' => '742 Evergreen Terrace, Springfield, OR',
                    'permanent_address' => '742 Evergreen Terrace, Springfield, OR',
                    'status' => 'active',
                ]
            );

            // Create/link Guardian
            $guardian = Guardian::withoutGlobalScopes()->updateOrCreate(
                ['school_id' => $school->id, 'phone' => $data['guardian_phone']],
                [
                    'name' => $data['guardian_name'],
                    'email' => strtolower(str_replace(' ', '.', $data['guardian_name'])).'@gmail.com',
                    'occupation' => 'Professional',
                    'address' => '742 Evergreen Terrace, Springfield, OR',
                    'national_id' => 'NID-'.rand(10000000, 99999999),
                ]
            );

            $student->guardians()->syncWithoutDetaching([
                $guardian->id => [
                    'school_id' => $school->id,
                    'relationship' => $data['guardian_relation'],
                    'is_primary' => true,
                    'is_emergency_contact' => true,
                ],
            ]);

            // Create current enrollment
            StudentEnrollment::withoutGlobalScopes()->updateOrCreate(
                [
                    'school_id' => $school->id,
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear->id,
                ],
                [
                    'academic_class_id' => $firstClass->id,
                    'section_id' => $firstSection?->id,
                    'roll_number' => $data['roll_number'],
                    'status' => 'current',
                    'enrolled_at' => $data['admission_date'],
                    'remarks' => 'Enrolled via academic admissions seeder.',
                ]
            );
        }
    }
}
