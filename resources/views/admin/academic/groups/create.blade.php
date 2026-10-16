@extends('layouts.admin')

@section('title', 'New Academic Stream | Academic Structure')

@section('admin-content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.academic.groups.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Groups & Streams</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Create</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Create Academic Stream</h1>
            <p class="text-xs text-slate-500 mt-0.5">Establish a specialized academic branch for secondary or higher secondary curricula.</p>
        </div>

        <a href="{{ route('admin.academic.groups.index') }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
            &larr; Back to Streams
        </a>
    </div>

    <!-- Form Card -->
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
        <form method="POST" action="{{ route('admin.academic.groups.store') }}" class="space-y-5">
            @csrf

            <!-- Stream / Group Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                    Stream / Group Name <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name', $group->name) }}"
                       placeholder="e.g. Science, Commerce & Business Studies, Humanities"
                       required
                       class="w-full rounded-lg border {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-200 focus:border-violet-500 focus:ring-violet-500/20' }} bg-white py-2 px-3 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2">
                @error('name')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Code & Status Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Stream Code -->
                <div>
                    <label for="code" class="block text-xs font-semibold text-slate-700 mb-1">
                        Code Identifier <span class="text-slate-400 font-normal">(Optional)</span>
                    </label>
                    <input type="text"
                           name="code"
                           id="code"
                           value="{{ old('code', $group->code) }}"
                           placeholder="e.g. SCI, COM, HUM, ARTS"
                           class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 font-mono">
                    @error('code')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">
                        Status <span class="text-rose-500">*</span>
                    </label>
                    <select name="status"
                            id="status"
                            class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                        <option value="active" {{ old('status', $group->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $group->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 mb-1">
                    Description <span class="text-slate-400 font-normal">(Optional)</span>
                </label>
                <textarea name="description"
                          id="description"
                          rows="3"
                          placeholder="Brief summary of syllabus orientation, career paths, or prerequisite prerequisites..."
                          class="w-full rounded-lg border border-slate-200 bg-white py-2 px-3 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20">{{ old('description', $group->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.academic.groups.index') }}"
                   class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition cursor-pointer">
                    Save Stream
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
