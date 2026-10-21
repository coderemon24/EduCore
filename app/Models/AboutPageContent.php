<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AboutPageContent extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'about_page_contents';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'school_id',
        'title',
        'subtitle',
        'established_year',
        'banner_image',
        'history_heading',
        'history_content',
        'history_image',
        'vision_title',
        'vision_text',
        'mission_title',
        'mission_text',
        'values_title',
        'values_list',
        'milestones',
        'statistics',
        'at_a_glance',
        'committee_members',
        'facilities',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'banner_image_url',
        'history_image_url',
        'og_image_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'values_list' => 'array',
            'milestones' => 'array',
            'statistics' => 'array',
            'at_a_glance' => 'array',
            'committee_members' => 'array',
            'facilities' => 'array',
        ];
    }

    /**
     * Get the school that owns this about page content.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Public URL for the hero banner image.
     */
    public function getBannerImageUrlAttribute(): ?string
    {
        if (! empty($this->banner_image)) {
            $path = ltrim($this->banner_image, '/\\');
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        return null;
    }

    /**
     * Public URL for the featured campus/history photograph.
     */
    public function getHistoryImageUrlAttribute(): ?string
    {
        if (! empty($this->history_image)) {
            $path = ltrim($this->history_image, '/\\');
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        return asset('assets/images/slider-1.jpg');
    }

    /**
     * Public URL for the OpenGraph social share image.
     */
    public function getOgImageUrlAttribute(): ?string
    {
        if (! empty($this->og_image)) {
            $path = ltrim($this->og_image, '/\\');
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        return $this->history_image_url;
    }

    /**
     * Retrieve the about page record for a specific school or return an authentic fallback instance.
     */
    public static function forSchool(?int $schoolId): self
    {
        if ($schoolId) {
            $content = static::where('school_id', $schoolId)->first();
            if ($content) {
                return $content;
            }
        }

        // Return a populated, non-persisted fallback instance
        $instance = new static([
            'school_id' => $schoolId ?? 1,
            'title' => 'সোনাতলা উচ্চ বিদ্যালয় — ইতিহাস ও ঐতিহ্য',
            'subtitle' => 'জ্ঞান, শৃঙ্খলা ও নৈতিক শিক্ষার প্রত্যয়ে ১৯৬৮ সাল থেকে নিরবচ্ছিন্ন অগ্রযাত্রা',
            'established_year' => '১৯৬৮',
            'history_heading' => 'বিদ্যালয়ের গৌরবোজ্জ্বল ইতিহাস ও পটভূমি',
            'history_content' => "পাবনা জেলার সাঁথিয়া উপজেলার অন্তর্গত বেড়া-সোনাতলা এলাকায় ১৯৬৮ সালে স্থানীয় শিক্ষানুরাগী, সমাজসেবক ও বিদ্যোৎসাহী ব্যক্তিবর্গের ঐকান্তিক প্রচেষ্টায় সোনাতলা উচ্চ বিদ্যালয় প্রতিষ্ঠিত হয়। সূচনালগ্ন থেকেই প্রতিষ্ঠানটি এই অঞ্চলের সাধারণ পরিবারের সন্তানদের মধ্যে আধুনিক ও নৈতিক শিক্ষার আলো বিস্তার করে আসছে।\n\nবর্তমানে বিদ্যালয়টিতে সুবিশাল খেলার মাঠ, দ্বিতল ও তিনতল বিশিষ্ট আধুনিক একাডেমিক ভবন, সমৃদ্ধ বিজ্ঞানাগার, শেখ রাসেল ডিজিটাল কম্পিউটার ল্যাব এবং সুপরিসর লাইব্রেরি রয়েছে। বিদ্যালয়ের দক্ষ ও অভিজ্ঞ শিক্ষকমণ্ডলী নিরলসভাবে পাঠদান কার্যক্রমে নিবেদিত রয়েছেন।",
            'vision_title' => 'আমাদের লক্ষ্য (Vision)',
            'vision_text' => 'প্রতিটি শিক্ষার্থীকে সুনাগরিক, দেশপ্রেমিক, আধুনিক বিজ্ঞানমনস্ক ও মানবিক মূল্যবোধসম্পন্ন মানুষ হিসেবে গড়ে তোলা।',
            'mission_title' => 'আমাদের উদ্দেশ্য (Mission)',
            'mission_text' => 'আনন্দমুখর ও সুশৃঙ্খল পরিবেশে মানসম্মত শিক্ষা নিশ্চিত করা এবং আধুনিক তথ্যপ্রযুক্তির মাধ্যমে শিক্ষার্থীদের ভবিষ্যতের উপযোগী করে তোলা।',
            'values_title' => 'আমাদের মূল আদর্শ ও মূল্যবোধ',
            'values_list' => [
                'সততা ও নৈতিক নিষ্ঠা',
                'শৃঙ্খলা ও কঠোর নিয়মানুবর্তিতা',
                'দেশপ্রেম ও সামাজিক দায়বদ্ধতা',
                'আধুনিক বিজ্ঞানমনস্কতা ও মুক্তবুদ্ধি চর্চা',
            ],
            'milestones' => [
                [
                    'year' => '১৯৬৮',
                    'title' => 'বিদ্যালয় প্রতিষ্ঠা',
                    'description' => 'পাবনার সাঁথিয়া উপজেলার শিক্ষানুরাগী ব্যক্তিবর্গের উদ্যোগে বিদ্যালয়টির শুভ সূচনা ঘটে।',
                ],
                [
                    'year' => '১৯৮৫',
                    'title' => 'প্রথম এসএসসি ব্যাচ',
                    'description' => 'মাধ্যমিক পরীক্ষায় শতভাগ কৃতকার্যতার সাথে প্রথম ব্যাচ সাফল্য অর্জন করে।',
                ],
                [
                    'year' => '২০০৫',
                    'title' => 'বিজ্ঞান ও কম্পিউটার শাখা চালু',
                    'description' => 'আধুনিক যুগোপযোগী বিজ্ঞান ও তথ্যপ্রযুক্তি শিক্ষার নতুন দিগন্ত উন্মোচিত হয়।',
                ],
                [
                    'year' => '২০১৮', 'title' => 'শেখ রাসেল ডিজিটাল ল্যাব স্থাপন',
                    'description' => 'উন্নত কম্পিউটার প্রশিক্ষণ ও আধুনিক মাল্টিমিডিয়া ক্লাসরুম সংযোজন করা হয়।',
                ],
                [
                    'year' => '২০২৪',
                    'title' => 'ডিজিটাল স্মার্ট ক্যাম্পাস রূপান্তর',
                    'description' => 'সম্পূর্ণ স্বয়ংক্রিয় স্মার্ট স্কুল ম্যানেজমেন্ট ও অনলাইন একাডেমিক ব্যবস্থা চালু।',
                ],
            ],
            'statistics' => [
                ['label' => 'বর্তমান শিক্ষার্থী', 'value' => '১২৫০+', 'note' => 'নিয়মিত অধ্যয়নরত শিক্ষার্থী'],
                ['label' => 'অভিজ্ঞ শিক্ষক-শিক্ষিকা', 'value' => '৪২', 'note' => 'উচ্চশিক্ষিত ও প্রশিক্ষণপ্রাপ্ত'],
                ['label' => 'পাসের গড় হার', 'value' => '৯৮.৫%', 'note' => 'বিগত ৫ বছরের বোর্ড পরীক্ষায়'],
                ['label' => 'গৌরবোজ্জ্বল ঐতিহ্য', 'value' => '৫৬+', 'note' => 'বছরের নিরবচ্ছিন্ন সেবাদান'],
            ],
            'at_a_glance' => [
                ['key' => 'প্রতিষ্ঠানের নাম', 'value' => 'সোনাতলা উচ্চ বিদ্যালয়'],
                ['key' => 'EIIN নম্বর', 'value' => '১২৫৬৯৩'],
                ['key' => 'স্কুল কোড', 'value' => '৮১০৭'],
                ['key' => 'এমপিও কোড', 'value' => '৮১০৭০২১৩০২'],
                ['key' => 'শিক্ষা বোর্ড', 'value' => 'মাধ্যমিক ও উচ্চমাধ্যমিক শিক্ষা বোর্ড, রাজশাহী'],
                ['key' => 'ডাকঘর ও উপজেলা', 'value' => 'সোনাতলা, সাঁথিয়া, পাবনা'],
                ['key' => 'বিদ্যালয়ের স্তর', 'value' => 'মাধ্যমিক (৬ষ্ঠ থেকে ১০ম শ্রেণি)'],
                ['key' => 'শিফট', 'value' => 'একক প্রভাতী ও দিবা শিফট'],
                ['key' => 'পাঠ্যক্রম', 'value' => 'জাতীয় শিক্ষাক্রম ও পাঠ্যপুস্তক বোর্ড (NCTB)'],
            ],
            'committee_members' => [
                ['name' => 'জনাব মো: রফিকুল ইসলাম', 'designation' => 'সভাপতি', 'role' => 'বিশিষ্ট শিক্ষানুরাগী ও সমাজসেবক'],
                ['name' => 'মো: খলিলুর রহমান', 'designation' => 'সদস্য সচিব', 'role' => 'প্রধান শিক্ষক (পদাধিকারবলে)'],
                ['name' => 'জনাব মো: জয়নাল আবেদীন', 'designation' => 'অভিভাবক সদস্য', 'role' => 'নির্বাচিত অভিভাবক প্রতিনিধি'],
                ['name' => 'মো: আব্দুল মজিদ', 'designation' => 'শিক্ষক প্রতিনিধি', 'role' => 'সিনিয়র সহকারী শিক্ষক'],
            ],
            'facilities' => [
                ['title' => 'শেখ রাসেল ডিজিটাল কম্পিউটার ল্যাব', 'description' => 'উচ্চগতির ইন্টারনেট সুবিধাসহ অত্যাধুনিক কম্পিউটার ল্যাব।', 'icon' => 'computer'],
                ['title' => 'সমৃদ্ধ বিজ্ঞানাগার', 'description' => 'পদার্থ, রসায়ন ও জীববিজ্ঞানের ব্যবহারিক পরীক্ষার সম্পূর্ণ আধুনিক সরঞ্জাম।', 'icon' => 'beaker'],
                ['title' => 'সুপরিসর লাইব্রেরি', 'description' => 'হাজারো একাডেমিক, রেফারেন্স, ইতিহাস ও সাহিত্যের সমৃদ্ধ বইয়ের সমাহার।', 'icon' => 'book-open'],
                ['title' => 'সুবিশাল খেলার মাঠ', 'description' => 'নিয়মিত শরীরচর্চা, ক্রিকেট ও ফুটবল টুর্নামেন্টের জন্য সবুজ উন্মুক্ত প্রান্তর।', 'icon' => 'trophy'],
            ],
            'meta_title' => 'বিদ্যালয় পরিচিতি ও ইতিহাস | সোনাতলা উচ্চ বিদ্যালয়',
            'meta_description' => 'সোনাতলা উচ্চ বিদ্যালয়, সাঁথিয়া, পাবনার ইতিহাস, লক্ষ্য, উদ্দেশ্য, অর্জন ও প্রাতিষ্ঠানিক পরিচিতি।',
            'meta_keywords' => 'সোনাতলা উচ্চ বিদ্যালয়, Sonatala High School, ইতিহাস, সাঁথিয়া পাবনা, EIIN 125693, বিদ্যালয় পরিচিতি',
        ]);

        return $instance;
    }
}
