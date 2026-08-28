<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Product;
use App\Models\RackStock;
use App\Models\ProductStock;
use App\Models\StockMovement;

class StockTransferController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Products With Available Rack Stock
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $products = RackStock::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->havingRaw('SUM(quantity) > 0')
            ->get();

        return view('admin.stock_transfer.index', compact('products'));
    }

    /*
    |--------------------------------------------------------------------------
    | Transfer Page For Selected Product
    |--------------------------------------------------------------------------
    */

    public function create(Product $product)
    {
        $rackStocks = RackStock::where('product_id', $product->id)
            ->where('quantity', '>', 0)
            ->orderBy('rack_no')
            ->orderBy('level_no')
            ->orderBy('slot_no')
            ->get();

        return view('admin.stock_transfer.create', compact('product', 'rackStocks'));
    }

    // /*
    // |--------------------------------------------------------------------------
    // | Store Transfer
    // |--------------------------------------------------------------------------
    // */
        // comment on 11-04-26
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'items'      => 'required|array|min:1',
    //         'items.*.from_rack'  => 'required',
    //         'items.*.from_level' => 'required',
    //         'items.*.from_slot'  => 'required',
    //         'items.*.to_rack'    => 'required',
    //         'items.*.to_level'   => 'required',
    //         'items.*.to_slot'    => 'required',
    //         'items.*.quantity'   => 'required|numeric|min:0.01',
    //         'items.*.remarks'    => 'nullable|string'
    //     ]);

    //     DB::beginTransaction();

    //     try {

    //         foreach ($request->items as $item) {

    //             $qty = (float) $item['quantity'];

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Lock Source Stock
    //             |--------------------------------------------------------------------------
    //             */
    //             $source = RackStock::where('product_id', $request->product_id)
    //                 ->where('rack_no',  $item['from_rack'])
    //                 ->where('level_no', $item['from_level'])
    //                 ->where('slot_no',  $item['from_slot'])
    //                 ->lockForUpdate()
    //                 ->first();

    //             if (!$source) {
    //                 throw new \Exception("Source location not found.");
    //             }

    //             if ($qty > $source->quantity) {
    //                 throw new \Exception("Transfer qty exceeds available stock.");
    //             }

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Reduce Source
    //             |--------------------------------------------------------------------------
    //             */
    //             $source->quantity -= $qty;
    //             $source->save();

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Destination (find or create)
    //             |--------------------------------------------------------------------------
    //             */
    //             $destination = RackStock::where('product_id', $request->product_id)
    //                 ->where('rack_no',  $item['to_rack'])
    //                 ->where('level_no', $item['to_level'])
    //                 ->where('slot_no',  $item['to_slot'])
    //                 ->lockForUpdate()
    //                 ->first();

    //             if ($destination) {

    //                 $destination->quantity += $qty;

    //             } else {

    //                 $destination = RackStock::create([
    //                     'product_id'  => $request->product_id,
    //                     'rack_no'     => $item['to_rack'],
    //                     'level_no'    => $item['to_level'],
    //                     'slot_no'     => $item['to_slot'],
    //                     'quantity'    => $qty,
    //                     'batch_no'    => $source->batch_no,
    //                     'expiry_date' => $source->expiry_date,
    //                 ]);
    //             }

    //             $destination->save();

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Movement Log
    //             |--------------------------------------------------------------------------
    //             */
    //             $lastCost = StockMovement::where('product_id', $request->product_id)
    //                 ->where('unit_cost', '>', 0)
    //                 ->orderByDesc('id')
    //                 ->value('unit_cost') ?? 0;

    //             StockMovement::create([
    //                 'product_id'     => $request->product_id,
    //                 'reference_type' => 'TRANSFER',
    //                 'reference_id'   => null,
    //                 'movement_type'  => 'TRANSFER',
    //                 'quantity'       => $qty,
    //                 'unit_cost'      => $lastCost,
    //                 'batch_no'       => $source->batch_no,
    //                 'expiry_date'    => $source->expiry_date,
    //                 'remarks'        =>
    //                     "From {$item['from_rack']}-{$item['from_level']}-{$item['from_slot']} "
    //                     . "To {$item['to_rack']}-{$item['to_level']}-{$item['to_slot']} "
    //                     . ($item['remarks'] ?? ''),
    //             ]);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'status'  => true,
    //             'message' => 'Stock transferred successfully.'
    //         ]);

    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         return response()->json([
    //             'status'  => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'items.*.rack_stock_id' => 'required|exists:rack_stocks,id',
    //         'items'      => 'required|array|min:1',
    //         'items.*.to_rack'    => 'required',
    //         'items.*.to_level'   => 'required',
    //         'items.*.to_slot'    => 'required',
    //         'items.*.quantity'   => 'required|numeric|min:0.01',
    //         'items.*.remarks'    => 'nullable|string'
    //     ]);

    //     DB::beginTransaction();

    //     try {

    //         foreach ($request->items as $item) {

    //             $qty = (float) $item['quantity'];

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Lock Source Stock
    //             |--------------------------------------------------------------------------
    //             */
    //             $source = RackStock::where('id', $item['rack_stock_id'])
    //                     ->where('product_id', $request->product_id)
    //                     ->lockForUpdate()
    //                     ->first();

    //             if (!$source) {
    //                 throw new \Exception("Source location not found.");
    //             }

    //             if ($qty > $source->quantity) {
    //                 throw new \Exception("Transfer qty exceeds available stock.");
    //             }

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Reduce Source
    //             |--------------------------------------------------------------------------
    //             */
    //             $source->quantity -= $qty;
    //             $source->save();

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Destination (find or create)
    //             |--------------------------------------------------------------------------
    //             */
    //             $destination = RackStock::where('product_id', $request->product_id)
    //                 ->where('rack_no',  $item['to_rack'])
    //                 ->where('level_no', $item['to_level'])
    //                 ->where('slot_no',  $item['to_slot'])
    //                 ->lockForUpdate()
    //                 ->first();

    //             if ($destination) {

    //                 $destination->quantity += $qty;

    //             } else {

    //                 $destination = RackStock::create([
    //                     'product_id'  => $request->product_id,
    //                     'rack_no'     => $item['to_rack'],
    //                     'level_no'    => $item['to_level'],
    //                     'slot_no'     => $item['to_slot'],
    //                     'quantity'    => $qty,
    //                     'batch_no'    => $source->batch_no,
    //                     'expiry_date' => $source->expiry_date,
    //                 ]);
    //             }

    //             $destination->save();

    //             /*
    //             |--------------------------------------------------------------------------
    //             | Movement Log
    //             |--------------------------------------------------------------------------
    //             */
    //             $lastCost = StockMovement::where('product_id', $request->product_id)
    //                 ->where('unit_cost', '>', 0)
    //                 ->orderByDesc('id')
    //                 ->value('unit_cost') ?? 0;

    //             StockMovement::create([
    //                 'product_id'     => $request->product_id,
    //                 'reference_type' => 'TRANSFER',
    //                 'reference_id'   => null,
    //                 'movement_type'  => 'TRANSFER',
    //                 'quantity'       => $qty,
    //                 'unit_cost'      => $lastCost,
    //                 'batch_no'       => $source->batch_no,
    //                 'expiry_date'    => $source->expiry_date,
    //                 'remarks'        =>
    //                     "From {$item['from_rack']}-{$item['from_level']}-{$item['from_slot']} "
    //                     . "To {$item['to_rack']}-{$item['to_level']}-{$item['to_slot']} "
    //                     . ($item['remarks'] ?? ''),
    //             ]);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'status'  => true,
    //             'message' => 'Stock transferred successfully.'
    //         ]);

    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         return response()->json([
    //             'status'  => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    
    
    public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'items.*.rack_stock_id' => 'required|exists:rack_stocks,id',
        'items'      => 'required|array|min:1',
        'items.*.to_rack'    => 'required',
        'items.*.to_level'   => 'required',
        'items.*.to_slot'    => 'required',
        'items.*.quantity'   => 'required|numeric|min:0.01',
        'items.*.remarks'    => 'nullable|string'
    ]);

    DB::beginTransaction();

    try {

        foreach ($request->items as $item) {

            $qty = (float) $item['quantity'];

            /*
            |--------------------------------------------------------------------------
            | Lock Source Stock
            |--------------------------------------------------------------------------
            */
            $source = RackStock::where('id', $item['rack_stock_id'])
                    ->where('product_id', $request->product_id)
                    ->lockForUpdate()
                    ->first();

            if (!$source) {
                throw new \Exception("Source location not found.");
            }

            if ($qty > $source->quantity) {
                throw new \Exception("Transfer qty exceeds available stock.");
            }

            /*
            |--------------------------------------------------------------------------
            | Reduce Source
            |--------------------------------------------------------------------------
            */
            $source->quantity -= $qty;
            $source->save();

            /*
            |--------------------------------------------------------------------------
            | Destination (find or create)
            |--------------------------------------------------------------------------
            */
            $destination = RackStock::where('product_id', $request->product_id)
                ->where('rack_no',  $item['to_rack'])
                ->where('level_no', $item['to_level'])
                ->where('slot_no',  $item['to_slot'])
                ->lockForUpdate()
                ->first();

            if ($destination) {

                $destination->quantity += $qty;

               
                if (empty($destination->stock_receiving_id) && !empty($source->stock_receiving_id)) {
                    $destination->stock_receiving_id = $source->stock_receiving_id;
                }

            } else {

                $destination = RackStock::create([
                    'product_id'         => $request->product_id,
                    'rack_no'            => $item['to_rack'],
                    'level_no'           => $item['to_level'],
                    'slot_no'            => $item['to_slot'],
                    'quantity'           => $qty,
                    'batch_no'           => $source->batch_no,
                    'expiry_date'        => $source->expiry_date,
                    'stock_receiving_id' => $source->stock_receiving_id ?: null,
                ]);
            }

            $destination->save();

            /*
            |--------------------------------------------------------------------------
            | Movement Log
            |--------------------------------------------------------------------------
            */
            $lastCost = StockMovement::where('product_id', $request->product_id)
                ->where('unit_cost', '>', 0)
                ->orderByDesc('id')
                ->value('unit_cost') ?? 0;

            StockMovement::create([
                'product_id'     => $request->product_id,
                'reference_type' => 'TRANSFER',
                'reference_id'   => null,
                'movement_type'  => 'TRANSFER',
                'quantity'       => $qty,
                'unit_cost'      => $lastCost,
                'batch_no'       => $source->batch_no,
                'expiry_date'    => $source->expiry_date,
                'remarks'        =>
                    "From {$item['from_rack']}-{$item['from_level']}-{$item['from_slot']} "
                    . "To {$item['to_rack']}-{$item['to_level']}-{$item['to_slot']} "
                    . ($item['remarks'] ?? ''),
            ]);
        }

        DB::commit();

        return response()->json([
            'status'  => true,
            'message' => 'Stock transferred successfully.'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'status'  => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
    
    
    
    
    
    
}