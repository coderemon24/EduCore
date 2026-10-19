<?php

namespace App\Http\Middleware;

use App\Models\Notice;
use App\Services\CurrentSchoolContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetFrontendSchoolContext
{
    /**
     * Handle an incoming request for the frontend portal.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $school = CurrentSchoolContext::resolve($request);

        View::share('currentSchool', $school);
        View::share('school', $school);

        // Populate recent announcements for the global running ticker
        $tickerNotices = collect();
        if ($school->exists && Schema::hasTable('notices')) {
            $tickerNotices = Notice::where('school_id', $school->id)
                ->where('status', 'published')
                ->latest('published_at')
                ->take(5)
                ->get(['id', 'title', 'slug', 'published_at', 'type', 'is_pinned']);
        }

        View::share('tickerNotices', $tickerNotices);

        return $next($request);
    }
}
