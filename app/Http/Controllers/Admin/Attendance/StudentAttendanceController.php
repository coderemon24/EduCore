<?php

namespace App\Http\Controllers\Admin\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Attendance\StoreStudentAttendanceRequest;
use App\Http\Requests\Admin\Attendance\UpdateStudentAttendanceRequest;
use App\Models\AcademicClass;
use App\Models\AcademicHoliday;
use App\Models\AcademicYear;
use App\Models\AttendanceAuditLog;
use App\Models\School;
use App\Models\Section;
use App\Models\StudentAttendance;
use App\Models\StudentEnrollment;
use Carbon\Carbon;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentAttendanceController extends Controller
{
    /**
     * Display the student roll-call interface with filters.
     */
    public function index(Request $request): View
    {
        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (Auth::user()?->school_id ?? session('current_school_id') ?? School::value('id'));

        $academicYears = AcademicYear::orderByDesc('is_current')->latest('start_date')->get();
        $selectedYearId = $request->input('academic_year_id', $academicYears->firstWhere('is_current', true)?->id ?? $academicYears->first()?->id);

        $classes = AcademicClass::ordered()->get();
        $selectedClassId = $request->input('academic_class_id');
        $selectedSectionId = $request->input('section_id');

        $sections = collect();
        if ($selectedClassId) {
            $sections = Section::where('academic_class_id', $selectedClassId)->orderBy('name')->get();
        }

        $attendanceDate = $request->input('attendance_date', Carbon::today()->toDateString());

        // Check if the selected date falls on a school holiday
        $holiday = null;
        if ($selectedYearId && $attendanceDate) {
            $holiday = AcademicHoliday::where('academic_year_id', $selectedYearId)
                ->coveringDate($attendanceDate)
                ->first();
        }

        $roster = collect();
        $summary = [
            'total' => 0,
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'half_day' => 0,
            'excused' => 0,
            'rate' => 0,
            'is_recorded' => false,
        ];

        if ($selectedClassId && $selectedSectionId && $selectedYearId) {
            // Load enrolled students
            $enrollments = StudentEnrollment::where('academic_class_id', $selectedClassId)
                ->where('section_id', $selectedSectionId)
                ->where('academic_year_id', $selectedYearId)
                ->where('status', 'current')
                ->with(['student'])
                ->orderBy('roll_number')
                ->get();

            // Load existing attendance records for the date
            $attendances = StudentAttendance::where('academic_class_id', $selectedClassId)
                ->where('section_id', $selectedSectionId)
                ->whereDate('attendance_date', $attendanceDate)
                ->get()
                ->keyBy('student_id');

            $isRecorded = $attendances->isNotEmpty();

            $roster = $enrollments->map(function ($enrollment) use ($attendances, $isRecorded) {
                $student = $enrollment->student;
                $record = $attendances->get($enrollment->student_id);

                return [
                    'student_id' => $student->id,
                    'roll_number' => $enrollment->roll_number,
                    'admission_number' => $student->admission_number,
                    'full_name' => $student->full_name,
                    'photo' => $student->photo,
                    'gender' => $student->gender,
                    'status' => $record ? $record->status : ($isRecorded ? 'absent' : 'present'),
                    'remarks' => $record?->remarks,
                    'attendance_id' => $record?->id,
                ];
            });

            $summary['total'] = $roster->count();
            $summary['present'] = $roster->where('status', 'present')->count();
            $summary['absent'] = $roster->where('status', 'absent')->count();
            $summary['late'] = $roster->where('status', 'late')->count();
            $summary['half_day'] = $roster->where('status', 'half_day')->count();
            $summary['excused'] = $roster->where('status', 'excused')->count();
            $summary['is_recorded'] = $isRecorded;
            $summary['rate'] = $summary['total'] > 0
                ? round((($summary['present'] + ($summary['half_day'] * 0.5) + ($summary['late'] * 0.8)) / $summary['total']) * 100, 1)
                : 0;
        }

        return view('admin.attendance.students.index', compact(
            'academicYears',
            'selectedYearId',
            'classes',
            'selectedClassId',
            'sections',
            'selectedSectionId',
            'attendanceDate',
            'holiday',
            'roster',
            'summary'
        ));
    }

    /**
     * Bulk save or update student attendance for a section.
     */
    public function store(StoreStudentAttendanceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (Auth::user()?->school_id ?? session('current_school_id') ?? Section::find($validated['section_id'])?->school_id ?? School::value('id'));
        $userId = Auth::id();
        $ip = $request->ip();

        $yearId = $validated['academic_year_id'];
        $classId = $validated['academic_class_id'];
        $sectionId = $validated['section_id'];
        $date = $validated['attendance_date'];
        $records = $validated['records'];

        $savedCount = 0;

        DB::transaction(function () use ($schoolId, $yearId, $classId, $sectionId, $date, $records, $userId, $ip, &$savedCount) {
            foreach ($records as $item) {
                $studentId = $item['student_id'];
                $newStatus = $item['status'];
                $remarks = $item['remarks'] ?? null;

                $existing = StudentAttendance::where('school_id', $schoolId)
                    ->where('student_id', $studentId)
                    ->whereDate('attendance_date', $date)
                    ->first();

                if ($existing) {
                    // Check if status changed for audit trail
                    if ($existing->status !== $newStatus) {
                        AttendanceAuditLog::create([
                            'school_id' => $schoolId,
                            'attendable_type' => StudentAttendance::class,
                            'attendable_id' => $existing->id,
                            'action' => 'updated',
                            'old_status' => $existing->status,
                            'new_status' => $newStatus,
                            'reason' => $remarks ?: 'Batch roll-call update',
                            'user_id' => $userId,
                            'ip_address' => $ip,
                            'created_at' => now(),
                        ]);
                    }

                    $existing->update([
                        'academic_year_id' => $yearId,
                        'academic_class_id' => $classId,
                        'section_id' => $sectionId,
                        'status' => $newStatus,
                        'remarks' => $remarks,
                        'updated_by_id' => $userId,
                    ]);
                } else {
                    $attendance = StudentAttendance::create([
                        'school_id' => $schoolId,
                        'academic_year_id' => $yearId,
                        'academic_class_id' => $classId,
                        'section_id' => $sectionId,
                        'student_id' => $studentId,
                        'attendance_date' => $date,
                        'status' => $newStatus,
                        'remarks' => $remarks,
                        'recorded_by_id' => $userId,
                        'updated_by_id' => $userId,
                    ]);

                    AttendanceAuditLog::create([
                        'school_id' => $schoolId,
                        'attendable_type' => StudentAttendance::class,
                        'attendable_id' => $attendance->id,
                        'action' => 'created',
                        'old_status' => null,
                        'new_status' => $newStatus,
                        'reason' => $remarks ?: 'Initial daily roll-call',
                        'user_id' => $userId,
                        'ip_address' => $ip,
                        'created_at' => now(),
                    ]);
                }

                $savedCount++;
            }
        });

        if (class_exists(ToastMagic::class)) {
            ToastMagic::success("Attendance recorded for {$savedCount} students on {$date}.");
        }

        return redirect()->route('admin.attendance.students.index', [
            'academic_year_id' => $yearId,
            'academic_class_id' => $classId,
            'section_id' => $sectionId,
            'attendance_date' => $date,
        ])->with('success', "Daily attendance successfully saved for {$savedCount} students.");
    }

    /**
     * Single attendance record correction with audit trail.
     */
    public function update(UpdateStudentAttendanceRequest $request, StudentAttendance $attendance): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $oldStatus = $attendance->status;
        $newStatus = $validated['status'];
        $userId = Auth::id();

        if ($oldStatus !== $newStatus) {
            AttendanceAuditLog::create([
                'school_id' => $attendance->school_id,
                'attendable_type' => StudentAttendance::class,
                'attendable_id' => $attendance->id,
                'action' => 'updated',
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'reason' => $validated['reason'] ?? $validated['remarks'] ?? 'Individual status correction',
                'user_id' => $userId,
                'ip_address' => $request->ip(),
                'created_at' => now(),
            ]);
        }

        $attendance->update([
            'status' => $newStatus,
            'remarks' => $validated['remarks'] ?? $attendance->remarks,
            'updated_by_id' => $userId,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance status updated successfully.',
                'attendance' => $attendance,
            ]);
        }

        return back()->with('success', 'Attendance record updated.');
    }
}
