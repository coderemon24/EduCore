<?php

namespace Database\Seeders;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\TimetableSlot;
use Illuminate\Database\Seeder;

class ClassRoutineSeeder extends Seeder
{
    /**
     * Seed realistic class-wise timetables across days and grades.
     */
    public function run(): void
    {
        $school = School::first();
        if (! $school) {
            return;
        }

        $academicYear = AcademicYear::where('school_id', $school->id)->where('is_current', true)->first()
            ?? AcademicYear::where('school_id', $school->id)->first();

        if (! $academicYear) {
            return;
        }

        $teachers = Staff::where('school_id', $school->id)->where('is_teaching', true)->get();
        if ($teachers->isEmpty()) {
            $teachers = Staff::where('school_id', $school->id)->get();
        }

        $subjects = Subject::where('school_id', $school->id)->get()->keyBy('code');
        if ($subjects->isEmpty()) {
            return;
        }

        // Daily Period Schedule (Standard Bangladesh Secondary Timetable)
        $periods = [
            1 => ['start' => '10:00:00', 'end' => '10:45:00'],
            2 => ['start' => '10:45:00', 'end' => '11:30:00'],
            3 => ['start' => '11:30:00', 'end' => '12:15:00'],
            4 => ['start' => '13:00:00', 'end' => '13:45:00'],
            5 => ['start' => '13:45:00', 'end' => '14:30:00'],
            6 => ['start' => '14:30:00', 'end' => '15:15:00'],
        ];

        // Class-specific subject rotations (Grade 6 to Grade 10)
        $schedules = [
            6 => [
                'saturday' => ['ENG-101', 'MTH-101', 'SOC-101', 'SCI-101', 'ICT-101', 'ENG-101'],
                'sunday' => ['MTH-101', 'ENG-101', 'SCI-101', 'SOC-101', 'ICT-101', 'MTH-101'],
                'monday' => ['SCI-101', 'SOC-101', 'ENG-101', 'MTH-101', 'ICT-101', 'SOC-101'],
                'tuesday' => ['ICT-101', 'MTH-101', 'ENG-101', 'SCI-101', 'SOC-101', 'ENG-101'],
                'wednesday' => ['ENG-101', 'SCI-101', 'MTH-101', 'SOC-101', 'ICT-101', 'MTH-101'],
                'thursday' => ['MTH-101', 'ENG-101', 'SCI-101', 'ICT-101', 'SOC-101', 'SCI-101'],
            ],
            7 => [
                'saturday' => ['MTH-101', 'SCI-101', 'ENG-101', 'SOC-101', 'ICT-101', 'MTH-101'],
                'sunday' => ['ENG-101', 'SOC-101', 'MTH-101', 'SCI-101', 'ICT-101', 'ENG-101'],
                'monday' => ['SOC-101', 'ENG-101', 'SCI-101', 'MTH-101', 'SOC-101', 'ICT-101'],
                'tuesday' => ['SCI-101', 'MTH-101', 'SOC-101', 'ENG-101', 'ICT-101', 'SCI-101'],
                'wednesday' => ['ICT-101', 'ENG-101', 'MTH-101', 'SCI-101', 'SOC-101', 'ENG-101'],
                'thursday' => ['ENG-101', 'MTH-101', 'SCI-101', 'SOC-101', 'ENG-101', 'SOC-101'],
            ],
            8 => [
                'saturday' => ['SCI-101', 'ENG-101', 'MTH-101', 'ICT-101', 'SOC-101', 'SCI-101'],
                'sunday' => ['MTH-101', 'SCI-101', 'ENG-101', 'SOC-101', 'ICT-101', 'MTH-101'],
                'monday' => ['ENG-101', 'MTH-101', 'SCI-101', 'ICT-101', 'SOC-101', 'ENG-101'],
                'tuesday' => ['SOC-101', 'SCI-101', 'MTH-101', 'ENG-101', 'ICT-101', 'SOC-101'],
                'wednesday' => ['SCI-101', 'ENG-101', 'SOC-101', 'MTH-101', 'ICT-101', 'SCI-101'],
                'thursday' => ['MTH-101', 'ENG-101', 'SCI-101', 'SOC-101', 'ENG-101', 'MTH-101'],
            ],
            9 => [
                'saturday' => ['PHY-201', 'CHM-201', 'MTH-101', 'BIO-201', 'ENG-101', 'ICT-101'],
                'sunday' => ['MTH-101', 'BIO-201', 'PHY-201', 'CHM-201', 'SOC-101', 'ENG-101'],
                'monday' => ['CHM-201', 'PHY-201', 'BIO-201', 'MTH-101', 'ICT-101', 'ENG-101'],
                'tuesday' => ['BIO-201', 'MTH-101', 'PHY-201', 'CHM-201', 'ENG-101', 'ICT-101'],
                'wednesday' => ['PHY-201', 'MTH-101', 'CHM-201', 'BIO-201', 'ENG-101', 'SOC-101'],
                'thursday' => ['MTH-101', 'PHY-201', 'BIO-201', 'CHM-201', 'ICT-101', 'ENG-101'],
            ],
            10 => [
                'saturday' => ['CHM-201', 'PHY-201', 'MTH-101', 'BIO-201', 'ICT-101', 'ENG-101'],
                'sunday' => ['PHY-201', 'MTH-101', 'CHM-201', 'BIO-201', 'ENG-101', 'SOC-101'],
                'monday' => ['MTH-101', 'CHM-201', 'PHY-201', 'BIO-201', 'ICT-101', 'ENG-101'],
                'tuesday' => ['BIO-201', 'PHY-201', 'MTH-101', 'CHM-201', 'ENG-101', 'ICT-101'],
                'wednesday' => ['PHY-201', 'BIO-201', 'CHM-201', 'MTH-101', 'ENG-101', 'SOC-101'],
                'thursday' => ['CHM-201', 'MTH-101', 'PHY-201', 'BIO-201', 'ICT-101', 'ENG-101'],
            ],
        ];

        $classes = AcademicClass::where('school_id', $school->id)->get()->keyBy('numeric_code');

        foreach ($schedules as $gradeNum => $dayPlans) {
            $class = $classes->get($gradeNum);
            if (! $class) {
                continue;
            }

            $sections = Section::where('school_id', $school->id)
                ->where('academic_class_id', $class->id)
                ->get();

            if ($sections->isEmpty()) {
                continue;
            }

            foreach ($sections as $sectionIndex => $section) {
                foreach ($dayPlans as $day => $subCodes) {
                    foreach ($subCodes as $periodIdx => $subCode) {
                        $periodNum = $periodIdx + 1;
                        $time = $periods[$periodNum] ?? null;
                        if (! $time) {
                            continue;
                        }

                        $subject = $subjects->get($subCode) ?? $subjects->first();
                        if (! $subject) {
                            continue;
                        }

                        $teacher = $teachers->isNotEmpty()
                            ? $teachers->get(($periodIdx + $gradeNum + $sectionIndex) % $teachers->count())
                            : null;

                        $isLab = in_array($subCode, ['ICT-101', 'PHY-201', 'CHM-201', 'BIO-201']) && ($periodNum >= 4);
                        $roomNumber = $isLab
                            ? ($subCode === 'ICT-101' ? 'আইসিটি ল্যাব' : 'বিজ্ঞান ল্যাব')
                            : "কক্ষ {$gradeNum}0" . ($sectionIndex + 1);

                        TimetableSlot::withoutGlobalScopes()->updateOrCreate(
                            [
                                'school_id' => $school->id,
                                'academic_year_id' => $academicYear->id,
                                'academic_class_id' => $class->id,
                                'section_id' => $section->id,
                                'day_of_week' => $day,
                                'start_time' => $time['start'],
                            ],
                            [
                                'end_time' => $time['end'],
                                'subject_id' => $subject->id,
                                'teacher_id' => $teacher?->id,
                                'room_number' => $roomNumber,
                                'type' => $isLab ? 'lab' : 'regular',
                            ]
                        );
                    }
                }
            }
        }
    }
}
