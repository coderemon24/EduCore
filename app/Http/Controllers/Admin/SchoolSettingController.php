<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSchoolSettingRequest;
use App\Models\School;
use DateTimeZone;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolSettingController extends Controller
{
    /**
     * Display the institution settings form.
     */
    public function edit(): View
    {
        $school = School::first() ?? new School([
            'name' => 'EduCore Academy',
            'code' => 'EDU-001',
            'timezone' => 'America/New_York',
            'currency' => 'USD',
        ]);

        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        $currencies = [
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

        return view('admin.settings.school', compact('school', 'timezones', 'currencies'));
    }

    /**
     * Update the institution settings in storage.
     */
    public function update(UpdateSchoolSettingRequest $request): RedirectResponse
    {
        $school = School::first();

        if (! $school) {
            $school = School::create($request->validated());
        } else {
            $school->update($request->validated());
        }

        return redirect()->route('admin.settings.school')
            ->with('success', 'Institutional profile and settings updated successfully.');
    }
}
