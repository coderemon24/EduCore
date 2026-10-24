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

{{-- Breadcrumb (Standard Clean Layout Matching Header & Footer Width) --}}
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
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }} • সাঁথিয়া, পাবনা</span>
    </div>
</div>

{{-- Top Hero Banner: Matches Reference Clean Green Header with GSAP Entrance --}}
<div class="page-hero-banner bg-[#005030] text-white py-8 sm:py-10 border-b border-emerald-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Pill Badge --}}
        <div class="page-hero-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-[#005030] text-xs font-bold shadow-xs">
            <i class="fa-solid fa-landmark text-[#005030]"></i>
            <span>পরিচিতি</span>
        </div>

        {{-- Page Heading --}}
        <h1 class="page-hero-title text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white mt-3 mb-2 tracking-tight">
            {{ $about->title ?: 'সোনাতলা উচ্চ বিদ্যালয় — ইতিহাস ও ঐতিহ্য' }}
        </h1>

        {{-- Sub-narrative context --}}
        <p class="page-hero-subtitle text-xs sm:text-sm text-emerald-100/90 font-medium">
            {{ $about->subtitle ?: 'পাবনার সাঁথিয়া উপজেলার ঐতিহ্যবাহী বিদ্যাপীঠ — অর্ধশতাব্দীর গৌরবোজ্জ্বল শিক্ষা ও সংস্কৃতির অগ্রযাত্রা' }}
        </p>
    </div>
</div>

