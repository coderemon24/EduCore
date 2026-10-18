<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Student\StoreStudentRequest;
use App\Http\Requests\Admin\Student\UpdateStudentRequest;
use App\Models\AcademicClass;
use App\Models\AcademicGroup;
use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use App\Services\StudentAdmissionService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(
        protected StudentAdmissionService $admissionService
    ) {}

    /**
     * Display a listing of students with search, filters, and pagination.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->input('status', 'all');

        if ($statusFilter === 'trashed') {
            $query = Student::onlyTrashed()
                ->with([
                    'currentEnrollment.academicClass',
                    'currentEnrollment.section',
                    'guardians',
                ])
                ->latest('deleted_at');
        } else {
            $query = Student::query()
                ->with([
                    'currentEnrollment.academicClass',
                    'currentEnrollment.section',
                    'guardians',
                ]);

            if ($statusFilter && $statusFilter !== 'all' && in_array($statusFilter, ['active', 'inactive', 'promoted', 'transferred', 'graduated'])) {
                $query->where('status', $statusFilter);
            }
        }

        // Keyword Search
        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('admission_number', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhereHas('currentEnrollment', function ($eq) use ($search) {
                        $eq->where('roll_number', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('guardians', function ($gq) use ($search) {
                        $gq->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Faceted Filters
        if ($gender = $request->input('gender')) {
            $query->where('gender', $gender);
        }

        if ($yearId = $request->input('academic_year_id')) {
            $query->whereHas('currentEnrollment', fn ($q) => $q->where('academic_year_id', $yearId));
        }

        if ($classId = $request->input('academic_class_id')) {
            $query->whereHas('currentEnrollment', fn ($q) => $q->where('academic_class_id', $classId));
        }

        if ($sectionId = $request->input('section_id')) {
            $query->whereHas('currentEnrollment', fn ($q) => $q->where('section_id', $sectionId));
        }

        $students = $query->orderByDesc('id')->paginate(15)->appends($request->query());

        // Quick Telemetry & Status Counts
        $rawCounts = Student::query()
            ->withTrashed()
            ->selectRaw("
                COUNT(*) as all_count,
                COUNT(CASE WHEN status = 'active' AND deleted_at IS NULL THEN 1 END) as active_count,
                COUNT(CASE WHEN status = 'inactive' AND deleted_at IS NULL THEN 1 END) as inactive_count,
                COUNT(CASE WHEN deleted_at IS NOT NULL THEN 1 END) as trashed_count
            ")
            ->first();

        $statusCounts = [
            'all' => (int) ($rawCounts->all_count ?? 0) - (int) ($rawCounts->trashed_count ?? 0),
            'active' => (int) ($rawCounts->active_count ?? 0),
            'inactive' => (int) ($rawCounts->inactive_count ?? 0),
            'trashed' => (int) ($rawCounts->trashed_count ?? 0),
        ];

        $stats = [
            'total' => $statusCounts['all'],
            'active' => $statusCounts['active'],
            'male' => Student::where('gender', 'male')->count(),
            'female' => Student::where('gender', 'female')->count(),
            'trashed' => $statusCounts['trashed'],
        ];

        $academicYears = AcademicYear::orderByDesc('is_current')->orderByDesc('start_date')->get();
        $academicClasses = AcademicClass::ordered()->with('sections')->get();
        $sections = $request->filled('academic_class_id')
            ? Section::where('academic_class_id', $request->academic_class_id)->get()
            : collect();
        $subjects = Subject::active()->orderBy('name')->get();

        return view('admin.students.index', compact(
            'students',
            'stats',
            'statusCounts',
            'statusFilter',
            'academicYears',
            'academicClasses',
            'sections',
            'subjects'
        ));
    }

    /**
     * Show the form for creating / admitting a new student.
     */
    public function create(): View
    {
        $suggestedAdmissionNumber = $this->admissionService->generateNextAdmissionNumber();
        $academicYears = AcademicYear::orderByDesc('is_current')->orderByDesc('start_date')->get();
        $activeYear = $academicYears->firstWhere('is_current', true) ?? $academicYears->first();
        $academicClasses = AcademicClass::ordered()->with('sections')->get();
        $academicGroups = AcademicGroup::orderBy('name')->get();
        $subjects = Subject::active()->orderBy('name')->get();

        return view('admin.students.create', compact(
            'suggestedAdmissionNumber',
            'academicYears',
            'activeYear',
            'academicClasses',
            'academicGroups',
            'subjects'
        ));
    }

    /**
     * Store a newly admitted student in storage.
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $student = $this->admissionService->admitStudent(
            $request->validated(),
            $request->file('photo')
        );

        ToastMagic::success("Student {$student->full_name} admitted successfully with Admission No: {$student->admission_number}.");

        return redirect()->route('admin.students.show', $student)
            ->with('success', "Student {$student->full_name} admitted successfully.");
    }

    /**
     * Display the specified student profile and enrollment timeline.
     */
    public function show(Student $student): View
    {
        $student->load([
            'enrollments' => fn ($q) => $q->withTrashed()->with(['academicYear', 'academicClass', 'section', 'academicGroup', 'optionalSubject']),
            'guardians',
        ]);

        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the student's personal and guardian details.
     */
    public function edit(Student $student): View
    {
        $student->load(['guardians', 'currentEnrollment.optionalSubject']);
        $academicClasses = AcademicClass::ordered()->with('sections')->get();
        $subjects = Subject::active()->orderBy('name')->get();

        return view('admin.students.edit', compact('student', 'academicClasses', 'subjects'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $this->admissionService->updateStudent(
            $student,
            $request->validated(),
            $request->file('photo')
        );

        ToastMagic::success("Student {$student->full_name} profile updated successfully.");

        return redirect()->route('admin.students.show', $student)
            ->with('success', "Student {$student->full_name} profile updated successfully.");
    }

    /**
     * Soft delete (archive) the specified student and their enrollments.
     */
    public function destroy(Request $request, Student $student): JsonResponse|RedirectResponse
    {
        $name = $student->full_name;

        DB::transaction(function () use ($student) {
            $student->enrollments()->delete();
            $student->delete();
        });

        $message = "Student '{$name}' was moved to trash.";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.students.index')
            ->with('success', $message);
    }

    /**
     * Restore the specified soft-deleted student and their enrollments.
     */
    public function restore(Request $request, Student $student): JsonResponse|RedirectResponse
    {
        $name = $student->full_name;

        DB::transaction(function () use ($student) {
            $student->restore();
            $student->enrollments()->restore();
        });

        $message = "Student '{$name}' was restored successfully.";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->back(fallback: route('admin.students.index', ['status' => 'trashed']))
            ->with('success', $message);
    }

    /**
     * Permanently purge (force delete) the specified student from database and disk.
     */
    public function forceDelete(Request $request, Student $student): JsonResponse|RedirectResponse
    {
        $name = $student->full_name;

        DB::transaction(function () use ($student) {
            // Delete photo file if present on disk
            if ($student->photo && ! str_starts_with($student->photo, 'http')) {
                $photoPath = public_path($student->photo);
                if (File::exists($photoPath)) {
                    File::delete($photoPath);
                }
            }

            // Detach guardians pivot
            $student->guardians()->detach();

            // Force delete enrollments
            $student->enrollments()->withTrashed()->forceDelete();

            // Force delete student record
            $student->forceDelete();
        });

        $message = "Student '{$name}' has been permanently purged.";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.students.index', ['status' => 'trashed'])
            ->with('success', $message);
    }

    /**
     * Process bulk actions on selected students (delete, restore, force_delete).
     */
    public function bulkAction(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,restore,force_delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer',
        ]);

        $action = $validated['action'];
        $ids = $validated['ids'];
        $count = 0;

        DB::transaction(function () use ($action, $ids, &$count) {
            if ($action === 'delete') {
                $students = Student::whereIn('id', $ids)->get();
                foreach ($students as $student) {
                    $student->enrollments()->delete();
                    $student->delete();
                    $count++;
                }
            } elseif ($action === 'restore') {
                $students = Student::onlyTrashed()->whereIn('id', $ids)->get();
                foreach ($students as $student) {
                    $student->restore();
                    $student->enrollments()->restore();
                    $count++;
                }
            } elseif ($action === 'force_delete') {
                $students = Student::onlyTrashed()->whereIn('id', $ids)->get();
                foreach ($students as $student) {
                    if ($student->photo && ! str_starts_with($student->photo, 'http')) {
                        $photoPath = public_path($student->photo);
                        if (File::exists($photoPath)) {
                            File::delete($photoPath);
                        }
                    }
                    $student->guardians()->detach();
                    $student->enrollments()->withTrashed()->forceDelete();
                    $student->forceDelete();
                    $count++;
                }
            }
        });

        $actionLabels = [
            'delete' => 'moved to trash',
            'restore' => 'restored successfully',
            'force_delete' => 'permanently purged',
        ];

        $message = "{$count} student(s) {$actionLabels[$action]}.";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count,
            ]);
        }

        $redirectStatus = ($action === 'restore' || $action === 'force_delete') ? 'trashed' : 'all';

        return redirect()->route('admin.students.index', ['status' => $redirectStatus])
            ->with('success', $message);
    }

    /**
     * Batch assign a 4th / optional subject across a class or section cohort.
     */
    public function batchAssignOptionalSubject(Request $request): RedirectResponse
    {
        $schoolId = app()->has('current_school_id') ? app('current_school_id') : (School::value('id') ?? 1);

        $validated = $request->validate([
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
            'academic_class_id' => ['required', 'integer', 'exists:academic_classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'optional_subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'overwrite_existing' => ['nullable', 'boolean'],
        ]);

        $query = StudentEnrollment::where('school_id', $schoolId)
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('academic_class_id', $validated['academic_class_id'])
            ->where('status', 'current');

        if (! empty($validated['section_id'])) {
            $query->where('section_id', $validated['section_id']);
        }

        if (empty($validated['overwrite_existing'])) {
            $query->whereNull('optional_subject_id');
        }

        $count = $query->update([
            'optional_subject_id' => $validated['optional_subject_id'] ?: null,
        ]);

        $subjectName = $validated['optional_subject_id']
            ? (Subject::find($validated['optional_subject_id'])?->name ?: 'Optional Subject')
            : 'None (Cleared)';

        ToastMagic::success("4th Subject '{$subjectName}' assigned to {$count} student(s) successfully.");

        return back();
    }
}
