<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Academic\AcademicClassRequest;
use App\Models\AcademicClass;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicClassController extends Controller
{
    /**
     * Display a listing of academic classes and grade levels.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));

        if ($statusFilter === 'trashed') {
            $query = AcademicClass::onlyTrashed()->latest('deleted_at');
        } else {
            $query = AcademicClass::query()->ordered();

            if ($statusFilter !== 'all' && in_array($statusFilter, ['active', 'inactive'], true)) {
                $query->where('status', $statusFilter);
            }
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('numeric_code', 'like', "%{$search}%");
            });
        }

        $classes = $query
            ->withCount(['sections', 'subjects'])
            ->with(['sections' => fn ($q) => $q->select(['id', 'academic_class_id', 'name', 'capacity'])])
            ->select(['id', 'school_id', 'name', 'numeric_code', 'order_index', 'status', 'created_at', 'deleted_at'])
            ->paginate(15)
            ->withQueryString();

        $rawCounts = AcademicClass::query()
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

        return view('admin.academic.classes.index', compact('classes', 'statusFilter', 'search', 'statusCounts'));
    }

    /**
     * Show the form for creating a new academic class.
     */
    public function create(): View
    {
        $nextOrder = (AcademicClass::max('order_index') ?? 0) + 1;
        $class = new AcademicClass([
            'status' => 'active',
            'order_index' => $nextOrder,
        ]);

        return view('admin.academic.classes.create', compact('class'));
    }

    /**
     * Store a newly created academic class in storage.
     */
    public function store(AcademicClassRequest $request): RedirectResponse
    {
        $class = AcademicClass::create($request->validated());

        ToastMagic::success("Class '{$class->name}' created successfully.");

        return redirect()->route('admin.academic.classes.index')
            ->with('success', "Class '{$class->name}' created successfully.");
    }

    /**
     * Show the form for editing the specified academic class.
     */
    public function edit(AcademicClass $class): View
    {
        return view('admin.academic.classes.edit', compact('class'));
    }

    /**
     * Update the specified academic class in storage.
     */
    public function update(AcademicClassRequest $request, AcademicClass $class): RedirectResponse
    {
        $class->update($request->validated());

        ToastMagic::success("Class '{$class->name}' updated successfully.");

        return redirect()->route('admin.academic.classes.index')
            ->with('success', "Class '{$class->name}' updated successfully.");
    }

    /**
     * Remove (soft delete / archive) the specified academic class from storage.
     */
    public function destroy(Request $request, AcademicClass $class): JsonResponse|RedirectResponse
    {
        $name = $class->name;
        $class->delete();

        $message = "Class '{$name}' moved to archive (soft deleted).";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.academic.classes.index')
            ->with('success', $message);
    }

    /**
     * Restore a soft-deleted academic class.
     */
    public function restore(Request $request, AcademicClass $class): JsonResponse|RedirectResponse
    {
        $class->restore();

        $message = "Class '{$class->name}' was restored successfully.";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->back(fallback: route('admin.academic.classes.index'))
            ->with('success', $message);
    }

    /**
     * Permanently remove (force delete) the specified academic class.
     */
    public function forceDelete(Request $request, AcademicClass $class): JsonResponse|RedirectResponse
    {
        $name = $class->name;
        $class->forceDelete();

        $message = "Class '{$name}' has been permanently purged.";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.academic.classes.index', ['status' => 'trashed'])
            ->with('success', $message);
    }

    /**
     * Get active sections belonging to the specified class for dynamic dependent dropdowns.
     */
    public function getSections(AcademicClass $class): JsonResponse
    {
        $sections = $class->sections()
            ->active()
            ->select(['id', 'academic_class_id', 'name', 'capacity', 'room_number'])
            ->orderBy('name')
            ->get();

        return response()->json($sections);
    }

    /**
     * Get active subjects assigned to the specified class for dynamic dependent dropdowns.
     */
    public function getSubjects(AcademicClass $class): JsonResponse
    {
        $subjects = $class->subjects()
            ->select(['subjects.id', 'subjects.name', 'subjects.code', 'subjects.type'])
            ->orderBy('subjects.name')
            ->get();

        return response()->json($subjects);
    }
}
