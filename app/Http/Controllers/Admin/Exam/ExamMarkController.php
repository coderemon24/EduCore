<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\ExamMarkComponent;
use App\Models\ExamSubject;
use App\Models\Section;
use App\Models\StudentEnrollment;
use App\Services\ExamMarkCorrectionService;
use App\Services\GradingService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExamMarkController extends Controller
{
    public function __construct(
        protected GradingService $gradingService,
        protected ExamMarkCorrectionService $correctionService
    ) {}

    /**
     * High-efficiency table-based marks entry screen.
     */
    public function entry(Request $request): View
    {
        $exams = Exam::latest('id')->get();
        $academicClasses = AcademicClass::active()->ordered()->get();

        $selectedExamId = $request->input('exam_id');
        $selectedClassId = $request->input('academic_class_id');
        $selectedSectionId = $request->input('section_id');
        $selectedSubjectId = $request->input('subject_id');

        $sections = collect();
        $examSubjects = collect();
        $selectedExamSubject = null;
        $studentRows = collect();
        $isLocked = false;

        if ($selectedClassId) {
            $sections = Section::where('academic_class_id', $selectedClassId)->active()->get();
        }

        if ($selectedExamId && $selectedClassId) {
            $examSubjects = ExamSubject::where('exam_id', $selectedExamId)
                ->where('academic_class_id', $selectedClassId)
                ->with(['subject', 'components'])
                ->get();
        }

        if ($selectedExamId && $selectedClassId && $selectedSectionId && $selectedSubjectId) {
            $selectedExam = Exam::with('gradingScale.gradeTiers')->find($selectedExamId);
            $isLocked = $selectedExam ? $selectedExam->isLocked() : false;

            $selectedExamSubject = ExamSubject::where('exam_id', $selectedExamId)
                ->where('academic_class_id', $selectedClassId)
                ->where('subject_id', $selectedSubjectId)
                ->with(['subject', 'components'])
                ->first();

            if ($selectedExam && $selectedExamSubject) {
                // Fetch students enrolled in this class & section
                $enrollments = StudentEnrollment::where('school_id', $selectedExam->school_id)
                    ->where('academic_year_id', $selectedExam->academic_year_id)
                    ->where('academic_class_id', $selectedClassId)
                    ->where('section_id', $selectedSectionId)
                    ->where('status', 'current')
                    ->with('student')
                    ->orderBy('roll_number')
                    ->get();

                // Fetch existing marks
                $existingMarks = ExamMark::where('exam_subject_id', $selectedExamSubject->id)
                    ->where('section_id', $selectedSectionId)
                    ->with('components')
                    ->get()
                    ->keyBy('student_id');

                $studentRows = $enrollments->map(function ($enrollment) use ($existingMarks) {
                    $mark = $existingMarks->get($enrollment->student_id);

                    return [
                        'enrollment' => $enrollment,
                        'student' => $enrollment->student,
                        'mark' => $mark,
                        'component_marks' => $mark ? $mark->components->keyBy('exam_subject_component_id') : collect(),
                    ];
                });
            }
        }

        return view('admin.exams.marks.entry', compact(
            'exams',
            'academicClasses',
            'sections',
            'examSubjects',
            'selectedExamId',
            'selectedClassId',
            'selectedSectionId',
            'selectedSubjectId',
            'selectedExamSubject',
            'studentRows',
            'isLocked'
        ));
    }

    /**
     * Batch save entered student marks atomically.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'academic_class_id' => ['required', 'exists:academic_classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'exam_subject_id' => ['required', 'exists:exam_subjects,id'],
            'marks' => ['required', 'array'],
            'marks.*.student_id' => ['required', 'exists:students,id'],
            'marks.*.attendance_status' => ['required', 'string', 'in:present,absent,exempt,incomplete'],
            'marks.*.components' => ['nullable', 'array'],
            'marks.*.remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $exam = Exam::with('gradingScale.gradeTiers')->findOrFail($validated['exam_id']);
        if ($exam->isLocked()) {
            ToastMagic::error('This examination has been published. Routine marks entry is locked.');

            return back();
        }

        $examSubject = ExamSubject::with('components')->findOrFail($validated['exam_subject_id']);
        $schoolId = $exam->school_id;
        $userId = auth()->guard('web')->id();

        // Validate component mark bounds
        $componentsMap = $examSubject->components->keyBy('id');
        foreach ($validated['marks'] as $idx => $sMark) {
            if ($sMark['attendance_status'] === 'present' && ! empty($sMark['components'])) {
                foreach ($sMark['components'] as $cId => $cVal) {
                    if ($cVal !== null && $cVal !== '') {
                        $cObj = $componentsMap->get((int) $cId);
                        $maxAllowed = $cObj ? (float) $cObj->max_marks : (float) $examSubject->full_marks;
                        if ((float) $cVal < 0 || (float) $cVal > $maxAllowed) {
                            return back()->withErrors([
                                "marks.{$idx}.components.{$cId}" => "Component mark exceeds maximum allowed ({$maxAllowed}).",
                            ])->withInput();
                        }
                    }
                }
            }
        }

        DB::transaction(function () use ($validated, $exam, $examSubject, $schoolId, $userId) {
            foreach ($validated['marks'] as $studentMarkData) {
                $studentId = $studentMarkData['student_id'];
                $status = $studentMarkData['attendance_status'];
                $components = $studentMarkData['components'] ?? [];

                $totalObtained = 0.00;
                $hasMarks = false;

                if ($status === 'present') {
                    foreach ($components as $compId => $compVal) {
                        if ($compVal !== null && $compVal !== '') {
                            $compObj = $examSubject->components->firstWhere('id', (int) $compId);
                            $maxAllowed = $compObj ? (float) $compObj->max_marks : (float) $examSubject->full_marks;
                            $val = min($maxAllowed, max(0.00, (float) $compVal));
                            $totalObtained += $val;
                            $hasMarks = true;
                        }
                    }
                }

                $marksObtained = ($status === 'present' && $hasMarks) ? $totalObtained : null;
                $isPassed = ($marksObtained !== null) && ($marksObtained >= (float) $examSubject->pass_marks);

                // Tier reference
                $eval = $this->gradingService->evaluateSubjectMark($examSubject, $marksObtained, $exam->gradingScale);

                $markRecord = ExamMark::updateOrCreate(
                    [
                        'exam_subject_id' => $examSubject->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'school_id' => $schoolId,
                        'exam_id' => $exam->id,
                        'academic_class_id' => $validated['academic_class_id'],
                        'section_id' => $validated['section_id'],
                        'attendance_status' => $status,
                        'marks_obtained' => $marksObtained,
                        'is_passed' => $isPassed,
                        'grade_tier_id' => $eval['tier']?->id,
                        'remarks' => $studentMarkData['remarks'] ?? null,
                        'entered_by' => $userId,
                        'updated_by' => $userId,
                    ]
                );

                // Save component marks
                if ($status === 'present') {
                    foreach ($components as $compId => $compVal) {
                        if ($compVal !== null && $compVal !== '') {
                            $compObj = $examSubject->components->firstWhere('id', (int) $compId);
                            $passCeiling = $compObj ? (float) $compObj->pass_marks : 0.00;
                            $cVal = (float) $compVal;

                            ExamMarkComponent::updateOrCreate(
                                [
                                    'exam_mark_id' => $markRecord->id,
                                    'exam_subject_component_id' => (int) $compId,
                                ],
                                [
                                    'marks_obtained' => $cVal,
                                    'is_passed' => $cVal >= $passCeiling,
                                ]
                            );
                        }
                    }
                } else {
                    ExamMarkComponent::where('exam_mark_id', $markRecord->id)->update([
                        'marks_obtained' => null,
                        'is_passed' => false,
                    ]);
                }
            }
        });

        ToastMagic::success('Student marks saved successfully.');

        return redirect()->route('admin.exams.marks.entry', [
            'exam_id' => $validated['exam_id'],
            'academic_class_id' => $validated['academic_class_id'],
            'section_id' => $validated['section_id'],
            'subject_id' => $examSubject->subject_id,
        ]);
    }

    /**
     * Submit an audited post-publication mark correction.
     */
    public function correct(Request $request, ExamMark $mark): RedirectResponse
    {
        $validated = $request->validate([
            'new_marks' => ['required', 'numeric', 'min:0'],
            'attendance_status' => ['nullable', 'string', 'in:present,absent,exempt,incomplete'],
            'reason' => ['required', 'string', 'min:5', 'max:500'],
            'components' => ['nullable', 'array'],
        ]);

        $status = $validated['attendance_status'] ?? $mark->attendance_status ?? 'present';

        try {
            $this->correctionService->correctMark(
                $mark,
                (float) $validated['new_marks'],
                $status,
                $validated['reason'],
                auth()->guard('web')->id(),
                $validated['components'] ?? []
            );

            ToastMagic::success('Mark corrected and logged to audit trail. Result rankings recalculated.');
        } catch (\Exception $e) {
            ToastMagic::error($e->getMessage());
        }

        return back();
    }
}
