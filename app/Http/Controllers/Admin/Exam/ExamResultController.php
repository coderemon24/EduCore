<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Section;
use App\Services\ExamResultService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamResultController extends Controller
{
    public function __construct(
        protected ExamResultService $examResultService
    ) {}

    /**
     * Display consolidated results tabulation sheet with merit ranks.
     */
    public function index(Request $request): View
    {
        $exams = Exam::latest('id')->get();
        $academicClasses = AcademicClass::active()->ordered()->get();

        $selectedExamId = $request->input('exam_id') ?? $exams->first()?->id;
        $selectedClassId = $request->input('academic_class_id');
        $selectedSectionId = $request->input('section_id');
        $selectedStatus = $request->input('result_status');

        $sections = collect();
        if ($selectedClassId) {
            $sections = Section::where('academic_class_id', $selectedClassId)->active()->get();
        }

        $query = ExamResult::with([
            'student.currentEnrollment',
            'academicClass',
            'section',
            'exam.gradingScale',
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

        if ($selectedStatus) {
            $query->where('result_status', $selectedStatus);
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('admission_number', 'LIKE', "%{$search}%");
            });
        }

        $results = $query->orderBy('rank_in_class')
            ->orderBy('rank_in_section')
            ->paginate(20)
            ->withQueryString();

        $selectedExam = $selectedExamId ? Exam::find($selectedExamId) : null;

        return view('admin.exams.results.index', compact(
            'exams',
            'academicClasses',
            'sections',
            'results',
            'selectedExamId',
            'selectedClassId',
            'selectedSectionId',
            'selectedStatus',
            'selectedExam'
        ));
    }

    /**
     * Execute batch result computation and rank generation.
     */
    public function calculate(Request $request, Exam $exam): RedirectResponse
    {
        $classId = $request->input('academic_class_id') ? (int) $request->input('academic_class_id') : null;
        $sectionId = $request->input('section_id') ? (int) $request->input('section_id') : null;

        $summary = $this->examResultService->calculateExamResults($exam, $classId, $sectionId);

        ToastMagic::success(
            "Calculated results for {$summary['calculated_count']} students ({$summary['passed_count']} passed, {$summary['failed_count']} failed). Merit ranks updated."
        );

        return redirect()->route('admin.exams.results.index', [
            'exam_id' => $exam->id,
            'academic_class_id' => $classId,
            'section_id' => $sectionId,
        ]);
    }

    /**
     * Publish the exam results and lock marks against edits.
     */
    public function publish(Exam $exam): RedirectResponse
    {
        $exam->update([
            'status' => 'published',
            'is_published' => true,
            'published_at' => now(),
            'published_by' => auth()->id(),
        ]);

        ExamResult::where('exam_id', $exam->id)->update([
            'status' => 'published',
            'published_at' => now(),
            'published_by' => auth()->id(),
        ]);

        ToastMagic::success('Examination results officially published. Student report cards are now unlocked for distribution.');

        return back();
    }

    /**
     * Unpublish the exam results and revert to unlocked state.
     */
    public function unpublish(Exam $exam): RedirectResponse
    {
        $exam->update([
            'status' => 'results_calculated',
            'is_published' => false,
            'published_at' => null,
            'published_by' => null,
        ]);

        ExamResult::where('exam_id', $exam->id)->update([
            'status' => 'approved',
            'published_at' => null,
            'published_by' => null,
        ]);

        ToastMagic::success('Examination results unpublished. Routine marks entry is now unlocked.');

        return back();
    }
}
