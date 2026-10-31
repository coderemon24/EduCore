@extends('portal.layouts.app')

@section('title', 'অভিভাবক ড্যাশবোর্ড | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('portal_title', 'অভিভাবক ড্যাশবোর্ড')

@section('portal_content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8"
     x-data="{
         activeWard: '{{ request('ward_id', '1') }}',
         switchWard(id) {
             this.activeWard = id;
         }
     }">

    {{-- Guardian Identity Banner --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-2xs relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="w-18 h-18 sm:w-20 sm:h-20 rounded-2xl bg-indigo-50 border-2 border-indigo-500/30 overflow-hidden shadow-xs shrink-0 flex items-center justify-center">
                    <img src="{{ $guardian->photo_url ?? 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80' }}"
                         alt="{{ $guardian->full_name ?? auth()->user()->name }}"
                         class="w-full h-full object-cover">
                </div>
                <div>
                    <div class="section-kicker inline-flex items-center gap-1.5 text-xs font-bold text-indigo-700 uppercase tracking-wider mb-1">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                        অভিভাবক পোর্টাল
                    </div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $guardian->full_name ?? auth()->user()->name ?? 'রবার্ট রাইট' }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1.5 text-xs text-slate-500 font-medium">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-800 font-bold">
                            <i class="fa-solid fa-users text-indigo-600"></i>
                            পিতা / বৈধ অভিভাবক
                        </span>
                        <span class="text-slate-300">•</span>
                        <span>মোবাইল: <strong class="text-slate-900">+1 (555) 987-6543</strong></span>
                        <span class="text-slate-300">•</span>
                        <span>সংযুক্ত সন্তান: <strong class="text-[#005030] font-bold">২ জন শিক্ষার্থী</strong></span>
                    </div>
                </div>
            </div>

            <div class="flex flex-row md:flex-col items-center md:items-end justify-between gap-2 border-t md:border-t-0 pt-4 md:pt-0 border-slate-100">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-[#005030] text-xs font-bold shadow-2xs">
                    <i class="fa-solid fa-shield-halved text-emerald-500"></i>
                    ভেরিফায়েড অভিভাবক একাউন্ট
                </span>
                <span class="text-xs text-slate-400 font-medium">সোনাতলা উচ্চ বিদ্যালয়</span>
            </div>
        </div>
    </div>

    {{-- Interactive Sibling / Ward Switcher Tabs (Recipe 4: Pick the Winner) --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-children text-[#005030]"></i>
                <span>সন্তান নির্বাচন করুন (এক-ক্লিকে স্যুইচ):</span>
            </label>
            <span class="text-xs text-slate-400">বর্তমান নির্বাচিত সন্তানের অগ্রগতি প্রদর্শিত হচ্ছে</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            {{-- Ward 1: Alexander Wright --}}
            <button type="button"
                    @click="switchWard('1')"
                    :class="activeWard === '1' ? 'border-2 border-[#005030] bg-emerald-50/60 shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-50'"
                    class="p-4 rounded-2xl text-left transition-all flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80" alt="আলেকজান্ডার রাইট" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <div class="text-sm font-extrabold text-slate-900">আলেকজান্ডার রাইট</div>
                        <div class="text-xs text-slate-500 font-medium">১০ম শ্রেণি • শাখা 'ক' • রোল: ০৪</div>
                        <div class="text-[11px] text-slate-400">ভর্তি নং: ADM-2026-0001</div>
                    </div>
                </div>
                <div class="text-right">
                    <span x-show="activeWard === '1'" class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-[#005030] text-white">
                        <i class="fa-solid fa-check text-[9px]"></i>
                        নির্বাচিত
                    </span>
                    <span x-show="activeWard !== '1'" class="text-xs font-semibold text-slate-400 hover:text-slate-600">
                        দেখুন →
                    </span>
                </div>
            </button>

            {{-- Ward 2: Mariam Wright --}}
            <button type="button"
                    @click="switchWard('2')"
                    :class="activeWard === '2' ? 'border-2 border-[#005030] bg-emerald-50/60 shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-50'"
                    class="p-4 rounded-2xl text-left transition-all flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                        <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=200&q=80" alt="মারিয়াম রাইট" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <div class="text-sm font-extrabold text-slate-900">মারিয়াম রাইট</div>
                        <div class="text-xs text-slate-500 font-medium">৭ম শ্রেণি • শাখা 'খ' • রোল: ১২</div>
                        <div class="text-[11px] text-slate-400">ভর্তি নং: ADM-2026-0045</div>
                    </div>
                </div>
                <div class="text-right">
                    <span x-show="activeWard === '2'" class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full bg-[#005030] text-white">
                        <i class="fa-solid fa-check text-[9px]"></i>
                        নির্বাচিত
                    </span>
                    <span x-show="activeWard !== '2'" class="text-xs font-semibold text-slate-400 hover:text-slate-600">
                        দেখুন →
                    </span>
                </div>
            </button>

        </div>
    </div>

    {{-- Active Child KPI Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">উপস্থিতি স্থিতি</span>
            <div class="text-2xl font-extrabold text-slate-900" x-text="activeWard === '1' ? '৯৪.৫%' : '৯৬.২%'">৯৪.৫%</div>
            <p class="text-xs text-emerald-700 font-semibold">সন্তোষজনক উপস্থিতি</p>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">সর্বশেষ জিপিএ</span>
            <div class="text-2xl font-extrabold text-slate-900" x-text="activeWard === '1' ? '৪.৮৫ (A+)' : '৫.০০ (A+)'">৪.৮৫ (A+)</div>
            <p class="text-xs text-sky-600 font-semibold">অর্ধবার্ষিক মূল্যায়ন ২০২৬</p>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">বকেয়া ফি</span>
            <div class="text-2xl font-extrabold text-emerald-700">৳০.০০</div>
            <p class="text-xs text-slate-500">সকল বকেয়া পরিশোধিত</p>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">শ্রেণি শিক্ষক</span>
            <div class="text-base font-extrabold text-slate-900" x-text="activeWard === '1' ? 'মো: রফিকুল ইসলাম' : 'ফারহানা চৌধুরী'">মো: রফিকুল ইসলাম</div>
            <p class="text-xs text-[#005030] font-semibold">যোগাযোগ: ০১৭xxxxxxxx</p>
        </div>
    </div>

    {{-- Asymmetric 60/40 Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {{-- Left 60%: Daily Routine & Results --}}
        <div class="lg:col-span-7 space-y-8">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="section-kicker inline-flex items-center gap-1.5 text-xs font-bold text-[#005030] uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-[#005030] animate-pulse"></span>
                            পাঠদান সময়সূচি
                        </div>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                            সন্তানের আজকের ক্লাস তালিকা
                        </h2>
                    </div>
                    <a href="{{ route('frontend.academic.routine') }}" class="text-xs font-bold text-[#005030] hover:underline">
                        রুটিন দেখুন
                    </a>
                </div>

                <div class="space-y-3">
                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-xs sm:text-sm text-slate-900">০১. বাংলা ১ম পত্র</div>
                            <div class="text-[11px] text-slate-500">০৯:০০ - ০৯:৪৫ • কক্ষ: ২০৪</div>
                        </div>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">উপস্থিত</span>
                    </div>

                    <div class="p-3.5 rounded-xl border-2 border-[#005030] bg-emerald-50/60 flex items-center justify-between">
                        <div>
                            <div class="font-extrabold text-xs sm:text-sm text-slate-900">০২. সাধারণ গণিত (চলমান)</div>
                            <div class="text-[11px] text-emerald-800">০৯:৪৫ - ১০:৩০ • কক্ষ: ২০৪</div>
                        </div>
                        <span class="text-xs font-extrabold text-[#005030]">ক্লাসে উপস্থিত</span>
                    </div>

                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-xs sm:text-sm text-slate-900">০৩. ইংরেজি ২য় পত্র</div>
                            <div class="text-[11px] text-slate-500">১০:৩০ - ১১:১৫ • কক্ষ: ২০৪</div>
                        </div>
                        <span class="text-xs text-slate-400">পরবর্তী</span>
                    </div>
                </div>
            </div>

            {{-- Progress Report --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base sm:text-lg font-extrabold text-slate-900">শিক্ষক মূল্যায়ন ও অগ্রগতি মন্তব্য</h2>
                    <span class="text-xs font-bold text-slate-400">শ্রেণি শিক্ষকের মন্তব্য</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 leading-relaxed italic">
                    "শিক্ষার্থী ক্লাসে নিয়মিত ও অত্যন্ত মনোযোগী। গণিত ও বিজ্ঞানে তার ধারাবাহিক অগ্রগতি প্রশংসনীয়। সহপাঠক্রমিক কার্যক্রমেও সক্রিয় অংশগ্রহণ রয়েছে।"
                </div>
            </div>
        </div>

        {{-- Right 40%: Fees & Bulletins --}}
        <div class="lg:col-span-5 space-y-8">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-base sm:text-lg font-extrabold text-slate-900">ফি ও ইনভয়েস রসিদ</h2>
                    <a href="{{ route('frontend.contact') }}#fees" class="text-xs font-bold text-[#005030] hover:underline">পেমেন্ট হিস্ট্রি</a>
                </div>
                <div class="space-y-3">
                    <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/70 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-slate-900">মার্চ ২০২৬ বেতন</div>
                            <div class="text-[11px] text-slate-500">পরিশোধিত: ০৫ মার্চ ২০২৬</div>
                        </div>
                        <div class="text-right font-bold text-slate-900">৳৫০০</div>
                    </div>
                    <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/70 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-slate-900">অর্ধবার্ষিক পরীক্ষা ফি</div>
                            <div class="text-[11px] text-slate-500">পরিশোধিত: ২০ ফেব্রুয়ারি ২০২৬</div>
                        </div>
                        <div class="text-right font-bold text-slate-900">৳৩৫০</div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                <h2 class="text-base sm:text-lg font-extrabold text-slate-900">অভিভাবক সার্কুলার ও পিটিএম (PTM)</h2>
                <div class="p-3.5 rounded-xl border border-sky-100 bg-sky-50/50 space-y-1">
                    <span class="text-[11px] font-bold text-sky-700">আসন্ন অভিভাবক সমাবেশ</span>
                    <p class="text-xs text-slate-800 font-medium">আগামী ১৫ এপ্রিল ২০২৬ সকাল ১০:০০ ঘটিকায় অর্ধবার্ষিক ফলাফল পর্যালোচনা সভা অনুষ্ঠিত হবে।</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
