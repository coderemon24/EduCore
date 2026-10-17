<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\School;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display a listing of institutional roles and permissions.
     */
    public function index(): View
    {
        $roles = Role::query()
            ->withCount(['users', 'permissions'])
            ->with('permissions')
            ->orderByDesc('is_system')
            ->orderBy('name')
            ->get();

        $permissionsByGroup = Permission::all()->groupBy('group');

        return view('admin.roles.index', compact('roles', 'permissionsByGroup'));
    }

    /**
     * Show the form for creating a new custom role.
     */
    public function create(): View
    {
        $permissionsByGroup = Permission::all()->groupBy('group');

        return view('admin.roles.create', compact('permissionsByGroup'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $schoolId = app()->has('current_school_id') ? app('current_school_id') : (School::value('id') ?? 1);

        $role = Role::create([
            'school_id' => $schoolId,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'is_system' => false,
        ]);

        if (! empty($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        ToastMagic::success("Role '{$role->name}' created successfully.");

        return redirect()->route('admin.roles.index');
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): View
    {
        $this->authorizeRoleTenant($role);

        $role->load('permissions');
        $permissionsByGroup = Permission::all()->groupBy('group');
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissionsByGroup', 'rolePermissionIds'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->authorizeRoleTenant($role);

        $data = $request->validated();

        $updateData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ];

        // Protect system role slug
        if (! $role->is_system) {
            $updateData['slug'] = $data['slug'];
        }

        $role->update($updateData);

        if (isset($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        } else {
            $role->permissions()->detach();
        }

        ToastMagic::success("Role '{$role->name}' updated successfully.");

        return redirect()->route('admin.roles.index');
    }

    /**
     * Remove the specified custom role from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $this->authorizeRoleTenant($role);

        if ($role->is_system) {
            ToastMagic::error('System roles are essential for institution operations and cannot be deleted.');

            return back();
        }

        if ($role->users()->count() > 0) {
            ToastMagic::error('Cannot delete this role because active staff members are currently assigned to it.');

            return back();
        }

        $roleName = $role->name;
        $role->permissions()->detach();
        $role->delete();

        ToastMagic::success("Role '{$roleName}' successfully deleted.");

        return redirect()->route('admin.roles.index');
    }

    /**
     * Ensure the role belongs to the active school tenant.
     */
    protected function authorizeRoleTenant(Role $role): void
    {
        $schoolId = app()->has('current_school_id')
            ? app('current_school_id')
            : (Auth::guard('web')->user()?->school_id ?? session('current_school_id'));

        if ($schoolId !== null && (int) $role->school_id !== (int) $schoolId) {
            abort(404);
        }
    }
}
