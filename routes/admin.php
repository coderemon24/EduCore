<?php

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
    });
});
