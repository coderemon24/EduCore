<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use BelongsToSchool, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subjects';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'name',
        'code',
        'type',
        'credit_hours',
        'description',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'credit_hours' => 'decimal:2',
        ];
    }

    /**
     * Scope a query to only include active subjects.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Get class assignments for this subject.
     */
    public function classSubjectAssignments(): HasMany
    {
        return $this->hasMany(ClassSubjectAssignment::class);
    }

    /**
     * Get classes this subject is assigned to.
     */
    public function academicClasses(): BelongsToMany
    {
        return $this->belongsToMany(AcademicClass::class, 'class_subject_assignments')
            ->withPivot(['id', 'school_id', 'academic_year_id', 'type', 'full_marks', 'pass_marks'])
            ->withTimestamps();
    }
}
