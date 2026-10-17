<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marksheet - {{ $student->full_name }}</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 6mm 8mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5px;
            line-height: 1.25;
            color: #0f172a;
            background-color: #ffffff;
        }

        @if($setting->background_image_path && file_exists(public_path($setting->background_image_path)))
            #watermark {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1000;
            }
            #watermark img {
                width: 100%;
                height: 100%;
            }
        @endif

        .container {
            width: 100%;
            padding: 3mm;
            @if(!$setting->background_image_path || !file_exists(public_path($setting->background_image_path)))
                border: 2px solid #0f172a;
            @endif
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Header */
        .header-table td {
            vertical-align: middle;
        }

        .school-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: #0f172a;
            letter-spacing: 0.5px;
        }

        .exam-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: #1e293b;
            margin-top: 2px;
            letter-spacing: 1px;
        }

        .logo-box {
            width: 60px;
            text-align: center;
        }

        .logo-box img {
            max-width: 55px;
            max-height: 55px;
        }

        .photo-box {
            width: 60px;
            height: 70px;
            border: 1px solid #0f172a;
            text-align: center;
            vertical-align: middle;
            background-color: #f8fafc;
        }

        .photo-box img {
            max-width: 58px;
            max-height: 68px;
        }

        /* Student Metadata Table */
        .meta-table {
            margin-top: 6px;
            margin-bottom: 6px;
            border-top: 1px solid #0f172a;
            border-bottom: 1px solid #0f172a;
            background-color: #ffffff;
        }

        .meta-table td {
            padding: 3px 5px;
            font-size: 8.5px;
            vertical-align: top;
        }

        .meta-label {
            font-weight: bold;
            color: #334155;
            display: inline-block;
            width: 55px;
        }

        .meta-val {
            font-weight: bold;
            color: #0f172a;
        }

        .student-name-header {
            font-size: 10.5px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
            color: #0f172a;
        }

        /* Section Banner */
        .section-banner {
            text-align: center;
            font-size: 9.5px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 3px 0;
            border-top: 1px solid #0f172a;
            border-bottom: 1px solid #0f172a;
            background-color: #f1f5f9;
            margin-bottom: 5px;
        }

        /* Subject Table */
        .subject-table {
            margin-bottom: 6px;
        }

        .subject-table th, .subject-table td {
            border: 1px solid #0f172a;
            padding: 2.5px 3px;
            text-align: center;
            font-size: 8px;
        }

        .subject-table th {
            font-weight: bold;
            text-transform: uppercase;
            background-color: #f1f5f9;
        }

        .subject-table td.subject-name {
            text-align: left;
            padding-left: 5px;
            font-weight: bold;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f1f5f9;
        }

        /* Bottom 3-Panel Table */
        .bottom-panels-table {
            margin-bottom: 10px;
        }

        .bottom-panels-table > tbody > tr > td {
            vertical-align: top;
            padding: 0 3px;
        }

        .panel-box {
            border: 1px solid #0f172a;
        }

        .panel-title {
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 2px;
            border-bottom: 1px solid #0f172a;
            background-color: #f1f5f9;
        }

        .sub-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sub-table th, .sub-table td {
            border: 1px solid #0f172a;
            padding: 1.5px 2px;
            text-align: center;
            font-size: 7.5px;
        }

        .sub-table th {
            font-weight: bold;
            background-color: #f8fafc;
        }

        .activity-row td {
            padding: 1.5px 3px;
            font-size: 7px;
            border-bottom: 1px dashed #cbd5e1;
        }

        /* Signatures Table */
        .signature-table {
            margin-top: 15px;
            width: 100%;
        }

        .signature-table td {
            text-align: center;
            vertical-align: bottom;
            font-size: 8.5px;
            font-weight: bold;
            padding: 0 10px;
        }

        .sig-line {
            border-top: 1px solid #0f172a;
            margin-top: 3px;
            padding-top: 2px;
        }

        .sig-img {
            max-height: 28px;
            margin-bottom: 2px;
        }
    </style>
</head>
<body>

    @if($setting->background_image_path && file_exists(public_path($setting->background_image_path)))
        <div id="watermark">
            <img src="{{ public_path($setting->background_image_path) }}" alt="Background">
        </div>
    @endif

    <div class="container">

        <!-- Header -->
        <table class="header-table">
            <tr>
                <td class="logo-box">
                    @if($setting->school_logo_path && file_exists(public_path($setting->school_logo_path)))
                        <img src="{{ public_path($setting->school_logo_path) }}" alt="Crest">
                    @else
                        <div style="font-size: 9px; font-weight: bold; color: #047857; border: 1px solid #047857; padding: 4px;">SEAL</div>
                    @endif
                </td>
                <td>
                    <div class="school-name">{{ $setting->header_title ?: ($school->name ?: 'Joypurhat Government Girls\' High School') }}</div>
                    <div class="exam-title">{{ $setting->sub_header_title ?: ($exam->title ?: 'HALF YEARLY EXAMINATION-2026') }}</div>
                </td>
                <td class="photo-box">
                    @if($student->photo_path && file_exists(public_path($student->photo_path)))
                        <img src="{{ public_path($student->photo_path) }}" alt="Student Photo">
                    @else
                        <div style="font-size: 7px; color: #64748b; font-weight: bold; padding: 10px 0;">PASSPORT<br>PHOTO</div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Student Meta -->
        @php
            $guardian = $student->guardians?->first();
            $fatherName = $student->father_name ?: ($guardian && $guardian->relationship === 'father' ? $guardian->full_name : 'MD MUSA SALAM');
            $motherName = $student->mother_name ?: ($guardian && $guardian->relationship === 'mother' ? $guardian->full_name : 'MST SHAKILA BANU');
        @endphp
        <table class="meta-table">
            <tr>
                <!-- Col 1 -->
                <td style="width: 38%;">
                    <div class="student-name-header">{{ $student->full_name }}</div>
                    <div><span class="meta-label">FATHER</span>: <span class="meta-val">{{ strtoupper($fatherName) }}</span></div>
                    <div><span class="meta-label">MOTHER</span>: <span class="meta-val">{{ strtoupper($motherName) }}</span></div>
                    <div><span class="meta-label">SID</span>: <span class="meta-val">{{ $student->admission_number }}</span></div>
                    <div><span class="meta-label">DOB</span>: <span class="meta-val">{{ $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '2012-11-09' }}</span></div>
                </td>

                <!-- Col 2 -->
                <td style="width: 34%;">
                    <div style="height: 14px;"></div>
                    <div><span class="meta-label">SHIFT</span>: <span class="meta-val">MORNING</span></div>
                    <div><span class="meta-label">BOARD</span>: <span class="meta-val">RAJSHAHI</span></div>
                    <div><span class="meta-label">CLASS</span>: <span class="meta-val">{{ strtoupper($enrollment?->academicClass?->name ?: 'NINE') }}</span></div>
                    <div><span class="meta-label">GROUP</span>: <span class="meta-val">{{ strtoupper($enrollment?->academicGroup?->name ?: 'SCIENCE') }}</span></div>
                    <div><span class="meta-label">SECTION</span>: <span class="meta-val">{{ strtoupper($enrollment?->section?->name ?: 'A') }}</span></div>
                </td>

                <!-- Col 3 -->
                <td style="width: 28%;">
                    <div style="height: 14px;"></div>
                    <div><span class="meta-label">ROLL NO</span>: <span class="meta-val">{{ $enrollment?->roll_number ?: '9' }}</span></div>
                    <div><span class="meta-label">GPA</span>: <span class="meta-val" style="font-size: 10px; color: #7c3aed;">{{ number_format($result?->gpa ?? 0, 2) }}</span></div>
                    <div><span class="meta-label">GRADE</span>: <span class="meta-val" style="font-size: 10px; color: #7c3aed;">{{ $result?->overall_grade ?: 'A' }}</span></div>
                    <div><span class="meta-label">MERIT</span>: <span class="meta-val">{{ $result?->rank_in_class ? '#' . $result->rank_in_class : '—' }}</span></div>
                </td>
            </tr>
        </table>

        <!-- Section Banner -->
        <div class="section-banner">
            SUBJECT-WISE GRADE & MARK SHEET
        </div>

        <!-- Subject Marks Table -->
        <table class="subject-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 35px;">CODE</th>
                    <th rowspan="2" style="text-align: left; padding-left: 5px;">SUBJECT</th>
                    <th colspan="4">{{ strtoupper($exam->title) }}</th>
                    <th rowspan="2" style="width: 38px;">Total</th>
                    @if($setting->show_highest_marks)
                        <th rowspan="2" style="width: 38px;">Highest</th>
                    @endif
                    <th rowspan="2" style="width: 28px;">GP</th>
                    <th rowspan="2" style="width: 32px;">Grade</th>
                </tr>
                <tr>
                    <th style="width: 36px;">Written</th>
                    <th style="width: 32px;">MCQ</th>
                    <th style="width: 30px;">CA</th>
                    <th style="width: 38px;">Practical</th>
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
                        <td>{{ $subj->subject?->code ?: '101' }}</td>
                        <td class="subject-name">{{ strtoupper($subj->subject?->name) }}</td>
                        <td>{{ $written !== null ? number_format($written, 0) : '' }}</td>
                        <td>{{ $mcq !== null ? number_format($mcq, 0) : '' }}</td>
                        <td>{{ $ca !== null ? number_format($ca, 0) : '' }}</td>
                        <td>{{ $practical !== null ? number_format($practical, 0) : '' }}</td>
                        <td><strong>{{ $mark ? number_format($totalObt, 0) : '—' }}</strong></td>
                        @if($setting->show_highest_marks)
                            <td style="color: #475569;">{{ number_format($highest, 0) }}</td>
                        @endif
                        <td><strong>{{ number_format($gp, 1) }}</strong></td>
                        <td style="font-weight: bold; color: {{ $grd === 'F' ? '#e11d48' : '#0f172a' }};">{{ $grd }}</td>
                    </tr>
                @endforeach

                <!-- Summary Row -->
                <tr class="total-row">
                    <td colspan="6" style="text-align: right; padding-right: 10px;">
                        Total Marks & GPA =
                    </td>
                    <td><strong>{{ number_format($result?->total_marks_obtained ?? $grandTotal, 0) }}</strong></td>
                    @if($setting->show_highest_marks)
                        <td><strong>{{ number_format($highestTotalInClass ?: $grandHighest, 0) }}</strong></td>
                    @endif
                    <td style="color: #7c3aed;"><strong>{{ number_format($result?->gpa ?? 0, 2) }}</strong></td>
                    <td style="color: #7c3aed;"><strong>{{ $result?->overall_grade ?: 'A' }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Bottom 3 Panels -->
        <table class="bottom-panels-table">
            <tr>
                <!-- Panel 1: OVERALL REPORT -->
                <td style="width: 36%;">
                    <div class="panel-box">
                        <div class="panel-title">OVERALL REPORT</div>
                        <table class="sub-table">
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
                                        <td>{{ $sub->subject?->code }}</td>
                                        <td>{{ $mk ? round($mk->marks_obtained * 0.7) : '' }}</td>
                                        <td>{{ $mk ? round($mk->marks_obtained * 0.3) : '' }}</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td><strong>{{ $mk ? round($mk->marks_obtained) : '' }}</strong></td>
                                        <td><strong>{{ $mk?->gradeTier?->point ? number_format($mk->gradeTier->point, 1) : '' }}</strong></td>
                                    </tr>
                                @endforeach
                                <tr style="font-weight: bold; background-color: #f1f5f9;">
                                    <td>Overall</td>
                                    <td colspan="4">Tot: {{ number_format($result?->total_marks_obtained ?? 0, 0) }}</td>
                                    <td>{{ number_format($result?->percentage ?? 0, 0) }}%</td>
                                    <td>{{ number_format($result?->gpa ?? 0, 1) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>

                <!-- Panel 2: ATTENDANCE & ACTIVITIES -->
                <td style="width: 36%;">
                    <div class="panel-box">
                        <div class="panel-title">ATTENDANCE REPORT</div>
                        <div style="font-size: 7px; text-align: center; font-weight: bold; padding: 1.5px 0; background-color: #f8fafc; border-bottom: 1px solid #0f172a;">
                            Month : Present/Working Days
                        </div>
                        <table style="width: 100%; font-size: 7px; padding: 2px;">
                            <tr>
                                <td>Jan : 7/10</td><td>Jul : --/--</td>
                            </tr>
                            <tr>
                                <td>Feb : 7/10</td><td>Aug : --/--</td>
                            </tr>
                            <tr>
                                <td>Mar : 7/7</td><td>Sep : --/--</td>
                            </tr>
                            <tr>
                                <td>Apr : 8/9</td><td>Oct : --/--</td>
                            </tr>
                            <tr>
                                <td>May : 7/7</td><td>Nov : --/--</td>
                            </tr>
                            <tr>
                                <td>Jun : 8/12</td><td>Dec : --/--</td>
                            </tr>
                        </table>

                        <div class="panel-title" style="border-top: 1px solid #0f172a;">EXTRA ACTIVITIES</div>
                        <table style="width: 100%;">
                            <tr class="activity-row">
                                <td>Cultural / Dramatic Performance</td>
                                <td style="text-align: right;"><strong>A+</strong></td>
                            </tr>
                            <tr class="activity-row">
                                <td>Scout / BNCC / Red Crescent</td>
                                <td style="text-align: right;"><strong>A</strong></td>
                            </tr>
                            <tr class="activity-row">
                                <td>Games And Sports</td>
                                <td style="text-align: right;"><strong>A+</strong></td>
                            </tr>
                            <tr class="activity-row" style="border-bottom: none;">
                                <td>Math / Science Olympiad</td>
                                <td style="text-align: right;"><strong>A+</strong></td>
                            </tr>
                        </table>
                    </div>
                </td>

                <!-- Panel 3: GPA GRADING SCALE -->
                <td style="width: 28%;">
                    <div class="panel-box">
                        <div class="panel-title">GPA GRADING</div>
                        <table class="sub-table">
                            <thead>
                                <tr>
                                    <th>Marks(%)</th>
                                    <th>Grade</th>
                                    <th>Point</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($exam->gradingScale && $exam->gradingScale->gradeTiers->isNotEmpty())
                                    @foreach($exam->gradingScale->gradeTiers as $gt)
                                        <tr>
                                            <td>{{ number_format($gt->min_percentage, 0) }}{{ $gt->max_percentage >= 100 ? '+' : '-' . number_format($gt->max_percentage, 0) }}</td>
                                            <td><strong>{{ $gt->grade }}</strong></td>
                                            <td>{{ number_format($gt->point, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td>80+</td><td><strong>A+</strong></td><td>5.00</td></tr>
                                    <tr><td>70-79</td><td><strong>A</strong></td><td>4.00</td></tr>
                                    <tr><td>60-69</td><td><strong>A-</strong></td><td>3.50</td></tr>
                                    <tr><td>50-59</td><td><strong>B</strong></td><td>3.00</td></tr>
                                    <tr><td>40-49</td><td><strong>C</strong></td><td>2.00</td></tr>
                                    <tr><td>33-39</td><td><strong>D</strong></td><td>1.00</td></tr>
                                    <tr><td><33</td><td><strong>F</strong></td><td>0.00</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Signatures Block -->
        <table class="signature-table">
            <tr>
                <td style="width: 30%;">
                    <div style="height: 25px;"></div>
                    <div class="sig-line">Signature (Guardian)</div>
                </td>
                <td style="width: 40%;">
                    <div style="height: 25px;"></div>
                    <div class="sig-line">Signature (Class Teacher)</div>
                </td>
                <td style="width: 30%;">
                    @if($setting->principal_signature_path && file_exists(public_path($setting->principal_signature_path)))
                        <img src="{{ public_path($setting->principal_signature_path) }}" alt="Principal Signature" class="sig-img">
                    @else
                        <div style="height: 25px;"></div>
                    @endif
                    <div class="sig-line">Signature ({{ $setting->headmaster_title ?: 'Head Master' }})</div>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>
