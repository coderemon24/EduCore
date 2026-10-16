@extends('layouts.admin')

@section('title', 'Subjects Catalog | Academic Structure')

@section('admin-content')
<div class="space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="text-slate-500">Academic</span>
                <span>/</span>
                <span class="font-semibold text-slate-700">Subjects Catalog</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Subjects Catalog</h1>
            <p class="text-xs text-slate-500 mt-0.5">Maintain the master catalog of institutional course subjects, codes, theory/practical classifications, and archiving.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.academic.subjects.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>New Subject</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-xs space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-medium pb-1 lg:pb-0">
                @php
                    $tabs = [
                        'all' => ['label' => 'All Subjects', 'count' => $statusCounts['all']],
                        'active' => ['label' => 'Active', 'count' => $statusCounts['active']],
                        'inactive' => ['label' => 'Inactive', 'count' => $statusCounts['inactive']],
                        'trashed' => ['label' => 'Archived / Trashed', 'count' => $statusCounts['trashed']],
                    ];
                @endphp
                @foreach ($tabs as $key => $tab)
                    @php
                        $isSelected = $statusFilter === $key;
                        $isTrashTab = $key === 'trashed';
                    @endphp
                    <a href="{{ route('admin.academic.subjects.index', array_merge(request()->query(), ['status' => $key, 'type' => $typeFilter, 'page' => 1])) }}"
                       class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 transition-colors whitespace-nowrap cursor-pointer {{ $isSelected ? ($isTrashTab ? 'bg-rose-600 text-white font-semibold shadow-xs' : 'bg-violet-600 text-white font-semibold shadow-xs') : ($isTrashTab ? 'text-rose-700 bg-rose-50/70 hover:bg-rose-100' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900') }}">
                        @if ($isTrashTab)
                            <svg class="h-3.5 w-3.5 {{ $isSelected ? 'text-rose-100' : 'text-rose-600' }}"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        @endif
                        <span>{{ $tab['label'] }}</span>
                        <span class="rounded-full px-1.5 py-0.2 text-[10px] font-bold {{ $isSelected ? 'bg-black/20 text-white' : ($isTrashTab ? 'bg-rose-200 text-rose-800' : 'bg-slate-200 text-slate-700') }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Filters & Search Form -->
            <form method="GET" action="{{ route('admin.academic.subjects.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="hidden" name="status" value="{{ $statusFilter }}">

                <!-- Filter by Type -->
                <div class="w-40">
                    <select name="type"
                            onchange="this.form.submit()"
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/50 py-1.5 px-2.5 text-xs text-slate-700 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                        <option value="all">All Types</option>
                        <option value="theory" {{ $typeFilter === 'theory' ? 'selected' : '' }}>Theory Only</option>
                        <option value="practical" {{ $typeFilter === 'practical' ? 'selected' : '' }}>Practical / Lab</option>
                        <option value="both" {{ $typeFilter === 'both' ? 'selected' : '' }}>Theory + Lab</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Search name or code..."
                           class="w-full sm:w-48 rounded-lg border border-slate-200 bg-slate-50/50 py-1.5 pl-8 pr-3 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    <svg class="h-3.5 w-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer">
                    Filter
                </button>
                @if(!empty($search) || $typeFilter !== 'all')
                    <a href="{{ route('admin.academic.subjects.index', ['status' => $statusFilter]) }}" class="text-xs text-slate-500 hover:text-rose-600">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Trashed Archive Context Banner -->
    @if ($statusFilter === 'trashed')
        <div class="rounded-xl border border-rose-200 bg-rose-50/60 p-4 text-xs text-rose-800 flex items-center justify-between gap-3 shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="h-7 w-7 rounded-lg bg-rose-100 flex items-center justify-center text-rose-700 shrink-0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <span class="font-bold">Archived Subjects Directory (Soft Deleted)</span>
                    <p class="text-[11px] text-rose-600 mt-0.5">These course subjects are decommissioned. You can restore them to active catalog or permanently purge them from the database.</p>
                </div>
            </div>
            <a href="{{ route('admin.academic.subjects.index') }}"
                class="text-xs font-semibold text-rose-700 hover:underline shrink-0">
                &larr; Back to Active Directory
            </a>
        </div>
    @endif

    <!-- Data Table -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-600 font-semibold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4">Subject Name</th>
                        <th class="py-3 px-4">Subject Code</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4">Credit Hours</th>
                        <th class="py-3 px-4 text-center">Class Assignments</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        @if($statusFilter === 'trashed')
                            <th class="py-3 px-4">Deleted Date</th>
                        @endif
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-normal">
                    @forelse ($subjects as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 font-semibold text-slate-900">
                                <span>{{ $item->name }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="rounded bg-slate-100 px-2 py-0.5 text-[11px] font-mono text-slate-700 font-semibold">
                                    {{ $item->code }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="rounded px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $item->type === 'theory' ? 'bg-blue-50 text-blue-700 border border-blue-200' : ($item->type === 'practical' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-purple-50 text-purple-700 border border-purple-200') }}">
                                    {{ $item->type === 'both' ? 'Theory + Lab' : ucfirst($item->type) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-slate-600">{{ $item->credit_hours ? number_format($item->credit_hours, 1) . ' hrs' : '—' }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-semibold text-slate-700">
                                    {{ $item->academic_classes_count }} Classes
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($item->trashed())
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-rose-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        <span>Soft Deleted</span>
                                    </span>
                                @else
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $item->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>
                            @if($statusFilter === 'trashed')
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">
                                    <span class="text-rose-600 font-medium">{{ $item->deleted_at ? $item->deleted_at->format('M d, Y') : '—' }}</span>
                                    <span class="block text-[10px] text-slate-400">{{ $item->deleted_at ? $item->deleted_at->diffForHumans() : '' }}</span>
                                </td>
                            @endif
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if ($item->trashed())
                                        <!-- Restore Button -->
                                        <button type="button" data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                            class="btn-restore-subject inline-flex items-center gap-1 rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 shadow-2xs transition hover:bg-emerald-100 hover:border-emerald-300 cursor-pointer">
                                            <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span>Restore</span>
                                        </button>

                                        <!-- Permanent Purge Button -->
                                        <button type="button" data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                            class="btn-force-delete-subject inline-flex items-center gap-1 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 shadow-2xs transition hover:bg-rose-100 hover:border-rose-300 cursor-pointer">
                                            <svg class="h-3.5 w-3.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Purge</span>
                                        </button>
                                    @else
                                        <!-- Edit Subject -->
                                        <a href="{{ route('admin.academic.subjects.edit', $item) }}"
                                           title="Edit Subject"
                                           class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 shadow-2xs transition cursor-pointer">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>

                                        <!-- Archive / Soft Delete Button -->
                                        <button type="button" data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                title="Archive Subject"
                                                class="btn-archive-subject inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 shadow-2xs transition cursor-pointer">
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
                            <td colspan="{{ $statusFilter === 'trashed' ? '8' : '7' }}" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <p class="text-sm font-medium text-slate-600">No Subjects in Catalog</p>
                                    <p class="text-xs text-slate-400">There are no course subjects matching the selected filter criteria.</p>
                                    @if($search || $typeFilter !== 'all' || $statusFilter !== 'all')
                                        <a href="{{ route('admin.academic.subjects.index') }}"
                                           class="mt-2 text-xs font-semibold text-violet-600 hover:text-violet-700">
                                            Reset all filters
                                        </a>
                                    @else
                                        <a href="{{ route('admin.academic.subjects.create') }}"
                                           class="mt-2 inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white shadow-xs hover:bg-violet-700">
                                            Create Subject
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subjects->hasPages())
            <div class="border-t border-slate-200 px-4 py-3 bg-slate-50/50">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Hidden Action Dispatch Form for SweetAlert Submissions -->
<form id="action-dispatch-form" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="_method" id="action-dispatch-method" value="POST">
</form>

@push('scripts')
<script>
    $(function() {
        var $form = $('#action-dispatch-form');
        var $method = $('#action-dispatch-method');

        // Archive / Soft Delete
        $(document).on('click', '.btn-archive-subject', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            if (!window.Swal) return;

            Swal.fire({
                title: 'Archive Course Subject?',
                html: `Are you sure you want to archive <b>${name}</b>?<br><span class="text-slate-500 text-xs mt-1 block">This is a safe soft-delete. The subject can be restored at any time from the "Archived" tab.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Archive Subject',
                cancelButtonText: 'Keep Active',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                focusCancel: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    $form.attr('action', '/admin/academic/subjects/' + id);
                    $method.val('DELETE');
                    $form.submit();
                }
            });
        });

        // Restore Soft Deleted Subject
        $(document).on('click', '.btn-restore-subject', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            if (!window.Swal) return;

            Swal.fire({
                title: 'Restore Course Subject?',
                html: `Do you want to restore <b>${name}</b> back into the active course catalog?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Restore Subject',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $form.attr('action', '/admin/academic/subjects/' + id + '/restore');
                    $method.val('POST');
                    $form.submit();
                }
            });
        });

        // Force Delete / Permanent Purge
        $(document).on('click', '.btn-force-delete-subject', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            if (!window.Swal) return;

            Swal.fire({
                title: 'Permanently Purge Subject?',
                html: `<div class="text-left text-xs text-rose-800 bg-rose-50 p-3 rounded-lg border border-rose-200 mb-2">
                        <b>CRITICAL WARNING:</b> You are about to permanently purge subject <b>${name}</b>.
                        <p class="mt-1 text-slate-600">All student grading entries and syllabus associations tied to this subject will be permanently destroyed. <u>This action cannot be undone.</u></p>
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
                    $form.attr('action', '/admin/academic/subjects/' + id + '/force-delete');
                    $method.val('DELETE');
                    $form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
