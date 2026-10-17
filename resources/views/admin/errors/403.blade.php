@extends('layouts.admin')

@section('title', '403 Forbidden • Access Denied | Administration')

@section('admin-content')
<div class="min-h-[60vh] flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full text-center space-y-5">

        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 border border-rose-200">
            <svg class="h-7 w-7 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        <div class="space-y-1.5">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Access Denied
            </h1>
            <p class="text-xs text-slate-500 leading-relaxed max-w-sm mx-auto">
                You do not have permission to access this page or resource.
            </p>
        </div>

        @if(isset($exception) && $exception->getMessage())
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-left text-xs font-mono text-slate-700 break-words">
                <span class="text-[11px] font-sans font-semibold text-slate-500 block mb-0.5">Details</span>
                <p class="text-xs text-slate-800">{{ $exception->getMessage() }}</p>
            </div>
        @endif

        <div class="flex items-center justify-center gap-2.5 pt-1">
            <button type="button"
                    onclick="window.history.back();"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                Go Back
            </button>
            <a href="{{ route('admin.dashboard') }}"
               class="rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                Dashboard
            </a>
        </div>

        <p class="text-[11px] text-slate-400 pt-3 border-t border-slate-100">
            Contact your school administrator if you need access to this feature.
        </p>
    </div>
</div>
@endsection
