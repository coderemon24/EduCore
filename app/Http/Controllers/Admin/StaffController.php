<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Staff\StoreStaffRequest;
use App\Http\Requests\Admin\Staff\UpdateStaffRequest;
use App\Http\Requests\Admin\Staff\UpdateStaffStatusRequest;
use App\Models\Role;
use App\Models\School;
use App\Models\Staff;
use App\Models\User;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Display a listing of institutional staff and faculty.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $typeFilter = $request->query('type', 'all'); // all, teaching, non_teaching
        $search = trim((string) $request->query('search', ''));
        $departmentFilter = $request->query('department');

        if ($statusFilter === 'trashed') {
            $query = Staff::onlyTrashed()->with(['user.roles'])->latest('deleted_at');
        } else {
            $query = Staff::query()->with(['user.roles'])->latest();

            if ($statusFilter !== 'all' && in_array($statusFilter, ['active', 'inactive', 'suspended'], true)) {
                $query->where('status', $statusFilter);
            }
        }

        // Employment type filter
        if ($typeFilter === 'teaching') {
            $query->where('is_teaching', true);
        } elseif ($typeFilter === 'non_teaching') {
            $query->where('is_teaching', false);
        }

        // Department filter
        if (! empty($departmentFilter) && $departmentFilter !== 'all') {
            $query->where('department', $departmentFilter);
        }

        // Keyword Search
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('staff_code', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        $staffMembers = $query->paginate(12)->withQueryString();

        // Calculate status counts for faceted tabs
        $rawCounts = Staff::withTrashed()
            ->selectRaw('
                COUNT(CASE WHEN deleted_at IS NULL THEN 1 END) as all_count,
                COUNT(CASE WHEN deleted_at IS NULL AND status = "active" THEN 1 END) as active_count,
                COUNT(CASE WHEN deleted_at IS NULL AND status = "inactive" THEN 1 END) as inactive_count,
                COUNT(CASE WHEN deleted_at IS NULL AND status = "suspended" THEN 1 END) as suspended_count,
                COUNT(CASE WHEN deleted_at IS NOT NULL THEN 1 END) as trashed_count,
                COUNT(CASE WHEN deleted_at IS NULL AND is_teaching = 1 THEN 1 END) as teaching_count,
                COUNT(CASE WHEN deleted_at IS NULL AND is_teaching = 0 THEN 1 END) as non_teaching_count
            ')->first();

        $statusCounts = [
            'all' => (int) ($rawCounts->all_count ?? 0),
            'active' => (int) ($rawCounts->active_count ?? 0),
            'inactive' => (int) ($rawCounts->inactive_count ?? 0),
            'suspended' => (int) ($rawCounts->suspended_count ?? 0),
            'trashed' => (int) ($rawCounts->trashed_count ?? 0),
            'teaching' => (int) ($rawCounts->teaching_count ?? 0),
            'non_teaching' => (int) ($rawCounts->non_teaching_count ?? 0),
        ];

        // Unique department list for filter dropdown
        $departments = Staff::query()
            ->whereNotNull('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('admin.staff.index', compact(
            'staffMembers',
            'statusFilter',
            'typeFilter',
            'search',
            'departmentFilter',
            'statusCounts',
            'departments'
        ));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create(Request $request): View
    {
        $defaultType = $request->query('type', 'teaching');
        $roles = Role::query()->orderBy('name')->get();

        $departments = Staff::query()
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');

        $designations = Staff::query()
            ->whereNotNull('designation')
            ->distinct()
            ->pluck('designation');

        return view('admin.staff.create', compact('defaultType', 'roles', 'departments', 'designations'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(StoreStaffRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $schoolId = app()->has('current_school_id') ? app('current_school_id') : (School::value('id') ?? 1);

        DB::beginTransaction();

        try {
            // 1. Handle Photo Upload
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $uploadDir = public_path('uploads/staff');

                if (! File::isDirectory($uploadDir)) {
                    File::makeDirectory($uploadDir, 0755, true);
                }

                $fileName = 'staff_'.time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
                $file->move($uploadDir, $fileName);
                $data['photo'] = 'uploads/staff/'.$fileName;
            }

            // 2. Optional Login Account Creation
            if ($request->boolean('create_user_account')) {
                $user = User::create([
                    'school_id' => $schoolId,
                    'name' => trim("{$data['first_name']} {$data['last_name']}"),
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'phone' => $data['phone'] ?? null,
                    'status' => $data['status'],
                    'avatar' => $data['photo'] ?? null,
                ]);

                if (! empty($data['role_id'])) {
                    $user->roles()->sync([$data['role_id']]);
                }

                $data['user_id'] = $user->id;
            }

            $data['school_id'] = $schoolId;
            $staff = Staff::create($data);

            DB::commit();

            ToastMagic::success('Faculty record created successfully.');

            return redirect()->route('admin.staff.show', $staff);
        } catch (\Throwable $e) {
            DB::rollBack();

            if (isset($data['photo']) && File::exists(public_path($data['photo']))) {
                File::delete(public_path($data['photo']));
            }

            ToastMagic::error('Failed to create staff record: '.$e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Display the specified staff profile.
     */
    public function show(Staff $staff): View
    {
        $this->authorizeStaffTenant($staff);

        $staff->load([
            'school',
            'user.roles.permissions',
            'classTeacherSections.academicClass',
            'subjectAssignments.academicClass',
            'subjectAssignments.subject',
            'subjectAssignments.academicYear',
        ]);

        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff profile.
     */
    public function edit(Staff $staff): View
    {
        $this->authorizeStaffTenant($staff);

        $staff->load('user.roles');
        $roles = Role::query()->orderBy('name')->get();

        $departments = Staff::query()
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');

        $designations = Staff::query()
            ->whereNotNull('designation')
            ->distinct()
            ->pluck('designation');

        return view('admin.staff.edit', compact('staff', 'roles', 'departments', 'designations'));
    }

    /**
     * Update the specified staff record in storage.
     */
    public function update(UpdateStaffRequest $request, Staff $staff): RedirectResponse
    {
        $this->authorizeStaffTenant($staff);

        $data = $request->validated();

        DB::beginTransaction();

        try {
            // 1. Handle Photo Upload Replacement
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $uploadDir = public_path('uploads/staff');

                if (! File::isDirectory($uploadDir)) {
                    File::makeDirectory($uploadDir, 0755, true);
                }

                $fileName = 'staff_'.time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
                $file->move($uploadDir, $fileName);

                // Delete old photo if it exists
                if ($staff->photo && File::exists(public_path($staff->photo))) {
                    File::delete(public_path($staff->photo));
                }

                $data['photo'] = 'uploads/staff/'.$fileName;
            }

            // 2. Update or Provision Linked User Account
            if ($staff->user) {
                $userData = [
                    'name' => trim("{$data['first_name']} {$data['last_name']}"),
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? null,
                    'status' => $data['status'],
                ];

                if (! empty($data['password'])) {
                    $userData['password'] = Hash::make($data['password']);
                }

                if (isset($data['photo'])) {
                    $userData['avatar'] = $data['photo'];
                }

                $staff->user->update($userData);

                if (! empty($data['role_id'])) {
                    $staff->user->roles()->sync([$data['role_id']]);
                }
            } elseif (! empty($data['password']) && ! empty($data['role_id'])) {
                $schoolId = $staff->school_id ?? (app()->has('current_school_id') ? app('current_school_id') : 1);
                $newUser = User::create([
                    'school_id' => $schoolId,
                    'name' => trim("{$data['first_name']} {$data['last_name']}"),
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'phone' => $data['phone'] ?? null,
                    'status' => $data['status'],
                    'avatar' => $data['photo'] ?? $staff->photo,
                ]);

                $newUser->roles()->sync([$data['role_id']]);
                $data['user_id'] = $newUser->id;
            }

            $staff->update($data);

            DB::commit();

            ToastMagic::success('Staff profile updated successfully.');

            return redirect()->route('admin.staff.show', $staff);
        } catch (\Throwable $e) {
            DB::rollBack();

            ToastMagic::error('Failed to update staff profile: '.$e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Soft delete the specified staff record.
     */
    public function destroy(Staff $staff): RedirectResponse
    {
        $this->authorizeStaffTenant($staff);

        $staff->delete();

        if ($staff->user) {
            $staff->user->update(['status' => 'inactive']);
        }

        ToastMagic::success('Staff record moved to trash.');

        return redirect()->route('admin.staff.index');
    }

    /**
     * Restore a soft-deleted staff record.
     */
    public function restore(int $id): RedirectResponse
    {
        $staff = Staff::onlyTrashed()->findOrFail($id);
        $this->authorizeStaffTenant($staff);

        $staff->restore();

        if ($staff->user) {
            $staff->user->update(['status' => 'active']);
        }

        ToastMagic::success('Staff record successfully restored.');

        return back();
    }

    /**
     * Permanently remove the specified staff record.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $staff = Staff::onlyTrashed()->findOrFail($id);
        $this->authorizeStaffTenant($staff);

        if ($staff->photo && File::exists(public_path($staff->photo))) {
            File::delete(public_path($staff->photo));
        }

        if ($staff->user) {
            $staff->user->delete();
        }

        $staff->forceDelete();

        ToastMagic::success('Staff record permanently purged.');

        return back();
    }

    /**
     * Toggle staff and linked user account activation status.
     */
    public function updateStatus(UpdateStaffStatusRequest $request, Staff $staff): JsonResponse|RedirectResponse
    {
        $this->authorizeStaffTenant($staff);

        $status = $request->validated('status');

        $staff->update(['status' => $status]);

        if ($staff->user) {
            $staff->user->update(['status' => $status]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Staff status updated to {$status}.",
                'status' => $status,
            ]);
        }

        ToastMagic::success("Status changed to {$status}.");

        return back();
    }

    /**
     * Ensure the staff record belongs to the active school tenant.
     */
    protected function authorizeStaffTenant(Staff $staff): void
    {
        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (Auth::guard('web')->user()?->school_id ?? session('current_school_id'));

        if ($schoolId !== null && (int) $staff->school_id !== (int) $schoolId) {
            abort(404);
        }
    }
}
