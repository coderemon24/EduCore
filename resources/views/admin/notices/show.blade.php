@extends('layouts.admin')

@section('title', $notice->title . ' | Notice Board')

@section('admin-content')
<div class="space-y-4 max-w-4xl">

    {{-- Breadcrumb & Actions --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.notices.index') }}" class="text-slate-600 hover:text-violet-600 transition">Notices</a>
                <span>/</span>
                <span class="font-medium text-slate-700">Notice #{{ $notice->id }}</span>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 truncate max-w-xl">{{ $notice->title }}</h1>
        </div>

        <div class="flex items-center gap-2">
            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Print</span>
            </button>

            <a href="{{ route('admin.notices.edit', $notice) }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit</span>
            </a>

            <a href="{{ route('admin.notices.index') }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition cursor-pointer">
                <span>Back</span>
            </a>
        </div>
    </div>

    {{-- Main Notice Card --}}
    <div class="rounded-lg border border-slate-200 bg-white shadow-2xs overflow-hidden">

        {{-- Notice Meta Bar --}}
        <div class="border-b border-slate-100 bg-slate-50 px-5 py-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-block px-2.5 py-1 rounded text-xs font-medium bg-slate-200 text-slate-700">
                        {{ \App\Models\Notice::TYPES[$notice->type] ?? ucfirst($notice->type) }}
                    </span>

                    <span class="inline-block px-2.5 py-1 rounded text-xs font-medium bg-violet-50 text-violet-700 border border-violet-100">
                        @if ($notice->target_audience === 'class_specific' && $notice->academicClass)
                            Class: {{ $notice->academicClass->name }}
                        @elseif ($notice->target_audience === 'all')
                            Audience: Everyone
                        @elseif ($notice->target_audience === 'teachers')
                            Audience: Teachers &amp; Staff
                        @elseif ($notice->target_audience === 'students')
                            Audience: Students
                        @elseif ($notice->target_audience === 'guardians')
                            Audience: Parents
                        @else
                            Audience: {{ ucfirst($notice->target_audience) }}
                        @endif
                    </span>

                    @if ($notice->is_pinned)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            Pinned
                        </span>
                    @endif

                    @if ($notice->status === 'published')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Published
                        </span>
                    @elseif ($notice->status === 'draft')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-600">
                            Draft
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-600">
                            Archived
                        </span>
                    @endif
                </div>

                <div class="text-xs text-slate-500">
                    {{ number_format($notice->view_count) }} views
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 mt-3 pt-3 border-t border-slate-200/60">
                <span>By <strong class="text-slate-800">{{ $notice->author?->name ?? 'Admin' }}</strong></span>
                <span>Date: <strong class="text-slate-800">{{ $notice->published_at ? $notice->published_at->format('M d, Y') : 'Draft' }}</strong></span>
                @if ($notice->expires_at)
                    <span>Expires: <strong class="text-rose-600">{{ $notice->expires_at->format('M d, Y') }}</strong></span>
                @endif
            </div>
        </div>

        {{-- Notice Content Body --}}
        <div class="p-5 sm:p-6 space-y-6">
            <div class="text-sm text-slate-800 leading-relaxed space-y-3 prose prose-slate max-w-none">
                {!! strip_tags($notice->content, '<p><br><b><strong><i><em><u><s><ul><ol><li><a><h1><h2><h3><h4><h5><h6><blockquote><table><thead><tbody><tr><th><td><span><div><hr><img><code><pre>') !!}
            </div>

            {{-- Attachment Box --}}
            @if ($notice->attachment_path)
                <div class="pt-4 border-t border-slate-100">
                    <span class="text-xs font-semibold text-slate-700 block mb-2">Attached File</span>
                    <div class="flex items-center justify-between p-3 rounded border border-slate-200 bg-slate-50">
                        <div class="flex items-center gap-2 min-w-0">
                            <svg class="h-5 w-5 text-violet-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <span class="text-xs font-medium text-slate-900 truncate">{{ $notice->attachment_name ?? 'Download File' }}</span>
                        </div>
                        <a href="{{ asset('storage/' . $notice->attachment_path) }}"
                           target="_blank"
                           class="inline-flex items-center gap-1 rounded bg-violet-600 hover:bg-violet-700 px-3 py-1.5 text-xs font-semibold text-white transition">
                            Download
                        </a>
                    </div>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
