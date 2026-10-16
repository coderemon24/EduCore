@extends('layouts.admin')

@section('title', 'Dashboard Overview | School Administration')

@section('admin-content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Dashboard</span>
                @if(isset($currentSchool) && $currentSchool)
                    <span>/</span>
                    <span class="rounded bg-violet-50 px-1.5 py-0.5 text-[10px] font-semibold text-violet-700">{{ $currentSchool->name }}</span>
                @endif
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Institutional Overview</h1>
            <p class="text-xs text-slate-500 mt-0.5">Overview of student enrollment, faculty metrics, academic performance, and announcements for {{ $currentSchool->name ?? 'EduCore Academy' }}.</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2.5">
            <button type="button"
                    id="btn-export-report"
                    data-action="export-report"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50 hover:border-slate-300">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span>Export Report</span>
            </button>

            <button type="button"
                    id="page-admission-btn"
                    data-action="new-admission"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>New Student Admission</span>
            </button>
        </div>
    </div>

    <!-- 4 Cohesive Institutional KPI Metric Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <!-- 1. Total Students -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs transition hover:border-slate-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Total Enrolled Students</p>
                    <h3 class="mt-1.5 text-2xl font-bold text-slate-900 tracking-tight">{{ $kpi['students']['count'] }}</h3>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    {{ $kpi['students']['growth'] }}
                </span>
                <span class="text-slate-500 font-medium">{{ number_format($kpi['students']['boys']) }} Boys &bull; {{ number_format($kpi['students']['girls']) }} Girls</span>
            </div>
        </div>

        <!-- 2. Teachers & Faculty -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs transition hover:border-slate-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Teachers & Faculty</p>
                    <h3 class="mt-1.5 text-2xl font-bold text-slate-900 tracking-tight">{{ $kpi['teachers']['count'] }}</h3>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    {{ $kpi['teachers']['growth'] }}
                </span>
                <span class="text-slate-500 font-medium">{{ number_format($kpi['teachers']['active']) }} On Duty Today</span>
            </div>
        </div>

        <!-- 3. Registered Parents -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs transition hover:border-slate-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Registered Parents</p>
                    <h3 class="mt-1.5 text-2xl font-bold text-slate-900 tracking-tight">{{ $kpi['parents']['count'] }}</h3>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    {{ $kpi['parents']['growth'] }}
                </span>
                <span class="text-slate-500 font-medium">{{ $kpi['parents']['portal_active'] }} Portal Active</span>
            </div>
        </div>

        <!-- 4. Fee Collections -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs transition hover:border-slate-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Fee Collections</p>
                    <h3 class="mt-1.5 text-2xl font-bold text-slate-900 tracking-tight">{{ $kpi['earnings']['count'] }}</h3>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    {{ $kpi['earnings']['growth'] }}
                </span>
                <span class="text-slate-500 font-medium">{{ $kpi['earnings']['collected_rate'] }} Collection Rate</span>
            </div>
        </div>

    </div>

    <!-- Analytics Section: Academic Performance & Demographics -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        <!-- Comparative Academic Performance Chart (8 Columns) -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs lg:col-span-8 flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Academic Assessment Performance (Jan – Dec)</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Monthly student examination averages compared with faculty benchmark targets</p>
                    </div>
                    <!-- Legend -->
                    <div class="flex items-center gap-4 text-xs">
                        <div class="flex items-center gap-1.5 font-medium text-slate-700">
                            <span class="h-2.5 w-2.5 rounded-xs bg-violet-600"></span>
                            <span>Student Average</span>
                        </div>
                        <div class="flex items-center gap-1.5 font-medium text-slate-700">
                            <span class="h-2.5 w-2.5 rounded-xs bg-slate-300"></span>
                            <span>Faculty Benchmark</span>
                        </div>
                    </div>
                </div>

                <!-- Responsive SVG Bar Chart -->
                <div class="mt-6">
                    <div class="h-64 w-full">
                        <svg class="h-full w-full overflow-visible" viewBox="0 0 720 220" preserveAspectRatio="none">
                            <!-- Horizontal Grid Lines -->
                            <line x1="30" y1="20" x2="710" y2="20" stroke="#f1f5f9" stroke-width="1" />
                            <text x="5" y="24" fill="#94a3b8" font-size="10" font-family="sans-serif">100%</text>

                            <line x1="30" y1="65" x2="710" y2="65" stroke="#f1f5f9" stroke-width="1" />
                            <text x="10" y="69" fill="#94a3b8" font-size="10" font-family="sans-serif">75%</text>

                            <line x1="30" y1="110" x2="710" y2="110" stroke="#f1f5f9" stroke-width="1" />
                            <text x="10" y="114" fill="#94a3b8" font-size="10" font-family="sans-serif">50%</text>

                            <line x1="30" y1="155" x2="710" y2="155" stroke="#f1f5f9" stroke-width="1" />
                            <text x="10" y="159" fill="#94a3b8" font-size="10" font-family="sans-serif">25%</text>

                            <line x1="30" y1="200" x2="710" y2="200" stroke="#e2e8f0" stroke-width="1" />
                            <text x="15" y="204" fill="#94a3b8" font-size="10" font-family="sans-serif">0%</text>

                            <!-- Bars for Each Month -->
                            @foreach ($examPerformance as $index => $item)
                                @php
                                    $xBase = 50 + ($index * 55);
                                    $studentHeight = ($item['students'] / 100) * 180;
                                    $studentY = 200 - $studentHeight;
                                    $teacherHeight = ($item['teachers'] / 100) * 180;
                                    $teacherY = 200 - $teacherHeight;
                                @endphp

                                <!-- Student Bar (Violet) -->
                                <rect x="{{ $xBase }}" y="{{ $studentY }}" width="14" height="{{ $studentHeight }}" rx="2" fill="#7c3aed" class="transition-all hover:opacity-80">
                                    <title>{{ $item['month'] }} Students: {{ $item['students'] }}%</title>
                                </rect>

                                <!-- Benchmark Bar (Neutral Slate) -->
                                <rect x="{{ $xBase + 16 }}" y="{{ $teacherY }}" width="14" height="{{ $teacherHeight }}" rx="2" fill="#cbd5e1" class="transition-all hover:opacity-80">
                                    <title>{{ $item['month'] }} Benchmark: {{ $item['teachers'] }}%</title>
                                </rect>

                                <!-- Month Label -->
                                <text x="{{ $xBase + 15 }}" y="216" text-anchor="middle" fill="#64748b" font-size="11" font-weight="500" font-family="sans-serif">
                                    {{ $item['month'] }}
                                </text>
                            @endforeach
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs text-slate-500">
                <span>Annual Average Score: <strong class="text-slate-800">89.4%</strong></span>
                <span class="text-emerald-600 font-semibold">+3.8% over previous academic year</span>
            </div>
        </div>

        <!-- Student Demographics (4 Columns) -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs lg:col-span-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Student Demographics</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Enrollment distribution by gender</p>
                    </div>
                    <span class="rounded-md border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-[11px] font-semibold text-slate-700">{{ number_format($genderRatio['total']) }} Enrolled</span>
                </div>

                <!-- Structured Distribution Progress -->
                <div class="my-6 space-y-4">
                    <!-- Dual Stacked Bar -->
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5 font-medium">
                            <span class="text-slate-700">Male ({{ $genderRatio['male_percentage'] }}%)</span>
                            <span class="text-slate-700">Female ({{ $genderRatio['female_percentage'] }}%)</span>
                        </div>
                        <div class="h-3 w-full rounded-full bg-slate-100 flex overflow-hidden p-0.5 border border-slate-200">
                            <div class="h-full rounded-full bg-violet-600" style="width: {{ $genderRatio['male_percentage'] }}%"></div>
                            <div class="h-full rounded-full bg-sky-400" style="width: {{ $genderRatio['female_percentage'] }}%"></div>
                        </div>
                    </div>

                    <!-- Breakdown Cards -->
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-3">
                            <div class="flex items-center gap-1.5 text-xs text-slate-600 font-medium mb-1">
                                <span class="h-2 w-2 rounded-full bg-violet-600"></span>
                                <span>Male</span>
                            </div>
                            <p class="text-base font-bold text-slate-900">{{ number_format($genderRatio['male_count']) }}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $genderRatio['male_percentage'] }}% of students</p>
                        </div>
                        <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-3">
                            <div class="flex items-center gap-1.5 text-xs text-slate-600 font-medium mb-1">
                                <span class="h-2 w-2 rounded-full bg-sky-400"></span>
                                <span>Female</span>
                            </div>
                            <p class="text-base font-bold text-slate-900">{{ number_format($genderRatio['female_count']) }}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $genderRatio['female_percentage'] }}% of students</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Demographic Summary -->
            <div class="border-t border-slate-100 pt-3 text-xs text-slate-500">
                <span>Ratio: <strong class="text-slate-700">1.09 : 1</strong> (Male to Female distribution)</span>
            </div>
        </div>

    </div>

    <!-- Academic Merit Roll Table & Institutional Notices -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        <!-- Academic Merit Roll Table (8 Columns) -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-xs overflow-hidden lg:col-span-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-5 border-b border-slate-200 gap-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Star Students (Academic Merit Roll)</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Top performing students across current semester examinations</p>
                </div>
                <button type="button"
                        data-action="view-honors"
                        class="text-xs font-semibold text-violet-600 hover:text-violet-700 transition flex items-center gap-1 self-start sm:self-center">
                    <span>View All Honors</span>
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Clean Data Table (No Dummy Checkboxes) -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 border-b border-slate-200 font-semibold text-xs">
                        <tr>
                            <th class="px-5 py-3.5">Roll ID</th>
                            <th class="px-5 py-3.5">Student</th>
                            <th class="px-5 py-3.5">Score</th>
                            <th class="px-5 py-3.5">Percentage</th>
                            <th class="px-5 py-3.5">Session</th>
                            <th class="px-5 py-3.5 text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($starStudents as $student)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-xs font-semibold text-slate-600">
                                {{ $student['id'] }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $student['avatar'] }}" alt="{{ $student['name'] }}" class="h-8 w-8 rounded-full object-cover border border-slate-200">
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $student['name'] }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $student['grade'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800">
                                {{ $student['marks'] }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                    {{ $student['percentage'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 font-medium">
                                {{ $student['year'] }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <button type="button"
                                        data-action="view-student"
                                        data-student-name="{{ $student['name'] }}"
                                        data-student-id="{{ $student['id'] }}"
                                        data-student-grade="{{ $student['grade'] }}"
                                        data-student-marks="{{ $student['marks'] }}"
                                        data-student-percentage="{{ $student['percentage'] }}"
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
                                    <span>Profile</span>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Institutional Notices (4 Columns) -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs lg:col-span-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Institutional Notices</h2>
                        <p class="text-xs text-slate-500 mt-0.5">School announcements and circulars</p>
                    </div>
                </div>

                <!-- Notices List -->
                <div class="mt-4 space-y-3">
                    @foreach ($notices as $notice)
                    <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-3 hover:bg-slate-50 hover:border-slate-200 transition">
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="rounded px-2 py-0.5 text-[10px] font-semibold {{ $notice['color'] }}">
                                {{ $notice['badge'] }}
                            </span>
                            <span class="text-slate-400 font-mono text-[10px]">{{ $notice['date'] }}</span>
                        </div>
                        <h4 class="text-xs font-medium text-slate-800 leading-snug">{{ $notice['title'] }}</h4>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 mt-4 text-xs text-slate-500 flex items-center justify-between">
                <span>{{ count($notices) }} Active Bulletins</span>
                <span class="text-slate-400">Updated Daily</span>
            </div>
        </div>

    </div>

</div>
@endsection
