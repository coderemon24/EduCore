@extends('layouts.app')

@section('title', $notice->title . ' | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($notice->content), 160))

@section('content')

{{-- Breadcrumb (Standard Clean Layout Matching Classes Page) --}}
<div class="bg-gray-100 border-b border-gray-200 py-3.5 text-xs text-gray-600" id="overview">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 flex items-center justify-between">
        <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>প্রচ্ছদ</span>
            </a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('frontend.notices.index') }}" class="hover:text-[#005030] transition-colors">
                নোটিশ বোর্ড
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold truncate max-w-xs">{{ $notice->title }}</span>
        </nav>
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}</span>
    </div>
</div>

{{-- Top Hero Banner: Matches Reference Image Clean Green Header --}}
<div class="bg-[#005030] text-white py-8 sm:py-10 border-b border-emerald-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        {{-- Pill Badge --}}
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-[#005030] text-xs font-bold shadow-xs">
            <i class="fa-solid fa-file-lines text-[#005030]"></i>
            <span>{{ \App\Models\Notice::TYPES[$notice->type] ?? 'বিজ্ঞপ্তি' }}</span>
        </div>

        {{-- Page Heading --}}
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-white mt-3 mb-2 tracking-tight leading-snug">
            {{ $notice->title }}
        </h1>

        {{-- Sub-narrative context --}}
        <p class="text-xs sm:text-sm text-emerald-100/90 font-medium flex items-center gap-2 flex-wrap">
            <span>প্রকাশিত: {{ \Carbon\Carbon::parse($notice->published_at)->format('d F Y, h:i A') }}</span>
            <span class="text-emerald-300">·</span>
            <span>দেখা হয়েছে: {{ $notice->view_count }} বার</span>
        </p>
    </div>
</div>

{{-- Main Content Canvas --}}
<section class="py-8 sm:py-12 bg-slate-50/60 min-h-[500px]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Main Notice Bulletin Article (Col 8) --}}
            <main class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-2xs space-y-6">
                {{-- Meta Bar --}}
                <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#005030] text-xs font-bold border border-emerald-200">
                            {{ \App\Models\Notice::TYPES[$notice->type] ?? 'সাধারণ' }}
                        </span>
                        @if($notice->is_pinned)
                            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 text-xs font-bold border border-amber-300">
                                পিন করা
                            </span>
                        @endif
                    </div>
                    <span class="text-slate-500 font-medium">
                        উদ্দেশ্য: {{ \App\Models\Notice::AUDIENCES[$notice->target_audience] ?? 'সকল' }}
                    </span>
                </div>

                {{-- Notice Content Body --}}
                <div class="text-base text-slate-800 leading-relaxed space-y-4">
                    {!! nl2br(e($notice->content)) !!}
                </div>

                {{-- Downloadable Attachment Box (If attached) --}}
                @if($notice->attachment_path)
                    <div class="mt-6 p-4 sm:p-5 rounded-2xl bg-[#eef7f2] border border-[#d2ecd9] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-2xs">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-[#005030] text-white flex items-center justify-center text-lg shrink-0 shadow-xs">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-slate-900">{{ $notice->attachment_name ?: 'সার্কুলার ডকুমেন্ট ফাইল' }}</span>
                                <span class="block text-xs text-slate-500">অফিসিয়াল স্বাক্ষরিত কপি</span>
                            </div>
                        </div>
                        <a href="{{ route('frontend.notices.download', $notice->slug ?: $notice->id) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#005030] hover:bg-[#003820] text-white font-bold text-xs transition-colors shadow-xs">
                            <i class="fa-solid fa-download text-xs"></i>
                            <span>ফাইল ডাউনলোড করুন</span>
                        </a>
                    </div>
                @endif

                {{-- Signature / Sign-off Strip --}}
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between text-xs text-slate-500 gap-2">
                    <span class="font-medium">আদেশক্রমে: বিদ্যালয় কর্তৃপক্ষ</span>
                    <span>{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}</span>
                </div>
            </main>

            {{-- Sidebar (Col 4) --}}
            <aside class="lg:col-span-4 space-y-5">
                {{-- Back to Board Button --}}
                <a href="{{ route('frontend.notices.index') }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-white border border-slate-200/90 text-slate-700 hover:bg-slate-50 font-bold text-xs shadow-2xs transition-colors">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>সকল বিজ্ঞপ্তিতে ফিরে যান</span>
                </a>

                {{-- Related Notices --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs">
                    <div class="bg-[#005030] text-white px-5 py-3.5">
                        <h3 class="text-xs font-bold tracking-wider uppercase">একই ক্যাটাগরির নোটিশ</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($relatedNotices as $item)
                            <div class="p-3.5 text-xs hover:bg-slate-50 transition-colors">
                                <span class="text-[10px] text-slate-400 block mb-0.5">{{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}</span>
                                <a href="{{ route('frontend.notices.show', $item->slug ?: $item->id) }}" class="text-xs font-bold text-slate-800 hover:text-[#005030] transition-colors line-clamp-2">
                                    {{ $item->title }}
                                </a>
                            </div>
                        @empty
                            <div class="p-5 text-center text-xs text-slate-400">
                                কোনো সম্পর্কিত নোটিশ নেই।
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Helpdesk Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-2xs space-y-2">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">জরুরি তথ্য যোগাযোগ</h4>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        বিজ্ঞপ্তি সংক্রান্ত কোনো জিজ্ঞাসা থাকলে বিদ্যালয় অফিসে সরাসরি যোগাযোগ করুন।
                    </p>
                    <div class="text-xs text-[#005030] font-bold font-mono pt-1">
                        {{ $school->phone ?? '+৮৮০ ১৭১২-৩৪৫৬৭৮' }}
                    </div>
                </div>
            </aside>

        </div>
    </div>
</section>

@endsection
