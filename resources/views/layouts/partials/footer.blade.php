{{-- Institutional Footer (Black Minimalist Tone) --}}
<footer class="bg-black text-slate-400 text-xs border-t border-zinc-800 mt-12 selection:bg-zinc-800 selection:text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Col 1: Institutional Identity --}}
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-zinc-900 border border-zinc-750 text-white flex items-center justify-center font-bold text-base shadow-xs">
                        SHS
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-sm leading-tight">সোনাতলা উচ্চ বিদ্যালয়</h2>
                        <span class="text-[11px] text-zinc-400">EIIN: ১২৫৬৯৩ | কোড: ৮১০৭</span>
                    </div>
                </div>
                <p class="text-[11px] leading-relaxed text-slate-400">
                    শিক্ষাই জাতির মেরুদণ্ড। একটি মানসম্মত, আধুনিক ও প্রযুক্তিবান্ধব মানবিক সমাজ গঠনে সোনাতলা উচ্চ বিদ্যালয় সদা প্রতিশ্রুতিবদ্ধ।
                </p>
                <div class="text-[11px] text-slate-400 space-y-1 pt-1">
                    <p>ঠিকানা: বেড়া-সোনাতলা, সাঁথিয়া, পাবনা</p>
                    <p>ফোন: +৮৮০ ১৭১২-৩৪৫৬৭৮</p>
                    <p>ইমেইল: info@sonatalahighschool.edu.bd</p>
                </div>
            </div>

            {{-- Col 2: Quick Links --}}
            <div>
                <h3 class="text-white font-bold text-xs uppercase tracking-wider mb-3 pb-1 border-b border-slate-800">
                    দ্রুত লিংক / Quick Links
                </h3>
                <ul class="space-y-2 text-[11px] text-slate-400">
                    <li><a href="{{ route('frontend.home') }}" class="hover:text-white hover:underline">প্রচ্ছদ (Home)</a></li>
                    <li><a href="{{ route('frontend.about') }}" class="hover:text-white hover:underline">বিদ্যালয় পরিচিতি (About)</a></li>
                    <li><a href="{{ route('frontend.academic.routine') }}" class="hover:text-white hover:underline">ক্লাস ও পরীক্ষা রুটিন (Routine)</a></li>
                    <li><a href="{{ route('frontend.teachers.index') }}" class="hover:text-white hover:underline">শিক্ষক ও কর্মচারীবৃন্দ (Faculty)</a></li>
                    <li><a href="{{ route('frontend.notices.index') }}" class="hover:text-white hover:underline">নোটিশ বোর্ড (Notice Board)</a></li>
                    <li><a href="{{ route('frontend.contact') }}" class="hover:text-white hover:underline">যোগাযোগ ও লোকেশন (Contact)</a></li>
                </ul>
            </div>

            {{-- Col 3: Academic & Student Services --}}
            <div>
                <h3 class="text-white font-bold text-xs uppercase tracking-wider mb-3 pb-1 border-b border-slate-800">
                    সেবাসমূহ / Student Services
                </h3>
                <ul class="space-y-2 text-[11px] text-slate-400">
                    <li><a href="{{ url('/admin/dashboard') }}" class="hover:text-white hover:underline">অনলাইন ভর্তি আবেদন</a></li>
                    <li><a href="{{ route('frontend.notices.index') }}" class="hover:text-white hover:underline">পরীক্ষার ফলাফল অনুসন্ধান</a></li>
                    <li><a href="{{ route('frontend.academic.routine') }}" class="hover:text-white hover:underline">ক্লাস রুটিন ও সিলেবাস</a></li>
                    <li><a href="{{ url('/admin/dashboard') }}" class="hover:text-white hover:underline">শিক্ষক ও অভিভাবক পোর্টাল</a></li>
                    <li><a href="{{ route('frontend.contact') }}" class="hover:text-white hover:underline">অভিযোগ ও মতামত বক্স</a></li>
                </ul>
            </div>

            {{-- Col 4: Official Government Links --}}
            <div>
                <h3 class="text-white font-bold text-xs uppercase tracking-wider mb-3 pb-1 border-b border-slate-800">
                    গুরুত্বপূর্ণ লিংক / Useful Links
                </h3>
                <ul class="space-y-2 text-[11px] text-slate-400">
                    <li><a href="https://bangladesh.gov.bd" target="_blank" rel="noopener noreferrer" class="hover:text-white hover:underline">জাতীয় তথ্য বাতায়ন (Bangladesh.gov.bd)</a></li>
                    <li><a href="https://moedu.gov.bd" target="_blank" rel="noopener noreferrer" class="hover:text-white hover:underline">শিক্ষা মন্ত্রণালয় (MoEdu)</a></li>
                    <li><a href="https://dshe.gov.bd" target="_blank" rel="noopener noreferrer" class="hover:text-white hover:underline">মাধ্যমিক ও উচ্চশিক্ষা অধিদপ্তর (DSHE)</a></li>
                    <li><a href="http://www.rajshahieducationboard.gov.bd" target="_blank" rel="noopener noreferrer" class="hover:text-white hover:underline">রাজশাহী শিক্ষা বোর্ড</a></li>
                </ul>
            </div>
        </div>

        {{-- Bottom Copyright Strip --}}
        <div class="mt-8 pt-4 border-t border-slate-900 text-[11px] text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>© {{ date('Y') }} সোনাতলা উচ্চ বিদ্যালয়। সর্বস্বত্ব সংরক্ষিত।</p>
            <p>সাঁথিয়া, পাবনা, রাজশাহী বিভাগ, বাংলাদেশ</p>
        </div>
    </div>
</footer>
