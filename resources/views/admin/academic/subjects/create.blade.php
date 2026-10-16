@extends('layouts.admin')

@section('title', 'New Subject | Academic Structure')

@section('admin-content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.academic.subjects.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Subjects</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Create</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Create Course Subject</h1>
            <p class="text-xs text-slate-500 mt-0.5">Register a course subject into the school catalog.</p>
        </div>

        <a href="{{ route('admin.academic.subjects.index') }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
            &larr; Back to Catalog
        </a>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
        <form method="POST" action="{{ route('admin.academic.subjects.store') }}" class="space-y-5">
            @csrf

            <!-- Subject Name & Code Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Subject Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                        Subject Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $subject->name) }}"
                           placeholder="e.g. Higher Mathematics"
                           required
                           class="w-full rounded-lg border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-200 focus:border-violet-500 focus:ring-violet-500/20' }} bg-white py-2 px-3 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject Code -->
                <div>
                    <label for="code" class="block text-xs font-semibold text-slate-700 mb-1">
                        Course Code <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="code"
                           id="code"
                           value="{{ old('code', $subject->code) }}"
                           placeholder="e.g. MTH-201, PHY-101"
                           required
                           class="w-full rounded-lg border {{ $errors->has('code') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-200 focus:border-violet-500 focus:ring-violet-500/20' }} bg-white py-2 px-3 text-xs text-slate-800 placeholder-slate-400 uppercase focus:outline-none focus:ring-2 font-mono">
                    @error('code')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-[11px] text-slate-400">Unique alphanumeric code within this school.</p>
                </div>
            </div>

            <!-- Type & Credit Hours Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Type -->
                <div>
                    <label for="type" class="block text-xs font-semibold text-slate-700 mb-1">
                        Curriculum Type <span class="text-rose-500">*</span>
                    </label>
                    <select name="type"
                            id="type"
                            class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                        <option value="theory" {{ old('type', $subject->type) === 'theory' ? 'selected' : '' }}>Theory Only</option>
                        <option value="practical" {{ old('type', $subject->type) === 'practical' ? 'selected' : '' }}>Practical / Lab Only</option>
                        <option value="both" {{ old('type', $subject->type) === 'both' ? 'selected' : '' }}>Theory + Practical Lab</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Credit Hours -->
                <div>
                    <label for="credit_hours" class="block text-xs font-semibold text-slate-700 mb-1">
                        Credit Hours / Weight
                    </label>
                    <input type="number"
                           step="0.5"
                           min="0"
                           max="20"
                           name="credit_hours"
                           id="credit_hours"
                           value="{{ old('credit_hours', $subject->credit_hours) }}"
                           placeholder="e.g. 3.00"
                           class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    @error('credit_hours')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">
                    Status <span class="text-rose-500">*</span>
                </label>
                <select name="status"
                        id="status"
                        class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    <option value="active" {{ old('status', $subject->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $subject->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 mb-1">
                    Syllabus / Course Description
                </label>
                <textarea name="description"
                          id="description"
                          rows="3"
                          placeholder="Optional syllabus summary, prerequisites, or learning objectives."
                          class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">{{ old('description', $subject->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.academic.subjects.index') }}"
                   class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition cursor-pointer">
                    Save Subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
