@extends('layouts.admin')

@section('title', 'Monthly Attendance Register & Reports | Administration')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.attendance.students.index') }}" class="text-slate-500 hover:text-violet-600 transition">Attendance</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Monthly Register</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Monthly Attendance Register</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Monthly section attendance sheet with daily presence codes, total aggregates, and attendance rates.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <x-report-export-dropdown
                :action="route('admin.attendance.reports.export')"
                :params="request()->query()"
                label="Export Register"
            />

            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer print:hidden">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Print Register</span>
            </button>

            <a href="{{ route('admin.attendance.students.index', ['academic_class_id' => $selectedClassId, 'section_id' => $selectedSectionId]) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer print:hidden">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Take Daily Attendance</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs print:hidden">
        <form method="GET" action="{{ route('admin.attendance.reports.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-6 items-end">
            <!-- Academic Session -->
            <div>
                <label for="filter_academic_year" class="block text-xs font-semibold text-slate-700 mb-1">Session</label>
                <select name="academic_year_id" id="filter_academic_year" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Class -->
            <div>
                <label for="filter_academic_class" class="block text-xs font-semibold text-slate-700 mb-1">Class</label>
                <select name="academic_class_id" id="filter_academic_class" onchange="window.handleReportClassChange(this.value)" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition" required>
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

            <!-- Month -->
            <div>
                <label for="filter_month" class="block text-xs font-semibold text-slate-700 mb-1">Month</label>
                <select name="month" id="filter_month" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Year -->
            <div>
                <label for="filter_year" class="block text-xs font-semibold text-slate-700 mb-1">Year</label>
                <select name="year" id="filter_year" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-slate-800 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>View Sheet</span>
                </button>
            </div>
        </form>
    </div>

    @if(!$selectedClassId || !$selectedSectionId)
        <div class="rounded-xl border border-slate-200 bg-white p-8 text-center shadow-2xs">
            <h3 class="text-sm font-bold text-slate-900">Select Class & Month to View Sheet</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                Choose an Academic Class and Section from the filter above, then click <strong>View Sheet</strong> to generate the monthly attendance register.
            </p>
        </div>
    @elseif($matrix->isEmpty())
        <div class="rounded-xl border border-slate-200 bg-white p-12 text-center shadow-2xs">
            <h3 class="text-base font-bold text-slate-800">No Enrolled Students</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                No active students were found enrolled in this section for the selected session.
            </p>
        </div>
    @else
        <!-- Monthly Metrics Overview -->
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Students</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5">{{ $overallStats['total_students'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Working Days</span>
                <p class="text-xl font-bold text-slate-900 mt-0.5">{{ $overallStats['total_working_days'] }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Average Attendance</span>
                <p class="text-xl font-bold text-emerald-600 mt-0.5">{{ $overallStats['avg_rate'] }}%</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Month & Session</span>
                <p class="text-sm font-bold text-slate-800 mt-1 truncate">
                    {{ \Carbon\Carbon::create(null, $selectedMonth, 1)->format('F') }} {{ $selectedYear }}
                </p>
            </div>
        </div>

        <!-- Legend -->
        <div class="flex items-center gap-3 text-[11px] font-medium text-slate-600 flex-wrap px-1">
            <span class="font-bold text-slate-700">Legend:</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-xs bg-emerald-600"></span> P = Present</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-xs bg-rose-600"></span> A = Absent</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-xs bg-amber-500"></span> L = Late</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-xs bg-sky-600"></span> HD = Half-Day</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-xs bg-indigo-600"></span> E = Excused</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-xs bg-slate-300"></span> W = Weekend</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-xs bg-amber-300"></span> H = Holiday</span>
        </div>

        <!-- Monthly Matrix Register Table -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[10px] font-bold text-slate-600 uppercase">
                            <th class="px-2 py-2 text-center w-10 border-r border-slate-200 sticky left-0 bg-slate-50 z-10">Roll</th>
                            <th class="px-3 py-2 text-left min-w-[150px] border-r border-slate-200 sticky left-10 bg-slate-50 z-10">Student</th>
                            @foreach($daysList as $d => $dayMeta)
                                <th class="px-1 py-1 min-w-[26px] border-r border-slate-200/60 {{ $dayMeta['is_weekend'] ? 'bg-slate-100 text-slate-400' : ($dayMeta['holiday'] ? 'bg-amber-50 text-amber-700' : '') }}"
                                    title="{{ $dayMeta['date'] }}{{ $dayMeta['holiday'] ? ' - '.$dayMeta['holiday']->title : '' }}">
                                    <span class="block">{{ $d }}</span>
                                    <span class="block text-[8px] font-normal text-slate-400">{{ substr($dayMeta['day_name'], 0, 1) }}</span>
                                </th>
                            @endforeach
                            <th class="px-2 py-2 w-10 text-emerald-700 bg-emerald-50/50">P</th>
                            <th class="px-2 py-2 w-10 text-rose-700 bg-rose-50/50">A</th>
                            <th class="px-2 py-2 w-10 text-amber-700 bg-amber-50/50">L</th>
                            <th class="px-2 py-2 w-10 text-indigo-700 bg-indigo-50/50">E</th>
                            <th class="px-3 py-2 w-16 text-slate-900 bg-slate-100 font-bold">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/70 text-[11px]">
                        @foreach($matrix as $row)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-2 py-2 font-bold text-slate-700 border-r border-slate-200 sticky left-0 bg-white z-10">
                                    {{ $row['roll_number'] }}
                                </td>
                                <td class="px-3 py-2 text-left font-semibold text-slate-900 border-r border-slate-200 sticky left-10 bg-white z-10 truncate max-w-[180px]">
                                    {{ $row['student']->full_name }}
                                </td>
                                @foreach($row['days'] as $d => $cell)
                                    @php
                                        $c = $cell['code'];
                                        $color = 'text-slate-400';
                                        if ($c === 'P') $color = 'text-emerald-700 font-bold';
                                        elseif ($c === 'A') $color = 'text-rose-700 font-bold bg-rose-50/80';
                                        elseif ($c === 'L') $color = 'text-amber-700 font-bold bg-amber-50/80';
                                        elseif ($c === 'HD') $color = 'text-sky-700 font-bold bg-sky-50/80';
                                        elseif ($c === 'E') $color = 'text-indigo-700 font-semibold bg-indigo-50/80';
                                        elseif ($c === 'H') $color = 'text-amber-500 bg-amber-50/40';
                                        elseif ($c === 'W') $color = 'text-slate-300 bg-slate-50';
                                    @endphp
                                    <td class="px-0.5 py-1.5 border-r border-slate-200/50 {{ $color }}">
                                        {{ $c }}
                                    </td>
                                @endforeach
                                <td class="px-2 py-2 font-bold text-emerald-700 bg-emerald-50/20">{{ $row['present'] }}</td>
                                <td class="px-2 py-2 font-bold text-rose-700 bg-rose-50/20">{{ $row['absent'] }}</td>
                                <td class="px-2 py-2 font-bold text-amber-700 bg-amber-50/20">{{ $row['late'] }}</td>
                                <td class="px-2 py-2 font-semibold text-indigo-700 bg-indigo-50/20">{{ $row['excused'] }}</td>
                                <td class="px-3 py-2 font-bold {{ $row['rate'] >= 80 ? 'text-emerald-700' : ($row['rate'] >= 65 ? 'text-amber-700' : 'text-rose-700') }} bg-slate-50/50">
                                    {{ $row['rate'] }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

@push('scripts')
<script>
    window.handleReportClassChange = function(classId) {
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
        });
    };
</script>
@endpush
@endsection
