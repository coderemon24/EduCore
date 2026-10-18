@php
    // Prepare Base64 Data URIs if not already passed
    if (!isset($bgDataUri) && $setting->background_image_path && file_exists(public_path($setting->background_image_path))) {
        $p = public_path($setting->background_image_path);
        $m = mime_content_type($p) ?: 'image/jpeg';
        $bgDataUri = 'data:' . $m . ';base64,' . base64_encode(file_get_contents($p));
    }
    if (!isset($logoDataUri) && $setting->school_logo_path && file_exists(public_path($setting->school_logo_path))) {
        $p = public_path($setting->school_logo_path);
        $m = mime_content_type($p) ?: 'image/png';
        $logoDataUri = 'data:' . $m . ';base64,' . base64_encode(file_get_contents($p));
    }
    if (!isset($sigDataUri) && $setting->principal_signature_path && file_exists(public_path($setting->principal_signature_path))) {
        $p = public_path($setting->principal_signature_path);
        $m = mime_content_type($p) ?: 'image/png';
        $sigDataUri = 'data:' . $m . ';base64,' . base64_encode(file_get_contents($p));
    }
    if (!isset($studentPhotoDataUri) && $student->photo_path && file_exists(public_path($student->photo_path))) {
        $p = public_path($student->photo_path);
        $m = mime_content_type($p) ?: 'image/jpeg';
        $studentPhotoDataUri = 'data:' . $m . ';base64,' . base64_encode(file_get_contents($p));
    }

    $guardian = $student->guardians?->first();
    $fatherName = $student->father_name ?: ($guardian && $guardian->relationship === 'father' ? $guardian->full_name : null);
    $motherName = $student->mother_name ?: ($guardian && $guardian->relationship === 'mother' ? $guardian->full_name : null);
    $optSubjId = $optionalSubjectId ?? $enrollment?->optional_subject_id;

    $showHighest = !empty($setting->show_highest_marks);
    $showAttendance = !empty($setting->show_attendance_report);
    $showActivities = !empty($setting->show_extra_activities);
    $showGrading = !empty($setting->show_gpa_grading_scale);
    $hasMiddleBox = $showAttendance || $showActivities;

    // Calculate column widths for bottom analytical panels
    $visiblePanels = 1; // Overall report always visible
    if ($hasMiddleBox) $visiblePanels++;
    if ($showGrading) $visiblePanels++;

    if ($visiblePanels === 3) {
        $wOverall = '36%';
        $wMiddle = '36%';
        $wGrading = '28%';
    } elseif ($visiblePanels === 2) {
        if ($hasMiddleBox) {
            $wOverall = '50%';
            $wMiddle = '50%';
            $wGrading = '0%';
        } else {
            $wOverall = '60%';
            $wMiddle = '0%';
            $wGrading = '40%';
        }
    } else {
        $wOverall = '100%';
        $wMiddle = '0%';
        $wGrading = '0%';
    }
@endphp

<!-- Full-bleed background for DomPDF -->
@if(!empty($bgDataUri))
    <div id="page-background">
        <img src="{{ $bgDataUri }}" alt="Background Template">
    </div>
@endif

