@extends('layouts.admin')

@section('title', 'About Page Management | EduCore Admin')

@section('admin-content')
<div class="space-y-6">

    {{-- Breadcrumb & Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-violet-600 transition-colors">Dashboard</a>
                <span>/</span>
                <span class="text-slate-600">Frontend</span>
                <span>/</span>
                <span class="text-slate-600">Pages</span>
                <span>/</span>
                <span class="text-slate-900 font-bold">About</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">About Page Management</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Customize institutional history, vision, milestones, leadership roster, and dynamic SEO settings.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('frontend.about') }}"
               target="_blank"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-2xs transition-colors">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>View Live Page</span>
            </a>

            <button type="submit"
                    form="about-page-form"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white hover:bg-violet-700 shadow-xs transition-colors cursor-pointer">
                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Save All Changes</span>
            </button>
        </div>
    </div>

    {{-- Main Form Canvas --}}
    <form id="about-page-form"
          action="{{ route('admin.frontend.pages.about.update') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Tabbed Navigation Bar --}}
        <div class="border-b border-slate-200 bg-white rounded-t-xl px-4 pt-3 flex flex-wrap gap-2 shadow-2xs" id="about-tabs-container">
            <button type="button"
                    onclick="switchAboutTab('tab-basic')"
                    id="btn-tab-basic"
                    class="about-tab-btn active inline-flex items-center gap-2 px-3.5 py-2.5 text-xs font-bold border-b-2 border-violet-600 text-violet-700 cursor-pointer transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>1. Basic & History</span>
            </button>

            <button type="button"
                    onclick="switchAboutTab('tab-vision')"
                    id="btn-tab-vision"
                    class="about-tab-btn inline-flex items-center gap-2 px-3.5 py-2.5 text-xs font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-800 cursor-pointer transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span>2. Vision & Mission</span>
            </button>

            <button type="button"
                    onclick="switchAboutTab('tab-milestones')"
                    id="btn-tab-milestones"
                    class="about-tab-btn inline-flex items-center gap-2 px-3.5 py-2.5 text-xs font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-800 cursor-pointer transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>3. Milestones Timeline</span>
            </button>

            <button type="button"
                    onclick="switchAboutTab('tab-stats')"
                    id="btn-tab-stats"
                    class="about-tab-btn inline-flex items-center gap-2 px-3.5 py-2.5 text-xs font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-800 cursor-pointer transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>4. Key Statistics</span>
            </button>

            <button type="button"
                    onclick="switchAboutTab('tab-committee')"
                    id="btn-tab-committee"
                    class="about-tab-btn inline-flex items-center gap-2 px-3.5 py-2.5 text-xs font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-800 cursor-pointer transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>5. Info & Committee</span>
            </button>

            <button type="button"
                    onclick="switchAboutTab('tab-seo')"
                    id="btn-tab-seo"
                    class="about-tab-btn inline-flex items-center gap-2 px-3.5 py-2.5 text-xs font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-800 cursor-pointer transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="flex items-center gap-1.5">
                    <span>6. SEO & Social Meta</span>
                    <span class="px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold">SEO</span>
                </span>
            </button>
        </div>

        {{-- TAB 1: BASIC & HISTORY --}}
        <div id="tab-basic" class="about-tab-pane bg-white rounded-b-xl border border-t-0 border-slate-200 p-6 space-y-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">প্রাতিষ্ঠানিক পরিচিতি ও সংক্ষিপ্ত ইতিহাস (Basic & History)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Configure institutional titles, established year, and the core narrative story.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <div class="md:col-span-8 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">পৃষ্ঠার প্রধান শিরোনাম (Page Title) <span class="text-red-500">*</span></label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $about->title) }}"
                           required
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-violet-500 focus:ring-1 focus:ring-violet-500">
                </div>

                <div class="md:col-span-4 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">প্রতিষ্ঠার সন (Established Year)</label>
                    <input type="text"
                           name="established_year"
                           value="{{ old('established_year', $about->established_year) }}"
                           placeholder="১৯৬৮"
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 font-mono">
                </div>

                <div class="md:col-span-12 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">উপ-শিরোনাম / প্রাতিষ্ঠানিক বার্তা (Subtitle)</label>
                    <input type="text"
                           name="subtitle"
                           value="{{ old('subtitle', $about->subtitle) }}"
                           placeholder="জ্ঞান, শৃঙ্খলা ও নৈতিক শিক্ষার প্রত্যয়ে ১৯৬৮ সাল থেকে নিরবচ্ছিন্ন অগ্রযাত্রা"
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-violet-500 focus:ring-1 focus:ring-violet-500">
                </div>

                <div class="md:col-span-12 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">ইতিহাস অংশের শিরোনাম (History Heading)</label>
                    <input type="text"
                           name="history_heading"
                           value="{{ old('history_heading', $about->history_heading) }}"
                           placeholder="বিদ্যালয়ের গৌরবোজ্জ্বল ইতিহাস ও ঐতিহ্য"
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-violet-500 focus:ring-1 focus:ring-violet-500">
                </div>

                <div class="md:col-span-12 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">বিস্তারিত ইতিহাস ও পটভূমি (History Narrative Story)</label>
                    <textarea name="history_content"
                              rows="6"
                              class="w-full rounded-lg border border-slate-300 bg-white p-3 text-xs text-slate-900 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 leading-relaxed">{{ old('history_content', $about->history_content) }}</textarea>
                    <p class="text-[11px] text-slate-500">Paragraphs are automatically formatted into readable sections on the public frontend.</p>
                </div>

                {{-- Campus Photograph Upload --}}
                <div class="md:col-span-6 p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3">
                    <label class="block text-xs font-bold text-slate-800">ক্যাম্পাস / ঐতিহাসিক ফিচার্ড ছবি (Featured Campus Photo)</label>
                    <div class="flex items-center gap-4">
                        <img src="{{ $about->history_image_url }}"
                             alt="Featured History Preview"
                             id="preview-history-img"
                             class="h-20 w-32 object-cover rounded-lg border border-slate-300 bg-white shrink-0">
                        <div class="space-y-1 text-xs">
                            <input type="file"
                                   name="history_image_file"
                                   accept="image/*"
                                   onchange="previewImage(this, 'preview-history-img')"
                                   class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer">
                            <p class="text-[10px] text-slate-400">Recommended: 1200x800px (Max 3MB, WebP/JPG/PNG)</p>
                        </div>
                    </div>
                </div>

                {{-- Hero Banner Image Upload --}}
                <div class="md:col-span-6 p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3">
                    <label class="block text-xs font-bold text-slate-800">হিরো ব্যানার ব্যাকগ্রাউন্ড ছবি (Hero Banner Optional)</label>
                    <div class="flex items-center gap-4">
                        <div class="h-20 w-32 rounded-lg border border-dashed border-slate-300 bg-white flex items-center justify-center shrink-0 text-slate-400 text-xs overflow-hidden">
                            @if($about->banner_image_url)
                                <img src="{{ $about->banner_image_url }}" alt="Banner" id="preview-banner-img" class="h-full w-full object-cover">
                            @else
                                <span id="preview-banner-text" class="text-[10px] text-center px-1">Default Gradient Banner</span>
                            @endif
                        </div>
                        <div class="space-y-1 text-xs">
                            <input type="file"
                                   name="banner_image_file"
                                   accept="image/*"
                                   onchange="previewImage(this, 'preview-banner-img', 'preview-banner-text')"
                                   class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer">
                            <p class="text-[10px] text-slate-400">Optional wide hero banner: 1920x600px</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: VISION, MISSION & VALUES --}}
        <div id="tab-vision" class="about-tab-pane hidden bg-white rounded-b-xl border border-t-0 border-slate-200 p-6 space-y-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">আমাদের লক্ষ্য, উদ্দেশ্য ও মূল আদর্শ (Vision, Mission & Values)</h3>
                <p class="text-xs text-slate-500 mt-0.5">Define core educational objectives and ethical pillars displayed prominently in modern 3-card grid format.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Vision Card --}}
                <div class="p-5 rounded-xl border border-emerald-200 bg-emerald-50/30 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                        <label class="text-xs font-bold text-emerald-950 uppercase tracking-wide">আমাদের লক্ষ্য (Vision Statement)</label>
                    </div>
                    <input type="text"
                           name="vision_title"
                           value="{{ old('vision_title', $about->vision_title) }}"
                           placeholder="আমাদের লক্ষ্য (Vision)"
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-900">
                    <textarea name="vision_text"
                              rows="4"
                              class="w-full rounded-lg border border-slate-300 bg-white p-3 text-xs text-slate-800 leading-relaxed">{{ old('vision_text', $about->vision_text) }}</textarea>
                </div>

                {{-- Mission Card --}}
                <div class="p-5 rounded-xl border border-blue-200 bg-blue-50/30 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                        <label class="text-xs font-bold text-blue-950 uppercase tracking-wide">আমাদের উদ্দেশ্য (Mission Statement)</label>
                    </div>
                    <input type="text"
                           name="mission_title"
                           value="{{ old('mission_title', $about->mission_title) }}"
                           placeholder="আমাদের উদ্দেশ্য (Mission)"
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-900">
                    <textarea name="mission_text"
                              rows="4"
                              class="w-full rounded-lg border border-slate-300 bg-white p-3 text-xs text-slate-800 leading-relaxed">{{ old('mission_text', $about->mission_text) }}</textarea>
                </div>

                {{-- Core Values Card --}}
                <div class="md:col-span-2 p-5 rounded-xl border border-slate-200 bg-slate-50/50 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wide">আমাদের মূল আদর্শ ও নৈতিক মূল্যবোধ (Core Values)</label>
                            <p class="text-xs text-slate-500">Key institutional guiding principles taught to students.</p>
                        </div>
                        <input type="text"
                               name="values_title"
                               value="{{ old('values_title', $about->values_title) }}"
                               placeholder="আমাদের মূল আদর্শ"
                               class="w-64 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-bold text-slate-800">
                    </div>

                    <div id="values-repeater-container" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @php
                            $valuesList = old('values_list', $about->values_list ?? []);
                            if(empty($valuesList)) { $valuesList = ['সততা ও নিষ্ঠা', 'শৃঙ্খলা ও নিয়মানুবর্তিতা']; }
                        @endphp
                        @foreach($valuesList as $idx => $val)
                            <div class="flex items-center gap-2 value-item-row">
                                <span class="h-1.5 w-1.5 rounded-full bg-violet-600 shrink-0"></span>
                                <input type="text"
                                       name="values_list[]"
                                       value="{{ $val }}"
                                       placeholder="মূল্যবোধ বা আদর্শ..."
                                       class="flex-1 rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-violet-500">
                                <button type="button"
                                        onclick="this.closest('.value-item-row').remove()"
                                        class="text-red-400 hover:text-red-600 p-1 cursor-pointer">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button"
                            onclick="addValueRow()"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-violet-700 hover:text-violet-900 cursor-pointer pt-1">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ নতুন মূল্যবোধ যোগ করুন</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- TAB 3: MILESTONES TIMELINE --}}
        <div id="tab-milestones" class="about-tab-pane hidden bg-white rounded-b-xl border border-t-0 border-slate-200 p-6 space-y-6 shadow-2xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">অগ্রযাত্রার ঐতিহাসিক মাইলফলক (Milestones Timeline)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Document pivotal achievements, establishment steps, and milestones from 1968 to present.</p>
                </div>
                <button type="button"
                        onclick="addMilestoneRow()"
                        class="inline-flex items-center gap-1.5 rounded-md bg-violet-50 text-violet-700 border border-violet-200 px-3 py-1.5 text-xs font-bold hover:bg-violet-100 cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ নতুন মাইলফলক যুক্ত করুন</span>
                </button>
            </div>

            <div id="milestones-repeater-list" class="space-y-3">
                @php
                    $milestones = old('milestones', $about->milestones ?? []);
                @endphp
                @forelse($milestones as $mIndex => $mItem)
                    <div class="milestone-card-row p-4 rounded-lg border border-slate-200 bg-slate-50/50 flex flex-col md:flex-row md:items-center gap-3">
                        <div class="w-full md:w-32 shrink-0 space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">সাল / বছর</label>
                            <input type="text"
                                   name="milestones[{{ $mIndex }}][year]"
                                   value="{{ $mItem['year'] ?? '' }}"
                                   placeholder="১৯৬৮"
                                   class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-mono font-bold text-slate-800">
                        </div>
                        <div class="w-full md:w-64 shrink-0 space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">শিরোনাম</label>
                            <input type="text"
                                   name="milestones[{{ $mIndex }}][title]"
                                   value="{{ $mItem['title'] ?? '' }}"
                                   placeholder="প্রতিষ্ঠা ও শুভ সূচনা"
                                   class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-800">
                        </div>
                        <div class="flex-1 space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">সংক্ষিপ্ত বিবরণ</label>
                            <input type="text"
                                   name="milestones[{{ $mIndex }}][description]"
                                   value="{{ $mItem['description'] ?? '' }}"
                                   placeholder="ঐতিহাসিক বিবরণী..."
                                   class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700">
                        </div>
                        <div class="pt-4 md:pt-4">
                            <button type="button"
                                    onclick="this.closest('.milestone-card-row').remove()"
                                    title="Delete Milestone"
                                    class="p-1.5 rounded text-red-500 hover:bg-red-50 cursor-pointer">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic" id="empty-milestones-text">No milestones yet. Click "+ নতুন মাইলফলক যুক্ত করুন" to add.</p>
                @endforelse
            </div>
        </div>

        {{-- TAB 4: KEY STATISTICS --}}
        <div id="tab-stats" class="about-tab-pane hidden bg-white rounded-b-xl border border-t-0 border-slate-200 p-6 space-y-6 shadow-2xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">এক নজরে অর্জন ও পরিসংখ্যান (Key Statistics)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Showcase quantitative achievements (students, faculty count, pass rate, campus age).</p>
                </div>
                <button type="button"
                        onclick="addStatRow()"
                        class="inline-flex items-center gap-1.5 rounded-md bg-violet-50 text-violet-700 border border-violet-200 px-3 py-1.5 text-xs font-bold hover:bg-violet-100 cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ নতুন পরিসংখ্যান যুক্ত করুন</span>
                </button>
            </div>

            <div id="stats-repeater-list" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @php
                    $statistics = old('statistics', $about->statistics ?? []);
                @endphp
                @foreach($statistics as $sIndex => $sItem)
                    <div class="stat-card-row p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3 relative group">
                        <button type="button"
                                onclick="this.closest('.stat-card-row').remove()"
                                title="Remove Stat"
                                class="absolute top-3 right-3 text-red-400 hover:text-red-600 cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">সূচক নাম / লেবেল</label>
                                <input type="text"
                                       name="statistics[{{ $sIndex }}][label]"
                                       value="{{ $sItem['label'] ?? '' }}"
                                       placeholder="বর্তমান শিক্ষার্থী"
                                       class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-800">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">পরিমাণ / মান</label>
                                <input type="text"
                                       name="statistics[{{ $sIndex }}][value]"
                                       value="{{ $sItem['value'] ?? '' }}"
                                       placeholder="১২৫০+"
                                       class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-bold font-mono text-[#005030]">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">অতিরিক্ত প্রাসঙ্গিক টীকা (Note)</label>
                            <input type="text"
                                   name="statistics[{{ $sIndex }}][note]"
                                   value="{{ $sItem['note'] ?? '' }}"
                                   placeholder="নিয়মিত অধ্যয়নরত শিক্ষার্থী"
                                   class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-600">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- TAB 5: AT A GLANCE & COMMITTEE --}}
        <div id="tab-committee" class="about-tab-pane hidden bg-white rounded-b-xl border border-t-0 border-slate-200 p-6 space-y-8 shadow-2xs">

            {{-- 1. At a Glance Specifications --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">এক নজরে প্রতিষ্ঠান (Institutional Specifications Sheet)</h3>
                        <p class="text-xs text-slate-500">Key-value table for EIIN, MPO Code, School Code, Board, Shifts, etc.</p>
                    </div>
                    <button type="button"
                            onclick="addGlanceRow()"
                            class="inline-flex items-center gap-1.5 rounded text-xs font-bold text-violet-700 hover:text-violet-900 cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ নতুন তথ্য যোগ করুন</span>
                    </button>
                </div>

                <div id="glance-repeater-list" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @php
                        $atAGlance = old('at_a_glance', $about->at_a_glance ?? []);
                    @endphp
                    @foreach($atAGlance as $gIndex => $gItem)
                        <div class="glance-row flex items-center gap-2 p-2 rounded-lg border border-slate-200 bg-slate-50/60">
                            <input type="text"
                                   name="at_a_glance[{{ $gIndex }}][key]"
                                   value="{{ $gItem['key'] ?? '' }}"
                                   placeholder="ফিল্ড নাম (যেমন: EIIN)"
                                   class="w-2/5 rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-medium text-slate-800">
                            <input type="text"
                                   name="at_a_glance[{{ $gIndex }}][value]"
                                   value="{{ $gItem['value'] ?? '' }}"
                                   placeholder="মান (যেমন: ১২৫৬৯৩)"
                                   class="flex-1 rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-900">
                            <button type="button"
                                    onclick="this.closest('.glance-row').remove()"
                                    class="text-red-400 hover:text-red-600 p-1 cursor-pointer">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. Managing Committee Directory --}}
            <div class="space-y-4 pt-4 border-t border-slate-200">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">বিদ্যালয় পরিচালনা পর্ষদ (Managing Committee Directory)</h3>
                        <p class="text-xs text-slate-500">Board members, presidents, and faculty representatives.</p>
                    </div>
                    <button type="button"
                            onclick="addCommitteeRow()"
                            class="inline-flex items-center gap-1.5 rounded-md bg-violet-50 text-violet-700 border border-violet-200 px-3 py-1.5 text-xs font-bold hover:bg-violet-100 cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ নতুন সদস্য যুক্ত করুন</span>
                    </button>
                </div>

                <div id="committee-repeater-list" class="space-y-2.5">
                    @php
                        $committee = old('committee_members', $about->committee_members ?? []);
                    @endphp
                    @foreach($committee as $cIndex => $cItem)
                        <div class="committee-row flex flex-col sm:flex-row sm:items-center gap-2.5 p-3 rounded-lg border border-slate-200 bg-slate-50/70">
                            <input type="text"
                                   name="committee_members[{{ $cIndex }}][name]"
                                   value="{{ $cItem['name'] ?? '' }}"
                                   placeholder="সদস্যের পুরো নাম"
                                   class="sm:w-1/3 rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-bold text-[#005030]">
                            <input type="text"
                                   name="committee_members[{{ $cIndex }}][designation]"
                                   value="{{ $cItem['designation'] ?? '' }}"
                                   placeholder="পদবি (যেমন: সভাপতি / সদস্য সচিব)"
                                   class="sm:w-1/4 rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-800">
                            <input type="text"
                                   name="committee_members[{{ $cIndex }}][role]"
                                   value="{{ $cItem['role'] ?? '' }}"
                                   placeholder="মন্তব্য বা ভূমিকা (যেমন: বিশিষ্ট শিক্ষানুরাগী)"
                                   class="flex-1 rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-600">
                            <button type="button"
                                    onclick="this.closest('.committee-row').remove()"
                                    class="text-red-400 hover:text-red-600 p-1.5 cursor-pointer">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 3. Campus Facilities --}}
            <div class="space-y-4 pt-4 border-t border-slate-200">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">ক্যাম্পাস ও আধুনিক সুযোগ-সুবিধা (Facilities & Infrastructure)</h3>
                        <p class="text-xs text-slate-500">Computer lab, science laboratory, library, auditoriums, and playground.</p>
                    </div>
                    <button type="button"
                            onclick="addFacilityRow()"
                            class="inline-flex items-center gap-1.5 rounded text-xs font-bold text-violet-700 hover:text-violet-900 cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ নতুন সুবিধা যোগ করুন</span>
                    </button>
                </div>

                <div id="facilities-repeater-list" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @php
                        $facilities = old('facilities', $about->facilities ?? []);
                    @endphp
                    @foreach($facilities as $fIndex => $fItem)
                        <div class="facility-row p-3 rounded-lg border border-slate-200 bg-slate-50/70 space-y-2 relative">
                            <button type="button"
                                    onclick="this.closest('.facility-row').remove()"
                                    class="absolute top-2.5 right-2.5 text-red-400 hover:text-red-600 cursor-pointer">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <input type="text"
                                   name="facilities[{{ $fIndex }}][title]"
                                   value="{{ $fItem['title'] ?? '' }}"
                                   placeholder="সুবিধার নাম (যেমন: শেখ রাসেল ডিজিটাল ল্যাব)"
                                   class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-bold text-slate-800">
                            <textarea name="facilities[{{ $fIndex }}][description]"
                                      rows="2"
                                      placeholder="সুবিধার সংক্ষিপ্ত বিবরণ..."
                                      class="w-full rounded border border-slate-300 bg-white p-2 text-xs text-slate-600 leading-relaxed">{{ $fItem['description'] ?? '' }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- TAB 6: SEO & SOCIAL GRAPH --}}
        <div id="tab-seo" class="about-tab-pane hidden bg-white rounded-b-xl border border-t-0 border-slate-200 p-6 space-y-6 shadow-2xs">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">সার্চ ইঞ্জিন অপটিমাইজেশন (SEO) ও সোশ্যাল মেটাডাটা</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Control how this page appears on Google search results, Facebook, Twitter, and WhatsApp shares.</p>
                </div>
                <span class="inline-flex items-center gap-1 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 text-[11px] font-bold">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Live SERP Simulator</span>
                </span>
            </div>

            {{-- Live Google Search Snippet Preview Widget --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 space-y-2 shadow-2xs max-w-2xl">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Google Search Result Preview</span>
                <div class="flex items-center gap-2 text-xs text-slate-600">
                    <span class="h-4 w-4 rounded-full bg-emerald-700 text-white flex items-center justify-center text-[10px] font-bold">S</span>
                    <span class="text-slate-800 font-medium">sonatalahighschool.edu.bd</span>
                    <span class="text-slate-400">› about</span>
                </div>
                <h4 id="serp-title-preview" class="text-sm sm:text-base font-semibold text-blue-800 hover:underline cursor-pointer truncate">
                    {{ $about->meta_title ?: ($about->title.' | '.($school->name ?? 'সোনাতলা উচ্চ বিদ্যালয়')) }}
                </h4>
                <p id="serp-desc-preview" class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                    {{ $about->meta_description ?: 'সোনাতলা উচ্চ বিদ্যালয়ের গৌরবোজ্জ্বল ইতিহাস, প্রতিষ্ঠার পটভূমি, লক্ষ্য ও উদ্দেশ্য এবং প্রাতিষ্ঠানিক পরিচিতি।' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 pt-2">
                <div class="md:col-span-8 space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-bold text-slate-800">এসইও মেটা টাইটেল (SEO Meta Title)</label>
                        <span id="title-char-counter" class="text-[10px] font-mono text-slate-400">0 / 60 chars</span>
                    </div>
                    <input type="text"
                           id="input-meta-title"
                           name="meta_title"
                           value="{{ old('meta_title', $about->meta_title) }}"
                           placeholder="বিদ্যালয় পরিচিতি ও ইতিহাস | সোনাতলা উচ্চ বিদ্যালয়"
                           oninput="updateSerpPreview()"
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 font-medium">
                    <p class="text-[11px] text-slate-400">Recommended: 50-60 characters for optimal Google search rendering.</p>
                </div>

                <div class="md:col-span-4 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-800">ক্যানোনিকাল ইউআরএল (Canonical URL)</label>
                    <input type="text"
                           name="canonical_url"
                           value="{{ old('canonical_url', $about->canonical_url) }}"
                           placeholder="{{ url('/about') }}"
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 font-mono">
                </div>

                <div class="md:col-span-12 space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label class="block text-xs font-bold text-slate-800">মেটা বিবরণী (Meta Description)</label>
                        <span id="desc-char-counter" class="text-[10px] font-mono text-slate-400">0 / 160 chars</span>
                    </div>
                    <textarea id="input-meta-desc"
                              name="meta_description"
                              rows="3"
                              placeholder="সোনাতলা উচ্চ বিদ্যালয়, সাঁথিয়া, পাবনার ইতিহাস, লক্ষ্য, উদ্দেশ্য, অর্জন ও প্রাতিষ্ঠানিক পরিচিতি।"
                              oninput="updateSerpPreview()"
                              class="w-full rounded-lg border border-slate-300 bg-white p-3 text-xs text-slate-900 focus:border-violet-500 focus:ring-1 focus:ring-violet-500 leading-relaxed">{{ old('meta_description', $about->meta_description) }}</textarea>
                    <p class="text-[11px] text-slate-400">Recommended: 120-160 characters describing the school's background.</p>
                </div>

                <div class="md:col-span-12 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-800">মেটা কি-ওয়ার্ডস (Meta Keywords - Comma Separated)</label>
                    <input type="text"
                           name="meta_keywords"
                           value="{{ old('meta_keywords', $about->meta_keywords) }}"
                           placeholder="সোনাতলা উচ্চ বিদ্যালয়, Sonatala High School, ইতিহাস, সাঁথিয়া পাবনা, EIIN 125693"
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900">
                </div>

                {{-- OpenGraph Social Share Section --}}
                <div class="md:col-span-6 p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3">
                    <label class="block text-xs font-bold text-slate-800">সোশ্যাল শেয়ার টাইটেল (OpenGraph Title)</label>
                    <input type="text"
                           name="og_title"
                           value="{{ old('og_title', $about->og_title) }}"
                           placeholder="আমাদের গৌরবোজ্জ্বল ইতিহাস ও ঐতিহ্য"
                           class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900">

                    <label class="block text-xs font-bold text-slate-800 pt-1">সোশ্যাল বিবরণী (OG Description)</label>
                    <textarea name="og_description"
                              rows="2"
                              placeholder="ফেসবুক বা সোশ্যাল মিডিয়ায় প্রদর্শিত বিবরণ..."
                              class="w-full rounded-lg border border-slate-300 bg-white p-2.5 text-xs text-slate-900 leading-relaxed">{{ old('og_description', $about->og_description) }}</textarea>
                </div>

                <div class="md:col-span-6 p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3">
                    <label class="block text-xs font-bold text-slate-800">সোশ্যাল শেয়ার ইমেজ (OpenGraph Thumbnail Image)</label>
                    <div class="flex items-center gap-4">
                        <img src="{{ $about->og_image_url }}"
                             alt="OG Share Preview"
                             id="preview-og-img"
                             class="h-20 w-32 object-cover rounded-lg border border-slate-300 bg-white shrink-0">
                        <div class="space-y-1 text-xs">
                            <input type="file"
                                   name="og_image_file"
                                   accept="image/*"
                                   onchange="previewImage(this, 'preview-og-img')"
                                   class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 cursor-pointer">
                            <p class="text-[10px] text-slate-400">Optimal size: 1200x630px for Facebook/Twitter share cards.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Form Action Bar --}}
        <div class="flex items-center justify-between rounded-xl bg-white p-4 border border-slate-200 shadow-2xs">
            <span class="text-xs text-slate-500">Remember to click save after making updates in any of the tabs.</span>
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-5 py-2.5 text-xs font-semibold text-white hover:bg-violet-700 shadow-xs transition-colors cursor-pointer">
                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Save All Changes</span>
            </button>
        </div>

    </form>
</div>

{{-- Interactive Client Scripts for Tabs, Repeaters and Live SERP Simulator --}}
<script>
    function switchAboutTab(tabId) {
        document.querySelectorAll('.about-tab-pane').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.about-tab-btn').forEach(btn => {
            btn.classList.remove('border-violet-600', 'text-violet-700', 'font-bold');
            btn.classList.add('border-transparent', 'text-slate-500', 'font-medium');
        });

        const activePane = document.getElementById(tabId);
        if (activePane) activePane.classList.remove('hidden');

        const activeBtn = document.getElementById('btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-slate-500', 'font-medium');
            activeBtn.classList.add('border-violet-600', 'text-violet-700', 'font-bold');
        }
    }

    function previewImage(input, previewImgId, fallbackTextId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(previewImgId);
                if (img) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                }
                const fallback = document.getElementById(fallbackTextId);
                if (fallback) fallback.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateSerpPreview() {
        const titleInput = document.getElementById('input-meta-title');
        const descInput = document.getElementById('input-meta-desc');
        const titlePreview = document.getElementById('serp-title-preview');
        const descPreview = document.getElementById('serp-desc-preview');
        const titleCounter = document.getElementById('title-char-counter');
        const descCounter = document.getElementById('desc-char-counter');

        if (titleInput && titlePreview) {
            titlePreview.textContent = titleInput.value.trim() || '{{ $about->title }}';
            if (titleCounter) titleCounter.textContent = titleInput.value.length + ' / 60 chars';
        }
        if (descInput && descPreview) {
            descPreview.textContent = descInput.value.trim() || 'সোনাতলা উচ্চ বিদ্যালয়ের সংক্ষিপ্ত পটভূমি বিবরণী।';
            if (descCounter) descCounter.textContent = descInput.value.length + ' / 160 chars';
        }
    }

    document.addEventListener('DOMContentLoaded', updateSerpPreview);

    // Repeater Row Generators
    function addValueRow() {
        const container = document.getElementById('values-repeater-container');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 value-item-row';
        div.innerHTML = `
            <span class="h-1.5 w-1.5 rounded-full bg-violet-600 shrink-0"></span>
            <input type="text" name="values_list[]" placeholder="মূল্যবোধ বা আদর্শ..." class="flex-1 rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-violet-500">
            <button type="button" onclick="this.closest('.value-item-row').remove()" class="text-red-400 hover:text-red-600 p-1 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        `;
        container.appendChild(div);
    }

    function addMilestoneRow() {
        const list = document.getElementById('milestones-repeater-list');
        const index = list.querySelectorAll('.milestone-card-row').length + Math.floor(Math.random() * 1000);
        const div = document.createElement('div');
        div.className = 'milestone-card-row p-4 rounded-lg border border-slate-200 bg-slate-50/50 flex flex-col md:flex-row md:items-center gap-3';
        div.innerHTML = `
            <div class="w-full md:w-32 shrink-0 space-y-1">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">সাল / বছর</label>
                <input type="text" name="milestones[${index}][year]" placeholder="২০২৪" class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-mono font-bold text-slate-800">
            </div>
            <div class="w-full md:w-64 shrink-0 space-y-1">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">শিরোনাম</label>
                <input type="text" name="milestones[${index}][title]" placeholder="মাইলফলক শিরোনাম" class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-800">
            </div>
            <div class="flex-1 space-y-1">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">সংক্ষিপ্ত বিবরণ</label>
                <input type="text" name="milestones[${index}][description]" placeholder="ঐতিহাসিক বিবরণ..." class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700">
            </div>
            <div class="pt-4 md:pt-4">
                <button type="button" onclick="this.closest('.milestone-card-row').remove()" class="p-1.5 rounded text-red-500 hover:bg-red-50 cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        `;
        list.appendChild(div);
        const emptyMsg = document.getElementById('empty-milestones-text');
        if (emptyMsg) emptyMsg.remove();
    }

    function addStatRow() {
        const list = document.getElementById('stats-repeater-list');
        const index = list.querySelectorAll('.stat-card-row').length + Math.floor(Math.random() * 1000);
        const div = document.createElement('div');
        div.className = 'stat-card-row p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3 relative group';
        div.innerHTML = `
            <button type="button" onclick="this.closest('.stat-card-row').remove()" class="absolute top-3 right-3 text-red-400 hover:text-red-600 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">সূচক নাম / লেবেল</label>
                    <input type="text" name="statistics[${index}][label]" placeholder="সূচক নাম" class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-800">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">পরিমাণ / মান</label>
                    <input type="text" name="statistics[${index}][value]" placeholder="যেমন: ১০০%" class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-bold font-mono text-[#005030]">
                </div>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">অতিরিক্ত প্রাসঙ্গিক টীকা (Note)</label>
                <input type="text" name="statistics[${index}][note]" placeholder="বিবরণী টীকা..." class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-600">
            </div>
        `;
        list.appendChild(div);
    }

    function addGlanceRow() {
        const list = document.getElementById('glance-repeater-list');
        const index = list.querySelectorAll('.glance-row').length + Math.floor(Math.random() * 1000);
        const div = document.createElement('div');
        div.className = 'glance-row flex items-center gap-2 p-2 rounded-lg border border-slate-200 bg-slate-50/60';
        div.innerHTML = `
            <input type="text" name="at_a_glance[${index}][key]" placeholder="ফিল্ড নাম" class="w-2/5 rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-medium text-slate-800">
            <input type="text" name="at_a_glance[${index}][value]" placeholder="মান" class="flex-1 rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-900">
            <button type="button" onclick="this.closest('.glance-row').remove()" class="text-red-400 hover:text-red-600 p-1 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        `;
        list.appendChild(div);
    }

    function addCommitteeRow() {
        const list = document.getElementById('committee-repeater-list');
        const index = list.querySelectorAll('.committee-row').length + Math.floor(Math.random() * 1000);
        const div = document.createElement('div');
        div.className = 'committee-row flex flex-col sm:flex-row sm:items-center gap-2.5 p-3 rounded-lg border border-slate-200 bg-slate-50/70';
        div.innerHTML = `
            <input type="text" name="committee_members[${index}][name]" placeholder="সদস্যের পুরো নাম" class="sm:w-1/3 rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-bold text-[#005030]">
            <input type="text" name="committee_members[${index}][designation]" placeholder="পদবি" class="sm:w-1/4 rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-800">
            <input type="text" name="committee_members[${index}][role]" placeholder="ভূমিকা / মন্তব্য" class="flex-1 rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-600">
            <button type="button" onclick="this.closest('.committee-row').remove()" class="text-red-400 hover:text-red-600 p-1.5 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        `;
        list.appendChild(div);
    }

    function addFacilityRow() {
        const list = document.getElementById('facilities-repeater-list');
        const index = list.querySelectorAll('.facility-row').length + Math.floor(Math.random() * 1000);
        const div = document.createElement('div');
        div.className = 'facility-row p-3 rounded-lg border border-slate-200 bg-slate-50/70 space-y-2 relative';
        div.innerHTML = `
            <button type="button" onclick="this.closest('.facility-row').remove()" class="absolute top-2.5 right-2.5 text-red-400 hover:text-red-600 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <input type="text" name="facilities[${index}][title]" placeholder="সুবিধার নাম" class="w-full rounded border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-bold text-slate-800">
            <textarea name="facilities[${index}][description]" rows="2" placeholder="সংক্ষিপ্ত বিবরণ..." class="w-full rounded border border-slate-300 bg-white p-2 text-xs text-slate-600 leading-relaxed"></textarea>
        `;
        list.appendChild(div);
    }
</script>
@endsection
