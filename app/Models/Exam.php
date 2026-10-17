<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use BelongsToSchool, HasFactory, SoftDeletes;

    protected $table = 'exams';

    protected $fillable = [
        'school_id',
        'academic_year_id',
        'grading_scale_id',
        'title',
        'exam_type',
        'start_date',
        'end_date',
        'description',
        'status',
        'is_published',
        'published_at',
        'published_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function gradingScale(): BelongsTo
    {
        return $this->belongsTo(GradingScale::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function examSubjects(): HasMany
    {
        return $this->hasMany(ExamSubject::class)->orderBy('order_index');
    }

    public function examMarks(): HasMany
    {
        return $this->hasMany(ExamMark::class);
    }

    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function isLocked(): bool
    {
        return $this->is_published || $this->status === 'published';
    }
}
