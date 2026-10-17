@extends('layouts.admin')

@section('title', 'Weekly Timetables & Class Schedules | Administration')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Mode Switcher -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="text-slate-500">Academic</span>
                <span>/</span>
                <span class="font-semibold text-slate-700">Timetables & Schedules</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Class Timetables & Schedules</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Manage weekly period schedules, classroom allocations, teacher assignments, and resolve timetable conflicts.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer print:hidden">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Print Schedule</span>
            </button>

            <button type="button"
                    onclick="window.openCreateSlotModal()"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer print:hidden">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Schedule Period</span>
            </button>
        </div>
    </div>

    <!-- Conflict Validation Alert Banner -->
    @if($errors->has('conflict'))
    <div class="rounded-xl border border-rose-300 bg-rose-50 p-4 shadow-2xs">
        <div class="flex items-start gap-3">
            <svg class="h-5 w-5 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="flex-1 text-xs">
                <p class="font-bold text-rose-900">Timetable Conflict Detected</p>
                <p class="text-rose-800 mt-0.5">{{ $errors->first('conflict') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Mode Selector & Filter Bar -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs print:hidden">
        <!-- Mode Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-3 mb-4">
            <a href="{{ route('admin.timetables.index', ['view_mode' => 'section', 'academic_year_id' => $selectedYearId, 'academic_class_id' => $selectedClassId, 'section_id' => $selectedSectionId]) }}"
               class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-bold transition cursor-pointer {{ $viewMode === 'section' ? 'bg-violet-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Section Weekly Schedule</span>
            </a>

            <a href="{{ route('admin.timetables.index', ['view_mode' => 'teacher', 'academic_year_id' => $selectedYearId, 'teacher_id' => $selectedTeacherId]) }}"
               class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-bold transition cursor-pointer {{ $viewMode === 'teacher' ? 'bg-violet-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Teacher / Faculty Schedule</span>
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.timetables.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-5 items-end">
            <input type="hidden" name="view_mode" value="{{ $viewMode }}">

            <!-- Academic Session -->
            <div>
                <label for="tt_academic_year" class="block text-xs font-semibold text-slate-700 mb-1">Academic Session</label>
                <select name="academic_year_id" id="tt_academic_year" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }} {{ $year->is_current ? '(Current)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($viewMode === 'section')
                <!-- Class Selection -->
                <div>
                    <label for="tt_class" class="block text-xs font-semibold text-slate-700 mb-1">Class / Grade</label>
                    <select name="academic_class_id" id="tt_class" onchange="window.handleTimetableClassChange(this.value)" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Section Selection -->
                <div>
                    <label for="tt_section" class="block text-xs font-semibold text-slate-700 mb-1">Section</label>
                    <select name="section_id" id="tt_section" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition" required>
                        <option value="">-- Select Section --</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" {{ $selectedSectionId == $sec->id ? 'selected' : '' }}>
                                {{ $sec->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <!-- Teacher Selection -->
                <div class="sm:col-span-2">
                    <label for="tt_teacher" class="block text-xs font-semibold text-slate-700 mb-1">Teaching Faculty Member</label>
                    <select name="teacher_id" id="tt_teacher" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition" required>
                        <option value="">-- Select Faculty / Teacher --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}" {{ $selectedTeacherId == $t->id ? 'selected' : '' }}>
                                {{ $t->full_name }} ({{ $t->designation ?? 'Teacher' }} • {{ $t->department ?? 'General' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Submit Filter -->
            <div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-slate-800 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span>Load Matrix</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Timetable Weekly Matrix Grid -->
    @if(($viewMode === 'section' && !$selectedSectionId) || ($viewMode === 'teacher' && !$selectedTeacherId))
        <div class="rounded-xl border border-slate-200 bg-white p-8 text-center shadow-2xs">
            <h3 class="text-sm font-bold text-slate-900">
                {{ $viewMode === 'section' ? 'Select Class & Section to View Schedule' : 'Select Faculty Member to View Workload' }}
            </h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                Choose an option from the filter above, or click <strong>Add Schedule Period</strong> to register a new class slot.
            </p>
        </div>
    @else
        <!-- Schedule Columns: Saturday to Thursday -->
        @php
            $displayDays = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
            if (($slotsGrouped['friday'] ?? collect())->isNotEmpty()) {
                $displayDays[] = 'friday';
            }
            $gridColsClass = count($displayDays) > 6 ? 'lg:grid-cols-7' : 'lg:grid-cols-6';
        @endphp
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 {{ $gridColsClass }} items-start">
            @foreach($displayDays as $dayKey)
                @php
                    $daySlots = $slotsGrouped[$dayKey] ?? collect();
                    $dayTitle = $daysOfWeek[$dayKey];
                @endphp
                <div class="rounded-xl border border-slate-200 bg-white shadow-2xs flex flex-col overflow-hidden min-h-[350px]">
                    <!-- Column Day Header -->
                    <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-3 py-2.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-800">{{ $dayTitle }}</span>
                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white text-[10px] font-bold text-slate-600 border border-slate-200">
                            {{ $daySlots->count() }}
                        </span>
                    </div>

                    <!-- Column Slots List -->
                    <div class="p-2 space-y-2 flex-1">
                        @forelse($daySlots as $slot)
                            @php
                                $typeBadgeColor = match($slot->type) {
                                    'lab' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'practical' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'break' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                                };
                            @endphp
                            <div class="group relative rounded-lg border border-slate-200 bg-white p-3 shadow-2xs hover:border-violet-300 hover:shadow-xs transition">
                                <!-- Time & Type Tag -->
                                <div class="flex items-center justify-between text-[10px] mb-1.5">
                                    <span class="font-bold text-slate-900 font-mono">
                                        {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}
                                    </span>
                                    <span class="inline-flex rounded border px-1.5 py-0.2 uppercase text-[9px] font-bold {{ $typeBadgeColor }}">
                                        {{ $slot->type }}
                                    </span>
                                </div>

                                <!-- Subject Title -->
                                <h4 class="text-xs font-bold text-slate-900 truncate">
                                    {{ $slot->subject?->name ?? 'Subject' }}
                                </h4>

                                <!-- Teacher or Section info -->
                                <div class="mt-2 space-y-1 text-[11px] text-slate-600">
                                    @if($viewMode === 'section')
                                        <div class="flex items-center gap-1.5 truncate">
                                            <svg class="h-3.5 w-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="truncate font-medium">{{ $slot->teacher?->full_name ?? 'Unassigned' }}</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 truncate">
                                            <svg class="h-3.5 w-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <span class="truncate font-medium">{{ $slot->academicClass?->name }} ({{ $slot->section?->name }})</span>
                                        </div>
                                    @endif

                                    @if($slot->room_number)
                                        <div class="flex items-center gap-1.5 text-[10px] text-slate-500">
                                            <svg class="h-3 w-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <span>Room: {{ $slot->room_number }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons on Hover -->
                                <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-end gap-1 print:hidden">
                                    <button type="button"
                                            onclick='window.openEditSlotModal(@json($slot))'
                                            class="inline-flex items-center p-1 rounded text-slate-400 hover:text-violet-600 hover:bg-slate-100 transition cursor-pointer"
                                            title="Edit Slot">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.timetables.destroy', $slot->id) }}" onsubmit="return confirm('Remove this timetable slot?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center p-1 rounded text-slate-400 hover:text-rose-600 hover:bg-slate-100 transition cursor-pointer"
                                                title="Delete Slot">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="h-32 rounded-lg border border-dashed border-slate-200 flex flex-col items-center justify-center p-3 text-center">
                                <span class="text-[10px] text-slate-400">No classes scheduled</span>
                                <button type="button"
                                        onclick="window.openCreateSlotModalForDay('{{ $dayKey }}')"
                                        class="mt-2 text-[10px] font-semibold text-violet-600 hover:underline print:hidden cursor-pointer">
                                    + Add Period
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

<!-- Schedule Period Creation & Editing Modal -->
<div id="slot-modal" class="fixed inset-0 z-50 hidden bg-slate-900/60 flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 bg-slate-50">
            <h3 id="modal-title" class="text-sm font-bold text-slate-900">Schedule Timetable Period</h3>
            <button type="button" onclick="window.closeSlotModal()" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition cursor-pointer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Form -->
        <form method="POST" id="slot-form" action="{{ route('admin.timetables.store') }}" class="p-5 space-y-4">
            @csrf
            <div id="method-field-wrapper"></div>

            <input type="hidden" name="academic_year_id" id="modal_year_id" value="{{ $selectedYearId }}">

            <div class="grid grid-cols-2 gap-3">
                <!-- Class -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Class / Grade *</label>
                    <select name="academic_class_id" id="modal_class_id" onchange="window.handleModalClassChange(this.value)" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden" required>
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Section -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Section *</label>
                    <select name="section_id" id="modal_section_id" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden" required>
                        <option value="">-- Select Section --</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" {{ $selectedSectionId == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- Day of Week -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Day of Week *</label>
                    <select name="day_of_week" id="modal_day" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden" required>
                        @foreach($daysOfWeek as $k => $label)
                            <option value="{{ $k }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Period Type *</label>
                    <select name="type" id="modal_type" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden" required>
                        <option value="regular">Regular Lecture</option>
                        <option value="lab">Science / Computer Lab</option>
                        <option value="practical">Practical / Activity</option>
                        <option value="break">Recess / Break</option>
                    </select>
                </div>
            </div>

            <!-- Preset Helper Quick-Buttons -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Period Time Presets:</label>
                <div class="flex items-center gap-1.5 flex-wrap text-[10px]">
                    <button type="button" onclick="window.applyTimePreset('08:30', '09:15')" class="rounded border border-slate-200 bg-slate-50 px-2 py-0.5 font-mono text-slate-700 hover:bg-violet-50 hover:border-violet-300">P1 08:30-09:15</button>
                    <button type="button" onclick="window.applyTimePreset('09:15', '10:00')" class="rounded border border-slate-200 bg-slate-50 px-2 py-0.5 font-mono text-slate-700 hover:bg-violet-50 hover:border-violet-300">P2 09:15-10:00</button>
                    <button type="button" onclick="window.applyTimePreset('10:15', '11:00')" class="rounded border border-slate-200 bg-slate-50 px-2 py-0.5 font-mono text-slate-700 hover:bg-violet-50 hover:border-violet-300">P3 10:15-11:00</button>
                    <button type="button" onclick="window.applyTimePreset('11:00', '11:45')" class="rounded border border-slate-200 bg-slate-50 px-2 py-0.5 font-mono text-slate-700 hover:bg-violet-50 hover:border-violet-300">P4 11:00-11:45</button>
                    <button type="button" onclick="window.applyTimePreset('12:30', '13:15')" class="rounded border border-slate-200 bg-slate-50 px-2 py-0.5 font-mono text-slate-700 hover:bg-violet-50 hover:border-violet-300">P5 12:30-13:15</button>
                    <button type="button" onclick="window.applyTimePreset('13:15', '14:00')" class="rounded border border-slate-200 bg-slate-50 px-2 py-0.5 font-mono text-slate-700 hover:bg-violet-50 hover:border-violet-300">P6 13:15-14:00</button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- Start Time -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Start Time *</label>
                    <input type="time" name="start_time" id="modal_start_time" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden" required>
                </div>

                <!-- End Time -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">End Time *</label>
                    <input type="time" name="end_time" id="modal_end_time" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden" required>
                </div>
            </div>

            <!-- Subject -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Subject *</label>
                <select name="subject_id" id="modal_subject_id" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden" required>
                    <option value="">-- Select Curriculum Subject --</option>
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- Teacher -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Assigned Teacher</label>
                    <select name="teacher_id" id="modal_teacher_id" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
                        <option value="">-- No Teacher Assigned --</option>
                        @foreach($teachers as $teach)
                            <option value="{{ $teach->id }}">{{ $teach->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Room Number -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Room / Classroom</label>
                    <input type="text" name="room_number" id="modal_room" placeholder="e.g. Room 102, Lab 3" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" onclick="window.closeSlotModal()" class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                    Save Schedule Slot
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    window.handleTimetableClassChange = function(classId) {
        var sectionSelect = document.getElementById('tt_section');
        if (!sectionSelect) return;

        sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';
        if (!classId) return;

        fetch('{{ url("admin/academic/classes") }}/' + classId + '/sections', {
            headers: { 'Accept': 'application/json' }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            var sections = Array.isArray(data) ? data : (data.sections || []);
            sections.forEach(function(sec) {
                var opt = document.createElement('option');
                opt.value = sec.id;
                opt.textContent = sec.name;
                sectionSelect.appendChild(opt);
            });
        });
    };

    window.handleModalClassChange = function(classId) {
        var sectionSelect = document.getElementById('modal_section_id');
        if (!sectionSelect) return;

        sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';
        if (!classId) return;

        fetch('{{ url("admin/academic/classes") }}/' + classId + '/sections', {
            headers: { 'Accept': 'application/json' }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            var sections = Array.isArray(data) ? data : (data.sections || []);
            sections.forEach(function(sec) {
                var opt = document.createElement('option');
                opt.value = sec.id;
                opt.textContent = sec.name;
                sectionSelect.appendChild(opt);
            });
        });
    };

    window.applyTimePreset = function(start, end) {
        document.getElementById('modal_start_time').value = start;
        document.getElementById('modal_end_time').value = end;
    };

    window.openCreateSlotModal = function() {
        var modal = document.getElementById('slot-modal');
        var form = document.getElementById('slot-form');
        var title = document.getElementById('modal-title');
        var methodWrapper = document.getElementById('method-field-wrapper');

        title.textContent = 'Schedule Timetable Period';
        form.action = '{{ route("admin.timetables.store") }}';
        methodWrapper.innerHTML = '';

        document.getElementById('modal_start_time').value = '';
        document.getElementById('modal_end_time').value = '';
        document.getElementById('modal_room').value = '';

        modal.classList.remove('hidden');
    };

    window.openCreateSlotModalForDay = function(day) {
        window.openCreateSlotModal();
        document.getElementById('modal_day').value = day;
    };

    window.openEditSlotModal = function(slot) {
        var modal = document.getElementById('slot-modal');
        var form = document.getElementById('slot-form');
        var title = document.getElementById('modal-title');
        var methodWrapper = document.getElementById('method-field-wrapper');

        title.textContent = 'Edit Timetable Period';
        form.action = '{{ url("admin/timetables") }}/' + slot.id;
        methodWrapper.innerHTML = '<input type="hidden" name="_method" value="PUT">';

        document.getElementById('modal_class_id').value = slot.academic_class_id;
        document.getElementById('modal_day').value = slot.day_of_week;
        document.getElementById('modal_type').value = slot.type;
        document.getElementById('modal_start_time').value = slot.start_time.substring(0, 5);
        document.getElementById('modal_end_time').value = slot.end_time.substring(0, 5);
        document.getElementById('modal_subject_id').value = slot.subject_id;
        document.getElementById('modal_teacher_id').value = slot.teacher_id || '';
        document.getElementById('modal_room').value = slot.room_number || '';

        // If sections for this class need updating
        window.handleModalClassChange(slot.academic_class_id);
        setTimeout(function() {
            document.getElementById('modal_section_id').value = slot.section_id;
        }, 300);

        modal.classList.remove('hidden');
    };

    window.closeSlotModal = function() {
        document.getElementById('slot-modal').classList.add('hidden');
    };
</script>
@endpush
@endsection
