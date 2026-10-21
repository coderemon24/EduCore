<?php

namespace Database\Seeders;

use App\Models\Notice;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::first();
        if (! $school) {
            return;
        }

        $user = User::first();
        $userId = $user ? $user->id : null;

        $notices = [
            [
                'title' => 'নির্বাচনী পরীক্ষা- ২০২৬ খ্রি. দশম শ্রেণী ভোকেশনাল',
                'slug' => 'election-exam-2026-class-10-vocational',
                'type' => 'exam',
                'target_audience' => 'students',
                'published_at' => Carbon::create(2026, 10, 21, 10, 0, 0),
                'content' => 'সোনাতলা উচ্চ বিদ্যালয়ের ২০২৬ শিক্ষাবর্ষের দশম শ্রেণির ভোকেশনাল শাখার সকল শিক্ষার্থীদের নির্বাচনী (টেস্ট) পরীক্ষা সংক্রান্ত নির্দেশনাবলী প্রকাশিত হলো। সকল শিক্ষার্থীকে নির্ধারিত সময়ে উপস্থিত থাকার নির্দেশ দেওয়া যাচ্ছে।',
                'is_pinned' => true,
                'status' => 'published',
            ],
            [
                'title' => 'নির্বাচনী পরীক্ষা ২০২৬ খ্রি. রুটিন।',
                'slug' => 'election-exam-routine-2026',
                'type' => 'general',
                'target_audience' => 'all',
                'published_at' => Carbon::create(2026, 10, 15, 11, 30, 0),
                'content' => '২০২৬ শিক্ষাবর্ষের দশম শ্রেণি ও এসএসসি পরীক্ষার্থীদের নির্বাচনী পরীক্ষার বিস্তারিত রুটিন ও সময়সূচি প্রকাশিত হয়েছে। সকল শিক্ষার্থী অফিস থেকে প্রবেশপত্র সংগ্রহ করবে।',
                'is_pinned' => true,
                'status' => 'published',
            ],
            [
                'title' => 'একাডেমিক ট্রান্সক্রিপ্ট সংগ্রহ করার জন্য জানানো হলো।',
                'slug' => 'academic-transcript-collection-notice',
                'type' => 'general',
                'target_audience' => 'students',
                'published_at' => Carbon::create(2026, 10, 13, 9, 15, 0),
                'content' => 'সকল উত্তীর্ণ শিক্ষার্থীদের অবগতির জন্য জানানো যাচ্ছে যে, বিদ্যালয় অফিস কক্ষ থেকে মূল একাডেমিক ট্রান্সক্রিপ্ট ও নম্বরপত্র সংগ্রহ করা যাবে।',
                'is_pinned' => false,
                'status' => 'published',
            ],
            [
                'title' => 'জুনিয়র বৃত্তি পরীক্ষা ২০২৬ রেজিস্ট্রেশন ফরম',
                'slug' => 'junior-scholarship-exam-registration-2026',
                'type' => 'academic',
                'target_audience' => 'students',
                'published_at' => Carbon::create(2026, 9, 7, 12, 0, 0),
                'content' => '২০২৬ সালের জুনিয়র বৃত্তি পরীক্ষায় অংশগ্রহণে আগ্রহী ৮ম শ্রেণির মেধাবী শিক্ষার্থীদের রেজিস্ট্রেশন ফরম জমা দেওয়ার আহ্বান জানানো হচ্ছে।',
                'is_pinned' => false,
                'status' => 'published',
            ],
            [
                'title' => '১০ম শ্রেণির অভিভাবক সমাবেশের আয়োজন করা হয়েছে।',
                'slug' => 'class-10-parents-meeting-2026',
                'type' => 'general',
                'target_audience' => 'guardians',
                'published_at' => Carbon::create(2026, 8, 18, 15, 0, 0),
                'content' => '১০ম শ্রেণির সকল শিক্ষার্থীদের সার্বিক পাঠোন্নতি, উপস্থিতি ও টেস্ট পরীক্ষার প্রস্তুতি পর্যালোচনার জন্য অভিভাবক সমাবেশের আয়োজন করা হয়েছে। সকল অভিভাবককে উপস্থিত থাকার অনুরোধ করা হলো।',
                'is_pinned' => false,
                'status' => 'published',
            ],
        ];

        foreach ($notices as $noticeData) {
            Notice::updateOrCreate(
                [
                    'school_id' => $school->id,
                    'slug' => $noticeData['slug'],
                ],
                array_merge($noticeData, [
                    'school_id' => $school->id,
                    'created_by' => $userId,
                ])
            );
        }
    }
}
