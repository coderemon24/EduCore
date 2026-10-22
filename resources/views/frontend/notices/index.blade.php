@extends('layouts.app')

@section('title', 'নোটিশ বোর্ড | সোনাতলা উচ্চ বিদ্যালয়')

@section('content')
{{-- Breadcrumb & Title --}}
<div class="bg-gray-100 border-b border-gray-200 py-3 text-xs text-gray-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div class="flex items-center gap-1.5 font-medium">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030]">প্রচ্ছদ</a>
            <span>/</span>
            <span class="text-gray-900 font-bold">নোটিশ বোর্ড</span>
        </div>
        <span class="text-gray-400">সর্বশেষ আপডেট: {{ now()->format('d M Y') }}</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- Header Banner --}}
    <div class="bg-[#005030] text-white p-6 sm:p-7 rounded-xl shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-6 border-l-4 border-amber-400">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                প্রাতিষ্ঠানিক <span class="text-amber-400">নোটিশ বোর্ড</span>
            </h1>
            <p class="text-sm sm:text-base text-emerald-100/90 mt-1 max-w-2xl leading-relaxed">
                সোনাতলা উচ্চ বিদ্যালয়ের সকল প্রাতিষ্ঠানিক, একাডেমিক ও পরীক্ষার আনুষ্ঠানিক সার্কুলার।
            </p>
        </div>
        <div class="w-full md:w-72">
            <div class="relative">
                <input type="text"
                       placeholder="বিজ্ঞপ্তি খুঁজুন..."
                       class="w-full bg-[#003820] text-white placeholder-emerald-300 text-xs rounded-lg px-3.5 py-2.5 border border-emerald-700/80 focus:outline-none focus:ring-1 focus:ring-white">
                <button type="button" class="absolute right-3 top-3 text-emerald-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Notice Directory Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Notices List (Col 8) --}}
        <div class="lg:col-span-8 space-y-4">
            @php
                $dummyNotices = [
                    [
                        'id' => 1,
                        'title' => '২০২৬ শিক্ষাবর্ষে ৬ষ্ঠ থেকে ৯ম শ্রেণিতে ভর্তি আবেদন সংক্রান্ত জরুরি বিজ্ঞপ্তি',
                        'date' => '১৯ অক্টোবর ২০২৬',
                        'type' => 'ভর্তি বিজ্ঞপ্তি',
                        'audience' => 'অভিভাবক ও শিক্ষার্থী',
                        'is_pinned' => true,
                        'excerpt' => 'সোনাতলা উচ্চ বিদ্যালয়ের ২০২৬ শিক্ষাবর্ষের সকল শ্রেণিতে ভর্তিচ্ছু শিক্ষার্থীদের অবগতির জন্য জানানো যাচ্ছে যে, আগামী ৩০ নভেম্বর ২০২৬ পর্যন্ত আবেদন ফরম বিতরণ ও জমা নেওয়া হবে...',
                    ],
                    [
                        'id' => 2,
                        'title' => 'আসন্ন বার্ষিক পরীক্ষা ২০২৬ এর সংশোধিত সময়সূচি ও প্রবেশপত্র বিতরণ সংক্রান্ত নোটিশ',
                        'date' => '১৮ অক্টোবর ২০২৬',
                        'type' => 'পরীক্ষা সংক্রান্ত',
                        'audience' => 'সকল শিক্ষার্থী',
                        'is_pinned' => true,
                        'excerpt' => 'সকল শ্রেণির শিক্ষার্থীদের জানানো যাচ্ছে যে, অনিবার্য কারণবশত বার্ষিক পরীক্ষার সময়সূচিতে সামান্য পরিবর্তন আনা হয়েছে। সংশোধিত রুটিন অনুযায়ী আগামী ০৫ নভেম্বর থেকে পরীক্ষা শুরু হবে...',
                    ],
                    [
                        'id' => 3,
                        'title' => 'সকল শিক্ষার্থীর ডিজিটাল ইউনিক আইডি (UID) তথ্য হালনাগাদ ও জন্মনিবন্ধন সনদ জমা',
                        'date' => '১৫ অক্টোবর ২০২৬',
                        'type' => 'প্রশাসনিক নোটিশ',
                        'audience' => 'সকল শিক্ষার্থী',
                        'is_pinned' => false,
                        'excerpt' => 'শিক্ষা মন্ত্রণালয়ের নির্দেশনা মোতাবেক সকল শিক্ষার্থীর ১৭ ডিজিটের অনলাইন জন্মনিবন্ধন সনদ ও প্রয়োজনীয় তথ্যাদি আগামী ২৫ অক্টোবরের মধ্যে স্ব-স্ব শ্রেণি শিক্ষকের নিকট জমা দিতে হবে...',
                    ],
                    [
                        'id' => 4,
                        'title' => 'উপবৃত্তি প্রাপ্ত শিক্ষার্থীদের তালিকা যাচাই ও নগদ মোবাইল ব্যাংকিং অ্যাকাউন্ট আপডেট',
                        'date' => '১২ অক্টোবর ২০২৬',
                        'type' => 'উপবৃত্তি সংক্রান্ত',
                        'audience' => 'উপবৃত্তিভোগী শিক্ষার্থী',
                        'is_pinned' => false,
                        'excerpt' => 'প্রধানমন্ত্রীর শিক্ষা সহায়তা ট্রাস্টের আওতায় উপবৃত্তিপ্রাপ্ত শিক্ষার্থীদের অবগতির জন্য জানানো যাচ্ছে যে, যাদের মোবাইল ব্যাংকিং একাউন্টে ত্রুটি রয়েছে তারা দ্রুত যোগাযোগ করুন...',
                    ],
                    [
                        'id' => 5,
                        'title' => 'শ্রীশ্রী দুর্গাপূজা ও লক্ষ্মীপূজা উপলক্ষে বিদ্যালয় সাময়িক বন্ধের বিজ্ঞপ্তি',
                        'date' => '০৮ অক্টোবর ২০২৬',
                        'type' => 'ছুটির নোটিশ',
                        'audience' => 'সকলের জন্য',
                        'is_pinned' => false,
                        'excerpt' => 'সকল শিক্ষক, কর্মচারী ও শিক্ষার্থীদের জানানো যাচ্ছে যে, দুর্গাপূজা ও লক্ষ্মীপূজা উপলক্ষে আগামী ১০ অক্টোবর থেকে ১৮ অক্টোবর পর্যন্ত বিদ্যালয়ের নিয়মিত শ্রেণি পাঠদান বন্ধ থাকবে...',
                    ],
                    [
                        'id' => 6,
                        'title' => 'জাতীয় শিক্ষা সপ্তাহ উপলক্ষে আন্তঃস্কুল বিতর্ক, রচনা ও আবৃত্তি প্রতিযোগিতা নোটিশ',
                        'date' => '২৮ সেপ্টেম্বর ২০২৬',
                        'type' => 'সহ-শিক্ষা কার্যক্রম',
                        'audience' => 'আগ্রহী শিক্ষার্থী',
                        'is_pinned' => false,
                        'excerpt' => 'জাতীয় শিক্ষা সপ্তাহ উপলক্ষে আগামী ০৪ অক্টোবর বিদ্যালয় মিলনায়তনে বিভিন্ন ইভেন্টে প্রতিযোগিতা অনুষ্ঠিত হবে। আগ্রহী শিক্ষার্থীদের শ্রেণি শিক্ষকের নিকট নাম অন্তর্ভুক্তির নির্দেশ দেওয়া হলো...',
                    ],
                ];
            @endphp

            <div class="bg-white border border-gray-300 rounded-xl shadow-2xs divide-y divide-gray-200 overflow-hidden">
                @foreach($dummyNotices as $n)
                    <article class="p-4 sm:p-5 hover:bg-gray-50/80 transition-colors flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5 flex-1">
                            {{-- Date Square --}}
                            <div class="w-13 h-13 rounded-lg bg-emerald-50 border border-emerald-300 text-[#005030] flex flex-col items-center justify-center flex-shrink-0 font-bold">
                                <span class="text-base font-extrabold font-mono leading-none">{{ explode(' ', $n['date'])[0] }}</span>
                                <span class="text-[11px] text-emerald-800 font-bold mt-0.5">{{ explode(' ', $n['date'])[1] }}</span>
                            </div>

                            <div class="space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-[11px] font-bold">
                                        {{ $n['type'] }}
                                    </span>
                                    @if($n['is_pinned'])
                                        <span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-900 text-[11px] font-bold border border-amber-300">
                                            পিন করা
                                        </span>
                                    @endif
                                    <span class="text-xs text-gray-500 font-medium">
                                        উদ্দেশ্য: {{ $n['audience'] }}
                                    </span>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 hover:text-[#005030] leading-snug">
                                    <a href="{{ route('frontend.notices.show', $n['id']) }}">
                                        {{ $n['title'] }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">
                                    {{ $n['excerpt'] }}
                                </p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 flex-shrink-0 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-0 border-gray-100 justify-end">
                            <a href="{{ route('frontend.notices.show', $n['id']) }}"
                               class="px-3.5 py-1.5 rounded bg-[#005030] text-white text-xs font-semibold hover:bg-[#003820] transition-colors shadow-2xs">
                                বিস্তারিত পড়ুন
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        {{-- Sidebar (Col 4) --}}
        <div class="lg:col-span-4 space-y-5">
            {{-- Category Filter Card --}}
            <div class="bg-white border border-gray-300 rounded-xl shadow-2xs p-4 space-y-2">
                <div class="text-xs font-bold text-gray-900 border-b border-gray-200 pb-2 uppercase tracking-wider">
                    নোটিশ ক্যাটাগরি
                </div>
                <div class="space-y-1 text-xs sm:text-sm">
                    <a href="{{ route('frontend.notices.index') }}" class="block px-2.5 py-1.5 rounded bg-emerald-50 text-[#005030] font-bold">
                        সকল নোটিশ (All Notices)
                    </a>
                    <a href="{{ route('frontend.notices.index') }}" class="block px-2.5 py-1.5 rounded text-gray-700 hover:bg-gray-100 font-medium">
                        একাডেমিক নোটিশ
                    </a>
                    <a href="{{ route('frontend.notices.index') }}" class="block px-2.5 py-1.5 rounded text-gray-700 hover:bg-gray-100 font-medium">
                        পরীক্ষা ও সময়সূচি
                    </a>
                    <a href="{{ route('frontend.notices.index') }}" class="block px-2.5 py-1.5 rounded text-gray-700 hover:bg-gray-100 font-medium">
                        ভর্তি সংক্রান্ত বিজ্ঞপ্তি
                    </a>
                    <a href="{{ route('frontend.notices.index') }}" class="block px-2.5 py-1.5 rounded text-gray-700 hover:bg-gray-100 font-medium">
                        ছুটির বিজ্ঞপ্তি
                    </a>
                </div>
            </div>

            {{-- Office Help Card --}}
            <div class="bg-[#003820] text-white border border-emerald-900 rounded-xl p-5 shadow-2xs space-y-2">
                <div class="text-xs font-bold text-emerald-300 uppercase tracking-wider">অফিসিয়াল তথ্য হেল্পডেস্ক</div>
                <p class="text-xs sm:text-sm text-emerald-100 leading-relaxed">
                    যেকোনো নোটিশ বা সার্কুলার সংক্রান্ত স্পষ্টীকরণের জন্য বিদ্যালয়ের প্রধান অফিস অথবা শ্রেণি শিক্ষকের সাথে যোগাযোগ করুন।
                </p>
                <div class="text-xs sm:text-sm text-white font-bold pt-1">
                    হেল্পলাইন: +৮৮০ ১৭১২-৩৪৫৬৭৮
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
