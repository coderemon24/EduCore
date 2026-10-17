<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    use BelongsToSchool, HasFactory;

    protected $table = 'exam_results';

    protected $fillable = [
        'school_id',
        'exam_id',
        'student_id',
        'academic_class_id',
        'section_id',
        'academic_year_id',
        'total_full_marks',
        'total_marks_obtained',
        'percentage',
        'gpa',
        'overall_grade',
        'result_status',
        'failed_subjects_count',
        'rank_in_class',
        'rank_in_section',
        'attendance_present_days',
        'attendance_total_days',
        'extra_activities',
        'status',
        'calculated_at',
        'published_at',
        'published_by',
    ];

    protected $casts = [
        'total_full_marks' => 'decimal:2',
        'total_marks_obtained' => 'decimal:2',
        'percentage' => 'decimal:2',
        'gpa' => 'decimal:2',
        'failed_subjects_count' => 'integer',
        'rank_in_class' => 'integer',
        'rank_in_section' => 'integer',
        'attendance_present_days' => 'integer',
        'attendance_total_days' => 'integer',
        'extra_activities' => 'array',
        'calculated_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
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

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
