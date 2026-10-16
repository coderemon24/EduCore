<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Student\BulkPromoteStudentsRequest;
use App\Http\Requests\Admin\Student\PromoteStudentRequest;
use App\Models\AcademicClass;
use App\Models\AcademicGroup;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Services\StudentAdmissionService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentPromotionController extends Controller
{
    public function __construct(
        protected StudentAdmissionService $admissionService
    ) {}

    /**
     * Show the individual student promotion / transfer interface.
     */
    public function promote(Student $student): View
    {
        $student->load(['currentEnrollment.academicYear', 'currentEnrollment.academicClass', 'currentEnrollment.section']);

        $academicYears = AcademicYear::orderByDesc('is_current')->orderByDesc('start_date')->get();
        $academicClasses = AcademicClass::ordered()->with('sections')->get();
        $academicGroups = AcademicGroup::orderBy('name')->get();

        return view('admin.students.promote', compact(
            'student',
            'academicYears',
            'academicClasses',
            'academicGroups'
        ));
    }

    /**
     * Process individual student promotion / class transfer.
     */
    public function processPromote(PromoteStudentRequest $request, Student $student): RedirectResponse
    {
        $this->admissionService->promoteStudent($student, $request->validated());

        $action = $request->input('action_type') === 'transfer' ? 'transferred' : 'promoted';
        ToastMagic::success("Student {$student->full_name} {$action} successfully.");

        return redirect()->route('admin.students.show', $student)
            ->with('success', "Student {$student->full_name} {$action} successfully.");
    }

    /**
     * Show the bulk class promotion interface.
     */
    public function bulkIndex(): View
    {
        $academicYears = AcademicYear::orderByDesc('is_current')->orderByDesc('start_date')->get();
        $academicClasses = AcademicClass::ordered()->with('sections')->get();

        return view('admin.students.promotions.index', compact(
            'academicYears',
            'academicClasses'
        ));
    }

    /**
     * AJAX endpoint to fetch student enrollments for a source class and section.
     */
    public function getRoster(Request $request): JsonResponse
    {
        $request->validate([
            'academic_year_id' => ['required', 'integer'],
            'academic_class_id' => ['required', 'integer'],
            'section_id' => ['nullable', 'integer'],
        ]);

        $query = StudentEnrollment::query()
            ->with('student')
            ->where('academic_year_id', $request->academic_year_id)
            ->where('academic_class_id', $request->academic_class_id)
            ->where('status', 'current');

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        $enrollments = $query->orderBy('roll_number')->get();

        $roster = $enrollments->map(function ($enrollment) {
            $student = $enrollment->student;

            return [
                'id' => $student->id,
                'admission_number' => $student->admission_number,
                'name' => $student->full_name,
                'gender' => ucfirst($student->gender),
                'roll_number' => $enrollment->roll_number ?? '—',
                'status' => ucfirst($student->status),
            ];
        });

        return response()->json($roster);
    }

    /**
     * Process bulk promotion of selected students.
     */
    public function processBulk(BulkPromoteStudentsRequest $request): RedirectResponse
    {
        $promotedCount = $this->admissionService->bulkPromote(
            $request->student_ids,
            $request->validated()
        );

        ToastMagic::success("Successfully promoted {$promotedCount} students to the next session.");

        return redirect()->route('admin.students.index')
            ->with('success', "Successfully promoted {$promotedCount} students.");
    }
}
