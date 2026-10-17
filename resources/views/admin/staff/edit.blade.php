@extends('layouts.admin')

@section('title', 'Edit Staff: ' . $staff->full_name . ' | Administration')

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
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between pb-1">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.staff.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Staff & Faculty</a>
                <span>/</span>
                <a href="{{ route('admin.staff.show', $staff) }}" class="font-medium text-slate-600 hover:text-violet-600 transition">{{ $staff->full_name }}</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Edit Record</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Edit Staff: {{ $staff->full_name }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5"><span class="font-mono font-medium text-slate-700">{{ $staff->staff_code }}</span> • {{ $staff->designation ?? 'Staff Member' }}</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.staff.show', $staff) }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs transition hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>View Profile</span>
            </a>
            <a href="{{ route('admin.staff.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs transition hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Directory</span>
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form method="POST"
          action="{{ route('admin.staff.update', $staff) }}"
          enctype="multipart/form-data"
          id="staff-edit-form"
          class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Personal Information -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-sm font-semibold text-slate-900">Personal Information</h2>
                <p class="text-xs text-slate-500 mt-0.5">Basic identity, contact details, and photo.</p>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                <!-- Dropify Portrait Photo -->
                <div class="lg:col-span-4">
                    <label for="photo" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Profile Photo <span class="text-slate-400 font-normal">(Optional)</span>
                    </label>
                    <input type="file"
                           name="photo"
                           id="photo"
                           class="dropify"
                           data-default-file="{{ $staff->avatar_url }}"
                           data-height="190"
                           data-max-file-size="2M"
                           data-allowed-file-extensions="jpg jpeg png webp" />
                    <p class="text-[10px] text-slate-400 mt-1.5">Upload a new file to replace the existing photo.</p>
                    @error('photo')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Identity Details Inputs -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Staff Code -->
                        <div>
                            <label for="staff_code" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Staff ID / Code <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="staff_code"
                                   id="staff_code"
                                   value="{{ old('staff_code', $staff->staff_code) }}"
                                   required
                                   class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono font-medium text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                            @error('staff_code')
                                <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Gender <span class="text-rose-500">*</span>
                            </label>
                            <select name="gender"
                                    id="gender"
                                    required
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500 cursor-pointer">
                                <option value="Male" {{ old('gender', $staff->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $staff->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $staff->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                First Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="first_name"
                                   id="first_name"
                                   value="{{ old('first_name', $staff->first_name) }}"
                                   required
                                   class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
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
                                   name="last_name"
                                   id="last_name"
                                   value="{{ old('last_name', $staff->last_name) }}"
                                   class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                            @error('last_name')
                                <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Email Address <span class="text-rose-500">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email', $staff->email) }}"
                                   required
                                   class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                            @error('email')
                                <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Mobile Phone
                            </label>
                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   value="{{ old('phone', $staff->phone) }}"
                                   class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                            @error('phone')
                                <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Date of Birth
                            </label>
                            <input type="date"
                                   name="date_of_birth"
                                   id="date_of_birth"
                                   value="{{ old('date_of_birth', $staff->date_of_birth?->format('Y-m-d')) }}"
                                   class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                            @error('date_of_birth')
                                <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Details -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-sm font-semibold text-slate-900">Employment Details</h2>
                <p class="text-xs text-slate-500 mt-0.5">Category, department, designation, and compensation.</p>
            </div>

            <div class="space-y-4">
                <!-- Faculty vs Non-Teaching Switch -->
                <div class="p-3.5 bg-slate-50/80 rounded-xl border border-slate-200/80">
                    <span class="block text-xs font-bold text-slate-800 mb-2">Personnel Category</span>
                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio"
                                   name="is_teaching"
                                   value="1"
                                   {{ old('is_teaching', $staff->is_teaching ? '1' : '0') == '1' ? 'checked' : '' }}
                                   class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-slate-300">
                            <span class="text-xs font-semibold text-slate-800">Teaching Faculty</span>
                            <span class="text-[11px] text-slate-400">(Can be assigned as Class Teacher or Subject Instructor)</span>
                        </label>

                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio"
                                   name="is_teaching"
                                   value="0"
                                   {{ old('is_teaching', $staff->is_teaching ? '1' : '0') == '0' ? 'checked' : '' }}
                                   class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-slate-300">
                            <span class="text-xs font-semibold text-slate-800">Non-Teaching Staff</span>
                            <span class="text-[11px] text-slate-400">(Administration, Accounting, Operations, Library)</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Department <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="department"
                               id="department"
                               list="department-suggestions"
                               value="{{ old('department', $staff->department) }}"
                               required
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                        <datalist id="department-suggestions">
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}">
                            @endforeach
                            <option value="Mathematics">
                            <option value="Sciences">
                            <option value="Languages & Literature">
                            <option value="Social Studies">
                            <option value="Administration">
                            <option value="Accounts & Finance">
                            <option value="Information Technology">
                            <option value="Physical Education">
                        </datalist>
                        @error('department')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Designation -->
                    <div>
                        <label for="designation" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Designation <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="designation"
                               id="designation"
                               list="designation-suggestions"
                               value="{{ old('designation', $staff->designation) }}"
                               required
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                        <datalist id="designation-suggestions">
                            @foreach($designations as $desig)
                                <option value="{{ $desig }}">
                            @endforeach
                            <option value="Senior Teacher">
                            <option value="Assistant Teacher">
                            <option value="Head of Department">
                            <option value="Academic Coordinator">
                            <option value="Principal">
                            <option value="Accountant">
                            <option value="Librarian">
                            <option value="Administrative Officer">
                        </datalist>
                        @error('designation')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Qualification -->
                    <div>
                        <label for="qualification" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Highest Academic Qualification
                        </label>
                        <input type="text"
                               name="qualification"
                               id="qualification"
                               value="{{ old('qualification', $staff->qualification) }}"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                        @error('qualification')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Joining Date -->
                    <div>
                        <label for="joining_date" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Joining Date <span class="text-rose-500">*</span>
                        </label>
                        <input type="date"
                               name="joining_date"
                               id="joining_date"
                               value="{{ old('joining_date', $staff->joining_date?->format('Y-m-d')) }}"
                               required
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                        @error('joining_date')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract Type -->
                    <div>
                        <label for="contract_type" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Contract Type <span class="text-rose-500">*</span>
                        </label>
                        <select name="contract_type"
                                id="contract_type"
                                required
                                class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500 cursor-pointer">
                            <option value="permanent" {{ old('contract_type', $staff->contract_type) === 'permanent' ? 'selected' : '' }}>Permanent</option>
                            <option value="probation" {{ old('contract_type', $staff->contract_type) === 'probation' ? 'selected' : '' }}>Probationary</option>
                            <option value="contract" {{ old('contract_type', $staff->contract_type) === 'contract' ? 'selected' : '' }}>Contractual</option>
                            <option value="part_time" {{ old('contract_type', $staff->contract_type) === 'part_time' ? 'selected' : '' }}>Part Time</option>
                        </select>
                        @error('contract_type')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Experience Years -->
                    <div>
                        <label for="experience_years" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Experience (Years)
                        </label>
                        <input type="number"
                               name="experience_years"
                               id="experience_years"
                               min="0"
                               max="50"
                               value="{{ old('experience_years', $staff->experience_years ?? 0) }}"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                        @error('experience_years')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Basic Salary -->
                    <div>
                        <label for="basic_salary" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Basic Salary (Monthly)
                        </label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="basic_salary"
                               id="basic_salary"
                               value="{{ old('basic_salary', $staff->basic_salary) }}"
                               placeholder="e.g. 4500.00"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                        @error('basic_salary')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Record Status -->
                <div class="pt-2">
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Account Status <span class="text-rose-500">*</span>
                    </label>
                    <select name="status"
                            id="status"
                            required
                            class="max-w-xs w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500 cursor-pointer">
                        <option value="active" {{ old('status', $staff->status) === 'active' ? 'selected' : '' }}>Active • Normal Operations</option>
                        <option value="inactive" {{ old('status', $staff->status) === 'inactive' ? 'selected' : '' }}>Inactive • On Leave / Deferred</option>
                        <option value="suspended" {{ old('status', $staff->status) === 'suspended' ? 'selected' : '' }}>Suspended • Disciplinary Hold</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Emergency Contacts & Addresses -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-sm font-semibold text-slate-900">Emergency & Addresses</h2>
                <p class="text-xs text-slate-500 mt-0.5">Emergency contact person and home addresses.</p>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Emergency Contact Name -->
                    <div>
                        <label for="emergency_contact_name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Emergency Contact Person
                        </label>
                        <input type="text"
                               name="emergency_contact_name"
                               id="emergency_contact_name"
                               value="{{ old('emergency_contact_name', $staff->emergency_contact_name) }}"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                        @error('emergency_contact_name')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Emergency Contact Phone -->
                    <div>
                        <label for="emergency_contact_phone" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Emergency Contact Phone
                        </label>
                        <input type="text"
                               name="emergency_contact_phone"
                               id="emergency_contact_phone"
                               value="{{ old('emergency_contact_phone', $staff->emergency_contact_phone) }}"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                        @error('emergency_contact_phone')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Current Address -->
                    <div>
                        <label for="current_address" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Current Address
                        </label>
                        <textarea name="current_address"
                                  id="current_address"
                                  rows="2"
                                  class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">{{ old('current_address', $staff->current_address) }}</textarea>
                        @error('current_address')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Permanent Address -->
                    <div>
                        <label for="permanent_address" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Permanent Address
                        </label>
                        <textarea name="permanent_address"
                                  id="permanent_address"
                                  rows="2"
                                  class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">{{ old('permanent_address', $staff->permanent_address) }}</textarea>
                        @error('permanent_address')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- User Account & Role -->
        @php
            $linkedUser = $staff->user;
            $currentRoleId = $linkedUser && $linkedUser->roles->isNotEmpty() ? $linkedUser->roles->first()->id : null;
        @endphp
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-sm font-semibold text-slate-900">User Account & Role</h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage login credentials and portal permissions.</p>
            </div>

            <div class="space-y-4">
                @if($linkedUser)
                    <div class="flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-800 text-xs">
                        <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Linked User Account: <strong>{{ $linkedUser->email }}</strong> (ID: #{{ $linkedUser->id }})</span>
                    </div>
                @else
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-xs">
                        <p class="font-semibold">No login account currently linked to this staff profile.</p>
                        <p class="text-[11px] text-amber-700 mt-0.5">To provision an account now, specify a password and assign a role below.</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 pt-1">
                    <!-- Password Update -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            {{ $linkedUser ? 'Reset Password (Leave blank to keep unchanged)' : 'Initial Password' }}
                        </label>
                        <input type="password"
                               name="password"
                               id="password"
                               placeholder="{{ $linkedUser ? 'Enter new password if changing' : 'Minimum 6 characters' }}"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500">
                        @error('password')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label for="role_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Assigned Role
                        </label>
                        <select name="role_id"
                                id="role_id"
                                class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-900 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-1 focus:ring-violet-500 cursor-pointer">
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $currentRoleId) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }} {{ $role->is_system ? '(Built-in)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">Controls portal access and capabilities.</p>
                        @error('role_id')
                            <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Submission Bar -->
        <div class="flex items-center justify-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <a href="{{ route('admin.staff.show', $staff) }}"
               class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer">
                Cancel
            </a>
            <button type="submit"
                    class="rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Update Staff Record</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin-assets/js/dropify.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.dropify').dropify({
            messages: {
                default: 'Drag and drop photo here, or click to browse',
                replace: 'Drag and drop or click to replace photo',
                remove: 'Remove',
                error: 'An error occurred while uploading'
            }
        });
    });
</script>
@endpush
