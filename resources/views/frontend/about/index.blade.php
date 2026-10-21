@extends('layouts.app')

{{-- Dynamic SEO Page Title & Description --}}
@section('title', $about->meta_title ?: ($about->title . ' | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়')))
@section('meta_description', $about->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($about->history_content), 160))

@push('styles')
    {{-- Dynamic SEO Meta Tags & OpenGraph Social Sharing --}}
    @if(!empty($about->meta_keywords))
        <meta name="keywords" content="{{ $about->meta_keywords }}">
    @endif
    <link rel="canonical" href="{{ $about->canonical_url ?: route('frontend.about') }}">
    <meta property="og:locale" content="bn_BD">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $about->og_title ?: $about->title }}">
    <meta property="og:description" content="{{ $about->og_description ?: ($about->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($about->history_content), 160)) }}">
    <meta property="og:url" content="{{ route('frontend.about') }}">
    <meta property="og:site_name" content="{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}">
    @if(!empty($about->og_image_url))
        <meta property="og:image" content="{{ $about->og_image_url }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $about->og_title ?: $about->title }}">
    <meta name="twitter:description" content="{{ $about->og_description ?: $about->meta_description }}">

    {{-- Structured Data (Schema.org JSON-LD) --}}
    <script type="application/ld+json">
    {!! json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'EducationalOrganization',
      'name' => $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়',
      'url' => route('frontend.about'),
      'logo' => asset('assets/images/logo.png'),
      'description' => $about->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($about->history_content), 160),
      'foundingDate' => (string)($about->established_year ?? '1968'),
      'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => $school->city ?? 'সাঁথিয়া',
        'addressRegion' => $school->state ?? 'পাবনা',
        'addressCountry' => 'BD',
      ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    {{-- Dedicated About Page Aesthetics: Bengali Font, Keyframe Animations & Micro-Interactions --}}
    <style>
        .about-page {
            font-family: 'Hind Siliguri', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #fbfbf9;
        }

        /* Subtle Keyframes */
        @keyframes aboutFadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes aboutPulseGold {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4);
            }
            50% {
                box-shadow: 0 0 0 8px rgba(212, 175, 55, 0);
            }
        }

        @keyframes aboutPulseGreen {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(0, 80, 48, 0.35);
            }
            50% {
                box-shadow: 0 0 0 8px rgba(0, 80, 48, 0);
            }
        }

        .animate-about-fade {
            animation: aboutFadeUp 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .pulse-gold-badge {
            animation: aboutPulseGold 2.8s infinite;
        }

        .pulse-green-dot {
            animation: aboutPulseGreen 2.2s infinite;
        }

        /* Interactive Card Elevate & Transitions */
        .interactive-card {
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), 
                        box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), 
                        border-color 0.28s ease;
        }

        .interactive-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px -6px rgba(0, 56, 32, 0.08), 0 4px 10px -2px rgba(0, 0, 0, 0.04);
            border-color: rgba(0, 80, 48, 0.35);
        }

        /* Golden institutional bottom bar */
        .gold-accent-line {
            background: linear-gradient(90deg, #b3820a 0%, #e2c058 50%, #b3820a 100%);
            height: 3px;
        }

        /* Section Title Underline */
        .section-header-line::after {
            content: '';
            display: block;
            width: 48px;
            height: 3px;
            background: #005030;
            margin-top: 6px;
            border-radius: 2px;
        }

        .section-header-line.center::after {
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endpush

@section('content')

<div class="about-page text-slate-800">

    {{-- 1. Institutional Hero Masthead Banner (Rich Forest Green + Gold Accent) --}}
    <section class="relative bg-gradient-to-br from-[#003620] via-[#00472b] to-[#002b1a] text-white pt-8 pb-12 overflow-hidden shadow-md">
        {{-- Architectural Watermark Pattern --}}
        <div class="absolute inset-0 opacity-[0.04] pointer-events-none" style="background-image: radial-gradient(#d4af37 1.5px, transparent 1.5px); background-size: 20px 20px;"></div>
        <div class="absolute -right-24 -top-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 animate-about-fade">
            {{-- Breadcrumb Navigation --}}
            <nav class="flex items-center gap-2 text-xs text-emerald-200/80 mb-5" aria-label="Breadcrumb">
                <a href="{{ route('frontend.home') }}" class="hover:text-white transition-colors flex items-center gap-1 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>প্রচ্ছদ</span>
                </a>
                <span class="text-emerald-400/50">/</span>
                <span class="text-emerald-200/90">পরিচিতি</span>
                <span class="text-emerald-400/50">/</span>
                <span class="text-white font-bold bg-white/10 px-2 py-0.5 rounded text-[11px] border border-white/10">ইতিহাস ও ঐতিহ্য</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div class="space-y-3 max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-xs text-emerald-100 backdrop-blur-xs">
                        <span class="h-2 w-2 rounded-full bg-amber-400 pulse-gold-badge"></span>
                        <span class="font-semibold tracking-wide">ঐতিহ্যবাহী বিদ্যাপীঠ</span>
                        <span class="text-white/40">•</span>
                        <span class="text-amber-300 font-medium">সাঁথিয়া, পাবনা</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-white leading-tight font-serif drop-shadow-xs">
                        {{ $about->title }}
                    </h1>

                    @if(!empty($about->subtitle))
                        <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed font-normal pt-1">
                            {{ $about->subtitle }}
                        </p>
                    @endif
                </div>

                @if(!empty($about->established_year))
                    <div class="shrink-0 flex items-center gap-3 bg-white/10 backdrop-blur-md border border-white/20 p-3 sm:p-4 rounded-xl shadow-lg">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 text-slate-950 flex flex-col items-center justify-center font-bold shadow-md">
                            <span class="text-[9px] uppercase tracking-tighter font-extrabold -mb-1">ESTD</span>
                            <span class="text-base font-extrabold font-mono">{{ $about->established_year }}</span>
                        </div>
                        <div class="text-xs">
                            <p class="text-emerald-200/80 font-medium">প্রতিষ্ঠাকালীন বর্ষ</p>
                            <p class="text-white font-bold text-sm">১৯৬৮ খ্রিষ্টাব্দ</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Golden bottom accent stripe --}}
        <div class="gold-accent-line absolute bottom-0 inset-x-0"></div>
    </section>

    {{-- Main Container Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14 space-y-12 lg:space-y-16">

        {{-- 2. History & Campus Story Section --}}
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

            {{-- Left Narrative Column --}}
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 space-y-6 shadow-sm interactive-card">
                <div class="border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#005030] mb-1">
                        <span class="w-2.5 h-2.5 rounded-sm bg-[#005030] inline-block"></span>
                        <span>প্রতিষ্ঠার প্রেক্ষাপট ও অগ্রযাত্রা</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 leading-snug font-serif">
                        {{ $about->history_heading ?: 'বিদ্যালয়ের গৌরবোজ্জ্বল ইতিহাস ও পটভূমি' }}
                    </h2>
                </div>

                {{-- Editorial Story Paragraphs --}}
                <div class="text-slate-700 leading-relaxed space-y-4 text-sm sm:text-[15px]">
                    @foreach(explode("\n\n", $about->history_content) as $pIdx => $paragraph)
                        @if(trim($paragraph))
                            <p class="{{ $pIdx === 0 ? 'text-slate-800 font-medium leading-loose border-l-3 border-[#005030] pl-4 bg-emerald-50/30 py-1 rounded-r' : '' }}">
                                {{ trim($paragraph) }}
                            </p>
                        @endif
                    @endforeach
                </div>

                {{-- Institutional Quality Badges --}}
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">প্রাতিষ্ঠানিক বৈশিষ্ট্য ও স্বীকৃতি</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-emerald-50/60 border border-emerald-100 hover:bg-emerald-100/60 transition-colors">
                            <div class="w-7 h-7 rounded-full bg-[#005030] text-white flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800">সরকারি স্বীকৃতিপ্রাপ্ত</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-emerald-50/60 border border-emerald-100 hover:bg-emerald-100/60 transition-colors">
                            <div class="w-7 h-7 rounded-full bg-[#005030] text-white flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800">শতভাগ এমপিওভুক্ত</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-2.5 rounded-lg bg-emerald-50/60 border border-emerald-100 hover:bg-emerald-100/60 transition-colors">
                            <div class="w-7 h-7 rounded-full bg-[#005030] text-white flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800">আধুনিক ডিজিটাল ল্যাব</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Campus Photo & Quick Facts Column --}}
            <div class="lg:col-span-5 space-y-6">

                {{-- Featured Photograph with Zoom and Frame --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-sm group interactive-card">
                    <div class="relative aspect-4/3 overflow-hidden bg-slate-900">
                        <img src="{{ $about->history_image_url }}"
                             alt="{{ $about->title }}"
                             class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-108">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>

                        {{-- Floating badge --}}
                        <div class="absolute top-3 right-3 bg-white/95 text-slate-900 text-[11px] font-bold px-2.5 py-1 rounded-full shadow-md flex items-center gap-1.5 border border-slate-100">
                            <span class="w-2 h-2 rounded-full bg-[#005030]"></span>
                            <span>ঐতিহাসিক ক্যাম্পাস</span>
                        </div>

                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <p class="text-sm font-bold truncate text-white drop-shadow-xs">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}</p>
                            <p class="text-xs text-emerald-200/90 mt-0.5">প্রধান একাডেমিক ভবন ও সুপরিসর সবুজ প্রাঙ্গণ</p>
                        </div>
                    </div>
                    <div class="p-3.5 bg-slate-50/80 border-t border-slate-100 text-xs text-slate-600 flex items-center justify-between">
                        <span>সাঁথিয়া-বেড়া আঞ্চলিক শিক্ষাকেন্দ্র</span>
                        <span class="text-[#005030] font-bold">স্থাপিত ১৯৬৮ খ্রি.</span>
                    </div>
                </div>

                {{-- Fast Telemetry Highlights --}}
                <div class="bg-white rounded-2xl border border-emerald-900/15 p-5 shadow-sm space-y-3 relative overflow-hidden interactive-card">
                    <div class="absolute top-0 right-0 w-28 h-28 bg-emerald-50 rounded-full blur-xl pointer-events-none -mr-10 -mt-10"></div>

                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#005030] text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                EIIN
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">সোনাতলা উচ্চ বিদ্যালয়</h4>
                                <p class="text-xs text-slate-500">পাবনা জেলা • স্থাপিত ১৯৬৮</p>
                            </div>
                        </div>
                        <span class="font-mono font-bold text-emerald-900 bg-emerald-100/80 px-2.5 py-1 rounded text-xs border border-emerald-200">
                            ১২৫৬৯৩
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-1 text-xs">
                        <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100 hover:border-emerald-300 transition-colors">
                            <span class="text-slate-500 block text-[11px]">স্কুল কোড</span>
                            <span class="font-bold text-slate-900 font-mono text-sm">৮১০৭</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100 hover:border-emerald-300 transition-colors">
                            <span class="text-slate-500 block text-[11px]">এমপিও কোড</span>
                            <span class="font-bold text-slate-900 font-mono text-sm">৮১০৭০২১৩০২</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- 3. Vision, Mission & Values (3-Pillar Interactive Cards) --}}
        <section class="space-y-6">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-[#005030] bg-[#005030]/10 px-3 py-1 rounded-full">
                    প্রাতিষ্ঠানিক অঙ্গীকার
                </span>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 font-serif section-header-line center">
                    লক্ষ্য, উদ্দেশ্য ও মূল আদর্শ
                </h2>
                <p class="text-xs sm:text-sm text-slate-600">
                    শিক্ষার্থীদের সার্বিক মেধা বিকাশ ও মূল্যবোধসম্পন্ন আলোকিত মানুষ গড়ার প্রত্যয়
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">

                {{-- Pillar 1: Vision --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-7 space-y-4 shadow-sm interactive-card group relative overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-[#005030]"></div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:bg-[#005030] group-hover:text-white shadow-xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-[#005030] transition-colors">
                            {{ $about->vision_title ?: 'আমাদের লক্ষ্য (Vision)' }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mt-2">
                            {{ $about->vision_text ?: 'প্রতিটি শিক্ষার্থীকে সুনাগরিক, দেশপ্রেমিক, আধুনিক বিজ্ঞানমনস্ক ও মানবিক মূল্যবোধসম্পন্ন মানুষ হিসেবে গড়ে তোলা।' }}
                        </p>
                    </div>
                </div>

                {{-- Pillar 2: Mission --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-7 space-y-4 shadow-sm interactive-card group relative overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-blue-600"></div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white shadow-xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-700 transition-colors">
                            {{ $about->mission_title ?: 'আমাদের উদ্দেশ্য (Mission)' }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mt-2">
                            {{ $about->mission_text ?: 'আনন্দমুখর ও সুশৃঙ্খল পরিবেশে মানসম্মত শিক্ষা নিশ্চিত করা এবং আধুনিক তথ্যপ্রযুক্তির মাধ্যমে শিক্ষার্থীদের ভবিষ্যতের উপযোগী করে তোলা।' }}
                        </p>
                    </div>
                </div>

                {{-- Pillar 3: Values --}}
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-7 space-y-4 shadow-sm interactive-card group relative overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-amber-500"></div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white shadow-xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-amber-700 transition-colors">
                            {{ $about->values_title ?: 'আমাদের মূল আদর্শ' }}
                        </h3>
                        <ul class="space-y-2 text-xs sm:text-sm text-slate-600 mt-2">
                            @php
                                $valuesList = !empty($about->values_list) ? $about->values_list : [
                                    'সততা ও নিষ্ঠা',
                                    'শৃঙ্খলা ও নিয়মানুবর্তিতা',
                                    'দেশপ্রেম ও মানবিকতা',
                                ];
                            @endphp
                            @foreach($valuesList as $vItem)
                                <li class="flex items-center gap-2.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                    <span class="font-medium text-slate-800">{{ $vItem }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </section>

        {{-- 4. Key Statistics & Achievements Counter Section (Rich Emerald Theme) --}}
        @if(!empty($about->statistics) && is_array($about->statistics))
        <section class="relative bg-gradient-to-r from-[#003820] via-[#005030] to-[#002f1d] rounded-3xl p-8 sm:p-10 text-white shadow-xl overflow-hidden">
            <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-emerald-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-16 -top-16 w-64 h-64 bg-amber-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 text-center mb-8">
                <span class="inline-block text-xs uppercase tracking-widest text-emerald-300 font-bold bg-white/10 px-3 py-0.5 rounded-full mb-2">
                    পরিসংখ্যান ও গৌরব
                </span>
                <h3 class="text-2xl sm:text-3xl font-bold font-serif text-white">এক পলকে সোনাতলা উচ্চ বিদ্যালয়</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 text-center relative z-10">
                @foreach($about->statistics as $stat)
                    <div class="p-4 sm:p-5 rounded-2xl bg-white/10 backdrop-blur-xs border border-white/15 hover:bg-white/18 hover:border-white/30 hover:-translate-y-1.5 transition-all duration-300 group">
                        <p class="text-3xl sm:text-4xl lg:text-5xl font-extrabold font-mono tracking-tight text-amber-300 group-hover:scale-105 transition-transform duration-300">
                            {{ $stat['value'] ?? '' }}
                        </p>
                        <p class="text-xs sm:text-sm font-bold text-white mt-2">{{ $stat['label'] ?? '' }}</p>
                        @if(!empty($stat['note']))
                            <p class="text-[11px] text-emerald-100/70 mt-1">{{ $stat['note'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 5. Chronological Milestones Timeline --}}
        @if(!empty($about->milestones) && is_array($about->milestones))
        <section class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-10 space-y-8 shadow-sm interactive-card">
            <div class="border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#005030] mb-1">
                    <span class="w-2.5 h-2.5 rounded-sm bg-[#005030] inline-block"></span>
                    <span>অগ্রযাত্রার রূপরেখা</span>
                </div>
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 font-serif">
                    আমাদের ঐতিহাসিক মাইলফলক
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">
                    ১৯৬৮ থেকে বর্তমান — সোনাতলা উচ্চ বিদ্যালয়ের ধারাবাহিক উন্নয়নের সোনালী স্মৃতি
                </p>
            </div>

            <div class="relative border-l-2 border-emerald-800/25 ml-4 sm:ml-6 pl-6 sm:pl-8 space-y-8">
                @foreach($about->milestones as $m)
                    <div class="relative group">
                        {{-- Pulsing timeline dot --}}
                        <div class="absolute -left-[31px] sm:-left-[39px] top-1.5 w-4 h-4 rounded-full border-2 border-[#005030] bg-white group-hover:bg-[#005030] group-hover:ring-4 group-hover:ring-emerald-500/20 transition-all duration-300"></div>

                        <div class="p-4 sm:p-5 rounded-xl bg-slate-50/70 border border-slate-100 group-hover:border-emerald-300 group-hover:bg-emerald-50/40 group-hover:-translate-y-1 transition-all duration-300 shadow-2xs">
                            <span class="inline-block px-3 py-0.5 rounded-full bg-emerald-100 text-[#005030] text-xs font-mono font-bold border border-emerald-200">
                                {{ $m['year'] ?? '' }} খ্রিষ্টাব্দ
                            </span>
                            <h4 class="text-base sm:text-lg font-bold text-slate-900 mt-2 group-hover:text-[#005030] transition-colors">
                                {{ $m['title'] ?? '' }}
                            </h4>
                            @if(!empty($m['description']))
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mt-1">
                                    {{ $m['description'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 6. Two-Column Layout: At a Glance & Managing Committee --}}
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Left Column: School at a Glance Specifications Table --}}
            <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-sm interactive-card">
                <div class="bg-[#005030] text-white px-5 py-3.5 font-bold text-sm uppercase tracking-wider flex items-center justify-between border-b-2 border-amber-400">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>এক নজরে প্রতিষ্ঠান</span>
                    </span>
                    <span class="text-[11px] text-emerald-200 font-mono">Quick Facts</span>
                </div>

                <dl class="divide-y divide-slate-100 text-xs sm:text-sm p-4 sm:p-5 space-y-1">
                    @php
                        $glanceList = !empty($about->at_a_glance) ? $about->at_a_glance : [
                            ['key' => 'প্রতিষ্ঠানের নাম', 'value' => 'সোনাতলা উচ্চ বিদ্যালয়'],
                            ['key' => 'EIIN নম্বর', 'value' => '১২৫৬৯৩'],
                            ['key' => 'স্কুল কোড', 'value' => '৮১০৭'],
                            ['key' => 'এমপিও কোড', 'value' => '৮১০৭০২১৩০২'],
                            ['key' => 'শিক্ষা বোর্ড', 'value' => 'রাজশাহী'],
                        ];
                    @endphp
                    @foreach($glanceList as $glance)
                        <div class="py-2.5 px-2 rounded-lg flex justify-between items-center gap-4 hover:bg-emerald-50/50 transition-colors">
                            <dt class="text-slate-500 shrink-0 font-medium">{{ $glance['key'] ?? '' }}:</dt>
                            <dd class="font-bold text-slate-900 text-right font-mono">{{ $glance['value'] ?? '' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- Right Column: Managing Committee Directory --}}
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-sm interactive-card">
                <div class="bg-[#005030] text-white px-5 py-3.5 font-bold text-sm uppercase tracking-wider flex items-center justify-between border-b-2 border-amber-400">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>বিদ্যালয় পরিচালনা পর্ষদ</span>
                    </span>
                    <span class="text-[11px] text-emerald-200 font-mono">Committee</span>
                </div>

                <div class="overflow-x-auto p-4 sm:p-5">
                    <table class="w-full text-left text-xs sm:text-sm border border-slate-200 rounded-xl overflow-hidden">
                        <thead class="bg-slate-100/80 text-slate-800 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-3 border-r border-slate-200 text-center w-14">ক্রমিক</th>
                                <th class="p-3 border-r border-slate-200">সদস্যের নাম</th>
                                <th class="p-3 border-r border-slate-200">পদবি</th>
                                <th class="p-3">দায়িত্ব / ভূমিকা</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800">
                            @php
                                $committeeList = !empty($about->committee_members) ? $about->committee_members : [];
                            @endphp
                            @if(!empty($committeeList))
                                @foreach($committeeList as $cIdx => $member)
                                    <tr class="hover:bg-emerald-50/50 transition-colors {{ $cIdx % 2 == 1 ? 'bg-slate-50/40' : '' }}">
                                        <td class="p-3 border-r border-slate-200 text-center font-bold font-mono text-slate-500">
                                            {{ sprintf('%02d', $cIdx + 1) }}
                                        </td>
                                        <td class="p-3 border-r border-slate-200 font-bold text-[#005030]">
                                            {{ $member['name'] ?? '' }}
                                        </td>
                                        <td class="p-3 border-r border-slate-200">
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                {{ $member['designation'] ?? '' }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-slate-600">
                                            {{ $member['role'] ?? '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-slate-400 italic">তথ্য শীঘ্রই হালনাগাদ করা হবে।</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

        {{-- 7. Modern Campus Facilities & Environment (Interactive 4-Card Grid) --}}
        @if(!empty($about->facilities) && is_array($about->facilities))
        <section class="space-y-6">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-[#005030] bg-[#005030]/10 px-3 py-1 rounded-full">
                    অবকাঠামো ও সুযোগ-সুবিধা
                </span>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 font-serif section-header-line center">
                    ক্যাম্পাস ও আধুনিক সুযোগ-সুবিধা
                </h2>
                <p class="text-xs sm:text-sm text-slate-600">
                    শিক্ষার্থীদের জন্য উপযোগী নিরাপদ, মনোরম ও আধুনিক শিক্ষাবান্ধব পরিবেশ
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 pt-2">
                @foreach($about->facilities as $fac)
                    <div class="bg-white rounded-2xl border border-slate-200/90 p-6 space-y-3 shadow-sm interactive-card group">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:bg-[#005030] group-hover:text-white shadow-xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-900 group-hover:text-[#005030] transition-colors">
                            {{ $fac['title'] ?? '' }}
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            {{ $fac['description'] ?? '' }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

    </div>

</div>

@endsection

