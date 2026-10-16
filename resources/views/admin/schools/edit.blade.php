@extends('layouts.admin')

@section('title', 'Edit ' . $school->name . ' | Super Admin')

@section('admin-content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col gap-2 pb-1">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.schools.index') }}" class="text-slate-600 hover:text-violet-600 transition">Schools</a>
            <span>/</span>
            <span class="font-semibold text-slate-700 truncate max-w-xs">Edit: {{ $school->name }}</span>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Academic Institution</h1>
                <p class="text-xs text-slate-500 mt-0.5">Modify tenant operational parameters, contacts, and configuration for #{{ $school->id }} ({{ $school->code ?: $school->slug }}).</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.schools.show', $school) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs transition hover:bg-slate-50">
                    <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>View Profile</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Validation Error Summary -->
    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50/80 p-4 text-xs text-rose-800 shadow-xs">
            <div class="flex items-center gap-2 font-semibold mb-1 text-rose-900">
                <svg class="h-4 w-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Please correct the input errors below:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-2 text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.schools.update', $school) }}" method="POST" enctype="multipart/form-data" id="edit-school-form" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Card 1: Core Institutional Identity -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">1. Institutional Identity & Status</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Primary institutional parameters and current operational lifecycle status.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <!-- Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Institution Official Name <span class="text-rose-500">*</span></label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $school->name) }}"
                           required
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('name') border-rose-300 ring-rose-200 @enderror">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">Operational Status <span class="text-rose-500">*</span></label>
                    <select id="status"
                            name="status"
                            required
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs cursor-pointer"
                            data-placeholder="Select Operational Status">
                        <option value="active" {{ old('status', $school->status?->value) === 'active' ? 'selected' : '' }}>Active (Operational)</option>
                        <option value="pending" {{ old('status', $school->status?->value) === 'pending' ? 'selected' : '' }}>Pending Verification</option>
                        <option value="suspended" {{ old('status', $school->status?->value) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="inactive" {{ old('status', $school->status?->value) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Code -->
                <div>
                    <label for="code" class="block text-xs font-semibold text-slate-700 mb-1.5">Campus Code</label>
                    <input type="text"
                           id="code"
                           name="code"
                           value="{{ old('code', $school->code) }}"
                           placeholder="e.g. EDU-001"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('code') border-rose-300 ring-rose-200 @enderror">
                </div>

                <!-- Established Year -->
                <div>
                    <label for="established_year" class="block text-xs font-semibold text-slate-700 mb-1.5">Founding Year</label>
                    <input type="number"
                           id="established_year"
                           name="established_year"
                           min="1800"
                           max="{{ date('Y') + 1 }}"
                           value="{{ old('established_year', $school->established_year) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <!-- Website -->
                <div>
                    <label for="website" class="block text-xs font-semibold text-slate-700 mb-1.5">Official Website URL</label>
                    <input type="url"
                           id="website"
                           name="website"
                           value="{{ old('website', $school->website) }}"
                           placeholder="https://example.edu"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>
            </div>
        </div>

        <!-- Card 2: Contact Channels & Location -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">2. Contact Channels & Location</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Physical campus address and administrative communications.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Contact Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $school->email) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('email') border-rose-300 ring-rose-200 @enderror">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Campus Phone</label>
                    <input type="text"
                           id="phone"
                           name="phone"
                           value="{{ old('phone', $school->phone) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <div class="sm:col-span-2">
                    <label for="address" class="block text-xs font-semibold text-slate-700 mb-1.5">Campus Street Address</label>
                    <input type="text"
                           id="address"
                           name="address"
                           value="{{ old('address', $school->address) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <div>
                    <label for="city" class="block text-xs font-semibold text-slate-700 mb-1.5">City</label>
                    <input type="text"
                           id="city"
                           name="city"
                           value="{{ old('city', $school->city) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <div>
                    <label for="state" class="block text-xs font-semibold text-slate-700 mb-1.5">State / Province</label>
                    <input type="text"
                           id="state"
                           name="state"
                           value="{{ old('state', $school->state) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <div>
                    <label for="country" class="block text-xs font-semibold text-slate-700 mb-1.5">Country</label>
                    <input type="text"
                           id="country"
                           name="country"
                           value="{{ old('country', $school->country) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <div>
                    <label for="postal_code" class="block text-xs font-semibold text-slate-700 mb-1.5">Postal / ZIP Code</label>
                    <input type="text"
                           id="postal_code"
                           name="postal_code"
                           value="{{ old('postal_code', $school->postal_code) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>
            </div>
        </div>

        <!-- Card 3: Localization & Currency -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">3. Localization & Regional Configuration</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Operating currency for fee ledgers and timezone for roll-call attendance.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="timezone" class="block text-xs font-semibold text-slate-700 mb-1.5">Timezone <span class="text-rose-500">*</span></label>
                    <select id="timezone"
                            name="timezone"
                            required
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs cursor-pointer"
                            data-placeholder="Select Institutional Timezone">
                        @foreach($timezones as $tz)
                            <option value="{{ $tz }}" {{ old('timezone', $school->timezone) === $tz ? 'selected' : '' }}>
                                {{ $tz }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="currency" class="block text-xs font-semibold text-slate-700 mb-1.5">Operating Currency <span class="text-rose-500">*</span></label>
                    <select id="currency"
                            name="currency"
                            required
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs cursor-pointer"
                            data-placeholder="Select Operating Currency">
                        @foreach($currencies as $code => $label)
                            <option value="{{ $code }}" {{ old('currency', $school->currency) === $code ? 'selected' : '' }}>
                                {{ $label }} ({{ $code }})
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <!-- Card 4: Institutional Branding & Media (Real-Time Dropify Upload) -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="border-b border-slate-100 pb-3 mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">4. Institutional Branding & Visual Identity</h2>
                    <p class="text-[11px] text-slate-500 mt-0.5">Upload new branding files or retain current assets. Real-time preview is active.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-md border border-violet-100 bg-violet-50/80 px-2 py-1 text-[10px] font-semibold text-violet-700 w-fit">
                    <svg class="h-3 w-3 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Direct Server Storage</span>
                </span>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Logo Upload -->
                <div>
                    <label for="logo" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        School Crest / Primary Logo
                    </label>
                    <input type="file"
                           id="logo"
                           name="logo"
                           class="dropify"
                           data-height="160"
                           data-max-file-size="5M"
                           data-allowed-file-extensions="png jpg jpeg svg webp"
                           data-default-file="{{ $school->logo ? $school->logo_url : '' }}" />
                    <p class="mt-1.5 text-[10px] text-slate-400">Supported: PNG, JPG, SVG, WebP (Max: 5MB). Leave blank to keep existing crest.</p>
                    @error('logo')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Favicon Upload -->
                <div>
                    <label for="favicon" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Browser Tab Favicon
                    </label>
                    <input type="file"
                           id="favicon"
                           name="favicon"
                           class="dropify"
                           data-height="160"
                           data-max-file-size="5M"
                           data-allowed-file-extensions="ico png svg"
                           data-default-file="{{ $school->favicon ? $school->favicon_url : '' }}" />
                    <p class="mt-1.5 text-[10px] text-slate-400">Supported: ICO, PNG, SVG (Max: 5MB). Recommended 32x32 or 64x64 square ratio.</p>
                    @error('favicon')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.schools.show', $school) }}"
               class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                Cancel
            </a>
            <button type="submit"
                    id="btn-edit-submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Update Institutional Record</span>
            </button>
        </div>
    </form>

</div>
@endsection

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
        border-color: #7c3aed !important;
        background-color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.08) !important;
    }
    .dropify-wrapper .dropify-message p {
        font-size: 12px !important;
        color: #475569 !important;
        font-weight: 500 !important;
        margin-top: 6px !important;
    }
    .dropify-wrapper .dropify-message span.file-icon {
        font-size: 32px !important;
        color: #7c3aed !important;
    }
    .dropify-wrapper .dropify-clear {
        border-radius: 0.375rem !important;
        background-color: #f43f5e !important;
        color: #ffffff !important;
        border: none !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        top: 10px !important;
        right: 10px !important;
        z-index: 20 !important;
        transition: all 0.15s ease-in-out !important;
    }
    .dropify-wrapper.has-preview .dropify-clear {
        display: block !important;
        opacity: 0.95 !important;
    }
    .dropify-wrapper .dropify-clear:hover {
        background-color: #e11d48 !important;
        opacity: 1 !important;
        transform: scale(1.02);
    }
    .dropify-wrapper .dropify-preview {
        background-color: #f8fafc !important;
        padding: 8px !important;
    }
    .dropify-wrapper .dropify-preview .dropify-render img {
        border-radius: 0.5rem !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
        background-color: #ffffff !important;
        padding: 4px !important;
        max-height: 140px !important;
        max-width: 90% !important;
        object-fit: contain !important;
        margin: 0 auto !important;
    }
    .dropify-wrapper .dropify-infos {
        background: rgba(15, 23, 42, 0.75) !important;
        backdrop-filter: blur(2px) !important;
    }
    .dropify-wrapper .dropify-infos .dropify-infos-inner p.dropify-infos-message {
        font-size: 11px !important;
        color: #f1f5f9 !important;
    }
    .dropify-wrapper .dropify-infos .dropify-infos-inner p.dropify-filename {
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #ffffff !important;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('admin-assets/js/dropify.min.js') }}"></script>
<script>
    (function() {
        function initDropify() {
            if (window.jQuery && typeof window.jQuery.fn.dropify === 'function') {
                window.jQuery('.dropify').each(function() {
                    var $el = window.jQuery(this);
                    if (!$el.data('dropify')) {
                        $el.dropify({
                            messages: {
                                'default': 'Drag and drop an image here or click to browse',
                                'replace': 'Drag and drop or click to replace image',
                                'remove':  'Remove',
                                'error':   'Ooops, something wrong happened.'
                            },
                            error: {
                                'fileSize': 'The file size is too big (@{{ value }} max).',
                                'fileExtension': 'The file format is not allowed (@{{ value }} only).'
                            },
                            imgFileExtensions: ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp', 'svg', 'ico']
                        });
                    }
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initDropify);
        } else {
            initDropify();
        }
        window.addEventListener('load', initDropify);

        // Double-submit prevention
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('edit-school-form');
            if (form) {
                form.addEventListener('submit', function() {
                    var btn = document.getElementById('btn-edit-submit');
                    if (btn) {
                        btn.disabled = true;
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                        var label = btn.querySelector('span');
                        if (label) label.textContent = 'Saving Changes...';
                    }
                });
            }
        });
    })();
</script>
@endpush
