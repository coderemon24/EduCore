@extends('layouts.app')

@section('title', 'ক্লাস ও পরীক্ষার সময়সূচি | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের শিক্ষাবর্ষের শ্রেণিভিত্তিক দৈনিক পাঠদান সময়সূচি, পিরিয়ড বণ্টন ও পরীক্ষার আনুষ্ঠানিক রুটিন।')

@section('content')

{{-- Print Optimization Styles --}}
<style>
    @media print {
        header, footer, nav, .page-hero-banner, .filter-toolbar-box, .sidebar-card-stack, #back-to-top, .no-print {
            display: none !important;
        }
        body {
            background-color: #ffffff !important;
            font-size: 11pt !important;
            color: #000000 !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .printable-routine-area {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        .print-header-banner {
            display: block !important;
            margin-bottom: 1.5rem !important;
            border-bottom: 2px solid #005030 !important;
            padding-bottom: 0.75rem !important;
        }
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        th, td {
            border: 1px solid #cbd5e1 !important;
            padding: 6px 10px !important;
        }
        thead th {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }
    }
</style>

{{-- Breadcrumb (Standard Clean Layout Matching Classes Page) --}}
<div class="bg-gray-100 border-b border-gray-200 py-3.5 text-xs text-gray-600 no-print" id="overview">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>প্রচ্ছদ</span>
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-500">একাডেমিক</span>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">ক্লাস ও পরীক্ষার সময়সূচি</span>
        </nav>
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">
            {{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }} • শিক্ষাবর্ষ: {{ $selectedYear->name ?? '২০২৬ খ্রি.' }}
        </span>
    </div>
</div>

{{-- Top Hero Banner: Matches Classes Page Layout --}}
<div class="page-hero-banner bg-[#005030] text-white py-8 sm:py-10 border-b border-emerald-900 no-print">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Pill Badge --}}
        <div class="page-hero-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-[#005030] text-xs font-bold shadow-xs">
            <i class="fa-solid fa-calendar-days text-[#005030]"></i>
            <span>একাডেমিক পরিক্রমা</span>
        </div>

        {{-- Page Heading (Recipe 1: Sharpen Message with focal accent span) --}}
        <h1 class="page-hero-title text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white mt-3 mb-2 tracking-tight">
            দৈনিক শ্রেণি পাঠদান ও <span class="text-amber-400 font-extrabold">একাডেমিক সময়সূচি</span>
        </h1>
        <span class="sr-only">ক্লাস ও পরীক্ষার সময়সূচি</span>

        {{-- Sub-narrative context --}}
        <p class="page-hero-subtitle text-xs sm:text-sm text-emerald-100/90 font-medium">
            সোনাতলা উচ্চ বিদ্যালয়ের শ্রেণিভিত্তিক শাখা বণ্টন, বিষয়ভিত্তিক পাঠদান সময় ও নিয়মিত শ্রেণি রুটিন
        </p>
    </div>
</div>

