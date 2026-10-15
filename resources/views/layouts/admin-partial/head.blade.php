<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'Admin Dashboard') | Laravel School Management System</title>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* Tooltip animations for collapsed sidebar */
    .sidebar-collapsed .nav-label,
    .sidebar-collapsed .nav-badge,
    .sidebar-collapsed .nav-chevron,
    .sidebar-collapsed .nav-section-title {
        display: none !important;
    }

    .sidebar-collapsed .sidebar-brand-text {
        display: none !important;
    }

    .sidebar-collapsed .nav-item-tooltip {
        display: block !important;
    }

    .nav-item-tooltip {
        display: none;
    }
</style>

<!-- Vite Assets -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

@stack('styles')
