{{-- Top Header Bar --}}
<header class="sticky top-0 z-30 h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8">
    {{-- Left: Mobile Toggle & Context --}}
    <div class="flex items-center gap-3 lg:gap-4">
        <button id="sidebar-toggle-btn" type="button" class="lg:hidden p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none" aria-label="Toggle Navigation">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="hidden sm:flex items-center gap-2 text-xs">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Term 1 Active
            </span>
            <span class="text-slate-300">•</span>
            <span class="text-slate-500 font-medium">Academic Week 7</span>
        </div>
    </div>

    {{-- Center: Global Quick Search --}}
    <div class="flex-1 max-w-md mx-4 hidden md:block">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input
                type="text"
                placeholder="Search students, staff, admission ID, roll no..."
                class="w-full pl-9 pr-12 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
            />
            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                <kbd class="px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-white border border-slate-200 rounded shadow-xs">Ctrl K</kbd>
            </div>
        </div>
    </div>

    {{-- Right: Actions & User Controls --}}
    <div class="flex items-center gap-2 sm:gap-3">
        {{-- Quick Add Shortcut --}}
        <div class="hidden sm:block">
            <a href="#" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium shadow-xs transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>New Admission</span>
            </a>
        </div>

        {{-- Notifications Bell --}}
        <div class="relative">
            <button type="button" class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 relative transition-colors" aria-label="Notifications">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
            </button>
        </div>

        {{-- Divider --}}
        <div class="h-6 w-px bg-slate-200 mx-1"></div>

        {{-- User Menu Pill --}}
        <div class="flex items-center gap-2.5 pl-1">
            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold text-xs border border-indigo-200">
                AV
            </div>
            <div class="hidden lg:block text-left">
                <div class="text-xs font-semibold text-slate-900 leading-tight">Dr. Arthur Vance</div>
                <div class="text-[11px] text-slate-500 leading-tight">Principal</div>
            </div>
        </div>
    </div>
</header>
