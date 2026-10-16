@extends('layouts.admin')

@section('title', $school->name . ' | Institution Details')

@section('admin-content')
<div class="space-y-6 max-w-6xl mx-auto">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.schools.index') }}" class="text-slate-600 hover:text-violet-600 transition">Schools</a>
                <span>/</span>
                <span class="font-semibold text-slate-700 truncate max-w-xs">{{ $school->name }}</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $school->name }}</h1>
                @if($school->trashed())
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-rose-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                        <span>Archived / Soft-Deleted</span>
                    </span>
                @else
                    @php
                        $statusValue = $school->status?->value ?? 'active';
                        $statusClasses = match($statusValue) {
                            'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'suspended' => 'bg-rose-50 text-rose-700 border-rose-200',
                            'inactive' => 'bg-slate-100 text-slate-600 border-slate-200',
                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $statusClasses }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusValue === 'active' ? 'bg-emerald-500' : ($statusValue === 'pending' ? 'bg-amber-500' : ($statusValue === 'suspended' ? 'bg-rose-500' : 'bg-slate-400')) }}"></span>
                        <span>{{ $school->status?->label() ?? ucfirst($statusValue) }}</span>
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-1 font-mono">Code: {{ $school->code ?: 'N/A' }} &bull; Slug: {{ $school->slug }} &bull; Tenant ID: #{{ $school->id }}</p>
        </div>

        <!-- Header Action Toolbar -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.schools.index') }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition hover:bg-slate-50 hover:border-slate-300">
                <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Directory</span>
            </a>

            @if($school->trashed())
                <!-- Restore Action -->
                <button type="button"
                        data-id="{{ $school->id }}"
                        data-name="{{ $school->name }}"
                        class="btn-restore-school inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 shadow-2xs transition hover:bg-emerald-100 hover:border-emerald-300 cursor-pointer">
                    <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Restore Institution</span>
                </button>

                <!-- Permanent Delete Action -->
                <button type="button"
                        data-id="{{ $school->id }}"
                        data-name="{{ $school->name }}"
                        class="btn-force-delete-school inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 shadow-2xs transition hover:bg-rose-100 hover:border-rose-300 cursor-pointer">
                    <svg class="h-3.5 w-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Purge Permanently</span>
                </button>
            @else
                <!-- Edit Action -->
                <a href="{{ route('admin.schools.edit', $school) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition hover:bg-slate-50 hover:border-slate-300">
                    <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Profile</span>
                </a>

                <!-- Change Status Action -->
                <button type="button"
                        data-id="{{ $school->id }}"
                        data-name="{{ $school->name }}"
                        data-status="{{ $school->status?->value ?? 'active' }}"
                        class="btn-change-status inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white shadow-xs transition hover:bg-violet-700 cursor-pointer">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span>Change Status</span>
                </button>

                <!-- Archive / Soft-Delete Action -->
                <button type="button"
                        data-id="{{ $school->id }}"
                        data-name="{{ $school->name }}"
                        class="btn-archive-school inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 shadow-2xs transition hover:bg-rose-100 hover:border-rose-300 cursor-pointer">
                    <svg class="h-3.5 w-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Archive Tenant</span>
                </button>
            @endif
        </div>
    </div>

    <!-- 4 High-Level Parameter Metric Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- 1. Operational Status -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-xs">
            <p class="text-xs font-semibold text-slate-500">Tenant Lifecycle</p>
            <h3 class="mt-1 text-lg font-bold text-slate-900 tracking-tight">{{ $school->status?->label() ?? 'Active' }}</h3>
            <p class="mt-1 text-[11px] text-slate-400">Institutional system status</p>
        </div>

        <!-- 2. Code -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-xs">
            <p class="text-xs font-semibold text-slate-500">Campus Code</p>
            <h3 class="mt-1 text-lg font-bold font-mono text-slate-900 tracking-tight">{{ $school->code ?: 'N/A' }}</h3>
            <p class="mt-1 text-[11px] text-slate-400">Academic ledger identifier</p>
        </div>

        <!-- 3. Established -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-xs">
            <p class="text-xs font-semibold text-slate-500">Established Year</p>
            <h3 class="mt-1 text-lg font-bold text-slate-900 tracking-tight">{{ $school->established_year ?: 'Not Specified' }}</h3>
            <p class="mt-1 text-[11px] text-slate-400">Foundational institutional date</p>
        </div>

        <!-- 4. Currency & Timezone -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-xs">
            <p class="text-xs font-semibold text-slate-500">Currency & Timezone</p>
            <h3 class="mt-1 text-lg font-bold text-slate-900 tracking-tight">{{ $school->currency ?: 'USD' }}</h3>
            <p class="mt-1 text-[11px] text-slate-400 truncate">{{ $school->timezone ?: 'UTC' }}</p>
        </div>
    </div>

    <!-- Detailed Metadata Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Column Left (2 cols): Core Profile & Location -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Card: Official Contacts & Web -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
                <div class="border-b border-slate-100 pb-3 mb-4">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Official Contacts & Portal</h2>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <dt class="font-semibold text-slate-500">Primary Contact Email</dt>
                        <dd class="mt-1 text-slate-900 font-medium font-mono">{{ $school->email ?: '—' }}</dd>
                    </div>

                    <div>
                        <dt class="font-semibold text-slate-500">Campus Telephone</dt>
                        <dd class="mt-1 text-slate-900 font-medium font-mono">{{ $school->phone ?: '—' }}</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="font-semibold text-slate-500">Official Portal Website</dt>
                        <dd class="mt-1">
                            @if($school->website)
                                <a href="{{ $school->website }}" target="_blank" rel="noopener noreferrer" class="text-violet-600 hover:text-violet-700 underline font-medium inline-flex items-center gap-1">
                                    <span>{{ $school->website }}</span>
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Card: Physical Campus Location -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
                <div class="border-b border-slate-100 pb-3 mb-4">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Physical Campus Location</h2>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="sm:col-span-2">
                        <dt class="font-semibold text-slate-500">Campus Street Address</dt>
                        <dd class="mt-1 text-slate-900 font-medium">{{ $school->address ?: '—' }}</dd>
                    </div>

                    <div>
                        <dt class="font-semibold text-slate-500">City & State</dt>
                        <dd class="mt-1 text-slate-900 font-medium">
                            {{ $school->city ?: '—' }}{{ $school->state ? ', ' . $school->state : '' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="font-semibold text-slate-500">Country & Postal Code</dt>
                        <dd class="mt-1 text-slate-900 font-medium">
                            {{ $school->country ?: 'United States' }} &bull; {{ $school->postal_code ?: '—' }}
                        </dd>
                    </div>
                </dl>
            </div>

        </div>

        <!-- Column Right (1 col): Branding & Telemetry -->
        <div class="space-y-6">

            <!-- Card: Branding Crest -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs text-center">
                <div class="h-28 w-28 mx-auto rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center p-3 mb-3 relative">
                    <img src="{{ $school->logo_url }}"
                         alt="{{ $school->name }}"
                         class="h-full w-full object-contain">
                </div>
                <h3 class="text-sm font-bold text-slate-800">{{ $school->name }}</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Official Institutional Crest</p>

                <!-- Dedicated Favicon Preview in Card -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium">Browser Favicon:</span>
                    <div class="flex items-center gap-1.5">
                        <img src="{{ $school->favicon_url }}" alt="Favicon" class="h-5 w-5 object-contain rounded border border-slate-200 p-0.5 bg-slate-50">
                        <span class="text-[11px] font-mono text-slate-600">{{ $school->favicon ? 'Custom Uploaded' : 'Default Crest' }}</span>
                    </div>
                </div>
            </div>

            <!-- Card: System Record Telemetry -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs">
                <div class="border-b border-slate-100 pb-3 mb-4">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">System Metadata</h2>
                </div>

                <dl class="space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500 font-medium">Database ID</dt>
                        <dd class="font-mono font-semibold text-slate-800">#{{ $school->id }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500 font-medium">Registered Date</dt>
                        <dd class="text-slate-800">{{ $school->created_at ? $school->created_at->format('M d, Y') : '—' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-slate-500 font-medium">Last Profile Update</dt>
                        <dd class="text-slate-800">{{ $school->updated_at ? $school->updated_at->diffForHumans() : '—' }}</dd>
                    </div>
                    @if($school->trashed())
                        <div class="flex items-center justify-between pt-2 border-t border-rose-100 text-rose-700 font-medium">
                            <dt>Archived On</dt>
                            <dd>{{ $school->deleted_at->format('M d, Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

        </div>

    </div>

</div>

<!-- Hidden Dynamic Action Form for jQuery / SweetAlert Submissions -->
<form id="action-dispatch-form" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="_method" id="action-dispatch-method" value="POST">
    <input type="hidden" name="status" id="action-dispatch-status" value="">
</form>

@push('scripts')
<script>
    // jQuery Event Delegation & SweetAlert2 Operations
    $(function() {
        var $form = $('#action-dispatch-form');
        var $method = $('#action-dispatch-method');
        var $status = $('#action-dispatch-status');

        // 1. Change Status Handler
        $(document).on('click', '.btn-change-status', function(e) {
            e.preventDefault();
            var schoolId = $(this).data('id');
            var schoolName = $(this).data('name');
            var currentStatus = $(this).data('status');

            if (!window.Swal) return;

            Swal.fire({
                title: 'Update Operational Status',
                html: `
                    <div class="text-left text-xs text-slate-600 mb-3">
                        <p class="mb-2">Select new lifecycle status for <b>${schoolName}</b>:</p>
                        <select id="swal-select-status" class="w-full rounded-lg border border-slate-300 p-2 text-xs focus:ring-2 focus:ring-violet-500 focus:outline-none">
                            <option value="active" ${currentStatus === 'active' ? 'selected' : ''}>Active (Operational)</option>
                            <option value="pending" ${currentStatus === 'pending' ? 'selected' : ''}>Pending (Under Verification)</option>
                            <option value="suspended" ${currentStatus === 'suspended' ? 'selected' : ''}>Suspended (Access Halted)</option>
                            <option value="inactive" ${currentStatus === 'inactive' ? 'selected' : ''}>Inactive (Decommissioned)</option>
                        </select>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Update Status',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#7c3aed',
                cancelButtonColor: '#64748b',
                focusConfirm: false,
                preConfirm: function() {
                    return $('#swal-select-status').val();
                }
            }).then(function(result) {
                if (result.isConfirmed && result.value) {
                    $form.attr('action', '/admin/schools/' + schoolId + '/status');
                    $method.val('PATCH');
                    $status.val(result.value);
                    $form.submit();
                }
            });
        });

        // 2. Archive / Soft-Delete Handler
        $(document).on('click', '.btn-archive-school', function(e) {
            e.preventDefault();
            var schoolId = $(this).data('id');
            var schoolName = $(this).data('name');

            if (!window.Swal) return;

            Swal.fire({
                title: 'Archive Institution?',
                html: `Are you sure you want to archive <b>${schoolName}</b>?<br><span class="text-slate-500 text-xs mt-1 block">This is a safe soft-delete. The school can be restored at any time from the "Archived" tab.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Archive Tenant',
                cancelButtonText: 'Keep Active',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                focusCancel: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    $form.attr('action', '/admin/schools/' + schoolId);
                    $method.val('DELETE');
                    $status.val('');
                    $form.submit();
                }
            });
        });

        // 3. Restore Soft-Deleted School Handler
        $(document).on('click', '.btn-restore-school', function(e) {
            e.preventDefault();
            var schoolId = $(this).data('id');
            var schoolName = $(this).data('name');

            if (!window.Swal) return;

            Swal.fire({
                title: 'Restore Institution?',
                html: `Do you want to restore <b>${schoolName}</b> back into the active academic directory?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Restore Institution',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $form.attr('action', '/admin/schools/' + schoolId + '/restore');
                    $method.val('POST');
                    $status.val('');
                    $form.submit();
                }
            });
        });

        // 4. Force Delete (Permanent Purge) Handler
        $(document).on('click', '.btn-force-delete-school', function(e) {
            e.preventDefault();
            var schoolId = $(this).data('id');
            var schoolName = $(this).data('name');

            if (!window.Swal) return;

            Swal.fire({
                title: 'Permanently Purge Institution?',
                html: `<div class="text-left text-xs text-rose-800 bg-rose-50 p-3 rounded-lg border border-rose-200 mb-2">
                        <b>CRITICAL WARNING:</b> You are about to permanently purge <b>${schoolName}</b>.
                        <p class="mt-1 text-slate-600">All institutional records, configurations, and tenant mapping will be destroyed forever. <u>This action cannot be undone.</u></p>
                       </div>
                       <p class="text-xs text-slate-500">Please confirm if you want to proceed with permanent destruction.</p>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes, Permanently Purge',
                cancelButtonText: 'Abort',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                focusCancel: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    $form.attr('action', '/admin/schools/' + schoolId + '/force-delete');
                    $method.val('DELETE');
                    $status.val('');
                    $form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
