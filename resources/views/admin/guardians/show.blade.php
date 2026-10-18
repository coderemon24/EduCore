@extends('layouts.admin')

@section('title', $guardian->name . ' | Parents & Guardians')

@section('admin-content')
<div class="space-y-4">

    {{-- Breadcrumb & Title --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.guardians.index') }}" class="text-slate-600 hover:text-violet-600 transition">Parents</a>
                <span>/</span>
                <span class="font-medium text-slate-700">{{ $guardian->name }}</span>
            </div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl font-bold tracking-tight text-slate-900">{{ $guardian->name }}</h1>
                @if($guardian->trashed())
                    <span class="inline-flex items-center rounded border border-rose-200 bg-rose-50 px-2 py-0.5 text-xs font-semibold text-rose-700">
                        Trash
                    </span>
                @else
                    <span class="inline-flex items-center rounded border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700">
                        Active
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-0.5">
                Contact information and linked students.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.guardians.index') }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back</span>
            </a>

            @unless($guardian->trashed())
                <a href="{{ route('admin.guardians.edit', $guardian) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span>Edit</span>
                </a>

                <form method="POST" action="{{ route('admin.guardians.destroy', $guardian) }}"
                      onsubmit="confirmGuardianAction(event, this, 'delete', '{{ addslashes($guardian->name) }}');"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 shadow-2xs hover:bg-rose-100 transition cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Move to Trash</span>
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.guardians.restore', $guardian->id) }}"
                      onsubmit="confirmGuardianAction(event, this, 'restore', '{{ addslashes($guardian->name) }}');"
                      class="inline">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 shadow-2xs hover:bg-emerald-100 transition cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Restore</span>
                    </button>
                </form>
            @endunless
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Left: Details Card --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-2xs space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="h-11 w-11 shrink-0 rounded-full bg-slate-100 text-slate-800 font-bold flex items-center justify-center text-sm border border-slate-200">
                        {{ strtoupper(substr($guardian->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">{{ $guardian->name }}</h2>
                        <p class="text-xs text-slate-500">{{ $guardian->occupation ?: 'Parent' }}</p>
                    </div>
                </div>

                {{-- Fields --}}
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="block text-[11px] font-medium text-slate-400">Phone</span>
                        <div class="mt-0.5 flex items-center gap-2 font-mono">
                            <a href="tel:{{ $guardian->phone }}" class="text-slate-900 hover:text-violet-600 font-semibold transition">
                                {{ $guardian->phone }}
                            </a>
                        </div>
                    </div>

                    <div>
                        <span class="block text-[11px] font-medium text-slate-400">Email</span>
                        <div class="mt-0.5">
                            @if($guardian->email)
                                <a href="mailto:{{ $guardian->email }}" class="text-slate-800 hover:text-violet-600 transition">
                                    {{ $guardian->email }}
                                </a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <span class="block text-[11px] font-medium text-slate-400">National ID</span>
                        <div class="mt-0.5 font-mono text-slate-800">
                            {{ $guardian->national_id ?: '—' }}
                        </div>
                    </div>

                    <div>
                        <span class="block text-[11px] font-medium text-slate-400">Address</span>
                        <div class="mt-0.5 text-slate-700 leading-relaxed">
                            {{ $guardian->address ?: '—' }}
                        </div>
                    </div>
                </div>

                {{-- Record Info --}}
                <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-400 space-y-1">
                    <div>Added: <span class="text-slate-600">{{ $guardian->created_at?->format('M d, Y') ?? '—' }}</span></div>
                    <div>Updated: <span class="text-slate-600">{{ $guardian->updated_at?->format('M d, Y') ?? '—' }}</span></div>
                    @if($guardian->deleted_at)
                        <div class="text-rose-600">Moved to trash: {{ $guardian->deleted_at->format('M d, Y') }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Children --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Children</h2>
                        <p class="text-xs text-slate-500">Students linked to this parent.</p>
                    </div>
                    <span class="rounded bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700 border border-slate-200">
                        {{ $guardian->students->count() }} {{ Str::plural('Student', $guardian->students->count()) }}
                    </span>
                </div>

                @if($guardian->students->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($guardian->students as $student)
                            @php
                                $rel = $student->pivot->relationship ?? 'Parent';
                                $isPrimary = $student->pivot->is_primary ?? false;
                                $isEmergency = $student->pivot->is_emergency_contact ?? false;
                                $className = $student->currentEnrollment?->academicClass?->name ?? 'Unassigned';
                                $sectionName = $student->currentEnrollment?->section?->name;
                                $rollNumber = $student->currentEnrollment?->roll_number;
                            @endphp
                            <div class="rounded-md border border-slate-200 bg-slate-50/50 p-3.5 space-y-2 hover:border-slate-300 transition">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <a href="{{ route('admin.students.show', $student) }}" class="text-xs font-bold text-slate-900 hover:text-violet-600 transition">
                                            {{ $student->full_name }}
                                        </a>
                                        <div class="font-mono text-[10px] text-slate-500">
                                            Admission: {{ $student->admission_number }}
                                        </div>
                                    </div>
                                    <span class="rounded bg-white px-2 py-0.5 text-[10px] font-semibold text-slate-600 border border-slate-200 capitalize">
                                        {{ $rel }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap gap-1.5 text-[10px]">
                                    <span class="rounded bg-violet-50 text-violet-700 border border-violet-100 px-1.5 py-0.5 font-bold">
                                        Class: {{ $className }}{{ $sectionName ? ' - '.$sectionName : '' }}
                                    </span>
                                    @if($rollNumber)
                                        <span class="rounded bg-white text-slate-700 border border-slate-200 px-1.5 py-0.5 font-mono">
                                            Roll: {{ $rollNumber }}
                                        </span>
                                    @endif
                                    @if($isPrimary)
                                        <span class="rounded bg-amber-50 text-amber-700 border border-amber-200 px-1.5 py-0.5 font-semibold">
                                            Primary
                                        </span>
                                    @endif
                                    @if($isEmergency)
                                        <span class="rounded bg-rose-50 text-rose-700 border border-rose-200 px-1.5 py-0.5 font-semibold">
                                            Emergency Contact
                                        </span>
                                    @endif
                                </div>

                                <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-xs">
                                    <span class="text-[11px] text-slate-400 capitalize">Status: {{ $student->status }}</span>
                                    <a href="{{ route('admin.students.show', $student) }}" class="text-[11px] font-semibold text-violet-600 hover:text-violet-700 transition">
                                        View Student →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded border border-dashed border-slate-200 p-6 text-center text-xs text-slate-400">
                        No students are currently linked to this parent.
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

<script>
    function confirmGuardianAction(event, form, type, name) {
        if (event) event.preventDefault();

        let title = 'Are you sure?';
        let text = `Do you want to proceed with ${name}?`;
        let icon = 'warning';
        let confirmBtnText = 'Yes, proceed';
        let confirmBtnColor = '#e11d48';

        if (type === 'delete') {
            title = 'Move to Trash?';
            text = `Move ${name} to trash?`;
            icon = 'warning';
            confirmBtnText = 'Yes, move to trash';
            confirmBtnColor = '#e11d48';
        } else if (type === 'restore') {
            title = 'Restore Parent?';
            text = `Restore ${name} back to active list?`;
            icon = 'question';
            confirmBtnText = 'Yes, restore';
            confirmBtnColor = '#059669';
        }

        if (window.Swal) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmBtnText,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            if (confirm(text)) {
                form.submit();
            }
        }
    }
</script>
@endsection

