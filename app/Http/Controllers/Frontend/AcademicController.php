<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicHoliday;
use App\Models\AcademicYear;
use App\Models\Notice;
use App\Models\TimetableSlot;
use App\Services\CurrentSchoolContext;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
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

    /**
     * Display the public academic holiday calendar and scheduled institutional breaks.
     */
    public function holidays(Request $request): View
    {
        $school = CurrentSchoolContext::get();

        $academicYears = collect();
        $selectedYear = null;
        $selectedYearId = null;
        $holidays = collect();
        $holidaysByMonth = collect();
        $nextHoliday = null;
        $telemetry = [
            'total_events' => 0,
            'total_days' => 0,
            'national_holidays' => 0,
            'vacation_breaks' => 0,
            'exam_breaks' => 0,
        ];
        $calendarEvents = [];

        if ($school->exists && Schema::hasTable('academic_years')) {
            $academicYears = AcademicYear::where('school_id', $school->id)
                ->orderByDesc('is_current')
                ->latest('start_date')
                ->get();

            $requestedYearId = $request->input('academic_year_id');
            if ($requestedYearId) {
                $selectedYear = $academicYears->firstWhere('id', (int) $requestedYearId);
            }

            // Fallback to current or first academic session
            if (! $selectedYear) {
                $selectedYear = $academicYears->firstWhere('is_current', true) ?? $academicYears->first();
            }

            $selectedYearId = $selectedYear?->id;
        }

        if ($school->exists && $selectedYear && Schema::hasTable('academic_holidays')) {
            $holidays = AcademicHoliday::where('school_id', $school->id)
                ->where('academic_year_id', $selectedYear->id)
                ->orderBy('start_date', 'asc')
                ->get();

            // Calculate telemetry metrics
            $telemetry['total_events'] = $holidays->count();
            $telemetry['total_days'] = (int) $holidays->sum(function ($h) {
                return $h->start_date->diffInDays($h->end_date) + 1;
            });
            $telemetry['national_holidays'] = $holidays->where('type', 'holiday')->count();
            $telemetry['vacation_breaks'] = $holidays->filter(function ($h) {
                return ($h->start_date->diffInDays($h->end_date) + 1) >= 3;
            })->count();
            $telemetry['exam_breaks'] = $holidays->where('type', 'exam_break')->count();

            // Find closest upcoming holiday from today
            $today = Carbon::today();
            $nextHoliday = $holidays->first(function ($h) use ($today) {
                return $h->end_date->greaterThanOrEqualTo($today);
            }) ?? $holidays->first();

            // Group holidays by month for the annual schedule view
            $holidaysByMonth = $holidays->groupBy(function ($h) {
                return $h->start_date->format('F Y');
            });

            // Format calendar JSON payload for interactive day inspector
            $calendarEvents = $holidays->map(function ($h) {
                $duration = $h->start_date->diffInDays($h->end_date) + 1;

                $typeLabel = match ($h->type) {
                    'holiday' => 'সরকারি ছুটি',
                    'exam_break' => 'পরীক্ষার প্রস্তুতি ছুটি',
                    'weekend' => 'সাপ্তাহিক ছুটি',
                    'unplanned' => 'বিশেষ ছুটি',
                    default => 'ছুটি',
                };

                $typeLabelEn = match ($h->type) {
                    'holiday' => 'Public Holiday',
                    'exam_break' => 'Exam Prep Break',
                    'weekend' => 'Weekend',
                    'unplanned' => 'Special Closure',
                    default => 'Holiday',
                };

                return [
                    'id' => $h->id,
                    'title' => $h->title,
                    'start_date' => $h->start_date->toDateString(),
                    'end_date' => $h->end_date->toDateString(),
                    'type' => $h->type,
                    'type_label' => $typeLabel,
                    'type_label_en' => $typeLabelEn,
                    'duration_days' => $duration,
                    'description' => $h->description ?? 'একাডেমিক ছুটির দিন উপলক্ষে সকল শ্রেণি কার্যক্রম বন্ধ থাকবে।',
                ];
            })->values()->all();
        }

        return view('frontend.academic.holidays', compact(
            'school',
            'academicYears',
            'selectedYear',
            'selectedYearId',
            'holidays',
            'holidaysByMonth',
            'nextHoliday',
            'telemetry',
            'calendarEvents'
        ));
    }

    /**
     * API endpoint to dynamically fetch holiday events for the interactive calendar.
     */
    public function holidayEvents(Request $request): JsonResponse
    {
        $school = CurrentSchoolContext::get();
        if (! $school->exists) {
            return response()->json(['status' => 'error', 'message' => 'School context not resolved'], 404);
        }

        $yearId = $request->input('academic_year_id');
        $holidays = AcademicHoliday::where('school_id', $school->id)
            ->when($yearId, fn ($q) => $q->where('academic_year_id', $yearId))
            ->orderBy('start_date', 'asc')
            ->get();

        $events = $holidays->map(function ($h) {
            $duration = $h->start_date->diffInDays($h->end_date) + 1;

            return [
                'id' => $h->id,
                'title' => $h->title,
                'start_date' => $h->start_date->toDateString(),
                'end_date' => $h->end_date->toDateString(),
                'type' => $h->type,
                'type_label' => match ($h->type) {
                    'holiday' => 'সরকারি ছুটি',
                    'exam_break' => 'পরীক্ষার প্রস্তুতি ছুটি',
                    'weekend' => 'সাপ্তাহিক ছুটি',
                    'unplanned' => 'বিশেষ ছুটি',
                    default => 'ছুটি',
                },
                'duration_days' => $duration,
                'description' => $h->description ?? 'একাডেমিক ছুটির দিন উপলক্ষে সকল শ্রেণি কার্যক্রম বন্ধ থাকবে।',
            ];
        });

        return response()->json([
            'status' => 'success',
            'count' => $events->count(),
            'data' => $events,
        ]);
    }
}
