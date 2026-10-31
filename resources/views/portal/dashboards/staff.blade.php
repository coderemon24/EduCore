@extends('portal.layouts.app')

@section('title', 'কর্মকর্তা ও কর্মচারী ড্যাশবোর্ড | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('portal_title', 'স্টাফ ড্যাশবোর্ড')

@section('portal_content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    {{-- Staff Identity Banner --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-2xs relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="w-18 h-18 sm:w-20 sm:h-20 rounded-2xl bg-amber-50 border-2 border-amber-500/30 overflow-hidden shadow-xs shrink-0 flex items-center justify-center">
                    <img src="{{ $staff->photo_url ?? 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80' }}"
                         alt="{{ $staff->name ?? auth()->user()->name }}"
                         class="w-full h-full object-cover">
                </div>
                <div>
                    <div class="section-kicker inline-flex items-center gap-1.5 text-xs font-bold text-amber-800 uppercase tracking-wider mb-1">
                        <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
                        প্রশাসনিক ও সাধারণ স্টাফ প্যানেল
                    </div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $staff->name ?? auth()->user()->name ?? 'র‌্যাচেল কোওয়ালস্কি' }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-2 mt-1.5 text-xs text-slate-500 font-medium">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-900 font-bold">
                            <i class="fa-solid fa-id-card-clip text-amber-700"></i>
                            অফিস সহকারী ও হিসাব রক্ষক
                        </span>
                        <span class="text-slate-300">•</span>
                        <span>স্টাফ আইডি: <strong class="text-slate-900">STF-2001</strong></span>
                        <span class="text-slate-300">•</span>
                        <span>দপ্তর: <strong class="text-slate-900">প্রশাসনিক শাখা</strong></span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-[#005030] text-xs font-bold border border-emerald-200">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    সক্রিয় ডিউটি
                </span>
            </div>
        </div>
    </div>

    {{-- Operational Shortcuts Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <a href="{{ route('frontend.contact') }}#fees" class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs hover:border-[#005030] transition-colors block">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center text-lg mb-3">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div class="text-sm font-extrabold text-slate-900">ফি আদায় ও রশিদ তৈরি</div>
            <p class="text-xs text-slate-500 mt-1">শিক্ষার্থীদের মাসিক বেতন ও অন্যান্য ফি গ্রহণ</p>
        </a>

        <a href="{{ route('frontend.academic.classes') }}" class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs hover:border-[#005030] transition-colors block">
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg mb-3">
                <i class="fa-solid fa-users-rectangle"></i>
            </div>
            <div class="text-sm font-extrabold text-slate-900">শিক্ষার্থী ও অভিভাবক ডিরেক্টরি</div>
            <p class="text-xs text-slate-500 mt-1">শ্রেণিভিত্তিক শিক্ষার্থীদের জরুরি তথ্য ও ফোন</p>
        </a>

        <a href="{{ route('frontend.academic.routine') }}" class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs hover:border-[#005030] transition-colors block">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg mb-3">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div class="text-sm font-extrabold text-slate-900">দৈনিক হাজিরা ওভারভিউ</div>
            <p class="text-xs text-slate-500 mt-1">সকল শ্রেণির সামগ্রিক উপস্থিতি হিসাব</p>
        </a>

        <a href="{{ route('frontend.notices.index') }}" class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs hover:border-[#005030] transition-colors block">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg mb-3">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div class="text-sm font-extrabold text-slate-900">প্রাতিষ্ঠানিক নোটিশ বোর্ড</div>
            <p class="text-xs text-slate-500 mt-1">অফিসিয়াল সার্কুলার ও নির্দেশনা পর্যবেক্ষণ</p>
        </a>
    </div>

</div>
@endsection
