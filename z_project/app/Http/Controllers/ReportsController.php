<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
     

public function nearExpiryStock()
{
    $stocks = DB::table('rack_stocks as rs')
        ->join('products as p', 'p.id', '=', 'rs.product_id')
        ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
        ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
        ->select(
            'rs.id',
            'p.id as item_code',
            'rs.product_id',
            'rs.batch_no',
            'rs.expiry_date',
            'p.product_name as item',
            'p.unit',
            'rs.quantity as qty',
            'p.brands as brand',
            'c.category_name as category',
            'p.supplier_traced as supplier_name',
            'rs.stock_receiving_id',
            'rs.expiry_date',
            DB::raw('DATEDIFF(rs.expiry_date, CURDATE()) as days_to_expiry'),
            'rs.rack_no',
            'rs.level_no',
            'rs.slot_no'
        )
        ->whereNotNull('rs.expiry_date')
        ->where('rs.quantity', '>', 0)
        ->where('rs.is_on_sale', false)
        ->whereRaw('DATEDIFF(rs.expiry_date, CURDATE()) BETWEEN 0 AND 60')
        ->orderBy('rs.expiry_date', 'asc')
        ->get();

    return view('admin.reports.near_expiry', compact('stocks'));
}

public function expiredStock()
{
    $stocks = DB::table('rack_stocks as rs')
        ->join('products as p', 'p.id', '=', 'rs.product_id')
        ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
        ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
        ->select(
            'rs.id',
            'p.id as item_code',
            'p.product_name as item',
            'rs.product_id',
            'rs.batch_no',
            'rs.stock_receiving_id',
            'p.unit',
            'rs.quantity as qty',
            'p.brands as brand',
            'c.category_name as category',
            'p.supplier_traced as supplier_name',
            'rs.expiry_date',
            DB::raw('ABS(DATEDIFF(rs.expiry_date, CURDATE())) as days_passed_expiry'),
            'rs.rack_no',
            'rs.level_no',
            'rs.slot_no'
        )
        ->whereNotNull('rs.expiry_date')
        ->where('rs.quantity', '>', 0)
        ->whereRaw('rs.expiry_date < CURDATE()')
        ->orderBy('rs.expiry_date', 'asc')
        ->get();

    return view('admin.reports.expired_products', compact('stocks'));
}

// public function putOnSale(Request $request)
// {
//     DB::table('rack_stocks')
//         ->where('product_id', $request->product_id)
//         ->where('batch_no', $request->batch_no)
//         ->where('expiry_date', $request->expiry_date)
//         ->update([
//             'is_on_sale' => true
//         ]);

//     return redirect()->back()->with('success', 'Product marked for urgent sale');
// }

public function putOnSale(Request $request)
{
    DB::table('rack_stocks')
        ->where('id', $request->id)
        ->update([
            'is_on_sale'      => true,
            'put_on_sale_at'  => now()
        ]);

    return redirect()->back()->with('success', 'Product marked for urgent sale.');
}

public function urgentSaleStock()
{
    $stocks = DB::table('rack_stocks as rs')
        ->join('products as p', 'p.id', '=', 'rs.product_id')
        ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
        ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
        ->select(
            'p.id as item_code',
            'rs.product_id',
            'rs.id',
            'rs.is_available_for_sale',
            'rs.batch_no',
            'rs.expiry_date',
            'p.product_name as item',
            'p.unit',
            'rs.quantity as qty',
            'p.brands as brand',
            'c.category_name as category',
            'p.supplier_traced as supplier_name',
            DB::raw('DATEDIFF(rs.expiry_date, CURDATE()) as days_to_expiry'),
            'rs.rack_no',
            'rs.level_no',
            'rs.slot_no'
        )
        ->where('rs.is_on_sale', true) 
        ->where('rs.quantity', '>', 0)
        ->whereRaw('DATEDIFF(rs.expiry_date, CURDATE()) >= 0')
        ->orderBy('rs.expiry_date', 'asc')
        ->get();

    return view('admin.reports.urgent_sale', compact('stocks'));
}


// public function removeFromSale(Request $request)
// {
//     DB::table('rack_stocks')
//         ->where('product_id', $request->product_id)
//         ->where('batch_no', $request->batch_no)
//         ->where('expiry_date', $request->expiry_date)
//         ->update([
//             'is_on_sale' => false
//         ]);

//     return redirect()->back()->with('success', 'Product moved back to Near Expiry');
// }


public function removeFromSale(Request $request)
{
    DB::table('rack_stocks')
        ->where('id', $request->id)
        ->update([
            'is_on_sale' => false,
            'put_on_sale_at' => null
        ]);

    return redirect()->back()->with('success', 'Product moved back to Near Expiry.');
}
   
public function togglePickList(Request $request)
{
    try {

        $stock = DB::table('rack_stocks')
            ->where('id', $request->id)
            ->first();

        if (!$stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stock not found'
            ], 404);
        }

        DB::table('rack_stocks')
            ->where('id', $request->id)
            ->update([
                'is_available_for_sale' => $request->status
            ]);

        return response()->json([
            'success' => true,
            'message' => $request->status
                ? 'Added to Pick List'
                : 'Removed from Pick List'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}        
            

        

    
}
