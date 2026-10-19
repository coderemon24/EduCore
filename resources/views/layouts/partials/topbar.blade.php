{{-- Top Institutional Utility Bar (Deep Green Tone) --}}
<div class="bg-[#003820] text-emerald-100 text-xs border-b border-[#002817]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-col sm:flex-row items-center justify-between gap-2">
        {{-- Left: Institutional Credentials --}}
        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-3.5 gap-y-1 text-[11px] sm:text-xs">
            <div>
                <span class="text-emerald-300">EIIN:</span>
                <span class="font-bold text-white tracking-wide">১২৫৬৯৩</span>
            </div>
            <span class="text-emerald-800 hidden sm:inline">|</span>
            <div>
                <span class="text-emerald-300">স্কুল কোড:</span>
                <span class="font-bold text-white tracking-wide">৮১০৭</span>
            </div>
            <span class="text-emerald-800 hidden sm:inline">|</span>
            <div>
                <span class="text-emerald-300">এমপিও কোড:</span>
                <span class="font-bold text-white tracking-wide">৮১০৭০২১৩০২</span>
            </div>
            <span class="text-emerald-800 hidden md:inline">|</span>
            <div class="hidden md:inline text-emerald-200">
                স্থাপিত: ১৯৬৮ খ্রি.
            </div>
        </div>

        {{-- Right: Contact Helpline & Portal Login --}}
        <div class="flex items-center gap-3 text-[11px] sm:text-xs">
            <div class="hidden lg:flex items-center gap-1.5 text-emerald-100">
                <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <span>হেল্পলাইন: <strong class="text-white">+৮৮০ ১৭১২-৩৪৫৬৭৮</strong></span>
            </div>
            <span class="text-emerald-800 hidden lg:inline">|</span>
            <a href="{{ url('/admin/dashboard') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-[#005030] hover:bg-[#00603a] text-white font-medium border border-emerald-700/60 transition-colors shadow-2xs">
                <svg class="w-3 h-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span>লগইন পোর্টাল</span>
            </a>
        </div>
    </div>
</div>
