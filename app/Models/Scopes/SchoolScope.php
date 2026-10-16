<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SchoolScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (app()->has('current_school_id')) {
            $schoolId = app('current_school_id');

            if ($schoolId !== null) {
                $builder->where($model->qualifyColumn('school_id'), $schoolId);
            }
        }
    }
}
