@extends('layouts.admin')

@section('title', 'Edit Role: ' . $role->name . ' | Roles & Permissions')

@section('admin-content')
<div class="space-y-5 max-w-5xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.roles.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Roles & Permissions</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Edit</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Edit Role: {{ $role->name }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                @if($role->is_system)
                    <span class="rounded bg-violet-100 text-violet-700 font-semibold px-1.5 py-0.5 text-[10px] mr-1">System</span>
                    Built-in role. Permissions can be customized.
                @else
                    Custom role. Modify role details and assigned permissions.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.roles.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs transition hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Roles</span>
            </a>
        </div>
    </div>

    <!-- Role Form -->
    <form method="POST"
          action="{{ route('admin.roles.update', $role) }}"
          id="role-edit-form"
          class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Role Details -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-sm font-semibold text-slate-900">Role Details</h2>
                <p class="text-xs text-slate-500 mt-0.5">Role name and unique system identifier.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Role Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Role Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $role->name) }}"
                           required
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Slug Identifier <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="slug"
                           id="slug"
                           value="{{ old('slug', $role->slug) }}"
                           {{ $role->is_system ? 'readonly' : 'required' }}
                           class="w-full rounded-lg border border-slate-200 {{ $role->is_system ? 'bg-slate-100 text-slate-500 cursor-not-allowed' : 'bg-slate-50/50 text-slate-900 hover:bg-white' }} px-3 py-2 text-xs font-mono transition focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                    @if($role->is_system)
                        <p class="text-[11px] text-slate-400 mt-1">Built-in role identifier cannot be modified.</p>
                    @endif
                    @error('slug')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Description
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="2"
                              placeholder="Brief description of this role's responsibilities..."
                              class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">Permissions</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Select the capabilities granted to users with this role.</p>
                </div>

                <!-- Global Select All / Clear Toggle Buttons -->
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <button type="button"
                            onclick="toggleAllPermissions(true);"
                            class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer">
                        Select All
                    </button>
                    <button type="button"
                            onclick="toggleAllPermissions(false);"
                            class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                        Clear All
                    </button>
                </div>
            </div>

            <!-- Permission Modules List -->
            <div class="space-y-5">
                @php
                    $groupLabels = [
                        'students' => ['title' => 'Students', 'desc' => 'Admissions, student records, and enrollments.'],
                        'staff' => ['title' => 'Staff & Teachers', 'desc' => 'Staff directory, teacher records, and portal accounts.'],
                        'academic' => ['title' => 'Academics', 'desc' => 'Classes, sections, subjects, and assignments.'],
                        'roles' => ['title' => 'Roles & Permissions', 'desc' => 'Role creation, customization, and access control.'],
                        'settings' => ['title' => 'School Settings', 'desc' => 'School profile and general configurations.'],
                    ];
                    $rolePermissionIds = old('permissions', $role->permissions->pluck('id')->toArray());
                @endphp

                @foreach($permissionsByGroup as $group => $permissions)
                    @php
                        $groupMeta = $groupLabels[$group] ?? ['title' => ucfirst($group), 'desc' => 'Permissions for ' . $group];
                    @endphp
                    <div class="rounded-lg border border-slate-200 bg-slate-50/50 p-4" id="group-wrapper-{{ $group }}">
                        <!-- Module Header with Group Select Toggle -->
                        <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/80 mb-3">
                            <div>
                                <h3 class="text-xs font-semibold text-slate-900">{{ $groupMeta['title'] }}</h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $groupMeta['desc'] }}</p>
                            </div>

                            <button type="button"
                                    onclick="toggleGroupPermissions('{{ $group }}');"
                                    class="text-xs font-semibold text-violet-700 hover:text-violet-900 cursor-pointer transition">
                                Toggle All
                            </button>
                        </div>

                        <!-- Checkbox Grid for this group -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                            @foreach($permissions as $perm)
                                <label class="flex items-start gap-2.5 p-2.5 rounded-lg border border-slate-200 bg-white hover:border-violet-300 hover:bg-violet-50/20 transition cursor-pointer">
                                    <input type="checkbox"
                                           name="permissions[]"
                                           value="{{ $perm->id }}"
                                           data-group="{{ $group }}"
                                           {{ in_array($perm->id, $rolePermissionIds) ? 'checked' : '' }}
                                           class="perm-checkbox mt-0.5 h-4 w-4 rounded text-violet-600 focus:ring-violet-500 border-slate-300">
                                    <div class="min-w-0">
                                        <span class="block text-xs font-medium text-slate-800 leading-tight">{{ $perm->name }}</span>
                                        <span class="block font-mono text-[10px] text-slate-400 mt-0.5">{{ $perm->slug }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Form Submission Bar -->
        <div class="flex items-center justify-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <a href="{{ route('admin.roles.index') }}"
               class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                Cancel
            </a>
            <button type="submit"
                    class="rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Save Changes</span>
            </button>
        </div>
    </form>
</div>

<script>
    function toggleAllPermissions(check) {
        document.querySelectorAll('.perm-checkbox').forEach(function(cb) {
            cb.checked = check;
        });
    }

    function toggleGroupPermissions(group) {
        var groupCheckboxes = document.querySelectorAll('.perm-checkbox[data-group="' + group + '"]');
        var anyUnchecked = false;
        groupCheckboxes.forEach(function(cb) {
            if (!cb.checked) anyUnchecked = true;
        });

        groupCheckboxes.forEach(function(cb) {
            cb.checked = anyUnchecked;
        });
    }
</script>
@endsection
