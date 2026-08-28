<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PreMaterialShortLog;
use App\Models\PostMaterialShortLog;

class ShortMaterialLogController extends Controller
{
    
//  comment on 2-04-26  
//     public function index()
// {
//     $orders = DB::table('original_items as oi')

//         ->leftJoin('order_items as oi2', function ($join) {
//             $join->on('oi.order_id', '=', 'oi2.order_id')
//                  ->on('oi.product_id', '=', 'oi2.product_id');
//         })

//         ->join('orders as o', 'o.id', '=', 'oi.order_id')
//         ->join('users as u', 'u.id', '=', 'o.user_id')
//         ->join('delivery_management as dm', 'dm.order_id', '=', 'o.id')

//         ->whereRaw('(oi.quantity - COALESCE(oi2.quantity,0)) > 0')
//         ->where('dm.delivery_status', '!=', 'pending')

//         ->select(
//             'o.id as order_id',
//             'u.name as customer_name',
//             'o.created_at as order_date'
//         )

//         ->distinct()  
//         ->orderByDesc('o.created_at')
//         ->get();

//     return view('admin.short_material.index', compact('orders'));
// }

// public function preindex()
// {
//     $logs = PreMaterialShortLog::with(['product', 'order.outlet', 'stock'])
//                 ->latest()
//                 ->get();

//     return view('admin.short_material.preindex', compact('logs'));
// }

// public function preindex()
// {
//     $logs = PreMaterialShortLog::with([
//                     'product',
//                     'order.outlet',
//                     'order.delivery'
//                 ])
//                 ->whereHas('order')
//                 ->whereDoesntHave('order.delivery', function ($q) {
//                     $q->whereIn('delivery_status', ['delivered', 'cancelled']);
//                 })
//                 ->latest()
//                 ->get();

//     return view('admin.short_material.preindex', compact('logs'));
// }


public function preindex()
{
    $logs = PreMaterialShortLog::with([
                    'product',
                    'order.outlet',
                    'order.delivery'
                ])
                ->whereHas('order')
                ->whereHas('order.delivery', function ($q) {
                    $q->where('delivery_status', 'pending');
                })
                ->latest()
                ->get();

    return view('admin.short_material.preindex', compact('logs'));
}



public function updateLog(Request $request)
{
    $log = PreMaterialShortLog::findOrFail($request->id);

    $log->update([
        'comment' => $request->comment,
        'lost_value' => $request->lost_value ?? 0
    ]);

    return response()->json(['success' => true]);
}

public function savePostShortLog(Request $request)
{
    PostMaterialShortLog::updateOrCreate(
        [
            'order_id' => $request->order_id,
            'product_id' => $request->product_id
        ],
        [
            'short_qty' => $request->short_qty,
            'comment' => $request->comment,
             'lost_value' => $request->lost_value ?? 0
        ]
    );

    return response()->json(['success' => true]);
}


public function index()
{
    $items = DB::table('original_items as oi')

        ->leftJoin('order_items as oi2', function ($join) {
            $join->on('oi.order_id', '=', 'oi2.order_id')
                 ->on('oi.product_id', '=', 'oi2.product_id');
        })
        
        ->leftJoin('post_material_short_logs as logs', function ($join) {
            $join->on('logs.order_id', '=', 'oi.order_id')
                ->on('logs.product_id', '=', 'oi.product_id');
        })
        
        ->leftJoin('product_stocks as ps', 'ps.product_id', '=', 'oi.product_id')

        ->join('orders as o', 'o.id', '=', 'oi.order_id')
        ->join('users as u', 'u.id', '=', 'o.user_id')
        ->join('users as outlet', 'outlet.id', '=', 'o.outlet_id')
        ->join('products as p', 'p.id', '=', 'oi.product_id')
        ->join('delivery_management as dm', 'dm.order_id', '=', 'o.id')

        ->whereNotIn('dm.delivery_status', ['pending', 'cancelled'])
        ->whereRaw('(oi.quantity - COALESCE(oi2.quantity,0)) > 0')

        ->select(
            'o.id as order_id',
            'oi.product_id',
            'u.name as customer_name',
            'outlet.outlet_name as outlet_name',
            'o.created_at as order_date',
            'p.product_name',
            'p.brands as brand',
            'oi.quantity as ordered_qty',
            DB::raw('COALESCE(oi2.quantity,0) as supplied_qty'),
            DB::raw('(oi.quantity - COALESCE(oi2.quantity,0)) as short_qty'),
            'oi.offer_price',
            
             DB::raw('COALESCE(ps.total_stock, 0) as live_stock'),
            
            'logs.comment',
            'logs.lost_value'
        )

        ->orderByDesc('o.created_at')
        ->get();
        
        foreach ($items as $item) {

    $lostValue = $item->short_qty * ($item->offer_price ?? 0);

    $existing = DB::table('post_material_short_logs')
        ->where('order_id', $item->order_id)
        ->where('product_id', $item->product_id)
        ->first();

    if ($existing) {
        
        DB::table('post_material_short_logs')
            ->where('order_id', $item->order_id)
            ->where('product_id', $item->product_id)
            ->update([
                'short_qty'  => $item->short_qty,
                'lost_value' => $lostValue,
            ]);
    } else {
       
        DB::table('post_material_short_logs')->insert([
            'order_id'   => $item->order_id,
            'product_id' => $item->product_id,
            'short_qty'  => $item->short_qty,
            'lost_value' => $lostValue,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

    $items = $items->groupBy('order_id');

    return view('admin.short_material.index', compact('items'));
}

public function show($orderId)
{
    $items = DB::table('original_items as oi')

        ->leftJoin('order_items as oi2', function ($join) {
            $join->on('oi.order_id', '=', 'oi2.order_id')
                 ->on('oi.product_id', '=', 'oi2.product_id');
        })

        ->join('orders as o', 'o.id', '=', 'oi.order_id')
        ->join('users as u', 'u.id', '=', 'o.user_id')
        ->join('products as p', 'p.id', '=', 'oi.product_id')
        ->join('delivery_management as dm', 'dm.order_id', '=', 'o.id')

        ->where('o.id', $orderId)
        ->where('dm.delivery_status', '!=', 'pending')
        ->whereRaw('(oi.quantity - COALESCE(oi2.quantity,0)) > 0')

        ->select(
            'o.id as order_id',
            'u.name as customer_name',
            'p.product_name',
            'p.brands as brand',
            'oi.quantity as ordered_qty',
            DB::raw('COALESCE(oi2.quantity,0) as supplied_qty'),
            DB::raw('(oi.quantity - COALESCE(oi2.quantity,0)) as short_qty'),
            'o.created_at as order_date'
        )

        ->get();

    return view('admin.short_material.show', compact('items'));
}

// Add for export to excel function: 

public function export()
{
    $items = DB::table('original_items as oi')

        ->leftJoin('order_items as oi2', function ($join) {
            $join->on('oi.order_id', '=', 'oi2.order_id')
                 ->on('oi.product_id', '=', 'oi2.product_id');
        })

        ->join('orders as o', 'o.id', '=', 'oi.order_id')
        ->join('users as u', 'u.id', '=', 'o.user_id')
        ->join('users as outlet', 'outlet.id', '=', 'o.outlet_id')
        ->join('products as p', 'p.id', '=', 'oi.product_id')
        ->join('delivery_management as dm', 'dm.order_id', '=', 'o.id')

        ->where('dm.delivery_status', '!=', 'pending')
        ->whereRaw('(oi.quantity - COALESCE(oi2.quantity,0)) > 0')

        ->select(
            'o.id as order_id',
            'outlet.outlet_name',
            'o.created_at as order_date',
            'p.product_name',
            'p.brands as brand',
            'oi.quantity as ordered_qty',
            DB::raw('COALESCE(oi2.quantity,0) as supplied_qty'),
            DB::raw('(oi.quantity - COALESCE(oi2.quantity,0)) as short_qty')
        )
        ->orderByDesc('o.created_at')
        ->get();

    $fileName = 'short-material-log.csv';

    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
    ];

    $callback = function () use ($items) {
        $file = fopen('php://output', 'w');

        fputcsv($file, [
            'Order ID',
            'Outlet Name',
            'Order Date',
            'Product',
            'Brand',
            'Ordered Qty',
            'Supplied Qty',
            'Short Qty'
        ]);

        foreach ($items as $item) {
            fputcsv($file, [
                $item->order_id,
                $item->outlet_name,
                $item->order_date,
                $item->product_name,
                $item->brand,
                $item->ordered_qty,
                $item->supplied_qty,
                $item->short_qty,
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
