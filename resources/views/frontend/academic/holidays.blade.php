@extends('layouts.app')

@section('title', 'ছুটির তালিকা ও একাডেমিক ক্যালেন্ডার | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়') . '-এর বার্ষিক সরকারি ও প্রাতিষ্ঠানিক ছুটির পূর্ণাঙ্গ তালিকা, উৎসব অবকাশ এবং ইন্টারঅ্যাক্টিভ একাডেমিক ছুটির ক্যালেন্ডার।')

@section('content')

{{-- Breadcrumb Navigation --}}
<div class="bg-gray-100 border-b border-gray-200 py-3.5 text-xs text-gray-600" id="overview">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>প্রচ্ছদ</span>
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-500">একাডেমিক</span>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">ছুটির তালিকা ও ক্যালেন্ডার</span>
        </nav>
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">
            {{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }} • শিক্ষাবর্ষ: {{ $selectedYear->name ?? 'চলতি শিক্ষাবর্ষ' }}
        </span>
    </div>
</div>

{{-- Top Hero Banner (Adhering to Brand Guidelines & ui-refactor.md) --}}
<div class="page-hero-banner bg-[#005030] text-white py-8 sm:py-10 border-b border-emerald-900 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(16,185,129,0.12),transparent_70%)] pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                {{-- Signature Kicker with Pulsing Dot (Recipe 7) --}}
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-950/60 border border-emerald-600/40 text-emerald-300 text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>একাডেমিক ক্যালেন্ডার ও ছুটির নির্দেশিকা</span>
                </div>

                {{-- Plain-spoken Core Headline with Accent Span (Recipe 1) --}}
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight">
                    বাৎসরিক প্রাতিষ্ঠানিক ছুটির <span class="text-amber-400 font-black">ক্যালেন্ডার ও তালিকা</span>
                </h1>

                <p class="text-xs sm:text-sm text-emerald-100/90 font-medium mt-2 max-w-2xl leading-relaxed">
                    {{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}-এর সরকারি গেজেট, শিক্ষা বোর্ড এবং প্রাতিষ্ঠানিক সমন্বয় অনুযায়ী অনুমোদিত সকল ছুটির তারিখ, সময়কাল ও সুনির্দিষ্ট কারণ।
                </p>
            </div>

            {{-- Academic Session Selector Pill --}}
            <div class="flex items-center gap-2 bg-emerald-900/60 p-1.5 rounded-xl border border-emerald-700/60 self-start md:self-auto">
                <span class="text-xs text-emerald-200 font-semibold px-2">শিক্ষাবর্ষ:</span>
                <form method="GET" action="{{ route('frontend.academic.holidays') }}" id="session-switch-form">
                    <select name="academic_year_id"
                            onchange="document.getElementById('session-switch-form').submit()"
                            class="bg-white text-slate-900 text-xs font-bold rounded-lg px-3 py-1.5 border-0 focus:ring-2 focus:ring-amber-400 cursor-pointer shadow-xs">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ ($selectedYearId == $year->id) ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->is_current ? '• (বর্তমান)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Telemetry Metrics Bar (Recipe 3: Qualifying Strip) --}}
<div class="bg-white border-b border-slate-200/90 py-4 shadow-2xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 text-center sm:text-left">
            <div class="sm:pr-4 flex items-center gap-3 justify-center sm:justify-start">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center text-lg border border-emerald-200/80 shrink-0">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div>
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono">{{ $telemetry['total_events'] }}</span>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">মোট ছুটির উপলক্ষ</p>
                </div>
            </div>

            <div class="pt-3 sm:pt-0 sm:px-4 flex items-center gap-3 justify-center sm:justify-start">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-lg border border-amber-200/80 shrink-0">
                    <i class="fa-solid fa-sun"></i>
                </div>
                <div>
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono">{{ $telemetry['total_days'] }}</span>
                    <span class="text-xs font-medium text-slate-500">দিন</span>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">সর্বমোট অবকাশ দিন</p>
                </div>
            </div>

            <div class="pt-3 sm:pt-0 sm:px-4 flex items-center gap-3 justify-center sm:justify-start">
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-700 flex items-center justify-center text-lg border border-sky-200/80 shrink-0">
                    <i class="fa-solid fa-umbrella-beach"></i>
                </div>
                <div>
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono">{{ $telemetry['vacation_breaks'] }}</span>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">দীর্ঘমেয়াদি অবকাশ</p>
                </div>
            </div>

            <div class="pt-3 sm:pt-0 sm:pl-4 flex items-center gap-3 justify-center sm:justify-start">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-lg border border-indigo-200/80 shrink-0">
                    <i class="fa-solid fa-book-open-reader"></i>
                </div>
                <div>
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono">{{ $telemetry['exam_breaks'] }}</span>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">পরীক্ষা প্রস্তুতি ছুটি</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Calendar & Substance Section --}}
<section class="py-8 sm:py-12 bg-slate-50/70 min-h-[600px]"
         id="calendar-root"
         x-data="{
            events: {{ Js::from($calendarEvents) }},
            currentYear: {{ (int) ($selectedYear ? Carbon\Carbon::parse($selectedYear->start_date)->year : now()->year) }},
            currentMonth: {{ (int) (now()->year == ($selectedYear ? Carbon\Carbon::parse($selectedYear->start_date)->year : now()->year) ? now()->month : ($selectedYear ? Carbon\Carbon::parse($selectedYear->start_date)->month : now()->month)) }},
            selectedDate: '{{ now()->toDateString() }}',
            selectedEvent: null,
            filterCategory: 'all',
            searchQuery: '',
            activeTab: 'calendar',
            monthNames: ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'],
            monthNamesEn: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            dayNames: ['রবি', 'সোম', 'মঙ্গল', 'বুধ', 'বৃহঃ', 'শুক্র', 'শনি'],
            
            init() {
                // Find initial event covering today or the first upcoming event
                this.updateSelectedDate(this.selectedDate);
                if (!this.selectedEvent && this.events.length > 0) {
                    var closest = this.events.find(e => e.start_date >= this.selectedDate) || this.events[0];
                    if (closest) {
                        this.selectEvent(closest);
                    }
                }
            },
            
            // Generate grid cells for currently selected month
            get calendarDays() {
                var year = this.currentYear;
                var month = this.currentMonth; // 1-indexed
                var firstDay = new Date(year, month - 1, 1).getDay(); // 0 is Sunday
                var totalDays = new Date(year, month, 0).getDate();
                
                var days = [];
                // Empty padding cells before 1st of month
                for (var p = 0; p < firstDay; p++) {
                    days.push({ day: null, dateStr: null, isCurrentMonth: false, holidays: [] });
                }
                
                // Actual days of the month
                for (var d = 1; d <= totalDays; d++) {
                    var mm = (month < 10 ? '0' : '') + month;
                    var dd = (d < 10 ? '0' : '') + d;
                    var dateStr = year + '-' + mm + '-' + dd;
                    
                    var matchingHolidays = this.events.filter(e => {
                        return dateStr >= e.start_date && dateStr <= e.end_date;
                    });
                    
                    days.push({
                        day: d,
                        dateStr: dateStr,
                        isCurrentMonth: true,
                        isToday: dateStr === '{{ now()->toDateString() }}',
                        holidays: matchingHolidays,
                        isHoliday: matchingHolidays.length > 0,
                        primaryHoliday: matchingHolidays[0] || null
                    });
                }
                return days;
            },
            
            prevMonth() {
                if (this.currentMonth === 1) {
                    this.currentMonth = 12;
                    this.currentYear--;
                } else {
                    this.currentMonth--;
                }
            },
            
            nextMonth() {
                if (this.currentMonth === 12) {
                    this.currentMonth = 1;
                    this.currentYear++;
                } else {
                    this.currentMonth++;
                }
            },
            
            goToToday() {
                var today = new Date();
                this.currentYear = today.getFullYear();
                this.currentMonth = today.getMonth() + 1;
                this.updateSelectedDate('{{ now()->toDateString() }}');
            },
            
            updateSelectedDate(dateStr) {
                this.selectedDate = dateStr;
                var matched = this.events.filter(e => dateStr >= e.start_date && dateStr <= e.end_date);
                this.selectedEvent = matched.length > 0 ? matched[0] : null;
            },
            
            selectEvent(evt) {
                this.selectedEvent = evt;
                this.selectedDate = evt.start_date;
                // Switch calendar month/year if event belongs to another month
                var parts = evt.start_date.split('-');
                if (parts.length === 3) {
                    this.currentYear = parseInt(parts[0], 10);
                    this.currentMonth = parseInt(parts[1], 10);
                }
                // Scroll calendar into view smoothly if on mobile
                if (window.innerWidth < 768) {
                    var el = document.getElementById('selected-reason-card');
                    if (el) { el.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
                }
            },
            
            formatDateBn(dateStr) {
                if (!dateStr) return '';
                var parts = dateStr.split('-');
                if (parts.length !== 3) return dateStr;
                var y = parts[0], m = parseInt(parts[1], 10), d = parseInt(parts[2], 10);
                return d + ' ' + this.monthNames[m - 1] + ', ' + y;
            },
            
            get filteredEvents() {
                var q = this.searchQuery.toLowerCase().trim();
                var cat = this.filterCategory;
                return this.events.filter(e => {
                    var matchCat = (cat === 'all') || (e.type === cat);
                    var matchQ = !q || (e.title.toLowerCase().includes(q) || (e.description && e.description.toLowerCase().includes(q)) || e.start_date.includes(q));
                    return matchCat && matchQ;
                });
            }
         }">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- View Switcher & Action Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-4">
            <div>
                <span class="text-xs font-bold text-[#005030] uppercase tracking-wider">ইন্টারেক্টিভ ইন্টারফেস</span>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    ছুটির দিন নির্বাচন ও কারণ যাচাই
                </h2>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                {{-- View Mode Toggle --}}
                <div class="inline-flex rounded-xl bg-slate-200/80 p-1 border border-slate-300/80 text-xs font-bold">
                    <button type="button"
                            @click="activeTab = 'calendar'"
                            :class="activeTab === 'calendar' ? 'bg-white text-[#005030] shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-calendar"></i>
                        <span>ক্যালেন্ডার ভিউ</span>
                    </button>
                    <button type="button"
                            @click="activeTab = 'timeline'"
                            :class="activeTab === 'timeline' ? 'bg-white text-[#005030] shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-list-check"></i>
                        <span>বার্ষিক তালিকা</span>
                    </button>
                </div>

                {{-- Print Button --}}
                <button type="button"
                        onclick="window.print()"
                        class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 hover:text-slate-900 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs cursor-pointer"
                        title="ছুটির তালিকা প্রিন্ট করুন">
                    <i class="fa-solid fa-print text-slate-500"></i>
                    <span class="hidden sm:inline">প্রিন্ট</span>
                </button>
            </div>
        </div>

        @if($holidays->isEmpty())
            {{-- Clean Empty State --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-2xs space-y-3 max-w-md mx-auto my-12">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-50 text-[#005030] flex items-center justify-center text-3xl border border-emerald-200">
                    <i class="fa-solid fa-calendar-xmark"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">কোনো নির্ধারিত ছুটি পাওয়া যায়নি</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    নির্বাচিত শিক্ষাবর্ষের ({{ $selectedYear->name ?? 'অজ্ঞাত' }}) জন্য এখনো কোনো সরকারি বা প্রাতিষ্ঠানিক ছুটির তালিকা ডাটাবেজে অন্তর্ভুক্ত করা হয়নি।
                </p>
                <div class="pt-2">
                    <a href="{{ route('frontend.academic.routine') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#005030] hover:underline">
                        <span>ক্লাস রুটিন দেখুন</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        @else

            {{-- 1. TAB: INTERACTIVE CALENDAR & REASON INSPECTOR (Recipe 2: Break the Symmetry 65/35 Split) --}}
            <div x-show="activeTab === 'calendar'" x-transition:enter="transition ease-out duration-200" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- Left Side: The Interactive Monthly Calendar Grid (8 Columns) --}}
                <div class="lg:col-span-8 bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
                    
                    {{-- Calendar Header / Month Switcher --}}
                    <div class="p-5 sm:p-6 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center text-xl shrink-0">
                                <i class="fa-regular fa-calendar-check"></i>
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-white tracking-tight flex items-center gap-2">
                                    <span x-text="monthNames[currentMonth - 1]"></span>
                                    <span class="text-amber-400 font-mono" x-text="currentYear"></span>
                                </h3>
                                <p class="text-xs text-slate-400" x-text="monthNamesEn[currentMonth - 1] + ' ' + currentYear"></p>
                            </div>
                        </div>

                        {{-- Month Navigation Buttons --}}
                        <div class="flex items-center gap-2">
                            <button type="button"
                                    @click="goToToday()"
                                    class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition cursor-pointer">
                                আজ
                            </button>
                            <div class="inline-flex rounded-lg bg-slate-800 border border-slate-700 p-0.5">
                                <button type="button"
                                        @click="prevMonth()"
                                        class="p-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-md transition cursor-pointer"
                                        title="পূর্ববর্তী মাস">
                                    <i class="fa-solid fa-chevron-left text-xs"></i>
                                </button>
                                <button type="button"
                                        @click="nextMonth()"
                                        class="p-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-md transition cursor-pointer"
                                        title="পরবর্তী মাস">
                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Day Names Bar (Sunday through Saturday) --}}
                    <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50/90 text-center text-xs font-bold text-slate-600 py-3">
                        <div class="text-slate-500">রবি</div>
                        <div>সোম</div>
                        <div>মঙ্গল</div>
                        <div>বুধ</div>
                        <div>বৃহঃ</div>
                        <div class="text-emerald-700">শুক্র</div>
                        <div class="text-amber-700">শনি</div>
                    </div>

                    {{-- Calendar Dates Grid --}}
                    <div class="grid grid-cols-7 gap-px bg-slate-200 p-px">
                        <template x-for="(cell, idx) in calendarDays" :key="idx">
                            <div @click="cell.isCurrentMonth && updateSelectedDate(cell.dateStr)"
                                 :class="{
                                    'bg-white cursor-pointer hover:bg-emerald-50/40 transition-colors': cell.isCurrentMonth,
                                    'bg-slate-50/70 cursor-default opacity-40': !cell.isCurrentMonth,
                                    'ring-2 ring-inset ring-[#005030] bg-emerald-50/60 font-black': cell.isCurrentMonth && cell.dateStr === selectedDate,
                                    'bg-amber-50/30': cell.isCurrentMonth && cell.isHoliday && cell.dateStr !== selectedDate
                                 }"
                                 class="min-h-[82px] sm:min-h-[96px] p-2 flex flex-col justify-between relative group">
                                
                                {{-- Top Row: Date Number and Status Indicator --}}
                                <div class="flex items-center justify-between">
                                    <span class="text-xs sm:text-sm font-bold font-mono inline-flex items-center justify-center w-6 h-6 rounded-full"
                                          :class="{
                                            'bg-[#005030] text-white': cell.isCurrentMonth && cell.isToday,
                                            'text-slate-900': cell.isCurrentMonth && !cell.isToday && !cell.isHoliday,
                                            'text-emerald-800 font-extrabold': cell.isCurrentMonth && cell.isHoliday && !cell.isToday,
                                            'text-slate-300': !cell.isCurrentMonth
                                          }"
                                          x-text="cell.day">
                                    </span>

                                    {{-- Holiday Type Icon Pill for cell --}}
                                    <template x-if="cell.isHoliday">
                                        <span class="w-2 h-2 rounded-full"
                                              :class="{
                                                'bg-emerald-500': cell.primaryHoliday && cell.primaryHoliday.type === 'holiday',
                                                'bg-sky-500': cell.primaryHoliday && cell.primaryHoliday.type === 'exam_break',
                                                'bg-amber-500': cell.primaryHoliday && cell.primaryHoliday.type === 'weekend',
                                                'bg-rose-500': cell.primaryHoliday && cell.primaryHoliday.type === 'unplanned'
                                              }">
                                        </span>
                                    </template>
                                </div>

                                {{-- Bottom Content: Holiday Badge on cell --}}
                                <div class="mt-1 space-y-1">
                                    <template x-if="cell.isHoliday && cell.primaryHoliday">
                                        <div class="text-[10px] sm:text-[11px] font-semibold leading-tight line-clamp-2 px-1.5 py-0.5 rounded border"
                                             :class="{
                                                'bg-emerald-100/80 text-emerald-900 border-emerald-300': cell.primaryHoliday.type === 'holiday',
                                                'bg-sky-100/80 text-sky-900 border-sky-300': cell.primaryHoliday.type === 'exam_break',
                                                'bg-amber-100/80 text-amber-900 border-amber-300': cell.primaryHoliday.type === 'weekend',
                                                'bg-rose-100/80 text-rose-900 border-rose-300': cell.primaryHoliday.type === 'unplanned'
                                             }">
                                            <span x-text="cell.primaryHoliday.title"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Calendar Footer Legend --}}
                    <div class="p-3 sm:p-4 bg-slate-50 border-t border-slate-200/90 flex flex-wrap items-center justify-between text-xs text-slate-600 gap-2">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center gap-1.5 font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span>সরকারি ও উৎসব ছুটি</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                                <span>পরীক্ষা প্রস্তুতি অবকাশ</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <span>সাপ্তাহিক / সমন্বিত ছুটি</span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                <span>বিশেষ আদেশ / জরুরি ছুটি</span>
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">ক্যালেন্ডারের তারিখে ক্লিক করে কারণ দেখুন</span>
                    </div>
                </div>

                {{-- Right Side: The Reason & Details Inspector (4 Columns) (Recipe 4: Pick the Winner) --}}
                <div class="lg:col-span-4 space-y-6" id="selected-reason-card">
                    
                    {{-- Active Selected Date Showcase Card --}}
                    <div class="bg-white border-2 border-[#005030]/20 rounded-2xl p-6 shadow-sm relative overflow-hidden transition-all">
                        {{-- Top Flag Accent --}}
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-sm font-bold border border-emerald-200">
                                    <i class="fa-regular fa-clock"></i>
                                </div>
                                <div>
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">নির্বাচিত তারিখ</span>
                                    <h4 class="text-sm font-bold text-slate-900 font-mono" x-text="formatDateBn(selectedDate)"></h4>
                                </div>
                            </div>

                            {{-- Closure Status Pill --}}
                            <template x-if="selectedEvent">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                    <span>বিদ্যালয় বন্ধ</span>
                                </span>
                            </template>
                            <template x-if="!selectedEvent">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>নিয়মিত ক্লাস</span>
                                </span>
                            </template>
                        </div>

                        {{-- Case A: Date HAS an Official Holiday with Proper Reason --}}
                        <template x-if="selectedEvent">
                            <div class="space-y-4">
                                {{-- Holiday Title --}}
                                <div>
                                    <span class="inline-flex px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider rounded border mb-1.5"
                                          :class="{
                                            'bg-emerald-50 text-emerald-800 border-emerald-200': selectedEvent.type === 'holiday',
                                            'bg-sky-50 text-sky-800 border-sky-200': selectedEvent.type === 'exam_break',
                                            'bg-amber-50 text-amber-800 border-amber-200': selectedEvent.type === 'weekend',
                                            'bg-rose-50 text-rose-800 border-rose-200': selectedEvent.type === 'unplanned'
                                          }"
                                          x-text="selectedEvent.type_label">
                                    </span>
                                    <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 leading-snug" x-text="selectedEvent.title"></h3>
                                </div>

                                {{-- Date Duration Box --}}
                                <div class="bg-slate-50 rounded-xl p-3 border border-slate-200/80 flex items-center justify-between text-xs">
                                    <div>
                                        <span class="text-slate-500 text-[11px] block font-medium">ছুটির ব্যাপ্তিকাল:</span>
                                        <span class="font-bold text-slate-800 font-mono" x-text="selectedEvent.start_date + ' থেকে ' + selectedEvent.end_date"></span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-slate-500 text-[11px] block font-medium">সময়সীমা:</span>
                                        <span class="font-bold text-[#005030] text-sm font-mono" x-text="selectedEvent.duration_days + ' দিন'"></span>
                                    </div>
                                </div>

                                {{-- PROPER REASON FROM BACKEND LOGIC (The Core User Requirement) --}}
                                <div class="space-y-1.5">
                                    <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-info text-[#005030]"></i>
                                        <span>ছুটির আনুষ্ঠানিক কারণ ও বিবরণ:</span>
                                    </span>
                                    <div class="p-3.5 bg-emerald-50/50 rounded-xl border border-emerald-200/60 text-xs sm:text-sm text-slate-700 leading-relaxed font-normal"
                                         x-text="selectedEvent.description">
                                    </div>
                                </div>

                                {{-- Institutional Guidance Note --}}
                                <div class="text-[11px] text-slate-500 bg-slate-50 rounded-lg p-2.5 border border-slate-200 flex items-start gap-2">
                                    <i class="fa-solid fa-shield-halved text-emerald-700 mt-0.5"></i>
                                    <span>ছুটিকালীন সময়ে বিদ্যালয়ের একাডেমিক ক্লাস স্থগিত থাকবে। অবকাশ সমাপ্তির পরবর্তী কার্যদিবসে যথারীতি পাঠদান শুরু হবে।</span>
                                </div>
                            </div>
                        </template>

                        {{-- Case B: Date has NO holiday scheduled (Regular Academic Day) --}}
                        <template x-if="!selectedEvent">
                            <div class="py-6 text-center space-y-3">
                                <div class="w-12 h-12 mx-auto rounded-full bg-emerald-50 text-[#005030] flex items-center justify-center text-xl border border-emerald-200">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>
                                <h4 class="text-base font-bold text-slate-900">নিয়মিত পাঠদান ও একাডেমিক দিবস</h4>
                                <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">
                                    নির্বাচিত তারিখে ({{ now()->format('Y-m-d') }}) কোনো প্রাতিষ্ঠানিক ছুটি নেই। সরকারি পাঠ্যক্রম অনুযায়ী সকল শ্রেণির ক্লাস ও পরীক্ষা রুটিনমাফিক পরিচালিত হবে।
                                </p>
                                <a href="{{ route('frontend.academic.routine') }}"
                                   class="inline-flex items-center gap-1.5 text-xs font-bold text-[#005030] hover:text-emerald-800 transition">
                                    <span>দৈনিক ক্লাস রুটিন দেখুন</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </template>
                    </div>

                    {{-- Next Upcoming Holiday Spotlight Card --}}
                    @if($nextHoliday)
                        @php
                            $nextDuration = $nextHoliday->start_date->diffInDays($nextHoliday->end_date) + 1;
                        @endphp
                        <div class="bg-gradient-to-br from-emerald-950 to-slate-900 text-white rounded-2xl p-5 shadow-xs border border-emerald-800/60 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-800/80 text-emerald-200 text-[10px] font-bold uppercase tracking-wider">
                                    <i class="fa-solid fa-bell text-amber-400"></i>
                                    <span>আসন্ন ছুটি</span>
                                </span>
                                <span class="text-xs font-mono text-emerald-300">{{ $nextHoliday->start_date->format('M d, Y') }}</span>
                            </div>

                            <h4 class="text-base font-bold text-white line-clamp-1">
                                {{ $nextHoliday->title }}
                            </h4>

                            <p class="text-xs text-slate-300 line-clamp-2 leading-relaxed">
                                {{ $nextHoliday->description }}
                            </p>

                            <div class="flex items-center justify-between pt-1 border-t border-emerald-800/60 text-xs">
                                <span class="text-emerald-300 font-mono font-semibold">{{ $nextDuration }} দিন অবকাশ</span>
                                <button type="button"
                                        @click="selectEvent({{ Js::from([
                                            'id' => $nextHoliday->id,
                                            'title' => $nextHoliday->title,
                                            'start_date' => $nextHoliday->start_date->toDateString(),
                                            'end_date' => $nextHoliday->end_date->toDateString(),
                                            'type' => $nextHoliday->type,
                                            'type_label' => match ($nextHoliday->type) {
                                                'holiday' => 'সরকারি ছুটি',
                                                'exam_break' => 'পরীক্ষার প্রস্তুতি ছুটি',
                                                'weekend' => 'সাপ্তাহিক ছুটি',
                                                default => 'বিশেষ ছুটি',
                                            },
                                            'duration_days' => $nextDuration,
                                            'description' => $nextHoliday->description,
                                        ]) }})"
                                        class="text-amber-400 hover:text-amber-300 font-bold transition flex items-center gap-1 cursor-pointer">
                                    <span>ক্যালেন্ডারে দেখুন</span>
                                    <i class="fa-solid fa-angle-right text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                </div>

            </div>

            {{-- 2. TAB: ANNUAL TIMELINE & FULL LIST VIEW --}}
            <div x-show="activeTab === 'timeline'" x-transition:enter="transition ease-out duration-200" class="space-y-6">
                
                {{-- Filter & Search Toolbar --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    {{-- Search Input --}}
                    <div class="relative flex-1 max-w-md">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text"
                               x-model="searchQuery"
                               placeholder="ছুটির নাম বা কারণ দিয়ে অনুসন্ধান করুন..."
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-[#005030] focus:border-[#005030] transition">
                    </div>

                    {{-- Category Filters --}}
                    <div class="flex flex-wrap items-center gap-1.5 text-xs font-bold">
                        <button type="button"
                                @click="filterCategory = 'all'"
                                :class="filterCategory === 'all' ? 'bg-[#005030] text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-3 py-1.5 rounded-lg transition cursor-pointer">
                            সকল ছুটি
                        </button>
                        <button type="button"
                                @click="filterCategory = 'holiday'"
                                :class="filterCategory === 'holiday' ? 'bg-emerald-700 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-3 py-1.5 rounded-lg transition cursor-pointer">
                            সরকারি ছুটি
                        </button>
                        <button type="button"
                                @click="filterCategory = 'exam_break'"
                                :class="filterCategory === 'exam_break' ? 'bg-sky-700 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="px-3 py-1.5 rounded-lg transition cursor-pointer">
                            পরীক্ষা বিরতি
                        </button>
                    </div>
                </div>

                {{-- Chronological Holiday Cards Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <template x-for="event in filteredEvents" :key="event.id">
                        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs hover:shadow-md hover:border-emerald-300 transition-all flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider border"
                                          :class="{
                                            'bg-emerald-50 text-emerald-800 border-emerald-200': event.type === 'holiday',
                                            'bg-sky-50 text-sky-800 border-sky-200': event.type === 'exam_break',
                                            'bg-amber-50 text-amber-800 border-amber-200': event.type === 'weekend',
                                            'bg-rose-50 text-rose-800 border-rose-200': event.type === 'unplanned'
                                          }"
                                          x-text="event.type_label">
                                    </span>
                                    <span class="text-xs font-mono font-bold text-[#005030]" x-text="event.duration_days + ' দিন'"></span>
                                </div>

                                <h4 class="text-base font-bold text-slate-900" x-text="event.title"></h4>

                                <div class="text-xs text-slate-500 font-mono flex items-center gap-1.5">
                                    <i class="fa-regular fa-calendar text-slate-400"></i>
                                    <span x-text="event.start_date + ' হতে ' + event.end_date"></span>
                                </div>

                                <p class="text-xs text-slate-600 leading-relaxed line-clamp-3 pt-1 border-t border-slate-100"
                                   x-text="event.description">
                                </p>
                            </div>

                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400 font-medium">একাডেমিক ক্যালেন্ডার</span>
                                <button type="button"
                                        @click="activeTab = 'calendar'; selectEvent(event)"
                                        class="text-xs font-bold text-[#005030] hover:underline flex items-center gap-1 cursor-pointer">
                                    <span>ক্যালেন্ডারে নির্বাচন</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Empty Search Results Message --}}
                <div x-show="filteredEvents.length === 0" class="bg-white border border-slate-200 rounded-2xl p-8 text-center text-xs text-slate-500">
                    অনুসন্ধানের সাথে মেলে এমন কোনো ছুটির তথ্য পাওয়া যায়নি।
                </div>

            </div>

        @endif

    </div>
</section>

{{-- Print Styles --}}
<style>
@media print {
    header, footer, .page-hero-banner, .snhs-nav-container, button, form {
        display: none !important;
    }
    body, section {
        background: white !important;
        color: black !important;
    }
    #calendar-root {
        padding: 0 !important;
    }
    .shadow-xs, .shadow-2xs, .shadow-sm, .shadow-md {
        box-shadow: none !important;
        border: 1px solid #ccc !important;
    }
}
</style>

@endsection
