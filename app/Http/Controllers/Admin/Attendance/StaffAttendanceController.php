<?php

namespace App\Http\Controllers\Admin\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceAuditLog;
use App\Models\School;
use App\Models\Staff;
use App\Models\StaffAttendance;
use Carbon\Carbon;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StaffAttendanceController extends Controller
{
    /**
     * Display staff attendance daily ledger.
     */
    public function index(Request $request): View
    {
        $attendanceDate = $request->input('attendance_date', Carbon::today()->toDateString());
        $departmentFilter = $request->input('department');
        $typeFilter = $request->input('type'); // teaching, non_teaching

        $staffQuery = Staff::query()->where('status', 'active');

        if ($typeFilter && in_array($typeFilter, ['teaching', 'non_teaching'])) {
            $staffQuery->where('is_teaching', $typeFilter === 'teaching');
        }

        if ($departmentFilter) {
            $staffQuery->where('department', $departmentFilter);
        }

        $staffMembers = $staffQuery->orderBy('first_name')->orderBy('last_name')->get();

        // Load existing staff attendance for the date
        $attendances = StaffAttendance::whereDate('attendance_date', $attendanceDate)
            ->get()
            ->keyBy('staff_id');

        $isRecorded = $attendances->isNotEmpty();

        $roster = $staffMembers->map(function ($staff) use ($attendances, $isRecorded) {
            $record = $attendances->get($staff->id);

            return [
                'staff_id' => $staff->id,
                'employee_id' => $staff->staff_code,
                'full_name' => $staff->full_name,
                'designation' => $staff->designation,
                'department' => $staff->department,
                'type' => $staff->is_teaching ? 'Teaching' : 'Non-Teaching',
                'avatar' => $staff->photo,
                'status' => $record ? $record->status : ($isRecorded ? 'absent' : 'present'),
                'check_in_time' => $record?->check_in_time ? substr($record->check_in_time, 0, 5) : null,
                'check_out_time' => $record?->check_out_time ? substr($record->check_out_time, 0, 5) : null,
                'remarks' => $record?->remarks,
            ];
        });

        $summary = [
            'total' => $roster->count(),
            'present' => $roster->where('status', 'present')->count(),
            'absent' => $roster->where('status', 'absent')->count(),
            'late' => $roster->where('status', 'late')->count(),
            'half_day' => $roster->where('status', 'half_day')->count(),
            'on_leave' => $roster->where('status', 'on_leave')->count(),
            'is_recorded' => $isRecorded,
        ];

        $departments = Staff::distinct()->whereNotNull('department')->pluck('department');

        return view('admin.attendance.staff.index', compact(
            'roster',
            'summary',
            'attendanceDate',
            'typeFilter',
            'departmentFilter',
            'departments'
        ));
    }

    /**
     * Store or update staff attendance in bulk.
     */
    public function store(Request $request): RedirectResponse
    {
        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (Auth::user()?->school_id ?? session('current_school_id') ?? School::value('id'));
        $userId = Auth::id();

        $validated = $request->validate([
            'attendance_date' => ['required', 'date', 'before_or_equal:today'],
            'records' => ['required', 'array', 'min:1'],
            'records.*.staff_id' => [
                'required',
                'integer',
                Rule::exists('staff', 'id')->where(function ($q) use ($schoolId) {
                    if ($schoolId) {
                        $q->where('school_id', $schoolId);
                    }
                }),
            ],
            'records.*.status' => ['required', 'string', Rule::in(['present', 'absent', 'late', 'half_day', 'on_leave'])],
            'records.*.check_in_time' => ['nullable', 'date_format:H:i'],
            'records.*.check_out_time' => ['nullable', 'date_format:H:i'],
            'records.*.remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $date = $validated['attendance_date'];
        $records = $validated['records'];
        $count = 0;

        DB::transaction(function () use ($schoolId, $date, $records, $userId, &$count) {
            foreach ($records as $item) {
                $staffId = $item['staff_id'];
                $newStatus = $item['status'];
                $checkIn = ! empty($item['check_in_time']) ? $item['check_in_time'] : null;
                $checkOut = ! empty($item['check_out_time']) ? $item['check_out_time'] : null;
                $remarks = $item['remarks'] ?? null;

                $existing = StaffAttendance::where('school_id', $schoolId)
                    ->where('staff_id', $staffId)
                    ->whereDate('attendance_date', $date)
                    ->first();

                if ($existing) {
                    if ($existing->status !== $newStatus) {
                        AttendanceAuditLog::create([
                            'school_id' => $schoolId,
                            'attendable_type' => StaffAttendance::class,
                            'attendable_id' => $existing->id,
                            'action' => 'updated',
                            'old_status' => $existing->status,
                            'new_status' => $newStatus,
                            'reason' => $remarks ?: 'Staff attendance update',
                            'user_id' => $userId,
                            'ip_address' => request()->ip(),
                            'created_at' => now(),
                        ]);
                    }

                    $existing->update([
                        'status' => $newStatus,
                        'check_in_time' => $checkIn,
                        'check_out_time' => $checkOut,
                        'remarks' => $remarks,
                        'updated_by_id' => $userId,
                    ]);
                } else {
                    $record = StaffAttendance::create([
                        'school_id' => $schoolId,
                        'staff_id' => $staffId,
                        'attendance_date' => $date,
                        'status' => $newStatus,
                        'check_in_time' => $checkIn,
                        'check_out_time' => $checkOut,
                        'remarks' => $remarks,
                        'recorded_by_id' => $userId,
                        'updated_by_id' => $userId,
                    ]);

                    AttendanceAuditLog::create([
                        'school_id' => $schoolId,
                        'attendable_type' => StaffAttendance::class,
                        'attendable_id' => $record->id,
                        'action' => 'created',
                        'old_status' => null,
                        'new_status' => $newStatus,
                        'reason' => $remarks ?: 'Initial staff attendance',
                        'user_id' => $userId,
                        'ip_address' => request()->ip(),
                        'created_at' => now(),
                    ]);
                }

                $count++;
            }
        });

        if (class_exists(ToastMagic::class)) {
            ToastMagic::success("Staff attendance saved for {$count} personnel.");
        }

        return redirect()->route('admin.attendance.staff.index', [
            'attendance_date' => $date,
        ])->with('success', "Staff attendance recorded for {$count} members.");
    }
}
