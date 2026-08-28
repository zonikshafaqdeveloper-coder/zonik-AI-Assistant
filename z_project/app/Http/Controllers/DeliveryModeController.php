<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryMode;
use App\Models\OrderLogistic;

class DeliveryModeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:delivery_modes,name'
        ]);

        $mode = DeliveryMode::create([
            'name' => $request->name
        ]);

        return response()->json($mode);
    }

    public function list()
    {
        return response()->json(
            DeliveryMode::orderBy('name')->get()
        );
    }
    
    public function delete(Request $request)
{
    $mode = DeliveryMode::find($request->id);

    if (!$mode) {
        return response()->json(['message' => 'Mode not found'], 404);
    }

    // Check if used anywhere
    // $isUsed = OrderLogistic::where('mode_of_delivery_id', $mode->id)->exists();

    // if ($isUsed) {
    //     return response()->json([
    //         'message' => 'This delivery mode is already used and cannot be deleted'
    //     ], 422);
    // }

    $mode->delete();

    return response()->json([
        'message' => 'Delivery mode deleted successfully'
    ]);
}
}
