@extends('layouts.admin')

@section('title', 'Edit Section | Academic Structure')

@section('admin-content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.academic.sections.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Sections</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Edit {{ $section->name }}</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Class Section</h1>
            <p class="text-xs text-slate-500 mt-0.5">Modify section details, room, and capacity limit.</p>
        </div>

        <a href="{{ route('admin.academic.sections.index') }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
            &larr; Back to Sections
        </a>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
        <form method="POST" action="{{ route('admin.academic.sections.update', $section) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Target Class Dropdown -->
            <div>
                <label for="academic_class_id" class="block text-xs font-semibold text-slate-700 mb-1">
                    Academic Class / Grade <span class="text-rose-500">*</span>
                </label>
                <select name="academic_class_id"
                        id="academic_class_id"
                        required
                        class="w-full rounded-lg border {{ $errors->has('academic_class_id') ? 'border-rose-400' : 'border-slate-200' }} bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    @foreach ($classes as $c)
                        <option value="{{ $c->id }}" {{ (string)old('academic_class_id', $section->academic_class_id) === (string)$c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
                @error('academic_class_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Section Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                    Section Name / Identifier <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name', $section->name) }}"
                       required
                       class="w-full rounded-lg border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-200 focus:border-violet-500 focus:ring-violet-500/20' }} bg-white py-2 px-3 text-xs text-slate-800 focus:outline-none focus:ring-2">
                @error('name')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Capacity & Room Number Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Capacity -->
                <div>
                    <label for="capacity" class="block text-xs font-semibold text-slate-700 mb-1">
                        Student Capacity
                    </label>
                    <input type="number"
                           name="capacity"
                           id="capacity"
                           value="{{ old('capacity', $section->capacity) }}"
                           min="1"
                           max="500"
                           class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    @error('capacity')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Room Number -->
                <div>
                    <label for="room_number" class="block text-xs font-semibold text-slate-700 mb-1">
                        Physical Room / Hall
                    </label>
                    <input type="text"
                           name="room_number"
                           id="room_number"
                           value="{{ old('room_number', $section->room_number) }}"
                           class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    @error('room_number')
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
                    <option value="active" {{ old('status', $section->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $section->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.academic.sections.index') }}"
                   class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition cursor-pointer">
                    Update Section
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
