@extends('layouts.admin')

@section('title', 'Student Daily Attendance | Administration')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Breadcrumbs -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="text-slate-500">Attendance</span>
                <span>/</span>
                <span class="font-semibold text-slate-700">Student Roll-Call</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Student Attendance Roll-Call</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Take daily section attendance, manage presence status, and maintain student attendance history.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.attendance.reports.index', ['academic_class_id' => $selectedClassId, 'section_id' => $selectedSectionId]) }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Monthly Register</span>
            </a>

            <a href="{{ route('admin.attendance.staff.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Staff Attendance</span>
            </a>
        </div>
    </div>

    <!-- Academic Filter Bar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.attendance.students.index') }}" id="attendance-filter-form" class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-5 items-end">
            <!-- Academic Session -->
            <div>
                <label for="filter_academic_year" class="block text-xs font-semibold text-slate-700 mb-1">Academic Session</label>
                <select name="academic_year_id" id="filter_academic_year" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_current ? '(Current)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Class / Grade -->
            <div>
                <label for="filter_academic_class" class="block text-xs font-semibold text-slate-700 mb-1">Class / Grade</label>
                <select name="academic_class_id" id="filter_academic_class" onchange="window.handleClassChange(this.value)" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition" required>
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Section -->
            <div>
                <label for="filter_section" class="block text-xs font-semibold text-slate-700 mb-1">Section</label>
                <select name="section_id" id="filter_section" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition" required>
                    <option value="">-- Select Section --</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec->id }}" {{ $selectedSectionId == $sec->id ? 'selected' : '' }}>
                            {{ $sec->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Attendance Date -->
            <div>
                <label for="attendance_date" class="block text-xs font-semibold text-slate-700 mb-1">Attendance Date</label>
                <input type="date"
                       name="attendance_date"
                       id="attendance_date"
                       value="{{ $attendanceDate }}"
                       max="{{ now()->toDateString() }}"
                       class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition"
                       required>
            </div>

            <!-- Submit Filter -->
            <div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-slate-800 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Load Roster</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Holiday Alert Notice (if selected date is a holiday) -->
    @if($holiday)
    <div class="rounded-xl border border-amber-200 bg-amber-50/80 p-4 shadow-2xs">
        <div class="flex items-start gap-3">
            <svg class="h-5 w-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="flex-1 text-xs">
                <p class="font-bold text-amber-900">Scheduled Academic Holiday / Non-Working Day</p>
                <p class="text-amber-800 mt-0.5">
                    <strong>{{ $holiday->title }}</strong> ({{ ucfirst(str_replace('_', ' ', $holiday->type)) }}) is scheduled from
                    {{ $holiday->start_date->format('M d, Y') }} to {{ $holiday->end_date->format('M d, Y') }}.
                    @if($holiday->description)
                        <span class="block mt-0.5 text-amber-700 italic">"{{ $holiday->description }}"</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    @if(!$selectedClassId || !$selectedSectionId)
        <!-- Initial Blank State -->
        <div class="rounded-xl border border-slate-200 bg-white p-8 text-center shadow-2xs">
            <h3 class="text-sm font-bold text-slate-900">Select Class & Section to Load Roster</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                Select an Academic Class and Section from the filter above, then click <strong>Load Roster</strong> to mark or view attendance.
            </p>
        </div>
    @elseif($roster->isEmpty())
        <!-- No Students Enrolled State -->
        <div class="rounded-xl border border-slate-200 bg-white p-12 text-center shadow-2xs">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-3">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">No Enrolled Students Found</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                There are currently no active students enrolled in this section for the selected academic year.
            </p>
            <div class="mt-4">
                <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition">
                    <span>Admit New Student</span>
                </a>
            </div>
        </div>
    @else
        <!-- Roster Roll-Call Form & Telemetry -->
        <form method="POST" action="{{ route('admin.attendance.students.store') }}" id="attendance-roster-form">
            @csrf
            <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">
            <input type="hidden" name="academic_class_id" value="{{ $selectedClassId }}">
            <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">
            <input type="hidden" name="attendance_date" value="{{ $attendanceDate }}">

            <!-- Telemetry & Bulk Action Toolbar -->
            <div class="mb-4 rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <!-- Metrics Badges -->
                    <div class="flex items-center gap-2 flex-wrap text-xs">
                        <span class="inline-flex items-center gap-1 rounded border border-slate-200 bg-slate-100 px-2.5 py-1 font-semibold text-slate-700">
                            <span>Total:</span>
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
                            <span>Excused:</span>
                            <span id="stat-excused" class="font-bold">{{ $summary['excused'] }}</span>
                        </span>
                        @if($summary['is_recorded'])
                            <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700 border border-blue-200 uppercase tracking-wider">
                                Saved
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 border border-amber-200 uppercase tracking-wider">
                                Not Submitted
                            </span>
                        @endif
                    </div>

                    <!-- Fast Quick-Mark Buttons & Save Action -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <button type="button"
                                onclick="window.markAllStudents('present')"
                                class="inline-flex items-center gap-1 rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-800 hover:bg-emerald-100 transition cursor-pointer">
                            <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Mark All Present</span>
                        </button>

                        <button type="button"
                                onclick="window.markAllStudents('absent')"
                                class="inline-flex items-center gap-1 rounded-lg border border-rose-300 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-800 hover:bg-rose-100 transition cursor-pointer">
                            <svg class="h-3.5 w-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Mark All Absent</span>
                        </button>

                        <button type="submit"
                                id="save-attendance-btn"
                                class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-1.5 text-xs font-bold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <span>Save Attendance</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Student Roster Table -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-600 uppercase tracking-wider font-semibold">
                                <th class="px-4 py-3 w-16 text-center">Roll #</th>
                                <th class="px-4 py-3">Student Name</th>
                                <th class="px-4 py-3 w-36">Admission #</th>
                                <th class="px-4 py-3 min-w-[340px]">Attendance Status</th>
                                <th class="px-4 py-3 min-w-[220px]">Remarks / Note</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/70" id="roster-table-body">
                            @foreach($roster as $index => $item)
                                <tr class="hover:bg-slate-50/50 transition student-row" data-student-id="{{ $item['student_id'] }}">
                                    <!-- Roll Number -->
                                    <td class="px-4 py-3 text-center font-bold text-slate-800">
                                        <span class="font-mono text-xs font-semibold text-slate-700">
                                            {{ $item['roll_number'] ?? ($index + 1) }}
                                        </span>
                                    </td>

                                    <!-- Student Profile -->
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8 shrink-0 rounded-md bg-slate-100 text-slate-700 flex items-center justify-center font-semibold text-xs border border-slate-200 uppercase overflow-hidden">
                                                @if($item['photo'])
                                                    <img src="{{ asset($item['photo']) }}" alt="{{ $item['full_name'] }}" class="h-full w-full object-cover">
                                                @else
                                                    {{ substr($item['full_name'], 0, 2) }}
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.students.show', $item['student_id']) }}" target="_blank" class="font-bold text-slate-900 hover:text-violet-600 transition">
                                                    {{ $item['full_name'] }}
                                                </a>
                                                <span class="block text-[10px] text-slate-400 capitalize">{{ $item['gender'] ?? 'Student' }}</span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="records[{{ $index }}][student_id]" value="{{ $item['student_id'] }}">
                                    </td>

                                    <!-- Admission Number -->
                                    <td class="px-4 py-3 font-mono text-[11px] text-slate-600 font-medium">
                                        {{ $item['admission_number'] }}
                                    </td>

                                    <!-- Status Pill Radio Buttons -->
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <!-- Present -->
                                            <label class="relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none status-pill {{ $item['status'] === 'present' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/50' }}">
                                                <input type="radio"
                                                       name="records[{{ $index }}][status]"
                                                       value="present"
                                                       class="sr-only"
                                                       onchange="window.handleStatusChange(this)"
                                                       {{ $item['status'] === 'present' ? 'checked' : '' }}>
                                                <span>Present</span>
                                            </label>

                                            <!-- Absent -->
                                            <label class="relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none status-pill {{ $item['status'] === 'absent' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-slate-600 border-slate-200 hover:border-rose-300 hover:bg-rose-50/50' }}">
                                                <input type="radio"
                                                       name="records[{{ $index }}][status]"
                                                       value="absent"
                                                       class="sr-only"
                                                       onchange="window.handleStatusChange(this)"
                                                       {{ $item['status'] === 'absent' ? 'checked' : '' }}>
                                                <span>Absent</span>
                                            </label>

                                            <!-- Late -->
                                            <label class="relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none status-pill {{ $item['status'] === 'late' ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-slate-600 border-slate-200 hover:border-amber-300 hover:bg-amber-50/50' }}">
                                                <input type="radio"
                                                       name="records[{{ $index }}][status]"
                                                       value="late"
                                                       class="sr-only"
                                                       onchange="window.handleStatusChange(this)"
                                                       {{ $item['status'] === 'late' ? 'checked' : '' }}>
                                                <span>Late</span>
                                            </label>

                                            <!-- Half-Day -->
                                            <label class="relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none status-pill {{ $item['status'] === 'half_day' ? 'bg-sky-600 text-white border-sky-600' : 'bg-white text-slate-600 border-slate-200 hover:border-sky-300 hover:bg-sky-50/50' }}">
                                                <input type="radio"
                                                       name="records[{{ $index }}][status]"
                                                       value="half_day"
                                                       class="sr-only"
                                                       onchange="window.handleStatusChange(this)"
                                                       {{ $item['status'] === 'half_day' ? 'checked' : '' }}>
                                                <span>Half-Day</span>
                                            </label>

                                            <!-- Excused -->
                                            <label class="relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none status-pill {{ $item['status'] === 'excused' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50' }}">
                                                <input type="radio"
                                                       name="records[{{ $index }}][status]"
                                                       value="excused"
                                                       class="sr-only"
                                                       onchange="window.handleStatusChange(this)"
                                                       {{ $item['status'] === 'excused' ? 'checked' : '' }}>
                                                <span>Excused</span>
                                            </label>
                                        </div>
                                    </td>

                                    <!-- Remarks Input -->
                                    <td class="px-4 py-3">
                                        <input type="text"
                                               name="records[{{ $index }}][remarks]"
                                               value="{{ $item['remarks'] ?? '' }}"
                                               placeholder="e.g. Doctor's appointment, bus breakdown"
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
    // Handle dependent sections dropdown loading
    window.handleClassChange = function(classId) {
        var sectionSelect = document.getElementById('filter_section');
        if (!sectionSelect) return;

        sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';
        if (!classId) return;

        fetch('{{ url("admin/academic/classes") }}/' + classId + '/sections', {
            headers: { 'Accept': 'application/json' }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            var sections = Array.isArray(data) ? data : (data.sections || []);
            sections.forEach(function(sec) {
                var opt = document.createElement('option');
                opt.value = sec.id;
                opt.textContent = sec.name;
                sectionSelect.appendChild(opt);
            });
        })
        .catch(function(err) {
            console.error('Failed to load sections:', err);
        });
    };

    // Fast Mark All Students
    window.markAllStudents = function(statusTarget) {
        var rows = document.querySelectorAll('#roster-table-body tr');
        rows.forEach(function(row) {
            var targetRadio = row.querySelector('input[type="radio"][value="' + statusTarget + '"]');
            if (targetRadio) {
                targetRadio.checked = true;
                window.updateRowVisuals(row, statusTarget);
            }
        });
        window.recalculateMetrics();
    };

    // Handle single radio status change
    window.handleStatusChange = function(radioInput) {
        var row = radioInput.closest('tr');
        if (!row) return;
        window.updateRowVisuals(row, radioInput.value);
        window.recalculateMetrics();
    };

    // Update row pill highlight colors
    window.updateRowVisuals = function(row, status) {
        var pills = row.querySelectorAll('.status-pill');
        var colorClasses = {
            'present': 'bg-emerald-600 text-white border-emerald-600',
            'absent': 'bg-rose-600 text-white border-rose-600',
            'late': 'bg-amber-500 text-white border-amber-500',
            'half_day': 'bg-sky-600 text-white border-sky-600',
            'excused': 'bg-indigo-600 text-white border-indigo-600'
        };

        pills.forEach(function(pill) {
            var input = pill.querySelector('input[type="radio"]');
            var val = input ? input.value : '';

            // Strip active styles
            pill.className = 'relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none status-pill bg-white text-slate-600 border-slate-200';

            if (input && input.checked) {
                pill.className = 'relative flex items-center justify-center rounded-lg border px-2.5 py-1.5 text-xs font-bold cursor-pointer transition select-none status-pill ' + (colorClasses[val] || 'bg-slate-800 text-white border-slate-800');
            }
        });
    };

    // Live update telemetry counters
    window.recalculateMetrics = function() {
        var rows = document.querySelectorAll('#roster-table-body tr');
        var counts = { present: 0, absent: 0, late: 0, half_day: 0, excused: 0 };

        rows.forEach(function(row) {
            var checked = row.querySelector('input[type="radio"]:checked');
            if (checked && counts[checked.value] !== undefined) {
                counts[checked.value]++;
            }
        });

        var pEl = document.getElementById('stat-present');
        var aEl = document.getElementById('stat-absent');
        var lEl = document.getElementById('stat-late');
        var eEl = document.getElementById('stat-excused');

        if (pEl) pEl.textContent = counts.present;
        if (aEl) aEl.textContent = counts.absent;
        if (lEl) lEl.textContent = counts.late;
        if (eEl) eEl.textContent = counts.excused;
    };
</script>
@endpush
@endsection
