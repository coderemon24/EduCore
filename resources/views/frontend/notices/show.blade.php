@extends('layouts.app')

@section('title', $notice->title . ' | নোটিশ')

@section('content')
{{-- Breadcrumbs --}}
<div class="bg-slate-100 border-b border-slate-200 py-3 text-xs text-slate-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-2">
        <a href="{{ route('frontend.home') }}" class="hover:text-emerald-800 transition-colors">প্রচ্ছদ</a>
        <span>/</span>
        <a href="{{ route('frontend.notices.index') }}" class="hover:text-emerald-800 transition-colors">নোটিশ বোর্ড</a>
        <span>/</span>
        <span class="text-slate-900 font-medium truncate max-w-md">{{ $notice->title }}</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Main Notice Bulletin Article (Col 8) --}}
        <main class="lg:col-span-8 bg-white rounded-xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-6">
            {{-- Header & Meta --}}
            <header class="space-y-3 pb-6 border-b border-slate-100">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 text-xs font-bold uppercase tracking-wider border border-emerald-200">
                        {{ \App\Models\Notice::TYPES[$notice->type] ?? 'সাধারণ' }}
                    </span>
                    @if($notice->is_pinned)
                        <span class="px-2.5 py-1 rounded bg-amber-100 text-amber-900 text-xs font-bold border border-amber-200">
                            পিন করা
                        </span>
                    @endif
                    <span class="text-xs text-slate-500 font-medium">
                        উদ্দেশ্যে: {{ \App\Models\Notice::AUDIENCES[$notice->target_audience] ?? 'সকল' }}
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-tight">
                    {{ $notice->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-xs text-slate-500 pt-1">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>প্রকাশিত: {{ \Carbon\Carbon::parse($notice->published_at)->format('d F Y, h:i A') }}</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>দেখা হয়েছে: {{ $notice->view_count }} বার</span>
                    </span>
                </div>
            </header>

            {{-- Notice Content Body --}}
            <div class="text-sm sm:text-base text-slate-800 leading-relaxed space-y-4">
                {!! nl2br(e($notice->content)) !!}
            </div>

            {{-- Downloadable Attachment Box (If attached) --}}
            @if($notice->attachment_path)
                <div class="mt-8 p-4 sm:p-5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-900">{{ $notice->attachment_name ?: 'সার্কুলার ডকুমেন্ট ফাইল' }}</span>
                            <span class="block text-[11px] text-slate-500">অফিসিয়াল স্বাক্ষরিত কপি</span>
                        </div>
                    </div>
                    <a href="{{ route('frontend.notices.download', $notice->slug ?: $notice->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-800 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>ফাইল ডাউনলোড করুন</span>
                    </a>
                </div>
            @endif

            {{-- Signature / Sign-off Strip --}}
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-500 gap-2">
                <span>আদেশক্রমে: বিদ্যালয় কর্তৃপক্ষ</span>
                <span>{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}</span>
            </div>
        </main>

        {{-- Sidebar (Col 4) --}}
        <aside class="lg:col-span-4 space-y-6">
            {{-- Back to Board Button --}}
            <a href="{{ route('frontend.notices.index') }}"
               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-semibold text-xs shadow-2xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>সকল বিজ্ঞপ্তিতে ফিরে যান</span>
            </a>

            {{-- Related Notices --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                <div class="bg-emerald-900 text-white px-4 py-3">
                    <h3 class="text-xs font-bold tracking-tight">একই ক্যাটাগরির নোটিশ</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($relatedNotices as $item)
                        <div class="p-3 text-xs hover:bg-slate-50 transition-colors">
                            <span class="text-[10px] text-slate-400 block mb-0.5">{{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}</span>
                            <a href="{{ route('frontend.notices.show', $item->slug ?: $item->id) }}" class="font-semibold text-slate-800 hover:text-emerald-800 transition-colors line-clamp-2">
                                {{ $item->title }}
                            </a>
                        </div>
                    @empty
                        <div class="p-4 text-center text-xs text-slate-400">
                            কোনো সম্পর্কিত নোটিশ নেই।
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Emergency Contact Card --}}
            <div class="bg-emerald-900 text-white rounded-xl p-5 shadow-xs space-y-3">
                <h4 class="text-xs font-bold text-amber-400 uppercase tracking-wider">জরুরি যোগাযোগ</h4>
                <p class="text-xs text-emerald-100 leading-relaxed">
                    বিজ্ঞপ্তি সংক্রান্ত কোনো জিজ্ঞাসা থাকলে বিদ্যালয় অফিসে সরাসরি যোগাযোগ করুন:
                </p>
                <div class="text-xs font-semibold text-white space-y-1">
                    <p>ফোন: {{ $school->phone ?? '+880 1712-345678' }}</p>
                    <p>ইমেইল: {{ $school->email ?? 'info@sonatalahighschool.edu.bd' }}</p>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

