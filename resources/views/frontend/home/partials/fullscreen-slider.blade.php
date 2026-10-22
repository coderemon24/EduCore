{{-- Full-Screen Hero Slider Managed by Swiper.js --}}
<section class="w-full relative bg-slate-950 overflow-hidden border-b border-slate-200" aria-label="প্রাতিষ্ঠানিক ফটো স্লাইডার">
    {{-- Swiper Container (Responsive Height: 280px mobile -> 420px tablet -> 550px desktop) --}}
    <div class="swiper fullscreen-hero-swiper relative w-full h-[280px] sm:h-[420px] lg:h-[550px] overflow-hidden select-none">
        <div class="swiper-wrapper">

            {{-- Slide 1: Teachers & Staff (Exact match to reference image) --}}
            <div class="swiper-slide relative w-full h-full">
                <img src="{{ asset('assets/images/banner-teachers.png') }}"
                     alt="শিক্ষক ও কর্মচারী বৃন্দ - সোনাতলা উচ্চ বিদ্যালয়"
                     class="w-full h-full object-cover object-center sm:object-[center_35%]"
                     loading="eager">

                {{-- Left-Top to Bottom-Right Theme Color (#005030) Shadow Overlay --}}
                <div class="fullscreen-slider-theme-shadow"></div>

                {{-- Slide Text Content (Left-Aligned with Entrance Animation) --}}
                <div class="absolute inset-0 flex items-center z-10">
                    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-3xl text-white space-y-1 sm:space-y-3 lg:space-y-4 pr-8 sm:pr-4">
                            <div class="fullscreen-slide-badge">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 sm:px-3 sm:py-1 rounded bg-[#005030]/90 text-emerald-200 text-[11px] sm:text-xs font-bold uppercase tracking-wider border border-emerald-500/40 backdrop-blur-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    আস্থা ও ঐতিহ্যের বিদ্যাপীঠ
                                </span>
                            </div>

                            <h2 class="fullscreen-slide-title text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight drop-shadow-lg text-white">
                                প্রতিটি সন্তানকে গড়ে তুলছি <span class="text-amber-300">সততা, মেধা ও মানবিক মূল্যবোধে</span>
                            </h2>

                            <p class="fullscreen-slide-desc text-sm sm:text-base font-normal leading-relaxed text-slate-100 drop-shadow-md max-w-2xl line-clamp-2 sm:line-clamp-none">
                                শুধু পাঠ্যবই নয়; স্নেহ, সুশিক্ষা ও আধুনিক প্রযুক্তির সমন্বয়ে সোনাতলা উচ্চ বিদ্যালয় নিশ্চিত করে প্রতিটি শিক্ষার্থীর নিরাপদ ও আত্মবিশ্বাসী ভবিষ্যৎ।
                            </p>

                            <div class="pt-2 sm:pt-3">
                                <a href="{{ route('frontend.contact') }}#enroll"
                                   class="inline-flex items-center gap-2 px-4 py-2 sm:px-5 sm:py-2.5 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs sm:text-sm shadow-md transition-all hover:scale-105 active:scale-95">
                                    <span>অনলাইন ভর্তি আবেদন</span>
                                    <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 2: Campus & Academic Environment --}}
            <div class="swiper-slide relative w-full h-full">
                <img src="{{ asset('assets/images/slider-1.jpg') }}"
                     alt="সোনাতলা উচ্চ বিদ্যালয় ক্যাম্পাস"
                     class="w-full h-full object-cover object-center"
                     loading="lazy">

                <div class="fullscreen-slider-theme-shadow"></div>

                <div class="absolute inset-0 flex items-center z-10">
                    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-3xl text-white space-y-1 sm:space-y-3 lg:space-y-4 pr-8 sm:pr-4">
                            <div class="fullscreen-slide-badge">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 sm:px-3 sm:py-1 rounded bg-[#005030]/90 text-emerald-200 text-[11px] sm:text-xs font-bold uppercase tracking-wider border border-emerald-500/40 backdrop-blur-xs">
                                    প্রযুক্তিবান্ধব শিক্ষা ও বিজ্ঞানাগার
                                </span>
                            </div>

                            <h2 class="fullscreen-slide-title text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight drop-shadow-lg text-white">
                                হাতেকলমে বিজ্ঞান ও <span class="text-amber-300">ডিজিটাল দক্ষতায়</span> আলোকিত এক শিক্ষাঙ্গন
                            </h2>

                            <p class="fullscreen-slide-desc text-sm sm:text-base font-normal leading-relaxed text-slate-100 drop-shadow-md max-w-2xl line-clamp-2 sm:line-clamp-none">
                                আধুনিক আইসিটি ল্যাব আর অভিজ্ঞ শিক্ষকমণ্ডলীর নিবিড় পরিচর্যায় প্রতিটি শিক্ষার্থী পায় বাস্তবমুখী শিক্ষা ও স্বপ্ন দেখার প্রেরণা।
                            </p>

                            <div class="pt-2 sm:pt-3">
                                <a href="{{ route('frontend.contact') }}#enroll"
                                   class="inline-flex items-center gap-2 px-4 py-2 sm:px-5 sm:py-2.5 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs sm:text-sm shadow-md transition-all hover:scale-105 active:scale-95">
                                    <span>অনলাইন ভর্তি আবেদন</span>
                                    <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 3: Co-Curricular & Sports --}}
            <div class="swiper-slide relative w-full h-full">
                <img src="{{ asset('assets/images/slider-2.jpg') }}"
                     alt="বার্ষিক ক্রীড়া ও সহ-শিক্ষা"
                     class="w-full h-full object-cover object-center"
                     loading="lazy">

                <div class="fullscreen-slider-theme-shadow"></div>

                <div class="absolute inset-0 flex items-center z-10">
                    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-3xl text-white space-y-1 sm:space-y-3 lg:space-y-4 pr-8 sm:pr-4">
                            <div class="fullscreen-slide-badge">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 sm:px-3 sm:py-1 rounded bg-[#005030]/90 text-emerald-200 text-[11px] sm:text-xs font-bold uppercase tracking-wider border border-emerald-500/40 backdrop-blur-xs">
                                    সুস্থ দেহ ও মানবিক মূল্যবোধ
                                </span>
                            </div>

                            <h2 class="fullscreen-slide-title text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight drop-shadow-lg text-white">
                                সহ-শিক্ষা ও ক্রীড়ায় <span class="text-amber-300">আত্মপ্রকাশের মুক্ত প্রাঙ্গণ</span>
                            </h2>

                            <p class="fullscreen-slide-desc text-sm sm:text-base font-normal leading-relaxed text-slate-100 drop-shadow-md max-w-2xl line-clamp-2 sm:line-clamp-none">
                                নিয়মিত খেলাধুলা, বিতর্ক ও সাংস্কৃতিক কর্মকাণ্ডে বিকশিত হয় শিশুর নেতৃত্বগুণ, সহমর্মিতা আর দলগত শৃঙ্খলা।
                            </p>

                            <div class="pt-2 sm:pt-3">
                                <a href="{{ route('frontend.contact') }}#enroll"
                                   class="inline-flex items-center gap-2 px-4 py-2 sm:px-5 sm:py-2.5 rounded-full bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs sm:text-sm shadow-md transition-all hover:scale-105 active:scale-95">
                                    <span>অনলাইন ভর্তি আবেদন</span>
                                    <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Left & Right Navigation Chevrons --}}
        <button type="button"
                class="fullscreen-swiper-prev absolute left-2 sm:left-4 lg:left-6 top-1/2 -translate-y-1/2 z-20 w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 rounded-full bg-white/20 hover:bg-white/40 text-white backdrop-blur-md border border-white/30 flex items-center justify-center transition-all shadow-lg hover:scale-105 active:scale-95 focus:outline-none cursor-pointer"
                aria-label="Previous Slide">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <button type="button"
                class="fullscreen-swiper-next absolute right-2 sm:right-4 lg:right-6 top-1/2 -translate-y-1/2 z-20 w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 rounded-full bg-white/20 hover:bg-white/40 text-white backdrop-blur-md border border-white/30 flex items-center justify-center transition-all shadow-lg hover:scale-105 active:scale-95 focus:outline-none cursor-pointer"
                aria-label="Next Slide">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        {{-- Centered Bottom Pagination Bullets --}}
        <div class="fullscreen-swiper-pagination absolute bottom-2 sm:bottom-4 lg:bottom-6 left-1/2 -translate-x-1/2 z-20 flex items-center justify-center gap-2"></div>
    </div>
</section>
