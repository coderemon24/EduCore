<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\ExamSubjectComponent;
use App\Models\GradeTier;
use App\Models\GradingScale;
use App\Models\MarksheetSetting;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();

        foreach ($schools as $school) {
            // 1. Marksheet Settings
            MarksheetSetting::updateOrCreate(
                ['school_id' => $school->id],
                [
                    'headmaster_title' => 'Head Master',
                    'header_title' => $school->name ?: 'Joypurhat Government Girls\' High School',
                    'sub_header_title' => 'HALF YEARLY EXAMINATION-2026',
                    'show_highest_marks' => true,
                    'show_attendance_report' => true,
                    'show_extra_activities' => true,
                    'show_gpa_grading_scale' => true,
                ]
            );

            // 2. Default Grading Scale (National GPA 5.0)
            $scale = GradingScale::updateOrCreate(
                ['school_id' => $school->id, 'name' => 'National GPA 5.0 Scale'],
                [
                    'description' => 'Standard national secondary and higher secondary grading scale.',
                    'fail_any_fails_all' => true,
                    'is_default' => true,
                    'is_active' => true,
                ]
            );

            $tiers = [
                ['grade' => 'A+', 'point' => 5.00, 'min_percentage' => 80.00, 'max_percentage' => 100.00, 'is_pass' => true, 'remarks' => 'Outstanding', 'order_index' => 1],
                ['grade' => 'A',  'point' => 4.00, 'min_percentage' => 70.00, 'max_percentage' => 79.99,  'is_pass' => true, 'remarks' => 'Excellent',   'order_index' => 2],
                ['grade' => 'A-', 'point' => 3.50, 'min_percentage' => 60.00, 'max_percentage' => 69.99,  'is_pass' => true, 'remarks' => 'Very Good',   'order_index' => 3],
                ['grade' => 'B',  'point' => 3.00, 'min_percentage' => 50.00, 'max_percentage' => 59.99,  'is_pass' => true, 'remarks' => 'Good',        'order_index' => 4],
                ['grade' => 'C',  'point' => 2.00, 'min_percentage' => 40.00, 'max_percentage' => 49.99,  'is_pass' => true, 'remarks' => 'Satisfactory', 'order_index' => 5],
                ['grade' => 'D',  'point' => 1.00, 'min_percentage' => 33.00, 'max_percentage' => 39.99,  'is_pass' => true, 'remarks' => 'Pass',        'order_index' => 6],
                ['grade' => 'F',  'point' => 0.00, 'min_percentage' => 0.00,  'max_percentage' => 32.99,  'is_pass' => false, 'remarks' => 'Fail',        'order_index' => 7],
            ];

            foreach ($tiers as $tierData) {
                GradeTier::updateOrCreate(
                    [
                        'grading_scale_id' => $scale->id,
                        'grade' => $tierData['grade'],
                    ],
                    $tierData
                );
            }

            // 3. Sample Exam
            $year = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first()
                ?? AcademicYear::where('school_id', $school->id)->first();

            if (! $year) {
                continue;
            }

            $exam = Exam::updateOrCreate(
                [
                    'school_id' => $school->id,
                    'academic_year_id' => $year->id,
                    'title' => 'Half Yearly Examination 2026',
                ],
                [
                    'grading_scale_id' => $scale->id,
                    'exam_type' => 'half_yearly',
                    'start_date' => Carbon::now()->subDays(10)->toDateString(),
                    'end_date' => Carbon::now()->addDays(10)->toDateString(),
                    'description' => 'Comprehensive mid-term evaluation across all academic subjects.',
                    'status' => 'marks_entry',
                    'is_published' => false,
                ]
            );

            // 4. Sample Exam Subjects for Active Classes
            $classes = AcademicClass::where('school_id', $school->id)->take(2)->get();
            $subjects = Subject::where('school_id', $school->id)->get();

            if ($subjects->isEmpty()) {
                // Ensure at least core subjects exist
                $coreSubjects = [
                    ['name' => 'Bengali 1st Paper', 'code' => '101', 'type' => 'theory'],
                    ['name' => 'English 1st Paper', 'code' => '107', 'type' => 'theory'],
                    ['name' => 'Mathematics', 'code' => '109', 'type' => 'theory'],
                    ['name' => 'Physics', 'code' => '136', 'type' => 'practical'],
                    ['name' => 'Physical Education', 'code' => '147', 'type' => 'theory'],
                ];
                foreach ($coreSubjects as $subj) {
                    Subject::firstOrCreate(
                        ['school_id' => $school->id, 'code' => $subj['code']],
                        array_merge($subj, ['school_id' => $school->id])
                    );
                }
                $subjects = Subject::where('school_id', $school->id)->get();
            }

            foreach ($classes as $class) {
                foreach ($subjects->take(5) as $idx => $subject) {
                    $examSubj = ExamSubject::updateOrCreate(
                        [
                            'school_id' => $school->id,
                            'exam_id' => $exam->id,
                            'academic_class_id' => $class->id,
                            'subject_id' => $subject->id,
                        ],
                        [
                            'full_marks' => $subject->type === 'practical' ? 100.00 : ($subject->code === '147' ? 50.00 : 100.00),
                            'pass_marks' => $subject->code === '147' ? 17.00 : 33.00,
                            'order_index' => $idx + 1,
                        ]
                    );

                    // Dynamic components
                    if ($subject->type === 'practical') {
                        // Written 50, MCQ 25, Practical 25
                        $components = [
                            ['name' => 'Written', 'max_marks' => 50.00, 'pass_marks' => 17.00, 'order_index' => 1],
                            ['name' => 'MCQ', 'max_marks' => 25.00, 'pass_marks' => 8.00, 'order_index' => 2],
                            ['name' => 'Practical', 'max_marks' => 25.00, 'pass_marks' => 8.00, 'order_index' => 3],
                        ];
                    } elseif ($subject->code === '147') {
                        // CA only 50
                        $components = [
                            ['name' => 'CA', 'max_marks' => 50.00, 'pass_marks' => 17.00, 'order_index' => 1],
                        ];
                    } else {
                        // Written 70, MCQ 30
                        $components = [
                            ['name' => 'Written', 'max_marks' => 70.00, 'pass_marks' => 23.00, 'order_index' => 1],
                            ['name' => 'MCQ', 'max_marks' => 30.00, 'pass_marks' => 10.00, 'order_index' => 2],
                        ];
                    }

                    foreach ($components as $compData) {
                        ExamSubjectComponent::updateOrCreate(
                            [
                                'exam_subject_id' => $examSubj->id,
                                'name' => $compData['name'],
                            ],
                            $compData
                        );
                    }
                }
            }
        }
    }
}
