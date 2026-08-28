<?php

namespace App\Http\Controllers;
use App\Exports\PincodeExport;
use App\Http\Controllers\Controller;
use App\Imports\PincodeImport;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Pincode;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\ZoneProcessing;

class PincodeController extends Controller


{

    public function createNew()
    {
        $zones = ZoneProcessing::all();
        return view('admin.pincode.create', compact('zones')); // Assuming you have a create blade file
    }
    public function index($zone_id)
    {
        $pincode = Pincode::where('zone_id', $zone_id)->latest()->get();
        return view('admin.pincode.index')->with(compact('pincode'));
    }




    public function exportpincode()
    {
        $timestamp = now()->format('Y-m-d:H:i:s');
        $filename = $timestamp . 'Pincode_list.xlsx';
        return Excel::download(new PincodeExport, $filename);
    }


    public function getPincode(Request $request)
    {
        $pincodeData = Pincode::where('status', 'Active')->latest()->get();
        return response()->json($pincodeData);
    }


    public function store(Request $request)
    {
        $request->validate([
            'pincode' => 'required|string|max:255',
            'zone_id' => 'required|exists:zoneprocessing,id',
        ]);

        $existingPincode = Pincode::where('pincode', $request->input('pincode'))
            ->where('zone_id', $request->input('zone_id'))
            ->first();

        if ($existingPincode) {
            return redirect()->back()->withInput()->with('pincode', 'Pincode already exists for this zone.');
        }

        $pincode = new Pincode();
        $pincode->pincode = $request->input('pincode');
        $pincode->zone_id = $request->input('zone_id');
        $pincode->save();

        return redirect()->back()->with('success', 'Pincode added successfully.');
    }




    public function edit($id)
    {
        $pincode = pincode::findOrFail($id);
        $zones = ZoneProcessing::all();
        return view('admin.pincode.edit', compact('pincode','zones'));
    }


    public function statusUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Active,Inactive',
        ]);

        $pincode = pincode::findOrFail($id);
        $pincode->status = $request->input('status');
        $pincode->save();

        return Redirect::back();
    }


    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'pincode' => 'required|string|max:255',
            'zone_id' => 'required|exists:zoneprocessing,id',
        ]);

        $pincode = Pincode::findOrFail($id);


        if ($pincode->pincode !== $request->input('pincode')) {
            $existingPincode = Pincode::where('pincode', $request->input('pincode'))
                ->where('zone_id', $request->input('zone_id'))
                ->first();

            if ($existingPincode) {
                return redirect()->back()->with('error', 'Pincode already exists for this location.');
            }
        }


        $pincode->pincode = $request->input('pincode');
        $pincode->zone_id = $request->input('zone_id');
        $pincode->save();
        return redirect()->route('pincode.index')->with('success', 'Pincode updated successfully.');
    }




    public function destroy($id)
    {
        $pincode = pincode::findOrFail($id);
        $pincode->delete();
        return redirect()->route('pincode.index')->with('success', 'Pincode deleted successfully.');
    }

    public function pincodeImportFiles(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        Storage::disk('public')->put($request->file->getClientOriginalName(), file_get_contents($request->file));

        Excel::import(new PincodeImport, $request->file->getClientOriginalName(), 'public');

        File::delete(storage_path('app/public/' . $request->file->getClientOriginalName()));

        return redirect()->back()->with('success', 'Pincode added successfully.');
    }

    function checkPincode($pincode)
    {
        $pincodeExists = pincode::where('pincode', $pincode)->first();
        return response()->json($pincodeExists);
    }
}
