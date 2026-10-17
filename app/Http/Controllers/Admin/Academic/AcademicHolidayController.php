<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\AcademicHoliday;
use App\Models\AcademicYear;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AcademicHolidayController extends Controller
{
    /**
     * Display a listing of academic holidays and breaks.
     */
    public function index(Request $request): View
    {
        $academicYears = AcademicYear::orderByDesc('is_current')->latest('start_date')->get();
        $selectedYearId = $request->input('academic_year_id', $academicYears->firstWhere('is_current', true)?->id ?? $academicYears->first()?->id);

        $holidays = AcademicHoliday::where('academic_year_id', $selectedYearId)
            ->orderBy('start_date')
            ->get();

        return view('admin.academic.holidays.index', compact(
            'academicYears',
            'selectedYearId',
            'holidays'
        ));
    }

    /**
     * Store a newly created school holiday.
     */
    public function store(Request $request): RedirectResponse
    {
        $schoolId = Auth::user()?->school_id ?? Auth::guard('admin')->user()?->school_id;

        $validated = $request->validate([
            'academic_year_id' => ['required', 'integer', Rule::exists('academic_years', 'id')],
            'title' => ['required', 'string', 'max:191'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'type' => ['required', 'string', Rule::in(['holiday', 'weekend', 'exam_break', 'unplanned'])],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        AcademicHoliday::create([
            'school_id' => $schoolId,
            'academic_year_id' => $validated['academic_year_id'],
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
        ]);

        if (class_exists(ToastMagic::class)) {
            ToastMagic::success('School holiday scheduled successfully.');
        }

        return redirect()->route('admin.academic.holidays.index', [
            'academic_year_id' => $validated['academic_year_id'],
        ])->with('success', 'Holiday created successfully.');
    }

    /**
     * Update an existing school holiday.
     */
    public function update(Request $request, AcademicHoliday $holiday): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'integer', Rule::exists('academic_years', 'id')],
            'title' => ['required', 'string', 'max:191'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'type' => ['required', 'string', Rule::in(['holiday', 'weekend', 'exam_break', 'unplanned'])],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $holiday->update([
            'academic_year_id' => $validated['academic_year_id'],
            'title' => $validated['title'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
        ]);

        if (class_exists(ToastMagic::class)) {
            ToastMagic::success('School holiday updated.');
        }

        return redirect()->route('admin.academic.holidays.index', [
            'academic_year_id' => $validated['academic_year_id'],
        ])->with('success', 'Holiday updated.');
    }

    /**
     * Delete an academic holiday.
     */
    public function destroy(AcademicHoliday $holiday): RedirectResponse
    {
        $yearId = $holiday->academic_year_id;
        $holiday->delete();

        if (class_exists(ToastMagic::class)) {
            ToastMagic::success('School holiday removed.');
        }

        return redirect()->route('admin.academic.holidays.index', [
            'academic_year_id' => $yearId,
        ])->with('success', 'Holiday removed.');
    }

    /**
     * API helper to check if a date is a holiday.
     */
    public function checkDate(Request $request): JsonResponse
    {
        $date = $request->input('date');
        $yearId = $request->input('academic_year_id');

        if (! $date || ! $yearId) {
            return response()->json(['is_holiday' => false]);
        }

        $holiday = AcademicHoliday::where('academic_year_id', $yearId)
            ->coveringDate($date)
            ->first();

        return response()->json([
            'is_holiday' => $holiday !== null,
            'holiday' => $holiday ? [
                'title' => $holiday->title,
                'type' => $holiday->type,
                'start_date' => $holiday->start_date->toDateString(),
                'end_date' => $holiday->end_date->toDateString(),
            ] : null,
        ]);
    }
}
