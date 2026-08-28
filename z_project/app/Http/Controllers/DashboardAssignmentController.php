<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardAssignmentController extends Controller
{
    public function index()
    {
        $assignments = DB::table('roles')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.dashboard-assignment.index', compact('assignments'));
    }

    public function edit($roleId)
    {
        $role = DB::table('roles')->where('id', $roleId)->first();

        if (!$role) {
            abort(404);
        }

        $sections = DB::table('dashboard_sections')
            ->orderBy('id')
            ->get();

        $assignedSections = DB::table('role_dashboard_sections')
            ->where('role_id', $roleId)
            ->pluck('dashboard_section_id')
            ->toArray();

        return view('admin.dashboard-assignment.edit', compact(
            'role',
            'sections',
            'assignedSections'
        ));
    }

    public function update(Request $request, $roleId)
    {
        DB::table('role_dashboard_sections')
            ->where('role_id', $roleId)
            ->delete();

        if ($request->sections) {
            foreach ($request->sections as $sectionId) {
                DB::table('role_dashboard_sections')->insert([
                    'role_id' => $roleId,
                    'dashboard_section_id' => $sectionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('dashboard-assignment.index')
            ->with('success', 'Dashboard sections updated successfully');
    }
}