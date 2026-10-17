@extends('layouts.admin')

@section('title', 'Exam Results & Merit List | School Admin')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Action Controls -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.exams.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Exams</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Results</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Exam Results & Merit List</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                View student marks, merit rankings, and manage publication.
            </p>
        </div>

        @if($selectedExam)
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Re-calculate Button -->
                <form id="form-calculate-ranks" method="POST" action="{{ route('admin.exams.results.calculate', $selectedExam) }}">
                    @csrf
                    <input type="hidden" name="academic_class_id" value="{{ $selectedClassId }}">
                    <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">
                    <button type="button" id="btn-calculate-ranks" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 hover:border-slate-300 transition cursor-pointer">
                        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Calculate Results</span>
                    </button>
                </form>

                <!-- Publish / Unpublish Button -->
                @if(! $selectedExam->is_published)
                    <form id="form-publish-results" method="POST" action="{{ route('admin.exams.results.publish', $selectedExam) }}">
                        @csrf
                        <button type="button" id="btn-publish-results" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-2xs hover:bg-emerald-700 transition cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Publish Results</span>
                        </button>
                    </form>
                @else
                    <form id="form-unpublish-results" method="POST" action="{{ route('admin.exams.results.unpublish', $selectedExam) }}">
                        @csrf
                        <button type="button" id="btn-unpublish-results" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 transition cursor-pointer" title="Click to unpublish results">
                            <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Published (Click to Unpublish)</span>
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>

    <!-- Filters & Search Bar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.exams.results.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-5 items-end">
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

            <!-- Result Status -->
            <div>
                <label for="result_status" class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                <select name="result_status" id="result_status" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">All Results</option>
                    <option value="passed" {{ $selectedStatus === 'passed' ? 'selected' : '' }}>Passed</option>
                    <option value="failed" {{ $selectedStatus === 'failed' ? 'selected' : '' }}>Failed</option>
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

    <!-- Tabulation Table -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-2xs overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider text-[10px] border-b border-slate-200">
                <tr>
                    <th class="px-3 py-3 w-16 text-center">Class Rank</th>
                    <th class="px-3 py-3 w-16 text-center">Sec Rank</th>
                    <th class="px-3 py-3 w-16 text-center">Roll</th>
                    <th class="px-3 py-3 min-w-[160px]">Student</th>
                    <th class="px-3 py-3 w-28">Class & Section</th>
                    <th class="px-3 py-3 w-28 text-center">Total Marks</th>
                    <th class="px-3 py-3 w-20 text-center">Percentage</th>
                    <th class="px-3 py-3 w-20 text-center font-bold">GPA</th>
                    <th class="px-3 py-3 w-16 text-center">Grade</th>
                    <th class="px-3 py-3 w-24 text-center">Status</th>
                    <th class="px-3 py-3 w-28 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($results as $res)
                    @php
                        $student = $res->student;
                        $enrollment = $student?->currentEnrollment;
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        <!-- Rank in Class -->
                        <td class="px-3 py-2 text-center">
                            @if($res->rank_in_class && $res->result_status === 'passed')
                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-full text-xs font-bold {{ $res->rank_in_class <= 3 ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $res->rank_in_class }}
                                </span>
                            @else
                                <span class="text-slate-400 font-mono text-[11px]">—</span>
                            @endif
                        </td>

                        <!-- Rank in Section -->
                        <td class="px-3 py-2 text-center font-mono font-semibold text-slate-700">
                            {{ $res->rank_in_section && $res->result_status === 'passed' ? $res->rank_in_section : '—' }}
                        </td>

                        <!-- Roll Number -->
                        <td class="px-3 py-2 text-center font-mono font-bold text-slate-900">
                            {{ $enrollment?->roll_number ?: '—' }}
                        </td>

                        <!-- Student -->
                        <td class="px-3 py-2">
                            <div class="font-bold text-slate-900">{{ $student?->full_name }}</div>
                            <div class="font-mono text-[10px] text-slate-500">SID: {{ $student?->admission_number }}</div>
                        </td>

                        <!-- Class & Section -->
                        <td class="px-3 py-2 text-slate-700">
                            <div>{{ $res->academicClass?->name }}</div>
                            <div class="text-[11px] text-slate-500">Sec: {{ $res->section?->name }}</div>
                        </td>

                        <!-- Total Marks -->
                        <td class="px-3 py-2 text-center font-mono font-semibold text-slate-900">
                            {{ number_format($res->total_marks_obtained, 1) }} / {{ number_format($res->total_full_marks, 0) }}
                        </td>

                        <!-- Percentage -->
                        <td class="px-3 py-2 text-center font-mono text-slate-700">
                            {{ number_format($res->percentage, 1) }}%
                        </td>

                        <!-- GPA -->
                        <td class="px-3 py-2 text-center font-mono font-bold text-violet-700 text-sm">
                            {{ number_format($res->gpa, 2) }}
                        </td>

                        <!-- Overall Grade -->
                        <td class="px-3 py-2 text-center">
                            <span class="inline-flex items-center justify-center min-w-[24px] px-1.5 py-0.5 rounded text-[11px] font-bold {{ $res->result_status === 'passed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                {{ $res->overall_grade }}
                            </span>
                        </td>

                        <!-- Status Badge -->
                        <td class="px-3 py-2 text-center">
                            @if($res->result_status === 'passed')
                                <span class="inline-flex items-center text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">PASSED</span>
                            @else
                                <span class="inline-flex items-center text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200" title="{{ $res->failed_subjects_count }} subject(s) failed">FAILED</span>
                            @endif
                        </td>

                        <!-- Action Links -->
                        <td class="px-3 py-2 text-right">
                            <a href="{{ route('admin.exams.report-cards.show', [$res->exam_id, $res->student_id]) }}"
                               target="_blank"
                               class="inline-flex items-center gap-1 text-xs font-semibold text-violet-600 hover:text-violet-900">
                                <span>Marksheet</span>
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="p-8 text-center text-slate-500">
                            No calculated results found. Click "Calculate Results" above to generate grades and merit ranks.
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnCalculate = document.getElementById('btn-calculate-ranks');
    const formCalculate = document.getElementById('form-calculate-ranks');

    if (btnCalculate && formCalculate) {
        btnCalculate.addEventListener('click', function () {
            if (window.Swal) {
                Swal.fire({
                    title: 'Calculate Results?',
                    text: 'This will calculate student GPAs, grades, and merit ranks for this exam.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#7c3aed',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, calculate',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formCalculate.submit();
                    }
                });
            } else if (confirm('Calculate GPAs and merit ranks for this exam?')) {
                formCalculate.submit();
            }
        });
    }

    const btnPublish = document.getElementById('btn-publish-results');
    const formPublish = document.getElementById('form-publish-results');

    if (btnPublish && formPublish) {
        btnPublish.addEventListener('click', function () {
            if (window.Swal) {
                Swal.fire({
                    title: 'Publish Results?',
                    text: 'Once published, marks entry will be locked and results will become final.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, publish',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formPublish.submit();
                    }
                });
            } else if (confirm('Publish exam results? Marks entry will be locked.')) {
                formPublish.submit();
            }
        });
    }

    const btnUnpublish = document.getElementById('btn-unpublish-results');
    const formUnpublish = document.getElementById('form-unpublish-results');

    if (btnUnpublish && formUnpublish) {
        btnUnpublish.addEventListener('click', function () {
            if (window.Swal) {
                Swal.fire({
                    title: 'Unpublish Results?',
                    text: 'This will reopen marks entry for teachers and staff.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, unpublish',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formUnpublish.submit();
                    }
                });
            } else if (confirm('Unpublish results and reopen marks entry?')) {
                formUnpublish.submit();
            }
        });
    }
});
</script>
@endpush
