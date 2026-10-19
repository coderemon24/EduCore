@extends('layouts.app')

@section('title', 'বিদ্যালয় পরিচিতি | সোনাতলা উচ্চ বিদ্যালয়')

@section('content')
{{-- Breadcrumb & Title --}}
<div class="bg-gray-100 border-b border-gray-200 py-3 text-xs text-gray-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div class="flex items-center gap-1.5 font-medium">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030]">প্রচ্ছদ</a>
            <span>/</span>
            <span class="text-gray-900 font-bold">পরিচিতি</span>
        </div>
        <span class="text-gray-400">স্থাপিত: ১৯৬৮ খ্রি.</span>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- Institutional Overview --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-8 bg-white border border-gray-300 rounded shadow-2xs p-6 space-y-4">
            <div class="border-b border-gray-200 pb-3">
                <span class="px-2.5 py-1 rounded bg-[#005030] text-white text-xs font-bold uppercase tracking-wider">
                    গৌরবোজ্জ্বল ইতিহাস
                </span>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">
                    সোনাতলা উচ্চ বিদ্যালয় — ইতিহাস ও ঐতিহ্য
                </h1>
            </div>

            <p class="text-xs sm:text-sm text-gray-700 leading-relaxed">
                পাবনা জেলার সাঁথিয়া উপজেলার অন্তর্গত বেড়া-সোনাতলা এলাকায় ১৯৬৮ সালে স্থানীয় শিক্ষানুরাগী, সমাজসেবক ও বিদ্যোৎসাহী ব্যক্তিবর্গের ঐকান্তিক প্রচেষ্টায় সোনাতলা উচ্চ বিদ্যালয় প্রতিষ্ঠিত হয়। সূচনালগ্ন থেকেই প্রতিষ্ঠানটি এই অঞ্চলের সাধারণ পরিবারের সন্তানদের মধ্যে আধুনিক ও নৈতিক শিক্ষার আলো বিস্তার করে আসছে।
            </p>

            <p class="text-xs sm:text-sm text-gray-700 leading-relaxed">
                বর্তমানে বিদ্যালয়টিতে সুবিশাল খেলার মাঠ, দ্বিতল ও তিনতল বিশিষ্ট আধুনিক একাডেমিক ভবন, সমৃদ্ধ বিজ্ঞানাগার, শেখ রাসেল ডিজিটাল কম্পিউটার ল্যাব এবং সুপরিসর লাইব্রেরি রয়েছে। বিদ্যালয়ের দক্ষ ও অভিজ্ঞ শিক্ষকমণ্ডলী নিরলসভাবে পাঠদান কার্যক্রমে নিবেদিত রয়েছেন।
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-3">
                <div class="p-4 bg-gray-50 border border-gray-200 rounded space-y-1.5" id="mission">
                    <h3 class="font-bold text-sm text-[#005030]">আমাদের লক্ষ্য (Vision)</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        প্রতিটি শিক্ষার্থীকে সুনাগরিক, দেশপ্রেমিক, আধুনিক বিজ্ঞানমনস্ক ও মানবিক মূল্যবোধসম্পন্ন মানুষ হিসেবে গড়ে তোলা।
                    </p>
                </div>
                <div class="p-4 bg-gray-50 border border-gray-200 rounded space-y-1.5">
                    <h3 class="font-bold text-sm text-[#005030]">আমাদের উদ্দেশ্য (Mission)</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        আনন্দমুখর ও সুশৃঙ্খল পরিবেশে মানসম্মত শিক্ষা নিশ্চিত করা এবং আধুনিক তথ্যপ্রযুক্তির মাধ্যমে শিক্ষার্থীদের ভবিষ্যতের উপযোগী করে তোলা।
                    </p>
                </div>
            </div>
        </div>

        {{-- Right Side Table: School at a Glance --}}
        <div class="lg:col-span-4 bg-white border border-gray-300 rounded shadow-2xs overflow-hidden">
            <div class="bg-[#005030] text-white px-4 py-2.5 font-bold text-xs uppercase tracking-wider">
                এক নজরে প্রতিষ্ঠান
            </div>
            <dl class="divide-y divide-gray-200 text-xs text-gray-800 p-4">
                <div class="py-2 flex justify-between">
                    <dt class="text-gray-500">প্রতিষ্ঠানের নাম:</dt>
                    <dd class="font-bold text-right">সোনাতলা উচ্চ বিদ্যালয়</dd>
                </div>
                <div class="py-2 flex justify-between">
                    <dt class="text-gray-500">EIIN নম্বর:</dt>
                    <dd class="font-bold text-[#005030] font-mono">১২৫৬৯৩</dd>
                </div>
                <div class="py-2 flex justify-between">
                    <dt class="text-gray-500">স্কুল কোড:</dt>
                    <dd class="font-bold font-mono">৮১০৭</dd>
                </div>
                <div class="py-2 flex justify-between">
                    <dt class="text-gray-500">এমপিও কোড:</dt>
                    <dd class="font-bold font-mono">৮১০৭০২১৩০২</dd>
                </div>
                <div class="py-2 flex justify-between">
                    <dt class="text-gray-500">শিক্ষা বোর্ড:</dt>
                    <dd class="font-bold">মাধ্যমিক ও উচ্চমাধ্যমিক (রাজশাহী)</dd>
                </div>
                <div class="py-2 flex justify-between">
                    <dt class="text-gray-500">ডাকঘর ও উপজেলা:</dt>
                    <dd class="font-bold">সোনাতলা, সাঁথিয়া, পাবনা</dd>
                </div>
                <div class="py-2 flex justify-between">
                    <dt class="text-gray-500">প্রধান শিক্ষক:</dt>
                    <dd class="font-bold">মো: খলিলুর রহমান</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Managing Committee (পরিচালনা পর্ষদ) --}}
    <div class="bg-white border border-gray-300 rounded shadow-2xs overflow-hidden" id="committee">
        <div class="bg-[#005030] text-white px-4 py-2.5 font-bold text-sm">
            বিদ্যালয় পরিচালনা পর্ষদ (Managing Committee)
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-left text-xs border border-gray-300">
                <thead class="bg-gray-100 text-gray-800 font-bold border-b border-gray-300">
                    <tr>
                        <th class="p-2.5 border-r border-gray-300">ক্রমিক</th>
                        <th class="p-2.5 border-r border-gray-300">নাম</th>
                        <th class="p-2.5 border-r border-gray-300">পদবি</th>
                        <th class="p-2.5">মন্তব্য</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-800">
                    <tr>
                        <td class="p-2.5 border-r border-gray-200 text-center font-bold">০১</td>
                        <td class="p-2.5 border-r border-gray-200 font-bold text-[#005030]">জনাব মো: রফিকুল ইসলাম</td>
                        <td class="p-2.5 border-r border-gray-200 font-semibold">সভাপতি</td>
                        <td class="p-2.5 text-gray-500">বিশিষ্ট শিক্ষানুরাগী ও সমাজসেবক</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="p-2.5 border-r border-gray-200 text-center font-bold">০২</td>
                        <td class="p-2.5 border-r border-gray-200 font-bold text-[#005030]">মো: খলিলুর রহমান</td>
                        <td class="p-2.5 border-r border-gray-200 font-semibold">সদস্য সচিব</td>
                        <td class="p-2.5 text-gray-500">প্রধান শিক্ষক, পদাধিকারবলে</td>
                    </tr>
                    <tr>
                        <td class="p-2.5 border-r border-gray-200 text-center font-bold">০৩</td>
                        <td class="p-2.5 border-r border-gray-200 font-bold text-[#005030]">জনাব মো: জয়নাল আবেদীন</td>
                        <td class="p-2.5 border-r border-gray-200 font-semibold">অভিভাবক সদস্য</td>
                        <td class="p-2.5 text-gray-500">নির্বাচিত অভিভাবক প্রতিনিধি</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="p-2.5 border-r border-gray-200 text-center font-bold">০৪</td>
                        <td class="p-2.5 border-r border-gray-200 font-bold text-[#005030]">মো: আব্দুল মজিদ</td>
                        <td class="p-2.5 border-r border-gray-200 font-semibold">শিক্ষক প্রতিনিধি</td>
                        <td class="p-2.5 text-gray-500">সিনিয়র শিক্ষক</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
