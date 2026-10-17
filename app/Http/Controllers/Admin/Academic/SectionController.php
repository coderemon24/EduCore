<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Academic\SectionRequest;
use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\Staff;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SectionController extends Controller
{
    /**
     * Display a listing of class sections.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $classFilter = $request->query('class_id', 'all');
        $search = trim((string) $request->query('search', ''));

        if ($statusFilter === 'trashed') {
            $query = Section::onlyTrashed()
                ->with([
                    'academicClass' => fn ($q) => $q->withTrashed()->select(['id', 'name']),
                    'classTeacher' => fn ($q) => $q->select(['id', 'first_name', 'last_name', 'staff_code']),
                ])
                ->latest('deleted_at');
        } else {
            $query = Section::query()
                ->with([
                    'academicClass' => fn ($q) => $q->withTrashed()->select(['id', 'name']),
                    'classTeacher' => fn ($q) => $q->select(['id', 'first_name', 'last_name', 'staff_code']),
                ]);

            if ($statusFilter !== 'all' && in_array($statusFilter, ['active', 'inactive'], true)) {
                $query->where('status', $statusFilter);
            }
        }

        if ($classFilter !== 'all' && is_numeric($classFilter)) {
            $query->where('academic_class_id', (int) $classFilter);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('room_number', 'like', "%{$search}%")
                    ->orWhereHas('academicClass', fn ($cq) => $cq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('classTeacher', fn ($tq) => $tq->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%"));
            });
        }

        $sections = $query
            ->select(['id', 'school_id', 'academic_class_id', 'class_teacher_id', 'name', 'capacity', 'room_number', 'status', 'created_at', 'deleted_at'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $classes = AcademicClass::ordered()->select(['id', 'name'])->get();
        $teachers = Staff::teaching()->active()->select(['id', 'first_name', 'last_name', 'staff_code'])->get();

        $rawCounts = Section::query()
            ->withTrashed()
            ->selectRaw("
                COUNT(CASE WHEN deleted_at IS NULL THEN 1 END) as all_count,
                COUNT(CASE WHEN deleted_at IS NULL AND status = 'active' THEN 1 END) as active_count,
                COUNT(CASE WHEN deleted_at IS NULL AND status = 'inactive' THEN 1 END) as inactive_count,
                COUNT(CASE WHEN deleted_at IS NOT NULL THEN 1 END) as trashed_count
            ")
            ->first();

        $statusCounts = [
            'all' => (int) ($rawCounts->all_count ?? 0),
            'active' => (int) ($rawCounts->active_count ?? 0),
            'inactive' => (int) ($rawCounts->inactive_count ?? 0),
            'trashed' => (int) ($rawCounts->trashed_count ?? 0),
        ];

        return view('admin.academic.sections.index', compact('sections', 'classes', 'teachers', 'statusFilter', 'classFilter', 'search', 'statusCounts'));
    }

    /**
     * Show the form for creating a new section.
     */
    public function create(Request $request): View
    {
        $classes = AcademicClass::ordered()->select(['id', 'name'])->get();
        $teachers = Staff::teaching()->active()->select(['id', 'first_name', 'last_name', 'staff_code'])->get();
        $defaultClassId = $request->query('class_id');

        $section = new Section([
            'status' => 'active',
            'capacity' => 40,
            'academic_class_id' => $defaultClassId,
        ]);

        return view('admin.academic.sections.create', compact('section', 'classes', 'teachers'));
    }

    /**
     * Store a newly created section in storage.
     */
    public function store(SectionRequest $request): RedirectResponse
    {
        $section = Section::create($request->validated());

        ToastMagic::success("Section '{$section->name}' created successfully.");

        return redirect()->route('admin.academic.sections.index', ['class_id' => $section->academic_class_id])
            ->with('success', "Section '{$section->name}' created successfully.");
    }

    /**
     * Show the form for editing the specified section.
     */
    public function edit(Section $section): View
    {
        $classes = AcademicClass::ordered()->select(['id', 'name'])->get();
        $teachers = Staff::teaching()->active()->select(['id', 'first_name', 'last_name', 'staff_code'])->get();

        return view('admin.academic.sections.edit', compact('section', 'classes', 'teachers'));
    }

    /**
     * Update the specified section in storage.
     */
    public function update(SectionRequest $request, Section $section): RedirectResponse
    {
        $section->update($request->validated());

        ToastMagic::success("Section '{$section->name}' updated successfully.");

        return redirect()->route('admin.academic.sections.index', ['class_id' => $section->academic_class_id])
            ->with('success', "Section '{$section->name}' updated successfully.");
    }

    /**
     * Remove (soft delete / archive) the specified section from storage.
     */
    public function destroy(Request $request, Section $section): JsonResponse|RedirectResponse
    {
        $name = $section->name;
        $classId = $section->academic_class_id;
        $section->delete();

        $message = "Section '{$name}' moved to archive (soft deleted).";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.academic.sections.index', ['class_id' => $classId])
            ->with('success', $message);
    }

    /**
     * Restore a soft-deleted section.
     */
    public function restore(Request $request, Section $section): JsonResponse|RedirectResponse
    {
        $section->restore();

        $message = "Section '{$section->name}' was restored successfully.";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->back(fallback: route('admin.academic.sections.index'))
            ->with('success', $message);
    }

    /**
     * Permanently remove (force delete) the specified section.
     */
    public function forceDelete(Request $request, Section $section): JsonResponse|RedirectResponse
    {
        $name = $section->name;
        $section->forceDelete();

        $message = "Section '{$name}' has been permanently purged.";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.academic.sections.index', ['status' => 'trashed'])
            ->with('success', $message);
    }
}
