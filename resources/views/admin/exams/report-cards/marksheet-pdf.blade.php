<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marksheet - {{ $student->full_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    
    @include('admin.exams.report-cards.partials.styles')

    @if(!empty($setting->custom_css))
        <style>
            {!! $setting->custom_css !!}
        </style>
    @endif
<body style="margin: 0; padding: 0;">

    @include('admin.exams.report-cards.partials.card', ['isPreview' => false])

</body>
</html>
