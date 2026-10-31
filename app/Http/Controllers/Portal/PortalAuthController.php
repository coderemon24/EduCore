<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\PortalLoginRequest;
use App\Services\CurrentSchoolContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PortalAuthController extends Controller
{
    /**
     * Display the portal login view.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('frontend.portal.dashboard');
        }

        $school = CurrentSchoolContext::get();

        return view('portal.auth.login', compact('school'));
    }

    /**
     * Handle incoming portal authentication request.
     */
    public function store(PortalLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('frontend.portal.dashboard'));
    }

    /**
     * Log out the authenticated portal user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->forget('current_school_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('frontend.portal.login')->with('status', 'You have been safely signed out of the institutional portal.');
    }
}

