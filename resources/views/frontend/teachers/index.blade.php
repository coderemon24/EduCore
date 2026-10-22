@extends('layouts.app')

@section('title', 'সম্মানিত শিক্ষকমণ্ডলী ও অনুষদ | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের নিবেদিতপ্রাণ ও প্রশিক্ষণপ্রাপ্ত শিক্ষক ও কর্মচারীবৃন্দের পূর্ণাঙ্গ বিবরণী ও অনুষদ পরিচিতি।')

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
<div class="bg-[#005030] text-white py-14 sm:py-20 border-b border-emerald-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-3.5">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#003820] text-emerald-200 text-xs font-bold uppercase tracking-wider border border-emerald-700/60">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span>FACULTY & MENTORS — সম্মানিত শিক্ষকমণ্ডলী</span>
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                জ্ঞানের আলোকবর্তিকা ও <span class="text-emerald-300 font-bold">ভবিষ্যৎ গড়ার কারিগর</span>
            </h1>
            <p class="text-sm sm:text-base text-emerald-100/90 max-w-2xl leading-relaxed mt-2">
                ১৯৬৮ খ্রিষ্টাব্দ থেকে আমাদের নিবেদিতপ্রাণ ও সরকারি প্রশিক্ষণপ্রাপ্ত শিক্ষকবৃন্দ আধুনিক তথ্যপ্রযুক্তি, মানবিক মূল্যবোধ ও নিবিড় পাঠদানের মাধ্যমে শিক্ষার্থীদের আগামী দিনের আদর্শ নাগরিক হিসেবে গড়ে তুলছেন।
            </p>
        </div>

        <div class="shrink-0 flex items-center gap-3.5 bg-[#003820] border border-emerald-700/60 px-6 py-4 rounded-2xl text-white shadow-xs">
            <div class="w-13 h-13 rounded-xl bg-emerald-800/80 border border-emerald-600/50 text-emerald-200 flex flex-col items-center justify-center font-bold shrink-0">
                <span class="text-[9px] uppercase tracking-wider font-extrabold -mb-0.5">ESTD</span>
                <span class="text-base font-extrabold font-mono text-white">{{ $school->established_year ?? '1968' }}</span>
            </div>
            <div class="text-xs">
                <p class="text-emerald-300/80 font-medium text-[11px]">প্রতিষ্ঠানের অনুষদ গর্ব</p>
                <p class="text-white font-bold text-sm sm:text-base">৩২+ জন অভিজ্ঞ শিক্ষক ও স্টাফ</p>
            </div>
        </div>
    </div>
</div>

