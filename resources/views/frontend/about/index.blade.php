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
@endpush

@section('content')

{{-- 1. Institutional Hero & Breadcrumb Banner --}}
<section class="relative bg-gradient-to-b from-slate-100 via-slate-50 to-white border-b border-slate-200/80 pt-6 pb-8 overflow-hidden">
    {{-- Subtle decorative grid pattern --}}
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#005030 1px, transparent 1px); background-size: 16px 16px;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-1.5 text-xs text-slate-500 mb-4" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] font-medium transition-colors">প্রচ্ছদ</a>
            <span>/</span>
            <span class="text-slate-400">পরিচিতি</span>
            <span>/</span>
            <span class="text-slate-900 font-bold">ইতিহাস ও ঐতিহ্য</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded bg-[#005030]/10 text-[#005030] text-xs font-bold uppercase tracking-wider">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#005030]"></span>
                    <span>গৌরবোজ্জ্বল ঐতিহ্য</span>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-slate-900 font-serif">
                    {{ $about->title }}
                </h1>
                @if(!empty($about->subtitle))
                    <p class="text-xs sm:text-sm text-slate-600 max-w-3xl leading-relaxed">
                        {{ $about->subtitle }}
                    </p>
                @endif
            </div>

            @if(!empty($about->established_year))
                <div class="shrink-0 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 bg-white shadow-2xs text-xs">
                    <span class="text-slate-500">প্রতিষ্ঠিত:</span>
                    <span class="font-bold text-[#005030] font-mono">{{ $about->established_year }} খ্রি.</span>
                </div>
            @endif
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    {{-- 2. Brief History & Featured Photo Card --}}
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        {{-- Narrative Column --}}
        <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 sm:p-8 space-y-5 shadow-2xs">
            <div class="border-b border-slate-100 pb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-[#005030]">সূচনালগ্ন ও বিকাশ</span>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mt-1">
                    {{ $about->history_heading ?: 'বিদ্যালয়ের গৌরবোজ্জ্বল ইতিহাস ও পটভূমি' }}
                </h2>
            </div>

            <div class="prose prose-sm max-w-none text-slate-700 leading-relaxed space-y-4 text-xs sm:text-sm">
                @foreach(explode("\n\n", $about->history_content) as $paragraph)
                    @if(trim($paragraph))
                        <p>{{ trim($paragraph) }}</p>
                    @endif
                @endforeach
            </div>

            {{-- Institutional Credential Badges --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-3 border-t border-slate-100 text-xs text-slate-700">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-[#005030] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>সরকারি স্বীকৃতিপ্রাপ্ত</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-[#005030] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>শতভাগ এমপিওভুক্ত</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-[#005030] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>আধুনিক ডিজিটাল ল্যাব</span>
                </div>
            </div>
        </div>

        {{-- Featured Campus Photo Column --}}
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-2xs">
                <div class="relative aspect-4/3 bg-slate-100 overflow-hidden group">
                    <img src="{{ $about->history_image_url }}"
                         alt="{{ $about->title }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-3 left-3 right-3 text-white text-xs">
                        <p class="font-bold truncate">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }}</p>
                        <p class="text-[10px] text-slate-300">প্রধান একাডেমিক ভবন ও মনোরম প্রাঙ্গণ</p>
                    </div>
                </div>
            </div>

            {{-- Quick Overview Badge Box --}}
            <div class="bg-emerald-50/50 rounded-xl border border-emerald-100 p-4 flex items-center justify-between text-xs text-emerald-950">
                <div class="flex items-center gap-2.5">
                    <div class="h-8 w-8 rounded-full bg-[#005030] text-white flex items-center justify-center font-bold text-xs">
                        EIIN
                    </div>
                    <div>
                        <p class="font-bold">সোনাতলা উচ্চ বিদ্যালয়</p>
                        <p class="text-[11px] text-emerald-800">সাঁথিয়া, পাবনা • স্থাপিত ১৯৬৮</p>
                    </div>
                </div>
                <span class="font-mono font-bold text-emerald-900 bg-emerald-100 px-2 py-1 rounded">১২৫৬৯৩</span>
            </div>
        </div>
    </section>

    {{-- 3. Core Pillars: Vision, Mission & Values --}}
    <section class="space-y-4">
        <div class="text-center max-w-xl mx-auto space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-[#005030]">প্রাতিষ্ঠানিক লক্ষ্য</span>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900">লক্ষ্য, উদ্দেশ্য ও মূল আদর্শ</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
            {{-- Vision --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-3 shadow-2xs hover:border-[#005030]/50 transition-colors">
                <div class="h-10 w-10 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-3 shadow-2xs hover:border-[#005030]/50 transition-colors">
                <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">{{ $about->mission_title ?: 'আমাদের উদ্দেশ্য (Mission)' }}</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    {{ $about->mission_text ?: 'আনন্দমুখর ও সুশৃঙ্খল পরিবেশে মানসম্মত শিক্ষা নিশ্চিত করা এবং আধুনিক তথ্যপ্রযুক্তির মাধ্যমে শিক্ষার্থীদের ভবিষ্যতের উপযোগী করে তোলা।' }}
                </p>
            </div>

            {{-- Values --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-3 shadow-2xs hover:border-[#005030]/50 transition-colors">
                <div class="h-10 w-10 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">{{ $about->values_title ?: 'আমাদের মূল আদর্শ' }}</h3>
                <ul class="space-y-1.5 text-xs text-slate-600">
                    @php
                        $valuesList = !empty($about->values_list) ? $about->values_list : [
                            'সততা ও নিষ্ঠা',
                            'শৃঙ্খলা ও নিয়মানুবর্তিতা',
                            'দেশপ্রেম ও মানবিকতা',
                        ];
                    @endphp
                    @foreach($valuesList as $vItem)
                        <li class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#005030]"></span>
                            <span>{{ $vItem }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- 4. Key Statistics & Achievements Metrics --}}
    @if(!empty($about->statistics) && is_array($about->statistics))
    <section class="bg-gradient-to-r from-[#005030] to-[#0d5c3a] rounded-2xl p-6 sm:p-8 text-white shadow-sm">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @foreach($about->statistics as $stat)
                <div class="space-y-1">
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-extrabold font-mono tracking-tight text-emerald-200">
                        {{ $stat['value'] ?? '' }}
                    </p>
                    <p class="text-xs sm:text-sm font-bold text-white">{{ $stat['label'] ?? '' }}</p>
                    @if(!empty($stat['note']))
                        <p class="text-[10px] text-emerald-100/70">{{ $stat['note'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- 5. Chronological Milestones Timeline --}}
    @if(!empty($about->milestones) && is_array($about->milestones))
    <section class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 space-y-6 shadow-2xs">
        <div class="border-b border-slate-100 pb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-[#005030]">অগ্রযাত্রার রূপরেখা</span>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mt-1">আমাদের ঐতিহাসিক মাইলফলক</h2>
        </div>

        <div class="relative border-l-2 border-[#005030]/20 ml-3 sm:ml-4 pl-6 sm:pl-8 space-y-8">
            @foreach($about->milestones as $m)
                <div class="relative group">
                    {{-- Timeline Dot --}}
                    <div class="absolute -left-[31px] sm:-left-[39px] top-1 h-3.5 w-3.5 rounded-full border-2 border-[#005030] bg-white group-hover:bg-[#005030] transition-colors"></div>

                    <div class="space-y-1">
                        <span class="inline-block px-2.5 py-0.5 rounded bg-emerald-50 text-[#005030] text-xs font-mono font-bold">
                            {{ $m['year'] ?? '' }} খ্রি.
                        </span>
                        <h4 class="text-sm sm:text-base font-bold text-slate-900">
                            {{ $m['title'] ?? '' }}
                        </h4>
                        @if(!empty($m['description']))
                            <p class="text-xs text-slate-600 leading-relaxed">
                                {{ $m['description'] }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- 6. Two-Column Layout: School at a Glance & Managing Committee --}}
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Left Column: School at a Glance Specifications Table --}}
        <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200 overflow-hidden shadow-2xs">
            <div class="bg-[#005030] text-white px-5 py-3 font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                <span>এক নজরে প্রতিষ্ঠান</span>
                <span class="text-[10px] text-emerald-200">At a Glance</span>
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
                    <div class="py-2.5 flex justify-between items-center gap-4">
                        <dt class="text-slate-500 shrink-0">{{ $glance['key'] ?? '' }}:</dt>
                        <dd class="font-bold text-slate-900 text-right font-mono">{{ $glance['value'] ?? '' }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        {{-- Right Column: Managing Committee Directory --}}
        <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 overflow-hidden shadow-2xs">
            <div class="bg-[#005030] text-white px-5 py-3 font-bold text-xs uppercase tracking-wider flex items-center justify-between">
                <span>বিদ্যালয় পরিচালনা পর্ষদ</span>
                <span class="text-[10px] text-emerald-200">Managing Committee</span>
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
                                <tr class="{{ $cIdx % 2 == 1 ? 'bg-slate-50/50' : '' }}">
                                    <td class="p-2.5 border-r border-slate-200 text-center font-bold font-mono text-slate-500">
                                        {{ sprintf('%02d', $cIdx + 1) }}
                                    </td>
                                    <td class="p-2.5 border-r border-slate-200 font-bold text-[#005030]">
                                        {{ $member['name'] ?? '' }}
                                    </td>
                                    <td class="p-2.5 border-r border-slate-200 font-semibold text-slate-800">
                                        {{ $member['designation'] ?? '' }}
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

    {{-- 7. Modern Campus Facilities & Environment --}}
    @if(!empty($about->facilities) && is_array($about->facilities))
    <section class="space-y-4">
        <div class="text-center max-w-xl mx-auto space-y-1">
            <span class="text-xs font-bold uppercase tracking-wider text-[#005030]">অবকাঠামো</span>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900">ক্যাম্পাস ও আধুনিক সুযোগ-সুবিধা</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
            @foreach($about->facilities as $fac)
                <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-2 shadow-2xs hover:border-[#005030]/40 transition-colors">
                    <div class="h-8 w-8 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

@endsection
