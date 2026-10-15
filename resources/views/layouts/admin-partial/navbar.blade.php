<header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-slate-200/80 bg-white/95 px-4 backdrop-blur-sm lg:px-6">
    <!-- Left: Hamburger toggle & Quick Search -->
    <div class="flex items-center gap-3">
        <!-- Desktop Sidebar Toggle -->
        <button id="sidebar-toggle-btn"
                type="button"
                aria-label="Toggle Sidebar"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500/20">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
        </button>

        <!-- Academic Session Badge -->
        <div class="hidden items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700 sm:flex">
            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Academic Year 2026-2027</span>
        </div>

        <!-- Global Search Bar -->
        <div class="relative hidden md:block w-72 lg:w-96">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text"
                   id="global-search-input"
                   placeholder="Search students, staff, roll ID, receipts..."
                   class="w-full rounded-lg border border-slate-200 bg-slate-50/70 py-1.5 pl-9 pr-9 text-xs text-slate-800 placeholder-slate-400 transition-all focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/10">
            <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                <kbd class="rounded border border-slate-200 bg-white px-1.5 py-0.5 text-[10px] font-semibold text-slate-400">⌘K</kbd>
            </div>
        </div>
    </div>

    <!-- Right: Quick Action, Alerts & Admin Profile -->
    <div class="flex items-center gap-2 lg:gap-3">
        <!-- Quick Admission Button -->
        <button type="button"
                id="btn-quick-admission"
                class="hidden sm:inline-flex items-center gap-1.5 rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500/30">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>New Admission</span>
        </button>

        <!-- Quick Collect Fee Button -->
        <button type="button"
                id="btn-quick-fee"
                class="hidden md:inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-xs transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
            <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Collect Fee</span>
        </button>

        <div class="h-6 w-px bg-slate-200"></div>

        <!-- Notification Bell -->
        <div class="relative">
            <button type="button"
                    id="btn-notifications"
                    class="relative inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-1.5 right-1.5 flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-rose-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-rose-500"></span>
                </span>
            </button>
        </div>

        <!-- Institution Calendar Quick Jump -->
        <button type="button"
                onclick="window.Swal && Swal.fire({title: 'Academic Calendar', text: 'Viewing institutional timetable and examination schedules for Fall 2026.', icon: 'info', confirmButtonColor: '#7c3aed'});"
                class="hidden sm:inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none">
            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </button>

        <!-- Admin User Profile Pill -->
        <div class="relative ml-1 flex items-center">
            <button type="button"
                    id="user-profile-menu-btn"
                    class="flex items-center gap-2.5 rounded-lg border border-slate-200/80 p-1 pr-2.5 transition hover:bg-slate-50 focus:outline-none">
                <div class="flex h-8 w-8 items-center justify-center rounded-md bg-gradient-to-tr from-violet-600 to-indigo-600 text-xs font-bold text-white shadow-xs">
                    AD
                </div>
                <div class="hidden text-left sm:block">
                    <div class="text-xs font-semibold text-slate-800">Dr. Arthur Vance</div>
                    <div class="text-[10px] text-slate-500">Super Administrator</div>
                </div>
                <svg class="hidden h-3.5 w-3.5 text-slate-400 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
    </div>
</header>
