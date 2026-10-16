<?php

namespace App\Services;

use App\Models\Guardian;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StudentAdmissionService
{
    /**
     * Propose the next sequential admission number for a school and year.
     */
    public function generateNextAdmissionNumber(?int $schoolId = null, ?int $year = null): string
    {
        $schoolId = $schoolId ?? (app()->has('current_school_id') ? app('current_school_id') : School::value('id') ?? 1);
        $year = $year ?? (int) date('Y');
        $prefix = "ADM-{$year}-";

        // Query the latest admission number matching this prefix for this institution
        $lastAdmission = Student::withoutGlobalScopes()
            ->where('school_id', $schoolId)
            ->where('admission_number', 'LIKE', "{$prefix}%")
            ->orderByDesc('id')
            ->value('admission_number');

        $nextSeq = 1;

        if ($lastAdmission && preg_match('/^ADM-\d{4}-(\d+)$/', $lastAdmission, $matches)) {
            $nextSeq = ((int) $matches[1]) + 1;
        } else {
            // Alternatively count total students for that year
            $count = Student::withoutGlobalScopes()
                ->where('school_id', $schoolId)
                ->where('admission_number', 'LIKE', "{$prefix}%")
                ->count();
            $nextSeq = $count + 1;
        }

        return $prefix.str_pad((string) $nextSeq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Store a new student with guardian linkage and initial academic enrollment.
     */
    public function admitStudent(array $data, ?UploadedFile $photo = null): Student
    {
        return DB::transaction(function () use ($data, $photo) {
            $schoolId = $data['school_id'] ?? (app()->has('current_school_id') ? app('current_school_id') : School::value('id') ?? 1);

            // Handle photo upload if present
            $photoPath = null;
            if ($photo && $photo->isValid()) {
                $photoPath = $this->uploadPhoto($photo);
            }

            // Create student entity
            $student = Student::create([
                'school_id' => $schoolId,
                'admission_number' => $data['admission_number'],
                'admission_date' => $data['admission_date'] ?? now()->format('Y-m-d'),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'blood_group' => $data['blood_group'] ?? null,
                'religion' => $data['religion'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'photo' => $photoPath,
                'current_address' => $data['current_address'] ?? null,
                'permanent_address' => $data['permanent_address'] ?? null,
                'status' => 'active',
            ]);

            // Handle guardian entity & linkage
            if (! empty($data['guardian_name']) && ! empty($data['guardian_phone'])) {
                $guardian = Guardian::withoutGlobalScopes()->firstOrCreate(
                    [
                        'school_id' => $schoolId,
                        'phone' => $data['guardian_phone'],
                    ],
                    [
                        'name' => $data['guardian_name'],
                        'email' => $data['guardian_email'] ?? null,
                        'occupation' => $data['guardian_occupation'] ?? null,
                        'address' => $data['guardian_address'] ?? ($data['current_address'] ?? null),
                        'national_id' => $data['guardian_national_id'] ?? null,
                    ]
                );

                $student->guardians()->attach($guardian->id, [
                    'school_id' => $schoolId,
                    'relationship' => $data['guardian_relation'] ?? 'parent',
                    'is_primary' => true,
                    'is_emergency_contact' => true,
                ]);
            }

            // Create initial enrollment record
            StudentEnrollment::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'academic_year_id' => $data['academic_year_id'],
                'academic_class_id' => $data['academic_class_id'],
                'section_id' => $data['section_id'] ?? null,
                'academic_group_id' => $data['academic_group_id'] ?? null,
                'roll_number' => $data['roll_number'] ?? null,
                'status' => 'current',
                'enrolled_at' => $data['admission_date'] ?? now()->format('Y-m-d'),
                'remarks' => $data['enrollment_remarks'] ?? 'Initial academic admission.',
            ]);

            return $student;
        });
    }

    /**
     * Update student details, profile photo, and primary guardian.
     */
    public function updateStudent(Student $student, array $data, ?UploadedFile $photo = null): Student
    {
        return DB::transaction(function () use ($student, $data, $photo) {
            $schoolId = $student->school_id;

            // Handle photo replacement
            if ($photo && $photo->isValid()) {
                if ($student->photo && file_exists(public_path($student->photo))) {
                    File::delete(public_path($student->photo));
                }
                $student->photo = $this->uploadPhoto($photo);
            }

            $student->update([
                'admission_number' => $data['admission_number'] ?? $student->admission_number,
                'admission_date' => $data['admission_date'] ?? $student->admission_date,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'blood_group' => $data['blood_group'] ?? null,
                'religion' => $data['religion'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'current_address' => $data['current_address'] ?? null,
                'permanent_address' => $data['permanent_address'] ?? null,
                'status' => $data['status'] ?? $student->status,
            ]);

            // Update or link Guardian
            if (! empty($data['guardian_name']) && ! empty($data['guardian_phone'])) {
                $primaryGuardian = $student->guardians()->wherePivot('is_primary', true)->first();

                if ($primaryGuardian) {
                    $primaryGuardian->update([
                        'name' => $data['guardian_name'],
                        'phone' => $data['guardian_phone'],
                        'email' => $data['guardian_email'] ?? $primaryGuardian->email,
                        'occupation' => $data['guardian_occupation'] ?? $primaryGuardian->occupation,
                        'address' => $data['guardian_address'] ?? $primaryGuardian->address,
                        'national_id' => $data['guardian_national_id'] ?? $primaryGuardian->national_id,
                    ]);

                    $student->guardians()->updateExistingPivot($primaryGuardian->id, [
                        'relationship' => $data['guardian_relation'] ?? 'parent',
                    ]);
                } else {
                    $guardian = Guardian::withoutGlobalScopes()->firstOrCreate(
                        [
                            'school_id' => $schoolId,
                            'phone' => $data['guardian_phone'],
                        ],
                        [
                            'name' => $data['guardian_name'],
                            'email' => $data['guardian_email'] ?? null,
                            'occupation' => $data['guardian_occupation'] ?? null,
                            'address' => $data['guardian_address'] ?? null,
                            'national_id' => $data['guardian_national_id'] ?? null,
                        ]
                    );

                    $student->guardians()->syncWithoutDetaching([
                        $guardian->id => [
                            'school_id' => $schoolId,
                            'relationship' => $data['guardian_relation'] ?? 'parent',
                            'is_primary' => true,
                            'is_emergency_contact' => true,
                        ],
                    ]);
                }
            }

            return $student;
        });
    }

    /**
     * Promote or transfer an individual student to another academic placement.
     */
    public function promoteStudent(Student $student, array $data): StudentEnrollment
    {
        return DB::transaction(function () use ($student, $data) {
            $schoolId = $student->school_id;

            // Transition all prior 'current' enrollments to 'promoted' or 'transferred'
            $priorStatus = ($data['action_type'] ?? 'promotion') === 'transfer' ? 'transferred' : 'promoted';

            StudentEnrollment::withoutGlobalScopes()
                ->where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->where('status', 'current')
                ->update([
                    'status' => $priorStatus,
                    'promoted_at' => now()->format('Y-m-d'),
                ]);

            // Create new current enrollment
            $newEnrollment = StudentEnrollment::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'academic_year_id' => $data['academic_year_id'],
                'academic_class_id' => $data['academic_class_id'],
                'section_id' => $data['section_id'] ?? null,
                'academic_group_id' => $data['academic_group_id'] ?? null,
                'roll_number' => $data['roll_number'] ?? null,
                'status' => 'current',
                'enrolled_at' => now()->format('Y-m-d'),
                'remarks' => $data['remarks'] ?? 'Academic promotion / class transfer.',
            ]);

            // Ensure student status remains active
            $student->update(['status' => 'active']);

            return $newEnrollment;
        });
    }

    /**
     * Bulk promote multiple students from a source session/class to a target session/class.
     */
    public function bulkPromote(array $studentIds, array $targetData): int
    {
        return DB::transaction(function () use ($studentIds, $targetData) {
            $schoolId = app()->has('current_school_id') ? app('current_school_id') : School::value('id') ?? 1;
            $promotedCount = 0;

            foreach ($studentIds as $studentId) {
                // Update previous enrollment
                StudentEnrollment::withoutGlobalScopes()
                    ->where('school_id', $schoolId)
                    ->where('student_id', $studentId)
                    ->where('status', 'current')
                    ->update([
                        'status' => 'promoted',
                        'promoted_at' => now()->format('Y-m-d'),
                    ]);

                // Create target enrollment
                StudentEnrollment::create([
                    'school_id' => $schoolId,
                    'student_id' => $studentId,
                    'academic_year_id' => $targetData['target_academic_year_id'],
                    'academic_class_id' => $targetData['target_academic_class_id'],
                    'section_id' => $targetData['target_section_id'] ?? null,
                    'status' => 'current',
                    'enrolled_at' => now()->format('Y-m-d'),
                    'remarks' => $targetData['remarks'] ?? 'Bulk session promotion.',
                ]);

                $promotedCount++;
            }

            return $promotedCount;
        });
    }

    /**
     * Upload and store student profile photo into public/uploads/students/photos/.
     */
    protected function uploadPhoto(UploadedFile $file): string
    {
        $directory = public_path('uploads/students/photos');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = 'student_'.Str::random(12).'_'.time().'.'.$extension;

        $file->move($directory, $filename);

        return 'uploads/students/photos/'.$filename;
    }
}
