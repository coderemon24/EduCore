<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamMark extends Model
{
    use BelongsToSchool, HasFactory;

    protected $table = 'exam_marks';

    protected $fillable = [
        'school_id',
        'exam_id',
        'exam_subject_id',
        'student_id',
        'academic_class_id',
        'section_id',
        'attendance_status',
        'marks_obtained',
        'is_passed',
        'grade_tier_id',
        'remarks',
        'entered_by',
        'updated_by',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'is_passed' => 'boolean',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function examSubject(): BelongsTo
    {
        return $this->belongsTo(ExamSubject::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function gradeTier(): BelongsTo
    {
        return $this->belongsTo(GradeTier::class);
    }

    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function components(): HasMany
    {
        return $this->hasMany(ExamMarkComponent::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ExamMarkHistory::class)->latest('created_at');
    }

    public function getPercentageAttribute(): float
    {
        $fullMarks = (float) ($this->examSubject?->full_marks ?? 100);
        if ($fullMarks <= 0 || $this->marks_obtained === null) {
            return 0.00;
        }

        return round(((float) $this->marks_obtained / $fullMarks) * 100, 2);
    }
}
