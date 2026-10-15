{{-- Sidebar Navigation --}}
<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-300 flex flex-col border-r border-slate-800 transition-transform duration-200 ease-in-out -translate-x-full lg:translate-x-0">
    {{-- Brand Header --}}
    <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800 bg-slate-950/60">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-sm group-hover:bg-indigo-500 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-semibold tracking-tight text-white group-hover:text-indigo-300 transition-colors">EduCore SIS</span>
                <span class="text-[11px] font-medium text-slate-400">School Administration</span>
            </div>
        </a>
        <button id="sidebar-close-btn" type="button" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-md hover:bg-slate-800" aria-label="Close sidebar">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Academic Session Pill --}}
    <div class="px-5 py-3 border-b border-slate-800/80 bg-slate-900/50">
        <div class="flex items-center justify-between text-xs">
            <span class="text-slate-400">Academic Year</span>
            <span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 font-medium text-[11px] border border-emerald-500/20">2026 – 2027</span>
        </div>
    </div>

    {{-- Navigation Links --}}
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6 text-xs">
        {{-- Section: Core --}}
        <div>
            <div class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Overview</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3 py-2 rounded-md font-medium text-white bg-indigo-600/20 text-indigo-300 border border-indigo-500/30">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </div>
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Calendar & Events</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        {{-- Section: Academic Directory --}}
        <div>
            <div class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Students & Staff</div>
            <ul class="space-y-1">
                <li>
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Students Directory</span>
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-800 text-slate-400 border border-slate-700">1,485</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span>New Admissions</span>
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 font-medium">New</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Teachers & Faculty</span>
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-800 text-slate-400 border border-slate-700">92</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Classes & Sections</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        {{-- Section: Academic Operations --}}
        <div>
            <div class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Operations</div>
            <ul class="space-y-1">
                <li>
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Daily Attendance</span>
                        </div>
                        <span class="text-[10px] text-emerald-400 font-medium">96.2%</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Timetables & Routine</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span>Examinations & Marks</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        {{-- Section: Finance & Administration --}}
        <div>
            <div class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Finance & Admin</div>
            <ul class="space-y-1">
                <li>
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Fees & Invoices</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span>Notice Board</span>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Reports & Analytics</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center justify-between px-3 py-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Settings</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Bottom User Profile Card --}}
    <div class="p-3 border-t border-slate-800 bg-slate-950/70">
        <div class="flex items-center gap-3 p-2 rounded-lg bg-slate-900 border border-slate-800">
            <div class="relative">
                <div class="w-9 h-9 rounded-full bg-slate-700 text-slate-200 flex items-center justify-center font-semibold text-xs border border-slate-600">
                    AV
                </div>
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-slate-900"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-medium text-white truncate">Dr. Arthur Vance</p>
                <p class="text-[11px] text-slate-400 truncate">Super Administrator</p>
            </div>
            <button type="button" class="text-slate-400 hover:text-white p-1 rounded transition-colors" title="Logout">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </div>
    </div>
</aside>