<div class="marksheet-card {{ !empty($isPreview) ? 'preview-card' : '' }}"
     style="padding: {{ $setting->padding_css }}; 
     @if(!empty($bgDataUri) || ($setting->background_image_path && file_exists(public_path($setting->background_image_path)))) background-image: url('{{ $bgDataUri ?? asset($setting->background_image_path) }}'); background-size: cover; background-position: center; @endif
     @if(empty($bgDataUri) && (!$setting->background_image_path || !file_exists(public_path($setting->background_image_path)))) border: 4px double #1e293b; outline: 1px solid #475569; outline-offset: -3px; @endif">

    <!-- Header Table -->
    <table class="w-full ms-header-table" style="margin-bottom: 6px;">
        <tr>
            <!-- Logo Column -->
            <td style="width: 75px; text-align: left; vertical-align: middle;">
                <div class="ms-logo-box">
                    @if(!empty($logoDataUri))
                        <img src="{{ $logoDataUri }}" style="
                        width: 150px;
                        " alt="Logo">
                    @elseif($setting->school_logo_path && file_exists(public_path($setting->school_logo_path)))
                        <img src="{{ asset($setting->school_logo_path) }}" alt="Logo">
                    @else
                        <div style="width: 50px; height: 50px; border-radius: 50%; border: 1px solid #0f172a; text-align: center; line-height: 50px; font-size: 8px; font-weight: bold; color: #0f172a;">
                            SEAL
                        </div>
                    @endif
                </div>
            </td>

            <!-- School & Exam Title Column -->
            <td style="text-align: center; vertical-align: middle; padding: 0 8px;">
                <div class="ms-school-name">
                    {{ $setting->header_title ?: ($school->name ?: 'School Name') }}
                </div>
                <div class="ms-exam-title">
                    {{ $setting->sub_header_title ?: $exam->title }}
                </div>
            </td>

            <!-- Student Photo Column -->
            <td style="width: 75px; text-align: right; vertical-align: middle;">
                <div class="ms-photo-box
                    {{  (empty($student->photo) || !file_exists(public_path($student->photo))) ? 'ms-photo-box-border' : '' }}
                " style="display: inline-block;">

                    @if(!empty($studentPhotoDataUri))
                        <img src="{{ $studentPhotoDataUri }}" alt="{{ $student->full_name }}">
                    @elseif($student->photo && file_exists(public_path($student->photo)))
                        <img src="{{ asset($student->photo) }}" alt="{{ $student->full_name }}">
                    @else
                        <div style="line-height: 75px; font-size: 8px; font-weight: bold; color: #94a3b8;">PHOTO</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Student Metadata 3-Column Table -->
    <table class="ms-meta-table">
        <tr>
            <!-- Col 1 -->
            <td style="width: 38%; border-right: 1px solid #cbd5e1;">
                <div class="ms-student-name">{{ $student->full_name }}</div>
                <table style="width: 100%; border: none;">
                    <tr><td style="width: 52px; font-weight: bold; color: #334155; padding: 1px 0; border: none;">FATHER</td><td style="padding: 1px 0; border: none;">: {{ strtoupper($fatherName ?: '—') }}</td></tr>
                    <tr><td style="width: 52px; font-weight: bold; color: #334155; padding: 1px 0; border: none;">MOTHER</td><td style="padding: 1px 0; border: none;">: {{ strtoupper($motherName ?: '—') }}</td></tr>
                    <tr><td style="width: 52px; font-weight: bold; color: #334155; padding: 1px 0; border: none;">SID</td><td style="padding: 1px 0; border: none; font-weight: bold;">: {{ $student->admission_number }}</td></tr>
                    <tr><td style="width: 52px; font-weight: bold; color: #334155; padding: 1px 0; border: none;">DOB</td><td style="padding: 1px 0; border: none;">: {{ $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '—' }}</td></tr>
                </table>
            </td>

            <!-- Col 2 -->
            <td style="width: 33%; border-right: 1px solid #cbd5e1;">
                <table style="width: 100%; border: none;">
                    <tr><td style="width: 56px; font-weight: bold; color: #334155; padding: 1px 0; border: none;">CLASS</td><td style="padding: 1px 0; border: none; font-weight: bold;">: {{ strtoupper($enrollment?->academicClass?->name ?: '—') }}</td></tr>
                    <tr><td style="width: 56px; font-weight: bold; color: #334155; padding: 1px 0; border: none;">SECTION</td><td style="padding: 1px 0; border: none; font-weight: bold;">: {{ strtoupper($enrollment?->section?->name ?: '—') }}</td></tr>
                    <tr><td style="width: 56px; font-weight: bold; color: #334155; padding: 1px 0; border: none;">GROUP</td><td style="padding: 1px 0; border: none;">: {{ strtoupper($enrollment?->academicGroup?->name ?: 'GENERAL') }}</td></tr>
                    <tr><td style="width: 56px; font-weight: bold; color: #334155; padding: 1px 0; border: none;">YEAR</td><td style="padding: 1px 0; border: none;">: {{ $exam->academicYear?->name ?: date('Y') }}</td></tr>
                </table>
            </td>

            <!-- Col 3 -->
            <td style="width: 29%;">
                <table style="width: 100%; border: none;">
                    <tr><td style="width: 56px; font-weight: bold; color: #334155; padding: 1px 0; border: none;">ROLL NO</td><td style="padding: 1px 0; border: none; font-weight: bold;">: {{ $enrollment?->roll_number ?: '—' }}</td></tr>
                    <tr><td style="width: 56px; font-weight: bold; color: #334155; padding: 1px 0; border: none;">GPA</td><td style="padding: 1px 0; border: none; font-weight: bold; color: #7c3aed;">: {{ number_format($result?->gpa ?? 0, 2) }}</td></tr>
                    <tr><td style="width: 56px; font-weight: bold; color: #334155; padding: 1px 0; border: none;">GRADE</td><td style="padding: 1px 0; border: none; font-weight: bold; color: #7c3aed;">: {{ $result?->overall_grade ?: 'F' }}</td></tr>
                    <tr><td style="width: 56px; font-weight: bold; color: #334155; padding: 1px 0; border: none;">RESULT</td><td style="padding: 1px 0; border: none; font-weight: bold; color: {{ ($result?->result_status === 'passed') ? '#059669' : '#e11d48' }};">: {{ strtoupper($result?->result_status ?: 'DRAFT') }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Subject Banner -->
    <div class="ms-section-banner">
        SUBJECT-WISE GRADE & MARK SHEET
    </div>

    <!-- Main Subject Table -->
    <table class="ms-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 44px;">CODE</th>
                <th rowspan="2" style="text-align: left; padding-left: 6px;">SUBJECT</th>
                <th colspan="4" style="background-color: #f8fafc;">{{ strtoupper($exam->title) }}</th>
                <th rowspan="2" style="width: 44px;">TOTAL</th>
                @if($showHighest)
                    <th rowspan="2" style="width: 46px;">HIGHEST</th>
                @endif
                <th rowspan="2" style="width: 32px;">GP</th>
                <th rowspan="2" style="width: 36px;">GRADE</th>
            </tr>
            <tr>
                <th style="width: 42px;">WRITTEN</th>
                <th style="width: 38px;">MCQ</th>
                <th style="width: 36px;">CA</th>
                <th style="width: 44px;">PRACTICAL</th>
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
                    <td style="font-family: monospace;">{{ $subj->subject?->code ?: '—' }}</td>
                    <td class="subject-name-td">
                        {{ strtoupper($subj->subject?->name ?: 'Subject') }}
                        @if($optSubjId && (int)$subj->subject_id === (int)$optSubjId)
                            <span style="font-size: 7.5px; font-weight: bold; color: #6d28d9; text-transform: uppercase; margin-left: 3px;">(Optional)</span>
                        @endif
                    </td>
                    <td style="font-family: monospace;">{{ $written !== null ? number_format($written, 0) : '' }}</td>
                    <td style="font-family: monospace;">{{ $mcq !== null ? number_format($mcq, 0) : '' }}</td>
                    <td style="font-family: monospace;">{{ $ca !== null ? number_format($ca, 0) : '' }}</td>
                    <td style="font-family: monospace;">{{ $practical !== null ? number_format($practical, 0) : '' }}</td>
                    <td style="font-family: monospace; font-weight: bold;">{{ $mark ? number_format($totalObt, 0) : '—' }}</td>
                    @if($showHighest)
                        <td style="font-family: monospace; color: #475569;">{{ number_format($highest, 0) }}</td>
                    @endif
                    <td style="font-family: monospace; font-weight: bold;">{{ number_format($gp, 1) }}</td>
                    <td style="font-weight: bold; color: {{ $grd === 'F' ? '#e11d48' : '#0f172a' }};">{{ $grd }}</td>
                </tr>
            @endforeach

            <!-- Total Summary Row -->
            <tr class="total-row">
                <td colspan="6" style="text-align: right; padding-right: 10px; font-weight: bold; letter-spacing: 0.5px;">
                    Total Marks & GPA =
                </td>
                <td style="font-family: monospace; font-weight: bold;">{{ number_format($result?->total_marks_obtained ?? $grandTotal, 0) }}</td>
                @if($showHighest)
                    <td style="font-family: monospace; font-weight: bold; color: #475569;">{{ number_format($highestTotalInClass ?: $grandHighest, 0) }}</td>
                @endif
                <td style="font-family: monospace; font-weight: bold; color: #7c3aed;">{{ number_format($result?->gpa ?? 0, 2) }}</td>
                <td style="font-weight: bold; color: #7c3aed;">{{ $result?->overall_grade ?: 'F' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Bottom Analytical Panels (DomPDF-safe Table) -->
    <table class="ms-bottom-table">
        <tr>
            <!-- Panel 1: OVERALL REPORT -->
            <td style="width: {{ $wOverall }}; vertical-align: top; padding-right: {{ $visiblePanels > 1 ? '4px' : '0' }};">
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
                                    <td style="font-weight: bold;">{{ $mk ? round($mk->marks_obtained) : '' }}</td>
                                    <td style="font-weight: bold;">{{ $mk?->gradeTier?->point ? number_format($mk->gradeTier->point, 1) : '' }}</td>
                                </tr>
                            @endforeach
                            <tr style="font-weight: bold; background-color: #f1f5f9;">
                                <td>Overall</td>
                                <td colspan="4">Total: {{ number_format($result?->total_marks_obtained ?? 0, 0) }}</td>
                                <td>{{ number_format($result?->percentage ?? 0, 0) }}%</td>
                                <td>{{ number_format($result?->gpa ?? 0, 1) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>

            {{-- Panel 2: Attendance & Activities --}}
            @if($hasMiddleBox)
                <td style="width: {{ $wMiddle }}; vertical-align: top; padding: 0 4px;">
                    <div class="ms-panel-box">
                        @if($showAttendance)
                            <div>
                                <div class="ms-panel-title">ATTENDANCE REPORT</div>
                                <div style="font-size: 7.5px; text-align: center; font-weight: bold; padding: 2px 0; background-color: #f8fafc; border-bottom: 1px solid #0f172a;">
                                    Present: {{ $result?->attendance_present_days ?? 0 }} / {{ $result?->attendance_total_days ?? 0 }} Days
                                </div>
                                <table style="width: 100%; border-collapse: collapse; font-size: 7px; padding: 2px;">
                                    <tr>
                                        <td style="border: none; padding: 1.5px 4px;">Jan : --/--</td>
                                        <td style="border: none; padding: 1.5px 4px;">Feb : --/--</td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 1.5px 4px;">Mar : --/--</td>
                                        <td style="border: none; padding: 1.5px 4px;">Apr : --/--</td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 1.5px 4px;">May : --/--</td>
                                        <td style="border: none; padding: 1.5px 4px;">Jun : --/--</td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 1.5px 4px;">Jul : --/--</td>
                                        <td style="border: none; padding: 1.5px 4px;">Aug : --/--</td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 1.5px 4px;">Sep : --/--</td>
                                        <td style="border: none; padding: 1.5px 4px;">Oct : --/--</td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 1.5px 4px;">Nov : --/--</td>
                                        <td style="border: none; padding: 1.5px 4px;">Dec : --/--</td>
                                    </tr>
                                </table>
                            </div>
                        @endif

                        @if($showActivities)
                            <div style="{{ $showAttendance ? 'border-top: 1px solid #0f172a;' : '' }}">
                                <div class="ms-panel-title">CO-CURRICULAR ACTIVITIES</div>
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr class="activity-item">
                                        <td style="border: none; padding: 2px 4px;">Cultural / Dramatic Performance</td>
                                        <td style="border: none; text-align: right; font-weight: bold; padding: 2px 4px;">A+</td>
                                    </tr>
                                    <tr class="activity-item">
                                        <td style="border: none; padding: 2px 4px;">Scout / BNCC / Red Crescent</td>
                                        <td style="border: none; text-align: right; font-weight: bold; padding: 2px 4px;">A</td>
                                    </tr>
                                    <tr class="activity-item">
                                        <td style="border: none; padding: 2px 4px;">Games And Sports</td>
                                        <td style="border: none; text-align: right; font-weight: bold; padding: 2px 4px;">A+</td>
                                    </tr>
                                    <tr class="activity-item" style="border-bottom: none;">
                                        <td style="border: none; padding: 2px 4px;">Math / Science Olympiad</td>
                                        <td style="border: none; text-align: right; font-weight: bold; padding: 2px 4px;">A+</td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                </td>
            @endif

            {{-- Panel 3: GPA Grading Scale --}}
            @if($showGrading)
                <td style="width: {{ $wGrading }}; vertical-align: top; padding-left: {{ $visiblePanels > 1 ? '4px' : '0' }};">
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
                                            <td style="font-weight: bold;">{{ $gt->grade }}</td>
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
                </td>
            @endif
        </tr>
    </table>

    <!-- Triple Verification Signature Lines -->
    <table class="ms-signature-table">
        <tr>
            <td style="width: 33.33%;">
                <div style="height: 28px;"></div>
                <div class="ms-sign-line">Signature<br>(Guardian)</div>
            </td>
            <td style="width: 33.33%;">
                <div style="height: 28px;"></div>
                <div class="ms-sign-line">Signature<br>(Class Teacher)</div>
            </td>
            <td style="width: 33.33%;">
                @if(!empty($sigDataUri))
                    <img src="{{ $sigDataUri }}" alt="Headmaster Signature" class="ms-sig-img">
                @elseif($setting->principal_signature_path && file_exists(public_path($setting->principal_signature_path)))
                    <img src="{{ asset($setting->principal_signature_path) }}" alt="Headmaster Signature" class="ms-sig-img">
                @else
                    <div style="height: 28px;"></div>
                @endif
                <div class="ms-sign-line">Signature<br>({{ $setting->headmaster_title ?: 'Head Master' }})</div>
            </td>
        </tr>
    </table>
</div>
