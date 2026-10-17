<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Academic\ClassSubjectAssignmentRequest;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\ClassSubjectAssignment;
use App\Models\Staff;
use App\Models\Subject;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
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
        $statusFilter = $request->query('status', 'all');
        $classFilter = $request->query('class_id', 'all');
        $yearFilter = $request->query('year_id', 'all');
        $search = trim((string) $request->query('search', ''));

        if ($statusFilter === 'trashed') {
            $query = ClassSubjectAssignment::onlyTrashed()
                ->with([
                    'academicClass' => fn ($q) => $q->withTrashed()->select(['id', 'name', 'numeric_code']),
                    'subject' => fn ($q) => $q->withTrashed()->select(['id', 'name', 'code', 'type']),
                    'academicYear' => fn ($q) => $q->withTrashed()->select(['id', 'name']),
                    'teacher' => fn ($q) => $q->withTrashed()->select(['id', 'first_name', 'last_name', 'staff_code']),
                ])
                ->latest('deleted_at');
        } else {
            $query = ClassSubjectAssignment::query()
                ->with([
                    'academicClass' => fn ($q) => $q->withTrashed()->select(['id', 'name', 'numeric_code']),
                    'subject' => fn ($q) => $q->withTrashed()->select(['id', 'name', 'code', 'type']),
                    'academicYear' => fn ($q) => $q->withTrashed()->select(['id', 'name']),
                    'teacher' => fn ($q) => $q->withTrashed()->select(['id', 'first_name', 'last_name', 'staff_code']),
                ]);

            if ($statusFilter !== 'all' && in_array($statusFilter, ['compulsory', 'optional', 'elective'], true)) {
                $query->where('type', $statusFilter);
            }
        }

        if ($classFilter !== 'all' && is_numeric($classFilter)) {
            $query->where('academic_class_id', (int) $classFilter);
        }

        if ($yearFilter !== 'all' && is_numeric($yearFilter)) {
            $query->where('academic_year_id', (int) $yearFilter);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('subject', fn ($sq) => $sq->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"))
                    ->orWhereHas('academicClass', fn ($cq) => $cq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('teacher', fn ($tq) => $tq->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
            });
        }

        $assignments = $query
            ->select(['id', 'school_id', 'academic_class_id', 'subject_id', 'academic_year_id', 'teacher_id', 'type', 'full_marks', 'pass_marks', 'created_at', 'deleted_at'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $classes = AcademicClass::ordered()->select(['id', 'name'])->get();
        $academicYears = AcademicYear::latest('start_date')->select(['id', 'name', 'is_current'])->get();
        $teachers = Staff::teaching()->active()->select(['id', 'first_name', 'last_name', 'staff_code'])->get();

        $rawCounts = ClassSubjectAssignment::query()
            ->withTrashed()
            ->selectRaw("
                COUNT(CASE WHEN deleted_at IS NULL THEN 1 END) as all_count,
                COUNT(CASE WHEN deleted_at IS NULL AND type = 'compulsory' THEN 1 END) as compulsory_count,
                COUNT(CASE WHEN deleted_at IS NULL AND type = 'optional' THEN 1 END) as optional_count,
                COUNT(CASE WHEN deleted_at IS NULL AND type = 'elective' THEN 1 END) as elective_count,
                COUNT(CASE WHEN deleted_at IS NOT NULL THEN 1 END) as trashed_count
            ")
            ->first();

        $statusCounts = [
            'all' => (int) ($rawCounts->all_count ?? 0),
            'compulsory' => (int) ($rawCounts->compulsory_count ?? 0),
            'optional' => (int) ($rawCounts->optional_count ?? 0),
            'elective' => (int) ($rawCounts->elective_count ?? 0),
            'trashed' => (int) ($rawCounts->trashed_count ?? 0),
        ];

        return view('admin.academic.assign-subjects.index', compact('assignments', 'classes', 'academicYears', 'teachers', 'classFilter', 'yearFilter', 'search', 'statusFilter', 'statusCounts'));
    }

    /**
     * Show the form for assigning subjects to a class.
     */
    public function create(Request $request): View
    {
        $classes = AcademicClass::ordered()->select(['id', 'name'])->get();
        $subjects = Subject::active()->orderBy('name')->select(['id', 'name', 'code', 'type'])->get();
        $academicYears = AcademicYear::latest('start_date')->select(['id', 'name', 'is_current'])->get();
        $teachers = Staff::teaching()->active()->select(['id', 'first_name', 'last_name', 'staff_code'])->get();
        $activeYear = $academicYears->firstWhere('is_current', true) ?? $academicYears->first();

        $defaultClassId = $request->query('class_id');

        return view('admin.academic.assign-subjects.create', compact('classes', 'subjects', 'academicYears', 'teachers', 'activeYear', 'defaultClassId'));
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
        $teacherId = $validated['teacher_id'] ?? null;
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
                    'teacher_id' => $teacherId,
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
        $assignment->load(['academicClass:id,name', 'subject:id,name,code', 'academicYear:id,name', 'teacher']);
        $teachers = Staff::teaching()->active()->select(['id', 'first_name', 'last_name', 'staff_code'])->get();

        return view('admin.academic.assign-subjects.edit', compact('assignment', 'teachers'));
    }

    /**
     * Update an individual subject assignment in storage.
     */
    public function update(Request $request, ClassSubjectAssignment $assignment): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id' => ['nullable', 'integer', 'exists:staff,id'],
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
     * Remove (soft delete / archive) the specified subject assignment from a class.
     */
    public function destroy(Request $request, ClassSubjectAssignment $assignment): JsonResponse|RedirectResponse
    {
        $classId = $assignment->academic_class_id;
        $assignment->delete();

        $message = 'Curriculum subject assignment moved to archive (soft deleted).';
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.academic.assign-subjects.index', ['class_id' => $classId])
            ->with('success', $message);
    }

    /**
     * Restore a soft-deleted curriculum assignment.
     */
    public function restore(Request $request, ClassSubjectAssignment $assignment): JsonResponse|RedirectResponse
    {
        $assignment->restore();

        $message = 'Curriculum subject assignment was restored successfully.';
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->back(fallback: route('admin.academic.assign-subjects.index'))
            ->with('success', $message);
    }

    /**
     * Permanently remove (force delete) the specified curriculum assignment.
     */
    public function forceDelete(Request $request, ClassSubjectAssignment $assignment): JsonResponse|RedirectResponse
    {
        $assignment->forceDelete();

        $message = 'Curriculum subject assignment has been permanently purged.';
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.academic.assign-subjects.index', ['status' => 'trashed'])
            ->with('success', $message);
    }
}
