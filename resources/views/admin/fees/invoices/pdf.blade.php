<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
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
        .invoice-badge {
            background-color: #4f46e5;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 4px;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .invoice-title {
            font-size: 16px;
            font-weight: bold;
            font-family: monospace;
            color: #0f172a;
            margin-top: 6px;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
            margin-top: 4px;
        }
        .status-paid { background-color: #dcfce7; color: #15803d; }
        .status-partial { background-color: #fef3c7; color: #b45309; }
        .status-unpaid { background-color: #f1f5f9; color: #475569; }
        .status-overdue { background-color: #fee2e2; color: #b91c1c; }
        .status-void { background-color: #f1f5f9; color: #94a3b8; }

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
            background-color: #f8fafc;
            font-size: 12px !important;
            font-weight: bold;
            color: #0f172a;
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
        $invoiceSchool = $school ?? $invoice->school ?? \App\Models\School::find($invoice->school_id) ?? \App\Models\School::first();
    @endphp

    <!-- Header Section -->
    <table class="header-table" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="vertical-align: middle; width: 65%;">
                <div class="school-name">{{ $invoiceSchool?->name ?? 'EduCore International School' }}</div>
                <div class="school-info">
                    {{ $invoiceSchool?->address ?? 'Institutional Campus' }}{{ !empty($invoiceSchool?->city) ? ', '.$invoiceSchool->city : '' }}<br>
                    Email: {{ $invoiceSchool?->email ?? 'admin@educore.edu' }} | Phone: {{ $invoiceSchool?->phone ?? '—' }}
                </div>
            </td>
            <td style="text-align: right; vertical-align: middle; width: 35%;">
                <span class="invoice-badge">Fee Invoice</span>
                <div class="invoice-title">{{ $invoice->invoice_number }}</div>
                <div>
                    <span class="status-badge status-{{ $invoice->status }}">{{ strtoupper($invoice->status) }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Student & Invoice Information Box -->
    <table class="meta-box" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="width: 50%;">
                <div class="meta-label">Billed Student</div>
                <div class="meta-val-primary">{{ $invoice->student?->first_name }} {{ $invoice->student?->last_name }}</div>
                <div class="meta-val-sub">
                    <strong>Admission No:</strong> {{ $invoice->student?->admission_number }}<br>
                    <strong>Class:</strong> {{ $invoice->academicClass?->name ?? '—' }}
                    @if($invoice->section)
                        • Sec: {{ $invoice->section->name }}
                    @endif
                    <br>
                    @if($invoice->student?->guardians?->isNotEmpty())
                        <strong>Guardian:</strong> {{ $invoice->student->guardians->first()->name }} ({{ $invoice->student->guardians->first()->phone }})
                    @endif
                </div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="meta-label">Invoice Details</div>
                <div class="meta-val-sub">
                    <strong>Title:</strong> {{ $invoice->title }}<br>
                    <strong>Issue Date:</strong> {{ $invoice->issue_date?->format('F d, Y') ?? '—' }}<br>
                    <strong>Due Date:</strong> {{ $invoice->due_date?->format('F d, Y') ?? '—' }}<br>
                    <strong>Session:</strong> {{ $invoice->academicYear?->name ?? date('Y') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Line Items Table -->
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
            @forelse($invoice->items as $index => $item)
                <tr>
                    <td style="text-align: center; color: #94a3b8; font-family: monospace;">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->name }}</strong></td>
                    <td style="color: #64748b;">{{ $item->feeCategory?->name ?? 'General' }}</td>
                    <td style="text-align: right; font-family: monospace;">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #94a3b8; padding: 12px;">No items listed on this invoice.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Totals & Balances Container (No Floats) -->
    <table class="summary-container-table" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <!-- Left Side: Notes & Instructions -->
            <td style="width: 52%; vertical-align: top; padding-right: 25px;">
                @if($invoice->remarks)
                    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 8px 12px; font-size: 10.5px; color: #475569; line-height: 1.4;">
                        <strong>Remarks / Notes:</strong><br>
                        {{ $invoice->remarks }}
                    </div>
                @else
                    <div style="background-color: #f8fafc; border: 1px dashed #e2e8f0; border-radius: 4px; padding: 8px 12px; font-size: 10.5px; color: #64748b; line-height: 1.4;">
                        <strong>Payment Instructions:</strong> Fees should be settled on or before the designated due date. Payments can be completed at the school accounts desk or online portal.
                    </div>
                @endif
            </td>

            <!-- Right Side: Itemized Totals Box -->
            <td style="width: 48%; vertical-align: top;">
                <table class="summary-totals-box" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="color: #64748b;">Subtotal:</td>
                        <td style="text-align: right; font-family: monospace; font-weight: bold;">{{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    @if($invoice->discount_amount > 0)
                        <tr>
                            <td style="color: #7c3aed;">Waiver / Discount:</td>
                            <td style="text-align: right; font-family: monospace; color: #7c3aed; font-weight: bold;">-{{ number_format($invoice->discount_amount, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td style="padding: 7px 10px;">Total Invoiced:</td>
                        <td style="text-align: right; font-family: monospace; font-size: 12px; padding: 7px 10px;">{{ number_format($invoice->subtotal - $invoice->discount_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="color: #15803d; padding-top: 5px;">Paid Amount:</td>
                        <td style="text-align: right; font-family: monospace; padding-top: 5px; color: #15803d; font-weight: bold;">
                            {{ number_format($invoice->paid_amount, 2) }}
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #e2e8f0;">
                        <td style="color: #0f172a; padding-top: 6px; font-weight: bold;">Amount Due:</td>
                        <td style="text-align: right; font-family: monospace; padding-top: 6px; font-weight: bold; font-size: 13px; color: {{ $invoice->due_amount > 0 ? '#b91c1c' : '#15803d' }};">
                            {{ number_format($invoice->due_amount, 2) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Payment History if any -->
    @if($invoice->payments && $invoice->payments->isNotEmpty())
        <div style="margin-top: 8px; margin-bottom: 20px;">
            <div style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #475569; margin-bottom: 5px; letter-spacing: 0.5px;">Payment Transactions Recorded</div>
            <table class="items-table" cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style="width: 110px; text-align: left;">Receipt #</th>
                        <th style="text-align: left;">Date</th>
                        <th style="text-align: left;">Method</th>
                        <th style="width: 100px; text-align: right;">Amount</th>
                        <th style="width: 80px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                        <tr>
                            <td style="font-family: monospace; font-weight: bold;">{{ $payment->receipt_number }}</td>
                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td style="text-align: right; font-family: monospace;">{{ number_format($payment->amount, 2) }}</td>
                            <td style="text-align: center; color: {{ $payment->status === 'completed' ? '#15803d' : '#b91c1c' }}; font-weight: bold;">
                                {{ strtoupper($payment->status) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Signatures -->
    <table class="signatures-section" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="width: 42%; vertical-align: top;">
                <div class="sign-line">Student / Guardian Acknowledgment</div>
                <div class="sign-sub">Signature & Date</div>
            </td>
            <td style="width: 16%;"></td>
            <td style="width: 42%; vertical-align: top; text-align: right;">
                <div class="sign-line">Authorized Institutional Official</div>
                <div class="sign-sub">Accounts & Finance Seal</div>
            </td>
        </tr>
    </table>

</body>
</html>
