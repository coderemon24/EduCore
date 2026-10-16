<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Academic\AcademicYearRequest;
use App\Models\AcademicYear;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of academic year sessions.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = AcademicYear::query()->latest('start_date');

        if ($statusFilter !== 'all' && in_array($statusFilter, ['active', 'inactive'], true)) {
            $query->where('status', $statusFilter);
        }

        if (! empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }

        $years = $query
            ->select(['id', 'school_id', 'name', 'start_date', 'end_date', 'is_current', 'status', 'created_at'])
            ->paginate(15)
            ->withQueryString();

        $rawCounts = AcademicYear::query()
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

        return view('admin.academic.years.index', compact('years', 'statusFilter', 'search', 'statusCounts'));
    }

    /**
     * Show the form for creating a new academic year.
     */
    public function create(): View
    {
        $year = new AcademicYear([
            'status' => 'active',
            'start_date' => now()->startOfYear()->toDateString(),
            'end_date' => now()->endOfYear()->toDateString(),
        ]);

        return view('admin.academic.years.create', compact('year'));
    }

    /**
     * Store a newly created academic year in storage.
     */
    public function store(AcademicYearRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $isCurrent = $request->boolean('is_current');
        $data['is_current'] = false;

        $academicYear = AcademicYear::create($data);

        if ($isCurrent) {
            $academicYear->setAsCurrent();
        }

        ToastMagic::success("Academic Year '{$academicYear->name}' created successfully.");

        return redirect()->route('admin.academic.years.index')
            ->with('success', "Academic Year '{$academicYear->name}' created successfully.");
    }

    /**
     * Show the form for editing the specified academic year.
     */
    public function edit(AcademicYear $year): View
    {
        return view('admin.academic.years.edit', compact('year'));
    }

    /**
     * Update the specified academic year in storage.
     */
    public function update(AcademicYearRequest $request, AcademicYear $year): RedirectResponse
    {
        $data = $request->validated();
        $isCurrent = $request->boolean('is_current');

        $year->update($data);

        if ($isCurrent && ! $year->is_current) {
            $year->setAsCurrent();
        }

        ToastMagic::success("Academic Year '{$year->name}' updated successfully.");

        return redirect()->route('admin.academic.years.index')
            ->with('success', "Academic Year '{$year->name}' updated successfully.");
    }

    /**
     * Atomically designate the specified year as the current session.
     */
    public function setCurrent(AcademicYear $year): RedirectResponse
    {
        $year->setAsCurrent();

        ToastMagic::success("Academic session '{$year->name}' is now active.");

        return redirect()->back(fallback: route('admin.academic.years.index'))
            ->with('success', "Academic session '{$year->name}' is now active.");
    }

    /**
     * Remove the specified academic year from storage.
     */
    public function destroy(AcademicYear $year): RedirectResponse
    {
        if ($year->is_current) {
            ToastMagic::error('Cannot delete the active current academic year. Designate another year as active first.');

            return redirect()->back(fallback: route('admin.academic.years.index'))->with('error', 'Cannot delete the active current academic year.');
        }

        $yearName = $year->name;
        $year->delete();

        ToastMagic::success("Academic Year '{$yearName}' deleted successfully.");

        return redirect()->route('admin.academic.years.index')
            ->with('success', "Academic Year '{$yearName}' deleted successfully.");
    }
}
