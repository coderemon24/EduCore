@extends('layouts.app')

@section('title', 'শ্রেণিসমূহ ও পাঠ্যসূচি | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের বিভিন্ন শ্রেণির শাখা বণ্টন, আসন সংখ্যা, শ্রেণি শিক্ষক ও জাতীয় শিক্ষাক্রম (NCTB) অনুমোদিত পাঠ্যক্রমের পূর্ণাঙ্গ বিবরণ।')

@section('content')

{{-- Breadcrumb --}}
<div class="bg-gray-100 border-b border-gray-200 py-3.5 text-xs sm:text-sm text-gray-600" id="overview">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>প্রচ্ছদ</span>
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-500">একাডেমিক</span>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">শ্রেণিসমূহ ও পাঠ্যসূচি</span>
        </nav>
        <span class="text-gray-500 text-xs sm:text-sm font-medium hidden sm:inline-block">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }} • সাঁথিয়া, পাবনা</span>
    </div>
</div>

{{-- Hero Banner (Matches teachers/index & about/index full-width pattern) --}}
<div class="bg-[#005030] text-white py-14 sm:py-20 border-b border-emerald-900 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6 relative z-10">
        <div class="space-y-3.5 max-w-2xl">
            <span class="hero-anim inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#003820] text-emerald-200 text-xs font-bold uppercase tracking-wider border border-emerald-700/60 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>ACADEMIC CURRICULUM — শ্রেণিভিত্তিক শিক্ষা রূপরেখা</span>
            </span>
            <h1 class="hero-anim text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                শ্রেণিসমূহ ও পাঠ্যসূচি — <span class="text-emerald-300 font-bold">মানসম্মত শিক্ষা ও সুশৃঙ্খল পাঠদান</span>
            </h1>
            <p class="hero-anim text-sm sm:text-base text-emerald-100/90 leading-relaxed mt-2">
                সোনাতলা উচ্চ বিদ্যালয়ের প্রতিটি শ্রেণির শাখা বিভাজন, দায়িত্বপ্রাপ্ত শ্রেণি শিক্ষক, শ্রেণিকক্ষ, আসন বিন্যাস ও জাতীয় শিক্ষাক্রম (NCTB) অনুমোদিত পাঠ্যক্রমের পূর্ণাঙ্গ রূপরেখা।
            </p>
        </div>

        <div class="hero-anim shrink-0 flex items-center gap-4 bg-[#003820]/90 backdrop-blur-xs border border-emerald-700/70 px-6 py-4 rounded-2xl text-white shadow-md">
            <div class="w-13 h-13 rounded-xl bg-emerald-800/80 border border-emerald-600/50 text-emerald-200 flex flex-col items-center justify-center font-bold shrink-0 shadow-inner">
                <span class="text-[10px] sm:text-[11px] uppercase tracking-wider font-extrabold -mb-0.5">ESTD</span>
                <span class="text-base sm:text-lg font-extrabold font-mono text-white">{{ $school->established_year ?? '1968' }}</span>
            </div>
            <div>
                <p class="text-emerald-300/90 font-medium text-xs">জাতীয় শিক্ষাক্রম মানদণ্ড</p>
                <p class="text-white font-bold text-sm sm:text-base">শিক্ষাবর্ষ: ২০২৬ খ্রিষ্টাব্দ</p>
            </div>
        </div>
    </div>

    <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-emerald-800/20 pointer-events-none"></div>
    <div class="absolute left-1/2 -top-24 w-96 h-96 rounded-full bg-emerald-700/10 pointer-events-none"></div>
</div>

