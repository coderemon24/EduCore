@extends('layouts.admin')

@section('title', 'Edit Grading Scale | School Admin')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Breadcrumbs -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.exams.grades.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Grading Scales</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Edit Scale</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Grading Scale</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Update grade points, percentage ranges, and pass/fail rules.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.exams.grades.index') }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition">
                <span>Cancel</span>
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.exams.grades.update', $scale) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Scale Parameters Card -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Scale Information</h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Scale Name <span class="text-rose-500">*</span></label>
                    <input type="text"
                           name="name"
                           id="name"
                           required
                           value="{{ old('name', $scale->name) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    @error('name')
                        <p class="mt-1 text-[11px] text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block text-xs font-semibold text-slate-700 mb-1">Description (Optional)</label>
                    <textarea name="description"
                              id="description"
                              rows="2"
                              class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">{{ old('description', $scale->description) }}</textarea>
                </div>
            </div>

            <!-- Policy Flags -->
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 pt-2">
                <label class="flex items-start gap-2.5 rounded-lg border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition">
                    <input type="checkbox" name="fail_any_fails_all" value="1" {{ old('fail_any_fails_all', $scale->fail_any_fails_all) ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                    <div>
                        <span class="block text-xs font-bold text-slate-800">Fail in 1 Subject = Fail All</span>
                        <span class="block text-[11px] text-slate-500 mt-0.5">If a student fails any subject, overall result is marked Fail with 0.00 GPA.</span>
                    </div>
                </label>

                <label class="flex items-start gap-2.5 rounded-lg border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition">
                    <input type="checkbox" name="is_default" value="1" {{ old('is_default', $scale->is_default) ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                    <div>
                        <span class="block text-xs font-bold text-slate-800">Default Scale</span>
                        <span class="block text-[11px] text-slate-500 mt-0.5">Automatically select this scale when creating new exams.</span>
                    </div>
                </label>

                <label class="flex items-start gap-2.5 rounded-lg border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $scale->is_active) ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                    <div>
                        <span class="block text-xs font-bold text-slate-800">Active</span>
                        <span class="block text-[11px] text-slate-500 mt-0.5">Available for selection in exams.</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Grade Tiers Editor Card -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Grade Tiers</h2>
                    <p class="text-[11px] text-slate-500">Define grade intervals from highest grade to failing grade.</p>
                </div>
                <button type="button"
                        onclick="addTierRow()"
                        class="inline-flex items-center gap-1.5 rounded-md border border-violet-200 bg-violet-50 px-2.5 py-1.5 text-xs font-semibold text-violet-700 hover:bg-violet-100 transition cursor-pointer">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add Tier</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-slate-200 rounded-lg" id="tiers-table">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-3 py-2 w-24">Grade</th>
                            <th class="px-3 py-2 w-28">GPA Point</th>
                            <th class="px-3 py-2 w-28">Min %</th>
                            <th class="px-3 py-2 w-28">Max %</th>
                            <th class="px-3 py-2 w-24 text-center">Pass?</th>
                            <th class="px-3 py-2">Remarks</th>
                            <th class="px-3 py-2 w-12 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tiers-tbody" class="divide-y divide-slate-100">
                        @foreach($scale->gradeTiers as $idx => $t)
                            <tr class="tier-row hover:bg-slate-50/50 transition">
                                <td class="px-3 py-2">
                                    <input type="text" name="tiers[{{ $idx }}][grade]" value="{{ $t->grade }}" required class="w-full rounded border border-slate-200 px-2 py-1 text-xs font-bold text-slate-800 uppercase text-center focus:border-violet-600 focus:outline-hidden">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" step="0.01" name="tiers[{{ $idx }}][point]" value="{{ number_format($t->point, 2) }}" required class="w-full rounded border border-slate-200 px-2 py-1 text-xs font-mono text-slate-800 text-center focus:border-violet-600 focus:outline-hidden">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" step="0.01" name="tiers[{{ $idx }}][min_percentage]" value="{{ number_format($t->min_percentage, 2) }}" required class="w-full rounded border border-slate-200 px-2 py-1 text-xs font-mono text-slate-800 text-center focus:border-violet-600 focus:outline-hidden">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" step="0.01" name="tiers[{{ $idx }}][max_percentage]" value="{{ number_format($t->max_percentage, 2) }}" required class="w-full rounded border border-slate-200 px-2 py-1 text-xs font-mono text-slate-800 text-center focus:border-violet-600 focus:outline-hidden">
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <input type="checkbox" name="tiers[{{ $idx }}][is_pass]" value="1" {{ $t->is_pass ? 'checked' : '' }} class="rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" name="tiers[{{ $idx }}][remarks]" value="{{ $t->remarks }}" placeholder="Remarks..." class="w-full rounded border border-slate-200 px-2 py-1 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <button type="button" onclick="removeTierRow(this)" class="text-slate-400 hover:text-rose-600 transition cursor-pointer">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.exams.grades.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">Cancel</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <span>Update Grading Scale</span>
            </button>
        </div>
    </form>

</div>

<script>
    let tierIndex = {{ count($scale->gradeTiers) }};

    function addTierRow() {
        const tbody = document.getElementById('tiers-tbody');
        const tr = document.createElement('tr');
        tr.className = 'tier-row hover:bg-slate-50/50 transition';
        tr.innerHTML = `
            <td class="px-3 py-2">
                <input type="text" name="tiers[${tierIndex}][grade]" placeholder="A+" required class="w-full rounded border border-slate-200 px-2 py-1 text-xs font-bold text-slate-800 uppercase text-center focus:border-violet-600 focus:outline-hidden">
            </td>
            <td class="px-3 py-2">
                <input type="number" step="0.01" name="tiers[${tierIndex}][point]" placeholder="0.00" required class="w-full rounded border border-slate-200 px-2 py-1 text-xs font-mono text-slate-800 text-center focus:border-violet-600 focus:outline-hidden">
            </td>
            <td class="px-3 py-2">
                <input type="number" step="0.01" name="tiers[${tierIndex}][min_percentage]" placeholder="0" required class="w-full rounded border border-slate-200 px-2 py-1 text-xs font-mono text-slate-800 text-center focus:border-violet-600 focus:outline-hidden">
            </td>
            <td class="px-3 py-2">
                <input type="number" step="0.01" name="tiers[${tierIndex}][max_percentage]" placeholder="100" required class="w-full rounded border border-slate-200 px-2 py-1 text-xs font-mono text-slate-800 text-center focus:border-violet-600 focus:outline-hidden">
            </td>
            <td class="px-3 py-2 text-center">
                <input type="checkbox" name="tiers[${tierIndex}][is_pass]" value="1" checked class="rounded border-slate-300 text-violet-600 focus:ring-violet-500">
            </td>
            <td class="px-3 py-2">
                <input type="text" name="tiers[${tierIndex}][remarks]" placeholder="Remarks..." class="w-full rounded border border-slate-200 px-2 py-1 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
            </td>
            <td class="px-3 py-2 text-center">
                <button type="button" onclick="removeTierRow(this)" class="text-slate-400 hover:text-rose-600 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        tierIndex++;
    }

    function removeTierRow(btn) {
        const row = btn.closest('tr');
        if (document.querySelectorAll('.tier-row').length > 1) {
            row.remove();
        } else {
            alert('A grading system requires at least one grade tier.');
        }
    }
</script>
@endsection
