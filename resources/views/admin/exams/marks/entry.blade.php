@extends('layouts.admin')

@section('title', 'Marks Entry | School Admin')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Breadcrumbs -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.exams.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Exams</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Marks Entry</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Student Marks Entry</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Enter and update subject marks for students.
            </p>
        </div>

        @if($selectedExamSubject)
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.exams.results.index', ['exam_id' => $selectedExamId, 'academic_class_id' => $selectedClassId, 'section_id' => $selectedSectionId]) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition">
                    <span>View Results</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Selection Bar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.exams.marks.entry') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-5 items-end">
            <!-- Exam -->
            <div>
                <label for="exam_id" class="block text-xs font-semibold text-slate-700 mb-1">Exam <span class="text-rose-500">*</span></label>
                <select name="exam_id" id="exam_id" required onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">Select Exam</option>
                    @foreach($exams as $ex)
                        <option value="{{ $ex->id }}" {{ $selectedExamId == $ex->id ? 'selected' : '' }}>
                            {{ $ex->title }} ({{ $ex->status }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Class -->
            <div>
                <label for="academic_class_id" class="block text-xs font-semibold text-slate-700 mb-1">Class <span class="text-rose-500">*</span></label>
                <select name="academic_class_id" id="academic_class_id" required onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">Select Class</option>
                    @foreach($academicClasses as $class)
                        <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Section -->
            <div>
                <label for="section_id" class="block text-xs font-semibold text-slate-700 mb-1">Section <span class="text-rose-500">*</span></label>
                <select name="section_id" id="section_id" required onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">Select Section</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec->id }}" {{ $selectedSectionId == $sec->id ? 'selected' : '' }}>
                            Section {{ $sec->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Subject -->
            <div>
                <label for="subject_id" class="block text-xs font-semibold text-slate-700 mb-1">Subject <span class="text-rose-500">*</span></label>
                <select name="subject_id" id="subject_id" required onchange="this.form.submit()" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    <option value="">Select Subject</option>
                    @foreach($examSubjects as $es)
                        <option value="{{ $es->subject_id }}" {{ $selectedSubjectId == $es->subject_id ? 'selected' : '' }}>
                            {{ $es->subject?->name }} (Full: {{ number_format($es->full_marks, 0) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-slate-800 transition cursor-pointer">
                    Load Students
                </button>
            </div>
        </form>
    </div>

    <!-- Active Selection Summary & Lock Warning -->
    @if($selectedExamSubject)
        @if($isLocked)
            <div class="rounded-xl border border-amber-300 bg-amber-50 p-4 shadow-2xs text-amber-900 flex items-start gap-3">
                <svg class="h-5 w-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <div class="text-xs">
                    <p class="font-bold">Exam Results Published (Locked)</p>
                    <p class="mt-0.5 text-amber-800">
                        Results are published and regular marks entry is locked. Use the "Correct" button on a student's row to modify marks with an audit reason.
                    </p>
                </div>
            </div>
        @endif

        <!-- Marks Entry Table Form -->
        <form method="POST" action="{{ route('admin.exams.marks.store') }}" id="marks-batch-form" class="space-y-4">
            @csrf
            <input type="hidden" name="exam_id" value="{{ $selectedExamId }}">
            <input type="hidden" name="academic_class_id" value="{{ $selectedClassId }}">
            <input type="hidden" name="section_id" value="{{ $selectedSectionId }}">
            <input type="hidden" name="exam_subject_id" value="{{ $selectedExamSubject->id }}">

            <!-- Quick Table Controls & Keyboard Shortcuts Legend -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs bg-slate-100/70 p-3 rounded-lg border border-slate-200">
                <div class="flex items-center gap-3">
                    <span class="font-bold text-slate-800">{{ $selectedExamSubject->subject?->name }} (Code: {{ $selectedExamSubject->subject?->code }})</span>
                    <span class="text-slate-400">|</span>
                    <span class="text-slate-600">Full Marks: <strong class="text-slate-900">{{ number_format($selectedExamSubject->full_marks, 0) }}</strong></span>
                    <span class="text-slate-400">|</span>
                    <span class="text-slate-600">Pass Marks: <strong class="text-slate-900">{{ number_format($selectedExamSubject->pass_marks, 0) }}</strong></span>
                </div>

                <div class="flex items-center gap-2 text-[11px] text-slate-500">
                    <span class="bg-white px-2 py-0.5 rounded border border-slate-200 font-mono text-slate-700">Tab</span> Next
                    <span class="bg-white px-2 py-0.5 rounded border border-slate-200 font-mono text-slate-700">↓ / ↑</span> Move
                    <span class="bg-white px-2 py-0.5 rounded border border-slate-200 font-mono text-slate-700">Ctrl + S</span> Save
                </div>
            </div>

            <!-- Table Matrix -->
            <div class="rounded-xl border border-slate-200 bg-white shadow-2xs overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse" id="marks-entry-table">
                    <thead class="bg-slate-50 text-slate-700 font-semibold uppercase tracking-wider text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="px-3 py-3 w-16 text-center">Roll</th>
                            <th class="px-3 py-3 w-28">Admission No</th>
                            <th class="px-3 py-3 min-w-[160px]">Student Name</th>
                            <th class="px-3 py-3 w-32">Attendance</th>

                            <!-- Dynamic Subject Components Headers -->
                            @foreach($selectedExamSubject->components as $comp)
                                <th class="px-3 py-3 w-28 text-center bg-violet-50/50 border-x border-slate-200">
                                    <div class="font-bold text-violet-900">{{ $comp->name }}</div>
                                    <div class="text-[9px] font-mono text-violet-700 lowercase">Max: {{ number_format($comp->max_marks, 0) }}</div>
                                </th>
                            @endforeach

                            <th class="px-3 py-3 w-24 text-center font-bold">Total</th>
                            <th class="px-3 py-3 w-20 text-center">Grade</th>
                            <th class="px-3 py-3 min-w-[140px]">Remarks</th>
                            @if($isLocked)
                                <th class="px-3 py-3 w-20 text-center">Audit</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($studentRows as $idx => $row)
                            @php
                                $student = $row['student'];
                                $enrollment = $row['enrollment'];
                                $mark = $row['mark'];
                                $status = $mark ? $mark->attendance_status : 'present';
                            @endphp
                            <tr class="student-mark-row hover:bg-slate-50/60 transition" data-row-idx="{{ $idx }}">
                                <input type="hidden" name="marks[{{ $idx }}][student_id]" value="{{ $student->id }}">

                                <!-- Roll Number -->
                                <td class="px-3 py-2 text-center font-mono font-bold text-slate-800">
                                    {{ $enrollment->roll_number ?: '—' }}
                                </td>

                                <!-- Admission SID -->
                                <td class="px-3 py-2 font-mono text-[11px] text-slate-600">
                                    {{ $student->admission_number }}
                                </td>

                                <!-- Student Name -->
                                <td class="px-3 py-2 font-medium text-slate-900 truncate">
                                    {{ $student->full_name }}
                                </td>

                                <!-- Attendance Status -->
                                <td class="px-3 py-2">
                                    <select name="marks[{{ $idx }}][attendance_status]"
                                            onchange="handleAttendanceChange(this, {{ $idx }})"
                                            {{ $isLocked ? 'disabled' : '' }}
                                            class="attendance-select w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
                                        <option value="present" {{ $status === 'present' ? 'selected' : '' }}>Present</option>
                                        <option value="absent" {{ $status === 'absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="exempt" {{ $status === 'exempt' ? 'selected' : '' }}>Exempt</option>
                                        <option value="incomplete" {{ $status === 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                                    </select>
                                </td>

                                <!-- Dynamic Component Inputs -->
                                @foreach($selectedExamSubject->components as $comp)
                                    @php
                                        $cMark = $row['component_marks']->get($comp->id);
                                        $cVal = $cMark ? $cMark->marks_obtained : '';
                                    @endphp
                                    <td class="px-3 py-2 text-center bg-violet-50/20 border-x border-slate-100">
                                        <input type="number"
                                               step="0.5"
                                               min="0"
                                               max="{{ $comp->max_marks }}"
                                               name="marks[{{ $idx }}][components][{{ $comp->id }}]"
                                               value="{{ $cVal }}"
                                               data-max="{{ $comp->max_marks }}"
                                               data-row="{{ $idx }}"
                                               oninput="calculateRowTotal({{ $idx }})"
                                               onkeydown="navigateGrid(event, this)"
                                               {{ ($isLocked || $status !== 'present') ? 'disabled' : '' }}
                                               class="comp-mark-input w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs font-mono font-semibold text-slate-900 text-center focus:border-violet-600 focus:ring-1 focus:ring-violet-500 focus:outline-hidden transition">
                                    </td>
                                @endforeach

                                <!-- Total Marks Display -->
                                <td class="px-3 py-2 text-center font-mono font-bold text-slate-900 row-total-display" id="row-total-{{ $idx }}">
                                    {{ $mark && $mark->marks_obtained !== null ? number_format($mark->marks_obtained, 1) : '—' }}
                                </td>

                                <!-- Letter Grade Preview -->
                                <td class="px-3 py-2 text-center row-grade-display" id="row-grade-{{ $idx }}">
                                    @if($mark && $mark->marks_obtained !== null)
                                        <span class="inline-flex items-center justify-center min-w-[24px] px-1.5 py-0.5 rounded text-[11px] font-bold {{ $mark->is_passed ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                            {{ $mark->gradeTier?->grade ?: ($mark->is_passed ? 'Pass' : 'F') }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-[11px]">—</span>
                                    @endif
                                </td>

                                <!-- Remarks -->
                                <td class="px-3 py-2">
                                    <input type="text"
                                           name="marks[{{ $idx }}][remarks]"
                                           value="{{ $mark?->remarks }}"
                                           placeholder="Notes..."
                                           {{ $isLocked ? 'disabled' : '' }}
                                           class="w-full rounded border border-slate-200 bg-white px-2 py-1 text-xs text-slate-800 placeholder:text-slate-300 focus:border-violet-600 focus:outline-hidden">
                                </td>

                                @if($isLocked)
                                    <td class="px-3 py-2 text-center">
                                        @if($mark)
                                            <button type="button"
                                                    onclick="openCorrectionModal({{ $mark->id }}, '{{ $student->full_name }}', {{ $mark->marks_obtained ?? 0 }}, '{{ $mark->attendance_status }}')"
                                                    class="text-xs font-semibold text-violet-600 hover:text-violet-800 cursor-pointer">
                                                Correct
                                            </button>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 7 + count($selectedExamSubject->components) }}" class="p-8 text-center text-slate-500">
                                    No active enrolled students found in this class and section.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(!$isLocked && $studentRows->isNotEmpty())
                <div class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-white shadow-2xs">
                    <div class="text-xs text-slate-500">
                        Total Students: <strong class="text-slate-800">{{ $studentRows->count() }}</strong>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-6 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-violet-700 transition cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Save Marks</span>
                        </button>
                    </div>
                </div>
            @endif
        </form>

        <!-- Correction Modal (for locked/published exams) -->
        <div id="correction-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-xl max-w-md w-full space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Update Student Mark</h3>
                    <button type="button" onclick="closeCorrectionModal()" class="text-slate-400 hover:text-slate-600">×</button>
                </div>

                <form id="correction-form" method="POST" action="" class="space-y-4">
                    @csrf
                    <div>
                        <p class="text-xs text-slate-500">Student: <strong id="modal-student-name" class="text-slate-800"></strong></p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Attendance Status</label>
                        <select name="attendance_status" id="modal-status" class="w-full rounded border border-slate-200 px-3 py-1.5 text-xs">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="exempt">Exempt</option>
                            <option value="incomplete">Incomplete</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Total Marks</label>
                        <input type="number" step="0.5" min="0" name="new_marks" id="modal-marks" required class="w-full rounded border border-slate-200 px-3 py-1.5 text-xs font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Reason <span class="text-rose-500">*</span></label>
                        <textarea name="reason" required rows="3" placeholder="Explain the reason for this mark update..." class="w-full rounded border border-slate-200 px-3 py-1.5 text-xs text-slate-800"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" onclick="closeCorrectionModal()" class="px-3 py-1.5 rounded border border-slate-200 text-xs text-slate-600 hover:bg-slate-50">Cancel</button>
                        <button type="submit" class="px-4 py-1.5 rounded bg-violet-600 text-xs font-bold text-white hover:bg-violet-700">Update Mark</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-violet-50 text-violet-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="mt-3 text-sm font-bold text-slate-900">Select Exam, Class, Section, and Subject</h3>
            <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                Select an exam, class, section, and subject above to enter marks.
            </p>
        </div>
    @endif

</div>

<script>
    function calculateRowTotal(rowIdx) {
        const row = document.querySelector(`tr[data-row-idx="${rowIdx}"]`);
        if (!row) return;

        const inputs = row.querySelectorAll('.comp-mark-input');
        let total = 0;
        let hasAnyValue = false;

        inputs.forEach(input => {
            const val = parseFloat(input.value);
            const max = parseFloat(input.getAttribute('data-max')) || 100;

            if (!isNaN(val)) {
                hasAnyValue = true;
                if (val > max) {
                    input.classList.add('border-rose-500', 'bg-rose-50');
                } else {
                    input.classList.remove('border-rose-500', 'bg-rose-50');
                }
                total += val;
            }
        });

        const totalCell = document.getElementById(`row-total-${rowIdx}`);
        const gradeCell = document.getElementById(`row-grade-${rowIdx}`);

        if (totalCell) {
            totalCell.textContent = hasAnyValue ? total.toFixed(1) : '—';
        }

        // Live preview grade indicator
        if (gradeCell && hasAnyValue) {
            const passMarks = {{ $selectedExamSubject ? (float) $selectedExamSubject->pass_marks : 33 }};
            const isPass = total >= passMarks;
            gradeCell.innerHTML = `<span class="inline-flex items-center justify-center min-w-[24px] px-1.5 py-0.5 rounded text-[11px] font-bold ${isPass ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200'}">${isPass ? 'Pass' : 'F'}</span>`;
        }
    }

    function handleAttendanceChange(select, rowIdx) {
        const row = document.querySelector(`tr[data-row-idx="${rowIdx}"]`);
        if (!row) return;

        const inputs = row.querySelectorAll('.comp-mark-input');
        const isPresent = select.value === 'present';

        inputs.forEach(input => {
            input.disabled = !isPresent;
            if (!isPresent) {
                input.value = '';
                input.classList.remove('border-rose-500', 'bg-rose-50');
            }
        });

        const totalCell = document.getElementById(`row-total-${rowIdx}`);
        const gradeCell = document.getElementById(`row-grade-${rowIdx}`);

        if (!isPresent) {
            if (totalCell) totalCell.textContent = '—';
            if (gradeCell) {
                const label = select.value.toUpperCase();
                gradeCell.innerHTML = `<span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-slate-100 text-slate-600">${label}</span>`;
            }
        } else {
            calculateRowTotal(rowIdx);
        }
    }

    // Grid Keyboard Navigation: Arrow Up / Down in column, Tab across
    function navigateGrid(e, currentInput) {
        const currentCell = currentInput.closest('td');
        const currentRow = currentInput.closest('tr');
        const colIdx = Array.from(currentRow.children).indexOf(currentCell);

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const nextRow = currentRow.nextElementSibling;
            if (nextRow) {
                const targetInput = nextRow.children[colIdx]?.querySelector('.comp-mark-input');
                if (targetInput && !targetInput.disabled) targetInput.focus();
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const prevRow = currentRow.previousElementSibling;
            if (prevRow) {
                const targetInput = prevRow.children[colIdx]?.querySelector('.comp-mark-input');
                if (targetInput && !targetInput.disabled) targetInput.focus();
            }
        }
    }

    // Hotkey: Ctrl + S to save
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            const form = document.getElementById('marks-batch-form');
            if (form) {
                e.preventDefault();
                form.submit();
            }
        }
    });

    // Correction Modal Helpers
    function openCorrectionModal(markId, studentName, currentMarks, status) {
        const modal = document.getElementById('correction-modal');
        const form = document.getElementById('correction-form');
        document.getElementById('modal-student-name').textContent = studentName;
        document.getElementById('modal-marks').value = currentMarks;
        document.getElementById('modal-status').value = status;
        form.action = `/admin/exams/marks/${markId}/correct`;
        modal.classList.remove('hidden');
    }

    function closeCorrectionModal() {
        document.getElementById('correction-modal').classList.add('hidden');
    }
</script>
@endsection
