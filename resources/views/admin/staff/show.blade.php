@extends('layouts.admin')

@section('title', $staff->full_name . ' | Staff Profile')

@section('admin-content')
<div class="space-y-5 max-w-6xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.staff.index', $staff->trashed() ? ['status' => 'trashed'] : []) }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Staff</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">{{ $staff->full_name }}</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Staff Profile</h1>
            <p class="text-xs text-slate-500 mt-0.5">Personnel record, teaching assignments, and account access.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.staff.index', $staff->trashed() ? ['status' => 'trashed'] : []) }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Directory</span>
            </a>

            @if($staff->trashed())
                <!-- Restore Record -->
                <form method="POST" action="{{ route('admin.staff.restore', $staff->id) }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-emerald-700 transition cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Restore Staff</span>
                    </button>
                </form>

                <!-- Force Delete Record -->
                <form method="POST" action="{{ route('admin.staff.force-delete', $staff->id) }}" onsubmit="return confirm('Permanently delete this staff member? This cannot be undone.');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-rose-700 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-rose-800 transition cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Delete Permanently</span>
                    </button>
                </form>
            @else
                <a href="{{ route('admin.staff.edit', $staff) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Profile</span>
                </a>

                <!-- Quick Status Change Form -->
                <form method="POST" action="{{ route('admin.staff.status.update', $staff) }}" class="inline-flex items-center gap-1.5">
                    @csrf
                    @method('PATCH')
                    <select name="status"
                            onchange="this.form.submit();"
                            class="rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                        <option value="active" {{ $staff->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $staff->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ $staff->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </form>

                <form method="POST" action="{{ route('admin.staff.destroy', $staff) }}" onsubmit="return confirm('Move this staff member to trash?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-2 text-xs font-semibold text-rose-700 shadow-2xs hover:bg-rose-100 transition cursor-pointer">
                        <svg class="h-4 w-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Trash</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Trashed Archive Context Banner -->
    @if ($staff->trashed())
        <div class="rounded-xl border border-rose-200 bg-rose-50/70 p-4 text-xs text-rose-800 flex items-center gap-3 shadow-2xs">
            <div class="h-8 w-8 rounded-lg bg-rose-100 flex items-center justify-center text-rose-700 shrink-0">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-rose-900">Archived Record</h4>
                <p class="text-rose-700 text-xs mt-0.5">Soft-deleted on {{ $staff->deleted_at->format('M d, Y') }}. You can restore it or delete it permanently.</p>
            </div>
        </div>
    @endif

    <!-- Profile Hero Card -->
    <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                @php
                    $initials = collect(explode(' ', $staff->full_name))->filter()->map(fn($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') ?: 'ST';
                @endphp
                @if($staff->avatar_url)
                    <img src="{{ $staff->avatar_url }}"
                         alt="{{ $staff->full_name }}"
                         class="h-20 w-20 rounded-xl object-cover border border-slate-200 bg-slate-50 shrink-0">
                @else
                    <div class="h-20 w-20 rounded-xl bg-violet-100 text-violet-700 font-bold text-2xl flex items-center justify-center border border-violet-200 shrink-0">
                        {{ $initials }}
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-xl font-bold text-slate-900">{{ $staff->full_name }}</h2>
                        <span class="font-mono text-xs font-semibold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                            {{ $staff->staff_code }}
                        </span>
                        @if($staff->is_teaching)
                            <span class="inline-flex items-center gap-1 rounded-full border border-violet-200 bg-violet-50 px-2.5 py-0.5 text-xs font-semibold text-violet-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-violet-600"></span>
                                Teacher
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-xs font-semibold text-slate-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                Staff
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-600 font-medium mt-1">
                        {{ $staff->designation ?? 'Staff Member' }} &bull; <span class="text-slate-500">{{ $staff->department ?? 'General' }}</span>
                    </p>
                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-4 flex-wrap">
                        <span>Joined: <strong class="text-slate-700 font-medium">{{ $staff->joining_date?->format('M d, Y') ?? '—' }}</strong></span>
                        <span class="capitalize">Contract: <strong class="text-slate-700 font-medium">{{ str_replace('_', ' ', $staff->contract_type ?? 'Permanent') }}</strong></span>
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:items-end justify-center border-t sm:border-t-0 sm:border-l border-slate-100 pt-4 sm:pt-0 sm:pl-6 shrink-0">
                <span class="text-xs font-medium text-slate-500">Status</span>
                <div class="mt-1">
                    @if($staff->trashed())
                        <span class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-2.5 py-0.5 text-xs font-semibold text-rose-700">
                            Archived
                        </span>
                    @elseif($staff->status === 'active')
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Active
                        </span>
                    @elseif($staff->status === 'inactive')
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            Inactive
                        </span>
                    @elseif($staff->status === 'suspended')
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-0.5 text-xs font-semibold text-rose-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                            Suspended
                        </span>
                    @endif
                </div>
                <span class="text-[11px] text-slate-400 mt-1">Record #{{ $staff->id }}</span>
            </div>
        </div>
    </div>

    <!-- Main Grid: Teaching Assignments & Details -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Left Column: Teaching Assignments & User Account (7 cols) -->
        <div class="lg:col-span-7 space-y-5">
            <!-- Teaching Assignments Card -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
                <div class="border-b border-slate-100 pb-3 mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Teaching Assignments</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Assigned class sections and subject courses.</p>
                    </div>

                    @if($staff->is_teaching)
                        <span class="rounded bg-violet-50 px-2 py-0.5 text-xs font-semibold text-violet-700 border border-violet-100">
                            Teaching Faculty
                        </span>
                    @endif
                </div>

                @if(! $staff->is_teaching)
                    <div class="p-6 text-center text-slate-400 bg-slate-50 rounded-lg border border-slate-200/80">
                        <svg class="h-8 w-8 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <p class="font-medium text-slate-700 text-xs">Non-Teaching Staff</p>
                        <p class="text-xs text-slate-500 mt-0.5">This profile is categorized as non-teaching staff. Academic teaching loads do not apply.</p>
                    </div>
                @else
                    <div class="space-y-5">
                        <!-- 1. Class Teacher Sections -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xs font-semibold text-slate-800">Class Teacher</h3>
                                <span class="text-xs text-slate-500">{{ $staff->classTeacherSections->count() }} {{ Str::plural('section', $staff->classTeacherSections->count()) }}</span>
                            </div>

                            @forelse($staff->classTeacherSections as $section)
                                <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 bg-slate-50/70 mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-lg bg-violet-100 text-violet-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ substr($section->academicClass->name ?? 'C', 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900 text-xs">{{ $section->academicClass->name ?? 'Class' }} — Section {{ $section->name }}</p>
                                            <p class="text-xs text-slate-500">Room: {{ $section->room_no ?: 'Main Hall' }} &bull; Capacity: {{ $section->capacity ?? 40 }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center gap-1 rounded border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Lead Teacher
                                    </span>
                                </div>
                            @empty
                                <div class="p-3 text-center text-slate-400 bg-slate-50 rounded-lg border border-dashed border-slate-200 text-xs">
                                    Not currently assigned as Class Teacher for any section.
                                </div>
                            @endforelse
                        </div>

                        <!-- 2. Subject Teaching Assignments -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xs font-semibold text-slate-800">Subject Classes</h3>
                                <span class="text-xs text-slate-500">{{ $staff->subjectAssignments->count() }} {{ Str::plural('subject', $staff->subjectAssignments->count()) }}</span>
                            </div>

                            <div class="divide-y divide-slate-100 border border-slate-200 rounded-lg overflow-hidden bg-white">
                                @forelse($staff->subjectAssignments as $assignment)
                                    <div class="p-3 flex items-center justify-between hover:bg-slate-50/70 transition">
                                        <div class="flex items-center gap-3">
                                            <div class="h-7 w-7 rounded bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ substr($assignment->subject->code ?? 'SUB', 0, 3) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-900 text-xs">{{ $assignment->subject->name ?? 'Subject' }} ({{ $assignment->subject->code ?? '—' }})</p>
                                                <p class="text-xs text-slate-500">
                                                    Class: <span class="font-medium text-slate-700">{{ $assignment->academicClass->name ?? 'All' }}</span>
                                                    @if($assignment->academicYear)
                                                        &bull; Session: {{ $assignment->academicYear->name }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <span class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-0.5 rounded">
                                            {{ $assignment->weekly_classes ?? 4 }} hrs/wk
                                        </span>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-slate-400 text-xs">
                                        No subject assignments linked to this teacher yet.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- User Account & Permissions Card -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
                <div class="border-b border-slate-100 pb-3 mb-5">
                    <h2 class="text-sm font-semibold text-slate-900">User Account & Role</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Portal login account and assigned role permissions.</p>
                </div>

                @if($staff->user)
                    @php
                        $user = $staff->user;
                        $role = $user->roles->first();
                        $permissions = $role ? $role->permissions : collect();
                    @endphp
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3.5 bg-slate-50 rounded-xl border border-slate-200/80">
                            <div>
                                <span class="text-xs font-medium text-slate-500 block">Login Email</span>
                                <span class="font-medium text-slate-900 text-xs">{{ $user->email }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-slate-500 block">Role</span>
                                <span class="font-semibold text-violet-700 text-xs">{{ $role ? $role->name : 'No Role Assigned' }}</span>
                            </div>
                        </div>

                        <!-- Role Permissions -->
                        <div>
                            <span class="text-xs font-semibold text-slate-800 mb-2 block">
                                Permissions ({{ $permissions->count() }})
                            </span>
                            @if($permissions->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5 max-h-48 overflow-y-auto p-1 admin-sidebar-scroll">
                                    @foreach($permissions as $perm)
                                        <span class="inline-flex items-center gap-1 rounded border border-slate-200 bg-white px-2 py-0.5 text-xs text-slate-700 shadow-2xs">
                                            <span class="h-1 w-1 rounded-full bg-violet-600"></span>
                                            {{ $perm->slug }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-slate-400">No permissions attached to this role.</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center text-slate-400 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                        <p class="font-semibold text-slate-700 text-xs">No Login Account</p>
                        <p class="text-xs text-slate-500 mt-0.5">This staff member cannot log in to the administrative portal.</p>
                        <a href="{{ route('admin.staff.edit', $staff) }}" class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition">
                            <span>Create Login Account</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Personal & Contact Details Card (5 cols) -->
        <div class="lg:col-span-5 space-y-5">
            <!-- Personal & Employment Details Card -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
                <div class="border-b border-slate-100 pb-3 mb-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Personal & Employment Details
                    </h2>
                </div>

                <dl class="divide-y divide-slate-100 text-xs">
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Gender</dt>
                        <dd class="font-medium text-slate-900">{{ $staff->gender ?? 'Not Specified' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Date of Birth</dt>
                        <dd class="font-medium text-slate-900">{{ $staff->date_of_birth?->format('M d, Y') ?? '—' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Email</dt>
                        <dd class="font-medium text-slate-900">{{ $staff->email }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Phone</dt>
                        <dd class="font-mono font-medium text-slate-900">{{ $staff->phone ?? '—' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Department</dt>
                        <dd class="font-medium text-slate-900">{{ $staff->department ?? 'General' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Designation</dt>
                        <dd class="font-medium text-slate-900">{{ $staff->designation ?? 'Staff Member' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Qualification</dt>
                        <dd class="font-medium text-slate-900">{{ $staff->qualification ?? '—' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Experience</dt>
                        <dd class="font-medium text-slate-900">{{ $staff->experience_years ? $staff->experience_years . ' Years' : '—' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Basic Salary</dt>
                        <dd class="font-medium text-slate-900">{{ $staff->basic_salary ? '$' . number_format($staff->basic_salary, 2) : '—' }}</dd>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <dt class="text-slate-500">Joining Date</dt>
                        <dd class="font-medium text-slate-900">{{ $staff->joining_date?->format('M d, Y') ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Emergency & Address Card -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
                <div class="border-b border-slate-100 pb-3 mb-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Emergency & Residence
                    </h2>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <span class="text-xs font-medium text-slate-500 block">Emergency Contact</span>
                        <p class="font-medium text-slate-900 mt-0.5">{{ $staff->emergency_contact_name ?: 'None listed' }}</p>
                        @if($staff->emergency_contact_phone)
                            <p class="text-slate-500 font-mono text-xs mt-0.5">{{ $staff->emergency_contact_phone }}</p>
                        @endif
                    </div>

                    <div>
                        <span class="text-xs font-medium text-slate-500 block">Current Address</span>
                        <p class="text-slate-700 mt-0.5 leading-relaxed">{{ $staff->current_address ?: 'No current address provided.' }}</p>
                    </div>

                    <div>
                        <span class="text-xs font-medium text-slate-500 block">Permanent Address</span>
                        <p class="text-slate-700 mt-0.5 leading-relaxed">{{ $staff->permanent_address ?: 'Same as current address.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
