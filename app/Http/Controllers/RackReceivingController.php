<?php

namespace App\Http\Controllers;

use App\Models\StockReceiving;
use App\Models\RackStock;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RackReceivingController extends Controller
{
        public function index()
{
    $grns = StockReceiving::whereIn('status', ['approved', 'approved_with_changes'])
        ->with(['vendor', 'rackStocks'])
        ->orderBy('id', 'desc')
        ->get();

    return view('admin.racks_receiving.index', compact('grns'));
}


    // Show rack allocation screen
    public function create(StockReceiving $grn)
    {
        $grn->load(['items.product']);
        // dd($grn->load(['items.product']));

        return view('admin.racks_receiving.create', compact('grn'));
    }

    // Store rack allocations
  public function store(Request $request, $grnId)
{
    try {
        $data = $request->json()->all();
        
        // dd($data);

        // validate
        if (empty($data['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'No rack allocation data found'
            ], 422);
        }
        
        $alreadyAllocated = RackStock::where('stock_receiving_id', $grnId)->exists();

        if ($alreadyAllocated) {
            return response()->json([
                'success' => false,
                'already_allocated' => true,
                'message' => 'Rack allocation for this GRN has already been saved.',
                'redirect_url' => route('admin.rack.receiving.index')
            ], 422);
        }

        DB::beginTransaction();

        foreach ($data['items'] as $item) {
            RackStock::create([
                'stock_receiving_id' => $grnId,
                'product_id'         => $item['product_id'],
                'rack_no'            => $item['rack_no'],
                'level_no'           => $item['level_no'],
                'slot_no'            => $item['slot_no'],
                'quantity'           => $item['quantity'],
                'batch_no'           => $item['batch_no'] ?? null,
                'expiry_date'        => $item['expiry_date'] ?? null,
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Rack allocation saved successfully',
            'redirect_url' => route('admin.rack.receiving.index')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function edit($grnId)
{
    $grn = StockReceiving::with([
        'items.product',
        'rackStocks.product'
    ])->findOrFail($grnId);

    return view('admin.racks_receiving.edit', compact('grn'));
}


// public function update(Request $request, $grnId)
// {
    
//     // dd($request->all());
//     DB::beginTransaction();

//     try {
//         RackStock::where('stock_receiving_id', $grnId)->delete();

//         foreach ($request->items as $item) {
//             RackStock::create([
//                 'stock_receiving_id' => $grnId,
//                 'product_id'         => $item['product_id'],
//                 'rack_no'            => $item['rack_no'],
//                 'level_no'           => $item['level_no'],
//                 'slot_no'            => $item['slot_no'],
//                 'quantity'           => $item['quantity'],
//                 'batch_no'           => $item['batch_no'] ?? null,
//                 'expiry_date'        => $item['expiry_date'] ?? null,
//             ]);
//         }

//         DB::commit();

//         return response()->json([
//             'success' => true,
//             'message' => 'Rack allocation updated successfully',
//              'redirect_url' => route('admin.rack.receiving.index')
//         ]);

//     } catch (\Exception $e) {
//         DB::rollBack();

//         return response()->json([
//             'success' => false,
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }



// public function update(Request $request, $grnId)
// {
//     DB::beginTransaction();

//     try {

//         RackStock::where('stock_receiving_id', $grnId)->delete();

//         foreach ($request->items as $item) {

//             RackStock::create([
//                 'stock_receiving_id' => $grnId,
//                 'product_id'         => $item['product_id'],
//                 'rack_no'            => $item['rack_no'],
//                 'level_no'           => $item['level_no'],
//                 'slot_no'            => $item['slot_no'],
//                 'quantity'           => $item['quantity'],
//                 'batch_no'           => $item['batch_no'] ?? null,
//                 'expiry_date'        => $item['expiry_date'] ?? null,
//             ]);
//         }

//         $productIds = collect($request->items)
//             ->pluck('product_id')
//             ->unique();

//         foreach ($productIds as $productId) {

//             $totalQty = RackStock::where('product_id', $productId)->sum('quantity');

//             ProductStock::updateOrCreate(
//                 ['product_id' => $productId],
//                 ['total_stock' => $totalQty]
//             );
//         }

//         DB::commit();

//         return response()->json([
//             'success' => true,
//             'message' => 'Rack allocation updated successfully.',
//             'redirect_url' => route('admin.rack.receiving.index'),
//         ]);

//     } catch (\Exception $e) {

//         DB::rollBack();

//         return response()->json([
//             'success' => false,
//             'message' => $e->getMessage(),
//         ], 500);
//     }
// }


public function update(Request $request, $grnId)
{
    DB::beginTransaction();

    try {
        $data = $request->json()->all();

        if (empty($data['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'No rack allocation data found'
            ], 422);
        }

        RackStock::where('stock_receiving_id', $grnId)->delete();

        foreach ($data['items'] as $item) {
            RackStock::create([
                'stock_receiving_id' => $grnId,
                'product_id'         => $item['product_id'],
                'rack_no'            => $item['rack_no'],
                'level_no'           => $item['level_no'] ?? null,
                'slot_no'            => $item['slot_no'] ?? null,
                'quantity'           => $item['quantity'],
                'batch_no'           => $item['batch_no'] ?? null,
                'expiry_date'        => $item['expiry_date'] ?? null,
            ]);
        }

        DB::commit();

        return response()->json([
            'success'      => true,
            'message'      => 'Rack allocation updated successfully',
            'redirect_url' => route('admin.rack.receiving.index')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


public function show($grnId)
{
    $grn = StockReceiving::with([
        'vendor',
        'purchaseOrder',
        'rackStocks.product'
    ])->findOrFail($grnId);

    return view('admin.racks_receiving.view', compact('grn'));
}


// public function liveStock()
// {
//     $stocks = RackStock::with('product')
//         ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
//         ->groupBy('product_id')
//         ->orderBy('product_id')
//         ->get();

//     return view('admin.racks_receiving.live-stock', compact('stocks'));
// }

public function liveStock()
{
    $stocks = RackStock::select(
            'rack_stocks.*',
            'vendors.name as vendor_name'
        )
        ->leftJoin('stock_receivings', 'rack_stocks.stock_receiving_id', '=', 'stock_receivings.id')
        ->leftJoin('vendors', 'stock_receivings.vendor_id', '=', 'vendors.id')
        ->with('product')
        ->where('rack_stocks.quantity', '>', 0)
        ->orderBy('rack_stocks.product_id')
        ->orderBy('rack_stocks.rack_no')
        ->orderBy('rack_stocks.level_no')
        ->orderBy('rack_stocks.slot_no')
        ->get();

    return view('admin.racks_receiving.live-stock', compact('stocks'));
}


// public function liveStock()
// {
//     $stocks = RackStock::select(
//             'rack_stocks.*',
//             'vendors.name as vendor_name'
//         )
//         ->join('products', 'rack_stocks.product_id', '=', 'products.id')
//         ->leftJoin('stock_receivings', 'rack_stocks.stock_receiving_id', '=', 'stock_receivings.id')
//         ->leftJoin('vendors', 'stock_receivings.vendor_id', '=', 'vendors.id')
//         ->with('product')
//         ->orderBy('rack_stocks.product_id')
//         ->orderBy('rack_stocks.rack_no')
//         ->orderBy('rack_stocks.level_no')
//         ->orderBy('rack_stocks.slot_no')
//         ->get();

//     return view('admin.racks_receiving.live-stock', compact('stocks'));
// }

// comment on 28-03-26
// public function liveStockReport()
// {
//     $stocks = ProductStock::with('product')
//         ->where('total_stock', '>', 0) 
//         ->orderBy('updated_at', 'desc')
//         ->get();

//     return view('admin.stock.live-stock', compact('stocks'));
// }



public function liveStockReport()
{
    $stocks = ProductStock::query()

        ->leftJoin(
            'products',
            'products.id',
            '=',
            'product_stocks.product_id'
        )

        ->select([
            'product_stocks.product_id',
            'product_stocks.total_stock',
            'product_stocks.updated_at',

            'products.product_name',
            'products.brands'
        ])

        ->orderBy('product_stocks.updated_at', 'desc')

        ->get();

    return view('admin.stock.live-stock', compact('stocks'));
}


// public function liveStockReport()
// {
//     $stocks = \DB::table('rack_stocks')
//         ->join('products', 'rack_stocks.product_id', '=', 'products.id')
//         ->select(
//             'rack_stocks.product_id',
//             'products.product_name',
//             'products.brands',
//             \DB::raw('SUM(rack_stocks.quantity) as total_stock'),
//             \DB::raw('MAX(rack_stocks.updated_at) as updated_at')
//         )
//         // ->where('rack_stocks.quantity', '>', 0)
//         ->groupBy(
//             'rack_stocks.product_id',
//             'products.product_name',
//             'products.brands'
//         )
//         ->orderByDesc('updated_at')
//         ->get();

//     return view('admin.stock.live-stock', compact('stocks'));
// }


public function productStockDetail($productId)
{
    $stocks = RackStock::select(
            'rack_stocks.*',
            'vendors.name as vendor_name',
            'stock_receivings.receipt_date',
            'stock_receivings.bill_date'
        )
        ->leftJoin('stock_receivings', 'rack_stocks.stock_receiving_id', '=', 'stock_receivings.id')
        ->leftJoin('vendors', 'stock_receivings.vendor_id', '=', 'vendors.id')
        ->with('product')
        ->where('rack_stocks.product_id', $productId)
        //  ->where('rack_stocks.quantity', '>', 0) 
        ->orderBy('rack_stocks.rack_no')
        ->orderBy('rack_stocks.level_no')
        ->orderBy('rack_stocks.slot_no')
        ->get();

    $product = $stocks->first()->product ?? null;

    return view('admin.racks_receiving.product-stock-detail', compact('stocks', 'product'));
}

public function history($product_id, Request $request)
{
    $rackStocks = RackStock::where('product_id', $product_id)
        ->orderBy('created_at', 'desc')
        ->get();

    $product = Product::find($product_id);
    $orderId = $request->order_id;

    return view('admin.orderitem.history', compact('rackStocks', 'product', 'orderId'));
}


}
