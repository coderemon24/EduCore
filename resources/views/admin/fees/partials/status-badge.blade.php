@props(['status'])

@php
    $status = strtolower((string) ($status ?? 'unpaid'));
@endphp

@switch($status)
    @case('paid')
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
            Paid
        </span>
        @break

    @case('partially_paid')
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
            <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
            Partially Paid
        </span>
        @break

    @case('overdue')
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
            Overdue
        </span>
        @break

    @case('reversed')
    @case('void')
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
            {{ ucfirst($status) }}
        </span>
        @break

    @default
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/80">
            <span class="h-1.5 w-1.5 rounded-full bg-sky-500"></span>
            Unpaid
        </span>
@endswitch
