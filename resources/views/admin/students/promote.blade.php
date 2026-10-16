@extends('layouts.admin')

@section('title', 'Promote / Transfer ' . $student->full_name . ' | Student Management')

@section('admin-content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.students.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Students</a>
                <span>/</span>
                <a href="{{ route('admin.students.show', $student) }}" class="font-medium text-slate-600 hover:text-violet-600 transition">{{ $student->full_name }}</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Promote / Transfer</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Student Promotion & Transfer</h1>
            <p class="text-xs text-slate-500 mt-0.5">Transition student to the next academic session or transfer to a different section while preserving history.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.students.show', $student) }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Profile</span>
            </a>
        </div>
    </div>

    <!-- Student Summary Hero -->
    @php
        $curr = $student->currentEnrollment;
    @endphp
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs">
        <div class="flex items-center gap-4">
            <img src="{{ $student->photo_url }}"
                 alt="{{ $student->full_name }}"
                 class="h-14 w-14 rounded-lg object-cover border border-slate-200 bg-slate-50 shrink-0">
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-base font-bold text-slate-900">{{ $student->full_name }}</h2>
                    <span class="font-mono text-xs font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                        {{ $student->admission_number }}
                    </span>
                </div>
                <div class="mt-1 flex items-center gap-2 text-xs text-slate-500 flex-wrap">
                    <span>Current Session: <strong class="text-slate-800">{{ $curr?->academicYear?->name ?? 'Unassigned' }}</strong></span>
                    <span>•</span>
                    <span>Class: <strong class="text-slate-800">{{ $curr?->academicClass?->name ?? 'Unassigned' }}</strong></span>
                    @if ($curr?->section)
                        <span>(Section {{ $curr->section->name }})</span>
                    @endif
                    @if ($curr?->roll_number)
                        <span>• Roll: <strong class="text-slate-800">#{{ $curr->roll_number }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Transition Form -->
    <form method="POST"
          action="{{ route('admin.students.promote.store', $student) }}"
          id="student-promote-form"
          class="space-y-6">
        @csrf

        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs space-y-6">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Target Academic Placement</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Select destination academic year, class, section, and update the student's roll number.</p>
            </div>

            <!-- Transition Type Selection -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">Transition Type <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="relative flex items-center gap-3 p-3 rounded-lg border border-slate-200 cursor-pointer transition hover:bg-slate-50 has-checked:border-violet-600 has-checked:bg-violet-50/50">
                        <input type="radio" name="action_type" value="promotion" checked class="text-violet-600 focus:ring-violet-500">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Academic Promotion</span>
                            <span class="text-[11px] text-slate-500">Advance to next grade/session with new enrollment</span>
                        </div>
                    </label>
                    <label class="relative flex items-center gap-3 p-3 rounded-lg border border-slate-200 cursor-pointer transition hover:bg-slate-50 has-checked:border-violet-600 has-checked:bg-violet-50/50">
                        <input type="radio" name="action_type" value="transfer" class="text-violet-600 focus:ring-violet-500">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Class / Section Transfer</span>
                            <span class="text-[11px] text-slate-500">Lateral transition within current or upcoming session</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Target Academic Session -->
                <div>
                    <label for="academic_year_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Target Academic Session <span class="text-rose-500">*</span>
                    </label>
                    <select id="academic_year_id"
                            name="academic_year_id"
                            required
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id', $curr?->academic_year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_current ? '• Current Active' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target Academic Class -->
                <div>
                    <label for="academic_class_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Target Class / Grade <span class="text-rose-500">*</span>
                    </label>
                    <select id="academic_class_id"
                            name="academic_class_id"
                            data-academic-class-select
                            required
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        <option value="">-- Select Target Class --</option>
                        @foreach ($academicClasses as $class)
                            <option value="{{ $class->id }}" {{ old('academic_class_id', $curr?->academic_class_id) == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_class_id')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target Section -->
                <div>
                    <label for="section_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Target Section
                    </label>
                    <select id="section_id"
                            name="section_id"
                            data-dependent-sections
                            data-selected="{{ old('section_id', $curr?->section_id) }}"
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        <option value="">-- Select Section --</option>
                    </select>
                    @error('section_id')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Roll Number -->
                <div>
                    <label for="roll_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        New Roll Number
                    </label>
                    <input type="text"
                           id="roll_number"
                           name="roll_number"
                           value="{{ old('roll_number', $curr?->roll_number) }}"
                           placeholder="e.g. 201"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <!-- Audit Remarks -->
                <div class="sm:col-span-2">
                    <label for="remarks" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Promotion Remarks / Notes
                    </label>
                    <textarea id="remarks"
                              name="remarks"
                              rows="2"
                              placeholder="e.g. Promoted to Grade 10 based on Annual Examination 2025 results."
                              class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-3 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">{{ old('remarks') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit & Navigation Bar -->
        <div class="flex items-center justify-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <a href="{{ route('admin.students.show', $student) }}"
               class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                Cancel
            </a>
            <button type="button"
                    onclick="window.confirmPromotionSubmit()"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Confirm Promotion / Transfer</span>
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
    window.confirmPromotionSubmit = function() {
        if (window.Swal) {
            Swal.fire({
                title: 'Confirm Student Promotion?',
                text: 'This will archive the current enrollment record and create a new active placement.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, proceed'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('student-promote-form').submit();
                }
            });
        } else if (confirm('Are you sure you want to promote/transfer this student?')) {
            document.getElementById('student-promote-form').submit();
        }
    };
</script>
@endpush
@endsection
