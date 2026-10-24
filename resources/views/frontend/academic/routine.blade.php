@extends('layouts.app')

@section('title', 'ক্লাস ও পরীক্ষার রুটিন | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের ২০২৬ শিক্ষাবর্ষের নিয়মিত ক্লাস পাঠদান ও পরীক্ষার আনুষ্ঠানিক সময়সূচি।')

@section('content')

{{-- Breadcrumb (Standard Clean Layout Matching Classes Page) --}}
<div class="bg-gray-100 border-b border-gray-200 py-3.5 text-xs text-gray-600" id="overview">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 flex items-center justify-between">
        <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>প্রচ্ছদ</span>
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-500">একাডেমিক</span>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">ক্লাস ও পরীক্ষার রুটিন</span>
        </nav>
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }} • শিক্ষাবর্ষ: ২০২৬ খ্রি.</span>
    </div>
</div>

{{-- Top Hero Banner: Matches Reference Image Clean Green Header --}}
<div class="bg-[#005030] text-white py-8 sm:py-10 border-b border-emerald-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        {{-- Pill Badge --}}
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-[#005030] text-xs font-bold shadow-xs">
            <i class="fa-solid fa-calendar-days text-[#005030]"></i>
            <span>একাডেমিক</span>
        </div>

        {{-- Page Heading --}}
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white mt-3 mb-2 tracking-tight">
            ক্লাস ও পরীক্ষার সময়সূচি
        </h1>

        {{-- Sub-narrative context --}}
        <p class="text-xs sm:text-sm text-emerald-100/90 font-medium">
            সোনাতলা উচ্চ বিদ্যালয়ের ২০২৬ শিক্ষাবর্ষের নিয়মিত দৈনিক পাঠদান ও পরীক্ষার রুটিন
        </p>
    </div>
</div>

{{-- Main Content Canvas --}}
<section class="py-8 sm:py-12 bg-slate-50/60 min-h-[500px]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-6">

        {{-- Notice / Effective Date Card --}}
        <div class="bg-[#eef7f2] border border-[#d2ecd9] rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-2xs">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#005030] text-white flex items-center justify-center text-lg shrink-0 shadow-xs">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900">
                        দৈনন্দিন শ্রেণি পাঠদান সময়সূচি (Academic Routine)
                    </h2>
                    <p class="text-xs text-slate-600">
                        শ্রেণি: ৬ষ্ঠ থেকে ১০ম শ্রেণি (প্রভাতী ও দিবা শাখা)
                    </p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100/80 text-[#005030] text-xs font-bold border border-emerald-300/80 self-start sm:self-auto">
                <i class="fa-solid fa-circle-check text-xs"></i>
                <span>কার্যকর: ০১ জানুয়ারি ২০২৬ থেকে</span>
            </span>
        </div>

        {{-- Routine Grid: Timetable (Left) & Circulars/Sidebar (Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Main Timetable Table (Col 8) --}}
            <div class="lg:col-span-8 bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-base text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-table-list text-[#005030] text-sm"></i>
                        <span>শ্রেণি পাঠদান সময়সূচি</span>
                    </h3>
                    <span class="text-xs text-slate-500 font-medium">প্রতি পিরিয়ড: ৪৫ মিনিট</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border border-slate-200 rounded-xl overflow-hidden">
                        <thead class="bg-[#005030] text-white font-bold">
                            <tr>
                                <th class="p-3 border-r border-emerald-800 text-center w-24">পিরিয়ড</th>
                                <th class="p-3 border-r border-emerald-800 text-center w-28">সময়</th>
                                <th class="p-3 border-r border-emerald-800">বিষয় ও পাঠ</th>
                                <th class="p-3">দায়িত্বপ্রাপ্ত শিক্ষক</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800">
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="p-3 text-center font-bold bg-slate-50/80 border-r border-slate-200 text-slate-700">১ম পিরিয়ড</td>
                                <td class="p-3 text-center font-mono border-r border-slate-200 text-slate-600">১০:০০ - ১০:৪৫</td>
                                <td class="p-3 font-bold text-[#005030] border-r border-slate-200">বাংলা ১ম ও ২য় পত্র</td>
                                <td class="p-3 text-slate-600">মো: আনিসুর রহমান (সিনিয়র শিক্ষক)</td>
                            </tr>
                            <tr class="bg-slate-50/40 hover:bg-slate-50/80 transition-colors">
                                <td class="p-3 text-center font-bold bg-slate-50/80 border-r border-slate-200 text-slate-700">২য় পিরিয়ড</td>
                                <td class="p-3 text-center font-mono border-r border-slate-200 text-slate-600">১০:৪৫ - ১১:৩০</td>
                                <td class="p-3 font-bold text-[#005030] border-r border-slate-200">ইংরেজি (English For Today)</td>
                                <td class="p-3 text-slate-600">মো: কামরুল হাসান (সহকারী শিক্ষক)</td>
                            </tr>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="p-3 text-center font-bold bg-slate-50/80 border-r border-slate-200 text-slate-700">৩য় পিরিয়ড</td>
                                <td class="p-3 text-center font-mono border-r border-slate-200 text-slate-600">১১:৩০ - ১২:১৫</td>
                                <td class="p-3 font-bold text-[#005030] border-r border-slate-200">গণিত ও জ্যামিতি</td>
                                <td class="p-3 text-slate-600">মো: আব্দুল মজিদ (সিনিয়র শিক্ষক)</td>
                            </tr>
                            <tr class="bg-amber-50 text-amber-950 font-bold border-y border-amber-200">
                                <td class="p-2.5 text-center border-r border-amber-200" colspan="2">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-amber-900">
                                        <i class="fa-solid fa-mug-saucer text-amber-700 text-xs"></i>
                                        <span>মধ্যাহ্ন বিরতি</span>
                                    </span>
                                </td>
                                <td class="p-2.5 text-xs" colspan="2">১২:১৫ - ০১:০০ (টিফিন, নামাজ ও বিশ্রাম)</td>
                            </tr>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="p-3 text-center font-bold bg-slate-50/80 border-r border-slate-200 text-slate-700">৪র্থ পিরিয়ড</td>
                                <td class="p-3 text-center font-mono border-r border-slate-200 text-slate-600">০১:০০ - ০১:৪৫</td>
                                <td class="p-3 font-bold text-[#005030] border-r border-slate-200">সাধারণ বিজ্ঞান / পদার্থ ও রসায়ন</td>
                                <td class="p-3 text-slate-600">মোছা: নাসরিন সুলতানা (সহকারী শিক্ষক)</td>
                            </tr>
                            <tr class="bg-slate-50/40 hover:bg-slate-50/80 transition-colors">
                                <td class="p-3 text-center font-bold bg-slate-50/80 border-r border-slate-200 text-slate-700">৫ম পিরিয়ড</td>
                                <td class="p-3 text-center font-mono border-r border-slate-200 text-slate-600">০১:৪৫ - ০২:৩০</td>
                                <td class="p-3 font-bold text-[#005030] border-r border-slate-200">তথ্য ও যোগাযোগ প্রযুক্তি (আইসিটি ল্যাব)</td>
                                <td class="p-3 text-slate-600">মো: মোশাররফ হোসেন (সহকারী শিক্ষক)</td>
                            </tr>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="p-3 text-center font-bold bg-slate-50/80 border-r border-slate-200 text-slate-700">৬ষ্ঠ পিরিয়ড</td>
                                <td class="p-3 text-center font-mono border-r border-slate-200 text-slate-600">০২:৩০ - ০৩:১৫</td>
                                <td class="p-3 font-bold text-[#005030] border-r border-slate-200">বাংলাদেশ ও বিশ্বপরিচয় / ইসলাম শিক্ষা</td>
                                <td class="p-3 text-slate-600">মাওলানা মো: ইউসুফ আলী (সহকারী শিক্ষক)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Sidebar (Col 4) --}}
            <div class="lg:col-span-4 space-y-5">
                {{-- Exam Routine Downloads --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs overflow-hidden">
                    <div class="bg-[#005030] text-white px-5 py-3.5 flex items-center justify-between">
                        <h3 class="font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-emerald-300"></i>
                            <span>পরীক্ষার রুটিন সার্কুলার</span>
                        </h3>
                        <span class="text-[10px] text-emerald-200 font-mono">PDF কপি</span>
                    </div>
                    <div class="p-4 space-y-3 text-xs">
                        <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl space-y-2 hover:border-slate-200 transition-colors">
                            <div class="font-bold text-slate-900">বার্ষিক পরীক্ষা ২০২৬ এর রুটিন</div>
                            <div class="text-[11px] text-slate-500">সকল শ্রেণি (৬ষ্ঠ থেকে ৯ম শ্রেণি)</div>
                            <div class="pt-1">
                                <a href="{{ route('frontend.notices.index') }}" class="text-[#005030] font-bold hover:underline inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-arrow-down text-xs"></i>
                                    <span>বিজ্ঞপ্তি ও ডাউনলোড দেখুন</span>
                                </a>
                            </div>
                        </div>

                        <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-xl space-y-2 hover:border-slate-200 transition-colors">
                            <div class="font-bold text-slate-900">এসএসসি টেস্ট পরীক্ষার সময়সূচি</div>
                            <div class="text-[11px] text-slate-500">১০ম শ্রেণির পরীক্ষার্থীদের জন্য</div>
                            <div class="pt-1">
                                <a href="{{ route('frontend.notices.index') }}" class="text-[#005030] font-bold hover:underline inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-arrow-down text-xs"></i>
                                    <span>বিজ্ঞপ্তি ও ডাউনলোড দেখুন</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Exam Instructions Box --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3 text-xs">
                    <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h4 class="font-bold text-slate-900">পরীক্ষার্থীদের সাধারণ নির্দেশনা</h4>
                    </div>
                    <ul class="space-y-2 text-slate-600 leading-relaxed text-xs">
                        <li class="flex items-start gap-2">
                            <span class="text-[#005030] font-bold">✓</span>
                            <span>পরীক্ষা শুরুর অন্তত ১৫ মিনিট পূর্বে পরীক্ষার হলে আসন গ্রহণ করতে হবে।</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-[#005030] font-bold">✓</span>
                            <span>প্রবেশপত্র (Admit Card) ছাড়া পরীক্ষায় অংশগ্রহণ নিষিদ্ধ।</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-[#005030] font-bold">✓</span>
                            <span>পরীক্ষার হলে মোবাইল ফোন বা কোনো ইলেকট্রনিক ডিভাইস সম্পূর্ণ নিষিদ্ধ।</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
