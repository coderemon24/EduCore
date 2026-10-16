<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class AcademicYear extends Model
{
    use BelongsToSchool, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'academic_years';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'name',
        'start_date',
        'end_date',
        'is_current',
        'status',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include active academic years.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include the current active academic year.
     */
    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('is_current', true);
    }

    /**
     * Atomically designate this academic year as the current active session.
     */
    public function setAsCurrent(): bool
    {
        return DB::transaction(function () {
            static::withoutGlobalScopes()
                ->where('school_id', $this->school_id)
                ->where('id', '!=', $this->id)
                ->update(['is_current' => false]);

            return $this->update([
                'is_current' => true,
                'status' => 'active',
            ]);
        });
    }

    /**
     * Get all curriculum subject assignments associated with this academic year.
     */
    public function subjectAssignments(): HasMany
    {
        return $this->hasMany(ClassSubjectAssignment::class);
    }
}
