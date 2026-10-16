<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicClass extends Model
{
    use BelongsToSchool, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'academic_classes';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'name',
        'numeric_code',
        'order_index',
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
            'numeric_code' => 'integer',
            'order_index' => 'integer',
        ];
    }

    /**
     * Scope a query to only include active classes.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to order classes naturally by order_index and numerical hierarchy.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order_index')->orderBy('numeric_code')->orderBy('name');
    }

    /**
     * Get all sections belonging to this academic class.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    /**
     * Get class subject assignments for this class.
     */
    public function classSubjectAssignments(): HasMany
    {
        return $this->hasMany(ClassSubjectAssignment::class);
    }

    /**
     * Get subjects assigned to this academic class.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'class_subject_assignments')
            ->withPivot(['id', 'school_id', 'academic_year_id', 'type', 'full_marks', 'pass_marks'])
            ->withTimestamps();
    }
}
