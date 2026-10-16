<!-- Minimalist, High-Clarity Admin Navbar -->
<header class="sticky top-0 z-30 flex h-14 w-full shrink-0 items-center justify-between border-b border-slate-200/80 bg-white/95 px-4 backdrop-blur-sm lg:px-6">
    <!-- Left: Sidebar Toggle & Sleek Search -->
    <div class="flex items-center gap-3">
        <!-- Sidebar Toggle -->
        <button id="sidebar-toggle-btn"
                type="button"
                aria-label="Toggle Sidebar"
                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
        </button>

        <!-- Optimized Search Container (Hidden on mobile to ensure clean responsive layout) -->
        <div class="hidden md:block relative md:w-64 lg:w-80 group/search">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within/search:text-violet-600 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text"
                   id="global-search-input"
                   placeholder="Search students, faculty, classes..."
                   autocomplete="off"
                   class="w-full rounded-lg border border-slate-200 bg-slate-50/80 py-1.5 pl-9 pr-9 text-xs font-normal text-slate-800 placeholder-slate-400 transition hover:bg-slate-50 hover:border-slate-300 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-2xs">
            <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                <kbd class="rounded border border-slate-200 bg-white px-1.5 py-0.5 text-[10px] font-medium text-slate-400 shadow-2xs">⌘K</kbd>
            </div>

            <!-- Sleek Quick Search Suggestions Popup -->
            <div id="search-quick-results"
                 class="hidden absolute left-0 top-full mt-1.5 w-full rounded-xl border border-slate-200 bg-white p-2 shadow-xl z-50 text-xs">
                <div class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Quick Navigation
                </div>
                <div class="space-y-0.5 mt-1">
                    <a href="javascript:void(0)" onclick="window.Swal && Swal.fire({title: 'Students Directory', text: 'Opening Students Directory...', icon: 'info', confirmButtonColor: '#7c3aed'});"
                       class="flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-slate-700 hover:bg-violet-50 hover:text-violet-700 transition cursor-pointer">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14c4.418 0 8 1.79 8 4v2H4v-2c0-2.21 3.582-4 8-4zm0-2a5 5 0 100-10 5 5 0 000 10z" />
                        </svg>
                        <span>Students SIS & Admissions</span>
                    </a>
                    <a href="javascript:void(0)" onclick="window.Swal && Swal.fire({title: 'Faculty & Teachers', text: 'Opening Faculty Directory...', icon: 'info', confirmButtonColor: '#7c3aed'});"
                       class="flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-slate-700 hover:bg-violet-50 hover:text-violet-700 transition cursor-pointer">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Teachers & Staff Records</span>
                    </a>
                    <a href="javascript:void(0)" onclick="window.Swal && Swal.fire({title: 'Fee Invoices', text: 'Opening Fee Collection...', icon: 'info', confirmButtonColor: '#7c3aed'});"
                       class="flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-slate-700 hover:bg-violet-50 hover:text-violet-700 transition cursor-pointer">
                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Fee Invoices & Payments</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Academic Calendar, Quick Action, Alerts & User Profile Dropdown -->
    <div class="flex items-center gap-2 sm:gap-3">
        <!-- Active Institution / Tenant Indicator -->
        <div class="relative hidden sm:block">
            @if(isset($isGlobalSuperAdmin) && $isGlobalSuperAdmin)
                <a href="{{ route('admin.schools.index') }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-violet-200 bg-violet-50/70 px-2.5 py-1.5 text-xs font-medium text-violet-800 shadow-2xs hover:bg-violet-100/70 transition">
                    <svg class="h-3.5 w-3.5 text-violet-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-bold text-violet-900">Platform Management</span>
                    <span class="rounded bg-violet-200/80 px-1.5 py-0.5 text-[9px] font-bold text-violet-800 uppercase tracking-wider">Super Admin</span>
                </a>
            @elseif(isset($currentSchool) && $currentSchool)
                <div class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50/80 px-2.5 py-1.5 text-xs font-medium text-slate-700 shadow-2xs">
                    <img src="{{ $currentSchool->logo_url }}" alt="{{ $currentSchool->name }}" class="h-4 w-4 object-contain shrink-0">
                    <span class="font-semibold text-slate-800">{{ $currentSchool->name }}</span>
                    <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[9px] font-bold text-emerald-700 uppercase tracking-wider">{{ $currentSchool?->status?->value ?? 'active' }}</span>
                </div>
            @endif
        </div>

        <!-- Academic Calendar Switcher -->
        <div class="relative">
            <button type="button"
                    id="academic-calendar-btn"
                    title="Change Academic Calendar / Session"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50/80 px-2.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                <svg class="h-4 w-4 text-violet-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span id="current-calendar-label" class="hidden md:inline">{{ isset($activeAcademicYear) && $activeAcademicYear ? $activeAcademicYear->name.' Session' : 'Active Session' }}</span>
                <span id="current-term-badge" class="rounded bg-violet-100 px-1.5 py-0.5 text-[10px] font-bold text-violet-700">Active</span>
                <svg id="calendar-dropdown-chevron" class="h-3.5 w-3.5 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Calendar Dropdown -->
            <div id="academic-calendar-dropdown"
                 class="hidden absolute right-0 mt-1.5 w-64 rounded-xl border border-slate-200 bg-white p-2 shadow-xl z-50 text-xs">
                <div class="px-2 py-1.5 border-b border-slate-100 mb-1 flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Academic Calendar</span>
                    <a href="{{ Route::has('admin.academic.years.index') ? route('admin.academic.years.index') : url('/admin/academic/years') }}" class="text-[10px] text-violet-600 hover:text-violet-800 font-semibold cursor-pointer">Manage Sessions</a>
                </div>
                <div class="space-y-1">
                    @if(isset($allAcademicYears) && $allAcademicYears->isNotEmpty())
                        @foreach($allAcademicYears as $yearItem)
                            <a href="{{ Route::has('admin.academic.years.index') ? route('admin.academic.years.index') : url('/admin/academic/years') }}"
                               class="calendar-session-item flex w-full items-center justify-between rounded-lg px-2.5 py-2 text-left transition hover:bg-slate-50 cursor-pointer {{ $yearItem->is_current ? 'bg-violet-50 text-violet-800 font-semibold' : 'text-slate-700 font-medium' }}">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full {{ $yearItem->is_current ? 'bg-violet-600' : 'bg-slate-300' }}"></span>
                                    <span>{{ $yearItem->name }}</span>
                                </div>
                                <span class="rounded px-1.5 py-0.5 text-[10px] font-bold {{ $yearItem->is_current ? 'bg-violet-200/60 text-violet-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $yearItem->is_current ? 'Current' : ucfirst($yearItem->status) }}
                                </span>
                            </a>
                        @endforeach
                    @else
                        <div class="p-3 text-center text-slate-400">
                            <p class="text-xs">No sessions configured</p>
                            <a href="{{ Route::has('admin.academic.years.index') ? route('admin.academic.years.index') : url('/admin/academic/years') }}" class="text-[11px] text-violet-600 font-semibold mt-1 inline-block">Set up Academic Year &rarr;</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Action Menu -->
        <div class="relative">
            <button type="button"
                    id="quick-action-menu-btn"
                    title="Quick Action"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-700 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>

            <!-- Quick Action Dropdown -->
            <div id="quick-action-menu-dropdown"
                 class="hidden absolute right-0 mt-1.5 w-44 rounded-lg border border-slate-200 bg-white p-1 shadow-lg z-50 text-xs">
                <button type="button"
                        data-action="new-admission"
                        class="flex w-full items-center gap-2 rounded-md px-2.5 py-1.5 text-left text-slate-700 hover:bg-violet-50 hover:text-violet-700 transition cursor-pointer">
                    <svg class="h-3.5 w-3.5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span>New Admission</span>
                </button>
                <button type="button"
                        data-action="collect-fee"
                        class="flex w-full items-center gap-2 rounded-md px-2.5 py-1.5 text-left text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition cursor-pointer">
                    <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Collect Fee</span>
                </button>
            </div>
        </div>

        <!-- Notification Bell & Dropdown (Dropdown instead of popup) -->
        <div class="relative">
            <button type="button"
                    id="btn-notifications"
                    title="Notifications"
                    aria-expanded="false"
                    class="relative inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-1.5 right-1.5 h-1.5 w-1.5 rounded-full bg-rose-500"></span>
            </button>

            <!-- Notifications Dropdown Menu -->
            <div id="notifications-dropdown"
                 class="hidden absolute right-0 mt-1.5 w-80 sm:w-96 rounded-xl border border-slate-200 bg-white shadow-xl z-50 text-xs overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3 bg-slate-50/70">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-800 text-xs">Notifications</span>
                        <span id="notif-unread-count" class="rounded-full bg-violet-100 px-2 py-0.5 text-[10px] font-bold text-violet-700">3 New</span>
                    </div>
                    <button type="button" id="btn-mark-all-read" class="text-[11px] font-medium text-violet-600 hover:text-violet-800 transition cursor-pointer">
                        Mark all as read
                    </button>
                </div>

                <div class="max-h-72 overflow-y-auto divide-y divide-slate-100 admin-sidebar-scroll">
                    <!-- Notification 1 -->
                    <div class="flex items-start gap-3 p-3.5 hover:bg-slate-50 transition cursor-pointer">
                        <div class="h-8 w-8 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 leading-tight">Term 1 Exam Marks Pending</p>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">3 faculty members have not submitted Grade 10 Physics marks.</p>
                            <span class="text-[10px] text-slate-400 mt-1 inline-block">10m ago</span>
                        </div>
                        <span class="h-1.5 w-1.5 rounded-full bg-violet-600 shrink-0 mt-1.5"></span>
                    </div>

                    <!-- Notification 2 -->
                    <div class="flex items-start gap-3 p-3.5 hover:bg-slate-50 transition cursor-pointer">
                        <div class="h-8 w-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 leading-tight">Fee Reconciliation Alert</p>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">18 invoices overdue for October monthly tuition.</p>
                            <span class="text-[10px] text-slate-400 mt-1 inline-block">45m ago</span>
                        </div>
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 shrink-0 mt-1.5"></span>
                    </div>

                    <!-- Notification 3 -->
                    <div class="flex items-start gap-3 p-3.5 hover:bg-slate-50 transition cursor-pointer">
                        <div class="h-8 w-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-800 leading-tight">New Student Admission</p>
                            <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Liam Alexander Vance enrolled into Grade 9 Section A.</p>
                            <span class="text-[10px] text-slate-400 mt-1 inline-block">2h ago</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 bg-slate-50/50 p-2 text-center">
                    <button type="button" class="text-xs font-semibold text-violet-700 hover:text-violet-800 transition cursor-pointer py-1">
                        View All Notifications
                    </button>
                </div>
            </div>
        </div>

        <div class="h-5 w-px bg-slate-200"></div>

        <!-- User Profile Trigger & Dropdown Menu -->
        @php
            $currentAdmin = Auth::guard('admin')->user();
            $adminDisplayName = $currentAdmin ? ($currentAdmin->full_name ?: $currentAdmin->username) : 'System Administrator';
            $adminInitials = collect(explode(' ', $adminDisplayName))->filter()->map(fn($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') ?: 'AD';
            $adminDesignation = $currentAdmin ? ($currentAdmin->designation ?: 'Administrator') : 'Super Administrator';
            $adminEmail = $currentAdmin ? $currentAdmin->email : 'admin@gmail.com';
        @endphp
        <div class="relative">
            <button type="button"
                    id="user-profile-menu-btn"
                    aria-expanded="false"
                    aria-haspopup="true"
                    class="flex items-center gap-2.5 rounded-lg p-1 transition hover:bg-slate-100/80 focus:outline-none focus:ring-2 focus:ring-violet-500/20 cursor-pointer">
                <!-- Avatar -->
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-violet-600 text-xs font-bold text-white shadow-xs">
                    {{ $adminInitials }}
                </div>
                <!-- Name & Designation (Right side of profile icon) -->
                <div class="hidden text-left sm:block">
                    <p class="text-xs font-semibold text-slate-800 leading-tight">{{ $adminDisplayName }}</p>
                    <p class="text-[10px] font-medium text-slate-500 leading-tight">{{ $adminDesignation }}</p>
                </div>
                <!-- Dropdown Chevron -->
                <svg id="profile-dropdown-chevron" class="h-3.5 w-3.5 text-slate-400 transition-transform duration-200 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- User Profile Dropdown -->
            <div id="user-profile-dropdown"
                 class="hidden absolute right-0 mt-1.5 w-56 rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl z-50 text-xs">
                
                <!-- Quick Info Header -->
                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                    <p class="text-xs font-semibold text-slate-900">{{ $adminDisplayName }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ $adminEmail }}</p>
                    <span class="mt-1 inline-block rounded bg-violet-50 px-1.5 py-0.5 text-[9px] font-bold text-violet-700">{{ $adminDesignation }}</span>
                </div>

                <div class="space-y-0.5">
                    <!-- Profile Link -->
                    <button type="button"
                            data-action="profile"
                            class="profile-action-btn flex w-full items-center gap-2.5 rounded-lg px-2.5 py-2 text-left text-slate-700 hover:bg-violet-50 hover:text-violet-700 transition cursor-pointer">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="font-medium">Profile</span>
                    </button>

                    <!-- Change Password Link -->
                    <button type="button"
                            data-action="change-password"
                            class="profile-action-btn flex w-full items-center gap-2.5 rounded-lg px-2.5 py-2 text-left text-slate-700 hover:bg-violet-50 hover:text-violet-700 transition cursor-pointer">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span class="font-medium">Change Password</span>
                    </button>
                </div>

                <div class="my-1 border-t border-slate-100"></div>

                <!-- Logout Form & Trigger -->
                <form id="admin-logout-form" method="POST" action="{{ route('admin.logout') }}" class="hidden">
                    @csrf
                </form>
                <button type="button"
                        onclick="document.getElementById('admin-logout-form').submit();"
                        id="btn-logout"
                        class="profile-action-btn flex w-full items-center gap-2.5 rounded-lg px-2.5 py-2 text-left text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition cursor-pointer">
                    <svg class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="font-semibold">Sign Out</span>
                </button>
            </div>
        </div>
    </div>
</header>
