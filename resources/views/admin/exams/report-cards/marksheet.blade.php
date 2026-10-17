<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marksheet - {{ $student->full_name }} ({{ $student->admission_number }}) | {{ $exam->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 6mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Top Action Bar (hidden in print) */
        .no-print-bar {
            background-color: #1e293b;
            color: #ffffff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }

        .btn-print {
            background-color: #7c3aed;
            color: #ffffff;
        }
        .btn-print:hover { background-color: #6d28d9; }

        .btn-back {
            background-color: rgba(255,255,255,0.15);
            color: #ffffff;
        }
        .btn-back:hover { background-color: rgba(255,255,255,0.25); }

        /* A4 Sheet Container */
        .marksheet-page-wrapper {
            display: flex;
            justify-content: center;
            padding: 20px 10px;
        }

        .marksheet-card {
            width: 210mm;
            min-height: 297mm;
            background-color: #ffffff;
            padding: 10mm 12mm;
            position: relative;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            @if($setting->background_image_path && file_exists(public_path($setting->background_image_path)))
                background-image: url('{{ asset($setting->background_image_path) }}');
                background-size: 100% 100%;
                background-repeat: no-repeat;
                background-position: center;
            @else
                border: 4px double #1e293b;
                outline: 1px solid #475569;
                outline-offset: -3px;
            @endif
        }

        /* Header Branding */
        .ms-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 8px;
        }

        .ms-logo-box {
            width: 70px;
            height: 70px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ms-logo-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .ms-title-box {
            flex-grow: 1;
            text-align: center;
        }

        .ms-school-name {
            font-family: 'Cinzel', 'Times New Roman', serif;
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #0f172a;
            line-height: 1.2;
        }

        .ms-exam-title {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #1e293b;
            margin-top: 3px;
        }

        .ms-photo-box {
            width: 70px;
            height: 80px;
            border: 1px solid #0f172a;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8fafc;
            overflow: hidden;
        }

        .ms-photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Student Meta 3-Column Block */
        .ms-meta-grid {
            display: grid;
            grid-template-columns: 1.4fr 1.2fr 0.9fr;
            gap: 10px;
            font-size: 10px;
            line-height: 1.4;
            padding: 6px 10px;
            border-top: 1px solid #0f172a;
            border-bottom: 1px solid #0f172a;
            margin-bottom: 8px;
            background-color: rgba(255, 255, 255, 0.85);
        }

        .ms-meta-col {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .ms-meta-item {
            display: flex;
        }

        .ms-meta-label {
            width: 65px;
            font-weight: 700;
            color: #334155;
            flex-shrink: 0;
        }

        .ms-meta-val {
            font-weight: 700;
            color: #0f172a;
        }

        .ms-student-name {
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        /* Section Banner */
        .ms-section-banner {
            text-align: center;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 3px 0;
            border-top: 1px solid #0f172a;
            border-bottom: 1px solid #0f172a;
            background-color: rgba(241, 245, 249, 0.9);
            margin-bottom: 6px;
        }

        /* Main Subject Table */
        .ms-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
            margin-bottom: 8px;
            background-color: rgba(255, 255, 255, 0.95);
        }

        .ms-table th, .ms-table td {
            border: 1px solid #0f172a;
            padding: 3px 4px;
            text-align: center;
            vertical-align: middle;
        }

        .ms-table th {
            font-weight: 800;
            text-transform: uppercase;
            background-color: rgba(241, 245, 249, 0.95);
        }

        .ms-table td.subject-name-td {
            text-align: left;
            font-weight: 700;
            padding-left: 6px;
        }

        .ms-table tr.total-row {
            font-weight: 800;
            background-color: rgba(241, 245, 249, 0.95);
        }

        /* Bottom 3-Panel Analytical Layout */
        .ms-bottom-panels {
            display: grid;
            grid-template-columns: 1.3fr 1.3fr 1fr;
            gap: 6px;
            margin-bottom: 15px;
        }

        .ms-panel-box {
            border: 1px solid #0f172a;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .ms-panel-title {
            text-align: center;
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 2px 4px;
            border-bottom: 1px solid #0f172a;
            background-color: rgba(241, 245, 249, 0.95);
        }

        .ms-subtable {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }

        .ms-subtable th, .ms-subtable td {
            border: 1px solid #0f172a;
            padding: 2px 3px;
            text-align: center;
        }

        .ms-subtable th {
            font-weight: 700;
            background-color: #f8fafc;
        }

        .activity-item {
            font-size: 8px;
            padding: 2px 4px;
            border-bottom: 1px dashed #cbd5e1;
            display: flex;
            justify-content: space-between;
        }

        /* Signature Verification Block */
        .ms-signatures {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 30px;
            padding: 0 15px;
        }

        .ms-sign-col {
            text-align: center;
            width: 140px;
        }

        .ms-sign-line {
            border-top: 1px solid #0f172a;
            margin-top: 4px;
            padding-top: 2px;
            font-size: 10px;
            font-weight: 700;
            color: #0f172a;
        }

        .ms-sig-img {
            height: 35px;
            margin: 0 auto -5px auto;
            display: block;
            object-fit: contain;
        }

        @media print {
            body { background: transparent; }
            .no-print-bar { display: none !important; }
            .marksheet-page-wrapper { padding: 0 !important; }
            .marksheet-card {
                box-shadow: none !important;
                width: 100% !important;
                min-height: 100% !important;
                page-break-after: avoid;
            }
        }
    </style>
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
        <div class="marksheet-card">

            <!-- Institutional Header -->
            <div class="ms-header">
                <!-- Crest Logo -->
                <div class="ms-logo-box">
                    @if($setting->school_logo_path && file_exists(public_path($setting->school_logo_path)))
                        <img src="{{ asset($setting->school_logo_path) }}" alt="Crest">
                    @else
                        <!-- Default Institutional Seal SVG -->
                        <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 65px; height: 65px;">
                            <polygon points="50,5 95,25 95,75 50,95 5,75 5,25" stroke="#059669" stroke-width="4" fill="#ecfdf5"/>
                            <polygon points="50,15 85,30 85,70 50,85 15,70 15,30" stroke="#047857" stroke-width="2" fill="#ffffff"/>
                            <path d="M50 30 L50 65 M35 48 L65 48" stroke="#047857" stroke-width="4" stroke-linecap="round"/>
                            <circle cx="50" cy="48" r="8" fill="#10b981"/>
                        </svg>
                    @endif
                </div>

                <!-- Titles -->
                <div class="ms-title-box">
                    <div class="ms-school-name">{{ $setting->header_title ?: ($school->name ?: 'Joypurhat Government Girls\' High School') }}</div>
                    <div class="ms-exam-title">{{ $setting->sub_header_title ?: ($exam->title ?: 'HALF YEARLY EXAMINATION-2026') }}</div>
                </div>

                <!-- Student Passport Photo -->
                <div class="ms-photo-box">
                    @if($student->photo_path && file_exists(public_path($student->photo_path)))
                        <img src="{{ asset($student->photo_path) }}" alt="Student Photo">
                    @elseif($student->avatar_url)
                        <img src="{{ $student->avatar_url }}" alt="Student Photo">
                    @else
                        <div style="font-size: 8px; color: #64748b; text-align: center; font-weight: 700;">PASSPORT<br>PHOTO</div>
                    @endif
                </div>
            </div>

            <!-- 3-Column Student Metadata Box -->
            @php
                $guardian = $student->guardians?->first();
                $fatherName = $student->father_name ?: ($guardian && $guardian->relationship === 'father' ? $guardian->full_name : 'MD MUSA SALAM');
                $motherName = $student->mother_name ?: ($guardian && $guardian->relationship === 'mother' ? $guardian->full_name : 'MST SHAKILA BANU');
            @endphp
            <div class="ms-meta-grid">
                <!-- Col 1 -->
                <div class="ms-meta-col">
                    <div class="ms-student-name">{{ $student->full_name }}</div>
                    <div class="ms-meta-item"><span class="ms-meta-label">FATHER</span>: <span class="ms-meta-val">&nbsp;{{ strtoupper($fatherName) }}</span></div>
                    <div class="ms-meta-item"><span class="ms-meta-label">MOTHER</span>: <span class="ms-meta-val">&nbsp;{{ strtoupper($motherName) }}</span></div>
                    <div class="ms-meta-item"><span class="ms-meta-label">SID</span>: <span class="ms-meta-val">&nbsp;{{ $student->admission_number }}</span></div>
                    <div class="ms-meta-item"><span class="ms-meta-label">DOB</span>: <span class="ms-meta-val">&nbsp;{{ $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '2012-11-09' }}</span></div>
                </div>

                <!-- Col 2 -->
                <div class="ms-meta-col">
                    <div class="ms-meta-item"><span class="ms-meta-label">SHIFT</span>: <span class="ms-meta-val">&nbsp;MORNING</span></div>
                    <div class="ms-meta-item"><span class="ms-meta-label">BOARD</span>: <span class="ms-meta-val">&nbsp;RAJSHAHI</span></div>
                    <div class="ms-meta-item"><span class="ms-meta-label">CLASS</span>: <span class="ms-meta-val">&nbsp;{{ strtoupper($enrollment?->academicClass?->name ?: 'NINE') }}</span></div>
                    <div class="ms-meta-item"><span class="ms-meta-label">GROUP</span>: <span class="ms-meta-val">&nbsp;{{ strtoupper($enrollment?->academicGroup?->name ?: 'SCIENCE') }}</span></div>
                    <div class="ms-meta-item"><span class="ms-meta-label">SECTION</span>: <span class="ms-meta-val">&nbsp;{{ strtoupper($enrollment?->section?->name ?: 'A') }}</span></div>
                </div>

                <!-- Col 3 -->
                <div class="ms-meta-col">
                    <div class="ms-meta-item"><span class="ms-meta-label">ROLL NO</span>: <span class="ms-meta-val">&nbsp;{{ $enrollment?->roll_number ?: '9' }}</span></div>
                    <div class="ms-meta-item"><span class="ms-meta-label">GPA</span>: <span class="ms-meta-val" style="font-size: 12px; color: #7c3aed;">&nbsp;{{ number_format($result?->gpa ?? 0, 2) }}</span></div>
                    <div class="ms-meta-item"><span class="ms-meta-label">GRADE</span>: <span class="ms-meta-val" style="font-size: 12px; color: #7c3aed;">&nbsp;{{ $result?->overall_grade ?: 'A' }}</span></div>
                </div>
            </div>

            <!-- Subject-Wise Grade & Mark Sheet Section Title -->
            <div class="ms-section-banner">
                SUBJECT-WISE GRADE & MARK SHEET
            </div>

            <!-- Subject Table with Dynamic Components -->
            <table class="ms-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 38px;">CODE</th>
                        <th rowspan="2" style="text-align: left; padding-left: 6px;">SUBJECT</th>
                        <th colspan="4" style="background-color: #f8fafc;">{{ strtoupper($exam->title) }}</th>
                        <th rowspan="2" style="width: 44px;">Total</th>
                        @if($setting->show_highest_marks)
                            <th rowspan="2" style="width: 44px;">Highest</th>
                        @endif
                        <th rowspan="2" style="width: 32px;">GP</th>
                        <th rowspan="2" style="width: 36px;">Grade</th>
                    </tr>
                    <tr>
                        <th style="width: 42px;">Written</th>
                        <th style="width: 38px;">MCQ</th>
                        <th style="width: 36px;">CA</th>
                        <th style="width: 46px;">Practical</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotal = 0;
                        $grandHighest = 0;
                    @endphp
                    @foreach($examSubjects as $subj)
                        @php
                            $mark = $marks->get($subj->id);
                            $highest = $highestMarks[$subj->id] ?? $subj->full_marks;
                            $grandHighest += $highest;

                            $cMarks = $mark ? $mark->components->keyBy(function($c) {
                                return strtolower(trim($c->examSubjectComponent?->name));
                            }) : collect();

                            $written = $cMarks->get('written')?->marks_obtained;
                            $mcq = $cMarks->get('mcq')?->marks_obtained;
                            $ca = $cMarks->get('ca')?->marks_obtained;
                            $practical = $cMarks->get('practical')?->marks_obtained;

                            $totalObt = $mark ? (float)$mark->marks_obtained : 0;
                            $grandTotal += $totalObt;
                            $gp = $mark?->gradeTier?->point ?? ($mark?->is_passed ? 1.00 : 0.00);
                            $grd = $mark?->gradeTier?->grade ?? ($mark?->is_passed ? 'D' : 'F');
                            if ($mark && $mark->attendance_status !== 'present') {
                                $grd = strtoupper($mark->attendance_status);
                                $gp = 0.00;
                            }
                        @endphp
                        <tr>
                            <td style="font-family: 'JetBrains Mono', monospace; font-weight: 700;">{{ $subj->subject?->code ?: '101' }}</td>
                            <td class="subject-name-td">{{ strtoupper($subj->subject?->name) }}</td>
                            <td style="font-family: 'JetBrains Mono', monospace;">{{ $written !== null ? number_format($written, 0) : '' }}</td>
                            <td style="font-family: 'JetBrains Mono', monospace;">{{ $mcq !== null ? number_format($mcq, 0) : '' }}</td>
                            <td style="font-family: 'JetBrains Mono', monospace;">{{ $ca !== null ? number_format($ca, 0) : '' }}</td>
                            <td style="font-family: 'JetBrains Mono', monospace;">{{ $practical !== null ? number_format($practical, 0) : '' }}</td>
                            <td style="font-family: 'JetBrains Mono', monospace; font-weight: 700;">{{ $mark ? number_format($totalObt, 0) : '—' }}</td>
                            @if($setting->show_highest_marks)
                                <td style="font-family: 'JetBrains Mono', monospace; color: #475569;">{{ number_format($highest, 0) }}</td>
                            @endif
                            <td style="font-family: 'JetBrains Mono', monospace; font-weight: 700;">{{ number_format($gp, 1) }}</td>
                            <td style="font-weight: 800; color: {{ $grd === 'F' ? '#e11d48' : '#0f172a' }};">{{ $grd }}</td>
                        </tr>
                    @endforeach

                    <!-- Bottom Summary Row -->
                    <tr class="total-row">
                        <td colspan="6" style="text-align: right; padding-right: 12px; font-weight: 800; letter-spacing: 0.5px;">
                            Total Marks & GPA =
                        </td>
                        <td style="font-family: 'JetBrains Mono', monospace; font-weight: 800;">{{ number_format($result?->total_marks_obtained ?? $grandTotal, 0) }}</td>
                        @if($setting->show_highest_marks)
                            <td style="font-family: 'JetBrains Mono', monospace; font-weight: 800; color: #475569;">{{ number_format($highestTotalInClass ?: $grandHighest, 0) }}</td>
                        @endif
                        <td style="font-family: 'JetBrains Mono', monospace; font-weight: 800; color: #7c3aed;">{{ number_format($result?->gpa ?? 0, 2) }}</td>
                        <td style="font-weight: 900; color: #7c3aed;">{{ $result?->overall_grade ?: 'A' }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Bottom 3-Panel Analytical Section -->
            <div class="ms-bottom-panels">

                <!-- Left Panel: OVERALL REPORT -->
                <div class="ms-panel-box">
                    <div class="ms-panel-title">OVERALL REPORT</div>
                    <table class="ms-subtable">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>W</th>
                                <th>M</th>
                                <th>P</th>
                                <th>C</th>
                                <th>TW/CA</th>
                                <th>Gp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($examSubjects->take(7) as $sub)
                                @php $mk = $marks->get($sub->id); @endphp
                                <tr>
                                    <td style="font-family: monospace;">{{ $sub->subject?->code }}</td>
                                    <td>{{ $mk ? round($mk->marks_obtained * 0.7) : '' }}</td>
                                    <td>{{ $mk ? round($mk->marks_obtained * 0.3) : '' }}</td>
                                    <td>—</td>
                                    <td>—</td>
                                    <td style="font-weight: 700;">{{ $mk ? round($mk->marks_obtained) : '' }}</td>
                                    <td style="font-weight: 700;">{{ $mk?->gradeTier?->point ? number_format($mk->gradeTier->point, 1) : '' }}</td>
                                </tr>
                            @endforeach
                            <tr style="font-weight: 800; background-color: #f1f5f9;">
                                <td>Overall</td>
                                <td colspan="4">Total: {{ number_format($result?->total_marks_obtained ?? 0, 0) }}</td>
                                <td>{{ number_format($result?->percentage ?? 0, 0) }}%</td>
                                <td>{{ number_format($result?->gpa ?? 0, 1) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Middle Panel: ATTENDANCE REPORT & EXTRA ACTIVITIES -->
                <div class="ms-panel-box" style="display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div class="ms-panel-title">ATTENDANCE REPORT</div>
                        <div style="font-size: 8px; text-align: center; font-weight: 700; padding: 2px 0; background-color: #f8fafc; border-bottom: 1px solid #0f172a;">
                            Month : Present/Working Days
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; font-size: 8px; font-family: 'JetBrains Mono', monospace; padding: 3px 6px; line-height: 1.4;">
                            <div>Jan : 7/10</div>
                            <div>Feb : 7/10</div>
                            <div>Mar : 7/7</div>
                            <div>Apr : 8/9</div>
                            <div>May : 7/7</div>
                            <div>Jun : 8/12</div>
                            <div>Jul : --/--</div>
                            <div>Aug : --/--</div>
                            <div>Sep : --/--</div>
                            <div>Oct : --/--</div>
                            <div>Nov : --/--</div>
                            <div>Dec : --/--</div>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #0f172a;">
                        <div class="ms-panel-title" style="border-bottom: 1px solid #0f172a;">EXTRA ACTIVITIES</div>
                        <div class="activity-item"><span>Cultural / Dramatic Performance</span><strong>A+</strong></div>
                        <div class="activity-item"><span>Scout / BNCC / Red Crescent</span><strong>A</strong></div>
                        <div class="activity-item"><span>Games And Sports</span><strong>A+</strong></div>
                        <div class="activity-item" style="border-bottom: none;"><span>Math / Science Olympiad</span><strong>A+</strong></div>
                    </div>
                </div>

                <!-- Right Panel: GPA GRADING SCALE -->
                <div class="ms-panel-box">
                    <div class="ms-panel-title">GPA GRADING</div>
                    <table class="ms-subtable">
                        <thead>
                            <tr>
                                <th>Range Of Marks(%)</th>
                                <th>Grade</th>
                                <th>Point</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($exam->gradingScale && $exam->gradingScale->gradeTiers->isNotEmpty())
                                @foreach($exam->gradingScale->gradeTiers as $gt)
                                    <tr>
                                        <td>{{ number_format($gt->min_percentage, 0) }} {{ $gt->max_percentage >= 100 ? 'Or Above' : 'To ' . number_format($gt->max_percentage, 0) }}</td>
                                        <td style="font-weight: 800;">{{ $gt->grade }}</td>
                                        <td style="font-family: monospace;">{{ number_format($gt->point, 2) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td>80 Or Above</td><td>A+</td><td>5.00</td></tr>
                                <tr><td>70 To 79</td><td>A</td><td>4.00</td></tr>
                                <tr><td>60 To 69</td><td>A-</td><td>3.50</td></tr>
                                <tr><td>50 To 59</td><td>B</td><td>3.00</td></tr>
                                <tr><td>40 To 49</td><td>C</td><td>2.00</td></tr>
                                <tr><td>33 To 39</td><td>D</td><td>1.00</td></tr>
                                <tr><td>Below 33</td><td>F</td><td>0.00</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- Triple Verification Signature Lines -->
            <div class="ms-signatures">
                <div class="ms-sign-col">
                    <div style="height: 30px;"></div>
                    <div class="ms-sign-line">Signature<br>(Guardian)</div>
                </div>

                <div class="ms-sign-col">
                    <div style="height: 30px;"></div>
                    <div class="ms-sign-line">Signature (Class Teacher)</div>
                </div>

                <div class="ms-sign-col">
                    @if($setting->principal_signature_path && file_exists(public_path($setting->principal_signature_path)))
                        <img src="{{ asset($setting->principal_signature_path) }}" alt="Headmaster Signature" class="ms-sig-img">
                    @else
                        <!-- Stylized Default Script Signature SVG -->
                        <svg class="ms-sig-img" viewBox="0 0 160 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 35 C 30 10, 45 45, 65 20 C 80 5, 95 30, 115 15 C 130 5, 140 25, 155 20" stroke="#0f172a" stroke-width="2" stroke-linecap="round"/>
                            <path d="M40 38 L 130 36" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    @endif
                    <div class="ms-sign-line">Signature ({{ $setting->headmaster_title ?: 'Head Master' }})</div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
