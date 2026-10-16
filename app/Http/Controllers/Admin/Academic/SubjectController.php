<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Academic\SubjectRequest;
use App\Models\Subject;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    /**
     * Display a listing of course subjects.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $typeFilter = $request->query('type', 'all');
        $search = trim((string) $request->query('search', ''));

        if ($statusFilter === 'trashed') {
            $query = Subject::onlyTrashed()->latest('deleted_at');
        } else {
            $query = Subject::query()->latest();

            if ($statusFilter !== 'all' && in_array($statusFilter, ['active', 'inactive'], true)) {
                $query->where('status', $statusFilter);
            }
        }

        if ($typeFilter !== 'all' && in_array($typeFilter, ['theory', 'practical', 'both'], true)) {
            $query->where('type', $typeFilter);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $subjects = $query
            ->withCount('academicClasses')
            ->select(['id', 'school_id', 'name', 'code', 'type', 'credit_hours', 'status', 'created_at', 'deleted_at'])
            ->paginate(15)
            ->withQueryString();

        $rawCounts = Subject::query()
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

        return view('admin.academic.subjects.index', compact('subjects', 'statusFilter', 'typeFilter', 'search', 'statusCounts'));
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create(): View
    {
        $subject = new Subject([
            'status' => 'active',
            'type' => 'theory',
            'credit_hours' => 3.00,
        ]);

        return view('admin.academic.subjects.create', compact('subject'));
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(SubjectRequest $request): RedirectResponse
    {
        $subject = Subject::create($request->validated());

        ToastMagic::success("Subject '{$subject->name}' ({$subject->code}) created successfully.");

        return redirect()->route('admin.academic.subjects.index')
            ->with('success', "Subject '{$subject->name}' created successfully.");
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject): View
    {
        return view('admin.academic.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(SubjectRequest $request, Subject $subject): RedirectResponse
    {
        $subject->update($request->validated());

        ToastMagic::success("Subject '{$subject->name}' updated successfully.");

        return redirect()->route('admin.academic.subjects.index')
            ->with('success', "Subject '{$subject->name}' updated successfully.");
    }

    /**
     * Remove (soft delete / archive) the specified subject from storage.
     */
    public function destroy(Request $request, Subject $subject): JsonResponse|RedirectResponse
    {
        $name = $subject->name;
        $subject->delete();

        $message = "Subject '{$name}' moved to archive (soft deleted).";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.academic.subjects.index')
            ->with('success', $message);
    }

    /**
     * Restore a soft-deleted subject.
     */
    public function restore(Request $request, Subject $subject): JsonResponse|RedirectResponse
    {
        $subject->restore();

        $message = "Subject '{$subject->name}' was restored successfully.";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->back(fallback: route('admin.academic.subjects.index'))
            ->with('success', $message);
    }

    /**
     * Permanently remove (force delete) the specified subject.
     */
    public function forceDelete(Request $request, Subject $subject): JsonResponse|RedirectResponse
    {
        $name = $subject->name;
        $subject->forceDelete();

        $message = "Subject '{$name}' has been permanently purged.";
        ToastMagic::success($message);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.academic.subjects.index', ['status' => 'trashed'])
            ->with('success', $message);
    }
}
