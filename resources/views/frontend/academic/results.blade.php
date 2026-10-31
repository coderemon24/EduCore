@extends('layouts.app')

@section('title', 'একাডেমিক ফলাফল ও নম্বরপত্র | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের টার্মিনাল, অর্ধবার্ষিক ও বার্ষিক পরীক্ষার ফলাফল অনুসন্ধান, মেধা তালিকা ও বিস্তারিত ডিজিটাল নম্বরপত্র।')

@section('content')

{{-- Breadcrumb Navigation (Standard EduCore Clean Layout) --}}
<div class="bg-gray-100 border-b border-gray-200 py-3.5 text-xs text-gray-600 print:hidden" id="overview">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>হোম</span>
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-500">একাডেমিক</span>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">ফলাফল ও নম্বরপত্র</span>
        </nav>
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">
            {{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }} • রেজাল্ট ও ট্রান্সক্রিপ্ট পোর্টাল
        </span>
    </div>
</div>

{{-- Top Hero Banner: Deep Emerald Header (#005030) with Golden Pill & Live Status --}}
<div class="page-hero-banner bg-[#005030] text-white py-8 sm:py-10 border-b border-emerald-900 print:hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Kicker Badge with Pulsing Live Status Dot (Recipe 7: Add a Signature) --}}
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-xs border border-white/20 text-emerald-100 text-xs font-semibold shadow-xs">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-400"></span>
            </span>
            <span>টার্মিনাল ও বার্ষিক মূল্যায়ন ডাটাবেজ</span>
            <span class="text-white/40">•</span>
            <span class="text-amber-300 font-bold">অফিসিয়াল গ্রেডশিট</span>
        </div>

        {{-- Page Heading (Recipe 1 & 5: Sharpen Message & Scale Type) --}}
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white mt-3 mb-2 tracking-tight">
            একাডেমিক ফলাফল <span class="text-amber-400 font-serif">&</span> নম্বরপত্র
        </h1>

        {{-- Sub-narrative context --}}
        <p class="text-xs sm:text-sm text-emerald-100/90 font-medium max-w-2xl leading-relaxed">
            সোনাতলা উচ্চ বিদ্যালয়ের টার্মিনাল, অর্ধবার্ষিক ও বার্ষিক পরীক্ষার শ্রেণিভিত্তিক মেধা তালিকা এবং শিক্ষার্থীর বিস্তারিত ডিজিটাল নম্বরপত্র অনুসন্ধান করুন।
        </p>
    </div>
</div>

{{-- Main Container Section --}}
<section class="py-8 sm:py-12 bg-slate-50/70 min-h-[600px] print:bg-white print:py-0"
         id="academic-results-section"
         x-data="{
            activeTab: '{{ $activeTab ?? 'merit' }}',
            selectedStudent: null,
            modalOpen: false,
            openMarksheet(studentData) {
                this.selectedStudent = studentData;
                this.modalOpen = true;
            },
            printMarksheet() {
                window.print();
            }
         }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Search & Filter Card (Recipe 2 & 3: Asymmetric Search Layout) --}}
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 mb-8 print:hidden transition-all">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5 mb-4.5">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center border border-emerald-100 font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 tracking-tight">পরীক্ষা ও শিক্ষার্থী ফিল্টার</h2>
                        <p class="text-xs text-slate-500">পরীক্ষার নাম, শ্রেণি, শাখা ও রোল নম্বর দিয়ে ফলাফল অনুসন্ধান করুন</p>
                    </div>
                </div>

                {{-- Fast Reset Link --}}
                <a href="{{ route('frontend.academic.results') }}"
                   class="text-xs font-semibold text-slate-500 hover:text-rose-600 transition-colors inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>রিসেট</span>
                </a>
            </div>

            <form action="{{ route('frontend.academic.results') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">

                    {{-- 1. Academic Year --}}
                    <div>
                        <label for="filter-year" class="block text-xs font-bold text-slate-700 mb-1.5">
                            শিক্ষাবর্ষ <span class="text-emerald-700">*</span>
                        </label>
                        <select name="year_id" id="filter-year"
                                class="w-full text-xs sm:text-sm font-medium rounded-xl border-slate-300 bg-slate-50/60 focus:bg-white focus:border-[#005030] focus:ring-2 focus:ring-[#005030]/20 py-2.5 px-3 transition-colors">
                            @foreach ($academicYears as $yr)
                                <option value="{{ $yr->id }}" {{ ($selectedYear && $selectedYear->id == $yr->id) ? 'selected' : '' }}>
                                    {{ $yr->name }} {{ $yr->is_current ? '(বর্তমান)' : '' }}
                                </option>
                            @endforeach
                            @if ($academicYears->isEmpty())
                                <option value="">২০২৬ শিক্ষাবর্ষ</option>
                            @endif
                        </select>
                    </div>

                    {{-- 2. Examination --}}
                    <div>
                        <label for="filter-exam" class="block text-xs font-bold text-slate-700 mb-1.5">
                            পরীক্ষার নাম <span class="text-emerald-700">*</span>
                        </label>
                        <select name="exam_id" id="filter-exam"
                                class="w-full text-xs sm:text-sm font-medium rounded-xl border-slate-300 bg-slate-50/60 focus:bg-white focus:border-[#005030] focus:ring-2 focus:ring-[#005030]/20 py-2.5 px-3 transition-colors">
                            @forelse ($exams as $ex)
                                <option value="{{ $ex->id }}" {{ ($selectedExam && $selectedExam->id == $ex->id) ? 'selected' : '' }}>
                                    {{ $ex->name }}
                                </option>
                            @empty
                                <option value="">বার্ষিক পরীক্ষা</option>
                            @endforelse
                        </select>
                    </div>

                    {{-- 3. Academic Class --}}
                    <div>
                        <label for="filter-class" class="block text-xs font-bold text-slate-700 mb-1.5">
                            শ্রেণি <span class="text-emerald-700">*</span>
                        </label>
                        <select name="academic_class_id" id="filter-class"
                                class="w-full text-xs sm:text-sm font-medium rounded-xl border-slate-300 bg-slate-50/60 focus:bg-white focus:border-[#005030] focus:ring-2 focus:ring-[#005030]/20 py-2.5 px-3 transition-colors">
                            @foreach ($academicClasses as $cls)
                                <option value="{{ $cls->id }}" {{ ($selectedClass && $selectedClass->id == $cls->id) ? 'selected' : '' }}>
                                    {{ $cls->name }}
                                </option>
                            @endforeach
                            @if ($academicClasses->isEmpty())
                                <option value="">৮ম শ্রেণি (Class 8)</option>
                            @endif
                        </select>
                    </div>

                    {{-- 4. Section --}}
                    <div>
                        <label for="filter-section" class="block text-xs font-bold text-slate-700 mb-1.5">
                            শাখা (সেকশন)
                        </label>
                        <select name="section_id" id="filter-section"
                                class="w-full text-xs sm:text-sm font-medium rounded-xl border-slate-300 bg-slate-50/60 focus:bg-white focus:border-[#005030] focus:ring-2 focus:ring-[#005030]/20 py-2.5 px-3 transition-colors">
                            <option value="all">সকল শাখা (All)</option>
                            @foreach ($sections as $sec)
                                <option value="{{ $sec->id }}" {{ ($selectedSection && $selectedSection->id == $sec->id) ? 'selected' : '' }}>
                                    {{ $sec->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 5. Roll / Student ID Search --}}
                    <div>
                        <label for="filter-roll" class="block text-xs font-bold text-slate-700 mb-1.5">
                            রোল নম্বর / স্টুডেন্ট আইডি
                        </label>
                        <div class="relative">
                            <input type="text"
                                   name="roll_or_id"
                                   id="filter-roll"
                                   value="{{ $searchQuery }}"
                                   placeholder="উদাঃ 01 বা STU-2026-001"
                                   class="w-full text-xs sm:text-sm font-medium rounded-xl border-slate-300 bg-slate-50/60 focus:bg-white focus:border-[#005030] focus:ring-2 focus:ring-[#005030]/20 py-2.5 pl-3 pr-8 transition-colors">
                            @if ($searchQuery)
                                <button type="button"
                                        onclick="document.getElementById('filter-roll').value=''; this.parentElement.closest('form').submit();"
                                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-100 flex-wrap gap-3">
                    <div class="text-xs text-slate-500">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span>
                        নির্দিষ্ট শিক্ষার্থীর নম্বরপত্র দেখতে শুধু রোল নম্বর বা আইডি লিখে সাবমিট করুন।
                    </div>

                    <div class="flex items-center gap-2.5">
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#005030] hover:bg-[#003822] text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow-md transition-all active:scale-98 cursor-pointer">
                            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>ফলাফল অনুসন্ধান</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Telemetry Metrics Bar (Recipe 4: Pick the Winner & Display Quick Stats) --}}
        @if ($results->isNotEmpty() || $telemetry['appeared'] > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-8 print:hidden">
                {{-- Metric 1: Total Appeared --}}
                <div class="bg-white rounded-xl border border-slate-200/90 p-4 shadow-2xs">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">মোট পরীক্ষার্থী</div>
                    <div class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                        {{ number_format($telemetry['appeared']) }} <span class="text-xs font-semibold text-slate-500">জন</span>
                    </div>
                    <div class="text-[11px] text-slate-500 mt-1">উপস্থিত ও মূল্যায়িত</div>
                </div>

                {{-- Metric 2: Total Passed --}}
                <div class="bg-white rounded-xl border border-emerald-200 p-4 shadow-2xs bg-emerald-50/20">
                    <div class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider mb-1">উত্তীর্ণ শিক্ষার্থী</div>
                    <div class="text-xl sm:text-2xl font-extrabold text-emerald-700 tracking-tight">
                        {{ number_format($telemetry['passed']) }} <span class="text-xs font-semibold text-emerald-600">জন</span>
                    </div>
                    <div class="text-[11px] text-emerald-700 font-medium mt-1">কৃতকার্য</div>
                </div>

                {{-- Metric 3: Total Failed --}}
                <div class="bg-white rounded-xl border border-rose-200 p-4 shadow-2xs bg-rose-50/20">
                    <div class="text-[11px] font-bold text-rose-800 uppercase tracking-wider mb-1">অনুত্তীর্ণ</div>
                    <div class="text-xl sm:text-2xl font-extrabold text-rose-600 tracking-tight">
                        {{ number_format($telemetry['failed']) }} <span class="text-xs font-semibold text-rose-500">জন</span>
                    </div>
                    <div class="text-[11px] text-rose-700 font-medium mt-1">অকৃতকার্য</div>
                </div>

                {{-- Metric 4: Pass Rate % --}}
                <div class="bg-white rounded-xl border border-blue-200 p-4 shadow-2xs bg-blue-50/20">
                    <div class="text-[11px] font-bold text-blue-800 uppercase tracking-wider mb-1">পাসের হার</div>
                    <div class="text-xl sm:text-2xl font-extrabold text-blue-700 tracking-tight">
                        {{ $telemetry['pass_rate'] }}%
                    </div>
                    <div class="text-[11px] text-blue-700 font-medium mt-1">সামগ্রিক অর্জন</div>
                </div>

                {{-- Metric 5: GPA 5.00 Achievers --}}
                <div class="bg-gradient-to-br from-amber-50 to-amber-100/40 rounded-xl border border-amber-300 p-4 shadow-2xs col-span-2 sm:col-span-1">
                    <div class="text-[11px] font-bold text-amber-900 uppercase tracking-wider mb-1">GPA 5.00 (A+)</div>
                    <div class="text-xl sm:text-2xl font-extrabold text-amber-950 tracking-tight flex items-baseline gap-1">
                        {{ number_format($telemetry['gpa5_count']) }} <span class="text-xs font-semibold text-amber-800">জন</span>
                    </div>
                    <div class="text-[11px] text-amber-800 font-bold mt-1">সর্বোচ্চ জিপিএ অর্জনকারী</div>
                </div>
            </div>
        @endif

        {{-- View Mode Tab Bar --}}
        <div class="flex items-center justify-between mb-6 border-b border-slate-200 pb-3 print:hidden flex-wrap gap-3">
            <div class="inline-flex rounded-xl p-1 bg-slate-200/80 border border-slate-300/80">
                <button type="button"
                        @click="activeTab = 'merit'"
                        :class="activeTab === 'merit' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="px-4 py-2 text-xs sm:text-sm rounded-lg transition-all flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-[#005030]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>শ্রেণি মেধা তালিকা (Gazette)</span>
                    @if ($results->isNotEmpty())
                        <span class="ml-1 text-[11px] px-1.5 py-0.5 rounded-full bg-emerald-100 text-[#005030] font-bold">
                            {{ $results->count() }}
                        </span>
                    @endif
                </button>

                <button type="button"
                        @click="activeTab = 'marksheet'"
                        :class="activeTab === 'marksheet' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="px-4 py-2 text-xs sm:text-sm rounded-lg transition-all flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>ডিজিটাল নম্বরপত্র (Marksheet)</span>
                    @if ($individualMarksheet)
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    @endif
                </button>
            </div>

            {{-- Action buttons --}}
            <div class="flex items-center gap-2">
                @if ($individualMarksheet)
                    <button type="button"
                            @click="printMarksheet()"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>নম্বরপত্র প্রিন্ট করুন</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- TAB 1: COHORT MERIT LIST & GAZETTE TABLE --}}
        <div x-show="activeTab === 'merit'" x-cloak class="transition-opacity">
            @if ($results->isNotEmpty())
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                    {{-- Table Header Info --}}
                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between flex-wrap gap-2">
                        <div>
                            <span class="text-xs font-bold text-[#005030] uppercase tracking-wider">মেধা তালিকা বিবরণী</span>
                            <h3 class="text-sm sm:text-base font-extrabold text-slate-900">
                                {{ $selectedExam->name ?? 'বার্ষিক পরীক্ষা' }} — {{ $selectedClass->name ?? '৮ম শ্রেণি' }}
                                @if ($selectedSection) (শাখা: {{ $selectedSection->name }}) @endif
                            </h3>
                        </div>
                        <div class="text-xs text-slate-500 font-medium">
                            সর্বমোট প্রকাশিত ফলাফল: <strong class="text-slate-800">{{ $results->count() }}</strong> জন শিক্ষার্থী
                        </div>
                    </div>

                    {{-- Responsive Table Wrapper --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-100/80 text-[11px] font-extrabold text-slate-700 uppercase tracking-wider border-b border-slate-200">
                                    <th class="py-3 px-4 text-center w-16">মেধা ক্রম</th>
                                    <th class="py-3 px-4 text-center w-16">রোল</th>
                                    <th class="py-3 px-4">শিক্ষার্থীর নাম ও আইডি</th>
                                    <th class="py-3 px-3 text-center">শ্রেণি ও শাখা</th>
                                    <th class="py-3 px-3 text-center">মোট নম্বর</th>
                                    <th class="py-3 px-3 text-center">জিপিএ (GPA)</th>
                                    <th class="py-3 px-3 text-center">গ্রেড</th>
                                    <th class="py-3 px-3 text-center">ফলাফল</th>
                                    <th class="py-3 px-4 text-right">নম্বরপত্র</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                                @foreach ($results as $index => $res)
                                    @php
                                        $student = $res->student;
                                        $enrollment = $student?->currentEnrollment;
                                        $roll = $enrollment?->roll_number ?? '—';
                                        $rank = $res->rank_in_class ?? ($index + 1);
                                        $gpa = number_format((float) $res->gpa, 2);
                                        $isGpa5 = $res->gpa >= 5.00;
                                        $isPassed = strtolower($res->status) === 'pass';
                                    @endphp
                                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                                        {{-- Rank Column with Badge for Top 3 --}}
                                        <td class="py-3 px-4 text-center font-bold">
                                            @if ($rank == 1)
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-slate-950 font-black text-xs shadow-2xs ring-2 ring-amber-300" title="১ম স্থান">১</span>
                                            @elseif ($rank == 2)
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-300 text-slate-900 font-bold text-xs" title="২য় স্থান">২</span>
                                            @elseif ($rank == 3)
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white font-bold text-xs" title="৩য় স্থান">৩</span>
                                            @else
                                                <span class="text-slate-500 font-semibold">{{ $rank }}</span>
                                            @endif
                                        </td>

                                        {{-- Roll Number --}}
                                        <td class="py-3 px-4 text-center font-bold text-slate-900 font-mono">
                                            {{ str_pad($roll, 2, '0', STR_PAD_LEFT) }}
                                        </td>

                                        {{-- Student Name & Admission Number --}}
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-[#005030] text-xs flex-shrink-0">
                                                    {{ mb_substr($student?->first_name ?? 'S', 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-900 group-hover:text-[#005030] transition-colors">
                                                        {{ $student?->first_name }} {{ $student?->last_name }}
                                                    </div>
                                                    <div class="text-[11px] text-slate-400 font-mono">
                                                        আইডি: {{ $student?->admission_number ?? ('STU-' . $student?->id) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Class & Section --}}
                                        <td class="py-3 px-3 text-center text-slate-600 font-medium">
                                            {{ $res->academicClass?->name ?? '—' }}
                                            @if ($res->section)
                                                <span class="text-[11px] text-slate-400">({{ $res->section->name }})</span>
                                            @endif
                                        </td>

                                        {{-- Total Marks --}}
                                        <td class="py-3 px-3 text-center font-bold text-slate-900">
                                            {{ number_format((float) $res->total_marks_obtained, 1) }}
                                            <span class="text-[10px] text-slate-400 font-normal">/ {{ (int) $res->total_marks_possible }}</span>
                                        </td>

                                        {{-- GPA --}}
                                        <td class="py-3 px-3 text-center font-black {{ $isGpa5 ? 'text-amber-600' : 'text-slate-900' }}">
                                            {{ $gpa }}
                                        </td>

                                        {{-- Grade --}}
                                        <td class="py-3 px-3 text-center">
                                            @if ($res->grade === 'A+' || $isGpa5)
                                                <span class="inline-block px-2.5 py-0.5 rounded-full bg-amber-100 border border-amber-300 text-amber-900 text-xs font-black shadow-2xs">A+</span>
                                            @elseif ($res->grade === 'A')
                                                <span class="inline-block px-2.5 py-0.5 rounded-full bg-emerald-100 border border-emerald-300 text-emerald-900 text-xs font-bold">A</span>
                                            @elseif ($res->grade === 'A-')
                                                <span class="inline-block px-2.5 py-0.5 rounded-full bg-teal-100 border border-teal-300 text-teal-900 text-xs font-bold">A-</span>
                                            @elseif ($res->grade === 'B' || $res->grade === 'C' || $res->grade === 'D')
                                                <span class="inline-block px-2.5 py-0.5 rounded-full bg-blue-100 border border-blue-300 text-blue-900 text-xs font-bold">{{ $res->grade }}</span>
                                            @else
                                                <span class="inline-block px-2.5 py-0.5 rounded-full bg-rose-100 border border-rose-300 text-rose-800 text-xs font-bold">F</span>
                                            @endif
                                        </td>

                                        {{-- Pass / Fail Status --}}
                                        <td class="py-3 px-3 text-center">
                                            @if ($isPassed)
                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    উত্তীর্ণ
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md">
                                                    <svg class="w-3 h-3 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                                    অনুত্তীর্ণ
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Actions: View Marksheet --}}
                                        <td class="py-3 px-4 text-right">
                                            <a href="{{ route('frontend.academic.results', array_merge(request()->query(), ['roll_or_id' => $roll, 'tab' => 'marksheet'])) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-[#005030] text-[#005030] hover:text-white border border-emerald-200 text-xs font-bold transition-all shadow-2xs">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span>নম্বরপত্র</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                {{-- Empty State for Merit List --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-10 sm:p-14 text-center shadow-xs">
                    <div class="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-100 text-[#005030] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-[#005030]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-2">কোনো ফলাফল পাওয়া যায়নি</h3>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mb-6">
                        নির্বাচিত পরীক্ষা ও শ্রেণির ফলাফল এখনো সার্ভারে প্রকাশিত বা প্রক্রিয়া করা হয়নি। অনুগ্রহ করে অন্য পরীক্ষা বা শ্রেণি নির্বাচন করে দেখুন।
                    </p>
                    <a href="{{ route('frontend.academic.results') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#005030] text-white text-xs font-bold hover:bg-[#003822] shadow-xs transition-colors">
                        <span>সব ফিল্টার রিসেট করুন</span>
                    </a>
                </div>
            @endif
        </div>

        {{-- TAB 2: INDIVIDUAL STUDENT MARKSHEET (PRINT-READY TRANSCRIPT) --}}
        <div x-show="activeTab === 'marksheet'" x-cloak class="transition-opacity">
            @if ($individualMarksheet)
                @php
                    $mStudent = $individualMarksheet['student'];
                    $mEnrollment = $individualMarksheet['enrollment'];
                    $mResult = $individualMarksheet['result'];
                    $mExam = $individualMarksheet['exam'];
                    $mSubjects = $individualMarksheet['examSubjects'];
                    $mMarks = $individualMarksheet['marks'];
                    $mSetting = $individualMarksheet['setting'];
                    $mGradingScale = $individualMarksheet['gradingScale'];
                    $mOptionalSubId = $individualMarksheet['optionalSubjectId'];

                    $mGpa = $mResult ? number_format((float) $mResult->gpa, 2) : '0.00';
                    $mGrade = $mResult?->grade ?? 'F';
                    $mPassed = $mResult && strtolower($mResult->status) === 'pass';
                @endphp

                <div class="bg-white rounded-2xl border border-slate-300 shadow-sm p-6 sm:p-10 max-w-4xl mx-auto print:border-none print:shadow-none print:p-0 print:max-w-none">

                    {{-- Institutional Header (Bangladesh Secondary Marksheet Pattern) --}}
                    <div class="border-b-2 border-[#005030] pb-6 mb-6 text-center relative">
                        <div class="flex items-center justify-center gap-4 mb-2">
                            <div class="w-16 h-16 rounded-full border border-slate-300 p-1 flex items-center justify-center bg-white">
                                <img src="{{ asset('assets/images/logo.png') }}"
                                     alt="Logo"
                                     class="max-w-full max-h-full object-contain"
                                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=SHS&background=005030&color=fff&size=128';">
                            </div>
                            <div class="text-left">
                                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                                    {{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}
                                </h2>
                                <div class="text-xs font-semibold text-slate-600 tracking-wider">
                                    সোনাতলা, বাঘারপাড়া, যশোর • স্থাপিত: ১৯৬৮ • EIIN: 115546
                                </div>
                                <div class="text-[11px] text-emerald-800 font-bold uppercase tracking-wider mt-0.5">
                                    অফিসিয়াল গ্রেডশিট ও শিক্ষাবর্ষিক মূল্যায়নপত্র
                                </div>
                            </div>
                        </div>

                        {{-- Exam Title Ribbon --}}
                        <div class="inline-block mt-2 px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-300 text-[#005030] text-xs font-extrabold uppercase tracking-wide">
                            {{ $mExam->name }} — শিক্ষাবর্ষ: {{ $mExam->academicYear?->name ?? '২০২৬' }}
                        </div>

                        {{-- Print Button (Hidden in Print) --}}
                        <div class="absolute right-0 top-0 print:hidden hidden sm:block">
                            <button type="button"
                                    @click="printMarksheet()"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-xs cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>প্রিন্ট করুন</span>
                            </button>
                        </div>
                    </div>

                    {{-- Student Information Card & Metadata Grid --}}
                    <div class="bg-slate-50/80 rounded-xl border border-slate-200 p-4 sm:p-5 mb-6 text-xs sm:text-sm">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-3 gap-x-4">
                            <div>
                                <span class="text-slate-400 font-medium block text-[11px]">শিক্ষার্থীর নাম:</span>
                                <strong class="text-slate-900 text-sm font-bold">{{ $mStudent->first_name }} {{ $mStudent->last_name }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 font-medium block text-[11px]">শ্রেণি রোল:</span>
                                <strong class="text-slate-900 text-sm font-bold font-mono">{{ str_pad($mEnrollment?->roll_number ?? '—', 2, '0', STR_PAD_LEFT) }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 font-medium block text-[11px]">স্টুডেন্ট আইডি:</span>
                                <strong class="text-slate-900 text-sm font-bold font-mono">{{ $mStudent->admission_number ?? ('STU-' . $mStudent->id) }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 font-medium block text-[11px]">শ্রেণি:</span>
                                <strong class="text-slate-800 font-semibold">{{ $mEnrollment?->academicClass?->name ?? ($selectedClass->name ?? '৮ম শ্রেণি') }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 font-medium block text-[11px]">শাখা (সেকশন):</span>
                                <strong class="text-slate-800 font-semibold">{{ $mEnrollment?->section?->name ?? 'পদ্মা' }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 font-medium block text-[11px]">বিভাগ / গ্রুপ:</span>
                                <strong class="text-slate-800 font-semibold">সাধারণ / জেনারেল</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Overall Academic Standing Flagship Card (Recipe 4: Pick the Winner) --}}
                    <div class="bg-gradient-to-r from-[#005030] to-emerald-900 text-white rounded-xl p-5 mb-6 shadow-sm flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-white/10 backdrop-blur-xs border border-white/20 flex flex-col items-center justify-center">
                                <span class="text-[10px] uppercase font-bold text-emerald-200">জিপিএ</span>
                                <span class="text-2xl font-black text-amber-300 leading-none">{{ $mGpa }}</span>
                            </div>
                            <div>
                                <div class="text-xs text-emerald-100 font-medium">সামগ্রিক ফলাফল ও গ্রেড</div>
                                <div class="text-lg font-black text-white flex items-center gap-2">
                                    <span>গ্রেড: {{ $mGrade }}</span>
                                    @if ($mPassed)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-500 text-white font-extrabold">উত্তীর্ণ (PASSED)</span>
                                    @else
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-rose-500 text-white font-extrabold">অনুত্তীর্ণ (FAILED)</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-6 text-right">
                            <div>
                                <div class="text-[11px] text-emerald-200">মোট প্রাপ্ত নম্বর</div>
                                <div class="text-lg font-bold text-white">
                                    {{ number_format((float) ($mResult?->total_marks_obtained ?? 0), 1) }}
                                    <span class="text-xs text-emerald-300 font-normal">/ {{ (int) ($mResult?->total_marks_possible ?? 0) }}</span>
                                </div>
                            </div>
                            @if ($mResult?->rank_in_class)
                                <div class="border-l border-emerald-800/80 pl-6">
                                    <div class="text-[11px] text-emerald-200">মেধা ক্রম</div>
                                    <div class="text-lg font-black text-amber-300">
                                        {{ $mResult->rank_in_class }} তম
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Subject-wise Detailed Marks Table --}}
                    <div class="overflow-x-auto rounded-xl border border-slate-200 mb-6">
                        <table class="w-full text-left border-collapse text-xs sm:text-sm">
                            <thead>
                                <tr class="bg-slate-100 text-[11px] font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
                                    <th class="py-3 px-3.5">বিষয় কোড ও নাম</th>
                                    <th class="py-3 px-3 text-center">পূর্ণমান</th>
                                    <th class="py-3 px-3 text-center">লিখিত/সৃজনশীল</th>
                                    <th class="py-3 px-3 text-center">নৈর্ব্যক্তিক (MCQ)</th>
                                    <th class="py-3 px-3 text-center">ব্যবহারিক</th>
                                    <th class="py-3 px-3 text-center font-bold text-slate-900">প্রাপ্ত নম্বর</th>
                                    <th class="py-3 px-3 text-center">লেটার গ্রেড</th>
                                    <th class="py-3 px-3 text-center font-bold">পয়েন্ট (GP)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($mSubjects as $sub)
                                    @php
                                        $mark = $mMarks->get($sub->id);
                                        $isOptional = $mOptionalSubId && $sub->subject_id == $mOptionalSubId;
                                        $cq = $mark?->components?->firstWhere('examSubjectComponent.name', 'CQ')?->marks_obtained
                                              ?? $mark?->components?->firstWhere('examSubjectComponent.name', 'Written')?->marks_obtained ?? '—';
                                        $mcq = $mark?->components?->firstWhere('examSubjectComponent.name', 'MCQ')?->marks_obtained ?? '—';
                                        $prac = $mark?->components?->firstWhere('examSubjectComponent.name', 'Practical')?->marks_obtained ?? '—';
                                        $total = $mark ? number_format((float) $mark->marks_obtained, 1) : '—';
                                        $gradeTier = $mark?->gradeTier;
                                    @endphp
                                    <tr class="hover:bg-slate-50/50">
                                        {{-- Subject Name & Code --}}
                                        <td class="py-3 px-3.5 font-medium text-slate-900">
                                            <div class="font-bold">{{ $sub->subject?->name ?? 'বিষয়' }}</div>
                                            <div class="text-[11px] text-slate-400 flex items-center gap-1.5 font-mono">
                                                <span>কোড: {{ $sub->subject?->code ?? '—' }}</span>
                                                @if ($isOptional)
                                                    <span class="text-[10px] px-1.5 py-0.2 rounded-md bg-amber-100 text-amber-900 font-bold">৪র্থ বিষয় (Optional)</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Full Marks --}}
                                        <td class="py-3 px-3 text-center text-slate-600 font-mono">{{ (int) $sub->full_marks }}</td>

                                        {{-- Components --}}
                                        <td class="py-3 px-3 text-center text-slate-600 font-mono">{{ $cq }}</td>
                                        <td class="py-3 px-3 text-center text-slate-600 font-mono">{{ $mcq }}</td>
                                        <td class="py-3 px-3 text-center text-slate-600 font-mono">{{ $prac }}</td>

                                        {{-- Obtained Marks --}}
                                        <td class="py-3 px-3 text-center font-bold text-slate-900 font-mono">{{ $total }}</td>

                                        {{-- Letter Grade --}}
                                        <td class="py-3 px-3 text-center font-bold">
                                            @if ($gradeTier?->name === 'A+')
                                                <span class="text-amber-600 font-black">A+</span>
                                            @elseif ($gradeTier?->name === 'A')
                                                <span class="text-emerald-700 font-bold">A</span>
                                            @elseif ($gradeTier?->name === 'F')
                                                <span class="text-rose-600 font-bold">F</span>
                                            @else
                                                <span class="text-slate-800">{{ $gradeTier?->name ?? '—' }}</span>
                                            @endif
                                        </td>

                                        {{-- Grade Point --}}
                                        <td class="py-3 px-3 text-center font-black text-slate-900 font-mono">
                                            {{ $gradeTier ? number_format((float) $gradeTier->point, 2) : '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-6 text-center text-slate-400 text-xs">
                                            এই শিক্ষার্থীর জন্য কোনো নির্দিষ্ট বিষয়ের নম্বর পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Grading System Reference & Signatures Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-200">
                        {{-- Standard Bangladesh Grading Scale Table --}}
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 text-[11px]">
                            <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-1.5 uppercase tracking-wider text-[11px]">
                                <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>অফিসিয়াল গ্রেডিং পদ্ধতি (NCTB)</span>
                            </h4>
                            <table class="w-full text-center border-collapse">
                                <thead>
                                    <tr class="text-slate-500 border-b border-slate-200 text-[10px] font-bold">
                                        <th class="pb-1 text-left">নম্বর ব্যাপ্তি</th>
                                        <th class="pb-1">লেটার গ্রেড</th>
                                        <th class="pb-1">গ্রেড পয়েন্ট</th>
                                        <th class="pb-1 text-right">মন্তব্য</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200/60 text-slate-700">
                                    <tr><td class="py-1 text-left">৮০ – ১০০</td><td class="font-bold text-amber-600">A+</td><td>৫.০০</td><td class="text-right">অসাধারণ</td></tr>
                                    <tr><td class="py-1 text-left">৭০ – ৭৯</td><td class="font-bold text-emerald-700">A</td><td>৪.০০</td><td class="text-right">চমৎকার</td></tr>
                                    <tr><td class="py-1 text-left">৬০ – ৬৯</td><td class="font-bold text-teal-700">A-</td><td>৩.৫০</td><td class="text-right">খুব ভালো</td></tr>
                                    <tr><td class="py-1 text-left">৫০ – ৫৯</td><td class="font-bold text-blue-700">B</td><td>৩.০০</td><td class="text-right">ভালো</td></tr>
                                    <tr><td class="py-1 text-left">৪০ – ৪৯</td><td class="font-bold text-slate-700">C</td><td>২.০০</td><td class="text-right">সন্তোষজনক</td></tr>
                                    <tr><td class="py-1 text-left">৩৩ – ৩৯</td><td class="font-bold text-slate-700">D</td><td>১.০০</td><td class="text-right">উত্তীর্ণ</td></tr>
                                    <tr><td class="py-1 text-left">০০ – ৩২</td><td class="font-bold text-rose-600">F</td><td>০.০০</td><td class="text-right">অনুত্তীর্ণ</td></tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Signatures & Certification Area --}}
                        <div class="flex flex-col justify-between pt-4 md:pt-0">
                            <div class="text-[11px] text-slate-500 leading-relaxed">
                                <strong class="text-slate-800">সনদপত্র ও প্রত্যয়ন:</strong><br>
                                এই নম্বরপত্রটি সোনাতলা উচ্চ বিদ্যালয়ের একাডেমিক পরীক্ষার কেন্দ্রীয় রেজাল্ট ম্যানেজমেন্ট সিস্টেম কর্তৃক প্রস্তুতকৃত।
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-12 text-center text-xs">
                                <div>
                                    <div class="border-t border-slate-400 pt-1.5 font-bold text-slate-800">শ্রেণি শিক্ষক</div>
                                    <div class="text-[10px] text-slate-400">স্বাক্ষর ও তারিখ</div>
                                </div>
                                <div>
                                    <div class="border-t border-slate-400 pt-1.5 font-bold text-slate-800">প্রধান শিক্ষক</div>
                                    <div class="text-[10px] text-slate-400">স্বাক্ষর ও সিলমোহর</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Back & Print Bar (Hidden in Print) --}}
                    <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-between print:hidden">
                        <button type="button"
                                @click="activeTab = 'merit'"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <span>মেধা তালিকায় ফিরে যান</span>
                        </button>

                        <button type="button"
                                @click="printMarksheet()"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#005030] hover:bg-[#003822] text-white text-xs sm:text-sm font-bold shadow-xs hover:shadow-md transition-all active:scale-98 cursor-pointer">
                            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>এই নম্বরপত্র প্রিন্ট করুন</span>
                        </button>
                    </div>

                </div>
            @else
                {{-- No Individual Student Searched State --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-10 sm:p-14 text-center shadow-xs max-w-xl mx-auto">
                    <div class="w-16 h-16 rounded-full bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-2">ব্যক্তিগত নম্বরপত্র অনুসন্ধান</h3>
                    <p class="text-xs sm:text-sm text-slate-500 mb-6">
                        নির্দিষ্ট শিক্ষার্থীর বিস্তারিত মার্কশিট দেখতে উপরের সার্চ বক্সে রোল নম্বর বা স্টুডেন্ট আইডি প্রদান করে অনুসন্ধান করুন, অথবা মেধা তালিকা থেকে শিক্ষার্থীর নামের পাশের <strong class="text-slate-800">"নম্বরপত্র"</strong> বাটনে ক্লিক করুন।
                    </p>
                    <button type="button"
                            @click="activeTab = 'merit'"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#005030] text-white text-xs font-bold hover:bg-[#003822] shadow-xs transition-colors cursor-pointer">
                        <span>মেধা তালিকা দেখুন</span>
                    </button>
                </div>
            @endif
        </div>

    </div>
</section>

{{-- Print Stylesheet --}}
<style>
@media print {
    body {
        background: #ffffff !important;
        color: #000000 !important;
        font-size: 12pt;
    }
    .print\:hidden {
        display: none !important;
    }
    .snhs-header,
    .snhs-navbar,
    .snhs-footer,
    footer,
    header,
    nav {
        display: none !important;
    }
    #academic-results-section {
        padding: 0 !important;
        background: transparent !important;
    }
}
</style>

@endsection
