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

    {{-- Dedicated CSS Micro-Interactions & Styling --}}
    <style>
        .about-page-wrapper {
            font-family: 'Hind Siliguri', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        .about-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
        }
        .about-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
            border-color: #005030;
        }
        .icon-box {
            transition: transform 0.22s ease, background-color 0.22s ease, color 0.22s ease;
        }
        .about-card:hover .icon-box {
            transform: scale(1.08);
            background-color: #005030;
            color: #ffffff;
        }
        .stat-box {
            background-color: #003820;
            border: 1px solid rgba(16, 185, 129, 0.35);
            border-radius: 0.75rem;
            transition: transform 0.22s ease, background-color 0.22s ease, border-color 0.22s ease;
        }
        .stat-box:hover {
            transform: translateY(-3px);
            background-color: #004427;
            border-color: #34d399;
        }
        .timeline-card {
            transition: transform 0.22s ease, border-color 0.22s ease, box-shadow 0.22s ease;
        }
        .timeline-card:hover {
            transform: translateX(4px);
            border-color: #005030;
            box-shadow: 0 4px 12px -2px rgba(0, 80, 48, 0.08);
        }
        .timeline-dot {
            transition: transform 0.22s ease, background-color 0.22s ease, box-shadow 0.22s ease;
        }
        .timeline-group:hover .timeline-dot {
            transform: scale(1.3);
            background-color: #005030;
            box-shadow: 0 0 0 4px rgba(0, 80, 48, 0.22);
        }
        .photo-card {
            overflow: hidden;
            border-radius: 0.75rem;
        }
        .photo-frame {
            transition: transform 0.5s ease;
        }
        .photo-card:hover .photo-frame {
            transform: scale(1.05);
        }
        .table-row-hover {
            transition: background-color 0.15s ease;
        }
        .table-row-hover:hover {
            background-color: #f0fdf4 !important;
        }
    </style>
@endpush

@section('content')

<div class="about-page-wrapper bg-slate-50 text-slate-800">

    {{-- 1. Standard Executive Breadcrumb Bar --}}
    <div class="bg-gray-100 border-b border-gray-200 py-3 text-xs text-gray-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
                <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>প্রচ্ছদ</span>
                </a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-500">পরিচিতি</span>
                <span class="text-gray-400">/</span>
                <span class="text-gray-900 font-bold">ইতিহাস ও ঐতিহ্য</span>
            </nav>
            <span class="text-gray-400 text-xs font-medium hidden sm:inline-block">সোনাতলা উচ্চ বিদ্যালয় • সাঁথিয়া, পাবনা</span>
        </div>
    </div>

    {{-- Main Page Container --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        {{-- 2. Institutional Header Banner (Signature Deep Forest Green) --}}
        <div class="bg-[#005030] text-white p-6 sm:p-7 rounded-xl shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-6 border-l-4 border-amber-400">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded bg-[#003820] text-emerald-200 text-xs font-bold uppercase tracking-wider border border-emerald-700/60">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    <span>গৌরবোজ্জ্বল ঐতিহ্য ও স্মৃতি</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white leading-tight">
                    {{ $about->title }}
                </h1>
                @if(!empty($about->subtitle))
                    <p class="text-xs sm:text-sm text-emerald-100/90 max-w-2xl leading-relaxed">
                        {{ $about->subtitle }}
                    </p>
                @endif
            </div>

            @if(!empty($about->established_year))
                <div class="shrink-0 flex items-center gap-3 bg-[#003820] border border-emerald-700/60 px-4 py-3 rounded-lg text-white shadow-xs">
                    <div class="w-12 h-12 rounded bg-amber-400 text-slate-950 flex flex-col items-center justify-center font-bold shrink-0">
                        <span class="text-[8px] uppercase tracking-tighter font-extrabold -mb-1">ESTD</span>
                        <span class="text-base font-extrabold font-mono">{{ $about->established_year }}</span>
                    </div>
                    <div class="text-xs">
                        <p class="text-emerald-200/80 font-medium text-[11px]">প্রতিষ্ঠাকালীন বর্ষ</p>
                        <p class="text-white font-bold text-sm">{{ $about->established_year }} খ্রিষ্টাব্দ</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- 3. Narrative & Featured Campus Photo --}}
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

            {{-- Left Story Column --}}
            <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 sm:p-8 space-y-6 shadow-2xs about-card">
                <div class="border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#005030] mb-1">
                        <span class="w-2 h-2 rounded-xs bg-[#005030] inline-block"></span>
                        <span>প্রতিষ্ঠার প্রেক্ষাপট ও অগ্রযাত্রা</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 leading-snug">
                        {{ $about->history_heading ?: 'বিদ্যালয়ের গৌরবোজ্জ্বল ইতিহাস ও পটভূমি' }}
                    </h2>
                </div>

                {{-- Story Paragraphs --}}
                <div class="text-slate-700 leading-relaxed space-y-4 text-xs sm:text-sm">
                    @foreach(explode("\n\n", $about->history_content) as $pIdx => $paragraph)
                        @if(trim($paragraph))
                            <p class="{{ $pIdx === 0 ? 'text-slate-800 font-medium leading-loose border-l-2 border-[#005030] pl-3.5 bg-slate-50/70 py-1.5 rounded-r' : '' }}">
                                {{ trim($paragraph) }}
                            </p>
                        @endif
                    @endforeach
                </div>

                {{-- Institutional Quality Badges --}}
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2.5">প্রাতিষ্ঠানিক স্বীকৃতি ও গুণমান</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                        <div class="flex items-center gap-2 p-2 rounded bg-emerald-50/70 border border-emerald-100 text-xs font-semibold text-slate-800">
                            <svg class="w-4 h-4 text-[#005030] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>সরকারি স্বীকৃতিপ্রাপ্ত</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded bg-emerald-50/70 border border-emerald-100 text-xs font-semibold text-slate-800">
                            <svg class="w-4 h-4 text-[#005030] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>শতভাগ এমপিওভুক্ত</span>
                        </div>
                        <div class="flex items-center gap-2 p-2 rounded bg-emerald-50/70 border border-emerald-100 text-xs font-semibold text-slate-800">
                            <svg class="w-4 h-4 text-[#005030] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>আধুনিক ডিজিটাল ল্যাব</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Campus Photo Column --}}
            <div class="lg:col-span-5 space-y-4">
                {{-- Campus Photo Card --}}
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-2xs photo-card about-card">
                    <div class="relative h-64 sm:h-72 w-full bg-slate-900 overflow-hidden">
                        <img src="{{ $about->history_image_url }}"
                             alt="{{ $about->title }}"
                             class="w-full h-full object-cover photo-frame">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/25 to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3 text-white text-xs">
                            <p class="font-bold truncate text-white">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}</p>
                            <p class="text-[11px] text-emerald-200/90 mt-0.5">প্রধান একাডেমিক ভবন ও সুপরিসর সবুজ ক্যাম্পাস</p>
                        </div>
                    </div>
                    <div class="p-3 bg-slate-50 border-t border-slate-100 text-xs text-slate-600 flex items-center justify-between">
                        <span>সাঁথিয়া-বেড়া আঞ্চলিক শিক্ষাকেন্দ্র</span>
                        <span class="text-[#005030] font-bold">স্থাপিত ১৯৬৮ খ্রি.</span>
                    </div>
                </div>

                {{-- Fast Telemetry Box --}}
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3 about-card">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-[#005030] text-white flex items-center justify-center font-bold text-xs">
                                EIIN
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-xs">সোনাতলা উচ্চ বিদ্যালয়</h4>
                                <p class="text-[11px] text-slate-500">পাবনা জেলা • স্থাপিত ১৯৬৮</p>
                            </div>
                        </div>
                        <span class="font-mono font-bold text-emerald-900 bg-emerald-100 px-2 py-0.5 rounded text-xs border border-emerald-200">
                            ১২৫৬৯৩
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="p-2 rounded bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 block text-[10px]">স্কুল কোড</span>
                            <span class="font-bold text-slate-900 font-mono">৮১০৭</span>
                        </div>
                        <div class="p-2 rounded bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 block text-[10px]">এমপিও কোড</span>
                            <span class="font-bold text-slate-900 font-mono">৮১০৭০২১৩০২</span>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        {{-- 4. Vision, Mission & Core Values (3-Pillar Cards) --}}
        <section class="space-y-4">
            <div class="text-center max-w-xl mx-auto space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-[#005030]">প্রাতিষ্ঠানিক অঙ্গীকার</span>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900">লক্ষ্য, উদ্দেশ্য ও মূল আদর্শ</h2>
                <div class="w-12 h-0.5 bg-[#005030] mx-auto mt-1 rounded"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2">
                {{-- Vision --}}
                <div class="about-card p-6 space-y-3.5 border-t-4 border-t-[#005030]">
                    <div class="w-11 h-11 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center icon-box shadow-2xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">{{ $about->vision_title ?: 'আমাদের লক্ষ্য (Vision)' }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $about->vision_text ?: 'প্রতিটি শিক্ষার্থীকে সুনাগরিক, দেশপ্রেমিক, আধুনিক বিজ্ঞানমনস্ক ও মানবিক মূল্যবোধসম্পন্ন মানুষ হিসেবে গড়ে তোলা।' }}
                    </p>
                </div>

                {{-- Mission --}}
                <div class="about-card p-6 space-y-3.5 border-t-4 border-t-[#005030]">
                    <div class="w-11 h-11 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center icon-box shadow-2xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">{{ $about->mission_title ?: 'আমাদের উদ্দেশ্য (Mission)' }}</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $about->mission_text ?: 'আনন্দমুখর ও সুশৃঙ্খল পরিবেশে মানসম্মত শিক্ষা নিশ্চিত করা এবং আধুনিক তথ্যপ্রযুক্তির মাধ্যমে শিক্ষার্থীদের ভবিষ্যতের উপযোগী করে তোলা।' }}
                    </p>
                </div>

                {{-- Values --}}
                <div class="about-card p-6 space-y-3.5 border-t-4 border-t-[#005030]">
                    <div class="w-11 h-11 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center icon-box shadow-2xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">{{ $about->values_title ?: 'আমাদের মূল আদর্শ ও মূল্যবোধ' }}</h3>
                    <ul class="space-y-2 text-xs text-slate-600">
                        @php
                            $valuesList = !empty($about->values_list) ? $about->values_list : [
                                'সততা ও নৈতিক নিষ্ঠা',
                                'শৃঙ্খলা ও কঠোর নিয়মানুবর্তিতা',
                                'দেশপ্রেম ও সামাজিক দায়বদ্ধতা',
                                'আধুনিক বিজ্ঞানমনস্কতা ও মুক্তবুদ্ধি চর্চা',
                            ];
                        @endphp
                        @foreach($valuesList as $vItem)
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#005030] shrink-0"></span>
                                <span class="font-medium text-slate-700">{{ $vItem }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        {{-- 5. Statistics Counters (Solid Institutional Deep Green) --}}
        @if(!empty($about->statistics) && is_array($about->statistics))
        <section class="bg-[#005030] text-white rounded-xl p-6 sm:p-8 shadow-xs border border-emerald-800">
            <div class="text-center mb-6">
                <span class="inline-block text-[11px] uppercase tracking-wider text-emerald-300 font-bold bg-[#003820] px-3 py-0.5 rounded-full border border-emerald-700/60 mb-1.5">
                    পরিসংখ্যান ও অর্জন
                </span>
                <h3 class="text-xl sm:text-2xl font-bold text-white">এক নজরে সোনাতলা উচ্চ বিদ্যালয়</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                @foreach($about->statistics as $stat)
                    <div class="stat-box p-4">
                        <p class="text-2xl sm:text-3xl lg:text-4xl font-extrabold font-mono tracking-tight text-white">
                            {{ $stat['value'] ?? '' }}
                        </p>
                        <p class="text-xs sm:text-sm font-bold text-emerald-200 mt-1.5">{{ $stat['label'] ?? '' }}</p>
                        @if(!empty($stat['note']))
                            <p class="text-[10px] text-emerald-300/80 mt-0.5">{{ $stat['note'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 6. Chronological Milestones Timeline --}}
        @if(!empty($about->milestones) && is_array($about->milestones))
        <section class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 space-y-6 shadow-2xs about-card">
            <div class="border-b border-slate-100 pb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-[#005030]">অগ্রযাত্রার রূপরেখা</span>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mt-1">আমাদের ঐতিহাসিক মাইলফলক</h2>
                <p class="text-xs text-slate-500 mt-0.5">১৯৬৮ সাল থেকে বর্তমান — সোনাতলা উচ্চ বিদ্যালয়ের ধারাবাহিক উন্নয়নের সোনালী স্মৃতি</p>
            </div>

            <div class="relative border-l-2 border-emerald-700/30 ml-3 sm:ml-4 pl-6 sm:pl-8 space-y-6">
                @foreach($about->milestones as $m)
                    <div class="relative timeline-group">
                        {{-- Dot --}}
                        <div class="absolute -left-[31px] sm:-left-[39px] top-1.5 w-3.5 h-3.5 rounded-full border-2 border-[#005030] bg-white timeline-dot"></div>

                        <div class="p-4 rounded-lg bg-slate-50 border border-slate-200/80 timeline-card">
                            <span class="inline-block px-2.5 py-0.5 rounded bg-emerald-100 text-[#005030] text-xs font-mono font-bold border border-emerald-200">
                                {{ $m['year'] ?? '' }} খ্রিষ্টাব্দ
                            </span>
                            <h4 class="text-sm sm:text-base font-bold text-slate-900 mt-1.5">
                                {{ $m['title'] ?? '' }}
                            </h4>
                            @if(!empty($m['description']))
                                <p class="text-xs text-slate-600 leading-relaxed mt-1">
                                    {{ $m['description'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- 7. School At a Glance & Managing Committee --}}
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            {{-- At a Glance (Col 5) --}}
            <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200 overflow-hidden shadow-2xs about-card">
                <div class="bg-[#005030] text-white px-4 py-3 font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>এক নজরে প্রতিষ্ঠান</span>
                    </span>
                    <span class="text-[10px] text-emerald-200 font-mono">Quick Facts</span>
                </div>

                <dl class="divide-y divide-slate-100 text-xs p-4 space-y-0.5">
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
                        <div class="py-2.5 px-2 flex justify-between items-center gap-4 table-row-hover rounded">
                            <dt class="text-slate-500 shrink-0 font-medium">{{ $glance['key'] ?? '' }}:</dt>
                            <dd class="font-bold text-slate-900 text-right font-mono">{{ $glance['value'] ?? '' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- Managing Committee (Col 7) --}}
            <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 overflow-hidden shadow-2xs about-card">
                <div class="bg-[#005030] text-white px-4 py-3 font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>বিদ্যালয় পরিচালনা পর্ষদ</span>
                    </span>
                    <span class="text-[10px] text-emerald-200 font-mono">Committee</span>
                </div>

                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left text-xs border border-slate-200 rounded">
                        <thead class="bg-slate-50 text-slate-800 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-2.5 border-r border-slate-200 text-center w-12">ক্রমিক</th>
                                <th class="p-2.5 border-r border-slate-200">নাম</th>
                                <th class="p-2.5 border-r border-slate-200">পদবি</th>
                                <th class="p-2.5">দায়িত্ব ও ভূমিকা</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800">
                            @php
                                $committeeList = !empty($about->committee_members) ? $about->committee_members : [];
                            @endphp
                            @if(!empty($committeeList))
                                @foreach($committeeList as $cIdx => $member)
                                    <tr class="table-row-hover {{ $cIdx % 2 == 1 ? 'bg-slate-50/50' : '' }}">
                                        <td class="p-2.5 border-r border-slate-200 text-center font-bold font-mono text-slate-500">
                                            {{ sprintf('%02d', $cIdx + 1) }}
                                        </td>
                                        <td class="p-2.5 border-r border-slate-200 font-bold text-[#005030]">
                                            {{ $member['name'] ?? '' }}
                                        </td>
                                        <td class="p-2.5 border-r border-slate-200">
                                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                {{ $member['designation'] ?? '' }}
                                            </span>
                                        </td>
                                        <td class="p-2.5 text-slate-600">
                                            {{ $member['role'] ?? '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-slate-400 italic">তথ্য শীঘ্রই হালনাগাদ করা হবে।</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

        {{-- 8. Campus Facilities Grid --}}
        @if(!empty($about->facilities) && is_array($about->facilities))
        <section class="space-y-4">
            <div class="text-center max-w-xl mx-auto space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-[#005030]">অবকাঠামো</span>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900">ক্যাম্পাস ও আধুনিক সুযোগ-সুবিধা</h2>
                <div class="w-12 h-0.5 bg-[#005030] mx-auto mt-1 rounded"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
                @foreach($about->facilities as $fac)
                    <div class="about-card p-5 space-y-2">
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center icon-box shadow-2xs">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900">{{ $fac['title'] ?? '' }}</h4>
                        <p class="text-[11px] text-slate-600 leading-relaxed">{{ $fac['description'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

    </div>

</div>

@endsection
