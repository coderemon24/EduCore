@extends('layouts.app')

@section('title', 'অভিভাবকের আস্থায় সম্মানিত শিক্ষকমণ্ডলী | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের নিবেদিতপ্রাণ ও প্রশিক্ষণপ্রাপ্ত সম্মানিত শিক্ষকমণ্ডলী—যাঁদের আন্তরিক পাঠদান ও নিবিড় পরিচর্যায় গড়ে উঠছে প্রতিটি সন্তান।')

@section('content')

{{-- 1. Executive Breadcrumb Bar --}}
<div class="bg-gray-100 border-b border-gray-200 py-3.5 text-xs text-gray-600" id="overview">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>প্রচ্ছদ</span>
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-500">পরিচিতি</span>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">সম্মানিত শিক্ষক ও কর্মচারীবৃন্দ</span>
        </nav>
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }} • সাঁথিয়া, পাবনা</span>
    </div>
</div>

{{-- 2. Institutional Hero Header Banner --}}
<div class="bg-[#005030] text-white py-14 sm:py-20 border-b border-emerald-900 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6 relative z-10">
        <div class="space-y-3.5 max-w-2xl">
            <span class="hero-anim inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#003820] text-emerald-200 text-xs font-bold uppercase tracking-wider border border-emerald-700/60 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>FACULTY & MENTORS — সোনাতলা উচ্চ বিদ্যালয়ের শিক্ষকমণ্ডলী</span>
            </span>
            <h1 class="hero-anim text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                অভিভাবকের গভীর আস্থায় প্রতিটি সন্তানকে <span class="text-emerald-300 font-bold">যোগ্য ও আদর্শ মানুষ হিসেবে</span> গড়ে তোলা
            </h1>
            <p class="hero-anim text-sm sm:text-base text-emerald-100/90 leading-relaxed mt-2">
                ১৯৬৮ খ্রিষ্টাব্দ থেকে আমাদের নিবেদিতপ্রাণ ও সরকারি প্রশিক্ষণপ্রাপ্ত শিক্ষকবৃন্দ শিক্ষার্থীদের শুধু মুখস্থ পাঠ নয়; বরং মেধা, সততা, মানবিক মূল্যবোধ ও কার্যকর জ্ঞান দিয়ে প্রস্তুত করছেন।
            </p>
        </div>

        <div class="hero-anim shrink-0 flex items-center gap-4 bg-[#003820]/90 backdrop-blur-xs border border-emerald-700/70 px-6 py-4 rounded-2xl text-white shadow-md hover:border-emerald-500/80 transition-all duration-300 hover:-translate-y-0.5">
            <div class="w-13 h-13 rounded-xl bg-emerald-800/80 border border-emerald-600/50 text-emerald-200 flex flex-col items-center justify-center font-bold shrink-0 shadow-inner">
                <span class="text-[9px] uppercase tracking-wider font-extrabold -mb-0.5">ESTD</span>
                <span class="text-base font-extrabold font-mono text-white">{{ $school->established_year ?? '1968' }}</span>
            </div>
            <div class="text-xs">
                <p class="text-emerald-300/80 font-medium text-[11px]">প্রতিষ্ঠানের অনুষদ শক্তি</p>
                <p class="text-white font-bold text-sm sm:text-base">৩২ জন অভিজ্ঞ শিক্ষক ও কর্মকর্তা</p>
            </div>
        </div>
    </div>

    {{-- Subtle Decorative Elements --}}
    <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-emerald-800/20 pointer-events-none"></div>
    <div class="absolute left-1/2 -top-24 w-96 h-96 rounded-full bg-emerald-700/10 pointer-events-none"></div>
</div>

{{-- 3. Qualifying Credentials & Telemetry Strip --}}
<div class="telemetry-strip bg-slate-900 text-slate-200 py-4 border-y border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs font-semibold">
            <div class="telemetry-metric-item flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800/60 transition-colors">
                <span class="w-9 h-9 rounded-xl bg-slate-800 text-emerald-400 flex items-center justify-center text-sm shrink-0 border border-slate-700 shadow-2xs"><i class="fa-solid fa-chalkboard-user"></i></span>
                <div>
                    <span class="text-slate-400 block text-[10px]">অনুষদ সদস্য সংখ্যা</span>
                    <span class="text-white font-bold text-xs sm:text-sm">৩২ জন শিক্ষক ও কর্মকর্তা</span>
                </div>
            </div>
            <div class="telemetry-metric-item flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800/60 transition-colors">
                <span class="w-9 h-9 rounded-xl bg-slate-800 text-emerald-400 flex items-center justify-center text-sm shrink-0 border border-slate-700 shadow-2xs"><i class="fa-solid fa-graduation-cap"></i></span>
                <div>
                    <span class="text-slate-400 block text-[10px]">উচ্চতর প্রাতিষ্ঠানিক যোগ্যতা</span>
                    <span class="text-white font-bold text-xs sm:text-sm">৮৫%+ স্নাতকোত্তর ও ১ম শ্রেণি</span>
                </div>
            </div>
            <div class="telemetry-metric-item flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800/60 transition-colors">
                <span class="w-9 h-9 rounded-xl bg-slate-800 text-emerald-400 flex items-center justify-center text-sm shrink-0 border border-slate-700 shadow-2xs"><i class="fa-solid fa-users-line"></i></span>
                <div>
                    <span class="text-slate-400 block text-[10px]">শ্রেণিকক্ষে নিবিড় মনোযোগ</span>
                    <span class="text-white font-bold text-xs sm:text-sm">১:৩০ শিক্ষক-শিক্ষার্থী অনুপাত</span>
                </div>
            </div>
            <div class="telemetry-metric-item flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800/60 transition-colors">
                <span class="w-9 h-9 rounded-xl bg-slate-800 text-emerald-400 flex items-center justify-center text-sm shrink-0 border border-slate-700 shadow-2xs"><i class="fa-solid fa-certificate"></i></span>
                <div>
                    <span class="text-slate-400 block text-[10px]">পেশাগত পাঠদান যোগ্যতা</span>
                    <span class="text-white font-bold text-xs sm:text-sm">১০০% বি.এড ও সরকারি প্রশিক্ষিত</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 4. Leadership Spotlight (Recipe 2: Break the Symmetry & Recipe 4: Pick the Winner) --}}
