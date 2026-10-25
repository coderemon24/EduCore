<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CurrentSchoolContext;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NewsController extends Controller
{
    /**
     * Institutional News Categories.
     */
    public const CATEGORIES = [
        'academic' => [
            'name' => 'একাডেমিক অর্জন',
            'slug' => 'academic',
            'icon' => 'fa-graduation-cap',
            'badge_color' => 'bg-emerald-50 text-[#005030] border-emerald-200',
        ],
        'sports' => [
            'name' => 'ক্রীড়া ও সহশিক্ষা',
            'slug' => 'sports',
            'icon' => 'fa-trophy',
            'badge_color' => 'bg-amber-50 text-amber-800 border-amber-200',
        ],
        'events' => [
            'name' => 'জাতীয় দিবস ও উৎসব',
            'slug' => 'events',
            'icon' => 'fa-calendar-check',
            'badge_color' => 'bg-rose-50 text-rose-800 border-rose-200',
        ],
        'science' => [
            'name' => 'বিজ্ঞান ও উদ্ভাবন',
            'slug' => 'science',
            'icon' => 'fa-flask',
            'badge_color' => 'bg-sky-50 text-sky-800 border-sky-200',
        ],
        'infrastructure' => [
            'name' => 'ক্যাম্পাস ও উন্নয়ন',
            'slug' => 'infrastructure',
            'icon' => 'fa-building-columns',
            'badge_color' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
        ],
        'community' => [
            'name' => 'সামাজিক কার্যক্রম',
            'slug' => 'community',
            'icon' => 'fa-hand-holding-heart',
            'badge_color' => 'bg-teal-50 text-teal-800 border-teal-200',
        ],
    ];

    /**
     * Display the paginated institutional news portal.
     */
    public function index(Request $request): View
    {
        $school = CurrentSchoolContext::get();
        $allNews = $this->getCuratedNewsList();

        $searchQuery = trim((string) $request->input('q', ''));
        $activeCategory = trim((string) $request->input('category', ''));

        // Filter by category
        if ($activeCategory !== '' && array_key_exists($activeCategory, self::CATEGORIES)) {
            $allNews = $allNews->where('category_slug', $activeCategory);
        }

        // Filter by search query
        if ($searchQuery !== '') {
            $needle = mb_strtolower($searchQuery);
            $allNews = $allNews->filter(function ($item) use ($needle) {
                return str_contains(mb_strtolower($item['title']), $needle)
                    || str_contains(mb_strtolower($item['excerpt']), $needle)
                    || str_contains(mb_strtolower($item['content']), $needle)
                    || str_contains(mb_strtolower($item['author']), $needle);
            });
        }

        // Category counts
        $categoriesWithCounts = [];
        $rawCollection = $this->getCuratedNewsList();
        foreach (self::CATEGORIES as $slug => $meta) {
            $categoriesWithCounts[$slug] = array_merge($meta, [
                'count' => $rawCollection->where('category_slug', $slug)->count(),
            ]);
        }

        // Flagship / Featured story (Top story when not actively searching)
        $featuredNews = null;
        if ($searchQuery === '' && $activeCategory === '') {
            $featuredNews = $allNews->firstWhere('is_featured', true) ?? $allNews->first();
            // Remove featured news from the standard grid to prevent duplicate card clutter
            $gridNews = $allNews->filter(fn ($item) => $item['slug'] !== ($featuredNews['slug'] ?? null));
        } else {
            $gridNews = $allNews;
        }

        // Sidebar Recent Stories
        $recentNews = $this->getCuratedNewsList()->take(4);

        // Paginate results
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 6;
        $total = $gridNews->count();
        $pagedSlice = $gridNews->slice(($page - 1) * $perPage, $perPage)->values();

        $newsList = new LengthAwarePaginator(
            $pagedSlice,
            $total,
            $perPage,
            $page,
            [
                'path' => route('frontend.news.index'),
                'query' => $request->query(),
            ]
        );

        return view('frontend.news.index', compact(
            'school',
            'featuredNews',
            'newsList',
            'categoriesWithCounts',
            'recentNews',
            'activeCategory',
            'searchQuery'
        ));
    }

    /**
     * Display a single news story in detail.
     */
    public function show(string $slug): View
    {
        $school = CurrentSchoolContext::get();
        $allNews = $this->getCuratedNewsList();

        $news = $allNews->firstWhere('slug', $slug);

        if (! $news) {
            abort(404, 'অনুরোধকৃত সংবাদটি পাওয়া যায়নি।');
        }

        // Related stories in same category
        $relatedNews = $allNews
            ->where('category_slug', $news['category_slug'])
            ->where('slug', '!=', $news['slug'])
            ->take(3);

        // Recent stories
        $recentNews = $allNews
            ->where('slug', '!=', $news['slug'])
            ->take(4);

        return view('frontend.news.show', compact('school', 'news', 'relatedNews', 'recentNews'));
    }

    /**
     * Build curated institutional news dataset with authentic Bengali context.
     *
     * @return Collection<int, array<string, mixed>>
     */
    protected function getCuratedNewsList(): Collection
    {
        return collect([
            [
                'id' => 1,
                'slug' => 'national-stem-olympiad-championship-2026',
                'title' => 'জাতীয় বিজ্ঞান ও স্টেম অলিম্পিয়াডে সোনাতলা উচ্চ বিদ্যালয়ের প্রথম স্থান অর্জন',
                'excerpt' => 'জাতীয় শিক্ষা সপ্তাহ ও স্টেম অলিম্পিয়াড ২০২৬ এ রোবটিক্স এবং নবায়নযোগ্য জ্বালানি প্রকল্পে জেলা ও বিভাগীয় পর্যায়ে সেরা গৌরব অর্জন করেছে সোনাতলা উচ্চ বিদ্যালয়ের তরুণ বিজ্ঞানী দল।',
                'content' => <<<'HTML'
<p class="lead text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-6">
    বিজ্ঞান ও তথ্যপ্রযুক্তির যুগে সোনাতলা উচ্চ বিদ্যালয়ের শিক্ষার্থীদের মেধা ও উদ্ভাবনী শক্তি আবারও জাতীয় পর্যায়ে প্রশংসিত হলো। জাতীয় শিক্ষা সপ্তাহ উপলক্ষে আয়োজিত স্টেম (STEM) অলিম্পিয়াড ২০২৬ এ রোবটিক্স ও নবায়নযোগ্য জ্বালানি উদ্ভাবন বিভাগে আমাদের বিদ্যালয় চ্যাম্পিয়ন ট্রফি অর্জন করেছে।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    গত ২০ অক্টোবর ২০২৬ তারিখে অনুষ্ঠিত জাতীয় পর্বের সমাপনী অনুষ্ঠানে বিজয়ীদের হাতে সম্মাননা স্মারক ও সনদপত্র তুলে দেওয়া হয়। প্রতিযোগিতায় নবম ও দশম শ্রেণির ক্ষুদে বিজ্ঞানীদের সমন্বয়ে গঠিত "টিম সোনাতলা ইনোভেটরস" পরিবেশবান্ধব সৌরচালিত স্মার্ট এগ্রিকালচার ও অটোমেটেড সেচ ব্যবস্থাপনা বিষয়ক কার্যক্ষম মডেল প্রদর্শন করে বিচারকদের ভূয়সী প্রশংসা কুড়ায়।
</p>
<h3 class="text-xl font-bold text-slate-900 mt-6 mb-3">প্রধান শিক্ষকের শুভেচ্ছা বাণী</h3>
<blockquote class="border-l-4 border-[#005030] pl-4 py-2 italic text-slate-700 bg-emerald-50/40 rounded-r-lg my-4">
    "আমাদের শিক্ষার্থীদের এই অভূতপূর্ব অর্জন সমগ্র সোনাতলাবাসীর জন্য গর্বের। নিয়মিত গবেষণামূলক বিজ্ঞান ক্লাব ও শিক্ষকদের নিবিড় পরিচর্যা শিক্ষার্থীদের স্বপ্নকে বাস্তবতায় রূপ দিতে সহায়ক ভূমিকা পালন করেছে।"
</blockquote>
<p class="mb-4 text-slate-700 leading-relaxed">
    বিদ্যালয় পরিচালনা পর্ষদ ও অভিভাবক শিক্ষক ফোরামের পক্ষ থেকে বিজয়ী শিক্ষার্থী দল এবং বিজ্ঞান বিভাগের সম্মানিত শিক্ষকবৃন্দকে উষ্ণ অভিনন্দন ও শুভেচ্ছা জানানো হয়েছে। আগামী সপ্তাহে বিজয়ী দলের সম্মানে বিদ্যালয় প্রাঙ্গণে সংবর্ধনা অনুষ্ঠানের আয়োজন করা হবে।
</p>
HTML,
                'category' => 'বিজ্ঞান ও উদ্ভাবন',
                'category_slug' => 'science',
                'badge_color' => 'bg-sky-50 text-sky-800 border-sky-200',
                'author' => 'বিজ্ঞান ও প্রযুক্তি ক্লাব',
                'published_at' => Carbon::now()->subDays(2)->format('d M Y'),
                'date_day' => Carbon::now()->subDays(2)->format('d'),
                'date_month' => Carbon::now()->subDays(2)->translatedFormat('M Y'),
                'read_time' => '৪ মিনিট পাঠ',
                'views_count' => 842,
                'is_featured' => true,
                'image_url' => 'assets/images/news/news-1.jpg',
                'image_alt' => 'স্টেম অলিম্পিয়াডে প্রথম স্থান অর্জন',
            ],
            [
                'id' => 2,
                'slug' => 'inter-school-cricket-tournament-champions-2026',
                'title' => 'আন্তঃউপজেলা ক্রিকেট টুর্নামেন্টে অপরাজিত চ্যাম্পিয়ন সোনাতলা ক্রিকেট একাদশ',
                'excerpt' => 'ফাইনালে সাঁথিয়া পাইলট উচ্চ বিদ্যালয়কে ৬ উইকেটে হারিয়ে উপজেলা ক্রীড়া সংস্থা আয়োজিত বার্ষিক স্কুল ক্রিকেট টুর্নামেন্টের শিরোপা নিজেদের করে নিল সোনাতলা উচ্চ বিদ্যালয়।',
                'content' => <<<'HTML'
<p class="lead text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-6">
    ক্রীড়াঙ্গনে সোনাতলা উচ্চ বিদ্যালয়ের ধারাবাহিক আধিপত্যের নতুন সংযোজন। সাঁথিয়া উপজেলা ক্রীড়া সংস্থা কর্তৃক আয়োজিত আন্তঃস্কুল টি-টোয়েন্টি টুর্নামেন্টে অপরাজিত চ্যাম্পিয়ন হওয়ার গৌরব অর্জন করেছে সোনাতলা ক্রিকেট একাদশ।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    রোমাঞ্চকর ফাইনালে প্রতিপক্ষ সাঁথিয়া পাইলট উচ্চ বিদ্যালয় প্রথমে ব্যাট করে নির্ধারিত ২০ ওভারে ৭ উইকেট হারিয়ে ১৪৫ রান সংগ্রহ করে। জবাবে সোনাতলা দলের উদ্বোধনী ব্যাটসম্যান রাফি ও অধিনায়ক তামিমের দায়িত্বশীল ইনিংসে ১৮.২ ওভারেই ৪ উইকেট হারিয়ে জয়ের কাঙ্ক্ষিত লক্ষ্যে পৌঁছে যায় বিদ্যালয় দল।
</p>
<h3 class="text-xl font-bold text-slate-900 mt-6 mb-3">ম্যাচসেরা ও ফাইনাল পরিসংখ্যান</h3>
<p class="mb-4 text-slate-700 leading-relaxed">
    ফাইনালে ৩ উইকেট শিকার এবং অপরাজিত ৪২ রানের অলরাউন্ড নৈপুণ্যের জন্য নবম শ্রেণির শিক্ষার্থী তামিম ইসলাম ম্যান অব দ্য ফাইনাল নির্বাচিত হন। খেলা শেষে উপজেলা নির্বাহী কর্মকর্তা বিজয়ীদের হাতে চ্যাম্পিয়ন ট্রফি তুলে দেন।
</p>
HTML,
                'category' => 'ক্রীড়া ও সহশিক্ষা',
                'category_slug' => 'sports',
                'badge_color' => 'bg-amber-50 text-amber-800 border-amber-200',
                'author' => 'শারীরিক শিক্ষা বিভাগ',
                'published_at' => Carbon::now()->subDays(4)->format('d M Y'),
                'date_day' => Carbon::now()->subDays(4)->format('d'),
                'date_month' => Carbon::now()->subDays(4)->translatedFormat('M Y'),
                'read_time' => '৩ মিনিট পাঠ',
                'views_count' => 615,
                'is_featured' => false,
                'image_url' => 'assets/images/news/news-2.jpg',
                'image_alt' => 'ক্রিকেট টুর্নামেন্টে চ্যাম্পিয়ন সোনাতলা',
            ],
            [
                'id' => 3,
                'slug' => 'sheikh-russel-digital-lab-modernization-2026',
                'title' => 'শেখ রাসেল আধুনিক ডিজিটাল কম্পিউটার ল্যাবে নতুন হাই-স্পিড ওয়ার্কস্টেশন স্থাপন',
                'excerpt' => 'তথ্যপ্রযুক্তিভিত্তিক আধুনিক শিক্ষা সম্প্রসারণের লক্ষ্যে বিদ্যালয়ের কম্পিউটার ল্যাবে যুক্ত হলো ৫০টি উচ্চগতির কম্পিউটার, নিরবচ্ছিন্ন অপটিক্যাল ফাইবার ইন্টারনেট ও স্মার্ট প্যানেল।',
                'content' => <<<'HTML'
<p class="lead text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-6">
    ডিজিটাল বাংলাদেশ বিনির্মাণ এবং স্মার্ট সিটিজেন গড়ার প্রত্যয়ে সোনাতলা উচ্চ বিদ্যালয়ের আইসিটি ল্যাবকে বিশ্বমানের প্রযুক্তি কাঠামোয় রূপান্তর করা হয়েছে।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    শিক্ষা প্রকৌশল অধিদপ্তর ও স্থানীয় প্রশাসনের যৌথ সহযোগিতায় বিদ্যালয়ের শেখ রাসেল ডিজিটাল কম্পিউটার ল্যাবে সম্প্রতি ৫০টি নতুন কোর-আই৭ ওয়ার্কস্টেশন, মাল্টিমিডিয়া স্মার্ট ইন্টারেক্টিভ ডিসপ্লে ও ১০০ এমবিপিএস ব্রডব্যান্ড নেটওয়ার্ক সংযোজন সম্পন্ন হয়েছে।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    এর মাধ্যমে ৬ষ্ঠ থেকে ১০ম শ্রেণির শিক্ষার্থীরা পাইথন প্রোগ্রামিং, ওয়েব ডিজাইন বেসিকস ও ডিজিটাল লিটারেসির ব্যবহারিক ক্লাসে সরাসরি হাতে-কলমে অংশগ্রহণের অনন্য সুযোগ পাবে।
</p>
HTML,
                'category' => 'ক্যাম্পাস ও উন্নয়ন',
                'category_slug' => 'infrastructure',
                'badge_color' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                'author' => 'আইসিটি ও ডিজিটাল উইং',
                'published_at' => Carbon::now()->subDays(7)->format('d M Y'),
                'date_day' => Carbon::now()->subDays(7)->format('d'),
                'date_month' => Carbon::now()->subDays(7)->translatedFormat('M Y'),
                'read_time' => '৪ মিনিট পাঠ',
                'views_count' => 520,
                'is_featured' => false,
                'image_url' => 'assets/images/news/news-3.jpg',
                'image_alt' => 'ডিজিটাল কম্পিউটার ল্যাব আধুনিকায়ন',
            ],
            [
                'id' => 4,
                'slug' => 'ssc-board-examination-merit-scholarship-recipients',
                'title' => 'এসএসসি ও বৃত্তি পরীক্ষায় সোনাতলা উচ্চ বিদ্যালয়ের শিক্ষার্থীদের শতভাগ সাফল্য',
                'excerpt' => 'রাজশাহী শিক্ষা বোর্ডের অধীনে সর্বশেষ এসএসসি পরীক্ষায় শতভাগ পাসসহ ৬৪ জন শিক্ষার্থী জিপিএ-৫ অর্জন করেছে। মেধাবৃত্তি পেয়েছে ১৫ জন কৃতি শিক্ষার্থী।',
                'content' => <<<'HTML'
<p class="lead text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-6">
    ঐতিহ্যবাহী সোনাতলা উচ্চ বিদ্যালয়ের ধারাবাহিক সাফল্যের মুকুটে যুক্ত হলো আরও একটি উজ্জ্বল পালক। রাজশাহী শিক্ষা বোর্ডের অধীনে অনুষ্ঠিত এসএসসি পরীক্ষায় আমাদের বিদ্যালয় শতভাগ পাশের অনন্য গৌরব অর্জন করেছে।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    বিজ্ঞান, মানবিক ও ব্যবসায় শিক্ষা শাখার মোট ২৩৮ জন পরীক্ষার্থীর মধ্যে ৬৪ জন শিক্ষার্থী পূর্ণাঙ্গ জিপিএ-৫.০০ লাভ করেছে। এছাড়াও শিক্ষা বোর্ড কর্তৃক প্রকাশিত মেধা তালিকায় ১৫ জন শিক্ষার্থী ট্যালেন্টপুল ও সাধারণ গ্রেডে সরকারি বৃত্তি অর্জন করেছে।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    শিক্ষকবৃন্দের নিরলস পাঠদান, নিয়মিত মডেল টেস্ট গ্রহণ এবং অভিভাবকদের সহযোগিতাই এই চমৎকার ফলাফলের ভিত্তি বলে মন্তব্য করেছেন বিদ্যালয় পরিচালনা পর্ষদের সভাপতি।
</p>
HTML,
                'category' => 'একাডেমিক অর্জন',
                'category_slug' => 'academic',
                'badge_color' => 'bg-emerald-50 text-[#005030] border-emerald-200',
                'author' => 'একাডেমিক কাউন্সিল',
                'published_at' => Carbon::now()->subDays(10)->format('d M Y'),
                'date_day' => Carbon::now()->subDays(10)->format('d'),
                'date_month' => Carbon::now()->subDays(10)->translatedFormat('M Y'),
                'read_time' => '৩ মিনিট পাঠ',
                'views_count' => 980,
                'is_featured' => false,
                'image_url' => 'assets/images/news/news-4.jpg',
                'image_alt' => 'এসএসসি পরীক্ষায় শতভাগ সাফল্য',
            ],
            [
                'id' => 5,
                'slug' => 'bengali-new-year-and-cultural-festival-celebration',
                'title' => 'বিদ্যালয় প্রাঙ্গণে বর্ণাঢ্য আয়োজনে বাংলা নববর্ষ ও বসন্ত উৎসব উদযাপিত',
                'excerpt' => 'আনন্দ শোভাযাত্রা, বৈশাখী মেলা, আবৃত্তি ও লোকনৃত্যের মধ্য দিয়ে বিদ্যালয়ের শিক্ষক, শিক্ষার্থী ও অভিভাবকবৃন্দের অংশগ্রহণে উদযাপিত হলো ঐতিহ্যবাহী বর্ষবরণ উৎসব।',
                'content' => <<<'HTML'
<p class="lead text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-6">
    বাঙালির প্রাণের উৎসব বাংলা নববর্ষ ও ঋতুরাজ বসন্তের আগমনকে কেন্দ্র করে সোনাতলা উচ্চ বিদ্যালয় প্রাঙ্গণ রূপ নিয়েছিল এক উৎসবমুখর মিলনমেলায়।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    সকালের সূচনায় বিদ্যালয় প্রাঙ্গণ থেকে বের হয় বর্ণাঢ্য মঙ্গল শোভাযাত্রা। এতে আবহমান গ্রাম-বাংলার ঐতিহ্যবাহী পালকি, বাঘ, পেঁচা ও পাখার প্রতিকৃতি নিয়ে শিক্ষার্থীরা অংশ নেয়। শোভাযাত্রা শেষে বিদ্যালয়ের বকুলতলায় অনুষ্ঠিত হয় মনোজ্ঞ সাংস্কৃতিক অনুষ্ঠান।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    শিক্ষার্থীদের স্বরচিত কবিতা আবৃত্তি, পল্লীগীতি, দেশাত্মবোধক গান এবং ঐতিহ্যবাহী নাটিকা পরিবেশনা দর্শকদের মুগ্ধ করে। এছাড়াও প্রাঙ্গণে আয়োজিত দেশীয় পিঠাপুলির মেলা সবার নজর কাড়ে।
</p>
HTML,
                'category' => 'জাতীয় দিবস ও উৎসব',
                'category_slug' => 'events',
                'badge_color' => 'bg-rose-50 text-rose-800 border-rose-200',
                'author' => 'সাংস্কৃতিক বিষয়ক কমিটি',
                'published_at' => Carbon::now()->subDays(14)->format('d M Y'),
                'date_day' => Carbon::now()->subDays(14)->format('d'),
                'date_month' => Carbon::now()->subDays(14)->translatedFormat('M Y'),
                'read_time' => '৩ মিনিট পাঠ',
                'views_count' => 430,
                'is_featured' => false,
                'image_url' => 'assets/images/news/news-5.jpg',
                'image_alt' => 'বাংলা নববর্ষ উদযাপন ও বৈশাখী উৎসব',
            ],
            [
                'id' => 6,
                'slug' => 'tree-plantation-and-eco-club-environmental-drive',
                'title' => 'পরিবেশ সচেতনতায় সোনাতলা ইকো-ক্লাবের উদ্যোগে ১০০০ ফলদ ও বনজ বৃক্ষরোপণ',
                'excerpt' => 'সবুজ ক্যাম্পাস গড়ার লক্ষ্যে বিদ্যালয়ের শিক্ষার্থী ও স্কাউট দলের অংশগ্রহণে ক্যাম্পাস ও পার্শ্ববর্তী কমিউনিটিতে সপ্তাহব্যাপী বৃক্ষরোপণ অভিযান পরিচালিত হয়েছে।',
                'content' => <<<'HTML'
<p class="lead text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-6">
    জলবায়ু পরিবর্তনের বিরূপ প্রভাব মোকাবিলা এবং সবুজ শ্যামল শিক্ষাঙ্গন গড়ার দৃঢ় প্রত্যয়ে সোনাতলা উচ্চ বিদ্যালয় ইকো-ক্লাব ও বাংলাদেশ স্কাউটস দলের যৌথ উদ্যোগে ব্যাপক পরিসরে বৃক্ষরোপণ সপ্তাহ পালিত হয়েছে।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    সপ্তাহব্যাপী এই কর্মসূচিতে বিদ্যালয় ক্যাম্পাস, খেলার মাঠের সীমানা প্রাচীর এবং পার্শ্ববর্তী গ্রামীণ সড়কের দুপাশে এক হাজারেরও বেশি ফলদ, বনজ ও ঔষধি বৃক্ষের চারা রোপণ করা হয়।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    কর্মসূচি চলাকালে প্রতিটি শ্রেণিকে নির্দিষ্ট কয়েকটি গাছের পরিচর্যার দায়িত্ব দেওয়া হয়, যাতে শিক্ষার্থীদের মধ্যে পরিবেশ ও প্রকৃতির প্রতি আত্মিক ভালোবাসা গড়ে ওঠে।
</p>
HTML,
                'category' => 'সামাজিক কার্যক্রম',
                'category_slug' => 'community',
                'badge_color' => 'bg-teal-50 text-teal-800 border-teal-200',
                'author' => 'ইকো ক্লাব ও স্কাউটস দল',
                'published_at' => Carbon::now()->subDays(18)->format('d M Y'),
                'date_day' => Carbon::now()->subDays(18)->format('d'),
                'date_month' => Carbon::now()->subDays(18)->translatedFormat('M Y'),
                'read_time' => '৩ মিনিট পাঠ',
                'views_count' => 388,
                'is_featured' => false,
                'image_url' => 'assets/images/news/news-6.jpg',
                'image_alt' => 'বৃক্ষরোপণ কর্মসূচি ও সবুজায়ন অভিযান',
            ],
        ]);
    }
}
