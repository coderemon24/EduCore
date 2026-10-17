@extends('layouts.admin')

@section('title', 'Academic Holidays & Non-Working Days | Administration')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="text-slate-500">Academic</span>
                <span>/</span>
                <span class="font-semibold text-slate-700">Holidays & Calendar</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Academic Holidays & Breaks</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Configure official school holidays, festive breaks, and non-working days for attendance validation and calendars.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button type="button"
                    onclick="window.openCreateHolidayModal()"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add School Holiday</span>
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.academic.holidays.index') }}" class="flex items-center gap-3">
            <div class="w-72">
                <label for="filter_year" class="block text-xs font-semibold text-slate-700 mb-1">Academic Session</label>
                <select name="academic_year_id" id="filter_year" onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_current ? '(Current Session)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Holidays Table -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-600 uppercase tracking-wider font-semibold">
                        <th class="px-4 py-3 w-12 text-center">#</th>
                        <th class="px-4 py-3">Holiday / Occasion</th>
                        <th class="px-4 py-3 w-32">Type</th>
                        <th class="px-4 py-3 w-36">Start Date</th>
                        <th class="px-4 py-3 w-36">End Date</th>
                        <th class="px-4 py-3 w-28 text-center">Duration</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3 w-24 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/70">
                    @forelse($holidays as $index => $holiday)
                        @php
                            $durationDays = $holiday->start_date->diffInDays($holiday->end_date) + 1;
                            $typeBadge = match($holiday->type) {
                                'holiday' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'exam_break' => 'bg-sky-50 text-sky-700 border-sky-200',
                                'weekend' => 'bg-slate-100 text-slate-700 border-slate-200',
                                default => 'bg-amber-50 text-amber-700 border-amber-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-4 py-3 text-center text-slate-400 font-mono">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-bold text-slate-900">
                                {{ $holiday->title }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded border px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $typeBadge }}">
                                    {{ ucfirst(str_replace('_', ' ', $holiday->type)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-slate-600">
                                {{ $holiday->start_date->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 font-mono text-slate-600">
                                {{ $holiday->end_date->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 font-bold text-slate-700">
                                    {{ $durationDays }} {{ Str::plural('day', $durationDays) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-500 truncate max-w-xs">
                                {{ $holiday->description ?: '—' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button"
                                            onclick='window.openEditHolidayModal(@json($holiday))'
                                            class="inline-flex items-center p-1.5 rounded-md text-slate-400 hover:text-violet-600 hover:bg-slate-100 transition cursor-pointer"
                                            title="Edit Holiday">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.academic.holidays.destroy', $holiday->id) }}" onsubmit="return confirm('Remove this holiday from the academic calendar?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center p-1.5 rounded-md text-slate-400 hover:text-rose-600 hover:bg-slate-100 transition cursor-pointer"
                                                title="Delete Holiday">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-slate-400">
                                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-600">No holidays scheduled for this session</p>
                                <button type="button" onclick="window.openCreateHolidayModal()" class="mt-2 text-xs font-bold text-violet-600 hover:underline cursor-pointer">
                                    + Add New Holiday
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Create / Edit Holiday Modal -->
<div id="holiday-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 bg-slate-50">
            <h3 id="holiday-modal-title" class="text-sm font-bold text-slate-900">Add Academic Holiday</h3>
            <button type="button" onclick="window.closeHolidayModal()" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" id="holiday-form" action="{{ route('admin.academic.holidays.store') }}" class="p-5 space-y-4">
            @csrf
            <div id="holiday-method-wrapper"></div>

            <!-- Session -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Academic Session *</label>
                <select name="academic_year_id" id="modal_holiday_year" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden" required>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Holiday Title / Occasion *</label>
                <input type="text" name="title" id="modal_holiday_title" placeholder="e.g. Independence Day, Winter Vacation" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden" required>
            </div>

            <!-- Type -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Holiday Category *</label>
                <select name="type" id="modal_holiday_type" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden" required>
                    <option value="holiday">Public / Institutional Holiday</option>
                    <option value="exam_break">Term Exam Preparation Break</option>
                    <option value="weekend">Special Weekend / Non-working</option>
                    <option value="unplanned">Unplanned / Weather Closure</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- Start Date -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Start Date *</label>
                    <input type="date" name="start_date" id="modal_holiday_start" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden" required>
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">End Date *</label>
                    <input type="date" name="end_date" id="modal_holiday_end" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden" required>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Description / Notes</label>
                <textarea name="description" id="modal_holiday_desc" rows="2" placeholder="Optional notes for school records" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" onclick="window.closeHolidayModal()" class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                    Save Holiday
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    window.openCreateHolidayModal = function() {
        var modal = document.getElementById('holiday-modal');
        var form = document.getElementById('holiday-form');
        var title = document.getElementById('holiday-modal-title');
        var methodWrapper = document.getElementById('holiday-method-wrapper');

        title.textContent = 'Add Academic Holiday';
        form.action = '{{ route("admin.academic.holidays.store") }}';
        methodWrapper.innerHTML = '';

        document.getElementById('modal_holiday_title').value = '';
        document.getElementById('modal_holiday_start').value = '';
        document.getElementById('modal_holiday_end').value = '';
        document.getElementById('modal_holiday_desc').value = '';

        modal.classList.remove('hidden');
    };

    window.openEditHolidayModal = function(holiday) {
        var modal = document.getElementById('holiday-modal');
        var form = document.getElementById('holiday-form');
        var title = document.getElementById('holiday-modal-title');
        var methodWrapper = document.getElementById('holiday-method-wrapper');

        title.textContent = 'Edit Academic Holiday';
        form.action = '{{ url("admin/academic/holidays") }}/' + holiday.id;
        methodWrapper.innerHTML = '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('modal_holiday_year').value = holiday.academic_year_id;
        document.getElementById('modal_holiday_title').value = holiday.title;
        document.getElementById('modal_holiday_type').value = holiday.type;
        document.getElementById('modal_holiday_start').value = holiday.start_date.substring(0, 10);
        document.getElementById('modal_holiday_end').value = holiday.end_date.substring(0, 10);
        document.getElementById('modal_holiday_desc').value = holiday.description || '';

        modal.classList.remove('hidden');
    };

    window.closeHolidayModal = function() {
        document.getElementById('holiday-modal').classList.add('hidden');
    };
</script>
@endpush
@endsection
