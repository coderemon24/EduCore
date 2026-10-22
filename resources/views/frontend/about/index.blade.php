@extends('layouts.app')

{{-- SEO meta --}}
@section('title', $about->meta_title ?: ($about->title . ' | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়')))
@section('meta_description', $about->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($about->history_content), 160))

@push('styles')
    {{-- OpenGraph tags --}}
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

    {{-- Schema JSON-LD --}}
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
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="bg-gray-100 border-b border-gray-200 py-3.5 text-xs text-gray-600" id="overview">
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
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">সোনাতলা উচ্চ বিদ্যালয় • সাঁথিয়া, পাবনা</span>
    </div>
</div>

{{-- Hero Banner --}}
<div class="bg-[#005030] text-white py-14 sm:py-20 border-b border-emerald-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-3.5">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#003820] text-emerald-200 text-xs font-bold uppercase tracking-wider border border-emerald-700/60">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span>গৌরবোজ্জ্বল ঐতিহ্য ও স্মৃতি</span>
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                {{ $about->title ?: 'সোনাতলা উচ্চ বিদ্যালয় — ইতিহাস ও ঐতিহ্য' }}
            </h1>
            @if(!empty($about->subtitle))
                <p class="text-sm sm:text-base text-emerald-100/90 max-w-2xl leading-relaxed mt-2">
                    {{ $about->subtitle }}
                </p>
            @endif
        </div>

        @if(!empty($about->established_year))
            <div class="shrink-0 flex items-center gap-3.5 bg-[#003820] border border-emerald-700/60 px-6 py-4 rounded-2xl text-white shadow-xs">
                <div class="w-13 h-13 rounded-xl bg-emerald-800/80 border border-emerald-600/50 text-emerald-200 flex flex-col items-center justify-center font-bold shrink-0">
                    <span class="text-[9px] uppercase tracking-wider font-extrabold -mb-0.5">ESTD</span>
                    <span class="text-base font-extrabold font-mono text-white">{{ $about->established_year }}</span>
                </div>
                <div class="text-xs">
                    <p class="text-emerald-300/80 font-medium text-[11px]">প্রতিষ্ঠাকালীন বর্ষ</p>
                    <p class="text-white font-bold text-sm sm:text-base">{{ $about->established_year }} খ্রিষ্টাব্দ</p>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Credentials Strip --}}
<div class="bg-slate-900 text-slate-200 py-4 border-y border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs font-semibold">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-emerald-400 flex items-center justify-center text-xs shrink-0"><i class="fa-solid fa-building-columns"></i></span>
                <div>
                    <span class="text-slate-400 block text-[10px]">EIIN নম্বর</span>
                    <span class="text-white font-mono font-bold text-xs sm:text-sm">১২৫৬৯৩</span>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-emerald-400 flex items-center justify-center text-xs shrink-0"><i class="fa-solid fa-award"></i></span>
                <div>
                    <span class="text-slate-400 block text-[10px]">শিক্ষা বোর্ড</span>
                    <span class="text-white font-bold text-xs sm:text-sm">রাজশাহী বোর্ড</span>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-emerald-400 flex items-center justify-center text-xs shrink-0"><i class="fa-solid fa-certificate"></i></span>
                <div>
                    <span class="text-slate-400 block text-[10px]">সরকারি স্বীকৃতি</span>
                    <span class="text-white font-bold text-xs sm:text-sm">শতভাগ এমপিওভুক্ত</span>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-slate-800 text-emerald-400 flex items-center justify-center text-xs shrink-0"><i class="fa-solid fa-calendar-check"></i></span>
                <div>
                    <span class="text-slate-400 block text-[10px]">ঐতিহ্য</span>
                    <span class="text-white font-bold text-xs sm:text-sm">১৯৬৮ খ্রি. থেকে চলমান</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- History & Narrative --}}
