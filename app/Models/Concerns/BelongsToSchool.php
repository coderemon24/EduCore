<?php

namespace App\Models\Concerns;

use App\Models\School;
use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToSchool
{
    /**
     * Boot the BelongsToSchool trait for an Eloquent model.
     */
    public static function bootBelongsToSchool(): void
    {
        static::addGlobalScope(new SchoolScope);

        static::creating(function ($model) {
            if (empty($model->school_id)) {
                $schoolId = app()->has('current_school_id')
                    ? app('current_school_id')
                    : (Auth::guard('web')->user()?->school_id ?? session('current_school_id') ?? School::value('id') ?? 1);

                if ($schoolId !== null) {
                    $model->school_id = $schoolId;
                }
            }
        });
    }

    /**
     * Retrieve the model for a bound value with tenant scope.
     *
     * @param  Builder  $query
     * @param  mixed  $value
     * @param  string|null  $field
     * @return Builder
     */
    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (Auth::guard('web')->user()?->school_id ?? session('current_school_id'));

        if ($schoolId !== null) {
            $query->where($this->qualifyColumn('school_id'), $schoolId);
        }

        return parent::resolveRouteBindingQuery($query, $value, $field);
    }

    /**
     * Get the school institution that owns this record.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
