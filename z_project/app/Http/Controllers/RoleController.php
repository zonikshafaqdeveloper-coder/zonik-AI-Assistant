<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RoleController extends Controller
{
    public function index()
{
    $roles = DB::table('roles')
        ->orderBy('id', 'desc')
        ->get();

    return view('admin.roles.index', compact('roles'));
}


    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        DB::table('roles')->insert([
            'name' => $request->name
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    public function edit($id)
    {
        $role = DB::table('roles')->where('id', $id)->first();
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id
        ]);

        DB::table('roles')->where('id', $id)->update([
            'name' => $request->name
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        DB::table('role_permissions')->where('role_id', $id)->delete();
        DB::table('roles')->where('id', $id)->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