{{-- Main Content Canvas --}}
<section class="py-8 sm:py-12 bg-slate-50/60 min-h-[500px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- History Intro Card --}}
        <div class="gsap-reveal bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs hover-card-lift" id="history">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                {{-- Left: Narrative --}}
                <div class="{{ !empty($about->history_image_url) ? 'lg:col-span-7' : 'lg:col-span-12' }} space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-[#005030] text-xs font-bold border border-emerald-200">
                        <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                        <span>আমাদের যাত্রা</span>
                    </div>

                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">
                        {{ $about->history_heading ?: 'বিদ্যালয়ের গৌরবোজ্জ্বল ইতিহাস ও পটভূমি' }}
                    </h2>

                    <div class="text-slate-700 leading-relaxed space-y-3.5 text-sm sm:text-base">
                        @foreach(explode("\n\n", $about->history_content) as $pIdx => $paragraph)
                            @if(trim($paragraph))
                                <p class="{{ $pIdx === 0 ? 'text-slate-900 font-medium leading-relaxed border-l-2 border-[#005030] pl-4 bg-[#eef7f2] py-2.5 rounded-r' : 'text-slate-600 leading-relaxed' }}">
                                    {{ trim($paragraph) }}
                                </p>
                            @endif
                        @endforeach
                    </div>

                    {{-- Institutional Credential Pills --}}
                    <div class="pt-4 flex flex-wrap items-center gap-2.5 text-xs font-semibold text-slate-700">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200">
                            <i class="fa-solid fa-award text-[#005030]"></i>
                            <span>সরকারি স্বীকৃতিপ্রাপ্ত</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200">
                            <i class="fa-solid fa-circle-check text-[#005030]"></i>
                            <span>শতভাগ এমপিওভুক্ত</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200">
                            <i class="fa-solid fa-calendar-check text-[#005030]"></i>
                            <span>স্থাপিত: {{ $about->established_year ?? '১৯৬৮' }} খ্রিষ্টাব্দ</span>
                        </span>
                    </div>
                </div>

                {{-- Right: Campus Image (if available) --}}
                @if(!empty($about->history_image_url))
                    <div class="lg:col-span-5">
                        <div class="relative rounded-2xl overflow-hidden border border-slate-200/90 shadow-2xs group">
                            <img src="{{ $about->history_image_url }}"
                                 alt="{{ $about->title }}"
                                 class="w-full h-72 sm:h-80 object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <p class="text-sm font-bold text-white">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}</p>
                                <p class="text-xs text-emerald-200 mt-0.5">প্রধান একাডেমিক ভবন ও শান্ত সবুজ ক্যাম্পাস</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Values Band (3 Clean Cards - Matches Sonatala Reference hL) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="mission">
            {{-- Vision Card --}}
            <div class="gsap-reveal bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs flex flex-col justify-between space-y-4 hover-card-lift">
                <div class="space-y-3">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center text-lg shadow-2xs">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">
                        {{ $about->vision_title ?: 'আমাদের লক্ষ্য' }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $about->vision_text ?: 'প্রতিটি শিক্ষার্থীর মেধা ও সৃজনশীলতা বিকাশে সহায়ক একটি মানসম্পন্ন শিক্ষা পরিবেশ গড়ে তোলা এবং আদর্শ সুনাগরিক তৈরি করা।' }}
                    </p>
                </div>
                <div class="pt-2 text-xs font-semibold text-[#005030] flex items-center gap-1">
                    <span>দীর্ঘমেয়াদী রূপকল্প</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </div>
            </div>

            {{-- Mission Card --}}
            <div class="gsap-reveal bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs flex flex-col justify-between space-y-4 hover-card-lift">
                <div class="space-y-3">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center text-lg shadow-2xs">
                        <i class="fa-solid fa-compass"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">
                        {{ $about->mission_title ?: 'আমাদের চিন্তাধারা' }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $about->mission_text ?: 'জ্ঞান, নৈতিকতা ও দক্ষতার সমন্বয়ে আগামী দিনের যোগ্য ও দায়িত্বশীল নাগরিক তৈরি করতে আধুনিক পাঠদান পদ্ধতি নিশ্চিত করা।' }}
                    </p>
                </div>
                <div class="pt-2 text-xs font-semibold text-[#005030] flex items-center gap-1">
                    <span>বাস্তবায়ন কৌশল</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </div>
            </div>

            {{-- Values Card --}}
            <div class="gsap-reveal bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs flex flex-col justify-between space-y-4 hover-card-lift">
                <div class="space-y-3">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center text-lg shadow-2xs">
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">
                        {{ $about->values_title ?: 'মূল্যবোধ' }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        সততা, শ্রদ্ধাবোধ, শৃঙ্খলা ও দেশপ্রেম — এই মূল্যবোধগুলোই আমাদের পাঠদান ও সার্বিক শিক্ষাদানের অবিচ্ছেদ্য মূল ভিত্তি।
                    </p>
                </div>
                <div class="pt-2 text-xs font-semibold text-[#005030] flex items-center gap-1">
                    <span>নীতি ও আদর্শ</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </div>
            </div>
        </div>

        {{-- Statistics Strip (If Available) --}}
        @if(!empty($about->statistics) && is_array($about->statistics))
            <div class="gsap-reveal grid grid-cols-2 md:grid-cols-4 gap-4" id="statistics">
                @foreach($about->statistics as $stat)
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs text-center space-y-1 hover-card-lift">
                        <p class="text-2xl sm:text-3xl font-extrabold text-[#005030] font-mono">
                            {{ $stat['value'] ?? '' }}
                        </p>
                        <p class="text-xs sm:text-sm font-bold text-slate-900">{{ $stat['label'] ?? '' }}</p>
                        @if(!empty($stat['note']))
                            <p class="text-[11px] text-slate-400">{{ $stat['note'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- 2-Column Grid: Quick Facts & Managing Committee --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start" id="committee">
            {{-- Left: At A Glance --}}
            <div class="gsap-reveal lg:col-span-4 bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-2xs space-y-4 hover-card-lift">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-sm">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">এক নজরে তথ্য</h3>
                </div>

                <dl class="divide-y divide-slate-100 text-xs">
                    @php
                        $glanceList = !empty($about->at_a_glance) ? $about->at_a_glance : [
                            ['key' => 'প্রতিষ্ঠানের নাম', 'value' => $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'],
                            ['key' => 'EIIN নম্বর', 'value' => '১২৫৬৯৩'],
                            ['key' => 'স্কুল কোড', 'value' => '৮১০৭'],
                            ['key' => 'এমপিও কোড', 'value' => '৮১০৭০২১৩০২'],
                            ['key' => 'শিক্ষা বোর্ড', 'value' => 'রাজশাহী শিক্ষা বোর্ড'],
                            ['key' => 'প্রতিষ্ঠাকাল', 'value' => ($about->established_year ?? '১৯৬৮') . ' খ্রিষ্টাব্দ'],
                        ];
                    @endphp
                    @foreach($glanceList as $glance)
                        <div class="py-2.5 flex justify-between items-center gap-2">
                            <dt class="text-slate-500 font-medium">{{ $glance['key'] ?? '' }}:</dt>
                            <dd class="font-bold text-slate-800 text-right">{{ $glance['value'] ?? '' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- Right: Managing Committee --}}
            <div class="gsap-reveal lg:col-span-8 bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-2xs space-y-4 hover-card-lift">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-sm">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">বিদ্যালয় পরিচালনা কমিটি</h3>
                    </div>
                    <span class="text-xs text-slate-400 font-medium hidden sm:inline-block">কার্যনির্বাহী পর্ষদ</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3 text-center w-12">নং</th>
                                <th class="py-2.5 px-3">সদস্যের নাম</th>
                                <th class="py-2.5 px-3">কমিটি পদবি</th>
                                <th class="py-2.5 px-3">দায়িত্ব</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @php
                                $committeeList = !empty($about->committee_members) ? $about->committee_members : [];
                            @endphp
                            @forelse($committeeList as $cIdx => $member)
                                <tr class="hover:bg-emerald-50/40 transition-colors">
                                    <td class="py-2.5 px-3 text-center font-mono text-slate-400">
                                        {{ sprintf('%02d', $cIdx + 1) }}
                                    </td>
                                    <td class="py-2.5 px-3 font-bold text-slate-900">
                                        {{ $member['name'] ?? '' }}
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <span class="inline-block px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-[#005030] border border-emerald-200/60">
                                            {{ $member['designation'] ?? '' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-slate-500">
                                        {{ $member['role'] ?? '' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400 italic">
                                        পরিচালনা কমিটির তালিকা শীঘ্রই হালনাগাদ করা হবে।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Facilities Grid (If Available) --}}
        @if(!empty($about->facilities) && is_array($about->facilities))
            <div class="gsap-reveal bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-2xs space-y-6 hover-card-lift" id="facilities">
                <div class="border-b border-slate-100 pb-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-[#005030] text-xs font-bold border border-emerald-200 mb-2">
                        <i class="fa-solid fa-school text-xs"></i>
                        <span>অবকাঠামো</span>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900">ক্যাম্পাস ও আধুনিক সুযোগ-সুবিধা</h3>
                    <p class="text-xs text-slate-500 mt-1">শিক্ষার্থীদের কার্যকর পাঠদান ও সহশিক্ষা কার্যক্রমের উপযোগী পরিবেশ</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach($about->facilities as $facility)
                        <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-100 space-y-2 hover:border-[#005030]/40 hover:bg-white hover:shadow-2xs transition-all duration-200">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-lg bg-[#005030] text-white flex items-center justify-center text-xs shrink-0 shadow-2xs">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                                <h4 class="font-bold text-sm text-slate-900">{{ $facility['title'] ?? '' }}</h4>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed pl-10.5">
                                {{ $facility['description'] ?? '' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Next Steps Action Banner --}}
        <div class="gsap-reveal bg-[#005030] text-white rounded-2xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row items-center justify-between gap-6 hover-card-lift">
            <div class="space-y-1 text-center md:text-left">
                <h3 class="text-lg sm:text-xl font-extrabold text-white">বিদ্যালয় ক্যাম্পাসে আপনাকে স্বাগতম</h3>
                <p class="text-xs sm:text-sm text-emerald-100/90">
                    আমাদের শিক্ষা পরিবেশ ও সুযোগ-সুবিধা সম্পর্কে বিস্তারিত জানতে সরাসরি যোগাযোগ করুন।
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('frontend.contact') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs shadow-xs transition-colors hover-pill-scale">
                    <i class="fa-solid fa-envelope"></i>
                    <span>যোগাযোগ করুন</span>
                </a>
                <a href="{{ route('frontend.academic.classes') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition-colors hover-pill-scale">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>শ্রেণিসমূহ দেখুন</span>
                </a>
            </div>
        </div>

    </div>
</section>

@endsection
