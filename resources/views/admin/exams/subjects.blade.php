@extends('layouts.admin')

@section('title', 'Exam Subjects | School Admin')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Breadcrumbs -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.exams.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Exams</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Subjects</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $exam->title }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Session: <span class="font-semibold text-slate-700">{{ $exam->academicYear?->name }}</span> |
                Status: <span class="font-semibold text-slate-700 uppercase">{{ $exam->status }}</span> |
                Grading Scale: <span class="font-semibold text-slate-700">{{ $exam->gradingScale?->name ?: 'Standard' }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.exams.index') }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition">
                <span>Back to Exams</span>
            </a>
            <a href="{{ route('admin.exams.marks.entry', ['exam_id' => $exam->id]) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-indigo-700 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Enter Marks</span>
            </a>
        </div>
    </div>

    <!-- Layout: Add Form on Left, List on Right -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Add Subject Form Column -->
        <div class="lg:col-span-5 rounded-xl border border-slate-200 bg-white p-5 shadow-2xs space-y-4">
            <div class="border-b border-slate-100 pb-2">
                <h2 class="text-sm font-bold text-slate-900">Add Subject to Exam</h2>
                <p class="text-[11px] text-slate-500">Set pass and full marks with component breakdown.</p>
            </div>

            <form method="POST" action="{{ route('admin.exams.subjects.store', $exam) }}" class="space-y-4" id="subject-config-form">
                @csrf

                <!-- Target Class -->
                <div>
                    <label for="academic_class_id" class="block text-xs font-semibold text-slate-700 mb-1">Class <span class="text-rose-500">*</span></label>
                    <select name="academic_class_id" id="academic_class_id" required class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                        <option value="">Select Class</option>
                        @foreach($academicClasses as $class)
                            <option value="{{ $class->id }}" {{ old('academic_class_id', $selectedClassId) == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject_id" class="block text-xs font-semibold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                    <select name="subject_id" id="subject_id" required class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->id }}" {{ old('subject_id') == $subj->id ? 'selected' : '' }}>
                                {{ $subj->name }} (Code: {{ $subj->code }}) [{{ ucfirst($subj->type) }}]
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Marks Settings -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="full_marks" class="block text-xs font-semibold text-slate-700 mb-1">Full Marks <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.5" name="full_marks" id="full_marks" value="{{ old('full_marks', 100) }}" required oninput="recalculateComponentSum()" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    </div>
                    <div>
                        <label for="pass_marks" class="block text-xs font-semibold text-slate-700 mb-1">Pass Marks <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.5" name="pass_marks" id="pass_marks" value="{{ old('pass_marks', 33) }}" required class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    </div>
                </div>

                <!-- Dynamic Components Section -->
                <div class="space-y-2 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-bold text-slate-800">Subject Components</span>
                            <span class="block text-[10px] text-slate-500">e.g. Theory, MCQ, Practical</span>
                        </div>
                        <button type="button" onclick="addComponentRow()" class="text-xs font-semibold text-violet-600 hover:text-violet-800 cursor-pointer">+ Add</button>
                    </div>

                    <div id="components-container" class="space-y-2">
                        <!-- Initial Default Components (Written 70 + MCQ 30) -->
                        <div class="component-row grid grid-cols-12 gap-2 items-center bg-slate-50 p-2 rounded-lg border border-slate-200">
                            <div class="col-span-5">
                                <input type="text" name="components[0][name]" value="Written" placeholder="Component Name" required class="w-full rounded border border-slate-200 px-2 py-1 text-xs text-slate-800 bg-white">
                            </div>
                            <div class="col-span-3">
                                <input type="number" step="0.5" name="components[0][max_marks]" value="70" placeholder="Max" required oninput="recalculateComponentSum()" class="comp-max-input w-full rounded border border-slate-200 px-2 py-1 text-xs font-mono text-slate-800 bg-white text-center">
                            </div>
                            <div class="col-span-3">
                                <input type="number" step="0.5" name="components[0][pass_marks]" value="23" placeholder="Pass" class="w-full rounded border border-slate-200 px-2 py-1 text-xs font-mono text-slate-800 bg-white text-center">
                            </div>
                            <div class="col-span-1 text-center">
                                <button type="button" onclick="removeComponentRow(this)" class="text-slate-400 hover:text-rose-600">×</button>
                            </div>
                        </div>

                        <div class="component-row grid grid-cols-12 gap-2 items-center bg-slate-50 p-2 rounded-lg border border-slate-200">
                            <div class="col-span-5">
                                <input type="text" name="components[1][name]" value="MCQ" placeholder="Component Name" required class="w-full rounded border border-slate-200 px-2 py-1 text-xs text-slate-800 bg-white">
                            </div>
                            <div class="col-span-3">
                                <input type="number" step="0.5" name="components[1][max_marks]" value="30" placeholder="Max" required oninput="recalculateComponentSum()" class="comp-max-input w-full rounded border border-slate-200 px-2 py-1 text-xs font-mono text-slate-800 bg-white text-center">
                            </div>
                            <div class="col-span-3">
                                <input type="number" step="0.5" name="components[1][pass_marks]" value="10" placeholder="Pass" class="w-full rounded border border-slate-200 px-2 py-1 text-xs font-mono text-slate-800 bg-white text-center">
                            </div>
                            <div class="col-span-1 text-center">
                                <button type="button" onclick="removeComponentRow(this)" class="text-slate-400 hover:text-rose-600">×</button>
                            </div>
                        </div>
                    </div>

                    <!-- Live Sum Feedback -->
                    <div class="flex items-center justify-between text-[11px] p-2 rounded bg-slate-100 font-mono">
                        <span class="text-slate-600">Component Sum:</span>
                        <span id="component-sum-display" class="font-bold text-slate-900">100.00 / 100.00</span>
                    </div>
                </div>

                <!-- Schedule Parameters -->
                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100">
                    <div>
                        <label for="exam_date" class="block text-xs font-semibold text-slate-700 mb-1">Exam Date</label>
                        <input type="date" name="exam_date" id="exam_date" value="{{ old('exam_date') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-1.5 text-xs text-slate-800">
                    </div>
                    <div>
                        <label for="room_number" class="block text-xs font-semibold text-slate-700 mb-1">Room / Hall</label>
                        <input type="text" name="room_number" id="room_number" placeholder="Room 102" value="{{ old('room_number') }}" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-1.5 text-xs text-slate-800">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                        Add Subject
                    </button>
                </div>
            </form>
        </div>

        <!-- Configured Subjects List Column -->
        <div class="lg:col-span-7 space-y-4">
            <!-- Filter by Class Bar -->
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs flex items-center justify-between gap-4">
                <form method="GET" action="{{ route('admin.exams.subjects.index', $exam) }}" class="flex items-center gap-2 flex-1">
                    <label for="filter_class" class="text-xs font-semibold text-slate-700 shrink-0">Class:</label>
                    <select name="academic_class_id" id="filter_class" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-1.5 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
                        <option value="">All Classes</option>
                        @foreach($academicClasses as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <div class="text-xs text-slate-500 font-medium">
                    Showing <span class="font-bold text-slate-800">{{ $examSubjects->count() }}</span> subject(s)
                </div>
            </div>

            <!-- Subject Cards -->
            <div class="space-y-3">
                @forelse($examSubjects as $subj)
                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs hover:border-slate-300 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="font-mono text-xs font-bold text-violet-700 bg-violet-50 px-2 py-0.5 rounded border border-violet-200">
                                        {{ $subj->subject?->code }}
                                    </span>
                                    <h3 class="text-sm font-bold text-slate-900">{{ $subj->subject?->name }}</h3>
                                    <span class="text-xs text-slate-500 font-medium bg-slate-100 px-2 py-0.5 rounded">
                                        Class: {{ $subj->academicClass?->name }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 text-xs text-slate-600 font-medium mt-2">
                                    <span>Full Marks: <strong class="text-slate-900">{{ number_format($subj->full_marks, 0) }}</strong></span>
                                    <span>•</span>
                                    <span>Pass Marks: <strong class="text-slate-900">{{ number_format($subj->pass_marks, 0) }}</strong></span>
                                    @if($subj->exam_date)
                                        <span>•</span>
                                        <span>Date: {{ $subj->exam_date->format('M d, Y') }}</span>
                                    @endif
                                </div>

                                <!-- Dynamic Components Breakdown Badges -->
                                <div class="flex items-center gap-1.5 flex-wrap mt-3">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 mr-1">Components:</span>
                                    @foreach($subj->components as $comp)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-slate-50 text-slate-700 px-2 py-1 rounded border border-slate-200">
                                            <span>{{ $comp->name }}:</span>
                                            <span class="font-mono font-bold text-violet-700">{{ number_format($comp->max_marks, 0) }}</span>
                                            <span class="text-[10px] text-slate-400">(Pass: {{ number_format($comp->pass_marks, 0) }})</span>
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('admin.exams.marks.entry', ['exam_id' => $exam->id, 'academic_class_id' => $subj->academic_class_id, 'subject_id' => $subj->subject_id]) }}"
                                   class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2.5 py-1.5 rounded border border-indigo-200 hover:bg-indigo-100 transition">
                                    Marks ({{ $subj->exam_marks_count }})
                                </a>

                                @if($subj->exam_marks_count == 0)
                                    <form method="POST" action="{{ route('admin.exams.subjects.destroy', [$exam, $subj]) }}" onsubmit="return confirm('Remove this subject?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Remove Subject">
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
                    <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center">
                        <p class="text-xs text-slate-500">No subjects added for this class.</p>
                        <p class="text-[11px] text-slate-400 mt-1">Add subjects using the form on the left.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

<script>
    let compCount = 2;

    function addComponentRow() {
        const container = document.getElementById('components-container');
        const div = document.createElement('div');
        div.className = 'component-row grid grid-cols-12 gap-2 items-center bg-slate-50 p-2 rounded-lg border border-slate-200';
        div.innerHTML = `
            <div class="col-span-5">
                <input type="text" name="components[${compCount}][name]" placeholder="e.g. Practical, CA" required class="w-full rounded border border-slate-200 px-2 py-1 text-xs text-slate-800 bg-white">
            </div>
            <div class="col-span-3">
                <input type="number" step="0.5" name="components[${compCount}][max_marks]" placeholder="Max" required oninput="recalculateComponentSum()" class="comp-max-input w-full rounded border border-slate-200 px-2 py-1 text-xs font-mono text-slate-800 bg-white text-center">
            </div>
            <div class="col-span-3">
                <input type="number" step="0.5" name="components[${compCount}][pass_marks]" placeholder="Pass" class="w-full rounded border border-slate-200 px-2 py-1 text-xs font-mono text-slate-800 bg-white text-center">
            </div>
            <div class="col-span-1 text-center">
                <button type="button" onclick="removeComponentRow(this)" class="text-slate-400 hover:text-rose-600">×</button>
            </div>
        `;
        container.appendChild(div);
        compCount++;
        recalculateComponentSum();
    }

    function removeComponentRow(btn) {
        const row = btn.closest('.component-row');
        row.remove();
        recalculateComponentSum();
    }

    function recalculateComponentSum() {
        const fullMarks = parseFloat(document.getElementById('full_marks').value) || 0;
        const inputs = document.querySelectorAll('.comp-max-input');
        let sum = 0;
        inputs.forEach(input => {
            sum += parseFloat(input.value) || 0;
        });

        const display = document.getElementById('component-sum-display');
        display.textContent = `${sum.toFixed(2)} / ${fullMarks.toFixed(2)}`;

        if (Math.abs(sum - fullMarks) < 0.01) {
            display.className = 'font-bold text-emerald-600';
        } else {
            display.className = 'font-bold text-rose-600';
        }
    }

    document.addEventListener('DOMContentLoaded', recalculateComponentSum);
</script>
@endsection
