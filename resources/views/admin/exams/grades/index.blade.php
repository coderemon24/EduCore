@extends('layouts.admin')

@section('title', 'Grading Scales | School Admin')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.exams.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Exams</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Grading Scales</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Grading Scales</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Configure grade points, percentage ranges, and pass/fail rules.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.exams.grades.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Grading Scale</span>
            </a>
        </div>
    </div>

    <!-- Grading Scales Grid -->
    <div class="grid grid-cols-1 gap-5">
        @forelse($scales as $scale)
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs hover:border-slate-300 transition">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 pb-4 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <h2 class="text-lg font-bold text-slate-900">{{ $scale->name }}</h2>
                            @if($scale->is_default)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-violet-50 text-violet-700 border border-violet-200">
                                    Default
                                </span>
                            @endif
                            @if($scale->fail_any_fails_all)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200" title="Any failed subject overrides overall result to GPA 0.00 / F">
                                    Fail in 1 Subject = Fail All
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                    Standard GPA
                                </span>
                            @endif
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $scale->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                {{ $scale->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 max-w-2xl">{{ $scale->description ?: 'No description provided.' }}</p>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('admin.exams.grades.edit', $scale) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            <span>Edit</span>
                        </a>

                        <form method="POST" action="{{ route('admin.exams.grades.destroy', $scale) }}" onsubmit="return confirm('Are you sure you want to delete this grading scale?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-md border border-rose-200 bg-white text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                    title="Delete Scale">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Grade Tiers Table Preview -->
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-left text-xs border border-slate-100 rounded-lg">
                        <thead class="bg-slate-50/70 text-slate-500 font-semibold uppercase tracking-wider text-[10px] border-b border-slate-100">
                            <tr>
                                <th class="px-3 py-2">Grade</th>
                                <th class="px-3 py-2">GPA Point</th>
                                <th class="px-3 py-2">Mark Range (%)</th>
                                <th class="px-3 py-2">Pass / Fail</th>
                                <th class="px-3 py-2">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($scale->gradeTiers as $tier)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-3 py-2 font-bold text-slate-900">
                                        <span class="inline-flex items-center justify-center min-w-[28px] px-1.5 py-0.5 rounded text-xs font-bold {{ $tier->is_pass ? 'bg-slate-100 text-slate-800' : 'bg-rose-100 text-rose-700' }}">
                                            {{ $tier->grade }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 font-mono font-semibold text-slate-700">
                                        {{ number_format($tier->point, 2) }}
                                    </td>
                                    <td class="px-3 py-2 font-mono text-slate-600">
                                        {{ number_format($tier->min_percentage, 0) }}% - {{ number_format($tier->max_percentage, 0) }}%
                                    </td>
                                    <td class="px-3 py-2">
                                        @if($tier->is_pass)
                                            <span class="inline-flex items-center text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Pass</span>
                                        @else
                                            <span class="inline-flex items-center text-[10px] font-semibold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">Fail</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-slate-500">
                                        {{ $tier->remarks ?: '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-violet-50 text-violet-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="mt-3 text-sm font-bold text-slate-900">No Grading Scales Found</h3>
                <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                    Add a grading scale to define percentage marks, grade points, and letter grades.
                </p>
                <div class="mt-4">
                    <a href="{{ route('admin.exams.grades.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition">
                        <span>Add Grading Scale</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($scales->hasPages())
        <div class="mt-4">
            {{ $scales->links() }}
        </div>
    @endif

</div>
@endsection
