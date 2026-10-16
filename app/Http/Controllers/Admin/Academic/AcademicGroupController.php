<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Academic\AcademicGroupRequest;
use App\Models\AcademicGroup;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicGroupController extends Controller
{
    /**
     * Display a listing of academic streams and groups.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = AcademicGroup::query()->latest();

        if ($statusFilter !== 'all' && in_array($statusFilter, ['active', 'inactive'], true)) {
            $query->where('status', $statusFilter);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $groups = $query
            ->select(['id', 'school_id', 'name', 'code', 'description', 'status', 'created_at'])
            ->paginate(15)
            ->withQueryString();

        $rawCounts = AcademicGroup::query()
            ->selectRaw("
                COUNT(*) as all_count,
                COUNT(CASE WHEN status = 'active' THEN 1 END) as active_count,
                COUNT(CASE WHEN status = 'inactive' THEN 1 END) as inactive_count
            ")
            ->first();

        $statusCounts = [
            'all' => (int) ($rawCounts->all_count ?? 0),
            'active' => (int) ($rawCounts->active_count ?? 0),
            'inactive' => (int) ($rawCounts->inactive_count ?? 0),
        ];

        return view('admin.academic.groups.index', compact('groups', 'statusFilter', 'search', 'statusCounts'));
    }

    /**
     * Show the form for creating a new academic group.
     */
    public function create(): View
    {
        $group = new AcademicGroup([
            'status' => 'active',
        ]);

        return view('admin.academic.groups.create', compact('group'));
    }

    /**
     * Store a newly created academic group in storage.
     */
    public function store(AcademicGroupRequest $request): RedirectResponse
    {
        $group = AcademicGroup::create($request->validated());

        ToastMagic::success("Academic stream '{$group->name}' created successfully.");

        return redirect()->route('admin.academic.groups.index')
            ->with('success', "Academic stream '{$group->name}' created successfully.");
    }

    /**
     * Show the form for editing the specified academic group.
     */
    public function edit(AcademicGroup $group): View
    {
        return view('admin.academic.groups.edit', compact('group'));
    }

    /**
     * Update the specified academic group in storage.
     */
    public function update(AcademicGroupRequest $request, AcademicGroup $group): RedirectResponse
    {
        $group->update($request->validated());

        ToastMagic::success("Academic stream '{$group->name}' updated successfully.");

        return redirect()->route('admin.academic.groups.index')
            ->with('success', "Academic stream '{$group->name}' updated successfully.");
    }

    /**
     * Remove the specified academic group from storage.
     */
    public function destroy(AcademicGroup $group): RedirectResponse
    {
        $name = $group->name;
        $group->delete();

        ToastMagic::success("Academic stream '{$name}' deleted successfully.");

        return redirect()->route('admin.academic.groups.index')
            ->with('success', "Academic stream '{$name}' deleted successfully.");
    }
}
