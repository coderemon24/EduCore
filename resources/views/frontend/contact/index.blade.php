@extends('layouts.app')

@section('title', 'যোগাযোগ ও অবস্থান | সোনাতলা উচ্চ বিদ্যালয়')

@section('content')
{{-- Breadcrumb & Title --}}
<div class="bg-gray-100 border-b border-gray-200 py-3 text-xs text-gray-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div class="flex items-center gap-1.5 font-medium">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030]">প্রচ্ছদ</a>
            <span>/</span>
            <span class="text-gray-900 font-bold">যোগাযোগ</span>
        </div>
        <span class="text-gray-400">সোনাতলা উচ্চ বিদ্যালয়</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- Header Banner --}}
    <div class="bg-[#005030] text-white p-5 rounded shadow-2xs">
        <h1 class="text-xl sm:text-2xl font-bold">যোগাযোগ ও অনুসন্ধান (Contact & Inquiries)</h1>
        <p class="text-xs text-emerald-200 mt-0.5">
            সোনাতলা উচ্চ বিদ্যালয়, সাঁথিয়া, পাবনা — যেকোনো তথ্যের জন্য সরাসরি যোগাযোগ করুন।
        </p>
    </div>

    {{-- Contact Information Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white border border-gray-300 rounded p-5 shadow-2xs space-y-2">
            <div class="text-xs font-bold text-[#005030] uppercase tracking-wider">বিদ্যালয়ের ঠিকানা</div>
            <div class="text-sm font-bold text-gray-900">বেড়া-সোনাতলা, সাঁথিয়া, পাবনা</div>
            <p class="text-xs text-gray-500 leading-relaxed">
                ডাকঘর: সোনাতলা, ডাক কোড: ৬৬৭০, উপজেলা: সাঁথিয়া, জেলা: পাবনা।
            </p>
        </div>

        <div class="bg-white border border-gray-300 rounded p-5 shadow-2xs space-y-2">
            <div class="text-xs font-bold text-[#005030] uppercase tracking-wider">টেলিফোন ও হেল্পলাইন</div>
            <div class="text-sm font-bold text-gray-900">
                <a href="tel:+8801712345678" class="hover:underline">+৮৮০ ১৭১২-৩৪৫৬৭৮</a>
            </div>
            <p class="text-xs text-gray-500 leading-relaxed">
                অফিস সময়: সকাল ৯:০০ টা থেকে বিকাল ৪:০০ টা পর্যন্ত (শুক্রবার ও শনিবার বন্ধ)।
            </p>
        </div>

        <div class="bg-white border border-gray-300 rounded p-5 shadow-2xs space-y-2">
            <div class="text-xs font-bold text-[#005030] uppercase tracking-wider">অফিসিয়াল ইমেইল</div>
            <div class="text-sm font-bold text-gray-900">
                <a href="mailto:info@sonatalahighschool.edu.bd" class="hover:underline">info@sonatalahighschool.edu.bd</a>
            </div>
            <p class="text-xs text-gray-500 leading-relaxed">
                যেকোনো প্রাতিষ্ঠানিক তথ্য ও সার্টিফিকেশন বিষয়ে ইমেইলে যোগাযোগ করতে পারেন।
            </p>
        </div>
    </div>

    {{-- Contact Form & Directions Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Contact Form (Col 7) --}}
        <div class="lg:col-span-7 bg-white border border-gray-300 rounded shadow-2xs p-6 space-y-4">
            <div class="border-b border-gray-200 pb-2">
                <h2 class="text-base font-bold text-gray-900">আমাদের একটি বার্তা পাঠান</h2>
                <p class="text-xs text-gray-500">ভর্তি, রেজাল্ট বা যেকোনো তথ্যের জন্য নিচের ফর্মটি পূরণ করুন।</p>
            </div>

            @if(session('success'))
                <div class="p-3 rounded bg-emerald-50 border border-emerald-300 text-[#005030] text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('frontend.contact.send') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">আপনার পূর্ণ নাম *</label>
                        <input type="text" name="name" required placeholder="নাম লিখুন"
                               class="w-full px-3 py-2 text-xs bg-white border border-gray-300 rounded text-gray-900 focus:outline-none focus:border-[#005030]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">মোবাইল নম্বর *</label>
                        <input type="text" name="phone" required placeholder="০১xxxxxxxxx"
                               class="w-full px-3 py-2 text-xs bg-white border border-gray-300 rounded text-gray-900 focus:outline-none focus:border-[#005030]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">ইমেইল ঠিকানা</label>
                        <input type="email" name="email" placeholder="example@mail.com"
                               class="w-full px-3 py-2 text-xs bg-white border border-gray-300 rounded text-gray-900 focus:outline-none focus:border-[#005030]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">বিষয় *</label>
                        <input type="text" name="subject" required placeholder="যেমন: নতুন ভর্তি সংক্রান্ত"
                               class="w-full px-3 py-2 text-xs bg-white border border-gray-300 rounded text-gray-900 focus:outline-none focus:border-[#005030]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">আপনার বার্তা লিখুন *</label>
                    <textarea name="message" rows="4" required placeholder="আপনার জিজ্ঞাসা বিস্তারিত লিখুন..."
                              class="w-full px-3 py-2 text-xs bg-white border border-gray-300 rounded text-gray-900 focus:outline-none focus:border-[#005030]"></textarea>
                </div>

                <button type="submit"
                        class="px-5 py-2.5 rounded bg-[#005030] hover:bg-[#003820] text-white text-xs font-bold transition-colors shadow-2xs">
                    বার্তা প্রেরণ করুন (Send Message)
                </button>
            </form>
        </div>

        {{-- Directions & Hours (Col 5) --}}
        <div class="lg:col-span-5 space-y-5">
            <div class="bg-white border border-gray-300 rounded shadow-2xs p-5 space-y-3">
                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider border-b border-gray-200 pb-2">
                    অফিস সময়সূচি
                </h3>
                <div class="divide-y divide-gray-100 text-xs">
                    <div class="py-2 flex justify-between">
                        <span class="text-gray-600">রবিবার - বৃহস্পতিবার:</span>
                        <span class="font-bold text-gray-900">সকাল ৯:০০ - বিকাল ৪:০০</span>
                    </div>
                    <div class="py-2 flex justify-between">
                        <span class="text-gray-600">শ্রেণি কার্যক্রম:</span>
                        <span class="font-bold text-gray-900">সকাল ১০:০০ - বিকাল ৩:১৫</span>
                    </div>
                    <div class="py-2 flex justify-between text-rose-700 font-bold">
                        <span>শুক্র ও শনিবার:</span>
                        <span>সাপ্তাহিক ছুটি (বন্ধ)</span>
                    </div>
                </div>
            </div>

            <div class="bg-[#003820] text-white border border-emerald-900 rounded p-5 shadow-2xs space-y-2">
                <h3 class="text-xs font-bold text-emerald-300 uppercase tracking-wider">লোকেশন ও দিকনির্দেশনা</h3>
                <p class="text-xs text-emerald-100 leading-relaxed">
                    সোনাতলা উচ্চ বিদ্যালয় পাবনা জেলার সাঁথিয়া উপজেলার সোনাতলা বাজারে অবস্থিত। ঢাকা-পাবনা মহাসড়কের বেড়া সিএন্ডবি বাসস্ট্যান্ড থেকে সিএনজি বা ইজিবাইকযোগে সরাসরি বিদ্যালয় ক্যাম্পাসে আসা যায়।
                </p>
                <div class="text-[11px] text-emerald-300 pt-1">
                    ডাকঘর: সোনাতলা • থানা: সাঁথিয়া • জেলা: পাবনা
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
