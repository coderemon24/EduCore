@extends('layouts.app')

@section('title', 'সম্মানিত শিক্ষকমণ্ডলী | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের নিবেদিতপ্রাণ ও প্রশিক্ষণপ্রাপ্ত সম্মানিত শিক্ষকমণ্ডলী—যাঁদের আন্তরিক পাঠদানে গড়ে উঠছে প্রতিটি সন্তান।')

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
            <span class="text-gray-500">পরিচিতি</span>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">শিক্ষকমণ্ডলী</span>
        </nav>
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }} • সাঁথিয়া, পাবনা</span>
    </div>
</div>

{{-- Top Hero Banner: Matches Reference Image Clean Green Header --}}
<div class="bg-[#005030] text-white py-8 sm:py-10 border-b border-emerald-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        {{-- Pill Badge --}}
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-[#005030] text-xs font-bold shadow-xs">
            <i class="fa-solid fa-chalkboard-user text-[#005030]"></i>
            <span>শিক্ষকমণ্ডলী</span>
        </div>

        {{-- Page Heading --}}
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white mt-3 mb-2 tracking-tight">
            সম্মানিত শিক্ষক ও কর্মচারীবৃন্দ
        </h1>

        {{-- Sub-narrative context --}}
        <p class="text-xs sm:text-sm text-emerald-100/90 font-medium">
            সোনাতলা উচ্চ বিদ্যালয়ের নিবেদিতপ্রাণ ও প্রশিক্ষণপ্রাপ্ত অভিজ্ঞ অনুষদ সদস্যবৃন্দ
        </p>
    </div>
</div>

{{-- Main Content Canvas --}}
<section class="py-8 sm:py-12 bg-slate-50/60 min-h-[500px]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-8">

        {{-- Institutional Leadership Showcase --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="leadership">
            {{-- Headmaster Card --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs flex flex-col justify-between space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-20 h-24 sm:w-24 sm:h-28 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=400&q=80"
                             alt="প্রধান শিক্ষক"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-1 min-w-0">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#005030] text-[11px] font-bold border border-emerald-200">
                            প্রধান শিক্ষক
                        </span>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 leading-snug">
                            মো: আলতাফ হোসেন
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">
                            এম.এ (ইংরেজি), বি.এড (১ম শ্রেণি)
                        </p>
                        <p class="text-xs text-slate-600 line-clamp-2 pt-1 leading-relaxed">
                            অর্ধশতকের শিক্ষাদান ঐতিহ্যে আমাদের অঙ্গীকার—প্রতিটি সন্তানকে মেধা, সততা ও মূল্যবোধসম্পন্ন মানুষ হিসেবে গড়ে তোলা।
                        </p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="font-medium">কার্যালয়: কক্ষ ১০১</span>
                    <a href="tel:+8801712345678" class="text-[#005030] font-bold hover:underline flex items-center gap-1 font-mono">
                        <i class="fa-solid fa-phone text-[10px]"></i>
                        <span>+৮৮০ ১৭১২-৩৪৫৬৭৮</span>
                    </a>
                </div>
            </div>

            {{-- Assistant Headmaster Card --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs flex flex-col justify-between space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-20 h-24 sm:w-24 sm:h-28 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80"
                             alt="সহকারী প্রধান শিক্ষক"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-1 min-w-0">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-bold border border-slate-200">
                            সহকারী প্রধান শিক্ষক
                        </span>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 leading-snug">
                            মোছা: রোকসানা পারভীন
                        </h3>
                        <p class="text-xs text-slate-500 font-medium">
                            এম.এসসি (পদার্থবিজ্ঞান), বি.এড
                        </p>
                        <p class="text-xs text-slate-600 line-clamp-2 pt-1 leading-relaxed">
                            শ্রেণিকক্ষে পাঠদানের গুণগত মান তদারকি এবং বিজ্ঞান ও তথ্যপ্রযুক্তি ল্যাবের সার্বিক ব্যবহারিক শিক্ষা পরিচালনা।
                        </p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="font-medium">শিক্ষক মিলনায়তন: কক্ষ ১০৩</span>
                    <a href="tel:+8801713456789" class="text-[#005030] font-bold hover:underline flex items-center gap-1 font-mono">
                        <i class="fa-solid fa-phone text-[10px]"></i>
                        <span>+৮৮০ ১৭১৩-৪৫৬৭৮৯</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Directory Filters & Search Bar --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs space-y-4" id="faculty-directory">
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <input type="text"
                           id="teacher-search-input"
                           value="{{ request('q') }}"
                           placeholder="শিক্ষকের নাম, বিষয়, পদবি বা বিভাগ লিখে খুঁজুন..."
                           class="w-full px-3.5 py-2.5 pl-9 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:border-[#005030] focus:bg-white transition-colors">
                    <span class="absolute left-3 top-3 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <button type="button"
                            id="clear-search-btn"
                            class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 {{ request('q') ? 'block' : 'hidden' }}">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 justify-between sm:justify-start text-xs">
                    <span class="text-slate-500 font-medium">
                        প্রদর্শিত: <strong id="visible-teacher-count" class="text-[#005030] font-bold">--</strong> জন
                    </span>
                    <button type="button"
                            id="reset-filters-btn"
                            class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition-colors flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-rotate-left text-[10px]"></i>
                        <span>রিসেট</span>
                    </button>
                </div>
            </div>

            {{-- Department filter pills --}}
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

                $defaultTeacherPhotos = [
                    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1580894732484-934d4001ec4c?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=400&q=80',
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
                        ];
                    }
                } elseif (!request()->filled('q') && !request()->filled('department')) {
                    $displayStaff = $fallbackTeachers;
                }
            @endphp

            <div class="pt-3 border-t border-slate-100 flex items-center gap-2 overflow-x-auto pb-1 text-xs">
                @foreach($standardDepts as $slug => $label)
                    <button type="button"
                            data-department="{{ $slug }}"
                            class="dept-filter-tab px-3.5 py-1.5 rounded-full font-semibold transition-all shrink-0 cursor-pointer {{ $loop->first ? 'bg-[#005030] text-white shadow-xs active' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Teachers Grid --}}
        <div id="teachers-grid-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($displayStaff as $staff)
                <div class="teacher-card-item bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs flex flex-col justify-between hover:border-slate-300 transition-colors"
                     data-department="{{ $staff['dept_slug'] }}"
                     data-department-name="{{ $staff['department'] }}"
                     data-name="{{ $staff['name'] }}"
                     data-designation="{{ $staff['designation'] }}"
                     data-qualification="{{ $staff['qualification'] }}">
                    <div class="space-y-3">
                        <div class="h-48 w-full bg-slate-100 rounded-xl overflow-hidden relative shadow-2xs">
                            <img src="{{ $staff['photo'] }}"
                                 alt="{{ $staff['name'] }}"
                                 loading="lazy"
                                 class="w-full h-full object-cover">
                            <div class="absolute bottom-2 left-2 bg-slate-900/80 text-white text-[10px] font-bold px-2 py-0.5 rounded-md">
                                {{ $staff['department'] }}
                            </div>
                        </div>

                        <div class="space-y-1">
                            <h3 class="teacher-name text-base font-bold text-slate-900 leading-snug">
                                {{ $staff['name'] }}
                            </h3>
                            <p class="text-xs font-semibold text-[#005030]">
                                {{ $staff['designation'] }}
                            </p>
                            <p class="text-[11px] text-slate-500 line-clamp-2 pt-1 border-t border-slate-100" title="{{ $staff['qualification'] }}">
                                {{ $staff['qualification'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Contact row --}}
                    <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between gap-2 text-xs">
                        <a href="tel:{{ $staff['phone'] }}"
                           class="flex-1 inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-slate-50 text-slate-700 hover:bg-slate-100 font-mono text-[11px] truncate">
                            <i class="fa-solid fa-phone text-[#005030] text-[10px]"></i>
                            <span class="truncate">{{ $staff['phone'] }}</span>
                        </a>
                        <a href="mailto:{{ $staff['email'] }}"
                           class="p-1.5 rounded-lg bg-slate-50 text-slate-700 hover:bg-slate-100 shrink-0"
                           title="{{ $staff['email'] }}">
                            <i class="fa-solid fa-envelope text-[#005030] text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Empty Search State --}}
        <div id="no-teachers-found" class="hidden bg-white border border-slate-200/90 rounded-2xl p-10 text-center space-y-3 max-w-md mx-auto shadow-2xs">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-user-slash"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">কোনো শিক্ষক খুঁজে পাওয়া যায়নি</h3>
            <p class="text-xs text-slate-500">
                আপনার অনুসন্ধানের সাথে মিল রয়েছে এমন কোনো শিক্ষক তালিকায় পাওয়া যায়নি।
            </p>
            <div class="pt-2">
                <button type="button"
                        onclick="document.getElementById('reset-filters-btn').click()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#005030] text-white text-xs font-bold hover:bg-[#003820] transition-colors shadow-xs cursor-pointer">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                    <span>সকল শিক্ষক প্রদর্শন করুন</span>
                </button>
            </div>
        </div>

        {{-- Pedagogical Values (3 Cards) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5" id="pedagogy-standards">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center text-base shadow-2xs">
                    <i class="fa-solid fa-book-open-reader"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900">সুনির্দিষ্ট পাঠ পরিকল্পনা</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    প্রতিটি ক্লাসের আগেই শিক্ষকগণ জাতীয় পাঠ্যক্রম অনুযায়ী বিস্তারিত লেসন প্ল্যান প্রস্তুত করেন, যা শতভাগ শিখনফল অর্জনে ভূমিকা রাখে।
                </p>
            </div>

            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center text-base shadow-2xs">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900">ব্যবহারিক ল্যাব ও প্রজেক্টর</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    তত্ত্বীয় আলোচনার পাশাপাশি শেখ রাসেল ডিজিটাল ল্যাব ও সুসজ্জিত বিজ্ঞানাগারে শিক্ষার্থীদের নিয়মিত ব্যবহারিক জ্ঞান প্রদান করা হয়।
                </p>
            </div>

            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center text-base shadow-2xs">
                    <i class="fa-solid fa-hands-holding-child"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900">বিশেষ মেন্টরিং ও যত্ন</h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    যেকোনো বিষয়ে পিছিয়ে থাকা শিক্ষার্থীদের চিহ্নিত করে ক্লাসের অতিরিক্ত সময়ে যত্নশীল শিক্ষক সহায়তার মাধ্যমে এগিয়ে নেওয়া হয়।
                </p>
            </div>
        </div>

        {{-- Consultation / Visit CTA --}}
        <div class="bg-[#005030] text-white rounded-2xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-center justify-between gap-6" id="consultation-cta">
            <div class="space-y-1 text-center md:text-left">
                <h3 class="text-lg sm:text-xl font-extrabold text-white">শিক্ষকদের সাথে সরাসরি পরামর্শ</h3>
                <p class="text-xs sm:text-sm text-emerald-100/90">
                    সন্তানের অগ্রগতি ও পড়াশোনা সংক্রান্ত যেকোনো প্রয়োজনে সরাসরি শিক্ষকদের সাথে সাক্ষাৎ করুন।
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('frontend.contact') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs shadow-xs transition-colors">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>সাক্ষাতের সময় নিন</span>
                </a>
                <a href="tel:+8801712345678"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition-colors">
                    <i class="fa-solid fa-phone"></i>
                    <span>+৮৮০ ১৭১২-৩৪৫৬৭৮</span>
                </a>
            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
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

            const matchesDept = (currentDept === 'all') ||
                                (cardDeptSlug === currentDept) ||
                                (currentDept === 'science' && cardDeptName.includes('বিজ্ঞান')) ||
                                (currentDept === 'humanities' && cardDeptName.includes('মানবিক')) ||
                                (currentDept === 'commerce' && cardDeptName.includes('ব্যবসা')) ||
                                (currentDept === 'language' && (cardDeptName.includes('ভাষা') || cardDeptName.includes('english') || cardDeptName.includes('বাংলা'))) ||
                                (currentDept === 'ict' && (cardDeptName.includes('আইসিটি') || cardDeptName.includes('কম্পিউটার'))) ||
                                (currentDept === 'religious' && cardDeptName.includes('ধর্ম')) ||
                                (currentDept === 'admin' && (cardDeptName.includes('প্রশাসন') || cardDeptName.includes('স্টাফ') || cardDeptName.includes('শারীরিক')));

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
            if (visibleCount === 0) {
                noResultsState.classList.remove('hidden');
            } else {
                noResultsState.classList.add('hidden');
            }
        }
    }

    // Bind search
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            currentSearch = this.value.trim().toLowerCase();
            if (clearSearchBtn) {
                if (currentSearch.length > 0) {
                    clearSearchBtn.classList.remove('hidden');
                } else {
                    clearSearchBtn.classList.add('hidden');
                }
            }
            filterTeachers();
        });
    }

    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
                currentSearch = '';
                this.classList.add('hidden');
                filterTeachers();
            }
        });
    }

    // Bind department tabs
    deptTabs.forEach(tab => {
        tab.addEventListener('click', function () {
            deptTabs.forEach(t => {
                t.classList.remove('bg-[#005030]', 'text-white', 'shadow-xs', 'active');
                t.classList.add('bg-white', 'text-slate-700', 'hover:bg-slate-100', 'border', 'border-slate-200');
            });
            this.classList.remove('bg-white', 'text-slate-700', 'hover:bg-slate-100', 'border', 'border-slate-200');
            this.classList.add('bg-[#005030]', 'text-white', 'shadow-xs', 'active');

            currentDept = this.getAttribute('data-department') || 'all';
            filterTeachers();
        });
    });

    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
                currentSearch = '';
            }
            if (clearSearchBtn) {
                clearSearchBtn.classList.add('hidden');
            }
            currentDept = 'all';
            if (deptTabs.length > 0) {
                deptTabs[0].click();
            } else {
                filterTeachers();
            }
        });
    }

    filterTeachers();
});
</script>
@endpush
