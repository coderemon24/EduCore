<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CurrentSchoolContext;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display the contact and inquiry page.
     */
    public function index(): View
    {
        $school = CurrentSchoolContext::get();

        return view('frontend.contact.index', compact('school'));
    }

    /**
     * Handle submitted public contact or inquiry form.
     */
    public function send(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:25'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        return back()->with('success', 'আপনার বার্তাটি সফলভাবে গৃহীত হয়েছে। অতি শীঘ্রই বিদ্যালয় কর্তৃপক্ষ আপনার সাথে যোগাযোগ করবে।');
    }
}
