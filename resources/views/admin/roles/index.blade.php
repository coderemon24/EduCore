@extends('layouts.admin')

@section('title', 'Roles & Permissions | Administration')

@section('admin-content')
<div class="space-y-5">

    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Roles & Permissions</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Roles & Permissions</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage user roles and portal access permissions.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.staff.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Staff Directory</span>
            </a>

            <a href="{{ route('admin.roles.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Create Role</span>
            </a>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-2xs overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-900">
                Configured Roles
            </h2>
            <span class="text-xs text-slate-500">{{ $roles->count() }} roles</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-700 font-semibold text-xs">
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4">Description</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4">Assigned Users</th>
                        <th class="py-3 px-4">Permissions</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($roles as $role)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Role Name & Slug -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-900 text-xs flex items-center gap-2">
                                    <span>{{ $role->name }}</span>
                                    @if($role->is_system)
                                        <span class="rounded bg-violet-100 text-violet-700 px-1.5 py-0.5 text-[10px] font-semibold">System</span>
                                    @endif
                                </div>
                                <div class="font-mono text-[11px] text-slate-400 mt-0.5">{{ $role->slug }}</div>
                            </td>

                            <!-- Description -->
                            <td class="py-3.5 px-4 text-slate-600 max-w-xs">
                                {{ $role->description ?: 'No description provided.' }}
                            </td>

                            <!-- Type -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($role->is_system)
                                    <span class="inline-flex items-center gap-1 rounded-full border border-violet-200 bg-violet-50 px-2.5 py-0.5 text-[10px] font-semibold text-violet-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-violet-600"></span> Built-in
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-[10px] font-semibold text-slate-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Custom
                                    </span>
                                @endif
                            </td>

                            <!-- Assigned Users -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 font-semibold text-xs text-slate-800">
                                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $role->users_count }} {{ Str::plural('User', $role->users_count) }}
                                </span>
                            </td>

                            <!-- Permissions -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center rounded-md bg-violet-50 px-2 py-0.5 font-mono text-[11px] font-semibold text-violet-700 border border-violet-200">
                                        {{ $role->permissions_count }} {{ Str::plural('perm', $role->permissions_count) }}
                                    </span>
                                    <span class="text-[11px] text-slate-500 truncate max-w-xs hidden sm:inline">
                                        {{ $role->permissions->pluck('name')->take(3)->implode(', ') }}
                                        @if($role->permissions->count() > 3)
                                            +{{ $role->permissions->count() - 3 }} more
                                        @endif
                                    </span>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Edit Role -->
                                    <a href="{{ route('admin.roles.edit', $role) }}"
                                       class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-violet-50 hover:text-violet-700 hover:border-violet-200 transition cursor-pointer">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span>Edit</span>
                                    </a>

                                    @if(! $role->is_system)
                                        <!-- Delete Custom Role -->
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Delete custom role \'{{ $role->name }}\'? Assigned users will lose this role.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition cursor-pointer">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">
                                No roles defined for this school.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
