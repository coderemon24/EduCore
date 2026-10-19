{{-- Running Breaking Notice Ticker (Minimal Slate Style) --}}
<div class="bg-white border-b border-slate-200 text-xs py-2 overflow-hidden shadow-2xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-3">
        {{-- Ticker Tag --}}
        <div class="flex-shrink-0 flex items-center gap-1.5 px-2.5 py-0.5 rounded bg-[#005030] text-white font-bold text-[11px] uppercase tracking-wide">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
            <span>জরুরি নোটিশ:</span>
        </div>

        {{-- Marquee Ticker Track --}}
        <div class="relative overflow-hidden flex-1 h-5">
            <div class="animate-ticker space-x-10 text-slate-700 font-medium text-xs">
                <a href="{{ route('frontend.notices.index') }}" class="hover:text-[#005030] hover:underline">
                    • ২০২৬ শিক্ষাবর্ষে ৬ষ্ঠ থেকে ৯ম শ্রেণিতে ভর্তি আবেদন চলছে। ফরম সংগ্রহ ও জমাদানের শেষ তারিখ ৩০ নভেম্বর।
                </a>
                <a href="{{ route('frontend.notices.index') }}" class="hover:text-[#005030] hover:underline">
                    • আসন্ন বার্ষিক পরীক্ষা ২০২৬ এর সংশোধিত সময়সূচি ও প্রবেশপত্র সংক্রান্ত জরুরি বিজ্ঞপ্তি।
                </a>
                <a href="{{ route('frontend.notices.index') }}" class="hover:text-slate-900 hover:underline">
                    • সকল শ্রেণির শিক্ষার্থীদের ডিজিটাল ইউনিক আইডি (UID) তথ্য হালনাগাদ সংক্রান্ত বিজ্ঞপ্তি।
                </a>
                <a href="{{ route('frontend.notices.index') }}" class="hover:text-slate-900 hover:underline">
                    • জাতীয় শিক্ষা সপ্তাহ উপলক্ষে আন্তঃস্কুল রচনা, বিতর্ক ও সাংস্কৃতিক প্রতিযোগিতার তারিখ ঘোষণা।
                </a>
                {{-- Loop duplication --}}
                <a href="{{ route('frontend.notices.index') }}" class="hover:text-slate-900 hover:underline">
                    • ২০২৬ শিক্ষাবর্ষে ৬ষ্ঠ থেকে ৯ম শ্রেণিতে ভর্তি আবেদন চলছে। ফরম সংগ্রহ ও জমাদানের শেষ তারিখ ৩০ নভেম্বর।
                </a>
                <a href="{{ route('frontend.notices.index') }}" class="hover:text-slate-900 hover:underline">
                    • আসন্ন বার্ষিক পরীক্ষা ২০২৬ এর সংশোধিত সময়সূচি ও প্রবেশপত্র সংক্রান্ত জরুরি বিজ্ঞপ্তি।
                </a>
            </div>
        </div>
    </div>
</div>