<section class="home-section bg-white" id="leadership">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header Rhythm --}}
        <div class="section-header-block">
            <div class="section-header-left">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    INSTITUTIONAL LEADERSHIP — প্রাতিষ্ঠানিক অভিভাবকত্ব ও তত্ত্বাবধান
                </span>
                <h2 class="section-title">
                    প্রতিষ্ঠানের অভিভাবকত্ব ও <span class="text-[#005030] font-bold">সুস্পষ্ট দিকনির্দেশনা</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    অভিজ্ঞ শিক্ষাবিদ ও প্রশাসনিক নেতৃত্বের প্রত্যক্ষ তত্ত্বাবধানে সোনাতলা উচ্চ বিদ্যালয় প্রতিটি শিক্ষার্থীর মেধা বিকাশ, চরিত্র গঠন ও শৃঙ্খলার সর্বোচ্চ নিশ্চয়তা প্রদান করে।
                </p>
            </div>
        </div>

        {{-- Asymmetric 65/35 Leadership Grid (Properly Balanced Proportions) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            {{-- Flagship Winner: Headmaster (65% width / 7 cols) --}}
            <div class="leadership-card lg:col-span-7 bg-slate-50/80 border border-slate-200/90 rounded-2xl p-6 sm:p-7 flex flex-col justify-between shadow-2xs hover:shadow-md hover:border-[#005030]/40 transition-all duration-300">
                <div class="space-y-5">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 pb-5 border-b border-slate-200/80">
                        {{-- Well-proportioned portrait frame --}}
                        <div class="relative w-28 h-32 sm:w-32 sm:h-36 rounded-2xl overflow-hidden bg-slate-200 border-2 border-emerald-800/30 shadow-xs shrink-0 group">
                            <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=400&q=80"
                                 alt="মো: খলিলুর রহমান - প্রধান শিক্ষক"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute bottom-0 inset-x-0 bg-slate-900/85 text-[10px] text-white text-center py-0.5 font-bold">
                                প্রতিষ্ঠান প্রধান
                            </div>
                        </div>

                        <div class="space-y-1.5 flex-1 min-w-0">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-100 text-[#005030] text-[11px] font-bold border border-emerald-300">
                                <i class="fa-solid fa-crown text-[10px]"></i>
                                প্রধান শিক্ষক (Headmaster)
                            </span>
                            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                                মো: খলিলুর রহমান
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 font-medium">
                                এম.এ (ইতিহাস, ১ম শ্রেণি), বি.এড (১ম শ্রেণি, রাজশাহী বিশ্ববিদ্যালয়)
                            </p>
                            <p class="text-xs text-emerald-800 font-semibold flex items-center gap-1.5 pt-0.5">
                                <i class="fa-solid fa-calendar-check text-[11px]"></i>
                                শিক্ষাদান ও বিদ্যালয় প্রশাসনে ২০+ বছরের সুদীর্ঘ অভিজ্ঞতা
                            </p>
                        </div>
                    </div>

                    {{-- Leadership Manifesto Quote --}}
                    <blockquote class="text-xs sm:text-sm text-slate-700 leading-relaxed italic bg-white p-4 rounded-xl border-l-3 border-[#005030] shadow-2xs">
                        "আমাদের প্রথম দায়িত্ব শুধু পাঠ্যবইয়ের জ্ঞান বিতরণ নয়; বরং স্নেহ, মমতা ও উপযুক্ত শৃঙ্খলার মাধ্যমে প্রতিটি সন্তানকে একজন সৎ, আত্মবিশ্বাসী ও আদর্শ মানুষ হিসেবে সমাজে প্রতিষ্ঠিত করা।"
                    </blockquote>

                    {{-- Office Hours & Schedule Strip --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-1">
                        <div class="bg-white p-3 rounded-xl border border-slate-200/80 flex items-center gap-2.5 shadow-2xs">
                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center shrink-0"><i class="fa-solid fa-clock"></i></span>
                            <div>
                                <span class="text-slate-400 block text-[10px]">সরাসরি অভিভাবক সাক্ষাৎ</span>
                                <span class="text-slate-800 font-bold text-[11px]">রবি - বৃহস্পতি (সকাল ১০:০০ - ১২:০০)</span>
                            </div>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200/80 flex items-center gap-2.5 shadow-2xs">
                            <span class="w-7 h-7 rounded-lg bg-sky-50 text-sky-700 flex items-center justify-center shrink-0"><i class="fa-solid fa-phone"></i></span>
                            <div>
                                <span class="text-slate-400 block text-[10px]">প্রধান শিক্ষকের ব্যক্তিগত দপ্তর</span>
                                <a href="tel:+8801712345678" class="text-[#005030] font-bold text-[11px] hover:underline">+৮৮০ ১৭১২-৩৪৫৬৭৮</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-5 mt-5 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-500">
                    <span class="font-medium">কার্যালয়: প্রধান শিক্ষকের কক্ষ (১০১)</span>
                    <a href="mailto:headmaster@sonatala.edu.bd" class="text-[#005030] font-bold hover:underline flex items-center gap-1.5 transition-colors">
                        <i class="fa-solid fa-envelope"></i>
                        <span>ইমেইল বার্তা পাঠান</span>
                    </a>
                </div>
            </div>

            {{-- Fixed Assistant Headmaster Card (35% width / 5 cols) --}}
            <div class="leadership-card lg:col-span-5 bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 flex flex-col justify-between shadow-2xs hover:shadow-md hover:border-[#005030]/40 transition-all duration-300">
                <div class="space-y-5">
                    {{-- Fixed portrait header with strict shrink-0 and explicit dimensions --}}
                    <div class="flex items-center gap-4 pb-5 border-b border-slate-100">
                        <div class="relative w-24 h-28 sm:w-28 sm:h-32 rounded-2xl overflow-hidden bg-slate-100 border-2 border-emerald-800/25 shadow-xs shrink-0 group">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80"
                                 alt="মোছা: রোকসানা পারভীন - সহকারী প্রধান শিক্ষক"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute bottom-0 inset-x-0 bg-slate-900/85 text-[10px] text-white text-center py-0.5 font-bold">
                                সহকারী প্রধান
                            </div>
                        </div>
                        <div class="space-y-1 flex-1 min-w-0">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold border border-slate-200">
                                সহকারী প্রধান শিক্ষক
                            </span>
                            <h3 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight truncate">
                                মোছা: রোকসানা পারভীন
                            </h3>
                            <p class="text-xs text-slate-600 font-medium">
                                এম.এসসি (পদার্থবিজ্ঞান), বি.এড
                            </p>
                            <span class="text-[11px] text-[#005030] font-semibold block">
                                একাডেমিক ও পরীক্ষা নিয়ন্ত্রণ সমন্বয়ক
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs text-slate-600 leading-relaxed">
                        <p class="text-slate-600">
                            শ্রেণিকক্ষে শিক্ষার্থীদের নিয়মিত উপস্থিতি, পাঠদানের গুণগত মান বজায় রাখা এবং পিছিয়ে পড়া শিক্ষার্থীদের নিবিড় পর্যবেক্ষণসহ সার্বিক অভ্যন্তরীণ শৃঙ্খলা তিনি পরিচালনা করেন।
                        </p>

                        <div class="space-y-2 pt-1">
                            <div class="flex items-center gap-2 text-slate-700 font-medium bg-slate-50 p-2 rounded-lg">
                                <i class="fa-solid fa-check-circle text-emerald-600 text-xs shrink-0"></i>
                                <span>বিজ্ঞানাগার ও আইসিটি ল্যাবের ব্যবহারিক পাঠ তদারকি</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-700 font-medium bg-slate-50 p-2 rounded-lg">
                                <i class="fa-solid fa-check-circle text-emerald-600 text-xs shrink-0"></i>
                                <span>শিক্ষার্থীদের আচরণিক মূল্যায়ন ও সহশিক্ষা ইনচার্জ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="font-medium">কক্ষ: শিক্ষক মিলনায়তন (১০৩)</span>
                    <a href="tel:+8801713456789" class="text-[#005030] font-bold hover:underline flex items-center gap-1.5 transition-colors">
                        <i class="fa-solid fa-phone"></i>
                        <span>+৮৮০ ১৭১৩-৪৫৬৭৮৯</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Hairline Structural Divider --}}
<div class="home-section-divider"></div>

{{-- 5. Core Faculty & Staff Directory (The "What") --}}
<section class="home-section bg-slate-50/60" id="faculty-directory">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header Rhythm --}}
        <div class="section-header-block">
            <div class="section-header-left">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    ACADEMIC FACULTY — সম্মানিত শিক্ষকমণ্ডলী
                </span>
                <h2 class="section-title">
                    শ্রেণিকক্ষে আন্তরিক পাঠদানে <span class="text-[#005030] font-bold">অভিজ্ঞ বিষয়ভিত্তিক শিক্ষকমণ্ডলী</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    বিজ্ঞান, মানবিক, ব্যবসায় শিক্ষা, ভাষা ও তথ্যপ্রযুক্তির প্রতিটি বিষয়ে দক্ষ ও যত্নশীল শিক্ষকবৃন্দ আপনার সন্তানের যেকোনো একাডেমিক প্রয়োজনে পাশে থাকতে প্রস্তুত।
                </p>
            </div>
        </div>

        {{-- Filter & Realtime Search Toolbar (No Page Reload) --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs mb-8 space-y-4">
            {{-- Realtime Search Bar --}}
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <input type="text"
                           id="teacher-search-input"
                           value="{{ request('q') }}"
                           placeholder="শিক্ষকের নাম, বিষয়, পদবি বা বিভাগ লিখে তাৎক্ষণিক খুঁজুন..."
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 pl-10 pr-10 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005030] focus:border-transparent transition-all">
                    
                    <span class="absolute left-3.5 top-3 text-slate-400 pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>

                    <button type="button"
                            id="clear-search-btn"
                            class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 p-1 rounded-full hover:bg-slate-200 transition-colors {{ request('q') ? 'flex' : 'hidden' }}"
                            title="অনুসন্ধান মুছুন">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 justify-between sm:justify-start">
                    <span class="text-xs text-slate-500 font-medium">
                        প্রদর্শিত শিক্ষক: <strong id="visible-teacher-count" class="text-[#005030] font-bold">--</strong> জন
                    </span>
                    <button type="button" 
                            id="reset-filters-btn"
                            class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors flex items-center gap-1 shadow-2xs">
                        <i class="fa-solid fa-rotate-left text-[11px]"></i>
                        <span>রিসেট</span>
                    </button>
                </div>
            </div>

            {{-- Realtime Department Filter Pill Tabs (No Reload) --}}
            @php
                $standardDepts = [
                    'all' => 'সকল অনুষদ',
                    'science' => 'বিজ্ঞান বিভাগ',
                    'humanities' => 'মানবিক বিভাগ',
                    'commerce' => 'ব্যবসায় শিক্ষা',
                    'language' => 'ভাষা ও সাহিত্য',
                    'ict' => 'আইসিটি ও কম্পিউটার',
                    'religious' => 'ধর্ম ও নৈতিক শিক্ষা',
                    'admin' => 'প্রশাসন ও স্টাফ',
                ];

                // Curated high quality teacher portraits so that no photo is ever blank
                $defaultTeacherPhotos = [
                    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1580894732484-934d4001ec4c?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=400&q=80',
                ];

                $fallbackTeachers = [
                    [
                        'name' => 'মো: আব্দুল মজিদ',
                        'designation' => 'সিনিয়র শিক্ষক (গণিত ও উচ্চতর গণিত)',
                        'department' => 'বিজ্ঞান বিভাগ',
                        'dept_slug' => 'science',
                        'qualification' => 'বি.এসসি (অনার্স), এম.এসসি (গণিত, ১ম শ্রেণি)',
                        'experience' => '১২+ বছর',
                        'phone' => '+৮৮০ ১৭১৪-৫৬৭৮৯০',
                        'email' => 'majid.math@sonatala.edu.bd',
                        'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                        'specialty' => 'গণিত অলিম্পিয়াড সমন্বয়ক',
                    ],
                    [
                        'name' => 'মো: আনিসুর রহমান',
                        'designation' => 'সিনিয়র শিক্ষক (বাংলা সাহিত্য ও ব্যাকরণ)',
                        'department' => 'মানবিক বিভাগ',
                        'dept_slug' => 'humanities',
                        'qualification' => 'এম.এ (বাংলা সাহিত্য), বি.এড (১ম শ্রেণি)',
                        'experience' => '১৪+ বছর',
                        'phone' => '+৮৮০ ১৭১৫-৬৭৮৯০১',
                        'email' => 'anisur.bangla@sonatala.edu.bd',
                        'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
                        'specialty' => 'বিতর্ক ক্লাব ও সাহিত্য পরিষদ পরিচালক',
                    ],
                    [
                        'name' => 'মো: কামরুল হাসান',
                        'designation' => 'সহকারী শিক্ষক (ইংরেজি ভাষা ও ব্যাকরণ)',
                        'department' => 'ভাষা ও সাহিত্য',
                        'dept_slug' => 'language',
                        'qualification' => 'বি.এ (অনার্স), এম.এ (ইংরেজি ভাষা ও সাহিত্য)',
                        'experience' => '৯+ বছর',
                        'phone' => '+৮৮০ ১৭১৬-৭৮৯০১২',
                        'email' => 'kamrul.english@sonatala.edu.bd',
                        'photo' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
                        'specialty' => 'স্পোকেন ইংলিশ ও ল্যাঙ্গুয়েজ ল্যাব মেন্টর',
                    ],
                    [
                        'name' => 'মোছা: নাসরিন সুলতানা',
                        'designation' => 'সহকারী শিক্ষক (জীববিজ্ঞান ও রসায়ন)',
                        'department' => 'বিজ্ঞান বিভাগ',
                        'dept_slug' => 'science',
                        'qualification' => 'বি.এসসি (অনার্স), এম.এসসি (রসায়ন বিজ্ঞান)',
                        'experience' => '৮+ বছর',
                        'phone' => '+৮৮০ ১৭১৭-৮৯০১২৩',
                        'email' => 'nasrin.science@sonatala.edu.bd',
                        'photo' => 'https://images.unsplash.com/photo-1580894732484-934d4001ec4c?auto=format&fit=crop&w=400&q=80',
                        'specialty' => 'বিজ্ঞান মেলা ও ল্যাব প্র্যাকটিক্যাল ইনচার্জ',
                    ],
                    [
                        'name' => 'মো: মোশাররফ হোসেন',
                        'designation' => 'সহকারী শিক্ষক (তথ্য ও যোগাযোগ প্রযুক্তি)',
                        'department' => 'আইসিটি ও কম্পিউটার',
                        'dept_slug' => 'ict',
                        'qualification' => 'বি.এসসি (কম্পিউটার সায়েন্স), ডিপ্লোমা ইন আইসিটি',
                        'experience' => '৬+ বছর',
                        'phone' => '+৮৮০ ১৭১৮-৯০১২৩৪',
                        'email' => 'mosharraf.ict@sonatala.edu.bd',
                        'photo' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
                        'specialty' => 'শেখ রাসেল ডিজিটাল ল্যাব ও কোডিং ক্লাব প্রশিক্ষক',
                    ],
                    [
                        'name' => 'মাওলানা মো: ইউসুফ আলী',
                        'designation' => 'সহকারী শিক্ষক (ধর্ম ও নৈতিক শিক্ষা)',
                        'department' => 'ধর্ম ও নৈতিক শিক্ষা',
                        'dept_slug' => 'religious',
                        'qualification' => 'ফাযিল ও কামিল (হাদিস বিভাগ, ১ম শ্রেণি)',
                        'experience' => '১৩+ বছর',
                        'phone' => '+৮৮০ ১৭১৯-০১২৩৪৫',
                        'email' => 'yusuf.islamic@sonatala.edu.bd',
                        'photo' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
                        'specialty' => 'কুরআন তিলাওয়াত ও হামদ-নাত প্রশিক্ষণ',
                    ],
                    [
                        'name' => 'মো: জহুরুল ইসলাম',
                        'designation' => 'সহকারী শিক্ষক (ব্যবসায় উদ্যোগ ও হিসাববিজ্ঞান)',
                        'department' => 'ব্যবসায় শিক্ষা',
                        'dept_slug' => 'commerce',
                        'qualification' => 'বি.বি.এস (অনার্স), এম.বি.এস (হিসাববিজ্ঞান)',
                        'experience' => '৭+ বছর',
                        'phone' => '+৮৮০ ১৭১১-২৩৪৫৬৭',
                        'email' => 'zohurul.commerce@sonatala.edu.bd',
                        'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                        'specialty' => 'ফিন্যান্স ও উদ্যোক্তা উন্নয়ন কোর্স সহায়ক',
                    ],
                    [
                        'name' => 'মো: সাইফুল ইসলাম',
                        'designation' => 'সহকারী শিক্ষক (শারীরিক শিক্ষা ও ক্রীড়া)',
                        'department' => 'প্রশাসন ও স্টাফ',
                        'dept_slug' => 'admin',
                        'qualification' => 'বি.পি.এড (শারীরিক শিক্ষা), ১ম শ্রেণি',
                        'experience' => '১১+ বছর',
                        'phone' => '+৮৮০ ১৭১০-৯৮৭৬৫৪',
                        'email' => 'saiful.sports@sonatala.edu.bd',
                        'photo' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=400&q=80',
                        'specialty' => 'বার্ষিক ক্রীড়া প্রতিযোগিতা ও রোভার স্কাউট লিডার',
                    ],
                ];

                $displayStaff = [];

                if (isset($teachers) && $teachers->isNotEmpty()) {
                    foreach ($teachers as $idx => $dbStaff) {
                        $fName = $dbStaff->full_name ?: trim($dbStaff->first_name . ' ' . $dbStaff->last_name);
                        $dDept = $dbStaff->department ?: 'বিজ্ঞান বিভাগ';
                        $dSlug = 'science';
                        if (str_contains($dDept, 'মানবিক')) $dSlug = 'humanities';
                        elseif (str_contains($dDept, 'ব্যবসা')) $dSlug = 'commerce';
                        elseif (str_contains($dDept, 'ভাষা') || str_contains($dDept, 'English') || str_contains($dDept, 'বাংলা')) $dSlug = 'language';
                        elseif (str_contains($dDept, 'আইসিটি') || str_contains($dDept, 'Computer')) $dSlug = 'ict';
                        elseif (str_contains($dDept, 'ধর্ম')) $dSlug = 'religious';
                        elseif (str_contains($dDept, 'প্রশাসন') || str_contains($dDept, 'Library') || str_contains($dDept, 'Finance')) $dSlug = 'admin';

                        $photoUrl = !empty($dbStaff->photo) 
                            ? asset('storage/' . $dbStaff->photo) 
                            : $defaultTeacherPhotos[$idx % count($defaultTeacherPhotos)];

                        $displayStaff[] = [
                            'name' => $fName,
                            'designation' => $dbStaff->designation ?: 'অনুষদ সদস্য',
                            'department' => $dDept,
                            'dept_slug' => $dSlug,
                            'qualification' => $dbStaff->qualification ?: 'স্নাতকোত্তর ও বি.এড',
                            'experience' => $dbStaff->experience_years ? ($dbStaff->experience_years . '+ বছর') : '৫+ বছর',
                            'phone' => $dbStaff->phone ?: '+৮৮০ ১৭১১-০০০০০০',
                            'email' => $dbStaff->email ?: 'teacher@sonatala.edu.bd',
                            'photo' => $photoUrl,
                            'specialty' => 'বিষয়ভিত্তিক পাঠদান ও মেন্টরিং',
                        ];
                    }
                } elseif (!request()->filled('q') && !request()->filled('department')) {
                    $displayStaff = $fallbackTeachers;
                }
            @endphp

            <div class="pt-3 border-t border-slate-100 flex items-center gap-2 overflow-x-auto pb-1 text-xs">
                <span class="text-slate-400 font-medium text-[11px] shrink-0 mr-1">বিভাগসমূহ:</span>
                @foreach($standardDepts as $slug => $label)
                    <button type="button"
                            data-department="{{ $slug }}"
                            class="dept-filter-tab px-3.5 py-1.5 rounded-full font-semibold transition-all shrink-0 cursor-pointer {{ $loop->first ? 'bg-[#005030] text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200/90' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Teacher Cards Grid with Controlled Non-Elongated Photo Aspect Ratio --}}
        <div id="teachers-grid-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($displayStaff as $staff)
                <div class="teacher-card-item bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:shadow-xl hover:border-[#005030]/50 transition-all duration-300 hover:-translate-y-2 flex flex-col justify-between group"
                     data-department="{{ $staff['dept_slug'] }}"
                     data-department-name="{{ $staff['department'] }}"
                     data-name="{{ $staff['name'] }}"
                     data-designation="{{ $staff['designation'] }}"
                     data-qualification="{{ $staff['qualification'] }}">
                    <div>
                        {{-- Top Accent Line --}}
                        <div class="h-1 w-full bg-slate-100 group-hover:bg-[#005030] transition-colors duration-300 rounded-full mb-3.5"></div>

                        {{-- Perfectly Proportioned Compact Photo Frame (NOT Too Tall) --}}
                        <div class="h-48 w-full rounded-xl overflow-hidden bg-slate-100 relative mb-3.5 border border-slate-200/70 group-hover:border-emerald-600/40 transition-colors shadow-2xs">
                            <img src="{{ $staff['photo'] }}"
                                 alt="{{ $staff['name'] }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-500 ease-out">

                            {{-- Pinned Department Badge Tag --}}
                            <div class="absolute bottom-2.5 left-2.5 bg-slate-900/85 text-white backdrop-blur-xs text-[11px] font-bold px-2.5 py-1 rounded-md shadow-sm">
                                {{ $staff['department'] }}
                            </div>

                            {{-- Experience Badge --}}
                            <div class="absolute top-2.5 right-2.5 bg-white/95 text-slate-800 backdrop-blur-xs text-[10px] font-bold px-2 py-0.5 rounded-md border border-slate-200/90 shadow-2xs">
                                {{ $staff['experience'] }}
                            </div>
                        </div>

                        {{-- Content Block --}}
                        <div class="space-y-1">
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-[#005030] transition-colors leading-snug">
                                {{ $staff['name'] }}
                            </h3>
                            <p class="text-xs sm:text-sm font-semibold text-emerald-800">
                                {{ $staff['designation'] }}
                            </p>
                            <p class="text-xs text-slate-600 leading-relaxed pt-1.5 border-t border-slate-100 line-clamp-2" title="{{ $staff['qualification'] }}">
                                {{ $staff['qualification'] }}
                            </p>
                            @if(!empty($staff['specialty']))
                                <p class="text-[11px] text-slate-500 font-medium pt-1 flex items-center gap-1.5 truncate">
                                    <i class="fa-solid fa-award text-amber-500 text-[10px] shrink-0"></i>
                                    <span class="truncate">{{ $staff['specialty'] }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Action Footer with Enhanced Tactile Buttons --}}
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600 mt-4 gap-2">
                        <a href="tel:{{ $staff['phone'] }}" 
                           class="flex-1 px-3 py-1.5 rounded-lg bg-emerald-50/80 hover:bg-[#005030] text-[#005030] hover:text-white font-semibold transition-colors duration-200 flex items-center justify-center gap-1.5 shadow-2xs group/btn">
                            <i class="fa-solid fa-phone text-xs group-hover/btn:scale-110 transition-transform"></i>
                            <span class="text-[11px] truncate">{{ $staff['phone'] }}</span>
                        </a>

                        <a href="mailto:{{ $staff['email'] }}" 
                           class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-sky-600 text-slate-600 hover:text-white flex items-center justify-center transition-colors duration-200 shadow-2xs shrink-0 group/mail"
                           title="{{ $staff['email'] }}">
                            <i class="fa-solid fa-envelope text-xs group-hover/mail:scale-110 transition-transform"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Dynamic Realtime Empty Search State --}}
        <div id="no-teachers-found" class="hidden bg-white border border-slate-200/90 rounded-2xl p-10 text-center space-y-4 max-w-lg mx-auto shadow-2xs mt-4">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-user-slash"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-slate-800">কোনো শিক্ষক খুঁজে পাওয়া যায়নি</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    আপনার অনুসন্ধানকৃত বিষয়ের বা নামের সাথে মিল রয়েছে এমন কোনো শিক্ষক তালিকায় পাওয়া যায়নি। অনুগ্রহ করে বানান যাচাই করুন অথবা ফিল্টার রিসেট করুন।
                </p>
            </div>
            <div class="pt-2">
                <button type="button" 
                        onclick="document.getElementById('reset-filters-btn').click()"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-[#005030] text-white text-xs font-bold hover:bg-[#003820] transition-colors shadow-xs cursor-pointer">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                    <span>সকল শিক্ষক প্রদর্শন করুন</span>
                </button>
            </div>
        </div>

    </div>
</section>

{{-- Hairline Structural Divider --}}
<div class="home-section-divider"></div>

{{-- 6. Faculty Pedagogical Standards & Mentorship Pillars (The "Why") --}}
<section class="home-section bg-white" id="pedagogy-standards">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header Rhythm --}}
        <div class="section-header-block">
            <div class="section-header-left">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    TEACHING EXCELLENCE — পাঠদান মানদণ্ড ও শিক্ষক অঙ্গীকার
                </span>
                <h2 class="section-title">
                    মুখস্থ নির্ভরতা পরিহার করে <span class="text-[#005030] font-bold">বোঝা ও প্রয়োগের শিক্ষা</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    সোনাতলা উচ্চ বিদ্যালয়ে আমরা প্রতিটি শিক্ষার্থীর স্বাভাবিক কৌতূহলকে মূল্যায়ন করি এবং নিয়মিত ব্যবহারিক ক্লাস ও যত্নশীল মেন্টরিংয়ের মাধ্যমে বিষয়ের মূল ভিত্তি শক্ত করি।
                </p>
            </div>
        </div>

        {{-- 3 Clean Editorial Capability Cards with Hover Lift --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Pillar 1 --}}
            <div class="pedagogy-card bg-slate-50/70 border border-slate-200/90 rounded-2xl p-6 space-y-3.5 shadow-2xs hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-[#005030] flex items-center justify-center text-xl font-bold shadow-2xs">
                    <i class="fa-solid fa-book-open-reader"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">
                    দৈনিক সুনির্দিষ্ট পাঠ পরিকল্পনা ও শিখনফল
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    প্রতিটি ক্লাসের আগেই শিক্ষকগণ জাতীয় পাঠ্যক্রম অনুযায়ী বিস্তারিত লেসন প্ল্যান তৈরি করেন। এতে প্রতিটি পিরিয়ডে নির্ধারিত শিখনফল পূর্ণাঙ্গভাবে অর্জন করা নিশ্চিত হয়।
                </p>
                <div class="pt-2 text-xs font-semibold text-[#005030] flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-[11px]"></i>
                    <span>সাপ্তাহিক শিক্ষক সমন্বয় ও অগ্রগতি পর্যালোচনা</span>
                </div>
            </div>

            {{-- Pillar 2 --}}
            <div class="pedagogy-card bg-slate-50/70 border border-slate-200/90 rounded-2xl p-6 space-y-3.5 shadow-2xs hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-800 flex items-center justify-center text-xl font-bold shadow-2xs">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">
                    হাতে-কলমে ল্যাব ও মাল্টিমিডিয়া ক্লাস
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    তত্ত্বীয় আলোচনার পাশাপাশি শেখ রাসেল ডিজিটাল ল্যাব এবং পদার্থ, রসায়ন ও জীববিজ্ঞানের সুসজ্জিত গবেষণাগারে নিয়মিত ব্যবহারিক পরীক্ষা সম্পন্ন করানো হয়।
                </p>
                <div class="pt-2 text-xs font-semibold text-sky-800 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-[11px]"></i>
                    <span>স্মার্ট প্রজেক্টর ও মাল্টিমিডিয়া ক্লাসরুম</span>
                </div>
            </div>

            {{-- Pillar 3 --}}
            <div class="pedagogy-card bg-slate-50/70 border border-slate-200/90 rounded-2xl p-6 space-y-3.5 shadow-2xs hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-900 flex items-center justify-center text-xl font-bold shadow-2xs">
                    <i class="fa-solid fa-hands-holding-child"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">
                    দুর্বল শিক্ষার্থীদের জন্য নিবিড় রিমিডিয়াল কেয়ার
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    পড়াশোনায় যেকোনো বিষয়ে পিছিয়ে থাকা শিক্ষার্থীদের দ্রুত শনাক্ত করে নিয়মিত ক্লাসের অতিরিক্ত সময়ে বিশেষ শিক্ষক সহায়তার মাধ্যমে এগিয়ে নেওয়া হয়।
                </p>
                <div class="pt-2 text-xs font-semibold text-amber-800 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-[11px]"></i>
                    <span>বিনা খরচে বিশেষ টিউটোরিয়াল সাপোর্ট</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Hairline Structural Divider --}}
