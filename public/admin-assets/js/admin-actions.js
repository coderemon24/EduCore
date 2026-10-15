/**
 * EduCore SIS - Admin Actions Controller
 * Handles interactive dialogs for admissions, fee collection,
 * Academic Calendar switcher, Notifications dropdown, and User Profile dropdown with password & logout workflows.
 */
(() => {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
        // Helper to check Swal availability
        const withSwal = (callback) => {
            if (window.Swal) {
                callback(window.Swal);
            } else {
                console.warn('SweetAlert2 (Swal) is not yet available.');
            }
        };

        // 1. Quick Admission Dialog
        const handleNewAdmission = () => {
            withSwal((Swal) => {
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
            });
        };

        document.querySelectorAll('[data-action="new-admission"], #btn-quick-admission, #page-admission-btn').forEach((el) => {
            el.addEventListener('click', (e) => {
                e.preventDefault();
                handleNewAdmission();
            });
        });

        // 2. Quick Fee Collection
        const handleCollectFee = () => {
            withSwal((Swal) => {
                Swal.fire({
                    title: 'Fee Collection Lookup',
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
            });
        };

        document.querySelectorAll('[data-action="collect-fee"], #btn-quick-fee').forEach((el) => {
            el.addEventListener('click', (e) => {
                e.preventDefault();
                handleCollectFee();
            });
        });

        // 3. Notifications Dropdown Toggle (Dropdown panel instead of popup modal)
        const notifBtn = document.getElementById('btn-notifications');
        const notifDropdown = document.getElementById('notifications-dropdown');
        if (notifBtn && notifDropdown) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                closeAllDropdowns(notifDropdown);
                const isHidden = notifDropdown.classList.toggle('hidden');
                notifBtn.setAttribute('aria-expanded', (!isHidden).toString());
            });

            // Mark all as read button
            const markAllBtn = document.getElementById('btn-mark-all-read');
            if (markAllBtn) {
                markAllBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const badge = notifBtn.querySelector('span.bg-rose-500');
                    if (badge) badge.classList.add('hidden');
                    const unreadCount = document.getElementById('notif-unread-count');
                    if (unreadCount) {
                        unreadCount.textContent = '0 New';
                        unreadCount.className = 'rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500';
                    }
                    notifDropdown.querySelectorAll('.bg-violet-600, .bg-amber-500').forEach(dot => dot.remove());
                });
            }
        }

        // 4. Quick Action Dropdown Menu Toggle
        const quickActionDropdownBtn = document.getElementById('quick-action-menu-btn');
        const quickActionDropdown = document.getElementById('quick-action-menu-dropdown');
        if (quickActionDropdownBtn && quickActionDropdown) {
            quickActionDropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                closeAllDropdowns(quickActionDropdown);
                quickActionDropdown.classList.toggle('hidden');
            });
        }

        // 5. Academic Calendar Switcher Dropdown
        const calendarBtn = document.getElementById('academic-calendar-btn');
        const calendarDropdown = document.getElementById('academic-calendar-dropdown');
        const calendarChevron = document.getElementById('calendar-dropdown-chevron');
        const calendarLabel = document.getElementById('current-calendar-label');
        const termBadge = document.getElementById('current-term-badge');

        if (calendarBtn && calendarDropdown) {
            calendarBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                closeAllDropdowns(calendarDropdown);
                const isHidden = calendarDropdown.classList.toggle('hidden');
                if (calendarChevron) {
                    calendarChevron.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            });

            // Session switching logic
            document.querySelectorAll('.calendar-session-item').forEach((item) => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const sessionName = item.getAttribute('data-session');
                    const sessionLabel = item.getAttribute('data-session-label');
                    const term = item.getAttribute('data-term');

                    if (calendarLabel) calendarLabel.textContent = sessionLabel;
                    if (termBadge) termBadge.textContent = term;

                    // Persist selection
                    localStorage.setItem('educore_active_academic_session', sessionName);
                    localStorage.setItem('educore_active_academic_term', term);

                    calendarDropdown.classList.add('hidden');
                    if (calendarChevron) calendarChevron.style.transform = 'rotate(0deg)';

                    withSwal((Swal) => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Academic Session Active',
                            text: `Switched context to ${sessionLabel} (${term}).`,
                            timer: 1600,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    });
                });
            });
        }

        // 6. User Profile Dropdown Menu & Actions
        const profileBtn = document.getElementById('user-profile-menu-btn');
        const profileDropdown = document.getElementById('user-profile-dropdown');
        const profileChevron = document.getElementById('profile-dropdown-chevron');

        if (profileBtn && profileDropdown) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                closeAllDropdowns(profileDropdown);
                const isHidden = profileDropdown.classList.toggle('hidden');
                profileBtn.setAttribute('aria-expanded', (!isHidden).toString());
                if (profileChevron) {
                    profileChevron.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            });

            // Profile action: View Profile
            const profileActionBtn = profileDropdown.querySelector('[data-action="profile"]');
            if (profileActionBtn) {
                profileActionBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    profileDropdown.classList.add('hidden');
                    if (profileChevron) profileChevron.style.transform = 'rotate(0deg)';

                    withSwal((Swal) => {
                        Swal.fire({
                            title: 'Administrator Profile',
                            html: `
                                <div class="text-left text-xs space-y-3 pt-2">
                                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                                        <div class="h-12 w-12 rounded-full bg-violet-600 text-white font-bold text-base flex items-center justify-center shadow-xs">
                                            AV
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-slate-800">Arthur Vance</p>
                                            <p class="text-slate-500">principal@school.edu</p>
                                            <span class="inline-block mt-0.5 rounded bg-violet-100 px-2 py-0.5 text-[10px] font-semibold text-violet-700">Super Administrator</span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-slate-600">
                                        <div class="p-2.5 rounded-lg border border-slate-100 bg-white">
                                            <p class="text-[10px] text-slate-400 font-medium">Employee ID</p>
                                            <p class="font-semibold text-slate-800">EMP-2024-001</p>
                                        </div>
                                        <div class="p-2.5 rounded-lg border border-slate-100 bg-white">
                                            <p class="text-[10px] text-slate-400 font-medium">Department</p>
                                            <p class="font-semibold text-slate-800">Executive Administration</p>
                                        </div>
                                        <div class="p-2.5 rounded-lg border border-slate-100 bg-white">
                                            <p class="text-[10px] text-slate-400 font-medium">Joined Date</p>
                                            <p class="font-semibold text-slate-800">15 Jan 2021</p>
                                        </div>
                                        <div class="p-2.5 rounded-lg border border-slate-100 bg-white">
                                            <p class="text-[10px] text-slate-400 font-medium">Security Status</p>
                                            <p class="font-semibold text-emerald-600">2FA Active</p>
                                        </div>
                                    </div>
                                </div>
                            `,
                            confirmButtonColor: '#7c3aed',
                            confirmButtonText: 'Done'
                        });
                    });
                });
            }

            // Profile action: Change Password
            const passwordActionBtn = profileDropdown.querySelector('[data-action="change-password"]');
            if (passwordActionBtn) {
                passwordActionBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    profileDropdown.classList.add('hidden');
                    if (profileChevron) profileChevron.style.transform = 'rotate(0deg)';

                    withSwal((Swal) => {
                        Swal.fire({
                            title: 'Change Account Password',
                            html: `
                                <div class="text-left text-xs space-y-3 pt-2">
                                    <div>
                                        <label class="block font-semibold text-slate-700 mb-1">Current Password</label>
                                        <input type="password" id="current-password" class="swal2-input !m-0 !w-full !text-xs !py-2" placeholder="••••••••">
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-slate-700 mb-1">New Password</label>
                                        <input type="password" id="new-password" class="swal2-input !m-0 !w-full !text-xs !py-2" placeholder="Min. 8 characters">
                                    </div>
                                    <div>
                                        <label class="block font-semibold text-slate-700 mb-1">Confirm New Password</label>
                                        <input type="password" id="confirm-password" class="swal2-input !m-0 !w-full !text-xs !py-2" placeholder="Repeat new password">
                                    </div>
                                </div>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Update Password',
                            confirmButtonColor: '#7c3aed',
                            cancelButtonColor: '#94a3b8',
                            focusConfirm: false,
                            preConfirm: () => {
                                const current = document.getElementById('current-password').value;
                                const next = document.getElementById('new-password').value;
                                const confirm = document.getElementById('confirm-password').value;

                                if (!current || !next || !confirm) {
                                    Swal.showValidationMessage('All password fields are required');
                                    return false;
                                }
                                if (next.length < 8) {
                                    Swal.showValidationMessage('New password must be at least 8 characters');
                                    return false;
                                }
                                if (next !== confirm) {
                                    Swal.showValidationMessage('New passwords do not match');
                                    return false;
                                }
                                return true;
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Password Updated!',
                                    text: 'Your account password has been successfully updated.',
                                    confirmButtonColor: '#7c3aed'
                                });
                            }
                        });
                    });
                });
            }

            // Profile action: Logout
            const logoutActionBtn = profileDropdown.querySelector('[data-action="logout"]');
            if (logoutActionBtn) {
                logoutActionBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    profileDropdown.classList.add('hidden');
                    if (profileChevron) profileChevron.style.transform = 'rotate(0deg)';

                    withSwal((Swal) => {
                        Swal.fire({
                            title: 'Sign Out Confirmation',
                            text: 'Are you sure you want to end your administrative session?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#e11d48',
                            cancelButtonColor: '#94a3b8',
                            confirmButtonText: 'Yes, Sign Out'
                        }).then((res) => {
                            if (res.isConfirmed) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Logged Out',
                                    text: 'Ending administrative session and redirecting...',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    });
                });
            }
        }

        // 7. Global Click-Outside & Keyboard (Escape) Dismissal Helper
        function closeAllDropdowns(exceptElement) {
            if (quickActionDropdown && quickActionDropdown !== exceptElement) {
                quickActionDropdown.classList.add('hidden');
            }
            if (calendarDropdown && calendarDropdown !== exceptElement) {
                calendarDropdown.classList.add('hidden');
                if (calendarChevron) calendarChevron.style.transform = 'rotate(0deg)';
            }
            if (notifDropdown && notifDropdown !== exceptElement) {
                notifDropdown.classList.add('hidden');
                if (notifBtn) notifBtn.setAttribute('aria-expanded', 'false');
            }
            if (profileDropdown && profileDropdown !== exceptElement) {
                profileDropdown.classList.add('hidden');
                if (profileChevron) profileChevron.style.transform = 'rotate(0deg)';
                if (profileBtn) profileBtn.setAttribute('aria-expanded', 'false');
            }
            const searchResults = document.getElementById('search-quick-results');
            if (searchResults && searchResults !== exceptElement) {
                searchResults.classList.add('hidden');
            }
        }

        document.addEventListener('click', () => {
            closeAllDropdowns(null);
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeAllDropdowns(null);
            }
        });
    });
})();
