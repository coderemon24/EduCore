@extends('layouts.app')

@section('title', 'সোনাতলা উচ্চ বিদ্যালয় | Sonatala High School')

@section('content')
    @php
        $bnDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        $enDigits = ['0','1','2','3','4','5','6','7','8','9'];
        $studentCount = (int)($stats['total_students'] ?? 0);
        $studentCountDisplay = ($studentCount >= 100)
            ? str_replace($enDigits, $bnDigits, number_format($studentCount)) . '+'
            : '১,২৫০+';
        $teacherCount = (int)($stats['total_teachers'] ?? 0);
        $teacherCountDisplay = ($teacherCount >= 15)
            ? str_replace($enDigits, $bnDigits, (string)$teacherCount)
            : '৪২';
    @endphp

    {{-- Hero slider --}}
    @include('frontend.home.partials.fullscreen-slider')

    {{-- High-Utility Quick Access Bar (Clean, Rapid Action Grid) --}}
    <section class="qualifying-strip-bar py-3.5 sm:py-4 px-4 sm:px-6 lg:px-8 relative z-20" aria-label="দ্রুত সেবা ও লিংক">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <a href="{{ route('frontend.contact') }}#enroll" class="quick-nav-pill group">
                    <span class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center shrink-0 text-sm group-hover:bg-[#005030] group-hover:text-white transition-all shadow-2xs">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </span>
                    <div class="min-w-0">
                        <strong class="text-slate-900 block font-bold text-xs sm:text-sm group-hover:text-[#005030] transition-colors truncate">ভর্তি তথ্য ও আবেদন</strong>
                        <span class="text-[11px] text-slate-500 block truncate">২০২৬ শিক্ষাবর্ষের ভর্তি নির্দেশিকা</span>
                    </div>
                </a>

                <a href="{{ route('frontend.academic.routine') }}" class="quick-nav-pill group">
                    <span class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center shrink-0 text-sm group-hover:bg-[#005030] group-hover:text-white transition-all shadow-2xs">
                        <i class="fa-solid fa-calendar-days"></i>
                    </span>
                    <div class="min-w-0">
                        <strong class="text-slate-900 block font-bold text-xs sm:text-sm group-hover:text-[#005030] transition-colors truncate">ক্লাস ও পরীক্ষার রুটিন</strong>
                        <span class="text-[11px] text-slate-500 block truncate">চলমান একাডেমিক সময়সূচি</span>
                    </div>
                </a>

                <a href="#notices" class="quick-nav-pill group">
                    <span class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center shrink-0 text-sm group-hover:bg-[#005030] group-hover:text-white transition-all shadow-2xs">
                        <i class="fa-solid fa-bullhorn"></i>
                    </span>
                    <div class="min-w-0">
                        <strong class="text-slate-900 block font-bold text-xs sm:text-sm group-hover:text-[#005030] transition-colors truncate">জরুরি নোটিশ বোর্ড</strong>
                        <span class="text-[11px] text-slate-500 block truncate">সর্বশেষ বিজ্ঞপ্তি ও সার্কুলার</span>
                    </div>
                </a>

                <a href="{{ route('frontend.teachers.index') }}" class="quick-nav-pill group">
                    <span class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center shrink-0 text-sm group-hover:bg-[#005030] group-hover:text-white transition-all shadow-2xs">
                        <i class="fa-solid fa-user-tie"></i>
                    </span>
                    <div class="min-w-0">
                        <strong class="text-slate-900 block font-bold text-xs sm:text-sm group-hover:text-[#005030] transition-colors truncate">শিক্ষকমণ্ডলী ও স্টাফ</strong>
                        <span class="text-[11px] text-slate-500 block truncate">অভিজ্ঞ শিক্ষকমণ্ডলীর তালিকা</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- Academic capabilities (With authentic lab imagery) --}}
    <section class="home-section bg-white py-12 sm:py-16" id="capabilities">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="section-header-block items-start">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    একাডেমিক উৎকর্ষ ও ল্যাব সুবিধা
                </span>
                <h2 class="section-title">
                    হাতেকলমে শিক্ষা, <span class="headline-signature-accent">ব্যবহারিক বিজ্ঞান</span> ও তথ্যপ্রযুক্তি
                </h2>
            </div>

            <div class="capabilities-grid">
                {{-- Science & lab hero --}}
                <div class="capability-card-hero group">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center font-bold shadow-2xs group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-[#005030] text-xs font-bold border border-emerald-200">
                                <i class="fa-solid fa-flask-vial"></i>
                                <span>প্রধান সক্ষমতা</span>
                            </span>
                        </div>

                        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 mb-2 leading-snug">
                            হাতেকলমে ব্যবহারিক বিজ্ঞান ও আধুনিক ল্যাব পরীক্ষণ
                        </h3>
                        <p class="text-sm text-slate-600 leading-relaxed mb-5">
                            পদার্থ, রসায়ন ও জীববিজ্ঞানের পৃথক শীতাতপনিয়ন্ত্রিত ল্যাবে নিয়মিত বাস্তব পরীক্ষণ ও উদ্ভাবন উৎসব।
                        </p>

                        <div class="capability-features-grid pt-4 border-t border-slate-200/80 mb-5">
                            <div class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-800">
                                <span class="w-5 h-5 rounded-full bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center shrink-0 text-[11px] font-bold"><i class="fa-solid fa-check"></i></span>
                                <span>পদার্থ, রসায়ন ও জীববিজ্ঞানের পৃথক ল্যাব</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-800">
                                <span class="w-5 h-5 rounded-full bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center shrink-0 text-[11px] font-bold"><i class="fa-solid fa-check"></i></span>
                                <span>আধুনিক মাইক্রোস্কোপ ও রিএজেন্ট সুবিধা</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-800">
                                <span class="w-5 h-5 rounded-full bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center shrink-0 text-[11px] font-bold"><i class="fa-solid fa-check"></i></span>
                                <span>অভিজ্ঞ ল্যাব সহকারী ও শিক্ষক তত্ত্বাবধান</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-slate-800">
                                <span class="w-5 h-5 rounded-full bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center shrink-0 text-[11px] font-bold"><i class="fa-solid fa-check"></i></span>
                                <span>বার্ষিক বিজ্ঞান মেলা ও প্রজেক্ট প্রদর্শনী</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200/70 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-medium">৬ষ্ঠ-১০ম শ্রেণির ব্যবহারিক সেশন</span>
                        <a href="{{ route('frontend.about') }}" class="text-xs sm:text-sm font-bold text-[#005030] hover:underline inline-flex items-center gap-1.5 group/link">
                            <span>ল্যাব পরিকাঠামো</span>
                            <i class="fa-solid fa-arrow-right-long text-xs transition-transform group-hover/link:translate-x-1"></i>
                        </a>
                    </div>
                </div>

                <div class="capabilities-secondary-stack">
                    {{-- ICT & digital --}}
                    <div class="capability-card-secondary group">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center font-bold group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-400">আইসিটি প্রশিক্ষণ</span>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-1 leading-snug">
                                ডিজিটাল আইসিটি ও কম্পিউটার শিক্ষা
                            </h4>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-3">
                                উচ্চগতির ইন্টারনেট, আধুনিক পিসি ও কোডিং পরিচিতিতে শিক্ষার্থীদের বাস্তবমুখী ডিজিটাল প্রস্তুতি।
                            </p>
                        </div>
                        <a href="{{ route('frontend.about') }}#infrastructure" class="pt-2.5 border-t border-slate-200/60 text-xs font-semibold text-[#005030] flex items-center justify-between group-hover:text-emerald-800">
                            <span>কম্পিউটার ল্যাব সুবিধা</span>
                            <i class="fa-solid fa-arrow-right-long text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>

                    {{-- Co-curricular & sports --}}
                    <div class="capability-card-secondary group">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center font-bold group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-400">সহ-শিক্ষা ও ক্রীড়া</span>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-1 leading-snug">
                                স্কাউটিং, বিতর্ক ও ক্রীড়াঙ্গন
                            </h4>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-3">
                                নিয়মিত খেলাধুলা, বিতর্ক প্রতিযোগিতা ও সক্রিয় স্কাউট ট্রুপে দলগত শৃঙ্খলা ও নেতৃত্বের বিকাশ।
                            </p>
                        </div>
                        <a href="{{ route('frontend.about') }}" class="pt-2.5 border-t border-slate-200/60 text-xs font-semibold text-[#005030] flex items-center justify-between group-hover:text-emerald-800">
                            <span>সহ-শিক্ষা ও ক্রীড়া কার্যক্রম</span>
                            <i class="fa-solid fa-arrow-right-long text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Institutional advantage & Metrics --}}
    <section class="home-section bg-slate-50/70 py-12 sm:py-16" id="advantage">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="section-header-block items-start">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    আমাদের অনন্যতা ও ঐতিহ্য
                </span>
                <h2 class="section-title">
                    সোনাতলা বিদ্যাপীঠের <span class="headline-signature-accent">শিক্ষাদর্শন ও অনন্য বৈশিষ্ট্য</span>
                </h2>
            </div>

            <div class="differentiators-grid mb-10">
                <div class="differentiator-card bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 flex gap-4 shadow-2xs group">
                    <div class="w-9 h-9 rounded-full bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center shrink-0 font-extrabold text-xs group-hover:scale-105 transition-transform duration-300">
                        ০১
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">
                            ৫৭ বছরের ধারাবাহিক সাফল্য ও ঐতিহ্য
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            বোর্ড পরীক্ষায় ধারাবাহিক ৯৮.৫% পাস ও শীর্ষ প্রতিষ্ঠানে প্রাক্তন শিক্ষার্থীদের দায়িত্বশীল অবস্থান।
                        </p>
                    </div>
                </div>

                <div class="differentiator-card bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 flex gap-4 shadow-2xs group">
                    <div class="w-9 h-9 rounded-full bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center shrink-0 font-extrabold text-xs group-hover:scale-105 transition-transform duration-300">
                        ০২
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">
                            প্রশিক্ষিত ও নিবেদিত শিক্ষক পরিষদ
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            বিএড ও মাস্টার্স ডিগ্রিধারী অভিজ্ঞ শিক্ষকদের শ্রেণিকক্ষেই শতভাগ ধারণা পরিষ্কার করার নিশ্চয়তা।
                        </p>
                    </div>
                </div>

                <div class="differentiator-card bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 flex gap-4 shadow-2xs group">
                    <div class="w-9 h-9 rounded-full bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center shrink-0 font-extrabold text-xs group-hover:scale-105 transition-transform duration-300">
                        ০৩
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">
                            ১০০% নিরাপদ ও রাজনীতিমুক্ত ক্যাম্পাস
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            স্নেহশীল সুশৃঙ্খল পরিবেশ, নিয়মিত অভিভাবক সংলাপ ও সার্বক্ষণিক শিক্ষার্থী সুরক্ষা।
                        </p>
                    </div>
                </div>

                <div class="differentiator-card bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 flex gap-4 shadow-2xs group">
                    <div class="w-9 h-9 rounded-full bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center shrink-0 font-extrabold text-xs group-hover:scale-105 transition-transform duration-300">
                        ০৪
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">
                            স্মার্ট ক্যাম্পাস ও ডিজিটাল তথ্যসেবা
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            অনলাইন নোটিশ বোর্ড, পরীক্ষার সময়সূচি ও জরুরি আপডেট সরাসরি অভিভাবকদের স্মার্টফোনে।
                        </p>
                    </div>
                </div>
            </div>

            {{-- Key metrics (Fixed '০ জন' & preview bugs) --}}
            <div class="metrics-counter-box bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-xs">
                <div class="bg-slate-100/90 border-b border-slate-200/80 px-5 sm:px-6 py-3 flex items-center justify-between text-xs font-bold text-slate-700">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-[#005030]"></i>
                        <span>এক নজরে আমাদের প্রাতিষ্ঠানিক অর্জন</span>
                    </span>
                    <span class="text-emerald-800 font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-award text-[#005030]"></i>
                        <span>স্বীকৃত মানদণ্ড</span>
                    </span>
                </div>

                <div class="p-5 sm:p-7 grid grid-cols-1 md:grid-cols-3 gap-5 text-center border-b border-slate-100">
                    <div class="metric-pill p-4 sm:p-5 rounded-xl bg-slate-50/80 border border-slate-200/70 transition-all hover:bg-emerald-50/50 hover:border-emerald-200">
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">ধারাবাহিক এসএসসি সাফল্য</div>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#005030] mt-1.5">৯৮.৫%</div>
                        <div class="text-xs text-slate-500 mt-1">বিগত ৫ বছরের ঈর্ষণীয় ফলাফল</div>
                    </div>
                    <div class="metric-pill p-4 sm:p-5 rounded-xl bg-slate-50/80 border border-slate-200/70 transition-all hover:bg-emerald-50/50 hover:border-emerald-200">
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">বর্তমান শিক্ষার্থী পরিবার</div>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 mt-1.5">{{ $studentCountDisplay }} জন</div>
                        <div class="text-xs text-slate-500 mt-1">সুশৃঙ্খল ও প্রাণবন্ত ক্যাম্পাস</div>
                    </div>
                    <div class="metric-pill p-4 sm:p-5 rounded-xl bg-slate-50/80 border border-slate-200/70 transition-all hover:bg-emerald-50/50 hover:border-emerald-200">
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">গৌরবময় ঐতিহ্য ও ইতিহাস</div>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 mt-1.5">৫৭ বছর</div>
                        <div class="text-xs text-slate-500 mt-1">প্রতিষ্ঠা সন: ১৯৬৮ খ্রি.</div>
                    </div>
                </div>

                <div class="px-5 sm:px-6 py-3.5 bg-slate-50/80 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-600">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5"><i class="fa-solid fa-chalkboard-user text-[#005030]"></i> শিক্ষক ও স্টাফ:</span>
                        <span>{{ $teacherCountDisplay }} জন</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5"><i class="fa-solid fa-desktop text-[#005030]"></i> ক্লাসরুম:</span>
                        <span>১৮টি মাল্টিমিডিয়া রুম</span>
                    </div>
                    <div class="flex items-center gap-2 font-mono">
                        <span class="font-bold text-slate-800 font-sans flex items-center gap-1.5"><i class="fa-solid fa-building-columns text-[#005030]"></i> EIIN:</span>
                        <span>১২৫৬৯৩</span>
                    </div>
                    <div class="flex items-center gap-2 font-mono">
                        <span class="font-bold text-slate-800 font-sans flex items-center gap-1.5"><i class="fa-solid fa-file-shield text-[#005030]"></i> সরকারি কোড:</span>
                        <span>৮১০৭</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Notices & updates --}}
    <section class="home-section bg-white py-12 sm:py-16" id="notices">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="section-header-block items-start">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    সর্বশেষ বিজ্ঞপ্তি ও সার্কুলার
                </span>
                <h2 class="section-title">
                    জরুরি নোটিশ বোর্ড ও <span class="headline-signature-accent">প্রাতিষ্ঠানিক পরিক্রমা</span>
                </h2>
            </div>

            <div class="dispatches-grid">
                {{-- Notice board --}}
                <div class="w-full h-full">
                    @include('frontend.home.partials.notice-board')
                </div>

                {{-- Stories & quick links --}}
                <div class="space-y-5">
                    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-xs">
                        <div class="bg-[#005030] text-white px-5 py-3 flex items-center justify-between">
                            <h3 class="font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-bullhorn text-emerald-300"></i>
                                <span>সাফল্য ও ক্যাম্পাস সংবাদ</span>
                            </h3>
                            <span class="text-[11px] text-emerald-200">Campus Stories</span>
                        </div>
                        <div class="divide-y divide-slate-100">
                            <article class="p-3.5 hover:bg-slate-50 transition-colors flex gap-3.5 items-center">
                                <div class="w-18 h-14 sm:w-20 sm:h-16 shrink-0 rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                                    <img src="{{ asset('assets/images/news-1.jpg') }}" alt="বার্ষিক ক্রীড়া" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug hover:text-[#005030] line-clamp-2">
                                        <a href="{{ route('frontend.notices.index') }}">
                                            দলগত সম্প্রীতি ও সুস্থ জীবনের প্রত্যয়ে বার্ষিক ক্রীড়া প্রতিযোগিতা সম্পন্ন
                                        </a>
                                    </h4>
                                    <span class="text-[11px] text-slate-500 font-medium mt-0.5 block">১৯ অক্টোবর, ২০২৬</span>
                                </div>
                            </article>

                            <article class="p-3.5 hover:bg-slate-50 transition-colors flex gap-3.5 items-center">
                                <div class="w-18 h-14 sm:w-20 sm:h-16 shrink-0 rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                                    <img src="{{ asset('assets/images/news-2.jpg') }}" alt="বিজ্ঞান মেলা" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug hover:text-[#005030] line-clamp-2">
                                        <a href="{{ route('frontend.notices.index') }}">
                                            বিজ্ঞানের ব্যবহারিক উদ্ভাবনে মুখর ক্যাম্পাস: ক্ষুদে বিজ্ঞানীদের প্রজেক্ট প্রদর্শনী
                                        </a>
                                    </h4>
                                    <span class="text-[11px] text-slate-500 font-medium mt-0.5 block">১৮ অক্টোবর, ২০২৬</span>
                                </div>
                            </article>

                            <article class="p-3.5 hover:bg-slate-50 transition-colors flex gap-3.5 items-center">
                                <div class="w-18 h-14 sm:w-20 sm:h-16 shrink-0 rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                                    <img src="{{ asset('assets/images/news-3.jpg') }}" alt="অভিভাবক সমাবেশ" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug hover:text-[#005030] line-clamp-2">
                                        <a href="{{ route('frontend.notices.index') }}">
                                            সন্তানের মানসিক সুস্থতা ও নতুন মূল্যায়ন নিয়ে শিক্ষক-অভিভাবক আন্তরিক সংলাপ
                                        </a>
                                    </h4>
                                    <span class="text-[11px] text-slate-500 font-medium mt-0.5 block">১৫ অক্টোবর, ২০২৬</span>
                                </div>
                            </article>
                        </div>
                        <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
                            <a href="{{ route('frontend.notices.index') }}" class="text-xs font-bold text-[#005030] hover:underline inline-flex items-center gap-1.5 group">
                                <span>আরও সংবাদ দেখুন</span>
                                <i class="fa-solid fa-arrow-right-long text-xs transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Quick services --}}
                    <div class="bg-slate-50 border border-slate-200/90 rounded-2xl p-4 space-y-2.5">
                        <div class="text-xs font-bold text-slate-900 border-b border-slate-200/70 pb-2 uppercase tracking-wide">
                            অভিভাবক ও শিক্ষার্থী সেবা
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <a href="{{ route('frontend.academic.routine') }}"
                               class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-white hover:bg-emerald-50 hover:text-[#005030] text-slate-800 text-xs font-semibold border border-slate-200 transition-all hover:-translate-y-0.5 shadow-2xs">
                                <i class="fa-solid fa-calendar-days text-[#005030] text-xs"></i>
                                <span>ক্লাস ও পরীক্ষার রুটিন</span>
                            </a>
                            <a href="{{ route('frontend.teachers.index') }}"
                               class="flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-white hover:bg-emerald-50 hover:text-[#005030] text-slate-800 text-xs font-semibold border border-slate-200 transition-all hover:-translate-y-0.5 shadow-2xs">
                                <i class="fa-solid fa-user-tie text-[#005030] text-xs"></i>
                                <span>শ্রদ্ধেয় শিক্ষকবৃন্দ</span>
                            </a>
                        </div>
                        <a href="https://wa.me/8801712345678" target="_blank" rel="noopener noreferrer"
                           class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-[#005030] hover:bg-[#003d24] text-white text-xs font-bold transition-all hover:-translate-y-0.5 shadow-xs">
                            <i class="fa-brands fa-whatsapp text-sm text-emerald-300"></i>
                            <span>WhatsApp হেল্পলাইন: +৮৮০ ১৭১২-৩৪৫৬৭৮</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Admissions pathway (Connected 3-Step Flow) --}}
    <section class="home-section bg-slate-50/70 py-12 sm:py-16" id="admission">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="section-header-block items-start">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    ভর্তি নির্দেশিকা
                </span>
                <h2 class="section-title">
                    সহজ ৩টি ধাপে <span class="headline-signature-accent">ভর্তি সম্পন্ন করার প্রক্রিয়া</span>
                </h2>
            </div>

            <div class="steps-grid">
                {{-- Step 1 --}}
                <div class="step-card bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 relative flex flex-col justify-between shadow-2xs group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center font-extrabold text-base mb-4 shadow-2xs group-hover:bg-[#005030] group-hover:text-white transition-all duration-300">
                            ০১
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1.5 leading-snug">
                            অনলাইন বা সরাসরি আবেদন
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            ওয়েবসাইটে সহজ অনলাইন ফরম পূরণ করুন অথবা বিদ্যালয় কার্যালয় থেকে সরাসরি প্রসপেক্টাস সংগ্রহ করুন।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                        <span>অনলাইন / অফলাইন ফরম</span>
                        <span class="text-[#005030] font-bold flex items-center gap-1"><i class="fa-solid fa-check text-xs"></i> ১ম ধাপ</span>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="step-card bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 relative flex flex-col justify-between shadow-2xs group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center font-extrabold text-base mb-4 shadow-2xs group-hover:bg-[#005030] group-hover:text-white transition-all duration-300">
                            ০২
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1.5 leading-snug">
                            শিক্ষক-অভিভাবক আন্তরিক সংলাপ
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            শিক্ষার্থীর আগ্রহ জানা এবং শিক্ষকদের সাথে ভবিষ্যৎ শিক্ষাপরিকল্পনা নিয়ে খোলামেলা মতবিনিময়।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                        <span>পরামর্শ ও কাউন্সিলিং</span>
                        <span class="text-[#005030] font-bold flex items-center gap-1"><i class="fa-solid fa-check text-xs"></i> ২য় ধাপ</span>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="step-card bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 relative flex flex-col justify-between shadow-2xs group">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center font-extrabold text-base mb-4 shadow-2xs group-hover:bg-[#005030] group-hover:text-white transition-all duration-300">
                            ০৩
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1.5 leading-snug">
                            নথি জমা ও আইডি কার্ড গ্রহণ
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            জন্মনিবন্ধন ও ট্রান্সক্রিপ্ট জমাদানের মাধ্যমে ভর্তি সম্পন্ন; সাথে সাথে আইডি ও রুটিন হস্তান্তর।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                        <span>সেশন প্রস্তুতি ও রুটিন</span>
                        <span class="text-emerald-700 font-bold flex items-center gap-1"><i class="fa-solid fa-check text-xs"></i> ৩য় ধাপ</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('frontend.contact') }}#enroll"
                   class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#005030] hover:bg-[#003d24] text-white text-xs sm:text-sm font-bold shadow-xs transition-all hover:scale-105 active:scale-95">
                    <span>ভর্তি আবেদন ও তথ্যসহায়তা</span>
                    <i class="fa-solid fa-arrow-right-long text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Campus life experience (Authentic School Campus Imagery replacing SaaS Laptop) --}}
    <section class="home-section bg-white py-12 sm:py-16" id="experience">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="section-header-block items-start">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    ক্যাম্পাস জীবন ও প্রাত্যহিক পরিবেশ
                </span>
                <h2 class="section-title">
                    সোনাতলা ক্যাম্পাসে প্রাত্যহিক দিনের <span class="headline-signature-accent">বাস্তব চিত্র</span>
                </h2>
            </div>

            <div class="space-y-6">
                {{-- Clean Authentic School Campus Life Showcase --}}
                <div class="relative rounded-2xl overflow-hidden border border-slate-200/90 shadow-sm aspect-16/9 sm:aspect-21/9 max-h-[420px] group">
                    <img src="{{ asset('assets/images/slider-1.jpg') }}"
                         alt="সোনাতলা উচ্চ বিদ্যালয় ক্যাম্পাস প্রাঙ্গণ ও পরিবেশ"
                         class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/25 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 right-4 sm:right-6 flex flex-wrap items-center justify-between gap-3 text-white">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#005030]/90 text-emerald-200 text-xs font-bold border border-emerald-400/40 backdrop-blur-xs mb-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                ক্যাম্পাস প্রাঙ্গণ
                            </span>
                            <h3 class="text-base sm:text-2xl font-extrabold text-white">সবুজ শ্যামল ছায়ায় নৈতিকতা ও সুশৃঙ্খল বিদ্যাপীঠ</h3>
                        </div>
                        <div class="hidden md:flex items-center gap-2 text-xs text-slate-200 bg-black/40 backdrop-blur-xs px-3.5 py-1.5 rounded-full border border-white/20">
                            <i class="fa-solid fa-location-dot text-emerald-400"></i>
                            <span>বেড়া-সোনাতলা, সাঁথিয়া, পাবনা</span>
                        </div>
                    </div>
                </div>

                <div class="experience-summaries-grid pt-1">
                    <div class="experience-card bg-slate-50 border border-slate-200/90 rounded-2xl p-4 sm:p-5 flex flex-col justify-between shadow-2xs group transition-all">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded bg-white text-[#005030] text-[11px] font-bold border border-slate-200 mb-2.5 shadow-2xs">
                                <span><i class="fa-regular fa-sun text-amber-500"></i></span>
                                <span>সকাল ৯:০০ টা</span>
                            </div>
                            <h4 class="text-sm sm:text-base font-bold text-slate-900 mb-1 group-hover:text-[#005030] transition-colors">
                                প্রাত্যহিক সমাবেশ ও শপথ
                            </h4>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                সমাবেশ, জাতীয় সংগীত ও নৈতিক শৃঙ্খলায় দিনের শুভসূচনা।
                            </p>
                        </div>
                    </div>

                    <div class="experience-card bg-slate-50 border border-slate-200/90 rounded-2xl p-4 sm:p-5 flex flex-col justify-between shadow-2xs group transition-all">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded bg-white text-[#005030] text-[11px] font-bold border border-slate-200 mb-2.5 shadow-2xs">
                                <span><i class="fa-solid fa-flask-vial text-emerald-600"></i></span>
                                <span>দুপুর ১:৩০ টা</span>
                            </div>
                            <h4 class="text-sm sm:text-base font-bold text-slate-900 mb-1 group-hover:text-[#005030] transition-colors">
                                বাস্তবমুখী ল্যাব পরীক্ষণ
                            </h4>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                বিজ্ঞানাগার ও কম্পিউটার ল্যাবে হাতেকলমে দক্ষতা শেখার উচ্ছ্বাস।
                            </p>
                        </div>
                    </div>

                    <div class="experience-card bg-slate-50 border border-slate-200/90 rounded-2xl p-4 sm:p-5 flex flex-col justify-between shadow-2xs group transition-all">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded bg-white text-[#005030] text-[11px] font-bold border border-slate-200 mb-2.5 shadow-2xs">
                                <span><i class="fa-solid fa-volleyball text-emerald-600"></i></span>
                                <span>বিকাল ৪:০০ টা</span>
                            </div>
                            <h4 class="text-sm sm:text-base font-bold text-slate-900 mb-1 group-hover:text-[#005030] transition-colors">
                                ছুটি ও সহপাঠক্রমিক চর্চা
                            </h4>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                খেলাধুলা, বিতর্ক, স্কাউটিং ও সাংস্কৃতিক চর্চায় শিক্ষার্থীদের মানসিক প্রফুল্লতা।
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Photo gallery (Vibrant Editorial Grid) --}}
    <section class="home-section bg-slate-50/70 py-12 sm:py-16" id="gallery">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="section-header-block items-start">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    ফটো গ্যালারি
                </span>
                <h2 class="section-title">
                    ক্যাম্পাসের প্রাণবন্ত <span class="headline-signature-accent">স্মরণীয় মুহূর্তচিত্র</span>
                </h2>
            </div>

            <div class="gallery-grid">
                <div class="space-y-2 group">
                    <div class="aspect-4/3 rounded-2xl border border-slate-200 overflow-hidden bg-slate-100 shadow-2xs">
                        <img src="{{ asset('assets/images/slider-1.jpg') }}"
                             alt="বিদ্যালয় ভবন ও প্রাঙ্গণ"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="text-xs font-bold text-slate-800 text-center">সবুজ ছায়ায় ঘেরা প্রধান ক্যাম্পাস ভবন</div>
                </div>

                <div class="space-y-2 group">
                    <div class="aspect-4/3 rounded-2xl border border-slate-200 overflow-hidden bg-slate-100 shadow-2xs">
                        <img src="{{ asset('assets/images/slider-3.jpg') }}"
                             alt="কম্পিউটার ল্যাব"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="text-xs font-bold text-slate-800 text-center">ভবিষ্যতের দক্ষতা অর্জনে আইসিটি ল্যাব</div>
                </div>

                <div class="space-y-2 group">
                    <div class="aspect-4/3 rounded-2xl border border-slate-200 overflow-hidden bg-slate-100 shadow-2xs">
                        <img src="{{ asset('assets/images/slider-2.jpg') }}"
                             alt="বার্ষিক ক্রীড়া প্রতিযোগিতা"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="text-xs font-bold text-slate-800 text-center">সুস্থ দেহ ও বন্ধুত্বের প্রতীক: খেলার মাঠ</div>
                </div>

                <div class="space-y-2 group">
                    <div class="aspect-4/3 rounded-2xl border border-slate-200 overflow-hidden bg-slate-100 shadow-2xs">
                        <img src="{{ asset('assets/images/news-2.jpg') }}"
                             alt="বিজ্ঞান মেলা ও ল্যাব"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="text-xs font-bold text-slate-800 text-center">কৌতূহলী মনের আবিষ্কার: বিজ্ঞান মেলা</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Admissions closing CTA --}}
    <section class="home-section bg-white py-12 sm:py-16" id="enroll">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="cta-banner-box">
                <div class="relative z-10 max-w-3xl">
                    <span class="inline-block px-3 py-1 rounded bg-amber-400 text-slate-950 text-xs font-bold uppercase tracking-wider mb-4">
                        ভর্তি চলছে • ২০২৬ শিক্ষাবর্ষ
                    </span>
                    <h3 class="text-2xl sm:text-4xl font-extrabold leading-tight text-white mb-3">
                        আপনার সন্তানের <span class="text-amber-400">সম্ভাবনাময় ভবিষ্যতের</span> সূচনা হোক আজই
                    </h3>
                    <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed mb-6">
                        ৬ষ্ঠ থেকে ১০ম শ্রেণিতে নতুন ভর্তির জন্য আজই অনলাইনে আবেদন করুন অথবা সরাসরি ক্যাম্পাস পরিদর্শনের জন্য যোগাযোগ করুন।
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('frontend.contact') }}"
                           class="px-5 py-3 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs sm:text-sm transition-all shadow-md hover:scale-105 active:scale-95 inline-flex items-center gap-2 group">
                            <span>ভর্তি আবেদন ও তথ্যসহায়তা</span>
                            <i class="fa-solid fa-arrow-right-long text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>

                        <a href="https://wa.me/8801712345678" target="_blank" rel="noopener noreferrer"
                           class="px-5 py-3 rounded-full bg-white/15 hover:bg-white/25 text-white font-bold text-xs sm:text-sm border border-white/30 backdrop-blur-xs transition-all hover:scale-105 active:scale-95 inline-flex items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                            <span>WhatsApp হেল্পলাইন</span>
                        </a>

                        <a href="tel:+8801712345678"
                           class="px-5 py-3 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white font-bold text-xs sm:text-sm border border-slate-700 transition-all inline-flex items-center gap-2 hover:scale-105 active:scale-95">
                            <i class="fa-solid fa-phone text-xs text-slate-400"></i>
                            <span>০১৭১২-৩৪৫৬৭৮</span>
                        </a>
                    </div>

                    <div class="mt-6 pt-5 border-t border-emerald-700/60 flex flex-wrap items-center gap-3 text-xs text-emerald-200 font-medium">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-landmark text-emerald-300"></i> প্রতিষ্ঠা: ১৯৬৮ খ্রি.</span>
                        <span>•</span>
                        <span>EIIN: ১২৫৬৯৩</span>
                        <span>•</span>
                        <span>স্কুল কোড: ৮১০৭</span>
                        <span>•</span>
                        <span>স্বীকৃতিপ্রাপ্ত: রাজশাহী শিক্ষা বোর্ড</span>
                    </div>
                </div>

                <div class="absolute -right-10 -bottom-10 w-96 h-96 rounded-full bg-emerald-400/5 pointer-events-none blur-3xl"></div>
            </div>
        </div>
    </section>

@endsection
