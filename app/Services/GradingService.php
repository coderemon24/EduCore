<?php

namespace App\Services;

use App\Models\ExamSubject;
use App\Models\GradeTier;
use App\Models\GradingScale;

class GradingService
{
    /**
     * Locate the matching GradeTier for a given percentage within a GradingScale.
     */
    public function findTier(GradingScale $scale, float $percentage): ?GradeTier
    {
        $tiers = $scale->gradeTiers;

        if ($tiers->isEmpty()) {
            return null;
        }

        // Percentage bounded between 0 and 100
        $bounded = max(0.00, min(100.00, round($percentage, 2)));

        foreach ($tiers as $tier) {
            if ($bounded >= (float) $tier->min_percentage && $bounded <= (float) $tier->max_percentage) {
                return $tier;
            }
        }

        // Fallback: if lowest percentage tier (like 0 - 32.99)
        return $tiers->sortBy('min_percentage')->first();
    }

    /**
     * Alias for findTier.
     */
    public function calculateGradeTier(GradingScale $scale, float $percentage): ?GradeTier
    {
        return $this->findTier($scale, $percentage);
    }

    /**
     * Evaluate single subject mark against its pass mark and scale tiers.
     *
     * @return array{percentage: float, is_passed: bool, tier: ?GradeTier, grade: string, point: float}
     */
    public function evaluateSubjectMark(ExamSubject $examSubject, ?float $marksObtained, ?GradingScale $scale = null): array
    {
        if ($marksObtained === null) {
            return [
                'percentage' => 0.00,
                'is_passed' => false,
                'tier' => null,
                'grade' => 'F',
                'point' => 0.00,
            ];
        }

        $fullMarks = (float) $examSubject->full_marks > 0 ? (float) $examSubject->full_marks : 100.00;
        $passMarks = (float) $examSubject->pass_marks;
        $percentage = round(($marksObtained / $fullMarks) * 100, 2);
        $isPassed = $marksObtained >= $passMarks;

        $tier = null;
        $grade = $isPassed ? 'D' : 'F';
        $point = $isPassed ? 1.00 : 0.00;

        if ($scale) {
            $tier = $this->findTier($scale, $percentage);
            if ($tier) {
                $grade = $tier->grade;
                $point = (float) $tier->point;
                // If marks < passMarks, force failing grade if tier is pass
                if (! $isPassed && $tier->is_pass) {
                    $failingTier = $scale->gradeTiers->where('is_pass', false)->first();
                    $grade = $failingTier ? $failingTier->grade : 'F';
                    $point = 0.00;
                }
            }
        }

        return [
            'percentage' => $percentage,
            'is_passed' => $isPassed,
            'tier' => $tier,
            'grade' => $grade,
            'point' => $point,
        ];
    }

    /**
     * Compute aggregated overall GPA and grade for a collection of subject results.
     *
     * @param  array<int, array{point: float, is_passed: bool, is_exempt?: bool, percentage: float}>  $subjectEvaluations
     * @return array{gpa: float, grade: string, result_status: string, failed_count: int}
     */
    public function calculateOverallResult(array $subjectEvaluations, ?GradingScale $scale = null): array
    {
        $totalPoints = 0.00;
        $applicableCount = 0;
        $failedCount = 0;
        $totalPercentage = 0.00;

        foreach ($subjectEvaluations as $eval) {
            if (! empty($eval['is_exempt'])) {
                continue; // Exempt subjects do not affect GPA
            }

            $applicableCount++;
            $totalPoints += (float) ($eval['point'] ?? 0.00);
            $totalPercentage += (float) ($eval['percentage'] ?? 0.00);

            if (empty($eval['is_passed'])) {
                $failedCount++;
            }
        }

        if ($applicableCount === 0) {
            return [
                'gpa' => 0.00,
                'grade' => 'F',
                'result_status' => 'exempted',
                'failed_count' => 0,
            ];
        }

        $failAnyFailsAll = $scale ? (bool) $scale->fail_any_fails_all : true;

        if ($failAnyFailsAll && $failedCount > 0) {
            $failingTier = $scale ? $scale->gradeTiers->where('is_pass', false)->first() : null;

            return [
                'gpa' => 0.00,
                'grade' => $failingTier ? $failingTier->grade : 'F',
                'result_status' => 'failed',
                'failed_count' => $failedCount,
            ];
        }

        $gpa = round($totalPoints / $applicableCount, 2);
        $avgPercentage = round($totalPercentage / $applicableCount, 2);

        $grade = 'Passed';
        if ($scale) {
            $tier = $this->findTier($scale, $avgPercentage);
            if ($tier) {
                $grade = $tier->grade;
            }
        }

        return [
            'gpa' => $gpa,
            'grade' => $grade,
            'result_status' => $failedCount > 0 ? 'failed' : 'passed',
            'failed_count' => $failedCount,
        ];
    }
}
