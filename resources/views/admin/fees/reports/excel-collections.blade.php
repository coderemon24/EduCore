<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        th {
            background-color: #4f46e5;
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
            <th colspan="9" style="background-color: #1e293b; color: #ffffff; font-size: 14pt; height: 30pt; text-align: left;">
                {{ $school?->name ?? 'EduCore International School' }} - Fee Collection Register
            </th>
        </tr>
        <tr>
            <td colspan="9" style="color: #64748b;">
                Reporting Period: {{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }} to {{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }} | Method: {{ ucfirst($methodFilter) }} | Generated: {{ now()->toDateTimeString() }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th>Receipt #</th>
            <th>Date</th>
            <th>Student Name</th>
            <th>Admission No</th>
            <th>Class</th>
            <th>Invoice #</th>
            <th>Method</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
        @foreach($payments as $payment)
            <tr>
                <td class="text">{{ $payment->receipt_number }}</td>
                <td class="text">{{ $payment->payment_date->format('Y-m-d') }}</td>
                <td class="text">{{ $payment->student ? ($payment->student->first_name . ' ' . $payment->student->last_name) : '—' }}</td>
                <td class="text">{{ $payment->student?->admission_number ?? '—' }}</td>
                <td class="text">{{ $payment->student?->currentEnrollment?->academicClass?->name ?? '—' }}</td>
                <td class="text">{{ $payment->invoice?->invoice_number ?? '—' }}</td>
                <td class="text">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                <td class="number">{{ $payment->amount }}</td>
                <td class="text" style="font-weight: bold; color: {{ $payment->status === 'completed' ? '#166534' : '#b91c1c' }};">
                    {{ strtoupper($payment->status) }}
                </td>
            </tr>
        @endforeach
        <tr></tr>
        <tr>
            <td colspan="7" style="font-weight: bold; text-align: right; border-top: 1pt solid #0f172a;">Total Completed Collection:</td>
            <td class="number" style="font-weight: bold; color: #166534; border-top: 1pt solid #0f172a;">{{ $totalCollected }}</td>
            <td style="border-top: 1pt solid #0f172a;"></td>
        </tr>
    </table>
</body>
</html>
