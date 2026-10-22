@extends('layouts.app')

@section('title', 'সোনাতলা উচ্চ বিদ্যালয় | Sonatala High School')

@section('content')
    {{-- Full-Screen Hero Slider Directly Below Navbar --}}
    @include('frontend.home.partials.fullscreen-slider')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

    {{-- TOP SECTION: Hero Swiper Slider + Headmaster Message Box --}}
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- Left: Swiper Slider (65% / 8 cols) --}}
        <div class="lg:col-span-8 bg-white border border-slate-200/90 rounded-2xl overflow-hidden flex flex-col shadow-xs self-start">
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
                                আদর্শ শিক্ষাঙ্গন ও মূল্যবোধ
                            </span>
                            <h2 class="text-base sm:text-2xl font-bold leading-tight">
                                আপনার সন্তানের ভবিষ্যৎ রচিত হোক <span class="text-amber-300">সততা, মেধা ও মানবিক মূল্যবোধে</span>
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300 mt-1 line-clamp-2">
                                পাবনার সাঁথিয়া অঞ্চলের ঐতিহ্যবাহী এই বিদ্যাপীঠে স্নেহ ও শৃঙ্খলার মেলবন্ধনে আমরা প্রতিটি শিক্ষার্থীকে গড়ে তুলি আগামীর সুনাগরিক হিসেবে।
                            </p>
                        </div>
                    </div>

                    {{-- Slide 2 --}}
                    <div class="swiper-slide relative">
                        <img src="{{ asset('assets/images/slider-2.jpg') }}"
                             alt="বার্ষিক ক্রীড়া ও সহ-শিক্ষা"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent p-4 sm:p-6 text-white">
                            <span class="inline-block px-2.5 py-0.5 rounded bg-[#005030] text-[11px] font-bold uppercase tracking-wider mb-1.5 border border-emerald-600/50">
                                সহ-শিক্ষা ও নেতৃত্ব
                            </span>
                            <h2 class="text-base sm:text-2xl font-bold leading-tight">
                                খেলার মাঠে একতা ও চরিত্র গঠন, সাংস্কৃতিক মঞ্চে মেধার বিকাশ
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300 mt-1 line-clamp-2">
                                শুধু পুথিগত বিদ্যা নয়; সুস্থ দেহ, সুদৃঢ় মনোবল ও দলগত শৃঙ্খলার মাধ্যমে প্রতিটি শিক্ষার্থীর আত্মবিশ্বাসকে বিকশিত করি।
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
                                আধুনিক বিজ্ঞানাগার ও ডিজিটাল ক্লাসরুমে বাস্তবমুখী পাঠদান
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300 mt-1 line-clamp-2">
                                নতুন শিক্ষাক্রমের আলোকে অভিজ্ঞ শিক্ষকদের তত্ত্বাবধানে হাতেকলমে বিজ্ঞান ও তথ্যপ্রযুক্তির আলোয় আগামীর প্রস্তুতি।
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
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-wrap items-center justify-between text-xs sm:text-sm text-slate-600 gap-2 font-medium">
                <div class="flex items-center gap-3">
                    <span>প্রতিষ্ঠা: <strong class="text-slate-900">১৯৬৮ খ্রি.</strong></span>
                    <span>•</span>
                    <span>EIIN: <strong class="text-slate-900 font-mono">১২৫৬৯৩</strong></span>
                    <span>•</span>
                    <span>স্কুল কোড: <strong class="text-slate-900 font-mono">৮১০৭</strong></span>
                </div>
                <a href="{{ route('frontend.about') }}" class="text-[#005030] font-bold hover:underline inline-flex items-center gap-1">
                    <span>গৌরবের ৫৭ বছরের ঐতিহ্য ও ইতিহাস জানুন</span>
                    <span>→</span>
                </a>
            </div>
        </div>
        {{-- Right: Notice Board Widget (35% / 4 cols) --}}
        @include('frontend.home.partials.notice-board')
    </section>

    {{-- MAIN 3-COLUMN EDITORIAL PORTAL FEED (Signature Institutional Tone) --}}
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- COLUMN 1: Category News with Left Thumbnails (45% / 5 cols) --}}
        <div class="lg:col-span-5 bg-white border border-slate-200 rounded shadow-2xs overflow-hidden">
            <div class="bg-[#005030] text-white px-4 py-2.5 flex items-center justify-between">
                <h3 class="font-bold text-xs uppercase tracking-wider">
                    শিক্ষার্থীদের সাফল্য ও ক্যাম্পাস পরিক্রমা
                </h3>
                <span class="text-[10px] text-emerald-200 font-semibold">Campus Stories</span>
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
                                দলগত সম্প্রীতি ও সুস্থ জীবনের প্রত্যয়ে বার্ষিক ক্রীড়া প্রতিযোগিতা সম্পন্ন
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
                                বিজ্ঞানের ব্যবহারিক উদ্ভাবনে মুখর ক্যাম্পাস: ক্ষুদে বিজ্ঞানীদের নতুন প্রজেক্ট প্রদর্শনী
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
                                সন্তানের মানসিক সুস্থতা ও নতুন মূল্যায়ন পদ্ধতি নিয়ে শিক্ষক-অভিভাবক আন্তরিক সংলাপ
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
                    ক্যাম্পাসের আরও অনুপ্রেরণাদায়ী গল্প দেখুন →
                </a>
            </div>
        </div>

        {{-- COLUMN 2: "সর্বশেষ" (Latest Circulars Text List) (35% / 4 cols) --}}
        <div class="lg:col-span-4 bg-white border border-slate-200 rounded shadow-2xs overflow-hidden">
            <div class="bg-[#005030] text-white px-4 py-2.5 flex items-center justify-between">
                <h3 class="font-bold text-xs uppercase tracking-wider">জরুরি বিজ্ঞপ্তি ও তথ্য</h3>
                <span class="text-[10px] text-emerald-200 font-mono">Live</span>
            </div>

            <div class="divide-y divide-slate-100 text-xs text-slate-800">
                <div class="p-3 hover:bg-slate-50 transition-colors">
                    <a href="{{ route('frontend.notices.index') }}" class="font-semibold block hover:text-[#005030] leading-snug">
                        বার্ষিক পরীক্ষার সংশোধিত সময়সূচি ও প্রবেশপত্র বিতরণ সংক্রান্ত জরুরি তথ্য
                    </a>
                    <span class="text-[10px] text-slate-400 block mt-1">১৯ অক্টোবর ২০২৬</span>
                </div>

                <div class="p-3 hover:bg-slate-50 transition-colors">
                    <a href="{{ route('frontend.notices.index') }}" class="font-semibold block hover:text-[#005030] leading-snug">
                        ২০২৬ শিক্ষাবর্ষে ৬ষ্ঠ শ্রেণিতে ভর্তি: আপনার সন্তানের মেধার বিকাশে আমাদের নতুন সূচনা
                    </a>
                    <span class="text-[10px] text-slate-400 block mt-1">১৮ অক্টোবর ২০২৬</span>
                </div>

                <div class="p-3 hover:bg-slate-50 transition-colors">
                    <a href="{{ route('frontend.notices.index') }}" class="font-semibold block hover:text-[#005030] leading-snug">
                        সকল শিক্ষার্থীর ইউনিক আইডি (UID) তথ্য হালনাগাদ ও নির্ভুল জন্মনিবন্ধন নিশ্চিতকরণ
                    </a>
                    <span class="text-[10px] text-slate-400 block mt-1">১৫ অক্টোবর ২০২৬</span>
                </div>

                <div class="p-3 hover:bg-slate-50 transition-colors">
                    <a href="{{ route('frontend.notices.index') }}" class="font-semibold block hover:text-[#005030] leading-snug">
                        মেধা ও প্রয়োজনভিত্তিক সরকারি উপবৃত্তি: কোনো শিক্ষার্থী যেন আর্থিক অনটনে পিছিয়ে না পড়ে
                    </a>
                    <span class="text-[10px] text-slate-400 block mt-1">১২ অক্টোবর ২০২৬</span>
                </div>

                <div class="p-3 hover:bg-slate-50 transition-colors">
                    <a href="{{ route('frontend.notices.index') }}" class="font-semibold block hover:text-[#005030] leading-snug">
                        শরৎকালীন অবকাশ ও শারদীয় দুর্গোৎসব উপলক্ষে বিদ্যালয় সাময়িক বন্ধের নোটিশ
                    </a>
                    <span class="text-[10px] text-slate-400 block mt-1">০৮ অক্টোবর ২০২৬</span>
                </div>

                <div class="p-3 hover:bg-slate-50 transition-colors">
                    <a href="{{ route('frontend.notices.index') }}" class="font-semibold block hover:text-[#005030] leading-snug">
                        অর্ধবার্ষিক পরীক্ষার ফি ও সেশন উন্নয়ন চার্জ পরিশোধের সংক্রান্ত বিজ্ঞপ্তি
                    </a>
                    <span class="text-[10px] text-slate-400 block mt-1">০২ অক্টোবর ২০২৬</span>
                </div>
            </div>

            <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
                <a href="{{ route('frontend.notices.index') }}" class="text-xs font-bold text-[#005030] hover:underline">
                    সকল অফিসিয়াল নোটিশ আর্কাইভ দেখুন →
                </a>
            </div>
        </div>

        {{-- COLUMN 3: Right Widget - Quick Services & Social (20% / 3 cols) --}}
        <div class="lg:col-span-3 space-y-4">

            {{-- Quick Action Button Links (Signature Institutional Emerald) --}}
            <div class="bg-white border border-slate-200 rounded shadow-2xs p-3 space-y-2">
                <div class="text-xs font-bold text-slate-900 border-b border-slate-100 pb-1.5 uppercase tracking-wide">
                    অভিভাবক ও শিক্ষার্থী সেবা
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
                    আমাদের শ্রদ্ধেয় শিক্ষকমণ্ডলী
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
                        সোনাতলা উচ্চ বিদ্যালয়ের প্রতিটি দিনে থাকে নতুন কিছু শেখার আনন্দ। ক্লাসরুমে বিজ্ঞানের ব্যবহারিক ক্লাস ও প্রযুক্তি চর্চার এক ঝলক... 🚀
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
                        ফেসবুক পেজে যুক্ত থাকুন
                    </a>
                </div>
            </div>

            {{-- Floating / Quick WhatsApp Inquiry Card --}}
            <div class="bg-white border border-slate-200 rounded p-3 text-center space-y-2">
                <div class="text-xs font-semibold text-slate-700">সন্তানের ভর্তি বা পাঠদান নিয়ে যে কোনো তথ্যসহায়তায়</div>
                <a href="https://wa.me/8801712345678" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center justify-center gap-1.5 w-full py-1.5 px-3 rounded bg-[#25D366] hover:bg-[#20ba5a] text-white text-xs font-bold transition-colors shadow-2xs">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.584 1.761.812 2.796.813 3.18 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.485-4.484 9.969-9.969 9.969-1.748 0-3.376-.452-4.796-1.241l-5.235 1.371 1.397-5.105c-.888-1.488-1.366-3.21-1.366-4.994 0-5.485 4.484-9.969 9.969-9.969 5.485 0 9.969 4.484 9.969 9.969z"/></svg>
                    <span>WhatsApp-এ সরাসরি কথা বলুন</span>
                </a>
            </div>

        </div>
    </section>

    {{-- INSTITUTIONAL AT-A-GLANCE SUMMARY TABLE --}}
    <section class="bg-white border border-slate-200 rounded shadow-2xs overflow-hidden">
        <div class="bg-[#005030] text-white px-4 py-2.5 flex items-center justify-between">
            <h3 class="font-bold text-xs uppercase tracking-wider">
                এক নজরে আমাদের অর্জন ও অবকাঠামো
            </h3>
            <span class="text-xs text-emerald-200">পরিসংখ্যান</span>
        </div>
        <div class="p-4 sm:p-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 text-center">
            <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                <div class="text-xs text-slate-500 font-medium">প্রতিষ্ঠা সন</div>
                <div class="text-lg font-bold text-slate-900 mt-0.5">১৯৬৮ খ্রি.</div>
                <div class="text-[10px] text-slate-400 mt-0.5">গৌরবময় ঐতিহ্য</div>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                <div class="text-xs text-slate-500 font-medium">EIIN নম্বর</div>
                <div class="text-lg font-bold text-slate-900 font-mono mt-0.5">১২৫৬৯৩</div>
                <div class="text-[10px] text-slate-400 mt-0.5">সরকারি স্বীকৃতিপ্রাপ্ত</div>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                <div class="text-xs text-slate-500 font-medium">প্রিয় শিক্ষার্থী</div>
                <div class="text-lg font-bold text-slate-900 mt-0.5">১,২৫০+ জন</div>
                <div class="text-[10px] text-slate-400 mt-0.5">প্রাণবন্ত পরিবার</div>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                <div class="text-xs text-slate-500 font-medium">শিক্ষক ও স্টাফ</div>
                <div class="text-lg font-bold text-slate-900 mt-0.5">৪২ জন</div>
                <div class="text-[10px] text-slate-400 mt-0.5">স্নেহশীল ও নিবেদিতপ্রাণ</div>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                <div class="text-xs text-slate-500 font-medium">শ্রেণিকক্ষ সংখ্যা</div>
                <div class="text-lg font-bold text-slate-900 mt-0.5">১৮টি</div>
                <div class="text-[10px] text-slate-400 mt-0.5">প্রযুক্তিবান্ধব পরিবেশ</div>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded">
                <div class="text-xs text-slate-500 font-medium">এসএসসি সাফল্য</div>
                <div class="text-lg font-bold text-slate-900 mt-0.5">৯৮.৫%</div>
                <div class="text-[10px] text-slate-400 mt-0.5">ধারাবাহিক মেধা</div>
            </div>
        </div>
    </section>

    {{-- CAMPUS PHOTO GALLERY (4 Crisp Real Event Photos) --}}
    <section class="bg-white border border-slate-200 rounded shadow-2xs overflow-hidden">
        <div class="bg-[#005030] text-white px-4 py-2.5 flex items-center justify-between">
            <h3 class="font-bold text-xs uppercase tracking-wider">
                ক্যাম্পাসের সোনালী মুহূর্ত ও প্রাঙ্গণচিত্র
            </h3>
            <span class="text-xs text-emerald-200">চিত্রাবলি</span>
        </div>
        <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="space-y-1.5">
                <div class="aspect-4/3 rounded border border-slate-200 overflow-hidden bg-slate-100">
                    <img src="{{ asset('assets/images/slider-1.jpg') }}"
                         alt="বিদ্যালয় ভবন" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
                <div class="text-[11px] font-semibold text-slate-800 text-center">সবুজ ছায়ায় ঘেরা প্রধান ক্যাম্পাস ভবন</div>
            </div>

            <div class="space-y-1.5">
                <div class="aspect-4/3 rounded border border-slate-200 overflow-hidden bg-slate-100">
                    <img src="{{ asset('assets/images/slider-3.jpg') }}"
                         alt="আইসিটি ও কম্পিউটার ল্যাব" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
                <div class="text-[11px] font-semibold text-slate-800 text-center">ভবিষ্যতের দক্ষতা অর্জনে কম্পিউটার ল্যাব</div>
            </div>

            <div class="space-y-1.5">
                <div class="aspect-4/3 rounded border border-slate-200 overflow-hidden bg-slate-100">
                    <img src="{{ asset('assets/images/slider-2.jpg') }}"
                         alt="বার্ষিক ক্রীড়া উৎসব" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
                <div class="text-[11px] font-semibold text-slate-800 text-center">সুস্থ দেহ ও বন্ধুত্বের প্রতীক: খেলার মাঠ</div>
            </div>

            <div class="space-y-1.5">
                <div class="aspect-4/3 rounded border border-slate-200 overflow-hidden bg-slate-100">
                    <img src="{{ asset('assets/images/news-2.jpg') }}"
                         alt="বিজ্ঞান মেলা ও ল্যাব" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
                <div class="text-[11px] font-semibold text-slate-800 text-center">কৌতূহলী মনের আবিষ্কার: বিজ্ঞান উৎসব</div>
            </div>
        </div>
    </section>

</div>
@endsection
