@extends('layouts.admin')

@section('title', 'Student Directory | Student Management')

@section('admin-content')
<div class="space-y-6">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="text-slate-500">Student Management</span>
                <span>/</span>
                <span class="font-semibold text-slate-700">Student Directory</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Student Directory</h1>
            <p class="text-xs text-slate-500 mt-0.5">Comprehensive student information system, admissions, enrollments, and status tracking.</p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('admin.students.promotions.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span>Bulk Promotions</span>
            </a>

            <button type="button"
                    onclick="document.getElementById('batch-optional-subject-modal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Batch 4th Subject</span>
            </button>

            <a href="{{ route('admin.students.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Admit New Student</span>
            </a>
        </div>
    </div>

    <!-- Status Tabs Toolbar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
        <div class="flex items-center gap-2 overflow-x-auto text-xs font-medium pb-1 sm:pb-0">
            @php
                $tabs = [
                    'all' => ['label' => 'All Students', 'count' => $statusCounts['all'] ?? 0],
                    'active' => ['label' => 'Active', 'count' => $statusCounts['active'] ?? 0],
                    'inactive' => ['label' => 'Inactive', 'count' => $statusCounts['inactive'] ?? 0],
                    'trashed' => ['label' => 'Archived / Trashed', 'count' => $statusCounts['trashed'] ?? 0],
                ];
            @endphp
            @foreach ($tabs as $key => $tab)
                @php
                    $isSelected = ($statusFilter ?? 'all') === $key;
                    $isTrashTab = $key === 'trashed';
                @endphp
                <a href="{{ route('admin.students.index', array_merge(request()->query(), ['status' => $key, 'page' => 1])) }}"
                   class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 transition-colors whitespace-nowrap cursor-pointer {{ $isSelected ? ($isTrashTab ? 'bg-rose-600 text-white font-semibold shadow-2xs' : 'bg-violet-600 text-white font-semibold shadow-2xs') : ($isTrashTab ? 'text-rose-700 bg-rose-50/70 hover:bg-rose-100' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900') }}">
                    @if ($isTrashTab)
                        <svg class="h-3.5 w-3.5 {{ $isSelected ? 'text-rose-100' : 'text-rose-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    @endif
                    <span>{{ $tab['label'] }}</span>
                    <span class="rounded-full px-1.5 py-0.2 text-[10px] font-bold {{ $isSelected ? 'bg-black/20 text-white' : ($isTrashTab ? 'bg-rose-200 text-rose-800' : 'bg-slate-200 text-slate-700') }}">
                        {{ $tab['count'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Trashed Archive Context Banner -->
    @if (($statusFilter ?? 'all') === 'trashed')
        <div class="rounded-xl border border-rose-200 bg-rose-50/60 p-4 text-xs text-rose-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-rose-100 flex items-center justify-center text-rose-700 shrink-0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-rose-900">Viewing Archived / Trashed Student Profiles</h4>
                    <p class="text-rose-700 text-[11px] mt-0.5">These records are soft-deleted. You can restore them to active status or permanently purge them from the institutional database.</p>
                </div>
            </div>
            <a href="{{ route('admin.students.index', ['status' => 'all']) }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-rose-300 bg-white px-3 py-1.5 text-xs font-semibold text-rose-800 hover:bg-rose-50 shadow-2xs shrink-0 self-start sm:self-auto cursor-pointer">
                <span>Back to Active Directory</span>
            </a>
        </div>
    @endif

    <!-- Telemetry Metric Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Students -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-slate-500">Total Enrolled</span>
                <span class="rounded-md bg-violet-50 p-1.5 text-violet-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </span>
            </div>
            <p class="mt-2 text-xl font-bold tracking-tight text-slate-900">{{ number_format($stats['total'] ?? 0) }}</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Active institutional roster</p>
        </div>

        <!-- Active Students -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-slate-500">Active Students</span>
                <span class="rounded-md bg-emerald-50 p-1.5 text-emerald-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="mt-2 text-xl font-bold tracking-tight text-slate-900">{{ number_format($stats['active'] ?? 0) }}</p>
            <p class="text-[10px] text-emerald-600 font-medium mt-0.5">Regular attendance & classes</p>
        </div>

        <!-- Male Students -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-slate-500">Male Students</span>
                <span class="rounded-md bg-blue-50 p-1.5 text-blue-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
            </div>
            <p class="mt-2 text-xl font-bold tracking-tight text-slate-900">{{ number_format($stats['male'] ?? 0) }}</p>
            <p class="text-[10px] text-slate-400 mt-0.5">{{ ($stats['total'] ?? 0) > 0 ? round(($stats['male'] / $stats['total']) * 100) : 0 }}% of institution</p>
        </div>

        <!-- Female Students -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-slate-500">Female Students</span>
                <span class="rounded-md bg-rose-50 p-1.5 text-rose-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
            </div>
            <p class="mt-2 text-xl font-bold tracking-tight text-slate-900">{{ number_format($stats['female'] ?? 0) }}</p>
            <p class="text-[10px] text-slate-400 mt-0.5">{{ ($stats['total'] ?? 0) > 0 ? round(($stats['female'] / $stats['total']) * 100) : 0 }}% of institution</p>
        </div>
    </div>

    <!-- Search & Faceted Filter Toolbar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 sm:p-5 shadow-2xs">
        <form method="GET" action="{{ route('admin.students.index') }}" class="space-y-4">
            <input type="hidden" name="status" value="{{ $statusFilter ?? 'all' }}">

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6">
                <!-- Search Query -->
                <div class="lg:col-span-2">
                    <label for="search" class="block text-[11px] font-semibold text-slate-600 mb-1">Search Keywords</label>
                    <div class="relative">
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Admission No, Name, Roll, Phone..."
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 py-1.5 pl-8 pr-3 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                        <svg class="h-3.5 w-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Academic Session -->
                <div>
                    <label for="academic_year_id" class="block text-[11px] font-semibold text-slate-600 mb-1">Academic Session</label>
                    <select id="academic_year_id"
                            name="academic_year_id"
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-2.5 py-1.5 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        <option value="">All Sessions</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_current ? '(Current)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Class -->
                <div>
                    <label for="academic_class_id" class="block text-[11px] font-semibold text-slate-600 mb-1">Class / Grade</label>
                    <select id="academic_class_id"
                            name="academic_class_id"
                            data-academic-class-select
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-2.5 py-1.5 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        <option value="">All Classes</option>
                        @foreach ($academicClasses as $class)
                            <option value="{{ $class->id }}" {{ request('academic_class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Section (Dependent Dropdown) -->
                <div>
                    <label for="section_id" class="block text-[11px] font-semibold text-slate-600 mb-1">Section</label>
                    <select id="section_id"
                            name="section_id"
                            data-dependent-sections
                            data-selected="{{ request('section_id') }}"
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-2.5 py-1.5 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        <option value="">All Sections</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-[11px] font-semibold text-slate-600 mb-1">Gender</label>
                    <select id="gender"
                            name="gender"
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-2.5 py-1.5 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        <option value="">All Genders</option>
                        <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Apply Filters</span>
                    </button>
                    @if (request()->hasAny(['search', 'academic_year_id', 'academic_class_id', 'section_id', 'gender']))
                        <a href="{{ route('admin.students.index', ['status' => $statusFilter ?? 'all']) }}"
                           class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition cursor-pointer">
                            Clear Filters
                        </a>
                    @endif
                </div>

                <span class="text-xs text-slate-500 font-medium">
                    Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students
                </span>
            </div>
        </form>
    </div>

    <!-- Bulk Action Floating Bar (shown when checkboxes are selected) -->
    <div id="bulk-action-bar"
         class="hidden rounded-xl border border-violet-200 bg-violet-50/90 px-4 py-3 shadow-sm transition-all duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-violet-600 text-[11px] font-bold text-white" id="bulk-selected-count">0</span>
            <span class="text-xs font-semibold text-violet-900" id="bulk-selected-label">students selected</span>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if (($statusFilter ?? 'all') === 'trashed')
                <!-- Bulk Restore -->
                <button type="button"
                        id="btn-bulk-restore"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-emerald-700 transition cursor-pointer">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Restore Selected</span>
                </button>

                <!-- Bulk Permanent Purge -->
                <button type="button"
                        id="btn-bulk-force-delete"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-rose-700 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-rose-800 transition cursor-pointer">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Permanently Purge</span>
                </button>
            @else
                <!-- Bulk Soft Delete (Move to Trash) -->
                <button type="button"
                        id="btn-bulk-delete"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-rose-700 transition cursor-pointer">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Move to Trash</span>
                </button>
            @endif

            <button type="button"
                    id="btn-bulk-clear"
                    class="rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                Deselect All
            </button>
        </div>
    </div>

    <!-- Student Roster Table -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200/80 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3 pl-4 pr-1 w-10">
                            <input type="checkbox"
                                   id="select-all-students"
                                   title="Select all on this page"
                                   class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500 cursor-pointer">
                        </th>
                        <th class="py-3 pl-2 pr-3">Student Name & Identity</th>
                        <th class="px-3 py-3">Admission No</th>
                        <th class="px-3 py-3">Current Class & Section</th>
                        <th class="px-3 py-3">Roll No</th>
                        <th class="px-3 py-3">Primary Guardian</th>
                        <th class="px-3 py-3">Gender / DOB</th>
                        <th class="px-3 py-3">Status</th>
                        <th class="py-3 pl-3 pr-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse ($students as $student)
                        @php
                            $enrollment = $student->currentEnrollment;
                            $guardian = $student->primaryGuardian;
                            $isTrashed = $student->trashed();
                        @endphp
                        <tr class="transition-colors hover:bg-slate-50/80 {{ $isTrashed ? 'bg-rose-50/30' : '' }}">
                            <!-- Checkbox Column -->
                            <td class="py-3 pl-4 pr-1">
                                <input type="checkbox"
                                       value="{{ $student->id }}"
                                       class="student-checkbox h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500 cursor-pointer">
                            </td>

                            <!-- Student Name & Avatar -->
                            <td class="py-3 pl-2 pr-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $student->photo_url }}"
                                         alt="{{ $student->full_name }}"
                                         class="h-9 w-9 rounded-full object-cover border border-slate-200 bg-slate-100 shrink-0">
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.students.show', $student) }}"
                                           class="font-semibold text-slate-900 hover:text-violet-600 transition truncate block">
                                            {{ $student->full_name }}
                                        </a>
                                        <p class="text-[11px] text-slate-500 truncate">{{ $student->email ?? $student->phone ?? 'No contact record' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Admission Number -->
                            <td class="px-3 py-3 font-mono font-semibold text-slate-800 text-[11px]">
                                <span class="rounded bg-slate-100 px-2 py-0.5 border border-slate-200/70 text-slate-700">
                                    {{ $student->admission_number }}
                                </span>
                            </td>

                            <!-- Class & Section -->
                            <td class="px-3 py-3">
                                @if ($enrollment && $enrollment->academicClass)
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="inline-flex items-center rounded-md border border-violet-100 bg-violet-50/80 px-2 py-0.5 text-[11px] font-semibold text-violet-700">
                                            {{ $enrollment->academicClass->name }}
                                        </span>
                                        @if ($enrollment->section)
                                            <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-100 px-1.5 py-0.5 text-[10px] font-medium text-slate-600">
                                                Sec: {{ $enrollment->section->name }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Not Enrolled</span>
                                @endif
                            </td>

                            <!-- Roll No -->
                            <td class="px-3 py-3 font-mono font-medium text-slate-700 text-[11px]">
                                {{ $enrollment?->roll_number ? '#'.$enrollment->roll_number : '—' }}
                            </td>

                            <!-- Guardian -->
                            <td class="px-3 py-3">
                                @if ($guardian)
                                    <div>
                                        <p class="font-medium text-slate-800 text-xs">{{ $guardian->name }}</p>
                                        <p class="text-[10px] text-slate-500 font-mono">{{ $guardian->phone }}</p>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">None Linked</span>
                                @endif
                            </td>

                            <!-- Gender & DOB -->
                            <td class="px-3 py-3">
                                <span class="capitalize text-slate-700 font-medium">{{ $student->gender }}</span>
                                <p class="text-[10px] text-slate-400">{{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : '—' }}</p>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-3 py-3">
                                @if ($isTrashed)
                                    <span class="inline-flex items-center gap-1 rounded-full border border-rose-200 bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-700 uppercase tracking-wider">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        Trashed
                                    </span>
                                @elseif ($student->status === 'active')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 uppercase tracking-wider">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                @elseif ($student->status === 'promoted')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-blue-200 bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700 uppercase tracking-wider">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                        Promoted
                                    </span>
                                @elseif ($student->status === 'transferred')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 uppercase tracking-wider">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        Transferred
                                    </span>
                                @elseif ($student->status === 'graduated')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-indigo-200 bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-700 uppercase tracking-wider">
                                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                        Graduated
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600 uppercase tracking-wider">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3 pl-3 pr-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if ($isTrashed)
                                        <!-- Restore Student -->
                                        <button type="button"
                                                data-id="{{ $student->id }}"
                                                data-name="{{ $student->full_name }}"
                                                title="Restore Student"
                                                class="btn-restore-student inline-flex items-center gap-1 rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 shadow-2xs transition hover:bg-emerald-100 hover:border-emerald-300 cursor-pointer">
                                            <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span>Restore</span>
                                        </button>

                                        <!-- Permanently Purge Student -->
                                        <button type="button"
                                                data-id="{{ $student->id }}"
                                                data-name="{{ $student->full_name }}"
                                                title="Permanently Purge"
                                                class="btn-force-delete-student inline-flex items-center gap-1 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 shadow-2xs transition hover:bg-rose-100 hover:border-rose-300 cursor-pointer">
                                            <svg class="h-3.5 w-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Purge</span>
                                        </button>
                                    @else
                                        <!-- View Profile -->
                                        <a href="{{ route('admin.students.show', $student) }}"
                                           title="View Student Profile"
                                           class="inline-flex h-7 w-7 items-center justify-center rounded-md text-slate-500 hover:bg-slate-100 hover:text-violet-600 transition cursor-pointer">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <!-- Promote / Transfer -->
                                        <a href="{{ route('admin.students.promote', $student) }}"
                                           title="Promote or Transfer Student"
                                           class="inline-flex h-7 w-7 items-center justify-center rounded-md text-slate-500 hover:bg-slate-100 hover:text-blue-600 transition cursor-pointer">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.students.edit', $student) }}"
                                           title="Edit Personal Information"
                                           class="inline-flex h-7 w-7 items-center justify-center rounded-md text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition cursor-pointer">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <!-- Soft Delete (Move to Trash) -->
                                        <button type="button"
                                                data-id="{{ $student->id }}"
                                                data-name="{{ $student->full_name }}"
                                                title="Move to Trash"
                                                class="btn-archive-student inline-flex h-7 w-7 items-center justify-center rounded-md text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition cursor-pointer">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="rounded-full bg-slate-100 p-3 mb-3 text-slate-400">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-800">No Student Records Found</h3>
                                    <p class="text-xs text-slate-500 mt-1 max-w-sm">
                                        @if (($statusFilter ?? 'all') === 'trashed')
                                            The trash bin is currently empty.
                                        @else
                                            No students matched your query or filters. Clear the active filters or admit a new student.
                                        @endif
                                    </p>
                                    <div class="mt-4 flex items-center gap-2">
                                        @if (($statusFilter ?? 'all') === 'trashed')
                                            <a href="{{ route('admin.students.index', ['status' => 'all']) }}"
                                               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                                                <span>Return to Active Students</span>
                                            </a>
                                        @else
                                            <a href="{{ route('admin.students.create') }}"
                                               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                <span>Admit First Student</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $students->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Hidden Action Dispatch Form for SweetAlert Submissions (Single Actions) -->
<form id="action-dispatch-form" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="_method" id="action-dispatch-method" value="POST">
</form>

<!-- Hidden Bulk Action Form -->
<form id="bulk-action-form" method="POST" action="{{ route('admin.students.bulk-action') }}" class="hidden">
    @csrf
    <input type="hidden" name="action" id="bulk-action-input" value="delete">
    <div id="bulk-action-ids-container"></div>
</form>

<!-- Batch Assign 4th Subject Modal -->
<div id="batch-optional-subject-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 hidden">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Batch Assign 4th / Optional Subject</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Assign an optional subject across a whole class or section cohort.</p>
            </div>
            <button type="button"
                    onclick="document.getElementById('batch-optional-subject-modal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 transition cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.students.batch-assign-optional-subject') }}" class="p-5 space-y-4">
            @csrf
            <!-- Session -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Academic Session <span class="text-rose-500">*</span></label>
                <select name="academic_year_id" required class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-800">
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $year->is_current ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_current ? '(Current)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Class -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Class / Grade <span class="text-rose-500">*</span></label>
                <select name="academic_class_id" required data-academic-class-select class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-800">
                    <option value="">-- Select Class --</option>
                    @foreach ($academicClasses as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Section -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Section (Optional - leave blank for all sections)</label>
                <select name="section_id" data-dependent-sections class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-800">
                    <option value="">All Sections</option>
                </select>
            </div>

            <!-- Optional Subject -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">4th Subject to Assign</label>
                <select name="optional_subject_id" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-800">
                    <option value="">-- Clear / None --</option>
                    @foreach ($subjects as $subj)
                        <option value="{{ $subj->id }}">{{ $subj->name }} ({{ $subj->code }})</option>
                    @endforeach
                </select>
                <p class="mt-1 text-[11px] text-slate-500">Sets or clears the 4th subject for selected students in bulk.</p>
            </div>

            <!-- Overwrite Checkbox -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="overwrite_existing" name="overwrite_existing" value="1" class="rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                <label for="overwrite_existing" class="text-xs text-slate-600">Overwrite existing assigned optional subjects</label>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                <button type="button"
                        onclick="document.getElementById('batch-optional-subject-modal').classList.add('hidden')"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 cursor-pointer">
                    Cancel
                </button>
                <button type="submit"
                        class="rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-violet-700 cursor-pointer">
                    Assign to Cohort
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    var $selectAll = $('#select-all-students');
    var $checkboxes = $('.student-checkbox');
    var $actionBar = $('#bulk-action-bar');
    var $countSpan = $('#bulk-selected-count');
    var $labelSpan = $('#bulk-selected-label');
    var $actionForm = $('#action-dispatch-form');
    var $actionMethod = $('#action-dispatch-method');
    var $bulkForm = $('#bulk-action-form');
    var $bulkActionInput = $('#bulk-action-input');
    var $bulkIdsContainer = $('#bulk-action-ids-container');

    // Update Bulk Bar Visibility & Count
    function updateBulkBar() {
        var selected = $checkboxes.filter(':checked').length;
        if (selected > 0) {
            $countSpan.text(selected);
            $labelSpan.text(selected === 1 ? 'student selected' : 'students selected');
            $actionBar.removeClass('hidden');
        } else {
            $actionBar.addClass('hidden');
        }

        // Keep Select All checkbox in sync
        var total = $checkboxes.length;
        $selectAll.prop('checked', total > 0 && selected === total);
    }

    // Toggle Select All
    $selectAll.on('change', function() {
        var isChecked = $(this).is(':checked');
        $checkboxes.prop('checked', isChecked);
        updateBulkBar();
    });

    // Individual Checkbox change
    $checkboxes.on('change', function() {
        updateBulkBar();
    });

    // Deselect All
    $('#btn-bulk-clear').on('click', function() {
        $selectAll.prop('checked', false);
        $checkboxes.prop('checked', false);
        updateBulkBar();
    });

    // Submit Bulk Form
    function submitBulk(action, title, text, confirmButtonText, confirmButtonColor) {
        var selectedIds = [];
        $checkboxes.filter(':checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        function executeSubmit() {
            $bulkActionInput.val(action);
            $bulkIdsContainer.empty();
            selectedIds.forEach(function(id) {
                $bulkIdsContainer.append('<input type="hidden" name="ids[]" value="' + id + '">');
            });
            $bulkForm.submit();
        }

        if (window.Swal) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor || '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmButtonText || 'Confirm'
            }).then(function(result) {
                if (result.isConfirmed) {
                    executeSubmit();
                }
            });
        } else if (confirm(text)) {
            executeSubmit();
        }
    }

    // Bulk Delete (Move to Trash)
    $('#btn-bulk-delete').on('click', function() {
        var count = $checkboxes.filter(':checked').length;
        submitBulk(
            'delete',
            'Move ' + count + ' Students to Trash?',
            'Selected student profiles and their enrollments will be archived in the trash bin.',
            'Yes, move to trash',
            '#e11d48'
        );
    });

    // Bulk Restore
    $('#btn-bulk-restore').on('click', function() {
        var count = $checkboxes.filter(':checked').length;
        submitBulk(
            'restore',
            'Restore ' + count + ' Students?',
            'Selected students will be restored back to the active student directory.',
            'Yes, restore students',
            '#059669'
        );
    });

    // Bulk Force Delete
    $('#btn-bulk-force-delete').on('click', function() {
        var count = $checkboxes.filter(':checked').length;
        submitBulk(
            'force_delete',
            'Permanently Purge ' + count + ' Students?',
            'CRITICAL: This permanently erases the selected students, photos, and historical enrollment ledgers. This action CANNOT be undone.',
            'Yes, permanently purge',
            '#be123c'
        );
    });

    // Single Archive (Soft Delete)
    $(document).on('click', '.btn-archive-student', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        var url = '{{ url("admin/students") }}/' + id;

        if (window.Swal) {
            Swal.fire({
                title: 'Move ' + name + ' to Trash?',
                text: 'The student profile and active enrollment will be archived. You can restore them anytime from the Trashed tab.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, move to trash'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $actionForm.attr('action', url);
                    $actionMethod.val('DELETE');
                    $actionForm.submit();
                }
            });
        } else if (confirm('Move ' + name + ' to trash?')) {
            $actionForm.attr('action', url);
            $actionMethod.val('DELETE');
            $actionForm.submit();
        }
    });

    // Single Restore
    $(document).on('click', '.btn-restore-student', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        var url = '{{ url("admin/students") }}/' + id + '/restore';

        if (window.Swal) {
            Swal.fire({
                title: 'Restore ' + name + '?',
                text: 'This will restore the student profile and enrollment back to active records.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, restore'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $actionForm.attr('action', url);
                    $actionMethod.val('POST');
                    $actionForm.submit();
                }
            });
        } else if (confirm('Restore ' + name + '?')) {
            $actionForm.attr('action', url);
            $actionMethod.val('POST');
            $actionForm.submit();
        }
    });

    // Single Force Delete (Permanent Purge)
    $(document).on('click', '.btn-force-delete-student', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        var url = '{{ url("admin/students") }}/' + id + '/force-delete';

        if (window.Swal) {
            Swal.fire({
                title: 'Permanently Purge ' + name + '?',
                text: 'CRITICAL: This permanently removes the student, stored photo, and enrollment records. This action cannot be reverted.',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#be123c',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, permanently purge'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $actionForm.attr('action', url);
                    $actionMethod.val('DELETE');
                    $actionForm.submit();
                }
            });
        } else if (confirm('Permanently purge ' + name + '?')) {
            $actionForm.attr('action', url);
            $actionMethod.val('DELETE');
            $actionForm.submit();
        }
    });
});
</script>
@endpush
@endsection
