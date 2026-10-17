<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title', 'Institutional Document')</title>
    <style>
        @page {
            margin: 10mm 12mm 12mm 12mm;
            size: @yield('page_size', 'a4 portrait');
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5px;
            line-height: 1.35;
            color: #0f172a;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
            margin: 0;
            padding: 0;
        }

        th, td {
            word-wrap: break-word;
            overflow: hidden;
            vertical-align: middle;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        /* Typography & Utilities */
        .font-mono {
            font-family: 'Courier New', Courier, monospace;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-muted {
            color: #64748b;
        }
        .text-dark {
            color: #0f172a;
        }
        .font-bold {
            font-weight: bold;
        }
        .font-semibold {
            font-weight: 600;
        }
        .uppercase {
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Outline Badges (Toner-Safe & High Contrast) */
        .doc-badge {
            display: inline-block;
            border: 1px solid #0f172a;
            color: #0f172a;
            background-color: transparent;
            font-size: 8.5px;
            font-weight: bold;
            padding: 2px 7px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 3px;
        }

        .status-badge {
            display: inline-block;
            font-size: 8px;
            font-weight: bold;
            padding: 2px 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-radius: 3px;
            border-width: 1px;
            border-style: solid;
        }
        .badge-success {
            border-color: #166534;
            color: #166534;
            background-color: #f0fdf4;
        }
        .badge-warning {
            border-color: #b45309;
            color: #b45309;
            background-color: #fffbeb;
        }
        .badge-danger {
            border-color: #b91c1c;
            color: #b91c1c;
            background-color: #fef2f2;
        }
        .badge-neutral {
            border-color: #94a3b8;
            color: #475569;
            background-color: #f8fafc;
        }

        /* Standard Structured Tables */
        .report-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }
        .report-table th {
            background-color: #f8fafc;
            color: #334155;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 5px 6px;
            border: 0.75pt solid #cbd5e1;
        }
        .report-table td {
            padding: 5px 6px;
            border: 0.75pt solid #e2e8f0;
            font-size: 9px;
            color: #1e293b;
        }
        .report-table tr:nth-child(even) td {
            background-color: #fcfdfe;
        }

        /* Meta Filter / Parameter Box */
        .meta-box {
            width: 100%;
            background-color: #ffffff;
            border: 0.75pt solid #cbd5e1;
            border-radius: 4px;
            margin-bottom: 12px;
        }
        .meta-box td {
            padding: 5px 8px;
            font-size: 9px;
            color: #334155;
        }

        /* Summary Total Bar */
        .total-box {
            margin-top: 10px;
            border-top: 1.5pt solid #0f172a;
            border-bottom: 1.5pt solid #0f172a;
            background-color: #f8fafc;
        }
        .total-box td {
            padding: 6px 8px;
            font-size: 10px;
        }

        /* Document Footer & Signatures */
        .doc-footer {
            margin-top: 24px;
            page-break-inside: avoid;
            width: 100%;
        }
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')
</body>
</html>