<section class="home-section bg-white" id="history">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header-block">
            <div class="section-header-left">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    FOUNDING HERITAGE — প্রতিষ্ঠার প্রেক্ষাপট ও অগ্রযাত্রা
                </span>
                <h2 class="section-title">
                    বিদ্যালয়ের গৌরবোজ্জ্বল ইতিহাস ও <span class="text-[#005030] font-bold">পটভূমি</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    পাবনার সাঁথিয়া উপজেলার শিক্ষা ও সংস্কৃতির অন্যতম পথিকৃৎ হিসেবে সোনাতলা উচ্চ বিদ্যালয় অর্ধশতাব্দীর বেশি সময় ধরে নিরলসভাবে মানসম্মত শিক্ষা ও মানবিক মূল্যবোধ ছড়িয়ে যাচ্ছে।
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Story --}}
            <div class="lg:col-span-7 about-card-hero space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#005030] block mb-1">সূচনালগ্ন থেকে বর্তমান</span>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 leading-snug">
                        {{ $about->history_heading ?: 'বিদ্যালয়ের গৌরবোজ্জ্বল ইতিহাস ও পটভূমি' }}
                    </h3>
                </div>

                <div class="text-slate-700 leading-relaxed space-y-4 text-sm sm:text-base">
                    @foreach(explode("\n\n", $about->history_content) as $pIdx => $paragraph)
                        @if(trim($paragraph))
                            <p class="{{ $pIdx === 0 ? 'text-slate-800 font-medium leading-relaxed border-l-2 border-[#005030] pl-4 bg-slate-50/80 py-3 rounded-r text-sm sm:text-base mb-4' : 'text-slate-700 leading-relaxed text-sm sm:text-base mb-4' }}">
                                {{ trim($paragraph) }}
                            </p>
                        @endif
                    @endforeach
                </div>

                {{-- Quality badges --}}
                <div class="pt-6 border-t border-slate-100">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-3">প্রাতিষ্ঠানিক স্বীকৃতি ও গুণমান</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/90 text-xs font-semibold text-slate-800 shadow-2xs hover:border-emerald-300 transition-colors">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100/80 text-[#005030] flex items-center justify-center shrink-0 text-xs">
                                <i class="fa-solid fa-award"></i>
                            </span>
                            <span>সরকারি স্বীকৃতিপ্রাপ্ত</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/90 text-xs font-semibold text-slate-800 shadow-2xs hover:border-emerald-300 transition-colors">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100/80 text-[#005030] flex items-center justify-center shrink-0 text-xs">
                                <i class="fa-solid fa-circle-check"></i>
                            </span>
                            <span>শতভাগ এমপিওভুক্ত</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200/90 text-xs font-semibold text-slate-800 shadow-2xs hover:border-emerald-300 transition-colors">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100/80 text-[#005030] flex items-center justify-center shrink-0 text-xs">
                                <i class="fa-solid fa-microchip"></i>
                            </span>
                            <span>আধুনিক ডিজিটাল ল্যাব</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Campus photo & stats --}}
            <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-24">
                <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs hover:shadow-md transition-all duration-300 group">
                    <div class="relative h-64 sm:h-72 w-full bg-slate-900 overflow-hidden">
                        <div class="absolute top-4 left-4 z-20 bg-slate-950/85 backdrop-blur-md text-white border border-white/20 px-3.5 py-1.5 rounded-full text-xs font-bold shadow-lg flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Estd. {{ $about->established_year ?? '১৯৬৮' }} • সাঁথিয়া</span>
                        </div>

                        <img src="{{ $about->history_image_url }}"
                             alt="{{ $about->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <p class="font-extrabold truncate text-white text-base sm:text-lg tracking-tight">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}</p>
                            <p class="text-xs text-emerald-200/95 mt-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-tree text-emerald-400 text-[11px]"></i>
                                <span>প্রধান একাডেমিক ভবন ও সুপরিসর সবুজ ক্যাম্পাস</span>
                            </p>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/90 border-t border-slate-100 text-xs text-slate-600 flex items-center justify-between">
                        <span class="flex items-center gap-1.5 font-medium text-slate-700">
                            <i class="fa-solid fa-graduation-cap text-[#005030]"></i>
                            <span>সাঁথিয়া-বেড়া আঞ্চলিক শিক্ষাকেন্দ্র</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#005030] text-[11px] font-bold border border-emerald-200/80">
                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                            <span>মূল ক্যাম্পাস</span>
                        </span>
                    </div>
                </div>

                <div class="about-card-stack space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#005030] text-white flex items-center justify-center font-bold text-xs shadow-2xs">
                                EIIN
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">সোনাতলা উচ্চ বিদ্যালয়</h4>
                                <p class="text-xs text-slate-500">পাবনা জেলা • স্থাপিত ১৯৬৮</p>
                            </div>
                        </div>
                        <span class="font-mono font-bold text-emerald-900 bg-emerald-100/90 px-2.5 py-1 rounded-lg text-xs border border-emerald-200">
                            ১২৫৬৯৩
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 block text-[11px] font-medium">স্কুল কোড</span>
                            <span class="font-bold text-slate-900 font-mono text-sm mt-0.5 block">৮১০৭</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-500 block text-[11px] font-medium">এমপিও কোড</span>
                            <span class="font-bold text-slate-900 font-mono text-sm mt-0.5 block">৮১০৭০২১৩০২</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="home-section-divider"></div>

