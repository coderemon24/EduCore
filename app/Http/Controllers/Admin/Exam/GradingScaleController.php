<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Models\GradeTier;
use App\Models\GradingScale;
use App\Models\School;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GradingScaleController extends Controller
{
    /**
     * Display a listing of institutional grading scales.
     */
    public function index(): View
    {
        $scales = GradingScale::with('gradeTiers')
            ->withCount('exams')
            ->latest('id')
            ->paginate(15);

        return view('admin.exams.grades.index', compact('scales'));
    }

    /**
     * Show the form for creating a new grading scale.
     */
    public function create(): View
    {
        return view('admin.exams.grades.create');
    }

    /**
     * Store a newly created grading scale with its grade tiers.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'fail_any_fails_all' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'tiers' => ['required', 'array', 'min:1'],
            'tiers.*.grade' => ['required', 'string', 'max:10'],
            'tiers.*.point' => ['required', 'numeric', 'min:0', 'max:10'],
            'tiers.*.min_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'tiers.*.max_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'tiers.*.is_pass' => ['nullable', 'boolean'],
            'tiers.*.remarks' => ['nullable', 'string', 'max:100'],
        ]);

        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (auth()->guard('web')->user()?->school_id ?? session('current_school_id') ?? School::value('id') ?? 1);

        DB::transaction(function () use ($validated, $schoolId) {
            if (! empty($validated['is_default'])) {
                GradingScale::where('school_id', $schoolId)->update(['is_default' => false]);
            }

            $scale = GradingScale::create([
                'school_id' => $schoolId,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'fail_any_fails_all' => ! empty($validated['fail_any_fails_all']),
                'is_default' => ! empty($validated['is_default']),
                'is_active' => ! empty($validated['is_active']),
            ]);

            foreach ($validated['tiers'] as $index => $tierData) {
                GradeTier::create([
                    'grading_scale_id' => $scale->id,
                    'grade' => strtoupper(trim($tierData['grade'])),
                    'point' => (float) $tierData['point'],
                    'min_percentage' => (float) $tierData['min_percentage'],
                    'max_percentage' => (float) $tierData['max_percentage'],
                    'is_pass' => ! empty($tierData['is_pass']),
                    'remarks' => $tierData['remarks'] ?? null,
                    'order_index' => $index + 1,
                ]);
            }
        });

        ToastMagic::success('Grading scale created successfully.');

        return redirect()->route('admin.exams.grades.index');
    }

    /**
     * Show the form for editing the specified grading scale.
     */
    public function edit(GradingScale $grade): View
    {
        $grade->load('gradeTiers');

        return view('admin.exams.grades.edit', ['scale' => $grade]);
    }

    /**
     * Update the specified grading scale and its grade tiers.
     */
    public function update(Request $request, GradingScale $grade): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'fail_any_fails_all' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'tiers' => ['required', 'array', 'min:1'],
            'tiers.*.grade' => ['required', 'string', 'max:10'],
            'tiers.*.point' => ['required', 'numeric', 'min:0', 'max:10'],
            'tiers.*.min_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'tiers.*.max_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'tiers.*.is_pass' => ['nullable', 'boolean'],
            'tiers.*.remarks' => ['nullable', 'string', 'max:100'],
        ]);

        $schoolId = $grade->school_id;

        DB::transaction(function () use ($validated, $grade, $schoolId) {
            if (! empty($validated['is_default'])) {
                GradingScale::where('school_id', $schoolId)->where('id', '!=', $grade->id)->update(['is_default' => false]);
            }

            $grade->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'fail_any_fails_all' => ! empty($validated['fail_any_fails_all']),
                'is_default' => ! empty($validated['is_default']),
                'is_active' => ! empty($validated['is_active']),
            ]);

            // Re-sync grade tiers
            $grade->gradeTiers()->delete();

            foreach ($validated['tiers'] as $index => $tierData) {
                GradeTier::create([
                    'grading_scale_id' => $grade->id,
                    'grade' => strtoupper(trim($tierData['grade'])),
                    'point' => (float) $tierData['point'],
                    'min_percentage' => (float) $tierData['min_percentage'],
                    'max_percentage' => (float) $tierData['max_percentage'],
                    'is_pass' => ! empty($tierData['is_pass']),
                    'remarks' => $tierData['remarks'] ?? null,
                    'order_index' => $index + 1,
                ]);
            }
        });

        ToastMagic::success('Grading scale updated successfully.');

        return redirect()->route('admin.exams.grades.index');
    }

    /**
     * Remove the specified grading scale.
     */
    public function destroy(GradingScale $grade): RedirectResponse
    {
        if ($grade->exams()->exists()) {
            ToastMagic::error('Cannot delete grading scale currently associated with examination records.');

            return back();
        }

        $grade->delete();

        ToastMagic::success('Grading scale removed successfully.');

        return redirect()->route('admin.exams.grades.index');
    }
}
