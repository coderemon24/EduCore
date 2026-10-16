@extends('layouts.admin')

@section('title', 'Onboard New Academic Tenant | Super Admin')

@section('admin-content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col gap-2 pb-1">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.schools.index') }}" class="text-slate-600 hover:text-violet-600 transition">Schools</a>
            <span>/</span>
            <span class="font-semibold text-slate-700">Onboard Tenant</span>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Onboard New Academic Tenant</h1>
                <p class="text-xs text-slate-500 mt-0.5">Register a new school institution on the multi-tenant SaaS platform.</p>
            </div>
            <a href="{{ route('admin.schools.index') }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs transition hover:bg-slate-50 hover:border-slate-300">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Directory</span>
            </a>
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

    <form action="{{ route('admin.schools.store') }}" method="POST" enctype="multipart/form-data" id="create-school-form" class="space-y-6">
        @csrf

        <!-- Card 1: Core Institutional Identity -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="border-b border-slate-100 pb-3 mb-5">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">1. Institutional Identity & Status</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Official registered name, unique campus code, and initial tenant status.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <!-- Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Institution Official Name <span class="text-rose-500">*</span></label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="e.g. Oxford Cambridge International Academy"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('name') border-rose-300 ring-rose-200 @enderror">
                    @error('name')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Initial Status -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1.5">Initial Status <span class="text-rose-500">*</span></label>
                    <select id="status"
                            name="status"
                            required
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs cursor-pointer">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (Operational)</option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending Verification</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Code -->
                <div>
                    <label for="code" class="block text-xs font-semibold text-slate-700 mb-1.5">Campus Code</label>
                    <input type="text"
                           id="code"
                           name="code"
                           value="{{ old('code') }}"
                           placeholder="e.g. OCI-001"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('code') border-rose-300 ring-rose-200 @enderror">
                    <p class="mt-1 text-[10px] text-slate-400">Unique identifier for admissions & invoicing.</p>
                </div>

                <!-- Established Year -->
                <div>
                    <label for="established_year" class="block text-xs font-semibold text-slate-700 mb-1.5">Founding Year</label>
                    <input type="number"
                           id="established_year"
                           name="established_year"
                           min="1800"
                           max="{{ date('Y') + 1 }}"
                           value="{{ old('established_year', date('Y')) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <!-- Website -->
                <div>
                    <label for="website" class="block text-xs font-semibold text-slate-700 mb-1.5">Official Website URL</label>
                    <input type="url"
                           id="website"
                           name="website"
                           value="{{ old('website') }}"
                           placeholder="https://example.edu"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
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
                           value="{{ old('email') }}"
                           placeholder="principal@oxford.edu"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('email') border-rose-300 ring-rose-200 @enderror">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Campus Phone</label>
                    <input type="text"
                           id="phone"
                           name="phone"
                           value="{{ old('phone') }}"
                           placeholder="+1 (555) 234-5678"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <div class="sm:col-span-2">
                    <label for="address" class="block text-xs font-semibold text-slate-700 mb-1.5">Campus Street Address</label>
                    <input type="text"
                           id="address"
                           name="address"
                           value="{{ old('address') }}"
                           placeholder="12 Academic Boulevard"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <div>
                    <label for="city" class="block text-xs font-semibold text-slate-700 mb-1.5">City</label>
                    <input type="text"
                           id="city"
                           name="city"
                           value="{{ old('city') }}"
                           placeholder="Boston"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <div>
                    <label for="state" class="block text-xs font-semibold text-slate-700 mb-1.5">State / Province</label>
                    <input type="text"
                           id="state"
                           name="state"
                           value="{{ old('state') }}"
                           placeholder="Massachusetts"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <div>
                    <label for="country" class="block text-xs font-semibold text-slate-700 mb-1.5">Country</label>
                    <input type="text"
                           id="country"
                           name="country"
                           value="{{ old('country', 'United States') }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <div>
                    <label for="postal_code" class="block text-xs font-semibold text-slate-700 mb-1.5">Postal / ZIP Code</label>
                    <input type="text"
                           id="postal_code"
                           name="postal_code"
                           value="{{ old('postal_code') }}"
                           placeholder="02108"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
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
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs cursor-pointer">
                        @foreach($timezones as $tz)
                            <option value="{{ $tz }}" {{ old('timezone', 'America/New_York') === $tz ? 'selected' : '' }}>
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
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs cursor-pointer">
                        @foreach($currencies as $code => $label)
                            <option value="{{ $code }}" {{ old('currency', 'USD') === $code ? 'selected' : '' }}>
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
                    <p class="text-[11px] text-slate-500 mt-0.5">Upload high-resolution logo and browser tab favicon directly with instant real-time preview.</p>
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
                           data-max-file-size="2M"
                           data-allowed-file-extensions="png jpg jpeg svg webp"
                           data-default-file="" />
                    <p class="mt-1.5 text-[10px] text-slate-400">Supported: PNG, JPG, SVG, WebP (Max: 2MB). Recommended transparent background.</p>
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
                           data-max-file-size="1M"
                           data-allowed-file-extensions="ico png svg"
                           data-default-file="" />
                    <p class="mt-1.5 text-[10px] text-slate-400">Supported: ICO, PNG, SVG (Max: 1MB). Recommended 32x32 or 64x64 square ratio.</p>
                    @error('favicon')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.schools.index') }}"
               class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                Cancel
            </a>
            <button type="submit"
                    id="btn-create-submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Complete Tenant Onboarding</span>
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
        font-size: 10px !important;
        padding: 3px 8px !important;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('admin-assets/js/dropify.min.js') }}"></script>
<script>
    $(function() {
        // Initialize Dropify for rich real-time image preview
        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop an image here or click to browse',
                'replace': 'Drag and drop or click to replace image',
                'remove':  'Remove',
                'error':   'Ooops, something wrong happened.'
            },
            error: {
                'fileSize': 'The file size is too big (@{{ value }} max).',
                'fileExtension': 'The file format is not allowed (@{{ value }} only).'
            }
        });

        // Double-submit prevention
        $('#create-school-form').on('submit', function() {
            var $btn = $('#btn-create-submit');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed').text('Onboarding Tenant...');
        });
    });
</script>
@endpush
