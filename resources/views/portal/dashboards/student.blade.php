@extends('portal.layouts.app')

@section('title', 'শিক্ষার্থী ড্যাশবোর্ড | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('portal_title', 'শিক্ষার্থী ড্যাশবোর্ড')

@section('portal_content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    {{-- Recipe 1: Sharpen the Message (Authoritative Headline with Single Focal Accent) --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-2xs relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            
            {{-- Student Identity Info --}}
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="w-18 h-18 sm:w-20 sm:h-20 rounded-2xl bg-emerald-50 border-2 border-emerald-500/30 overflow-hidden shadow-xs shrink-0 flex items-center justify-center">
                    <img src="{{ $student->photo_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80' }}"
                         alt="{{ $student->full_name ?? auth()->user()->name }}"
                         class="w-full h-full object-cover">
                </div>
                <div>
                    <div class="section-kicker inline-flex items-center gap-1.5 text-xs font-bold text-[#005030] uppercase tracking-wider mb-1">
                        <span class="w-2 h-2 rounded-full bg-[#005030] animate-pulse"></span>
                        শিক্ষার্থী পোর্টাল
                    </div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $student->full_name ?? auth()->user()->name ?? 'আলেকজান্ডার রাইট' }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1.5 text-xs text-slate-500 font-medium">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold">
                            <i class="fa-solid fa-graduation-cap text-[#005030]"></i>
                            {{ $student->classroom->name ?? '১০ম শ্রেণি' }} (শাখা: 'ক')
                        </span>
                        <span class="text-slate-300">•</span>
                        <span>রোল নং: <strong class="text-slate-900">{{ $student->roll_no ?? '০৪' }}</strong></span>
                        <span class="text-slate-300">•</span>
                        <span>ভর্তি নং: <strong class="text-slate-900">{{ $student->admission_no ?? 'ADM-2026-0001' }}</strong></span>
                    </div>
                </div>
            </div>

            {{-- Floating Anchor Credential Badge (Recipe 2) --}}
            <div class="flex flex-row md:flex-col items-center md:items-end justify-between gap-2 border-t md:border-t-0 pt-4 md:pt-0 border-slate-100">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-[#005030] text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    নিয়মিত শিক্ষার্থী
                </span>
                <span class="text-xs text-slate-400 font-medium">সেশন: ২০২৬ শিক্ষাবর্ষ</span>
            </div>

        </div>
    </div>

    {{-- Recipe 5: 4-Card Scaled Typographic KPI Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        {{-- KPI 1: Attendance Rate --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-xs font-bold uppercase tracking-wider">উপস্থিতি হার</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-sm">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">৯৪.৫%</div>
                <div class="text-xs text-slate-500 font-medium mt-0.5">মোট ক্লাস: ১৩৮ | উপস্থিত: ১৩১ দিন</div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-[#005030] h-1.5 rounded-full" style="width: 94.5%"></div>
            </div>
        </div>

        {{-- KPI 2: Examination GPA --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-xs font-bold uppercase tracking-wider">সর্বশেষ জিপিএ</span>
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-award"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">৪.৮৫ <span class="text-xs font-bold text-emerald-600">(A+)</span></div>
                <div class="text-xs text-slate-500 font-medium mt-0.5">অর্ধবার্ষিক মূল্যায়ন ২০২৬</div>
            </div>
            <div class="text-[11px] font-bold text-sky-600">মেধাক্রম: শ্রেণি ৪র্থ স্থান</div>
        </div>

        {{-- KPI 3: Fee Status (Recipe 6: Color Meaning) --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-xs font-bold uppercase tracking-wider">ফি স্থিতি</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-700 tracking-tight">পরিশোধিত</div>
                <div class="text-xs text-slate-500 font-medium mt-0.5">চলতি মাস: মার্চ ২০২৬</div>
            </div>
            <div class="text-[11px] font-bold text-slate-400">সর্বশেষ রসিদ: #INV-2026-0312</div>
        </div>

        {{-- KPI 4: Academic Working Days --}}
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-3">
            <div class="flex items-center justify-between text-slate-500">
                <span class="text-xs font-bold uppercase tracking-wider">মোট কার্যদিবস</span>
                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-sm">
                    <i class="fa-regular fa-clock"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">১৮৪ দিন</div>
                <div class="text-xs text-slate-500 font-medium mt-0.5">শিক্ষাবর্ষের মোট নির্ধারিত সময়</div>
            </div>
            <div class="text-[11px] font-bold text-[#005030]">পরবর্তী ছুটি: ২৬ মার্চ</div>
        </div>

    </div>

    {{-- Recipe 2: Asymmetric 60/40 Editorial Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Left Primary Column (60%, lg:col-span-7) --}}
        <div class="lg:col-span-7 space-y-8">
            
            {{-- Recipe 3: Build the Argument (Today's Routine Section) --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                
                <div class="flex items-center justify-between">
                    <div>
                        <div class="section-kicker inline-flex items-center gap-1.5 text-xs font-bold text-[#005030] uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-[#005030] animate-pulse"></span>
                            দৈনিক পাঠদান সময়সূচি
                        </div>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                            আজকের ক্লাস ও বিষয় বণ্টন
                        </h2>
                    </div>
                    <a href="{{ route('frontend.academic.routine') }}" class="text-xs font-bold text-[#005030] hover:underline inline-flex items-center gap-1">
                        <span>পূর্ণাঙ্গ রুটিন</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                {{-- Flagship Prominence Period Cards (Recipe 4: Pick the Winner) --}}
                <div class="space-y-3">
                    
                    {{-- Period 1 --}}
                    <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0">০১</span>
                            <div>
                                <h3 class="text-xs sm:text-sm font-bold text-slate-900">বাংলা ১ম পত্র</h3>
                                <div class="text-[11px] text-slate-500 font-medium">শিক্ষক: সেলিনা বেগম • কক্ষ: ২০৪</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-700">০৯:০০ - ০৯:৪৫</span>
                            <div class="text-[10px] text-slate-400">সম্পন্ন</div>
                        </div>
                    </div>

                    {{-- Period 2: FLAGSHIP WINNER (Active Current Period) --}}
                    <div class="flex items-center justify-between p-4 rounded-xl border-2 border-[#005030] bg-emerald-50/70 shadow-xs relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-[#005030] text-white text-[9px] font-extrabold px-2 py-0.5 rounded-bl-lg uppercase tracking-wider">
                            চলমান ক্লাস
                        </div>
                        <div class="flex items-center gap-3.5">
                            <span class="w-9 h-9 rounded-lg bg-[#005030] text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-2xs">০২</span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm sm:text-base font-extrabold text-slate-900">সাধারণ গণিত</h3>
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                </div>
                                <div class="text-xs text-emerald-900 font-medium mt-0.5">শিক্ষক: মো: রফিকুল ইসলাম • কক্ষ: ২০৪</div>
                            </div>
                        </div>
                        <div class="text-right pt-2">
                            <span class="text-xs sm:text-sm font-extrabold text-[#005030]">০৯:৪৫ - ১০:৩০</span>
                            <div class="text-[11px] text-emerald-700 font-semibold">বর্তমান পিরিয়ড</div>
                        </div>
                    </div>

                    {{-- Period 3 --}}
                    <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0">০৩</span>
                            <div>
                                <h3 class="text-xs sm:text-sm font-bold text-slate-900">ইংরেজি ২য় পত্র</h3>
                                <div class="text-[11px] text-slate-500 font-medium">শিক্ষক: এলিনর পেম্বারটন • কক্ষ: ২০৪</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-700">১০:৩০ - ১১:১৫</span>
                            <div class="text-[10px] text-slate-400">পরবর্তী</div>
                        </div>
                    </div>

                    {{-- Recess / Tiffin Break --}}
                    <div class="p-2.5 rounded-xl bg-amber-50/60 border border-amber-200/80 text-center text-xs font-bold text-amber-800 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-utensils text-[11px]"></i>
                        <span>মধ্যাহ্ন বিরতি (টিফিন): ১১:১৫ - ১১:৪৫</span>
                    </div>

                    {{-- Period 4 --}}
                    <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0">০৪</span>
                            <div>
                                <h3 class="text-xs sm:text-sm font-bold text-slate-900">পদার্থবিজ্ঞান (তত্ত্বীয় ও ব্যবহারিক)</h3>
                                <div class="text-[11px] text-slate-500 font-medium">শিক্ষক: ড. আহমেদ কবির • বিজ্ঞান ল্যাব ১</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-700">১১:৪৫ - ১২:৩০</span>
                            <div class="text-[10px] text-slate-400">আসন্ন</div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Latest Progress Report Card Preview --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="section-kicker inline-flex items-center gap-1.5 text-xs font-bold text-[#005030] uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-[#005030] animate-pulse"></span>
                            পরীক্ষার মূল্যায়ন প্রতিবেদন
                        </div>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                            অর্ধবার্ষিক পরীক্ষা ২০২৬ — নম্বরপত্র
                        </h2>
                    </div>
                    <a href="{{ route('frontend.academic.results') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-[#005030] hover:text-white text-slate-800 text-xs font-bold transition-colors">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>মার্কশিট ডাউনলোড</span>
                    </a>
                </div>

                {{-- Subject Marks Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-500 uppercase tracking-wider">
                                <th class="pb-2.5 font-bold">বিষয়</th>
                                <th class="pb-2.5 font-bold text-center">পূর্ণমান</th>
                                <th class="pb-2.5 font-bold text-center">প্রাপ্ত নম্বর</th>
                                <th class="pb-2.5 font-bold text-center">গ্রেড</th>
                                <th class="pb-2.5 font-bold text-right">জিপিএ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            <tr>
                                <td class="py-2.5 font-bold text-slate-900">বাংলা ১ম ও ২য় পত্র</td>
                                <td class="py-2.5 text-center text-slate-500">১০০</td>
                                <td class="py-2.5 text-center font-bold text-slate-900">৮৮</td>
                                <td class="py-2.5 text-center"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold">A+</span></td>
                                <td class="py-2.5 text-right font-bold text-slate-900">৫.০০</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-bold text-slate-900">ইংরেজি ১ম ও ২য় পত্র</td>
                                <td class="py-2.5 text-center text-slate-500">১০০</td>
                                <td class="py-2.5 text-center font-bold text-slate-900">৮৪</td>
                                <td class="py-2.5 text-center"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold">A+</span></td>
                                <td class="py-2.5 text-right font-bold text-slate-900">৫.০০</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-bold text-slate-900">সাধারণ গণিত</td>
                                <td class="py-2.5 text-center text-slate-500">১০০</td>
                                <td class="py-2.5 text-center font-bold text-slate-900">৯২</td>
                                <td class="py-2.5 text-center"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold">A+</span></td>
                                <td class="py-2.5 text-right font-bold text-slate-900">৫.০০</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-bold text-slate-900">পদার্থবিজ্ঞান</td>
                                <td class="py-2.5 text-center text-slate-500">১০০</td>
                                <td class="py-2.5 text-center font-bold text-slate-900">৭৯</td>
                                <td class="py-2.5 text-center"><span class="px-2 py-0.5 rounded bg-sky-100 text-sky-800 font-bold">A</span></td>
                                <td class="py-2.5 text-right font-bold text-slate-900">৪.০০</td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-bold text-slate-900">বাংলাদেশ ও বিশ্বপরিচয়</td>
                                <td class="py-2.5 text-center text-slate-500">১০০</td>
                                <td class="py-2.5 text-center font-bold text-slate-900">৮৫</td>
                                <td class="py-2.5 text-center"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold">A+</span></td>
                                <td class="py-2.5 text-right font-bold text-slate-900">৫.০০</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Summary Banner --}}
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                    <div>
                        <span class="text-slate-500">মোট প্রাপ্ত নম্বর:</span>
                        <strong class="text-slate-900 font-bold ml-1">৪২৮ / ৫০০</strong>
                    </div>
                    <div>
                        <span class="text-slate-500">গড় গ্রেড পয়েন্ট (GPA):</span>
                        <strong class="text-[#005030] font-extrabold text-sm ml-1">৪.৮৫ (A+)</strong>
                    </div>
                </div>

            </div>

        </div>

        {{-- Right Secondary Column (40%, lg:col-span-5) --}}
        <div class="lg:col-span-5 space-y-8">
            
            {{-- Fee Invoices & Payment Ledger --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="section-kicker inline-flex items-center gap-1.5 text-xs font-bold text-[#005030] uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-[#005030] animate-pulse"></span>
                            হিসাব ও বকেয়া
                        </div>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight mt-0.5">
                            ফি ও পেমেন্ট রসিদ
                        </h2>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                        বকেয়া নেই
                    </span>
                </div>

                <div class="space-y-3">
                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-slate-900">মার্চ ২০২৬ বেতন</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">রশিদ: #INV-2026-0312 • ০৫ মার্চ ২০২৬</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-slate-900">৳৫০০</div>
                            <a href="{{ route('frontend.contact') }}#receipt" class="text-[11px] font-bold text-[#005030] hover:underline inline-flex items-center gap-1">
                                <i class="fa-solid fa-arrow-down"></i>
                                <span>রসিদ</span>
                            </a>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-slate-900">অর্ধবার্ষিক পরীক্ষা ফি</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">রশিদ: #INV-2026-0284 • ২০ ফেব্রুয়ারি ২০২৬</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-slate-900">৳৩৫০</div>
                            <a href="{{ route('frontend.contact') }}#receipt" class="text-[11px] font-bold text-[#005030] hover:underline inline-flex items-center gap-1">
                                <i class="fa-solid fa-arrow-down"></i>
                                <span>রসিদ</span>
                            </a>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-slate-900">আইসিটি ল্যাব ও কম্পিউটার চার্জ</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">রশিদ: #INV-2026-0155 • ১০ জানুয়ারি ২০২৬</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-slate-900">৳২০০</div>
                            <a href="{{ route('frontend.contact') }}#receipt" class="text-[11px] font-bold text-[#005030] hover:underline inline-flex items-center gap-1">
                                <i class="fa-solid fa-arrow-down"></i>
                                <span>রসিদ</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="pt-1">
                    <a href="{{ route('frontend.contact') }}#fees" class="w-full py-2.5 px-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-2xs transition-colors flex items-center justify-center gap-2">
                        <i class="fa-regular fa-credit-card"></i>
                        <span>অনলাইন ফি পরিশোধ পোর্টাল</span>
                    </a>
                </div>
            </div>

            {{-- Recent School Notices & Bulletins --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="section-kicker inline-flex items-center gap-1.5 text-xs font-bold text-[#0284c7] uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-[#0284c7] animate-pulse"></span>
                            প্রাতিষ্ঠানিক সার্কুলার
                        </div>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight mt-0.5">
                            জরুরি নোটিশ বোর্ড
                        </h2>
                    </div>
                    <a href="{{ route('frontend.notices.index') }}" class="text-xs font-bold text-[#0284c7] hover:underline">
                        সব দেখুন
                    </a>
                </div>

                <div class="space-y-3">
                    <div class="p-3.5 rounded-xl border border-sky-100 bg-sky-50/50 hover:bg-sky-50 transition-colors">
                        <div class="flex items-center gap-2 text-[11px] text-sky-700 font-bold mb-1">
                            <i class="fa-solid fa-bullhorn text-sky-600"></i>
                            <span>২৮ ফেব্রুয়ারি ২০২৬</span>
                        </div>
                        <a href="{{ route('frontend.notices.index') }}" class="text-xs font-bold text-slate-900 hover:text-[#005030] transition-colors line-clamp-2">
                            ২০২৬ সালের এসএসসি মডেল টেস্ট পরীক্ষার সংশোধিত সময়সূচি প্রকাশ সংক্রান্ত।
                        </a>
                    </div>

                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-2 text-[11px] text-slate-500 font-semibold mb-1">
                            <i class="fa-regular fa-calendar"></i>
                            <span>২৫ ফেব্রুয়ারি ২০২৬</span>
                        </div>
                        <a href="{{ route('frontend.notices.index') }}" class="text-xs font-bold text-slate-900 hover:text-[#005030] transition-colors line-clamp-2">
                            আসন্ন ঐতিহাসিক ২৬ মার্চ মহান স্বাধীনতা দিবস উপলক্ষে বার্ষিক রচনা ও চিত্রাঙ্কন প্রতিযোগিতা।
                        </a>
                    </div>

                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-2 text-[11px] text-slate-500 font-semibold mb-1">
                            <i class="fa-regular fa-calendar"></i>
                            <span>২০ ফেব্রুয়ারি ২০২৬</span>
                        </div>
                        <a href="{{ route('frontend.notices.index') }}" class="text-xs font-bold text-slate-900 hover:text-[#005030] transition-colors line-clamp-2">
                            ডিজিটাল আইডি কার্ড সংগ্রহ ও শিক্ষার্থীদের ইউনিক আইডি হালনাগাদ সংক্রান্ত নির্দেশনা।
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
