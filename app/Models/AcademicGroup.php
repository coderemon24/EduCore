<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicGroup extends Model
{
    use BelongsToSchool, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'academic_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'name',
        'code',
        'description',
        'status',
    ];

    /**
     * Scope a query to only include active academic groups.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
