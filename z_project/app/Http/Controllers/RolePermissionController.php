<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RolePermissionController extends Controller
{
     public function edit($roleId)
    {
        $role = DB::table('roles')->where('id', $roleId)->first();

        if (!$role) {
            abort(404);
        }

        $permissions = DB::table('permissions')
            ->orderBy('module')
            ->get()
            ->groupBy('module');

        $assignedPermissions = DB::table('role_permissions')
            ->where('role_id', $roleId)
            ->pluck('permission_id')
            ->toArray();

        return view('admin.roles.assign-permissions', compact(
            'role',
            'permissions',
            'assignedPermissions'
        ));
    }

    /**
     * Update permissions for role
     */
    public function update(Request $request, $roleId)
    {
        DB::table('role_permissions')
            ->where('role_id', $roleId)
            ->delete();

        if ($request->permissions) {
            foreach ($request->permissions as $permissionId) {
                DB::table('role_permissions')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        return redirect()->route('roles.index')
            ->with('success', 'Permissions updated successfully');
    }
}
