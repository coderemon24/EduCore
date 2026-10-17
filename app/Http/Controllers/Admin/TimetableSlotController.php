<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\TimetableSlot;
use App\Services\TimetableConflictService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TimetableSlotController extends Controller
{
    public function __construct(
        protected TimetableConflictService $conflictService
    ) {}

    /**
     * Display weekly timetable matrix for sections or faculty teachers.
     */
    public function index(Request $request): View
    {
        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (Auth::user()?->school_id ?? session('current_school_id') ?? School::value('id'));

        $academicYears = AcademicYear::orderByDesc('is_current')->latest('start_date')->get();
        $selectedYearId = $request->input('academic_year_id', $academicYears->firstWhere('is_current', true)?->id ?? $academicYears->first()?->id);

        $viewMode = $request->input('view_mode', 'section'); // 'section' or 'teacher'

        $classes = AcademicClass::ordered()->get();
        $selectedClassId = $request->input('academic_class_id');
        $selectedSectionId = $request->input('section_id');

        $sections = collect();
        if ($selectedClassId) {
            $sections = Section::where('academic_class_id', $selectedClassId)->orderBy('name')->get();
        }

        // Available teachers and subjects for slot creation dropdowns
        $teachers = Staff::where('status', 'active')
            ->where(function ($q) {
                $q->where('is_teaching', true)
                    ->orWhere('designation', 'LIKE', '%Teacher%')
                    ->orWhere('designation', 'LIKE', '%Faculty%')
                    ->orWhere('designation', 'LIKE', '%Instructor%');
            })
            ->orderBy('first_name')
            ->get();

        $selectedTeacherId = $request->input('teacher_id');

        $subjects = Subject::orderBy('name')->get();

        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        $slotsGrouped = collect();
        foreach (array_keys($daysOfWeek) as $day) {
            $slotsGrouped[$day] = collect();
        }

        if ($viewMode === 'section' && $selectedSectionId && $selectedYearId) {
            $slots = TimetableSlot::where('section_id', $selectedSectionId)
                ->where('academic_year_id', $selectedYearId)
                ->with(['subject', 'teacher', 'academicClass', 'section'])
                ->orderBy('start_time')
                ->get();

            foreach ($slots as $slot) {
                $day = strtolower($slot->day_of_week);
                if (isset($slotsGrouped[$day])) {
                    $slotsGrouped[$day]->push($slot);
                }
            }
        } elseif ($viewMode === 'teacher' && $selectedTeacherId && $selectedYearId) {
            $slots = TimetableSlot::where('teacher_id', $selectedTeacherId)
                ->where('academic_year_id', $selectedYearId)
                ->with(['subject', 'academicClass', 'section', 'teacher'])
                ->orderBy('start_time')
                ->get();

            foreach ($slots as $slot) {
                $day = strtolower($slot->day_of_week);
                if (isset($slotsGrouped[$day])) {
                    $slotsGrouped[$day]->push($slot);
                }
            }
        }

        return view('admin.timetables.index', compact(
            'academicYears',
            'selectedYearId',
            'classes',
            'selectedClassId',
            'sections',
            'selectedSectionId',
            'teachers',
            'selectedTeacherId',
            'subjects',
            'viewMode',
            'daysOfWeek',
            'slotsGrouped'
        ));
    }

    /**
     * Store a newly created timetable schedule slot with conflict pre-check.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (Auth::user()?->school_id ?? session('current_school_id') ?? Section::find($request->input('section_id'))?->school_id ?? School::value('id'));

        $validated = $request->validate([
            'academic_year_id' => ['required', 'integer', Rule::exists('academic_years', 'id')],
            'academic_class_id' => ['required', 'integer', Rule::exists('academic_classes', 'id')],
            'section_id' => ['required', 'integer', Rule::exists('sections', 'id')],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')],
            'teacher_id' => ['nullable', 'integer', Rule::exists('staff', 'id')],
            'day_of_week' => ['required', 'string', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room_number' => ['nullable', 'string', 'max:50'],
            'type' => ['required', 'string', Rule::in(['regular', 'lab', 'practical', 'break'])],
        ]);

        // Pre-check for timetable conflict
        $conflictResult = $this->conflictService->checkConflict(
            schoolId: $schoolId,
            academicYearId: (int) $validated['academic_year_id'],
            dayOfWeek: $validated['day_of_week'],
            startTime: $validated['start_time'],
            endTime: $validated['end_time'],
            sectionId: (int) $validated['section_id'],
            teacherId: ! empty($validated['teacher_id']) ? (int) $validated['teacher_id'] : null,
            roomNumber: ! empty($validated['room_number']) ? $validated['room_number'] : null
        );

        if ($conflictResult['has_conflict']) {
            $errorMessage = implode(' ', $conflictResult['messages']);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'conflicts' => $conflictResult['conflicts'],
                ], 422);
            }

            return back()->withInput()->withErrors(['conflict' => $errorMessage]);
        }

        $slot = TimetableSlot::create([
            'school_id' => $schoolId,
            'academic_year_id' => $validated['academic_year_id'],
            'academic_class_id' => $validated['academic_class_id'],
            'section_id' => $validated['section_id'],
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'] ?? null,
            'day_of_week' => strtolower($validated['day_of_week']),
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'room_number' => $validated['room_number'] ?? null,
            'type' => $validated['type'],
        ]);

        if (class_exists(ToastMagic::class)) {
            ToastMagic::success('Timetable period scheduled successfully.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Slot created successfully.',
                'slot' => $slot->load(['subject', 'teacher', 'academicClass', 'section']),
            ]);
        }

        return redirect()->route('admin.timetables.index', [
            'academic_year_id' => $validated['academic_year_id'],
            'academic_class_id' => $validated['academic_class_id'],
            'section_id' => $validated['section_id'],
            'view_mode' => 'section',
        ])->with('success', 'Timetable slot created successfully.');
    }

    /**
     * Update an existing timetable slot with conflict exclusion.
     */
    public function update(Request $request, TimetableSlot $slot): RedirectResponse|JsonResponse
    {
        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (Auth::user()?->school_id ?? session('current_school_id') ?? $slot->school_id);

        $validated = $request->validate([
            'academic_year_id' => ['required', 'integer', Rule::exists('academic_years', 'id')],
            'academic_class_id' => ['required', 'integer', Rule::exists('academic_classes', 'id')],
            'section_id' => ['required', 'integer', Rule::exists('sections', 'id')],
            'subject_id' => ['required', 'integer', Rule::exists('subjects', 'id')],
            'teacher_id' => ['nullable', 'integer', Rule::exists('staff', 'id')],
            'day_of_week' => ['required', 'string', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room_number' => ['nullable', 'string', 'max:50'],
            'type' => ['required', 'string', Rule::in(['regular', 'lab', 'practical', 'break'])],
        ]);

        // Conflict check excluding the current slot ID
        $conflictResult = $this->conflictService->checkConflict(
            schoolId: $schoolId,
            academicYearId: (int) $validated['academic_year_id'],
            dayOfWeek: $validated['day_of_week'],
            startTime: $validated['start_time'],
            endTime: $validated['end_time'],
            sectionId: (int) $validated['section_id'],
            teacherId: ! empty($validated['teacher_id']) ? (int) $validated['teacher_id'] : null,
            roomNumber: ! empty($validated['room_number']) ? $validated['room_number'] : null,
            excludeSlotId: $slot->id
        );

        if ($conflictResult['has_conflict']) {
            $errorMessage = implode(' ', $conflictResult['messages']);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'conflicts' => $conflictResult['conflicts'],
                ], 422);
            }

            return back()->withInput()->withErrors(['conflict' => $errorMessage]);
        }

        $slot->update([
            'academic_year_id' => $validated['academic_year_id'],
            'academic_class_id' => $validated['academic_class_id'],
            'section_id' => $validated['section_id'],
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'] ?? null,
            'day_of_week' => strtolower($validated['day_of_week']),
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'room_number' => $validated['room_number'] ?? null,
            'type' => $validated['type'],
        ]);

        if (class_exists(ToastMagic::class)) {
            ToastMagic::success('Timetable period updated successfully.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Slot updated successfully.',
                'slot' => $slot->fresh()->load(['subject', 'teacher', 'academicClass', 'section']),
            ]);
        }

        return redirect()->route('admin.timetables.index', [
            'academic_year_id' => $validated['academic_year_id'],
            'academic_class_id' => $validated['academic_class_id'],
            'section_id' => $validated['section_id'],
            'view_mode' => 'section',
        ])->with('success', 'Timetable slot updated successfully.');
    }

    /**
     * Delete a timetable schedule slot.
     */
    public function destroy(TimetableSlot $slot): RedirectResponse|JsonResponse
    {
        $slot->delete();

        if (class_exists(ToastMagic::class)) {
            ToastMagic::success('Timetable period removed.');
        }

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Timetable period removed successfully.',
            ]);
        }

        return back()->with('success', 'Timetable slot removed.');
    }
}
