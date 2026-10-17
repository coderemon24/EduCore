@extends('layouts.admin')

@section('title', 'Official Payment Receipt: ' . $payment->receipt_number . ' | Administration')

@section('admin-content')
<div class="max-w-3xl mx-auto space-y-5">

    <!-- Top Screen Action Bar (Hidden on Print) -->
    <div class="print:hidden flex items-center justify-between pb-3 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Admin</a>
                <span>/</span>
                <a href="{{ route('admin.fees.invoices.index') }}" class="font-medium text-slate-600 hover:text-violet-600 transition">Invoices</a>
                <span>/</span>
                <a href="{{ route('admin.fees.invoices.show', $payment->fee_invoice_id) }}" class="font-medium font-mono text-slate-600 hover:text-violet-600 transition">{{ $payment->invoice?->invoice_number }}</a>
                <span>/</span>
                <span class="font-semibold font-mono text-slate-700">Receipt</span>
            </div>
            <h1 class="text-xl font-bold font-mono text-slate-900">{{ $payment->receipt_number }}</h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.fees.invoices.show', $payment->fee_invoice_id) }}"
               class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
                &larr; View Invoice
            </a>

            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-violet-700 transition cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Print Official Receipt</span>
            </button>
        </div>
    </div>

    <!-- Official Printable Voucher Container -->
    <div id="printable-voucher" class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm print:border-none print:shadow-none print:p-0 space-y-6">

        @php
            $receiptSchool = $payment->school ?? $currentSchool ?? \App\Models\School::find($payment->school_id) ?? \App\Models\School::first();
        @endphp

        <!-- Header: Institution Brand & Title -->
        <div class="flex items-start justify-between border-b border-slate-200 pb-5">
            <div class="flex items-center gap-3">
                @if($receiptSchool && $receiptSchool->logo_url)
                    <img src="{{ $receiptSchool->logo_url }}" alt="School Logo" class="h-12 w-auto object-contain">
                @endif
                <div>
                    <h2 class="text-lg font-bold uppercase tracking-tight text-slate-900">
                        {{ $receiptSchool?->name ?? 'EduCore International School' }}
                    </h2>
                    <p class="text-xs text-slate-500">
                        {{ $receiptSchool?->address ?? 'Institutional Academic Campus' }}{{ !empty($receiptSchool?->city) ? ', '.$receiptSchool->city : '' }}
                    </p>
                    <p class="text-[11px] text-slate-400">
                        {{ $receiptSchool?->email ?? 'admin@educore.edu' }} • Phone: {{ $receiptSchool?->phone ?? '—' }}
                    </p>
                </div>
            </div>

            <div class="text-right">
                <span class="inline-block px-3 py-1 rounded bg-slate-900 text-white text-xs font-bold uppercase tracking-wider">
                    Official Fee Receipt
                </span>
                <div class="text-sm font-mono font-bold text-slate-800 mt-2">
                    {{ $payment->receipt_number }}
                </div>
                <div class="text-xs text-slate-500">
                    Date: {{ $payment->payment_date->format('F d, Y') }}
                </div>
            </div>
        </div>

        @if($payment->isReversed())
            <div class="rounded-lg border-2 border-dashed border-rose-400 bg-rose-50/75 p-3 text-center">
                <span class="text-sm font-black uppercase tracking-widest text-rose-700 block">VOID / PAYMENT REVERSED</span>
                <span class="text-xs text-rose-600 mt-0.5 block">Reversed on {{ $payment->reversed_at?->format('M d, Y H:i') }} • Reason: {{ $payment->reversal_reason }}</span>
            </div>
        @endif

        <!-- Student & Invoice Details Grid -->
        <div class="grid grid-cols-2 gap-4 rounded-lg bg-slate-50/75 p-4 border border-slate-100 text-xs">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Student Particulars</span>
                <div class="font-bold text-slate-900 text-sm">{{ $payment->student?->first_name }} {{ $payment->student?->last_name }}</div>
                <div class="font-mono text-slate-600 mt-0.5">Admission No: <span class="font-semibold text-slate-800">{{ $payment->student?->admission_number }}</span></div>
                <div class="text-slate-600 mt-0.5">
                    Class: <span class="font-semibold text-slate-800">{{ $payment->invoice?->academicClass?->name }}</span>
                    @if($payment->invoice?->section)
                        • Sec: {{ $payment->invoice->section->name }}
                    @endif
                </div>
            </div>

            <div class="text-right">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Payment Reference</span>
                <div class="text-slate-700">Invoice: <span class="font-mono font-bold text-violet-700">{{ $payment->invoice?->invoice_number }}</span></div>
                <div class="text-slate-700 mt-0.5">Method: <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span></div>
                @if($payment->transaction_reference || $payment->cheque_number)
                    <div class="text-[11px] font-mono text-slate-500 mt-0.5">
                        Ref: {{ $payment->transaction_reference ?? 'Chq #'.$payment->cheque_number }}
                    </div>
                @endif
                <div class="text-[11px] text-slate-500 mt-0.5">Tendered By Cashier: {{ $payment->receiver?->name ?? 'Admin' }}</div>
            </div>
        </div>

        <!-- Line Items Table -->
        <div class="overflow-hidden border border-slate-200 rounded-lg">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-100/75 text-slate-700 font-bold uppercase tracking-wider">
                        <th class="px-4 py-2.5 w-10 text-center">#</th>
                        <th class="px-4 py-2.5">Fee Category & Description</th>
                        <th class="px-4 py-2.5 text-right">Billed Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($payment->invoice?->items as $index => $item)
                        <tr>
                            <td class="px-4 py-2 text-center text-slate-400 font-mono">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">
                                <span class="font-semibold text-slate-900">{{ $item->name }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $item->feeCategory?->name }}</span>
                            </td>
                            <td class="px-4 py-2 text-right font-mono text-slate-900">
                                {{ number_format($item->amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals & Balances -->
        <div class="flex justify-end pt-2">
            <div class="w-64 space-y-1.5 text-xs">
                <div class="flex justify-between text-slate-500">
                    <span>Invoice Subtotal:</span>
                    <span class="font-mono">{{ number_format($payment->invoice?->subtotal, 2) }}</span>
                </div>
                @if($payment->invoice?->discount_amount > 0)
                    <div class="flex justify-between text-violet-700">
                        <span>Waiver / Discount:</span>
                        <span class="font-mono">-{{ number_format($payment->invoice->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between border-t border-slate-200 pt-1.5 font-bold text-slate-900 text-sm">
                    <span>Amount Tendered:</span>
                    <span class="font-mono text-emerald-700">{{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="flex justify-between border-t border-slate-100 pt-1 text-slate-600">
                    <span>Remaining Due on Invoice:</span>
                    <span class="font-mono font-bold {{ $payment->invoice?->due_amount > 0 ? 'text-amber-700' : 'text-slate-400' }}">
                        {{ number_format($payment->invoice?->due_amount, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Cashier Notes if any -->
        @if($payment->notes)
            <div class="rounded-lg bg-slate-50 p-3 text-xs text-slate-600 border border-slate-100">
                <span class="font-bold text-slate-700 block mb-0.5">Remarks / Notes:</span>
                {{ $payment->notes }}
            </div>
        @endif

        <!-- Signatures & Stamp Footer -->
        <div class="pt-12 grid grid-cols-2 gap-8 text-xs text-slate-500">
            <div class="text-left border-t border-slate-300 pt-2">
                <span class="block font-semibold text-slate-700">Guardian / Payer Signature</span>
                <span class="text-[10px] text-slate-400">Received with thanks</span>
            </div>
            <div class="text-right border-t border-slate-300 pt-2">
                <span class="block font-semibold text-slate-700">Authorized Officer / Cashier</span>
                <span class="text-[10px] text-slate-400">Institutional Accounts Seal</span>
            </div>
        </div>

    </div>

</div>
@endsection
