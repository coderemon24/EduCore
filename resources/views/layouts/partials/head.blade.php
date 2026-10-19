<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'সোনাতলা উচ্চ বিদ্যালয় | Sonatala High School')</title>
<meta name="description" content="@yield('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের অফিসিয়াল পোর্টাল — নোটিশ, রুটিন, ফলাফল ও প্রাতিষ্ঠানিক তথ্য।')">

<!-- Institutional Favicon -->
<link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
<link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">

<!-- Google Fonts: Hind Siliguri (Bengali) & Plus Jakarta Sans (English) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Swiper Slider CSS (Local Asset) -->
<link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">

<style>
    :root {
        --font-bilingual: 'Hind Siliguri', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    body {
        font-family: var(--font-bilingual);
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
        background-color: #f8fafc;
        color: #1e293b;
    }

    /* Custom Ticker Marquee Animation */
    @keyframes ticker-slide {
        0% { transform: translate3d(0, 0, 0); }
        100% { transform: translate3d(-50%, 0, 0); }
    }

    .animate-ticker {
        display: inline-flex;
        white-space: nowrap;
        animation: ticker-slide 32s linear infinite;
    }

    .animate-ticker:hover {
        animation-play-state: paused;
    }

    /* Hero Swiper Custom Pagination & Navigation */
    .hero-swiper-pagination .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: rgba(255, 255, 255, 0.7);
        opacity: 0.8;
        transition: all 0.25s ease;
    }
    .hero-swiper-pagination .swiper-pagination-bullet-active {
        width: 22px;
        border-radius: 4px;
        background: #ffffff;
        opacity: 1;
    }

    /* Custom Slim Scrollbars */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<!-- Vite Assets -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

@stack('styles')
