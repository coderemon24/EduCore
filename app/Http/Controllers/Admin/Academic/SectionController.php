<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Academic\SectionRequest;
use App\Models\AcademicClass;
use App\Models\Section;
use Devrabiul\ToastMagic\Facades\ToastMagic;
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

        $query = Section::query()->with(['academicClass:id,name']);

        if ($statusFilter !== 'all' && in_array($statusFilter, ['active', 'inactive'], true)) {
            $query->where('status', $statusFilter);
        }

        if ($classFilter !== 'all' && is_numeric($classFilter)) {
            $query->where('academic_class_id', (int) $classFilter);
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('room_number', 'like', "%{$search}%")
                    ->orWhereHas('academicClass', fn ($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        $sections = $query
            ->select(['id', 'school_id', 'academic_class_id', 'name', 'capacity', 'room_number', 'status', 'created_at'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $classes = AcademicClass::ordered()->select(['id', 'name'])->get();

        $rawCounts = Section::query()
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

        return view('admin.academic.sections.index', compact('sections', 'classes', 'statusFilter', 'classFilter', 'search', 'statusCounts'));
    }

    /**
     * Show the form for creating a new section.
     */
    public function create(Request $request): View
    {
        $classes = AcademicClass::ordered()->select(['id', 'name'])->get();
        $defaultClassId = $request->query('class_id');

        $section = new Section([
            'status' => 'active',
            'capacity' => 40,
            'academic_class_id' => $defaultClassId,
        ]);

        return view('admin.academic.sections.create', compact('section', 'classes'));
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

        return view('admin.academic.sections.edit', compact('section', 'classes'));
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
     * Remove the specified section from storage.
     */
    public function destroy(Section $section): RedirectResponse
    {
        $name = $section->name;
        $classId = $section->academic_class_id;
        $section->delete();

        ToastMagic::success("Section '{$name}' deleted successfully.");

        return redirect()->route('admin.academic.sections.index', ['class_id' => $classId])
            ->with('success', "Section '{$name}' deleted successfully.");
    }
}
