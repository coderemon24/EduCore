@extends('layouts.admin')

@section('title', 'Institution Profile & Settings | School Administration')

@section('admin-content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- Top Breadcrumb & Header -->
    <div class="flex flex-col gap-2 pb-1">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
            <span>/</span>
            <span class="text-slate-500">Settings</span>
            <span>/</span>
            <span class="font-semibold text-slate-700">Institution Profile</span>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Institution Profile & Settings</h1>
                <p class="text-xs text-slate-500 mt-0.5">Manage official campus identity, communication channels, localization, and branding details.</p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-2xs">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span>Active Institution: <b>{{ $school->name }}</b></span>
            </div>
        </div>
    </div>

    <!-- Success Feedback Flash Message -->
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50/80 p-4 text-xs font-medium text-emerald-800 shadow-xs flex items-center gap-3">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 shrink-0">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Validation Error Summary -->
    @if($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50/80 p-4 text-xs text-rose-800 shadow-xs">
            <div class="flex items-center gap-2 font-semibold mb-1 text-rose-900">
                <svg class="h-4 w-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Please correct the errors indicated below:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-2 text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.school.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Card 1: Core Institutional Identity -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="border-b border-slate-100 pb-4 mb-5">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">1. Core Institutional Identity</h2>
                <p class="text-xs text-slate-500 mt-0.5">Primary institutional registration identifiers and official campus naming.</p>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- School Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Institution Official Name <span class="text-rose-500">*</span></label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $school->name) }}"
                           required
                           placeholder="e.g. EduCore Academy & Preparatory School"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('name') border-rose-300 ring-rose-200 @enderror">
                    @error('name')
                        <p class="mt-1 text-[11px] text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- School Code -->
                <div>
                    <label for="code" class="block text-xs font-semibold text-slate-700 mb-1.5">Institutional Code / Registration ID</label>
                    <input type="text"
                           id="code"
                           name="code"
                           value="{{ old('code', $school->code) }}"
                           placeholder="e.g. EDU-001"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    <p class="mt-1 text-[10px] text-slate-400">Unique abbreviation used on student records and invoices.</p>
                </div>

                <!-- Established Year -->
                <div>
                    <label for="established_year" class="block text-xs font-semibold text-slate-700 mb-1.5">Founding / Established Year</label>
                    <input type="number"
                           id="established_year"
                           name="established_year"
                           min="1800"
                           max="{{ date('Y') + 1 }}"
                           value="{{ old('established_year', $school->established_year) }}"
                           placeholder="e.g. 2010"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <!-- Website URL -->
                <div class="sm:col-span-2">
                    <label for="website" class="block text-xs font-semibold text-slate-700 mb-1.5">Official Institution Portal / Website URL</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                        <input type="url"
                               id="website"
                               name="website"
                               value="{{ old('website', $school->website) }}"
                               placeholder="https://educore.test"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 py-2 pl-9 pr-3 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('website') border-rose-300 ring-rose-200 @enderror">
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Contact Details & Campus Location -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="border-b border-slate-100 pb-4 mb-5">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">2. Contact Channels & Campus Location</h2>
                <p class="text-xs text-slate-500 mt-0.5">Physical campus address, administrative communications, and postal details.</p>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Administrative Contact Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $school->email) }}"
                           placeholder="contact@educore.test"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('email') border-rose-300 ring-rose-200 @enderror">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Campus Phone Number</label>
                    <input type="text"
                           id="phone"
                           name="phone"
                           value="{{ old('phone', $school->phone) }}"
                           placeholder="+1 (555) 019-2834"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs @error('phone') border-rose-300 ring-rose-200 @enderror">
                </div>

                <!-- Street Address -->
                <div class="sm:col-span-2">
                    <label for="address" class="block text-xs font-semibold text-slate-700 mb-1.5">Campus Street Address</label>
                    <textarea id="address"
                              name="address"
                              rows="2"
                              placeholder="742 Evergreen Terrace, Academic District"
                              class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">{{ old('address', $school->address) }}</textarea>
                </div>

                <!-- City & State -->
                <div>
                    <label for="city" class="block text-xs font-semibold text-slate-700 mb-1.5">City</label>
                    <input type="text"
                           id="city"
                           name="city"
                           value="{{ old('city', $school->city) }}"
                           placeholder="Springfield"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <div>
                    <label for="state" class="block text-xs font-semibold text-slate-700 mb-1.5">State / Province</label>
                    <input type="text"
                           id="state"
                           name="state"
                           value="{{ old('state', $school->state) }}"
                           placeholder="Oregon"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <!-- Country & Postal Code -->
                <div>
                    <label for="country" class="block text-xs font-semibold text-slate-700 mb-1.5">Country</label>
                    <input type="text"
                           id="country"
                           name="country"
                           value="{{ old('country', $school->country) }}"
                           placeholder="United States"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>

                <div>
                    <label for="postal_code" class="block text-xs font-semibold text-slate-700 mb-1.5">Postal / ZIP Code</label>
                    <input type="text"
                           id="postal_code"
                           name="postal_code"
                           value="{{ old('postal_code', $school->postal_code) }}"
                           placeholder="97477"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                </div>
            </div>
        </div>

        <!-- Card 3: Localization & Currency -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="border-b border-slate-100 pb-4 mb-5">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">3. Localization & Accounting Currency</h2>
                <p class="text-xs text-slate-500 mt-0.5">Timezone for roll-call attendance timestamps and active currency for fee billing.</p>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- Timezone -->
                <div>
                    <label for="timezone" class="block text-xs font-semibold text-slate-700 mb-1.5">Institutional Timezone <span class="text-rose-500">*</span></label>
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

                <!-- Currency -->
                <div>
                    <label for="currency" class="block text-xs font-semibold text-slate-700 mb-1.5">Operating Financial Currency <span class="text-rose-500">*</span></label>
                    <select id="currency"
                            name="currency"
                            required
                            class="select2 w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs cursor-pointer"
                            data-placeholder="Select Financial Currency">
                        @foreach($currencies as $code => $label)
                            <option value="{{ $code }}" {{ old('currency', $school->currency) === $code ? 'selected' : '' }}>
                                {{ $label }} ({{ $code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Card 4: Branding Asset -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="border-b border-slate-100 pb-4 mb-5">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">4. Institutional Branding</h2>
                <p class="text-xs text-slate-500 mt-0.5">Asset path or public URL for the official school crest and sidebar header badge.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-start gap-6">
                <!-- Preview -->
                <div class="flex flex-col items-center justify-center p-4 rounded-xl border border-dashed border-slate-200 bg-slate-50/60 w-32 h-32 shrink-0">
                    <img src="{{ asset($school->logo ?: 'admin-assets/images/laravel-school-transparent.png') }}"
                         alt="Crest Preview"
                         class="h-16 w-16 object-contain">
                    <span class="mt-2 text-[10px] text-slate-400 font-semibold uppercase">Logo Asset</span>
                </div>

                <div class="flex-1 w-full">
                    <label for="logo" class="block text-xs font-semibold text-slate-700 mb-1.5">Logo Path or Image URL</label>
                    <input type="text"
                           id="logo"
                           name="logo"
                           value="{{ old('logo', $school->logo) }}"
                           placeholder="admin-assets/images/laravel-school-transparent.png"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono text-slate-800 placeholder-slate-400 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
                    <p class="mt-1.5 text-[11px] text-slate-400 leading-normal">
                        Default: <code>admin-assets/images/laravel-school-transparent.png</code>. Displayed on reports, receipts, and student report cards.
                    </p>
                </div>
            </div>
        </div>

        <!-- Submit Action Bar -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.dashboard') }}"
               class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                Cancel
            </a>
            <button type="submit"
                    id="btn-save-settings"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Save Institutional Changes</span>
            </button>
        </div>
    </form>

</div>
@endsection
