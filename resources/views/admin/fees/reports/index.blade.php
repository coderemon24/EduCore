@extends('layouts.admin')

@section('title', 'Financial Reports & Collection Register | Administration')

@section('admin-content')
<div class="space-y-5">

    <!-- Page Header & Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.fees.invoices.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Fees & Accounts</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Financial Reports</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Institutional Collection & Dues Reports</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Audit daily fee collection registers, breakdown tender methods, and track class-wise recovery rates.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <x-report-export-dropdown
                :action="route('admin.fees.reports.export')"
                :params="request()->query()"
                label="Export Report"
            />

            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Print Report</span>
            </button>
        </div>
    </div>

    <!-- Tab Switcher -->
    <div class="flex rounded-xl border border-slate-200 bg-white p-1 shadow-2xs max-w-sm">
        <a href="{{ route('admin.fees.reports.index', ['tab' => 'collections', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
           class="flex-1 text-center rounded-lg py-2 text-xs font-semibold transition {{ $activeTab === 'collections' ? 'bg-violet-600 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
            Collection Register
        </a>
        <a href="{{ route('admin.fees.reports.index', ['tab' => 'dues', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
           class="flex-1 text-center rounded-lg py-2 text-xs font-semibold transition {{ $activeTab === 'dues' ? 'bg-violet-600 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
            Outstanding by Class
        </a>
    </div>

    @if($activeTab === 'collections')
        <!-- Date-Range Filter Form -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
            <form method="GET" action="{{ route('admin.fees.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-end">
                <input type="hidden" name="tab" value="collections">

                <div>
                    <label for="rep_start_date" class="block text-xs font-semibold text-slate-700 mb-1">From Date</label>
                    <input type="date"
                           name="start_date"
                           id="rep_start_date"
                           value="{{ $startDate }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                </div>

                <div>
                    <label for="rep_end_date" class="block text-xs font-semibold text-slate-700 mb-1">To Date</label>
                    <input type="date"
                           name="end_date"
                           id="rep_end_date"
                           value="{{ $endDate }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                </div>

                <div>
                    <label for="rep_payment_method" class="block text-xs font-semibold text-slate-700 mb-1">Payment Method</label>
                    <select name="payment_method" id="rep_payment_method" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                        <option value="all">All Methods</option>
                        <option value="cash" {{ $methodFilter === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ $methodFilter === 'bank_transfer' ? 'selected' : '' }}>Bank Wire / Transfer</option>
                        <option value="cheque" {{ $methodFilter === 'cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="pos_card" {{ $methodFilter === 'pos_card' ? 'selected' : '' }}>POS Terminal / Card</option>
                        <option value="other" {{ $methodFilter === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="w-full rounded-lg bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-900 transition cursor-pointer">
                        Generate Register
                    </button>
                </div>
            </form>
        </div>

        <!-- Collection Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
                <span class="text-xs font-medium text-slate-500 block">Total Revenue Collected</span>
                <span class="text-2xl font-bold font-mono text-emerald-700 block mt-1">{{ number_format($totalCollected, 2) }}</span>
                <span class="text-xs text-slate-500 mt-1 block">{{ $totalTransactions }} settled {{ Str::plural('transaction', $totalTransactions) }}</span>
            </div>

            <!-- Cash Collections -->
            @php
                $cashData = $methodBreakdown['cash'] ?? null;
                $cashTotal = $cashData ? (float) $cashData->total_amount : 0.00;
            @endphp
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
                <span class="text-xs font-medium text-slate-500 block">Cash Tendered</span>
                <span class="text-2xl font-bold font-mono text-slate-900 block mt-1">{{ number_format($cashTotal, 2) }}</span>
                <span class="text-xs text-slate-500 mt-1 block">{{ $cashData?->count ?? 0 }} cash entries</span>
            </div>

            <!-- Bank Transfers & Cheques -->
            @php
                $bankTotal = (float) (($methodBreakdown['bank_transfer']?->total_amount ?? 0) + ($methodBreakdown['cheque']?->total_amount ?? 0));
                $bankCount = (int) (($methodBreakdown['bank_transfer']?->count ?? 0) + ($methodBreakdown['cheque']?->count ?? 0));
            @endphp
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
                <span class="text-xs font-medium text-slate-500 block">Bank Wire & Cheques</span>
                <span class="text-2xl font-bold font-mono text-blue-700 block mt-1">{{ number_format($bankTotal, 2) }}</span>
                <span class="text-xs text-slate-500 mt-1 block">{{ $bankCount }} deposits verified</span>
            </div>

            <!-- POS Card & Other -->
            @php
                $posTotal = (float) (($methodBreakdown['pos_card']?->total_amount ?? 0) + ($methodBreakdown['other']?->total_amount ?? 0));
                $posCount = (int) (($methodBreakdown['pos_card']?->count ?? 0) + ($methodBreakdown['other']?->count ?? 0));
            @endphp
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
                <span class="text-xs font-medium text-slate-500 block">POS Terminal / Card</span>
                <span class="text-2xl font-bold font-mono text-violet-700 block mt-1">{{ number_format($posTotal, 2) }}</span>
                <span class="text-xs text-slate-500 mt-1 block">{{ $posCount }} electronic receipts</span>
            </div>
        </div>

        <!-- Detailed Transactions Register -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/75 flex items-center justify-between">
                <h3 class="text-xs font-semibold text-slate-700">Daily Collection Log</h3>
                <span class="text-xs text-slate-500">Period: {{ \Carbon\Carbon::parse($startDate)->format('M d') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-700 font-semibold">
                            <th class="px-4 py-2.5">Receipt #</th>
                            <th class="px-4 py-2.5">Date</th>
                            <th class="px-4 py-2.5">Student</th>
                            <th class="px-4 py-2.5">Invoice #</th>
                            <th class="px-4 py-2.5">Method</th>
                            <th class="px-4 py-2.5">Reference / Memo</th>
                            <th class="px-4 py-2.5">Cashier</th>
                            <th class="px-4 py-2.5 text-right">Amount</th>
                            <th class="px-4 py-2.5 text-center">Status</th>
                            <th class="px-4 py-2.5 text-right">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($payments as $p)
                            <tr class="hover:bg-slate-50/50 {{ $p->isReversed() ? 'bg-slate-50/60 opacity-60' : '' }}">
                                <td class="px-4 py-2.5 font-mono font-bold text-slate-900">
                                    <a href="{{ route('admin.fees.receipts.show', $p) }}" class="text-violet-600 hover:underline">
                                        {{ $p->receipt_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-2.5 whitespace-nowrap">{{ $p->payment_date->format('M d, Y') }}</td>
                                <td class="px-4 py-2.5">
                                    <span class="font-semibold text-slate-900 block">{{ $p->student?->first_name }} {{ $p->student?->last_name }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $p->student?->admission_number }}</span>
                                </td>
                                <td class="px-4 py-2.5 font-mono">
                                    <a href="{{ route('admin.fees.invoices.show', $p->fee_invoice_id) }}" class="text-slate-700 hover:underline">
                                        {{ $p->invoice?->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                                        {{ ucfirst(str_replace('_', ' ', $p->payment_method)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 font-mono text-slate-500 max-w-[140px] truncate">
                                    {{ $p->transaction_reference ?? ($p->cheque_number ? 'Chq: '.$p->cheque_number : '—') }}
                                </td>
                                <td class="px-4 py-2.5 text-slate-600">{{ $p->receiver?->name ?? 'Admin' }}</td>
                                <td class="px-4 py-2.5 text-right font-mono font-bold {{ $p->isReversed() ? 'text-slate-400 line-through' : 'text-emerald-700' }}">
                                    {{ number_format($p->amount, 2) }}
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    @if($p->isCompleted())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Settled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            Reversed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <a href="{{ route('admin.fees.receipts.show', $p) }}"
                                       class="inline-flex items-center px-2 py-1 rounded text-[11px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-12 text-center text-slate-400">
                                    No collection records found within the selected date interval.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="border-t border-slate-200 px-4 py-3">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

    @else
        <!-- Outstanding Dues by Class Table -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/75 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-semibold text-slate-700">Class Dues & Recovery Summary</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Session: {{ $currentYear?->name ?? 'Active Session' }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-700 font-semibold">
                            <th class="px-4 py-3">Academic Grade Level</th>
                            <th class="px-4 py-3 text-right">Billed Invoices</th>
                            <th class="px-4 py-3 text-right">Waivers</th>
                            <th class="px-4 py-3 text-right">Net Billed</th>
                            <th class="px-4 py-3 text-right">Collected</th>
                            <th class="px-4 py-3 text-right">Outstanding Due</th>
                            <th class="px-4 py-3 text-center w-36">Recovery Progress</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($classDues as $row)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-3 font-bold text-slate-900">
                                    {{ $row['class']->name }}
                                </td>
                                <td class="px-4 py-3 text-right font-mono">{{ number_format($row['invoiced'], 2) }}</td>
                                <td class="px-4 py-3 text-right font-mono text-slate-500">
                                    {{ $row['discount'] > 0 ? '-'.number_format($row['discount'], 2) : '—' }}
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-semibold text-slate-800">{{ number_format($row['net_invoiced'], 2) }}</td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-emerald-700">{{ number_format($row['collected'], 2) }}</td>
                                <td class="px-4 py-3 text-right font-mono font-bold {{ $row['due'] > 0 ? 'text-amber-700' : 'text-slate-400' }}">
                                    {{ number_format($row['due'], 2) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-emerald-600 h-2 rounded-full transition-all duration-300" style="width: {{ min(100, $row['rate']) }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-600 mt-1 block">{{ $row['rate'] }}%</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                                    No academic classes found for current session.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
