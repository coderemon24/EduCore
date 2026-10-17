<?php

namespace App\Http\Controllers\Admin\Fee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Fee\StoreFeeCategoryRequest;
use App\Models\FeeCategory;
use App\Models\School;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeeCategoryController extends Controller
{
    /**
     * Display listing of institutional fee categories.
     */
    public function index(Request $request): View
    {
        $query = FeeCategory::query()
            ->withCount(['feeStructures', 'invoiceItems'])
            ->latest('id');

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $categories = $query->paginate(15)->withQueryString();

        return view('admin.fees.categories.index', compact('categories'));
    }

    /**
     * Store a newly created fee category.
     */
    public function store(StoreFeeCategoryRequest $request): RedirectResponse
    {
        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (auth()->guard('web')->user()?->school_id ?? session('current_school_id') ?? School::value('id') ?? 1);

        FeeCategory::create([
            'school_id' => $schoolId,
            'name' => $request->validated('name'),
            'code' => strtoupper($request->validated('code')),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        ToastMagic::success('Fee category created successfully.');

        return redirect()->route('admin.fees.categories.index');
    }

    /**
     * Update the specified fee category.
     */
    public function update(StoreFeeCategoryRequest $request, FeeCategory $category): RedirectResponse
    {
        $category->update([
            'name' => $request->validated('name'),
            'code' => strtoupper($request->validated('code')),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        ToastMagic::success('Fee category updated successfully.');

        return redirect()->route('admin.fees.categories.index');
    }

    /**
     * Remove the specified fee category if not in active use.
     */
    public function destroy(FeeCategory $category): RedirectResponse
    {
        if ($category->invoiceItems()->exists()) {
            ToastMagic::error('Cannot delete fee category because it is already referenced in student invoices.');

            return back();
        }

        $category->delete();

        ToastMagic::success('Fee category deleted successfully.');

        return redirect()->route('admin.fees.categories.index');
    }
}
