@extends('layouts.admin')

@section('title', 'Edit Class | Academic Structure')

@section('admin-content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.academic.classes.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Classes</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Edit {{ $class->name }}</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Class / Grade</h1>
            <p class="text-xs text-slate-500 mt-0.5">Modify class naming, order, and status.</p>
        </div>

        <a href="{{ route('admin.academic.classes.index') }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
            &larr; Back to Classes
        </a>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
        <form method="POST" action="{{ route('admin.academic.classes.update', $class) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Class Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                    Class / Grade Title <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name', $class->name) }}"
                       required
                       class="w-full rounded-lg border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-200 focus:border-violet-500 focus:ring-violet-500/20' }} bg-white py-2 px-3 text-xs text-slate-800 focus:outline-none focus:ring-2">
                @error('name')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Numeric Code & Order Index Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Numeric Code -->
                <div>
                    <label for="numeric_code" class="block text-xs font-semibold text-slate-700 mb-1">
                        Numeric Value / Grade Level
                    </label>
                    <input type="number"
                           name="numeric_code"
                           id="numeric_code"
                           value="{{ old('numeric_code', $class->numeric_code) }}"
                           min="0"
                           max="100"
                           class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    @error('numeric_code')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order Index -->
                <div>
                    <label for="order_index" class="block text-xs font-semibold text-slate-700 mb-1">
                        Display Order Sequence <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           name="order_index"
                           id="order_index"
                           value="{{ old('order_index', $class->order_index) }}"
                           required
                           min="0"
                           max="1000"
                           class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    @error('order_index')
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
                    <option value="active" {{ old('status', $class->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $class->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 mb-1">
                    Description / Scope
                </label>
                <textarea name="description"
                          id="description"
                          rows="3"
                          class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">{{ old('description', $class->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.academic.classes.index') }}"
                   class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition cursor-pointer">
                    Update Class
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
