<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CurrentSchoolContext;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EventController extends Controller
{
    /**
     * Institutional Event Categories with authentic styling and iconography.
     */
    public const CATEGORIES = [
        'sports' => [
            'name' => 'ক্রীড়া ও সহশিক্ষা',
            'slug' => 'sports',
            'icon' => 'fa-trophy',
            'badge_color' => 'bg-amber-50 text-amber-800 border-amber-200',
        ],
        'academic' => [
            'name' => 'একাডেমিক ও বিজ্ঞান',
            'slug' => 'academic',
            'icon' => 'fa-flask',
            'badge_color' => 'bg-sky-50 text-sky-800 border-sky-200',
        ],
        'cultural' => [
            'name' => 'সাহিত্য ও সংস্কৃতি',
            'slug' => 'cultural',
            'icon' => 'fa-masks-theater',
            'badge_color' => 'bg-purple-50 text-purple-800 border-purple-200',
        ],
        'national' => [
            'name' => 'জাতীয় দিবস ও শ্রদ্ধাঞ্জলি',
            'slug' => 'national',
            'icon' => 'fa-flag',
            'badge_color' => 'bg-rose-50 text-rose-800 border-rose-200',
        ],
        'community' => [
            'name' => 'সামাজিক ও পরিবেশ',
            'slug' => 'community',
            'icon' => 'fa-seedling',
            'badge_color' => 'bg-emerald-50 text-[#005030] border-emerald-200',
        ],
    ];

    /**
     * Display the paginated institutional events calendar and showcase.
     */
    public function index(Request $request): View
    {
        $school = CurrentSchoolContext::get();
        $allEvents = $this->getCuratedEventsList();

        $searchQuery = trim((string) $request->input('q', ''));
        $activeCategory = trim((string) $request->input('category', ''));
        $activeStatus = trim((string) $request->input('status', 'all'));

        // Filter by status (upcoming or completed)
        if (in_array($activeStatus, ['upcoming', 'completed'], true)) {
            $allEvents = $allEvents->where('status', $activeStatus);
        } else {
            $activeStatus = 'all';
        }

        // Filter by category
        if ($activeCategory !== '' && array_key_exists($activeCategory, self::CATEGORIES)) {
            $allEvents = $allEvents->where('category_slug', $activeCategory);
        }

        // Filter by search keyword
        if ($searchQuery !== '') {
            $needle = mb_strtolower($searchQuery);
            $allEvents = $allEvents->filter(function ($item) use ($needle) {
                return str_contains(mb_strtolower($item['title']), $needle)
                    || str_contains(mb_strtolower($item['excerpt']), $needle)
                    || str_contains(mb_strtolower($item['venue']), $needle)
                    || str_contains(mb_strtolower($item['organizer']), $needle);
            });
        }

        // Status counts for filter tabs
        $rawCollection = $this->getCuratedEventsList();
        $statusCounts = [
            'all' => $rawCollection->count(),
            'upcoming' => $rawCollection->where('status', 'upcoming')->count(),
            'completed' => $rawCollection->where('status', 'completed')->count(),
        ];

        // Category counts
        $categoriesWithCounts = [];
        foreach (self::CATEGORIES as $slug => $meta) {
            $categoriesWithCounts[$slug] = array_merge($meta, [
                'count' => $rawCollection->where('category_slug', $slug)->count(),
            ]);
        }

        // Flagship / Featured event (Top upcoming event when not actively searching)
        $featuredEvent = null;
        if ($searchQuery === '' && $activeCategory === '' && $activeStatus !== 'completed') {
            $featuredEvent = $allEvents->firstWhere('is_featured', true) ?? $allEvents->first();
            $gridEvents = $allEvents->filter(fn ($item) => $item['slug'] !== ($featuredEvent['slug'] ?? null));
        } else {
            $gridEvents = $allEvents;
        }

        // Sidebar top 3 upcoming highlights
        $upcomingHighlights = $rawCollection->where('status', 'upcoming')->take(3);

        // Paginate results
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 6;
        $total = $gridEvents->count();
        $pagedSlice = $gridEvents->slice(($page - 1) * $perPage, $perPage)->values();

        $eventsList = new LengthAwarePaginator(
            $pagedSlice,
            $total,
            $perPage,
            $page,
            [
                'path' => route('frontend.events.index'),
                'query' => $request->query(),
            ]
        );

        return view('frontend.events.index', compact(
            'school',
            'featuredEvent',
            'eventsList',
            'categoriesWithCounts',
            'statusCounts',
            'activeCategory',
            'activeStatus',
            'searchQuery',
            'upcomingHighlights'
        ));
    }

    /**
     * Display a single event with full program timeline and details.
     */
    public function show(string $slug): View
    {
        $school = CurrentSchoolContext::get();
        $allEvents = $this->getCuratedEventsList();

        $event = $allEvents->firstWhere('slug', $slug);

        if (! $event) {
            abort(404, 'অনুরোধকৃত ইভেন্টটি খুঁজে পাওয়া যায়নি।');
        }

        // Related events in same category
        $relatedEvents = $allEvents
            ->where('category_slug', $event['category_slug'])
            ->where('slug', '!=', $event['slug'])
            ->take(3);

        // Upcoming events for sidebar
        $upcomingEvents = $allEvents
            ->where('status', 'upcoming')
            ->where('slug', '!=', $event['slug'])
            ->take(4);

        return view('frontend.events.show', compact('school', 'event', 'relatedEvents', 'upcomingEvents'));
    }

    /**
     * Curated institutional events dataset with authentic Bengali context.
     *
     * @return Collection<int, array<string, mixed>>
     */
    protected function getCuratedEventsList(): Collection
    {
        return collect([
            [
                'id' => 1,
                'slug' => 'annual-sports-competition-2026',
                'title' => '৫৮তম বার্ষিক ক্রীড়া প্রতিযোগিতা ও পুরস্কার বিতরণী অনুষ্ঠান ২০২৬',
                'excerpt' => 'সোনাতলা উচ্চ বিদ্যালয়ের ৫৮তম বার্ষিক ক্রীড়া প্রতিযোগিতা, মনোজ্ঞ মার্চপাস্ট, মশাল প্রজ্বালন ও বর্ণাঢ্য সাংস্কৃতিক পরিবেশনার শুভ উদ্বোধন।',
                'description' => <<<'HTML'
<p class="lead text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-6">
    ঐতিহ্যবাহী সোনাতলা উচ্চ বিদ্যালয়ের ৫৮তম বার্ষিক ক্রীড়া প্রতিযোগিতা ও পুরস্কার বিতরণী উৎসব আগামী ১৫ নভেম্বর ২০২৬ রোজ রবিবার বিদ্যালয় কেন্দ্রীয় খেলার মাঠে জমকালো আয়োজনে অনুষ্ঠিত হবে।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    দিনব্যাপী এই ক্রীড়া মহাযজ্ঞে দৌড়, হাই জাম্প, লং জাম্প, লৌহ গোলক নিক্ষেপ, ডিসকাস থ্রো, যেমন খুশি তেমন সাজো ও স্কাউটস দলের মার্চপাস্ট সহ মোট ৩২টি একক ও দলগত ইভেন্টে বিদ্যালয়ের ৬ষ্ঠ থেকে ১০ম শ্রেণির প্রায় ৮০০ শিক্ষার্থী অংশগ্রহণ করবে।
</p>
<h3 class="text-xl font-bold text-slate-900 mt-6 mb-3">প্রধান অতিথি ও বিশেষ আকর্ষণ</h3>
<p class="mb-4 text-slate-700 leading-relaxed">
    উদ্বোধনী অনুষ্ঠানে প্রধান অতিথি হিসেবে জাতীয় পতাকা উত্তোলন ও মশাল প্রজ্বালন করবেন মান্যবর জেলা প্রশাসক ও বিজ্ঞ জেলা ম্যাজিস্ট্রেট, পাবনা। অনুষ্ঠানে সভাপতিত্ব করবেন সোনাতলা উচ্চ বিদ্যালয় পরিচালনা পর্ষদের সম্মানিত সভাপতি।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    প্রতিযোগিতা শেষে সমাপনী পর্বে বিজয়ীদের মাঝে মেডেল, ট্রফি ও সনদপত্র বিতরণ করা হবে। এছাড়া অভিভাবক ও প্রাক্তন শিক্ষার্থীদের অংশগ্রহণে বিশেষ প্রীতি দৌড় প্রতিযোগিতা অনুষ্ঠিত হবে।
</p>
HTML,
                'category' => 'ক্রীড়া ও সহশিক্ষা',
                'category_slug' => 'sports',
                'status' => 'upcoming',
                'badge_color' => 'bg-amber-50 text-amber-800 border-amber-200',
                'status_badge' => 'bg-emerald-50 text-[#005030] border-emerald-200',
                'status_text' => 'আসন্ন ইভেন্ট',
                'start_date' => '2026-11-15',
                'display_date' => '১৫ নভেম্বর ২০২৬',
                'date_day' => '১৫',
                'date_month' => 'নভেম্বর',
                'date_year' => '২০২৬',
                'time' => 'সকাল ০৯:০০ - বিকাল ০৫:০০',
                'venue' => 'বিদ্যালয় কেন্দ্রীয় খেলার মাঠ, সাঁথিয়া, পাবনা',
                'organizer' => 'ক্রীড়া ও শারীরিক শিক্ষা কমিটি',
                'coordinator' => 'মোঃ রফিকুল ইসলাম (সহকারী শিক্ষক, শারীরিক শিক্ষা)',
                'contact_phone' => '+৮৮০ ১৭১২-৩৪৫৬৭৮',
                'is_featured' => true,
                'image_url' => 'assets/images/news-2.jpg',
                'image_alt' => '৫৮তম বার্ষিক ক্রীড়া প্রতিযোগিতা',
                'schedule' => [
                    [
                        'time' => 'সকাল ০৯:০০',
                        'title' => 'জাতীয় সংগীত ও মশাল প্রজ্বালন',
                        'details' => 'জাতীয় ও প্রাতিষ্ঠানিক পতাকা উত্তোলন, মার্চপাস্ট এবং মশাল প্রজ্বালনের মাধ্যমে প্রতিযোগিতার আনুষ্ঠানিক উদ্বোধন।',
                    ],
                    [
                        'time' => 'সকাল ১০:০০',
                        'title' => 'ট্র্যাক অ্যান্ড ফিল্ড ইভেন্টস (পর্ব-১)',
                        'details' => '১০০ মিটার ও ২০০ মিটার দৌড়, হাই জাম্প এবং লং জাম্প প্রতিযোগিতা (ক, খ ও গ গ্রুপ)।',
                    ],
                    [
                        'time' => 'দুপুর ০১:০০',
                        'title' => 'মধ্যাহ্ন বিরতি ও নামাজের সময়',
                        'details' => 'প্রতিযোগী ও অতিথিবৃন্দের আপ্যায়ন ও বিশ্রাম।',
                    ],
                    [
                        'time' => 'দুপুর ০২:০০',
                        'title' => 'যেমন খুশি তেমন সাজো ও প্রীতি ম্যাচ',
                        'details' => 'শিক্ষার্থীদের বহুরূপী সাজসজ্জা প্রদর্শনী এবং শিক্ষক বনাম প্রাক্তন শিক্ষার্থী প্রীতি ফুটবল ম্যাচ।',
                    ],
                    [
                        'time' => 'বিকাল ০৪:০০',
                        'title' => 'পুরস্কার বিতরণী ও সমাপনী ভাষণ',
                        'details' => 'চ্যাম্পিয়ন ট্রফি প্রদান এবং প্রধান অতিথির অনুপ্রেরণামূলক সমাপনী বক্তব্য।',
                    ],
                ],
            ],
            [
                'id' => 2,
                'slug' => 'science-fair-and-robotics-exhibition-2026',
                'title' => 'আন্তঃবিদ্যালয় বিজ্ঞান মেলা, রোবটিক্স ও আইসিটি প্রদর্শনী ২০২৬',
                'excerpt' => 'তরুণ উদ্ভাবকদের নবায়নযোগ্য জ্বালানি, স্মার্ট কৃষি, এআই প্রযুক্তি ও রোবটিক্স প্রজেক্ট নিয়ে দুই দিনব্যাপী বিজ্ঞান মেলা।',
                'description' => <<<'HTML'
<p class="lead text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-6">
    ডিজিটাল বাংলাদেশ থেকে স্মার্ট বাংলাদেশের অগ্রযাত্রায় শিক্ষার্থীদের মেধার বিকাশ ও প্রযুক্তিমনস্ক করার লক্ষ্যে সোনাতলা উচ্চ বিদ্যালয় আইসিটি ক্লাব ও বিজ্ঞান বিভাগের যৌথ ব্যবস্থাপনায় আয়োজিত হতে যাচ্ছে আন্তঃবিদ্যালয় বিজ্ঞান মেলা ২০২৬।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    মেলায় উপজেলার ২০টি বিদ্যালয়ের প্রায় ৫০টি প্রজেক্ট প্রদর্শিত হবে। প্রজেক্ট ক্যাটাগরিগুলোর মধ্যে রয়েছে: পরিবেশবান্ধব নবায়নযোগ্য জ্বালানি, স্মার্ট কৃষিসেচ প্রযুক্তি, রোবটিক্স ও ড্রোন মেকানিজম, এবং দৈনন্দিন জীবনে সাইবার নিরাপত্তা সচেতনতা।
</p>
<h3 class="text-xl font-bold text-slate-900 mt-6 mb-3">বিশেষ সেমিনার ও কুইজ অলিম্পিয়াড</h3>
<p class="mb-4 text-slate-700 leading-relaxed">
    মেলার দ্বিতীয় দিনে বুয়েট ও রাজশাহী বিশ্ববিদ্যালয়ের তরুণ গবেষকদের সঞ্চালনায় "আগামীর বিশ্বে কৃত্রিম বুদ্ধিমত্তা ও বিজ্ঞান চিন্তা" শীর্ষক বিশেষ মুক্ত সেমিনার অনুষ্ঠিত হবে। মেলা সবার জন্য উন্মুক্ত।
</p>
HTML,
                'category' => 'একাডেমিক ও বিজ্ঞান',
                'category_slug' => 'academic',
                'status' => 'upcoming',
                'badge_color' => 'bg-sky-50 text-sky-800 border-sky-200',
                'status_badge' => 'bg-emerald-50 text-[#005030] border-emerald-200',
                'status_text' => 'আসন্ন ইভেন্ট',
                'start_date' => '2026-11-22',
                'display_date' => '২২-২৩ নভেম্বর ২০২৬',
                'date_day' => '২২',
                'date_month' => 'নভেম্বর',
                'date_year' => '২০২৬',
                'time' => 'সকাল ১০:০০ - বিকাল ০৪:৩০',
                'venue' => 'শেখ রাসেল আধুনিক ডিজিটাল কম্পিউটার ল্যাব ও অডিটোরিয়াম',
                'organizer' => 'বিজ্ঞান বিভাগ ও সোনাতলা ইনোভেটরস ক্লাব',
                'coordinator' => 'মোঃ জাহিদুল ইসলাম (প্রভাষক, পদার্থবিজ্ঞান)',
                'contact_phone' => '+৮৮০ ১৭১২-৩৪৫৬৭৯',
                'is_featured' => false,
                'image_url' => 'assets/images/news-1.jpg',
                'image_alt' => 'বিজ্ঞান মেলা ও রোবটিক্স প্রদর্শনী',
                'schedule' => [
                    [
                        'time' => 'সকাল ১০:০০',
                        'title' => 'প্রজেক্ট স্টল উদ্বোধন ও প্রদর্শন',
                        'details' => 'অতিথিবৃন্দ কর্তৃক ৫০টি উদ্ভাবনী বিজ্ঞান প্রজেক্ট স্টল পরিদর্শন ও ক্ষুদে বিজ্ঞানীদের সাথে প্রশ্নোত্তর।',
                    ],
                    [
                        'time' => 'দুপুর ১২:০০',
                        'title' => 'বিজ্ঞান অলিম্পিয়াড ও কুইজ পর্ব',
                        'details' => 'অংশগ্রহণকারী শিক্ষার্থীদের জন্য তাৎক্ষণিক বিজ্ঞান কুইজ ও যুক্তি প্রতিযোগিতা।',
                    ],
                    [
                        'time' => 'বিকাল ০৩:৩০',
                        'title' => 'সেরা ৩টি উদ্ভাবনী প্রজেক্ট ঘোষণা',
                        'details' => 'বিচারকমণ্ডলীর মূল্যায়নে সেরা রোবটিক্স ও পরিবেশ প্রজেক্টকে গবেষণা অনুদান ও ক্রেস্ট প্রদান।',
                    ],
                ],
            ],
            [
                'id' => 3,
                'slug' => 'victory-day-and-cultural-evening-celebration',
                'title' => 'মহান বিজয় দিবস ও ৫৪তম স্বাধীনতা স্মারক সাংস্কৃতিক সন্ধ্যা',
                'excerpt' => 'জাতীয় স্মৃতিসৌধে শ্রদ্ধাঞ্জলি নিবেদন, মুক্তিযোদ্ধাদের স্মৃতিচারণ, দেশাত্মবোধক গান ও নাটক পরিবেশনার মধ্য দিয়ে মহান বিজয় দিবস উদযাপন।',
                'description' => <<<'HTML'
<p class="lead text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-6">
    বীর মুক্তিযোদ্ধাদের আত্মত্যাগের প্রতি গভীর শ্রদ্ধা এবং নতুন প্রজন্মের মাঝে মুক্তিযুদ্ধের চেতনা সমুন্নত রাখতে ১৬ ডিসেম্বর মহান বিজয় দিবস যথাযোগ্য মর্যাদায় উদযাপিত হবে।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    সূর্যোদয়ের সাথে সাথে বিদ্যালয়ের শহীদ মিনারে পুষ্পস্তবক অর্পণের মধ্য দিয়ে দিবসের শুভ সূচনা হবে। সকাল ৯টায় অনুষ্ঠিত হবে "মুক্তিযুদ্ধের ইতিহাস ও আমাদের দায়বদ্ধতা" শীর্ষক স্মৃতিচারণ আলোচনা সভা, যেখানে উপজেলার বীর মুক্তিযোদ্ধাবৃন্দ উপস্থিত থেকে তাদের সম্মুখ সমরের বীরত্বগাথা তুলে ধরবেন।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    সন্ধ্যায় বিদ্যালয়ের উন্মুক্ত মুক্তমঞ্চে অনুষ্ঠিত হবে মনোজ্ঞ সাংস্কৃতিক অনুষ্ঠান। এতে শিক্ষার্থীদের পরিবেশনায় সমবেত দেশাত্মবোধক সংগীত, আবৃত্তি এবং মুক্তিযুদ্ধভিত্তিক গীতি-নাট্যালেখ্য "রক্তাক্ত একাত্তর" পরিবেশিত হবে।
</p>
HTML,
                'category' => 'জাতীয় দিবস ও শ্রদ্ধাঞ্জলি',
                'category_slug' => 'national',
                'status' => 'upcoming',
                'badge_color' => 'bg-rose-50 text-rose-800 border-rose-200',
                'status_badge' => 'bg-emerald-50 text-[#005030] border-emerald-200',
                'status_text' => 'আসন্ন ইভেন্ট',
                'start_date' => '2026-12-16',
                'display_date' => '১৬ ডিসেম্বর ২০২৬',
                'date_day' => '১৬',
                'date_month' => 'ডিসেম্বর',
                'date_year' => '২০২৬',
                'time' => 'সকাল ০৬:৩০ - রাত ০৮:০০',
                'venue' => 'বিদ্যালয় প্রাঙ্গণ ও কেন্দ্রীয় মুক্তমঞ্চ',
                'organizer' => 'জাতীয় দিবস উদযাপন উপ-কমিটি',
                'coordinator' => 'সৈয়দা সেলিনা পারভীন (সিনিয়র শিক্ষক, বাংলা)',
                'contact_phone' => '+৮৮০ ১৭১২-৩৪৫৬৮০',
                'is_featured' => false,
                'image_url' => 'assets/images/slider-2.jpg',
                'image_alt' => 'মহান বিজয় দিবস উদযাপন',
                'schedule' => [
                    [
                        'time' => 'সকাল ০৬:৩০',
                        'title' => 'শহীদ বেদীতে পুষ্পস্তবক অর্পণ',
                        'details' => 'বিদ্যালয় প্রাঙ্গণের শহীদ মিনারে শিক্ষক, শিক্ষার্থী ও পরিচালনা পর্ষদের পুষ্পার্ঘ্য অর্পণ।',
                    ],
                    [
                        'time' => 'সকাল ০৯:৩০',
                        'title' => 'মুক্তিযোদ্ধাদের সংবর্ধনা ও আলোচনা',
                        'details' => 'স্থানীয় ৫ জন বীর মুক্তিযোদ্ধাকে বিশেষ উত্তরীয় ও সম্মাননা স্মারক প্রদান।',
                    ],
                    [
                        'time' => 'সন্ধ্যা ০৬:০০',
                        'title' => 'বিজয় সাংস্কৃতিক সন্ধ্যা',
                        'details' => 'দেশাত্মবোধক সংগীত, স্বরচিত কবিতা পাঠ এবং নাটক পরিবেশনা।',
                    ],
                ],
            ],
            [
                'id' => 4,
                'slug' => 'ssc-candidates-doa-and-farewell-2026',
                'title' => '২০২৬ সালের এসএসসি পরীক্ষার্থীদের বিদায় সংবর্ধনা ও দোয়া মাহফিল',
                'excerpt' => 'আসন্ন এসএসসি ও দাখিল সমমান পরীক্ষায় অংশগ্রহণকারী শিক্ষার্থীবৃন্দের সাফল্য কামনায় দোয়া মাহফিল ও বিদায় সংবর্ধনা।',
                'description' => <<<'HTML'
<p class="lead text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-6">
    সোনাতলা উচ্চ বিদ্যালয় থেকে ২০২৬ সালের এসএসসি পরীক্ষায় অংশগ্রহণকারী বিজ্ঞান, মানবিক ও ব্যবসায় শিক্ষা শাখার ২৪৫ জন শিক্ষার্থীর বিদায় সংবর্ধনা অনুষ্ঠিত হতে যাচ্ছে।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    অনুষ্ঠানে পরীক্ষার্থীদের মাঝে পরীক্ষার প্রবেশপত্র ও প্রয়োজনীয় শিক্ষা সামগ্রী উপহারস্বরূপ তুলে দেওয়া হবে। সিনিয়র শিক্ষকবৃন্দ পরীক্ষার হলে সময় ব্যবস্থাপনা, মানসিক স্থিরতা ও প্রশ্নোত্তরের কৌশল নিয়ে মূল্যবান দিকনির্দেশনা প্রদান করবেন।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    পরিশেষে পরীক্ষার্থীদের সুস্বাস্থ্য, মেধার সঠিক বিকাশ ও বোর্ড পরীক্ষায় ঈর্ষণীয় ফলাফলের জন্য মহান সৃষ্টিকর্তার দরবারে বিশেষ মোনাজাত অনুষ্ঠিত হবে।
</p>
HTML,
                'category' => 'একাডেমিক ও বিজ্ঞান',
                'category_slug' => 'academic',
                'status' => 'upcoming',
                'badge_color' => 'bg-sky-50 text-sky-800 border-sky-200',
                'status_badge' => 'bg-emerald-50 text-[#005030] border-emerald-200',
                'status_text' => 'আসন্ন ইভেন্ট',
                'start_date' => '2026-12-28',
                'display_date' => '২৮ ডিসেম্বর ২০২৬',
                'date_day' => '২৮',
                'date_month' => 'ডিসেম্বর',
                'date_year' => '২০২৬',
                'time' => 'সকাল ১০:০০ - দুপুর ০১:৩০',
                'venue' => 'বিদ্যালয় প্রধান মিলনায়তন (অডিটোরিয়াম)',
                'organizer' => 'একাডেমিক কাউন্সিল ও বিদায় সংবর্ধনা কমিটি',
                'coordinator' => 'মোসাঃ নাসরিন সুলতানা (প্রধান শিক্ষক)',
                'contact_phone' => '+৮৮০ ১৭১২-৩৪৫৬৭৮',
                'is_featured' => false,
                'image_url' => 'assets/images/slider-1.jpg',
                'image_alt' => 'এসএসসি পরীক্ষার্থীদের বিদায় সংবর্ধনা',
                'schedule' => [
                    [
                        'time' => 'সকাল ১০:০০',
                        'title' => 'কুরআন তিলাওয়াত ও স্বাগত বক্তব্য',
                        'details' => 'পবিত্র ধর্মগ্রন্থ পাঠ এবং দশম শ্রেণির শ্রেণি শিক্ষকদের আশীর্বাদ বাণী।',
                    ],
                    [
                        'time' => 'সকাল ১১:০০',
                        'title' => 'প্রবেশপত্র ও শুভেচ্ছা স্মারক প্রদান',
                        'details' => 'পরীক্ষার্থীদের মাঝে প্রবেশপত্র, কলম বক্স ও পরীক্ষার নির্দেশিকা বিতরণ।',
                    ],
                    [
                        'time' => 'দুপুর ১২:৩০',
                        'title' => 'বিশেষ দোয়া ও মোনাজাত',
                        'details' => 'পরীক্ষার্থীদের সামগ্রিক সাফল্য কামনায় বিশেষ মোনাজাত পরিচালনা।',
                    ],
                ],
            ],
            [
                'id' => 5,
                'slug' => 'international-mother-language-day-celebration',
                'title' => 'আন্তর্জাতিক মাতৃভাষা দিবস ও অমর একুশে ফেব্রুয়ারি উদযাপন',
                'excerpt' => 'প্রভাতফেরি, বর্ণমালা মিছিল, ভাষা শহীদদের স্মরণে কবিতা আবৃত্তি ও হস্তলিপি প্রতিযোগিতা অনুষ্ঠিত হয়েছে।',
                'description' => <<<'HTML'
<p class="lead text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-6">
    অমর একুশে ফেব্রুয়ারি ও আন্তর্জাতিক মাতৃভাষা দিবস উপলক্ষে সোনাতলা উচ্চ বিদ্যালয় প্রাঙ্গণে যথাযোগ্য ভাবগাম্ভীর্য ও শ্রদ্ধায় পালিত হয়েছে ভাষা শহীদ দিবস।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    ভোর ৬টায় খালি পায়ে প্রভাতফেরি সহকারে শিক্ষক ও শিক্ষার্থীরা সোনাতলা বাজারের প্রধান সড়ক প্রদক্ষিণ করে বিদ্যালয় শহীদ মিনারে এসে পুষ্পস্তবক অর্পণ করে।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    দিবসটি উপলক্ষে আয়োজিত সুন্দর হাতের লেখা ও বাংলা বানান প্রতিযোগিতায় বিজয়ী শিক্ষার্থীদের মাঝে বই ও মেডেল উপহার প্রদান করা হয়।
</p>
HTML,
                'category' => 'জাতীয় দিবস ও শ্রদ্ধাঞ্জলি',
                'category_slug' => 'national',
                'status' => 'completed',
                'badge_color' => 'bg-rose-50 text-rose-800 border-rose-200',
                'status_badge' => 'bg-slate-100 text-slate-600 border-slate-300',
                'status_text' => 'সম্পন্ন',
                'start_date' => '2026-02-21',
                'display_date' => '২১ ফেব্রুয়ারি ২০২৬',
                'date_day' => '২১',
                'date_month' => 'ফেব্রুয়ারি',
                'date_year' => '২০২৬',
                'time' => 'সকাল ০৬:০০ - দুপুর ১২:৩০',
                'venue' => 'বিদ্যালয় শহীদ মিনার ও সভাকক্ষ',
                'organizer' => 'সাহিত্য ও সংস্কৃতি সংসদ',
                'coordinator' => 'মোঃ শফিকুল ইসলাম (সহকারী শিক্ষক)',
                'contact_phone' => '+৮৮০ ১৭১২-৩৪৫৬৭৮',
                'is_featured' => false,
                'image_url' => 'assets/images/slider-3.jpg',
                'image_alt' => 'আন্তর্জাতিক মাতৃভাষা দিবস প্রভাতফেরি',
                'schedule' => [
                    [
                        'time' => 'সকাল ০৬:০০',
                        'title' => 'প্রভাতফেরি ও শহীদ মিনারে পুষ্পস্তবক',
                        'details' => '"আমার ভাইয়ের রক্তে রাঙানো একুশে ফেব্রুয়ারি" গানে খালি পায়ে প্রভাতফেরি।',
                    ],
                    [
                        'time' => 'সকাল ০৯:০০',
                        'title' => 'হস্তলিপি ও বানান প্রতিযোগিতা',
                        'details' => '৬ষ্ঠ থেকে ৮ম শ্রেণির শিক্ষার্থীদের সুন্দর বাংলা হাতের লেখা প্রতিযোগিতা।',
                    ],
                ],
            ],
            [
                'id' => 6,
                'slug' => 'monsoon-tree-plantation-campaign-2026',
                'title' => 'সবুজ শ্যামল সোনাতলা: বর্ষাকালীন বৃক্ষরোপণ সপ্তাহ ও চারা বিতরণ কর্মসূচি',
                'excerpt' => 'পরিবেশের ভারসাম্য রক্ষা ও গ্রিন ক্যাম্পাস রূপায়নে ১০০০ ফলদ, বনজ ও ওষধি বৃক্ষের চারা রোপণ ও বিতরণ সম্পন্ন হয়েছে।',
                'description' => <<<'HTML'
<p class="lead text-base sm:text-lg text-slate-700 font-medium leading-relaxed mb-6">
    জলবায়ু পরিবর্তনের ক্ষতিকর প্রভাব মোকাবিলা এবং ভবিষ্যৎ প্রজন্মের জন্য একটি বাসযোগ্য সবুজ পৃথিবী নিশ্চিত করতে সোনাতলা উচ্চ বিদ্যালয় ইকো ক্লাব পরিচালিত সপ্তাহব্যাপী বৃক্ষরোপণ অভিযান সফলভাবে সম্পন্ন হয়েছে।
</p>
<p class="mb-4 text-slate-700 leading-relaxed">
    অভিযানে বিদ্যালয়ের খেলার মাঠের চারপাশ, সীমানা প্রাচীর এবং শিক্ষার্থীদের নিজ নিজ বসতবাড়ির আঙিনায় রোপণের জন্য বিনামূল্যে আম, কাঁঠাল, নিম, আমলকী ও মেহগনি গাছের উন্নত জাতের চারা বিতরণ করা হয়েছে।
</p>
HTML,
                'category' => 'সামাজিক ও পরিবেশ',
                'category_slug' => 'community',
                'status' => 'completed',
                'badge_color' => 'bg-emerald-50 text-[#005030] border-emerald-200',
                'status_badge' => 'bg-slate-100 text-slate-600 border-slate-300',
                'status_text' => 'সম্পন্ন',
                'start_date' => '2026-07-10',
                'display_date' => '১০-১৭ জুলাই ২০২৬',
                'date_day' => '১০',
                'date_month' => 'জুলাই',
                'date_year' => '২০২৬',
                'time' => 'সকাল ১০:০০ - বিকাল ০৪:০০',
                'venue' => 'বিদ্যালয় বোটানিক্যাল গার্ডেন ও চত্বর',
                'organizer' => 'সোনাতলা ইকো ক্লাব ও বাংলাদেশ স্কাউটস',
                'coordinator' => 'মোঃ আবু জাফর (কৃষি শিক্ষা শিক্ষক)',
                'contact_phone' => '+৮৮০ ১৭১২-৩৪৫৬৭৮',
                'is_featured' => false,
                'image_url' => 'assets/images/news-3.jpg',
                'image_alt' => 'বৃক্ষরোপণ কর্মসূচি ও চারা বিতরণ',
                'schedule' => [
                    [
                        'time' => 'সকাল ১০:০০',
                        'title' => 'চারা বিতরণ ও বৃক্ষরোপণ সমাবেশ',
                        'details' => 'শিক্ষার্থীদের মাঝে বিনামূল্যে চারা বিতরণ ও ফলদ বৃক্ষরোপণের শপথ গ্রহণ।',
                    ],
                ],
            ],
        ]);
    }
}
