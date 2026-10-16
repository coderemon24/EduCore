<?php

use App\Http\Controllers\Admin\Academic\AcademicClassController;
use App\Http\Controllers\Admin\Academic\AcademicGroupController;
use App\Http\Controllers\Admin\Academic\AcademicYearController;
use App\Http\Controllers\Admin\Academic\ClassSubjectAssignmentController;
use App\Http\Controllers\Admin\Academic\SectionController;
use App\Http\Controllers\Admin\Academic\SubjectController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\SchoolSettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| All administrative routes for the school management portal are registered
| here with the 'admin' prefix and 'admin.' name prefix.
|
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin Authentication Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'create'])->name('login');
        Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    });

    // Authenticated Admin Portal Routes
    Route::middleware('admin.auth')->group(function () {
        Route::redirect('/', '/admin/dashboard');
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

        // Institution Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/school', [SchoolSettingController::class, 'edit'])->name('school');
            Route::put('/school', [SchoolSettingController::class, 'update'])->name('school.update');
        });

        // Super Admin Multi-School Management
        Route::middleware('admin.super')->prefix('schools')->name('schools.')->group(function () {
            Route::get('/', [SchoolController::class, 'index'])->name('index');
            Route::get('/create', [SchoolController::class, 'create'])->name('create');
            Route::post('/', [SchoolController::class, 'store'])->name('store');
            Route::get('/{school}', [SchoolController::class, 'show'])->name('show');
            Route::get('/{school}/edit', [SchoolController::class, 'edit'])->name('edit');
            Route::put('/{school}', [SchoolController::class, 'update'])->name('update');
            Route::delete('/{school}', [SchoolController::class, 'destroy'])->name('destroy');
            Route::patch('/{school}/status', [SchoolController::class, 'updateStatus'])->name('status.update');
            Route::post('/{school}/restore', [SchoolController::class, 'restore'])->name('restore')->withTrashed();
            Route::delete('/{school}/force-delete', [SchoolController::class, 'forceDelete'])->name('force-delete')->withTrashed();
        });

        // Academic Structure & Institutional Hierarchy
        Route::prefix('academic')->name('academic.')->group(function () {
            // Academic Years / Sessions
            Route::post('years/{year}/set-current', [AcademicYearController::class, 'setCurrent'])->name('years.set-current');
            Route::resource('years', AcademicYearController::class)->except(['show']);

            // Dependent Dropdown APIs
            Route::get('classes/{class}/sections', [AcademicClassController::class, 'getSections'])->name('classes.sections.api');
            Route::get('classes/{class}/subjects', [AcademicClassController::class, 'getSubjects'])->name('classes.subjects.api');

            // Academic Classes / Grades
            Route::resource('classes', AcademicClassController::class)->except(['show']);

            // Sections
            Route::resource('sections', SectionController::class)->except(['show']);

            // Subjects Catalog
            Route::resource('subjects', SubjectController::class)->except(['show']);

            // Class-Subject Assignments (Curriculum Matrix)
            Route::resource('assign-subjects', ClassSubjectAssignmentController::class)->except(['show']);

            // Academic Streams / Groups
            Route::resource('groups', AcademicGroupController::class)->except(['show']);
        });
    });
});
