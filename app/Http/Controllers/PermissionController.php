<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = DB::table('permissions')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'module' => 'required',
            'name'   => 'required|unique:permissions,name'
        ]);

        DB::table('permissions')->insert([
            'module'     => $request->module,
            'name'       => $request->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully');
    }

    public function edit($id)
    {
        $permission = DB::table('permissions')->where('id', $id)->first();
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'module' => 'required',
            'name'   => 'required|unique:permissions,name,' . $id
        ]);

        DB::table('permissions')->where('id', $id)->update([
            'module'     => $request->module,
            'name'       => $request->name,
            'updated_at' => now(),
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully');
    }

    public function destroy($id)
    {
        DB::table('role_permissions')->where('permission_id', $id)->delete();
        DB::table('permissions')->where('id', $id)->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
}
