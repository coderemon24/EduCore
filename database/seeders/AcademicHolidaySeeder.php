<?php

namespace Database\Seeders;

use App\Models\AcademicHoliday;
use App\Models\AcademicYear;
use App\Models\School;
use Illuminate\Database\Seeder;

class AcademicHolidaySeeder extends Seeder
{
    /**
     * Run the database seeds for academic holidays.
     */
    public function run(): void
    {
        $school = School::first();

        if (! $school) {
            return;
        }

        $year2025 = AcademicYear::withoutGlobalScopes()
            ->where('school_id', $school->id)
            ->where('name', 'like', '%2025%')
            ->first();

        $year2026 = AcademicYear::withoutGlobalScopes()
            ->where('school_id', $school->id)
            ->where('name', 'like', '%2026%')
            ->first();

        if ($year2025) {
            $this->seedHolidaysFor2025($school->id, $year2025->id);
        }

        if ($year2026) {
            $this->seedHolidaysFor2026($school->id, $year2026->id);
        }
    }

    /**
     * Seed realistic institutional holidays for 2025-2026 academic session.
     */
    protected function seedHolidaysFor2025(int $schoolId, int $academicYearId): void
    {
        $holidays = [
            [
                'title' => 'শহীদ দিবস ও আন্তর্জাতিক মাতৃভাষা দিবস',
                'start_date' => '2025-02-21',
                'end_date' => '2025-02-21',
                'type' => 'holiday',
                'description' => 'মহান ২১শে ফেব্রুয়ারি ভাষা আন্দোলনের অমর শহীদদের স্মরণে জাতীয় ছুটির দিন। ক্যাম্পাসে জাতীয় পতাকা অর্ধনমিত থাকবে এবং প্রভাতফেরি ও আলোচনা সভা অনুষ্ঠিত হবে। সাধারণ পাঠদান কার্যক্রম বন্ধ থাকবে।',
            ],
            [
                'title' => 'পবিত্র শবে বরাত',
                'start_date' => '2025-02-25',
                'end_date' => '2025-02-25',
                'type' => 'holiday',
                'description' => 'পবিত্র লাইলাতুল বরাত উপলক্ষে সাধারণ ছুটি। সকল একাডেমিক ও প্রশাসনিক শাখা বন্ধ থাকবে।',
            ],
            [
                'title' => 'জাতির পিতা বঙ্গবন্ধু শেখ মুজিবুর রহমান-এর জন্মদিবস ও জাতীয় শিশু দিবস',
                'start_date' => '2025-03-17',
                'end_date' => '2025-03-17',
                'type' => 'holiday',
                'description' => 'জাতীয় শিশু দিবস উদযাপন উপলক্ষে সকল ক্লাস স্থগিত থাকবে। শিক্ষার্থীদের জন্য চিত্রাঙ্কন ও রচনা প্রতিযোগিতা অনুষ্ঠিত হবে।',
            ],
            [
                'title' => 'পবিত্র রমজান ও ঈদুল ফিতর অবকাশ',
                'start_date' => '2025-03-24',
                'end_date' => '2025-04-05',
                'type' => 'holiday',
                'description' => 'পবিত্র মাহে রমজানের শেষ দশক, জুমাতুল বিদা, শবে কদর এবং ঈদুল ফিতর উপলক্ষে দীর্ঘ ১২ দিনের বার্ষিক ধর্মীয় ছুটি। সকল শিক্ষার্থী ও শিক্ষকদের জন্য পূর্ণ অবকাশ থাকবে।',
            ],
            [
                'title' => 'মহান স্বাধীনতা ও জাতীয় দিবস',
                'start_date' => '2025-03-26',
                'end_date' => '2025-03-26',
                'type' => 'holiday',
                'description' => '২৬শে মার্চ মহান স্বাধীনতা দিবস উপলক্ষে জাতীয় ছুটির দিন। সকাল ৯:০০ টায় ক্যাম্পাসে পতাকা উত্তোলন ও কুচকাওয়াজ অনুষ্ঠিত হবে।',
            ],
            [
                'title' => 'বাংলা নববর্ষ (পহেলা বৈশাখ ১৪৩২)',
                'start_date' => '2025-04-14',
                'end_date' => '2025-04-14',
                'type' => 'holiday',
                'description' => 'শুভ নববর্ষ ও পহেলা বৈশাখ উপলক্ষে সাধারণ ছুটি। মঙ্গল শোভাযাত্রা ও সাংস্কৃতিক অনুষ্ঠান আয়োজিত হবে।',
            ],
            [
                'title' => 'মে দিবস (আন্তর্জাতিক শ্রমিক দিবস)',
                'start_date' => '2025-05-01',
                'end_date' => '2025-05-01',
                'type' => 'holiday',
                'description' => 'শ্রমিক সংহতি ও অধিকার রক্ষার বৈশ্বিক দিবস উপলক্ষে সরকারি ছুটি। বিদ্যালয়ের সার্বিক কার্যক্রম বন্ধ থাকবে।',
            ],
            [
                'title' => 'শুভ বুদ্ধ পূর্ণিমা (বৈশাখী পূর্ণিমা)',
                'start_date' => '2025-05-12',
                'end_date' => '2025-05-12',
                'type' => 'holiday',
                'description' => 'গৌতম বুদ্ধের জন্ম, বোধিলাভ ও মহাপরিনির্বাণ স্মরণে ধর্মীয় ছুটি।',
            ],
            [
                'title' => 'গ্রীষ্মকালীন অবকাশ ও পবিত্র ঈদুল আজহা',
                'start_date' => '2025-06-03',
                'end_date' => '2025-06-15',
                'type' => 'holiday',
                'description' => 'গ্রীষ্মের তীব্র দাবদাহে শিক্ষার্থীদের স্বাস্থ্য সুরক্ষা এবং পবিত্র ঈদুল আজহার কুরবানি উপলক্ষে ১৩ দিনের সমন্বিত অবকাশ। হোমওয়ার্ক ও প্রজেক্ট নির্দেশিকা অনলাইনে সরবরাহ করা হবে।',
            ],
            [
                'title' => 'পবিত্র আশুরা (১০ই মহররম)',
                'start_date' => '2025-07-06',
                'end_date' => '2025-07-06',
                'type' => 'holiday',
                'description' => 'কারবালার ঐতিহাসিক শোকাবহ ঘটনার স্মরণে পবিত্র আশুরা উপলক্ষে সরকারি ছুটি।',
            ],
            [
                'title' => 'শ্রীকৃষ্ণের শুভ জন্মাষ্টমী',
                'start_date' => '2025-08-16',
                'end_date' => '2025-08-16',
                'type' => 'holiday',
                'description' => 'সনাতন ধর্মাবলম্বীদের অন্যতম প্রধান উৎসব শ্রীকৃষ্ণের শুভ জন্মাষ্টমী উপলক্ষে বিদ্যালয়ে সাধারণ ছুটি।',
            ],
            [
                'title' => 'অর্ধ-বার্ষিক পরীক্ষা প্রস্তুতিমূলক বিশেষ ছুটি',
                'start_date' => '2025-09-18',
                'end_date' => '2025-09-20',
                'type' => 'exam_break',
                'description' => 'সকল শ্রেণির ১ম পর্ব/অর্ধ-বার্ষিক মূল্যায়নের প্রস্তুতি গ্রহণে শিক্ষার্থীদের ৩ দিনের রিভিশন স্টাডি লিভ। নিয়মিত ক্লাস বন্ধ থাকবে কিন্তু লাইব্রেরি উন্মুক্ত থাকবে।',
            ],
            [
                'title' => 'শুভ দুর্গাপূজা ও ফাতেহা-ই-ইয়াজদাহম অবকাশ',
                'start_date' => '2025-09-29',
                'end_date' => '2025-10-04',
                'type' => 'holiday',
                'description' => 'শারদীয় দুর্গোৎসব (মহা সপ্তমী থেকে বিজয়া দশমী) ও ফাতেহা-ই-ইয়াজদাহম উপলক্ষে ৬ দিনের সমন্বিত উৎসব ছুটি।',
            ],
            [
                'title' => 'মহান বিজয় দিবস',
                'start_date' => '2025-12-16',
                'end_date' => '2025-12-16',
                'type' => 'holiday',
                'description' => '১৬ই ডিসেম্বর জাতীয় বিজয় দিবস। বীর শহীদদের শ্রদ্ধা জ্ঞাপনে প্রভাতফেরি ও পুষ্পস্তবক অর্পণ কর্মসূচি পালিত হবে।',
            ],
            [
                'title' => 'শীতকালীন অবকাশ ও খ্রিষ্টানদের শুভ বড়দিন',
                'start_date' => '2025-12-22',
                'end_date' => '2025-12-31',
                'type' => 'holiday',
                'description' => 'শুভ বড়দিন, যিশুখ্রিষ্টের জন্মোৎসব ও বার্ষিক শীতকালীন অবকাশ উপলক্ষে ১০ দিনের ছুটি। নতুন শিক্ষাবর্ষের পাঠ্যপুস্তক বিতরণ পয়লা জানুয়ারি অনুষ্ঠিত হবে।',
            ],
        ];

        foreach ($holidays as $item) {
            AcademicHoliday::withoutGlobalScopes()->updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'academic_year_id' => $academicYearId,
                    'title' => $item['title'],
                ],
                [
                    'start_date' => $item['start_date'],
                    'end_date' => $item['end_date'],
                    'type' => $item['type'],
                    'description' => $item['description'],
                ]
            );
        }
    }

    /**
     * Seed holidays for 2026-2027 session.
     */
    protected function seedHolidaysFor2026(int $schoolId, int $academicYearId): void
    {
        $holidays = [
            [
                'title' => 'শহীদ দিবস ও আন্তর্জাতিক মাতৃভাষা দিবস',
                'start_date' => '2026-02-21',
                'end_date' => '2026-02-21',
                'type' => 'holiday',
                'description' => 'ভাষা শহীদদের আত্মত্যাগের স্মরণে আন্তর্জাতিক মাতৃভাষা দিবসের সরকারি ছুটি।',
            ],
            [
                'title' => 'পবিত্র ঈদুল ফিতর অবকাশ',
                'start_date' => '2026-03-18',
                'end_date' => '2026-03-28',
                'type' => 'holiday',
                'description' => 'পবিত্র ঈদুল ফিতর উৎসব উপলক্ষে ১১ দিনের শিক্ষাপ্রতিষ্ঠান অবকাশ।',
            ],
            [
                'title' => 'মহান স্বাধীনতা ও জাতীয় দিবস',
                'start_date' => '2026-03-26',
                'end_date' => '2026-03-26',
                'type' => 'holiday',
                'description' => 'মহান স্বাধীনতা দিবস উপলক্ষে ক্যাম্পাসে জাতীয় কর্মসূচি ও সাধারণ ছুটি।',
            ],
            [
                'title' => 'বাংলা নববর্ষ (পহেলা বৈশাখ ১৪৩৩)',
                'start_date' => '2026-04-14',
                'end_date' => '2026-04-14',
                'type' => 'holiday',
                'description' => 'পহেলা বৈশাখ ও বাংলা বর্ষবরণ উৎসব ছুটি।',
            ],
            [
                'title' => 'গ্রীষ্মকালীন অবকাশ ও পবিত্র ঈদুল আজহা',
                'start_date' => '2026-05-24',
                'end_date' => '2026-06-04',
                'type' => 'holiday',
                'description' => 'বার্ষিক গ্রীষ্মকালীন ও পবিত্র ঈদুল আজহার ১২ দিনের সমন্বিত শিক্ষাপ্রতিষ্ঠান ছুটি।',
            ],
            [
                'title' => 'শারদীয় দুর্গাপূজা ও বিজয়া দশমী',
                'start_date' => '2026-10-18',
                'end_date' => '2026-10-23',
                'type' => 'holiday',
                'description' => 'শারদীয় দুর্গোৎসব ও বিজয়া দশমী উপলক্ষে ৬ দিনের আনুষ্ঠানিক ছুটি।',
            ],
            [
                'title' => 'বার্ষিক পরীক্ষা সমাপনী ও শীতকালীন অবকাশ',
                'start_date' => '2026-12-20',
                'end_date' => '2026-12-31',
                'type' => 'holiday',
                'description' => 'বার্ষিক মূল্যায়ন সমাপনী ও খ্রিষ্টীয় শুভ বড়দিন উপলক্ষে ১২ দিনের শীতকালীন ছুটি।',
            ],
        ];

        foreach ($holidays as $item) {
            AcademicHoliday::withoutGlobalScopes()->updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'academic_year_id' => $academicYearId,
                    'title' => $item['title'],
                ],
                [
                    'start_date' => $item['start_date'],
                    'end_date' => $item['end_date'],
                    'type' => $item['type'],
                    'description' => $item['description'],
                ]
            );
        }
    }
}
