{{-- Notice Board Widget (Exact match to reference design) --}}
@php
    $bnDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    $enDigits = ['0','1','2','3','4','5','6','7','8','9'];
    $bnMonths = [
        1 => 'জানুয়ারি', 2 => 'ফেব্রুয়ারি', 3 => 'মার্চ', 4 => 'এপ্রিল',
        5 => 'মে', 6 => 'জুন', 7 => 'জুলাই', 8 => 'আগস্ট',
        9 => 'সেপ্টেম্বর', 10 => 'অক্টোবর', 11 => 'নভেম্বর', 12 => 'ডিসেম্বর'
    ];

    $fallbackNotices = [
        [
            'title' => 'নির্বাচনী পরীক্ষা- ২০২৬ খ্রি. দশম শ্রেণী ভোকেশনাল',
            'date' => '২১ অক্টোবর, ২০২৬',
            'slug' => 'election-exam-2026-class-10-vocational',
            'badge' => null,
        ],
        [
            'title' => 'নির্বাচনী পরীক্ষা ২০২৬ খ্রি. রুটিন।',
            'date' => '১৫ অক্টোবর, ২০২৬',
            'slug' => 'election-exam-routine-2026',
            'badge' => 'সাধারণ',
        ],
        [
            'title' => 'একাডেমিক ট্রান্সক্রিপ্ট সংগ্রহ করার জন্য জানানো হলো।',
            'date' => '১৩ অক্টোবর, ২০২৬',
            'slug' => 'academic-transcript-collection-notice',
            'badge' => 'সাধারণ',
        ],
        [
            'title' => 'জুনিয়র বৃত্তি পরীক্ষা ২০২৬ রেজিস্ট্রেশন ফরম',
            'date' => '৭ সেপ্টেম্বর, ২০২৬',
            'slug' => 'junior-scholarship-exam-registration-2026',
            'badge' => null,
        ],
        [
            'title' => '১০ম শ্রেণির অভিভাবক সমাবেশের আয়োজন করা হয়েছে।',
            'date' => '১৮ আগস্ট, ২০২৬',
            'slug' => 'class-10-parents-meeting-2026',
            'badge' => 'সাধারণ',
        ],
    ];

    $displayNotices = [];
    if (isset($latestNotices) && $latestNotices->isNotEmpty()) {
        foreach ($latestNotices->take(5) as $n) {
            $formattedDate = '';
            if ($n->published_at) {
                $pubDate = $n->published_at instanceof \Carbon\CarbonInterface
                    ? $n->published_at
                    : \Carbon\Carbon::parse($n->published_at);
                $day = str_replace($enDigits, $bnDigits, $pubDate->format('j'));
                $month = $bnMonths[$pubDate->month] ?? $pubDate->format('M');
                $year = str_replace($enDigits, $bnDigits, $pubDate->format('Y'));
                $formattedDate = "{$day} {$month}, {$year}";
            } else {
                $formattedDate = '২১ অক্টোবর, ২০২৬';
            }

            $badge = null;
            if ($n->type === 'general') {
                $badge = 'সাধারণ';
            }

            $displayNotices[] = [
                'title' => $n->title,
                'date' => $formattedDate,
                'slug' => $n->slug ?: (string)$n->id,
                'badge' => $badge,
            ];
        }
    } else {
        $displayNotices = $fallbackNotices;
    }
@endphp

<div class="w-full h-full bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 flex flex-col justify-between shadow-xs">
    {{-- Header: Bell Icon + Heading + "সকল নোটিশ" Button --}}
    <div class="flex items-center justify-between pb-3 sm:pb-4">
        <div class="flex items-center gap-2.5">
            {{-- Peach/Pink Circular Bell Container --}}
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#005030] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 text-white" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                </svg>
            </div>
            <h2 class="text-xl sm:text-[22px] font-bold text-slate-900 tracking-tight">
                নোটিশ বোর্ড
            </h2>
        </div>

        <a href="{{ route('frontend.notices.index') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 sm:px-4 sm:py-1.5 rounded-full bg-[#005030] hover:bg-[#003d24] text-white text-xs sm:text-sm font-bold shadow-xs transition-colors cursor-pointer">
            <span>সকল নোটিশ</span>
        </a>
    </div>

    {{-- Notice Items List --}}
    <div class="divide-y divide-slate-100 flex-1">
        @foreach($displayNotices as $notice)
            <div class="py-3.5 sm:py-4 flex items-center justify-between gap-3 group">
                {{-- Left Content: Red Bullet, Title, Calendar Date & Tag --}}
                <div class="flex-1 min-w-0 pr-2">
                    <div class="flex items-start gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#c53030] shrink-0 mt-1.5"></span>
                        <a href="{{ route('frontend.notices.show', $notice['slug']) }}"
                           class="text-sm sm:text-[14px] font-semibold text-slate-800 leading-snug group-hover:text-[#005030] transition-colors line-clamp-2">
                            {{ $notice['title'] }}
                        </a>
                    </div>
                    <div class="flex items-center gap-1.5 mt-1.5 pl-4 text-xs text-slate-500 font-normal">
                        {{-- Dot Grid Calendar Icon matching reference design --}}
                        <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z"/>
                            <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                        </svg>
                        <span>{{ $notice['date'] }}</span>
                        @if(!empty($notice['badge']))
                            <span class="inline-flex items-center px-2 py-0.5 rounded-sm bg-[#e6f4ea] text-[#005030] text-[11px] font-semibold leading-none ml-2">
                                {{ $notice['badge'] }}
                            </span>
                        @endif
                    </div>
                </div>

                <a href="{{ route('frontend.notices.show', $notice['slug']) }}"
                   class="rounded-full border border-[#005030] text-[#005030] 
                   notice_show  px-3.5 sm:px-4 py-1 text-xs sm:text-[14px] font-semibold transition-all duration-150 shrink-0 text-center cursor-pointer">
                    দেখুন
                </a>
            </div>
        @endforeach
    </div>
</div>
