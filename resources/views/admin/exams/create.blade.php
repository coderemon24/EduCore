@extends('layouts.admin')

@section('title', 'Add Exam | School Admin')

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
                <span class="font-semibold text-slate-700">Add Exam</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Add Exam</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Create a new exam and set dates and grading scale.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.exams.index') }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition">
                <span>Cancel</span>
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs max-w-4xl">
        <form method="POST" action="{{ route('admin.exams.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Academic Year -->
                <div>
                    <label for="academic_year_id" class="block text-xs font-semibold text-slate-700 mb-1">Academic Year <span class="text-rose-500">*</span></label>
                    <select name="academic_year_id" id="academic_year_id" required class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                        <option value="">Select Academic Year</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ (old('academic_year_id') == $year->id || $year->is_current) ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_current ? '(Current Active)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grading Scale -->
                <div>
                    <label for="grading_scale_id" class="block text-xs font-semibold text-slate-700 mb-1">Grading Scale <span class="text-rose-500">*</span></label>
                    <select name="grading_scale_id" id="grading_scale_id" required class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                        <option value="">Select Grading Scale</option>
                        @foreach($gradingScales as $scale)
                            <option value="{{ $scale->id }}" {{ (old('grading_scale_id') == $scale->id || $scale->is_default) ? 'selected' : '' }}>
                                {{ $scale->name }} {{ $scale->is_default ? '(Default)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('grading_scale_id')
                        <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Exam Title -->
                <div class="sm:col-span-2">
                    <label for="title" class="block text-xs font-semibold text-slate-700 mb-1">Exam Title <span class="text-rose-500">*</span></label>
                    <input type="text"
                           name="title"
                           id="title"
                           required
                           value="{{ old('title', 'Half Yearly Exam 2026') }}"
                           placeholder="e.g. Half Yearly Exam 2026"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    @error('title')
                        <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Exam Type -->
                <div>
                    <label for="exam_type" class="block text-xs font-semibold text-slate-700 mb-1">Exam Type <span class="text-rose-500">*</span></label>
                    <select name="exam_type" id="exam_type" required class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                        <option value="terminal" {{ old('exam_type') === 'terminal' ? 'selected' : '' }}>Terminal Exam</option>
                        <option value="half_yearly" {{ old('exam_type', 'half_yearly') === 'half_yearly' ? 'selected' : '' }}>Half Yearly Exam</option>
                        <option value="final" {{ old('exam_type') === 'final' ? 'selected' : '' }}>Final Exam</option>
                        <option value="class_test" {{ old('exam_type') === 'class_test' ? 'selected' : '' }}>Class Test</option>
                        <option value="model_test" {{ old('exam_type') === 'model_test' ? 'selected' : '' }}>Model Test</option>
                    </select>
                    @error('exam_type')
                        <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="scheduled" {{ old('status', 'scheduled') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="marks_entry" {{ old('status') === 'marks_entry' ? 'selected' : '' }}>Marks Entry Open</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-xs font-semibold text-slate-700 mb-1">Start Date <span class="text-rose-500">*</span></label>
                    <input type="date"
                           name="start_date"
                           id="start_date"
                           required
                           value="{{ old('start_date', date('Y-m-d')) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    @error('start_date')
                        <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-xs font-semibold text-slate-700 mb-1">End Date <span class="text-rose-500">*</span></label>
                    <input type="date"
                           name="end_date"
                           id="end_date"
                           required
                           value="{{ old('end_date', date('Y-m-d', strtotime('+14 days'))) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    @error('end_date')
                        <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block text-xs font-semibold text-slate-700 mb-1">Description (Optional)</label>
                    <textarea name="description"
                              id="description"
                              rows="3"
                              placeholder="Notes or instructions for teachers..."
                              class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <a href="{{ route('admin.exams.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">Cancel</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                    <span>Create Exam</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
