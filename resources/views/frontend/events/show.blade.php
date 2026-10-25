@extends('layouts.app')

@section('title', $event['title'] . ' | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', Str::limit(strip_tags($event['excerpt']), 155))

@section('content')

{{-- 1. Breadcrumb --}}
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
            <a href="{{ route('frontend.events.index') }}" class="hover:text-[#005030] transition-colors">
                ইভেন্ট ও আয়োজন
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold line-clamp-1 max-w-[200px] sm:max-w-md">
                {{ $event['title'] }}
            </span>
        </nav>
        <a href="{{ route('frontend.events.index') }}" class="text-xs text-[#005030] font-bold hover:underline flex items-center gap-1">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>সকল ইভেন্ট</span>
        </a>
    </div>
</div>

{{-- 2. Hero Header Banner --}}
<div class="page-hero-banner bg-[#005030] text-white py-8 sm:py-10 border-b border-emerald-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3">
        
        {{-- Badges Strip --}}
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-[#005030] text-xs font-bold shadow-xs">
                <i class="fa-solid fa-calendar-check text-[#005030]"></i>
                <span>{{ $event['category'] }}</span>
            </span>

            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $event['status'] === 'upcoming' ? 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/30' : 'bg-white/10 text-white border border-white/20' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $event['status'] === 'upcoming' ? 'bg-emerald-400 animate-pulse' : 'bg-slate-300' }}"></span>
                <span>{{ $event['status_text'] }}</span>
            </span>
        </div>

        {{-- Headline --}}
        <h1 class="page-hero-title text-xl sm:text-2xl lg:text-3xl font-extrabold text-white tracking-tight leading-tight max-w-4xl">
            {{ $event['title'] }}
        </h1>

        {{-- Event Meta Pills --}}
        <div class="flex flex-wrap items-center gap-3 sm:gap-4 pt-1 text-xs text-emerald-100/90 font-medium">
            <div class="flex items-center gap-1.5">
                <i class="fa-regular fa-calendar text-emerald-300"></i>
                <span class="font-bold text-white">{{ $event['display_date'] }}</span>
            </div>
            <span>•</span>
            <div class="flex items-center gap-1.5">
                <i class="fa-regular fa-clock text-emerald-300"></i>
                <span>{{ $event['time'] }}</span>
            </div>
            <span>•</span>
            <div class="flex items-center gap-1.5">
                <i class="fa-solid fa-location-dot text-emerald-300"></i>
                <span class="line-clamp-1">{{ $event['venue'] }}</span>
            </div>
        </div>

    </div>
</div>

{{-- 3. Main Content --}}
<section class="py-8 sm:py-12 bg-slate-50/70 min-h-[550px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Left Content: Image, Description, Timeline, Actions (8 Cols) --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- Featured Image Box --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-2xs gsap-reveal">
                    <div class="relative bg-slate-100 h-64 sm:h-80 md:h-96 overflow-hidden">
                        <img src="{{ asset($event['image_url']) }}"
                             alt="{{ $event['title'] }}"
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.src='{{ asset('assets/images/news-1.jpg') }}';">
                    </div>
                </div>

                {{-- Detailed Narrative / About Event --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-5 gsap-reveal">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                            <i class="fa-solid fa-align-left"></i>
                        </span>
                        <h2 class="font-bold text-sm text-slate-900 uppercase tracking-wider">ইভেন্ট বিবরণ ও প্রেক্ষাপট</h2>
                    </div>

                    <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-sm">
                        {!! $event['description'] !!}
                    </div>
                </div>

                {{-- Program Schedule Timeline --}}
                @if(!empty($event['schedule']))
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6 gsap-reveal">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-list-check"></i>
                                </span>
                                <h3 class="font-bold text-sm text-slate-900 uppercase tracking-wider">ধাপভিত্তিক পূর্ণাঙ্গ সময়সূচি</h3>
                            </div>
                            <span class="text-xs text-slate-400 font-medium">অধিবেশন ভিত্তিক</span>
                        </div>

                        {{-- Vertical Schedule Timeline --}}
                        <div class="relative pl-6 sm:pl-8 space-y-6 before:absolute before:left-2 sm:before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-200">
                            @foreach($event['schedule'] as $index => $step)
                                <div class="relative timeline-step gsap-reveal">
                                    {{-- Timeline Node Dot --}}
                                    <div class="absolute -left-6 sm:-left-8 top-0.5 w-4.5 h-4.5 rounded-full bg-white border-2 border-[#005030] flex items-center justify-center shadow-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#005030]"></span>
                                    </div>

                                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 space-y-1.5">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-emerald-100 text-[#005030] text-xs font-bold font-mono">
                                                <i class="fa-regular fa-clock text-[10px]"></i>
                                                <span>{{ $step['time'] }}</span>
                                            </span>
                                            <span class="text-[11px] text-slate-400 font-medium">পর্ব {{ $index + 1 }}</span>
                                        </div>

                                        <h4 class="text-sm font-bold text-slate-900 pt-0.5">
                                            {{ $step['title'] }}
                                        </h4>

                                        <p class="text-xs text-slate-600 leading-relaxed">
                                            {{ $step['details'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endif

                {{-- Action Bar (Print, Share, Back) --}}
                <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex flex-wrap items-center justify-between gap-3 gsap-reveal">
                    <a href="{{ route('frontend.events.index') }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i>
                        <span>সকল ইভেন্ট ক্যালেন্ডারে ফিরুন</span>
                    </a>

                    <div class="flex items-center gap-2">
                        <button type="button"
                                onclick="window.print()"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-colors">
                            <i class="fa-solid fa-print text-[11px]"></i>
                            <span>প্রিন্ট করুন</span>
                        </button>

                        <button type="button"
                                onclick="navigator.clipboard.writeText(window.location.href); alert('ইভেন্টের লিংক সফলভাবে কপি করা হয়েছে!');"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-[#005030] hover:bg-emerald-950 text-white text-xs font-bold shadow-2xs transition-colors">
                            <i class="fa-regular fa-share-from-square text-[11px]"></i>
                            <span>লিংক কপি করুন</span>
                        </button>
                    </div>
                </div>

            </div>

            {{-- Right Sticky Sidebar (4 Cols) --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- 1. Event Quick Facts Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-4 gsap-reveal">
                    <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                            <i class="fa-solid fa-circle-info"></i>
                        </span>
                        <h3 class="font-bold text-xs text-slate-900 uppercase tracking-wider">জরুরি তথ্যাবলী</h3>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-start gap-2.5 pb-2.5 border-b border-slate-100">
                            <i class="fa-regular fa-calendar-days text-[#005030] mt-0.5 text-sm"></i>
                            <div>
                                <span class="text-slate-400 block text-[11px]">অনুষ্ঠানের তারিখ:</span>
                                <span class="font-bold text-slate-900">{{ $event['display_date'] }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 pb-2.5 border-b border-slate-100">
                            <i class="fa-regular fa-clock text-[#005030] mt-0.5 text-sm"></i>
                            <div>
                                <span class="text-slate-400 block text-[11px]">নির্ধারিত সময়সূচি:</span>
                                <span class="font-bold text-slate-900">{{ $event['time'] }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 pb-2.5 border-b border-slate-100">
                            <i class="fa-solid fa-location-dot text-[#005030] mt-0.5 text-sm"></i>
                            <div>
                                <span class="text-slate-400 block text-[11px]">অনুষ্ঠানস্থল / ভেন্যু:</span>
                                <span class="font-bold text-slate-900">{{ $event['venue'] }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 pb-2.5 border-b border-slate-100">
                            <i class="fa-solid fa-users text-[#005030] mt-0.5 text-sm"></i>
                            <div>
                                <span class="text-slate-400 block text-[11px]">আয়োজক কমিটি:</span>
                                <span class="font-bold text-slate-900">{{ $event['organizer'] }}</span>
                            </div>
                        </div>

                        @if(!empty($event['coordinator']))
                            <div class="flex items-start gap-2.5 pb-2.5 border-b border-slate-100">
                                <i class="fa-solid fa-user-tie text-[#005030] mt-0.5 text-sm"></i>
                                <div>
                                    <span class="text-slate-400 block text-[11px]">দায়িত্বপ্রাপ্ত সমন্বয়ক:</span>
                                    <span class="font-bold text-slate-900">{{ $event['coordinator'] }}</span>
                                </div>
                            </div>
                        @endif

                        @if(!empty($event['contact_phone']))
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-phone text-[#005030] mt-0.5 text-sm"></i>
                                <div>
                                    <span class="text-slate-400 block text-[11px]">তথ্য হেল্পলাইন:</span>
                                    <a href="tel:{{ $event['contact_phone'] }}" class="font-bold text-[#005030] hover:underline">
                                        {{ $event['contact_phone'] }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 2. Related Events (Same Category) --}}
                @if($relatedEvents->count() > 0)
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3.5 gsap-reveal">
                        <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                                <i class="fa-solid fa-layer-group"></i>
                            </span>
                            <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">একই বিভাগের অন্যান্য আয়োজন</h4>
                        </div>

                        <div class="space-y-3 divide-y divide-slate-100">
                            @foreach($relatedEvents as $rEvent)
                                <a href="{{ route('frontend.events.show', $rEvent['slug']) }}"
                                   class="pt-3 first:pt-0 flex items-center gap-3 group block hover:bg-slate-50 p-1.5 -mx-1.5 rounded-xl transition-colors cursor-pointer">
                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 relative">
                                        <img src="{{ asset($rEvent['image_url']) }}"
                                             alt="{{ $rEvent['title'] }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                             onerror="this.onerror=null; this.src='{{ asset('assets/images/news-2.jpg') }}';">
                                    </div>
                                    <div class="flex-1 min-w-0 space-y-1">
                                        <div class="text-[10px] text-slate-400 flex items-center gap-1">
                                            <i class="fa-regular fa-calendar text-[9px]"></i>
                                            <span>{{ $rEvent['display_date'] }}</span>
                                        </div>
                                        <h5 class="text-xs font-bold text-slate-900 group-hover:text-[#005030] leading-snug line-clamp-2 transition-colors">
                                            {{ $rEvent['title'] }}
                                        </h5>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- 3. Top Upcoming Highlights --}}
                @if($upcomingEvents->count() > 0)
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3.5 gsap-reveal">
                        <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                            <span class="w-7 h-7 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                                <i class="fa-solid fa-calendar-plus"></i>
                            </span>
                            <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">আসন্ন পরবর্তী আয়োজনসমূহ</h4>
                        </div>

                        <div class="space-y-2.5 divide-y divide-slate-100">
                            @foreach($upcomingEvents as $uEvent)
                                <a href="{{ route('frontend.events.show', $uEvent['slug']) }}"
                                   class="pt-2.5 first:pt-0 flex items-start gap-2.5 group block hover:bg-slate-50 p-1.5 -mx-1.5 rounded-xl transition-colors cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-emerald-50 text-[#005030] flex flex-col items-center justify-center flex-shrink-0 text-[10px] font-extrabold">
                                        {{ $uEvent['date_day'] }}
                                    </span>
                                    <div class="flex-1 min-w-0 space-y-0.5">
                                        <h5 class="text-xs font-bold text-slate-900 group-hover:text-[#005030] leading-snug line-clamp-2 transition-colors">
                                            {{ $uEvent['title'] }}
                                        </h5>
                                        <span class="text-[10px] text-slate-400">{{ $uEvent['time'] }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof gsap !== 'undefined') {
        gsap.from('.page-hero-title', { opacity: 0, y: 15, duration: 0.6, ease: 'power2.out' });

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
