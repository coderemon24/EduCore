@extends('layouts.app')

@section('title', 'শিক্ষক ও কর্মচারীবৃন্দ | সোনাতলা উচ্চ বিদ্যালয়')

@section('content')
{{-- Breadcrumb & Title --}}
<div class="bg-gray-100 border-b border-gray-200 py-3 text-xs text-gray-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div class="flex items-center gap-1.5 font-medium">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030]">প্রচ্ছদ</a>
            <span>/</span>
            <span class="text-gray-900 font-bold">শিক্ষক ও কর্মচারীবৃন্দ</span>
        </div>
        <span class="text-gray-400">সোনাতলা উচ্চ বিদ্যালয়</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    {{-- Header Banner --}}
    <div class="bg-[#005030] text-white p-5 rounded shadow-2xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold">সম্মানিত শিক্ষক ও কর্মচারীবৃন্দ</h1>
            <p class="text-xs text-emerald-200 mt-0.5">
                সোনাতলা উচ্চ বিদ্যালয়ের নিবেদিতপ্রাণ শিক্ষক ও কর্মকর্তা-কর্মচারীগণের বিবরণ।
            </p>
        </div>
        <div class="w-full md:w-72">
            <div class="relative">
                <input type="text"
                       placeholder="শিক্ষকের নাম বা পদবি দিয়ে খুঁজুন..."
                       class="w-full bg-[#003820] text-white placeholder-emerald-300 text-xs rounded px-3 py-2 border border-emerald-700 focus:outline-none focus:ring-1 focus:ring-white">
                <button type="button" class="absolute right-2.5 top-2 text-emerald-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </div>
        </div>
    </div>

    @php
        $dummyStaff = [
            [
                'name' => 'মো: খলিলুর রহমান',
                'designation' => 'প্রধান শিক্ষক (Headmaster)',
                'department' => 'প্রশাসন ও ব্যবস্থাপনা',
                'qualification' => 'এম.এ (ইতিহাস), বি.এড (১ম শ্রেণি)',
                'joining' => '২০০৫',
                'phone' => '+৮৮০ ১৭১২-৩৪৫৬৭৮',
                'photo' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'মোছা: রোকসানা পারভীন',
                'designation' => 'সহকারী প্রধান শিক্ষক',
                'department' => 'বিজ্ঞান বিভাগ',
                'qualification' => 'এম.এসসি (পদার্থবিজ্ঞান), বি.এড',
                'joining' => '২০০৮',
                'phone' => '+৮৮০ ১৭১৩-৪৫৬৭৮৯',
                'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'মো: আব্দুল মজিদ',
                'designation' => 'সিনিয়র শিক্ষক (গণিত)',
                'department' => 'বিজ্ঞান বিভাগ',
                'qualification' => 'বি.এসসি (অনার্স), এম.এসসি (গণিত)',
                'joining' => '২০১০',
                'phone' => '+৮৮০ ১৭১৪-৫৬৭৮৯০',
                'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'মো: আনিসুর রহমান',
                'designation' => 'সিনিয়র শিক্ষক (বাংলা)',
                'department' => 'মানবিক বিভাগ',
                'qualification' => 'এম.এ (বাংলা সাহিত্য), বি.এড',
                'joining' => '২০১২',
                'phone' => '+৮৮০ ১৭১৫-৬৭৮৯০১',
                'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'মো: কামরুল হাসান',
                'designation' => 'সহকারী শিক্ষক (ইংরেজি)',
                'department' => 'ভাষা ও সাহিত্য বিভাগ',
                'qualification' => 'বি.এ (অনার্স), এম.এ (ইংরেজি)',
                'joining' => '২০১৫',
                'phone' => '+৮৮০ ১৭১৬-৭৮৯০১২',
                'photo' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'মোছা: নাসরিন সুলতানা',
                'designation' => 'সহকারী শিক্ষক (জীববিজ্ঞান ও রসায়ন)',
                'department' => 'বিজ্ঞান বিভাগ',
                'qualification' => 'বি.এসসি (অনার্স), এম.এসসি (রসায়ন)',
                'joining' => '২০১৬',
                'phone' => '+৮৮০ ১৭১৭-৮৯০১২৩',
                'photo' => 'https://images.unsplash.com/photo-1580894732484-934d4001ec4c?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'মো: মোশাররফ হোসেন',
                'designation' => 'সহকারী শিক্ষক (আইসিটি ও তথ্যপ্রযুক্তি)',
                'department' => 'কম্পিউটার ও আইসিটি ল্যাব',
                'qualification' => 'বি.এসসি (কম্পিউটার সায়েন্স), ডিপ্লোমা ইন আইসিটি',
                'joining' => '২০১৮',
                'phone' => '+৮৮০ ১৭১৮-৯০১২৩৪',
                'photo' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'name' => 'মাওলানা মো: ইউসুফ আলী',
                'designation' => 'সহকারী শিক্ষক (ধর্ম ও নৈতিক শিক্ষা)',
                'department' => 'ধর্মীয় শিক্ষা বিভাগ',
                'qualification' => 'ফাযিল, কামিল (হাদিস)',
                'joining' => '২০১১',
                'phone' => '+৮৮০ ১৭১৯-০১২৩৪৫',
                'photo' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
            ],
        ];
    @endphp

    {{-- Faculty Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @foreach($dummyStaff as $staff)
            <div class="bg-white border border-gray-300 rounded shadow-2xs overflow-hidden flex flex-col justify-between hover:border-[#005030] transition-colors">
                <div class="p-4 text-center space-y-3">
                    {{-- Photo Frame --}}
                    <div class="w-24 h-28 mx-auto border border-gray-300 rounded overflow-hidden bg-gray-100 shadow-2xs">
                        <img src="{{ $staff['photo'] }}"
                             alt="{{ $staff['name'] }}"
                             class="w-full h-full object-cover">
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 text-sm leading-snug">
                            {{ $staff['name'] }}
                        </h3>
                        <p class="text-xs font-semibold text-[#005030] mt-0.5">
                            {{ $staff['designation'] }}
                        </p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-[10px] font-medium border border-gray-200">
                            {{ $staff['department'] }}
                        </span>
                    </div>

                    <p class="text-[11px] text-gray-500 italic">
                        {{ $staff['qualification'] }}
                    </p>
                </div>

                {{-- Contact Footer --}}
                <div class="px-3 py-2 bg-gray-50 border-t border-gray-200 text-center text-xs text-gray-600">
                    <span class="text-[11px] font-medium">মোবাইল: {{ $staff['phone'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
