@props(['status'])

@php
    $status = strtolower((string) ($status ?? 'unpaid'));
@endphp

@switch($status)
    @case('paid')
        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
            Paid
        </span>
        @break

    @case('partially_paid')
        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
            Partially Paid
        </span>
        @break

    @case('overdue')
        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
            Overdue
        </span>
        @break

    @case('reversed')
    @case('void')
        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
            {{ ucfirst($status) }}
        </span>
        @break

    @default
        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-50 text-slate-700 border border-slate-200">
            Unpaid
        </span>
@endswitch
