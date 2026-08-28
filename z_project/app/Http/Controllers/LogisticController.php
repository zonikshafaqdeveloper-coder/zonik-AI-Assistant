<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderLogistic;
use App\Models\DeliveryManagement;
use App\Exports\LogisticsExport;
use Maatwebsite\Excel\Facades\Excel;

class LogisticController extends Controller
{
    public function index()
{
    $orders = Order::with(['outlet', 'pickList', 'latestDelivery','logistic.mode','logistics'])
        ->withSum('items as picked_qty', 'quantity')          
        ->withSum('originalItems as ordered_qty', 'quantity')
         ->where('status', '!=', 'draft')
        ->orderBy('created_at', 'desc')
        ->get();
        
        // dd($orders);

    return view('admin.logistics.index', compact('orders'));
}

public function store(Request $request)
{
    $request->validate([
        'order_id'          => 'required|exists:orders,id',
        'rack_no'           => 'required',
        'no_of_box'         => 'required|integer|min:1',
        'delivery_priority'=> 'required|in:P1,P2,P3,P4,P5,P6,P7,P8,P9,P10',
        'mode_of_delivery' => 'required|exists:delivery_modes,id',
    ]);

    OrderLogistic::updateOrCreate(
        ['order_id' => $request->order_id],
        [
            'rack_no'            => $request->rack_no,
            'no_of_box'          => $request->no_of_box,
            'delivery_priority' => $request->delivery_priority,
            'mode_of_delivery_id'=> $request->mode_of_delivery,
        ]
    );

    return response()->json(['status' => true]);
}
public function storeSingle(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'field'    => 'required|string',
        'value'    => 'nullable'
    ]);

    $allowed = [
        'rack_no',
        'no_of_box',
        'delivery_priority',
        'mode_of_delivery_id'
    ];

    if (!in_array($request->field, $allowed)) {
        return response()->json(['error' => 'Invalid field'], 400);
    }

    $logistic = \App\Models\OrderLogistic::firstOrCreate([
        'order_id' => $request->order_id
    ]);

    $logistic->{$request->field} = $request->value;
    $logistic->save();

    return response()->json(['status' => true]);
}

public function export()
{
    return Excel::download(new LogisticsExport, 'logistics.xlsx');
}

    public function updateStatus(Request $request)
    {
        $delivery = DeliveryManagement::where('order_id', $request->order_id)
                            ->latest()
                            ->first();
    
        if (!$delivery) {
            return response()->json(['error' => 'Delivery not found'], 404);
        }
    
        if ($delivery->delivery_status === 'pending' && $request->status === 'hold') {
            $delivery->delivery_status = 'hold';
        }
        elseif ($delivery->delivery_status === 'hold' && $request->status === 'pending') {
            $delivery->delivery_status = 'pending';
        }
        else {
            return response()->json(['error' => 'Invalid status transition'], 403);
        }
    
        $delivery->save();
    
        return response()->json(['success' => true]);
    }


}
