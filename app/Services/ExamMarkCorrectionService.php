<?php

namespace App\Services;

use App\Models\ExamMark;
use App\Models\ExamMarkComponent;
use App\Models\ExamMarkHistory;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ExamMarkCorrectionService
{
    public function __construct(
        protected ExamResultService $examResultService
    ) {}

    /**
     * Correct an existing student mark record with mandatory audit logging and automatic result recalculation.
     */
    public function correctMark(
        ExamMark $mark,
        float $newMarks,
        string $newStatus,
        string $reason,
        ?int $changedByUserId = null,
        array $componentMarks = []
    ): ExamMark {
        if (trim($reason) === '') {
            throw new InvalidArgumentException('A clear correction reason is required when modifying exam marks.');
        }

        $examSubject = $mark->examSubject;
        if ($newStatus === 'present' && $newMarks > (float) $examSubject->full_marks) {
            throw new InvalidArgumentException("Marks obtained ({$newMarks}) cannot exceed the subject maximum ({$examSubject->full_marks}).");
        }

        DB::transaction(function () use ($mark, $newMarks, $newStatus, $reason, $changedByUserId, $componentMarks, $examSubject) {
            $prevMarks = $mark->marks_obtained;
            $prevStatus = $mark->attendance_status;

            // 1. Log audit history
            ExamMarkHistory::create([
                'exam_mark_id' => $mark->id,
                'previous_marks' => $prevMarks,
                'new_marks' => $newStatus === 'present' ? $newMarks : null,
                'previous_status' => $prevStatus,
                'new_status' => $newStatus,
                'reason' => $reason,
                'changed_by' => $changedByUserId,
                'created_at' => now(),
            ]);

            // 2. Update components if provided
            if (! empty($componentMarks)) {
                foreach ($componentMarks as $componentId => $compMarkVal) {
                    ExamMarkComponent::updateOrCreate(
                        [
                            'exam_mark_id' => $mark->id,
                            'exam_subject_component_id' => $componentId,
                        ],
                        [
                            'marks_obtained' => $newStatus === 'present' ? $compMarkVal : null,
                            'is_passed' => true,
                        ]
                    );
                }
            }

            // 3. Update main mark record
            $mark->update([
                'marks_obtained' => $newStatus === 'present' ? $newMarks : null,
                'attendance_status' => $newStatus,
                'is_passed' => $newStatus === 'present' ? ($newMarks >= (float) $examSubject->pass_marks) : false,
                'updated_by' => $changedByUserId,
            ]);

            // 4. Recalculate results and ranks for the affected exam and class cohort
            $this->examResultService->calculateExamResults($mark->exam, $mark->academic_class_id, $mark->section_id);
        });

        return $mark->fresh();
    }
}
