<?php

namespace App\Http\Controllers\Admin\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAboutPageRequest;
use App\Models\AboutPageContent;
use App\Models\School;
use App\Services\CurrentSchoolContext;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AboutPageController extends Controller
{
    /**
     * Display the About page edit form in the admin dashboard.
     */
    public function edit(): View
    {
        $school = CurrentSchoolContext::get();
        if (! $school || ! $school->exists) {
            $school = School::first() ?? new School(['id' => 1, 'name' => 'সোনাতলা উচ্চ বিদ্যালয়']);
        }

        $about = AboutPageContent::forSchool($school->id);

        return view('admin.frontend.pages.about', compact('school', 'about'));
    }

    /**
     * Update the About page content, repeaters, and SEO settings in storage.
     */
    public function update(UpdateAboutPageRequest $request): RedirectResponse
    {
        $school = CurrentSchoolContext::get();
        if (! $school || ! $school->exists) {
            $school = School::first();
        }

        if (! $school) {
            ToastMagic::error('No active school context found.');

            return redirect()->back();
        }

        $validated = $request->validated();

        // 1. Handle Banner Image Upload
        if ($request->hasFile('banner_image_file')) {
            $file = $request->file('banner_image_file');
            $filename = 'about_banner_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/about'), $filename);
            $validated['banner_image'] = 'uploads/about/'.$filename;
        }

        // 2. Handle History Featured Image Upload
        if ($request->hasFile('history_image_file')) {
            $file = $request->file('history_image_file');
            $filename = 'about_history_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/about'), $filename);
            $validated['history_image'] = 'uploads/about/'.$filename;
        }

        // 3. Handle OG Social Share Image Upload
        if ($request->hasFile('og_image_file')) {
            $file = $request->file('og_image_file');
            $filename = 'about_og_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/about'), $filename);
            $validated['og_image'] = 'uploads/about/'.$filename;
        }

        // Clean file input keys
        unset($validated['banner_image_file'], $validated['history_image_file'], $validated['og_image_file']);

        // 4. Clean and Sanitize Dynamic Repeaters
        if (isset($validated['values_list']) && is_array($validated['values_list'])) {
            $validated['values_list'] = array_values(array_filter($validated['values_list'], fn ($val) => ! empty(trim((string) $val))));
        }

        if (isset($validated['milestones']) && is_array($validated['milestones'])) {
            $validated['milestones'] = array_values(array_filter($validated['milestones'], function ($item) {
                return ! empty(trim((string) ($item['year'] ?? ''))) || ! empty(trim((string) ($item['title'] ?? '')));
            }));
        }

        if (isset($validated['statistics']) && is_array($validated['statistics'])) {
            $validated['statistics'] = array_values(array_filter($validated['statistics'], function ($item) {
                return ! empty(trim((string) ($item['label'] ?? ''))) || ! empty(trim((string) ($item['value'] ?? '')));
            }));
        }

        if (isset($validated['at_a_glance']) && is_array($validated['at_a_glance'])) {
            $validated['at_a_glance'] = array_values(array_filter($validated['at_a_glance'], function ($item) {
                return ! empty(trim((string) ($item['key'] ?? ''))) || ! empty(trim((string) ($item['value'] ?? '')));
            }));
        }

        if (isset($validated['committee_members']) && is_array($validated['committee_members'])) {
            $validated['committee_members'] = array_values(array_filter($validated['committee_members'], function ($item) {
                return ! empty(trim((string) ($item['name'] ?? '')));
            }));
        }

        if (isset($validated['facilities']) && is_array($validated['facilities'])) {
            $validated['facilities'] = array_values(array_filter($validated['facilities'], function ($item) {
                return ! empty(trim((string) ($item['title'] ?? '')));
            }));
        }

        // 5. Update or Create Database Record
        AboutPageContent::updateOrCreate(
            ['school_id' => $school->id],
            $validated
        );

        $successMessage = 'About page content, layout repeaters, and SEO settings updated successfully.';
        ToastMagic::success($successMessage);

        return redirect()->route('admin.frontend.pages.about')
            ->with('success', $successMessage);
    }
}
