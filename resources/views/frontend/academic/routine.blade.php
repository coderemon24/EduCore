@extends('layouts.app')

@section('title', 'ক্লাস ও পরীক্ষার রুটিন | সোনাতলা উচ্চ বিদ্যালয়')

@section('content')
{{-- Breadcrumb & Title --}}
<div class="bg-gray-100 border-b border-gray-200 py-3 text-xs text-gray-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div class="flex items-center gap-1.5 font-medium">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030]">প্রচ্ছদ</a>
            <span>/</span>
            <span class="text-gray-900 font-bold">একাডেমিক রুটিন</span>
        </div>
        <span class="text-gray-400">শিক্ষাবর্ষ: ২০২৬ খ্রি.</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- Header Banner --}}
    <div class="bg-[#005030] text-white p-5 rounded shadow-2xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold">ক্লাস ও পরীক্ষার সময়সূচি (Academic Routine)</h1>
            <p class="text-xs text-emerald-200 mt-0.5">
                সোনাতলা উচ্চ বিদ্যালয়ের ২০২৬ শিক্ষাবর্ষের নিয়মিত ক্লাস ও পরীক্ষার রুটিন।
            </p>
        </div>
        <div>
            <span class="px-3 py-1 rounded bg-[#003820] text-emerald-200 text-xs font-semibold border border-emerald-700">
                কার্যকর: ০১ জানুয়ারি ২০২৬ থেকে
            </span>
        </div>
    </div>

    {{-- Class Routine Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Main Timetable Table (Col 8) --}}
        <div class="lg:col-span-8 bg-white border border-gray-300 rounded shadow-2xs overflow-hidden space-y-4 p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-200 pb-3">
                <h3 class="font-bold text-base text-gray-900">
                    দৈনন্দিন শ্রেণি পাঠদান সময়সূচি (রুটিন)
                </h3>
                <div class="text-xs text-gray-500 font-medium">
                    শ্রেণি: ৬ষ্ঠ থেকে ১০ম শ্রেণি (প্রভাতী ও দিবা শাখা)
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border border-gray-300">
                    <thead class="bg-[#005030] text-white font-bold">
                        <tr>
                            <th class="p-2.5 border-r border-emerald-800 text-center">পিরিয়ড</th>
                            <th class="p-2.5 border-r border-emerald-800 text-center">সময়</th>
                            <th class="p-2.5 border-r border-emerald-800">বিষয় ও পাঠ</th>
                            <th class="p-2.5">দায়িত্বপ্রাপ্ত শিক্ষক</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-800">
                        <tr>
                            <td class="p-2.5 text-center font-bold bg-gray-50 border-r border-gray-200">১ম পিরিয়ড</td>
                            <td class="p-2.5 text-center font-mono border-r border-gray-200">১০:০০ - ১০:৪৫</td>
                            <td class="p-2.5 font-bold text-[#005030] border-r border-gray-200">বাংলা ১ম ও ২য় পত্র</td>
                            <td class="p-2.5">মো: আনিসুর রহমান (সিনিয়র শিক্ষক)</td>
                        </tr>
                        <tr class="bg-gray-50/50">
                            <td class="p-2.5 text-center font-bold bg-gray-50 border-r border-gray-200">২য় পিরিয়ড</td>
                            <td class="p-2.5 text-center font-mono border-r border-gray-200">১০:৪৫ - ১১:৩০</td>
                            <td class="p-2.5 font-bold text-[#005030] border-r border-gray-200">ইংরেজি (English For Today)</td>
                            <td class="p-2.5">মো: কামরুল হাসান (সহকারী শিক্ষক)</td>
                        </tr>
                        <tr>
                            <td class="p-2.5 text-center font-bold bg-gray-50 border-r border-gray-200">৩য় পিরিয়ড</td>
                            <td class="p-2.5 text-center font-mono border-r border-gray-200">১১:৩০ - ১২:১৫</td>
                            <td class="p-2.5 font-bold text-[#005030] border-r border-gray-200">গণিত ও জ্যামিতি</td>
                            <td class="p-2.5">মো: আব্দুল মজিদ (সিনিয়র শিক্ষক)</td>
                        </tr>
                        <tr class="bg-amber-50 text-amber-950 font-bold">
                            <td class="p-2 text-center border-r border-gray-200" colspan="2">মধ্যাহ্ন বিরতি</td>
                            <td class="p-2" colspan="2">১২:১৫ - ০১:০০ (টিফিন, নামাজ ও বিশ্রাম)</td>
                        </tr>
                        <tr>
                            <td class="p-2.5 text-center font-bold bg-gray-50 border-r border-gray-200">৪র্থ পিরিয়ড</td>
                            <td class="p-2.5 text-center font-mono border-r border-gray-200">০১:০০ - ০১:৪৫</td>
                            <td class="p-2.5 font-bold text-[#005030] border-r border-gray-200">সাধারণ বিজ্ঞান / পদার্থ ও রসায়ন</td>
                            <td class="p-2.5">মোছা: নাসরিন সুলতানা (সহকারী শিক্ষক)</td>
                        </tr>
                        <tr class="bg-gray-50/50">
                            <td class="p-2.5 text-center font-bold bg-gray-50 border-r border-gray-200">৫ম পিরিয়ড</td>
                            <td class="p-2.5 text-center font-mono border-r border-gray-200">০১:৪৫ - ০২:৩০</td>
                            <td class="p-2.5 font-bold text-[#005030] border-r border-gray-200">তথ্য ও যোগাযোগ প্রযুক্তি (আইসিটি ল্যাব)</td>
                            <td class="p-2.5">মো: মোশাররফ হোসেন (সহকারী শিক্ষক)</td>
                        </tr>
                        <tr>
                            <td class="p-2.5 text-center font-bold bg-gray-50 border-r border-gray-200">৬ষ্ঠ পিরিয়ড</td>
                            <td class="p-2.5 text-center font-mono border-r border-gray-200">০২:৩০ - ০৩:১৫</td>
                            <td class="p-2.5 font-bold text-[#005030] border-r border-gray-200">বাংলাদেশ ও বিশ্বপরিচয় / ইসলাম শিক্ষা</td>
                            <td class="p-2.5">মাওলানা মো: ইউসুফ আলী (সহকারী শিক্ষক)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Examination Routine Sidebar (Col 4) --}}
        <div class="lg:col-span-4 space-y-5">
            {{-- Exam Routine Circular Download Box --}}
            <div class="bg-white border border-gray-300 rounded shadow-2xs overflow-hidden">
                <div class="bg-[#005030] text-white px-4 py-2.5 flex items-center justify-between">
                    <h3 class="font-bold text-xs tracking-wide">টার্ম ও বার্ষিক পরীক্ষার রুটিন</h3>
                    <span class="text-[10px] text-emerald-200">PDF ফাইল</span>
                </div>
                <div class="p-4 space-y-3 text-xs">
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded space-y-1">
                        <div class="font-bold text-gray-900">বার্ষিক পরীক্ষা ২০২৬ এর রুটিন</div>
                        <div class="text-[11px] text-gray-500">সকল শ্রেণি (৬ষ্ঠ থেকে ৯ম শ্রেণি)</div>
                        <div class="pt-1">
                            <a href="#" class="text-[#005030] font-bold hover:underline inline-flex items-center gap-1">
                                <span>ডাউনলোড (PDF, 240 KB)</span>
                                <span>↓</span>
                            </a>
                        </div>
                    </div>

                    <div class="p-3 bg-gray-50 border border-gray-200 rounded space-y-1">
                        <div class="font-bold text-gray-900">এসএসসি নির্বাচনি (টেস্ট) পরীক্ষার সময়সূচি</div>
                        <div class="text-[11px] text-gray-500">১০ম শ্রেণির পরীক্ষার্থীদের জন্য</div>
                        <div class="pt-1">
                            <a href="#" class="text-[#005030] font-bold hover:underline inline-flex items-center gap-1">
                                <span>ডাউনলোড (PDF, 180 KB)</span>
                                <span>↓</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rules & Instructions Box --}}
            <div class="bg-[#003820] text-white border border-emerald-900 rounded p-4 shadow-2xs space-y-2 text-xs">
                <div class="font-bold text-emerald-300 uppercase tracking-wide">পরীক্ষার্থীদের সাধারণ নির্দেশনা</div>
                <ul class="space-y-1.5 text-emerald-100 list-disc list-inside leading-relaxed text-[11px]">
                    <li>পরীক্ষা শুরুর অন্তত ১৫ মিনিট পূর্বে পরীক্ষার হলে উপস্থিত হতে হবে।</li>
                    <li>প্রবেশপত্র (Admit Card) ছাড়া পরীক্ষায় অংশগ্রহণ নিষিদ্ধ।</li>
                    <li>পরীক্ষার হলে মোবাইল ফোন বা কোনো ইলেকট্রনিক ডিভাইস আনা সম্পূর্ণ নিষেধ।</li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection
