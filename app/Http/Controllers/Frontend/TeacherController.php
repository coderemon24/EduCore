<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Services\CurrentSchoolContext;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TeacherController extends Controller
{
    /**
     * Display the faculty and staff member directory.
     */
    public function index(Request $request): View
    {
        $school = CurrentSchoolContext::get();

        $teachers = collect();
        $departments = collect();

        if ($school->exists && Schema::hasTable('staff')) {
            $query = Staff::where('school_id', $school->id)
                ->where('status', 'active');

            if ($request->filled('department')) {
                $query->where('department', $request->department);
            }

            if ($request->filled('q')) {
                $search = $request->q;
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%");
                });
            }

            $teachers = $query->orderBy('is_teaching', 'desc')
                ->orderBy('first_name', 'asc')
                ->paginate(12)
                ->withQueryString();

            $departments = Staff::where('school_id', $school->id)
                ->whereNotNull('department')
                ->distinct()
                ->pluck('department');
        }

        return view('frontend.teachers.index', compact('school', 'teachers', 'departments'));
    }
}
