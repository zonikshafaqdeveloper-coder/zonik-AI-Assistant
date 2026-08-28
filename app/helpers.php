<?php

use Illuminate\Support\Facades\DB;

function hasPermission($permission)
{
    if (!auth()->guard('admin')->check()) {
        return false;
    }

    $admin = auth()->guard('admin')->user();

    if ($admin->role_id == 1) {
        return true;
    }

    return DB::table('role_permissions')
        ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
        ->where('role_permissions.role_id', $admin->role_id)
        ->where('permissions.name', $permission)
        ->exists();
}
