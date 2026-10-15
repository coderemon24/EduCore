<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    @include('layouts.admin-partial.head')
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50/60 selection:bg-violet-500 selection:text-white" id="admin-body">

    <!-- Root Layout Container -->
    <div id="admin-layout-wrapper" class="flex min-h-screen">

        <!-- Mobile Backdrop Overlay -->
        <div id="sidebar-backdrop"
             class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-xs transition-opacity duration-300 hidden lg:hidden"></div>

        <!-- Collapsible Admin Sidebar -->
        @include('layouts.admin-partial.sidebar')

        <!-- Main Content Column -->
        <div id="admin-main-content" class="flex min-w-0 flex-1 flex-col transition-all duration-300 ease-in-out">
            
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

    <!-- Layout & Sidebar Interaction Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.getElementById('admin-body');
            const sidebar = document.getElementById('admin-sidebar');
            const mainContent = document.getElementById('admin-main-content');
            const toggleBtn = document.getElementById('sidebar-toggle-btn');
            const mobileCloseBtn = document.getElementById('mobile-sidebar-close-btn');
            const backdrop = document.getElementById('sidebar-backdrop');
            const searchInput = document.getElementById('global-search-input');

            // 1. Restore Sidebar Collapsed Preference
            const isCollapsed = localStorage.getItem('educore_sidebar_collapsed') === 'true';
            if (isCollapsed && window.innerWidth >= 1024) {
                applyCollapsedState(true);
            }

            function applyCollapsedState(collapsed) {
                if (collapsed) {
                    body.classList.add('sidebar-collapsed');
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-20');
                } else {
                    body.classList.remove('sidebar-collapsed');
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');
                }
            }

            // 2. Desktop Toggle Click Handler
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        // On mobile: toggle mobile drawer
                        sidebar.classList.toggle('-translate-x-full');
                        backdrop.classList.toggle('hidden');
                    } else {
                        // On desktop: collapse/expand
                        const currentlyCollapsed = body.classList.contains('sidebar-collapsed');
                        applyCollapsedState(!currentlyCollapsed);
                        localStorage.setItem('educore_sidebar_collapsed', (!currentlyCollapsed).toString());
                    }
                });
            }

            // 3. Mobile Close Button & Backdrop Click
            if (mobileCloseBtn) {
                mobileCloseBtn.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                });
            }

            // 4. Initial Mobile Sidebar State
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
            }

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.add('hidden');
                    const saved = localStorage.getItem('educore_sidebar_collapsed') === 'true';
                    applyCollapsedState(saved);
                } else {
                    body.classList.remove('sidebar-collapsed');
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');
                    sidebar.classList.add('-translate-x-full');
                }
            });

            // 5. Global Keyboard Shortcut for Search (⌘K / Ctrl+K)
            window.addEventListener('keydown', (e) => {
                if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }
            });

            // 6. Interactive Quick Actions with SweetAlert2
            const quickAdmissionBtn = document.getElementById('btn-quick-admission');
            if (quickAdmissionBtn) {
                quickAdmissionBtn.addEventListener('click', () => {
                    if (window.Swal) {
                        Swal.fire({
                            title: 'New Student Admission',
                            html: `
                                <div class="text-left text-sm space-y-3 pt-2">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Student Full Name</label>
                                        <input id="swal-student-name" class="swal2-input !m-0 !w-full !text-xs !py-2" placeholder="e.g. Liam Alexander Vance">
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Grade Level</label>
                                            <select id="swal-grade" class="swal2-select !m-0 !w-full !text-xs !py-2">
                                                <option>Grade 8</option>
                                                <option>Grade 9</option>
                                                <option>Grade 10</option>
                                                <option>Grade 11</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Gender</label>
                                            <select id="swal-gender" class="swal2-select !m-0 !w-full !text-xs !py-2">
                                                <option>Male</option>
                                                <option>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Register Admission',
                            confirmButtonColor: '#7c3aed',
                            cancelButtonColor: '#94a3b8',
                            focusConfirm: false,
                            preConfirm: () => {
                                const name = document.getElementById('swal-student-name').value;
                                if (!name) {
                                    Swal.showValidationMessage('Please enter student name');
                                    return false;
                                }
                                return { name: name };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Admission Created!',
                                    text: `Student ${result.value.name} successfully registered. System ID generated.`,
                                    confirmButtonColor: '#7c3aed'
                                });
                            }
                        });
                    }
                });
            }

            const quickFeeBtn = document.getElementById('btn-quick-fee');
            if (quickFeeBtn) {
                quickFeeBtn.addEventListener('click', () => {
                    if (window.Swal) {
                        Swal.fire({
                            title: 'Quick Fee Collection',
                            input: 'text',
                            inputLabel: 'Student Admission or Roll ID',
                            inputPlaceholder: 'e.g. PRE-0021',
                            showCancelButton: true,
                            confirmButtonText: 'Lookup Ledger',
                            confirmButtonColor: '#059669',
                            cancelButtonColor: '#94a3b8'
                        }).then((res) => {
                            if (res.isConfirmed && res.value) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Ledger Retrieved',
                                    text: `Student ${res.value}: Term 1 Balance is $450.00 (Due 31 Oct).`,
                                    confirmButtonColor: '#059669'
                                });
                            }
                        });
                    }
                });
            }

            const notifBtn = document.getElementById('btn-notifications');
            if (notifBtn) {
                notifBtn.addEventListener('click', () => {
                    if (window.Swal) {
                        Swal.fire({
                            title: 'Administrative Alerts',
                            html: `
                                <div class="text-left text-xs space-y-2.5">
                                    <div class="p-2.5 rounded-lg bg-violet-50 border border-violet-100">
                                        <p class="font-semibold text-violet-900">Term 1 Exam Marks Pending</p>
                                        <p class="text-violet-700 text-[11px]">3 faculty members have not submitted Grade 10 Physics marks.</p>
                                    </div>
                                    <div class="p-2.5 rounded-lg bg-amber-50 border border-amber-100">
                                        <p class="font-semibold text-amber-900">Fee Reconciliation Alert</p>
                                        <p class="text-amber-700 text-[11px]">18 invoices overdue for October monthly tuition.</p>
                                    </div>
                                </div>
                            `,
                            confirmButtonColor: '#7c3aed',
                            confirmButtonText: 'Acknowledge All'
                        });
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
