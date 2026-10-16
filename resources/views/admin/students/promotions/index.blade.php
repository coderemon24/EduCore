@extends('layouts.admin')

@section('title', 'Bulk Class Promotions | Student Management')

@section('admin-content')
<div class="space-y-6 max-w-6xl mx-auto">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.students.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Students</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Bulk Promotions</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Bulk Class Promotion</h1>
            <p class="text-xs text-slate-500 mt-0.5">Advance entire cohorts of students to the next academic session in a single transaction.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.students.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Directory</span>
            </a>
        </div>
    </div>

    <!-- Bulk Promotion Form -->
    <form method="POST"
          action="{{ route('admin.students.promotions.store') }}"
          id="bulk-promote-form"
          class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            <!-- Panel 1: Source Cohort Selection -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-700 text-[10px] text-white">1</span>
                        <span>Source Class Cohort</span>
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-0.5 ml-7">Select the origin session and grade level to load currently enrolled students.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Source Session -->
                    <div>
                        <label for="source_year_id" class="block text-[11px] font-semibold text-slate-600 mb-1">Source Session</label>
                        <select id="source_year_id"
                                class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-2.5 py-1.5 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                            <option value="">-- Select Session --</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}">
                                    {{ $year->name }} {{ $year->is_current ? '• Current' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Source Class -->
                    <div>
                        <label for="source_class_id" class="block text-[11px] font-semibold text-slate-600 mb-1">Source Class</label>
                        <select id="source_class_id"
                                class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-2.5 py-1.5 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                            <option value="">-- Select Class --</option>
                            @foreach ($academicClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Source Section -->
                    <div>
                        <label for="source_section_id" class="block text-[11px] font-semibold text-slate-600 mb-1">Source Section</label>
                        <select id="source_section_id"
                                class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-2.5 py-1.5 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                            <option value="">All Sections</option>
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button"
                            id="btn-fetch-roster"
                            onclick="window.fetchRoster()"
                            class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer">
                        <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Load Enrolled Students</span>
                    </button>
                </div>
            </div>

            <!-- Panel 2: Target Promotion Placement -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 flex items-center gap-2">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-violet-600 text-[10px] text-white">2</span>
                        <span>Target Destination Placement</span>
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-0.5 ml-7">Target session, next grade level, and section assignment for promoted students.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Target Session -->
                    <div>
                        <label for="target_academic_year_id" class="block text-[11px] font-semibold text-slate-600 mb-1">
                            Target Session <span class="text-rose-500">*</span>
                        </label>
                        <select id="target_academic_year_id"
                                name="target_academic_year_id"
                                required
                                class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-2.5 py-1.5 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                            <option value="">-- Target Session --</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}">
                                    {{ $year->name }} {{ $year->is_current ? '• Current' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Target Class -->
                    <div>
                        <label for="target_academic_class_id" class="block text-[11px] font-semibold text-slate-600 mb-1">
                            Target Class <span class="text-rose-500">*</span>
                        </label>
                        <select id="target_academic_class_id"
                                name="target_academic_class_id"
                                data-academic-class-select
                                required
                                class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-2.5 py-1.5 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                            <option value="">-- Target Class --</option>
                            @foreach ($academicClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Target Section -->
                    <div>
                        <label for="target_section_id" class="block text-[11px] font-semibold text-slate-600 mb-1">Target Section</label>
                        <select id="target_section_id"
                                name="target_section_id"
                                data-dependent-sections
                                class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-2.5 py-1.5 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                            <option value="">-- Optional Section --</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="remarks" class="block text-[11px] font-semibold text-slate-600 mb-1">Promotion Notes / Audit Remarks</label>
                    <input type="text"
                           id="remarks"
                           name="remarks"
                           value="{{ old('remarks', 'Batch academic cohort promotion.') }}"
                           placeholder="Audit log notes for new enrollment rows"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-1.5 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>
            </div>

        </div>

        <!-- Student Selection Checklist Card -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 flex-wrap gap-2">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900">Student Roster Checklist</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Select individual students to promote. Deselect repeating or transferring students.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span id="selected-count-badge" class="inline-flex items-center rounded-full bg-violet-100 px-2.5 py-0.5 text-xs font-bold text-violet-800">
                        0 selected
                    </span>
                    <button type="button"
                            onclick="window.toggleSelectAll(true)"
                            class="text-xs font-semibold text-violet-600 hover:text-violet-800 cursor-pointer">
                        Select All
                    </button>
                    <span class="text-slate-300">|</span>
                    <button type="button"
                            onclick="window.toggleSelectAll(false)"
                            class="text-xs font-semibold text-slate-500 hover:text-slate-700 cursor-pointer">
                        Deselect All
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="roster-table">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-bold uppercase text-slate-500">
                            <th class="py-2.5 pl-4 pr-3 w-10">
                                <input type="checkbox" id="check-all" onchange="window.toggleSelectAll(this.checked)" class="rounded border-slate-300 text-violet-600 focus:ring-violet-500 cursor-pointer">
                            </th>
                            <th class="px-3 py-2.5">Admission No</th>
                            <th class="px-3 py-2.5">Student Full Name</th>
                            <th class="px-3 py-2.5">Gender</th>
                            <th class="px-3 py-2.5">Current Roll</th>
                            <th class="px-3 py-2.5 text-right pr-4">Status</th>
                        </tr>
                    </thead>
                    <tbody id="roster-tbody" class="divide-y divide-slate-100 text-xs text-slate-700">
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400 italic">
                                Select source session and class, then click "Load Enrolled Students" above.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <span class="text-xs text-slate-500" id="submit-summary-text">
                Ready to execute promotion once students are selected.
            </span>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.students.index') }}"
                   class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </a>
                <button type="button"
                        id="btn-submit-bulk"
                        onclick="window.confirmBulkPromotion()"
                        class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    <span>Execute Bulk Promotion</span>
                </button>
            </div>
        </div>
    </form>

</div>

@push('scripts')
<script>
    // Source class change -> load sections for source
    document.getElementById('source_class_id').addEventListener('change', function() {
        var classId = this.value;
        var sectionSelect = document.getElementById('source_section_id');
        if (!classId) {
            sectionSelect.innerHTML = '<option value="">All Sections</option>';
            if (window.jQuery) $(sectionSelect).trigger('change');
            return;
        }

        fetch('/admin/academic/classes/' + classId + '/sections', {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(sections => {
            var html = '<option value="">All Sections</option>';
            sections.forEach(function(s) {
                html += '<option value="' + s.id + '">' + s.name + '</option>';
            });
            sectionSelect.innerHTML = html;
            if (window.jQuery) $(sectionSelect).trigger('change');
        });
    });

    // Fetch roster AJAX
    window.fetchRoster = function() {
        var yearId = document.getElementById('source_year_id').value;
        var classId = document.getElementById('source_class_id').value;
        var sectionId = document.getElementById('source_section_id').value;
        var tbody = document.getElementById('roster-tbody');
        var btn = document.getElementById('btn-fetch-roster');

        if (!yearId || !classId) {
            if (window.Swal) {
                Swal.fire({
                    title: 'Selection Required',
                    text: 'Please select both Source Session and Source Class first.',
                    icon: 'warning'
                });
            } else {
                alert('Please select both Source Session and Source Class first.');
            }
            return;
        }

        tbody.innerHTML = '<tr><td colspan="6" class="py-8 text-center text-slate-500"><svg class="animate-spin h-5 w-5 mx-auto text-violet-600 mb-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>Loading cohort students...</td></tr>';

        var url = new URL('{{ route('admin.students.promotions.roster') }}', window.location.origin);
        url.searchParams.append('academic_year_id', yearId);
        url.searchParams.append('academic_class_id', classId);
        if (sectionId) url.searchParams.append('section_id', sectionId);

        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(students => {
                if (!Array.isArray(students) || students.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="py-8 text-center text-slate-400 italic">No currently enrolled students found in this source cohort.</td></tr>';
                    window.updateSelectedCount();
                    return;
                }

                var html = '';
                students.forEach(function(s) {
                    html += '<tr class="transition hover:bg-slate-50">';
                    html += '  <td class="py-2.5 pl-4 pr-3">';
                    html += '    <input type="checkbox" name="student_ids[]" value="' + s.id + '" checked onchange="window.updateSelectedCount()" class="student-checkbox rounded border-slate-300 text-violet-600 focus:ring-violet-500 cursor-pointer">';
                    html += '  </td>';
                    html += '  <td class="px-3 py-2.5 font-mono font-semibold text-slate-800">' + s.admission_number + '</td>';
                    html += '  <td class="px-3 py-2.5 font-semibold text-slate-900">' + s.name + '</td>';
                    html += '  <td class="px-3 py-2.5">' + s.gender + '</td>';
                    html += '  <td class="px-3 py-2.5 font-mono">#' + s.roll_number + '</td>';
                    html += '  <td class="px-3 py-2.5 text-right pr-4"><span class="inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700">' + s.status + '</span></td>';
                    html += '</tr>';
                });

                tbody.innerHTML = html;
                document.getElementById('check-all').checked = true;
                window.updateSelectedCount();
            })
            .catch(err => {
                tbody.innerHTML = '<tr><td colspan="6" class="py-8 text-center text-rose-500">Error loading cohort students.</td></tr>';
            });
    };

    window.toggleSelectAll = function(checked) {
        var checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(function(cb) {
            cb.checked = checked;
        });
        document.getElementById('check-all').checked = checked;
        window.updateSelectedCount();
    };

    window.updateSelectedCount = function() {
        var selected = document.querySelectorAll('.student-checkbox:checked').length;
        document.getElementById('selected-count-badge').textContent = selected + ' selected';
        document.getElementById('submit-summary-text').textContent = selected + ' student(s) selected for bulk promotion.';
    };

    window.confirmBulkPromotion = function() {
        var selected = document.querySelectorAll('.student-checkbox:checked').length;
        var targetYear = document.getElementById('target_academic_year_id').value;
        var targetClass = document.getElementById('target_academic_class_id').value;

        if (selected === 0) {
            if (window.Swal) Swal.fire({ title: 'No Students Selected', text: 'Please check at least one student from the roster checklist.', icon: 'warning' });
            else alert('Please check at least one student from the roster checklist.');
            return;
        }

        if (!targetYear || !targetClass) {
            if (window.Swal) Swal.fire({ title: 'Destination Placement Required', text: 'Please select both Target Session and Target Class.', icon: 'warning' });
            else alert('Please select both Target Session and Target Class.');
            return;
        }

        if (window.Swal) {
            Swal.fire({
                title: 'Execute Bulk Promotion?',
                text: 'This will promote ' + selected + ' student(s) into the selected target session and grade level.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#7c3aed',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, execute promotion'
            }).then((res) => {
                if (res.isConfirmed) {
                    document.getElementById('bulk-promote-form').submit();
                }
            });
        } else if (confirm('Promote ' + selected + ' students?')) {
            document.getElementById('bulk-promote-form').submit();
        }
    };
</script>
@endpush
@endsection
