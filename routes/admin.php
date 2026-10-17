<?php

use App\Http\Controllers\Admin\Academic\AcademicClassController;
use App\Http\Controllers\Admin\Academic\AcademicGroupController;
use App\Http\Controllers\Admin\Academic\AcademicHolidayController;
use App\Http\Controllers\Admin\Academic\AcademicYearController;
use App\Http\Controllers\Admin\Academic\ClassSubjectAssignmentController;
use App\Http\Controllers\Admin\Academic\SectionController;
use App\Http\Controllers\Admin\Academic\SubjectController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Attendance\AttendanceReportController;
use App\Http\Controllers\Admin\Attendance\StaffAttendanceController;
use App\Http\Controllers\Admin\Attendance\StudentAttendanceController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\SchoolSettingController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentPromotionController;
use App\Http\Controllers\Admin\TimetableSlotController;
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
            Route::post('years/{year}/restore', [AcademicYearController::class, 'restore'])->name('years.restore')->withTrashed();
            Route::delete('years/{year}/force-delete', [AcademicYearController::class, 'forceDelete'])->name('years.force-delete')->withTrashed();
            Route::resource('years', AcademicYearController::class)->except(['show']);

            // Dependent Dropdown APIs
            Route::get('classes/{class}/sections', [AcademicClassController::class, 'getSections'])->name('classes.sections.api');
            Route::get('classes/{class}/subjects', [AcademicClassController::class, 'getSubjects'])->name('classes.subjects.api');

            // Academic Classes / Grades
            Route::post('classes/{class}/restore', [AcademicClassController::class, 'restore'])->name('classes.restore')->withTrashed();
            Route::delete('classes/{class}/force-delete', [AcademicClassController::class, 'forceDelete'])->name('classes.force-delete')->withTrashed();
            Route::resource('classes', AcademicClassController::class)->except(['show']);

            // Sections
            Route::post('sections/{section}/restore', [SectionController::class, 'restore'])->name('sections.restore')->withTrashed();
            Route::delete('sections/{section}/force-delete', [SectionController::class, 'forceDelete'])->name('sections.force-delete')->withTrashed();
            Route::resource('sections', SectionController::class)->except(['show']);

            // Subjects Catalog
            Route::post('subjects/{subject}/restore', [SubjectController::class, 'restore'])->name('subjects.restore')->withTrashed();
            Route::delete('subjects/{subject}/force-delete', [SubjectController::class, 'forceDelete'])->name('subjects.force-delete')->withTrashed();
            Route::resource('subjects', SubjectController::class)->except(['show']);

            // Class-Subject Assignments (Curriculum Matrix)
            Route::post('assign-subjects/{assignment}/restore', [ClassSubjectAssignmentController::class, 'restore'])->name('assign-subjects.restore')->withTrashed();
            Route::delete('assign-subjects/{assignment}/force-delete', [ClassSubjectAssignmentController::class, 'forceDelete'])->name('assign-subjects.force-delete')->withTrashed();
            Route::resource('assign-subjects', ClassSubjectAssignmentController::class)->parameters(['assign-subjects' => 'assignment'])->except(['show']);

            // Academic Streams / Groups
            Route::post('groups/{group}/restore', [AcademicGroupController::class, 'restore'])->name('groups.restore')->withTrashed();
            Route::delete('groups/{group}/force-delete', [AcademicGroupController::class, 'forceDelete'])->name('groups.force-delete')->withTrashed();
            Route::resource('groups', AcademicGroupController::class)->except(['show']);

            // Academic Holidays & Non-Working Days
            Route::get('holidays/check-date', [AcademicHolidayController::class, 'checkDate'])->name('holidays.check-date');
            Route::resource('holidays', AcademicHolidayController::class)->except(['create', 'show', 'edit'])->middleware('school.permission:academic.holidays');
        });

        // Student Management (SIS)
        Route::prefix('students')->name('students.')->group(function () {
            // Bulk Class Promotion
            Route::get('promotions', [StudentPromotionController::class, 'bulkIndex'])->name('promotions.index');
            Route::get('promotions/roster', [StudentPromotionController::class, 'getRoster'])->name('promotions.roster');
            Route::post('promotions', [StudentPromotionController::class, 'processBulk'])->name('promotions.store');

            // Individual Student Promotion / Transfer
            Route::get('{student}/promote', [StudentPromotionController::class, 'promote'])->name('promote');
            Route::post('{student}/promote', [StudentPromotionController::class, 'processPromote'])->name('promote.store');

            // Bulk Actions (Move to Trash, Restore, Force Delete)
            Route::post('bulk-action', [StudentController::class, 'bulkAction'])->name('bulk-action');

            // Soft Delete, Restore & Force Delete
            Route::post('{student}/restore', [StudentController::class, 'restore'])->name('restore')->withTrashed();
            Route::delete('{student}/force-delete', [StudentController::class, 'forceDelete'])->name('force-delete')->withTrashed();

            // Core Resource Routes
            Route::get('/', [StudentController::class, 'index'])->name('index');
            Route::get('/create', [StudentController::class, 'create'])->name('create');
            Route::post('/', [StudentController::class, 'store'])->name('store');
            Route::get('/{student}', [StudentController::class, 'show'])->name('show')->withTrashed();
            Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
            Route::put('/{student}', [StudentController::class, 'update'])->name('update');
            Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
        });

        // Faculty & Staff Management
        Route::get('teachers', function () {
            request()->merge(['type' => 'teaching']);

            return app(StaffController::class)->index(request());
        })->name('teachers.index')->middleware('school.permission:staff.view');

        Route::prefix('staff')->name('staff.')->group(function () {
            Route::post('{id}/restore', [StaffController::class, 'restore'])->name('restore')->withTrashed()->middleware('school.permission:staff.delete');
            Route::delete('{id}/force-delete', [StaffController::class, 'forceDelete'])->name('force-delete')->withTrashed()->middleware('school.permission:staff.delete');
            Route::patch('{staff}/status', [StaffController::class, 'updateStatus'])->name('status.update')->middleware('school.permission:staff.manage_status');

            Route::get('/', [StaffController::class, 'index'])->name('index')->middleware('school.permission:staff.view');
            Route::get('/create', [StaffController::class, 'create'])->name('create')->middleware('school.permission:staff.create');
            Route::post('/', [StaffController::class, 'store'])->name('store')->middleware('school.permission:staff.create');
            Route::get('/{staff}', [StaffController::class, 'show'])->name('show')->withTrashed()->middleware('school.permission:staff.view');
            Route::get('/{staff}/edit', [StaffController::class, 'edit'])->name('edit')->middleware('school.permission:staff.edit');
            Route::put('/{staff}', [StaffController::class, 'update'])->name('update')->middleware('school.permission:staff.edit');
            Route::delete('/{staff}', [StaffController::class, 'destroy'])->name('destroy')->middleware('school.permission:staff.delete');
        });

        // Roles & Permissions Management
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index')->middleware('school.permission:roles.view');
            Route::get('/create', [RoleController::class, 'create'])->name('create')->middleware('school.permission:roles.manage');
            Route::post('/', [RoleController::class, 'store'])->name('store')->middleware('school.permission:roles.manage');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit')->middleware('school.permission:roles.manage');
            Route::put('/{role}', [RoleController::class, 'update'])->name('update')->middleware('school.permission:roles.manage');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy')->middleware('school.permission:roles.manage');
        });

        // Attendance Management
        Route::prefix('attendance')->name('attendance.')->group(function () {
            // Student Attendance
            Route::get('students', [StudentAttendanceController::class, 'index'])->name('students.index')->middleware('school.permission:attendance.view');
            Route::post('students', [StudentAttendanceController::class, 'store'])->name('students.store')->middleware('school.permission:attendance.students.mark');
            Route::put('students/{attendance}', [StudentAttendanceController::class, 'update'])->name('students.update')->middleware('school.permission:attendance.students.mark');

            // Staff Attendance
            Route::get('staff', [StaffAttendanceController::class, 'index'])->name('staff.index')->middleware('school.permission:attendance.view');
            Route::post('staff', [StaffAttendanceController::class, 'store'])->name('staff.store')->middleware('school.permission:attendance.staff.mark');

            // Attendance Reports & Register
            Route::get('reports', [AttendanceReportController::class, 'index'])->name('reports.index')->middleware('school.permission:attendance.reports');
        });

        // Class Timetables & Schedules
        Route::prefix('timetables')->name('timetables.')->group(function () {
            Route::get('/', [TimetableSlotController::class, 'index'])->name('index')->middleware('school.permission:timetables.view');
            Route::post('/', [TimetableSlotController::class, 'store'])->name('store')->middleware('school.permission:timetables.manage');
            Route::put('/{slot}', [TimetableSlotController::class, 'update'])->name('update')->middleware('school.permission:timetables.manage');
            Route::delete('/{slot}', [TimetableSlotController::class, 'destroy'])->name('destroy')->middleware('school.permission:timetables.manage');
            Route::get('/teachers', function () {
                request()->merge(['view_mode' => 'teacher']);

                return app(TimetableSlotController::class)->index(request());
            })->name('teachers')->middleware('school.permission:timetables.view');
        });
    });
});
