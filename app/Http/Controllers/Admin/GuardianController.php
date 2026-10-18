<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GuardianController extends Controller
{
    /**
     * Display a listing of parents & guardians with optimized queries and real-time search support.
     */
    public function index(Request $request): View|string
    {
        $status = $request->query('status', 'active');
        $search = trim((string) $request->query('search', ''));

        // Base query with tab status filter
        if ($status === 'archived') {
            $query = Guardian::onlyTrashed();
        } elseif ($status === 'all') {
            $query = Guardian::withTrashed();
        } else {
            $status = 'active';
            $query = Guardian::query();
        }

        // Fast keyword search using indexed columns and student relation
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('national_id', 'like', "%{$search}%")
                    ->orWhereHas('students', function ($sq) use ($search) {
                        $sq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('admission_number', 'like', "%{$search}%");
                    });
            });
        }

        // Optimized single-query count aggregation
        $rawCounts = Guardian::withTrashed()
            ->selectRaw('
                COUNT(*) as total_count,
                SUM(CASE WHEN deleted_at IS NULL THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN deleted_at IS NOT NULL THEN 1 ELSE 0 END) as archived_count
            ')
            ->first();

        $counts = [
            'active' => (int) ($rawCounts->active_count ?? 0),
            'archived' => (int) ($rawCounts->archived_count ?? 0),
            'all' => (int) ($rawCounts->total_count ?? 0),
        ];

        // Optimized eager loading with strict column selection to minimize memory & query time
        $guardians = $query->select([
            'id',
            'school_id',
            'name',
            'phone',
            'email',
            'occupation',
            'national_id',
            'address',
            'deleted_at',
            'created_at',
        ])
            ->with([
                'students' => function ($sq) {
                    $sq->select([
                        'students.id',
                        'students.school_id',
                        'students.first_name',
                        'students.last_name',
                        'students.admission_number',
                        'students.status',
                    ])
                        ->with([
                            'currentEnrollment.academicClass:id,name',
                            'currentEnrollment.section:id,name',
                        ]);
                },
            ])
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        // If AJAX request (real-time search / pagination), return partial table HTML directly
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.guardians.partials.table', [
                'guardians' => $guardians,
                'status' => $status,
                'search' => $search,
            ])->render();
        }

        return view('admin.guardians.index', [
            'guardians' => $guardians,
            'counts' => $counts,
            'status' => $status,
            'search' => $search,
        ]);
    }

    /**
     * Display the parent details and enrolled children.
     */
    public function show(int|string $id): View
    {
        $guardian = Guardian::withTrashed()
            ->select([
                'id',
                'school_id',
                'name',
                'phone',
                'email',
                'occupation',
                'national_id',
                'address',
                'deleted_at',
                'created_at',
                'updated_at',
            ])
            ->with([
                'students' => function ($sq) {
                    $sq->select([
                        'students.id',
                        'students.school_id',
                        'students.first_name',
                        'students.last_name',
                        'students.admission_number',
                        'students.status',
                    ])
                        ->with([
                            'currentEnrollment.academicClass:id,name',
                            'currentEnrollment.section:id,name',
                        ]);
                },
            ])
            ->findOrFail($id);

        return view('admin.guardians.show', [
            'guardian' => $guardian,
        ]);
    }

    /**
     * Show the form for editing parent details.
     */
    public function edit(Guardian $guardian): View
    {
        return view('admin.guardians.edit', [
            'guardian' => $guardian,
        ]);
    }

    /**
     * Update the parent details in database.
     */
    public function update(Request $request, Guardian $guardian): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $guardian->update($validated);

        ToastMagic::success('Parent details updated successfully.');

        return redirect()
            ->route('admin.guardians.show', $guardian)
            ->with('success', 'Parent details updated successfully.');
    }

    /**
     * Move the parent record to trash.
     */
    public function destroy(Guardian $guardian): RedirectResponse
    {
        $guardian->delete();

        ToastMagic::success('Parent record moved to trash.');

        return redirect()
            ->route('admin.guardians.index')
            ->with('success', 'Parent record moved to trash.');
    }

    /**
     * Restore the parent record from trash.
     */
    public function restore(int|string $id): RedirectResponse
    {
        $guardian = Guardian::onlyTrashed()->findOrFail($id);
        $guardian->restore();

        ToastMagic::success('Parent record restored successfully.');

        return redirect()
            ->route('admin.guardians.index', ['status' => 'archived'])
            ->with('success', 'Parent record restored successfully.');
    }

    /**
     * Permanently delete the parent record and unlink student relationships.
     */
    public function forceDelete(int|string $id): RedirectResponse
    {
        $guardian = Guardian::withTrashed()->findOrFail($id);
        $guardian->students()->detach();
        $guardian->forceDelete();

        ToastMagic::success('Parent record deleted permanently.');

        return redirect()
            ->route('admin.guardians.index', ['status' => 'archived'])
            ->with('success', 'Parent record deleted permanently.');
    }

    /**
     * Perform bulk actions (trash, restore, delete forever) on selected parents.
     */
    public function bulkAction(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:delete,restore,force_delete'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        $action = $validated['action'];
        $ids = $validated['ids'];
        $count = count($ids);

        if ($action === 'delete') {
            Guardian::whereIn('id', $ids)->delete();
            $message = "{$count} ".Str::plural('parent record', $count).' moved to trash.';
        } elseif ($action === 'restore') {
            Guardian::onlyTrashed()->whereIn('id', $ids)->restore();
            $message = "{$count} ".Str::plural('parent record', $count).' restored successfully.';
        } elseif ($action === 'force_delete') {
            $guardians = Guardian::withTrashed()->whereIn('id', $ids)->get();
            foreach ($guardians as $g) {
                $g->students()->detach();
                $g->forceDelete();
            }
            $message = "{$count} ".Str::plural('parent record', $count).' deleted permanently.';
        }

        ToastMagic::success($message);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
