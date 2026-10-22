{{-- Metrics --}}
<section class="bg-white border-y border-slate-200/90 py-8 shadow-2xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            {{-- Students --}}
            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50/70 border border-slate-200/70">
                <div class="w-12 h-12 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ number_format($stats['total_students']) }}+
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-slate-600">
                        মোট শিক্ষার্থী (Students)
                    </div>
                </div>
            </div>

            {{-- Faculty --}}
            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50/70 border border-slate-200/70">
                <div class="w-12 h-12 rounded-lg bg-indigo-100 text-indigo-800 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $stats['total_teachers'] }}+
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-slate-600">
                        দক্ষ শিক্ষক ও স্টাফ (Faculty)
                    </div>
                </div>
            </div>

            {{-- Classrooms / Sections --}}
            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50/70 border border-slate-200/70">
                <div class="w-12 h-12 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $stats['total_classes'] }}টি
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-slate-600">
                        শ্রেণিকক্ষ ও শাখা (Classes)
                    </div>
                </div>
            </div>

            {{-- Success Rate / Established --}}
            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50/70 border border-slate-200/70">
                <div class="w-12 h-12 rounded-lg bg-rose-100 text-rose-800 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ $stats['passing_rate'] }}
                    </div>
                    <div class="text-xs sm:text-sm font-semibold text-slate-600">
                        পাবলিক পরীক্ষায় সাফল্য
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