{{-- Main Substance Section --}}
<section class="py-8 sm:py-12 bg-slate-50/60 min-h-[500px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Print Header (Shown only during browser print) --}}
        <div class="hidden print-header-banner text-center space-y-1">
            <h2 class="text-xl font-bold text-slate-900">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}</h2>
            <p class="text-xs text-slate-600">সাঁথিয়া, পাবনা • শ্রেণি পাঠদান সময়সূচি</p>
            <div class="text-xs font-semibold text-slate-800 pt-1">
                <span>শ্রেণি: {{ $selectedClass->name ?? 'সব শ্রেণি' }}</span> •
                <span>শিক্ষাবর্ষ: {{ $selectedYear->name ?? '২০২৬' }}</span>
                @if($selectedSection)
                    • <span>শাখা: {{ $selectedSection->name }}</span>
                @endif
                @if($selectedDay !== 'all')
                    • <span>দিন: {{ $days[$selectedDay] ?? $selectedDay }}</span>
                @endif
            </div>
        </div>

        {{-- Interactive Filter & Selectors Toolbar (Recipe 3: Build Argument & Clear Controls) --}}
        <div class="filter-toolbar-box bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-2xs space-y-5 gsap-reveal hover-card-lift no-print">
            
            {{-- Toolbar Title & Quick Actions --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#005030] section-kicker-dot animate-pulse"></span>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-[#005030] text-xs"></i>
                        <span>রুটিন ফিল্টার ও নির্বাচন</span>
                    </h2>
                    <span class="text-xs text-slate-500 hidden md:inline">| শ্রেণি ও শিক্ষাবর্ষ অনুযায়ী রুটিন দেখুন</span>
                </div>

                {{-- Print & Quick Helper Action --}}
                <div class="flex items-center gap-2.5 self-start sm:self-auto">
                    <button type="button"
                            onclick="window.print()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-[#005030] hover:bg-emerald-100 text-xs font-bold border border-emerald-200/90 transition-all shadow-2xs cursor-pointer hover-pill-scale"
                            title="এই রুটিনটি প্রিন্ট বা PDF আকারে সংরক্ষণ করুন">
                        <i class="fa-solid fa-print text-xs"></i>
                        <span>রুটিন প্রিন্ট করুন</span>
                    </button>
                    <a href="{{ route('frontend.academic.classes') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold border border-slate-200 transition-colors cursor-pointer">
                        <i class="fa-solid fa-book-open text-xs"></i>
                        <span>পাঠ্যসূচি</span>
                    </a>
                </div>
            </div>

            {{-- Filter Form --}}
            <form action="{{ route('frontend.academic.routine') }}" method="GET" class="space-y-4" id="routineFilterForm">
                
                {{-- Year, Class & Section Selectors Row --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-end">
                    
                    {{-- 1. Academic Year Dropdown (Cols 3) --}}
                    <div class="lg:col-span-3 space-y-1.5">
                        <label for="year_id" class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-calendar text-[#005030] text-xs"></i>
                            <span>শিক্ষাবর্ষ নির্বাচন</span>
                        </label>
                        <select name="year_id"
                                id="year_id"
                                onchange="document.getElementById('routineFilterForm').submit()"
                                class="w-full text-xs font-medium text-slate-800 bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005030]/20 focus:border-[#005030] transition-colors">
                            @foreach($years as $year)
                                <option value="{{ $year->id }}" {{ ($selectedYear?->id == $year->id) ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_current ? '(বর্তমান সেশন)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. Section Selector (Cols 3) --}}
                    <div class="lg:col-span-3 space-y-1.5">
                        <label for="section_id" class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-users-rectangle text-[#005030] text-xs"></i>
                            <span>শাখা নির্বাচন</span>
                        </label>
                        <select name="section_id"
                                id="section_id"
                                onchange="document.getElementById('routineFilterForm').submit()"
                                class="w-full text-xs font-medium text-slate-800 bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005030]/20 focus:border-[#005030] transition-colors">
                            <option value="all" {{ empty($selectedSection) ? 'selected' : '' }}>সকল শাখা (All Sections)</option>
                            @foreach($sections as $sec)
                                <option value="{{ $sec->id }}" {{ ($selectedSection?->id == $sec->id) ? 'selected' : '' }}>
                                    {{ $sec->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Day of Week Dropdown for Mobile / Direct selection (Cols 3) --}}
                    <div class="lg:col-span-4 space-y-1.5">
                        <label for="day" class="block text-xs font-bold text-slate-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-clock text-[#005030] text-xs"></i>
                            <span>বার / দিন নির্বাচন</span>
                        </label>
                        <select name="day"
                                id="day"
                                onchange="document.getElementById('routineFilterForm').submit()"
                                class="w-full text-xs font-medium text-slate-800 bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005030]/20 focus:border-[#005030] transition-colors">
                            <option value="all" {{ ($selectedDay === 'all') ? 'selected' : '' }}>সম্পূর্ণ সপ্তাহ (সব দিন)</option>
                            @foreach($days as $key => $label)
                                <option value="{{ $key }}" {{ ($selectedDay === $key) ? 'selected' : '' }}>
                                    {{ $label }} ({{ ucfirst($key) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Submit Button (Cols 2) --}}
                    <div class="lg:col-span-2">
                        <button type="submit"
                                class="w-full py-2.5 px-4 rounded-xl bg-[#005030] text-white text-xs font-bold hover:bg-[#003d24] transition-colors shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            <span>রুটিন দেখুন</span>
                        </button>
                    </div>
                </div>

                {{-- Hidden class_id so submitting form preserves currently selected class --}}
                <input type="hidden" name="class_id" value="{{ $selectedClass?->id ?? '' }}">

                {{-- Class Selector Pills (Horizontal Nav, matches classes.blade.php pattern) --}}
                <div class="pt-2 border-t border-slate-100 space-y-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">
                        শ্রেণি নির্বাচন করুন:
                    </span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($classes as $c)
                            @php
                                $isActiveClass = ($selectedClass?->id == $c->id);
                                $url = route('frontend.academic.routine', [
                                    'year_id' => $selectedYear?->id,
                                    'class_id' => $c->id,
                                    'section_id' => $selectedSection?->id ?? 'all',
                                    'day' => $selectedDay,
                                ]);
                            @endphp
                            <a href="{{ $url }}"
                               class="px-3 py-2 rounded-xl text-center text-xs font-bold transition-all duration-150 cursor-pointer {{ $isActiveClass ? 'bg-[#005030] text-white shadow-xs scale-[1.02] border border-[#005030]' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/90 hover:border-emerald-300' }}">
                                {{ $c->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Day-of-Week Quick Switcher Tabs (Saturday to Thursday + All Days) --}}
                <div class="pt-2 border-t border-slate-100 space-y-2">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">
                        সাপ্তাহিক কর্মদিবস নির্বাচন:
                    </span>
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                        @php
                            $allDaysUrl = route('frontend.academic.routine', [
                                'year_id' => $selectedYear?->id,
                                'class_id' => $selectedClass?->id,
                                'section_id' => $selectedSection?->id ?? 'all',
                                'day' => 'all',
                            ]);
                        @endphp
                        <a href="{{ $allDaysUrl }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors cursor-pointer {{ ($selectedDay === 'all') ? 'bg-slate-900 text-white shadow-2xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                            <i class="fa-solid fa-list-check text-[11px] mr-1"></i>
                            <span>সম্পূর্ণ সপ্তাহ</span>
                        </a>

                        @foreach($days as $dayKey => $dayLabel)
                            @php
                                $isActiveDay = ($selectedDay === $dayKey);
                                $dayUrl = route('frontend.academic.routine', [
                                    'year_id' => $selectedYear?->id,
                                    'class_id' => $selectedClass?->id,
                                    'section_id' => $selectedSection?->id ?? 'all',
                                    'day' => $dayKey,
                                ]);
                            @endphp
                            <a href="{{ $dayUrl }}"
                               class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors cursor-pointer {{ $isActiveDay ? 'bg-[#005030] text-white shadow-2xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                                {{ $dayLabel }}
                            </a>
                        @endforeach
                    </div>
                </div>

            </form>
        </div>

        {{-- Asymmetric Editorial Layout: Routine Schedule Canvas (8 Cols) & Sidebar (4 Cols) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            {{-- Main Timetable Canvas (Col 8) --}}
            <div class="lg:col-span-8 space-y-5 printable-routine-area">
                
                {{-- Timetable Header Badge --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 flex flex-wrap items-center justify-between gap-3 shadow-2xs no-print">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center text-lg shrink-0 border border-emerald-200/90">
                            <i class="fa-solid fa-table-cells"></i>
                        </div>
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-slate-900">
                                {{ $selectedClass->name ?? 'শ্রেণি' }} - সময়সূচি
                            </h2>
                            <p class="text-xs text-slate-600">
                                শিক্ষাবর্ষ: {{ $selectedYear->name ?? '২০২৬' }} |
                                শাখা: {{ $selectedSection ? $selectedSection->name : 'সকল শাখা' }} |
                                দিন: {{ $selectedDay === 'all' ? 'সম্পূর্ণ সপ্তাহ' : ($days[$selectedDay] ?? 'সকল দিন') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100/80 text-[#005030] text-xs font-bold border border-emerald-300/80">
                            <i class="fa-solid fa-circle-check text-xs"></i>
                            <span>{{ $stats['total_slots'] }} টি নির্ধারিত ক্লাস</span>
                        </span>
                    </div>
                </div>

                @if($timetableSlots->isEmpty())
                    {{-- Clean Empty State --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-10 sm:p-14 text-center shadow-2xs space-y-3">
                        <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-2xl border border-amber-200">
                            <i class="fa-regular fa-calendar-xmark"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">
                            কোনো পাঠদান সময়সূচি পাওয়া যায়নি
                        </h3>
                        <p class="text-xs text-slate-600 max-w-md mx-auto leading-relaxed">
                            {{ $selectedClass->name ?? 'নির্বাচিত শ্রেণি' }}-এর জন্য এই নির্দিষ্ট দিন বা শাখায় কোনো রুটিন যুক্ত করা হয়নি। অনুগ্রহ করে অন্য দিন বা শাখা নির্বাচন করুন।
                        </p>
                        <div class="pt-3 no-print">
                            <a href="{{ route('frontend.academic.routine', ['class_id' => $selectedClass?->id, 'day' => 'all']) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#005030] text-white text-xs font-bold hover:bg-[#003d24] transition-colors shadow-2xs">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>সম্পূর্ণ সপ্তাহের রুটিন দেখুন</span>
                            </a>
                        </div>
                    </div>
                @else
                    
                    {{-- Schedule Rendering: Single Day View vs Full Week Grouped View --}}
                    @if($selectedDay !== 'all')
                        {{-- 1. SINGLE DAY TABLE VIEW --}}
                        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
                            <div class="bg-[#005030] text-white px-5 py-3.5 flex items-center justify-between">
                                <h3 class="font-bold text-sm flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-day text-amber-400"></i>
                                    <span>{{ $days[$selectedDay] ?? $selectedDay }} এর পাঠদান সময়সূচি</span>
                                </h3>
                                <span class="text-xs text-emerald-100 font-mono">{{ $timetableSlots->count() }}টি পিরিয়ড</span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                                        <tr>
                                            <th class="p-3.5 border-r border-slate-200 text-center w-24">পিরিয়ড</th>
                                            <th class="p-3.5 border-r border-slate-200 text-center w-28">সময়</th>
                                            <th class="p-3.5 border-r border-slate-200">বিষয় ও বিবরণ</th>
                                            <th class="p-3.5 border-r border-slate-200">শিক্ষক</th>
                                            <th class="p-3.5 text-center w-28">কক্ষ / ল্যাব</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-slate-800">
                                        @foreach($timetableSlots as $index => $slot)
                                            {{-- Insert Midday Break row after 3rd period --}}
                                            @if($loop->iteration == 4)
                                                <tr class="bg-amber-50/80 text-amber-950 font-bold border-y border-amber-200">
                                                    <td class="p-2.5 text-center border-r border-amber-200" colspan="2">
                                                        <span class="inline-flex items-center gap-1.5 text-xs text-amber-900">
                                                            <i class="fa-solid fa-mug-saucer text-amber-700 text-xs"></i>
                                                            <span>মধ্যাহ্ন বিরতি</span>
                                                        </span>
                                                    </td>
                                                    <td class="p-2.5 text-xs" colspan="3">
                                                        ১২:১৫ - ০১:০০ (টিফিন, নামাজ ও বিশ্রাম)
                                                    </td>
                                                </tr>
                                            @endif

                                            <tr class="hover:bg-slate-50/80 transition-colors {{ $slot->type === 'lab' ? 'bg-emerald-50/20' : '' }}">
                                                <td class="p-3.5 text-center font-bold bg-slate-50/60 border-r border-slate-200 text-slate-700">
                                                    {{ $loop->iteration }}ম পিরিয়ড
                                                </td>
                                                <td class="p-3.5 text-center font-mono border-r border-slate-200 text-slate-600 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i') }}
                                                </td>
                                                <td class="p-3.5 border-r border-slate-200">
                                                    <div class="font-bold text-[#005030] text-sm">
                                                        {{ $slot->subject->name ?? 'সাধারণ পাঠ' }}
                                                    </div>
                                                    <div class="text-[11px] text-slate-500 font-mono flex items-center gap-2 mt-0.5">
                                                        <span class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 font-bold">
                                                            {{ $slot->subject->code ?? 'SUB-101' }}
                                                        </span>
                                                        @if($slot->section)
                                                            <span>শাখা: {{ $slot->section->name }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="p-3.5 border-r border-slate-200">
                                                    @if($slot->teacher)
                                                        <div class="font-bold text-slate-800">
                                                            {{ $slot->teacher->full_name }}
                                                        </div>
                                                        <div class="text-[11px] text-slate-500">
                                                            {{ $slot->teacher->designation ?? 'শিক্ষক' }}
                                                        </div>
                                                    @else
                                                        <span class="text-slate-400 italic">অনির্ধারিত</span>
                                                    @endif
                                                </td>
                                                <td class="p-3.5 text-center">
                                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-semibold {{ $slot->type === 'lab' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-700' }}">
                                                        {{ $slot->room_number ?? 'শ্রেণিকক্ষ' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    @else
                        {{-- 2. FULL WEEK ACCORDION / MULTI-DAY SECTION VIEW --}}
                        <div class="space-y-6">
                            @foreach($days as $dayKey => $dayLabel)
                                @php
                                    $daySlots = $groupedSlots->get($dayKey, collect());
                                @endphp
                                @if($daySlots->isNotEmpty())
                                    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
                                        {{-- Day Header Strip --}}
                                        <div class="bg-[#005030] text-white px-5 py-3 flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-calendar-day text-amber-400 text-xs"></i>
                                                <h3 class="font-bold text-xs sm:text-sm uppercase tracking-wide">
                                                    {{ $dayLabel }} ({{ ucfirst($dayKey) }})
                                                </h3>
                                            </div>
                                            <span class="text-xs text-emerald-100 font-medium">
                                                {{ $daySlots->count() }}টি পিরিয়ড
                                            </span>
                                        </div>

                                        {{-- Timetable for this day --}}
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-left text-xs">
                                                <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                                                    <tr>
                                                        <th class="p-3 border-r border-slate-200 text-center w-24">পিরিয়ড</th>
                                                        <th class="p-3 border-r border-slate-200 text-center w-28">সময়</th>
                                                        <th class="p-3 border-r border-slate-200">বিষয় ও কোড</th>
                                                        <th class="p-3 border-r border-slate-200">শিক্ষক</th>
                                                        <th class="p-3 text-center w-28">কক্ষ / ল্যাব</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 text-slate-800">
                                                    @foreach($daySlots as $slot)
                                                        @if($loop->iteration == 4)
                                                            <tr class="bg-amber-50/80 text-amber-950 font-bold border-y border-amber-200">
                                                                <td class="p-2 text-center border-r border-amber-200" colspan="2">
                                                                    <span class="inline-flex items-center gap-1.5 text-xs text-amber-900">
                                                                        <i class="fa-solid fa-mug-saucer text-amber-700 text-xs"></i>
                                                                        <span>মধ্যাহ্ন বিরতি</span>
                                                                    </span>
                                                                </td>
                                                                <td class="p-2 text-xs" colspan="3">
                                                                    ১২:১৫ - ০১:০০ (টিফিন, নামাজ ও বিশ্রাম)
                                                                </td>
                                                            </tr>
                                                        @endif

                                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                                            <td class="p-3 text-center font-bold bg-slate-50/60 border-r border-slate-200 text-slate-700">
                                                                {{ $loop->iteration }}ম পিরিয়ড
                                                            </td>
                                                            <td class="p-3 text-center font-mono border-r border-slate-200 text-slate-600 whitespace-nowrap">
                                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i') }}
                                                            </td>
                                                            <td class="p-3 border-r border-slate-200">
                                                                <div class="font-bold text-[#005030]">
                                                                    {{ $slot->subject->name ?? 'পাঠদান' }}
                                                                </div>
                                                                <div class="text-[11px] text-slate-500 font-mono mt-0.5">
                                                                    {{ $slot->subject->code ?? 'SUB' }}
                                                                    @if($slot->section)
                                                                        • শাখা: {{ $slot->section->name }}
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td class="p-3 border-r border-slate-200">
                                                                @if($slot->teacher)
                                                                    <div class="font-bold text-slate-800">{{ $slot->teacher->full_name }}</div>
                                                                    <div class="text-[10px] text-slate-500">{{ $slot->teacher->designation ?? 'শিক্ষক' }}</div>
                                                                @else
                                                                    <span class="text-slate-400 italic">অনির্ধারিত</span>
                                                                @endif
                                                            </td>
                                                            <td class="p-3 text-center">
                                                                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold {{ $slot->type === 'lab' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-700' }}">
                                                                    {{ $slot->room_number ?? 'কক্ষ' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                @endif

            </div>

            {{-- Sidebar Column (Col 4) --}}
            <div class="lg:col-span-4 space-y-5 sidebar-card-stack no-print">
                
                {{-- Quick Summary Card (Recipe 4: Clean Secondary Anchor) --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-4 gsap-reveal hover-card-lift">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs font-bold border border-emerald-200">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-xs uppercase tracking-wider text-slate-900">রুটিন একনজরে</h3>
                            <span class="text-[11px] text-slate-500">{{ $selectedClass->name ?? 'শ্রেণি' }}</span>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-50">
                            <span class="text-slate-500">শিক্ষাবর্ষ</span>
                            <span class="font-bold text-slate-800">{{ $selectedYear->name ?? '২০২৬' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-50">
                            <span class="text-slate-500">মোট পিরিয়ড</span>
                            <span class="font-bold text-[#005030]">{{ $stats['total_slots'] }} টি</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-50">
                            <span class="text-slate-500">বিষয় সংখ্যা</span>
                            <span class="font-bold text-slate-800">{{ $stats['total_subjects'] }} টি</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-50">
                            <span class="text-slate-500">দায়িত্বপ্রাপ্ত শিক্ষক</span>
                            <span class="font-bold text-slate-800">{{ $stats['total_teachers'] }} জন</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 border-b border-slate-50">
                            <span class="text-slate-500">প্রতি পিরিয়ড সময়কাল</span>
                            <span class="font-bold text-slate-800">৪৫ মিনিট</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5">
                            <span class="text-slate-500">দৈনিক ক্লাস সময়</span>
                            <span class="font-mono text-slate-800 font-bold">১০:০০ - ০৩:১৫</span>
                        </div>
                    </div>
                </div>

                {{-- Exam Routine Downloads Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden gsap-reveal hover-card-lift">
                    <div class="bg-[#005030] text-white px-5 py-3.5 flex items-center justify-between">
                        <h3 class="font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-emerald-300"></i>
                            <span>পরীক্ষার রুটিন সার্কুলার</span>
                        </h3>
                        <span class="text-[10px] text-emerald-200 font-mono">PDF নোটিশ</span>
                    </div>
                    <div class="p-4 space-y-3 text-xs">
                        @if($routineNotices->isNotEmpty())
                            @foreach($routineNotices as $notice)
                                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl space-y-1.5 hover:border-slate-200 transition-colors">
                                    <div class="font-bold text-slate-900 leading-snug">
                                        {{ $notice->title }}
                                    </div>
                                    <div class="text-[11px] text-slate-500 flex items-center gap-2">
                                        <span>{{ $notice->published_at ? $notice->published_at->format('d M, Y') : 'সম্প্রতি' }}</span>
                                        @if($notice->type)
                                            <span class="text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded text-[10px] font-bold">
                                                {{ $notice->type === 'exam' ? 'পরীক্ষা' : 'একাডেমিক' }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="pt-1">
                                        <a href="{{ route('frontend.notices.show', $notice->slug) }}" class="text-[#005030] font-bold hover:underline inline-flex items-center gap-1 text-[11px]">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                            <span>বিজ্ঞপ্তি ও ডাউনলোড</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl space-y-1.5 hover:border-slate-200 transition-colors">
                                <div class="font-bold text-slate-900">বার্ষিক পরীক্ষা ২০২৬ এর সময়সূচি</div>
                                <div class="text-[11px] text-slate-500">সকল শ্রেণি (৬ষ্ঠ থেকে ১০ম শ্রেণি)</div>
                                <div class="pt-1">
                                    <a href="{{ route('frontend.notices.index') }}" class="text-[#005030] font-bold hover:underline inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-arrow-down text-xs"></i>
                                        <span>নোটিশ বোর্ড দেখুন</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Exam & Class Instructions Box --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3 text-xs gsap-reveal hover-card-lift">
                    <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h4 class="font-bold text-slate-900">শিক্ষার্থীদের সাধারণ নির্দেশনা</h4>
                    </div>
                    <ul class="space-y-2.5 text-slate-600 leading-relaxed text-xs">
                        <li class="flex items-start gap-2">
                            <span class="text-[#005030] font-bold">✓</span>
                            <span>ক্লাস শুরুর অন্তত ১০ মিনিট পূর্বে স্ব-স্ব শ্রেণিকক্ষে আসন গ্রহণ করতে হবে।</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-[#005030] font-bold">✓</span>
                            <span>নিয়মিত প্রাতিষ্ঠানিক ইউনিফর্ম ও আইডি কার্ড পরিধান বাধ্যতামূলক।</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-[#005030] font-bold">✓</span>
                            <span>পরীক্ষার হলে মোবাইল ফোন বা কোনো ধরনের ইলেকট্রনিক ডিভাইস সম্পূর্ণ নিষিদ্ধ।</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-[#005030] font-bold">✓</span>
                            <span>বিজ্ঞান ও আইসিটি ল্যাব ক্লাসে শিক্ষকের নির্দেশনা ব্যতীত যন্ত্রপাতি পরিচালনা নিষেধ।</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

    </div>
</section>

@endsection
