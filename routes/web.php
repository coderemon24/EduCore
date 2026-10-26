<?php

use App\Http\Controllers\Frontend\AcademicController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\NoticeController;
use App\Http\Controllers\Frontend\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Client-Side School Frontend)
|--------------------------------------------------------------------------
| All public routes for the school frontend portal. Multi-tenant
| school context is automatically resolved via 'frontend.school' middleware.
| Administrative routes remain loaded via routes/admin.php.
|
*/

Route::middleware(['web', 'frontend.school'])->name('frontend.')->group(function () {
    // Homepage
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // About Us / Institutional Profile
    Route::get('/about', [HomeController::class, 'about'])->name('about');

    // Notice Board & Circulars
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
    Route::get('/notices/{slug}', [NoticeController::class, 'show'])->name('notices.show');
    Route::get('/notices/{slug}/download', [NoticeController::class, 'downloadAttachment'])->name('notices.download');

    // Teachers & Faculty Directory
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');

    // Academic Classes & Curriculum
    Route::get('/academic/classes', [AcademicController::class, 'classes'])->name('academic.classes');

    // Academic Information & Routine
    Route::get('/academic/routine', [AcademicController::class, 'routine'])->name('academic.routine');

    // Academic Holidays & Institutional Calendar
    Route::get('/academic/holidays', [AcademicController::class, 'holidays'])->name('academic.holidays');
    Route::get('/academic/holidays/events', [AcademicController::class, 'holidayEvents'])->name('academic.holidays.events');

    // News & Media Portal
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

    // Events & Institutional Calendar
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

    // Contact Us & Inquiry Form
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
});