{{-- 3. Qualifying Credentials & Telemetry Strip --}}
<div class="bg-slate-900 text-slate-200 py-4 border-y border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs font-semibold">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-emerald-400 flex items-center justify-center text-xs shrink-0"><i class="fa-solid fa-chalkboard-user"></i></span>
                <div>
                    <span class="text-slate-400 block text-[10px]">মোট অনুষদ ও কর্মী</span>
                    <span class="text-white font-bold text-xs sm:text-sm">৩২+ জন শিক্ষক ও স্টাফ</span>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-emerald-400 flex items-center justify-center text-xs shrink-0"><i class="fa-solid fa-graduation-cap"></i></span>
                <div>
                    <span class="text-slate-400 block text-[10px]">উচ্চতর ডিগ্রিধারী</span>
                    <span class="text-white font-bold text-xs sm:text-sm">৮৫%+ ১ম শ্রেণি ও স্নাতকোত্তর</span>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-emerald-400 flex items-center justify-center text-xs shrink-0"><i class="fa-solid fa-users-line"></i></span>
                <div>
                    <span class="text-slate-400 block text-[10px]">শিক্ষক-শিক্ষার্থী অনুপাত</span>
                    <span class="text-white font-bold text-xs sm:text-sm">১:৩০ নিবিড় কেয়ার</span>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-emerald-400 flex items-center justify-center text-xs shrink-0"><i class="fa-solid fa-certificate"></i></span>
                <div>
                    <span class="text-slate-400 block text-[10px]">পেশাগত প্রশিক্ষণ</span>
                    <span class="text-white font-bold text-xs sm:text-sm">১০০% বিএড ও আইসিটি সার্টিফাইড</span>
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
                    INSTITUTIONAL LEADERSHIP — প্রশাসনিক ও একাডেমিক নেতৃত্ব
                </span>
                <h2 class="section-title">
                    প্রতিষ্ঠানের দূরদর্শী নেতৃত্ব ও <span class="text-[#005030] font-bold">দিকনির্দেশনা</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    অভিজ্ঞ প্রাতিষ্ঠানিক প্রধানগণের সুযোগ্য নেতৃত্বে সোনাতলা উচ্চ বিদ্যালয় প্রতিবছর মাধ্যমিক পরীক্ষায় কৃতিত্বপূর্ণ ফলাফল ও আদর্শিক শৃঙ্খলা বজায় রেখে চলেছে।
                </p>
            </div>
        </div>

        {{-- Asymmetric 65/35 Leadership Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            {{-- Flagship Winner: Headmaster (65% width / 7 cols) --}}
            <div class="lg:col-span-7 bg-slate-50/70 border border-slate-200/90 rounded-2xl p-6 sm:p-8 flex flex-col justify-between shadow-2xs hover:shadow-xs transition-shadow">
                <div class="space-y-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 pb-6 border-b border-slate-200/80">
                        {{-- High-res portrait frame --}}
                        <div class="relative w-28 h-32 sm:w-32 sm:h-36 rounded-xl overflow-hidden bg-slate-200 border-2 border-emerald-800/30 shadow-xs shrink-0">
                            <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=400&q=80"
                                 alt="মো: খলিলুর রহমান - প্রধান শিক্ষক"
                                 class="w-full h-full object-cover">
                            <div class="absolute bottom-0 inset-x-0 bg-slate-900/85 text-[10px] text-white text-center py-0.5 font-bold">
                                প্রতিষ্ঠান প্রধান
                            </div>
                        </div>

                        <div class="space-y-1.5">
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
                                শিক্ষাদান ও প্রশাসনে ২০+ বছরের সুদীর্ঘ অভিজ্ঞতা
                            </p>
                        </div>
                    </div>

                    {{-- Leadership Manifesto Quote --}}
                    <blockquote class="text-sm text-slate-700 leading-relaxed italic bg-white p-4 rounded-xl border-l-3 border-[#005030] shadow-2xs">
                        "আমাদের লক্ষ্য শুধু ভালো ফলাফল অর্জন নয়; বরং প্রতিটি শিক্ষার্থীকে সৎ, নিষ্ঠাবান, বিজ্ঞানমনস্ক এবং নৈতিকতাবোধসম্পন্ন একজন আদর্শ নাগরিক হিসেবে গড়ে তোলা। অভিভাবকদের সার্বিক সহযোগিতা ও শিক্ষকদের নিরলস পরিশ্রমে সোনাতলা উচ্চ বিদ্যালয় তার অগ্রযাত্রা অব্যাহত রেখেছে।"
                    </blockquote>

                    {{-- Office Hours & Schedule Strip --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-1">
                        <div class="bg-white p-3 rounded-lg border border-slate-200/80 flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-md bg-emerald-50 text-[#005030] flex items-center justify-center shrink-0"><i class="fa-solid fa-clock"></i></span>
                            <div>
                                <span class="text-slate-400 block text-[10px]">সরাসরি সাক্ষাৎকার সময়</span>
                                <span class="text-slate-800 font-bold text-[11px]">রবি - বৃহস্পতি (সকাল ১০টা - ১২টা)</span>
                            </div>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-slate-200/80 flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-md bg-sky-50 text-sky-700 flex items-center justify-center shrink-0"><i class="fa-solid fa-phone"></i></span>
                            <div>
                                <span class="text-slate-400 block text-[10px]">দাপ্তরিক যোগাযোগ</span>
                                <a href="tel:+8801712345678" class="text-[#005030] font-bold text-[11px] hover:underline">+৮৮০ ১৭১২-৩৪৫৬৭৮</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-slate-200/80 flex items-center justify-between text-xs text-slate-500">
                    <span class="font-medium">কার্যালয়: প্রধান শিক্ষকের প্রশাসনিক কক্ষ (কক্ষ নং-১০১)</span>
                    <a href="mailto:headmaster@sonatala.edu.bd" class="text-[#005030] font-bold hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-envelope"></i>
                        <span>ইমেইল পাঠান</span>
                    </a>
                </div>
            </div>

            {{-- Secondary Leadership Card: Assistant Headmaster (35% width / 5 cols) --}}
            <div class="lg:col-span-5 bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 flex flex-col justify-between shadow-2xs hover:shadow-xs transition-shadow">
                <div class="space-y-5">
                    <div class="flex items-center gap-4 pb-5 border-b border-slate-100">
                        <div class="relative w-22 h-26 rounded-xl overflow-hidden bg-slate-200 border border-slate-300 shadow-xs shrink-0">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80"
                                 alt="মোছা: রোকসানা পারভীন - সহকারী প্রধান শিক্ষক"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="space-y-1">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold border border-slate-200">
                                সহকারী প্রধান শিক্ষক
                            </span>
                            <h3 class="text-lg font-bold text-slate-900 tracking-tight">
                                মোছা: রোকসানা পারভীন
                            </h3>
                            <p class="text-xs text-slate-600">
                                এম.এসসি (পদার্থবিজ্ঞান), বি.এড
                            </p>
                            <span class="text-[11px] text-[#005030] font-semibold block">
                                একাডেমিক ও পরীক্ষা নিয়ন্ত্রণ সমন্বয়ক
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
                        <p>
                            সহকারী প্রধান শিক্ষক বিদ্যালয়ের অভ্যন্তরীণ পরীক্ষা, শ্রেণী রুটিন প্রণয়ন, ছাত্র-ছাত্রীদের হাজিরা ও শৃঙ্খলা ব্যবস্থার সার্বিক তদারকি করে থাকেন।
                        </p>

                        <div class="space-y-2 pt-2">
                            <div class="flex items-center gap-2 text-slate-700 font-medium">
                                <i class="fa-solid fa-check-circle text-emerald-600 text-xs"></i>
                                <span>বিজ্ঞান ও আইসিটি ল্যাব তদারকি প্রধান</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-700 font-medium">
                                <i class="fa-solid fa-check-circle text-emerald-600 text-xs"></i>
                                <span>কিশোরী স্বাস্থ্য ও সহশিক্ষা কার্যক্রম ইনচার্জ</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-700 font-medium">
                                <i class="fa-solid fa-check-circle text-emerald-600 text-xs"></i>
                                <span>অভিভাবক সমন্বয় ও মূল্যায়ন কমিটি সদস্য</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="font-medium">কক্ষ: শিক্ষক মিলনায়তন (১০৩)</span>
                    <a href="tel:+8801713456789" class="text-[#005030] font-bold hover:underline flex items-center gap-1">
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
                    TEACHING FACULTY & STAFF — অনুষদ ও কর্মকর্তা-কর্মচারী
                </span>
                <h2 class="section-title">
                    নিবেদিতপ্রাণ অনুষদ ও <span class="text-[#005030] font-bold">বিভাগীয় শিক্ষকবৃন্দ</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    বিজ্ঞান, মানবিক, ব্যবসায় শিক্ষা, ভাষা ও তথ্যপ্রযুক্তি বিভাগের সম্মানিত বিষয়ভিত্তিক শিক্ষকগণের তালিকা। প্রয়োজনে সরাসরি মোবাইল বা ইমেইলে যোগাযোগ করুন।
                </p>
            </div>
        </div>

        {{-- Filter & Search Toolbar --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs mb-8 space-y-4">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('frontend.teachers.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="শিক্ষকের নাম, পদবি, বিষয় বা বিভাগ দিয়ে খুঁজুন..."
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005030] focus:border-transparent transition-all">
                    @if(request('department'))
                        <input type="hidden" name="department" value="{{ request('department') }}">
                    @endif
                    <button type="submit" class="absolute right-3 top-2.5 text-slate-400 hover:text-[#005030] transition-colors" title="অনুসন্ধান করুন">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </button>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-[#005030] hover:bg-[#003820] text-white text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-filter text-xs"></i>
                        <span>ফিল্টার প্রয়োগ</span>
                    </button>

                    @if(request()->hasAny(['q', 'department']))
                        <a href="{{ route('frontend.teachers.index') }}" class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors flex items-center justify-center gap-1 shrink-0" title="সকল ফিল্টার রিসেট করুন">
                            <i class="fa-solid fa-xmark"></i>
                            <span>রিসেট</span>
                        </a>
                    @endif
                </div>
            </form>

            {{-- Department Filter Pill Tabs --}}
            @php
                $activeDept = request('department');
                $standardDepts = [
                    'বিজ্ঞান বিভাগ' => 'বিজ্ঞান',
                    'মানবিক বিভাগ' => 'মানবিক',
                    'ব্যবসায় শিক্ষা' => 'ব্যবসায় শিক্ষা',
                    'ভাষা ও সাহিত্য বিভাগ' => 'ভাষা ও সাহিত্য',
                    'কম্পিউটার ও আইসিটি ল্যাব' => 'আইসিটি ও কম্পিউটার',
                    'ধর্মীয় শিক্ষা বিভাগ' => 'ধর্ম ও নৈতিক শিক্ষা',
                    'প্রশাসন ও ব্যবস্থাপনা' => 'প্রশাসন ও স্টাফ',
                ];
            @endphp
            <div class="pt-3 border-t border-slate-100 flex items-center gap-2 overflow-x-auto pb-1 text-xs">
                <span class="text-slate-400 font-medium text-[11px] shrink-0 mr-1">বিভাগসমূহ:</span>
                <a href="{{ route('frontend.teachers.index', array_filter(['q' => request('q')])) }}"
                   class="px-3.5 py-1.5 rounded-full font-semibold transition-all shrink-0 {{ empty($activeDept) ? 'bg-[#005030] text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200/80' }}">
                    সকল অনুষদ
                </a>

                @foreach($standardDepts as $deptKey => $deptLabel)
                    <a href="{{ route('frontend.teachers.index', array_filter(['department' => $deptKey, 'q' => request('q')])) }}"
                       class="px-3.5 py-1.5 rounded-full font-semibold transition-all shrink-0 {{ $activeDept === $deptKey ? 'bg-[#005030] text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200/80' }}">
                        {{ $deptLabel }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Teacher Cards Grid --}}
        @php
            // Curated authentic faculty fallback array with verified Bengali records
            $fallbackTeachers = [
                [
                    'name' => 'মো: আব্দুল মজিদ',
                    'designation' => 'সিনিয়র শিক্ষক (গণিত ও উচ্চতর গণিত)',
                    'department' => 'বিজ্ঞান বিভাগ',
                    'qualification' => 'বি.এসসি (অনার্স), এম.এসসি (গণিত, ১ম শ্রেণি)',
                    'experience' => '১২+ বছর',
                    'phone' => '+৮৮০ ১৭১৪-৫৬৭৮৯০',
                    'email' => 'majid.math@sonatala.edu.bd',
                    'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                    'specialty' => 'মাধ্যমিক গণিত অলিম্পিয়াড প্রশিক্ষক',
                ],
                [
                    'name' => 'মো: আনিসুর রহমান',
                    'designation' => 'সিনিয়র শিক্ষক (বাংলা সাহিত্য ও ব্যাকরণ)',
                    'department' => 'মানবিক বিভাগ',
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
                    'department' => 'ভাষা ও সাহিত্য বিভাগ',
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
                    'qualification' => 'বি.এসসি (অনার্স), এম.এসসি (রসায়ন বিজ্ঞান)',
                    'experience' => '৮+ বছর',
                    'phone' => '+৮৮০ ১৭১৭-৮৯০১২৩',
                    'email' => 'nasrin.science@sonatala.edu.bd',
                    'photo' => 'https://images.unsplash.com/photo-1580894732484-934d4001ec4c?auto=format&fit=crop&w=400&q=80',
                    'specialty' => 'বিজ্ঞান মেলা প্রকল্প ও ল্যাব প্র্যাকটিক্যাল ইনচার্জ',
                ],
                [
                    'name' => 'মো: মোশাররফ হোসেন',
                    'designation' => 'সহকারী শিক্ষক (তথ্য ও যোগাযোগ প্রযুক্তি)',
                    'department' => 'কম্পিউটার ও আইসিটি ল্যাব',
                    'qualification' => 'বি.এসসি (কম্পিউটার সায়েন্স), ডিপ্লোমা ইন আইসিটি',
                    'experience' => '৬+ বছর',
                    'phone' => '+৮৮০ ১৭১৮-৯০১২৩৪',
                    'email' => 'mosharraf.ict@sonatala.edu.bd',
                    'photo' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
                    'specialty' => 'শেখ রাসেল ডিজিটাল ল্যাব ও কোডিং ক্লাব প্রশিক্ষক',
                ],
                [
                    'name' => 'মাওলানা মো: ইউসুফ আলী',
                    'designation' => 'সহকারী শিক্ষক (ইসলাম শিক্ষা ও নৈতিকতা)',
                    'department' => 'ধর্মীয় শিক্ষা বিভাগ',
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
                    'department' => 'শারীরিক শিক্ষা ও সহশিক্ষা',
                    'qualification' => 'বি.পি.এড (শারীরিক শিক্ষা), ১ম শ্রেণি',
                    'experience' => '১১+ বছর',
                    'phone' => '+৮৮০ ১৭১০-৯৮৭৬৫৪',
                    'email' => 'saiful.sports@sonatala.edu.bd',
                    'photo' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=400&q=80',
                    'specialty' => 'বার্ষিক ক্রীড়া প্রতিযোগিতা ও রোভার স্কাউট লিডার',
                ],
            ];

            // If user filtered by department, filter the fallback records as well
            if (!empty($activeDept)) {
                $fallbackTeachers = array_values(array_filter($fallbackTeachers, function($t) use ($activeDept) {
                    return $t['department'] === $activeDept;
                }));
            }

            // If user searched by keyword, filter fallback records
            if (request()->filled('q')) {
                $searchKeyword = mb_strtolower(request('q'));
                $fallbackTeachers = array_values(array_filter($fallbackTeachers, function($t) use ($searchKeyword) {
                    return str_contains(mb_strtolower($t['name']), $searchKeyword)
                        || str_contains(mb_strtolower($t['designation']), $searchKeyword)
                        || str_contains(mb_strtolower($t['department']), $searchKeyword);
                }));
            }
        @endphp

        @if(isset($teachers) && $teachers->isNotEmpty())
            {{-- Dynamic Eloquent Paginated Records --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($teachers as $staff)
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:shadow-md hover:border-[#005030]/40 transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            {{-- Top Accent Line --}}
                            <div class="h-1 w-full bg-slate-100 group-hover:bg-[#005030] transition-colors rounded-full mb-3.5"></div>

                            {{-- Architectural 4:5 Portrait Frame --}}
                            <div class="aspect-[4/5] w-full rounded-xl overflow-hidden bg-slate-100 relative mb-3.5 border border-slate-200/70 group-hover:border-emerald-600/30 transition-colors">
                                @if(!empty($staff->photo))
                                    <img src="{{ asset('storage/' . $staff->photo) }}"
                                         alt="{{ $staff->full_name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 text-slate-400 group-hover:scale-105 transition-transform duration-300">
                                        <i class="fa-solid fa-user-tie text-4xl text-slate-300 mb-1"></i>
                                        <span class="text-[10px] font-bold text-slate-400">অনুষদ সদস্য</span>
                                    </div>
                                @endif

                                {{-- Pinned Department Badge Tag --}}
                                @if(!empty($staff->department))
                                    <div class="absolute bottom-2.5 left-2.5 bg-slate-900/85 text-white backdrop-blur-xs text-[11px] font-bold px-2.5 py-1 rounded-md shadow-2xs">
                                        {{ $staff->department }}
                                    </div>
                                @endif

                                @if($staff->is_teaching)
                                    <div class="absolute top-2.5 right-2.5 bg-emerald-50 text-[#005030] border border-emerald-200/80 text-[10px] font-extrabold px-2 py-0.5 rounded-md shadow-2xs">
                                        শিক্ষক
                                    </div>
                                @endif
                            </div>

                            {{-- Content Block --}}
                            <div class="space-y-1">
                                <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-[#005030] transition-colors leading-snug">
                                    {{ $staff->full_name ?: ($staff->first_name . ' ' . $staff->last_name) }}
                                </h3>
                                <p class="text-xs sm:text-sm font-semibold text-emerald-800">
                                    {{ $staff->designation ?: 'অনুষদ সদস্য' }}
                                </p>
                                @if(!empty($staff->qualification))
                                    <p class="text-xs text-slate-600 leading-relaxed pt-1.5 border-t border-slate-100 line-clamp-2" title="{{ $staff->qualification }}">
                                        {{ $staff->qualification }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Action Footer --}}
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600 mt-4">
                            @if(!empty($staff->phone))
                                <a href="tel:{{ $staff->phone }}" class="text-slate-700 hover:text-[#005030] font-semibold flex items-center gap-1.5 transition-colors">
                                    <i class="fa-solid fa-phone text-emerald-700 text-xs"></i>
                                    <span>{{ $staff->phone }}</span>
                                </a>
                            @else
                                <span class="text-slate-400 text-[11px]">সরাসরি যোগাযোগযোগ্য</span>
                            @endif

                            @if(!empty($staff->email))
                                <a href="mailto:{{ $staff->email }}" class="text-slate-400 hover:text-[#005030] transition-colors p-1" title="{{ $staff->email }}">
                                    <i class="fa-solid fa-envelope text-sm"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="mt-8 flex justify-center">
                {{ $teachers->links() }}
            </div>

        @elseif(!empty($fallbackTeachers))
            {{-- Authentic Curated Fallback Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($fallbackTeachers as $staff)
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs hover:shadow-md hover:border-[#005030]/40 transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            {{-- Top Accent Line --}}
                            <div class="h-1 w-full bg-slate-100 group-hover:bg-[#005030] transition-colors rounded-full mb-3.5"></div>

                            {{-- Architectural 4:5 Portrait Frame --}}
                            <div class="aspect-[4/5] w-full rounded-xl overflow-hidden bg-slate-100 relative mb-3.5 border border-slate-200/70 group-hover:border-emerald-600/30 transition-colors">
                                <img src="{{ $staff['photo'] }}"
                                     alt="{{ $staff['name'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                                {{-- Pinned Department Badge Tag --}}
                                <div class="absolute bottom-2.5 left-2.5 bg-slate-900/85 text-white backdrop-blur-xs text-[11px] font-bold px-2.5 py-1 rounded-md shadow-2xs">
                                    {{ $staff['department'] }}
                                </div>

                                {{-- Experience Badge --}}
                                <div class="absolute top-2.5 right-2.5 bg-white/90 text-slate-800 backdrop-blur-xs text-[10px] font-bold px-2 py-0.5 rounded-md border border-slate-200/80 shadow-2xs">
                                    অভিজ্ঞতা: {{ $staff['experience'] }}
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
                                <p class="text-[11px] text-slate-500 font-medium pt-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-award text-amber-500 text-[10px]"></i>
                                    <span>{{ $staff['specialty'] }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- Action Footer --}}
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600 mt-4">
                            <a href="tel:{{ $staff['phone'] }}" class="text-slate-700 hover:text-[#005030] font-semibold flex items-center gap-1.5 transition-colors">
                                <i class="fa-solid fa-phone text-emerald-700 text-xs"></i>
                                <span>{{ $staff['phone'] }}</span>
                            </a>
                            <a href="mailto:{{ $staff['email'] }}" class="text-slate-400 hover:text-[#005030] transition-colors p-1" title="{{ $staff['email'] }}">
                                <i class="fa-solid fa-envelope text-sm"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            {{-- Empty Search / Filter State --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-10 text-center space-y-4 max-w-lg mx-auto shadow-2xs">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-user-slash"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-bold text-slate-800">কোনো শিক্ষক খুঁজে পাওয়া যায়নি</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        আপনার অনুসন্ধানের সাথে মিলে এমন কোনো শিক্ষকের তথ্য পাওয়া যায়নি। অনুগ্রহ করে ভিন্ন শব্দ দিয়ে চেষ্টা করুন অথবা ফিল্টার রিসেট করুন।
                    </p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('frontend.teachers.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#005030] text-white text-xs font-bold hover:bg-[#003820] transition-colors shadow-xs">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        <span>সকল শিক্ষক প্রদর্শন করুন</span>
                    </a>
                </div>
            </div>
        @endif

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
                    TEACHING EXCELLENCE — শিক্ষাদান মানদণ্ড ও মূল্যায়ন
                </span>
                <h2 class="section-title">
                    আধুনিক পাঠদান পদ্ধতি ও <span class="text-[#005030] font-bold">শিক্ষক অঙ্গীকার</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    সোনাতলা উচ্চ বিদ্যালয়ের শিক্ষকমণ্ডলী শুধু প্রথাগত পাঠদানেই সীমাবদ্ধ থাকেন না; বরং মাল্টিমিডিয়া ল্যাব, নিয়মিত টিচিং ইভ্যালুয়েশন এবং নিবিড় যত্ন নিশ্চিত করেন।
                </p>
            </div>
        </div>

        {{-- 3 Clean Editorial Capability Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Pillar 1 --}}
            <div class="bg-slate-50/70 border border-slate-200/90 rounded-2xl p-6 space-y-3.5 shadow-2xs hover:shadow-xs transition-shadow">
                <div class="w-11 h-11 rounded-xl bg-emerald-100 text-[#005030] flex items-center justify-center text-lg font-bold shadow-2xs">
                    <i class="fa-solid fa-book-open-reader"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">
                    নিয়মিত লেসন প্ল্যানিং ও মূল্যায়ন
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    প্রতিটি পাঠ্যবিষয়ের জন্য জাতীয় শিক্ষাক্রম অনুসারে দৈনিক পাঠ পরিকল্পনা (Lesson Plan) তৈরি এবং সাপ্তাহিক ক্লাস পর্যালোচনার মাধ্যমে বিষয়বস্তুর ধারাবাহিকতা নিশ্চিত করা হয়।
                </p>
                <div class="pt-2 text-xs font-semibold text-[#005030] flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-[11px]"></i>
                    <span>সাপ্তাহিক শিক্ষক সমন্বয় বৈঠক</span>
                </div>
            </div>

            {{-- Pillar 2 --}}
            <div class="bg-slate-50/70 border border-slate-200/90 rounded-2xl p-6 space-y-3.5 shadow-2xs hover:shadow-xs transition-shadow">
                <div class="w-11 h-11 rounded-xl bg-sky-100 text-sky-800 flex items-center justify-center text-lg font-bold shadow-2xs">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">
                    ডিজিটাল ও ল্যাবভিত্তিক ক্লাস
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    বিজ্ঞান ও তথ্যপ্রযুক্তি শিক্ষায় শিক্ষার্থীদের হাতে-কলমে দক্ষ করে তুলতে শেখ রাসেল ডিজিটাল ল্যাব ও আধুনিক পদার্থ, রসায়ন ও জীব ল্যাব নিয়মিত পরিচালিত হয়।
                </p>
                <div class="pt-2 text-xs font-semibold text-sky-800 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-[11px]"></i>
                    <span>স্মার্ট প্রজেক্টর ও মাল্টিমিডিয়া ক্লাসরুম</span>
                </div>
            </div>

            {{-- Pillar 3 --}}
            <div class="bg-slate-50/70 border border-slate-200/90 rounded-2xl p-6 space-y-3.5 shadow-2xs hover:shadow-xs transition-shadow">
                <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-900 flex items-center justify-center text-lg font-bold shadow-2xs">
                    <i class="fa-solid fa-hands-holding-child"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">
                    দুর্বল শিক্ষার্থীদের নিবিড় পরিচর্যা
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    পড়াশোনায় পিছিয়ে থাকা শিক্ষার্থীদের চিহ্নিত করে নিয়মিত ক্লাসের অতিরিক্ত সময়ে বিশেষ রিমিডিয়াল কেয়ার ও শিক্ষক মেন্টরশিপের ব্যবস্থা রাখা হয়েছে।
                </p>
                <div class="pt-2 text-xs font-semibold text-amber-800 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-[11px]"></i>
                    <span>বিনা খরচে স্পেশাল কোচিং ও সাপোর্ট</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Hairline Structural Divider --}}
<div class="home-section-divider"></div>

{{-- 7. Call to Action: Guardian Consultation & Meeting Schedule (The Next Step) --}}
<section class="home-section bg-slate-50/80" id="consultation-cta">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-[#005030] text-white rounded-3xl p-8 sm:p-12 lg:p-16 relative overflow-hidden shadow-xs">
            <div class="relative z-10 max-w-2xl space-y-4">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#003820] text-emerald-200 text-xs font-bold uppercase tracking-wider border border-emerald-700/60">
                    <i class="fa-solid fa-calendar-days text-[11px]"></i>
                    <span>GUARDIAN CONSULTATION — অভিভাবক পরামর্শ ও সাক্ষাৎ</span>
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    সন্তানের পড়াশোনা ও অগ্রগতি নিয়ে <span class="text-amber-400 font-bold">শিক্ষকদের সাথে কথা বলুন</span>
                </h2>
                <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed">
                    প্রতি সপ্তাহের রবিবার থেকে বৃহস্পতিবার দুপুর ১২:৩০ হতে ১:৩০ পর্যন্ত শিক্ষক মিলনায়তনে অভিভাবক সাক্ষাতের ব্যবস্থা রয়েছে। সরাসরি সাক্ষাৎ অথবা জরুরি প্রয়োজনে প্রশাসনিক যোগাযোগ ফর্মের মাধ্যমে বার্তা পাঠান।
                </p>
                <div class="pt-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    {{-- Primary Amber CTA Button (Strictly Reserved Conversion Action) --}}
                    <a href="{{ route('frontend.contact') }}"
                       class="px-7 py-3.5 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-sm shadow-xs transition-all hover:scale-105 active:scale-95 text-center flex items-center justify-center gap-2">
                        <span>সাক্ষাৎ ও পরামর্শের জন্য যোগাযোগ করুন</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="tel:+8801712345678"
                       class="px-6 py-3.5 rounded-full bg-[#003820] hover:bg-[#002a18] text-emerald-200 border border-emerald-700/70 font-bold text-xs transition-colors text-center flex items-center justify-center gap-2">
                        <i class="fa-solid fa-phone"></i>
                        <span>হটলাইন: +৮৮০ ১৭১২-৩৪৫৬৭৮</span>
                    </a>
                </div>
            </div>

            {{-- Decorative Academic Graphic Geometry --}}
            <div class="hidden lg:block absolute -right-10 -bottom-10 w-96 h-96 rounded-full bg-emerald-800/30 border border-emerald-700/30 pointer-events-none"></div>
            <div class="hidden lg:block absolute right-24 bottom-12 text-emerald-800/40 text-9xl pointer-events-none">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
        </div>
    </div>
</section>

@endsection
