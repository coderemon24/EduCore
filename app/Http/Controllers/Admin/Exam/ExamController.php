<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\GradingScale;
use App\Models\School;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    /**
     * Display a listing of examination sessions.
     */
    public function index(Request $request): View
    {
        $query = Exam::with(['academicYear', 'gradingScale'])
            ->withCount(['examSubjects', 'examMarks', 'examResults'])
            ->latest('id');

        if ($search = trim((string) $request->input('search'))) {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->input('academic_year_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $exams = $query->paginate(15)->withQueryString();
        $academicYears = AcademicYear::latest('id')->get();

        return view('admin.exams.index', compact('exams', 'academicYears'));
    }

    /**
     * Show the form for creating a new exam.
     */
    public function create(): View
    {
        $academicYears = AcademicYear::latest('id')->get();
        $gradingScales = GradingScale::active()->get();

        return view('admin.exams.create', compact('academicYears', 'gradingScales'));
    }

    /**
     * Store a newly created exam in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'grading_scale_id' => ['nullable', 'exists:grading_scales,id'],
            'title' => ['required', 'string', 'max:150'],
            'exam_type' => ['required', 'string', 'in:terminal,half_yearly,final,class_test,model_test'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'string', 'in:draft,scheduled,ongoing,marks_entry'],
        ]);

        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (auth()->guard('web')->user()?->school_id ?? session('current_school_id') ?? School::value('id') ?? 1);

        // Enforce uniqueness of title within the academic year
        $exists = Exam::where('school_id', $schoolId)
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('title', $validated['title'])
            ->exists();

        if ($exists) {
            ToastMagic::error('An exam session with this title already exists in the selected academic year.');

            return back()->withInput();
        }

        $exam = Exam::create([
            'school_id' => $schoolId,
            'academic_year_id' => $validated['academic_year_id'],
            'grading_scale_id' => $validated['grading_scale_id'] ?? GradingScale::where('school_id', $schoolId)->where('is_default', true)->value('id'),
            'title' => $validated['title'],
            'exam_type' => $validated['exam_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'draft',
        ]);

        ToastMagic::success('Exam session created. You may now configure subjects and assessment components.');

        return redirect()->route('admin.exams.subjects.index', $exam);
    }

    /**
     * Display the specified exam session (redirects to subject configuration).
     */
    public function show(Exam $exam): RedirectResponse
    {
        return redirect()->route('admin.exams.subjects.index', $exam);
    }

    /**
     * Show the form for editing the specified exam.
     */
    public function edit(Exam $exam): View
    {
        $academicYears = AcademicYear::latest('id')->get();
        $gradingScales = GradingScale::active()->get();

        return view('admin.exams.edit', compact('exam', 'academicYears', 'gradingScales'));
    }

    /**
     * Update the specified exam in storage.
     */
    public function update(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'grading_scale_id' => ['nullable', 'exists:grading_scales,id'],
            'title' => ['required', 'string', 'max:150'],
            'exam_type' => ['required', 'string', 'in:terminal,half_yearly,final,class_test,model_test'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'string', 'in:draft,scheduled,ongoing,marks_entry,results_calculated,published,archived'],
        ]);

        $exam->update($validated);

        ToastMagic::success('Exam session details updated successfully.');

        return redirect()->route('admin.exams.index');
    }

    /**
     * Quick status transition endpoint.
     */
    public function updateStatus(Request $request, Exam $exam): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'string', 'in:draft,scheduled,ongoing,marks_entry,results_calculated,published,archived'],
        ]);

        $newStatus = $request->input('status');

        if ($newStatus === 'published') {
            $exam->update([
                'status' => 'published',
                'is_published' => true,
                'published_at' => now(),
                'published_by' => auth()->id(),
            ]);
            ToastMagic::success('Exam officially published. Marks entry is now locked against routine changes.');
        } else {
            $exam->update([
                'status' => $newStatus,
                'is_published' => false,
            ]);
            ToastMagic::success("Exam status updated to '{$newStatus}'.");
        }

        return back();
    }

    /**
     * Remove the specified exam from storage.
     */
    public function destroy(Exam $exam): RedirectResponse
    {
        if ($exam->is_published) {
            ToastMagic::error('Cannot delete an officially published examination.');

            return back();
        }

        $exam->delete();

        ToastMagic::success('Exam session removed successfully.');

        return redirect()->route('admin.exams.index');
    }
}
