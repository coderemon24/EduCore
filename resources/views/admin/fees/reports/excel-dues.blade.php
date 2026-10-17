<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            border: 0.5pt solid #cbd5e1;
            padding: 6px;
        }
        td {
            border: 0.5pt solid #e2e8f0;
            padding: 4px 6px;
        }
        .number {
            mso-number-format: "\#\,\#\#0\.00";
            text-align: right;
        }
        .text {
            mso-number-format: "\@";
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th colspan="7" style="background-color: #1e293b; color: #ffffff; font-size: 14pt; height: 30pt; text-align: left;">
                {{ $school?->name ?? 'EduCore International School' }} - Outstanding Dues by Class
            </th>
        </tr>
        <tr>
            <td colspan="7" style="color: #64748b;">
                Session: {{ $currentYear?->name ?? date('Y') }} | Generated: {{ now()->toDateTimeString() }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th>Class</th>
            <th>Total Invoiced</th>
            <th>Waivers / Discounts</th>
            <th>Net Invoiced</th>
            <th>Collected Amount</th>
            <th>Outstanding Due</th>
            <th>Collection Rate (%)</th>
        </tr>
        @php
            $totInvoiced = 0;
            $totDiscount = 0;
            $totNet = 0;
            $totCollected = 0;
            $totDue = 0;
        @endphp
        @foreach($classDues as $row)
            @php
                $totInvoiced += $row['invoiced'];
                $totDiscount += $row['discount'];
                $totNet += $row['net_invoiced'];
                $totCollected += $row['collected'];
                $totDue += $row['due'];
            @endphp
            <tr>
                <td class="text" style="font-weight: bold;">{{ $row['class']->name }}</td>
                <td class="number">{{ $row['invoiced'] }}</td>
                <td class="number">{{ $row['discount'] }}</td>
                <td class="number" style="font-weight: bold;">{{ $row['net_invoiced'] }}</td>
                <td class="number" style="color: #166534; font-weight: bold;">{{ $row['collected'] }}</td>
                <td class="number" style="color: #b91c1c; font-weight: bold;">{{ $row['due'] }}</td>
                <td class="number">{{ $row['rate'] }}%</td>
            </tr>
        @endforeach
        <tr></tr>
        <tr style="font-weight: bold;">
            <td class="text" style="border-top: 1pt solid #000;">TOTALS:</td>
            <td class="number" style="border-top: 1pt solid #000;">{{ $totInvoiced }}</td>
            <td class="number" style="border-top: 1pt solid #000;">{{ $totDiscount }}</td>
            <td class="number" style="border-top: 1pt solid #000;">{{ $totNet }}</td>
            <td class="number" style="border-top: 1pt solid #000; color: #166534;">{{ $totCollected }}</td>
            <td class="number" style="border-top: 1pt solid #000; color: #b91c1c;">{{ $totDue }}</td>
            <td class="number" style="border-top: 1pt solid #000;">{{ $totNet > 0 ? round(($totCollected / $totNet) * 100, 1) : 0 }}%</td>
        </tr>
    </table>
</body>
</html>
