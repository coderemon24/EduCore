{{-- Footer --}}
<footer class="pt-12 site-footer bg-gradient-to-b from-[#031d13] via-[#02130c] to-[#010b07] text-slate-300 border-t-2 border-[#005030] mt-12 sm:mt-16 selection:bg-[#005030] selection:text-white relative overflow-hidden" aria-label="প্রাতিষ্ঠানিক ফুটার">
    <div class="absolute top-0 right-0 -mt-16 -mr-16 w-80 h-80 rounded-full bg-emerald-500/5 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-80 h-80 rounded-full bg-emerald-500/5 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 sm:pt-12 pb-6 sm:pb-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10">
            {{-- About & contact --}}
            <div class="lg:col-span-5 space-y-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-full bg-white p-1 border-2 border-emerald-500/40 shadow-sm flex items-center justify-center shrink-0">
                        <img src="{{ asset('assets/images/logo.png') }}"
                             alt="সোনাতলা উচ্চ বিদ্যালয়"
                             class="max-w-full max-h-full object-contain"
                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=SHS&background=005030&color=fff&size=128';">
                    </div>
                    <div>
                        <h2 class="text-white font-extrabold text-base sm:text-lg leading-tight tracking-tight">
                            সোনাতলা উচ্চ বিদ্যালয়
                        </h2>
                        <div class="text-xs font-semibold text-emerald-300 uppercase tracking-wider mt-0.5">
                            Sonatala High School
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-white/5 border border-white/10 text-emerald-300 text-[11px] font-semibold font-mono">
                        EIIN: ১২৫৬৯৩
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-white/5 border border-white/10 text-emerald-300 text-[11px] font-semibold font-mono">
                        স্কুল কোড: ৮১০৭
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-[11px] font-semibold">
                        স্থাপিত: ১৯৬৮ খ্রি.
                    </span>
                </div>

                <p class="text-xs sm:text-sm leading-relaxed text-slate-300/90">
                    শিক্ষাই জাতির মেরুদণ্ড। একটি মানসম্মত, আধুনিক ও প্রযুক্তিবান্ধব মানবিক সমাজ গঠনে পাবনার সাঁথিয়ার ৫৭ বছরের সমৃদ্ধ শিক্ষাধারা নিয়ে সোনাতলা উচ্চ বিদ্যালয় সদা প্রতিশ্রুতিবদ্ধ।
                </p>

                <div class="space-y-2.5 pt-2 text-xs sm:text-sm text-slate-300">
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-location-dot text-emerald-400 mt-1 shrink-0 text-xs"></i>
                        <span>বেড়া-সোনাতলা, সাঁথিয়া, পাবনা, রাজশাহী বিভাগ</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-phone text-emerald-400 shrink-0 text-xs"></i>
                        <a href="tel:+8801712345678" class="hover:text-white transition-colors font-medium">
                            +৮৮০ ১৭১২-৩৪৫৬৭৮
                        </a>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-envelope text-emerald-400 shrink-0 text-xs"></i>
                        <a href="mailto:info@sonatalahighschool.edu.bd" class="hover:text-white transition-colors font-medium">
                            info@sonatalahighschool.edu.bd
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="lg:col-span-2 space-y-4">
                <h3 class="text-white font-bold text-sm sm:text-[15px] tracking-wide pb-2 border-b border-emerald-500/20 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>দ্রুত লিংক</span>
                </h3>
                <ul class="space-y-2.5 text-xs sm:text-[13px]">
                    <li>
                        <a href="{{ route('frontend.home') }}" class="group flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-emerald-400/70 group-hover:text-emerald-300 group-hover:translate-x-1 transition-transform"></i>
                            <span>প্রচ্ছদ (Home)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.about') }}" class="group flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-emerald-400/70 group-hover:text-emerald-300 group-hover:translate-x-1 transition-transform"></i>
                            <span>বিদ্যালয় পরিচিতি</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.academic.routine') }}" class="group flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-emerald-400/70 group-hover:text-emerald-300 group-hover:translate-x-1 transition-transform"></i>
                            <span>ক্লাস ও পরীক্ষা রুটিন</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.teachers.index') }}" class="group flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-emerald-400/70 group-hover:text-emerald-300 group-hover:translate-x-1 transition-transform"></i>
                            <span>শিক্ষক ও কর্মচারীবৃন্দ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.notices.index') }}" class="group flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-emerald-400/70 group-hover:text-emerald-300 group-hover:translate-x-1 transition-transform"></i>
                            <span>নোটিশ বোর্ড</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.events.index') }}" class="group flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-emerald-400/70 group-hover:text-emerald-300 group-hover:translate-x-1 transition-transform"></i>
                            <span>ইভেন্ট ও ক্যালেন্ডার</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.contact') }}" class="group flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-emerald-400/70 group-hover:text-emerald-300 group-hover:translate-x-1 transition-transform"></i>
                            <span>যোগাযোগ ও অবস্থান</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Services --}}
            <div class="lg:col-span-2 space-y-4">
                <h3 class="text-white font-bold text-sm sm:text-[15px] tracking-wide pb-2 border-b border-emerald-500/20 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>সেবাসমূহ</span>
                </h3>
                <ul class="space-y-2.5 text-xs sm:text-[13px]">
                    <li>
                        <a href="{{ route('frontend.contact') }}" class="group flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-emerald-400/70 group-hover:text-emerald-300 group-hover:translate-x-1 transition-transform"></i>
                            <span>অনলাইন ভর্তি আবেদন</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.notices.index') }}" class="group flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-emerald-400/70 group-hover:text-emerald-300 group-hover:translate-x-1 transition-transform"></i>
                            <span>ফলাফল ও ট্রান্সক্রিপ্ট</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.academic.routine') }}" class="group flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-emerald-400/70 group-hover:text-emerald-300 group-hover:translate-x-1 transition-transform"></i>
                            <span>সিলেবাস ও পরীক্ষা সূচি</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/dashboard') }}" class="group flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-emerald-400/70 group-hover:text-emerald-300 group-hover:translate-x-1 transition-transform"></i>
                            <span>শিক্ষক ও অভিভাবক পোর্টাল</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.contact') }}" class="group flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <i class="fa-solid fa-chevron-right text-[10px] text-emerald-400/70 group-hover:text-emerald-300 group-hover:translate-x-1 transition-transform"></i>
                            <span>অভিযোগ ও মতামত বক্স</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Government portals --}}
            <div class="lg:col-span-3 space-y-4">
                <h3 class="text-white font-bold text-sm sm:text-[15px] tracking-wide pb-2 border-b border-emerald-500/20 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    <span>সরকারি পোর্টাল ও সহায়তা</span>
                </h3>
                <ul class="space-y-2.5 text-xs sm:text-[13px]">
                    <li>
                        <a href="https://bangladesh.gov.bd" target="_blank" rel="noopener noreferrer" class="group flex items-center justify-between text-slate-300 hover:text-white transition-colors">
                            <span>জাতীয় তথ্য বাতায়ন</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-500 group-hover:text-emerald-400 transition-colors"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://moedu.gov.bd" target="_blank" rel="noopener noreferrer" class="group flex items-center justify-between text-slate-300 hover:text-white transition-colors">
                            <span>শিক্ষা মন্ত্রণালয় (MoEdu)</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-500 group-hover:text-emerald-400 transition-colors"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://dshe.gov.bd" target="_blank" rel="noopener noreferrer" class="group flex items-center justify-between text-slate-300 hover:text-white transition-colors">
                            <span>মাধ্যমিক ও উচ্চশিক্ষা অধিদপ্তর</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-500 group-hover:text-emerald-400 transition-colors"></i>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.rajshahieducationboard.gov.bd" target="_blank" rel="noopener noreferrer" class="group flex items-center justify-between text-slate-300 hover:text-white transition-colors">
                            <span>রাজশাহী শিক্ষা বোর্ড</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-500 group-hover:text-emerald-400 transition-colors"></i>
                        </a>
                    </li>
                </ul>

                {{-- WhatsApp helpline --}}
                <a href="https://wa.me/8801712345678" target="_blank" rel="noopener noreferrer"
                   class="group block mt-4 p-3.5 rounded-xl bg-white/5 hover:bg-emerald-950/60 border border-emerald-500/25 hover:border-emerald-400/60 transition-all duration-200 shadow-xs hover:shadow-md hover:shadow-emerald-950/40 cursor-pointer"
                   aria-label="সরাসরি হোয়াটসঅ্যাপে কথা বলুন: ০১৭১২-৩৪৫৬৭৮">
                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <span class="text-xs font-semibold text-emerald-200/90 group-hover:text-emerald-100 transition-colors">
                            ভর্তি ও তথ্যের জন্য সরাসরি কথা বলুন
                        </span>
                        <span class="relative flex h-2 w-2 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs sm:text-sm font-bold text-white group-hover:text-emerald-300 transition-colors">
                        <span class="inline-flex items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-emerald-400 text-base group-hover:scale-110 transition-transform"></i>
                            <span>০১৭১২-৩৪৫৬৭৮</span>
                        </span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-emerald-400/60 group-hover:text-emerald-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-all"></i>
                    </div>
                </a>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="mt-8 sm:mt-10 pt-4 sm:pt-5 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
            <p class="text-center sm:text-left">
                © {{ date('Y') }} সোনাতলা উচ্চ বিদ্যালয়। সর্বস্বত্ব সংরক্ষিত।
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3 text-[11px] sm:text-xs text-slate-400">
                <span class="inline-flex items-center gap-1.5 text-emerald-300/90 font-medium">
                    <i class="fa-solid fa-building-columns text-[10px]"></i>
                    রাজশাহী শিক্ষা বোর্ড অধিভুক্ত
                </span>
                <span class="text-slate-600">•</span>
                <span>সাঁথিয়া, পাবনা</span>
                <span class="text-slate-600">•</span>
                <span class="text-slate-400 font-mono">EduCore™ School Portal</span>
            </div>
        </div>
    </div>
</footer>
