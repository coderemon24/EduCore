@extends('layouts.app')

@section('title', 'সোনাতলা উচ্চ বিদ্যালয় | Sonatala High School')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

    {{-- TOP SECTION: Hero Swiper Slider + Headmaster Message Box --}}
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

        {{-- Left: Swiper Slider (65% / 8 cols) --}}
        <div class="lg:col-span-8 bg-white border border-slate-200 rounded overflow-hidden flex flex-col justify-between shadow-2xs">
            {{-- Swiper Container --}}
            <div class="swiper hero-swiper relative h-64 sm:h-80 md:h-[390px] w-full bg-slate-900 overflow-hidden">
                <div class="swiper-wrapper">
                    {{-- Slide 1 --}}
                    <div class="swiper-slide relative">
                        <img src="{{ asset('assets/images/slider-1.jpg') }}"
                             alt="সোনাতলা উচ্চ বিদ্যালয় ভবন"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent p-4 sm:p-6 text-white">
                            <span class="inline-block px-2.5 py-0.5 rounded bg-[#005030] text-[11px] font-bold uppercase tracking-wider mb-1.5 border border-emerald-600/50">
                                ক্যাম্পাস পরিচিতি
                            </span>
                            <h2 class="text-base sm:text-2xl font-bold leading-tight">
                                জ্ঞানের আলোয় আলোকিত, প্রযুক্তিবান্ধব ও মানবিক বিদ্যাপীঠ
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300 mt-1 line-clamp-2">
                                সাঁথিয়া-বেড়া অঞ্চলের ঐতিহ্যবাহী শিক্ষাপ্রতিষ্ঠান সোনাতলা উচ্চ বিদ্যালয় প্রাঙ্গণ ও মনোরম খেলার মাঠ।
                            </p>
                        </div>
                    </div>

                    {{-- Slide 2 --}}
                    <div class="swiper-slide relative">
                        <img src="{{ asset('assets/images/slider-2.jpg') }}"
                             alt="বার্ষিক ক্রীড়া প্রতিযোগিতা"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent p-4 sm:p-6 text-white">
                            <span class="inline-block px-2.5 py-0.5 rounded bg-[#005030] text-[11px] font-bold uppercase tracking-wider mb-1.5 border border-emerald-600/50">
                                সহ-শিক্ষা ও ক্রীড়া
                            </span>
                            <h2 class="text-base sm:text-2xl font-bold leading-tight">
                                বার্ষিক ক্রীড়া প্রতিযোগিতা ও সাংস্কৃতিক উৎসব ২০২৬
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300 mt-1 line-clamp-2">
                                শিক্ষার্থীদের মেধা, সৃজনশীলতা ও শারীরিক সক্ষমতা বিকাশে নিয়মিত সাংস্কৃতিক ও ক্রীড়া প্রতিযোগিতা।
                            </p>
                        </div>
                    </div>

                    {{-- Slide 3 --}}
                    <div class="swiper-slide relative">
                        <img src="{{ asset('assets/images/slider-3.jpg') }}"
                             alt="বিজ্ঞানাগার ও আইসিটি ল্যাব"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent p-4 sm:p-6 text-white">
                            <span class="inline-block px-2.5 py-0.5 rounded bg-[#005030] text-[11px] font-bold uppercase tracking-wider mb-1.5 border border-emerald-600/50">
                                বিজ্ঞান ও প্রযুক্তি
                            </span>
                            <h2 class="text-base sm:text-2xl font-bold leading-tight">
                                আধুনিক বিজ্ঞান ল্যাবরেটরি ও ডিজিটাল শ্রেণিকক্ষ
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300 mt-1 line-clamp-2">
                                নতুন শিক্ষাক্রমের আলোকে অভিজ্ঞ শিক্ষকমণ্ডলীর তত্ত্বাবধানে শিক্ষার্থীদের বাস্তবভিত্তিক বিজ্ঞান শিক্ষা।
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Swiper Pagination & Navigation Controls --}}
                <div class="hero-swiper-pagination absolute bottom-3 right-4 z-20 flex gap-1.5"></div>

                <button type="button" class="hero-swiper-prev absolute left-3 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-black/40 hover:bg-black/70 text-white flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" class="hero-swiper-next absolute right-3 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-black/40 hover:bg-black/70 text-white flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            {{-- Slider Bottom Information Strip --}}
            <div class="p-3 bg-slate-50 border-t border-slate-200 flex flex-wrap items-center justify-between text-xs text-slate-600 gap-2 font-medium">
                <div class="flex items-center gap-3">
                    <span>স্থাপিত: <strong class="text-slate-900">১৯৬৮ খ্রি.</strong></span>
                    <span>•</span>
                    <span>EIIN: <strong class="text-slate-900 font-mono">১২৫৬৯৩</strong></span>
                    <span>•</span>
                    <span>স্কুল কোড: <strong class="text-slate-900 font-mono">৮১০৭</strong></span>
                </div>
                <a href="{{ route('frontend.about') }}" class="text-[#005030] font-bold hover:underline inline-flex items-center gap-1">
                    <span>বিদ্যালয়ের ইতিহাস পড়ুন</span>
                    <span>→</span>
                </a>
            </div>
        </div>

        {{-- Right: Headmaster's Desk Message (35% / 4 cols) --}}
        <div class="lg:col-span-4 bg-white border border-slate-200 rounded overflow-hidden flex flex-col shadow-2xs">
            <div class="bg-[#005030] text-white px-4 py-2.5 flex items-center justify-between font-bold text-xs uppercase tracking-wider">
                <span>প্রধান শিক্ষকের বাণী</span>
                <span class="text-[10px] text-emerald-200 font-normal">Headmaster Desk</span>
            </div>
            <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between space-y-3.5">
                <div class="flex items-start gap-3.5">
                    <div class="w-20 h-24 flex-shrink-0 border border-slate-200 rounded overflow-hidden bg-slate-100 shadow-2xs">
                        <img src="{{ asset('assets/images/headmaster.jpg') }}"
                             alt="মো: খলিলুর রহমান - প্রধান শিক্ষক"
                             class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm leading-snug">
                            মো: খলিলুর রহমান
                        </h3>
                        <div class="text-xs text-slate-700 font-semibold mt-0.5">প্রধান শিক্ষক</div>
                        <div class="text-[11px] text-slate-500">এম.এ, বি.এড (১ম শ্রেণি)</div>
                        <div class="text-[11px] text-slate-400">সোনাতলা উচ্চ বিদ্যালয়</div>
                    </div>
                </div>

                <div class="text-xs text-slate-700 leading-relaxed border-l-2 border-[#005030] pl-3 py-1 bg-slate-50 rounded-r">
                    "বিসমিল্লাহির রাহমানির রাহিম। সোনাতলা উচ্চ বিদ্যালয় পাবনা জেলার একটি অন্যতম ঐতিহ্যবাহী শিক্ষাপ্রতিষ্ঠান। ১৯৬৮ সাল থেকে গুণগত শিক্ষা প্রদান ও সুনাগরিক তৈরির ব্রত নিয়ে আমাদের পথচলা। নতুন কারিকুলাম ও প্রযুক্তির সমন্বয়ে আমরা শিক্ষার্থীদের আলোকিত মানুষ হিসেবে গড়ে তুলতে সচেষ্ট।"
                </div>

                <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium">ফোন: +৮৮০ ১৭১২-৩৪৫৬৭৮</span>
                    <a href="{{ route('frontend.about') }}" class="font-bold text-[#005030] hover:underline">
                        সম্পূর্ণ বাণী →
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN 3-COLUMN EDITORIAL PORTAL FEED (Signature Institutional Tone) --}}
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- COLUMN 1: Category News with Left Thumbnails (45% / 5 cols) --}}
        <div class="lg:col-span-5 bg-white border border-slate-200 rounded shadow-2xs overflow-hidden">
            <div class="bg-[#005030] text-white px-4 py-2.5 flex items-center justify-between">
                <h3 class="font-bold text-xs uppercase tracking-wider">
                    প্রাতিষ্ঠানিক সংবাদ ও বিশেষ কার্যক্রম
                </h3>
                <span class="text-[10px] text-emerald-200">Campus News</span>
            </div>

            <div class="divide-y divide-slate-100">
                {{-- Item 1 --}}
                <article class="p-3.5 hover:bg-slate-50 transition-colors flex gap-3">
                    <div class="w-24 h-20 sm:w-28 sm:h-20 flex-shrink-0 border border-slate-200 rounded overflow-hidden bg-slate-100">
                        <img src="{{ asset('assets/images/news-1.jpg') }}"
                             alt="বার্ষিক ক্রীড়া প্রতিযোগিতা"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 flex flex-col justify-between">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug hover:text-[#005030]">
                            <a href="{{ route('frontend.notices.index') }}">
                                সোনাতলা উচ্চ বিদ্যালয়ের বার্ষিক ক্রীড়া প্রতিযোগিতা ও পুরস্কার বিতরণী সম্পন্ন
                            </a>
                        </h4>
                        <div class="text-[11px] text-slate-500 mt-1">
                            অক্টোবর ১৯, ২০২৬ /// ১০:৪৫ পূর্বাহ্ন
                        </div>
                    </div>
                </article>

                {{-- Item 2 --}}
                <article class="p-3.5 hover:bg-slate-50 transition-colors flex gap-3">
                    <div class="w-24 h-20 sm:w-28 sm:h-20 flex-shrink-0 border border-slate-200 rounded overflow-hidden bg-slate-100">
                        <img src="{{ asset('assets/images/news-2.jpg') }}"
                             alt="বিজ্ঞান মেলা"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 flex flex-col justify-between">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug hover:text-[#005030]">
                            <a href="{{ route('frontend.notices.index') }}">
                                বিজ্ঞান ও প্রযুক্তি সপ্তাহে ক্ষুদে বিজ্ঞানীদের উদ্ভাবনী প্রজেক্ট প্রদর্শনী
                            </a>
                        </h4>
                        <div class="text-[11px] text-slate-500 mt-1">
                            অক্টোবর ১৮, ২০২৬ /// ১১:২০ পূর্বাহ্ন
                        </div>
                    </div>
                </article>

                {{-- Item 3 --}}
                <article class="p-3.5 hover:bg-slate-50 transition-colors flex gap-3">
                    <div class="w-24 h-20 sm:w-28 sm:h-20 flex-shrink-0 border border-slate-200 rounded overflow-hidden bg-slate-100">
                        <img src="{{ asset('assets/images/news-3.jpg') }}"
                             alt="শিক্ষক অভিভাবক সভা"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 flex flex-col justify-between">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug hover:text-[#005030]">
                            <a href="{{ route('frontend.notices.index') }}">
                                নতুন শিক্ষাক্রম ও শিক্ষার্থীদের মূল্যায়ন পদ্ধতি নিয়ে শিক্ষক-অভিভাবক সমাবেশ
                            </a>
                        </h4>
                        <div class="text-[11px] text-slate-500 mt-1">
                            অক্টোবর ১৫, ২০২৬ /// ০৩:১৫ অপরাহ্ন
                        </div>
                    </div>
                </article>
            </div>

            <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
                <a href="{{ route('frontend.notices.index') }}" class="text-xs font-bold text-[#005030] hover:underline">
                    আরও সংবাদ ও প্রতিবেদন দেখুন →
                </a>
            </div>
        </div>

        {{-- COLUMN 2: "সর্বশেষ" (Latest Circulars Text List) (35% / 4 cols) --}}
        <div class="lg:col-span-4 bg-white border border-slate-200 rounded shadow-2xs overflow-hidden">
            <div class="bg-[#005030] text-white px-4 py-2.5 flex items-center justify-between">
                <h3 class="font-bold text-xs uppercase tracking-wider">সর্বশেষ নোটিশ</h3>
                <span class="text-[10px] text-emerald-200 font-mono">Live</span>
            </div>

            <div class="divide-y divide-slate-100 text-xs text-slate-800">
                <div class="p-3 hover:bg-slate-50 transition-colors">
                    <a href="{{ route('frontend.notices.index') }}" class="font-semibold block hover:text-[#005030] leading-snug">
                        বার্ষিক পরীক্ষার সংশোধিত সময়সূচি ও প্রবেশপত্র বিতরণ সংক্রান্ত জরুরি বিজ্ঞপ্তি
                    </a>
                    <span class="text-[10px] text-slate-400 block mt-1">১৯ অক্টোবর ২০২৬</span>
                </div>

                <div class="p-3 hover:bg-slate-50 transition-colors">
                    <a href="{{ route('frontend.notices.index') }}" class="font-semibold block hover:text-[#005030] leading-snug">
                        ২০২৬ শিক্ষাবর্ষে ৬ষ্ঠ শ্রেণিতে ভর্তি লটারি ও আবেদন ফরম জমা দেওয়ার সময় বৃদ্ধি
                    </a>
                    <span class="text-[10px] text-slate-400 block mt-1">১৮ অক্টোবর ২০২৬</span>
                </div>

                <div class="p-3 hover:bg-slate-50 transition-colors">
                    <a href="{{ route('frontend.notices.index') }}" class="font-semibold block hover:text-[#005030] leading-snug">
                        সকল শিক্ষার্থীর ইউনিক আইডি (UID) তথ্য হালনাগাদ ও জন্মনিবন্ধন সনদ জমা সংক্রান্ত
                    </a>
                    <span class="text-[10px] text-slate-400 block mt-1">১৫ অক্টোবর ২০২৬</span>
                </div>

                <div class="p-3 hover:bg-slate-50 transition-colors">
                    <a href="{{ route('frontend.notices.index') }}" class="font-semibold block hover:text-[#005030] leading-snug">
                        উপবৃত্তি প্রাপ্ত শিক্ষার্থীদের তালিকা যাচাই ও নগদ মোবাইল ব্যাংকিং অ্যাকাউন্ট আপডেট
                    </a>
                    <span class="text-[10px] text-slate-400 block mt-1">১২ অক্টোবর ২০২৬</span>
                </div>

                <div class="p-3 hover:bg-slate-50 transition-colors">
                    <a href="{{ route('frontend.notices.index') }}" class="font-semibold block hover:text-[#005030] leading-snug">
                        শ্রীশ্রী দুর্গাপূজা ও লক্ষ্মীপূজা উপলক্ষে বিদ্যালয় সাময়িক বন্ধের বিজ্ঞপ্তি
                    </a>
                    <span class="text-[10px] text-slate-400 block mt-1">০৮ অক্টোবর ২০২৬</span>
                </div>

                <div class="p-3 hover:bg-slate-50 transition-colors">
                    <a href="{{ route('frontend.notices.index') }}" class="font-semibold block hover:text-[#005030] leading-snug">
                        অর্ধবার্ষিক পরীক্ষার ফি ও বকেয়া বেতন পরিশোধের শেষ তারিখ সংক্রান্ত নোটিশ
                    </a>
                    <span class="text-[10px] text-slate-400 block mt-1">০২ অক্টোবর ২০২৬</span>
                </div>
            </div>

            <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
                <a href="{{ route('frontend.notices.index') }}" class="text-xs font-bold text-[#005030] hover:underline">
                    সকল নোটিশ আর্কাইভ দেখুন →
                </a>
            </div>
        </div>

        {{-- COLUMN 3: Right Widget - Quick Services & Social (20% / 3 cols) --}}
        <div class="lg:col-span-3 space-y-4">

            {{-- Quick Action Button Links (Signature Institutional Emerald) --}}
            <div class="bg-white border border-slate-200 rounded shadow-2xs p-3 space-y-2">
                <div class="text-xs font-bold text-slate-900 border-b border-slate-100 pb-1.5 uppercase tracking-wide">
                    জরুরি লিঙ্ক ও সেবা
                </div>
                <a href="{{ url('/admin/dashboard') }}"
                   class="block w-full py-2 px-3 rounded bg-[#005030] hover:bg-[#004027] text-white text-xs font-bold text-center transition-colors shadow-2xs">
                    অনলাইন ভর্তি আবেদন ২০২৬
                </a>
                <a href="{{ route('frontend.academic.routine') }}"
                   class="block w-full py-2 px-3 rounded bg-slate-100 hover:bg-emerald-50 hover:text-[#005030] text-slate-800 text-xs font-semibold text-center border border-slate-200 transition-colors">
                    ক্লাস ও পরীক্ষার রুটিন
                </a>
                <a href="{{ route('frontend.teachers.index') }}"
                   class="block w-full py-2 px-3 rounded bg-slate-100 hover:bg-emerald-50 hover:text-[#005030] text-slate-800 text-xs font-semibold text-center border border-slate-200 transition-colors">
                    শিক্ষক ও কর্মচারীবৃন্দ
                </a>
            </div>

            {{-- Facebook Video / Social Embed Card --}}
            <div class="bg-white border border-slate-200 rounded shadow-2xs overflow-hidden">
                <div class="p-3 border-b border-slate-100 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-[#005030] text-white flex items-center justify-center font-bold text-xs">
                        SHS
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 leading-tight">সোনাতলা উচ্চ বিদ্যালয়</h4>
                        <div class="text-[10px] text-slate-400">অফিসিয়াল ফেসবুক পেজ</div>
                    </div>
                </div>

                {{-- Post / Video Preview Box --}}
                <div class="p-3 space-y-2.5">
                    <p class="text-xs text-slate-700 leading-snug">
                        সোনাতলা উচ্চ বিদ্যালয়ের ২০২৬ শিক্ষাবর্ষের আইসিটি ক্লাসরুম ও বিজ্ঞান উৎসবের এক ঝলক... 🚀
                    </p>
                    <div class="relative rounded border border-slate-200 bg-slate-900 overflow-hidden aspect-video flex items-center justify-center group cursor-pointer">
                        <img src="{{ asset('assets/images/video-thumb.jpg') }}"
                             alt="ভিডিও প্রিভিউ"
                             class="w-full h-full object-cover opacity-80 group-hover:opacity-90 transition-opacity">
                        <div class="absolute w-10 h-10 rounded-full bg-white/95 text-slate-900 flex items-center justify-center shadow">
                            <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-100">
                        <span>👍 ৫২ লাইক</span>
                        <span>💬 ১২ মন্তব্য</span>
                        <span>↗️ ৪ শেয়ার</span>
                    </div>
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer"
                       class="block w-full py-1.5 px-3 rounded bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold text-center border border-slate-200 transition-colors">
                        See more on Facebook
                    </a>
                </div>
            </div>

            {{-- Floating / Quick WhatsApp Inquiry Card --}}
            <div class="bg-white border border-slate-200 rounded p-3 text-center space-y-2">
                <div class="text-xs font-semibold text-slate-700">যেকোনো তথ্যে সরাসরি মেসেজ করুন</div>
                <a href="https://wa.me/8801712345678" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center justify-center gap-1.5 w-full py-1.5 px-3 rounded bg-[#25D366] hover:bg-[#20ba5a] text-white text-xs font-bold transition-colors shadow-2xs">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.584 1.761.812 2.796.813 3.18 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.485-4.484 9.969-9.969 9.969-1.748 0-3.376-.452-4.796-1.241l-5.235 1.371 1.397-5.105c-.888-1.488-1.366-3.21-1.366-4.994 0-5.485 4.484-9.969 9.969-9.969 5.485 0 9.969 4.484 9.969 9.969z"/></svg>
                    <span>WhatsApp চ্যাট করুন</span>
                </a>
            </div>

        </div>
    </section>

    {{-- INSTITUTIONAL AT-A-GLANCE SUMMARY TABLE --}}
    <section class="bg-white border border-slate-200 rounded shadow-2xs overflow-hidden">
        <div class="bg-[#005030] text-white px-4 py-2.5 flex items-center justify-between">
            <h3 class="font-bold text-xs uppercase tracking-wider">
                এক নজরে সোনাতলা উচ্চ বিদ্যালয় (Institutional Telemetry)
            </h3>
            <span class="text-xs text-emerald-200">পরিসংখ্যান</span>
        </div>
        <div class="p-4 sm:p-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 text-center">
            <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                <div class="text-xs text-slate-500 font-medium">প্রতিষ্ঠা সন</div>
                <div class="text-lg font-bold text-slate-900 mt-0.5">১৯৬৮ খ্রি.</div>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                <div class="text-xs text-slate-500 font-medium">EIIN নম্বর</div>
                <div class="text-lg font-bold text-slate-900 font-mono mt-0.5">১২৫৬৯৩</div>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                <div class="text-xs text-slate-500 font-medium">মোট শিক্ষার্থী</div>
                <div class="text-lg font-bold text-slate-900 mt-0.5">১,২৫০+ জন</div>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                <div class="text-xs text-slate-500 font-medium">শিক্ষক ও স্টাফ</div>
                <div class="text-lg font-bold text-slate-900 mt-0.5">৪২ জন</div>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                <div class="text-xs text-slate-500 font-medium">শ্রেণিকক্ষ সংখ্যা</div>
                <div class="text-lg font-bold text-slate-900 mt-0.5">১৮টি</div>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                <div class="text-xs text-slate-500 font-medium">এসএসসি সাফল্য</div>
                <div class="text-lg font-bold text-slate-900 mt-0.5">৯৮.৫%</div>
            </div>
        </div>
    </section>

    {{-- CAMPUS PHOTO GALLERY (4 Crisp Real Event Photos) --}}
    <section class="bg-white border border-slate-200 rounded shadow-2xs overflow-hidden">
        <div class="bg-[#005030] text-white px-4 py-2.5 flex items-center justify-between">
            <h3 class="font-bold text-xs uppercase tracking-wider">
                ক্যাম্পাস চিত্রশালা / Photo Gallery
            </h3>
            <span class="text-xs text-emerald-200">চিত্রাবলি</span>
        </div>
        <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="space-y-1.5">
                <div class="aspect-4/3 rounded border border-slate-200 overflow-hidden bg-slate-100">
                    <img src="{{ asset('assets/images/slider-1.jpg') }}"
                         alt="বিদ্যালয় ভবন" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
                <div class="text-[11px] font-semibold text-slate-800 text-center">বিদ্যালয়ের প্রধান ভবন</div>
            </div>

            <div class="space-y-1.5">
                <div class="aspect-4/3 rounded border border-slate-200 overflow-hidden bg-slate-100">
                    <img src="{{ asset('assets/images/slider-3.jpg') }}"
                         alt="আইসিটি ও কম্পিউটার ল্যাব" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
                <div class="text-[11px] font-semibold text-slate-800 text-center">কম্পিউটার ল্যাব</div>
            </div>

            <div class="space-y-1.5">
                <div class="aspect-4/3 rounded border border-slate-200 overflow-hidden bg-slate-100">
                    <img src="{{ asset('assets/images/slider-2.jpg') }}"
                         alt="বার্ষিক ক্রীড়া উৎসব" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
                <div class="text-[11px] font-semibold text-slate-800 text-center">বার্ষিক ক্রীড়া প্রতিযোগিতা</div>
            </div>

            <div class="space-y-1.5">
                <div class="aspect-4/3 rounded border border-slate-200 overflow-hidden bg-slate-100">
                    <img src="{{ asset('assets/images/news-2.jpg') }}"
                         alt="বিজ্ঞান মেলা ও ল্যাব" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
                <div class="text-[11px] font-semibold text-slate-800 text-center">ব্যবহারিক বিজ্ঞান উৎসব</div>
            </div>
        </div>
    </section>

</div>
@endsection
