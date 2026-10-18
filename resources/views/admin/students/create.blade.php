@extends('layouts.admin')

@section('title', 'Student Admission | Student Management')

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
    .dropify-wrapper .dropify-message p {
        font-size: 12px !important;
        color: #64748b !important;
        font-weight: 500 !important;
    }
    .dropify-wrapper .dropify-preview .dropify-render img {
        border-radius: 0.5rem !important;
        object-fit: contain !important;
        margin: 0 auto !important;
    }
</style>
@endpush

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
                <span class="font-semibold text-slate-700">New Admission</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Student Admission & Enrollment</h1>
            <p class="text-xs text-slate-500 mt-0.5">Register a new student identity, set initial session placement, and link parent contacts.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.students.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Directory</span>
            </a>
        </div>
    </div>

    <!-- Admission Form -->
    <form method="POST"
          action="{{ route('admin.students.store') }}"
          enctype="multipart/form-data"
          id="student-admission-form"
          class="space-y-6">
        @csrf

        <!-- CARD 1: Academic Enrollment & Placement -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5 flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-violet-600 text-[10px] text-white">1</span>
                        <span>Academic Placement & Institutional Enrollment</span>
                    </h2>
                    <p class="text-[11px] text-slate-500 mt-0.5 ml-7">Target academic session, class grade level, section, and roll number assignment.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Academic Session / Year -->
                <div>
                    <label for="academic_year_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Academic Session <span class="text-rose-500">*</span>
                    </label>
                    <select id="academic_year_id"
                            name="academic_year_id"
                            required
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id', $activeYear?->id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_current ? '• Current Active' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Academic Class / Grade -->
                <div>
                    <label for="academic_class_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Class / Grade Level <span class="text-rose-500">*</span>
                    </label>
                    <select id="academic_class_id"
                            name="academic_class_id"
                            data-academic-class-select
                            required
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        <option value="">-- Select Class --</option>
                        @foreach ($academicClasses as $class)
                            <option value="{{ $class->id }}" {{ old('academic_class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_class_id')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section (Dependent Dropdown) -->
                <div>
                    <label for="section_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Section
                    </label>
                    <select id="section_id"
                            name="section_id"
                            data-dependent-sections
                            data-selected="{{ old('section_id') }}"
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        <option value="">-- Select Section --</option>
                    </select>
                    @error('section_id')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Admission Number (Auto-Suggested, Manual Override) -->
                <div>
                    <label for="admission_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Admission Number <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="admission_number"
                           name="admission_number"
                           value="{{ old('admission_number', $suggestedAdmissionNumber) }}"
                           required
                           placeholder="e.g. ADM-2026-0001"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono font-semibold text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('admission_number') border-rose-300 ring-rose-200 @enderror">
                    <p class="mt-1 text-[10px] text-slate-400">Unique identifier within institution. Pre-suggested sequential number.</p>
                    @error('admission_number')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Admission Date -->
                <div>
                    <label for="admission_date" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Admission Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date"
                           id="admission_date"
                           name="admission_date"
                           value="{{ old('admission_date', date('Y-m-d')) }}"
                           required
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('admission_date') border-rose-300 ring-rose-200 @enderror">
                    @error('admission_date')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Roll Number -->
                <div>
                    <label for="roll_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Class Roll Number
                    </label>
                    <input type="text"
                           id="roll_number"
                           name="roll_number"
                           value="{{ old('roll_number') }}"
                           placeholder="e.g. 101"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    @error('roll_number')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 4th / Optional Subject -->
                <div>
                    <label for="optional_subject_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        4th / Optional Subject
                    </label>
                    <select id="optional_subject_id"
                            name="optional_subject_id"
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        <option value="">-- No Optional Subject --</option>
                        @foreach ($subjects ?? [] as $subj)
                            <option value="{{ $subj->id }}" {{ old('optional_subject_id') == $subj->id ? 'selected' : '' }}>
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
        </div>

        <!-- CARD 2: Student Demographic Identity -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-violet-600 text-[10px] text-white">2</span>
                    <span>Student Personal Details & Demographics</span>
                </h2>
                <p class="text-[11px] text-slate-500 mt-0.5 ml-7">Legal names, gender, birthday, blood group, contact records, and profile photograph.</p>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Text Details (2 Columns) -->
                <div class="lg:col-span-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            First Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="first_name"
                               name="first_name"
                               value="{{ old('first_name') }}"
                               required
                               placeholder="e.g. Alexander"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('first_name') border-rose-300 ring-rose-200 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Last Name / Surname
                        </label>
                        <input type="text"
                               id="last_name"
                               name="last_name"
                               value="{{ old('last_name') }}"
                               placeholder="e.g. Wright"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                        @error('last_name')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
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
                            <option value="male" {{ old('gender', 'male') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other / Non-Binary</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Date of Birth
                        </label>
                        <input type="date"
                               id="date_of_birth"
                               name="date_of_birth"
                               value="{{ old('date_of_birth') }}"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                        @error('date_of_birth')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Blood Group -->
                    <div>
                        <label for="blood_group" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Blood Group
                        </label>
                        <select id="blood_group"
                                name="blood_group"
                                class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                            <option value="">-- Unknown / Unspecified --</option>
                            @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Religion -->
                    <div>
                        <label for="religion" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Religion
                        </label>
                        <input type="text"
                               id="religion"
                               name="religion"
                               value="{{ old('religion') }}"
                               placeholder="e.g. Christianity, Islam..."
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    </div>

                    <!-- Student Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Student Email Address
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="student@school.edu"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                        @error('email')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student Phone -->
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Student Contact Phone
                        </label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               placeholder="+1 (555) 000-0000"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    </div>
                </div>

                <!-- Dropify Photo Upload (1 Column) -->
                <div>
                    <label for="photo" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Student Portrait Photograph
                    </label>
                    <input type="file"
                           id="photo"
                           name="photo"
                           class="dropify"
                           data-height="200"
                           data-max-file-size="2M"
                           data-allowed-file-extensions="png jpg jpeg webp"
                           data-default-file="" />
                    <p class="mt-1.5 text-[10px] text-slate-400">Supported formats: JPG, PNG, WebP (Max: 2MB). Direct institutional storage.</p>
                    @error('photo')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- CARD 3: Parent & Guardian Contacts -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-violet-600 text-[10px] text-white">3</span>
                    <span>Parent / Guardian Records</span>
                </h2>
                <p class="text-[11px] text-slate-500 mt-0.5 ml-7">Designate primary emergency contact person and relationship.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Guardian Name -->
                <div>
                    <label for="guardian_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Guardian Full Name
                    </label>
                    <input type="text"
                           id="guardian_name"
                           name="guardian_name"
                           value="{{ old('guardian_name') }}"
                           placeholder="e.g. Robert Wright"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    @error('guardian_name')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guardian Phone -->
                <div>
                    <label for="guardian_phone" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Guardian Phone Number
                    </label>
                    <input type="text"
                           id="guardian_phone"
                           name="guardian_phone"
                           value="{{ old('guardian_phone') }}"
                           placeholder="e.g. +1 (555) 987-6543"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    @error('guardian_phone')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Relationship -->
                <div>
                    <label for="guardian_relation" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Relationship to Student
                    </label>
                    <select id="guardian_relation"
                            name="guardian_relation"
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                        <option value="father" {{ old('guardian_relation') === 'father' ? 'selected' : '' }}>Father</option>
                        <option value="mother" {{ old('guardian_relation') === 'mother' ? 'selected' : '' }}>Mother</option>
                        <option value="legal_guardian" {{ old('guardian_relation') === 'legal_guardian' ? 'selected' : '' }}>Legal Guardian</option>
                        <option value="other" {{ old('guardian_relation') === 'other' ? 'selected' : '' }}>Other Relative</option>
                    </select>
                </div>

                <!-- Guardian Email -->
                <div>
                    <label for="guardian_email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Guardian Email
                    </label>
                    <input type="email"
                           id="guardian_email"
                           name="guardian_email"
                           value="{{ old('guardian_email') }}"
                           placeholder="parent@gmail.com"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <!-- Occupation -->
                <div>
                    <label for="guardian_occupation" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Occupation / Employer
                    </label>
                    <input type="text"
                           id="guardian_occupation"
                           name="guardian_occupation"
                           value="{{ old('guardian_occupation') }}"
                           placeholder="e.g. Civil Engineer"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <!-- National ID -->
                <div>
                    <label for="guardian_national_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        National ID / SSN
                    </label>
                    <input type="text"
                           id="guardian_national_id"
                           name="guardian_national_id"
                           value="{{ old('guardian_national_id') }}"
                           placeholder="e.g. NID-492049182"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>
            </div>
        </div>

        <!-- CARD 4: Residential Addresses -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-violet-600 text-[10px] text-white">4</span>
                    <span>Residential & Permanent Addresses</span>
                </h2>
                <p class="text-[11px] text-slate-500 mt-0.5 ml-7">Home residence location and emergency mailing contact address.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Present Address -->
                <div>
                    <label for="current_address" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Current / Present Address
                    </label>
                    <textarea id="current_address"
                              name="current_address"
                              rows="3"
                              placeholder="House, Street, Apartment, City, State, Postal Code..."
                              class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-3 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">{{ old('current_address') }}</textarea>
                </div>

                <!-- Permanent Address -->
                <div>
                    <label for="permanent_address" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Permanent Address
                    </label>
                    <textarea id="permanent_address"
                              name="permanent_address"
                              rows="3"
                              placeholder="Permanent family address..."
                              class="w-full rounded-lg border border-slate-200 bg-slate-50/50 p-3 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">{{ old('permanent_address') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit & Navigation Bar -->
        <div class="flex items-center justify-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <a href="{{ route('admin.students.index') }}"
               class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                Cancel
            </a>
            <button type="submit"
                    id="btn-submit-admission"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Complete Admission & Enroll</span>
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script src="{{ asset('admin-assets/js/dropify.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.jQuery && typeof window.jQuery.fn.dropify === 'function') {
            window.jQuery('.dropify').dropify({
                messages: {
                    'default': 'Drag and drop photo here or click to browse',
                    'replace': 'Drag and drop or click to replace photo',
                    'remove':  'Remove',
                    'error':   'Error reading image file'
                }
            });
        }

        var form = document.getElementById('student-admission-form');
        if (form) {
            form.addEventListener('submit', function() {
                var btn = document.getElementById('btn-submit-admission');
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('opacity-75', 'cursor-not-allowed');
                    var span = btn.querySelector('span');
                    if (span) span.textContent = 'Enrolling Student...';
                }
            });
        }
    });
</script>
@endpush
@endsection
