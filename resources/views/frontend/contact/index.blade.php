@extends('layouts.app')

@section('title', 'যোগাযোগ ও অনুসন্ধান | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়, সাঁথিয়া, পাবনা — যেকোনো প্রাতিষ্ঠানিক তথ্য ও সহযোগিতার জন্য সরাসরি যোগাযোগ করুন।')

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
            <span class="text-gray-500">যোগাযোগ</span>
            <span class="text-gray-400">/</span>
            <span class="text-gray-900 font-bold">যোগাযোগ ও অনুসন্ধান</span>
        </nav>
        <span class="text-gray-500 text-xs font-medium hidden sm:inline-block">{{ $school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়' }} • সাঁথিয়া, পাবনা</span>
    </div>
</div>

{{-- Top Hero Banner: Matches Reference Image Clean Green Header --}}
<div class="bg-[#005030] text-white py-8 sm:py-10 border-b border-emerald-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        {{-- Pill Badge --}}
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-[#005030] text-xs font-bold shadow-xs">
            <i class="fa-solid fa-headset text-[#005030]"></i>
            <span>যোগাযোগ</span>
        </div>

        {{-- Page Heading --}}
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white mt-3 mb-2 tracking-tight">
            যোগাযোগ ও অনুসন্ধান
        </h1>

        {{-- Sub-narrative context --}}
        <p class="text-xs sm:text-sm text-emerald-100/90 font-medium">
            সোনাতলা উচ্চ বিদ্যালয়, সাঁথিয়া, পাবনা — যেকোনো প্রাতিষ্ঠানিক তথ্য ও অনুসন্ধান বিষয়ে আমাদের সাথে যোগাযোগ করুন
        </p>
    </div>
</div>

{{-- Main Content Canvas --}}
<section class="py-8 sm:py-12 bg-slate-50/60 min-h-[500px]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-8">

        {{-- Top 3 Contact Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- Address Card --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center text-base shadow-2xs">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">বিদ্যালয়ের ঠিকানা</h3>
                    <p class="text-base font-bold text-slate-900 mt-1">সোনাতলা, সাঁথিয়া, পাবনা</p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    ডাকঘর: সোনাতলা, পোস্টাল কোড: ৬৬৭০, উপজেলা: সাঁথিয়া, জেলা: পাবনা।
                </p>
            </div>

            {{-- Phone Card --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center text-base shadow-2xs">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">টেলিফোন ও হেল্পলাইন</h3>
                    <p class="text-base font-bold text-slate-900 mt-1">
                        <a href="tel:+8801712345678" class="text-[#005030] hover:underline font-mono">+৮৮০ ১৭১২-৩৪৫৬৭৮</a>
                    </p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    অফিস সময়: সকাল ৯:০০ থেকে বিকাল ৪:০০ পর্যন্ত (শুক্রবার ও শনিবার বন্ধ)।
                </p>
            </div>

            {{-- Email Card --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] flex items-center justify-center text-base shadow-2xs">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">অফিসিয়াল ইমেইল</h3>
                    <p class="text-base font-bold text-slate-900 mt-1">
                        <a href="mailto:info@sonatalahighschool.edu.bd" class="text-[#005030] hover:underline text-sm font-semibold truncate block">info@sonatalahighschool.edu.bd</a>
                    </p>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed">
                    যেকোনো প্রাতিষ্ঠানিক তথ্য ও সার্টিফিকেশন বিষয়ে ইমেইলে যোগাযোগ করতে পারেন।
                </p>
            </div>
        </div>

        {{-- Contact Form & Office Hours Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Contact Form (Col 7) --}}
            <div class="lg:col-span-7 bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-6 sm:p-7 space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-lg font-bold text-slate-900">আমাদের একটি বার্তা পাঠান</h2>
                    <p class="text-xs text-slate-500 mt-0.5">ভর্তি, পরীক্ষার তথ্য বা যেকোনো অনুসন্ধানের জন্য নিচের ফর্মটি পূরণ করুন।</p>
                </div>

                @if(session('success'))
                    <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-[#005030] text-xs font-semibold flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('frontend.contact.send') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">আপনার পূর্ণ নাম *</label>
                            <input type="text" name="name" required placeholder="নাম লিখুন"
                                   class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:border-[#005030] focus:ring-1 focus:ring-[#005030]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">মোবাইল নম্বর *</label>
                            <input type="text" name="phone" required placeholder="০১xxxxxxxxx"
                                   class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:border-[#005030] focus:ring-1 focus:ring-[#005030]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">ইমেইল ঠিকানা</label>
                            <input type="email" name="email" placeholder="example@mail.com"
                                   class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:border-[#005030] focus:ring-1 focus:ring-[#005030]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">বিষয় *</label>
                            <input type="text" name="subject" required placeholder="যেমন: নতুন ভর্তি সংক্রান্ত"
                                   class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:border-[#005030] focus:ring-1 focus:ring-[#005030]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">আপনার বার্তা লিখুন *</label>
                        <textarea name="message" rows="4" required placeholder="আপনার জিজ্ঞাসা বিস্তারিত লিখুন..."
                                  class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:border-[#005030] focus:ring-1 focus:ring-[#005030]"></textarea>
                    </div>

                    <div>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#005030] hover:bg-[#003820] text-white text-xs font-bold transition-colors shadow-xs">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>বার্তা প্রেরণ করুন</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Office Hours & Directions (Col 5) --}}
            <div class="lg:col-span-5 space-y-5">
                {{-- Office Hours Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-sm">
                            <i class="fa-solid fa-business-time"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">অফিস সময়সূচি</h3>
                    </div>

                    <div class="divide-y divide-slate-100 text-xs">
                        <div class="py-2.5 flex justify-between items-center">
                            <span class="text-slate-600 font-medium">রবিবার - বৃহস্পতিবার:</span>
                            <span class="font-bold text-slate-900">সকাল ৯:০০ - বিকাল ৪:০০</span>
                        </div>
                        <div class="py-2.5 flex justify-between items-center">
                            <span class="text-slate-600 font-medium">শ্রেণি কার্যক্রম:</span>
                            <span class="font-bold text-slate-900">সকাল ১০:০০ - বিকাল ৩:১৫</span>
                        </div>
                        <div class="py-2.5 flex justify-between items-center text-rose-700 font-bold">
                            <span>শুক্র ও শনিবার:</span>
                            <span class="inline-block px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[11px] border border-rose-200">সাপ্তাহিক ছুটি (বন্ধ)</span>
                        </div>
                    </div>
                </div>

                {{-- Location & Directions Card --}}
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-5 sm:p-6 space-y-3">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#005030] flex items-center justify-center text-sm">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">ক্যাম্পাসে আসার দিকনির্দেশনা</h3>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        সোনাতলা উচ্চ বিদ্যালয় পাবনা জেলার সাঁথিয়া উপজেলার সোনাতলা বাজারে অবস্থিত। ঢাকা-পাবনা মহাসড়কের বেড়া সিএন্ডবি বাসস্ট্যান্ড থেকে সিএনজি বা ইজিবাইকযোগে সরাসরি বিদ্যালয় ক্যাম্পাসে আসা যায়।
                    </p>

                    <div class="pt-2 flex items-center gap-2 text-xs font-semibold text-[#005030]">
                        <i class="fa-solid fa-landmark text-xs"></i>
                        <span>ডাকঘর: সোনাতলা • থানা: সাঁথিয়া • জেলা: পাবনা</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
