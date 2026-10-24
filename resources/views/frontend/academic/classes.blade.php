@extends('layouts.app')

@section('title', 'শ্রেণিসমূহ ও বিষয় | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের বিভিন্ন শ্রেণির শাখা বণ্টন, আসন সংখ্যা, শ্রেণি শিক্ষক ও জাতীয় শিক্ষাক্রম (NCTB) অনুমোদিত পাঠ্যক্রমের পূর্ণাঙ্গ বিবরণ।')

@section('content')

{{-- Top Hero Banner: Matches Reference Image Clean Green Header --}}
<div class="bg-[#005030] text-white py-8 sm:py-10 border-b border-emerald-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        {{-- Academic Pill Badge --}}
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white text-[#005030] text-xs font-bold shadow-xs">
            <i class="fa-solid fa-graduation-cap text-[#005030]"></i>
            <span>একাডেমিক</span>
        </div>

        {{-- Page Heading --}}
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white mt-3 mb-2 tracking-tight">
            শ্রেণিসমূহ ও বিষয়
        </h1>
        <span class="sr-only">শ্রেণিসমূহ ও পাঠ্যসূচি</span>

        {{-- Breadcrumb Nav --}}
        <nav class="flex items-center gap-1.5 text-xs text-emerald-200/80 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}" class="hover:text-white transition-colors">হোম</a>
            <span>›</span>
            <span>একাডেমিক</span>
            <span>›</span>
            <span class="text-white font-bold">শ্রেণিসমূহ</span>
        </nav>
    </div>
</div>

{{-- Main Substance Section --}}
<section class="py-8 sm:py-12 bg-slate-50/60 min-h-[500px]"
         id="classes-directory"
         x-data="{
            activeClass: '{{ $selectedClassId ?? ($classes->first()?->id ?? '') }}',
            setActive(id) {
                this.activeClass = id;
                if (history.pushState) {
                    history.replaceState(null, null, '#class-' + id);
                }
            },
            init() {
                var hash = window.location.hash;
                if (hash && hash.startsWith('#class-')) {
                    var hashId = hash.replace('#class-', '');
                    if (hashId) { this.activeClass = hashId; }
                }
            }
         }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 space-y-6">

        @if($classes->isEmpty())
            {{-- Clean Empty State --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-2xs space-y-3 max-w-md mx-auto my-6">
                <div class="w-14 h-14 mx-auto rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center text-2xl border border-emerald-200">
                    <i class="fa-solid fa-school"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">বর্তমানে কোনো শ্রেণির তথ্য উপলব্ধ নেই</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    বিদ্যালয় প্রশাসন কর্তৃক শ্রেণি তালিকা হালনাগাদ করা হচ্ছে।
                </p>
                <div class="pt-2">
                    <a href="{{ route('frontend.contact') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#005030] text-white text-xs font-bold hover:bg-[#003820] transition-colors">
                        <i class="fa-solid fa-envelope"></i>
                        <span>যোগাযোগ করুন</span>
                    </a>
                </div>
            </div>
        @else
            {{-- Class Selector Tabs: Exactly Matching Reference Layout --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-2.5">
                @foreach($classes as $c)
                    <button type="button"
                            @click="setActive('{{ $c->id }}')"
                            :class="activeClass == '{{ $c->id }}'
                                ? 'bg-[#005030] text-white border-2 border-[#005030] font-bold shadow-xs'
                                : 'bg-white text-slate-700 border border-slate-200 hover:border-slate-300 hover:bg-slate-50 font-semibold shadow-2xs'"
                            class="px-2.5 py-2.5 rounded-xl text-center text-xs sm:text-sm transition-all duration-150 cursor-pointer truncate">
                        {{ $c->name }}
                    </button>
                @endforeach
            </div>

            {{-- Class Detail Showcase Panels --}}
            @foreach($classes as $c)
                @php
                    $sectionsCount = $c->sections->count();
                    $subjectsCount = $c->subjects->count();
                    $optionalCount = $c->subjects->where('pivot.type', 'optional')->count();
                @endphp

                <div x-show="activeClass == '{{ $c->id }}'"
                     x-cloak
                     class="space-y-6">

                    {{-- Class Banner Card: Soft Emerald Tint with Mortarboard Icon --}}
                    <div class="bg-[#eef7f2] border border-[#d2ecd9] rounded-2xl p-4 sm:p-5 flex items-center gap-4 shadow-2xs">
                        <div class="w-12 h-12 rounded-xl bg-[#005030] text-white flex items-center justify-center text-xl shrink-0 shadow-xs">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 leading-snug">
                                {{ $c->name }}
                            </h2>
                            <div class="flex items-center gap-2 text-xs text-slate-600 flex-wrap mt-1 font-medium">
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-tags text-slate-400 text-[11px]"></i>
                                    <span>{{ $sectionsCount }}টি শাখা</span>
                                </span>
                                <span class="text-slate-300">·</span>
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-book text-slate-400 text-[11px]"></i>
                                    <span>{{ $subjectsCount }}টি বিষয়</span>
                                </span>
                                @if($optionalCount > 0)
                                    <span class="text-slate-300">·</span>
                                    <span class="inline-flex items-center gap-1.5 text-amber-700 font-semibold">
                                        <i class="fa-solid fa-circle-check text-amber-600 text-[11px]"></i>
                                        <span>{{ $optionalCount }}টি ঐচ্ছিক</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Main Detail Grid: Sections (Left) & Subjects (Right) --}}
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-start">

                        {{-- Left Column: Sections (শাখাসমূহ) --}}
                        <div class="md:col-span-4 lg:col-span-3 bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-4">
                            <div class="flex items-center gap-2 text-slate-800 font-bold text-sm sm:text-base pb-3 border-b border-slate-100">
                                <i class="fa-solid fa-tags text-slate-400 text-xs"></i>
                                <span>শাখাসমূহ</span>
                            </div>

                            @if($c->sections->isEmpty())
                                <div class="text-xs text-slate-400 py-4 text-center">
                                    কোনো শাখা যোগ করা হয়নি।
                                </div>
                            @else
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-2 gap-2.5">
                                    @foreach($c->sections as $section)
                                        <div class="flex flex-col items-center justify-center p-3.5 rounded-xl bg-[#eef7f2] border border-[#d2ecd9] text-center shadow-2xs group hover:border-[#005030] transition-colors">
                                            <span class="text-xl font-bold text-[#005030] leading-none">{{ $section->name }}</span>
                                            <span class="text-[11px] text-slate-500 font-medium mt-1">শাখা</span>
                                            <span class="sr-only">শাখা: {{ $section->name }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Section Teacher Reassurance Line --}}
                                @if($c->sections->whereNotNull('classTeacher')->isNotEmpty())
                                    <div class="pt-3 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                                        @foreach($c->sections as $section)
                                            @if($section->classTeacher)
                                                <div class="flex items-center gap-1.5 truncate">
                                                    <i class="fa-solid fa-user-check text-[#005030] text-[10px]"></i>
                                                    <span class="truncate">শাখা {{ $section->name }}: <strong class="text-slate-900 font-semibold">{{ $section->classTeacher->full_name ?? $section->classTeacher->name }}</strong></span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Right Column: Subjects (বিষয়সমূহ) --}}
                        <div class="md:col-span-8 lg:col-span-9 bg-white border border-slate-200/90 rounded-2xl p-5 shadow-2xs space-y-4">
                            <div class="flex items-center gap-2 text-slate-800 font-bold text-sm sm:text-base pb-3 border-b border-slate-100">
                                <i class="fa-solid fa-book text-slate-400 text-xs"></i>
                                <span>বিষয়সমূহ</span>
                            </div>

                            @if($c->subjects->isEmpty())
                                <div class="text-xs text-slate-400 py-6 text-center">
                                    কোনো বিষয় যোগ করা হয়নি।
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    @foreach($c->subjects as $subject)
                                        @php
                                            $isOptional = ($subject->pivot->type ?? '') === 'optional';
                                        @endphp
                                        <div class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-[#fbfcfd] hover:bg-white border border-slate-200/80 hover:border-emerald-300 transition-all duration-150 shadow-2xs">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <i class="fa-solid fa-arrow-right-to-bracket text-emerald-600 text-xs shrink-0"></i>
                                                <span class="text-xs sm:text-sm font-semibold text-slate-800 truncate">{{ $subject->name }}</span>
                                            </div>
                                            @if($isOptional)
                                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-[#d4a017] text-white shrink-0 ml-2">ঐচ্ছিক</span>
                                            @else
                                                <span class="sr-only">আবশ্যিক</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        @endif

    </div>
</section>
@endsection
