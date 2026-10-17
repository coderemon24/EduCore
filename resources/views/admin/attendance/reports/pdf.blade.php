<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Attendance Register</title>
    <style>
        @page {
            margin: 10mm;
            size: a4 landscape;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        .header-table {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .badge {
            background-color: #4f46e5;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
            text-transform: uppercase;
        }
        .meta-strip {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            margin-bottom: 12px;
            padding: 6px 10px;
            font-size: 9.5px;
        }
        .matrix-table {
            width: 100%;
            border-collapse: collapse;
        }
        .matrix-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            padding: 4px 2px;
            border: 1px solid #cbd5e1;
        }
        .matrix-table td {
            padding: 4px 2px;
            border: 1px solid #e2e8f0;
            font-size: 8.5px;
            text-align: center;
        }
        .code-P { color: #166534; font-weight: bold; }
        .code-A { color: #b91c1c; font-weight: bold; background-color: #fef2f2; }
        .code-L { color: #b45309; font-weight: bold; }
        .code-H { color: #4338ca; background-color: #eef2ff; }
        .code-W { color: #94a3b8; background-color: #f1f5f9; }
    </style>
</head>
<body>

    @php
        $academicClass = $classes->firstWhere('id', $selectedClassId);
        $section = $sections->firstWhere('id', $selectedSectionId);
        $monthName = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('F Y');
    @endphp

    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 70%;">
                <div class="school-name">{{ $school?->name ?? 'EduCore International School' }}</div>
                <div style="font-size: 9px; color: #64748b; margin-top: 2px;">
                    {{ $school?->address ?? 'Institutional Campus' }}
                </div>
            </td>
            <td style="width: 30%; text-align: right;">
                <span class="badge">Attendance Register</span>
                <div style="font-size: 13px; font-weight: bold; margin-top: 4px; color: #0f172a;">{{ $monthName }}</div>
            </td>
        </tr>
    </table>

    <table class="meta-strip" cellpadding="0" cellspacing="0">
        <tr>
            <td><strong>Class:</strong> {{ $academicClass?->name ?? '—' }}</td>
            <td><strong>Section:</strong> {{ $section?->name ?? '—' }}</td>
            <td><strong>Total Students:</strong> {{ $overallStats['total_students'] }}</td>
            <td><strong>Working Days:</strong> {{ $overallStats['total_working_days'] }}</td>
            <td style="text-align: right;"><strong>Class Average Rate:</strong> {{ $overallStats['avg_rate'] }}%</td>
        </tr>
    </table>

    <table class="matrix-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 25px;">Roll</th>
                <th style="width: 110px; text-align: left; padding-left: 6px;">Student Name</th>
                @foreach($daysList as $d => $dayMeta)
                    <th style="width: 16px; {{ $dayMeta['is_weekend'] ? 'background-color: #e2e8f0;' : '' }}">
                        {{ $d }}<br>
                        <span style="font-size: 7px; color: #94a3b8;">{{ substr($dayMeta['day_name'], 0, 1) }}</span>
                    </th>
                @endforeach
                <th style="width: 20px;">P</th>
                <th style="width: 20px;">A</th>
                <th style="width: 20px;">L</th>
                <th style="width: 32px;">Rate</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matrix as $row)
                <tr>
                    <td style="font-family: monospace; font-weight: bold;">{{ $row['roll_number'] ?? '—' }}</td>
                    <td style="text-align: left; padding-left: 6px; font-weight: bold; overflow: hidden; white-space: nowrap;">
                        {{ $row['student']->first_name }} {{ $row['student']->last_name }}
                    </td>
                    @foreach($daysList as $d => $dayMeta)
                        @php
                            $cell = $row['days'][$d] ?? ['code' => '-'];
                            $code = $cell['code'];
                        @endphp
                        <td class="code-{{ $code }}">
                            {{ $code }}
                        </td>
                    @endforeach
                    <td style="font-weight: bold; color: #166534;">{{ $row['present'] }}</td>
                    <td style="font-weight: bold; color: #b91c1c;">{{ $row['absent'] }}</td>
                    <td style="font-weight: bold; color: #b45309;">{{ $row['late'] }}</td>
                    <td style="font-weight: bold; font-family: monospace;">{{ $row['rate'] }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($daysList) + 6 }}" style="text-align: center; color: #94a3b8; padding: 15px;">
                        No enrolled students or attendance recorded for this class/section.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 10px; font-size: 8px; color: #64748b;">
        <strong>Legend:</strong>
        <span style="color: #166534; font-weight: bold; margin-left: 6px;">P = Present</span> •
        <span style="color: #b91c1c; font-weight: bold; margin-left: 6px;">A = Absent</span> •
        <span style="color: #b45309; font-weight: bold; margin-left: 6px;">L = Late</span> •
        <span style="color: #4338ca; font-weight: bold; margin-left: 6px;">H = Holiday</span> •
        <span style="color: #94a3b8; font-weight: bold; margin-left: 6px;">W = Weekend</span>
    </div>

</body>
</html>
