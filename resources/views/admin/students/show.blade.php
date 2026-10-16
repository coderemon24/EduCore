@extends('layouts.admin')

@section('title', $student->full_name . ' | Student Profile')

@section('admin-content')
<div class="space-y-6 max-w-6xl mx-auto">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.students.index', $student->trashed() ? ['status' => 'trashed'] : []) }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Students</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">{{ $student->full_name }}</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Student Profile & Academic Record</h1>
            <p class="text-xs text-slate-500 mt-0.5">Comprehensive identity overview, family guardians, and chronological enrollment timeline.</p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('admin.students.index', $student->trashed() ? ['status' => 'trashed'] : []) }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Directory</span>
            </a>

            @if ($student->trashed())
                <!-- Restore Button -->
                <button type="button"
                        id="btn-restore-show"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-emerald-700 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Restore Student</span>
                </button>

                <!-- Permanent Purge Button -->
                <button type="button"
                        id="btn-purge-show"
                        class="inline-flex items-center gap-2 rounded-lg bg-rose-700 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-rose-800 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Permanently Purge</span>
                </button>
            @else
                <a href="{{ route('admin.students.promote', $student) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-blue-700 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    <span>Promote / Transfer</span>
                </a>
                <a href="{{ route('admin.students.edit', $student) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Edit Profile</span>
                </a>
                <button type="button"
                        id="btn-archive-show"
                        class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-2 text-xs font-semibold text-rose-700 shadow-2xs hover:bg-rose-100 transition cursor-pointer">
                    <svg class="h-4 w-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Move to Trash</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Trashed Archive Context Banner -->
    @if ($student->trashed())
        <div class="rounded-xl border border-rose-200 bg-rose-50/70 p-4 text-xs text-rose-800 flex items-center gap-3 shadow-2xs">
            <div class="h-8 w-8 rounded-lg bg-rose-100 flex items-center justify-center text-rose-700 shrink-0">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-rose-900">This student record is currently Archived / Trashed</h4>
                <p class="text-rose-700 text-[11px] mt-0.5">Soft-deleted on {{ $student->deleted_at->format('M d, Y h:i A') }}. You can restore it to the active roster or permanently purge it.</p>
            </div>
        </div>
    @endif

    <!-- Student Hero Badge Banner Card -->
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <img src="{{ $student->photo_url }}"
                     alt="{{ $student->full_name }}"
                     class="h-20 w-20 rounded-xl object-cover border-2 border-slate-100 shadow-xs bg-slate-50 shrink-0">
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-xl font-bold text-slate-900">{{ $student->full_name }}</h2>
                        @if ($student->trashed())
                            <span class="inline-flex items-center gap-1 rounded-full border border-rose-200 bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-700 uppercase tracking-wider">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                Trashed
                            </span>
                        @elseif ($student->status === 'active')
                            <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 uppercase tracking-wider">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600 uppercase tracking-wider">
                                {{ ucfirst($student->status) }}
                            </span>
                        @endif
                    </div>

                    <div class="mt-2 flex items-center gap-3 text-xs text-slate-600 flex-wrap">
                        <span class="inline-flex items-center gap-1 font-mono font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                            Adm No: {{ $student->admission_number }}
                        </span>
                        @php
                            $curr = $student->currentEnrollment;
                        @endphp
                        @if ($curr && $curr->academicClass)
                            <span class="inline-flex items-center rounded-md border border-violet-100 bg-violet-50 px-2 py-0.5 font-semibold text-violet-700">
                                {{ $curr->academicClass->name }} {{ $curr->section ? '• Section '.$curr->section->name : '' }}
                            </span>
                            @if ($curr->roll_number)
                                <span class="font-mono text-slate-500">
                                    Roll: #{{ $curr->roll_number }}
                                </span>
                            @endif
                        @endif
                        <span class="text-slate-400">|</span>
                        <span>Admitted: {{ $student->admission_date ? $student->admission_date->format('M d, Y') : '—' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Communication Pills -->
            <div class="flex flex-col sm:items-end gap-1.5 text-xs text-slate-600">
                @if ($student->phone)
                    <div class="flex items-center gap-1.5 font-mono text-slate-700">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>{{ $student->phone }}</span>
                    </div>
                @endif
                @if ($student->email)
                    <div class="flex items-center gap-1.5 font-mono text-slate-700">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $student->email }}</span>
                    </div>
                @endif
                <div class="text-[11px] text-slate-400">
                    School Scope: <span class="font-semibold text-slate-600">{{ $student->school->name ?? 'Active Institution' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3-Column Profile Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Column 1 & 2: Demographics, Addresses & Family Guardian Information -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Personal Demographics -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
                <div class="border-b border-slate-100 pb-3 mb-4 flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Personal & Demographic Details</h3>
                    <span class="text-[11px] text-slate-400">Identity Record</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 text-[11px] block">Full Name</span>
                        <p class="font-semibold text-slate-900 mt-0.5">{{ $student->full_name }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[11px] block">Gender</span>
                        <p class="font-semibold text-slate-900 capitalize mt-0.5">{{ $student->gender }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[11px] block">Date of Birth</span>
                        <p class="font-semibold text-slate-900 mt-0.5">
                            {{ $student->date_of_birth ? $student->date_of_birth->format('F d, Y') : '—' }}
                            @if ($student->date_of_birth)
                                <span class="text-slate-400 font-normal">({{ $student->date_of_birth->age }} years)</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[11px] block">Blood Group</span>
                        <p class="font-semibold text-slate-900 mt-0.5">
                            @if ($student->blood_group)
                                <span class="inline-flex items-center px-2 py-0.2 rounded bg-rose-50 text-rose-700 font-bold border border-rose-100 text-[11px]">
                                    {{ $student->blood_group }}
                                </span>
                            @else
                                <span class="text-slate-400 italic">Not Specified</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[11px] block">Religion</span>
                        <p class="font-semibold text-slate-900 mt-0.5">{{ $student->religion ?? '—' }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 text-[11px] block">Institutional Admission Date</span>
                        <p class="font-semibold text-slate-900 mt-0.5">{{ $student->admission_date ? $student->admission_date->format('F d, Y') : '—' }}</p>
                    </div>
                </div>

                <!-- Addresses -->
                <div class="mt-6 pt-5 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 text-[11px] block font-medium">Present / Current Address</span>
                        <p class="text-slate-700 mt-1 whitespace-pre-line">{{ $student->current_address ?: 'No current residential address recorded.' }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[11px] block font-medium">Permanent Address</span>
                        <p class="text-slate-700 mt-1 whitespace-pre-line">{{ $student->permanent_address ?: 'No permanent address recorded.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Family & Guardians Network -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
                <div class="border-b border-slate-100 pb-3 mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Parents & Guardians Network</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Family relationships, emergency contacts & verified phone numbers</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600">
                        {{ $student->guardians->count() }} Linked
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse ($student->guardians as $guardian)
                        <div class="rounded-lg border border-slate-200 bg-slate-50/50 p-4 transition hover:bg-slate-50">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="font-bold text-slate-900 text-sm">{{ $guardian->name }}</h4>
                                        <span class="rounded bg-violet-100 px-2 py-0.5 text-[10px] font-semibold text-violet-800 uppercase tracking-wider">
                                            {{ ucfirst($guardian->pivot->relationship ?? 'Guardian') }}
                                        </span>
                                        @if ($guardian->pivot->is_primary)
                                            <span class="rounded bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-800">
                                                Primary Guardian
                                            </span>
                                        @endif
                                        @if ($guardian->pivot->is_emergency_contact)
                                            <span class="rounded bg-rose-100 px-2 py-0.5 text-[10px] font-semibold text-rose-800">
                                                Emergency Contact
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1 text-xs text-slate-600">
                                        <div>
                                            <span class="text-slate-400">Phone:</span>
                                            <span class="font-mono font-medium text-slate-800 ml-1">{{ $guardian->phone }}</span>
                                        </div>
                                        @if ($guardian->email)
                                            <div>
                                                <span class="text-slate-400">Email:</span>
                                                <span class="font-mono text-slate-800 ml-1">{{ $guardian->email }}</span>
                                            </div>
                                        @endif
                                        @if ($guardian->occupation)
                                            <div>
                                                <span class="text-slate-400">Occupation:</span>
                                                <span class="text-slate-800 ml-1">{{ $guardian->occupation }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if ($guardian->address || $guardian->national_id)
                                <div class="mt-3 pt-3 border-t border-slate-200/50 grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px] text-slate-500">
                                    @if ($guardian->national_id)
                                        <p><span class="font-medium text-slate-600">ID / SSN:</span> {{ $guardian->national_id }}</p>
                                    @endif
                                    @if ($guardian->address)
                                        <p class="sm:col-span-2"><span class="font-medium text-slate-600">Address:</span> {{ $guardian->address }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic py-3 text-center">No parent or guardian records currently linked.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Column 3: Chronological Academic Enrollment Timeline -->
        <div class="space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
                <div class="border-b border-slate-100 pb-3 mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Academic History</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Chronological class progression</p>
                    </div>
                    <span class="rounded-full bg-violet-50 px-2 py-0.5 text-[10px] font-bold text-violet-700">
                        {{ $student->enrollments->count() }} Session(s)
                    </span>
                </div>

                <!-- Timeline Component -->
                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    @forelse ($student->enrollments as $enrollment)
                        <div class="relative group">
                            <!-- Timeline Dot Indicator -->
                            <div class="absolute -left-6 top-1 h-4 w-4 rounded-full border-2 border-white {{ $enrollment->status === 'current' ? 'bg-violet-600 ring-4 ring-violet-100' : ($enrollment->status === 'promoted' ? 'bg-blue-500' : 'bg-slate-400') }}"></div>

                            <div class="rounded-lg border border-slate-100 bg-slate-50/70 p-3.5 transition group-hover:bg-white group-hover:border-slate-200 shadow-2xs">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-bold text-slate-900">
                                        {{ $enrollment->academicYear?->name ?? 'Session Unknown' }}
                                    </span>
                                    @if ($enrollment->status === 'current')
                                        <span class="inline-flex items-center rounded-full bg-emerald-100/80 px-2 py-0.2 text-[9px] font-bold text-emerald-800 uppercase">
                                            Current
                                        </span>
                                    @elseif ($enrollment->status === 'promoted')
                                        <span class="inline-flex items-center rounded-full bg-blue-100/80 px-2 py-0.2 text-[9px] font-bold text-blue-800 uppercase">
                                            Promoted
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-200 px-2 py-0.2 text-[9px] font-bold text-slate-700 uppercase">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-1 text-xs text-slate-700 font-semibold">
                                    {{ $enrollment->academicClass?->name ?? 'Class Unassigned' }}
                                    @if ($enrollment->section)
                                        <span class="font-normal text-slate-500">• Section {{ $enrollment->section->name }}</span>
                                    @endif
                                </div>

                                <div class="mt-2 flex items-center justify-between text-[10px] text-slate-400 border-t border-slate-100 pt-2">
                                    <span>Roll: {{ $enrollment->roll_number ? '#'.$enrollment->roll_number : '—' }}</span>
                                    <span>Enrolled: {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('M d, Y') : '—' }}</span>
                                </div>

                                @if ($enrollment->remarks)
                                    <p class="mt-1 text-[10px] text-slate-500 italic">{{ $enrollment->remarks }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">No enrollment ledger recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Hidden Action Dispatch Form for SweetAlert Submissions -->
<form id="show-action-dispatch-form" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="_method" id="show-action-dispatch-method" value="POST">
</form>

@push('scripts')
<script>
$(function() {
    var $actionForm = $('#show-action-dispatch-form');
    var $actionMethod = $('#show-action-dispatch-method');
    var studentName = '{{ addslashes($student->full_name) }}';

    // Move to Trash
    $('#btn-archive-show').on('click', function(e) {
        e.preventDefault();
        var url = '{{ route("admin.students.destroy", $student) }}';

        if (window.Swal) {
            Swal.fire({
                title: 'Move ' + studentName + ' to Trash?',
                text: 'The student profile and active enrollment will be archived. You can restore them anytime from the Trashed directory.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, move to trash'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $actionForm.attr('action', url);
                    $actionMethod.val('DELETE');
                    $actionForm.submit();
                }
            });
        } else if (confirm('Move ' + studentName + ' to trash?')) {
            $actionForm.attr('action', url);
            $actionMethod.val('DELETE');
            $actionForm.submit();
        }
    });

    // Restore Student
    $('#btn-restore-show').on('click', function(e) {
        e.preventDefault();
        var url = '{{ route("admin.students.restore", $student) }}';

        if (window.Swal) {
            Swal.fire({
                title: 'Restore ' + studentName + '?',
                text: 'This will restore the student profile and enrollment records back to active status.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, restore'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $actionForm.attr('action', url);
                    $actionMethod.val('POST');
                    $actionForm.submit();
                }
            });
        } else if (confirm('Restore ' + studentName + '?')) {
            $actionForm.attr('action', url);
            $actionMethod.val('POST');
            $actionForm.submit();
        }
    });

    // Permanently Purge Student
    $('#btn-purge-show').on('click', function(e) {
        e.preventDefault();
        var url = '{{ route("admin.students.force-delete", $student) }}';

        if (window.Swal) {
            Swal.fire({
                title: 'Permanently Purge ' + studentName + '?',
                text: 'CRITICAL: This permanently removes this student, their photo, and enrollment history. This action CANNOT be reverted.',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#be123c',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, permanently purge'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $actionForm.attr('action', url);
                    $actionMethod.val('DELETE');
                    $actionForm.submit();
                }
            });
        } else if (confirm('Permanently purge ' + studentName + '?')) {
            $actionForm.attr('action', url);
            $actionMethod.val('DELETE');
            $actionForm.submit();
        }
    });
});
</script>
@endpush
@endsection