{{-- Credentials & Stats Telemetry Strip --}}
<div class="telemetry-strip bg-slate-900 text-slate-200 py-4 sm:py-5 border-y border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="telemetry-metric-item flex items-center gap-3">
                <span class="telemetry-icon-box w-10 h-10 rounded-xl bg-slate-800 text-emerald-400 flex items-center justify-center text-base shrink-0 border border-slate-700 shadow-2xs">
                    <i class="fa-solid fa-school"></i>
                </span>
                <div>
                    <span class="text-slate-400 block text-xs font-medium">সক্রিয় শ্রেণি স্তর</span>
                    <span class="text-white font-bold text-sm sm:text-base">{{ $telemetry['total_classes'] ?? $classes->count() }}টি শ্রেণি স্তর</span>
                </div>
            </div>
            <div class="telemetry-metric-item flex items-center gap-3">
                <span class="telemetry-icon-box w-10 h-10 rounded-xl bg-slate-800 text-emerald-400 flex items-center justify-center text-base shrink-0 border border-slate-700 shadow-2xs">
                    <i class="fa-solid fa-layer-group"></i>
                </span>
                <div>
                    <span class="text-slate-400 block text-xs font-medium">মোট শাখা বিভাজন</span>
                    <span class="text-white font-bold text-sm sm:text-base">{{ $telemetry['total_sections'] ?? 0 }}টি প্রভাতী ও দিবা শাখা</span>
                </div>
            </div>
            <div class="telemetry-metric-item flex items-center gap-3">
                <span class="telemetry-icon-box w-10 h-10 rounded-xl bg-slate-800 text-emerald-400 flex items-center justify-center text-base shrink-0 border border-slate-700 shadow-2xs">
                    <i class="fa-solid fa-book-open"></i>
                </span>
                <div>
                    <span class="text-slate-400 block text-xs font-medium">নির্ধারিত পাঠ্য বিষয়</span>
                    <span class="text-white font-bold text-sm sm:text-base">{{ $telemetry['total_subjects'] ?? 0 }}টি আবশ্যিক ও ঐচ্ছিক</span>
                </div>
            </div>
            <div class="telemetry-metric-item flex items-center gap-3">
                <span class="telemetry-icon-box w-10 h-10 rounded-xl bg-slate-800 text-emerald-400 flex items-center justify-center text-base shrink-0 border border-slate-700 shadow-2xs">
                    <i class="fa-solid fa-certificate"></i>
                </span>
                <div>
                    <span class="text-slate-400 block text-xs font-medium">শিক্ষা বোর্ড স্বীকৃতি</span>
                    <span class="text-white font-bold text-sm sm:text-base">রাজশাহী বোর্ড • EIIN: ১২৫৬৯৩</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Interactive Classes Substance Section --}}
