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

    <form action="{{ route('admin.schools.update', $school) }}" method="POST" id="edit-school-form" class="space-y-6">
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
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs cursor-pointer">
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
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs cursor-pointer">
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
                            class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs cursor-pointer">
                        @foreach($currencies as $code => $label)
                            <option value="{{ $code }}" {{ old('currency', $school->currency) === $code ? 'selected' : '' }}>
                                {{ $label }} ({{ $code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="logo" class="block text-xs font-semibold text-slate-700 mb-1.5">Institutional Crest / Logo Asset Path</label>
                    <input type="text"
                           id="logo"
                           name="logo"
                           value="{{ old('logo', $school->logo) }}"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-mono text-slate-800 transition hover:bg-white focus:bg-white focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
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

@push('scripts')
<script>
    // jQuery prevent duplicate submits
    $(function() {
        $('#edit-school-form').on('submit', function() {
            var $btn = $('#btn-edit-submit');
            $btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed').text('Saving Changes...');
        });
    });
</script>
@endpush
