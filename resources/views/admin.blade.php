<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    @include('layouts.admin-partial.head')
</head>
<body class="h-screen overflow-hidden antialiased text-slate-800 bg-slate-50/60 selection:bg-violet-500 selection:text-white" id="admin-body">

    <!-- Root Layout Container (Fixed Sidebar Viewport) -->
    <div id="admin-layout-wrapper" class="flex h-screen overflow-hidden bg-slate-50">

        <!-- Mobile Backdrop Overlay -->
        <div id="sidebar-backdrop"
             class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs transition-opacity duration-300 hidden lg:hidden"></div>

        <!-- Collapsible Admin Sidebar (Fixed Desktop Viewport) -->
        @include('layouts.admin-partial.sidebar')

        <!-- Main Content Column (Independent Scroll Canvas) -->
        <div id="admin-main-content" class="flex min-w-0 flex-1 flex-col h-screen overflow-y-auto overflow-x-hidden transition-all duration-300 ease-in-out">
            
            <!-- Admin Top Navigation Header -->
            @include('layouts.admin-partial.navbar')

            <!-- Page Dynamic Yield Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('admin-content')
            </main>

            <!-- Admin Footer -->
            @include('layouts.admin-partial.footer')
        </div>
    </div>

    <!-- Feature-Wise Modular Admin JavaScript Assets -->
    <script src="{{ asset('admin-assets/js/admin-sidebar.js') }}" defer></script>
    <script src="{{ asset('admin-assets/js/admin-search.js') }}" defer></script>
    <script src="{{ asset('admin-assets/js/admin-actions.js') }}" defer></script>

    @stack('scripts')
</body>
</html>
