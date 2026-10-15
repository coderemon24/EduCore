@extends('layouts.admin')

@section('title', 'Institutional Overview & Academic Telemetry')

@section('admin-content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Portal</a>
                <span>/</span>
                <span class="text-slate-400">Administration</span>
                <span>/</span>
                <span class="font-semibold text-violet-700">Dashboard Telemetry</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Institutional Overview & Telemetry</h1>
            <p class="text-xs text-slate-500 mt-0.5">Comprehensive academic telemetry, faculty records, student benchmarks, and financial realization.</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2.5">
            <button type="button"
                    onclick="window.Swal && Swal.fire({title: 'Generating Audit Report', text: 'Compiling institutional analytics for 2026-2027...', icon: 'info', timer: 1800, showConfirmButton: false});"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50 hover:border-slate-300">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span>Export PDF</span>
            </button>

            <button type="button"
                    id="page-admission-btn"
                    onclick="document.getElementById('btn-quick-admission')?.click()"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>New Student Admission</span>
            </button>
        </div>
    </div>

    <!-- 4 Pastel-Accented KPI Metric Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <!-- 1. Total Students (Soft Violet) -->
        <div class="relative overflow-hidden rounded-xl border border-violet-100 bg-white p-5 shadow-xs transition duration-200 hover:shadow-md hover:border-violet-200">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Enrolled Students</span>
                    <h3 class="mt-1 text-2xl font-extrabold text-slate-900 tracking-tight">{{ $kpi['students']['count'] }}</h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600 border border-violet-100/80">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                <span class="text-slate-400 font-medium">{{ number_format($kpi['students']['boys']) }} Boys • {{ number_format($kpi['students']['girls']) }} Girls</span>
            </div>
        </div>

        <!-- 2. Teachers & Faculty (Soft Sky) -->
        <div class="relative overflow-hidden rounded-xl border border-sky-100 bg-white p-5 shadow-xs transition duration-200 hover:shadow-md hover:border-sky-200">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Teachers & Faculty</span>
                    <h3 class="mt-1 text-2xl font-extrabold text-slate-900 tracking-tight">{{ $kpi['teachers']['count'] }}</h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-sky-600 border border-sky-100/80">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

        <!-- 3. Parents & Guardians (Soft Peach/Amber) -->
        <div class="relative overflow-hidden rounded-xl border border-amber-100 bg-white p-5 shadow-xs transition duration-200 hover:shadow-md hover:border-amber-200">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Registered Parents</span>
                    <h3 class="mt-1 text-2xl font-extrabold text-slate-900 tracking-tight">{{ $kpi['parents']['count'] }}</h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600 border border-amber-100/80">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                <span class="text-slate-500 font-medium">{{ $kpi['parents']['portal_active'] }} App Adoption</span>
            </div>
        </div>

        <!-- 4. Fee Earnings & Financials (Soft Mint/Emerald) -->
        <div class="relative overflow-hidden rounded-xl border border-emerald-100 bg-white p-5 shadow-xs transition duration-200 hover:shadow-md hover:border-emerald-200">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Term Fee Realization</span>
                    <h3 class="mt-1 text-2xl font-extrabold text-slate-900 tracking-tight">{{ $kpi['earnings']['count'] }}</h3>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100/80">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                <span class="text-slate-500 font-medium">{{ $kpi['earnings']['collected_rate'] }} Collected</span>
            </div>
        </div>

    </div>

    <!-- Analytics Section: Comparative Bar Chart & Radial Ratio Donut -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        <!-- Comparative Exam & Performance Bar Chart (8 Columns) -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs lg:col-span-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Comparative Performance Overview (Jan - Dec)</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Academic assessment index comparing student achievement vs. curriculum milestone targets</p>
                </div>
                <!-- Legend -->
                <div class="flex items-center gap-4 text-xs">
                    <div class="flex items-center gap-1.5 font-medium text-slate-700">
                        <span class="h-3 w-3 rounded-xs bg-violet-600"></span>
                        <span>Students</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-medium text-slate-700">
                        <span class="h-3 w-3 rounded-xs bg-sky-400"></span>
                        <span>Teachers Target</span>
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
                            <rect x="{{ $xBase }}" y="{{ $studentY }}" width="14" height="{{ $studentHeight }}" rx="3" fill="#7c3aed" class="transition-all hover:opacity-80">
                                <title>{{ $item['month'] }} Students: {{ $item['students'] }}%</title>
                            </rect>

                            <!-- Teacher Bar (Sky Blue) -->
                            <rect x="{{ $xBase + 16 }}" y="{{ $teacherY }}" width="14" height="{{ $teacherHeight }}" rx="3" fill="#38bdf8" class="transition-all hover:opacity-80">
                                <title>{{ $item['month'] }} Teachers: {{ $item['teachers'] }}%</title>
                            </rect>

                            <!-- Month Label -->
                            <text x="{{ $xBase + 15 }}" y="216" text-anchor="middle" fill="#64748b" font-size="11" font-weight="500" font-family="sans-serif">
                                {{ $item['month'] }}
                            </text>
                        @endforeach
                    </svg>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs text-slate-500">
                <span>Annual Academic Score Average: <strong class="text-slate-800">89.4%</strong></span>
                <span class="text-emerald-600 font-semibold">+3.8% improvement over previous academic session</span>
            </div>
        </div>

        <!-- Student Gender Ratio Donut Chart (4 Columns) -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs lg:col-span-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Student Gender Ratio</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Institutional demographics</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">15,000 Enrolled</span>
                </div>

                <!-- Donut Chart -->
                <div class="relative my-6 flex items-center justify-center">
                    <svg class="h-44 w-44 -rotate-90 transform" viewBox="0 0 100 100">
                        <!-- Background Circle -->
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="#f1f5f9" stroke-width="12" />
                        <!-- Boys Arc (52.3%) -> 2 * PI * 38 = 238.76. 52.3% = 124.87 -->
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="#7c3aed" stroke-width="12"
                                stroke-dasharray="124.9 238.8" stroke-linecap="round" />
                        <!-- Girls Arc (47.7%) -> 47.7% = 113.89, offset by 124.9 -->
                        <circle cx="50" cy="50" r="38" fill="transparent" stroke="#38bdf8" stroke-width="12"
                                stroke-dasharray="113.9 238.8" stroke-dashoffset="-124.9" stroke-linecap="round" />
                    </svg>

                    <!-- Center Statistics -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-2xl font-black text-slate-900">{{ number_format($genderRatio['total']) }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Enrolled</span>
                    </div>
                </div>
            </div>

            <!-- Breakdown Stats -->
            <div class="space-y-2.5 border-t border-slate-100 pt-3">
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-violet-600"></span>
                        <span class="font-medium text-slate-700">Male Students</span>
                    </div>
                    <span class="font-bold text-slate-900">{{ number_format($genderRatio['male_count']) }} ({{ $genderRatio['male_percentage'] }}%)</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-sky-400"></span>
                        <span class="font-medium text-slate-700">Female Students</span>
                    </div>
                    <span class="font-bold text-slate-900">{{ number_format($genderRatio['female_count']) }} ({{ $genderRatio['female_percentage'] }}%)</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Star Students Data Table & Institutional Bulletins Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        <!-- Star Students Data Table (8 Columns) -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-xs overflow-hidden lg:col-span-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-5 border-b border-slate-200 gap-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Star Students (Academic Merit Roll)</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Top percentile performers based on current semester examinations</p>
                </div>
                <button type="button"
                        onclick="window.Swal && Swal.fire({title: 'Merit Roll Directory', text: 'Displaying complete honors roll for all secondary grades.', icon: 'info', confirmButtonColor: '#7c3aed'});"
                        class="text-xs font-semibold text-violet-600 hover:text-violet-700 transition flex items-center gap-1 self-start sm:self-center">
                    <span>View All Honors</span>
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/80 text-slate-500 border-b border-slate-200 font-semibold uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5 w-10">
                                <input type="checkbox" class="rounded border-slate-300 text-violet-600 focus:ring-violet-500/20">
                            </th>
                            <th class="px-4 py-3.5">Roll ID</th>
                            <th class="px-4 py-3.5">Student Details</th>
                            <th class="px-4 py-3.5">Marks Scored</th>
                            <th class="px-4 py-3.5">Percentage</th>
                            <th class="px-4 py-3.5">Academic Year</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($starStudents as $student)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5">
                                <input type="checkbox" class="rounded border-slate-300 text-violet-600 focus:ring-violet-500/20">
                            </td>
                            <td class="px-4 py-3.5 font-mono text-[11px] font-semibold text-slate-600">
                                {{ $student['id'] }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $student['avatar'] }}" alt="{{ $student['name'] }}" class="h-8 w-8 rounded-full object-cover border border-slate-200">
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $student['name'] }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $student['grade'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-slate-800">
                                {{ $student['marks'] }}
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700 border border-emerald-200">
                                    {{ $student['percentage'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-500 font-medium">
                                {{ $student['year'] }}
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <button type="button"
                                        onclick="window.Swal && Swal.fire({title: '{{ $student['name'] }}', html: '<strong>Roll ID:</strong> {{ $student['id'] }}<br><strong>Grade:</strong> {{ $student['grade'] }}<br><strong>Score:</strong> {{ $student['marks'] }} ({{ $student['percentage'] }})', icon: 'success', confirmButtonColor: '#7c3aed'});"
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-700 hover:bg-slate-50 transition">
                                    <span>Profile</span>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Institutional Bulletins & Notices (4 Columns) -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs lg:col-span-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Institutional Notice Board</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Official school alerts & announcements</p>
                    </div>
                    <span class="rounded-full bg-violet-50 px-2 py-0.5 text-[10px] font-semibold text-violet-700">Official</span>
                </div>

                <!-- Notices List -->
                <div class="mt-4 space-y-3">
                    @foreach ($notices as $notice)
                    <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-3 hover:bg-slate-50 hover:border-slate-200 transition">
                        <div class="flex items-center justify-between text-[11px] mb-1">
                            <span class="rounded px-2 py-0.5 text-[10px] font-semibold {{ $notice['color'] }}">
                                {{ $notice['badge'] }}
                            </span>
                            <span class="text-slate-400 font-mono text-[10px]">{{ $notice['date'] }}</span>
                        </div>
                        <h4 class="text-xs font-semibold text-slate-800 leading-snug">{{ $notice['title'] }}</h4>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Action Card inside widget -->
            <div class="mt-5 rounded-lg border border-violet-100 bg-violet-50/50 p-3.5 text-xs">
                <div class="flex items-center gap-2 font-bold text-violet-900">
                    <svg class="h-4 w-4 text-violet-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Mid-Term Exams Notice</span>
                </div>
                <p class="mt-1 text-[11px] text-slate-600">Syllabus submissions close on 25 October. Ensure all class coordinators have uploaded rubrics.</p>
            </div>
        </div>

    </div>

</div>
@endsection
