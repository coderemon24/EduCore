@extends('layouts.admin')

@section('title', 'Faculty & Staff Attendance | Administration')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="text-slate-500">Attendance</span>
                <span>/</span>
                <span class="font-semibold text-slate-700">Staff Attendance</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Faculty & Staff Attendance</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Record daily attendance, working presence, check-in/out timestamps, and leave notes for school personnel.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.attendance.students.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Student Roll-Call</span>
            </a>

            <a href="{{ route('admin.staff.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Staff Directory</span>
            </a>
        </div>
    </div>

    <!-- Date & Filter Bar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.attendance.staff.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4 items-end">
            <!-- Attendance Date -->
            <div>
                <label for="filter_date" class="block text-xs font-semibold text-slate-700 mb-1">Attendance Date</label>
                <input type="date"
                       name="attendance_date"
                       id="filter_date"
                       value="{{ $attendanceDate }}"
                       max="{{ now()->toDateString() }}"
                       class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition"
                       required>
            </div>

            <!-- Staff Type Filter -->
            <div>
                <label for="filter_type" class="block text-xs font-semibold text-slate-700 mb-1">Personnel Type</label>
                <select name="type" id="filter_type" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">All Personnel (Teaching & Non-Teaching)</option>
                    <option value="teaching" {{ $typeFilter === 'teaching' ? 'selected' : '' }}>Teaching Faculty Only</option>
                    <option value="non_teaching" {{ $typeFilter === 'non_teaching' ? 'selected' : '' }}>Non-Teaching Staff Only</option>
                </select>
            </div>

            <!-- Department Filter -->
            <div>
                <label for="filter_department" class="block text-xs font-semibold text-slate-700 mb-1">Department</label>
                <select name="department" id="filter_department" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ $departmentFilter === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Filter -->
            <div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-slate-800 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Load Staff Ledger</span>
                </button>
            </div>
        </form>
    </div>

    @if($roster->isEmpty())
        <div class="rounded-xl border border-slate-200 bg-white p-12 text-center shadow-2xs">
            <h3 class="text-base font-bold text-slate-800">No Active Staff Members Found</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                No active staff personnel match the selected criteria.
            </p>
        </div>
    @else
        <!-- Staff Attendance Submission Form -->
        <form method="POST" action="{{ route('admin.attendance.staff.store') }}" id="staff-attendance-form">
            @csrf
            <input type="hidden" name="attendance_date" value="{{ $attendanceDate }}">

            <!-- Telemetry & Actions Toolbar -->
            <div class="mb-4 rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <!-- Metrics Badges -->
                    <div class="flex items-center gap-2 flex-wrap text-xs">
                        <span class="inline-flex items-center gap-1 rounded border border-slate-200 bg-slate-100 px-2.5 py-1 font-semibold text-slate-700">
                            <span>Total Staff:</span>
                            <span id="stat-total" class="font-bold text-slate-900">{{ $summary['total'] }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 rounded border border-emerald-200 bg-emerald-50 px-2.5 py-1 font-semibold text-emerald-800">
                            <span>Present:</span>
                            <span id="stat-present" class="font-bold">{{ $summary['present'] }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 rounded border border-rose-200 bg-rose-50 px-2.5 py-1 font-semibold text-rose-800">
                            <span>Absent:</span>
                            <span id="stat-absent" class="font-bold">{{ $summary['absent'] }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 rounded border border-amber-200 bg-amber-50 px-2.5 py-1 font-semibold text-amber-800">
                            <span>Late:</span>
                            <span id="stat-late" class="font-bold">{{ $summary['late'] }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 rounded border border-indigo-200 bg-indigo-50 px-2.5 py-1 font-semibold text-indigo-800">
                            <span>On Leave:</span>
                            <span id="stat-leave" class="font-bold">{{ $summary['on_leave'] }}</span>
                        </span>
                    </div>

                    <!-- Action Controls -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <button type="button"
                                onclick="window.markAllStaff('present')"
                                class="inline-flex items-center gap-1 rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-800 hover:bg-emerald-100 transition cursor-pointer">
                            <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Mark All Present</span>
                        </button>

                        <button type="button"
                                onclick="window.markAllStaff('absent')"
                                class="inline-flex items-center gap-1 rounded-lg border border-rose-300 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-800 hover:bg-rose-100 transition cursor-pointer">
                            <svg class="h-3.5 w-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Mark All Absent</span>
                        </button>

                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-1.5 text-xs font-bold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <span>Save Staff Attendance</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Staff Attendance Table -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-600 uppercase tracking-wider font-semibold">
                                <th class="px-4 py-3 w-12 text-center">#</th>
                                <th class="px-4 py-3">Faculty / Staff Member</th>
                                <th class="px-4 py-3 w-28">Department</th>
                                <th class="px-4 py-3 min-w-[340px]">Attendance Status</th>
                                <th class="px-4 py-3 w-28">Check In</th>
                                <th class="px-4 py-3 w-28">Check Out</th>
                                <th class="px-4 py-3 min-w-[180px]">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70" id="staff-table-body">
                            @foreach($roster as $index => $item)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-3 text-center text-slate-400 font-mono">{{ $index + 1 }}</td>

                                    <!-- Staff Profile -->
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8 shrink-0 rounded-md bg-slate-100 text-slate-700 flex items-center justify-center font-semibold text-xs border border-slate-200 uppercase overflow-hidden">
                                                @if($item['avatar'])
                                                    <img src="{{ asset($item['avatar']) }}" alt="{{ $item['full_name'] }}" class="h-full w-full object-cover">
                                                @else
                                                    {{ substr($item['full_name'], 0, 2) }}
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.staff.show', $item['staff_id']) }}" target="_blank" class="font-bold text-slate-900 hover:text-violet-600 transition">
                                                    {{ $item['full_name'] }}
                                                </a>
                                                <div class="flex items-center gap-1.5 text-[10px] text-slate-500">
                                                    <span>{{ $item['designation'] ?? 'Staff' }}</span>
                                                    <span>&bull;</span>
                                                    <span class="font-mono text-slate-400">{{ $item['employee_id'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="records[{{ $index }}][staff_id]" value="{{ $item['staff_id'] }}">
                                    </td>

                                    <!-- Department -->
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-700">
                                            {{ $item['department'] ?? 'General' }}
                                        </span>
                                    </td>

                                    <!-- Status Pills -->
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <!-- Present -->
                                            <label class="relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none staff-status-pill {{ $item['status'] === 'present' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200 hover:border-emerald-300' }}">
                                                <input type="radio"
                                                       name="records[{{ $index }}][status]"
                                                       value="present"
                                                       class="sr-only"
                                                       onchange="window.handleStaffStatusChange(this)"
                                                       {{ $item['status'] === 'present' ? 'checked' : '' }}>
                                                <span>Present</span>
                                            </label>

                                            <!-- Absent -->
                                            <label class="relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none staff-status-pill {{ $item['status'] === 'absent' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-slate-600 border-slate-200 hover:border-rose-300' }}">
                                                <input type="radio"
                                                       name="records[{{ $index }}][status]"
                                                       value="absent"
                                                       class="sr-only"
                                                       onchange="window.handleStaffStatusChange(this)"
                                                       {{ $item['status'] === 'absent' ? 'checked' : '' }}>
                                                <span>Absent</span>
                                            </label>

                                            <!-- Late -->
                                            <label class="relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none staff-status-pill {{ $item['status'] === 'late' ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-slate-600 border-slate-200 hover:border-amber-300' }}">
                                                <input type="radio"
                                                       name="records[{{ $index }}][status]"
                                                       value="late"
                                                       class="sr-only"
                                                       onchange="window.handleStaffStatusChange(this)"
                                                       {{ $item['status'] === 'late' ? 'checked' : '' }}>
                                                <span>Late</span>
                                            </label>

                                            <!-- Half-Day -->
                                            <label class="relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none staff-status-pill {{ $item['status'] === 'half_day' ? 'bg-sky-600 text-white border-sky-600' : 'bg-white text-slate-600 border-slate-200 hover:border-sky-300' }}">
                                                <input type="radio"
                                                       name="records[{{ $index }}][status]"
                                                       value="half_day"
                                                       class="sr-only"
                                                       onchange="window.handleStaffStatusChange(this)"
                                                       {{ $item['status'] === 'half_day' ? 'checked' : '' }}>
                                                <span>Half-Day</span>
                                            </label>

                                            <!-- On Leave -->
                                            <label class="relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none staff-status-pill {{ $item['status'] === 'on_leave' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300' }}">
                                                <input type="radio"
                                                       name="records[{{ $index }}][status]"
                                                       value="on_leave"
                                                       class="sr-only"
                                                       onchange="window.handleStaffStatusChange(this)"
                                                       {{ $item['status'] === 'on_leave' ? 'checked' : '' }}>
                                                <span>On Leave</span>
                                            </label>
                                        </div>
                                    </td>

                                    <!-- Check-In -->
                                    <td class="px-4 py-3">
                                        <input type="time"
                                               name="records[{{ $index }}][check_in_time]"
                                               value="{{ $item['check_in_time'] ?? '' }}"
                                               class="w-full rounded-lg border border-slate-200 bg-slate-50/40 px-2 py-1.5 text-xs text-slate-700 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                                    </td>

                                    <!-- Check-Out -->
                                    <td class="px-4 py-3">
                                        <input type="time"
                                               name="records[{{ $index }}][check_out_time]"
                                               value="{{ $item['check_out_time'] ?? '' }}"
                                               class="w-full rounded-lg border border-slate-200 bg-slate-50/40 px-2 py-1.5 text-xs text-slate-700 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                                    </td>

                                    <!-- Remarks -->
                                    <td class="px-4 py-3">
                                        <input type="text"
                                               name="records[{{ $index }}][remarks]"
                                               value="{{ $item['remarks'] ?? '' }}"
                                               placeholder="Notes / Duty details"
                                               class="w-full rounded-lg border border-slate-200 bg-slate-50/40 px-2.5 py-1.5 text-xs text-slate-700 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    @endif

</div>

@push('scripts')
<script>
    window.markAllStaff = function(statusTarget) {
        var rows = document.querySelectorAll('#staff-table-body tr');
        rows.forEach(function(row) {
            var targetRadio = row.querySelector('input[type="radio"][value="' + statusTarget + '"]');
            if (targetRadio) {
                targetRadio.checked = true;
                window.updateStaffRowVisuals(row, statusTarget);
            }
        });
        window.recalculateStaffMetrics();
    };

    window.handleStaffStatusChange = function(radioInput) {
        var row = radioInput.closest('tr');
        if (!row) return;
        window.updateStaffRowVisuals(row, radioInput.value);
        window.recalculateStaffMetrics();
    };

    window.updateStaffRowVisuals = function(row, status) {
        var pills = row.querySelectorAll('.staff-status-pill');
        var colorClasses = {
            'present': 'bg-emerald-600 text-white border-emerald-600',
            'absent': 'bg-rose-600 text-white border-rose-600',
            'late': 'bg-amber-500 text-white border-amber-500',
            'half_day': 'bg-sky-600 text-white border-sky-600',
            'on_leave': 'bg-indigo-600 text-white border-indigo-600'
        };

        pills.forEach(function(pill) {
            var input = pill.querySelector('input[type="radio"]');
            var val = input ? input.value : '';

            pill.className = 'relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none staff-status-pill bg-white text-slate-600 border-slate-200';

            if (input && input.checked) {
                pill.className = 'relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none staff-status-pill ' + (colorClasses[val] || 'bg-slate-800 text-white border-slate-800');
            }
        });
    };

    window.recalculateStaffMetrics = function() {
        var rows = document.querySelectorAll('#staff-table-body tr');
        var counts = { present: 0, absent: 0, late: 0, half_day: 0, on_leave: 0 };

        rows.forEach(function(row) {
            var checked = row.querySelector('input[type="radio"]:checked');
            if (checked && counts[checked.value] !== undefined) {
                counts[checked.value]++;
            }
        });

        var pEl = document.getElementById('stat-present');
        var aEl = document.getElementById('stat-absent');
        var lEl = document.getElementById('stat-late');
        var oEl = document.getElementById('stat-leave');

        if (pEl) pEl.textContent = counts.present;
        if (aEl) aEl.textContent = counts.absent;
        if (lEl) lEl.textContent = counts.late;
        if (oEl) oEl.textContent = counts.on_leave;
    };
</script>
@endpush
@endsection
