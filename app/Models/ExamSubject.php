<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSubject extends Model
{
    use BelongsToSchool, HasFactory;

    protected $table = 'exam_subjects';

    protected $fillable = [
        'school_id',
        'exam_id',
        'academic_class_id',
        'subject_id',
        'full_marks',
        'pass_marks',
        'exam_date',
        'start_time',
        'end_time',
        'room_number',
        'order_index',
    ];

    protected $casts = [
        'full_marks' => 'decimal:2',
        'pass_marks' => 'decimal:2',
        'exam_date' => 'date',
        'order_index' => 'integer',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(ExamSubjectComponent::class)->orderBy('order_index');
    }

    public function examMarks(): HasMany
    {
        return $this->hasMany(ExamMark::class);
    }
}
