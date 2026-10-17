@extends('layouts.admin')

@section('title', 'Invoice Details: ' . $invoice->invoice_number . ' | Administration')

@section('admin-content')
<div class="space-y-5 max-w-5xl mx-auto">

    <!-- Top Navigation & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.fees.invoices.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Invoices</a>
                <span>/</span>
                <span class="font-semibold font-mono text-slate-700">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold font-mono tracking-tight text-slate-900">{{ $invoice->invoice_number }}</h1>
                @include('admin.fees.partials.status-badge', ['status' => $invoice->status])
            </div>
            <p class="text-xs text-slate-500 mt-0.5">{{ $invoice->title }}</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs cursor-pointer">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Print Invoice</span>
            </button>

            @if($invoice->due_amount > 0 && $invoice->status !== 'void')
                <button type="button"
                        onclick="window.openWaiverModal()"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs cursor-pointer">
                    <svg class="h-4 w-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Apply Waiver</span>
                </button>

                <a href="{{ route('admin.fees.payments.collect', ['invoice_id' => $invoice->id]) }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-emerald-700 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Collect Payment</span>
                </a>
            @endif

            @if($invoice->paid_amount == 0 && $invoice->status !== 'void')
                <form method="POST" action="{{ route('admin.fees.invoices.void', $invoice) }}" class="inline" onsubmit="return confirm('Void/cancel this entire invoice? This action cannot be undone.');">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition cursor-pointer">
                        Void Invoice
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Student & Institution Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Student Card -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs space-y-2 md:col-span-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Student Profile & Billing Info</span>
            <div class="flex items-start justify-between pt-1">
                <div>
                    <h2 class="text-base font-bold text-slate-900">
                        {{ $invoice->student?->first_name }} {{ $invoice->student?->last_name }}
                    </h2>
                    <div class="text-xs font-mono text-slate-500 mt-0.5">
                        Admission Number: <span class="font-bold text-slate-700">{{ $invoice->student?->admission_number }}</span>
                    </div>
                    <div class="text-xs text-slate-600 mt-1">
                        Class: <span class="font-semibold text-slate-800">{{ $invoice->academicClass?->name }}</span>
                        @if($invoice->section)
                            • Section: <span class="font-semibold text-slate-800">{{ $invoice->section->name }}</span>
                        @endif
                        • Session: <span class="text-slate-600">{{ $invoice->academicYear?->name }}</span>
                    </div>
                </div>

                @if($invoice->student?->primaryGuardian)
                    <div class="text-right text-xs">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Guardian Details</span>
                        <span class="font-semibold text-slate-800">{{ $invoice->student->primaryGuardian->name }}</span>
                        <span class="block text-slate-500 font-mono">{{ $invoice->student->primaryGuardian->phone ?? '—' }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Schedule Card -->
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs space-y-2">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Schedule Details</span>
            <div class="space-y-1.5 text-xs pt-1">
                <div class="flex justify-between">
                    <span class="text-slate-500">Issue Date:</span>
                    <span class="font-medium text-slate-800">{{ $invoice->issue_date->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Due Date:</span>
                    <span class="font-bold {{ $invoice->isOverdue() && $invoice->due_amount > 0 ? 'text-rose-600' : 'text-slate-800' }}">
                        {{ $invoice->due_date->format('M d, Y') }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Issued By:</span>
                    <span class="text-slate-700">{{ $invoice->creator?->name ?? 'System' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Balances Ledger Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-2xs text-center">
        <div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Subtotal Amount</span>
            <span class="block text-lg font-bold font-mono text-slate-900 mt-1">{{ number_format($invoice->subtotal, 2) }}</span>
        </div>
        <div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Discount / Waiver</span>
            <span class="block text-lg font-bold font-mono text-violet-700 mt-1">
                {{ $invoice->discount_amount > 0 ? '-'.number_format($invoice->discount_amount, 2) : '0.00' }}
            </span>
        </div>
        <div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Amount Paid</span>
            <span class="block text-lg font-bold font-mono text-emerald-700 mt-1">{{ number_format($invoice->paid_amount, 2) }}</span>
        </div>
        <div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Outstanding Balance</span>
            <span class="block text-lg font-bold font-mono {{ $invoice->due_amount > 0 ? 'text-amber-700' : 'text-slate-400' }} mt-1">
                {{ number_format($invoice->due_amount, 2) }}
            </span>
        </div>
    </div>

    <!-- Itemized Fee Line Items -->
    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden shadow-2xs">
        <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/75 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Fee Items Breakdown</h3>
            <span class="text-xs text-slate-500">{{ $invoice->items->count() }} line {{ Str::plural('item', $invoice->items->count()) }}</span>
        </div>

        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/50 text-slate-600 font-semibold uppercase tracking-wider">
                    <th class="px-4 py-2.5 w-10 text-center">#</th>
                    <th class="px-4 py-2.5">Category</th>
                    <th class="px-4 py-2.5">Description</th>
                    <th class="px-4 py-2.5 text-right">Standard Fee</th>
                    <th class="px-4 py-2.5 text-right">Discount</th>
                    <th class="px-4 py-2.5 text-right">Paid</th>
                    <th class="px-4 py-2.5 text-right">Due Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @foreach($invoice->items as $index => $item)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-4 py-2.5 text-center text-slate-400 font-mono">{{ $index + 1 }}</td>
                        <td class="px-4 py-2.5 font-semibold text-slate-800">
                            {{ $item->feeCategory?->name ?? 'General' }}
                        </td>
                        <td class="px-4 py-2.5 text-slate-700">
                            {{ $item->name }}
                        </td>
                        <td class="px-4 py-2.5 text-right font-mono font-medium text-slate-900">
                            {{ number_format($item->amount, 2) }}
                        </td>
                        <td class="px-4 py-2.5 text-right font-mono text-slate-500">
                            {{ $item->discount_amount > 0 ? '-'.number_format($item->discount_amount, 2) : '—' }}
                        </td>
                        <td class="px-4 py-2.5 text-right font-mono text-emerald-700">
                            {{ number_format($item->paid_amount, 2) }}
                        </td>
                        <td class="px-4 py-2.5 text-right font-mono font-bold {{ $item->due_amount > 0 ? 'text-amber-700' : 'text-slate-400' }}">
                            {{ number_format($item->due_amount, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Payment History Table -->
    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden shadow-2xs">
        <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/75 flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Payment Transactions History</h3>
            <span class="text-xs text-slate-500">{{ $invoice->payments->count() }} {{ Str::plural('record', $invoice->payments->count()) }}</span>
        </div>

        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/50 text-slate-600 font-semibold uppercase tracking-wider">
                    <th class="px-4 py-2.5">Receipt #</th>
                    <th class="px-4 py-2.5">Date</th>
                    <th class="px-4 py-2.5">Method</th>
                    <th class="px-4 py-2.5">Reference</th>
                    <th class="px-4 py-2.5">Cashier</th>
                    <th class="px-4 py-2.5 text-right">Amount</th>
                    <th class="px-4 py-2.5 text-center">Status</th>
                    <th class="px-4 py-2.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($invoice->payments as $payment)
                    <tr class="hover:bg-slate-50/50 {{ $payment->isReversed() ? 'bg-slate-50/60 opacity-60' : '' }}">
                        <td class="px-4 py-2.5 font-mono font-bold text-slate-900">
                            <a href="{{ route('admin.fees.receipts.show', $payment) }}" class="text-violet-600 hover:underline">
                                {{ $payment->receipt_number }}
                            </a>
                        </td>
                        <td class="px-4 py-2.5 text-slate-600">
                            {{ $payment->payment_date->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-2.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 font-mono text-[11px] text-slate-500">
                            {{ $payment->transaction_reference ?? ($payment->cheque_number ? 'Chq: '.$payment->cheque_number : '—') }}
                        </td>
                        <td class="px-4 py-2.5 text-slate-600">
                            {{ $payment->receiver?->name ?? 'Cashier' }}
                        </td>
                        <td class="px-4 py-2.5 text-right font-mono font-bold {{ $payment->isReversed() ? 'text-slate-400 line-through' : 'text-emerald-700' }}">
                            {{ number_format($payment->amount, 2) }}
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            @if($payment->isCompleted())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Settled
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200" title="{{ $payment->reversal_reason }}">
                                    Reversed
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 text-right space-x-1 whitespace-nowrap">
                            <a href="{{ route('admin.fees.receipts.show', $payment) }}"
                               class="inline-flex items-center gap-1 px-2 py-1 rounded text-[11px] font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                                Receipt
                            </a>

                            @if($payment->isCompleted())
                                <button type="button"
                                        onclick="window.openReversalModal({{ $payment->id }}, '{{ $payment->receipt_number }}', '{{ number_format($payment->amount, 2) }}')"
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded text-[11px] font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 transition cursor-pointer">
                                    Reverse
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-slate-400 italic">
                            No payment transactions recorded for this invoice yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Discounts and Waivers Table if any -->
    @if($invoice->discounts->isNotEmpty())
        <div class="rounded-xl border border-slate-200 bg-white overflow-hidden shadow-2xs">
            <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/75">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Applied Institutional Waivers</h3>
            </div>
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/50 text-slate-600 font-semibold uppercase tracking-wider">
                        <th class="px-4 py-2.5">Waiver Name</th>
                        <th class="px-4 py-2.5">Justification</th>
                        <th class="px-4 py-2.5">Authorized By</th>
                        <th class="px-4 py-2.5 text-right">Deduction Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($invoice->discounts as $discount)
                        <tr>
                            <td class="px-4 py-2.5 font-semibold text-slate-900">{{ $discount->name }}</td>
                            <td class="px-4 py-2.5 text-slate-600">{{ $discount->reason }}</td>
                            <td class="px-4 py-2.5 text-slate-600">{{ $discount->authorizer?->name ?? 'Administrator' }}</td>
                            <td class="px-4 py-2.5 text-right font-mono font-bold text-violet-700">-{{ number_format($discount->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>

<!-- Apply Waiver Modal -->
<div id="waiver-modal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50/75 px-5 py-3.5">
            <h3 class="text-sm font-bold text-slate-900">Authorize Fee Waiver / Discount</h3>
            <button type="button" onclick="window.closeWaiverModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.fees.invoices.waiver', $invoice) }}" class="p-5 space-y-4">
            @csrf
            <div>
                <label for="waiver_name" class="block text-xs font-semibold text-slate-700 mb-1">Waiver Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="waiver_name" required value="Special Principal Waiver" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
            </div>

            <div>
                <label for="waiver_amount" class="block text-xs font-semibold text-slate-700 mb-1">
                    Discount Amount (Max: {{ number_format($invoice->due_amount, 2) }}) <span class="text-rose-500">*</span>
                </label>
                <input type="number" step="0.01" min="0.01" max="{{ $invoice->due_amount }}" name="amount" id="waiver_amount" required value="{{ min(50.00, $invoice->due_amount) }}" class="w-full font-mono font-bold rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden">
            </div>

            <div>
                <label for="waiver_reason" class="block text-xs font-semibold text-slate-700 mb-1">Authorization Justification <span class="text-rose-500">*</span></label>
                <textarea name="reason" id="waiver_reason" required rows="3" placeholder="Reason for scholarship, merit waiver, or sibling aid..." class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="window.closeWaiverModal()" class="rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white hover:bg-violet-700 transition cursor-pointer shadow-2xs">
                    Apply Waiver
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reverse Payment Modal -->
<div id="reversal-modal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between border-b border-rose-100 bg-rose-50/75 px-5 py-3.5">
            <h3 class="text-sm font-bold text-rose-900">Reverse Financial Payment</h3>
            <button type="button" onclick="window.closeReversalModal()" class="text-rose-400 hover:text-rose-600 cursor-pointer">&times;</button>
        </div>

        <form id="reversal-form" method="POST" action="" class="p-5 space-y-4">
            @csrf
            <div class="rounded-lg bg-rose-50 border border-rose-200 p-3 text-xs text-rose-800">
                <p class="font-bold">Caution: Financial Reversal</p>
                <p class="mt-1">
                    Reversing receipt <span id="rev-receipt-num" class="font-mono font-bold"></span> of <span id="rev-amount" class="font-mono font-bold"></span> will restore the student's due balance. The payment entry will be permanently audited and marked as reversed.
                </p>
            </div>

            <div>
                <label for="reversal_reason" class="block text-xs font-semibold text-slate-700 mb-1">Mandatory Audit Reason <span class="text-rose-500">*</span></label>
                <textarea name="reversal_reason" id="reversal_reason" required rows="3" placeholder="Explain why this payment is reversed (e.g. Bounced cheque, Cashier duplicate typo)..." class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-rose-600 focus:outline-hidden"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="window.closeReversalModal()" class="rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="rounded-lg bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-700 transition cursor-pointer shadow-2xs">
                    Confirm Reversal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    window.openWaiverModal = function() {
        document.getElementById('waiver-modal').classList.remove('hidden');
    };
    window.closeWaiverModal = function() {
        document.getElementById('waiver-modal').classList.add('hidden');
    };

    window.openReversalModal = function(paymentId, receiptNum, amount) {
        var modal = document.getElementById('reversal-modal');
        var form = document.getElementById('reversal-form');
        form.action = "{{ url('admin/fees/payments') }}/" + paymentId + "/reverse";

        document.getElementById('rev-receipt-num').innerText = receiptNum;
        document.getElementById('rev-amount').innerText = amount;

        modal.classList.remove('hidden');
    };
    window.closeReversalModal = function() {
        document.getElementById('reversal-modal').classList.add('hidden');
    };
</script>
@endsection
