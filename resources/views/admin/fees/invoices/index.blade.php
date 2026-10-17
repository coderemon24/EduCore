@extends('layouts.admin')

@section('title', 'Student Invoices & Dues Directory | Administration')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Action Buttons -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <span class="text-slate-500">Fees & Accounts</span>
                <span>/</span>
                <span class="font-semibold text-slate-700">Invoices & Dues</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Student Invoices & Dues Directory</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Monitor student fee billing, track outstanding balances, and record offline payments.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.fees.reports.index') }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Financial Reports</span>
            </a>

            <a href="{{ route('admin.fees.invoices.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3.5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Generate Invoices</span>
            </a>
        </div>
    </div>

    <!-- Institutional Financial Metrics Summary -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Invoiced -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Invoiced</span>
                <span class="rounded-lg bg-blue-50 p-2 text-blue-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-bold font-mono tracking-tight text-slate-900">{{ number_format($totalInvoiced, 2) }}</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-1">Gross fees billed across students</p>
        </div>

        <!-- Total Collected -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Collected</span>
                <span class="rounded-lg bg-emerald-50 p-2 text-emerald-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-bold font-mono tracking-tight text-emerald-700">{{ number_format($totalCollected, 2) }}</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-1">Confirmed offline collections received</p>
        </div>

        <!-- Outstanding Dues -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Outstanding Dues</span>
                <span class="rounded-lg bg-amber-50 p-2 text-amber-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-bold font-mono tracking-tight text-amber-700">{{ number_format($totalOutstanding, 2) }}</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-1">Pending student balances to collect</p>
        </div>

        <!-- Overdue Invoices -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Overdue Invoices</span>
                <span class="rounded-lg bg-rose-50 p-2 text-rose-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-bold font-mono tracking-tight text-rose-700">{{ $overdueCount }}</span>
                <span class="text-xs text-rose-600 font-medium">Action Needed</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-1">Invoices past assigned due date</p>
        </div>
    </div>

    <!-- Filter & Search Panel -->
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.fees.invoices.index') }}" class="space-y-3">
            <!-- Status Filter Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs">
                <span class="font-semibold text-slate-500 mr-1 shrink-0">Filter Status:</span>
                @php
                    $statuses = [
                        'all' => 'All Invoices',
                        'unpaid' => 'Unpaid',
                        'partially_paid' => 'Partially Paid',
                        'paid' => 'Fully Paid',
                        'overdue' => 'Overdue',
                        'void' => 'Cancelled / Void',
                    ];
                @endphp
                @foreach($statuses as $key => $label)
                    <button type="submit"
                            name="status"
                            value="{{ $key }}"
                            class="rounded-full px-3 py-1 font-semibold transition cursor-pointer shrink-0 {{ $statusFilter === $key ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Detailed Dropdown Filters -->
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-5 items-end pt-2 border-t border-slate-100">
                <div class="md:col-span-2">
                    <label for="filter_search" class="block text-xs font-semibold text-slate-700 mb-1">Search Directory</label>
                    <div class="relative">
                        <input type="text"
                               name="search"
                               id="filter_search"
                               value="{{ request('search') }}"
                               placeholder="Invoice number, student name, admission no..."
                               class="w-full rounded-lg border border-slate-200 bg-slate-50/50 pl-8 pr-3 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div>
                    <label for="filter_class" class="block text-xs font-semibold text-slate-700 mb-1">Grade Class</label>
                    <select name="academic_class_id" id="filter_class" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                        <option value="">All Classes</option>
                        @foreach($academicClasses as $class)
                            <option value="{{ $class->id }}" {{ request('academic_class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="filter_year" class="block text-xs font-semibold text-slate-700 mb-1">Academic Session</label>
                    <select name="academic_year_id" id="filter_year" class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:bg-white focus:outline-hidden transition">
                        <option value="">All Sessions</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="w-full rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-900 transition cursor-pointer">
                        Filter Results
                    </button>
                    @if(request()->hasAny(['search', 'academic_class_id', 'academic_year_id', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('admin.fees.invoices.index') }}"
                           class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Invoices Ledger Directory Table -->
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-slate-600 uppercase tracking-wider font-semibold">
                        <th class="px-4 py-3">Invoice #</th>
                        <th class="px-4 py-3">Student Details</th>
                        <th class="px-4 py-3">Class & Section</th>
                        <th class="px-4 py-3">Due Date</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                        <th class="px-4 py-3 text-right">Waiver</th>
                        <th class="px-4 py-3 text-right">Paid</th>
                        <th class="px-4 py-3 text-right">Balance Due</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3 font-mono font-bold text-slate-900">
                                <a href="{{ route('admin.fees.invoices.show', $invoice) }}" class="text-violet-600 hover:underline">
                                    {{ $invoice->invoice_number }}
                                </a>
                                <span class="block text-[11px] font-normal text-slate-400 font-sans truncate max-w-[140px]">{{ $invoice->title }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($invoice->student)
                                    <div class="font-semibold text-slate-900">
                                        {{ $invoice->student->first_name }} {{ $invoice->student->last_name }}
                                    </div>
                                    <div class="text-[11px] font-mono text-slate-400">
                                        Adm: {{ $invoice->student->admission_number }}
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Student record removed</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                <div>{{ $invoice->academicClass?->name ?? '—' }}</div>
                                @if($invoice->section)
                                    <div class="text-[11px] text-slate-400">Sec: {{ $invoice->section->name }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="{{ $invoice->isOverdue() && $invoice->due_amount > 0 ? 'text-rose-600 font-bold' : 'text-slate-600' }}">
                                    {{ $invoice->due_date->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-medium text-slate-800">
                                {{ number_format($invoice->subtotal, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-slate-500">
                                {{ $invoice->discount_amount > 0 ? '-'.number_format($invoice->discount_amount, 2) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-emerald-700 font-semibold">
                                {{ number_format($invoice->paid_amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-bold {{ $invoice->due_amount > 0 ? 'text-amber-700 text-sm' : 'text-slate-400' }}">
                                {{ number_format($invoice->due_amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @include('admin.fees.partials.status-badge', ['status' => $invoice->status])
                            </td>
                            <td class="px-4 py-3 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ route('admin.fees.invoices.show', $invoice) }}"
                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                    Details
                                </a>

                                @if($invoice->due_amount > 0 && $invoice->status !== 'void')
                                    <a href="{{ route('admin.fees.payments.collect', ['invoice_id' => $invoice->id]) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-2xs">
                                        Collect
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-slate-400">
                                <svg class="mx-auto h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm font-semibold text-slate-600">No invoices match selected criteria</p>
                                <p class="text-xs text-slate-400 mt-1">Generate invoices for an academic class or adjust your directory filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="border-t border-slate-200 px-4 py-3">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
