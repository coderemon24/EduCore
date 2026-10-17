@extends('layouts.admin')

@section('title', 'Exams | School Admin')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Exams</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Exams</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Manage school exams, subject setup, marks entry, and results.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.exams.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Exam</span>
            </a>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.exams.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4 items-end">
            <div>
                <label for="filter_search" class="block text-xs font-semibold text-slate-700 mb-1">Search</label>
                <div class="relative">
                    <input type="text"
                           name="search"
                           id="filter_search"
                           value="{{ request('search') }}"
                           placeholder="Exam title..."
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 pl-8 pr-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div>
                <label for="filter_year" class="block text-xs font-semibold text-slate-700 mb-1">Academic Year</label>
                <select name="academic_year_id" id="filter_year" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">All Academic Years</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_current ? '(Active)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="filter_status" class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                <select name="status" id="filter_status" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="marks_entry" {{ request('status') === 'marks_entry' ? 'selected' : '' }}>Marks Entry Open</option>
                    <option value="results_calculated" {{ request('status') === 'results_calculated' ? 'selected' : '' }}>Results Calculated</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published (Locked)</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-slate-800 transition cursor-pointer">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'academic_year_id', 'status']))
                    <a href="{{ route('admin.exams.index') }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Exam Cards / List -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($exams as $exam)
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs hover:border-slate-300 transition">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-base font-bold text-slate-900">{{ $exam->title }}</h2>

                            <!-- Status Badge -->
                            @php
                                $statusClasses = [
                                    'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'scheduled' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'ongoing' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'marks_entry' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'results_calculated' => 'bg-teal-50 text-teal-700 border-teal-200',
                                    'published' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'archived' => 'bg-slate-100 text-slate-600 border-slate-200',
                                ];
                                $badgeClass = $statusClasses[$exam->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeClass }}">
                                {{ ucfirst(str_replace('_', ' ', $exam->status)) }}
                            </span>

                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                {{ ucfirst(str_replace('_', ' ', $exam->exam_type)) }}
                            </span>

                            <span class="text-xs text-slate-500 font-medium">
                                {{ $exam->academicYear?->name }}
                            </span>
                        </div>

                        <div class="flex items-center gap-4 text-xs text-slate-500 flex-wrap">
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $exam->start_date?->format('M d, Y') }} — {{ $exam->end_date?->format('M d, Y') }}
                            </span>

                            <span class="flex items-center gap-1">
                                <span class="font-bold text-slate-700">{{ $exam->exam_subjects_count }}</span> Configured Subjects
                            </span>

                            <span class="flex items-center gap-1">
                                <span class="font-bold text-slate-700">{{ $exam->exam_marks_count }}</span> Marks Entries
                            </span>

                            <span class="flex items-center gap-1">
                                <span class="font-bold text-slate-700">{{ $exam->exam_results_count }}</span> Student Results
                            </span>

                            @if($exam->gradingScale)
                                <span class="text-[11px] text-violet-700 bg-violet-50 px-2 py-0.5 rounded border border-violet-200">
                                    Scale: {{ $exam->gradingScale->name }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Controls -->
                    <div class="flex items-center gap-2 flex-wrap shrink-0">
                        <!-- Subjects & Components Setup -->
                        <a href="{{ route('admin.exams.subjects.index', $exam) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span>Subjects</span>
                        </a>

                        <!-- Marks Entry Grid -->
                        <a href="{{ route('admin.exams.marks.entry', ['exam_id' => $exam->id]) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-indigo-200 bg-indigo-50/70 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Marks Entry</span>
                        </a>

                        <!-- Results & Tabulation -->
                        <a href="{{ route('admin.exams.results.index', ['exam_id' => $exam->id]) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-teal-200 bg-teal-50/70 text-xs font-semibold text-teal-700 hover:bg-teal-100 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Results</span>
                        </a>

                        <!-- Report Cards -->
                        <a href="{{ route('admin.exams.report-cards.index', ['exam_id' => $exam->id]) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-violet-200 bg-violet-50 text-xs font-semibold text-violet-700 hover:bg-violet-100 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Report Cards</span>
                        </a>

                        <!-- Edit & Delete -->
                        <a href="{{ route('admin.exams.edit', $exam) }}" class="p-1.5 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Edit">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>

                        @if(! $exam->is_published)
                            <form method="POST" action="{{ route('admin.exams.destroy', $exam) }}" onsubmit="return confirm('Are you sure you want to delete this exam?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Delete Exam">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-violet-50 text-violet-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="mt-3 text-sm font-bold text-slate-900">No Exams Scheduled</h3>
                <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                    Create an exam to configure subjects, enter marks, and generate report cards.
                </p>
                <div class="mt-4">
                    <a href="{{ route('admin.exams.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition">
                        <span>Add Exam</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($exams->hasPages())
        <div class="mt-4">
            {{ $exams->links() }}
        </div>
    @endif

</div>
@endsection
