@extends('layouts.admin')

@section('title', 'Academic Institutions & Tenants | Platform Administration')

@section('admin-content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="text-slate-500">Platform Management</span>
                <span>/</span>
                <span class="font-semibold text-slate-700">Schools & Campuses</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Academic Institutions & Tenants</h1>
            <p class="text-xs text-slate-500 mt-0.5">Central management portal for institutional onboarding, configuration, lifecycle status, and tenant archiving.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.schools.create') }}"
               id="btn-onboard-school"
               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Onboard New Tenant</span>
            </a>
        </div>
    </div>

    <!-- Flash Message Notification -->
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50/80 p-4 text-xs font-medium text-emerald-800 shadow-xs flex items-center gap-3">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 shrink-0">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Status Tabs & Filter Toolbar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-xs space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <!-- Filter Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 lg:pb-0 text-xs font-medium">
                @php
                    $tabs = [
                        'all' => ['label' => 'All Active Tenants', 'count' => $statusCounts['all']],
                        'active' => ['label' => 'Active', 'count' => $statusCounts['active']],
                        'pending' => ['label' => 'Pending Verification', 'count' => $statusCounts['pending']],
                        'suspended' => ['label' => 'Suspended', 'count' => $statusCounts['suspended']],
                        'inactive' => ['label' => 'Inactive', 'count' => $statusCounts['inactive']],
                        'trashed' => ['label' => 'Archived / Trashed', 'count' => $statusCounts['trashed']],
                    ];
                @endphp

                @foreach($tabs as $tabKey => $tab)
                    @php
                        $isSelected = ($statusFilter === $tabKey);
                        $isTrashTab = ($tabKey === 'trashed');
                    @endphp
                    <a href="{{ route('admin.schools.index', array_merge(request()->query(), ['status' => $tabKey, 'page' => 1])) }}"
                       class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 transition-colors whitespace-nowrap {{ $isSelected ? ($isTrashTab ? 'bg-rose-600 text-white font-semibold shadow-xs' : 'bg-violet-600 text-white font-semibold shadow-xs') : ($isTrashTab ? 'text-rose-700 bg-rose-50/70 hover:bg-rose-100' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900') }}">
                        @if($isTrashTab)
                            <svg class="h-3.5 w-3.5 {{ $isSelected ? 'text-rose-100' : 'text-rose-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        @endif
                        <span>{{ $tab['label'] }}</span>
                        <span class="rounded-full px-1.5 py-0.2 text-[10px] font-bold {{ $isSelected ? 'bg-black/20 text-white' : ($isTrashTab ? 'bg-rose-200 text-rose-800' : 'bg-slate-200 text-slate-700') }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.schools.index') }}" method="GET" class="flex items-center gap-2 w-full lg:w-80">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <div class="relative w-full">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Filter name, code, email, city..."
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/70 py-1.5 pl-9 pr-8 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    @if($search)
                        <a href="{{ route('admin.schools.index', ['status' => $statusFilter]) }}"
                           class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400 hover:text-slate-600"
                           title="Clear search">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
                <button type="submit"
                        class="rounded-lg bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-900 shadow-2xs cursor-pointer">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Trashed Archive Context Banner -->
    @if($statusFilter === 'trashed')
        <div class="rounded-xl border border-rose-200 bg-rose-50/60 p-4 text-xs text-rose-800 flex items-center justify-between gap-3 shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="h-7 w-7 rounded-lg bg-rose-100 flex items-center justify-center text-rose-700 shrink-0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <span class="font-bold">Archived Tenants Directory (Soft Deleted)</span>
                    <p class="text-[11px] text-rose-600 mt-0.5">These institutions are decommissioned. You can restore them to active directory or permanently purge them from the database.</p>
                </div>
            </div>
            <a href="{{ route('admin.schools.index') }}" class="text-xs font-semibold text-rose-700 hover:underline shrink-0">
                &larr; Back to Active Directory
            </a>
        </div>
    @endif

    <!-- High-Information-Density Schools Table -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3 px-4">Institution & Code</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Official Contact</th>
                        <th class="py-3 px-4">Campus Location</th>
                        <th class="py-3 px-4">Timezone & Currency</th>
                        <th class="py-3 px-4">{{ $statusFilter === 'trashed' ? 'Archived Date' : 'Registered' }}</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($schools as $school)
                        <tr class="transition-colors hover:bg-slate-50/80">
                            <!-- Institution & Code -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center p-1.5 shrink-0">
                                        <img src="{{ asset($school->logo ?: 'admin-assets/images/laravel-school-transparent.png') }}"
                                             alt="{{ $school->name }}"
                                             class="h-full w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.schools.show', $school) }}"
                                           class="font-semibold text-slate-900 hover:text-violet-600 transition truncate block max-w-xs">
                                            {{ $school->name }}
                                        </a>
                                        <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-500 font-mono">
                                            <span class="font-bold text-slate-700">{{ $school->code ?: 'N/A' }}</span>
                                            <span>&bull;</span>
                                            <span class="text-slate-400">slug: {{ $school->slug }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3.5 px-4">
                                @if($school->trashed())
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-rose-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        <span>Soft Deleted</span>
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
                            </td>

                            <!-- Contact Info -->
                            <td class="py-3.5 px-4">
                                <p class="text-slate-800 font-medium truncate max-w-[180px]">{{ $school->email ?: '—' }}</p>
                                <p class="text-slate-500 text-[11px] mt-0.5 font-mono">{{ $school->phone ?: '—' }}</p>
                            </td>

                            <!-- Location -->
                            <td class="py-3.5 px-4">
                                <p class="text-slate-800 font-medium">{{ $school->city ? $school->city . ($school->state ? ', ' . $school->state : '') : '—' }}</p>
                                <p class="text-slate-400 text-[11px] mt-0.5">{{ $school->country ?: 'United States' }}</p>
                            </td>

                            <!-- Timezone & Currency -->
                            <td class="py-3.5 px-4">
                                <span class="text-slate-700 font-medium block truncate max-w-[140px]">{{ $school->timezone ?: 'UTC' }}</span>
                                <span class="inline-block mt-0.5 text-[10px] font-bold text-violet-700 bg-violet-50 px-1.5 py-0.2 rounded border border-violet-100">{{ $school->currency ?: 'USD' }}</span>
                            </td>

                            <!-- Date Column -->
                            <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px]">
                                @if($school->trashed())
                                    <span class="text-rose-600 font-medium">{{ $school->deleted_at->format('M d, Y') }}</span>
                                    <span class="block text-[10px] text-slate-400">{{ $school->deleted_at->diffForHumans() }}</span>
                                @else
                                    <span>{{ $school->created_at ? $school->created_at->format('M d, Y') : '—' }}</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($school->trashed())
                                        <!-- Restore Action Button (jQuery SweetAlert) -->
                                        <button type="button"
                                                data-id="{{ $school->id }}"
                                                data-name="{{ $school->name }}"
                                                class="btn-restore-school inline-flex items-center gap-1 rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 text-xs font-semibold text-emerald-700 shadow-2xs transition hover:bg-emerald-100 hover:border-emerald-300 cursor-pointer">
                                            <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span>Restore</span>
                                        </button>

                                        <!-- Permanent Force-Delete Button (jQuery SweetAlert) -->
                                        <button type="button"
                                                data-id="{{ $school->id }}"
                                                data-name="{{ $school->name }}"
                                                class="btn-force-delete-school inline-flex items-center gap-1 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-700 shadow-2xs transition hover:bg-rose-100 hover:border-rose-300 cursor-pointer">
                                            <svg class="h-3.5 w-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Purge</span>
                                        </button>
                                    @else
                                        <!-- Edit Tenant -->
                                        <a href="{{ route('admin.schools.edit', $school) }}"
                                           title="Edit Institution"
                                           class="inline-flex items-center justify-center h-7 w-7 rounded-lg border border-slate-200 bg-white text-slate-600 shadow-2xs transition hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <!-- View Details Button -->
                                        <a href="{{ route('admin.schools.show', $school) }}"
                                           title="View Details"
                                           class="inline-flex items-center justify-center h-7 w-7 rounded-lg border border-slate-200 bg-white text-slate-600 shadow-2xs transition hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        <!-- Quick Status Toggle Button -->
                                        <button type="button"
                                                data-id="{{ $school->id }}"
                                                data-name="{{ $school->name }}"
                                                data-status="{{ $school->status?->value ?? 'active' }}"
                                                title="Change Lifecycle Status"
                                                class="btn-change-status inline-flex items-center justify-center h-7 w-7 rounded-lg border border-slate-200 bg-white text-slate-600 shadow-2xs transition hover:bg-violet-50 hover:text-violet-700 hover:border-violet-200 cursor-pointer">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </button>

                                        <!-- Soft Delete / Archive Button (jQuery SweetAlert) -->
                                        <button type="button"
                                                data-id="{{ $school->id }}"
                                                data-name="{{ $school->name }}"
                                                title="Archive / Soft Delete"
                                                class="btn-archive-school inline-flex items-center justify-center h-7 w-7 rounded-lg border border-slate-200 bg-white text-slate-400 shadow-2xs transition hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 cursor-pointer">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">
                                <div class="max-w-xs mx-auto text-center space-y-2">
                                    <div class="h-10 w-10 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <p class="font-semibold text-slate-800 text-sm">No institutions found</p>
                                    <p class="text-xs text-slate-400">There are no academic tenants matching the selected filter criteria or keyword.</p>
                                    @if($search || $statusFilter !== 'all')
                                        <a href="{{ route('admin.schools.index') }}" class="inline-block mt-2 text-xs font-semibold text-violet-600 hover:text-violet-700">
                                            Reset all filters
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        @if($schools->hasPages())
            <div class="border-t border-slate-200 px-4 py-3 bg-slate-50/50">
                {{ $schools->links() }}
            </div>
        @endif
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

        // 1. Quick Change Status Handler
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
