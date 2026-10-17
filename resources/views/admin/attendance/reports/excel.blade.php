<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        th {
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: bold;
            border: 0.5pt solid #cbd5e1;
            padding: 5px;
            text-align: center;
        }
        td {
            border: 0.5pt solid #e2e8f0;
            padding: 4px;
            text-align: center;
        }
        .text {
            mso-number-format: "\@";
        }
        .number {
            mso-number-format: "0";
            text-align: right;
        }
    </style>
</head>
<body>
    @php
        $academicClass = $classes->firstWhere('id', $selectedClassId);
        $section = $sections->firstWhere('id', $selectedSectionId);
        $monthName = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('F Y');
    @endphp

    <table>
        <tr>
            <th colspan="{{ count($daysList) + 6 }}" style="background-color: #1e293b; color: #ffffff; font-size: 14pt; height: 30pt; text-align: left;">
                {{ $school?->name ?? 'EduCore International School' }} - Monthly Attendance Register
            </th>
        </tr>
        <tr>
            <td colspan="{{ count($daysList) + 6 }}" style="text-align: left; color: #64748b;">
                Month: {{ $monthName }} | Class: {{ $academicClass?->name ?? '—' }} | Section: {{ $section?->name ?? '—' }} | Total Students: {{ $overallStats['total_students'] }} | Avg Rate: {{ $overallStats['avg_rate'] }}%
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align: left;">Roll</th>
            <th style="text-align: left;">Student Name</th>
            @foreach($daysList as $d => $dayMeta)
                <th style="{{ $dayMeta['is_weekend'] ? 'background-color: #94a3b8;' : '' }}">
                    {{ $d }}<br>{{ $dayMeta['day_name'] }}
                </th>
            @endforeach
            <th>Present</th>
            <th>Absent</th>
            <th>Late</th>
            <th>Rate (%)</th>
        </tr>
        @foreach($matrix as $row)
            <tr>
                <td class="text" style="text-align: left;">{{ $row['roll_number'] ?? '—' }}</td>
                <td class="text" style="text-align: left; font-weight: bold;">
                    {{ $row['student']->first_name }} {{ $row['student']->last_name }}
                </td>
                @foreach($daysList as $d => $dayMeta)
                    @php
                        $cell = $row['days'][$d] ?? ['code' => '-'];
                        $code = $cell['code'];
                    @endphp
                    <td class="text">
                        {{ $code }}
                    </td>
                @endforeach
                <td class="number" style="color: #166534; font-weight: bold;">{{ $row['present'] }}</td>
                <td class="number" style="color: #b91c1c; font-weight: bold;">{{ $row['absent'] }}</td>
                <td class="number" style="color: #b45309;">{{ $row['late'] }}</td>
                <td class="text" style="font-weight: bold;">{{ $row['rate'] }}%</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
