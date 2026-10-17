@props([
    'action',
    'label' => 'Export',
    'params' => request()->query(),
])

@php
    $baseParams = is_array($params) ? $params : [];
    $pdfUrl = $action . '?' . http_build_query(array_merge($baseParams, ['format' => 'pdf']));
    $excelUrl = $action . '?' . http_build_query(array_merge($baseParams, ['format' => 'excel']));
    $csvUrl = $action . '?' . http_build_query(array_merge($baseParams, ['format' => 'csv']));
@endphp

<div x-data="{ open: false }" class="relative inline-block text-left" @click.outside="open = false">
    <!-- Trigger Button -->
    <button type="button"
            @click="open = !open"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 hover:text-slate-900 focus:outline-hidden transition cursor-pointer">
        <svg class="h-4 w-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        <span>{{ $label }}</span>
        <svg class="h-3.5 w-3.5 text-slate-400 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg focus:outline-hidden"
         style="display: none;">
        
        <div class="px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1">
            Choose Export Format
        </div>

        <!-- PDF Export Option -->
        <a href="{{ $pdfUrl }}"
           target="_blank"
           @click="open = false"
           class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-xs font-medium text-slate-700 hover:bg-rose-50 hover:text-rose-700 transition group cursor-pointer">
            <div class="flex h-7 w-7 items-center justify-center rounded-md bg-rose-100 text-rose-600 group-hover:bg-rose-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <div class="font-semibold">PDF Document</div>
                <div class="text-[10px] text-slate-400 group-hover:text-rose-500">Printable A4 document (.pdf)</div>
            </div>
        </a>

        <!-- Excel Export Option -->
        <a href="{{ $excelUrl }}"
           @click="open = false"
           class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-xs font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition group cursor-pointer">
            <div class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <div class="font-semibold">Excel Spreadsheet</div>
                <div class="text-[10px] text-slate-400 group-hover:text-emerald-500">Formatted workbook (.xls)</div>
            </div>
        </a>

        <!-- CSV Export Option -->
        <a href="{{ $csvUrl }}"
           @click="open = false"
           class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-xs font-medium text-slate-700 hover:bg-sky-50 hover:text-sky-700 transition group cursor-pointer">
            <div class="flex h-7 w-7 items-center justify-center rounded-md bg-sky-100 text-sky-600 group-hover:bg-sky-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                </svg>
            </div>
            <div>
                <div class="font-semibold">CSV Spreadsheet</div>
                <div class="text-[10px] text-slate-400 group-hover:text-sky-500">Comma-separated values (.csv)</div>
            </div>
        </a>

    </div>
</div>