{{-- Vision, Mission & Values --}}
<section class="home-section bg-slate-50/60" id="mission">
    <span id="manifesto" class="sr-only"></span>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header-block">
            <div class="section-header-left">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    INSTITUTIONAL MANIFESTO — প্রাতিষ্ঠানিক অঙ্গীকার
                </span>
                <h2 class="section-title">
                    লক্ষ্য, উদ্দেশ্য ও <span class="text-[#005030] font-bold">মূল আদর্শ</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    শিক্ষার্থীদের শুধু পাঠ্যবইয়ের জ্ঞানে সীমাবদ্ধ না রেখে তাদেরকে সৎ, চরিত্রবান, দেশপ্রেমিক ও আধুনিক তথ্যপ্রযুক্তিমনস্ক নাগরিক হিসেবে গড়ে তোলাই আমাদের অঙ্গীকার।
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Vision & Mission --}}
            <div class="lg:col-span-7 about-card-hero space-y-8">
                {{-- Vision --}}
                <div class="space-y-3.5">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200/80 text-[#005030] flex items-center justify-center text-lg shadow-2xs shrink-0">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-[#005030] block">দীর্ঘমেয়াদী রূপকল্প</span>
                            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900">{{ $about->vision_title ?: 'আমাদের লক্ষ্য (Vision)' }}</h3>
                        </div>
                    </div>
                    <p class="text-sm sm:text-base text-slate-700 leading-relaxed font-normal pt-1">
                        {{ $about->vision_text ?: 'প্রতিটি শিক্ষার্থীকে সুনাগরিক, দেশপ্রেমিক, আধুনিক বিজ্ঞানমনস্ক ও মানবিক মূল্যবোধসম্পন্ন মানুষ হিসেবে গড়ে তোলা।' }}
                    </p>
                </div>

                <div class="border-t border-slate-100"></div>

                {{-- Mission --}}
                <div class="space-y-3.5">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200/80 text-[#005030] flex items-center justify-center text-lg shadow-2xs shrink-0">
                            <i class="fa-solid fa-compass"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-[#005030] block">বাস্তবায়ন কৌশল</span>
                            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900">{{ $about->mission_title ?: 'আমাদের উদ্দেশ্য (Mission)' }}</h3>
                        </div>
                    </div>
                    <p class="text-sm sm:text-base text-slate-700 leading-relaxed font-normal pt-1">
                        {{ $about->mission_text ?: 'আনন্দমুখর ও সুশৃঙ্খল পরিবেশে মানসম্মত শিক্ষা নিশ্চিত করা এবং আধুনিক তথ্যপ্রযুক্তির মাধ্যমে শিক্ষার্থীদের ভবিষ্যতের উপযোগী করে তোলা।' }}
                    </p>
                </div>
            </div>

            {{-- Core Values --}}
            <div class="lg:col-span-5 about-card-stack space-y-6">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200/80 text-[#005030] flex items-center justify-center text-base shadow-2xs shrink-0">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#005030] block">মূল নীতিসমূহ</span>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900">{{ $about->values_title ?: 'আমাদের মূল আদর্শ ও মূল্যবোধ' }}</h3>
                    </div>
                </div>

                <ul class="space-y-3.5 text-sm sm:text-base">
                    @php
                        $valuesList = !empty($about->values_list) ? $about->values_list : [
                            'সততা ও নৈতিক নিষ্ঠা',
                            'শৃঙ্খলা ও কঠোর নিয়মানুবর্তিতা',
                            'দেশপ্রেম ও সামাজিক দায়বদ্ধতা',
                            'আধুনিক বিজ্ঞানমনস্কতা ও মুক্তবুদ্ধি চর্চা',
                        ];
                    @endphp
                    @foreach($valuesList as $vItem)
                        <li class="flex items-center gap-3 p-3.5 rounded-xl bg-slate-50/80 border border-slate-100">
                            <span class="w-7 h-7 rounded-lg bg-[#005030] text-white flex items-center justify-center text-xs shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </span>
                            <span class="font-bold text-slate-800 text-sm sm:text-base">{{ $vItem }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

<div class="home-section-divider"></div>

{{-- Statistics & Milestones --}}
@if(!empty($about->statistics) && is_array($about->statistics))
<section class="home-section bg-white" id="statistics">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header-block">
            <div class="section-header-left">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    KEY METRICS — অর্জন ও পরিসংখ্যান
                </span>
                <h2 class="section-title">
                    এক নজরে বিদ্যালয়ের <span class="text-[#005030] font-bold">সাফল্যের চিত্র</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    বিগত অর্ধশতকের বেশি সময়ে হাজারো শিক্ষার্থীর আলোকবর্তিকা হয়ে ওঠা সোনাতলা উচ্চ বিদ্যালয়ের বাস্তব অর্জন ও সক্ষমতার পরিসংখ্যান।
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($about->statistics as $stat)
                <div class="about-stat-box group">
                    <p class="text-4xl lg:text-5xl font-extrabold font-mono tracking-tight text-[#005030] group-hover:scale-105 transition-transform duration-200 origin-left">
                        {{ $stat['value'] ?? '' }}
                    </p>
                    <p class="text-base font-bold text-slate-900 mt-2.5">{{ $stat['label'] ?? '' }}</p>
                    @if(!empty($stat['note']))
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $stat['note'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>

<div class="home-section-divider"></div>
@endif

{{-- History Timeline --}}
@if(!empty($about->milestones) && is_array($about->milestones))
<section class="home-section bg-slate-50/60" id="milestones">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header-block">
            <div class="section-header-left">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    CHRONOLOGY — অগ্রযাত্রার মাইলফলক
                </span>
                <h2 class="section-title">
                    আমাদের ঐতিহাসিক <span class="text-[#005030] font-bold">অগ্রযাত্রার রূপরেখা</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    ১৯৬৮ সাল থেকে বর্তমান — সোনাতলা উচ্চ বিদ্যালয়ের ধারাবাহিক উন্নয়নের সোনালী স্মৃতি ও প্রাতিষ্ঠানিক রূপান্তরের ইতিহাস।
                </p>
            </div>
        </div>

        <div class="relative border-l-2 border-[#005030]/30 ml-4 sm:ml-6 pl-6 sm:pl-8 space-y-6">
            @foreach($about->milestones as $m)
                <div class="relative group">
                    <div class="absolute -left-[31px] sm:-left-[39px] top-4 w-4 h-4 rounded-full border-2 border-[#005030] bg-white group-hover:bg-[#005030] group-hover:scale-125 transition-all duration-200"></div>

                    <div class="about-card-stack group-hover:-translate-y-0.5 group-hover:border-[#005030]/50 transition-all duration-200">
                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-50 text-[#005030] text-xs font-mono font-bold border border-emerald-200/70">
                            {{ $m['year'] ?? '' }} খ্রিষ্টাব্দ
                        </span>
                        <h4 class="text-base sm:text-lg font-bold text-slate-900 mt-2">
                            {{ $m['title'] ?? '' }}
                        </h4>
                        @if(!empty($m['description']))
                            <p class="text-sm text-slate-600 leading-relaxed mt-1">
                                {{ $m['description'] }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<div class="home-section-divider"></div>
@endif

{{-- Governance & Facts --}}
<section class="home-section bg-white" id="committee">
    <span id="governance" class="sr-only"></span>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header-block">
            <div class="section-header-left">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    GOVERNANCE & SPECIFICATIONS — পরিচালনা ও তথ্য
                </span>
                <h2 class="section-title">
                    প্রতিষ্ঠান পরিচিতি ও <span class="text-[#005030] font-bold">পরিচালনা পর্ষদ</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    বিদ্যালয়ের সার্বিক শিক্ষা ব্যবস্থাপনা, অবকাঠামোগত উন্নয়ন ও প্রশাসনিক স্বচ্ছতা নিশ্চিতকরণে পরিচালনা পর্ষদ নিয়মিত দিকনির্দেশনা প্রদান করে আসছে।
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- At a glance --}}
            <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs">
                <div class="bg-[#005030] text-white px-5 py-4 font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-list text-emerald-300"></i>
                        <span>এক নজরে প্রতিষ্ঠান</span>
                    </span>
                    <span class="text-[10px] text-emerald-200 font-mono">Quick Facts</span>
                </div>

                <dl class="divide-y divide-slate-100 text-xs p-6 space-y-1">
                    @php
                        $glanceList = !empty($about->at_a_glance) ? $about->at_a_glance : [
                            ['key' => 'প্রতিষ্ঠানের নাম', 'value' => 'সোনাতলা উচ্চ বিদ্যালয়'],
                            ['key' => 'EIIN নম্বর', 'value' => '১২৫৬৯৩'],
                            ['key' => 'স্কুল কোড', 'value' => '৮১০৭'],
                            ['key' => 'এমপিও কোড', 'value' => '৮১০৭০২১৩০২'],
                            ['key' => 'শিক্ষা বোর্ড', 'value' => 'রাজশাহী শিক্ষা বোর্ড'],
                        ];
                    @endphp
                    @foreach($glanceList as $glance)
                        <div class="py-3 px-3 flex justify-between items-center gap-4 hover:bg-slate-50/80 rounded-lg transition-colors">
                            <dt class="text-slate-600 shrink-0 font-medium text-sm">{{ $glance['key'] ?? '' }}:</dt>
                            <dd class="font-bold text-slate-900 text-right font-mono text-sm">{{ $glance['value'] ?? '' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- Managing committee --}}
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs">
                <div class="bg-[#005030] text-white px-5 py-4 font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-users text-emerald-300"></i>
                        <span>বিদ্যালয় পরিচালনা পর্ষদ</span>
                    </span>
                    <span class="text-[10px] text-emerald-200 font-mono">Committee</span>
                </div>

                <div class="overflow-x-auto p-6">
                    <table class="w-full text-left text-xs border border-slate-200/90 rounded-xl overflow-hidden">
                        <thead class="bg-slate-50 text-slate-800 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-3.5 border-r border-slate-200 text-center w-14">ক্রমিক</th>
                                <th class="p-3.5 border-r border-slate-200">নাম</th>
                                <th class="p-3.5 border-r border-slate-200">পদবি</th>
                                <th class="p-3.5">দায়িত্ব ও ভূমিকা</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800">
                            @php
                                $committeeList = !empty($about->committee_members) ? $about->committee_members : [];
                            @endphp
                            @if(!empty($committeeList))
                                @foreach($committeeList as $cIdx => $member)
                                    <tr class="hover:bg-slate-50/70 transition-colors {{ $cIdx % 2 == 1 ? 'bg-slate-50/30' : '' }}">
                                        <td class="p-3.5 border-r border-slate-200 text-center font-bold font-mono text-slate-500">
                                             {{ sprintf('%02d', $cIdx + 1) }}
                                        </td>
                                        <td class="p-3.5 border-r border-slate-200 font-bold text-sm text-[#005030]">
                                            {{ $member['name'] ?? '' }}
                                        </td>
                                        <td class="p-3.5 border-r border-slate-200">
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-[#005030] border border-emerald-200/80">
                                                {{ $member['designation'] ?? '' }}
                                            </span>
                                        </td>
                                        <td class="p-3.5 text-xs sm:text-sm text-slate-600">
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
        </div>
    </div>
</section>

<div class="home-section-divider"></div>

{{-- Campus Facilities --}}
@if(!empty($about->facilities) && is_array($about->facilities))
<section class="home-section bg-slate-50/60" id="infrastructure">
    <span id="facilities" class="sr-only"></span>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header-block">
            <div class="section-header-left">
                <span class="section-kicker">
                    <span class="section-kicker-dot"></span>
                    CAMPUS INFRASTRUCTURE — আধুনিক সুযোগ-সুবিধা
                </span>
                <h2 class="section-title">
                    ক্যাম্পাস ও আধুনিক <span class="text-[#005030] font-bold">অবকাঠামো</span>
                </h2>
            </div>
            <div class="section-header-right">
                <p class="section-subtitle">
                    যুগোপযোগী বিজ্ঞান ও তথ্যপ্রযুক্তি শিক্ষাদানের জন্য রয়েছে শেখ রাসেল ডিজিটাল ল্যাব, সমৃদ্ধ বিজ্ঞানাগার, লাইব্রেরি ও সুবিশাল খেলার মাঠ।
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @php
                $flagshipFacility = $about->facilities[0] ?? null;
                $secondaryFacilities = array_slice($about->facilities, 1);
            @endphp

            @if($flagshipFacility)
            <div class="lg:col-span-7 about-card-hero space-y-6 group">
                <div class="flex items-center justify-between">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center text-2xl shadow-2xs group-hover:scale-105 transition-transform duration-300">
                        <i class="fa-solid fa-laptop-code"></i>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-emerald-50 text-[#005030] text-xs font-bold border border-emerald-200">
                        <i class="fa-solid fa-star text-amber-500"></i>
                        <span>প্রধান অবকাঠামো</span>
                    </span>
                </div>

                <div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-3 leading-snug">
                        {{ $flagshipFacility['title'] ?? 'শেখ রাসেল ডিজিটাল কম্পিউটার ল্যাব' }}
                    </h3>
                    <p class="text-sm sm:text-base text-slate-700 leading-relaxed mb-6">
                        {{ $flagshipFacility['description'] ?? 'উচ্চগতির ইন্টারনেট সুবিধাসহ অত্যাধুনিক কম্পিউটার ল্যাব ও সার্বক্ষণিক আইসিটি প্রশিক্ষণ ব্যবস্থা।' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-5 border-t border-slate-100">
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-[#005030] flex items-center justify-center shrink-0 text-xs font-bold"><i class="fa-solid fa-check"></i></span>
                        <span>উচ্চগতির ব্রডব্যান্ড ইন্টারনেট সংযোগ</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-[#005030] flex items-center justify-center shrink-0 text-xs font-bold"><i class="fa-solid fa-check"></i></span>
                        <span>আধুনিক কম্পিউটার ও প্রজেক্টর সুবিধা</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-[#005030] flex items-center justify-center shrink-0 text-xs font-bold"><i class="fa-solid fa-check"></i></span>
                        <span>নিয়মিত আইসিটি ব্যবহারিক ক্লাস</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-[#005030] flex items-center justify-center shrink-0 text-xs font-bold"><i class="fa-solid fa-check"></i></span>
                        <span>অভিজ্ঞ কম্পিউটার শিক্ষক দ্বারা পরিচালিত</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="lg:col-span-5 space-y-4">
                @foreach($secondaryFacilities as $sFac)
                    <div class="about-card-stack space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center text-sm shadow-2xs shrink-0">
                                <i class="fa-solid fa-landmark"></i>
                            </div>
                            <h4 class="text-base font-bold text-slate-900">{{ $sFac['title'] ?? '' }}</h4>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed pl-13">{{ $sFac['description'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<div class="home-section-divider"></div>
@endif

{{-- Admissions CTA --}}
<section class="home-section bg-[#005030] text-white relative overflow-hidden" id="next-step">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center">
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#003820] text-emerald-200 text-xs font-bold uppercase tracking-wider border border-emerald-700/60 mb-5">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
            <span>পরবর্তী পদক্ষেপ • NEXT STEP</span>
        </span>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight max-w-3xl">
            ক্যাম্পাস পরিদর্শন করুন অথবা সরাসরি <span class="text-amber-400 font-bold">যোগাযোগ</span> করুন
        </h2>
        <p class="text-sm sm:text-base text-emerald-100/90 max-w-2xl leading-relaxed mt-4 mb-8">
            বিদ্যালয়ের অ্যাকাডেমিক পরিবেশ ও আধুনিক অবকাঠামোগত সুযোগ-সুবিধা সরেজমিনে প্রত্যক্ষ করতে শিক্ষার্থী ও অভিভাবকদের সাদর আমন্ত্রণ জানাচ্ছি।
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('frontend.contact') }}"
               class="about-cta-btn about-cta-btn-primary text-sm sm:text-base">
                <span>যোগাযোগ ও ক্যাম্পাসে আসুন</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>

            <a href="{{ route('frontend.academic.routine') }}"
               class="about-cta-btn about-cta-btn-secondary text-sm sm:text-base">
                <span>ক্লাস রুটিন ও অ্যাকাডেমিক তথ্য</span>
                <i class="fa-solid fa-chevron-right text-xs text-emerald-300"></i>
            </a>
        </div>
    </div>
</section>

@endsection
