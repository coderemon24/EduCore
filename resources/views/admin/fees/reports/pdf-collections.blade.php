<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fee Collections Register</title>
    <style>
        @page {
            margin: 10mm 12mm 12mm 12mm;
            size: a4 landscape;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.35;
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
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-badge {
            background-color: #4f46e5;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .filter-meta {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            margin-bottom: 14px;
        }
        .filter-meta td {
            font-size: 10px;
            padding: 6px 10px;
        }
        .items-table {
            margin-bottom: 0;
        }
        .items-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 6px;
            border: 1px solid #cbd5e1;
            letter-spacing: 0.3px;
        }
        .items-table td {
            padding: 5px 6px;
            border: 1px solid #e2e8f0;
            font-size: 9.5px;
            vertical-align: middle;
        }
        .total-box {
            margin-top: 12px;
            border-top: 2px solid #0f172a;
            background-color: #f8fafc;
        }
        .total-box td {
            padding: 7px 10px;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table class="header-table" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="width: 70%; vertical-align: middle;">
                <div class="school-name">{{ $school?->name ?? 'EduCore International School' }}</div>
                <div style="font-size: 9.5px; color: #64748b; margin-top: 2px;">
                    {{ $school?->address ?? 'Institutional Campus' }} • {{ $school?->phone ?? 'Phone: —' }}
                </div>
            </td>
            <td style="width: 30%; text-align: right; vertical-align: middle;">
                <span class="report-badge">Financial Register</span>
                <div style="font-size: 13px; font-weight: bold; margin-top: 4px; color: #0f172a;">Fee Collections Report</div>
            </td>
        </tr>
    </table>

    <!-- Filter Meta Summary -->
    <table class="filter-meta" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="width: 50%;"><strong>Reporting Period:</strong> {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</td>
            <td style="width: 50%; text-align: right;"><strong>Payment Method Filter:</strong> {{ ucfirst($methodFilter) }}</td>
        </tr>
        <tr>
            <td style="width: 50%;"><strong>Total Transactions:</strong> {{ count($payments) }}</td>
            <td style="width: 50%; text-align: right;"><strong>Generated On:</strong> {{ now()->format('M d, Y h:i A') }}</td>
        </tr>
    </table>

    <!-- Main Collections Table with Exact Percentage Column Widths (Sum = 100%) -->
    <table class="items-table" cellpadding="0" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">#</th>
                <th style="width: 15%; text-align: left;">Receipt #</th>
                <th style="width: 10%; text-align: left;">Date</th>
                <th style="width: 22%; text-align: left;">Student Name</th>
                <th style="width: 10%; text-align: left;">Class</th>
                <th style="width: 14%; text-align: left;">Invoice #</th>
                <th style="width: 8%; text-align: left;">Method</th>
                <th style="width: 9%; text-align: right;">Amount</th>
                <th style="width: 8%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $payment)
                <tr>
                    <td style="text-align: center; color: #94a3b8; font-family: monospace;">{{ $index + 1 }}</td>
                    <td style="font-family: monospace; font-weight: bold;">{{ $payment->receipt_number }}</td>
                    <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                    <td style="font-weight: 500;">{{ $payment->student ? ($payment->student->first_name . ' ' . $payment->student->last_name) : '—' }}</td>
                    <td>{{ $payment->student?->currentEnrollment?->academicClass?->name ?? '—' }}</td>
                    <td style="font-family: monospace;">{{ $payment->invoice?->invoice_number ?? '—' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                    <td style="text-align: right; font-family: monospace; font-weight: bold;">{{ number_format($payment->amount, 2) }}</td>
                    <td style="text-align: center; font-weight: bold; color: {{ $payment->status === 'completed' ? '#166534' : '#b91c1c' }};">
                        {{ strtoupper($payment->status) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #94a3b8; padding: 14px;">No collection records found for the selected filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Totals Container -->
    <table class="total-box" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="width: 75%; font-weight: bold; color: #0f172a;">
                Total Completed Collections:
            </td>
            <td style="width: 25%; text-align: right; font-family: monospace; font-size: 12px; font-weight: bold; color: #166534;">
                {{ number_format($totalCollected, 2) }}
            </td>
        </tr>
    </table>

</body>
</html>
