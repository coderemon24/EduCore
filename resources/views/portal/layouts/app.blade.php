<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    @include('layouts.partials.head')
    <title>@yield('title', 'প্রাতিষ্ঠানিক পোর্টাল | সোনাতলা উচ্চ বিদ্যালয়')</title>
</head>
<body class="min-h-full flex flex-col bg-[#f8fafc] text-slate-800 antialiased selection:bg-[#005030] selection:text-white"
      x-data="{ mobileDrawerOpen: false, userMenuOpen: false }">

    @php
        $currentSchool = \App\Services\CurrentSchoolContext::get();
        $schoolName = $currentSchool->name ?? 'সোনাতলা উচ্চ বিদ্যালয়';
        $eiin = $currentSchool->eiin ?? '১২৫৬৯৩';
        $mpoCode = $currentSchool->mpo_code ?? '৮১০৭০২১৩০২';
        $user = auth()->user();
        $userRole = strtolower($user?->role ?? ($role ?? 'student'));
        
        $roleBadges = [
            'student'  => ['label' => 'শিক্ষার্থী প্যানেল', 'bg' => 'bg-emerald-50', 'text' => 'text-[#005030]', 'border' => 'border-emerald-200', 'icon' => 'fa-graduation-cap'],
            'guardian' => ['label' => 'অভিভাবক প্যানেল', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'icon' => 'fa-users'],
            'teacher'  => ['label' => 'শিক্ষক প্যানেল', 'bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'border' => 'border-sky-200', 'icon' => 'fa-chalkboard-user'],
            'staff'    => ['label' => 'কর্মকর্তা ও কর্মচারী প্যানেল', 'bg' => 'bg-amber-50', 'text' => 'text-amber-800', 'border' => 'border-amber-200', 'icon' => 'fa-id-card-clip'],
        ];
        $currentBadge = $roleBadges[$userRole] ?? $roleBadges['student'];
    @endphp

    {{-- Top Utility Bar (Neutral Ground & Credentials) --}}
    <div class="bg-slate-900 text-slate-300 text-xs py-2 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-[#005030] text-emerald-200 text-[11px] font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    ডিজিটাল ক্যাম্পাস
                </span>
                <span class="text-slate-400 hidden sm:inline-block">EIIN: <strong class="text-slate-200">{{ $eiin }}</strong></span>
                <span class="text-slate-600 hidden sm:inline-block">•</span>
                <span class="text-slate-400 hidden md:inline-block">MPO: <strong class="text-slate-200">{{ $mpoCode }}</strong></span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-slate-400 text-[11px] hidden sm:inline-block">
                    <i class="fa-regular fa-calendar-check mr-1 text-emerald-400"></i>
                    {{ \Carbon\Carbon::now()->locale('bn')->isoFormat('dddd, D MMMM YYYY') }}
                </span>
                <a href="{{ route('frontend.home') }}"
                   class="inline-flex items-center gap-1 text-slate-300 hover:text-white transition-colors text-xs font-medium">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>মূল ওয়েবসাইট</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Sticky Institutional Portal Header --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18 sm:h-20">
                
                {{-- School Brand & Emblem --}}
                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="{{ Route::has('frontend.portal.dashboard') ? route('frontend.portal.dashboard') : url('/portal/dashboard') }}" 
                       class="flex items-center gap-3 group">
                        <div class="w-11 h-11 sm:w-13 sm:h-13 rounded-full border border-slate-200 p-0.5 bg-white flex items-center justify-center shadow-xs group-hover:border-[#005030] transition-colors shrink-0">
                            <img src="{{ asset('assets/images/logo.png') }}"
                                 alt="{{ $schoolName }}"
                                 class="max-w-full max-h-full object-contain"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=SHS&background=005030&color=fff&size=128';">
                        </div>
                        <div>
                            <div class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-[#005030] transition-colors tracking-tight leading-tight">
                                {{ $schoolName }}
                            </div>
                            <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1.5 mt-0.5">
                                <span>Institutional Portal</span>
                                <span class="text-slate-300">•</span>
                                <span class="text-[#005030] font-bold">সাঁথিয়া, পাবনা</span>
                            </div>
                        </div>
                    </a>

                    {{-- Role Badge --}}
                    <div class="hidden lg:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border shadow-2xs {{ $currentBadge['bg'] }} {{ $currentBadge['text'] }} {{ $currentBadge['border'] }} ml-2">
                        <i class="fa-solid {{ $currentBadge['icon'] }}"></i>
                        <span>{{ $currentBadge['label'] }}</span>
                    </div>
                </div>

                {{-- User Profile & Actions --}}
                <div class="flex items-center gap-2 sm:gap-4">
                    
                    {{-- Notice Alert Shortcut --}}
                    <a href="{{ route('frontend.notices.index') }}"
                       class="relative p-2 text-slate-600 hover:text-[#005030] hover:bg-slate-100 rounded-lg transition-colors"
                       title="প্রাতিষ্ঠানিক নোটিশ"
                       aria-label="নোটিশ সমূহ">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-rose-500 ring-2 ring-white"></span>
                    </a>

                    {{-- User Dropdown --}}
                    <div class="relative" @click.away="userMenuOpen = false">
                        <button type="button"
                                @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#005030] transition-colors"
                                :aria-expanded="userMenuOpen.toString()">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-emerald-100 border border-emerald-300 text-[#005030] font-bold flex items-center justify-center text-sm shadow-2xs overflow-hidden shrink-0">
                                @if(!empty($user?->avatar_url))
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <span>{{ mb_substr($user->name ?? 'ব্যবহারকারী', 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="text-left hidden md:block">
                                <div class="text-xs font-bold text-slate-900 leading-tight truncate max-w-[140px]">
                                    {{ $user->name ?? 'ডেমো ব্যবহারকারী' }}
                                </div>
                                <div class="text-[11px] text-slate-500 font-medium">
                                    {{ $currentBadge['label'] }}
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-xs ml-0.5 transition-transform duration-200"
                               :class="userMenuOpen ? 'rotate-180' : ''"></i>
                        </button>

                        {{-- Dropdown Card --}}
                        <div x-show="userMenuOpen"
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50">
                            
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-900">{{ $user->name ?? 'ডেমো ব্যবহারকারী' }}</p>
                                <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $user->email ?? 'portal.user@educore.edu' }}</p>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 mt-2 rounded-full {{ $currentBadge['bg'] }} {{ $currentBadge['text'] }}">
                                    <i class="fa-solid {{ $currentBadge['icon'] }} text-[9px]"></i>
                                    {{ $currentBadge['label'] }}
                                </span>
                            </div>

                            <div class="py-1">
                                <a href="{{ Route::has('frontend.portal.dashboard') ? route('frontend.portal.dashboard') : url('/portal/dashboard') }}"
                                   class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 hover:text-[#005030] font-medium transition-colors">
                                    <i class="fa-solid fa-table-columns text-slate-400 w-4"></i>
                                    <span>আমার ড্যাশবোর্ড</span>
                                </a>
                                <a href="{{ route('frontend.academic.routine') }}"
                                   class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 hover:text-[#005030] font-medium transition-colors">
                                    <i class="fa-regular fa-clock text-slate-400 w-4"></i>
                                    <span>একাডেমিক রুটিন</span>
                                </a>
                                <a href="{{ route('frontend.notices.index') }}"
                                   class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 hover:text-[#005030] font-medium transition-colors">
                                    <i class="fa-regular fa-newspaper text-slate-400 w-4"></i>
                                    <span>নোটিশ ও সার্কুলার</span>
                                </a>
                                <a href="{{ route('frontend.home') }}"
                                   class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 hover:text-[#005030] font-medium transition-colors">
                                    <i class="fa-solid fa-globe text-slate-400 w-4"></i>
                                    <span>পাবলিক ওয়েবসাইট</span>
                                </a>
                            </div>

                            <div class="border-t border-slate-100 pt-1">
                                <form method="POST" action="{{ Route::has('frontend.portal.logout') ? route('frontend.portal.logout') : url('/portal/logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-semibold transition-colors">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-4"></i>
                                        <span>লগআউট করুন</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile Hamburger Button --}}
                    <button type="button"
                            @click="mobileDrawerOpen = !mobileDrawerOpen"
                            class="p-2 rounded-lg text-slate-700 hover:bg-slate-100 focus:outline-none lg:hidden"
                            aria-label="Toggle Portal Drawer">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>

            </div>
        </div>
    </header>

    {{-- Breadcrumb & Academic Status Bar --}}
    <div class="bg-white border-b border-slate-200/80 py-2.5 text-xs text-slate-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
                <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-house text-slate-400 text-xs"></i>
                    <span>প্রচ্ছদ</span>
                </a>
                <span class="text-slate-300">/</span>
                <a href="{{ Route::has('frontend.portal.dashboard') ? route('frontend.portal.dashboard') : url('/portal/dashboard') }}" class="hover:text-[#005030] transition-colors">
                    <span>পোর্টাল</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-900 font-bold">@yield('portal_title', 'ড্যাশবোর্ড')</span>
            </nav>
            <div class="flex items-center gap-2.5 text-[11px] font-semibold text-slate-500">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#005030] border border-emerald-200">
                    <i class="fa-solid fa-circle-check text-[9px]"></i>
                    ২০২৬ শিক্ষাবর্ষ (চলমান)
                </span>
                <span class="text-slate-300">•</span>
                <span>সেশন: জানুয়ারি - ডিসেম্বর</span>
            </div>
        </div>
    </div>

    {{-- Flash Notifications --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] text-xs font-semibold flex items-center justify-between shadow-2xs mb-4">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center justify-between shadow-2xs mb-4">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-rose-700 hover:text-rose-900">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif
    </div>

    {{-- Mobile Drawer Navigation Canvas --}}
    <div x-show="mobileDrawerOpen"
         x-cloak
         class="fixed inset-0 z-50 lg:hidden"
         role="dialog"
         aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="mobileDrawerOpen = false"></div>

        <div class="fixed inset-y-0 right-0 w-full max-w-xs bg-white shadow-2xl p-6 flex flex-col justify-between overflow-y-auto">
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('assets/images/logo.png') }}"
                             alt="{{ $schoolName }}"
                             class="w-9 h-9 object-contain">
                        <div>
                            <div class="text-xs font-bold text-slate-900">{{ $schoolName }}</div>
                            <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full {{ $currentBadge['bg'] }} {{ $currentBadge['text'] }}">
                                {{ $currentBadge['label'] }}
                            </span>
                        </div>
                    </div>
                    <button type="button"
                            @click="mobileDrawerOpen = false"
                            class="p-2 text-slate-500 hover:text-slate-800">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                {{-- Mobile Navigation Links --}}
                <div class="space-y-1">
                    <a href="{{ Route::has('frontend.portal.dashboard') ? route('frontend.portal.dashboard') : url('/portal/dashboard') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-800 hover:bg-emerald-50 hover:text-[#005030] transition-colors">
                        <i class="fa-solid fa-gauge-high text-slate-400 w-4"></i>
                        <span>ড্যাশবোর্ড ওভারভিউ</span>
                    </a>
                    <a href="{{ route('frontend.academic.routine') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-800 hover:bg-emerald-50 hover:text-[#005030] transition-colors">
                        <i class="fa-regular fa-clock text-slate-400 w-4"></i>
                        <span>ক্লাস রুটিন ও সময়সূচি</span>
                    </a>
                    <a href="{{ route('frontend.academic.results') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-800 hover:bg-emerald-50 hover:text-[#005030] transition-colors">
                        <i class="fa-solid fa-award text-slate-400 w-4"></i>
                        <span>পরীক্ষার ফলাফল ও মার্কশিট</span>
                    </a>
                    <a href="{{ route('frontend.notices.index') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-800 hover:bg-emerald-50 hover:text-[#005030] transition-colors">
                        <i class="fa-regular fa-bell text-slate-400 w-4"></i>
                        <span>নোটিশ বোর্ড</span>
                    </a>
                    <a href="{{ route('frontend.academic.holidays') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-800 hover:bg-emerald-50 hover:text-[#005030] transition-colors">
                        <i class="fa-regular fa-calendar-days text-slate-400 w-4"></i>
                        <span>ছুটির তালিকা ও ক্যালেন্ডার</span>
                    </a>
                    <a href="{{ route('frontend.teachers.index') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-800 hover:bg-emerald-50 hover:text-[#005030] transition-colors">
                        <i class="fa-solid fa-chalkboard-user text-slate-400 w-4"></i>
                        <span>শিক্ষক ও স্টাফ ডিরেক্টরি</span>
                    </a>
                    <a href="{{ route('frontend.home') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-800 hover:bg-emerald-50 hover:text-[#005030] transition-colors">
                        <i class="fa-solid fa-arrow-up-right-from-square text-slate-400 w-4"></i>
                        <span>মূল পাবলিক ওয়েবসাইট</span>
                    </a>
                </div>
            </div>

            {{-- Drawer Footer Logout --}}
            <div class="pt-4 border-t border-slate-100">
                <form method="POST" action="{{ Route::has('frontend.portal.logout') ? route('frontend.portal.logout') : url('/portal/logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-rose-50 text-rose-600 font-bold text-xs hover:bg-rose-100 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>লগআউট করুন</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Main Portal Content Canvas --}}
    <main class="flex-1 py-6 sm:py-8">
        @yield('portal_content')
    </main>

    {{-- Minimalist Institutional Portal Footer --}}
    <footer class="bg-white border-t border-slate-200 py-6 mt-12 text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <div>
                <span class="font-bold text-slate-800">{{ $schoolName }}</span> • সমন্বিত ডিজিটাল শিক্ষার্থী ও অভিভাবক সেবা পোর্টাল
                <div class="text-[11px] text-slate-400 mt-0.5">কপিরাইট © {{ date('Y') }} সর্বস্বত্ব সংরক্ষিত।</div>
            </div>
            <div class="flex items-center gap-4 text-xs font-medium">
                <a href="{{ route('frontend.contact') }}" class="hover:text-[#005030] transition-colors">আইটি সাপোর্ট ও হেল্পডেস্ক</a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('frontend.academic.routine') }}" class="hover:text-[#005030] transition-colors">একাডেমিক ক্যালেন্ডার</a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('frontend.about') }}" class="hover:text-[#005030] transition-colors">বিদ্যালয় পরিচিতি</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
