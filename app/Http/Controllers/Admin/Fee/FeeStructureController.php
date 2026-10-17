<?php

namespace App\Http\Controllers\Admin\Fee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Fee\StoreFeeStructureRequest;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\FeeCategory;
use App\Models\FeeStructure;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeeStructureController extends Controller
{
    /**
     * Display fee structures catalog with session and class filters.
     */
    public function index(Request $request): View
    {
        $query = FeeStructure::query()
            ->with(['academicYear', 'academicClass', 'feeCategory'])
            ->latest('id');

        if ($yearId = $request->input('academic_year_id')) {
            $query->where('academic_year_id', $yearId);
        }

        if ($classId = $request->input('academic_class_id')) {
            $query->where(function ($q) use ($classId) {
                $q->where('academic_class_id', $classId)
                    ->orWhereNull('academic_class_class');
            });
        }

        if ($categoryId = $request->input('fee_category_id')) {
            $query->where('fee_category_id', $categoryId);
        }

        if ($frequency = $request->input('frequency')) {
            $query->where('frequency', $frequency);
        }

        $structures = $query->paginate(15)->withQueryString();

        $academicYears = AcademicYear::latest('id')->get();
        $academicClasses = AcademicClass::ordered()->get();
        $feeCategories = FeeCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.fees.structures.index', compact(
            'structures',
            'academicYears',
            'academicClasses',
            'feeCategories'
        ));
    }

    /**
     * Store a newly created fee structure.
     */
    public function store(StoreFeeStructureRequest $request): RedirectResponse
    {
        FeeStructure::create([
            'academic_year_id' => $request->validated('academic_year_id'),
            'academic_class_id' => $request->validated('academic_class_id'),
            'fee_category_id' => $request->validated('fee_category_id'),
            'name' => $request->validated('name'),
            'amount' => $request->validated('amount'),
            'frequency' => $request->validated('frequency'),
            'due_day' => $request->validated('due_day'),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        ToastMagic::success('Fee structure created successfully.');

        return redirect()->route('admin.fees.structures.index');
    }

    /**
     * Update the specified fee structure.
     */
    public function update(StoreFeeStructureRequest $request, FeeStructure $structure): RedirectResponse
    {
        $structure->update([
            'academic_year_id' => $request->validated('academic_year_id'),
            'academic_class_id' => $request->validated('academic_class_id'),
            'fee_category_id' => $request->validated('fee_category_id'),
            'name' => $request->validated('name'),
            'amount' => $request->validated('amount'),
            'frequency' => $request->validated('frequency'),
            'due_day' => $request->validated('due_day'),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        ToastMagic::success('Fee structure updated successfully.');

        return redirect()->route('admin.fees.structures.index');
    }

    /**
     * Delete the specified fee structure.
     */
    public function destroy(FeeStructure $structure): RedirectResponse
    {
        if ($structure->invoiceItems()->exists()) {
            ToastMagic::error('Cannot delete fee structure because it is linked to generated invoices.');

            return back();
        }

        $structure->delete();

        ToastMagic::success('Fee structure deleted successfully.');

        return redirect()->route('admin.fees.structures.index');
    }

    /**
     * API: Get active fee structures for a class/year for invoice generation.
     */
    public function getByClass(Request $request): JsonResponse
    {
        $yearId = $request->input('academic_year_id');
        $classId = $request->input('academic_class_id');

        $query = FeeStructure::query()
            ->where('is_active', true)
            ->with('feeCategory');

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }

        if ($classId) {
            $query->where(function ($q) use ($classId) {
                $q->where('academic_class_id', $classId)
                    ->orWhereNull('academic_class_id');
            });
        }

        $structures = $query->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'name' => $s->name,
                'amount' => (float) $s->amount,
                'category_id' => $s->fee_category_id,
                'category_name' => $s->feeCategory?->name,
                'frequency' => ucfirst(str_replace('_', ' ', $s->frequency)),
            ];
        });

        return response()->json($structures);
    }
}
