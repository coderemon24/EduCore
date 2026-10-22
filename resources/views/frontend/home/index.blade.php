@extends('layouts.app')

@section('title', 'সোনাতলা উচ্চ বিদ্যালয় | Sonatala High School')

@section('content')
    {{-- 1. HERO SLIDER DIRECTLY BELOW NAVBAR --}}
    @include('frontend.home.partials.fullscreen-slider')

    {{-- 2. THIN QUALIFYING STRIP: WHO THIS PRODUCT / INSTITUTION IS FOR --}}
    <section class="qualifying-strip-bar py-3.5 sm:py-4 px-4 sm:px-6 lg:px-8 relative z-20 shadow-xs" aria-label="কাদের জন্য সোনাতলা উচ্চ বিদ্যালয়">
        <div class="max-w-7xl mx-auto flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            {{-- Left Label & Kicker --}}
            <div class="flex items-center gap-3 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[11px] font-bold uppercase tracking-wider border border-emerald-400/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    WHO WE SERVE
                </span>
                <span class="text-xs sm:text-sm font-semibold text-slate-200">
                    সোনাতলা বিদ্যাপীঠ মূলত যাদের জন্য:
                </span>
            </div>

            {{-- 4 Targeted Audience Segments --}}
            <div class="qualifying-cards-grid flex-1">
                <div class="qualifying-card">
                    <span class="text-emerald-400 shrink-0 mt-0.5"><i class="fa-solid fa-circle-check"></i></span>
                    <div>
                        <strong class="text-white block font-semibold text-sm">সচেতন অভিভাবক</strong>
                        <span class="text-xs text-slate-300 leading-relaxed block">সন্তানের নৈতিক চরিত্র গঠন, শৃঙ্খলা ও নিরাপদ পরিবেশ চান যারা</span>
                    </div>
                </div>

                <div class="qualifying-card">
                    <span class="text-emerald-400 shrink-0 mt-0.5"><i class="fa-solid fa-circle-check"></i></span>
                    <div>
                        <strong class="text-white block font-semibold text-sm">৬ষ্ঠ-১০ম শ্রেণির শিক্ষার্থী</strong>
                        <span class="text-xs text-slate-300 leading-relaxed block">বিজ্ঞানাগার ও কম্পিউটার ল্যাবে হাতেকলমে দক্ষতা শিখতে আগ্রহী</span>
                    </div>
                </div>

                <div class="qualifying-card">
                    <span class="text-emerald-400 shrink-0 mt-0.5"><i class="fa-solid fa-circle-check"></i></span>
                    <div>
                        <strong class="text-white block font-semibold text-sm">মেধাবী ও উপবৃত্তি অন্বেষী</strong>
                        <span class="text-xs text-slate-300 leading-relaxed block">আর্থিক অনটনে কোনো মেধা যেন ঝরে না পড়ে, সে নিশ্চয়তা প্রত্যাশী</span>
                    </div>
                </div>

                <div class="qualifying-card">
                    <span class="text-emerald-400 shrink-0 mt-0.5"><i class="fa-solid fa-circle-check"></i></span>
                    <div>
                        <strong class="text-white block font-semibold text-sm">আদর্শ সমাজ ও অ্যালামনাই</strong>
                        <span class="text-xs text-slate-300 leading-relaxed block">পাবনার সাঁথিয়ার ৫৭ বছরের সমৃদ্ধ শিক্ষাধারাকে এগিয়ে নিতে চান</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. WHAT IT DOES: ACADEMIC CAPABILITIES & REAL-TIME PLATFORM --}}
    <section class="home-section bg-white" id="capabilities">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header: Rhythm Pattern (Kicker, Heading Left, Supporting Paragraph Right) --}}
            <div class="section-header-block">
                <div class="section-header-left">
                    <span class="section-kicker">
                        <span class="section-kicker-dot"></span>
                        WHAT WE DELIVER — ACADEMIC EXCELLENCE
                    </span>
                    <h2 class="section-title">
                        হাতেকলমে শিক্ষা, <span class="headline-signature-accent">প্রযুক্তির আলো</span> ও মানবিক মূল্যবোধের পূর্ণাঙ্গ বিকাশ
                    </h2>
                </div>
                <div class="section-header-right">
                    <p class="section-subtitle">
                        পুঁথিগত বিদ্যাকে বাস্তবে রূপ দিতে আমরা চালু করেছি নতুন শিক্ষাক্রমের আধুনিক ল্যাব, সার্বক্ষণিক আইসিটি প্রশিক্ষণ ও ব্যক্তিগত শিক্ষক মেন্টরিং। এখানে প্রতিটি শিক্ষার্থী নিজের সুপ্ত প্রতিভার বিকাশ ঘটিয়ে আগামীর জন্য আত্মবিশ্বাসী হয়ে ওঠে।
                    </p>
                </div>
            </div>

            {{-- Asymmetric Capabilities Layout: Flagship Dominant Hero (60%) + Secondary Stack (40%) --}}
            <div class="capabilities-grid">
                {{-- Dominant Flagship Hero: Practical Science & Laboratory Innovation --}}
                <div class="capability-card-hero group">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center font-bold shadow-2xs group-hover:scale-105 transition-transform duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
                                <span><i class="fa-solid fa-flask-vial"></i></span>
                                <span>প্রধান প্রাতিষ্ঠানিক সক্ষমতা</span>
                            </span>
                        </div>

                        <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-3 leading-snug">
                            হাতেকলমে ব্যবহারিক বিজ্ঞান ও আধুনিক ল্যাব পরীক্ষণ
                        </h3>
                        <p class="text-sm sm:text-base text-slate-700 leading-relaxed mb-6">
                            পুঁথিগত তত্ত্বের বাইরে গিয়ে শিক্ষার্থীরা যাতে বিজ্ঞানের প্রতিটি সূত্র বাস্তবে প্রত্যক্ষ করতে পারে, সেজন্য রয়েছে পদার্থ, রসায়ন ও জীববিজ্ঞানের পৃথক শীতাতপনিয়ন্ত্রিত ল্যাব। নিয়মিত পরীক্ষণ ও বার্ষিক বিজ্ঞান উৎসবের মাধ্যমে শিক্ষার্থীদের মৌলিক চিন্তাশক্তি শাণিত করা হয়।
                        </p>

                        {{-- Structured Laboratory Proof Checklist --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-4 border-t border-slate-200/80 mb-6">
                            <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800">
                                <span class="w-5 h-5 rounded-full bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center shrink-0 text-[11px] font-bold"><i class="fa-solid fa-check"></i></span>
                                <span>পদার্থ, রসায়ন ও জীববিজ্ঞানের পৃথক ল্যাব</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800">
                                <span class="w-5 h-5 rounded-full bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center shrink-0 text-[11px] font-bold"><i class="fa-solid fa-check"></i></span>
                                <span>উচ্চমানের মাইক্রোস্কোপ ও রিএজেন্ট সুবিধা</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800">
                                <span class="w-5 h-5 rounded-full bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center shrink-0 text-[11px] font-bold"><i class="fa-solid fa-check"></i></span>
                                <span>অভিজ্ঞ ল্যাব সহকারী ও শিক্ষক তত্ত্বাবধান</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800">
                                <span class="w-5 h-5 rounded-full bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center shrink-0 text-[11px] font-bold"><i class="fa-solid fa-check"></i></span>
                                <span>বার্ষিক বিজ্ঞান ও উদ্ভাবন প্রজেক্ট প্রদর্শনী</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200/70 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-medium">৬ষ্ঠ-১০ম শ্রেণির বাধ্যতামূলক প্র্যাকটিক্যাল সেশন</span>
                        <a href="{{ route('frontend.about') }}" class="text-xs sm:text-sm font-bold text-[#005030] hover:underline inline-flex items-center gap-1.5 group/link">
                            <span>বিজ্ঞানাগারের বিস্তারিত পরিকাঠামো</span>
                            <i class="fa-solid fa-arrow-right-long text-xs transition-transform group-hover/link:translate-x-1"></i>
                        </a>
                    </div>
                </div>

                {{-- Secondary Stack: Compact 2 Cards in Lighter Secondary Tier --}}
                <div class="capabilities-secondary-stack">
                    {{-- Secondary Card 1: ICT & Digital Education --}}
                    <div class="capability-card-secondary group">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center font-bold group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-400">ডিজিটাল সাক্ষরতা</span>
                            </div>
                            <h4 class="text-lg sm:text-xl font-bold text-slate-900 mb-2 leading-snug">
                                ডিজিটাল আইসিটি ও কম্পিউটার শিক্ষা
                            </h4>
                            <p class="text-sm text-slate-700 leading-relaxed mb-3">
                                উচ্চগতির ইন্টারনেট ও আধুনিক কম্পিউটার সমৃদ্ধ ল্যাবে তথ্যপ্রযুক্তির মৌলিক দক্ষতা, কোডিং পরিচিতি ও ডিজিটাল শিক্ষায় শিক্ষার্থীদের যুগোপযোগী প্রস্তুতি।
                            </p>
                        </div>
                        <a href="{{ route('frontend.about') }}#infrastructure" class="pt-3 border-t border-slate-200/60 text-xs font-semibold text-[#005030] flex items-center justify-between group-hover:text-emerald-800">
                            <span>কম্পিউটার ল্যাব সুবিধা</span>
                            <i class="fa-solid fa-arrow-right-long text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>

                    {{-- Secondary Card 2: Co-Curricular & Sports --}}
                    <div class="capability-card-secondary group">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center font-bold group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-400">নেতৃত্ব ও শৃঙ্খলা</span>
                            </div>
                            <h4 class="text-lg sm:text-xl font-bold text-slate-900 mb-2 leading-snug">
                                স্কাউটিং, বিতর্ক ও ক্রীড়াঙ্গন
                            </h4>
                            <p class="text-sm text-slate-700 leading-relaxed mb-3">
                                নিয়মিত খেলাধুলা, বিতর্ক প্রতিযোগিতা, সক্রিয় স্কাউট ট্রুপ ও সাংস্কৃতিক আয়োজনের মাধ্যমে প্রতিটি শিক্ষার্থীর মানসিক দৃঢ়তা ও দলগত নেতৃত্ব বিকাশ।
                            </p>
                        </div>
                        <a href="{{ route('frontend.about') }}" class="pt-3 border-t border-slate-200/60 text-xs font-semibold text-[#005030] flex items-center justify-between group-hover:text-emerald-800">
                            <span>সহ-শিক্ষা ও ক্রীড়া কার্যক্রম</span>
                            <i class="fa-solid fa-arrow-right-long text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION DIVIDER --}}
    <div class="home-section-divider"></div>

    {{-- 4. WHY IT IS DIFFERENT: THE INSTITUTIONAL ADVANTAGE & METRICS --}}
    <section class="home-section bg-slate-50/60" id="advantage">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header: Rhythm Pattern --}}
            <div class="section-header-block">
                <div class="section-header-left">
                    <span class="section-kicker">
                        <span class="section-kicker-dot"></span>
                        WHY WE ARE DIFFERENT — THE ADVANTAGE
                    </span>
                    <h2 class="section-title">
                        সাধারণ স্কুলের চেয়ে কেন ব্যতিক্রমী <span class="headline-signature-accent">আমাদের শিক্ষাপদ্ধতি ও ঐতিহ্য</span>?
                    </h2>
                </div>
                <div class="section-header-right">
                    <p class="section-subtitle">
                        ১৯৬৮ সালে প্রতিষ্ঠার পর থেকে ৫ দশকেরও বেশি সময় ধরে সাঁথিয়া অঞ্চলের সর্বস্তরের অভিভাবকের নির্ভরযোগ্য আস্থা সোনাতলা উচ্চ বিদ্যালয়। শুধু পাসের সংখ্যা নয়, আমরা গড়ে তুলি বিবেকবান, সুশৃঙ্খল ও আত্মমর্যাদাবোধসম্পন্ন প্রতিটি সন্তানকে।
                    </p>
                </div>
            </div>

            {{-- 4 Comparative Value Pillars --}}
            <div class="differentiators-grid mb-12">
                {{-- Pillar 1 --}}
                <div class="differentiator-card bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 flex gap-4 shadow-2xs group">
                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 text-slate-800 flex items-center justify-center shrink-0 font-bold text-sm group-hover:scale-105 transition-transform duration-300">
                        ০১
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1.5">
                            ৫৭ বছরের ধারাবাহিক ঈর্ষণীয় সাফল্য ও ঐতিহ্য
                        </h3>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            বোর্ড পরীক্ষায় ধারাবাহিক ৯৮.৫% সাফল্য এবং শত শত প্রাক্তন কৃতি শিক্ষার্থীর দেশ-বিদেশের শীর্ষ বিশ্ববিদ্যালয় ও পেশায় দায়িত্বশীল অবস্থানই আমাদের নির্ভরযোগ্যতার জীবন্ত প্রমাণ।
                        </p>
                    </div>
                </div>

                {{-- Pillar 2 --}}
                <div class="differentiator-card bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 flex gap-4 shadow-2xs group">
                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 text-slate-800 flex items-center justify-center shrink-0 font-bold text-sm group-hover:scale-105 transition-transform duration-300">
                        ০২
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1.5">
                            বিএড ও মাস্টার্স ডিগ্রিধারী নিবেদিত শিক্ষক পরিষদ
                        </h3>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            প্রতিটি বিষয়ে অভিজ্ঞ ও প্রশিক্ষিত শিক্ষকমণ্ডলীর সরাসরি তত্ত্বাবধান। কোনো বিকল্প নয়—শ্রেণিকক্ষেই শতভাগ ধারণা পরিষ্কার করে দেওয়া হয় যাতে অতিরিক্ত প্রাইভেটের বোঝা না বাড়ে।
                        </p>
                    </div>
                </div>

                {{-- Pillar 3 --}}
                <div class="differentiator-card bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 flex gap-4 shadow-2xs group">
                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 text-slate-800 flex items-center justify-center shrink-0 font-bold text-sm group-hover:scale-105 transition-transform duration-300">
                        ০৩
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1.5">
                            রাজনীতিমুক্ত, বুলিংমুক্ত ও ১০০% নিরাপদ সবুজ ক্যাম্পাস
                        </h3>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            ছাত্র-ছাত্রীদের পূর্ণ নিরাপত্তা, নিয়মিত অভিভাবক পরামর্শ এবং স্নেহশীল সুশৃঙ্খল পরিবেশ—যেখানে কোনো শিক্ষার্থী কোনো ভয় বা সংকোচ ছাড়া প্রশ্ন করতে শেখে।
                        </p>
                    </div>
                </div>

                {{-- Pillar 4 --}}
                <div class="differentiator-card bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 flex gap-4 shadow-2xs group">
                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 text-slate-800 flex items-center justify-center shrink-0 font-bold text-sm group-hover:scale-105 transition-transform duration-300">
                        ০৪
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1.5">
                            EduCore স্মার্ট ম্যানেজমেন্ট ও সার্বক্ষণিক অভিভাবক সংযোগ
                        </h3>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            অনলাইন নোটিশ বোর্ড, পরীক্ষার সময়সূচি এবং জরুরি বিজ্ঞপ্তি ঘরে বসেই স্মার্টফোনে দেখার সুবিধা। যে কোনো তথ্যে সরাসরি অফিস ও শিক্ষকদের সাথে যোগাযোগের দ্রুত ব্যবস্থা।
                        </p>
                    </div>
                </div>
            </div>

            {{-- Institutional Metrics Counter: Primary Outcomes + Secondary Meta Strip --}}
            <div class="metrics-counter-box bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-xs">
                <div class="bg-[#005030] text-white px-6 py-3.5 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider">
                        এক নজরে আমাদের প্রাতিষ্ঠানিক সক্ষমতা ও অর্জন
                    </span>
                    <span class="text-xs text-emerald-200 font-medium">প্রতিষ্ঠানের মূল সূচকসমূহ</span>
                </div>

                {{-- Primary Tier: 3 Dominant Headline Achievements --}}
                <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-3 gap-6 text-center border-b border-slate-100">
                    <div class="metric-pill p-4 rounded-xl bg-slate-50/80 border border-slate-200/70 transition-all hover:bg-emerald-50/50 hover:border-emerald-200">
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">এসএসসি ধারাবাহিক সাফল্য</div>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#005030] mt-2">৯৮.৫%</div>
                        <div class="text-xs text-slate-500 mt-1">বিগত ৫ বছরের ঈর্ষণীয় ফলাফল</div>
                    </div>
                    <div class="metric-pill p-4 rounded-xl bg-slate-50/80 border border-slate-200/70 transition-all hover:bg-emerald-50/50 hover:border-emerald-200">
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">বর্তমান শিক্ষার্থী পরিবার</div>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 mt-2">{{ $stats['total_students'] ?? '১,২৫০+' }} জন</div>
                        <div class="text-xs text-slate-500 mt-1">সুশৃঙ্খল ও প্রাণবন্ত ক্যাম্পাস</div>
                    </div>
                    <div class="metric-pill p-4 rounded-xl bg-slate-50/80 border border-slate-200/70 transition-all hover:bg-emerald-50/50 hover:border-emerald-200">
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">গৌরবময় ঐতিহ্য ও ইতিহাস</div>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 mt-2">৫৭ বছর</div>
                        <div class="text-xs text-slate-500 mt-1">প্রতিষ্ঠা সন: ১৯৬৮ খ্রি.</div>
                    </div>
                </div>

                {{-- Secondary Tier: Lighter Operational & Administrative Meta Strip --}}
                <div class="px-6 py-4 bg-slate-50/70 flex flex-wrap items-center justify-between gap-4 text-xs text-slate-600">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5"><i class="fa-solid fa-chalkboard-user text-emerald-700"></i> শিক্ষক ও স্টাফ:</span>
                        <span>{{ $stats['total_teachers'] ?? '৪২' }} জন প্রশিক্ষিত শিক্ষক</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-800 flex items-center gap-1.5"><i class="fa-solid fa-desktop text-emerald-700"></i> ডিজিটাল ক্লাসরুম:</span>
                        <span>১৮টি মাল্টিমিডিয়া রুম</span>
                    </div>
                    <div class="flex items-center gap-2 font-mono">
                        <span class="font-bold text-slate-800 font-sans flex items-center gap-1.5"><i class="fa-solid fa-building-columns text-emerald-700"></i> EIIN:</span>
                        <span>১২৫৬৯৩</span>
                    </div>
                    <div class="flex items-center gap-2 font-mono">
                        <span class="font-bold text-slate-800 font-sans flex items-center gap-1.5"><i class="fa-solid fa-file-shield text-emerald-700"></i> সরকারি কোড:</span>
                        <span>৮১০৭</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION DIVIDER --}}
    <div class="home-section-divider"></div>

    {{-- 5. CURRENT DISPATCHES & NOTICE BOARD (REAL-TIME ACADEMIC PULSE) --}}
    <section class="home-section bg-white" id="notices">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header: Rhythm Pattern --}}
            <div class="section-header-block">
                <div class="section-header-left">
                    <span class="section-kicker">
                        <span class="section-kicker-dot"></span>
                        LIVE DISPATCHES & OFFICIAL NOTICES
                    </span>
                    <h2 class="section-title">
                        জরুরি নোটিশ বোর্ড ও <span class="headline-signature-accent">চলমান প্রাতিষ্ঠানিক পরিক্রমা</span>
                    </h2>
                </div>
                <div class="section-header-right">
                    <p class="section-subtitle">
                        পরীক্ষার সময়সূচি, সরকারি উপবৃত্তি, ভর্তি নির্দেশনা ও সহ-শিক্ষার সকল আনুষ্ঠানিক সার্কুলার সবার আগে জানুন সরাসরি আমাদের রিয়েল-টাইম প্রাতিষ্ঠানিক বুলেটিন থেকে।
                    </p>
                </div>
            </div>

            {{-- 2-Column Balanced Editorial Hub --}}
            <div class="dispatches-grid">
                {{-- Column 1: Interactive Notice Board Widget --}}
                <div class="w-full h-full">
                    @include('frontend.home.partials.notice-board')
                </div>

                {{-- Column 2: Campus Stories + Quick Services --}}
                <div class="space-y-6">
                    {{-- Campus Stories Card --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-xs">
                        <div class="bg-[#005030] text-white px-5 py-3 flex items-center justify-between">
                            <h3 class="font-bold text-xs uppercase tracking-wider">
                                সাফল্য ও ক্যাম্পাস সংবাদ
                            </h3>
                            <span class="text-[11px] text-emerald-200">Campus Stories</span>
                        </div>
                        <div class="divide-y divide-slate-100">
                            {{-- Story 1 --}}
                            <article class="p-3.5 hover:bg-slate-50 transition-colors flex gap-3.5 items-center">
                                <div class="w-20 h-16 sm:w-24 sm:h-18 shrink-0 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                    <img src="{{ asset('assets/images/news-1.jpg') }}" alt="বার্ষিক ক্রীড়া" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm sm:text-base font-bold text-slate-900 leading-snug hover:text-[#005030] line-clamp-2">
                                        <a href="{{ route('frontend.notices.index') }}">
                                            দলগত সম্প্রীতি ও সুস্থ জীবনের প্রত্যয়ে বার্ষিক ক্রীড়া প্রতিযোগিতা সম্পন্ন
                                        </a>
                                    </h4>
                                    <span class="text-[11px] text-slate-500 font-medium mt-1 block">১৯ অক্টোবর, ২০২৬</span>
                                </div>
                            </article>

                            {{-- Story 2 --}}
                            <article class="p-3.5 hover:bg-slate-50 transition-colors flex gap-3.5 items-center">
                                <div class="w-20 h-16 sm:w-24 sm:h-18 shrink-0 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                    <img src="{{ asset('assets/images/news-2.jpg') }}" alt="বিজ্ঞান মেলা" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm sm:text-base font-bold text-slate-900 leading-snug hover:text-[#005030] line-clamp-2">
                                        <a href="{{ route('frontend.notices.index') }}">
                                            বিজ্ঞানের ব্যবহারিক উদ্ভাবনে মুখর ক্যাম্পাস: ক্ষুদে বিজ্ঞানীদের প্রজেক্ট প্রদর্শনী
                                        </a>
                                    </h4>
                                    <span class="text-[11px] text-slate-500 font-medium mt-1 block">১৮ অক্টোবর, ২০২৬</span>
                                </div>
                            </article>

                            {{-- Story 3 --}}
                            <article class="p-3.5 hover:bg-slate-50 transition-colors flex gap-3.5 items-center">
                                <div class="w-20 h-16 sm:w-24 sm:h-18 shrink-0 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                    <img src="{{ asset('assets/images/news-3.jpg') }}" alt="অভিভাবক সমাবেশ" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm sm:text-base font-bold text-slate-900 leading-snug hover:text-[#005030] line-clamp-2">
                                        <a href="{{ route('frontend.notices.index') }}">
                                            সন্তানের মানসিক সুস্থতা ও নতুন মূল্যায়ন নিয়ে শিক্ষক-অভিভাবক আন্তরিক সংলাপ
                                        </a>
                                    </h4>
                                    <span class="text-[11px] text-slate-500 font-medium mt-1 block">১৫ অক্টোবর, ২০২৬</span>
                                </div>
                            </article>
                        </div>
                        <div class="p-3 bg-slate-50 border-t border-slate-100 text-center">
                            <a href="{{ route('frontend.notices.index') }}" class="text-xs font-bold text-[#005030] hover:underline inline-flex items-center gap-1.5 group">
                                <span>আরও অনুপ্রেরণাদায়ী গল্প দেখুন</span>
                                <i class="fa-solid fa-arrow-right-long text-xs transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Quick Action Links --}}
                    <div class="bg-slate-50 border border-slate-200/90 rounded-2xl p-4 sm:p-5 space-y-3">
                        <div class="text-xs font-bold text-slate-900 border-b border-slate-200/70 pb-2 uppercase tracking-wide">
                            অভিভাবক ও শিক্ষার্থী সেবা
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            <a href="{{ route('frontend.academic.routine') }}"
                               class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-white hover:bg-emerald-50 hover:text-[#005030] text-slate-800 text-xs font-semibold border border-slate-200 transition-all hover:-translate-y-0.5 shadow-2xs">
                                <i class="fa-solid fa-calendar-days text-[#005030] text-sm"></i>
                                <span>ক্লাস ও পরীক্ষার রুটিন</span>
                            </a>
                            <a href="{{ route('frontend.teachers.index') }}"
                               class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-white hover:bg-emerald-50 hover:text-[#005030] text-slate-800 text-xs font-semibold border border-slate-200 transition-all hover:-translate-y-0.5 shadow-2xs">
                                <i class="fa-solid fa-user-tie text-[#005030] text-sm"></i>
                                <span>আমাদের শ্রদ্ধেয় শিক্ষকগণ</span>
                            </a>
                        </div>
                        <a href="https://wa.me/8801712345678" target="_blank" rel="noopener noreferrer"
                           class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-[#25D366] hover:bg-[#20ba5a] text-white text-xs font-bold transition-all hover:-translate-y-0.5 shadow-xs">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                            <span>WhatsApp-এ সরাসরি কথা বলুন</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION DIVIDER --}}
    <div class="home-section-divider"></div>

    {{-- 6. HOW YOU GET STARTED: ADMISSION & ENROLLMENT JOURNEY --}}
    <section class="home-section bg-slate-50/60" id="admission">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header: Rhythm Pattern --}}
            <div class="section-header-block">
                <div class="section-header-left">
                    <span class="section-kicker">
                        <span class="section-kicker-dot"></span>
                        HOW TO GET STARTED — ENROLLMENT PATHWAY
                    </span>
                    <h2 class="section-title">
                        মাত্র ৩টি সহজ ধাপে আপনার সন্তানের <span class="headline-signature-accent">সোনাতলা যাত্রার সূচনা</span>
                    </h2>
                </div>
                <div class="section-header-right">
                    <p class="section-subtitle">
                        কোনো দীর্ঘসূত্রিতা বা অতিরিক্ত আনুষ্ঠানিকতা নেই। অনলাইনে অথবা ক্যাম্পাসে এসে মাত্র ৩টি স্বচ্ছ পদক্ষেপে আপনার সন্তানের ভর্তি ও নতুন সেশনের শুভসূচনা নিশ্চিত করুন।
                    </p>
                </div>
            </div>

            {{-- 3 Step Process Cards --}}
            <div class="steps-grid mb-10">
                {{-- Step 1 --}}
                <div class="step-card bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 relative flex flex-col justify-between shadow-2xs group">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <span class="text-3xl font-extrabold text-slate-800 font-mono group-hover:scale-105 transition-transform duration-300">০১</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-bold border border-slate-200">
                                ধাপ ১
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2 leading-snug">
                            আবেদন ও প্রাথমিক তথ্য জমাদান
                        </h3>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            ওয়েবসাইটে সহজ অনলাইন ফরম পূরণ করুন অথবা বিদ্যালয় প্রশাসনিক কার্যালয় থেকে সরাসরি ভর্তি আবেদন ফরম ও প্রসপেক্টাস সংগ্রহ করুন।
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                        <span>অনলাইন / অফলাইন উভয় সুবিধা</span>
                        <span class="text-slate-700 font-bold flex items-center gap-1.5"><i class="fa-solid fa-check text-xs"></i> সহজ</span>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="step-card bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 relative flex flex-col justify-between shadow-2xs group">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <span class="text-3xl font-extrabold text-slate-800 font-mono group-hover:scale-105 transition-transform duration-300">০২</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-bold border border-slate-200">
                                ধাপ ২
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2 leading-snug">
                            শিক্ষক-অভিভাবক আন্তরিক কাউন্সিলিং
                        </h3>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            নতুন শিক্ষাক্রমের আলোকে শিক্ষার্থীর আগ্রহ ও সামর্থ্য জানা এবং অভিভাবকদের সাথে ভবিষ্যৎ শিক্ষাপরিকল্পনা নিয়ে খোলামেলা মতবিনিময়।
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                        <span>ব্যক্তিগত পরামর্শ ও কাউন্সেলিং</span>
                        <span class="text-slate-700 font-bold flex items-center gap-1.5"><i class="fa-solid fa-check text-xs"></i> বন্ধুত্বপূর্ণ</span>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="step-card bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 relative flex flex-col justify-between shadow-2xs group">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <span class="text-3xl font-extrabold text-slate-800 font-mono group-hover:scale-105 transition-transform duration-300">০৩</span>
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-bold border border-slate-200">
                                ধাপ ৩
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2 leading-snug">
                            নথিপত্র নিশ্চিতকরণ ও স্টুডেন্ট আইডি
                        </h3>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            জন্মনিবন্ধন ও পূর্বের ক্লাসের ট্রান্সক্রিপ্ট জমাদানের মাধ্যমে আনুষ্ঠানিক ভর্তি সম্পন্ন; সাথে সাথে আইডি কার্ড ও ক্লাস রুটিন হস্তান্তর।
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
                        <span>তাৎক্ষণিক সেশন প্রস্তুতি</span>
                        <span class="text-emerald-600 font-bold flex items-center gap-1.5"><i class="fa-solid fa-check text-xs"></i> ক্লাসে স্বাগতম</span>
                    </div>
                </div>
            </div>

            {{-- Action Banner --}}
            <div class="p-6 sm:p-8 bg-gradient-to-r from-[#004027] via-[#005030] to-[#043420] text-white rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xs">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">২০২৬ শিক্ষাবর্ষে আপনার সন্তানের মেধার বিকাশে আমরা পাশে আছি</h3>
                    <p class="text-sm sm:text-base text-emerald-100 mt-1">ভর্তি সংক্রান্ত যেকোনো জিজ্ঞাসায় আমাদের হেল্পলাইনে কথা বলুন অথবা সরাসরি যোগাযোগ করুন।</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <a href="{{ route('frontend.contact') }}"
                       class="px-5 py-2.5 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs sm:text-sm transition-colors shadow-xs">
                        ভর্তি অনুসন্ধান ও যোগাযোগ
                    </a>
                    <a href="{{ route('frontend.notices.index') }}"
                       class="px-5 py-2.5 rounded-full bg-white/10 hover:bg-white/20 text-white font-semibold text-xs sm:text-sm border border-white/20 transition-colors">
                        ভর্তি নোটিশ ডাউনলোড
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION DIVIDER --}}
    <div class="home-section-divider"></div>

    {{-- 7. WHAT IT FEELS LIKE TO USE: DAY-TO-DAY EXPERIENCE --}}
    <section class="home-section bg-white" id="experience">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header: Rhythm Pattern --}}
            <div class="section-header-block">
                <div class="section-header-left">
                    <span class="section-kicker">
                        <span class="section-kicker-dot"></span>
                        DAY-TO-DAY EXPERIENCE — WHAT IT FEELS LIKE
                    </span>
                    <h2 class="section-title">
                        সোনাতলা ক্যাম্পাসে একটি সাধারণ দিনের <span class="headline-signature-accent">বাস্তব অনুভূতি</span>
                    </h2>
                </div>
                <div class="section-header-right">
                    <p class="section-subtitle">
                        সকাল ৯টার প্রাত্যহিক সমাবেশ থেকে শুরু করে বিকেলে বিজ্ঞানের ব্যবহারিক ক্লাস আর স্মার্টফোনে নোটিশ পাওয়ার নিশ্চয়তা—শিক্ষার্থী ও অভিভাবকদের প্রতিদিনের জীবনযাত্রায় কীভাবে স্বস্তি ও শৃঙ্খলা এনে দেয় আমাদের প্রাতিষ্ঠানিক পরিবেশ?
                    </p>
                </div>
            </div>

            {{-- One Large Product Visual + 3 Short Summary Lines --}}
            <div class="space-y-8">
                {{-- One Large Product Visual Framed in Desktop Browser Frame --}}
                <div class="experience-frame bg-slate-900 border border-slate-200">
                    {{-- Minimalist Window Topbar --}}
                    <div class="px-4 py-3 bg-slate-900 border-b border-slate-800 flex items-center justify-between text-xs text-slate-400">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-500/80 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-500/80 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block"></span>
                            <span class="ml-2 font-mono text-[11px] text-slate-400 hidden sm:inline-block">sonatalahs.edu.bd/portal/live</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[10px] font-semibold border border-emerald-400/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                স্মার্ট ক্যাম্পাস ও ডিজিটাল লার্নিং প্ল্যাটফর্ম
                            </span>
                        </div>
                    </div>

                    {{-- Image Visual with capped max height for perfect proportions --}}
                    <div class="w-full relative bg-slate-950 aspect-video max-h-[500px] overflow-hidden">
                        <img src="{{ asset('assets/images/product-experience.jpg') }}"
                             alt="সোনাতলা উচ্চ বিদ্যালয় ডিজিটাল প্ল্যাটফর্ম ও স্মার্ট শিক্ষাঙ্গন"
                             class="w-full h-full object-cover">
                    </div>
                </div>

                {{-- Three Short Summary Lines Describing Day to Day Reality --}}
                <div class="experience-summaries-grid pt-4">
                    {{-- Summary Line 1 --}}
                    <div class="experience-card bg-slate-50 border border-slate-200/90 rounded-2xl p-5 sm:p-6 flex flex-col justify-between shadow-2xs group transition-all">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded bg-white text-[#005030] text-[11px] font-bold border border-slate-200 mb-3 shadow-2xs">
                                <span><i class="fa-regular fa-sun text-amber-500"></i></span>
                                <span>সকাল ৯:০০ টা</span>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-2 group-hover:text-[#005030] transition-colors">
                                শৃঙ্খলা ও নৈতিক প্রার্থনায় দিন শুরু
                            </h4>
                            <p class="text-sm text-slate-700 leading-relaxed">
                                প্রতিদিনের সকাল শুরু হয় সমাবেশ ও নৈতিক শৃঙ্খলা দিয়ে, শেষ হয় স্পষ্ট বাড়ির কাজ ও শিক্ষক পরামর্শে।
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-200/60 text-[11px] font-semibold text-emerald-700">
                            সুশৃঙ্খল ক্যাম্পাস সংস্কৃতি
                        </div>
                    </div>

                    {{-- Summary Line 2 --}}
                    <div class="experience-card bg-slate-50 border border-slate-200/90 rounded-2xl p-5 sm:p-6 flex flex-col justify-between shadow-2xs group transition-all">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded bg-white text-[#005030] text-[11px] font-bold border border-slate-200 mb-3 shadow-2xs">
                                <span><i class="fa-solid fa-mobile-screen-button text-emerald-600"></i></span>
                                <span>সারাদিন রিয়েল-টাইম</span>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-2 group-hover:text-[#005030] transition-colors">
                                হাতের মুঠোয় স্বচ্ছ তথ্যপ্রবাহ
                            </h4>
                            <p class="text-sm text-slate-700 leading-relaxed">
                                স্মার্ট ডায়েরি ও অনলাইন নোটিশ বোর্ডে রুটিন ও রেজাল্ট থাকে অভিভাবকের হাতের মুঠোয়।
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-200/60 text-[11px] font-semibold text-emerald-700">
                            অভিভাবকবান্ধব নিশ্চয়তা
                        </div>
                    </div>

                    {{-- Summary Line 3 --}}
                    <div class="experience-card bg-slate-50 border border-slate-200/90 rounded-2xl p-5 sm:p-6 flex flex-col justify-between shadow-2xs group transition-all">
                        <div>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded bg-white text-[#005030] text-[11px] font-bold border border-slate-200 mb-3 shadow-2xs">
                                <span><i class="fa-solid fa-flask-vial text-emerald-600"></i></span>
                                <span>দুপুর ১:৩০ টা</span>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-slate-900 mb-2 group-hover:text-[#005030] transition-colors">
                                তত্ত্ব পেরিয়ে বাস্তব আবিষ্কার
                            </h4>
                            <p class="text-sm text-slate-700 leading-relaxed">
                                থিওরির পরপরই বিজ্ঞানাগার ও কম্পিউটার ল্যাবে হাতেকলমে প্রয়োগ শেখার উচ্ছ্বাস।
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-200/60 text-[11px] font-semibold text-emerald-700">
                            ব্যবহারিক মেধার বিকাশ
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION DIVIDER --}}
    <div class="home-section-divider"></div>

    {{-- 8. CAMPUS LIFE & PROUD MOMENTS (PHOTO GALLERY) --}}
    <section class="home-section bg-slate-50/60" id="gallery">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header: Rhythm Pattern --}}
            <div class="section-header-block">
                <div class="section-header-left">
                    <span class="section-kicker">
                        <span class="section-kicker-dot"></span>
                        CAMPUS LIFE & PROUD MOMENTS
                    </span>
                    <h2 class="section-title">
                        ক্যাম্পাসের সোনালী মুহূর্ত ও <span class="headline-signature-accent">সোনাতলা প্রাঙ্গণচিত্র</span>
                    </h2>
                </div>
                <div class="section-header-right">
                    <p class="section-subtitle">
                        সবুজ শ্যামল ক্যাম্পাসে খেলাধুলার উচ্ছ্বাস, বিজ্ঞান মেলার উদ্ভাবন, শিক্ষক-শিক্ষার্থীর আন্তরিক বন্ধন ও প্রাতিষ্ঠানিক উৎসবের কয়েকটি স্মরণীয় চিত্রাবলি।
                    </p>
                </div>
            </div>

            {{-- 4-Grid Campus Moments --}}
            <div class="gallery-grid">
                <div class="space-y-2 group">
                    <div class="aspect-4/3 rounded-xl border border-slate-200 overflow-hidden bg-slate-100 shadow-2xs">
                        <img src="{{ asset('assets/images/slider-1.jpg') }}"
                             alt="বিদ্যালয় ভবন ও প্রাঙ্গণ"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="text-xs font-bold text-slate-800 text-center">সবুজ ছায়ায় ঘেরা প্রধান ক্যাম্পাস ভবন</div>
                </div>

                <div class="space-y-2 group">
                    <div class="aspect-4/3 rounded-xl border border-slate-200 overflow-hidden bg-slate-100 shadow-2xs">
                        <img src="{{ asset('assets/images/slider-3.jpg') }}"
                             alt="কম্পিউটার ল্যাব"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="text-xs font-bold text-slate-800 text-center">ভবিষ্যতের দক্ষতা অর্জনে আইসিটি ল্যাব</div>
                </div>

                <div class="space-y-2 group">
                    <div class="aspect-4/3 rounded-xl border border-slate-200 overflow-hidden bg-slate-100 shadow-2xs">
                        <img src="{{ asset('assets/images/slider-2.jpg') }}"
                             alt="বার্ষিক ক্রীড়া প্রতিযোগিতা"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="text-xs font-bold text-slate-800 text-center">সুস্থ দেহ ও বন্ধুত্বের প্রতীক: খেলার মাঠ</div>
                </div>

                <div class="space-y-2 group">
                    <div class="aspect-4/3 rounded-xl border border-slate-200 overflow-hidden bg-slate-100 shadow-2xs">
                        <img src="{{ asset('assets/images/news-2.jpg') }}"
                             alt="বিজ্ঞান মেলা ও ল্যাব"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="text-xs font-bold text-slate-800 text-center">কৌতূহলী মনের আবিষ্কার: বিজ্ঞান মেলা</div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION DIVIDER --}}
    <div class="home-section-divider"></div>

    {{-- 9. THE ASK / FINAL CALL TO ACTION --}}
    <section class="home-section bg-white" id="enroll">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header: Rhythm Pattern --}}
            <div class="section-header-block">
                <div class="section-header-left">
                    <span class="section-kicker">
                        <span class="section-kicker-dot"></span>
                        THE FINAL STEP — JOIN OUR FAMILY
                    </span>
                    <h2 class="section-title">
                        ২০২৬ শিক্ষাবর্ষে আপনার সন্তানের মেধার বিকাশে <span class="headline-signature-accent">আমরা প্রস্তুত</span>
                    </h2>
                </div>
                <div class="section-header-right">
                    <p class="section-subtitle">
                        অভিজ্ঞ শিক্ষকমণ্ডলীর স্নেহ ও প্রযুক্তির সমন্বয়ে সন্তানের ভবিষ্যৎ গড়ুন সোনাতলা উচ্চ বিদ্যালয়ে। সীমিত আসনের জন্য আজই যোগাযোগ করুন অথবা সরাসরি অনলাইন আবেদন সম্পন্ন করুন।
                    </p>
                </div>
            </div>

            {{-- High-Conversion Closing Callout Card --}}
            <div class="cta-banner-box">
                <div class="relative z-10 max-w-3xl">
                    <span class="inline-block px-3 py-1 rounded bg-amber-400 text-slate-950 text-xs font-bold uppercase tracking-wider mb-4">
                        ভর্তি চলছে • ২০২৬ শিক্ষাবর্ষ
                    </span>
                    <h3 class="text-2xl sm:text-4xl font-extrabold leading-tight text-white mb-4">
                        আপনার সন্তানের <span class="text-amber-400">সম্ভাবনাময় ভবিষ্যতের</span> সূচনা হোক আজই
                    </h3>
                    <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed mb-8">
                        ৬ষ্ঠ থেকে ১০ম শ্রেণিতে নতুন ভর্তির জন্য আমাদের প্রশাসনিক কার্যালয় সর্বদা প্রস্তুত। যেকোনো তথ্যের জন্য সরাসরি ফোনে কথা বলতে পারেন অথবা ক্যাম্পাস পরিদর্শনের জন্য আবেদন করতে পারেন।
                    </p>

                    <div class="flex flex-wrap items-center gap-4">
                        <a href="{{ route('frontend.contact') }}"
                           class="px-6 py-3.5 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-sm transition-all shadow-md hover:scale-105 active:scale-95 inline-flex items-center gap-2 group">
                            <span>ভর্তি আবেদন ও তথ্যসহায়তা</span>
                            <i class="fa-solid fa-arrow-right-long text-xs transition-transform group-hover:translate-x-1"></i>
                        </a>

                        <a href="https://wa.me/8801712345678" target="_blank" rel="noopener noreferrer"
                           class="px-6 py-3.5 rounded-full bg-white/15 hover:bg-white/25 text-white font-bold text-sm border border-white/30 backdrop-blur-xs transition-all hover:scale-105 active:scale-95 inline-flex items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            <span>WhatsApp হেল্পলাইন</span>
                        </a>

                        <a href="tel:+8801712345678"
                           class="px-6 py-3.5 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white font-bold text-sm border border-slate-700 transition-all inline-flex items-center gap-2 hover:scale-105 active:scale-95">
                            <i class="fa-solid fa-phone text-xs text-slate-400"></i>
                            <span>০১৭১২-৩৪৫৬৭৮</span>
                        </a>
                    </div>

                    {{-- Trust Indicators & Meta Strip --}}
                    <div class="mt-8 pt-6 border-t border-emerald-700/60 flex flex-wrap items-center gap-4 text-xs text-emerald-200 font-medium">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-landmark text-emerald-300"></i> প্রতিষ্ঠা: ১৯৬৮ খ্রি.</span>
                        <span>•</span>
                        <span>EIIN: ১২৫৬৯৩</span>
                        <span>•</span>
                        <span>স্কুল কোড: ৮১০৭</span>
                        <span>•</span>
                        <span>স্বীকৃতিপ্রাপ্ত: রাজশাহী শিক্ষা বোর্ড</span>
                    </div>
                </div>

                {{-- Subtle Watermark / Architectural Emblem Graphic in background --}}
                <div class="absolute -right-10 -bottom-10 w-96 h-96 rounded-full bg-emerald-400/5 pointer-events-none blur-3xl"></div>
            </div>
        </div>
    </section>

@endsection
