<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SchoolStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSchoolRequest;
use App\Http\Requests\Admin\UpdateSchoolRequest;
use App\Http\Requests\Admin\UpdateSchoolStatusRequest;
use App\Models\School;
use DateTimeZone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SchoolController extends Controller
{
    /**
     * Display a listing of institutional tenants.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));

        if ($statusFilter === 'trashed') {
            $query = School::onlyTrashed()->latest('deleted_at');
        } else {
            $query = School::query()->latest();

            if ($statusFilter !== 'all' && in_array($statusFilter, ['active', 'inactive', 'suspended', 'pending'], true)) {
                $query->where('status', $statusFilter);
            }
        }

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $schools = $query->paginate(10)->withQueryString();

        $statusCounts = [
            'all' => School::count(),
            'active' => School::where('status', SchoolStatus::Active)->count(),
            'pending' => School::where('status', SchoolStatus::Pending)->count(),
            'suspended' => School::where('status', SchoolStatus::Suspended)->count(),
            'inactive' => School::where('status', SchoolStatus::Inactive)->count(),
            'trashed' => School::onlyTrashed()->count(),
        ];

        return view('admin.schools.index', compact('schools', 'statusFilter', 'search', 'statusCounts'));
    }

    /**
     * Show the form for creating a new institutional tenant.
     */
    public function create(): View
    {
        $school = new School([
            'status' => SchoolStatus::Active,
            'timezone' => 'America/New_York',
            'currency' => 'USD',
            'country' => 'United States',
        ]);

        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $currencies = $this->currencyList();

        return view('admin.schools.create', compact('school', 'timezones', 'currencies'));
    }

    /**
     * Store a newly created institutional tenant in storage.
     */
    public function store(StoreSchoolRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoDir = public_path('uploads/schools/logos');
            if (! File::isDirectory($logoDir)) {
                File::makeDirectory($logoDir, 0755, true, true);
            }
            $logoName = 'logo_'.time().'_'.Str::random(10).'.'.$logoFile->getClientOriginalExtension();
            $logoFile->move($logoDir, $logoName);
            $data['logo'] = 'uploads/schools/logos/'.$logoName;
        }

        if ($request->hasFile('favicon')) {
            $faviconFile = $request->file('favicon');
            $faviconDir = public_path('uploads/schools/favicons');
            if (! File::isDirectory($faviconDir)) {
                File::makeDirectory($faviconDir, 0755, true, true);
            }
            $faviconName = 'favicon_'.time().'_'.Str::random(10).'.'.$faviconFile->getClientOriginalExtension();
            $faviconFile->move($faviconDir, $faviconName);
            $data['favicon'] = 'uploads/schools/favicons/'.$faviconName;
        }

        $school = School::create($data);

        return redirect()->route('admin.schools.show', $school)
            ->with('success', "Institutional tenant \"{$school->name}\" was created successfully.");
    }

    /**
     * Display the specified school institution details.
     */
    public function show(School $school): View
    {
        return view('admin.schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified school institution.
     */
    public function edit(School $school): View
    {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $currencies = $this->currencyList();

        return view('admin.schools.edit', compact('school', 'timezones', 'currencies'));
    }

    /**
     * Update the specified school institution in storage.
     */
    public function update(UpdateSchoolRequest $request, School $school): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($school->logo && File::exists(public_path($school->logo))) {
                File::delete(public_path($school->logo));
            }

            $logoFile = $request->file('logo');
            $logoDir = public_path('uploads/schools/logos');
            if (! File::isDirectory($logoDir)) {
                File::makeDirectory($logoDir, 0755, true, true);
            }
            $logoName = 'logo_'.time().'_'.Str::random(10).'.'.$logoFile->getClientOriginalExtension();
            $logoFile->move($logoDir, $logoName);
            $data['logo'] = 'uploads/schools/logos/'.$logoName;
        } else {
            unset($data['logo']);
        }

        if ($request->hasFile('favicon')) {
            if ($school->favicon && File::exists(public_path($school->favicon))) {
                File::delete(public_path($school->favicon));
            }

            $faviconFile = $request->file('favicon');
            $faviconDir = public_path('uploads/schools/favicons');
            if (! File::isDirectory($faviconDir)) {
                File::makeDirectory($faviconDir, 0755, true, true);
            }
            $faviconName = 'favicon_'.time().'_'.Str::random(10).'.'.$faviconFile->getClientOriginalExtension();
            $faviconFile->move($faviconDir, $faviconName);
            $data['favicon'] = 'uploads/schools/favicons/'.$faviconName;
        } else {
            unset($data['favicon']);
        }

        $school->update($data);

        return redirect()->route('admin.schools.show', $school)
            ->with('success', "Institutional details for \"{$school->name}\" have been updated successfully.");
    }

    /**
     * Soft delete the specified school institution (Archive).
     */
    public function destroy(Request $request, School $school): JsonResponse|RedirectResponse
    {
        $schoolName = $school->name;
        $school->delete();

        $message = "Institution \"{$schoolName}\" was moved to archive (soft deleted).";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.schools.index')->with('success', $message);
    }

    /**
     * Restore a soft-deleted school institution.
     */
    public function restore(Request $request, School $school): JsonResponse|RedirectResponse
    {
        $school->restore();
        $message = "Institution \"{$school->name}\" was restored to operational directory successfully.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Permanently remove a school institution from database.
     */
    public function forceDelete(Request $request, School $school): JsonResponse|RedirectResponse
    {
        $schoolName = $school->name;
        $school->forceDelete();

        $message = "Institution \"{$schoolName}\" has been permanently purged.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.schools.index', ['status' => 'trashed'])->with('success', $message);
    }

    /**
     * Update the specified school institution lifecycle status.
     */
    public function updateStatus(UpdateSchoolStatusRequest $request, School $school): JsonResponse|RedirectResponse
    {
        $newStatus = $request->enum('status', SchoolStatus::class);
        $school->update(['status' => $newStatus]);

        $message = "Status for \"{$school->name}\" changed to {$newStatus->label()}.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $newStatus->value,
                'label' => $newStatus->label(),
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Currencies key-value collection.
     *
     * @return array<string, string>
     */
    private function currencyList(): array
    {
        return [
            'USD' => 'US Dollar ($)',
            'EUR' => 'Euro (€)',
            'GBP' => 'British Pound (£)',
            'BDT' => 'Bangladeshi Taka (৳)',
            'INR' => 'Indian Rupee (₹)',
            'CAD' => 'Canadian Dollar ($)',
            'AUD' => 'Australian Dollar ($)',
            'AED' => 'UAE Dirham (AED)',
            'SAR' => 'Saudi Riyal (SAR)',
        ];
    }
}
