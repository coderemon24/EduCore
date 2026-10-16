<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Academic\ClassSubjectAssignmentRequest;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\ClassSubjectAssignment;
use App\Models\Subject;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassSubjectAssignmentController extends Controller
{
    /**
     * Display a listing of class-subject curriculum assignments.
     */
    public function index(Request $request): View
    {
        $classFilter = $request->query('class_id', 'all');
        $yearFilter = $request->query('year_id', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = ClassSubjectAssignment::query()
            ->with(['academicClass:id,name,numeric_code', 'subject:id,name,code,type', 'academicYear:id,name']);

        if ($classFilter !== 'all' && is_numeric($classFilter)) {
            $query->where('academic_class_id', (int) $classFilter);
        }

        if ($yearFilter !== 'all' && is_numeric($yearFilter)) {
            $query->where('academic_year_id', (int) $yearFilter);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('subject', fn ($sq) => $sq->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"))
                    ->orWhereHas('academicClass', fn ($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        $assignments = $query
            ->select(['id', 'school_id', 'academic_class_id', 'subject_id', 'academic_year_id', 'type', 'full_marks', 'pass_marks', 'created_at'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $classes = AcademicClass::ordered()->select(['id', 'name'])->get();
        $academicYears = AcademicYear::latest('start_date')->select(['id', 'name', 'is_current'])->get();

        return view('admin.academic.assign-subjects.index', compact('assignments', 'classes', 'academicYears', 'classFilter', 'yearFilter', 'search'));
    }

    /**
     * Show the form for assigning subjects to a class.
     */
    public function create(Request $request): View
    {
        $classes = AcademicClass::ordered()->select(['id', 'name'])->get();
        $subjects = Subject::active()->orderBy('name')->select(['id', 'name', 'code', 'type'])->get();
        $academicYears = AcademicYear::latest('start_date')->select(['id', 'name', 'is_current'])->get();
        $activeYear = $academicYears->firstWhere('is_current', true) ?? $academicYears->first();

        $defaultClassId = $request->query('class_id');

        return view('admin.academic.assign-subjects.create', compact('classes', 'subjects', 'academicYears', 'activeYear', 'defaultClassId'));
    }

    /**
     * Store curriculum subject assignments for a class.
     */
    public function store(ClassSubjectAssignmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $classId = $validated['academic_class_id'];
        $subjectIds = $validated['subject_ids'];
        $yearId = $validated['academic_year_id'] ?? null;
        $type = $validated['type'];
        $fullMarks = $validated['full_marks'];
        $passMarks = $validated['pass_marks'];

        $count = 0;
        foreach ($subjectIds as $subjectId) {
            ClassSubjectAssignment::updateOrCreate(
                [
                    'academic_class_id' => $classId,
                    'subject_id' => $subjectId,
                    'academic_year_id' => $yearId,
                ],
                [
                    'type' => $type,
                    'full_marks' => $fullMarks,
                    'pass_marks' => $passMarks,
                ]
            );
            $count++;
        }

        $className = AcademicClass::find($classId)?->name ?? 'Class';
        ToastMagic::success("{$count} subject(s) assigned to {$className} successfully.");

        return redirect()->route('admin.academic.assign-subjects.index', ['class_id' => $classId])
            ->with('success', "{$count} subject(s) assigned to {$className} successfully.");
    }

    /**
     * Show the form for editing an individual subject assignment.
     */
    public function edit(ClassSubjectAssignment $assignment): View
    {
        $assignment->load(['academicClass:id,name', 'subject:id,name,code', 'academicYear:id,name']);

        return view('admin.academic.assign-subjects.edit', compact('assignment'));
    }

    /**
     * Update an individual subject assignment in storage.
     */
    public function update(Request $request, ClassSubjectAssignment $assignment): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:compulsory,optional,elective'],
            'full_marks' => ['required', 'integer', 'min:1', 'max:500'],
            'pass_marks' => ['required', 'integer', 'min:0', 'lte:full_marks'],
        ]);

        $assignment->update($validated);

        ToastMagic::success('Curriculum assignment updated successfully.');

        return redirect()->route('admin.academic.assign-subjects.index', ['class_id' => $assignment->academic_class_id])
            ->with('success', 'Curriculum assignment updated successfully.');
    }

    /**
     * Remove the specified subject assignment from a class.
     */
    public function destroy(ClassSubjectAssignment $assignment): RedirectResponse
    {
        $classId = $assignment->academic_class_id;
        $assignment->delete();

        ToastMagic::success('Subject assignment removed from class successfully.');

        return redirect()->route('admin.academic.assign-subjects.index', ['class_id' => $classId])
            ->with('success', 'Subject assignment removed from class successfully.');
    }
}
