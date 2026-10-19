<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    @include('layouts.partials.head')
</head>
<body class="min-h-full flex flex-col bg-slate-50 text-slate-800 antialiased selection:bg-slate-800 selection:text-white" id="frontend-body">

    {{-- Top Institutional Utility Bar (Neutral Dark) --}}
    @include('layouts.partials.topbar')

    {{-- Main Institutional Brand & Clean Navigation --}}
    @include('layouts.partials.navbar')


    {{-- Page Dynamic Content Canvas --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Institutional Footer (Dark Minimalist) --}}
    @include('layouts.partials.footer')

    {{-- Frontend Local Assets Scripts (Swiper & Main JS) --}}
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @stack('scripts')
</body>
</html>
