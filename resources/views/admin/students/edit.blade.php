@extends('layouts.admin')

@section('title', 'Edit ' . $student->full_name . ' | Student Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin-assets/css/dropify.min.css') }}">
<style>
    .dropify-wrapper {
        border-radius: 0.75rem !important;
        border: 1.5px dashed #cbd5e1 !important;
        background-color: #f8fafc !important;
        transition: all 0.2s ease-in-out !important;
    }
    .dropify-wrapper:hover {
        background-color: #ffffff !important;
        border-color: #7c3aed !important;
    }
    .dropify-wrapper .dropify-preview .dropify-render img {
        border-radius: 0.5rem !important;
        object-fit: contain !important;
        margin: 0 auto !important;
    }
</style>
@endpush

@section('admin-content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Breadcrumb & Header -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.students.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Students</a>
                <span>/</span>
                <a href="{{ route('admin.students.show', $student) }}" class="font-medium text-slate-600 hover:text-violet-600 transition">{{ $student->full_name }}</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Edit Profile</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Student Profile</h1>
            <p class="text-xs text-slate-500 mt-0.5">Update personal identity, photo, status, and linked parent contact details.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.students.show', $student) }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>View Profile</span>
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form method="POST"
          action="{{ route('admin.students.update', $student) }}"
          enctype="multipart/form-data"
          id="student-edit-form"
          class="space-y-6">
        @csrf
        @method('PUT')

        @php
            $guardian = $student->primaryGuardian;
        @endphp

        <!-- CARD 1: Identity & Demographics -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">1. Student Identity & Demographics</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Core student identity, admission number, status, and portrait.</p>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Admission Number -->
                    <div>
                        <label for="admission_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Admission Number <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="admission_number"
                               name="admission_number"
                               value="{{ old('admission_number', $student->admission_number) }}"
                               required
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono font-semibold text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('admission_number') border-rose-300 ring-rose-200 @enderror">
                        @error('admission_number')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Student Status <span class="text-rose-500">*</span>
                        </label>
                        <select id="status"
                                name="status"
                                required
                                class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                            <option value="active" {{ old('status', $student->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="promoted" {{ old('status', $student->status) === 'promoted' ? 'selected' : '' }}>Promoted</option>
                            <option value="transferred" {{ old('status', $student->status) === 'transferred' ? 'selected' : '' }}>Transferred</option>
                            <option value="graduated" {{ old('status', $student->status) === 'graduated' ? 'selected' : '' }}>Graduated</option>
                            <option value="suspended" {{ old('status', $student->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            First Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="first_name"
                               name="first_name"
                               value="{{ old('first_name', $student->first_name) }}"
                               required
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('first_name') border-rose-300 ring-rose-200 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Last Name
                        </label>
                        <input type="text"
                               id="last_name"
                               name="last_name"
                               value="{{ old('last_name', $student->last_name) }}"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Gender <span class="text-rose-500">*</span>
                        </label>
                        <select id="gender"
                                name="gender"
                                required
                                class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                            <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $student->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Date of Birth
                        </label>
                        <input type="date"
                               id="date_of_birth"
                               name="date_of_birth"
                               value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    </div>

                    <!-- Admission Date -->
                    <div>
                        <label for="admission_date" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Admission Date <span class="text-rose-500">*</span>
                        </label>
                        <input type="date"
                               id="admission_date"
                               name="admission_date"
                               value="{{ old('admission_date', $student->admission_date?->format('Y-m-d')) }}"
                               required
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    </div>

                    <!-- Blood Group -->
                    <div>
                        <label for="blood_group" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Blood Group
                        </label>
                        <select id="blood_group"
                                name="blood_group"
                                class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                            <option value="">-- Unspecified --</option>
                            @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $student->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $student->email) }}"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Phone</label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ old('phone', $student->phone) }}"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    </div>

                    <!-- 4th / Optional Subject -->
                    <div class="sm:col-span-2">
                        <label for="optional_subject_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            4th / Optional Subject
                        </label>
                        @php
                            $currentOptionalId = $student->currentEnrollment?->optional_subject_id;
                        @endphp
                        <select id="optional_subject_id"
                                name="optional_subject_id"
                                class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                            <option value="">-- No Optional Subject --</option>
                            @foreach ($subjects ?? [] as $subj)
                                <option value="{{ $subj->id }}" {{ old('optional_subject_id', $currentOptionalId) == $subj->id ? 'selected' : '' }}>
                                    {{ $subj->name }} ({{ $subj->code }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-[11px] text-slate-500">Optional. Leave blank to use class default.</p>
                        @error('optional_subject_id')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Photo Upload with Existing Preview -->
                <div>
                    <label for="photo" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Profile Photograph
                    </label>
                    <input type="file"
                           id="photo"
                           name="photo"
                           class="dropify"
                           data-height="200"
                           data-max-file-size="2M"
                           data-allowed-file-extensions="png jpg jpeg webp"
                           data-default-file="{{ $student->photo && file_exists(public_path($student->photo)) ? asset($student->photo) : '' }}" />
                    <p class="mt-1 text-[10px] text-slate-400">Leave unchanged or upload a new photo to replace.</p>
                </div>
            </div>
        </div>

        <!-- CARD 2: Primary Guardian -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">2. Primary Guardian Details</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Primary family point of contact.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label for="guardian_name" class="block text-xs font-semibold text-slate-700 mb-1.5">Guardian Name</label>
                    <input type="text"
                           id="guardian_name"
                           name="guardian_name"
                           value="{{ old('guardian_name', $guardian?->name) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>
                <div>
                    <label for="guardian_phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Guardian Phone</label>
                    <input type="text"
                           id="guardian_phone"
                           name="guardian_phone"
                           value="{{ old('guardian_phone', $guardian?->phone) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>
                <div>
                    <label for="guardian_relation" class="block text-xs font-semibold text-slate-700 mb-1.5">Relationship</label>
                    @php $rel = $guardian?->pivot?->relationship ?? 'father'; @endphp
                    <select id="guardian_relation"
                            name="guardian_relation"
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        <option value="father" {{ old('guardian_relation', $rel) === 'father' ? 'selected' : '' }}>Father</option>
                        <option value="mother" {{ old('guardian_relation', $rel) === 'mother' ? 'selected' : '' }}>Mother</option>
                        <option value="legal_guardian" {{ old('guardian_relation', $rel) === 'legal_guardian' ? 'selected' : '' }}>Legal Guardian</option>
                        <option value="other" {{ old('guardian_relation', $rel) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label for="guardian_email" class="block text-xs font-semibold text-slate-700 mb-1.5">Guardian Email</label>
                    <input type="email"
                           id="guardian_email"
                           name="guardian_email"
                           value="{{ old('guardian_email', $guardian?->email) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>
                <div>
                    <label for="guardian_occupation" class="block text-xs font-semibold text-slate-700 mb-1.5">Occupation</label>
                    <input type="text"
                           id="guardian_occupation"
                           name="guardian_occupation"
                           value="{{ old('guardian_occupation', $guardian?->occupation) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>
                <div>
                    <label for="guardian_national_id" class="block text-xs font-semibold text-slate-700 mb-1.5">National ID / SSN</label>
                    <input type="text"
                           id="guardian_national_id"
                           name="guardian_national_id"
                           value="{{ old('guardian_national_id', $guardian?->national_id) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>
            </div>
        </div>

        <!-- CARD 3: Addresses -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">3. Residential Addresses</h2>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="current_address" class="block text-xs font-semibold text-slate-700 mb-1.5">Present Address</label>
                    <textarea id="current_address" name="current_address" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-3 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">{{ old('current_address', $student->current_address) }}</textarea>
                </div>
                <div>
                    <label for="permanent_address" class="block text-xs font-semibold text-slate-700 mb-1.5">Permanent Address</label>
                    <textarea id="permanent_address" name="permanent_address" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-3 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">{{ old('permanent_address', $student->permanent_address) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit & Navigation Bar -->
        <div class="flex items-center justify-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <a href="{{ route('admin.students.show', $student) }}"
               class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Save Profile Changes</span>
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script src="{{ asset('admin-assets/js/dropify.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.jQuery && typeof window.jQuery.fn.dropify === 'function') {
            window.jQuery('.dropify').dropify();
        }
    });
</script>
@endpush
@endsection
