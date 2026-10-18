<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marksheet - {{ $student->full_name }} ({{ $student->admission_number }}) | {{ $exam->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    @include('admin.exams.report-cards.partials.styles')

    @if(!empty($setting->custom_css))
        <style>
            {!! $setting->custom_css !!}
        </style>
    @endif
</head>
<body>

    <!-- Print Navigation Bar -->
    <div class="no-print-bar">
        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="{{ route('admin.exams.report-cards.index', ['exam_id' => $exam->id]) }}" class="btn btn-back">
                ← Back to Report Cards
            </a>
            <span style="font-size: 13px; font-weight: 700;">Marksheet: {{ $student->full_name }} ({{ $student->admission_number }})</span>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            <button onclick="window.print()" class="btn btn-print">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Print Marksheet
            </button>
            <a href="{{ route('admin.exams.report-cards.pdf', [$exam->id, $student->id]) }}" class="btn btn-back" style="background-color: #059669;">
                Download PDF
            </a>
        </div>
    </div>

    <!-- A4 Document Wrapper -->
    <div class="marksheet-page-wrapper">
        @include('admin.exams.report-cards.partials.card', ['isPreview' => true])
    </div>

</body>
</html>
