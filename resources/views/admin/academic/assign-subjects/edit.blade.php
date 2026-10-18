@extends('layouts.admin')

@section('title', 'Edit Subject Assignment | Academic Structure')

@section('admin-content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.academic.assign-subjects.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Assign Subjects</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Edit</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Curriculum Assignment</h1>
            <p class="text-xs text-slate-500 mt-0.5">Adjust curriculum category or pass/full mark benchmarks for this subject.</p>
        </div>

        <a href="{{ route('admin.academic.assign-subjects.index') }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
            &larr; Back to Assignments
        </a>
    </div>

    <!-- Read-only Overview Card -->
    <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div>
                <span class="text-slate-400 block text-[11px]">Academic Class</span>
                <span class="font-semibold text-slate-800">{{ $assignment->academicClass?->name ?? '—' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Assigned Subject</span>
                <span class="font-semibold text-slate-800">{{ $assignment->subject?->name ?? '—' }} ({{ $assignment->subject?->code ?? '—' }})</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Session / Year</span>
                <span class="font-semibold text-slate-800">{{ $assignment->academicYear?->name ?? 'Permanent Curriculum' }}</span>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
        <form method="POST" action="{{ route('admin.academic.assign-subjects.update', $assignment) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Curriculum Type -->
            <div>
                <label for="type" class="block text-xs font-semibold text-slate-700 mb-1">
                    Curriculum Subject Type <span class="text-rose-500">*</span>
                </label>
                <select name="type"
                        id="type"
                        required
                        class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    <option value="compulsory" {{ old('type', $assignment->type) === 'compulsory' ? 'selected' : '' }}>Compulsory / Mandatory</option>
                    <option value="optional" {{ old('type', $assignment->type) === 'optional' ? 'selected' : '' }}>Optional (Student can opt in)</option>
                    <option value="elective" {{ old('type', $assignment->type) === 'elective' ? 'selected' : '' }}>Elective / Stream Specific</option>
                </select>
                @error('type')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-[11px] text-slate-400">Class default. Can be overridden per student during admission or batch assign.</p>
            </div>

            <!-- Assigned Subject Teacher (Optional) -->
            <div>
                <label for="teacher_id" class="block text-xs font-semibold text-slate-700 mb-1">
                    Subject Teacher <span class="text-slate-400 font-normal">(Optional)</span>
                </label>
                <select name="teacher_id"
                        id="teacher_id"
                        class="select2 w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    <option value="">None (Unassigned)</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ (string)old('teacher_id', $assignment->teacher_id) === (string)$teacher->id ? 'selected' : '' }}>
                            {{ $teacher->first_name }} {{ $teacher->last_name }} ({{ $teacher->staff_code }})
                        </option>
                    @endforeach
                </select>
                @error('teacher_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Marks Configuration Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Full Marks -->
                <div>
                    <label for="full_marks" class="block text-xs font-semibold text-slate-700 mb-1">
                        Full Marks <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           name="full_marks"
                           id="full_marks"
                           value="{{ old('full_marks', $assignment->full_marks) }}"
                           min="1"
                           max="500"
                           required
                           class="w-full rounded-lg border {{ $errors->has('full_marks') ? 'border-rose-400' : 'border-slate-200' }} bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    @error('full_marks')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pass Marks -->
                <div>
                    <label for="pass_marks" class="block text-xs font-semibold text-slate-700 mb-1">
                        Pass Marks Threshold <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           name="pass_marks"
                           id="pass_marks"
                           value="{{ old('pass_marks', $assignment->pass_marks) }}"
                           min="0"
                           max="500"
                           required
                           class="w-full rounded-lg border {{ $errors->has('pass_marks') ? 'border-rose-400' : 'border-slate-200' }} bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    @error('pass_marks')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.academic.assign-subjects.index') }}"
                   class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition cursor-pointer">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
