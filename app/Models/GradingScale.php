<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSchool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradingScale extends Model
{
    use BelongsToSchool, HasFactory, SoftDeletes;

    protected $table = 'grading_scales';

    protected $fillable = [
        'school_id',
        'name',
        'description',
        'fail_any_fails_all',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'fail_any_fails_all' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function gradeTiers(): HasMany
    {
        return $this->hasMany(GradeTier::class)->orderBy('min_percentage', 'desc');
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
