<?php

namespace App\Services;

use App\Models\ClassSubjectAssignment;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\ExamResult;
use App\Models\StudentAttendance;
use App\Models\StudentEnrollment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExamResultService
{
    public function __construct(
        protected GradingService $gradingService
    ) {}

    /**
     * Compute and persist results for all enrolled students in an exam cohort.
     *
     * @return array{calculated_count: int, passed_count: int, failed_count: int}
     */
    public function calculateExamResults(Exam $exam, ?int $classId = null, ?int $sectionId = null): array
    {
        $exam->load(['gradingScale.gradeTiers', 'examSubjects.components']);

        // Find applicable classes
        $classQuery = $exam->examSubjects()->reorder()->select('academic_class_id')->distinct();
        if ($classId) {
            $classQuery->where('academic_class_id', $classId);
        }
        $classIds = $classQuery->pluck('academic_class_id');

        $enrollmentQuery = StudentEnrollment::where('school_id', $exam->school_id)
            ->where('academic_year_id', $exam->academic_year_id)
            ->whereIn('academic_class_id', $classIds)
            ->where('status', 'current');

        if ($sectionId) {
            $enrollmentQuery->where('section_id', $sectionId);
        }

        $enrollments = $enrollmentQuery->with(['student'])->get();

        // Optional subject fallback defined at the class assignment level
        $classOptionalAssignments = ClassSubjectAssignment::whereIn('academic_class_id', $classIds)
            ->where('type', 'optional')
            ->pluck('subject_id', 'academic_class_id')
            ->toArray();

        $calculatedCount = 0;
        $passedCount = 0;
        $failedCount = 0;

        DB::transaction(function () use ($exam, $enrollments, $classOptionalAssignments, &$calculatedCount, &$passedCount, &$failedCount) {
            foreach ($enrollments as $enrollment) {
                $studentId = $enrollment->student_id;
                $classId = $enrollment->academic_class_id;
                $sectionId = $enrollment->section_id;

                // Determine student's designated optional subject (individual enrollment override or class default)
                $studentOptionalSubjectId = $enrollment->optional_subject_id
                    ?: ($classOptionalAssignments[$classId] ?? null);

                // All exam subjects for this class
                $examSubjects = $exam->examSubjects->where('academic_class_id', $classId);

                // Student's marks for this exam
                $marks = ExamMark::where('exam_id', $exam->id)
                    ->where('student_id', $studentId)
                    ->get()
                    ->keyBy('exam_subject_id');

                $totalFullMarks = 0.00;
                $totalMarksObtained = 0.00;
                $subjectEvaluations = [];

                foreach ($examSubjects as $subj) {
                    $mark = $marks->get($subj->id);
                    $fullMarks = (float) $subj->full_marks;
                    $status = $mark ? $mark->attendance_status : 'missing';
                    $obtained = $mark ? (float) $mark->marks_obtained : 0.00;

                    $isExempt = ($status === 'exempt');
                    $isOptional = ($studentOptionalSubjectId && (int) $subj->subject_id === (int) $studentOptionalSubjectId);

                    if (! $isExempt) {
                        $totalFullMarks += $fullMarks;
                        if ($status === 'present') {
                            $totalMarksObtained += $obtained;
                        }
                    }

                    $eval = $this->gradingService->evaluateSubjectMark(
                        $subj,
                        $status === 'present' ? $obtained : 0.00,
                        $exam->gradingScale
                    );

                    $eval['is_exempt'] = $isExempt;
                    $eval['is_optional'] = $isOptional;
                    $eval['subject_id'] = $subj->subject_id;
                    if ($status === 'absent' || $status === 'missing' || $status === 'incomplete') {
                        $eval['is_passed'] = false;
                        $eval['point'] = 0.00;
                        $eval['grade'] = 'F';
                    }

                    $subjectEvaluations[] = $eval;
                }

                $overallPercentage = $totalFullMarks > 0
                    ? round(($totalMarksObtained / $totalFullMarks) * 100, 2)
                    : 0.00;

                $overall = $this->gradingService->calculateOverallResult(
                    $subjectEvaluations,
                    $exam->gradingScale
                );

                // Student Attendance Stats within the Exam session range
                $attendanceStats = $this->getStudentAttendanceSummary($exam->school_id, $studentId, $exam->academic_year_id);

                ExamResult::updateOrCreate(
                    [
                        'school_id' => $exam->school_id,
                        'exam_id' => $exam->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'academic_class_id' => $classId,
                        'section_id' => $sectionId,
                        'academic_year_id' => $exam->academic_year_id,
                        'total_full_marks' => $totalFullMarks,
                        'total_marks_obtained' => $totalMarksObtained,
                        'percentage' => $overallPercentage,
                        'gpa' => $overall['gpa'],
                        'overall_grade' => $overall['grade'],
                        'result_status' => $overall['result_status'],
                        'failed_subjects_count' => $overall['failed_count'],
                        'attendance_present_days' => $attendanceStats['present_days'],
                        'attendance_total_days' => $attendanceStats['total_days'],
                        'status' => 'draft',
                        'calculated_at' => Carbon::now(),
                    ]
                );

                $calculatedCount++;
                if ($overall['result_status'] === 'passed') {
                    $passedCount++;
                } else {
                    $failedCount++;
                }
            }

            // Assign Class and Section ranks
            $this->assignRanks($exam);
        });

        // Update exam state
        if ($exam->status === 'marks_entry' || $exam->status === 'ongoing') {
            $exam->update(['status' => 'results_calculated']);
        }

        return [
            'calculated_count' => $calculatedCount,
            'passed_count' => $passedCount,
            'failed_count' => $failedCount,
        ];
    }

    /**
     * Assign class and section ranks to calculated exam results.
     */
    public function assignRanks(Exam $exam): void
    {
        $results = ExamResult::where('exam_id', $exam->id)->get();

        // 1. Group by Academic Class
        $byClass = $results->groupBy('academic_class_id');
        foreach ($byClass as $classResults) {
            $sorted = $classResults->sort(function ($a, $b) {
                // Passed first
                $aPassed = $a->result_status === 'passed' ? 1 : 0;
                $bPassed = $b->result_status === 'passed' ? 1 : 0;
                if ($aPassed !== $bPassed) {
                    return $bPassed <=> $aPassed;
                }
                // GPA DESC
                if ((float) $a->gpa !== (float) $b->gpa) {
                    return (float) $b->gpa <=> (float) $a->gpa;
                }
                // Total marks obtained DESC
                if ((float) $a->total_marks_obtained !== (float) $b->total_marks_obtained) {
                    return (float) $b->total_marks_obtained <=> (float) $a->total_marks_obtained;
                }

                return $a->student_id <=> $b->student_id;
            })->values();

            foreach ($sorted as $index => $res) {
                ExamResult::where('id', $res->id)->update(['rank_in_class' => $index + 1]);
            }
        }

        // 2. Group by Section
        $bySection = $results->groupBy('section_id');
        foreach ($bySection as $sectionResults) {
            $sorted = $sectionResults->sort(function ($a, $b) {
                $aPassed = $a->result_status === 'passed' ? 1 : 0;
                $bPassed = $b->result_status === 'passed' ? 1 : 0;
                if ($aPassed !== $bPassed) {
                    return $bPassed <=> $aPassed;
                }
                if ((float) $a->gpa !== (float) $b->gpa) {
                    return (float) $b->gpa <=> (float) $a->gpa;
                }
                if ((float) $a->total_marks_obtained !== (float) $b->total_marks_obtained) {
                    return (float) $b->total_marks_obtained <=> (float) $a->total_marks_obtained;
                }

                return $a->student_id <=> $b->student_id;
            })->values();

            foreach ($sorted as $index => $res) {
                ExamResult::where('id', $res->id)->update(['rank_in_section' => $index + 1]);
            }
        }
    }

    /**
     * Map of highest mark obtained per subject for a specific class in an exam.
     *
     * @return array<int, float> [subject_id => highest_mark]
     */
    public function getSubjectHighestMarks(Exam $exam, int $classId): array
    {
        return ExamMark::where('exam_id', $exam->id)
            ->where('academic_class_id', $classId)
            ->where('attendance_status', 'present')
            ->select('exam_subject_id', DB::raw('MAX(marks_obtained) as max_marks'))
            ->groupBy('exam_subject_id')
            ->pluck('max_marks', 'exam_subject_id')
            ->map(fn ($val) => (float) $val)
            ->toArray();
    }

    /**
     * Helper to fetch student attendance count for the year/session.
     */
    protected function getStudentAttendanceSummary(int $schoolId, int $studentId, int $academicYearId): array
    {
        $attendanceQuery = StudentAttendance::where('school_id', $schoolId)
            ->where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId);

        $totalDays = (clone $attendanceQuery)->count();
        $presentDays = (clone $attendanceQuery)->whereIn('status', ['present', 'late', 'half_day'])->count();

        return [
            'present_days' => $presentDays,
            'total_days' => $totalDays,
        ];
    }
}
