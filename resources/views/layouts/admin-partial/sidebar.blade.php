<!-- Admin Sidebar Navigation -->
<aside id="admin-sidebar"
       class="group/sidebar fixed inset-y-0 left-0 z-50 flex h-screen w-64 flex-col shrink-0 border-r border-slate-200 bg-white transition-all duration-300 ease-in-out lg:relative lg:z-40 -translate-x-full lg:translate-x-0">

    <!-- Sidebar Header / Logo -->
    <div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-200/80 px-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 overflow-hidden cursor-pointer">
            <!-- Brand Logo -->
            <img src="{{ $currentSchool?->logo_url ?? asset('admin-assets/images/laravel-school-transparent.png') }}"
                 alt="Laravel School Management"
                 class="h-9 w-auto object-contain shrink-0">
            <div class="sidebar-brand-text flex flex-col truncate">
                @if(isset($isGlobalSuperAdmin) && $isGlobalSuperAdmin)
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-800">EduCore Platform</span>
                    <span class="text-[10px] font-medium text-violet-600">Global SaaS Admin</span>
                @else
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-800">{{ $currentSchool->name ?? 'EduCore SIS' }}</span>
                    <span class="text-[10px] font-medium text-slate-500">{{ $currentSchool ? ($currentSchool->code ? $currentSchool->code.' • Active' : 'School Admin Portal') : 'School Admin Portal' }}</span>
                @endif
            </div>
        </a>

        <!-- Mobile Close Button -->
        <button id="mobile-sidebar-close-btn"
                type="button"
                aria-label="Close Sidebar"
                class="lg:hidden inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 hover:bg-slate-100 cursor-pointer">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Scroll Area (Scrolls independently when menu items exceed viewport height) -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6 admin-sidebar-scroll">

        <!-- Category: Core Management -->
        <div>
            <div class="nav-section-title px-3 mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">
                Core Management
            </div>
            <ul class="space-y-1">
                <!-- Overview / Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.dashboard') ? 'bg-violet-50 text-violet-700 font-semibold shadow-xs' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="nav-label truncate text-sm">Overview</span>

                        <!-- Floating Tooltip for collapsed mode -->
                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Overview
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </a>
                </li>

                <!-- Schools & Institutions (Multi-Tenant Management) - Platform Admins Only -->
                @php
                    $isAdminGuard = Auth::guard('admin')->check();
                    $isSuperAdmin = $isAdminGuard && Auth::guard('admin')->user()?->role === 'super_admin';
                    $isSchoolsActive = request()->routeIs('admin.schools.*');
                    $schoolTargetRoute = $isSuperAdmin ? route('admin.schools.index') : route('admin.settings.school');
                @endphp
                @if($isAdminGuard)
                <li>
                    <a href="{{ $schoolTargetRoute }}"
                       class="group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 cursor-pointer {{ $isSchoolsActive ? 'bg-violet-50 text-violet-700 font-semibold shadow-2xs border-l-2 border-violet-600 pl-2.5' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <svg class="h-4.5 w-4.5 shrink-0 {{ $isSchoolsActive ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="nav-label truncate text-xs font-semibold tracking-tight">Schools</span>
                        <span class="nav-badge ml-auto inline-flex items-center gap-1 rounded border border-violet-200/90 bg-violet-50/80 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-violet-700 transition-colors group-hover:bg-violet-100">
                            <span class="h-1 w-1 rounded-full bg-violet-500"></span>
                            <span>{{ $isSuperAdmin ? 'Tenant' : 'Profile' }}</span>
                        </span>

                        <!-- Floating Tooltip for collapsed mode -->
                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Schools & Campuses <span class="text-violet-300 text-[10px]">&bull; {{ $isSuperAdmin ? 'Multi-Tenant' : 'Profile' }}</span>
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </a>
                </li>
                @endif

                <!-- Student Management Collapsible Dropdown -->
                @php
                    $isStudentsActive = request()->routeIs('admin.students.*');
                @endphp
                <li class="relative group/dropdown" id="sidebar-students-dropdown-wrapper">
                    <button type="button"
                            id="sidebar-students-toggle"
                            onclick="window.toggleStudentsSidebarDropdown(event)"
                            aria-expanded="{{ $isStudentsActive ? 'true' : 'false' }}"
                            class="group w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 cursor-pointer {{ $isStudentsActive ? 'bg-violet-50 text-violet-800 font-semibold shadow-2xs border-l-2 border-violet-600 pl-2.5' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="h-4.5 w-4.5 shrink-0 {{ $isStudentsActive ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14c4.418 0 8 1.79 8 4v2H4v-2c0-2.21 3.582-4 8-4zm0-2a5 5 0 100-10 5 5 0 000 10z" />
                            </svg>
                            <span class="nav-label truncate text-xs font-semibold tracking-tight">Student Management</span>
                        </div>
                        <svg id="students-dropdown-chevron"
                             class="nav-chevron h-3.5 w-3.5 shrink-0 text-slate-400 transition-transform duration-200 {{ $isStudentsActive ? 'rotate-180 text-violet-600' : 'rotate-0' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>

                        <!-- Floating Tooltip for collapsed mode -->
                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Student Management
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </button>

                    <!-- Submenu Accordion -->
                    <ul id="sidebar-students-submenu"
                        class="space-y-0.5 mt-1 pl-4 pr-1 {{ $isStudentsActive ? 'block' : 'hidden' }}">
                        <!-- Student Directory -->
                        <li>
                            <a href="{{ route('admin.students.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.students.index') || request()->routeIs('admin.students.show') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.students.index') || request()->routeIs('admin.students.show') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Student Directory</span>
                            </a>
                        </li>
                        <!-- Student Admission -->
                        <li>
                            <a href="{{ route('admin.students.create') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.students.create') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.students.create') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">New Admission</span>
                            </a>
                        </li>
                        <!-- Promotions & Transfers -->
                        <li>
                            <a href="{{ route('admin.students.promotions.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.students.promotions.*') || request()->routeIs('admin.students.promote*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.students.promotions.*') || request()->routeIs('admin.students.promote*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Promotions & Transfers</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Teachers & Faculty -->
                @php
                    $isTeachersActive = request()->routeIs('admin.teachers.*') || (request()->routeIs('admin.staff.*') && request('type') === 'teaching');
                @endphp
                <li>
                    <a href="{{ route('admin.teachers.index') }}"
                       class="group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 cursor-pointer {{ $isTeachersActive ? 'bg-violet-50 text-violet-700 font-semibold shadow-2xs border-l-2 border-violet-600 pl-2.5' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <svg class="h-4.5 w-4.5 shrink-0 {{ $isTeachersActive ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="nav-label truncate text-xs font-semibold tracking-tight">Teachers & Faculty</span>

                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Teachers & Faculty
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </a>
                </li>

                <!-- Staff Directory -->
                @php
                    $isStaffActive = request()->routeIs('admin.staff.*') && request('type') !== 'teaching';
                @endphp
                <li>
                    <a href="{{ route('admin.staff.index') }}"
                       class="group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 cursor-pointer {{ $isStaffActive ? 'bg-violet-50 text-violet-700 font-semibold shadow-2xs border-l-2 border-violet-600 pl-2.5' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <svg class="h-4.5 w-4.5 shrink-0 {{ $isStaffActive ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="nav-label truncate text-xs font-semibold tracking-tight">Staff Directory</span>

                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Staff Directory
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </a>
                </li>

                <!-- Parents -->
                <li>
                    <a href="javascript:void(0)"
                       onclick="window.Swal && Swal.fire({title: 'Parents Portal', text: 'Opening Guardian Contacts and Communication Records.', icon: 'info', confirmButtonColor: '#7c3aed'});"
                       class="group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                        <svg class="h-5 w-5 shrink-0 text-slate-400 group-hover:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="nav-label truncate text-sm">Parents</span>

                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Parents
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Category: Academic & Operations -->
        <div>
            <div class="nav-section-title px-3 mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">
                Academic & Operations
            </div>
            <ul class="space-y-1">
                <!-- Academic Structure Collapsible Dropdown -->
                @php
                    $isAcademicActive = request()->routeIs('admin.academic.*');
                @endphp
                <li class="relative group/dropdown" id="sidebar-academic-dropdown-wrapper">
                    <button type="button"
                            id="sidebar-academic-toggle"
                            onclick="window.toggleAcademicSidebarDropdown(event)"
                            aria-expanded="{{ $isAcademicActive ? 'true' : 'false' }}"
                            class="group w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 cursor-pointer {{ $isAcademicActive ? 'bg-violet-50 text-violet-800 font-semibold shadow-2xs border-l-2 border-violet-600 pl-2.5' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="h-4.5 w-4.5 shrink-0 {{ $isAcademicActive ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5" />
                            </svg>
                            <span class="nav-label truncate text-xs font-semibold tracking-tight">Academic Structure</span>
                        </div>
                        <svg id="academic-dropdown-chevron"
                             class="nav-chevron h-3.5 w-3.5 shrink-0 text-slate-400 transition-transform duration-200 {{ $isAcademicActive ? 'rotate-180 text-violet-600' : 'rotate-0' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>

                        <!-- Floating Tooltip for collapsed mode -->
                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Academic Structure
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </button>

                    <!-- Submenu Accordion -->
                    <ul id="sidebar-academic-submenu"
                        class="space-y-0.5 mt-1 pl-4 pr-1 {{ $isAcademicActive ? 'block' : 'hidden' }}">
                        <!-- Academic Years -->
                        <li>
                            <a href="{{ Route::has('admin.academic.years.index') ? route('admin.academic.years.index') : url('/admin/academic/years') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.academic.years.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.academic.years.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Academic Years</span>
                            </a>
                        </li>
                        <!-- Classes & Grades -->
                        <li>
                            <a href="{{ Route::has('admin.academic.classes.index') ? route('admin.academic.classes.index') : url('/admin/academic/classes') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.academic.classes.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.academic.classes.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Classes & Grades</span>
                            </a>
                        </li>
                        <!-- Sections -->
                        <li>
                            <a href="{{ Route::has('admin.academic.sections.index') ? route('admin.academic.sections.index') : url('/admin/academic/sections') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.academic.sections.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.academic.sections.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Sections</span>
                            </a>
                        </li>
                        <!-- Subjects -->
                        <li>
                            <a href="{{ Route::has('admin.academic.subjects.index') ? route('admin.academic.subjects.index') : url('/admin/academic/subjects') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.academic.subjects.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.academic.subjects.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Subjects Catalog</span>
                            </a>
                        </li>
                        <!-- Assign Subjects -->
                        <li>
                            <a href="{{ Route::has('admin.academic.assign-subjects.index') ? route('admin.academic.assign-subjects.index') : url('/admin/academic/assign-subjects') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.academic.assign-subjects.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.academic.assign-subjects.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Assign Subjects</span>
                            </a>
                        </li>
                        <!-- Groups / Streams -->
                        <li>
                            <a href="{{ Route::has('admin.academic.groups.index') ? route('admin.academic.groups.index') : url('/admin/academic/groups') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.academic.groups.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.academic.groups.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Groups / Streams</span>
                            </a>
                        </li>
                        <!-- Academic Holidays -->
                        <li>
                            <a href="{{ Route::has('admin.academic.holidays.index') ? route('admin.academic.holidays.index') : url('/admin/academic/holidays') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.academic.holidays.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.academic.holidays.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Holidays & Calendar</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Attendance Management Collapsible Dropdown -->
                @php
                    $isAttendanceActive = request()->routeIs('admin.attendance.*');
                @endphp
                <li class="relative group/dropdown" id="sidebar-attendance-dropdown-wrapper">
                    <button type="button"
                            id="sidebar-attendance-toggle"
                            onclick="window.toggleAttendanceSidebarDropdown(event)"
                            aria-expanded="{{ $isAttendanceActive ? 'true' : 'false' }}"
                            class="group w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 cursor-pointer {{ $isAttendanceActive ? 'bg-violet-50 text-violet-800 font-semibold shadow-2xs border-l-2 border-violet-600 pl-2.5' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="h-4.5 w-4.5 shrink-0 {{ $isAttendanceActive ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span class="nav-label truncate text-xs font-semibold tracking-tight">Attendance</span>
                        </div>
                        <svg id="attendance-dropdown-chevron"
                             class="nav-chevron h-3.5 w-3.5 shrink-0 text-slate-400 transition-transform duration-200 {{ $isAttendanceActive ? 'rotate-180 text-violet-600' : 'rotate-0' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>

                        <!-- Floating Tooltip for collapsed mode -->
                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Attendance Records
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </button>

                    <!-- Submenu Accordion -->
                    <ul id="sidebar-attendance-submenu"
                        class="space-y-0.5 mt-1 pl-4 pr-1 {{ $isAttendanceActive ? 'block' : 'hidden' }}">
                        <!-- Student Attendance -->
                        <li>
                            <a href="{{ route('admin.attendance.students.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.attendance.students.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.attendance.students.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Student Roll-Call</span>
                            </a>
                        </li>
                        <!-- Staff Attendance -->
                        <li>
                            <a href="{{ route('admin.attendance.staff.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.attendance.staff.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.attendance.staff.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Staff Attendance</span>
                            </a>
                        </li>
                        <!-- Monthly Register & Reports -->
                        <li>
                            <a href="{{ route('admin.attendance.reports.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.attendance.reports.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.attendance.reports.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Monthly Register</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Timetable & Schedules Collapsible Dropdown -->
                @php
                    $isTimetableActive = request()->routeIs('admin.timetables.*');
                @endphp
                <li class="relative group/dropdown" id="sidebar-timetable-dropdown-wrapper">
                    <button type="button"
                            id="sidebar-timetable-toggle"
                            onclick="window.toggleTimetableSidebarDropdown(event)"
                            aria-expanded="{{ $isTimetableActive ? 'true' : 'false' }}"
                            class="group w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 cursor-pointer {{ $isTimetableActive ? 'bg-violet-50 text-violet-800 font-semibold shadow-2xs border-l-2 border-violet-600 pl-2.5' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="h-4.5 w-4.5 shrink-0 {{ $isTimetableActive ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="nav-label truncate text-xs font-semibold tracking-tight">Timetables</span>
                        </div>
                        <svg id="timetable-dropdown-chevron"
                             class="nav-chevron h-3.5 w-3.5 shrink-0 text-slate-400 transition-transform duration-200 {{ $isTimetableActive ? 'rotate-180 text-violet-600' : 'rotate-0' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>

                        <!-- Floating Tooltip for collapsed mode -->
                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Class Schedules
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </button>

                    <!-- Submenu Accordion -->
                    <ul id="sidebar-timetable-submenu"
                        class="space-y-0.5 mt-1 pl-4 pr-1 {{ $isTimetableActive ? 'block' : 'hidden' }}">
                        <!-- Class Timetable -->
                        <li>
                            <a href="{{ route('admin.timetables.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.timetables.index') && request('view_mode') !== 'teacher' ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.timetables.index') && request('view_mode') !== 'teacher' ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Class Timetables</span>
                            </a>
                        </li>
                        <!-- Teacher Schedule -->
                        <li>
                            <a href="{{ route('admin.timetables.teachers') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.timetables.teachers') || (request()->routeIs('admin.timetables.index') && request('view_mode') === 'teacher') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.timetables.teachers') || (request()->routeIs('admin.timetables.index') && request('view_mode') === 'teacher') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Faculty Schedules</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Exams & Results Collapsible Dropdown -->
                @php
                    $canViewExams = !Auth::guard('web')->check() || (Auth::guard('web')->user()?->hasPermission('exams.view'));
                    $isExamsActive = request()->routeIs('admin.exams.*');
                @endphp
                @if($canViewExams)
                <li class="relative group/dropdown" id="sidebar-exams-dropdown-wrapper">
                    <button type="button"
                            id="sidebar-exams-toggle"
                            onclick="window.toggleExamsSidebarDropdown(event)"
                            aria-expanded="{{ $isExamsActive ? 'true' : 'false' }}"
                            class="group w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 cursor-pointer {{ $isExamsActive ? 'bg-violet-50 text-violet-800 font-semibold shadow-2xs border-l-2 border-violet-600 pl-2.5' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="h-4.5 w-4.5 shrink-0 {{ $isExamsActive ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="nav-label truncate text-xs font-semibold tracking-tight">Exams & Results</span>
                        </div>
                        <svg id="exams-dropdown-chevron"
                             class="nav-chevron h-3.5 w-3.5 shrink-0 text-slate-400 transition-transform duration-200 {{ $isExamsActive ? 'rotate-180 text-violet-600' : 'rotate-0' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>

                        <!-- Floating Tooltip for collapsed mode -->
                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Exams & Results
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </button>

                    <!-- Submenu Accordion -->
                    <ul id="sidebar-exams-submenu"
                        class="space-y-0.5 mt-1 pl-4 pr-1 {{ $isExamsActive ? 'block' : 'hidden' }}">
                        <!-- All Exams -->
                        <li>
                            <a href="{{ route('admin.exams.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ (request()->routeIs('admin.exams.index') || request()->routeIs('admin.exams.create') || request()->routeIs('admin.exams.edit') || request()->routeIs('admin.exams.subjects.*')) ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ (request()->routeIs('admin.exams.index') || request()->routeIs('admin.exams.create') || request()->routeIs('admin.exams.edit') || request()->routeIs('admin.exams.subjects.*')) ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">All Exams</span>
                            </a>
                        </li>
                        <!-- Marks Entry -->
                        <li>
                            <a href="{{ route('admin.exams.marks.entry') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.exams.marks.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.exams.marks.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Marks Entry</span>
                            </a>
                        </li>
                        <!-- Results & Ranks -->
                        <li>
                            <a href="{{ route('admin.exams.results.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.exams.results.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.exams.results.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Results & Ranks</span>
                            </a>
                        </li>
                        <!-- Report Cards -->
                        <li>
                            <a href="{{ route('admin.exams.report-cards.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.exams.report-cards.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.exams.report-cards.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Report Cards</span>
                            </a>
                        </li>
                        <!-- Grading Scales -->
                        <li>
                            <a href="{{ route('admin.exams.grades.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.exams.grades.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.exams.grades.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Grading Scales</span>
                            </a>
                        </li>
                        <!-- Marksheet & ID Card Settings -->
                        <li>
                            <a href="{{ route('admin.exams.settings.marksheet') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.exams.settings.marksheet*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.exams.settings.marksheet*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Marksheet Settings</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>

        <!-- Category: Finance & System -->
        <div>
            <div class="nav-section-title px-3 mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">
                Finance & System
            </div>
            <ul class="space-y-1">
                <!-- Fees & Accounts Collapsible Dropdown -->
                @php
                    $canViewFees = !Auth::guard('web')->check() || (Auth::guard('web')->user()?->hasPermission('fees.view'));
                    $isFeesActive = request()->routeIs('admin.fees.*');
                @endphp
                @if($canViewFees)
                <li class="relative group/dropdown" id="sidebar-fees-dropdown-wrapper">
                    <button type="button"
                            id="sidebar-fees-toggle"
                            onclick="window.toggleFeesSidebarDropdown(event)"
                            aria-expanded="{{ $isFeesActive ? 'true' : 'false' }}"
                            class="group w-full flex items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 cursor-pointer {{ $isFeesActive ? 'bg-violet-50 text-violet-800 font-semibold shadow-2xs border-l-2 border-violet-600 pl-2.5' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="h-4.5 w-4.5 shrink-0 {{ $isFeesActive ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="nav-label truncate text-xs font-semibold tracking-tight">Fees & Accounts</span>
                        </div>
                        <svg id="fees-dropdown-chevron"
                             class="nav-chevron h-3.5 w-3.5 shrink-0 text-slate-400 transition-transform duration-200 {{ $isFeesActive ? 'rotate-180 text-violet-600' : 'rotate-0' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>

                        <!-- Floating Tooltip for collapsed mode -->
                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Fees & Accounts
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </button>

                    <!-- Submenu Accordion -->
                    <ul id="sidebar-fees-submenu"
                        class="space-y-0.5 mt-1 pl-4 pr-1 {{ $isFeesActive ? 'block' : 'hidden' }}">
                        <!-- Invoices & Dues -->
                        <li>
                            <a href="{{ route('admin.fees.invoices.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.fees.invoices.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.fees.invoices.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Invoices & Dues</span>
                            </a>
                        </li>
                        <!-- Fee Structures -->
                        <li>
                            <a href="{{ route('admin.fees.structures.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.fees.structures.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.fees.structures.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Fee Structures</span>
                            </a>
                        </li>
                        <!-- Fee Categories -->
                        <li>
                            <a href="{{ route('admin.fees.categories.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.fees.categories.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.fees.categories.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Fee Categories</span>
                            </a>
                        </li>
                        <!-- Financial Reports -->
                        <li>
                            <a href="{{ route('admin.fees.reports.index') }}"
                               class="group relative flex items-center gap-2.5 rounded-md px-3 py-1.5 text-xs font-medium transition-colors cursor-pointer {{ request()->routeIs('admin.fees.reports.*') ? 'bg-violet-100/70 text-violet-800 font-bold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('admin.fees.reports.*') ? 'bg-violet-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                <span class="truncate">Financial Reports</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Notice Board -->
                <li>
                    <a href="javascript:void(0)"
                       onclick="window.Swal && Swal.fire({title: 'Notice Board', text: 'Managing School Bulletins and Parent Announcements.', icon: 'info', confirmButtonColor: '#7c3aed'});"
                       class="group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                        <svg class="h-5 w-5 shrink-0 text-slate-400 group-hover:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        <span class="nav-label truncate text-sm">Notice Board</span>

                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Notice Board
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </a>
                </li>

                <!-- Roles & Permissions -->
                @php
                    $canManageRoles = !Auth::guard('web')->check() || (Auth::guard('web')->user()?->hasPermission('roles.view'));
                    $isRolesActive = request()->routeIs('admin.roles.*');
                @endphp
                @if($canManageRoles)
                <li>
                    <a href="{{ route('admin.roles.index') }}"
                       class="group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150 cursor-pointer {{ $isRolesActive ? 'bg-violet-50 text-violet-700 font-semibold shadow-2xs border-l-2 border-violet-600 pl-2.5' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                        <svg class="h-4.5 w-4.5 shrink-0 {{ $isRolesActive ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="nav-label truncate text-xs font-semibold tracking-tight">Roles & Permissions</span>

                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Roles & Permissions
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </a>
                </li>
                @endif

                <!-- Settings -->
                @php
                    $isSettingsActive = request()->routeIs('admin.settings.*');
                @endphp
                <li>
                    <a href="{{ route('admin.settings.school') }}"
                       class="group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors cursor-pointer {{ $isSettingsActive ? 'bg-violet-50 text-violet-700 font-semibold shadow-xs' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="h-5 w-5 shrink-0 {{ $isSettingsActive ? 'text-violet-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="nav-label truncate text-sm">Settings</span>

                        <span class="nav-item-tooltip absolute left-full top-1/2 -translate-y-1/2 ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-medium rounded-md shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50 whitespace-nowrap">
                            Settings
                            <span class="absolute left-[-4px] top-1/2 -translate-y-1/2 border-y-4 border-y-transparent border-r-4 border-r-slate-900"></span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Sidebar Footer User Quick Card -->
    @php
        $sidebarAdmin = Auth::guard('admin')->user() ?? Auth::guard('web')->user();
        $sidebarAdminName = $sidebarAdmin ? ($sidebarAdmin->full_name ?: ($sidebarAdmin->name ?: $sidebarAdmin->username)) : 'System Administrator';
        $sidebarAdminInitials = collect(explode(' ', $sidebarAdminName))->filter()->map(fn($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') ?: 'AD';
        $sidebarAdminEmail = $sidebarAdmin ? $sidebarAdmin->email : 'admin@gmail.com';
    @endphp
    <div class="border-t border-slate-200/80 p-3 shrink-0">
        <div class="flex items-center gap-3 rounded-lg bg-slate-50 p-2">
            <div class="h-9 w-9 shrink-0 rounded-full bg-violet-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                {{ $sidebarAdminInitials }}
            </div>
            <div class="sidebar-brand-text min-w-0 flex-1">
                <p class="truncate text-xs font-semibold text-slate-800">{{ $sidebarAdminName }}</p>
                <p class="truncate text-[10px] text-slate-500">{{ $sidebarAdminEmail }}</p>
            </div>
        </div>
    </div>

    <!-- Direct Fallback Script for Sidebar Academic Structure Dropdown -->
    <script>
        window.toggleAcademicSidebarDropdown = function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            var body = document.getElementById('admin-body') || document.body;
            var sidebar = document.getElementById('admin-sidebar');
            var toggle = document.getElementById('sidebar-academic-toggle');
            var submenu = document.getElementById('sidebar-academic-submenu');
            var chevron = document.getElementById('academic-dropdown-chevron');

            if (!submenu) return;

            // If sidebar is collapsed in icon mode, expand to full width
            if (body && body.classList.contains('sidebar-collapsed')) {
                body.classList.remove('sidebar-collapsed');
                if (sidebar) {
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');
                }
                try { localStorage.setItem('educore_sidebar_collapsed', 'false'); } catch (_) {}
            }

            var isHidden = submenu.classList.contains('hidden') || submenu.style.display === 'none';

            if (isHidden) {
                submenu.classList.remove('hidden');
                submenu.classList.add('block');
                submenu.style.display = 'block';
                if (chevron) {
                    chevron.classList.add('rotate-180');
                    chevron.classList.remove('rotate-0');
                }
                if (toggle) toggle.setAttribute('aria-expanded', 'true');
            } else {
                submenu.classList.add('hidden');
                submenu.classList.remove('block');
                submenu.style.display = 'none';
                if (chevron) {
                    chevron.classList.remove('rotate-180');
                    chevron.classList.add('rotate-0');
                }
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
            }
        };

        window.toggleStudentsSidebarDropdown = function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            var body = document.getElementById('admin-body') || document.body;
            var sidebar = document.getElementById('admin-sidebar');
            var toggle = document.getElementById('sidebar-students-toggle');
            var submenu = document.getElementById('sidebar-students-submenu');
            var chevron = document.getElementById('students-dropdown-chevron');

            if (!submenu) return;

            // If sidebar is collapsed in icon mode, expand to full width
            if (body && body.classList.contains('sidebar-collapsed')) {
                body.classList.remove('sidebar-collapsed');
                if (sidebar) {
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');
                }
                try { localStorage.setItem('educore_sidebar_collapsed', 'false'); } catch (_) {}
            }

            var isHidden = submenu.classList.contains('hidden') || submenu.style.display === 'none';

            if (isHidden) {
                submenu.classList.remove('hidden');
                submenu.classList.add('block');
                submenu.style.display = 'block';
                if (chevron) {
                    chevron.classList.add('rotate-180');
                    chevron.classList.remove('rotate-0');
                }
                if (toggle) toggle.setAttribute('aria-expanded', 'true');
            } else {
                submenu.classList.add('hidden');
                submenu.classList.remove('block');
                submenu.style.display = 'none';
                if (chevron) {
                    chevron.classList.remove('rotate-180');
                    chevron.classList.add('rotate-0');
                }
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
            }
        };

        window.toggleAttendanceSidebarDropdown = function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            var body = document.getElementById('admin-body') || document.body;
            var sidebar = document.getElementById('admin-sidebar');
            var toggle = document.getElementById('sidebar-attendance-toggle');
            var submenu = document.getElementById('sidebar-attendance-submenu');
            var chevron = document.getElementById('attendance-dropdown-chevron');

            if (!submenu) return;

            if (body && body.classList.contains('sidebar-collapsed')) {
                body.classList.remove('sidebar-collapsed');
                if (sidebar) {
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');
                }
                try { localStorage.setItem('educore_sidebar_collapsed', 'false'); } catch (_) {}
            }

            var isHidden = submenu.classList.contains('hidden') || submenu.style.display === 'none';

            if (isHidden) {
                submenu.classList.remove('hidden');
                submenu.classList.add('block');
                submenu.style.display = 'block';
                if (chevron) {
                    chevron.classList.add('rotate-180');
                    chevron.classList.remove('rotate-0');
                }
                if (toggle) toggle.setAttribute('aria-expanded', 'true');
            } else {
                submenu.classList.add('hidden');
                submenu.classList.remove('block');
                submenu.style.display = 'none';
                if (chevron) {
                    chevron.classList.remove('rotate-180');
                    chevron.classList.add('rotate-0');
                }
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
            }
        };

        window.toggleTimetableSidebarDropdown = function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            var body = document.getElementById('admin-body') || document.body;
            var sidebar = document.getElementById('admin-sidebar');
            var toggle = document.getElementById('sidebar-timetable-toggle');
            var submenu = document.getElementById('sidebar-timetable-submenu');
            var chevron = document.getElementById('timetable-dropdown-chevron');

            if (!submenu) return;

            if (body && body.classList.contains('sidebar-collapsed')) {
                body.classList.remove('sidebar-collapsed');
                if (sidebar) {
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');
                }
                try { localStorage.setItem('educore_sidebar_collapsed', 'false'); } catch (_) {}
            }

            var isHidden = submenu.classList.contains('hidden') || submenu.style.display === 'none';

            if (isHidden) {
                submenu.classList.remove('hidden');
                submenu.classList.add('block');
                submenu.style.display = 'block';
                if (chevron) {
                    chevron.classList.add('rotate-180');
                    chevron.classList.remove('rotate-0');
                }
                if (toggle) toggle.setAttribute('aria-expanded', 'true');
            } else {
                submenu.classList.add('hidden');
                submenu.classList.remove('block');
                submenu.style.display = 'none';
                if (chevron) {
                    chevron.classList.remove('rotate-180');
                    chevron.classList.add('rotate-0');
                }
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
            }
        };

        window.toggleFeesSidebarDropdown = function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            var body = document.getElementById('admin-body') || document.body;
            var sidebar = document.getElementById('admin-sidebar');
            var toggle = document.getElementById('sidebar-fees-toggle');
            var submenu = document.getElementById('sidebar-fees-submenu');
            var chevron = document.getElementById('fees-dropdown-chevron');

            if (!submenu) return;

            if (body && body.classList.contains('sidebar-collapsed')) {
                body.classList.remove('sidebar-collapsed');
                if (sidebar) {
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');
                }
                try { localStorage.setItem('educore_sidebar_collapsed', 'false'); } catch (_) {}
            }

            var isHidden = submenu.classList.contains('hidden') || submenu.style.display === 'none';

            if (isHidden) {
                submenu.classList.remove('hidden');
                submenu.classList.add('block');
                submenu.style.display = 'block';
                if (chevron) {
                    chevron.classList.add('rotate-180');
                    chevron.classList.remove('rotate-0');
                }
                if (toggle) toggle.setAttribute('aria-expanded', 'true');
            } else {
                submenu.classList.add('hidden');
                submenu.classList.remove('block');
                submenu.style.display = 'none';
                if (chevron) {
                    chevron.classList.remove('rotate-180');
                    chevron.classList.add('rotate-0');
                }
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
            }
        };

        window.toggleExamsSidebarDropdown = function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            var body = document.getElementById('admin-body') || document.body;
            var sidebar = document.getElementById('admin-sidebar');
            var toggle = document.getElementById('sidebar-exams-toggle');
            var submenu = document.getElementById('sidebar-exams-submenu');
            var chevron = document.getElementById('exams-dropdown-chevron');

            if (!submenu) return;

            if (body && body.classList.contains('sidebar-collapsed')) {
                body.classList.remove('sidebar-collapsed');
                if (sidebar) {
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');
                }
                try { localStorage.setItem('educore_sidebar_collapsed', 'false'); } catch (_) {}
            }

            var isHidden = submenu.classList.contains('hidden') || submenu.style.display === 'none';

            if (isHidden) {
                submenu.classList.remove('hidden');
                submenu.classList.add('block');
                submenu.style.display = 'block';
                if (chevron) {
                    chevron.classList.add('rotate-180');
                    chevron.classList.remove('rotate-0');
                }
                if (toggle) toggle.setAttribute('aria-expanded', 'true');
            } else {
                submenu.classList.add('hidden');
                submenu.classList.remove('block');
                submenu.style.display = 'none';
                if (chevron) {
                    chevron.classList.remove('rotate-180');
                    chevron.classList.add('rotate-0');
                }
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
            }
        };
    </script>
</aside>
