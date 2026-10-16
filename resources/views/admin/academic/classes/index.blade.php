@extends('layouts.admin')

@section('title', 'Academic Classes & Grades | Academic Structure')

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
                <span class="font-semibold text-slate-700">Classes & Grades</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Classes & Grade Levels</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage grade levels, ordering, and section assignments across the institution.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.academic.classes.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>New Class</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto text-xs font-medium">
                @php
                    $tabs = [
                        'all' => ['label' => 'All Classes', 'count' => $statusCounts['all']],
                        'active' => ['label' => 'Active', 'count' => $statusCounts['active']],
                        'inactive' => ['label' => 'Inactive', 'count' => $statusCounts['inactive']],
                    ];
                @endphp
                @foreach ($tabs as $key => $tab)
                    <a href="{{ route('admin.academic.classes.index', ['status' => $key, 'search' => request('search')]) }}"
                       class="rounded-lg px-3 py-1.5 transition-colors cursor-pointer {{ $statusFilter === $key ? 'bg-violet-50 text-violet-700 font-semibold border border-violet-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        {{ $tab['label'] }}
                        <span class="ml-1.5 rounded-full px-1.5 py-0.2 text-[10px] {{ $statusFilter === $key ? 'bg-violet-200/80 text-violet-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $tab['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Search Bar -->
            <form method="GET" action="{{ route('admin.academic.classes.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ $statusFilter }}">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Search class or code..."
                           class="w-full sm:w-60 rounded-lg border border-slate-200 bg-slate-50/50 py-1.5 pl-8 pr-3 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    <svg class="h-3.5 w-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer">
                    Search
                </button>
                @if(!empty($search))
                    <a href="{{ route('admin.academic.classes.index', ['status' => $statusFilter]) }}" class="text-xs text-slate-500 hover:text-rose-600">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-600 font-semibold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4 w-16">Order</th>
                        <th class="py-3 px-4">Class / Grade Title</th>
                        <th class="py-3 px-4">Numeric Code</th>
                        <th class="py-3 px-4">Sections</th>
                        <th class="py-3 px-4 text-center">Assigned Subjects</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-normal">
                    @forelse ($classes as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 font-mono font-medium text-slate-500">{{ $item->order_index }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-900">
                                <span>{{ $item->name }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="rounded bg-slate-100 px-2 py-0.5 text-[11px] font-mono text-slate-700">
                                    {{ $item->numeric_code !== null ? $item->numeric_code : '—' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @forelse($item->sections as $sec)
                                        <a href="{{ route('admin.academic.sections.index', ['class_id' => $item->id]) }}"
                                           class="rounded bg-slate-100 hover:bg-violet-100 hover:text-violet-800 transition px-2 py-0.5 text-[10px] font-medium text-slate-700">
                                            {{ $sec->name }}
                                        </a>
                                    @empty
                                        <span class="text-slate-400 text-[11px]">No sections</span>
                                    @endforelse
                                    <a href="{{ route('admin.academic.sections.create', ['class_id' => $item->id]) }}"
                                       title="Add Section"
                                       class="text-violet-600 hover:text-violet-800 text-[11px] font-bold ml-1">+ Add</a>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.academic.assign-subjects.index', ['class_id' => $item->id]) }}"
                                   class="inline-flex items-center gap-1 rounded-full bg-violet-50 px-2.5 py-0.5 text-[10px] font-semibold text-violet-700 hover:bg-violet-100 transition">
                                    <span>{{ $item->subjects_count }} Subjects</span>
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $item->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.academic.classes.edit', $item) }}"
                                       title="Edit Class"
                                       class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition cursor-pointer">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('admin.academic.classes.destroy', $item) }}" class="inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="confirmAcademicDelete(this, 'Delete Class', 'Deleting {{ $item->name }} will also remove its associated sections and subject assignments. Proceed?')"
                                                title="Delete Class"
                                                class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-rose-200 text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <p class="text-sm font-medium text-slate-600">No Academic Classes Found</p>
                                    <p class="text-xs text-slate-400">Create classes such as Grade 1, Grade 10, or Kindergarten.</p>
                                    <a href="{{ route('admin.academic.classes.create') }}"
                                       class="mt-2 inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white shadow-xs hover:bg-violet-700">
                                        Create Class
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($classes->hasPages())
            <div class="border-t border-slate-200 px-4 py-3 bg-slate-50/50">
                {{ $classes->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function confirmAcademicDelete(button, title, text) {
        if (window.Swal) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        } else if (confirm(text)) {
            button.closest('form').submit();
        }
    }
</script>
@endpush
@endsection
