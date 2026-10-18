<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\ClassSubjectAssignment;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\MarksheetSetting;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Services\ExamResultService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\LaravelPdf\Facades\Pdf;

class ReportCardController extends Controller
{
    public function __construct(
        protected ExamResultService $examResultService
    ) {}

    /**
     * Display listing of students for mark sheet preview and export.
     */
    public function index(Request $request): View
    {
        $exams = Exam::latest('id')->get();
        $academicClasses = AcademicClass::active()->ordered()->get();

        $selectedExamId = $request->input('exam_id') ?? $exams->first()?->id;
        $selectedClassId = $request->input('academic_class_id');
        $selectedSectionId = $request->input('section_id');

        $sections = collect();
        if ($selectedClassId) {
            $sections = Section::where('academic_class_id', $selectedClassId)->active()->get();
        }

        $query = ExamResult::with([
            'student.currentEnrollment',
            'academicClass',
            'section',
            'exam',
        ]);

        if ($selectedExamId) {
            $query->where('exam_id', $selectedExamId);
        }

        if ($selectedClassId) {
            $query->where('academic_class_id', $selectedClassId);
        }

        if ($selectedSectionId) {
            $query->where('section_id', $selectedSectionId);
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('admission_number', 'LIKE', "%{$search}%");
            });
        }

        $results = $query->orderBy('rank_in_class')->paginate(20)->withQueryString();
        $selectedExam = $selectedExamId ? Exam::find($selectedExamId) : null;

        return view('admin.exams.report-cards.index', compact(
            'exams',
            'academicClasses',
            'sections',
            'results',
            'selectedExamId',
            'selectedClassId',
            'selectedSectionId',
            'selectedExam'
        ));
    }

    /**
     * Preview single student mark sheet in browser (print-ready layout).
     */
    public function show(Exam $exam, Student $student): View
    {
        $data = $this->buildMarksheetData($exam, $student);

        return view('admin.exams.report-cards.marksheet', $data);
    }

    /**
     * Stream or download PDF for single student mark sheet using Spatie LaravelPdf.
     */
    public function downloadPdf(Exam $exam, Student $student)
    {
        $data = $this->buildMarksheetData($exam, $student);

        $sid = $student->admission_number ?: $student->id;
        $filename = "marksheet-SID-{$sid}-{$exam->id}.pdf";

        return Pdf::view('admin.exams.report-cards.marksheet-pdf', $data)
            ->format('a4')
            ->margins(0, 0, 0, 0)
            ->name($filename);
    }

    /**
     * Bulk generate PDF for an entire section or class.
     */
    public function bulkPdf(Exam $exam, Request $request)
    {
        $classId = $request->input('academic_class_id');
        $sectionId = $request->input('section_id');

        $query = ExamResult::where('exam_id', $exam->id)
            ->with(['student.currentEnrollment']);

        if ($classId) {
            $query->where('academic_class_id', $classId);
        }
        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }

        $results = $query->orderBy('rank_in_class')->get();

        if ($results->isEmpty()) {
            ToastMagic::error('No calculated results found for the selected cohort.');

            return back();
        }

        $studentsData = [];
        foreach ($results as $res) {
            $studentsData[] = $this->buildMarksheetData($exam, $res->student);
        }

        $filename = "bulk-marksheets-exam-{$exam->id}.pdf";

        return Pdf::view('admin.exams.report-cards.bulk-pdf', [
            'studentsData' => $studentsData,
            'exam' => $exam,
            'setting' => $studentsData[0]['setting'] ?? null,
        ])
            ->format('a4')
            ->margins(0, 0, 0, 0)
            ->name($filename);
    }

    /**
     * Helper to prepare all view variables for student mark sheet.
     */
    protected function buildMarksheetData(Exam $exam, Student $student): array
    {
        $exam->loadMissing(['academicYear', 'gradingScale.gradeTiers']);
        $school = $exam->school ?? School::find($exam->school_id) ?? School::first();

        $enrollment = $student->currentEnrollment;
        $classId = $enrollment?->academic_class_id;

        // Fetch student's ExamResult
        $result = ExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        // Exam subjects for this class
        $examSubjects = ExamSubject::where('exam_id', $exam->id)
            ->where('academic_class_id', $classId)
            ->with(['subject', 'components'])
            ->orderBy('order_index')
            ->get();

        // Student's marks
        $marks = ExamMark::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->with(['components.examSubjectComponent', 'gradeTier'])
            ->get()
            ->keyBy('exam_subject_id');

        // Highest mark in exam per subject for this class
        $highestMarks = $classId ? $this->examResultService->getSubjectHighestMarks($exam, $classId) : [];

        // Highest overall total marks in class
        $highestTotalInClass = $classId ? ExamResult::where('exam_id', $exam->id)
            ->where('academic_class_id', $classId)
            ->max('total_marks_obtained') : 0;

        // Marksheet setting
        $setting = MarksheetSetting::firstOrCreate(
            ['school_id' => $school->id],
            [
                'headmaster_title' => 'Head Master',
                'show_highest_marks' => true,
                'show_attendance_report' => true,
                'show_extra_activities' => true,
                'show_gpa_grading_scale' => true,
                'padding_top' => '10mm',
                'padding_right' => '12mm',
                'padding_bottom' => '10mm',
                'padding_left' => '12mm',
            ]
        );

        $bgDataUri = null;
        if ($setting->background_image_path && file_exists(public_path($setting->background_image_path))) {
            $path = public_path($setting->background_image_path);
            $mime = mime_content_type($path) ?: 'image/jpeg';
            $bgDataUri = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
        }

        $logoDataUri = null;
        if ($setting->school_logo_path && file_exists(public_path($setting->school_logo_path))) {
            $path = public_path($setting->school_logo_path);
            $mime = mime_content_type($path) ?: 'image/png';
            $logoDataUri = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
        }

        $sigDataUri = null;
        if ($setting->principal_signature_path && file_exists(public_path($setting->principal_signature_path))) {
            $path = public_path($setting->principal_signature_path);
            $mime = mime_content_type($path) ?: 'image/png';
            $sigDataUri = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
        }

        $studentPhotoDataUri = null;
        if ($student->photo_path && file_exists(public_path($student->photo_path))) {
            $path = public_path($student->photo_path);
            $mime = mime_content_type($path) ?: 'image/jpeg';
            $studentPhotoDataUri = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
        }

        // Resolve student's optional subject ID (from student enrollment or class optional assignment)
        $optionalSubjectId = (int) ($enrollment?->optional_subject_id ?? 0);
        if (! $optionalSubjectId && $classId) {
            $classOpt = ClassSubjectAssignment::where('academic_class_id', $classId)
                ->where('type', 'optional')
                ->value('subject_id');
            if ($classOpt) {
                $optionalSubjectId = (int) $classOpt;
            }
        }

        return [
            'exam' => $exam,
            'student' => $student,
            'enrollment' => $enrollment,
            'result' => $result,
            'examSubjects' => $examSubjects,
            'marks' => $marks,
            'highestMarks' => $highestMarks,
            'highestTotalInClass' => $highestTotalInClass,
            'setting' => $setting,
            'school' => $school,
            'bgDataUri' => $bgDataUri,
            'logoDataUri' => $logoDataUri,
            'sigDataUri' => $sigDataUri,
            'studentPhotoDataUri' => $studentPhotoDataUri,
            'optionalSubjectId' => $optionalSubjectId,
        ];
    }
}
