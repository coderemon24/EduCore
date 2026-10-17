<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Outstanding Dues Report by Class</title>
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
            background-color: #0f172a;
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
                <span class="report-badge">Dues Analysis</span>
                <div style="font-size: 13px; font-weight: bold; margin-top: 4px; color: #0f172a;">Outstanding by Class</div>
            </td>
        </tr>
    </table>

    <!-- Meta Details -->
    <table class="filter-meta" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="width: 50%;"><strong>Academic Session:</strong> {{ $currentYear?->name ?? date('Y') }}</td>
            <td style="width: 50%; text-align: right;"><strong>Total Classes Analyzed:</strong> {{ count($classDues) }}</td>
        </tr>
        <tr>
            <td style="width: 50%;"><strong>Report Type:</strong> Class-wise Receivables & Recovery Rates</td>
            <td style="width: 50%; text-align: right;"><strong>Generated On:</strong> {{ now()->format('M d, Y h:i A') }}</td>
        </tr>
    </table>

    <!-- Main Table with Percentage Columns (Sum = 100%) -->
    <table class="items-table" cellpadding="0" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th style="width: 25%; text-align: left;">Academic Class</th>
                <th style="width: 12%; text-align: right;">Invoiced</th>
                <th style="width: 11%; text-align: right;">Waivers</th>
                <th style="width: 13%; text-align: right;">Net Invoiced</th>
                <th style="width: 12%; text-align: right;">Collected</th>
                <th style="width: 12%; text-align: right;">Outstanding Due</th>
                <th style="width: 10%; text-align: center;">Rate (%)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totInvoiced = 0;
                $totDiscount = 0;
                $totNet = 0;
                $totCollected = 0;
                $totDue = 0;
            @endphp
            @forelse($classDues as $index => $row)
                @php
                    $totInvoiced += $row['invoiced'];
                    $totDiscount += $row['discount'];
                    $totNet += $row['net_invoiced'];
                    $totCollected += $row['collected'];
                    $totDue += $row['due'];
                @endphp
                <tr>
                    <td style="text-align: center; color: #94a3b8; font-family: monospace;">{{ $index + 1 }}</td>
                    <td style="font-weight: bold;">{{ $row['class']->name }}</td>
                    <td style="text-align: right; font-family: monospace;">{{ number_format($row['invoiced'], 2) }}</td>
                    <td style="text-align: right; font-family: monospace; color: #7c3aed;">{{ number_format($row['discount'], 2) }}</td>
                    <td style="text-align: right; font-family: monospace; font-weight: bold;">{{ number_format($row['net_invoiced'], 2) }}</td>
                    <td style="text-align: right; font-family: monospace; color: #166534; font-weight: bold;">{{ number_format($row['collected'], 2) }}</td>
                    <td style="text-align: right; font-family: monospace; color: {{ $row['due'] > 0 ? '#b91c1c' : '#64748b' }}; font-weight: bold;">
                        {{ number_format($row['due'], 2) }}
                    </td>
                    <td style="text-align: center; font-weight: bold;">{{ $row['rate'] }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #94a3b8; padding: 14px;">No class fee metrics available.</td>
                </tr>
            @endforelse
        </tbody>
        @if(count($classDues) > 0)
            <tfoot>
                <tr style="background-color: #f8fafc; font-weight: bold; border-top: 2px solid #0f172a;">
                    <td colspan="2" style="text-align: right; padding: 7px;">Institutional Totals:</td>
                    <td style="text-align: right; font-family: monospace;">{{ number_format($totInvoiced, 2) }}</td>
                    <td style="text-align: right; font-family: monospace; color: #7c3aed;">{{ number_format($totDiscount, 2) }}</td>
                    <td style="text-align: right; font-family: monospace;">{{ number_format($totNet, 2) }}</td>
                    <td style="text-align: right; font-family: monospace; color: #166534;">{{ number_format($totCollected, 2) }}</td>
                    <td style="text-align: right; font-family: monospace; color: #b91c1c;">{{ number_format($totDue, 2) }}</td>
                    <td style="text-align: center;">{{ $totNet > 0 ? round(($totCollected / $totNet) * 100, 1) : 0 }}%</td>
                </tr>
            </tfoot>
        @endif
    </table>

</body>
</html>
