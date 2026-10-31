@extends('portal.layouts.app')

@section('title', 'শিক্ষক ড্যাশবোর্ড | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('portal_title', 'শিক্ষক ড্যাশবোর্ড')

@section('portal_content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    {{-- Teacher Identity Banner --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-2xs relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="w-18 h-18 sm:w-20 sm:h-20 rounded-2xl bg-sky-50 border-2 border-sky-500/30 overflow-hidden shadow-xs shrink-0 flex items-center justify-center">
                    <img src="{{ $teacher->photo_url ?? 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=400&q=80' }}"
                         alt="{{ $teacher->name ?? auth()->user()->name }}"
                         class="w-full h-full object-cover">
                </div>
                <div>
                    <div class="section-kicker inline-flex items-center gap-1.5 text-xs font-bold text-sky-700 uppercase tracking-wider mb-1">
                        <span class="w-2 h-2 rounded-full bg-sky-600 animate-pulse"></span>
                        শিক্ষক অনুষদ প্যানেল
                    </div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $teacher->name ?? auth()->user()->name ?? 'এলিনর পেম্বারটন' }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1.5 text-xs text-slate-500 font-medium">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-800 font-bold">
                            <i class="fa-solid fa-chalkboard-user text-sky-600"></i>
                            সিনিয়র সহকারী শিক্ষক
                        </span>
                        <span class="text-slate-300">•</span>
                        <span>স্টাফ কোড: <strong class="text-slate-900">TCH-1001</strong></span>
                        <span class="text-slate-300">•</span>
                        <span>অনুষদ: <strong class="text-slate-900">ইংরেজি ও মানবিক বিভাগ</strong></span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('frontend.academic.routine') }}" class="px-4 py-2 rounded-xl bg-[#005030] hover:bg-[#003822] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-list-check"></i>
                    <span>হাজিরা গ্রহণ করুন</span>
                </a>
                <a href="{{ route('frontend.academic.results') }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>নম্বর এন্ট্রি</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Teacher KPI Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">আজকের ক্লাস</span>
            <div class="text-2xl font-extrabold text-slate-900">৪টি পিরিয়ড</div>
            <p class="text-xs text-emerald-700 font-semibold">২টি সম্পন্ন • ২টি অবশিষ্ট</p>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">শ্রেণি শিক্ষক দায়িত্ব</span>
            <div class="text-2xl font-extrabold text-slate-900">৯ম শ্রেণি ('ক')</div>
            <p class="text-xs text-slate-500 font-medium">মোট শিক্ষার্থী: ৪৫ জন</p>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">পাঠদান প্রাপ্ত শিক্ষার্থী</span>
            <div class="text-2xl font-extrabold text-slate-900">১৮৪ জন</div>
            <p class="text-xs text-slate-500 font-medium">১০ম, ৯ম ও ৮ম শ্রেণি</p>
        </div>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-2">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">হাজিরা রিপোর্ট স্থিতি</span>
            <div class="text-2xl font-extrabold text-[#005030]">হালনাগাদ</div>
            <p class="text-xs text-slate-500 font-medium">আজকের রেজিস্টার সংরক্ষিত</p>
        </div>
    </div>

    {{-- Today's Schedule & Classes --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-8 space-y-5">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="section-kicker inline-flex items-center gap-1.5 text-xs font-bold text-[#005030] uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-[#005030] animate-pulse"></span>
                            পাঠদান শিডিউল
                        </div>
                        <h2 class="text-lg font-extrabold text-slate-900">আজকের শিক্ষক রুটিন</h2>
                    </div>
                    <span class="text-xs font-bold text-slate-500">{{ \Carbon\Carbon::now()->locale('bn')->isoFormat('dddd') }}</span>
                </div>

                <div class="space-y-3">
                    <div class="p-4 rounded-xl border-2 border-[#005030] bg-emerald-50/60 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-9 h-9 rounded-lg bg-[#005030] text-white font-extrabold text-xs flex items-center justify-center">০৩</span>
                            <div>
                                <div class="font-extrabold text-slate-900 text-sm">১০ম শ্রেণি ('ক') — ইংরেজি ২য় পত্র</div>
                                <div class="text-xs text-emerald-900">১০:৩০ - ১১:১৫ • কক্ষ নং ২০৪</div>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-[#005030] text-white text-xs font-bold">আসন্ন ক্লাস</span>
                    </div>

                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center">০৫</span>
                            <div>
                                <div class="font-bold text-slate-900 text-xs sm:text-sm">৯ম শ্রেণি ('খ') — ইংরেজি ১ম পত্র</div>
                                <div class="text-[11px] text-slate-500">১২:৩০ - ০১:১৫ • কক্ষ নং ২০২</div>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-slate-400">পরবর্তী</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-5">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-4">
                <h2 class="text-base font-extrabold text-slate-900">একাডেমিক দায়িত্ব ও অ্যাসাইনমেন্ট</h2>
                <ul class="text-xs space-y-2 text-slate-700">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-600 mt-0.5"></i>
                        <span>১০ম শ্রেণির প্রাক-নির্বাচনী খাতা মূল্যায়ন</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-emerald-600 mt-0.5"></i>
                        <span>বার্ষিক সাংস্কৃতিক সপ্তাহ সমন্বয়ক</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection
