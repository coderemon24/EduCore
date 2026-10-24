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

    /**
     * Display public academic classes, section allocations, and curriculum subjects.
     */
    public function classes(Request $request): View
    {
        $school = CurrentSchoolContext::get();

        $classes = collect();
        $selectedClass = null;
        $selectedClassId = null;
        $telemetry = [
            'total_classes' => 0,
            'total_sections' => 0,
            'total_subjects' => 0,
        ];

        if ($school->exists && Schema::hasTable('academic_classes')) {
            $classes = AcademicClass::where('school_id', $school->id)
                ->where('status', 'active')
                ->with([
                    'sections' => function ($query) use ($school) {
                        $query->where('school_id', $school->id)
                            ->where('status', 'active')
                            ->with('classTeacher');
                    },
                    'subjects' => function ($query) use ($school) {
                        $query->where('subjects.school_id', $school->id)
                            ->where('subjects.status', 'active');
                    },
                ])
                ->orderBy('order_index', 'asc')
                ->orderBy('numeric_code', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            // Calculate telemetry metrics across active school classes
            $telemetry['total_classes'] = $classes->count();
            $telemetry['total_sections'] = $classes->sum(fn ($c) => $c->sections->count());

            // Unique subjects across all classes
            $uniqueSubjectIds = $classes->flatMap(fn ($c) => $c->subjects->pluck('id'))->unique();
            $telemetry['total_subjects'] = $uniqueSubjectIds->count();

            $classId = $request->get('class_id', $classes->first()?->id);
            if ($classId) {
                $selectedClassId = (int) $classId;
                $selectedClass = $classes->firstWhere('id', $selectedClassId) ?? $classes->first();
                if ($selectedClass) {
                    $selectedClassId = $selectedClass->id;
                }
            }
        }

        return view('frontend.academic.classes', compact(
            'school',
            'classes',
            'selectedClass',
            'selectedClassId',
            'telemetry'
        ));
    }
}
