@extends('layouts.admin')

@section('title', 'Assign Subjects | Academic Structure')

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
                <span class="font-semibold text-slate-700">Assign Subjects</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Class Subject Assignments</h1>
            <p class="text-xs text-slate-500 mt-0.5">Map curriculum subjects to academic classes, establish credit hours, and configure exam pass marks.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.academic.assign-subjects.create', ['class_id' => request('class_id') !== 'all' ? request('class_id') : null]) }}"
               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Assign Subjects to Class</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.academic.assign-subjects.index') }}" class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Filter by Class -->
                <div class="w-48">
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Filter by Class</label>
                    <select name="class_id"
                            onchange="this.form.submit()"
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 py-1.5 px-2.5 text-xs text-slate-700">
                        <option value="all">All Classes</option>
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" {{ (string)$classFilter === (string)$c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter by Academic Year -->
                <div class="w-48">
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Filter by Academic Year</label>
                    <select name="year_id"
                            onchange="this.form.submit()"
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 py-1.5 px-2.5 text-xs text-slate-700">
                        <option value="all">All Sessions (General Curriculum)</option>
                        @foreach ($academicYears as $y)
                            <option value="{{ $y->id }}" {{ (string)$yearFilter === (string)$y->id ? 'selected' : '' }}>
                                {{ $y->name }} {{ $y->is_current ? '(Current)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Search Input -->
            <div class="flex items-center gap-2 self-end">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Search subject or class..."
                           class="w-full sm:w-56 rounded-lg border border-slate-200 bg-slate-50/50 py-1.5 pl-8 pr-3 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20">
                    <svg class="h-3.5 w-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer">
                    Search
                </button>
                @if(!empty($search) || $classFilter !== 'all' || $yearFilter !== 'all')
                    <a href="{{ route('admin.academic.assign-subjects.index') }}" class="text-xs text-slate-500 hover:text-rose-600">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-600 font-semibold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4">Class / Grade</th>
                        <th class="py-3 px-4">Subject Name</th>
                        <th class="py-3 px-4">Subject Code</th>
                        <th class="py-3 px-4 text-center">Curriculum Type</th>
                        <th class="py-3 px-4 text-center">Full Marks</th>
                        <th class="py-3 px-4 text-center">Pass Marks</th>
                        <th class="py-3 px-4">Academic Session</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-normal">
                    @forelse ($assignments as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 font-semibold text-slate-900">
                                <span>{{ $item->academicClass?->name ?? '—' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-medium text-slate-800">{{ $item->subject?->name ?? '—' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="rounded bg-slate-100 px-2 py-0.5 text-[11px] font-mono text-slate-700 font-medium">
                                    {{ $item->subject?->code ?? '—' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @php
                                    $typeStyles = [
                                        'compulsory' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'optional' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'elective' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    ];
                                    $typeClass = $typeStyles[$item->type] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $typeClass }}">
                                    {{ $item->type }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center font-mono font-medium text-slate-900">
                                {{ $item->full_marks }}
                            </td>
                            <td class="py-3 px-4 text-center font-mono text-slate-600">
                                {{ $item->pass_marks }}
                            </td>
                            <td class="py-3 px-4">
                                @if($item->academicYear)
                                    <span class="inline-flex items-center gap-1 text-slate-700">
                                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ $item->academicYear->name }}</span>
                                    </span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500">
                                        Permanent Curriculum
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.academic.assign-subjects.edit', $item) }}"
                                       title="Edit Assignment"
                                       class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition cursor-pointer">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('admin.academic.assign-subjects.destroy', $item) }}" class="inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="confirmAcademicDelete(this, 'Remove Assignment', 'Are you sure you want to remove {{ $item->subject?->name }} from {{ $item->academicClass?->name }}?')"
                                                title="Remove Assignment"
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
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <p class="text-sm font-medium text-slate-600">No Subject Assignments Found</p>
                                    <p class="text-xs text-slate-400">Map subjects from your curriculum catalog to academic classes.</p>
                                    <a href="{{ route('admin.academic.assign-subjects.create') }}"
                                       class="mt-2 inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white shadow-xs hover:bg-violet-700">
                                        Assign Subjects
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assignments->hasPages())
            <div class="border-t border-slate-200 px-4 py-3 bg-slate-50/50">
                {{ $assignments->links() }}
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
                confirmButtonText: 'Yes, Remove',
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
