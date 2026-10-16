<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\AcademicGroup;
use App\Models\AcademicYear;
use App\Models\ClassSubjectAssignment;
use App\Models\School;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class AcademicSeeder extends Seeder
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

        // 1. Academic Years
        $academicYear2025 = AcademicYear::withoutGlobalScopes()->updateOrCreate(
            ['school_id' => $school->id, 'name' => '2025–2026'],
            [
                'start_date' => '2025-01-01',
                'end_date' => '2025-12-31',
                'is_current' => true,
                'status' => 'active',
                'notes' => 'Current operating academic session.',
            ]
        );

        AcademicYear::withoutGlobalScopes()->updateOrCreate(
            ['school_id' => $school->id, 'name' => '2024–2025'],
            [
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'is_current' => false,
                'status' => 'inactive',
                'notes' => 'Archived previous academic session.',
            ]
        );

        AcademicYear::withoutGlobalScopes()->updateOrCreate(
            ['school_id' => $school->id, 'name' => '2026–2027'],
            [
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'is_current' => false,
                'status' => 'inactive',
                'notes' => 'Upcoming academic session for admissions planning.',
            ]
        );

        // 2. Academic Groups / Streams
        $groups = [
            ['name' => 'General', 'code' => 'GEN', 'description' => 'General foundation stream for primary and junior secondary grades.'],
            ['name' => 'Science', 'code' => 'SCI', 'description' => 'Science & STEM curriculum stream.'],
            ['name' => 'Business Studies', 'code' => 'BUS', 'description' => 'Commerce, accounting, and business administration stream.'],
            ['name' => 'Humanities', 'code' => 'HUM', 'description' => 'Arts, literature, and social sciences stream.'],
        ];

        foreach ($groups as $groupData) {
            AcademicGroup::withoutGlobalScopes()->updateOrCreate(
                ['school_id' => $school->id, 'name' => $groupData['name']],
                [
                    'code' => $groupData['code'],
                    'description' => $groupData['description'],
                    'status' => 'active',
                ]
            );
        }

        // 3. Subjects Catalog
        $subjectsData = [
            ['name' => 'English Language', 'code' => 'ENG-101', 'type' => 'theory', 'credit_hours' => 3.00],
            ['name' => 'General Mathematics', 'code' => 'MTH-101', 'type' => 'theory', 'credit_hours' => 4.00],
            ['name' => 'General Science', 'code' => 'SCI-101', 'type' => 'both', 'credit_hours' => 3.50],
            ['name' => 'Information Technology', 'code' => 'ICT-101', 'type' => 'both', 'credit_hours' => 3.00],
            ['name' => 'Social Studies', 'code' => 'SOC-101', 'type' => 'theory', 'credit_hours' => 3.00],
            ['name' => 'Physics', 'code' => 'PHY-201', 'type' => 'both', 'credit_hours' => 4.00],
            ['name' => 'Chemistry', 'code' => 'CHM-201', 'type' => 'both', 'credit_hours' => 4.00],
            ['name' => 'Biology', 'code' => 'BIO-201', 'type' => 'both', 'credit_hours' => 4.00],
            ['name' => 'Principles of Accounting', 'code' => 'ACC-201', 'type' => 'theory', 'credit_hours' => 4.00],
            ['name' => 'Business Organization', 'code' => 'BUS-201', 'type' => 'theory', 'credit_hours' => 3.00],
        ];

        $subjectModels = [];
        foreach ($subjectsData as $sData) {
            $subjectModels[$sData['code']] = Subject::withoutGlobalScopes()->updateOrCreate(
                ['school_id' => $school->id, 'code' => $sData['code']],
                [
                    'name' => $sData['name'],
                    'type' => $sData['type'],
                    'credit_hours' => $sData['credit_hours'],
                    'status' => 'active',
                ]
            );
        }

        // 4. Academic Classes & Sections (Grade 1 - 10)
        for ($i = 1; $i <= 10; $i++) {
            $className = "Grade {$i}";
            $academicClass = AcademicClass::withoutGlobalScopes()->updateOrCreate(
                ['school_id' => $school->id, 'name' => $className],
                [
                    'numeric_code' => $i,
                    'order_index' => $i,
                    'description' => "Standard academic grade {$i} curriculum level.",
                    'status' => 'active',
                ]
            );

            // Sections A & B
            Section::withoutGlobalScopes()->updateOrCreate(
                ['school_id' => $school->id, 'academic_class_id' => $academicClass->id, 'name' => 'Section A'],
                [
                    'capacity' => 40,
                    'room_number' => "Room {$i}01",
                    'status' => 'active',
                ]
            );

            Section::withoutGlobalScopes()->updateOrCreate(
                ['school_id' => $school->id, 'academic_class_id' => $academicClass->id, 'name' => 'Section B'],
                [
                    'capacity' => 40,
                    'room_number' => "Room {$i}02",
                    'status' => 'active',
                ]
            );

            // Assign core foundation subjects
            $coreCodes = ['ENG-101', 'MTH-101', 'SCI-101', 'ICT-101', 'SOC-101'];
            foreach ($coreCodes as $code) {
                if (isset($subjectModels[$code])) {
                    ClassSubjectAssignment::withoutGlobalScopes()->updateOrCreate(
                        [
                            'school_id' => $school->id,
                            'academic_class_id' => $academicClass->id,
                            'subject_id' => $subjectModels[$code]->id,
                            'academic_year_id' => $academicYear2025->id,
                        ],
                        [
                            'type' => 'compulsory',
                            'full_marks' => 100,
                            'pass_marks' => 33,
                        ]
                    );
                }
            }

            // For Grades 9 & 10, add science and business electives
            if ($i >= 9) {
                $electiveCodes = ['PHY-201', 'CHM-201', 'BIO-201', 'ACC-201', 'BUS-201'];
                foreach ($electiveCodes as $code) {
                    if (isset($subjectModels[$code])) {
                        ClassSubjectAssignment::withoutGlobalScopes()->updateOrCreate(
                            [
                                'school_id' => $school->id,
                                'academic_class_id' => $academicClass->id,
                                'subject_id' => $subjectModels[$code]->id,
                                'academic_year_id' => $academicYear2025->id,
                            ],
                            [
                                'type' => 'elective',
                                'full_marks' => 100,
                                'pass_marks' => 40,
                            ]
                        );
                    }
                }
            }
        }
    }
}
