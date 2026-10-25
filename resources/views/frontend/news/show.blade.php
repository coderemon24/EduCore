@extends('layouts.app')

@section('title', ($news['title'] ?? 'সংবাদ প্রতিবেদন') . ' | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($news['excerpt'] ?? ''), 160))

@section('content')

{{-- 1. Breadcrumb: Institutional Standard Matching Classes & Notices --}}
<div class="bg-gray-100 border-b border-gray-200 py-3.5 text-xs text-gray-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>প্রচ্ছদ</span>
            </a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('frontend.news.index') }}" class="hover:text-[#005030] transition-colors">সংবাদ ও মিডিয়া</a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold truncate max-w-[200px] sm:max-w-xs">{{ $news['title'] }}</span>
        </nav>
        <a href="{{ route('frontend.news.index') }}" class="text-xs text-[#005030] hover:underline font-bold hidden sm:inline-flex items-center gap-1">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>সকল সংবাদে ফিরুন</span>
        </a>
    </div>
</div>

{{-- 2. Main Article Content Canvas --}}
<section class="py-8 sm:py-12 bg-slate-50 min-h-[600px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Main Article Stream (Col 8) --}}
            <main class="lg:col-span-8 space-y-6">

                <article class="bg-white border border-slate-200/90 rounded-2xl shadow-xs p-6 sm:p-8 space-y-6 gsap-reveal">

                    {{-- Back & Category Pill --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $news['badge_color'] ?? 'bg-emerald-50 text-[#005030] border-emerald-200' }}">
                                <i class="fa-solid fa-tag text-[10px] mr-1"></i>{{ $news['category'] }}
                            </span>
                            @if(!empty($news['is_featured']))
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-400 text-slate-950">
                                    <i class="fa-solid fa-star text-[10px] mr-1"></i>শীর্ষ প্রতিবেদন
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('frontend.news.index') }}"
                           class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-[#005030] font-semibold transition-colors">
                            <i class="fa-solid fa-arrow-left text-[11px]"></i>
                            <span>সংবাদ তালিকায় ফিরুন</span>
                        </a>
                    </div>

                    {{-- Article Headline --}}
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 leading-snug tracking-tight">
                        {{ $news['title'] }}
                    </h1>

                    {{-- Author & Publication Meta Bar --}}
                    <div class="flex flex-wrap items-center gap-4 py-3 px-4 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-600 font-medium">
                        <div class="flex items-center gap-1.5">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-[#005030] flex items-center justify-center text-[11px]">
                                <i class="fa-solid fa-pen-nib"></i>
                            </div>
                            <span>{{ $news['author'] }}</span>
                        </div>
                        <span class="text-slate-300">•</span>
                        <div class="flex items-center gap-1.5">
                            <i class="fa-regular fa-calendar text-[#005030]"></i>
                            <span>{{ $news['published_at'] }}</span>
                        </div>
                        <span class="text-slate-300">•</span>
                        <div class="flex items-center gap-1.5">
                            <i class="fa-regular fa-clock text-[#005030]"></i>
                            <span>{{ $news['read_time'] }}</span>
                        </div>
                        <span class="text-slate-300">•</span>
                        <div class="flex items-center gap-1.5">
                            <i class="fa-regular fa-eye text-[#005030]"></i>
                            <span>{{ $news['views_count'] }} বার পঠিত</span>
                        </div>
                    </div>

                    {{-- Featured Article Banner Image --}}
                    <div class="rounded-2xl overflow-hidden aspect-[16/9] bg-slate-100 border border-slate-200/80 shadow-2xs relative">
                        <img src="{{ asset($news['image_url']) }}"
                             alt="{{ $news['title'] }}"
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.src='{{ asset('assets/images/news-1.jpg') }}';">
                    </div>

                    {{-- Rich Article Text Body --}}
                    <div class="news-article-content text-slate-700 space-y-4 pt-2">
                        {!! $news['content'] !!}
                    </div>

                    {{-- Desk / Editorial Signature --}}
                    <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center text-sm font-bold">
                                <i class="fa-solid fa-school"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-900">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}</div>
                                <div class="text-[11px] text-slate-500">তথ্য ও গণসংযোগ বিভাগ • সাঁথিয়া, পাবনা</div>
                            </div>
                        </div>

                        {{-- Social Share Trigger Strip --}}
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-500 font-medium mr-1">শেয়ার করুন:</span>

                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="ফেসবুকে শেয়ার করুন"
                               class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center text-xs transition-colors">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>

                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($news['title']) }}&url={{ urlencode(url()->current()) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="টুইটারে শেয়ার করুন"
                               class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-500 hover:text-white flex items-center justify-center text-xs transition-colors">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>

                            <a href="https://api.whatsapp.com/send?text={{ urlencode($news['title'] . ' ' . url()->current()) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="হোয়াটসঅ্যাপে শেয়ার করুন"
                               class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center text-xs transition-colors">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>

                            <button type="button"
                                    onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('সংবাদের লিংকটি কপি করা হয়েছে!');"
                                    aria-label="লিংক কপি করুন"
                                    class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-700 hover:text-white flex items-center justify-center text-xs transition-colors">
                                <i class="fa-solid fa-link"></i>
                            </button>
                        </div>
                    </div>

                </article>

                {{-- Related News Section (3 Cards) --}}
                @if(isset($relatedNews) && $relatedNews->count() > 0)
                    <div class="space-y-4 pt-4">
                        <div class="flex items-center gap-2 border-b border-slate-200 pb-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#005030]"></span>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight">
                                সম্পর্কিত আরও সংবাদ
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach($relatedNews as $rel)
                                <article class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden flex flex-col justify-between gsap-reveal hover-card-lift group">
                                    <div class="relative overflow-hidden aspect-[16/10] bg-slate-100">
                                        <img src="{{ asset($rel['image_url']) }}"
                                             alt="{{ $rel['title'] }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                             onerror="this.onerror=null; this.src='{{ asset('assets/images/news-1.jpg') }}';">
                                        <div class="absolute top-2 left-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/95 text-slate-800 shadow-xs">
                                                {{ $rel['category'] }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="p-3.5 flex-1 flex flex-col justify-between space-y-2">
                                        <div class="space-y-1">
                                            <div class="text-[10px] text-slate-400">
                                                <i class="fa-regular fa-calendar mr-1"></i>{{ $rel['published_at'] }}
                                            </div>
                                            <h5 class="text-xs font-bold text-slate-900 group-hover:text-[#005030] leading-snug line-clamp-2 transition-colors">
                                                <a href="{{ route('frontend.news.show', $rel['slug']) }}">
                                                    {{ $rel['title'] }}
                                                </a>
                                            </h5>
                                        </div>

                                        <a href="{{ route('frontend.news.show', $rel['slug']) }}"
                                           class="text-[11px] font-bold text-[#005030] hover:underline inline-flex items-center gap-1 pt-1">
                                            <span>বিস্তারিত</span>
                                            <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif

            </main>

            {{-- Sidebar Column (Col 4) --}}
            <aside class="lg:col-span-4 space-y-6">

                {{-- Back to News Hub Button Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3 gsap-reveal hover-card-lift">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">সংবাদ পোর্টাল</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        সোনাতলা উচ্চ বিদ্যালয়ের সকল বিভাগীয় ও প্রাতিষ্ঠানিক সংবাদ দেখতে মূল পোর্টালে যান।
                    </p>
                    <a href="{{ route('frontend.news.index') }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#005030] text-white text-xs font-bold shadow-xs hover:bg-emerald-950 transition-colors">
                        <i class="fa-solid fa-newspaper text-xs"></i>
                        <span>সকল সংবাদ ব্রাউজ করুন</span>
                    </a>
                </div>

                {{-- Recent Stories Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 space-y-4 gsap-reveal hover-card-lift">
                    <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">সর্বশেষ অন্যান্য সংবাদ</h4>
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
                                    <div class="text-[10px] text-slate-400">
                                        {{ $rNews['published_at'] }}
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

                {{-- Press & Media Helpdesk Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3 gsap-reveal hover-card-lift">
                    <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">তথ্য ও প্রেস সেল</h4>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        যেকোনো তথ্য যাচাই অথবা প্রেস বিবৃতির জন্য বিদ্যালয়ের প্রধান অফিস অথবা তথ্য সেলের সাথে যোগাযোগ করুন।
                    </p>

                    <div class="text-xs text-[#005030] font-bold font-mono">
                        হেল্পলাইন: +৮৮০ ১৭১২-৩৪৫৬৭৮
                    </div>
                </div>

            </aside>

        </div>

    </div>
</section>

@endsection
