<?php

namespace App\Models\Concerns;

use App\Models\School;
use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToSchool
{
    /**
     * Boot the BelongsToSchool trait for an Eloquent model.
     */
    public static function bootBelongsToSchool(): void
    {
        static::addGlobalScope(new SchoolScope);

        static::creating(function ($model) {
            if (empty($model->school_id) && app()->has('current_school_id')) {
                $schoolId = app('current_school_id');

                if ($schoolId !== null) {
                    $model->school_id = $schoolId;
                }
            }
        });
    }

    /**
     * Get the school institution that owns this record.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
