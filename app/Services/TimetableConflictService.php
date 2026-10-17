<?php

namespace App\Services;

use App\Models\TimetableSlot;

class TimetableConflictService
{
    /**
     * Check for scheduling collisions involving teacher, room, or section.
     *
     * @return array{has_conflict: bool, messages: list<string>, conflicts: list<array<string, mixed>>}
     */
    public function checkConflict(
        int $schoolId,
        int $academicYearId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        int $sectionId,
        ?int $teacherId = null,
        ?string $roomNumber = null,
        ?int $excludeSlotId = null
    ): array {
        $dayOfWeek = strtolower(trim($dayOfWeek));
        $roomNumber = $roomNumber ? trim($roomNumber) : null;

        $baseQuery = TimetableSlot::query()
            ->where('school_id', $schoolId)
            ->where('academic_year_id', $academicYearId)
            ->where('day_of_week', $dayOfWeek)
            ->when($excludeSlotId, fn ($q) => $q->where('id', '!=', $excludeSlotId))
            ->where(function ($q) use ($startTime, $endTime) {
                // Strict interval intersection: existing.start < new.end AND existing.end > new.start
                $q->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            });

        $messages = [];
        $conflicts = [];

        // 1. Check Section Collision (same class section already has a class scheduled at this time)
        $sectionSlot = (clone $baseQuery)
            ->where('section_id', $sectionId)
            ->with(['academicClass', 'section', 'subject', 'teacher'])
            ->first();

        if ($sectionSlot) {
            $subjectName = $sectionSlot->subject?->name ?? 'Another subject';
            $className = $sectionSlot->academicClass?->name ?? 'Class';
            $secName = $sectionSlot->section?->name ?? '';
            $timeWindow = substr($sectionSlot->start_time, 0, 5).' - '.substr($sectionSlot->end_time, 0, 5);

            $msg = "Section Collision: {$className} ({$secName}) already has '{$subjectName}' scheduled from {$timeWindow}.";
            $messages[] = $msg;
            $conflicts[] = [
                'type' => 'section',
                'message' => $msg,
                'slot_id' => $sectionSlot->id,
            ];
        }

        // 2. Check Teacher Collision (teacher is teaching in another room/section)
        if ($teacherId) {
            $teacherSlot = (clone $baseQuery)
                ->where('teacher_id', $teacherId)
                ->with(['academicClass', 'section', 'subject', 'teacher'])
                ->first();

            if ($teacherSlot) {
                $teacherName = $teacherSlot->teacher?->full_name ?? 'The selected teacher';
                $className = $teacherSlot->academicClass?->name ?? 'Class';
                $secName = $teacherSlot->section?->name ?? '';
                $timeWindow = substr($teacherSlot->start_time, 0, 5).' - '.substr($teacherSlot->end_time, 0, 5);

                $msg = "Teacher Collision: {$teacherName} is already assigned to {$className} ({$secName}) from {$timeWindow}.";
                $messages[] = $msg;
                $conflicts[] = [
                    'type' => 'teacher',
                    'message' => $msg,
                    'slot_id' => $teacherSlot->id,
                ];
            }
        }

        // 3. Check Room Collision (physical room already booked by another section)
        if (! empty($roomNumber)) {
            $roomSlot = (clone $baseQuery)
                ->where('room_number', $roomNumber)
                ->with(['academicClass', 'section', 'subject'])
                ->first();

            if ($roomSlot) {
                $className = $roomSlot->academicClass?->name ?? 'Class';
                $secName = $roomSlot->section?->name ?? '';
                $timeWindow = substr($roomSlot->start_time, 0, 5).' - '.substr($roomSlot->end_time, 0, 5);

                $msg = "Room Collision: Room '{$roomNumber}' is already occupied by {$className} ({$secName}) from {$timeWindow}.";
                $messages[] = $msg;
                $conflicts[] = [
                    'type' => 'room',
                    'message' => $msg,
                    'slot_id' => $roomSlot->id,
                ];
            }
        }

        return [
            'has_conflict' => count($messages) > 0,
            'messages' => $messages,
            'conflicts' => $conflicts,
        ];
    }
}
