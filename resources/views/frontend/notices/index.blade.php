@extends('layouts.app')

@section('title', 'প্রাতিষ্ঠানিক নোটিশ বোর্ড | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের সকল প্রাতিষ্ঠানিক, একাডেমিক, পরীক্ষার রুটিন ও ছুটির সার্কুলার।')

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
            <span class="text-gray-500">বিজ্ঞপ্তি</span>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">নোটিশ বোর্ড</span>
        </nav>
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }} • সর্বশেষ আপডেট: {{ now()->format('d M Y') }}</span>
    </div>
</div>

{{-- Top Hero Banner: Matches Reference Image Clean Green Header --}}
<div class="bg-[#005030] text-white py-8 sm:py-10 border-b border-emerald-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        {{-- Pill Badge --}}
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-[#005030] text-xs font-bold shadow-xs">
            <i class="fa-solid fa-bullhorn text-[#005030]"></i>
            <span>বিজ্ঞপ্তি</span>
        </div>

        {{-- Page Heading --}}
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white mt-3 mb-2 tracking-tight">
            প্রাতিষ্ঠানিক নোটিশ বোর্ড
        </h1>

        {{-- Sub-narrative context --}}
        <p class="text-xs sm:text-sm text-emerald-100/90 font-medium">
            সোনাতলা উচ্চ বিদ্যালয়ের সকল প্রাতিষ্ঠানিক, একাডেমিক ও পরীক্ষার আনুষ্ঠানিক সার্কুলার ও নোটিশ
        </p>
    </div>
</div>

{{-- Main Content Canvas --}}
<section class="py-8 sm:py-12 bg-slate-50/60 min-h-[500px]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-6">

        {{-- Layout Grid: Notices List (Left) & Filter Sidebar (Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            {{-- Main Notice List (Col 8) --}}
            <div class="lg:col-span-8 space-y-4">
                @php
                    $displayNotices = [];

                    if (isset($notices) && $notices->isNotEmpty()) {
                        foreach ($notices as $dbNotice) {
                            $pubDate = $dbNotice->published_at ? \Carbon\Carbon::parse($dbNotice->published_at) : now();
                            $displayNotices[] = [
                                'id' => $dbNotice->slug ?: $dbNotice->id,
                                'title' => $dbNotice->title,
                                'day' => $pubDate->format('d'),
                                'month_year' => $pubDate->format('M Y'),
                                'type' => \App\Models\Notice::TYPES[$dbNotice->type] ?? 'সাধারণ',
                                'audience' => \App\Models\Notice::AUDIENCES[$dbNotice->target_audience] ?? 'সকল',
                                'is_pinned' => (bool)$dbNotice->is_pinned,
                                'excerpt' => \Illuminate\Support\Str::limit(strip_tags($dbNotice->content), 150),
                            ];
                        }
                    } elseif (!request()->filled('q') && !request()->filled('type')) {
                        // Curated Fallbacks
                        $displayNotices = [
                            [
                                'id' => 'admission-notice-2026',
                                'title' => '২০২৬ শিক্ষাবর্ষে ৬ষ্ঠ থেকে ৯ম শ্রেণিতে ভর্তি আবেদন সংক্রান্ত জরুরি বিজ্ঞপ্তি',
                                'day' => '১৯',
                                'month_year' => 'Oct 2026',
                                'type' => 'ভর্তি বিজ্ঞপ্তি',
                                'audience' => 'অভিভাবক ও শিক্ষার্থী',
                                'is_pinned' => true,
                                'excerpt' => 'সোনাতলা উচ্চ বিদ্যালয়ের ২০২৬ শিক্ষাবর্ষের সকল শ্রেণিতে ভর্তিচ্ছু শিক্ষার্থীদের অবগতির জন্য জানানো যাচ্ছে যে, আগামী ৩০ নভেম্বর ২০২৬ পর্যন্ত আবেদন ফরম বিতরণ ও জমা নেওয়া হবে...',
                            ],
                            [
                                'id' => 'annual-exam-routine-2026',
                                'title' => 'আসন্ন বার্ষিক পরীক্ষা ২০২৬ এর সংশোধিত সময়সূচি ও প্রবেশপত্র বিতরণ সংক্রান্ত নোটিশ',
                                'day' => '১৮',
                                'month_year' => 'Oct 2026',
                                'type' => 'পরীক্ষা সংক্রান্ত',
                                'audience' => 'সকল শিক্ষার্থী',
                                'is_pinned' => true,
                                'excerpt' => 'সকল শ্রেণির শিক্ষার্থীদের জানানো যাচ্ছে যে, অনিবার্য কারণবশত বার্ষিক পরীক্ষার সময়সূচিতে সামান্য পরিবর্তন আনা হয়েছে। সংশোধিত রুটিন অনুযায়ী পরীক্ষা অনুষ্ঠিত হবে...',
                            ],
                            [
                                'id' => 'digital-uid-update',
                                'title' => 'সকল শিক্ষার্থীর ডিজিটাল ইউনিক আইডি (UID) তথ্য হালনাগাদ ও জন্মনিবন্ধন সনদ জমা',
                                'day' => '১৫',
                                'month_year' => 'Oct 2026',
                                'type' => 'প্রশাসনিক নোটিশ',
                                'audience' => 'সকল শিক্ষার্থী',
                                'is_pinned' => false,
                                'excerpt' => 'শিক্ষা মন্ত্রণালয়ের নির্দেশনা মোতাবেক সকল শিক্ষার্থীর ১৭ ডিজিটের অনলাইন জন্মনিবন্ধন সনদ ও প্রয়োজনীয় তথ্যাদি আগামী ২৫ অক্টোবরের মধ্যে শ্রেণি শিক্ষকের নিকট জমা দিতে হবে...',
                            ],
                            [
                                'id' => 'stipend-beneficiaries-update',
                                'title' => 'উপবৃত্তি প্রাপ্ত শিক্ষার্থীদের তালিকা যাচাই ও নগদ মোবাইল ব্যাংকিং অ্যাকাউন্ট আপডেট',
                                'day' => '১২',
                                'month_year' => 'Oct 2026',
                                'type' => 'উপবৃত্তি সংক্রান্ত',
                                'audience' => 'উপবৃত্তিভোগী শিক্ষার্থী',
                                'is_pinned' => false,
                                'excerpt' => 'প্রধানমন্ত্রীর শিক্ষা সহায়তা ট্রাস্টের আওতায় উপবৃত্তিপ্রাপ্ত শিক্ষার্থীদের অবগতির জন্য জানানো যাচ্ছে যে, যাদের মোবাইল ব্যাংকিং একাউন্টে ত্রুটি রয়েছে তারা দ্রুত যোগাযোগ করুন...',
                            ],
                            [
                                'id' => 'puja-vacation-notice',
                                'title' => 'শ্রীশ্রী দুর্গাপূজা ও লক্ষ্মীপূজা উপলক্ষে বিদ্যালয় সাময়িক বন্ধের বিজ্ঞপ্তি',
                                'day' => '০৮',
                                'month_year' => 'Oct 2026',
                                'type' => 'ছুটির নোটিশ',
                                'audience' => 'সকলের জন্য',
                                'is_pinned' => false,
                                'excerpt' => 'সকল শিক্ষক, কর্মচারী ও শিক্ষার্থীদের জানানো যাচ্ছে যে, দুর্গাপূজা ও লক্ষ্মীপূজা উপলক্ষে আগামী ১০ অক্টোবর থেকে ১৮ অক্টোবর পর্যন্ত বিদ্যালয়ের শ্রেণি পাঠদান বন্ধ থাকবে...',
                            ],
                        ];
                    }
                @endphp

                @if(empty($displayNotices))
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-10 text-center shadow-2xs space-y-3">
                        <div class="w-12 h-12 mx-auto rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">কোনো নোটিশ পাওয়া যায়নি</h3>
                        <p class="text-xs text-slate-500">আপনার অনুসন্ধানের সাথে মিলে এমন কোনো বিজ্ঞপ্তি এই মুহূর্তে উপলব্ধ নেই।</p>
                        <div class="pt-2">
                            <a href="{{ route('frontend.notices.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#005030] text-white text-xs font-bold hover:bg-[#003820] transition-colors">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>সকল নোটিশ দেখুন</span>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs divide-y divide-slate-100 overflow-hidden">
                        @foreach($displayNotices as $n)
                            <article class="p-5 sm:p-6 hover:bg-slate-50/80 transition-colors flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-4 flex-1">
                                    {{-- Date Box --}}
                                    <div class="w-13 h-13 rounded-xl bg-[#eef7f2] border border-[#d2ecd9] text-[#005030] flex flex-col items-center justify-center shrink-0 font-bold shadow-2xs">
                                        <span class="text-base font-extrabold font-mono leading-none">{{ $n['day'] }}</span>
                                        <span class="text-[10px] text-emerald-800 font-bold mt-0.5">{{ $n['month_year'] }}</span>
                                    </div>

                                    <div class="space-y-1.5 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-semibold border border-slate-200">
                                                {{ $n['type'] }}
                                            </span>
                                            @if($n['is_pinned'])
                                                <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 text-[11px] font-bold border border-amber-300">
                                                    পিন করা
                                                </span>
                                            @endif
                                            <span class="text-xs text-slate-400 font-medium">
                                                উদ্দেশ্য: {{ $n['audience'] }}
                                            </span>
                                        </div>

                                        <h2 class="text-base sm:text-lg font-bold text-slate-900 hover:text-[#005030] transition-colors leading-snug">
                                            <a href="{{ route('frontend.notices.show', $n['id']) }}">
                                                {{ $n['title'] }}
                                            </a>
                                        </h2>

                                        <p class="text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed">
                                            {{ $n['excerpt'] }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Action Button --}}
                                <div class="shrink-0 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-0 border-slate-100 flex justify-end">
                                    <a href="{{ route('frontend.notices.show', $n['id']) }}"
                                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#005030] text-white text-xs font-bold hover:bg-[#003820] transition-colors shadow-2xs">
                                        <span>বিস্তারিত</span>
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Pagination (if dynamic) --}}
                    @if(isset($notices) && method_exists($notices, 'hasPages') && $notices->hasPages())
                        <div class="pt-2">
                            {{ $notices->links() }}
                        </div>
                    @endif
                @endif
            </div>

            {{-- Sidebar (Col 4) --}}
            <div class="lg:col-span-4 space-y-5">
                {{-- Search Box --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 space-y-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">বিজ্ঞপ্তি খুঁজুন</h3>
                    <form action="{{ route('frontend.notices.index') }}" method="GET" class="relative">
                        @if(request('type'))
                            <input type="hidden" name="type" value="{{ request('type') }}">
                        @endif
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="শিরোনাম বা বিষয় লিখুন..."
                               class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:border-[#005030] focus:bg-white transition-colors">
                        <button type="submit" class="absolute right-3 top-2.5 text-slate-400 hover:text-[#005030]">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </button>
                    </form>
                </div>

                {{-- Category Filter Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 space-y-3">
                    <div class="text-xs font-bold text-slate-900 border-b border-slate-100 pb-2.5 uppercase tracking-wider">
                        নোটিশ ক্যাটাগরি
                    </div>
                    <div class="space-y-1.5 text-xs">
                        <a href="{{ route('frontend.notices.index') }}"
                           class="flex items-center justify-between px-3 py-2 rounded-xl {{ !request('type') ? 'bg-[#005030] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 font-medium' }} transition-colors">
                            <span>সকল নোটিশ</span>
                            <i class="fa-solid fa-layer-group text-[11px]"></i>
                        </a>
                        <a href="{{ route('frontend.notices.index', ['type' => 'academic']) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-xl {{ request('type') === 'academic' ? 'bg-[#005030] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 font-medium' }} transition-colors">
                            <span>একাডেমিক নোটিশ</span>
                            <i class="fa-solid fa-graduation-cap text-[11px]"></i>
                        </a>
                        <a href="{{ route('frontend.notices.index', ['type' => 'exam']) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-xl {{ request('type') === 'exam' ? 'bg-[#005030] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 font-medium' }} transition-colors">
                            <span>পরীক্ষা ও সময়সূচি</span>
                            <i class="fa-solid fa-file-pen text-[11px]"></i>
                        </a>
                        <a href="{{ route('frontend.notices.index', ['type' => 'admission']) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-xl {{ request('type') === 'admission' ? 'bg-[#005030] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 font-medium' }} transition-colors">
                            <span>ভর্তি সংক্রান্ত বিজ্ঞপ্তি</span>
                            <i class="fa-solid fa-user-plus text-[11px]"></i>
                        </a>
                        <a href="{{ route('frontend.notices.index', ['type' => 'holiday']) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-xl {{ request('type') === 'holiday' ? 'bg-[#005030] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 font-medium' }} transition-colors">
                            <span>ছুটির বিজ্ঞপ্তি</span>
                            <i class="fa-solid fa-calendar-xmark text-[11px]"></i>
                        </a>
                    </div>
                </div>

                {{-- Helpdesk Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
                    <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">তথ্য হেল্পডেস্ক</h4>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        যেকোনো নোটিশ বা সার্কুলার সংক্রান্ত তথ্যের জন্য বিদ্যালয়ের প্রধান অফিস অথবা শ্রেণি শিক্ষকের সাথে যোগাযোগ করুন।
                    </p>
                    <div class="text-xs text-[#005030] font-bold font-mono">
                        হেল্পলাইন: +৮৮০ ১৭১২-৩৪৫৬৭৮
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

@endsection
