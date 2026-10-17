@extends('layouts.admin')

@section('title', 'Report Cards | School Admin')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.exams.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Exams</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Report Cards</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Report Cards</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Preview and download student report cards.
            </p>
        </div>

        @if($selectedExam)
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.exams.report-cards.bulk-pdf', ['exam' => $selectedExam->id, 'academic_class_id' => $selectedClassId, 'section_id' => $selectedSectionId]) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Download All (PDF)</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Filters & Search Bar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.exams.report-cards.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4 items-end">
            <!-- Exam -->
            <div>
                <label for="exam_id" class="block text-xs font-semibold text-slate-700 mb-1">Exam <span class="text-rose-500">*</span></label>
                <select name="exam_id" id="exam_id" required onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    @foreach($exams as $ex)
                        <option value="{{ $ex->id }}" {{ $selectedExamId == $ex->id ? 'selected' : '' }}>
                            {{ $ex->title }} ({{ ucfirst($ex->status) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Class -->
            <div>
                <label for="academic_class_id" class="block text-xs font-semibold text-slate-700 mb-1">Class</label>
                <select name="academic_class_id" id="academic_class_id" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">All Classes</option>
                    @foreach($academicClasses as $class)
                        <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Section -->
            <div>
                <label for="section_id" class="block text-xs font-semibold text-slate-700 mb-1">Section</label>
                <select name="section_id" id="section_id" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">All Sections</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec->id }}" {{ $selectedSectionId == $sec->id ? 'selected' : '' }}>
                            Section {{ $sec->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div>
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by name or admission no..."
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 pl-8 pr-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </form>
    </div>

    <!-- Student Cards / Table -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-2xs overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider text-[10px] border-b border-slate-200">
                <tr>
                    <th class="px-3 py-3 w-16 text-center">Roll</th>
                    <th class="px-3 py-3 w-28">Admission No</th>
                    <th class="px-3 py-3 min-w-[160px]">Student</th>
                    <th class="px-3 py-3 w-28">Class & Section</th>
                    <th class="px-3 py-3 w-28 text-center">Total Marks</th>
                    <th class="px-3 py-3 w-20 text-center font-bold">GPA</th>
                    <th class="px-3 py-3 w-16 text-center">Grade</th>
                    <th class="px-3 py-3 w-20 text-center">Class Rank</th>
                    <th class="px-3 py-3 w-36 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($results as $res)
                    @php
                        $student = $res->student;
                        $enrollment = $student?->currentEnrollment;
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <!-- Roll Number -->
                        <td class="px-3 py-2 text-center font-mono font-bold text-slate-900">
                            {{ $enrollment?->roll_number ?: '—' }}
                        </td>

                        <!-- Admission SID -->
                        <td class="px-3 py-2 font-mono text-[11px] text-slate-600">
                            {{ $student?->admission_number }}
                        </td>

                        <!-- Student -->
                        <td class="px-3 py-2">
                            <div class="font-bold text-slate-900">{{ $student?->full_name }}</div>
                            <div class="text-[10px] text-slate-500">Guardian: {{ $student?->guardians?->first()?->full_name ?: '—' }}</div>
                        </td>

                        <!-- Class & Section -->
                        <td class="px-3 py-2 text-slate-700">
                            <div>{{ $res->academicClass?->name }}</div>
                            <div class="text-[11px] text-slate-500">Sec: {{ $res->section?->name }}</div>
                        </td>

                        <!-- Marks -->
                        <td class="px-3 py-2 text-center font-mono font-semibold text-slate-900">
                            {{ number_format($res->total_marks_obtained, 1) }} / {{ number_format($res->total_full_marks, 0) }}
                        </td>

                        <!-- GPA -->
                        <td class="px-3 py-2 text-center font-mono font-bold text-violet-700 text-sm">
                            {{ number_format($res->gpa, 2) }}
                        </td>

                        <!-- Grade -->
                        <td class="px-3 py-2 text-center">
                            <span class="inline-flex items-center justify-center min-w-[24px] px-1.5 py-0.5 rounded text-[11px] font-bold {{ $res->result_status === 'passed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                {{ $res->overall_grade }}
                            </span>
                        </td>

                        <!-- Rank in Class -->
                        <td class="px-3 py-2 text-center font-mono font-bold text-slate-800">
                            {{ $res->rank_in_class && $res->result_status === 'passed' ? '#' . $res->rank_in_class : '—' }}
                        </td>

                        <!-- Action Buttons -->
                        <td class="px-3 py-2 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.exams.report-cards.show', [$res->exam_id, $res->student_id]) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition">
                                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Preview</span>
                                </a>

                                <a href="{{ route('admin.exams.report-cards.pdf', [$res->exam_id, $res->student_id]) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-violet-600 text-xs font-semibold text-white hover:bg-violet-700 transition">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span>PDF</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-slate-500">
                            No report cards found for this exam. Calculate results first to generate report cards.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($results->hasPages())
        <div class="mt-4">
            {{ $results->links() }}
        </div>
    @endif

</div>
@endsection
