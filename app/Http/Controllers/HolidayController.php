<?php

namespace App\Http\Controllers;
use App\Exports\HolidaysExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
 use App\Imports\HolidaysImport;

use App\Models\Holiday;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::all();
        return view('admin.holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('admin.holidays.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'holiday_date' => 'required',
            'holiday_name' => 'required',
        ]);

        Holiday::create($request->all());

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday created successfully.');
    }

    public function edit(Holiday $holiday)
    {
        return view('admin.holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'holiday_date' => 'required',
            'holiday_name' => 'required',
        ]);

        $holiday->update($request->all());

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday updated successfully');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday deleted successfully');
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        Storage::disk('public')->put($request->file->getClientOriginalName(), file_get_contents($request->file));

        Excel::import(new HolidaysImport, $request->file->getClientOriginalName(), 'public');

        File::delete(storage_path('app/public/' . $request->file->getClientOriginalName()));

        return redirect()->back()->with('success', 'Holidays added successfully.');
    }

    public function export()
    {
        return Excel::download(new HolidaysExport, 'holidays.xlsx');
    }

}
