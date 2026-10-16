@extends('layouts.admin')

@section('title', 'Edit Academic Year | Academic Structure')

@section('admin-content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.academic.years.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Academic Years</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Edit {{ $year->name }}</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Academic Year</h1>
            <p class="text-xs text-slate-500 mt-0.5">Modify session parameters and active status.</p>
        </div>

        <a href="{{ route('admin.academic.years.index') }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
            &larr; Back to Sessions
        </a>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
        <form method="POST" action="{{ route('admin.academic.years.update', $year) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Year Title / Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                    Academic Session Title <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name', $year->name) }}"
                       required
                       class="w-full rounded-lg border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-200 focus:border-violet-500 focus:ring-violet-500/20' }} bg-white py-2 px-3 text-xs text-slate-800 focus:outline-none focus:ring-2">
                @error('name')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Range Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-xs font-semibold text-slate-700 mb-1">
                        Start Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date"
                           name="start_date"
                           id="start_date"
                           value="{{ old('start_date', $year->start_date?->format('Y-m-d')) }}"
                           required
                           class="w-full rounded-lg border {{ $errors->has('start_date') ? 'border-rose-400' : 'border-slate-200 focus:border-violet-500 focus:ring-violet-500/20' }} bg-white py-2 px-3 text-xs text-slate-800 focus:outline-none focus:ring-2">
                    @error('start_date')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-xs font-semibold text-slate-700 mb-1">
                        End Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date"
                           name="end_date"
                           id="end_date"
                           value="{{ old('end_date', $year->end_date?->format('Y-m-d')) }}"
                           required
                           class="w-full rounded-lg border {{ $errors->has('end_date') ? 'border-rose-400' : 'border-slate-200 focus:border-violet-500 focus:ring-violet-500/20' }} bg-white py-2 px-3 text-xs text-slate-800 focus:outline-none focus:ring-2">
                    @error('end_date')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status & Current Toggle Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">
                        Lifecycle Status <span class="text-rose-500">*</span>
                    </label>
                    <select name="status"
                            id="status"
                            class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                        <option value="active" {{ old('status', $year->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $year->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Current Session Checkbox -->
                <div class="flex items-center pt-5">
                    <label class="relative flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox"
                               name="is_current"
                               value="1"
                               {{ old('is_current', $year->is_current) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                        <span class="text-xs font-medium text-slate-700">Set as Active Current Academic Year</span>
                    </label>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-xs font-semibold text-slate-700 mb-1">
                    Administrative Notes
                </label>
                <textarea name="notes"
                          id="notes"
                          rows="3"
                          class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">{{ old('notes', $year->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.academic.years.index') }}"
                   class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition cursor-pointer">
                    Update Academic Year
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
