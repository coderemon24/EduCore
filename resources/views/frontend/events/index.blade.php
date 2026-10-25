@extends('layouts.app')

@section('title', 'বাৎসরিক ইভেন্ট ক্যালেন্ডার ও আয়োজনসমূহ | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের বাৎসরিক ক্রীড়া প্রতিযোগিতা, আন্তঃস্কুল বিজ্ঞান মেলা, জাতীয় দিবস ও সাংস্কৃতিক উৎসব সমূহের সময়সূচি ও বিস্তারিত তথ্য।')

@section('content')

{{-- 1. Breadcrumb (Clean & Minimalist Layout Matching Classes Page) --}}
<div class="bg-gray-100 border-b border-gray-200 py-3.5 text-xs text-gray-600" id="overview">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>প্রচ্ছদ</span>
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-500">ইভেন্ট ও আয়োজন</span>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">বাৎসরিক ক্যালেন্ডার</span>
        </nav>
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">
            {{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }} • শিক্ষাবর্ষ: ২০২৬
        </span>
    </div>
</div>

{{-- 2. Top Hero Banner: Matches Reference Image Clean Green Header with GSAP Entrance --}}
<div class="page-hero-banner bg-[#005030] text-white py-8 sm:py-10 border-b border-emerald-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Pill Badge --}}
        <div class="page-hero-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-[#005030] text-xs font-bold shadow-xs">
            <i class="fa-solid fa-calendar-days text-[#005030]"></i>
            <span>ইভেন্ট ও আয়োজন</span>
        </div>

        {{-- Page Heading --}}
        <h1 class="page-hero-title text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white mt-3 mb-2 tracking-tight">
            বাৎসরিক ইভেন্ট ক্যালেন্ডার ও <span class="text-emerald-300 font-bold">বর্ণাঢ্য আয়োজনসমূহ</span>
        </h1>

        {{-- Sub-narrative context --}}
        <p class="page-hero-subtitle text-xs sm:text-sm text-emerald-100/90 font-medium max-w-3xl leading-relaxed">
            খেলাধুলা, বিজ্ঞান মেলা, সাংস্কৃতিক উৎসব, জাতীয় দিবস ও সহশিক্ষা কার্যক্রমের সার্বিক সময়সূচি ও প্রজ্ঞাপন
        </p>
    </div>
</div>

