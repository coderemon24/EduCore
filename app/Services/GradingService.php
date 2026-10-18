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
     * Supports compulsory vs optional (4th) subjects:
     * - Optional subject failure does not trigger fail_any_fails_all overall exam failure.
     * - Optional subject points above 2.00 contribute bonus GP: max(0, Optional GP - 2.00).
     * - Overall GPA divisor is strictly the count of compulsory subjects, capped at 5.00.
     *
     * @param  array<int, array{point: float, is_passed: bool, is_exempt?: bool, is_optional?: bool, percentage: float}>  $subjectEvaluations
     * @return array{gpa: float, grade: string, result_status: string, failed_count: int, failed_compulsory_count: int, failed_optional_count: int, optional_bonus: float}
     */
    public function calculateOverallResult(array $subjectEvaluations, ?GradingScale $scale = null): array
    {
        $compulsoryPoints = 0.00;
        $compulsoryCount = 0;
        $compulsoryTotalPercentage = 0.00;
        $failedCompulsoryCount = 0;

        $optionalBonus = 0.00;
        $failedOptionalCount = 0;

        $allApplicableCount = 0;
        $allTotalPoints = 0.00;
        $allTotalPercentage = 0.00;

        foreach ($subjectEvaluations as $eval) {
            if (! empty($eval['is_exempt'])) {
                continue; // Exempt subjects do not affect GPA
            }

            $allApplicableCount++;
            $point = (float) ($eval['point'] ?? 0.00);
            $percentage = (float) ($eval['percentage'] ?? 0.00);
            $isPassed = ! empty($eval['is_passed']);

            $allTotalPoints += $point;
            $allTotalPercentage += $percentage;

            if (! empty($eval['is_optional'])) {
                if (! $isPassed) {
                    $failedOptionalCount++;
                } else {
                    $optionalBonus += max(0.00, round($point - 2.00, 2));
                }
            } else {
                $compulsoryCount++;
                $compulsoryPoints += $point;
                $compulsoryTotalPercentage += $percentage;

                if (! $isPassed) {
                    $failedCompulsoryCount++;
                }
            }
        }

        if ($allApplicableCount === 0) {
            return [
                'gpa' => 0.00,
                'grade' => 'F',
                'result_status' => 'exempted',
                'failed_count' => 0,
                'failed_compulsory_count' => 0,
                'failed_optional_count' => 0,
                'optional_bonus' => 0.00,
            ];
        }

        $totalFailedCount = $failedCompulsoryCount + $failedOptionalCount;
        $failAnyFailsAll = $scale ? (bool) $scale->fail_any_fails_all : true;

        // fail_any_fails_all applies ONLY to COMPULSORY subjects
        if ($failAnyFailsAll && $failedCompulsoryCount > 0) {
            $failingTier = $scale ? $scale->gradeTiers->where('is_pass', false)->first() : null;

            return [
                'gpa' => 0.00,
                'grade' => $failingTier ? $failingTier->grade : 'F',
                'result_status' => 'failed',
                'failed_count' => $totalFailedCount,
                'failed_compulsory_count' => $failedCompulsoryCount,
                'failed_optional_count' => $failedOptionalCount,
                'optional_bonus' => $optionalBonus,
            ];
        }

        if ($compulsoryCount > 0) {
            $rawGpa = ($compulsoryPoints + $optionalBonus) / $compulsoryCount;
            $gpa = min(5.00, round($rawGpa, 2));
            $avgPercentage = round($compulsoryTotalPercentage / $compulsoryCount, 2);
        } else {
            $gpa = round($allTotalPoints / $allApplicableCount, 2);
            $avgPercentage = round($allTotalPercentage / $allApplicableCount, 2);
        }

        $grade = 'Passed';
        if ($scale) {
            $tier = $this->findTier($scale, $avgPercentage);
            if ($tier) {
                $grade = $tier->grade;
            }

            // If GPA achieves 5.00 (e.g. through optional bonus points), elevate to top tier (A+)
            if ($gpa >= 5.00) {
                $topTier = $scale->gradeTiers->where('is_pass', true)->sortByDesc('point')->first();
                if ($topTier) {
                    $grade = $topTier->grade;
                }
            }
        }

        $isPassedOverall = ($failedCompulsoryCount === 0);

        return [
            'gpa' => $gpa,
            'grade' => $grade,
            'result_status' => $isPassedOverall ? 'passed' : 'failed',
            'failed_count' => $totalFailedCount,
            'failed_compulsory_count' => $failedCompulsoryCount,
            'failed_optional_count' => $failedOptionalCount,
            'optional_bonus' => $optionalBonus,
        ];
    }
}
