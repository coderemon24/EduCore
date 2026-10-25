@extends('layouts.app')

@section('title', 'প্রাতিষ্ঠানিক সংবাদ ও মিডিয়া | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের সকল একাডেমিক সাফল্য, বিজ্ঞান মেলা, সাংস্কৃতিক ও ক্রীড়া কার্যক্রমের সর্বশেষ সংবাদ ও প্রতিবেদন।')

@section('content')

{{-- 1. Breadcrumb: Institutional Standard Matching Classes & Notices --}}
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
            <span class="text-gray-500">সংবাদ ও মিডিয়া</span>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">সকল সংবাদ</span>
        </nav>
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">
            {{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }} • সর্বশেষ আপডেট: {{ now()->format('d M Y') }}
        </span>
    </div>
</div>

{{-- 2. Top Hero Banner: Institutional Green #005030 Header --}}
<div class="bg-[#005030] text-white py-8 sm:py-12 border-b border-emerald-900 page-hero-banner relative overflow-hidden">
    {{-- Subtle decorative institutional pattern --}}
    <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-white/5 pointer-events-none blur-2xl"></div>
    <div class="absolute left-1/3 -bottom-20 w-80 h-80 rounded-full bg-emerald-400/10 pointer-events-none blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Signature Pill Badge (Recipe 7: Add a Signature) --}}
        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/95 text-[#005030] text-xs font-extrabold shadow-xs page-hero-badge">
            <span class="w-2 h-2 rounded-full bg-[#005030] animate-pulse"></span>
            <i class="fa-solid fa-newspaper text-[#005030]"></i>
            <span>সংবাদ সমাচার ও আপডেট</span>
        </div>

        {{-- Page Heading (Recipe 1: Sharpen Message) --}}
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white mt-3.5 mb-2.5 tracking-tight page-hero-title">
            প্রাতিষ্ঠানিক <span class="text-amber-400 font-bold">সংবাদ ও মিডিয়া</span> পোর্টাল
        </h1>

        {{-- Sub-narrative context --}}
        <p class="text-xs sm:text-sm text-emerald-100/90 font-medium max-w-2xl leading-relaxed page-hero-subtitle">
            সোনাতলা উচ্চ বিদ্যালয়ের সকল একাডেমিক সাফল্য, সহশিক্ষা, বিজ্ঞান মেলা, সাংস্কৃতিক উৎসব ও ক্যাম্পাসের গৌরবময় অগ্রযাত্রার আনুষ্ঠানিক চিত্র ও প্রতিবেদন
        </p>

        {{-- Mobile & Tablet Quick Filter Pills Strip --}}
        <div class="mt-6 pt-5 border-t border-emerald-800/80 flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 text-xs font-medium">
            <span class="text-emerald-200/80 text-[11px] whitespace-nowrap mr-1 font-semibold">
                <i class="fa-solid fa-filter mr-1"></i>ফিল্টার:
            </span>
            <a href="{{ route('frontend.news.index') }}"
               class="px-3 py-1.5 rounded-lg whitespace-nowrap transition-colors {{ empty($activeCategory) ? 'bg-white text-[#005030] font-bold shadow-xs' : 'bg-emerald-900/70 text-emerald-100 hover:bg-emerald-800' }}">
                সকল সংবাদ
            </a>
            @foreach($categoriesWithCounts as $cSlug => $cat)
                <a href="{{ route('frontend.news.index', ['category' => $cSlug]) }}"
                   class="px-3 py-1.5 rounded-lg whitespace-nowrap transition-colors flex items-center gap-1.5 {{ $activeCategory === $cSlug ? 'bg-white text-[#005030] font-bold shadow-xs' : 'bg-emerald-900/70 text-emerald-100 hover:bg-emerald-800' }}">
                    <i class="fa-solid {{ $cat['icon'] }} text-[10px]"></i>
                    <span>{{ $cat['name'] }}</span>
                    <span class="text-[10px] opacity-75">({{ $cat['count'] }})</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- 3. Main Content Canvas --}}
<section class="py-8 sm:py-12 bg-slate-50 min-h-[600px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- Active Search or Category Filter Indicator --}}
        @if(!empty($activeCategory) || !empty($searchQuery))
            <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 gsap-reveal">
                <div class="flex items-center gap-2.5 text-xs text-slate-700 font-medium">
                    <span class="w-6 h-6 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center font-bold">
                        <i class="fa-solid fa-magnifying-glass text-[11px]"></i>
                    </span>
                    <div>
                        ফিল্টার ফলাফল:
                        @if(!empty($searchQuery))
                            অনুসন্ধান <span class="font-bold text-slate-900">"{{ $searchQuery }}"</span>
                        @endif
                        @if(!empty($activeCategory) && isset($categoriesWithCounts[$activeCategory]))
                            @if(!empty($searchQuery)) • @endif
                            ক্যাটাগরি <span class="font-bold text-[#005030]">{{ $categoriesWithCounts[$activeCategory]['name'] }}</span>
                        @endif
                        <span class="text-slate-500 font-normal">({{ $newsList->total() }}টি ফলাফল প্রাপ্ত)</span>
                    </div>
                </div>
                <a href="{{ route('frontend.news.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs text-rose-600 hover:text-rose-700 font-bold hover:underline">
                    <i class="fa-solid fa-xmark"></i>
                    <span>ফিল্টার রিসেট করুন</span>
                </a>
            </div>
        @endif

        {{-- 4. Asymmetric Layout Grid: Main News Canvas (Col 8) & Sidebar (Col 4) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Main News Stream (Col 8) --}}
            <div class="lg:col-span-8 space-y-8">

                {{-- Flagship Hero Winner Card (Recipe 4: Pick the Winner) --}}
                @if(isset($featuredNews) && $featuredNews)
                    <article class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden gsap-reveal hover-card-lift group">
                        <div class="relative overflow-hidden aspect-[16/9] sm:aspect-[21/10] bg-slate-100">
                            {{-- Image with smooth zoom --}}
                            <img src="{{ asset($featuredNews['image_url']) }}"
                                 alt="{{ $featuredNews['title'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/images/news-1.jpg') }}';">

                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>

                            {{-- Floating Badges --}}
                            <div class="absolute top-4 left-4 flex flex-wrap items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold shadow-xs bg-amber-400 text-slate-950">
                                    <i class="fa-solid fa-star text-[10px] mr-1"></i>শীর্ষ সংবাদ
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold shadow-xs bg-white/95 text-slate-900 backdrop-blur-xs">
                                    {{ $featuredNews['category'] }}
                                </span>
                            </div>

                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <div class="flex items-center gap-3 text-xs text-slate-200 font-medium mb-1.5">
                                    <span><i class="fa-regular fa-calendar mr-1"></i>{{ $featuredNews['published_at'] }}</span>
                                    <span>•</span>
                                    <span><i class="fa-regular fa-clock mr-1"></i>{{ $featuredNews['read_time'] }}</span>
                                    <span>•</span>
                                    <span><i class="fa-solid fa-user-pen mr-1"></i>{{ $featuredNews['author'] }}</span>
                                </div>
                                <h2 class="text-lg sm:text-2xl font-extrabold text-white leading-snug drop-shadow-xs group-hover:text-amber-300 transition-colors">
                                    <a href="{{ route('frontend.news.show', $featuredNews['slug']) }}">
                                        {{ $featuredNews['title'] }}
                                    </a>
                                </h2>
                            </div>
                        </div>

                        {{-- Flagship Body --}}
                        <div class="p-5 sm:p-6 space-y-4">
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                                {{ $featuredNews['excerpt'] }}
                            </p>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <i class="fa-solid fa-eye text-[11px] text-[#005030]"></i>
                                    <span>{{ $featuredNews['views_count'] }} বার পঠিত</span>
                                </div>

                                <a href="{{ route('frontend.news.show', $featuredNews['slug']) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#005030] text-white text-xs font-bold shadow-xs hover:bg-emerald-950 transition-all hover:scale-102">
                                    <span>সম্পূর্ণ সংবাদ পড়ুন</span>
                                    <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endif

                {{-- Secondary News Grid (Recipe 2: Break Symmetry & Recipe 5: Scale the Type) --}}
                @if($newsList->count() > 0)
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#005030]"></span>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                                    সকল সাম্প্রতিক সংবাদ ও প্রতিবেদন
                                </h3>
                            </div>
                            <span class="text-xs text-slate-500 font-medium">পৃষ্ঠা {{ $newsList->currentPage() }} এর {{ $newsList->lastPage() }}</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            @foreach($newsList as $item)
                                <article class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden flex flex-col justify-between gsap-reveal hover-card-lift group transition-all duration-300">
                                    {{-- Card Image Frame --}}
                                    <div class="relative overflow-hidden aspect-[16/10] bg-slate-100">
                                        <img src="{{ asset($item['image_url']) }}"
                                             alt="{{ $item['title'] }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                             onerror="this.onerror=null; this.src='{{ asset('assets/images/news-1.jpg') }}';">

                                        {{-- Top Category Pill Badge --}}
                                        <div class="absolute top-3 left-3">
                                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold shadow-xs border bg-white/95 text-slate-800 backdrop-blur-xs">
                                                {{ $item['category'] }}
                                            </span>
                                        </div>

                                        {{-- Top-Right Date Tag --}}
                                        <div class="absolute top-3 right-3 bg-slate-900/80 backdrop-blur-xs text-white px-2 py-0.5 rounded-md text-[10px] font-semibold">
                                            {{ $item['date_day'] }} {{ $item['date_month'] }}
                                        </div>
                                    </div>

                                    {{-- Card Content --}}
                                    <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between space-y-3">
                                        <div class="space-y-2">
                                            <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                                <span><i class="fa-regular fa-clock mr-1 text-[#005030]"></i>{{ $item['read_time'] }}</span>
                                                <span>•</span>
                                                <span><i class="fa-solid fa-pen-nib mr-1 text-slate-400"></i>{{ $item['author'] }}</span>
                                            </div>

                                            <h4 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-[#005030] leading-snug transition-colors line-clamp-2">
                                                <a href="{{ route('frontend.news.show', $item['slug']) }}">
                                                    {{ $item['title'] }}
                                                </a>
                                            </h4>

                                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed font-normal">
                                                {{ $item['excerpt'] }}
                                            </p>
                                        </div>

                                        {{-- Card Footer --}}
                                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                            <span class="text-[11px] text-slate-400">
                                                <i class="fa-regular fa-eye mr-1"></i>{{ $item['views_count'] }}
                                            </span>

                                            <a href="{{ route('frontend.news.show', $item['slug']) }}"
                                               class="text-xs font-bold text-[#005030] hover:text-emerald-950 flex items-center gap-1.5 transition-colors">
                                                <span>বিস্তারিত পড়ুন</span>
                                                <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        {{-- Pagination Bar --}}
                        @if($newsList->hasPages())
                            <div class="pt-6 border-t border-slate-200">
                                {{ $newsList->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Empty State Result --}}
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-8 sm:p-12 text-center space-y-4 shadow-2xs">
                        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                            <i class="fa-solid fa-newspaper"></i>
                        </div>
                        <h4 class="text-base font-bold text-slate-900">কোন সংবাদ প্রতিবেদন পাওয়া যায়নি</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                            আপনার অনুসন্ধানের সাথে মিলে এমন কোন সংবাদ পাওয়া যায়নি। অন্য কোন শব্দ লিখে খুঁজুন অথবা সকল সংবাদ ব্রাউজ করুন।
                        </p>
                        <a href="{{ route('frontend.news.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#005030] text-white text-xs font-bold shadow-xs hover:bg-emerald-900 transition-colors">
                            <i class="fa-solid fa-arrow-rotate-left text-[11px]"></i>
                            <span>সকল সংবাদ দেখুন</span>
                        </a>
                    </div>
                @endif

            </div>

            {{-- Sidebar Column (Col 4) --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- 1. Search Box Widget --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 space-y-3 gsap-reveal hover-card-lift">
                    <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">সংবাদ খুঁজুন</h3>
                    </div>

                    <form action="{{ route('frontend.news.index') }}" method="GET" class="relative">
                        @if(!empty($activeCategory))
                            <input type="hidden" name="category" value="{{ $activeCategory }}">
                        @endif
                        <input type="text"
                               name="q"
                               value="{{ $searchQuery }}"
                               placeholder="শিরোনাম বা কী-ওয়ার্ড লিখুন..."
                               class="w-full pl-3.5 pr-10 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:border-[#005030] focus:bg-white transition-colors">
                        <button type="submit"
                                aria-label="অনুসন্ধান করুন"
                                class="absolute right-3 top-2.5 text-slate-400 hover:text-[#005030] transition-colors">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </form>
                </div>

                {{-- 2. News Categories Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 space-y-3 gsap-reveal hover-card-lift">
                    <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">সংবাদ ক্যাটাগরি</h3>
                        <span class="text-[11px] text-slate-400 font-semibold">{{ count($categoriesWithCounts) }}টি বিভাগ</span>
                    </div>

                    <div class="space-y-1.5 text-xs font-medium">
                        <a href="{{ route('frontend.news.index') }}"
                           class="flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ empty($activeCategory) ? 'bg-[#005030] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-[11px] {{ empty($activeCategory) ? 'text-amber-300' : 'text-slate-400' }}"></i>
                                <span>সকল সংবাদ</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[10px] {{ empty($activeCategory) ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                                {{ array_sum(array_column($categoriesWithCounts, 'count')) }}
                            </span>
                        </a>

                        @foreach($categoriesWithCounts as $cSlug => $cat)
                            <a href="{{ route('frontend.news.index', ['category' => $cSlug]) }}"
                               class="flex items-center justify-between px-3 py-2 rounded-xl transition-colors {{ $activeCategory === $cSlug ? 'bg-[#005030] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50' }}">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid {{ $cat['icon'] }} text-[11px] {{ $activeCategory === $cSlug ? 'text-amber-300' : 'text-slate-400' }}"></i>
                                    <span>{{ $cat['name'] }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] {{ $activeCategory === $cSlug ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $cat['count'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- 3. Recent News Widget Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 space-y-4 gsap-reveal hover-card-lift">
                    <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">সাম্প্রতিক সংবাদ</h3>
                    </div>

                    <div class="space-y-3.5 divide-y divide-slate-100">
                        @foreach($recentNews as $rNews)
                            <div class="pt-3.5 first:pt-0 flex items-center gap-3 group">
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 relative">
                                    <img src="{{ asset($rNews['image_url']) }}"
                                         alt="{{ $rNews['title'] }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                         onerror="this.onerror=null; this.src='{{ asset('assets/images/news-1.jpg') }}';">
                                </div>
                                <div class="flex-1 min-w-0 space-y-1">
                                    <div class="text-[10px] text-slate-400 flex items-center gap-1.5">
                                        <span><i class="fa-regular fa-calendar text-[10px]"></i> {{ $rNews['published_at'] }}</span>
                                    </div>
                                    <h5 class="text-xs font-bold text-slate-900 group-hover:text-[#005030] leading-snug line-clamp-2 transition-colors">
                                        <a href="{{ route('frontend.news.show', $rNews['slug']) }}">
                                            {{ $rNews['title'] }}
                                        </a>
                                    </h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 4. Institutional Media & Press Contact Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3 gsap-reveal hover-card-lift">
                    <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">মিডিয়া ও তথ্য সেল</h4>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        বিদ্যালয়ের যেকোনো প্রাতিষ্ঠানিক সংবাদ, প্রেস বিজ্ঞপ্তি অথবা প্রেস কভারেজ সংক্রান্ত অনুসন্ধানের জন্য অফিসিয়াল মিডিয়া উইংয়ে যোগাযোগ করুন।
                    </p>

                    <div class="space-y-1.5 text-xs text-slate-700">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-[#005030] text-[11px] w-4"></i>
                            <span class="font-bold">+৮৮০ ১৭১২-৩৪৫৬৭৮</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-[#005030] text-[11px] w-4"></i>
                            <span>media@sonatalahighschool.edu.bd</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-[#005030] text-[11px] w-4"></i>
                            <span class="text-slate-500">প্রশাসনিক ভবন (কক্ষ ২০৪), সোনাতলা</span>
                        </div>
                    </div>
                </div>

                {{-- 5. Annual Magazine & Gazette Card --}}
                <div class="bg-gradient-to-br from-emerald-950 to-[#005030] rounded-2xl p-5 text-white shadow-xs space-y-3 gsap-reveal">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-400 text-slate-950 text-[10px] font-extrabold">
                        <i class="fa-solid fa-book-open"></i>
                        <span>বার্ষিক প্রকাশনা</span>
                    </div>
                    <h4 class="text-sm font-bold text-white leading-snug">
                        সোনাতলা বিদ্যাপীঠ বার্ষিকী ও ডিজিটাল নিউজলেটার
                    </h4>
                    <p class="text-[11px] text-emerald-100/80 leading-relaxed">
                        শিক্ষার্থী ও শিক্ষকদের স্বরচিত সাহিত্যকর্ম ও বাৎসরিক স্মৃতি স্মারক নিয়ে প্রকাশিত ডিজিটাল সংস্করণটি দেখুন।
                    </p>
                    <a href="{{ route('frontend.notices.index') }}"
                       class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-300 hover:text-amber-200 transition-colors">
                        <span>বিজ্ঞপ্তি ও প্রকাশনা আর্কাইভ</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>

@endsection
