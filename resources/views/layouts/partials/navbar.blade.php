<header x-data="{ mobileMenuOpen: false, mAbout: {{ request()->routeIs('frontend.about*') ? 'true' : 'false' }}, mStaff: {{ request()->routeIs('frontend.teachers*') ? 'true' : 'false' }}, mAcademic: {{ request()->routeIs('frontend.academic*') ? 'true' : 'false' }} }"
        x-effect="document.body.style.overflow = mobileMenuOpen ? 'hidden' : ''"
        class="snhs-header bg-white border-b border-slate-200 sticky top-0 z-50">
    {{-- Brand header --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('frontend.home') }}" class="flex items-center gap-3.5 group">
                <div class="w-13 h-13 sm:w-15 sm:h-15 flex-shrink-0 rounded-full border border-slate-300 p-1 bg-white flex items-center justify-center shadow-2xs group-hover:border-[#005030] transition-colors">
                    <img src="{{ asset('assets/images/logo.png') }}"
                         alt="সোনাতলা উচ্চ বিদ্যালয়"
                         class="max-w-full max-h-full object-contain"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=SHS&background=005030&color=fff&size=128';">
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight leading-tight group-hover:text-[#005030] transition-colors">
                        সোনাতলা উচ্চ বিদ্যালয়
                    </h1>
                    <div class="text-xs font-semibold text-slate-500 tracking-wider uppercase mt-0.5">
                        Sonatala High School
                    </div>
                    <div class="text-[11px] text-slate-400 font-medium mt-0.5 hidden sm:block">
                        বেড়া-সোনাতলা, সাঁথিয়া, পাবনা • স্থাপিত: ১৯৬৮ খ্রি.
                    </div>
                </div>
            </a>

            {{-- Quick contact & actions --}}
            <div class="hidden md:flex items-center gap-3">
                <div class="text-right">
                    <div class="text-[11px] text-slate-400 font-medium">প্রধান শিক্ষকের দপ্তর</div>
                    <a href="tel:+8801712345678" class="text-sm font-bold text-slate-900 hover:text-[#005030]">
                        +৮৮০ ১৭১২-৩৪৫৬৭৮
                    </a>
                </div>
                <div class="w-9 h-9 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center text-[#005030]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <a href="{{ route('frontend.contact') }}#enroll"
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-amber-400 hover:bg-amber-300 text-slate-950 text-xs font-extrabold shadow-xs transition-all hover:scale-105 active:scale-95">
                    <svg class="w-3.5 h-3.5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>অনলাইন ভর্তি</span>
                </a>
                <div class="h-6 w-px bg-slate-200 mx-0.5"></div>
                <a href="{{ url('/admin/dashboard') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold shadow-2xs transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>লগইন পোর্টাল</span>
                </a>
            </div>

            {{-- Mobile menu button --}}
            <div class="snhs-menu-btn">
                <button type="button"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="p-2 rounded border border-slate-300 text-slate-700 hover:bg-slate-100 focus:outline-none"
                        aria-label="Toggle Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Main navigation --}}
    <nav class="snhs-nav" aria-label="প্রধান নেভিগেশন">
        <div class="snhs-nav-inner">
            <div class="snhs-nav-list">
                {{-- Home --}}
                <div class="snhs-nav-item {{ request()->routeIs('frontend.home') ? 'is-active' : '' }}">
                    <a href="{{ route('frontend.home') }}" class="snhs-nav-link">মুলপাতা</a>
                </div>

                {{-- About / পরিচিতি --}}
                <div class="snhs-nav-item {{ request()->routeIs('frontend.about*') ? 'is-active' : '' }}">
                    <a href="{{ route('frontend.about') }}" class="snhs-nav-link" aria-haspopup="true">
                        <span>পরিচিতি</span>
                        <svg class="snhs-nav-caret" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M5.5 7.5L10 12l4.5-4.5H5.5z"/></svg>
                    </a>
                    <div class="snhs-dropdown" data-dropdown="about">
                        <a href="{{ route('frontend.about') }}#overview" data-section="overview" class="{{ request()->routeIs('frontend.about*') ? 'is-active' : '' }}">আমাদের কথা</a>
                        <a href="{{ route('frontend.about') }}#history" data-section="history">ইতিহাস ও ঐতিহ্য</a>
                        <a href="{{ route('frontend.about') }}#mission" data-section="mission">লক্ষ্য ও উদ্দেশ্য</a>
                        <a href="{{ route('frontend.about') }}#statistics" data-section="statistics">অর্জন ও পরিসংখ্যান</a>
                        <a href="{{ route('frontend.about') }}#milestones" data-section="milestones">অগ্রযাত্রার মাইলফলক</a>
                        <a href="{{ route('frontend.about') }}#committee" data-section="committee">পরিচালনা পর্ষদ</a>
                        <a href="{{ route('frontend.about') }}#infrastructure" data-section="infrastructure">অবকাঠামো ও ক্যাম্পাস</a>
                    </div>
                </div>

                {{-- Staff / জনবল --}}
                <div class="snhs-nav-item {{ request()->routeIs('frontend.teachers*') ? 'is-active' : '' }}">
                    <a href="{{ route('frontend.teachers.index') }}" class="snhs-nav-link" aria-haspopup="true">
                        <span>জনবল</span>
                        <svg class="snhs-nav-caret" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M5.5 7.5L10 12l4.5-4.5H5.5z"/></svg>
                    </a>
                    <div class="snhs-dropdown" data-dropdown="staff">
                        <a href="{{ route('frontend.teachers.index') }}" class="{{ request()->routeIs('frontend.teachers*') ? 'is-active' : '' }}">শিক্ষকমণ্ডলী</a>
                        <a href="{{ route('frontend.teachers.index') }}#staff">কর্মচারী তালিকা</a>
                        <a href="{{ route('frontend.teachers.index') }}#principal">প্রধান শিক্ষক</a>
                        <a href="{{ route('frontend.teachers.index') }}#office">অফিস ও সহায়ক স্টাফ</a>
                    </div>
                </div>

                {{-- Students / শিক্ষার্থীদের তথ্য --}}
                <div class="snhs-nav-item">
                    <a href="{{ route('frontend.notices.index') }}" class="snhs-nav-link">শিক্ষার্থীদের তথ্য</a>
                </div>

                {{-- Academic / একাডেমিক --}}
                <div class="snhs-nav-item {{ request()->routeIs('frontend.academic*') ? 'is-active' : '' }}">
                    <a href="{{ route('frontend.academic.classes') }}" class="snhs-nav-link" aria-haspopup="true">
                        <span>একাডেমিক</span>
                        <svg class="snhs-nav-caret" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M5.5 7.5L10 12l4.5-4.5H5.5z"/></svg>
                    </a>
                    <div class="snhs-dropdown" data-dropdown="academic">
                        <a href="{{ route('frontend.academic.classes') }}" class="{{ request()->routeIs('frontend.academic.classes*') ? 'is-active' : '' }}">শ্রেণিসমূহ</a>
                        <a href="{{ route('frontend.academic.routine') }}" class="{{ request()->routeIs('frontend.academic.routine*') ? 'is-active' : '' }}">ক্লাস রুটিন</a>
                        <a href="{{ route('frontend.academic.routine') }}#exams">পরীক্ষার সময়সূচি</a>
                        <a href="{{ route('frontend.notices.index') }}">সিলেবাস ও পাঠ্যপুস্তক</a>
                        <a href="{{ route('frontend.notices.index') }}">ফলাফল ও নম্বরপত্র</a>
                        <a href="{{ route('frontend.notices.index') }}">সহপাঠক্রমিক কার্যক্রম</a>
                    </div>
                </div>

                {{-- Notice Board --}}
                <div class="snhs-nav-item {{ request()->routeIs('frontend.notices*') ? 'is-active' : '' }}">
                    <a href="{{ route('frontend.notices.index') }}" class="snhs-nav-link">নোটিশ বোর্ড</a>
                </div>

                {{-- News --}}
                <div class="snhs-nav-item {{ request()->routeIs('frontend.news*') ? 'is-active' : '' }}">
                    <a href="{{ route('frontend.news.index') }}" class="snhs-nav-link">সংবাদ</a>
                </div>

                {{-- Events --}}
                <div class="snhs-nav-item {{ request()->routeIs('frontend.events*') ? 'is-active' : '' }}">
                    <a href="{{ route('frontend.events.index') }}" class="snhs-nav-link">ইভেন্ট</a>
                </div>

                {{-- Gallery --}}
                <div class="snhs-nav-item">
                    <a href="{{ route('frontend.home') }}#gallery" class="snhs-nav-link">ছবির গ্যালারি</a>
                </div>

                {{-- Tutor Day --}}
                <div class="snhs-nav-item">
                    <a href="{{ route('frontend.notices.index') }}" class="snhs-nav-link">ছুটির দিন</a>
                </div>

                {{-- Admission --}}
                <div class="snhs-nav-item">
                    <a href="{{ route('frontend.notices.index') }}" class="snhs-nav-link">ভর্তি</a>
                </div>

                {{-- Contact --}}
                <div class="snhs-nav-item {{ request()->routeIs('frontend.contact*') ? 'is-active' : '' }}">
                    <a href="{{ route('frontend.contact') }}" class="snhs-nav-link">যোগাযোগ</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Mobile drawer --}}
    <div x-show="mobileMenuOpen"
         x-cloak
         x-transition:enter="snhs-overlay-enter"
         x-transition:enter-start="snhs-overlay-enter-start"
         x-transition:enter-end="snhs-overlay-enter-end"
         x-transition:leave="snhs-overlay-leave"
         x-transition:leave-start="snhs-overlay-leave-start"
         x-transition:leave-end="snhs-overlay-leave-end"
         class="snhs-mobile-only snhs-overlay fixed inset-0 bg-black/60"
         @click="mobileMenuOpen = false"></div>

    <div x-show="mobileMenuOpen"
         x-cloak
         x-transition:enter="snhs-drawer-enter"
         x-transition:enter-start="snhs-drawer-enter-start"
         x-transition:enter-end="snhs-drawer-enter-end"
         x-transition:leave="snhs-drawer-leave"
         x-transition:leave-start="snhs-drawer-leave-start"
         x-transition:leave-end="snhs-drawer-leave-end"
         class="snhs-mobile-only snhs-drawer fixed inset-y-0 left-0 w-72 bg-white shadow-2xl overflow-y-auto">
        <div class="p-4 bg-[#005030] text-white flex items-center justify-between">
            <span class="font-bold text-sm">মেনু নির্বাচন করুন</span>
            <button type="button" @click="mobileMenuOpen = false" class="text-white p-1" aria-label="Close menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="py-2 text-sm font-semibold divide-y divide-slate-100 text-slate-800">
            <a href="{{ route('frontend.home') }}" class="snhs-mobile-link {{ request()->routeIs('frontend.home') ? 'is-active' : '' }}">মুলপাতা</a>

            {{-- About accordion --}}
            <div>
                <button type="button" @click="mAbout = !mAbout" class="snhs-mobile-toggle {{ request()->routeIs('frontend.about*') ? 'is-active' : '' }}">
                    <span>পরিচিতি</span>
                    <svg class="snhs-mobile-caret" :class="mAbout ? 'is-open' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="mAbout" x-cloak class="snhs-mobile-sub" data-mobile-sub="about">
                    <a href="{{ route('frontend.about') }}#overview" data-section="overview" @click="mobileMenuOpen = false" class="{{ request()->routeIs('frontend.about*') ? 'is-active' : '' }}">আমাদের কথা</a>
                    <a href="{{ route('frontend.about') }}#history" data-section="history" @click="mobileMenuOpen = false">ইতিহাস ও ঐতিহ্য</a>
                    <a href="{{ route('frontend.about') }}#mission" data-section="mission" @click="mobileMenuOpen = false">লক্ষ্য ও উদ্দেশ্য</a>
                    <a href="{{ route('frontend.about') }}#statistics" data-section="statistics" @click="mobileMenuOpen = false">অর্জন ও পরিসংখ্যান</a>
                    <a href="{{ route('frontend.about') }}#milestones" data-section="milestones" @click="mobileMenuOpen = false">অগ্রযাত্রার মাইলফলক</a>
                    <a href="{{ route('frontend.about') }}#committee" data-section="committee" @click="mobileMenuOpen = false">পরিচালনা পর্ষদ</a>
                    <a href="{{ route('frontend.about') }}#infrastructure" data-section="infrastructure" @click="mobileMenuOpen = false">অবকাঠামো ও ক্যাম্পাস</a>
                </div>
            </div>

            {{-- Staff accordion --}}
            <div>
                <button type="button" @click="mStaff = !mStaff" class="snhs-mobile-toggle {{ request()->routeIs('frontend.teachers*') ? 'is-active' : '' }}">
                    <span>জনবল</span>
                    <svg class="snhs-mobile-caret" :class="mStaff ? 'is-open' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="mStaff" x-cloak class="snhs-mobile-sub" data-mobile-sub="staff">
                    <a href="{{ route('frontend.teachers.index') }}" @click="mobileMenuOpen = false" class="{{ request()->routeIs('frontend.teachers*') ? 'is-active' : '' }}">শিক্ষকমণ্ডলী</a>
                    <a href="{{ route('frontend.teachers.index') }}#staff" @click="mobileMenuOpen = false">কর্মচারী তালিকা</a>
                    <a href="{{ route('frontend.teachers.index') }}#principal" @click="mobileMenuOpen = false">প্রধান শিক্ষক</a>
                    <a href="{{ route('frontend.teachers.index') }}#office" @click="mobileMenuOpen = false">অফিস ও সহায়ক স্টাফ</a>
                </div>
            </div>

            <a href="{{ route('frontend.notices.index') }}" class="snhs-mobile-link">শিক্ষার্থীদের তথ্য</a>

            {{-- Academic accordion --}}
            <div>
                <button type="button" @click="mAcademic = !mAcademic" class="snhs-mobile-toggle {{ request()->routeIs('frontend.academic*') ? 'is-active' : '' }}">
                    <span>একাডেমিক</span>
                    <svg class="snhs-mobile-caret" :class="mAcademic ? 'is-open' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="mAcademic" x-cloak class="snhs-mobile-sub" data-mobile-sub="academic">
                    <a href="{{ route('frontend.academic.classes') }}" @click="mobileMenuOpen = false" class="{{ request()->routeIs('frontend.academic.classes*') ? 'is-active' : '' }}">শ্রেণিসমূহ</a>
                    <a href="{{ route('frontend.academic.routine') }}" @click="mobileMenuOpen = false" class="{{ request()->routeIs('frontend.academic.routine*') ? 'is-active' : '' }}">ক্লাস রুটিন</a>
                    <a href="{{ route('frontend.academic.routine') }}#exams" @click="mobileMenuOpen = false">পরীক্ষার সময়সূচি</a>
                    <a href="{{ route('frontend.notices.index') }}" @click="mobileMenuOpen = false">সিলেবাস ও পাঠ্যপুস্তক</a>
                    <a href="{{ route('frontend.notices.index') }}" @click="mobileMenuOpen = false">ফলাফল ও নম্বরপত্র</a>
                    <a href="{{ route('frontend.notices.index') }}" @click="mobileMenuOpen = false">সহপাঠক্রমিক কার্যক্রম</a>
                </div>
            </div>

            <a href="{{ route('frontend.notices.index') }}" class="snhs-mobile-link {{ request()->routeIs('frontend.notices*') ? 'is-active' : '' }}">নোটিশ বোর্ড</a>
            <a href="{{ route('frontend.news.index') }}" class="snhs-mobile-link {{ request()->routeIs('frontend.news*') ? 'is-active' : '' }}">সংবাদ</a>
            <a href="{{ route('frontend.events.index') }}" class="snhs-mobile-link {{ request()->routeIs('frontend.events*') ? 'is-active' : '' }}">ইভেন্ট</a>
            <a href="{{ route('frontend.home') }}#gallery" class="snhs-mobile-link" @click="mobileMenuOpen = false">ছবির গ্যালারি</a>
            <a href="{{ route('frontend.notices.index') }}" class="snhs-mobile-link">ছুটির দিন</a>
            <a href="{{ route('frontend.contact') }}#enroll" class="snhs-mobile-link bg-amber-400 text-slate-950 font-bold hover:bg-amber-300">ভর্তি আবেদন (অনলাইন)</a>
            <a href="{{ route('frontend.contact') }}" class="snhs-mobile-link {{ request()->routeIs('frontend.contact*') ? 'is-active' : '' }}">যোগাযোগ</a>

            <a href="{{ url('/admin/dashboard') }}" class="snhs-mobile-link snhs-mobile-login">পোর্টাল লগইন</a>
        </div>
    </div>
