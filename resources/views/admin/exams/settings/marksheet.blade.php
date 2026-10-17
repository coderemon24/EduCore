@extends('layouts.admin')

@section('title', 'Marksheet Settings | School Admin')

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
                <span class="font-semibold text-slate-700">Marksheet Settings</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Marksheet Settings</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Upload background template, logo, signature, and configure report card display.
            </p>
        </div>
    </div>

    <!-- Settings Form Container -->
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-2xs max-w-4xl">
        <form method="POST" action="{{ route('admin.exams.settings.marksheet.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Uploads Grid -->
            <div>
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4">Template & Images</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Background Template Image -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-800">
                            Background Template
                        </label>
                        <p class="text-[11px] text-slate-500">
                            A4 portrait background image (JPG, PNG, WebP).
                        </p>

                        <div class="mt-2 rounded-lg border border-dashed border-slate-300 p-4 text-center bg-slate-50/50">
                            @if($setting->background_image_path && file_exists(public_path($setting->background_image_path)))
                                <div class="mb-3">
                                    <img src="{{ asset($setting->background_image_path) }}" alt="Background Template" class="h-32 mx-auto rounded border border-slate-200 object-cover shadow-xs">
                                    <span class="block text-[10px] text-emerald-600 font-bold mt-1">✓ Template uploaded</span>
                                </div>
                            @else
                                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="block text-[10px] text-slate-400 mb-2">No background (uses default border)</span>
                            @endif

                            <input type="file" name="background_image" accept="image/png,image/jpeg,image/webp" class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer">
                        </div>
                    </div>

                    <!-- School Logo -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-800">
                            School Logo
                        </label>
                        <p class="text-[11px] text-slate-500">
                            Displays at the top of the marksheet.
                        </p>

                        <div class="mt-2 rounded-lg border border-dashed border-slate-300 p-4 text-center bg-slate-50/50">
                            @if($setting->school_logo_path && file_exists(public_path($setting->school_logo_path)))
                                <div class="mb-3">
                                    <img src="{{ asset($setting->school_logo_path) }}" alt="Logo" class="h-20 mx-auto rounded border border-slate-200 object-contain">
                                    <span class="block text-[10px] text-emerald-600 font-bold mt-1">✓ Logo uploaded</span>
                                </div>
                            @else
                                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <span class="block text-[10px] text-slate-400 mb-2">No logo uploaded</span>
                            @endif

                            <input type="file" name="school_logo" accept="image/*" class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer">
                        </div>
                    </div>

                    <!-- Principal Signature -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-800">
                            Principal / Head Teacher Signature
                        </label>
                        <p class="text-[11px] text-slate-500">
                            Displays on the signature line of report cards.
                        </p>

                        <div class="mt-2 rounded-lg border border-dashed border-slate-300 p-4 text-center bg-slate-50/50">
                            @if($setting->principal_signature_path && file_exists(public_path($setting->principal_signature_path)))
                                <div class="mb-3">
                                    <img src="{{ asset($setting->principal_signature_path) }}" alt="Signature" class="h-16 mx-auto object-contain">
                                    <span class="block text-[10px] text-emerald-600 font-bold mt-1">✓ Signature uploaded</span>
                                </div>
                            @else
                                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </div>
                                <span class="block text-[10px] text-slate-400 mb-2">No signature uploaded</span>
                            @endif

                            <input type="file" name="principal_signature" accept="image/*" class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Typography & Headers -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <h2 class="text-sm font-bold text-slate-900">Header & Sign-off Titles</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="header_title" class="block text-xs font-semibold text-slate-700 mb-1">School Name (Optional Override)</label>
                        <input type="text"
                               name="header_title"
                               id="header_title"
                               value="{{ old('header_title', $setting->header_title) }}"
                               placeholder="Leave blank to use default school name"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    </div>

                    <div>
                        <label for="headmaster_title" class="block text-xs font-semibold text-slate-700 mb-1">Principal / Head Teacher Title <span class="text-rose-500">*</span></label>
                        <input type="text"
                               name="headmaster_title"
                               id="headmaster_title"
                               required
                               value="{{ old('headmaster_title', $setting->headmaster_title ?: 'Head Master') }}"
                               placeholder="e.g. Principal, Head Master, Headmistress"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                    </div>
                </div>
            </div>

            <!-- Marksheet Feature Toggles -->
            <div class="pt-4 border-t border-slate-100 space-y-3">
                <h2 class="text-sm font-bold text-slate-900">Marksheet Display Options</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="flex items-center gap-2.5 rounded-lg border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition">
                        <input type="checkbox" name="show_highest_marks" value="1" {{ old('show_highest_marks', $setting->show_highest_marks) ? 'checked' : '' }} class="rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                        <span class="text-xs font-semibold text-slate-800">Show Highest Marks Column</span>
                    </label>

                    <label class="flex items-center gap-2.5 rounded-lg border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition">
                        <input type="checkbox" name="show_attendance_report" value="1" {{ old('show_attendance_report', $setting->show_attendance_report) ? 'checked' : '' }} class="rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                        <span class="text-xs font-semibold text-slate-800">Show Monthly Attendance Table</span>
                    </label>

                    <label class="flex items-center gap-2.5 rounded-lg border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition">
                        <input type="checkbox" name="show_extra_activities" value="1" {{ old('show_extra_activities', $setting->show_extra_activities) ? 'checked' : '' }} class="rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                        <span class="text-xs font-semibold text-slate-800">Show Co-Curricular Activities Table</span>
                    </label>

                    <label class="flex items-center gap-2.5 rounded-lg border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition">
                        <input type="checkbox" name="show_gpa_grading_scale" value="1" {{ old('show_gpa_grading_scale', $setting->show_gpa_grading_scale) ? 'checked' : '' }} class="rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                        <span class="text-xs font-semibold text-slate-800">Show Grading Scale Table</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-6 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-violet-700 transition cursor-pointer">
                    <span>Save Settings</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
