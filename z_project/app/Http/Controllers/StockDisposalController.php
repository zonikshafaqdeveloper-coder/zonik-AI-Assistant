<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StockDisposal;
use App\Models\Product;

class StockDisposalController extends Controller
{
    
    public function index()
    {
        $disposals = StockDisposal::latest()->get();

        return view('admin.disposals.index', compact('disposals'));
    }

  
   public function store(Request $request)
{
    DB::beginTransaction();

    try {

        $stock = DB::table('rack_stocks')
            ->where('product_id', $request->product_id)
            ->where('batch_no', $request->batch_no)
            ->where('expiry_date', $request->expiry_date)
            ->first();

        if (!$stock || $stock->quantity <= 0) {
            return response()->json(['error' => 'Invalid stock'], 400);
        }

        
        $stock_type = $stock->stock_receiving_id ? 'GRN' : 'OPENING';

        $unit_cost = 0;

       
        if (!$stock->stock_receiving_id) {

            $product = DB::table('products')
                ->where('id', $stock->product_id)
                ->first();

            $unit_cost = $product->cost_per_item ?? 0;

        } else {



            $item = DB::table('stock_receiving_items')
                ->where('stock_receiving_id', $stock->stock_receiving_id)
                ->where('product_id', $stock->product_id)
                ->where('batch_no', $stock->batch_no)
                ->where('expiry_date', $stock->expiry_date)
                ->first();

            $unit_cost = $item->purchase_rate ?? 0;
        }

        $total = $stock->quantity * $unit_cost;
        
        $productStock = DB::table('product_stocks')
            ->where('product_id', $stock->product_id)
            ->lockForUpdate()
            ->first();

        if (!$productStock) {
            throw new \Exception('Product stock not found');
        }

        if ($productStock->total_stock < $stock->quantity) {
            throw new \Exception('Insufficient total stock');
        }

       
        $disposal = StockDisposal::create([
            'product_id' => $stock->product_id,
            'stock_receiving_id' => $stock->stock_receiving_id,
            'batch_no' => $stock->batch_no,
            'expiry_date' => $stock->expiry_date,
            'quantity' => $stock->quantity,
            'unit_cost' => $unit_cost,
            'total_value' => $total,
            'stock_type' => $stock_type,
            'reason' => $request->reason,
            'disposed_by' => auth()->id()
        ]);

      
        DB::table('stock_movements')->insert([
            'product_id'     => $stock->product_id,
            'reference_type' => 'ADJUSTMENT',
            'reference_id'   => $disposal->id,
            'movement_type'  => 'OUT',
            'quantity'       => $stock->quantity,
            'unit_cost'      => $unit_cost,
            'batch_no'       => $stock->batch_no,
            'expiry_date'    => $stock->expiry_date,
            'remarks'        => 'Disposed Stock',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

       
        DB::table('rack_stocks')
            ->where('id', $stock->id)
            ->update([
                'quantity' => 0
            ]);
            
             DB::table('product_stocks')
            ->where('product_id', $stock->product_id)
            ->update([
                'total_stock' => $productStock->total_stock - $stock->quantity
            ]);

        DB::commit();

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}



public function bulkOpeningDispose(Request $request)
{
    DB::beginTransaction();

    try {

        foreach ($request->items as $item) {

            $total = $item['quantity'] * $item['unit_cost'];

          
            $disposal = StockDisposal::create([
                'product_id' => $item['product_id'],
                'stock_receiving_id' => null,
                'batch_no' => $item['batch_no'],
                'expiry_date' => $item['expiry_date'],
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
                'total_value' => $total,
                'stock_type' => 'OPENING',
                'reason' => $request->reason,
                'disposed_by' => auth()->id()
            ]);

           
            DB::table('stock_movements')->insert([
                'product_id'     => $item['product_id'],
                'reference_type' => 'ADJUSTMENT',
                'reference_id'   => $disposal->id,
                'movement_type'  => 'OUT',
                'quantity'       => $item['quantity'],
                'unit_cost'      => $item['unit_cost'],
                'batch_no'       => $item['batch_no'],
                'expiry_date'    => $item['expiry_date'],
                'remarks'        => 'Opening Stock Disposal',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        DB::commit();

        return response()->json([
            'message' => 'Opening stock disposed successfully'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}

public function create()
{
    $products = Product::select('id', 'product_name')->get();

    return view('admin.disposals.create', compact('products'));
}

public function getProductStock(Request $request)
{
    $stocks = DB::table('rack_stocks as rs')
        ->leftJoin('products as p', 'p.id', '=', 'rs.product_id')
        ->leftJoin('stock_receiving_items as sri', function ($join) {
            $join->on('sri.stock_receiving_id', '=', 'rs.stock_receiving_id')
                 ->on('sri.product_id', '=', 'rs.product_id')
                 ->on('sri.batch_no', '=', 'rs.batch_no')
                 ->on('sri.expiry_date', '=', 'rs.expiry_date');
        })
        ->where('rs.product_id', $request->product_id)
        ->where('rs.quantity', '>', 0)
        ->select(
            'rs.*',
            DB::raw("IF(rs.stock_receiving_id IS NULL, 'OPENING', 'GRN') as stock_type"),
            DB::raw("IF(rs.stock_receiving_id IS NULL, p.cost_per_item, sri.purchase_rate) as unit_cost")
        )
        ->get();

    return response()->json($stocks);
}

public function placed(Request $request)
{
    DB::beginTransaction();

    try {

        foreach ($request->items as $item) {

            // Skip empty rows
            if (!isset($item['qty']) || $item['qty'] <= 0) {
                continue;
            }

            // ==============================
            // LOCK RACK STOCK (SAFETY)
            // ==============================
            $rackStock = DB::table('rack_stocks')
                ->where('product_id', $item['product_id'])
                ->where('batch_no', $item['batch_no'])
                ->where('expiry_date', $item['expiry_date'])
                ->lockForUpdate()
                ->first();

            if (!$rackStock) {
                throw new \Exception('Rack stock not found');
            }

            if ($rackStock->quantity < $item['qty']) {
                throw new \Exception('Insufficient rack stock for batch ' . $item['batch_no']);
            }

            // ==============================
            // LOCK PRODUCT STOCK (SAFETY)
            // ==============================
            $productStock = DB::table('product_stocks')
                ->where('product_id', $item['product_id'])
                ->lockForUpdate()
                ->first();

            if (!$productStock) {
                throw new \Exception('Product stock not found');
            }

            if ($productStock->total_stock < $item['qty']) {
                throw new \Exception('Insufficient total stock');
            }

            // ==============================
            // CALCULATE VALUES (DON'T TRUST FRONTEND)
            // ==============================
            $unit_cost = 0;

            if (empty($item['stock_receiving_id'])) {

                $product = DB::table('products')
                    ->where('id', $item['product_id'])
                    ->first();

                $unit_cost = $product->cost_per_item ?? 0;

                $stock_type = 'OPENING';

            } else {

                $grnItem = DB::table('stock_receiving_items')
                    ->where('stock_receiving_id', $item['stock_receiving_id'])
                    ->where('product_id', $item['product_id'])
                    ->where('batch_no', $item['batch_no'])
                    ->where('expiry_date', $item['expiry_date'])
                    ->first();

                $unit_cost = $grnItem->purchase_rate ?? 0;

                $stock_type = 'GRN';
            }

            $total = $item['qty'] * $unit_cost;

            // ==============================
            // CREATE DISPOSAL
            // ==============================
            $disposal = StockDisposal::create([
                'product_id' => $item['product_id'],
                'stock_receiving_id' => $item['stock_receiving_id'],
                'batch_no' => $item['batch_no'],
                'expiry_date' => $item['expiry_date'],
                'quantity' => $item['qty'],
                'unit_cost' => $unit_cost,
                'total_value' => $total,
                'stock_type' => $stock_type,
                'reason' => $item['reason'],
                'disposed_by' => auth()->id()
            ]);

            // ==============================
            // STOCK MOVEMENT (FIXED)
            // ==============================
            DB::table('stock_movements')->insert([
                'product_id'     => $item['product_id'],
                'reference_type' => 'DAMAGED',
                'reference_id'   => $disposal->id,
                'movement_type'  => 'OUT',
                'quantity'       => $item['qty'],
                'unit_cost'      => $unit_cost,
                'batch_no'       => $item['batch_no'],
                'expiry_date'    => $item['expiry_date'],
                'remarks'        => 'Damaged Stock',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // ==============================
            // UPDATE RACK STOCK
            // ==============================
            DB::table('rack_stocks')
                ->where('id', $rackStock->id)
                ->update([
                    'quantity' => $rackStock->quantity - $item['qty']
                ]);

            // ==============================
            // UPDATE PRODUCT STOCK
            // ==============================
            DB::table('product_stocks')
                ->where('product_id', $item['product_id'])
                ->update([
                    'total_stock' => $productStock->total_stock - $item['qty']
                ]);
        }

        DB::commit();

        return redirect()
            ->route('admin.disposals.index')
            ->with('success', 'Stock damaged recorded successfully');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', $e->getMessage());
    }
}


}