<section class="home-section bg-slate-50/70"
         id="classes-directory"
         x-data="{
            activeClass: '{{ $selectedClassId ?? ($classes->first()?->id ?? '') }}',
            setActive(id) {
                this.activeClass = id;
                if (history.pushState) {
                    history.replaceState(null, null, '#class-' + id);
                }
            },
            init() {
                var hash = window.location.hash;
                if (hash && hash.startsWith('#class-')) {
                    var hashId = hash.replace('#class-', '');
                    if (hashId) { this.activeClass = hashId; }
                }
            }
         }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 sm:space-y-12">

        {{-- Standard Institutional Section Header --}}
        <div class="section-header-block">
            <div class="section-header-left">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    ACADEMIC ARCHITECTURE — প্রাতিষ্ঠানিক শ্রেণি কাঠামো
                </span>
                <h2 class="section-title">
                    শ্রেণিভিত্তিক বিবরণ ও <span class="text-[#005030] font-bold">পূর্ণাঙ্গ পাঠ্যক্রম</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    নিচের তালিকা থেকে যেকোনো শ্রেণি নির্বাচন করুন। নির্বাচিত শ্রেণির শাখা বিভাজন, দায়িত্বপ্রাপ্ত শ্রেণি শিক্ষক, শ্রেণিকক্ষ, আসন সংখ্যা এবং জাতীয় শিক্ষাক্রম (NCTB) অনুমোদিত পাঠ্যসূচি তাৎক্ষণিক প্রদর্শিত হবে।
                </p>
            </div>
        </div>

        @if($classes->isEmpty())
            {{-- Clean Educational Empty State --}}
            <div class="bg-white border border-slate-200/90 rounded-3xl p-12 sm:p-16 text-center shadow-2xs space-y-5 max-w-2xl mx-auto">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-50 text-[#005030] flex items-center justify-center text-3xl border border-emerald-200/80 shadow-2xs">
                    <i class="fa-solid fa-school"></i>
                </div>
                <div class="space-y-2">
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900">বর্তমানে কোনো শ্রেণির তথ্য উপলব্ধ নেই</h3>
                    <p class="text-sm text-slate-600 leading-relaxed max-w-md mx-auto">
                        আমাদের প্রাতিষ্ঠানিক শ্রেণি ও পাঠ্যসূচি তালিকা হালনাগাদ করা হচ্ছে। বিস্তারিত তথ্যের জন্য বিদ্যালয় কার্যালয়ে সরাসরি যোগাযোগ করুন।
                    </p>
                </div>
                <div class="pt-3">
                    <a href="{{ route('frontend.contact') }}" class="about-cta-btn about-cta-btn-primary text-xs sm:text-sm">
                        <i class="fa-solid fa-envelope"></i>
                        <span>কার্যালয়ে যোগাযোগ করুন</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        @else
            {{-- Tactile Class Selector Navigation Strip --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-2xs space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs border border-emerald-200/60">
                            <i class="fa-solid fa-layer-group"></i>
                        </span>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">শ্রেণি নির্বাচন করুন</h3>
                    </div>
                    <div class="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-pointer text-slate-400"></i>
                        <span>যেকোনো শ্রেণিতে ক্লিক করে শাখা বিন্যাস ও বিষয়তালিকা দেখুন</span>
                    </div>
                </div>

                {{-- Responsive Class Pills Container --}}
                <div class="flex items-center gap-3 sm:gap-4 overflow-x-auto pb-2 sm:flex-wrap">
                    @foreach($classes as $c)
                        <button type="button"
                                @click="setActive('{{ $c->id }}')"
                                :class="activeClass == '{{ $c->id }}'
                                    ? 'bg-[#005030] text-white border-[#005030] shadow-sm font-bold scale-[1.02]'
                                    : 'bg-slate-50/80 text-slate-700 border-slate-200/90 hover:bg-white hover:border-[#005030]/40 hover:text-[#005030] font-medium'"
                                class="group relative flex-shrink-0 px-5 py-3.5 rounded-xl border text-xs transition-all duration-200 cursor-pointer text-left hover:-translate-y-0.5">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-graduation-cap text-xs"
                                   :class="activeClass == '{{ $c->id }}' ? 'text-amber-400' : 'text-slate-400 group-hover:text-[#005030]'"></i>
                                <span class="text-sm font-bold tracking-tight">{{ $c->name }}</span>
                            </div>
                            <div class="text-[11px] mt-1.5 opacity-85"
                                 :class="activeClass == '{{ $c->id }}' ? 'text-emerald-100' : 'text-slate-500'">
                                {{ $c->sections->count() }}টি শাখা · {{ $c->subjects->count() }}টি বিষয়
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Class Detail Showcase Panels --}}
            @foreach($classes as $c)
                @php
                    $sectionsCount = $c->sections->count();
                    $subjectsCount = $c->subjects->count();
                    $optionalCount = $c->subjects->where('pivot.type', 'optional')->count();
                    $isSecondaryClass = in_array($c->numeric_code, [9, 10]) || str_contains($c->name, '৯ম') || str_contains($c->name, '১০ম') || str_contains(strtolower($c->name), 'nine') || str_contains(strtolower($c->name), 'ten');
                @endphp

                <div x-show="activeClass == '{{ $c->id }}'"
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-8">

                    {{-- Flagship Class Banner Card --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="flex items-start sm:items-center gap-4 sm:gap-5">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-emerald-50 text-[#005030] flex items-center justify-center text-2xl border-2 border-emerald-200/80 shadow-2xs shrink-0">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                        {{ $c->name }}
                                    </h3>
                                    @if($c->numeric_code)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-semibold bg-slate-100 text-slate-700 border border-slate-200/80">
                                            কোড: {{ $c->numeric_code }}
                                        </span>
                                    @endif
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-[#005030] border border-emerald-200/80">
                                        নিয়মিত শ্রেণি
                                    </span>
                                </div>

                                <div class="flex items-center gap-3.5 text-xs sm:text-sm text-slate-600 flex-wrap pt-0.5">
                                    <span class="inline-flex items-center gap-1.5 font-medium">
                                        <i class="fa-solid fa-layer-group text-[#005030]"></i>
                                        <strong>{{ $sectionsCount }}</strong>টি সক্রিয় শাখা
                                    </span>
                                    <span class="text-slate-300">·</span>
                                    <span class="inline-flex items-center gap-1.5 font-medium">
                                        <i class="fa-solid fa-book-open text-[#005030]"></i>
                                        <strong>{{ $subjectsCount }}</strong>টি নির্ধারিত বিষয়
                                    </span>
                                    @if($optionalCount > 0)
                                        <span class="text-slate-300">·</span>
                                        <span class="inline-flex items-center gap-1.5 text-amber-700 font-semibold">
                                            <i class="fa-solid fa-circle-check text-amber-500"></i>
                                            <strong>{{ $optionalCount }}</strong>টি ঐচ্ছিক বিষয়
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Action Controls & Stream Tags --}}
                        <div class="flex items-center gap-3 flex-wrap">
                            @if($isSecondaryClass)
                                {{-- Secondary Streams for Secondary Classes --}}
                                <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-200/90 rounded-xl p-1.5 shadow-2xs">
                                    <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider px-1.5">শাখা বিভাগ:</span>
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-flask text-[10px]"></i> বিজ্ঞান
                                    </span>
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-scale-balanced text-[10px]"></i> মানবিক
                                    </span>
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-briefcase text-[10px]"></i> ব্যবসায় শিক্ষা
                                    </span>
                                </div>
                            @endif

                            {{-- Class Routine Shortcut --}}
                            <a href="{{ route('frontend.academic.routine') }}?class_id={{ $c->id }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-[#005030] text-[#005030] hover:text-white text-xs font-bold border border-emerald-200 hover:border-[#005030] shadow-2xs transition-all duration-200 hover:-translate-y-0.5">
                                <i class="fa-solid fa-calendar-days text-amber-500"></i>
                                <span>ক্লাস রুটিন দেখুন</span>
                                <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Detail Grid: Sections (Left) & Subjects Table (Right) with proper gap --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                        {{-- Left Column: Sections & Appointed Teachers (5 Cols) --}}
                        <div class="lg:col-span-5 bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-2xs space-y-5">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center text-sm border border-emerald-200/60">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-base text-slate-900">শাখা ও শ্রেণি শিক্ষক</h4>
                                        <div class="text-[11px] text-slate-400 font-medium">Class Sections & Mentorship</div>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200/80">
                                    {{ $sectionsCount }}টি শাখা
                                </span>
                            </div>

                            @if($c->sections->isEmpty())
                                <div class="p-8 text-center text-xs text-slate-500 bg-slate-50/80 rounded-xl border border-dashed border-slate-200 space-y-1.5">
                                    <i class="fa-solid fa-circle-info text-slate-400 text-xl block mb-1"></i>
                                    <p class="font-bold text-slate-700 text-sm">এই শ্রেণিতে বর্তমানে কোনো শাখা অন্তর্ভুক্ত করা হয়নি।</p>
                                    <p class="text-[11px] text-slate-500">বিদ্যালয় প্রশাসন কর্তৃক শাখা বিন্যাস প্রক্রিয়া সম্পন্ন হচ্ছে।</p>
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($c->sections as $section)
                                        <div class="p-5 rounded-xl border border-slate-200/80 bg-slate-50/60 hover:bg-white hover:border-emerald-300 transition-all duration-200 shadow-2xs hover:shadow-xs space-y-4 group">
                                            <div class="flex items-center justify-between gap-3">
                                                <div class="flex items-center gap-3">
                                                    <span class="w-10 h-10 rounded-xl bg-[#005030] text-white flex items-center justify-center font-bold text-sm shadow-2xs group-hover:scale-105 transition-transform">
                                                        {{ $section->name }}
                                                    </span>
                                                    <div>
                                                        <div class="font-bold text-sm text-slate-900">
                                                            শাখা: {{ $section->name }}
                                                        </div>
                                                        <div class="text-[11px] text-slate-500 font-medium">
                                                            নিয়মিত পাঠদান ও নিবিড় পর্যবেক্ষণ
                                                        </div>
                                                    </div>
                                                </div>

                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-[#005030] border border-emerald-200">
                                                    সক্রিয়
                                                </span>
                                            </div>

                                            {{-- Teacher Profile Box --}}
                                            <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 flex items-center gap-3.5 shadow-2xs">
                                                <div class="w-11 h-11 rounded-full bg-slate-100 text-[#005030] flex items-center justify-center text-base shrink-0 border border-slate-200">
                                                    <i class="fa-solid fa-chalkboard-user"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                                        দায়িত্বপ্রাপ্ত শ্রেণি শিক্ষক
                                                    </div>
                                                    <div class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                                        @if($section->classTeacher)
                                                            {{ $section->classTeacher->full_name ?? $section->classTeacher->name }}
                                                        @else
                                                            <span class="text-slate-500 italic font-normal">অফিস তত্ত্বাবধানে পরিচালিত</span>
                                                        @endif
                                                    </div>
                                                    @if($section->classTeacher && !empty($section->classTeacher->designation))
                                                        <div class="text-[11px] text-[#005030] font-medium truncate pt-0.5">
                                                            {{ $section->classTeacher->designation }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Section Telemetry: Room & Seat Capacity --}}
                                            <div class="grid grid-cols-2 gap-3 text-xs text-slate-600 pt-3 border-t border-slate-200/70 font-medium">
                                                <div class="flex items-center gap-2 text-xs">
                                                    <i class="fa-solid fa-door-open text-slate-400"></i>
                                                    <span>কক্ষ: <strong class="text-slate-800">{{ $section->room_number ?? 'নির্ধারিত' }}</strong></span>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs justify-end">
                                                    <i class="fa-solid fa-user-group text-slate-400"></i>
                                                    <span>আসন: <strong class="text-slate-800">{{ $section->capacity ?? '৫০' }}</strong> জন</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Right Column: Curriculum & Subject Mastery (7 Cols) --}}
                        <div class="lg:col-span-7 bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-2xs space-y-5">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center text-sm border border-emerald-200/60">
                                        <i class="fa-solid fa-book-open"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-base text-slate-900">পাঠ্যক্রম ও নির্ধারিত বিষয়সমূহ</h4>
                                        <div class="text-[11px] text-slate-400 font-medium">NCTB Approved Curriculum & Marks Distribution</div>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200/80">
                                    {{ $subjectsCount }}টি বিষয়
                                </span>
                            </div>

                            @if($c->subjects->isEmpty())
                                <div class="p-10 text-center text-xs text-slate-500 bg-slate-50/80 rounded-xl border border-dashed border-slate-200 space-y-2">
                                    <i class="fa-solid fa-book-open text-slate-400 text-2xl block"></i>
                                    <div class="font-bold text-slate-700 text-sm">এই শ্রেণির জন্য এখনো কোনো বিষয় বরাদ্দ করা হয়নি।</div>
                                    <div class="text-slate-500 text-xs">জাতীয় শিক্ষাক্রম অনুযায়ী বিষয় বণ্টন প্রক্রিয়া সম্পন্ন হচ্ছে।</div>
                                </div>
                            @else
                                <div class="overflow-x-auto border border-slate-200/90 rounded-xl shadow-2xs">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200/90">
                                            <tr>
                                                <th class="px-4 py-3.5 text-slate-900 font-bold">বিষয়ের নাম ও শিরোনাম</th>
                                                <th class="px-4 py-3.5 text-center">কোড</th>
                                                <th class="px-4 py-3.5 text-center">পূর্ণমান</th>
                                                <th class="px-4 py-3.5 text-center">পাস নম্বর</th>
                                                <th class="px-4 py-3.5 text-right">ধরন</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 text-slate-800">
                                            @foreach($c->subjects as $subject)
                                                @php
                                                    $isOptional = ($subject->pivot->type ?? '') === 'optional';
                                                @endphp
                                                <tr class="hover:bg-slate-50/80 transition-colors">
                                                    <td class="px-4 py-3.5 font-medium text-slate-900">
                                                        <div class="flex items-center gap-2.5">
                                                            <i class="fa-solid fa-book-bookmark text-slate-400 text-xs"></i>
                                                            <span class="font-bold text-sm tracking-tight text-slate-900">{{ $subject->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3.5 text-center font-mono text-slate-600 text-xs">
                                                        {{ $subject->code ?? '—' }}
                                                    </td>
                                                    <td class="px-4 py-3.5 text-center font-mono font-bold text-slate-900 text-xs">
                                                        {{ $subject->pivot->full_marks ?? 100 }}
                                                    </td>
                                                    <td class="px-4 py-3.5 text-center font-mono text-slate-600 text-xs">
                                                        {{ $subject->pivot->pass_marks ?? 33 }}
                                                    </td>
                                                    <td class="px-4 py-3.5 text-right">
                                                        @if($isOptional)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-300">
                                                                ঐচ্ছিক বিষয়
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                                আবশ্যিক বিষয়
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            {{-- Academic Curriculum Notice Note --}}
                            <div class="bg-amber-50/70 border border-amber-200/80 rounded-xl p-4 text-xs text-amber-900 flex items-start gap-3.5 leading-relaxed">
                                <i class="fa-solid fa-circle-info text-amber-600 mt-0.5 shrink-0 text-sm"></i>
                                <div>
                                    জাতীয় শিক্ষাক্রম ও পাঠ্যপুস্তক বোর্ড (NCTB) কর্তৃক অনুমোদিত নিয়মিত সিলেবাস অনুযায়ী পরীক্ষা ও মূল্যায়ন সম্পন্ন হয়। বার্ষিক পরীক্ষার নম্বর বণ্টন বা বিস্তারিত পাঠ্যসূচির জন্য নোটিশ বোর্ড বা শ্রেণি শিক্ষকের কার্যালয়ে যোগাযোগ করুন।
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>

{{-- Hairline Structural Divider --}}
<div class="home-section-divider"></div>

{{-- Consultation & Admission CTA (Uses standardized .cta-banner-box .cta-box tokens) --}}
<section class="home-section bg-slate-50/80" id="admission-cta">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="cta-banner-box cta-box rounded-3xl p-8 sm:p-12 lg:p-16 relative overflow-hidden shadow-lg border border-emerald-700/60">
            <div class="relative z-10 max-w-4xl space-y-4">
                <span class="guardian-kicker-pill">
                    <i class="fa-solid fa-user-graduate text-[11px] text-emerald-400"></i>
                    <span>ADMISSION & GUIDANCE — শ্রেণিভিত্তিক ভর্তি ও তথ্য সেবা</span>
                </span>
                <h2 class="cta-banner-title text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    সন্তানের ভর্তি অথবা শ্রেণিভিত্তিক তথ্য নিয়ে <span class="text-amber-400 font-bold">সরাসরি কথা বলুন</span>
                </h2>
                <p class="cta-banner-desc text-sm sm:text-base leading-relaxed">
                    সোনাতলা উচ্চ বিদ্যালয়ের নির্দিষ্ট শ্রেণিতে আসন প্রাপ্যতা, ভর্তি প্রক্রিয়া, পাঠ্যক্রম অথবা শ্রেণি শিক্ষকের সাথে সাক্ষাৎ সংক্রান্ত যেকোনো তথ্যের জন্য সরাসরি বিদ্যালয় কার্যালয়ে যোগাযোগ করুন।
                </p>

                <div class="pt-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    <a href="{{ route('frontend.contact') }}#enroll"
                       class="about-cta-btn about-cta-btn-primary text-sm sm:text-base">
                        <i class="fa-solid fa-file-signature text-slate-950"></i>
                        <span>ভর্তি আবেদন ও তথ্য অনুসন্ধান</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>

                    <a href="{{ route('frontend.academic.routine') }}"
                       class="about-cta-btn guardian-cta-btn-secondary text-sm sm:text-base">
                        <i class="fa-solid fa-calendar-days text-amber-300"></i>
                        <span>ক্লাস রুটিন দেখুন</span>
                    </a>
                </div>
            </div>

            <div class="hidden lg:block absolute -right-12 -bottom-12 w-96 h-96 rounded-full bg-emerald-800/30 border border-emerald-600/30 pointer-events-none"></div>
        </div>
    </div>
</section>
@endsection
