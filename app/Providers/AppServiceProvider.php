<?php

namespace App\Providers;

use App\Models\School;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.admin', 'layouts.admin-partial.*', 'admin.*'], function ($view) {
            try {
                if (Schema::hasTable('schools')) {
                    $schoolId = app()->has('current_school_id') ? app('current_school_id') : null;
                    $currentSchool = $schoolId ? School::find($schoolId) : School::first();
                    $view->with('currentSchool', $currentSchool);
                    $view->with('allSchools', School::select(['id', 'name', 'slug', 'code', 'status'])->get());
                }
            } catch (\Throwable) {
                // Ignore if DB connection or table is unavailable during testing/early boot
            }
        });
    }
}
