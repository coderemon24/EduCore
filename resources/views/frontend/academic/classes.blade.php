@extends('layouts.app')

@section('title', 'শ্রেণিসমূহ ও পাঠ্যসূচি | ' . ($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়'))
@section('meta_description', 'সোনাতলা উচ্চ বিদ্যালয়ের বিভিন্ন শ্রেণির শাখা বণ্টন, আসন সংখ্যা, শ্রেণি শিক্ষক ও পাঠ্যক্রমের পূর্ণাঙ্গ বিবরণ।')

@section('content')
{{-- Breadcrumbs & Session Meta --}}
<div class="bg-gray-100 border-b border-gray-200 py-3 text-xs text-gray-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div class="flex items-center gap-1.5 font-medium">
            <a href="{{ route('frontend.home') }}" class="hover:text-[#005030] transition-colors">প্রচ্ছদ</a>
            <span>/</span>
            <span class="text-gray-400">একাডেমিক</span>
            <span>/</span>
            <span class="text-gray-900 font-bold">শ্রেণিসমূহ ও পাঠ্যসূচি</span>
        </div>
        <div class="hidden sm:flex items-center gap-2 text-gray-500 font-medium">
            <i class="fa-solid fa-calendar-check text-[#005030]"></i>
            <span>শিক্ষাবর্ষ: ২০২৬ খ্রি.</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8"
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

    {{-- Hero Banner with Telemetry Metrics --}}
    <div class="bg-[#005030] text-white p-6 sm:p-8 rounded-xl shadow-xs border border-emerald-900/40 relative overflow-hidden">
        {{-- Subtle decorative watermark icon --}}
        <div class="absolute -right-6 -bottom-8 text-emerald-800/25 text-9xl pointer-events-none select-none">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="max-w-2xl space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-900/80 text-emerald-200 text-xs font-semibold border border-emerald-700/60 shadow-2xs">
                    <i class="fa-solid fa-school text-amber-400"></i>
                    <span>প্রাতিষ্ঠানিক একাডেমিক কাঠামো</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    শ্রেণিসমূহ ও পাঠ্যসূচি (Academic Classes)
                </h1>
                <p class="text-xs sm:text-sm text-emerald-100/90 leading-relaxed font-normal">
                    সোনাতলা উচ্চ বিদ্যালয়ের পাঠদানকৃত বিভিন্ন শ্রেণির শাখা বিভাজন, দায়িত্বপ্রাপ্ত শ্রেণি শিক্ষক, শ্রেণিকক্ষ, আসন সংখ্যা ও পাঠ্যক্রমের পূর্ণাঙ্গ তথ্যমালা।
                </p>
            </div>

            {{-- 3 Telemetry Metric Badges --}}
            <div class="grid grid-cols-3 gap-3 sm:gap-4 shrink-0">
                <div class="bg-[#003820] border border-emerald-700/60 rounded-lg p-3 text-center min-w-[90px] sm:min-w-[110px] shadow-2xs">
                    <div class="text-xs text-emerald-300 font-medium flex items-center justify-center gap-1.5 mb-1">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>মোট শ্রেণি</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-white font-mono">
                        {{ $telemetry['total_classes'] ?? $classes->count() }}
                    </div>
                </div>

                <div class="bg-[#003820] border border-emerald-700/60 rounded-lg p-3 text-center min-w-[90px] sm:min-w-[110px] shadow-2xs">
                    <div class="text-xs text-emerald-300 font-medium flex items-center justify-center gap-1.5 mb-1">
                        <i class="fa-solid fa-layer-group"></i>
                        <span>মোট শাখা</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-amber-400 font-mono">
                        {{ $telemetry['total_sections'] ?? 0 }}
                    </div>
                </div>

                <div class="bg-[#003820] border border-emerald-700/60 rounded-lg p-3 text-center min-w-[90px] sm:min-w-[110px] shadow-2xs">
                    <div class="text-xs text-emerald-300 font-medium flex items-center justify-center gap-1.5 mb-1">
                        <i class="fa-solid fa-book-open"></i>
                        <span>পাঠ্য বিষয়</span>
                    </div>
                    <div class="text-xl sm:text-2xl font-black text-emerald-200 font-mono">
                        {{ $telemetry['total_subjects'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($classes->isEmpty())
        {{-- Friendly Empty State when database has no active classes --}}
        <div class="bg-white border border-slate-200 rounded-xl p-12 text-center shadow-2xs space-y-4">
            <div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 text-[#005030] flex items-center justify-center text-2xl border border-emerald-200">
                <i class="fa-solid fa-school"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-slate-900">বর্তমানে কোনো শ্রেণির তথ্য উপলব্ধ নেই</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    আমাদের প্রাতিষ্ঠানিক শ্রেণি তালিকা হালনাগাদ করা হচ্ছে। বিস্তারিত তথ্যের জন্য বিদ্যালয় কার্যালয়ে যোগাযোগ করুন।
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('frontend.contact') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#005030] text-white text-xs font-bold hover:bg-[#003820] transition-colors">
                    <i class="fa-solid fa-envelope"></i>
                    <span>যোগাযোগ করুন</span>
                </a>
            </div>
        </div>
    @else
        {{-- Tactile Class Selector Navigation Bar --}}
        <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5 shadow-2xs space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#005030]"></span>
                    <h2 class="text-sm font-bold text-slate-900">শ্রেণি নির্বাচন করুন (Select Class)</h2>
                </div>
                <div class="text-[11px] text-slate-500 font-medium">
                    নির্দিষ্ট শ্রেণির শাখা ও বিষয় দেখতে নিচের বোতামগুলোতে ক্লিক করুন
                </div>
            </div>

            {{-- Responsive class pills (horizontal scroll on mobile, flex-wrap on tablet/desktop) --}}
            <div class="flex items-center gap-2.5 overflow-x-auto pb-1 sm:flex-wrap">
                @foreach($classes as $c)
                    <button type="button"
                            @click="setActive('{{ $c->id }}')"
                            :class="activeClass == '{{ $c->id }}'
                                ? 'bg-[#005030] text-white border-[#005030] shadow-sm font-bold'
                                : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100 hover:border-slate-300 font-medium'"
                            class="group relative flex-shrink-0 px-4 py-2.5 rounded-lg border text-xs transition-all duration-150 cursor-pointer text-left">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-xs"
                               :class="activeClass == '{{ $c->id }}' ? 'text-amber-400' : 'text-slate-400 group-hover:text-slate-600'"></i>
                            <span class="font-bold">{{ $c->name }}</span>
                        </div>
                        <div class="text-[10px] mt-0.5 opacity-80"
                             :class="activeClass == '{{ $c->id }}' ? 'text-emerald-100' : 'text-slate-500'">
                            {{ $c->sections->count() }}টি শাখা · {{ $c->subjects->count() }}টি বিষয়
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Class Content Panels --}}
        @foreach($classes as $c)
            @php
                $sectionsCount = $c->sections->count();
                $subjectsCount = $c->subjects->count();
                $optionalCount = $c->subjects->where('pivot.type', 'optional')->count();
                $isSecondaryClass = in_array($c->numeric_code, [9, 10]) || str_contains($c->name, '৯ম') || str_contains($c->name, '১০ম') || str_contains(strtolower($c->name), 'nine') || str_contains(strtolower($c->name), 'ten');
            @endphp

            <div x-show="activeClass == '{{ $c->id }}'"
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-6">

                {{-- Class Summary Header Card --}}
                <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-emerald-50 text-[#005030] flex items-center justify-center text-2xl border border-emerald-200 shrink-0">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-xl font-bold text-slate-900">{{ $c->name }}</h3>
                                @if($c->numeric_code)
                                    <span class="px-2 py-0.5 rounded text-[11px] font-mono font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        কোড: {{ $c->numeric_code }}
                                    </span>
                                @endif
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    নিয়মিত পাঠদান
                                </span>
                            </div>

                            <div class="flex items-center gap-3 mt-1.5 text-xs text-slate-600 flex-wrap">
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-layer-group text-slate-400"></i>
                                    <strong>{{ $sectionsCount }}</strong>টি শাখা
                                </span>
                                <span class="text-slate-300">·</span>
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-book-open text-slate-400"></i>
                                    <strong>{{ $subjectsCount }}</strong>টি নির্ধারিত বিষয়
                                </span>
                                @if($optionalCount > 0)
                                    <span class="text-slate-300">·</span>
                                    <span class="inline-flex items-center gap-1.5 text-amber-700 font-medium">
                                        <i class="fa-solid fa-circle-check text-amber-500"></i>
                                        <strong>{{ $optionalCount }}</strong>টি ঐচ্ছিক বিষয়
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions: Routine Link & Group Badges --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        @if($isSecondaryClass)
                            {{-- Secondary Streams for Class 9 and 10 --}}
                            <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-200 rounded-lg p-1.5">
                                <span class="text-[11px] text-slate-500 font-semibold px-1">বিভাগ:</span>
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-flask text-[10px]"></i> বিজ্ঞান
                                </span>
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-scale-balanced text-[10px]"></i> মানবিক
                                </span>
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-briefcase text-[10px]"></i> ব্যবসায় শিক্ষা
                                </span>
                            </div>
                        @endif

                        <a href="{{ route('frontend.academic.routine') }}?class_id={{ $c->id }}"
                           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-[#005030] hover:bg-[#003820] text-white text-xs font-bold shadow-2xs transition-colors">
                            <i class="fa-solid fa-calendar-days text-amber-400"></i>
                            <span>ক্লাস রুটিন দেখুন</span>
                        </a>
                    </div>
                </div>

                {{-- 2-Column Responsive Detail Layout --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                    {{-- Left Column: Sections & Class Teachers (5 Cols) --}}
                    <div class="lg:col-span-5 bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-md bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-layer-group"></i>
                                </div>
                                <h4 class="font-bold text-sm text-slate-900">শাখাসমূহ (Sections)</h4>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                {{ $sectionsCount }}টি শাখা
                            </span>
                        </div>

                        @if($c->sections->isEmpty())
                            <div class="p-6 text-center text-xs text-slate-500 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                                <i class="fa-solid fa-circle-info text-slate-400 text-base mb-1 block"></i>
                                এই শ্রেণিতে বর্তমানে কোনো শাখা অন্তর্ভুক্ত করা হয়নি।
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($c->sections as $section)
                                    <div class="p-4 rounded-lg border border-slate-200 bg-slate-50/60 hover:bg-white hover:border-emerald-300 transition-all space-y-3">
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-2">
                                                <span class="w-8 h-8 rounded-lg bg-[#005030] text-white flex items-center justify-center font-bold text-xs shadow-2xs">
                                                    {{ $section->name }}
                                                </span>
                                                <div>
                                                    <div class="font-bold text-xs text-slate-900">
                                                        শাখা: {{ $section->name }}
                                                    </div>
                                                    <div class="text-[10px] text-slate-500">
                                                        নিয়মিত শ্রেণি পাঠদান
                                                    </div>
                                                </div>
                                            </div>

                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                                সক্রিয়
                                            </span>
                                        </div>

                                        {{-- Class Teacher Details --}}
                                        <div class="bg-white p-2.5 rounded-md border border-slate-100 flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-xs shrink-0 border border-slate-200">
                                                <i class="fa-solid fa-chalkboard-user"></i>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                                                    শ্রেণি শিক্ষক (Class Teacher)
                                                </div>
                                                <div class="text-xs font-bold text-slate-900 truncate">
                                                    @if($section->classTeacher)
                                                        {{ $section->classTeacher->full_name ?? $section->classTeacher->name }}
                                                    @else
                                                        <span class="text-slate-500 italic">অফিস তত্ত্বাবধানে</span>
                                                    @endif
                                                </div>
                                                @if($section->classTeacher && !empty($section->classTeacher->designation))
                                                    <div class="text-[11px] text-emerald-700">
                                                        {{ $section->classTeacher->designation }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Section Telemetry: Room & Seat Capacity --}}
                                        <div class="grid grid-cols-2 gap-2 text-xs text-slate-600 pt-1 border-t border-slate-100">
                                            <div class="flex items-center gap-1.5 text-[11px]">
                                                <i class="fa-solid fa-door-open text-slate-400"></i>
                                                <span>কক্ষ: <strong>{{ $section->room_number ?? 'নির্ধারিত' }}</strong></span>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-[11px] justify-end">
                                                <i class="fa-solid fa-user-group text-slate-400"></i>
                                                <span>আসন: <strong>{{ $section->capacity ?? '৫০' }}</strong> জন</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Right Column: Curriculum & Assigned Subjects (7 Cols) --}}
                    <div class="lg:col-span-7 bg-white border border-slate-200 rounded-xl p-5 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-md bg-emerald-50 text-[#005030] flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-book-open"></i>
                                </div>
                                <h4 class="font-bold text-sm text-slate-900">পাঠ্যক্রম ও বিষয়সমূহ (Curriculum Subjects)</h4>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                {{ $subjectsCount }}টি বিষয়
                            </span>
                        </div>

                        @if($c->subjects->isEmpty())
                            <div class="p-8 text-center text-xs text-slate-500 bg-slate-50 rounded-lg border border-dashed border-slate-200 space-y-2">
                                <i class="fa-solid fa-book-open text-slate-400 text-2xl block"></i>
                                <div>এই শ্রেণির জন্য এখনো কোনো বিষয় বরাদ্দ করা হয়নি।</div>
                            </div>
                        @else
                            <div class="overflow-hidden border border-slate-200 rounded-lg">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                                        <tr>
                                            <th class="p-3">বিষয়ের নাম</th>
                                            <th class="p-3 text-center">কোড</th>
                                            <th class="p-3 text-center">পূর্ণমান</th>
                                            <th class="p-3 text-center">পাস নম্বর</th>
                                            <th class="p-3 text-right">ধরন</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-slate-800">
                                        @foreach($c->subjects as $subject)
                                            @php
                                                $isOptional = ($subject->pivot->type ?? '') === 'optional';
                                            @endphp
                                            <tr class="hover:bg-slate-50/70 transition-colors">
                                                <td class="p-3 font-medium text-slate-900">
                                                    <div class="flex items-center gap-2">
                                                        <i class="fa-solid fa-book-bookmark text-slate-400 text-[11px]"></i>
                                                        <span class="font-bold">{{ $subject->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="p-3 text-center font-mono text-slate-600 text-[11px]">
                                                    {{ $subject->code ?? '—' }}
                                                </td>
                                                <td class="p-3 text-center font-mono font-semibold text-slate-900">
                                                    {{ $subject->pivot->full_marks ?? 100 }}
                                                </td>
                                                <td class="p-3 text-center font-mono text-slate-600">
                                                    {{ $subject->pivot->pass_marks ?? 33 }}
                                                </td>
                                                <td class="p-3 text-right">
                                                    @if($isOptional)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-300">
                                                            ঐচ্ছিক
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                            আবশ্যিক
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        {{-- Curriculum Footer Note --}}
                        <div class="bg-amber-50/50 border border-amber-200/80 rounded-lg p-3 text-[11px] text-amber-900 flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-info text-amber-600 mt-0.5 shrink-0"></i>
                            <div>
                                জাতীয় শিক্ষাক্রম ও পাঠ্যপুস্তক বোর্ড (NCTB) কর্তৃক অনুমোদিত পাঠ্যসূচি অনুযায়ী পাঠদান পরিচালিত হয়। বিস্তারিত সিলেবাসের জন্য নোটিশ বোর্ড বা শ্রেণি শিক্ষকের সাথে যোগাযোগ করুন।
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- Bottom Informational Callout Bar --}}
    <div class="bg-slate-100 border border-slate-200 rounded-xl p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-xs text-slate-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-[#005030] text-sm shrink-0">
                <i class="fa-solid fa-headset"></i>
            </div>
            <div>
                <div class="font-bold text-slate-900 text-sm">ভর্তি ও একাডেমিক তথ্য সহায়তা</div>
                <div class="text-slate-500 text-[11px]">কোনো শ্রেণির আসন বা ভর্তি প্রক্রিয়া সম্পর্কে জানতে সরাসরি যোগাযোগ করুন।</div>
            </div>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('frontend.academic.routine') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-800 font-bold text-xs transition-colors shadow-2xs">
                <i class="fa-solid fa-calendar-days text-[#005030]"></i>
                <span>দৈনিক ক্লাস রুটিন</span>
            </a>
            <a href="{{ route('frontend.contact') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#005030] hover:bg-[#003820] text-white font-bold text-xs transition-colors shadow-2xs">
                <i class="fa-solid fa-paper-plane text-amber-400"></i>
                <span>যোগাযোগ করুন</span>
            </a>
        </div>
    </div>
</div>
@endsection
