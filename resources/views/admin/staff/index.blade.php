@extends('layouts.admin')

@php
    $isTeachingMode = ($typeFilter === 'teaching');
    $pageTitle = $isTeachingMode ? 'Teachers' : 'Staff Directory';
@endphp

@section('title', $pageTitle . ' | Administration')

@section('admin-content')
<div class="space-y-5">

    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="text-slate-500">Staff</span>
                <span>/</span>
                <span class="font-semibold text-slate-700">{{ $isTeachingMode ? 'Teachers' : 'Directory' }}</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $pageTitle }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ $isTeachingMode ? 'Manage teachers, subject assignments, and class sections.' : 'Manage school staff, faculty records, and portal accounts.' }}
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if($isTeachingMode)
                <a href="{{ route('admin.staff.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>All Staff</span>
                </a>
            @else
                <a href="{{ route('admin.teachers.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Teachers Only</span>
                </a>
            @endif

            <a href="{{ route('admin.staff.create', $isTeachingMode ? ['type' => 'teaching'] : []) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add {{ $isTeachingMode ? 'Teacher' : 'Staff' }}</span>
            </a>
        </div>
    </div>

    <!-- Status Tabs Toolbar -->
    <div class="rounded-xl border border-slate-200 bg-white p-3.5 shadow-2xs">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <!-- Status Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-medium pb-1 sm:pb-0 admin-sidebar-scroll">
                @php
                    $tabs = [
                        'all' => ['label' => 'All', 'count' => $statusCounts['all'] ?? 0],
                        'active' => ['label' => 'Active', 'count' => $statusCounts['active'] ?? 0],
                        'inactive' => ['label' => 'Inactive', 'count' => $statusCounts['inactive'] ?? 0],
                        'suspended' => ['label' => 'Suspended', 'count' => $statusCounts['suspended'] ?? 0],
                        'trashed' => ['label' => 'Archived', 'count' => $statusCounts['trashed'] ?? 0],
                    ];
                @endphp
                @foreach ($tabs as $key => $tab)
                    @php
                        $isSelected = ($statusFilter ?? 'all') === $key;
                        $isTrashTab = $key === 'trashed';
                    @endphp
                    <a href="{{ route('admin.staff.index', array_merge(request()->query(), ['status' => $key, 'page' => 1])) }}"
                       class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 transition-colors whitespace-nowrap cursor-pointer {{ $isSelected ? ($isTrashTab ? 'bg-rose-600 text-white font-semibold' : 'bg-violet-600 text-white font-semibold') : ($isTrashTab ? 'text-rose-700 bg-rose-50/70 hover:bg-rose-100' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900') }}">
                        @if ($isTrashTab)
                            <svg class="h-3.5 w-3.5 {{ $isSelected ? 'text-rose-100' : 'text-rose-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        @endif
                        <span>{{ $tab['label'] }}</span>
                        <span class="rounded px-1.5 py-0.5 text-[10px] font-semibold {{ $isSelected ? 'bg-black/20 text-white' : ($isTrashTab ? 'bg-rose-200 text-rose-800' : 'bg-slate-200 text-slate-700') }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Quick Type Filter Buttons -->
            <div class="flex items-center gap-1.5 border-t sm:border-t-0 sm:border-l border-slate-200 pt-2 sm:pt-0 sm:pl-3 shrink-0">
                <span class="text-xs font-medium text-slate-500 mr-1 hidden lg:inline">Type:</span>
                <a href="{{ route('admin.staff.index', array_merge(request()->query(), ['type' => 'all', 'page' => 1])) }}"
                   class="rounded-md px-2.5 py-1 text-xs font-semibold {{ ($typeFilter ?? 'all') === 'all' ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    All ({{ ($statusCounts['teaching'] ?? 0) + ($statusCounts['non_teaching'] ?? 0) }})
                </a>
                <a href="{{ route('admin.staff.index', array_merge(request()->query(), ['type' => 'teaching', 'page' => 1])) }}"
                   class="rounded-md px-2.5 py-1 text-xs font-semibold {{ ($typeFilter ?? 'all') === 'teaching' ? 'bg-violet-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    Teachers ({{ $statusCounts['teaching'] ?? 0 }})
                </a>
                <a href="{{ route('admin.staff.index', array_merge(request()->query(), ['type' => 'non_teaching', 'page' => 1])) }}"
                   class="rounded-md px-2.5 py-1 text-xs font-semibold {{ ($typeFilter ?? 'all') === 'non_teaching' ? 'bg-slate-700 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    Staff ({{ $statusCounts['non_teaching'] ?? 0 }})
                </a>
            </div>
        </div>
    </div>

    <!-- Trashed Archive Context Banner -->
    @if (($statusFilter ?? 'all') === 'trashed')
        <div class="rounded-xl border border-rose-200 bg-rose-50/60 p-4 text-xs text-rose-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-rose-100 flex items-center justify-center text-rose-700 shrink-0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-rose-900">Archived Records</h4>
                    <p class="text-rose-700 text-xs mt-0.5">These staff records are soft-deleted. You can restore them or permanently delete them.</p>
                </div>
            </div>
            <a href="{{ route('admin.staff.index', ['status' => 'all']) }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-rose-300 bg-white px-3 py-1.5 text-xs font-semibold text-rose-800 hover:bg-rose-50 shadow-2xs shrink-0 self-start sm:self-auto cursor-pointer">
                <span>Back to Active Directory</span>
            </a>
        </div>
    @endif

    <!-- Search & Faceted Filter Bar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.staff.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-12">
            <input type="hidden" name="status" value="{{ $statusFilter ?? 'all' }}">

            <!-- Search Keyword -->
            <div class="lg:col-span-5 relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text"
                       name="search"
                       value="{{ $search ?? '' }}"
                       placeholder="Search by name, staff code, email, designation..."
                       class="w-full rounded-lg border border-slate-200 bg-slate-50/50 py-2 pl-9 pr-3 text-xs text-slate-900 placeholder-slate-400 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-violet-500">
            </div>

            <!-- Department Filter -->
            <div class="lg:col-span-3">
                <select name="department"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50/50 py-2 px-3 text-xs text-slate-900 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-violet-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ ($departmentFilter ?? '') === $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Employment Type Filter -->
            <div class="lg:col-span-2">
                <select name="type"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50/50 py-2 px-3 text-xs text-slate-900 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-violet-500">
                    <option value="all" {{ ($typeFilter ?? 'all') === 'all' ? 'selected' : '' }}>All Roles</option>
                    <option value="teaching" {{ ($typeFilter ?? 'all') === 'teaching' ? 'selected' : '' }}>Teaching (Faculty)</option>
                    <option value="non_teaching" {{ ($typeFilter ?? 'all') === 'non_teaching' ? 'selected' : '' }}>Non-Teaching</option>
                </select>
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 rounded-lg bg-violet-600 py-2 px-3 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer text-center">
                    Filter
                </button>
                @if(!empty($search) || !empty($departmentFilter) || ($typeFilter ?? 'all') !== 'all')
                    <a href="{{ route('admin.staff.index', ['status' => $statusFilter ?? 'all']) }}"
                       class="rounded-lg border border-slate-200 bg-slate-100 py-2 px-3 text-xs font-semibold text-slate-600 hover:bg-slate-200 transition text-center"
                       title="Clear Filters">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Staff Directory Data Table -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-700 font-semibold text-xs">
                        <th class="py-3 px-4">Staff Member</th>
                        <th class="py-3 px-4">Staff ID</th>
                        <th class="py-3 px-4">Designation & Dept</th>
                        <th class="py-3 px-4">Employment</th>
                        <th class="py-3 px-4">Role & Account</th>
                        <th class="py-3 px-4">Contact</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($staffMembers as $staff)
                        @php
                            $initials = collect(explode(' ', $staff->full_name))->filter()->map(fn($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') ?: 'ST';
                            $user = $staff->user;
                            $roleName = $user && $user->roles->isNotEmpty() ? $user->roles->first()->name : ($staff->is_teaching ? 'Teacher' : 'Staff');
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Staff Member Avatar + Name -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    @if($staff->avatar_url)
                                        <img src="{{ $staff->avatar_url }}" alt="{{ $staff->full_name }}" class="h-9 w-9 rounded-full object-cover shrink-0 border border-slate-200">
                                    @else
                                        <div class="h-9 w-9 rounded-md bg-slate-100 text-slate-700 font-semibold text-xs flex items-center justify-center shrink-0 border border-slate-200">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.staff.show', $staff) }}" class="font-semibold text-slate-900 hover:text-violet-600 transition block truncate">
                                            {{ $staff->full_name }}
                                        </a>
                                        <span class="text-[11px] text-slate-400 capitalize block">{{ $staff->gender ?? 'Not specified' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Staff Code -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-mono text-xs font-semibold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                    {{ $staff->staff_code }}
                                </span>
                            </td>

                            <!-- Designation & Dept -->
                            <td class="py-3 px-4">
                                <div class="font-medium text-slate-900">{{ $staff->designation ?? 'Staff Member' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $staff->department ?? 'General' }}</div>
                            </td>

                            <!-- Employment Type -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($staff->is_teaching)
                                    <span class="inline-flex items-center rounded border border-slate-200 bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-700">
                                        Teaching Faculty
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded border border-slate-200 bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-600">
                                        Non-Teaching
                                    </span>
                                @endif
                                <div class="text-[10px] text-slate-400 mt-0.5 capitalize">{{ str_replace('_', ' ', $staff->employment_type ?? 'full_time') }}</div>
                            </td>

                            <!-- User Account & Role -->
                            <td class="py-3 px-4">
                                @if($user)
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-flex items-center gap-1 rounded border border-emerald-200 bg-emerald-50 px-1.5 py-0.5 text-[10px] font-bold text-emerald-700">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Linked User
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-violet-700 font-semibold mt-0.5">{{ $roleName }}</div>
                                @else
                                    <span class="inline-flex items-center rounded border border-amber-200 bg-amber-50 px-1.5 py-0.5 text-[10px] font-medium text-amber-700">
                                        No Login Account
                                    </span>
                                @endif
                            </td>

                            <!-- Contact -->
                            <td class="py-3 px-4">
                                <div class="text-slate-800 truncate max-w-[180px]">{{ $staff->email }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">{{ $staff->phone ?? '—' }}</div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($staff->trashed())
                                    <span class="inline-flex items-center rounded border border-rose-200 bg-rose-50 px-2 py-0.5 text-[11px] font-medium text-rose-700">
                                        Archived
                                    </span>
                                @elseif($staff->status === 'active')
                                    <span class="inline-flex items-center rounded border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700">
                                        Active
                                    </span>
                                @elseif($staff->status === 'inactive')
                                    <span class="inline-flex items-center rounded border border-amber-200 bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-700">
                                        Inactive
                                    </span>
                                @elseif($staff->status === 'suspended')
                                    <span class="inline-flex items-center rounded border border-rose-200 bg-rose-50 px-2 py-0.5 text-[11px] font-medium text-rose-700">
                                        Suspended
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    @if($staff->trashed())
                                        <!-- Restore Form -->
                                        <form method="POST" action="{{ route('admin.staff.restore', $staff->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    title="Restore Staff Record"
                                                    class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition cursor-pointer">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- Force Delete Form -->
                                        <form method="POST" action="{{ route('admin.staff.force-delete', $staff->id) }}" onsubmit="return confirm('Permanently delete this staff member? This cannot be undone.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    title="Permanently Purge"
                                                    class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition cursor-pointer">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <!-- View Profile -->
                                        <a href="{{ route('admin.staff.show', $staff) }}"
                                           title="View Profile"
                                           class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition cursor-pointer">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.staff.edit', $staff) }}"
                                           title="Edit Staff"
                                           class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-violet-50 hover:text-violet-700 hover:border-violet-200 transition cursor-pointer">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>

                                        <!-- Quick Status Toggle Dropdown / Form -->
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <button type="button"
                                                    onclick="this.nextElementSibling.classList.toggle('hidden'); event.stopPropagation();"
                                                    title="Change Status"
                                                    class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                </svg>
                                            </button>
                                            <div class="hidden absolute right-0 mt-1 w-36 rounded-lg border border-slate-200 bg-white p-1 shadow-lg z-50 text-xs"
                                                 onclick="event.stopPropagation();">
                                                <div class="px-2 py-1 text-[11px] font-semibold text-slate-500">Change Status</div>
                                                <form method="POST" action="{{ route('admin.staff.status.update', $staff) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" name="status" value="active"
                                                            class="flex w-full items-center gap-1.5 rounded px-2 py-1 text-left text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 {{ $staff->status === 'active' ? 'font-bold text-emerald-700' : '' }}">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active
                                                    </button>
                                                    <button type="submit" name="status" value="inactive"
                                                            class="flex w-full items-center gap-1.5 rounded px-2 py-1 text-left text-slate-700 hover:bg-amber-50 hover:text-amber-700 {{ $staff->status === 'inactive' ? 'font-bold text-amber-700' : '' }}">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Inactive
                                                    </button>
                                                    <button type="submit" name="status" value="suspended"
                                                            class="flex w-full items-center gap-1.5 rounded px-2 py-1 text-left text-slate-700 hover:bg-rose-50 hover:text-rose-700 {{ $staff->status === 'suspended' ? 'font-bold text-rose-700' : '' }}">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Suspended
                                                    </button>
                                                </form>
                                                <div class="my-1 border-t border-slate-100"></div>
                                                <form method="POST" action="{{ route('admin.staff.destroy', $staff) }}" onsubmit="return confirm('Move this staff member to trash?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="flex w-full items-center gap-1.5 rounded px-2 py-1 text-left text-rose-600 hover:bg-rose-50">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Move to Trash
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <p class="font-semibold text-slate-700 text-sm">No staff or faculty members found</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Try adjusting your filters or register a new staff member.</p>
                                    <a href="{{ route('admin.staff.create', $isTeachingMode ? ['type' => 'teaching'] : []) }}" class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <span>Add New Record</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staffMembers->hasPages())
            <div class="border-t border-slate-200 px-4 py-3 bg-slate-50/50">
                {{ $staffMembers->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    // Close open dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        document.querySelectorAll('[x-data] .absolute:not(.hidden)').forEach(function(el) {
            el.classList.add('hidden');
        });
    });
</script>
@endsection
