@extends('layouts.app')

@section('title', 'শ্রেণিসমূহ ও পাঠ্যসূচি | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের বিভিন্ন শ্রেণির শাখা বণ্টন, আসন সংখ্যা, শ্রেণি শিক্ষক ও জাতীয় শিক্ষাক্রম (NCTB) অনুমোদিত পাঠ্যক্রমের পূর্ণাঙ্গ বিবরণ।')

@section('content')
{{-- Breadcrumbs & Top Context Meta --}}
<div class="bg-gray-100 border-b border-gray-200 py-3 text-xs text-gray-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div class="flex items-center gap-1.5 font-medium">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors">প্রচ্ছদ</a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-400">একাডেমিক</span>
            <span class="text-gray-300">/</span>
            <span class="text-gray-900 font-bold">শ্রেণিসমূহ ও পাঠ্যসূচি</span>
        </div>
        <div class="hidden sm:flex items-center gap-2 text-gray-500 font-medium">
            <i class="fa-solid fa-calendar-check text-[#005030]"></i>
            <span>শিক্ষাবর্ষ: ২০২৬ খ্রি.</span>
        </div>
    </div>
</div>

{{-- Top Hero / Narrative Argument Header --}}
<section class="bg-white border-b border-slate-200/80 pt-10 pb-8 sm:pt-14 sm:pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header Rhythm Block (Recipe 3 & Recipe 1) --}}
        <div class="section-header-block mb-8 sm:mb-10">
            <div class="section-header-left">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    ACADEMIC ARCHITECTURE — প্রাতিষ্ঠানিক শ্রেণি কাঠামো
                </span>
                <h1 class="section-title">
                    শ্রেণিসমূহ ও পাঠ্যসূচি — <span class="text-[#005030] font-bold">মানসম্মত শিক্ষাক্রম ও সুশৃঙ্খল পাঠদান</span>
                </h1>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    সোনাতলা উচ্চ বিদ্যালয়ের প্রতিটি শ্রেণির শাখা বিভাজন, দায়িত্বপ্রাপ্ত শ্রেণি শিক্ষক, শ্রেণিকক্ষ, আসন বিন্যাস ও জাতীয় শিক্ষাক্রম (NCTB) অনুমোদিত পাঠ্যক্রমের পূর্ণাঙ্গ রূপরেখা।
                </p>
            </div>
        </div>

        {{-- Asymmetric 65/35 Hero & Telemetry Container (Recipe 2 & Recipe 4) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            {{-- Left Dominant Column (65% / 8 Cols) --}}
            <div class="lg:col-span-8 bg-[#005030] text-white p-6 sm:p-8 rounded-2xl shadow-xs border border-emerald-900/40 relative overflow-hidden flex flex-col justify-between">
                {{-- Decorative Watermark Icon --}}
                <div class="absolute -right-6 -bottom-8 text-emerald-800/25 text-9xl pointer-events-none select-none">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>

                <div class="relative z-10 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-900/80 text-emerald-200 text-xs font-semibold border border-emerald-700/60 shadow-2xs">
                        <i class="fa-solid fa-school text-amber-400"></i>
                        <span>জাতীয় শিক্ষাক্রম ও পাঠ্যপুস্তক বোর্ড (NCTB) নির্দেশিত পাঠদান</span>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-extrabold text-white leading-snug">
                        দক্ষ শিক্ষক ও সুবিন্যস্ত শ্রেণিকক্ষে মেধা বিকাশের পূর্ণাঙ্গ নিশ্চয়তা
                    </h2>

                    <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed font-normal max-w-2xl">
                        আমাদের বিদ্যালয়ে ৬ষ্ঠ থেকে ১০ম শ্রেণি পর্যন্ত নিয়মিত প্রভাতী ও দিবা শাখায় অভিজ্ঞ শিক্ষকমণ্ডলীর তত্ত্বাবধানে পাঠদান পরিচালিত হয়। মাধ্যমিক স্তরে (৯ম-১০ম শ্রেণি) বিজ্ঞান, মানবিক ও ব্যবসায় শিক্ষা শাখার শিক্ষার্থীদের জন্য রয়েছে অত্যাধুনিক ল্যাবরেটরি ও বিশেষায়িত প্রস্তুতি।
                    </p>
                </div>

                <div class="relative z-10 pt-6 mt-6 border-t border-emerald-800/60 flex items-center justify-between flex-wrap gap-4 text-xs">
                    <div class="flex items-center gap-4 text-emerald-200">
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-halved text-amber-400"></i>
                            <span>নিরাপদ ও সুশৃঙ্খল পরিবেশ</span>
                        </span>
                        <span class="hidden sm:inline text-emerald-600">·</span>
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-flask-vial text-emerald-300"></i>
                            <span>ব্যবহারিক বিজ্ঞান ল্যাব</span>
                        </span>
                    </div>

                    <a href="{{ route('frontend.academic.routine') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-emerald-900 hover:bg-emerald-800 text-white font-bold text-xs border border-emerald-700 transition-colors shadow-2xs">
                        <i class="fa-solid fa-calendar-days text-amber-400"></i>
                        <span>শ্রেণি রুটিন দেখুন</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            {{-- Right Supporting Column: 3 Hallmark Telemetry Counters (35% / 4 Cols) --}}
            <div class="lg:col-span-4 bg-slate-50/90 border border-slate-200/90 rounded-2xl p-6 flex flex-col justify-between shadow-2xs">
                <div class="space-y-4">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500 pb-2 border-b border-slate-200/80 flex items-center justify-between">
                        <span>একাডেমিক সারসংক্ষেপ</span>
                        <span class="w-2 h-2 rounded-full bg-[#005030]"></span>
                    </div>

                    {{-- 3 Telemetry Cards --}}
                    <div class="space-y-3">
                        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 flex items-center justify-between shadow-2xs hover:border-[#005030]/30 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center text-sm shrink-0 border border-emerald-200/60">
                                    <i class="fa-solid fa-school"></i>
                                </span>
                                <div>
                                    <span class="text-slate-500 block text-xs font-medium">সক্রিয় শ্রেণি স্তর</span>
                                    <span class="text-slate-900 font-bold text-sm">৬ষ্ঠ থেকে ১০ম শ্রেণি</span>
                                </div>
                            </div>
                            <span class="text-2xl font-black text-[#005030] font-mono">
                                {{ $telemetry['total_classes'] ?? $classes->count() }}
                            </span>
                        </div>

                        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 flex items-center justify-between shadow-2xs hover:border-[#005030]/30 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center text-sm shrink-0 border border-amber-200/60">
                                    <i class="fa-solid fa-layer-group"></i>
                                </span>
                                <div>
                                    <span class="text-slate-500 block text-xs font-medium">মোট শাখা বিভাজন</span>
                                    <span class="text-slate-900 font-bold text-sm">প্রভাতী ও দিবা শাখা</span>
                                </div>
                            </div>
                            <span class="text-2xl font-black text-amber-700 font-mono">
                                {{ $telemetry['total_sections'] ?? 0 }}
                            </span>
                        </div>

                        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 flex items-center justify-between shadow-2xs hover:border-[#005030]/30 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-sm shrink-0 border border-blue-200/60">
                                    <i class="fa-solid fa-book-open"></i>
                                </span>
                                <div>
                                    <span class="text-slate-500 block text-xs font-medium">নির্ধারিত পাঠ্য বিষয়</span>
                                    <span class="text-slate-900 font-bold text-sm">আবশ্যিক ও ঐচ্ছিক</span>
                                </div>
                            </div>
                            <span class="text-2xl font-black text-blue-700 font-mono">
                                {{ $telemetry['total_subjects'] ?? 0 }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Quiet bottom metadata strip --}}
                <div class="pt-4 border-t border-slate-200/80 text-[11px] text-slate-500 flex items-center justify-between">
                    <span>জাতীয় শিক্ষাক্রম মানদণ্ড</span>
                    <span class="text-[#005030] font-semibold">সেশন ২০২৬</span>
                </div>
            </div>
        </div>

        {{-- Floating Anchor: Official Accreditation Credentials Bar (Recipe 2 & Recipe 7) --}}
        <div class="relative -mb-6 sm:-mb-8 mt-6 max-w-5xl mx-auto z-20">
            <div class="bg-white border border-slate-200/90 rounded-xl p-3.5 sm:p-4 shadow-sm flex items-center justify-between flex-wrap gap-4 text-xs text-slate-600">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 text-[#005030] flex items-center justify-center text-xs font-bold shrink-0">
                        <i class="fa-solid fa-certificate"></i>
                    </span>
                    <div>
                        <div class="font-bold text-slate-900 text-xs sm:text-sm">সরকারি স্বীকৃতি ও বোর্ড অধিভুক্তি</div>
                        <div class="text-slate-500 text-[11px]">মাধ্যমিক ও উচ্চ মাধ্যমিক শিক্ষা বোর্ড, রাজশাহী কর্তৃক অনুমোদিত</div>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-[11px] text-slate-500 font-medium">
                    <span>স্থাপিত: <strong class="text-slate-800">১৯৬৮ খ্রি.</strong></span>
                    <span class="text-slate-300">|</span>
                    <span>EIIN: <strong class="text-slate-800">১২৫৬৯৩</strong></span>
                    <span class="text-slate-300">|</span>
                    <span>বিদ্যালয় কোড: <strong class="text-slate-800">৮১০৭</strong></span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Hairline Structural Divider (Recipe 7) --}}
<div class="home-section-divider"></div>

{{-- Main Interactive Classes Substance Section (Recipe 3, 4, 5) --}}
<section class="py-12 sm:py-16 bg-slate-50/60"
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        @if($classes->isEmpty())
            {{-- Clean Educational Empty State --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-12 text-center shadow-2xs space-y-4 max-w-2xl mx-auto">
                <div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 text-[#005030] flex items-center justify-center text-2xl border border-emerald-200">
                    <i class="fa-solid fa-school"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-xl font-bold text-slate-900">বর্তমানে কোনো শ্রেণির তথ্য উপলব্ধ নেই</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        আমাদের প্রাতিষ্ঠানিক শ্রেণি ও পাঠ্যসূচি তালিকা হালনাগাদ করা হচ্ছে। বিস্তারিত তথ্যের জন্য বিদ্যালয় কার্যালয়ে সরাসরি যোগাযোগ করুন।
                    </p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('frontend.contact') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs shadow-xs transition-all hover:scale-105 active:scale-95">
                        <i class="fa-solid fa-envelope"></i>
                        <span>কার্যালয়ে যোগাযোগ করুন</span>
                    </a>
                </div>
            </div>
        @else
            {{-- Tactile Class Selector Navigation Strip (Recipe 4: Winner Elevation) --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-2xs space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="section-kicker !mb-0 text-[10px]">
                            <span class="section-kicker-dot"></span>
                            SELECT GRADE
                        </span>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">শ্রেণি নির্বাচন করুন</h2>
                    </div>
                    <div class="text-xs text-slate-500 font-medium">
                        যেকোনো শ্রেণিতে ক্লিক করে তাৎক্ষণিক শাখা বিন্যাস ও পাঠ্যক্রম দেখুন
                    </div>
                </div>

                {{-- Responsive Class Pills Container --}}
                <div class="flex items-center gap-3 overflow-x-auto pb-2 sm:flex-wrap">
                    @foreach($classes as $c)
                        <button type="button"
                                @click="setActive('{{ $c->id }}')"
                                :class="activeClass == '{{ $c->id }}'
                                    ? 'bg-[#005030] text-white border-[#005030] shadow-sm font-bold scale-[1.02]'
                                    : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100 hover:border-slate-300 font-medium'"
                                class="group relative flex-shrink-0 px-4 py-3 rounded-xl border text-xs transition-all duration-200 cursor-pointer text-left hover:-translate-y-0.5">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-graduation-cap text-xs"
                                   :class="activeClass == '{{ $c->id }}' ? 'text-amber-400' : 'text-slate-400 group-hover:text-slate-600'"></i>
                                <span class="text-sm font-bold tracking-tight">{{ $c->name }}</span>
                            </div>
                            <div class="text-[11px] mt-1 opacity-80"
                                 :class="activeClass == '{{ $c->id }}' ? 'text-emerald-100' : 'text-slate-500'">
                                {{ $c->sections->count() }}টি শাখা · {{ $c->subjects->count() }}টি বিষয়
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Class Detail Showcase Panels (Recipe 3, 4, 5) --}}
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
                     class="space-y-6">

                    {{-- Flagship Class Banner Card --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 shadow-2xs flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                        <div class="flex items-start sm:items-center gap-4 sm:gap-5">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-[#005030] flex items-center justify-center text-2xl border-2 border-emerald-200/80 shadow-xs shrink-0">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                        {{ $c->name }}
                                    </h3>
                                    @if($c->numeric_code)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                            কোড: {{ $c->numeric_code }}
                                        </span>
                                    @endif
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        নিয়মিত শ্রেণি
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 text-xs sm:text-sm text-slate-600 flex-wrap pt-0.5">
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
                                    <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider px-1">বিভাগ:</span>
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
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-50 hover:bg-sky-100 text-sky-800 text-xs font-bold border border-sky-200 shadow-2xs transition-all hover:-translate-y-0.5">
                                <i class="fa-solid fa-calendar-days text-sky-600"></i>
                                <span>ক্লাস রুটিন দেখুন</span>
                                <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>

                    {{-- 60/40 Asymmetric Detail Grid (Recipe 2: Break the Symmetry) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        {{-- Left Column: Sections & Appointed Teachers (40% / 5 Cols) --}}
                        <div class="lg:col-span-5 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-sm">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-base text-slate-900">শাখা ও শ্রেণি শিক্ষক</h4>
                                        <div class="text-[11px] text-slate-400">Class Sections & Mentorship</div>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                    {{ $sectionsCount }}টি শাখা
                                </span>
                            </div>

                            @if($c->sections->isEmpty())
                                <div class="p-8 text-center text-xs text-slate-500 bg-slate-50 rounded-xl border border-dashed border-slate-200 space-y-1">
                                    <i class="fa-solid fa-circle-info text-slate-400 text-lg block mb-1"></i>
                                    <p class="font-medium text-slate-700">এই শ্রেণিতে বর্তমানে কোনো শাখা অন্তর্ভুক্ত করা হয়নি।</p>
                                    <p class="text-[11px] text-slate-400">বিদ্যালয় প্রশাসন কর্তৃক শাখা বিন্যাস প্রক্রিয়াধীন রয়েছে।</p>
                                </div>
                            @else
                                <div class="space-y-3.5">
                                    @foreach($c->sections as $section)
                                        <div class="p-4 rounded-xl border border-slate-200/80 bg-slate-50/60 hover:bg-white hover:border-emerald-300 transition-all duration-200 shadow-2xs hover:shadow-xs space-y-3.5 group">
                                            <div class="flex items-center justify-between gap-2">
                                                <div class="flex items-center gap-2.5">
                                                    <span class="w-9 h-9 rounded-xl bg-[#005030] text-white flex items-center justify-center font-bold text-sm shadow-2xs group-hover:scale-105 transition-transform">
                                                        {{ $section->name }}
                                                    </span>
                                                    <div>
                                                        <div class="font-bold text-sm text-slate-900">
                                                            শাখা: {{ $section->name }}
                                                        </div>
                                                        <div class="text-[11px] text-slate-500">
                                                            নিয়মিত শ্রেণি পাঠদান
                                                        </div>
                                                    </div>
                                                </div>

                                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-[#005030] border border-emerald-200">
                                                    সক্রিয়
                                                </span>
                                            </div>

                                            {{-- Teacher Profile Box --}}
                                            <div class="bg-white p-3 rounded-xl border border-slate-200/70 flex items-center gap-3 shadow-2xs">
                                                <div class="w-10 h-10 rounded-full bg-slate-100 text-[#005030] flex items-center justify-center text-sm shrink-0 border border-slate-200">
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
                                                        <div class="text-[11px] text-[#005030] font-medium truncate">
                                                            {{ $section->classTeacher->designation }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Section Telemetry: Room & Seat Capacity --}}
                                            <div class="grid grid-cols-2 gap-2 text-xs text-slate-600 pt-2 border-t border-slate-200/60 font-medium">
                                                <div class="flex items-center gap-1.5 text-[11px]">
                                                    <i class="fa-solid fa-door-open text-slate-400"></i>
                                                    <span>কক্ষ: <strong class="text-slate-800">{{ $section->room_number ?? 'নির্ধারিত' }}</strong></span>
                                                </div>
                                                <div class="flex items-center gap-1.5 text-[11px] justify-end">
                                                    <i class="fa-solid fa-user-group text-slate-400"></i>
                                                    <span>আসন: <strong class="text-slate-800">{{ $section->capacity ?? '৫০' }}</strong> জন</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Right Column: Curriculum & Subject Mastery (60% / 7 Cols) --}}
                        <div class="lg:col-span-7 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-sm">
                                        <i class="fa-solid fa-book-open"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-base text-slate-900">পাঠ্যক্রম ও নির্ধারিত বিষয়সমূহ</h4>
                                        <div class="text-[11px] text-slate-400">NCTB Approved Curriculum & Marks Distribution</div>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                    {{ $subjectsCount }}টি বিষয়
                                </span>
                            </div>

                            @if($c->subjects->isEmpty())
                                <div class="p-10 text-center text-xs text-slate-500 bg-slate-50 rounded-xl border border-dashed border-slate-200 space-y-2">
                                    <i class="fa-solid fa-book-open text-slate-400 text-2xl block"></i>
                                    <div class="font-medium text-slate-700 text-sm">এই শ্রেণির জন্য এখনো কোনো বিষয় বরাদ্দ করা হয়নি।</div>
                                    <div class="text-slate-400 text-xs">জাতীয় শিক্ষাক্রম অনুযায়ী বিষয় বণ্টন প্রক্রিয়া সম্পন্ন হচ্ছে।</div>
                                </div>
                            @else
                                <div class="overflow-hidden border border-slate-200/90 rounded-xl shadow-2xs">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200/90">
                                            <tr>
                                                <th class="p-3.5 text-slate-900 font-bold">বিষয়ের নাম ও শিরোনাম</th>
                                                <th class="p-3.5 text-center">কোড</th>
                                                <th class="p-3.5 text-center">পূর্ণমান</th>
                                                <th class="p-3.5 text-center">পাস নম্বর</th>
                                                <th class="p-3.5 text-right">ধরন</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 text-slate-800">
                                            @foreach($c->subjects as $subject)
                                                @php
                                                    $isOptional = ($subject->pivot->type ?? '') === 'optional';
                                                @endphp
                                                <tr class="hover:bg-slate-50/80 transition-colors">
                                                    <td class="p-3.5 font-medium text-slate-900">
                                                        <div class="flex items-center gap-2.5">
                                                            <i class="fa-solid fa-book-bookmark text-slate-400 text-xs"></i>
                                                            <span class="font-bold text-sm tracking-tight text-slate-900">{{ $subject->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-3.5 text-center font-mono text-slate-600 text-xs">
                                                        {{ $subject->code ?? '—' }}
                                                    </td>
                                                    <td class="p-3.5 text-center font-mono font-bold text-slate-900 text-xs">
                                                        {{ $subject->pivot->full_marks ?? 100 }}
                                                    </td>
                                                    <td class="p-3.5 text-center font-mono text-slate-600 text-xs">
                                                        {{ $subject->pivot->pass_marks ?? 33 }}
                                                    </td>
                                                    <td class="p-3.5 text-right">
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
                            <div class="bg-amber-50/60 border border-amber-200/80 rounded-xl p-3.5 text-xs text-amber-900 flex items-start gap-3">
                                <i class="fa-solid fa-circle-info text-amber-600 mt-0.5 shrink-0 text-sm"></i>
                                <div class="leading-relaxed">
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

{{-- Hairline Structural Divider (Recipe 7) --}}
<div class="home-section-divider"></div>

{{-- Recipe 6: Strict Conversion Callout (Amber Button Reserved for Primary CTA) --}}
<section class="py-12 sm:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-[#003820] text-white rounded-2xl p-8 sm:p-10 shadow-sm border border-slate-800 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-900/70 text-emerald-300 text-xs font-semibold border border-emerald-700/60">
                    <i class="fa-solid fa-user-graduate text-amber-400"></i>
                    <span>ভর্তি ও শ্রেণিভিত্তিক তথ্য সেবা</span>
                </span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    সন্তানের ভর্তি অথবা শ্রেণিভিত্তিক তথ্য জানতে চান?
                </h3>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                    সোনাতলা উচ্চ বিদ্যালয়ের নির্দিষ্ট শ্রেণিতে আসন প্রাপ্যতা, ভর্তি প্রক্রিয়া অথবা শ্রেণি শিক্ষকের সাথে সাক্ষাৎ সংক্রান্ত যেকোনো প্রয়োজনে সরাসরি আমাদের সাথে যোগাযোগ করুন।
                </p>
            </div>

            <div class="flex items-center gap-3 flex-wrap shrink-0">
                <a href="{{ route('frontend.academic.routine') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition-all hover:scale-105 active:scale-95 shadow-xs">
                    <i class="fa-solid fa-calendar-days text-amber-400"></i>
                    <span>ক্লাস রুটিন দেখুন</span>
                </a>

                {{-- Reserved Warm Amber Primary Conversion CTA --}}
                <a href="{{ route('frontend.contact') }}#enroll"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs shadow-xs transition-all hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-file-signature text-slate-950"></i>
                    <span>ভর্তি আবেদন ও তথ্য</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
