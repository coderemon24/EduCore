{{-- Institutional Header & Minimal Navigation Bar --}}
<header x-data="{ mobileMenuOpen: false, aboutOpen: false, academicOpen: false }" class="bg-white border-b border-slate-200 sticky top-0 z-40">
    {{-- Institutional Brand Header --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
        <div class="flex items-center justify-between gap-4">
            {{-- Brand Logo & Formal Titles --}}
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

            {{-- Emergency Contact Quick Badge & Login Portal (Desktop) --}}
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
                <div class="h-8 w-px bg-slate-200 mx-0.5"></div>
                <a href="{{ url('/admin/dashboard') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#005030] hover:bg-[#003d25] text-white text-xs font-semibold shadow-2xs transition-colors">
                    <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>লগইন পোর্টাল</span>
                </a>
            </div>

            {{-- Mobile Menu Trigger --}}
            <div class="flex items-center md:hidden">
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

    {{-- Institutional Navigation Bar (Signature Forest Emerald #005030) --}}
    <nav class="bg-[#005030] text-white hidden md:block border-t border-[#004228] shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center text-sm font-semibold divide-x divide-[#004228]">
                {{-- Home --}}
                <a href="{{ route('frontend.home') }}"
                   class="px-4 py-2.5 transition-colors hover:bg-[#004027] {{ request()->routeIs('frontend.home') ? 'bg-[#003820] text-amber-300 font-bold border-b-2 border-amber-400' : 'text-emerald-50' }}">
                    প্রচ্ছদ
                </a>

                {{-- About Us Dropdown --}}
                <div class="relative" @click.away="aboutOpen = false">
                    <button type="button"
                            @click="aboutOpen = !aboutOpen"
                            class="px-4 py-2.5 inline-flex items-center gap-1 hover:bg-[#004027] transition-colors {{ request()->routeIs('frontend.about*') ? 'bg-[#003820] text-amber-300 font-bold border-b-2 border-amber-400' : 'text-emerald-50' }}">
                        <span>আমাদের কথা</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="aboutOpen"
                         x-cloak
                         class="absolute left-0 mt-0 w-48 bg-white text-slate-800 border border-slate-200 shadow-xl py-1 z-50 text-xs font-medium rounded-b">
                        <a href="{{ route('frontend.about') }}" class="block px-4 py-2 hover:bg-emerald-50 hover:text-[#005030] border-b border-slate-100">সংক্ষিপ্ত ইতিহাস</a>
                        <a href="{{ route('frontend.about') }}#mission" class="block px-4 py-2 hover:bg-emerald-50 hover:text-[#005030] border-b border-slate-100">লক্ষ্য ও উদ্দেশ্য</a>
                        <a href="{{ route('frontend.about') }}#committee" class="block px-4 py-2 hover:bg-emerald-50 hover:text-[#005030]">পরিচালনা পর্ষদ</a>
                    </div>
                </div>

                {{-- Academic Dropdown --}}
                <div class="relative" @click.away="academicOpen = false">
                    <button type="button"
                            @click="academicOpen = !academicOpen"
                            class="px-4 py-2.5 inline-flex items-center gap-1 hover:bg-[#004027] transition-colors {{ request()->routeIs('frontend.academic*') ? 'bg-[#003820] text-amber-300 font-bold border-b-2 border-amber-400' : 'text-emerald-50' }}">
                        <span>একাডেমিক</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="academicOpen"
                         x-cloak
                         class="absolute left-0 mt-0 w-48 bg-white text-slate-800 border border-slate-200 shadow-xl py-1 z-50 text-xs font-medium rounded-b">
                        <a href="{{ route('frontend.academic.routine') }}" class="block px-4 py-2 hover:bg-emerald-50 hover:text-[#005030] border-b border-slate-100">ক্লাস রুটিন</a>
                        <a href="{{ route('frontend.academic.routine') }}" class="block px-4 py-2 hover:bg-emerald-50 hover:text-[#005030] border-b border-slate-100">পরীক্ষার সময়সূচি</a>
                        <a href="{{ route('frontend.notices.index') }}" class="block px-4 py-2 hover:bg-emerald-50 hover:text-[#005030]">সিলেবাস ও বইয়ের তালিকা</a>
                    </div>
                </div>

                {{-- Teachers & Staff --}}
                <a href="{{ route('frontend.teachers.index') }}"
                   class="px-4 py-2.5 transition-colors hover:bg-[#004027] {{ request()->routeIs('frontend.teachers*') ? 'bg-[#003820] text-amber-300 font-bold border-b-2 border-amber-400' : 'text-emerald-50' }}">
                    শিক্ষক ও কর্মচারী
                </a>

                {{-- Notice Board --}}
                <a href="{{ route('frontend.notices.index') }}"
                   class="px-4 py-2.5 transition-colors hover:bg-[#004027] {{ request()->routeIs('frontend.notices*') ? 'bg-[#003820] text-amber-300 font-bold border-b-2 border-amber-400' : 'text-emerald-50' }}">
                    নোটিশ বোর্ড
                </a>

                {{-- Contact --}}
                <a href="{{ route('frontend.contact') }}"
                   class="px-4 py-2.5 transition-colors hover:bg-[#004027] {{ request()->routeIs('frontend.contact*') ? 'bg-[#003820] text-amber-300 font-bold border-b-2 border-amber-400' : 'text-emerald-50' }}">
                    যোগাযোগ
                </a>
            </div>
        </div>
    </nav>

    {{-- Mobile Drawer Navigation --}}
    <div x-show="mobileMenuOpen"
         x-cloak
         class="md:hidden fixed inset-0 z-50 bg-black/60"
         @click="mobileMenuOpen = false"></div>

    <div x-show="mobileMenuOpen"
         x-cloak
         class="md:hidden fixed inset-y-0 left-0 w-72 bg-white z-50 shadow-2xl overflow-y-auto">
        <div class="p-4 bg-[#005030] text-white flex items-center justify-between">
            <span class="font-bold text-sm">মেনু নির্বাচন করুন</span>
            <button type="button" @click="mobileMenuOpen = false" class="text-white p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="py-2 text-sm font-semibold divide-y divide-slate-100 text-slate-800">
            <a href="{{ route('frontend.home') }}" class="block px-4 py-2.5 hover:bg-emerald-50 hover:text-[#005030]">প্রচ্ছদ</a>
            <a href="{{ route('frontend.about') }}" class="block px-4 py-2.5 hover:bg-emerald-50 hover:text-[#005030]">আমাদের কথা</a>
            <a href="{{ route('frontend.academic.routine') }}" class="block px-4 py-2.5 hover:bg-emerald-50 hover:text-[#005030]">ক্লাস ও পরীক্ষা রুটিন</a>
            <a href="{{ route('frontend.teachers.index') }}" class="block px-4 py-2.5 hover:bg-emerald-50 hover:text-[#005030]">শিক্ষক ও কর্মচারীবৃন্দ</a>
            <a href="{{ route('frontend.notices.index') }}" class="block px-4 py-2.5 hover:bg-emerald-50 hover:text-[#005030]">নোটিশ বোর্ড</a>
            <a href="{{ route('frontend.contact') }}" class="block px-4 py-2.5 hover:bg-emerald-50 hover:text-[#005030]">যোগাযোগ</a>
            <a href="{{ url('/admin/dashboard') }}" class="block px-4 py-2.5 bg-[#005030] text-white font-bold">পোর্টাল লগইন</a>
        </div>
    </div>
</header>
