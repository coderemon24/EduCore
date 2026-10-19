@php
    $currentSchool = \App\Services\CurrentSchoolContext::get();
    $eiin = $currentSchool->eiin ?? '১২৫৬৯৩';
    $mpoCode = $currentSchool->mpo_code ?? '৮১০৭০২১৩০২';
    $estd = ($currentSchool->established_year && $currentSchool->established_year != 2010) ? $currentSchool->established_year : '1971';

    // Fetch published notices or fallback to institutional circulars
    $tickerNotices = collect();
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('notices')) {
            $tickerNotices = \App\Models\Notice::where('status', 'published')
                ->latest('published_at')
                ->take(6)
                ->get();
        }
    } catch (\Throwable $e) {
        $tickerNotices = collect();
    }
@endphp

{{-- Top Institutional Utility Bar (Matching User Reference Image) --}}
<div class="site-topbar">
    <div class="site-topbar-inner">

        {{-- Left: Update Pill Badge & Left-to-Right Infinite Scrollable Shironam --}}
        <div class="topbar-left">
            {{-- Update Pill Badge --}}
            <span class="topbar-badge">আপডেট</span>

            {{-- Left-to-Right Infinite Scrollable Shironam Container --}}
            <div class="topbar-ticker-container">
                <div class="topbar-fade-left"></div>

                <div class="shironam-marquee-track">
                    {{-- Loop Group 1 --}}
                    <div class="shironam-marquee-group">
                        <a href="{{ route('frontend.notices.index') }}" class="shironam-link">
                            নির্বাচনী পরীক্ষা ২০২৩ খ্রি. রুটিন প্রকাশ করা হয়েছে।
                        </a>
                        <span class="shironam-dot">•</span>

                        @if($tickerNotices->isNotEmpty())
                            @foreach($tickerNotices as $notice)
                                <a href="{{ route('frontend.notices.show', $notice->slug ?? $notice->id) }}" class="shironam-link">
                                    {{ $notice->title }}
                                </a>
                                <span class="shironam-dot">•</span>
                            @endforeach
                        @else
                            <a href="{{ route('frontend.notices.index') }}" class="shironam-link">
                                ২০২৬ শিক্ষাবর্ষে ৬ষ্ঠ থেকে ৯ম শ্রেণিতে ভর্তি আবেদন চলছে — ফরম সংগ্রহ ও জমাদানের শেষ তারিখ ৩০ নভেম্বর।
                            </a>
                            <span class="shironam-dot">•</span>
                            <a href="{{ route('frontend.notices.index') }}" class="shironam-link">
                                আসন্ন বার্ষিক পরীক্ষা ২০২৬ এর সংশোধিত সময়সূচি ও প্রবেশপত্র সংক্রান্ত জরুরি বিজ্ঞপ্তি।
                            </a>
                            <span class="shironam-dot">•</span>
                            <a href="{{ route('frontend.notices.index') }}" class="shironam-link">
                                সকল শ্রেণির শিক্ষার্থীদের ডিজিটাল ইউনিক আইডি (UID) তথ্য হালনাগাদ সংক্রান্ত বিজ্ঞপ্তি।
                            </a>
                            <span class="shironam-dot">•</span>
                        @endif
                    </div>

                    {{-- Loop Group 2 (Identical Clone for Flawless Infinite Seamless Looping) --}}
                    <div class="shironam-marquee-group" aria-hidden="true">
                        <a href="{{ route('frontend.notices.index') }}" class="shironam-link">
                            নির্বাচনী পরীক্ষা ২০২৩ খ্রি. রুটিন প্রকাশ করা হয়েছে।
                        </a>
                        <span class="shironam-dot">•</span>

                        @if($tickerNotices->isNotEmpty())
                            @foreach($tickerNotices as $notice)
                                <a href="{{ route('frontend.notices.show', $notice->slug ?? $notice->id) }}" class="shironam-link">
                                    {{ $notice->title }}
                                </a>
                                <span class="shironam-dot">•</span>
                            @endforeach
                        @else
                            <a href="{{ route('frontend.notices.index') }}" class="shironam-link">
                                ২০২৬ শিক্ষাবর্ষে ৬ষ্ঠ থেকে ৯ম শ্রেণিতে ভর্তি আবেদন চলছে — ফরম সংগ্রহ ও জমাদানের শেষ তারিখ ৩০ নভেম্বর।
                            </a>
                            <span class="shironam-dot">•</span>
                            <a href="{{ route('frontend.notices.index') }}" class="shironam-link">
                                আসন্ন বার্ষিক পরীক্ষা ২০২৬ এর সংশোধিত সময়সূচি ও প্রবেশপত্র সংক্রান্ত জরুরি বিজ্ঞপ্তি।
                            </a>
                            <span class="shironam-dot">•</span>
                            <a href="{{ route('frontend.notices.index') }}" class="shironam-link">
                                সকল শ্রেণির শিক্ষার্থীদের ডিজিটাল ইউনিক আইডি (UID) তথ্য হালনাগাদ সংক্রান্ত বিজ্ঞপ্তি।
                            </a>
                            <span class="shironam-dot">•</span>
                        @endif
                    </div>
                </div>

                <div class="topbar-fade-right"></div>
            </div>
        </div>

        {{-- Right: Institutional Credentials (EIIN, MPO CODE, ESTD) --}}
        <div class="topbar-right">
            <div class="topbar-credential-item">
                <span class="topbar-credential-label">EIIN:</span>
                <span class="topbar-credential-value">{{ $eiin }}</span>
            </div>
            <div class="topbar-credential-item topbar-credential-mpo">
                <span class="topbar-credential-label">MPO CODE:</span>
                <span class="topbar-credential-value">{{ $mpoCode }}</span>
            </div>
            <div class="topbar-credential-item">
                <span class="topbar-credential-label">ESTD:</span>
                <span class="topbar-credential-value">{{ $estd }}</span>
            </div>
        </div>

    </div>
</div>
