<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $payment->receipt_number }}</title>
    <style>
        @page {
            margin: 12mm 15mm 15mm 15mm;
            size: a4 portrait;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }
        th, td {
            word-wrap: break-word;
            overflow: hidden;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .school-info {
            font-size: 10px;
            color: #64748b;
            margin-top: 3px;
            line-height: 1.35;
        }
        .receipt-badge {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 4px;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .receipt-title {
            font-size: 15px;
            font-weight: bold;
            font-family: monospace;
            color: #0f172a;
            margin-top: 6px;
        }
        .meta-box {
            width: 100%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 16px;
        }
        .meta-box td {
            padding: 10px 14px;
            vertical-align: top;
        }
        .meta-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        .meta-val-primary {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
        }
        .meta-val-sub {
            font-size: 10.5px;
            color: #334155;
            margin-top: 3px;
            line-height: 1.4;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .items-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
        }
        .items-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .summary-container-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            margin-bottom: 20px;
        }
        .summary-totals-box {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .summary-totals-box td {
            padding: 5px 10px;
            font-size: 11px;
        }
        .total-row {
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
            background-color: #f0fdf4;
            font-size: 12px !important;
            font-weight: bold;
            color: #166534;
        }
        .reversed-alert {
            background-color: #fef2f2;
            border: 1px solid #f87171;
            color: #b91c1c;
            padding: 8px;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 14px;
            font-size: 11px;
        }
        .notes-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 10.5px;
            color: #475569;
            line-height: 1.4;
        }
        .signatures-section {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signatures-section td {
            width: 45%;
            vertical-align: top;
        }
        .sign-line {
            border-top: 1px solid #94a3b8;
            padding-top: 6px;
            font-size: 11px;
            font-weight: bold;
            color: #334155;
        }
        .sign-sub {
            font-size: 9.5px;
            color: #94a3b8;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    @php
        $receiptSchool = $school ?? $payment->school ?? \App\Models\School::find($payment->school_id) ?? \App\Models\School::first();
    @endphp

    <!-- Header Section -->
    <table class="header-table" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="vertical-align: middle; width: 65%;">
                <div class="school-name">{{ $receiptSchool?->name ?? 'EduCore International School' }}</div>
                <div class="school-info">
                    {{ $receiptSchool?->address ?? 'Institutional Campus' }}{{ !empty($receiptSchool?->city) ? ', '.$receiptSchool->city : '' }}<br>
                    Email: {{ $receiptSchool?->email ?? 'admin@educore.edu' }} | Phone: {{ $receiptSchool?->phone ?? '—' }}
                </div>
            </td>
            <td style="text-align: right; vertical-align: middle; width: 35%;">
                <span class="receipt-badge">Payment Receipt</span>
                <div class="receipt-title">{{ $payment->receipt_number }}</div>
                <div style="font-size: 10.5px; color: #64748b; margin-top: 3px;">
                    Date: {{ $payment->payment_date->format('F d, Y') }}
                </div>
            </td>
        </tr>
    </table>

    @if($payment->isReversed())
        <div class="reversed-alert">
            VOID / PAYMENT REVERSED<br>
            <span style="font-size: 10px; font-weight: normal;">Reversed on {{ $payment->reversed_at?->format('M d, Y H:i') }} • Reason: {{ $payment->reversal_reason }}</span>
        </div>
    @endif

    <!-- Student & Payment Information Box -->
    <table class="meta-box" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="width: 50%;">
                <div class="meta-label">Student Details</div>
                <div class="meta-val-primary">{{ $payment->student?->first_name }} {{ $payment->student?->last_name }}</div>
                <div class="meta-val-sub">
                    <strong>Admission No:</strong> {{ $payment->student?->admission_number }}<br>
                    <strong>Class:</strong> {{ $payment->invoice?->academicClass?->name ?? '—' }}
                    @if($payment->invoice?->section)
                        • Sec: {{ $payment->invoice->section->name }}
                    @endif
                </div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="meta-label">Payment Information</div>
                <div class="meta-val-sub">
                    <strong>Invoice No:</strong> {{ $payment->invoice?->invoice_number }}<br>
                    <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}<br>
                    @if($payment->transaction_reference || $payment->cheque_number)
                        <strong>Reference:</strong> {{ $payment->transaction_reference ?? 'Chq #'.$payment->cheque_number }}<br>
                    @endif
                    <strong>Cashier:</strong> {{ $payment->receiver?->name ?? 'System Administrator' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Line Items Breakdown -->
    <table class="items-table" cellpadding="0" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th style="width: 6%; text-align: center;">#</th>
                <th style="width: 48%; text-align: left;">Fee Item & Description</th>
                <th style="width: 26%; text-align: left;">Category</th>
                <th style="width: 20%; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payment->invoice?->items ?? [] as $index => $item)
                <tr>
                    <td style="text-align: center; color: #94a3b8; font-family: monospace;">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->name }}</strong></td>
                    <td style="color: #64748b;">{{ $item->feeCategory?->name ?? 'General' }}</td>
                    <td style="text-align: right; font-family: monospace;">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #94a3b8; padding: 12px;">No items listed on invoice.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Totals & Balances Container (No Floats) -->
    <table class="summary-container-table" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <!-- Left Side: Notes & Informational text -->
            <td style="width: 52%; vertical-align: top; padding-right: 25px;">
                @if($payment->notes)
                    <div class="notes-box">
                        <strong>Remarks / Notes:</strong><br>
                        {{ $payment->notes }}
                    </div>
                @else
                    <div class="notes-box" style="border-style: dashed; color: #64748b;">
                        <strong>Notice:</strong> This official computer-generated receipt serves as institutional verification of fee payment. Please retain this document for your academic & financial records.
                    </div>
                @endif
            </td>

            <!-- Right Side: Itemized Financial Totals -->
            <td style="width: 48%; vertical-align: top;">
                <table class="summary-totals-box" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="color: #64748b;">Invoice Subtotal:</td>
                        <td style="text-align: right; font-family: monospace; font-weight: bold;">{{ number_format($payment->invoice?->subtotal, 2) }}</td>
                    </tr>
                    @if($payment->invoice?->discount_amount > 0)
                        <tr>
                            <td style="color: #7c3aed;">Waiver / Discount:</td>
                            <td style="text-align: right; font-family: monospace; color: #7c3aed; font-weight: bold;">-{{ number_format($payment->invoice->discount_amount, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td style="padding: 7px 10px;">Amount Received:</td>
                        <td style="text-align: right; font-family: monospace; font-size: 13px; padding: 7px 10px;">{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding-top: 6px;">Remaining Invoice Balance:</td>
                        <td style="text-align: right; font-family: monospace; padding-top: 6px; font-weight: bold; color: {{ $payment->invoice?->due_amount > 0 ? '#b45309' : '#64748b' }};">
                            {{ number_format($payment->invoice?->due_amount, 2) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Signatures Section with guaranteed spacing and clear separation -->
    <table class="signatures-section" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="width: 42%; vertical-align: top;">
                <div class="sign-line">Guardian / Payer Signature</div>
                <div class="sign-sub">Received with thanks</div>
            </td>
            <td style="width: 16%;"></td>
            <td style="width: 42%; vertical-align: top; text-align: right;">
                <div class="sign-line">Authorized Signature & Seal</div>
                <div class="sign-sub">Institutional Accounts Division</div>
            </td>
        </tr>
    </table>

</body>
</html>
