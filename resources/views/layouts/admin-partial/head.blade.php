<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'Admin Dashboard') | Laravel School Management System</title>

<!-- Dynamic Institutional Favicon -->
@php
    $faviconUrl = isset($school) && $school instanceof \App\Models\School && $school->favicon_url
        ? $school->favicon_url
        : (isset($currentSchool) && $currentSchool?->favicon_url
            ? $currentSchool->favicon_url
            : asset('admin-assets/images/laravel-school-transparent.png'));
@endphp
<link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
<link rel="shortcut icon" href="{{ $faviconUrl }}">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Noto Sans Bengali', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* Global Clickable Cursor Pointer Rule */
    button,
    a,
    select,
    [role="button"],
    [type="button"],
    [type="submit"],
    [type="reset"],
    .cursor-pointer {
        cursor: pointer !important;
    }

    /* Custom Slim Scrollbars */
    .admin-sidebar-scroll::-webkit-scrollbar,
    #admin-main-content::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }
    .admin-sidebar-scroll::-webkit-scrollbar-track,
    #admin-main-content::-webkit-scrollbar-track {
        background: transparent;
    }
    .admin-sidebar-scroll::-webkit-scrollbar-thumb,
    #admin-main-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }
    .admin-sidebar-scroll::-webkit-scrollbar-thumb:hover,
    #admin-main-content::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Collapsed Sidebar Rules & Floating Tooltips with Elevated Stacking Context */
    .sidebar-collapsed #admin-sidebar {
        overflow: visible !important;
        z-index: 50 !important;
    }

    .sidebar-collapsed #admin-sidebar .admin-sidebar-scroll {
        overflow: visible !important;
    }

    .sidebar-collapsed #admin-sidebar li {
        position: relative !important;
    }

    .sidebar-collapsed .nav-label,
    .sidebar-collapsed .nav-chevron,
    .sidebar-collapsed .nav-section-title {
        display: none !important;
    }

    .sidebar-collapsed .sidebar-brand-text {
        display: none !important;
    }

    .sidebar-collapsed #sidebar-academic-submenu,
    .sidebar-collapsed #sidebar-students-submenu {
        display: none;
    }

    .sidebar-collapsed #sidebar-academic-dropdown-wrapper:hover #sidebar-academic-submenu,
    .sidebar-collapsed #sidebar-students-dropdown-wrapper:hover #sidebar-students-submenu {
        display: block !important;
        position: absolute;
        left: 100%;
        top: 0;
        width: 14rem;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        padding: 0.5rem;
        z-index: 9999;
    }

    .sidebar-collapsed #admin-sidebar a,
    .sidebar-collapsed #sidebar-academic-toggle,
    .sidebar-collapsed #sidebar-students-toggle {
        justify-content: center !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    .sidebar-collapsed .nav-item-tooltip {
        display: inline-flex !important;
        align-items: center;
        visibility: hidden;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.15s ease-in-out, visibility 0.15s ease-in-out;
        z-index: 9999 !important;
    }

    .sidebar-collapsed #admin-sidebar a:hover .nav-item-tooltip {
        visibility: visible !important;
        opacity: 1 !important;
    }

    .nav-item-tooltip {
        display: none;
    }
</style>

<!-- Core jQuery & Select2 Vendor Scripts (Synchronous for Admin Stack & Form Plugins) -->
<script src="{{ asset('admin-assets/js/jquery.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('admin-assets/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-assets/css/select2-custom.css') }}">
<script src="{{ asset('admin-assets/js/select2.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/admin-select2.js') }}"></script>

<!-- ToastMagic (Toaster) Styles -->
{!! \Devrabiul\ToastMagic\Facades\ToastMagic::styles() !!}

<!-- Vite Assets -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

@stack('styles')