{{-- 3. Main Content Section --}}
<section class="py-8 sm:py-12 bg-slate-50/70 min-h-[550px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Status Tabs & Search Bar Strip --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-3 sm:p-4 shadow-2xs space-y-3.5 gsap-reveal">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                
                {{-- Status Filter Tabs (Upcoming, Completed, All) --}}
                <div class="inline-flex p-1 rounded-xl bg-slate-100 border border-slate-200 text-xs font-semibold self-start sm:self-auto">
                    <a href="{{ route('frontend.events.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
                       class="px-3.5 py-1.5 rounded-lg transition-all {{ $activeStatus === 'all' ? 'bg-[#005030] text-white shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        <span>সকল ইভেন্ট</span>
                        <span class="ml-1 text-[10px] px-1.5 py-0.2 rounded-full {{ $activeStatus === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                            {{ $statusCounts['all'] }}
                        </span>
                    </a>
                    <a href="{{ route('frontend.events.index', array_merge(request()->except('status', 'page'), ['status' => 'upcoming'])) }}"
                       class="px-3.5 py-1.5 rounded-lg transition-all {{ $activeStatus === 'upcoming' ? 'bg-[#005030] text-white shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        <span>চলমান ও আসন্ন</span>
                        <span class="ml-1 text-[10px] px-1.5 py-0.2 rounded-full {{ $activeStatus === 'upcoming' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                            {{ $statusCounts['upcoming'] }}
                        </span>
                    </a>
                    <a href="{{ route('frontend.events.index', array_merge(request()->except('status', 'page'), ['status' => 'completed'])) }}"
                       class="px-3.5 py-1.5 rounded-lg transition-all {{ $activeStatus === 'completed' ? 'bg-[#005030] text-white shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                        <span>সম্পন্ন</span>
                        <span class="ml-1 text-[10px] px-1.5 py-0.2 rounded-full {{ $activeStatus === 'completed' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                            {{ $statusCounts['completed'] }}
                        </span>
                    </a>
                </div>

                {{-- Keyword Search Form --}}
                <form action="{{ route('frontend.events.index') }}" method="GET" class="relative flex-1 sm:max-w-xs">
                    @if(!empty($activeStatus) && $activeStatus !== 'all')
                        <input type="hidden" name="status" value="{{ $activeStatus }}">
                    @endif
                    @if(!empty($activeCategory))
                        <input type="hidden" name="category" value="{{ $activeCategory }}">
                    @endif
                    <div class="relative">
                        <input type="text"
                               name="q"
                               value="{{ $searchQuery }}"
                               placeholder="ইভেন্ট বা ভেন্যু খুঁজুন..."
                               class="w-full pl-9 pr-9 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#005030] focus:ring-1 focus:ring-[#005030] transition-all">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </div>
                        @if(!empty($searchQuery))
                            <a href="{{ route('frontend.events.index', request()->except('q', 'page')) }}"
                               class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-rose-600">
                                <i class="fa-solid fa-circle-xmark text-xs"></i>
                            </a>
                        @endif
                    </div>
                </form>

            </div>

            {{-- Category Filter Pills (Horizontal Scroll) --}}
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2 overflow-x-auto no-scrollbar">
                <div class="flex items-center gap-1.5 min-w-max">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mr-1">বিভাগ:</span>
                    
                    <a href="{{ route('frontend.events.index', request()->except('category', 'page')) }}"
                       class="px-2.5 py-1 rounded-lg text-xs font-medium transition-all {{ empty($activeCategory) ? 'bg-slate-900 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        সকল বিভাগ
                    </a>

                    @foreach($categoriesWithCounts as $cSlug => $cat)
                        <a href="{{ route('frontend.events.index', array_merge(request()->except('category', 'page'), ['category' => $cSlug])) }}"
                           class="px-2.5 py-1 rounded-lg text-xs font-medium transition-all flex items-center gap-1 {{ $activeCategory === $cSlug ? 'bg-[#005030] text-white font-bold shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            <i class="fa-solid {{ $cat['icon'] }} text-[10px] {{ $activeCategory === $cSlug ? 'text-emerald-200' : 'text-slate-400' }}"></i>
                            <span>{{ $cat['name'] }}</span>
                            <span class="text-[10px] opacity-80">({{ $cat['count'] }})</span>
                        </a>
                    @endforeach
                </div>

                {{-- Reset Link if Filters Active --}}
                @if(!empty($activeCategory) || !empty($searchQuery) || $activeStatus !== 'all')
                    <a href="{{ route('frontend.events.index') }}"
                       class="text-xs text-rose-600 hover:text-rose-700 font-bold whitespace-nowrap px-2 py-1 flex items-center gap-1">
                        <i class="fa-solid fa-rotate-left text-[11px]"></i>
                        <span>রিসেট</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Active Filter Alert Info --}}
        @if(!empty($activeCategory) || !empty($searchQuery) || $activeStatus !== 'all')
            <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-200/80 text-xs text-slate-700 flex items-center justify-between gap-3 gsap-reveal">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-filter text-[#005030]"></i>
                    <span>
                        ফিল্টার করা ফলাফল: 
                        @if($activeStatus !== 'all')
                            অবস্থা <span class="font-bold text-slate-900">"{{ $activeStatus === 'upcoming' ? 'চলমান ও আসন্ন' : 'সম্পন্ন' }}"</span>
                        @endif
                        @if(!empty($activeCategory) && isset($categoriesWithCounts[$activeCategory]))
                            • বিভাগ <span class="font-bold text-slate-900">"{{ $categoriesWithCounts[$activeCategory]['name'] }}"</span>
                        @endif
                        @if(!empty($searchQuery))
                            • অনুসন্ধান <span class="font-bold text-slate-900">"{{ $searchQuery }}"</span>
                        @endif
                    </span>
                </div>
                <span class="text-slate-500 font-semibold">{{ $eventsList->total() }}টি ইভেন্ট</span>
            </div>
        @endif

        {{-- Main Editorial Layout (Asymmetric 65/35 Split) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Left Dominant Content (8 Cols) --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- 1. Flagship Featured Event (Pick the Winner Recipe 4) --}}
                @if($featuredEvent)
                    <div class="event-card bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-xs hover-card-lift gsap-reveal">
                        <div class="grid grid-cols-1 md:grid-cols-12">
                            
                            {{-- Visual Image Container (5 Cols) --}}
                            <div class="md:col-span-5 relative bg-slate-100 min-h-[220px] md:min-h-[280px] overflow-hidden">
                                <img src="{{ asset($featuredEvent['image_url']) }}"
                                     alt="{{ $featuredEvent['title'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/images/news-1.jpg') }}';">
                                
                                {{-- Pulsing Status Badge --}}
                                <div class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/95 backdrop-blur-xs text-[#005030] text-[11px] font-bold shadow-xs">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>{{ $featuredEvent['status_text'] }}</span>
                                </div>

                                {{-- Category Tag --}}
                                <div class="absolute bottom-3 left-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[11px] font-bold bg-slate-950/80 text-white backdrop-blur-xs">
                                        {{ $featuredEvent['category'] }}
                                    </span>
                                </div>
                            </div>

                            {{-- Narrative & Details (7 Cols) --}}
                            <div class="md:col-span-7 p-5 sm:p-6 flex flex-col justify-between space-y-4">
                                <div class="space-y-2.5">
                                    {{-- Calendar Date Pill Bar --}}
                                    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-[#005030] font-bold font-mono">
                                            <i class="fa-regular fa-calendar-check text-[11px]"></i>
                                            <span>{{ $featuredEvent['display_date'] }}</span>
                                        </span>
                                        <span>•</span>
                                        <span class="flex items-center gap-1">
                                            <i class="fa-regular fa-clock text-slate-400"></i>
                                            <span>{{ $featuredEvent['time'] }}</span>
                                        </span>
                                    </div>

                                    <h2 class="text-base sm:text-lg lg:text-xl font-bold text-slate-900 leading-snug hover:text-[#005030] transition-colors">
                                        <a href="{{ route('frontend.events.show', $featuredEvent['slug']) }}">
                                            {{ $featuredEvent['title'] }}
                                        </a>
                                    </h2>

                                    <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">
                                        {{ $featuredEvent['excerpt'] }}
                                    </p>
                                </div>

                                {{-- Venue & CTA Footer --}}
                                <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-center gap-1.5 text-xs text-slate-600">
                                        <i class="fa-solid fa-location-dot text-[#005030]"></i>
                                        <span class="line-clamp-1">{{ $featuredEvent['venue'] }}</span>
                                    </div>

                                    <a href="{{ route('frontend.events.show', $featuredEvent['slug']) }}"
                                       class="inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#005030] hover:bg-emerald-950 text-white text-xs font-bold shadow-2xs transition-all whitespace-nowrap">
                                        <span>বিস্তারিত সময়সূচি</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>
                @endif

                {{-- 2. Secondary Events Grid (2 Columns) --}}
                @if($eventsList->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach($eventsList as $event)
                            <div class="event-card bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-2xs hover-card-lift flex flex-col justify-between gsap-reveal">
                                
                                {{-- Card Media Header --}}
                                <div class="relative bg-slate-100 h-44 overflow-hidden group">
                                    <img src="{{ asset($event['image_url']) }}"
                                         alt="{{ $event['title'] }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         onerror="this.onerror=null; this.src='{{ asset('assets/images/news-2.jpg') }}';">
                                    
                                    {{-- Date Box Overlay --}}
                                    <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-xs rounded-xl p-1.5 text-center min-w-[50px] shadow-xs border border-slate-200/60">
                                        <span class="block text-sm font-extrabold text-[#005030] leading-none">{{ $event['date_day'] }}</span>
                                        <span class="block text-[10px] font-bold text-slate-600 leading-tight mt-0.5">{{ $event['date_month'] }}</span>
                                    </div>

                                    {{-- Status & Category Pills --}}
                                    <div class="absolute top-3 right-3 flex flex-col items-end gap-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $event['status_badge'] }} border shadow-2xs">
                                            {{ $event['status_text'] }}
                                        </span>
                                    </div>

                                    <div class="absolute bottom-2.5 left-3">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-950/75 text-white backdrop-blur-xs">
                                            {{ $event['category'] }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Card Body Content --}}
                                <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between space-y-3">
                                    <div class="space-y-2">
                                        <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-[#005030] leading-snug line-clamp-2 transition-colors">
                                            <a href="{{ route('frontend.events.show', $event['slug']) }}">
                                                {{ $event['title'] }}
                                            </a>
                                        </h3>

                                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-2">
                                            {{ $event['excerpt'] }}
                                        </p>
                                    </div>

                                    {{-- Event Meta Details --}}
                                    <div class="space-y-1.5 pt-2 border-t border-slate-100 text-xs text-slate-500">
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-regular fa-clock text-[#005030] text-[11px] w-3.5"></i>
                                            <span class="line-clamp-1">{{ $event['time'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-solid fa-location-dot text-[#005030] text-[11px] w-3.5"></i>
                                            <span class="line-clamp-1">{{ $event['venue'] }}</span>
                                        </div>
                                    </div>

                                    {{-- Card Action Footer --}}
                                    <div class="pt-2 flex items-center justify-between">
                                        <span class="text-[11px] text-slate-400 font-medium">
                                            {{ $event['organizer'] }}
                                        </span>
                                        <a href="{{ route('frontend.events.show', $event['slug']) }}"
                                           class="inline-flex items-center gap-1 text-xs font-bold text-[#005030] hover:text-emerald-950 hover:underline transition-colors">
                                            <span>বিস্তারিত</span>
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>

                                </div>

                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination Controls --}}
                    @if($eventsList->hasPages())
                        <div class="pt-6 flex justify-center">
                            {{ $eventsList->links() }}
                        </div>
                    @endif

                @else
                    {{-- Empty State --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-10 text-center space-y-3 shadow-2xs">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-lg">
                            <i class="fa-regular fa-calendar-xmark"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800">কোনো ইভেন্ট খুঁজে পাওয়া যায়নি</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            আপনার অনুসন্ধানের সাথে মিলে এমন কোনো প্রাতিষ্ঠানিক ইভেন্ট বর্তমান তালিকায় নেই। ফিল্টার রিসেট করে পুনরায় চেষ্টা করুন।
                        </p>
                        <a href="{{ route('frontend.events.index') }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#005030] text-white text-xs font-bold hover:bg-emerald-950 transition-colors">
                            <i class="fa-solid fa-rotate-left text-[11px]"></i>
                            <span>সকল ইভেন্ট দেখুন</span>
                        </a>
                    </div>
                @endif

            </div>

            {{-- Right Sticky Sidebar (4 Cols) --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- 1. Monthly Calendar Highlights Card --}}
                <div class="sidebar-card bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3.5 gsap-reveal">
                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                                <i class="fa-solid fa-calendar-check"></i>
                            </span>
                            <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">মাসিক দিনপঞ্জি</h4>
                        </div>
                        <span class="text-[11px] font-bold text-[#005030] font-mono">২০২৬</span>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        সোনাতলা উচ্চ বিদ্যালয়ের চলতি শিক্ষাবর্ষের সকল প্রাতিষ্ঠানিক পরীক্ষা, বার্ষিক ছুটি এবং বিশেষ সহশিক্ষা ইভেন্টের সমন্বিত সময়সূচি।
                    </p>

                    <div class="space-y-2 pt-1">
                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-[#005030] flex items-center justify-center font-bold text-[11px]">১৫</span>
                                <span class="font-medium text-slate-800">৫৮তম বার্ষিক ক্রীড়া প্রতিযোগিতা</span>
                            </div>
                            <span class="text-[10px] text-emerald-700 font-bold">আসন্ন</span>
                        </div>

                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-sky-100 text-sky-800 flex items-center justify-center font-bold text-[11px]">২২</span>
                                <span class="font-medium text-slate-800">বিজ্ঞান মেলা ও রোবটিক্স প্রদর্শনী</span>
                            </div>
                            <span class="text-[10px] text-sky-700 font-bold">আসন্ন</span>
                        </div>

                        <div class="p-2 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-rose-100 text-rose-800 flex items-center justify-center font-bold text-[11px]">১৬</span>
                                <span class="font-medium text-slate-800">মহান বিজয় দিবস ও সাংস্কৃতিক সন্ধ্যা</span>
                            </div>
                            <span class="text-[10px] text-rose-700 font-bold">আসন্ন</span>
                        </div>
                    </div>

                    <a href="{{ route('frontend.academic.routine') }}"
                       class="inline-flex items-center gap-1.5 text-xs font-bold text-[#005030] hover:text-emerald-950 hover:underline transition-colors pt-1">
                        <span>ক্লাস ও পরীক্ষার রুটিন দেখুন</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                {{-- 2. Upcoming Highlights Quick Widget --}}
                <div class="sidebar-card bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3.5 gsap-reveal">
                    <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                            <i class="fa-solid fa-bell"></i>
                        </span>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">শীর্ষ আসন্ন আয়োজন</h4>
                    </div>

                    <div class="space-y-3 divide-y divide-slate-100">
                        @foreach($upcomingHighlights as $uEvent)
                            <a href="{{ route('frontend.events.show', $uEvent['slug']) }}"
                               class="pt-3 first:pt-0 flex items-start gap-3 group block hover:bg-slate-50 p-1.5 -mx-1.5 rounded-xl transition-colors cursor-pointer">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200/80 flex-shrink-0 flex flex-col items-center justify-center text-[#005030]">
                                    <span class="text-xs font-extrabold leading-none">{{ $uEvent['date_day'] }}</span>
                                    <span class="text-[9px] font-bold leading-none mt-0.5">{{ $uEvent['date_month'] }}</span>
                                </div>
                                <div class="flex-1 min-w-0 space-y-0.5">
                                    <h5 class="text-xs font-bold text-slate-900 group-hover:text-[#005030] leading-snug line-clamp-2 transition-colors">
                                        {{ $uEvent['title'] }}
                                    </h5>
                                    <div class="text-[11px] text-slate-400 flex items-center gap-1">
                                        <i class="fa-solid fa-location-dot text-[9px]"></i>
                                        <span class="line-clamp-1">{{ $uEvent['venue'] }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- 3. Event Helpdesk & Coordinator Info --}}
                <div class="sidebar-card bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3 gsap-reveal hover-card-lift">
                    <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                            <i class="fa-solid fa-headset"></i>
                        </span>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">ইভেন্ট হেল্পডেস্ক ও সমন্বয়</h4>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        বার্ষিক ক্রীড়া প্রতিযোগিতা, বিজ্ঞান মেলা বা যেকোনো ইভেন্টে অংশগ্রহণ ও স্টল বরাদ্দের জন্য অফিস চলাকালীন যোগাযোগ করুন।
                    </p>

                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 space-y-1.5 text-xs">
                        <div class="text-slate-500 font-medium">সমন্বয়ক দপ্তর:</div>
                        <div class="font-bold text-slate-800">সহকারী প্রধান শিক্ষকের কার্যালয়</div>
                        <div class="text-[#005030] font-bold font-mono">+৮৮০ ১৭১২-৩৪৫৬৭৮</div>
                    </div>
                </div>

                {{-- 4. Circulars & Official Notice Card --}}
                <div class="sidebar-card bg-emerald-50/60 border border-emerald-200/80 rounded-2xl p-5 shadow-2xs space-y-3 gsap-reveal hover-card-lift">
                    <div class="flex items-center gap-2 pb-2.5 border-b border-emerald-100">
                        <span class="w-7 h-7 rounded-lg bg-emerald-100 text-[#005030] flex items-center justify-center text-xs">
                            <i class="fa-solid fa-bullhorn"></i>
                        </span>
                        <h4 class="font-bold text-xs text-[#005030] uppercase tracking-wider">বিজ্ঞপ্তি ও নির্দেশিকা</h4>
                    </div>

                    <h5 class="text-xs font-bold text-slate-900 leading-snug">
                        ইভেন্ট সংক্রান্ত প্রাতিষ্ঠানিক নির্দেশিকা ও সময়সূচি
                    </h5>

                    <p class="text-xs text-slate-600 leading-relaxed font-normal">
                        সকল প্রতিযোগিতা, অংশগ্রহণকারী তালিকা এবং মূল্যায়ন নীতিমালার আনুষ্ঠানিক বিজ্ঞপ্তি দেখতে নোটিশ বোর্ডে যান।
                    </p>

                    <a href="{{ route('frontend.notices.index') }}"
                       class="inline-flex items-center gap-1.5 text-xs font-bold text-[#005030] hover:text-emerald-950 hover:underline transition-colors pt-1">
                        <span>নোটিশ বোর্ড আর্কাইভ</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof gsap !== 'undefined') {
        // Hero entrance animation
        gsap.from('.page-hero-badge', { opacity: 0, y: -10, duration: 0.5, ease: 'power2.out' });
        gsap.from('.page-hero-title', { opacity: 0, y: 15, duration: 0.6, delay: 0.1, ease: 'power2.out' });
        gsap.from('.page-hero-subtitle', { opacity: 0, y: 15, duration: 0.6, delay: 0.2, ease: 'power2.out' });

        // ScrollTrigger reveal for cards
        if (typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);

            gsap.utils.toArray('.gsap-reveal').forEach(function (elem) {
                gsap.from(elem, {
                    scrollTrigger: {
                        trigger: elem,
                        start: 'top 88%',
                        toggleActions: 'play none none none'
                    },
                    opacity: 0,
                    y: 20,
                    duration: 0.6,
                    ease: 'power2.out'
                });
            });
        }
    }
});
</script>
@endpush