<div class="home-section-divider"></div>

{{-- 7. Call to Action: Guardian Consultation & Visiting Schedule (The Next Step) --}}
<section class="home-section bg-slate-50/80" id="consultation-cta">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="cta-box bg-gradient-to-br from-[#005030] to-[#003820] text-white rounded-3xl p-8 sm:p-12 lg:p-16 relative overflow-hidden shadow-lg border border-emerald-700/50">
            <div class="relative z-10 max-w-2xl space-y-4">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#002a18] text-emerald-200 text-xs font-bold uppercase tracking-wider border border-emerald-600/50 shadow-xs">
                    <i class="fa-solid fa-calendar-days text-[11px]"></i>
                    <span>GUARDIAN ENGAGEMENT — অভিভাবক পরামর্শ ও মতবিনিময়</span>
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    সন্তানের ভবিষ্যৎ ও অগ্রগতি নিয়ে <span class="text-amber-400 font-bold">শিক্ষকদের সাথে সরাসরি কথা বলুন</span>
                </h2>
                <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed">
                    আপনার সন্তানের ক্লাসরুমের অগ্রগতি, আচরণ কিংবা ভবিষ্যৎ পরিকল্পনা নিয়ে যেকোনো শিক্ষক বা প্রতিষ্ঠান প্রধানের সাথে কথা বলতে পারেন। প্রতি রবিবার থেকে বৃহস্পতিবার দুপুর ১২:৩০ হতে ১:৩০ পর্যন্ত শিক্ষক মিলনায়তনে সুনির্দিষ্ট অভিভাবক সাক্ষাৎ সময় নির্ধারিত রয়েছে।
                </p>

                {{-- Upgraded, High-Contrast Call to Action Buttons --}}
                <div class="pt-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    {{-- Primary Amber CTA Button --}}
                    <a href="{{ route('frontend.contact') }}"
                       class="px-8 py-4 rounded-xl bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 font-extrabold text-sm sm:text-base shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0 text-center flex items-center justify-center gap-2.5">
                        <span>সরাসরি সাক্ষাতের জন্য সময় নির্ধারণ করুন</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>

                    {{-- Secondary Hotline Button --}}
                    <a href="tel:+8801712345678"
                       class="px-7 py-4 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/25 font-bold text-sm sm:text-base backdrop-blur-xs shadow-xs hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0 text-center flex items-center justify-center gap-2.5">
                        <i class="fa-solid fa-phone text-amber-300"></i>
                        <span>হটলাইন: +৮৮০ ১৭১২-৩৪৫৬৭৮</span>
                    </a>
                </div>
            </div>

            {{-- Decorative Academic Graphic Geometry --}}
            <div class="hidden lg:block absolute -right-12 -bottom-12 w-96 h-96 rounded-full bg-emerald-800/30 border border-emerald-600/30 pointer-events-none"></div>
            <div class="hidden lg:block absolute right-24 bottom-12 text-emerald-800/30 text-9xl pointer-events-none">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // =========================================================================
    // 1. Real-time Department Tabs & Search Filter (Zero Page Reload)
    // =========================================================================
    const searchInput = document.getElementById('teacher-search-input');
    const clearSearchBtn = document.getElementById('clear-search-btn');
    const deptTabs = document.querySelectorAll('.dept-filter-tab');
    const teacherCards = document.querySelectorAll('.teacher-card-item');
    const noResultsState = document.getElementById('no-teachers-found');
    const resetFiltersBtn = document.getElementById('reset-filters-btn');
    const visibleCountBadge = document.getElementById('visible-teacher-count');

    let currentDept = 'all';
    let currentSearch = (searchInput ? searchInput.value.trim().toLowerCase() : '');

    function filterTeachers() {
        let visibleCount = 0;

        teacherCards.forEach(card => {
            const cardDeptSlug = (card.getAttribute('data-department') || '').toLowerCase();
            const cardDeptName = (card.getAttribute('data-department-name') || '').toLowerCase();
            const cardName = (card.getAttribute('data-name') || '').toLowerCase();
            const cardDesignation = (card.getAttribute('data-designation') || '').toLowerCase();
            const cardQualification = (card.getAttribute('data-qualification') || '').toLowerCase();

            // Check department match
            const matchesDept = (currentDept === 'all') || 
                                (cardDeptSlug === currentDept) || 
                                (currentDept === 'science' && cardDeptName.includes('বিজ্ঞান')) ||
                                (currentDept === 'humanities' && cardDeptName.includes('মানবিক')) ||
                                (currentDept === 'commerce' && cardDeptName.includes('ব্যবসা')) ||
                                (currentDept === 'language' && (cardDeptName.includes('ভাষা') || cardDeptName.includes('english') || cardDeptName.includes('বাংলা'))) ||
                                (currentDept === 'ict' && (cardDeptName.includes('আইসিটি') || cardDeptName.includes('কম্পিউটার'))) ||
                                (currentDept === 'religious' && cardDeptName.includes('ধর্ম')) ||
                                (currentDept === 'admin' && (cardDeptName.includes('প্রশাসন') || cardDeptName.includes('স্টাফ') || cardDeptName.includes('শারীরিক')));

            // Check search text match
            const matchesSearch = !currentSearch || 
                                  cardName.includes(currentSearch) || 
                                  cardDesignation.includes(currentSearch) || 
                                  cardDeptName.includes(currentSearch) ||
                                  cardQualification.includes(currentSearch);

            if (matchesDept && matchesSearch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCountBadge) {
            visibleCountBadge.textContent = visibleCount;
        }

        if (noResultsState) {
            noResultsState.style.display = (visibleCount === 0) ? 'block' : 'none';
        }
    }

    // Set initial count
    filterTeachers();

    // Tab Click Listeners
    deptTabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            currentDept = this.getAttribute('data-department') || 'all';

            // Update tab styles
            deptTabs.forEach(t => {
                t.classList.remove('bg-[#005030]', 'text-white', 'shadow-xs');
                t.classList.add('bg-white', 'text-slate-700', 'border', 'border-slate-200/90');
            });
            this.classList.remove('bg-white', 'text-slate-700', 'border', 'border-slate-200/90');
            this.classList.add('bg-[#005030]', 'text-white', 'shadow-xs');

            filterTeachers();
        });
    });

    // Real-time input search
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            currentSearch = this.value.trim().toLowerCase();
            if (clearSearchBtn) {
                if (currentSearch) {
                    clearSearchBtn.classList.remove('hidden');
                    clearSearchBtn.classList.add('flex');
                } else {
                    clearSearchBtn.classList.remove('flex');
                    clearSearchBtn.classList.add('hidden');
                }
            }
            filterTeachers();
        });
    }

    // Clear search button
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function () {
            searchInput.value = '';
            currentSearch = '';
            clearSearchBtn.classList.remove('flex');
            clearSearchBtn.classList.add('hidden');
            filterTeachers();
            searchInput.focus();
        });
    }

    // Reset button
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
                currentSearch = '';
            }
            if (clearSearchBtn) {
                clearSearchBtn.classList.remove('flex');
                clearSearchBtn.classList.add('hidden');
            }
            const allTab = document.querySelector('.dept-filter-tab[data-department="all"]');
            if (allTab) {
                allTab.click();
            }
        });
    }

    // =========================================================================
    // 2. Safe & Smooth Entrance Animations (Never Leaves Cards Invisible)
    // =========================================================================
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!prefersReducedMotion) {
        // Hero Content Animation (Immediate upon page load)
        if (window.gsap) {
            gsap.fromTo(".hero-anim", 
                { opacity: 0, y: 16 }, 
                { opacity: 1, y: 0, duration: 0.6, stagger: 0.08, ease: "power2.out", clearProps: "all" }
            );
        }

        // Section & Card Elements: Reveal ONLY when physically entering viewport
        if ('IntersectionObserver' in window) {
            const teacherPageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        observer.unobserve(el);

                        if (window.gsap) {
                            gsap.fromTo(el, 
                                { opacity: 0, y: 18 }, 
                                { opacity: 1, y: 0, duration: 0.5, ease: "power2.out", clearProps: "all" }
                            );
                        } else {
                            el.style.opacity = '1';
                            el.style.transform = 'none';
                        }
                    }
                });
            }, {
                rootMargin: '0px 0px -40px 0px',
                threshold: 0.05
            });

            const elementsToReveal = document.querySelectorAll(
                '.telemetry-metric-item, .leadership-card, .pedagogy-card, .cta-box'
            );

            elementsToReveal.forEach(el => {
                const rect = el.getBoundingClientRect();
                // If already inside the visible viewport, reveal immediately
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    if (window.gsap) {
                        gsap.fromTo(el, 
                            { opacity: 0, y: 14 }, 
                            { opacity: 1, y: 0, duration: 0.45, ease: "power2.out", clearProps: "all" }
                        );
                    }
                } else {
                    teacherPageObserver.observe(el);
                }
            });
        }
    }

    // Failsafe guarantee: ensure NO elements on this page remain at opacity: 0
    const ensureTeachersPageVisible = () => {
        document.querySelectorAll(
            '.hero-anim, .telemetry-metric-item, .leadership-card, .pedagogy-card, .cta-box, .teacher-card-item, .section-header-block'
        ).forEach(el => {
            if (window.getComputedStyle(el).opacity === '0') {
                el.style.opacity = '1';
                el.style.transform = 'none';
            }
        });
    };

    window.addEventListener('load', ensureTeachersPageVisible);
    setTimeout(ensureTeachersPageVisible, 500);
});
</script>
@endpush