</header>

{{-- Navigation & scrollspy --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    function getHeaderOffset() {
        var header = document.querySelector('.snhs-header');
        return (header ? header.offsetHeight : 120) + 16;
    }

    function scrollToTarget(targetId, smooth) {
        if (!targetId) return false;
        var el = document.getElementById(targetId) || document.querySelector('[name="' + targetId + '"]');
        if (el) {
            var elTop = el.getBoundingClientRect().top + window.pageYOffset;
            var finalTop = Math.max(0, elTop - getHeaderOffset());
            window.scrollTo({
                top: finalTop,
                behavior: smooth ? 'smooth' : 'auto'
            });
            return true;
        }
        return false;
    }

    // Scroll to hash on page load
    if (window.location.hash) {
        var initialHash = window.location.hash.substring(1);
        setTimeout(function () {
            scrollToTarget(initialHash, true);
            updateActiveSection(initialHash);
        }, 180);
    }

    // Smooth scroll for hash links
    document.addEventListener('click', function (e) {
        var link = e.target.closest('a');
        if (!link) return;

        var href = link.getAttribute('href');
        if (!href || href === '#' || href === '#!') return;

        var hashIndex = href.indexOf('#');
        if (hashIndex === -1) return;

        var pathPart = href.substring(0, hashIndex);
        var hashPart = href.substring(hashIndex + 1);

        var currentPath = window.location.pathname;
        var isCurrentPage = !pathPart || pathPart === currentPath || href.startsWith(window.location.origin + currentPath + '#');

        if (isCurrentPage && hashPart) {
            var targetFound = scrollToTarget(hashPart, true);
            if (targetFound) {
                e.preventDefault();
                history.pushState(null, '', '#' + hashPart);
                updateActiveSection(hashPart);
            }
        }
    });

    // Scrollspy
    var sectionIds = ['overview', 'history', 'mission', 'manifesto', 'statistics', 'milestones', 'committee', 'governance', 'infrastructure', 'facilities', 'gallery'];
    var observedElements = [];
    sectionIds.forEach(function (id) {
        var el = document.getElementById(id);
        if (el) {
            observedElements.push({ id: id, el: el });
        }
    });

    function updateActiveSection(activeId) {
        var canonicalId = activeId;
        if (activeId === 'manifesto') canonicalId = 'mission';
        if (activeId === 'governance') canonicalId = 'committee';
        if (activeId === 'facilities') canonicalId = 'infrastructure';

        // Sync active links
        document.querySelectorAll('[data-dropdown="about"] a, [data-mobile-sub="about"] a').forEach(function (link) {
            var s = link.getAttribute('data-section');
            var h = link.getAttribute('href') || '';
            if (s === canonicalId || h.endsWith('#' + canonicalId) || (canonicalId === 'overview' && !h.includes('#'))) {
                link.classList.add('is-active');
            } else {
                link.classList.remove('is-active');
            }
        });
    }

    if (observedElements.length > 0 && window.location.pathname.includes('/about')) {
        var scrollTimer = null;
        window.addEventListener('scroll', function () {
            if (scrollTimer) return;
            scrollTimer = requestAnimationFrame(function () {
                var currentScroll = window.pageYOffset + getHeaderOffset() + 60;
                var currentActive = 'overview';

                for (var i = 0; i < observedElements.length; i++) {
                    var el = observedElements[i].el;
                    var top = el.getBoundingClientRect().top + window.pageYOffset;
                    if (currentScroll >= top) {
                        currentActive = observedElements[i].id;
                    }
                }
                updateActiveSection(currentActive);
                scrollTimer = null;
            });
        }, { passive: true });
    }
});
</script>
