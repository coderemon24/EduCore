@extends('layouts.admin')

@section('title', 'Edit ' . $guardian->name . ' | Parents & Guardians')

@section('admin-content')
<div class="space-y-4">

    {{-- Breadcrumb & Title --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.guardians.index') }}" class="text-slate-600 hover:text-violet-600 transition">Parents</a>
                <span>/</span>
                <a href="{{ route('admin.guardians.show', $guardian) }}" class="text-slate-600 hover:text-violet-600 transition">{{ $guardian->name }}</a>
                <span>/</span>
                <span class="font-medium text-slate-700">Edit</span>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Edit Parent</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Update phone number, email, and contact address.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.guardians.show', $guardian) }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Cancel</span>
            </a>
        </div>
    </div>

    {{-- Validation Error Alert --}}
    @if(isset($errors) && $errors->any())
        <div class="rounded-lg border border-rose-200 bg-rose-50/70 p-3 text-xs text-rose-800 space-y-1">
            <div class="font-semibold">Please fix the following errors:</div>
            <ul class="list-disc list-inside space-y-0.5 text-rose-700 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.guardians.update', $guardian) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-2xs space-y-4">
            <div class="border-b border-slate-100 pb-2">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Parent Information</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                {{-- Name --}}
                <div class="space-y-1">
                    <label for="name" class="block font-semibold text-slate-700">
                        Full Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $guardian->name) }}"
                           required
                           class="w-full rounded-md border @error('name') border-rose-400 bg-rose-50/20 @else border-slate-200 bg-white @enderror px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden transition shadow-2xs">
                    @error('name')
                        <p class="text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="space-y-1">
                    <label for="phone" class="block font-semibold text-slate-700">
                        Phone Number <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="phone"
                           name="phone"
                           value="{{ old('phone', $guardian->phone) }}"
                           required
                           inputmode="tel"
                           class="w-full font-mono rounded-md border @error('phone') border-rose-400 bg-rose-50/20 @else border-slate-200 bg-white @enderror px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden transition shadow-2xs">
                    @error('phone')
                        <p class="text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="space-y-1">
                    <label for="email" class="block font-semibold text-slate-700">
                        Email Address
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $guardian->email) }}"
                           inputmode="email"
                           class="w-full rounded-md border @error('email') border-rose-400 bg-rose-50/20 @else border-slate-200 bg-white @enderror px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden transition shadow-2xs">
                    @error('email')
                        <p class="text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Job / Occupation --}}
                <div class="space-y-1">
                    <label for="occupation" class="block font-semibold text-slate-700">
                        Job / Occupation
                    </label>
                    <input type="text"
                           id="occupation"
                           name="occupation"
                           value="{{ old('occupation', $guardian->occupation) }}"
                           placeholder="e.g. Teacher, Engineer, Doctor"
                           class="w-full rounded-md border @error('occupation') border-rose-400 bg-rose-50/20 @else border-slate-200 bg-white @enderror px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden transition shadow-2xs">
                    @error('occupation')
                        <p class="text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- National ID --}}
                <div class="space-y-1 sm:col-span-2">
                    <label for="national_id" class="block font-semibold text-slate-700">
                        National ID / NID
                    </label>
                    <input type="text"
                           id="national_id"
                           name="national_id"
                           value="{{ old('national_id', $guardian->national_id) }}"
                           placeholder="National ID or passport number"
                           class="w-full font-mono rounded-md border @error('national_id') border-rose-400 bg-rose-50/20 @else border-slate-200 bg-white @enderror px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden transition shadow-2xs">
                    @error('national_id')
                        <p class="text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="space-y-1 sm:col-span-2">
                    <label for="address" class="block font-semibold text-slate-700">
                        Address
                    </label>
                    <textarea id="address"
                              name="address"
                              rows="3"
                              placeholder="Home address"
                              class="w-full rounded-md border @error('address') border-rose-400 bg-rose-50/20 @else border-slate-200 bg-white @enderror px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 focus:outline-hidden transition shadow-2xs">{{ old('address', $guardian->address) }}</textarea>
                    @error('address')
                        <p class="text-[11px] text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-2.5">
            <a href="{{ route('admin.guardians.show', $guardian) }}"
               class="rounded-lg border border-slate-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                Cancel
            </a>
            <button type="submit"
                    class="rounded-lg bg-violet-600 px-4 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                Save Parent
            </button>
        </div>
    </form>

</div>
@endsection
