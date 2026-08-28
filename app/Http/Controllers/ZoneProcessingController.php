<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ZoneProcessing;
use Illuminate\Support\Facades\Redirect;
class ZoneProcessingController extends Controller
{
    public function index()
    {
        $zoneProcessings = ZoneProcessing::all();
        return view('admin.zoneprocessing.index', compact('zoneProcessings'));
    }

    public function create()
    {
        return view('admin.zoneprocessing.create');
    }

    public function edit($id)
{
    $zoneProcessing = ZoneProcessing::findOrFail($id);
    return view('admin.zoneprocessing.edit', compact('zoneProcessing'));
}
public function update(Request $request, $id)
{
    $zoneProcessing = ZoneProcessing::findOrFail($id);

    $request->validate([
            'zone_name' => 'required',
            'processing_time' => 'required',
            'shipping_time' => 'required',
            'delivery_time' => 'required',
            'bulk_delivery_charges' => 'required',
            'single_delivery_charges' => 'required',
            'min_order' => 'required',
            'order_above' => 'required',
            'pay_on_delivery' => 'required',
            'next_day_timing' => 'required',
            'same_day_timing' => 'required',
            'same_day_slot' => 'required',
            'next_day_slot' => 'required',
            'week_day_slot' => 'required',
            'packing_charge' => 'required',
            'others_charges' => 'required',
            'status' => 'required',
            'regular_days' => 'nullable|boolean',
            'delivery_days' => 'nullable|array',
            'delivery_days.*' => 'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
        ]);

    // dd($request->pay_on_delivery);


    $data = $request->all();
    $data['regular_days'] = $request->has('regular_days') ? 1 : 0;
    $data['delivery_days'] = $request->delivery_days ?? [];

    $zoneProcessing->update($data);

    return redirect()->route('zoneprocessings.index')->with('success', 'Zone Processing updated successfully.');
}

    public function store(Request $request)
    {
          $request->validate([
            'zone_name' => 'required',
            'processing_time' => 'required',
            'shipping_time' => 'required',
            'delivery_time' => 'required',
            'bulk_delivery_charges' => 'required',
            'single_delivery_charges' => 'required',
            'order_above' => 'required',
            'min_order' => 'required',
            'next_day_timing' => 'required',
            'same_day_timing' => 'required',
            'same_day_slot' => 'required',
            'next_day_slot' => 'required',
            'week_day_slot' => 'required',
            'pay_on_delivery' => 'required',
            'packing_charge' => 'required',
            'others_charges' => 'required',
            'status' => 'required',
            'regular_days' => 'nullable|boolean',
            'delivery_days' => 'nullable|array',
            'delivery_days.*' => 'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
        ]);


        $existingRecord = ZoneProcessing::where('zone_name', $request->zone_name)->first();

        if ($existingRecord) {
            return redirect()->route('zoneprocessings.create')->withInput()->withErrors(['zone_name' => 'Zone with this name already exists.']);
        }



         $data = $request->all();
        // dd($data);
        $data['regular_days'] = $request->has('regular_days') ? 1 : 0;
        $data['delivery_days'] = $request->delivery_days ?? [];

        ZoneProcessing::create($data);

        return redirect()->route('zoneprocessings.index')->with('success', 'Zone Processing created successfully.');
    }

    public function statusUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Active,Inactive',
        ]);

        $pincode = ZoneProcessing::findOrFail($id);
        $pincode->status = $request->input('status');
        $pincode->save();

        return Redirect::back();
    }


    public function destroy($id)
    {
        $pincode = ZoneProcessing::findOrFail($id);
        $pincode->delete();
        return redirect()->route('zoneprocessings.index')->with('success', 'Pincode deleted successfully.');
    }


}
