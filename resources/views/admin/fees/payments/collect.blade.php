@extends('layouts.admin')

@section('title', 'Collect Fee Payment | Administration')

@section('admin-content')
<div class="max-w-2xl mx-auto space-y-5">

    <!-- Page Header & Breadcrumbs -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.fees.invoices.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Invoices</a>
                <span>/</span>
                <a href="{{ route('admin.fees.invoices.show', $invoice) }}" class="font-medium font-mono text-slate-600 hover:text-violet-600 transition">{{ $invoice->invoice_number }}</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">Collect</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Offline Payment Collection</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Record cash, bank transfer, cheque, or POS card tender against student dues.
            </p>
        </div>

        <a href="{{ route('admin.fees.invoices.show', $invoice) }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
            &larr; Back to Invoice
        </a>
    </div>

    <!-- Outstanding Balance & Student Card -->
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs space-y-3">
        <div class="flex items-start justify-between pb-3 border-b border-slate-100">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Student Obligation</span>
                <h2 class="text-base font-bold text-slate-900 mt-0.5">
                    {{ $invoice->student?->first_name }} {{ $invoice->student?->last_name }}
                </h2>
                <div class="text-xs text-slate-500 font-mono mt-0.5">
                    Admission No: {{ $invoice->student?->admission_number }} • Class: {{ $invoice->academicClass?->name }}
                </div>
            </div>

            <div class="text-right">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Invoice Number</span>
                <div class="font-mono font-bold text-violet-700 text-sm mt-0.5">{{ $invoice->invoice_number }}</div>
                <div class="text-[11px] text-slate-400">Due: {{ $invoice->due_date->format('M d, Y') }}</div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 text-center pt-1">
            <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                <span class="text-[10px] text-slate-500 block uppercase font-medium">Billed Subtotal</span>
                <span class="font-mono font-bold text-slate-900 text-sm">{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                <span class="text-[10px] text-slate-500 block uppercase font-medium">Already Paid</span>
                <span class="font-mono font-bold text-emerald-700 text-sm">{{ number_format($invoice->paid_amount, 2) }}</span>
            </div>
            <div class="p-2 rounded-lg bg-amber-50/75 border border-amber-200">
                <span class="text-[10px] text-amber-700 block uppercase font-bold">Outstanding Due</span>
                <span class="font-mono font-bold text-amber-900 text-base" id="due-balance-amount">{{ number_format($invoice->due_amount, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Tender Form -->
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-2xs">
        <form id="payment-collect-form" method="POST" action="{{ route('admin.fees.payments.store') }}" class="space-y-4" onsubmit="window.handleSubmitPayment(event)">
            @csrf
            <input type="hidden" name="fee_invoice_id" value="{{ $invoice->id }}">
            <input type="hidden" name="idempotency_token" value="{{ $idempotencyToken }}">

            <!-- Amount Input with Quick Full Pay Button -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="payment_amount" class="block text-xs font-semibold text-slate-700">
                        Payment Amount Received <span class="text-rose-500">*</span>
                    </label>
                    <button type="button"
                            onclick="document.getElementById('payment_amount').value = '{{ $invoice->due_amount }}'"
                            class="text-xs font-semibold text-violet-600 hover:text-violet-700 cursor-pointer">
                        Pay Full Balance ({{ number_format($invoice->due_amount, 2) }})
                    </button>
                </div>
                <div class="relative">
                    <input type="number"
                           step="0.01"
                           min="0.01"
                           max="{{ $invoice->due_amount }}"
                           name="amount"
                           id="payment_amount"
                           required
                           value="{{ old('amount', $invoice->due_amount) }}"
                           placeholder="0.00"
                           class="w-full rounded-lg border border-slate-200 font-mono font-bold text-lg px-3.5 py-2.5 text-slate-900 placeholder:text-slate-400 focus:border-emerald-600 focus:outline-hidden transition">
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Accepts partial payments up to the current outstanding due balance.</p>
            </div>

            <!-- Date and Method -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="payment_date" class="block text-xs font-semibold text-slate-700 mb-1">Payment Date <span class="text-rose-500">*</span></label>
                    <input type="date"
                           name="payment_date"
                           id="payment_date"
                           required
                           value="{{ old('payment_date', date('Y-m-d')) }}"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                </div>

                <div>
                    <label for="payment_method" class="block text-xs font-semibold text-slate-700 mb-1">Payment Tender Mode <span class="text-rose-500">*</span></label>
                    <select name="payment_method" id="payment_method" onchange="window.toggleMethodFields(this.value)" required class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                        <option value="cash" selected>Cash Payment</option>
                        <option value="bank_transfer">Bank Wire / Deposit Slip</option>
                        <option value="cheque">Cheque</option>
                        <option value="pos_card">Card / POS Terminal</option>
                        <option value="other">Other Offline Mode</option>
                    </select>
                </div>
            </div>

            <!-- Conditional Reference Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="method-references-container">
                <div id="field-bank-name" class="hidden">
                    <label for="bank_name" class="block text-xs font-semibold text-slate-700 mb-1">Bank Name</label>
                    <input type="text"
                           name="bank_name"
                           id="bank_name"
                           placeholder="e.g. Standard Chartered"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                </div>

                <div id="field-cheque-num" class="hidden">
                    <label for="cheque_number" class="block text-xs font-semibold text-slate-700 mb-1">Cheque / Slip Number</label>
                    <input type="text"
                           name="cheque_number"
                           id="cheque_number"
                           placeholder="Cheque # / Voucher #"
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                </div>

                <div class="sm:col-span-2">
                    <label for="transaction_reference" class="block text-xs font-semibold text-slate-700 mb-1">Transaction Reference / Memo</label>
                    <input type="text"
                           name="transaction_reference"
                           id="transaction_reference"
                           placeholder="Optional receipt reference or branch notes..."
                           class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition">
                </div>
            </div>

            <div>
                <label for="notes" class="block text-xs font-semibold text-slate-700 mb-1">Cashier Notes</label>
                <textarea name="notes"
                          id="notes"
                          rows="2"
                          placeholder="Optional remarks recorded on voucher..."
                          class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-800 focus:border-violet-600 focus:outline-hidden transition"></textarea>
            </div>

            <!-- Submit Buttons with Double-Click Protection -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.fees.invoices.show', $invoice) }}"
                   class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit"
                        id="btn-submit-payment"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-xs font-semibold text-white hover:bg-emerald-700 transition shadow-2xs cursor-pointer">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span id="btn-submit-text">Confirm Payment & Issue Receipt</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    window.toggleMethodFields = function(method) {
        var bankField = document.getElementById('field-bank-name');
        var chequeField = document.getElementById('field-cheque-num');

        if (method === 'cheque' || method === 'bank_transfer') {
            bankField.classList.remove('hidden');
            chequeField.classList.remove('hidden');
        } else if (method === 'pos_card') {
            bankField.classList.remove('hidden');
            chequeField.classList.add('hidden');
        } else {
            bankField.classList.add('hidden');
            chequeField.classList.add('hidden');
        }
    };

    window.handleSubmitPayment = function(e) {
        var btn = document.getElementById('btn-submit-payment');
        var btnText = document.getElementById('btn-submit-text');

        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        btnText.innerText = "Processing Payment...";
    };
</script>
@endsection
