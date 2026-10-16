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
                    $admin = auth('admin')->user();
                    $isSuperAdmin = $admin && $admin->role === 'super_admin';

                    if ($schoolId) {
                        $currentSchool = School::find($schoolId);
                        $isGlobalSuperAdmin = false;
                    } elseif ($isSuperAdmin) {
                        $currentSchool = null;
                        $isGlobalSuperAdmin = true;
                    } else {
                        $currentSchool = School::first();
                        $isGlobalSuperAdmin = false;
                    }

                    $view->with('currentSchool', $currentSchool);
                    $view->with('isGlobalSuperAdmin', $isGlobalSuperAdmin);
                    $view->with('allSchools', School::select(['id', 'name', 'slug', 'code', 'status', 'logo', 'favicon'])->get());
                }
            } catch (\Throwable) {
                // Ignore if DB connection or table is unavailable during testing/early boot
            }
        });
    }
}
