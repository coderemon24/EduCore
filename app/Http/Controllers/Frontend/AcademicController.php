<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Notice;
use App\Models\TimetableSlot;
use App\Services\CurrentSchoolContext;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AcademicController extends Controller
{
    /**
     * Display class timetables and examination routines.
     */
    public function routine(Request $request): View
    {
        $school = CurrentSchoolContext::get();

        $classes = collect();
        $selectedClass = null;
        $timetableSlots = collect();

        if ($school->exists && Schema::hasTable('academic_classes')) {
            $classes = AcademicClass::where('school_id', $school->id)
                ->orderBy('order_index', 'asc')
                ->orderBy('numeric_code', 'asc')
                ->get();

            $classId = $request->get('class_id', $classes->first()?->id);
            if ($classId) {
                $selectedClass = $classes->firstWhere('id', $classId);

                if (Schema::hasTable('timetable_slots')) {
                    $timetableSlots = TimetableSlot::where('academic_class_id', $classId)
                        ->with(['subject', 'teacher', 'section'])
                        ->orderBy('day_of_week')
                        ->orderBy('start_time')
                        ->get();
                }
            }
        }

        // Circulars specifically tagged with academic or exam types
        $routineNotices = collect();
        if ($school->exists && Schema::hasTable('notices')) {
            $routineNotices = Notice::where('school_id', $school->id)
                ->where('status', 'published')
                ->whereIn('type', [Notice::TYPE_ACADEMIC, Notice::TYPE_EXAM])
                ->latest('published_at')
                ->take(5)
                ->get();
        }

        return view('frontend.academic.routine', compact(
            'school',
            'classes',
            'selectedClass',
            'timetableSlots',
            'routineNotices'
        ));
    }
}
