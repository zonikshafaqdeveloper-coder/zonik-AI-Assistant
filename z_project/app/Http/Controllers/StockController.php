<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderDetail;
use App\Models\StockReceiving;
use App\Models\StockReceivingItem;
use App\Models\StockMovement;
use App\Models\ProductStock;
use App\Models\CustomerPriceChangeLog;
use App\Models\VendorBill;
use App\Models\RackStock;
use App\Models\VendorPayment;
use App\Models\Vendor;
use App\Models\VendorPriceList;
use App\Models\CustomerPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockOpeningExport;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{
   
    public function index()
{
     $stockReceivings = StockReceiving::with([
            'purchaseOrder.vendor',
            'items',
            'vendorBill'
        ])
        ->orderBy('id', 'desc')
        ->get();

    //  OVERALL TOTAL (all records in DB)
    $overallTotalQty = StockReceivingItem::sum('actual_qty');


    return view('admin.stock.index', compact('stockReceivings','overallTotalQty'));
}

public function export()
{
    return Excel::download(new StockOpeningExport, 'opening_stock.xlsx');
}

public function stock_opening()
    {
        $openings = StockMovement::with('product')
            ->where('reference_type', 'OPENING')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.stock_opening.index', compact('openings'));
    }

    public function stock_opening_create()
{
    $products = Product::select('id','product_name','cost_per_item')
        ->orderBy('product_name')
        ->get();

    return view('admin.stock_opening.create', compact('products'));
}

 public function stock_opening_store(Request $request)
{
    // $request->validate([
    //     'items' => 'required|array|min:1',
    //     'items.*.product_id' => 'required|exists:products,id',
    //     'items.*.quantity'   => 'required|numeric|min:0.01',
    //     'items.*.rack_no'    => 'required',
    //     'items.*.level_no'   => 'required',
    //     'items.*.slot_no'    => 'required',
    // ]);
    
$request->validate([
    'items' => 'required|array|min:1',
    'items.*.product_id' => 'required|exists:products,id',
    'items.*.quantity'   => 'required|numeric|min:0.01',
    'items.*.rack_no'    => 'required',
]);

    DB::beginTransaction();

    try {

        foreach ($request->items as $item) {

            $qty = (float) $item['quantity'];

            /*
            |--------------------------------------------------------------------------
            | Create Rack Stock
            |--------------------------------------------------------------------------
            */
            $rackStock = RackStock::create([
                'stock_receiving_id' => null,
                'product_id'         => $item['product_id'],
                'batch_no'           => $item['batch_no'] ?? null,
                'expiry_date'        => $item['expiry_date'] ?? null,
                'quantity'           => $qty,
                'rack_no'            => $item['rack_no'],
                'level_no'           => $item['level_no'],
                'slot_no'            => $item['slot_no'],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Product Stock (MASTER TABLE)
            |--------------------------------------------------------------------------
            */
            $productStock = ProductStock::where('product_id', $item['product_id'])
                ->lockForUpdate()
                ->first();

            if (!$productStock) {
                $productStock = ProductStock::create([
                    'product_id'  => $item['product_id'],
                    'total_stock' => 0
                ]);
            }

            $productStock->total_stock += $qty;
            $productStock->save();

            /*
            |--------------------------------------------------------------------------
            | Stock Movement Log
            |--------------------------------------------------------------------------
            */
            StockMovement::create([
                'product_id'     => $item['product_id'],
                'reference_type' => 'OPENING',
                'reference_id'   => null,
                'movement_type'  => 'IN',
                'quantity'       => $qty,
                'unit_cost'      => $item['unit_cost'] ?? 0,
                'batch_no'       => $item['batch_no'] ?? null,
                'expiry_date'    => $item['expiry_date'] ?? null,
                'remarks'        => 'Opening stock entry',
            ]);
        }

        DB::commit();

        return response()->json([
            'status'       => true,
            'message'      => 'Opening stock saved successfully',
            'redirect_url' => route('admin.stock-opening')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status'  => false,
            'message' => $e->getMessage()
        ], 500);
    }
}



public function stockAdjustmentIndex()
{
    $products = RackStock::with('product')
        ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
        ->groupBy('product_id')
        ->havingRaw('SUM(quantity) > 0')
        ->orderBy('product_id')
        ->get();

    return view('admin.stock_adjustment.index', compact('products'));
}


public function stockAdjustmentCreate($productId)
{
    $product = Product::findOrFail($productId);

    $rackStocks = RackStock::where('product_id', $productId)
        ->where('quantity', '>', 0)
        ->orderBy('rack_no')
        ->orderBy('level_no')
        ->orderBy('slot_no')
        ->get();

    return view('admin.stock_adjustment.create', compact('product','rackStocks'));
}

// comment on 07-04-26
// public function stockAdjustmentStore(Request $request)
// {
//     $request->validate([
//     'product_id' => 'required|exists:products,id',
//     'items'      => 'required|array|min:1',

//     'items.*.rack_no'  => 'required',

//     'items.*.level_no' => 'nullable',
//     'items.*.slot_no'  => 'nullable',

//     'items.*.adjustment_type' => 'required|in:IN,OUT,TRANSFER',
//     'items.*.quantity' => 'required|numeric|min:0',
//     'items.*.remarks'  => 'required|string',
// ]);

// foreach ($request->items as $index => $item) {

//     $flatRacks = ['F1','F2','F3','F4','F5','F6'];

//     if (!in_array($item['rack_no'], $flatRacks)) {

//         if (empty($item['level_no']) || empty($item['slot_no'])) {
//             throw new \Exception("Row " . ($index + 1) . ": Level and Slot are required for rack {$item['rack_no']}");
//         }
//     }
// }

//     DB::beginTransaction();

//     try {

//         foreach ($request->items as $item) {

//             $qty = (float) $item['quantity'];

//             // Quantity validation
//             if ($item['adjustment_type'] !== 'TRANSFER' && $qty <= 0) {
//                 throw new \Exception("Quantity must be greater than 0 for IN or OUT adjustments.");
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | Lock Rack Stock (old location if transfer)
//             |--------------------------------------------------------------------------
//             */
//             // $rackStock = RackStock::where('product_id', $request->product_id)
//             //     ->where('rack_no',  $item['old_rack_no']  ?? $item['rack_no'])
//             //     ->where('level_no', $item['old_level_no'] ?? $item['level_no'])
//             //     ->where('slot_no',  $item['old_slot_no']  ?? $item['slot_no'])
//             //     ->lockForUpdate()
//             //     ->first();


//             $rack = $item['old_rack_no'] ?? $item['rack_no'];
//             $level = $item['old_level_no'] ?? $item['level_no'];
//             $slot = $item['old_slot_no'] ?? $item['slot_no'];

//             $query = RackStock::where('product_id', $request->product_id)
//                 ->where('rack_no', $rack);

//             $flatRacks = ['F1','F2','F3','F4','F5','F6'];

//             if (!in_array($rack, $flatRacks)) {
//                 $query->where('level_no', $level)
//                     ->where('slot_no', $slot);
//             }

//             $rackStock = $query->lockForUpdate()->first();
            
//             if (!$rackStock) {
//                 throw new \Exception(
//                     "Rack stock not found at Rack {$item['rack_no']} / Level {$item['level_no']} / Slot {$item['slot_no']}"
//                 );
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | Adjust Rack Stock Quantity
//             |--------------------------------------------------------------------------
//             */
//             if ($item['adjustment_type'] === 'IN') {
//                 $rackStock->quantity += $qty;
//             }

//             if ($item['adjustment_type'] === 'OUT') {
//                 if ($qty > $rackStock->quantity) {
//                     throw new \Exception("Adjustment quantity exceeds available rack stock.");
//                 }
//                 $rackStock->quantity -= $qty;
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | Transfer Location Only (no quantity change)
//             |--------------------------------------------------------------------------
//             */
//             if ($item['adjustment_type'] === 'TRANSFER') {
//                 $rackStock->rack_no  = $item['rack_no'];
//                 $rackStock->level_no = $item['level_no'];
//                 $rackStock->slot_no  = $item['slot_no'];
//             }

//             $rackStock->save();

//             /*
//             |--------------------------------------------------------------------------
//             | Update Product Stock (MASTER TABLE)
//             |--------------------------------------------------------------------------
//             */
//             $productStock = ProductStock::where('product_id', $request->product_id)
//                 ->lockForUpdate()
//                 ->first();

//             if (!$productStock) {
//                 $productStock = ProductStock::create([
//                     'product_id'  => $request->product_id,
//                     'total_stock' => 0
//                 ]);
//             }

//             if ($item['adjustment_type'] === 'IN') {
//                 $productStock->total_stock += $qty;
//             }

//             if ($item['adjustment_type'] === 'OUT') {
//                 if ($qty > $productStock->total_stock) {
//                     throw new \Exception("Product stock cannot go negative.");
//                 }
//                 $productStock->total_stock -= $qty;
//             }

//             // TRANSFER → no change
//             $productStock->save();

//             /*
//             |--------------------------------------------------------------------------
//             | Stock Movement Log
//             |--------------------------------------------------------------------------
//             */
//             $lastCost = StockMovement::where('product_id', $request->product_id)
//                 ->where('unit_cost', '>', 0)
//                 ->orderBy('id', 'desc')
//                 ->value('unit_cost') ?? 0;

//             if ($item['adjustment_type'] === 'TRANSFER') {

//                 StockMovement::create([
//                     'product_id'     => $request->product_id,
//                     'reference_type' => 'TRANSFER',
//                     'reference_id'   => null,
//                     'movement_type'  => 'TRANSFER',
//                     'quantity'       => $rackStock->quantity,
//                     'unit_cost'      => $lastCost,
//                     'batch_no'       => $rackStock->batch_no,
//                     'expiry_date'    => $rackStock->expiry_date,
//                     'remarks'        => $item['remarks'],
//                 ]);

//             } else {

//                 StockMovement::create([
//                     'product_id'     => $request->product_id,
//                     'reference_type' => 'ADJUSTMENT',
//                     'reference_id'   => null,
//                     'movement_type'  => $item['adjustment_type'], // IN / OUT
//                     'quantity'       => $qty,
//                     'unit_cost'      => $lastCost,
//                     'batch_no'       => $rackStock->batch_no,
//                     'expiry_date'    => $rackStock->expiry_date,
//                     'remarks'        => $item['remarks'],
//                 ]);
//             }
//         }

//         DB::commit();

//         return response()->json([
//             'status'  => true,
//             'message' => 'Stock adjustments saved successfully.'
//         ]);

//     } catch (\Exception $e) {
//         DB::rollBack();

//         return response()->json([
//             'status'  => false,
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }




public function stockAdjustmentStore(Request $request)
{
    Log::info('[STOCK] Request Started', [
        'product_id' => $request->product_id,
        'items' => $request->items
    ]);

    $request->validate([
        'product_id' => 'required|exists:products,id',
        'items'      => 'required|array|min:1',

        // ✅ REQUIRED FOR CORRECT UPDATE
        'items.*.rack_stock_id' => 'required|exists:rack_stocks,id',

        'items.*.rack_no'  => 'required',
        'items.*.level_no' => 'nullable',
        'items.*.slot_no'  => 'nullable',

        'items.*.adjustment_type' => 'required|in:IN,OUT,TRANSFER',
        'items.*.quantity' => 'required|numeric|min:0',
        'items.*.remarks'  => 'required|string',
    ]);

    DB::beginTransaction();
    Log::info('[STOCK] Transaction Started');

    try {

        foreach ($request->items as $index => $item) {

            Log::info('[STOCK] Processing Item', [
                'row' => $index + 1,
                'item' => $item
            ]);

            $qty = (float) $item['quantity'];

            if ($item['adjustment_type'] !== 'TRANSFER' && $qty <= 0) {
                throw new \Exception("Quantity must be greater than 0 for IN or OUT adjustments.");
            }

            /*
            |--------------------------------------------------------------------------
            | ✅ FETCH BY ID (CORE FIX)
            |--------------------------------------------------------------------------
            */
            $rackStock = RackStock::where('id', $item['rack_stock_id'])
                ->lockForUpdate()
                ->first();

            if (!$rackStock) {
                Log::error('[STOCK] RackStock Not Found', [
                    'rack_stock_id' => $item['rack_stock_id']
                ]);
                throw new \Exception("Invalid rack stock selected.");
            }

            Log::info('[STOCK] Loaded RackStock', [
                'id' => $rackStock->id,
                'current_qty' => $rackStock->quantity
            ]);

            /*
            |--------------------------------------------------------------------------
            | ✅ ADJUSTMENT LOGIC (CORRECT)
            |--------------------------------------------------------------------------
            */

            // IN → Increase same row
            if ($item['adjustment_type'] === 'IN') {
                $rackStock->quantity += $qty;
            }

            // OUT → Decrease same row
            if ($item['adjustment_type'] === 'OUT') {
                if ($qty > $rackStock->quantity) {
                    Log::error('[STOCK] OUT exceeds stock', [
                        'available' => $rackStock->quantity,
                        'requested' => $qty
                    ]);
                    throw new \Exception("Adjustment quantity exceeds available rack stock.");
                }
                $rackStock->quantity -= $qty;
            }

            // TRANSFER → Change location only
            if ($item['adjustment_type'] === 'TRANSFER') {
                $rackStock->rack_no  = $item['rack_no'];
                $rackStock->level_no = $item['level_no'];
                $rackStock->slot_no  = $item['slot_no'];
            }

            $rackStock->save();

            Log::info('[STOCK] After Adjustment', [
                'id' => $rackStock->id,
                'new_qty' => $rackStock->quantity
            ]);

            /*
            |--------------------------------------------------------------------------
            | PRODUCT STOCK UPDATE
            |--------------------------------------------------------------------------
            */
            $productStock = ProductStock::where('product_id', $request->product_id)
                ->lockForUpdate()
                ->first();

            if (!$productStock) {
                $productStock = ProductStock::create([
                    'product_id'  => $request->product_id,
                    'total_stock' => 0
                ]);
            }

            if ($item['adjustment_type'] === 'IN') {
                $productStock->total_stock += $qty;
            }

            if ($item['adjustment_type'] === 'OUT') {
                if ($qty > $productStock->total_stock) {
                    throw new \Exception("Product stock cannot go negative.");
                }
                $productStock->total_stock -= $qty;
            }

            $productStock->save();

            Log::info('[STOCK] Product Stock Updated', [
                'total_stock' => $productStock->total_stock
            ]);

            /*
            |--------------------------------------------------------------------------
            | STOCK MOVEMENT LOG
            |--------------------------------------------------------------------------
            */
            $lastCost = StockMovement::where('product_id', $request->product_id)
                ->where('unit_cost', '>', 0)
                ->orderBy('id', 'desc')
                ->value('unit_cost') ?? 0;

            StockMovement::create([
                'product_id'     => $request->product_id,
                'reference_type' => 'ADJUSTMENT',
                'reference_id'   => $rackStock->id,
                'movement_type'  => $item['adjustment_type'],
                'quantity'       => $qty,
                'unit_cost'      => $lastCost,
                'batch_no'       => $rackStock->batch_no,
                'expiry_date'    => $rackStock->expiry_date,
                'remarks'        => $item['remarks'],
            ]);

            Log::info('[STOCK] Movement Created', [
                'rack_stock_id' => $rackStock->id
            ]);
        }

        DB::commit();
        Log::info('[STOCK] Transaction Committed');

        return response()->json([
            'status'  => true,
            'message' => 'Stock adjustments saved successfully.'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error('[STOCK] Transaction Failed', [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'status'  => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
   
    // public function create(Request $request)
    // {
    //     $purchaseOrders = PurchaseOrderDetail::where('status', 'approved')
    //         ->orderBy('id', 'desc')
    //         ->get(['id', 'purchase_order_number']);
           
    //     $selectedPoId = $request->query('po_id');

    //     return view('admin.stock.create', compact('purchaseOrders','selectedPoId'));
    // }
    
    public function create(Request $request)
{
    $pendingAllocation = $this->getPendingRackAllocation();

    if ($pendingAllocation) {
        return redirect()
            ->back() 
            ->with('error', $pendingAllocation['message'])
            ->with('rack_allocation_id', $pendingAllocation['latest_unallocated_id']);
    }

    $purchaseOrders = PurchaseOrderDetail::where('status', 'approved')
        ->orderBy('id', 'desc')
        ->get(['id', 'purchase_order_number']);

    $selectedPoId = $request->query('po_id');

    return view('admin.stock.create', compact('purchaseOrders', 'selectedPoId'));
}

private function getPendingRackAllocation(): ?array
{
    $receivingItems = StockReceivingItem::whereHas('stockReceiving', function ($q) {
            $q->whereIn('status', ['approved', 'approved_with_changes']);
        })
        ->whereRaw('actual_qty > returned_qty')
        ->whereRaw('(actual_qty + free_quantity) > 0')
        ->get(['stock_receiving_id', 'product_id']);

    if ($receivingItems->isEmpty()) {
        return null;
    }

    $allocatedPairs = RackStock::whereIn('stock_receiving_id', $receivingItems->pluck('stock_receiving_id')->unique())
        ->get(['stock_receiving_id', 'product_id'])
        ->map(fn ($r) => $r->stock_receiving_id . '-' . $r->product_id)
        ->unique()
        ->flip();

    $unallocated = $receivingItems->reject(
        fn ($item) => isset($allocatedPairs[$item->stock_receiving_id . '-' . $item->product_id])
    );

    if ($unallocated->isEmpty()) {
        return null;
    }

    $latestUnallocated = $unallocated->max('stock_receiving_id');

    $grnList = $unallocated->pluck('stock_receiving_id')->unique()
        ->map(fn ($id) => 'IGGRN-' . str_pad($id, 5, '0', STR_PAD_LEFT))
        ->implode(', ');

    return [
        'latest_unallocated_id' => $latestUnallocated,
        'message' => "There is pending rack allocation in: {$grnList}. Please complete rack allocation before adding any product to PO.",
    ];
}

public function checkAnyPendingRackAllocation()
{
    $pending = $this->getPendingRackAllocation();

    if ($pending) {
        return response()->json([
            'allocated'             => false,
            'latest_unallocated_id' => $pending['latest_unallocated_id'],
            'message'               => $pending['message'],
        ]);
    }

    return response()->json(['allocated' => true, 'message' => 'No pending rack allocation.']);
}

      function generateBillNumber()
    {
        $lastNumber = DB::table('vendor_bills')
            ->lockForUpdate()
            ->max(DB::raw("CAST(SUBSTRING(bill_no, 6) AS UNSIGNED)"));

        $nextNumber = ($lastNumber ?? 0) + 1;

        return 'BILL-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }



public function stock_receivings(Request $request)
{
    // dd($request->all());
    $request->validate([
        'purchase_order_id' => 'required|exists:purchase_order_details,id',
        'receipt_date'      => 'required|date',
        'bill_no'           => 'required|string|max:50|unique:stock_receivings,bill_no',
        'items'             => 'required',
        'original_bill'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    $items = json_decode($request->items, true);
    // dd($items);
    // dd($request->all());

    if (!is_array($items) || count($items) === 0) {
        return response()->json([
            'success' => false,
            'message' => 'No items found'
        ], 422);
    }

    try {

        DB::transaction(function () use ($request, $items, &$srId) {

            $po = PurchaseOrderDetail::lockForUpdate()
                ->findOrFail($request->purchase_order_id);

                if ($po && $po->status !== 'received') {
                        $po->update(['status' => 'received']);
                    }

            
            $billFilename = null;
            if ($request->hasFile('original_bill')) {
                $file = $request->file('original_bill');
                $billFilename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/stock_receiving_bills'), $billFilename);
            }

            
            // $billNo = null;
            // if ($request->save_type === 'submit') {
            //     $lastNumber = VendorBill::lockForUpdate()
            //         ->max(DB::raw("CAST(SUBSTRING(bill_no, 6) AS UNSIGNED)"));

            //     $billNo = 'BILL-' . str_pad(($lastNumber ?? 0) + 1, 5, '0', STR_PAD_LEFT);
            // }

            
            $sr = StockReceiving::create([
                'purchase_order_id' => $po->id,
                'vendor_id'         => $po->vendor_id,
                'receipt_date'      => $request->receipt_date,
                'bill_no'           => $request->bill_no,
                'bill_date'         => $request->bill_date,
                'original_bill'     => $billFilename,

                'subtotal'          => $request->subtotal,
                'discount_percent'  => $request->discount_percent,
                'tax_amount'        => $request->tax_amount,
                'delivery_charges'  => $request->delivery_charges,
                'grand_total'       => $request->grand_total,

                'status'            => $request->save_type === 'draft'
                                        ? 'draft'
                                        : 'submitted',

                'created_by'        => auth()->id(),
            ]);

      
            foreach ($items as $item) {
                StockReceivingItem::create([
                    'stock_receiving_id'     => $sr->id,
                    'purchase_order_item_id' => $item['po_item_id'],
                    'product_id'             => $item['product_id'],
                    'po_qty'                 => $item['po_qty'],
                    'free_quantity'          => $item['freeqty'],
                    'row_tax'                => $item['row_tax'],
                    'actual_qty'             => $item['actual_qty'],
                    'returned_qty'           => $item['returned_qty'] ?? 0,
                    'return_reason'          => $item['return_reason'] ?? null,
                    'to_be_return_qty'       => $item['to_be_return_qty'] ?? 0,
                    'to_be_return_reason'    => $item['to_be_return_reason'] ?? null,
                    'short_qty'              => $item['short_qty'] ?? 0,
                    'purchase_rate'          => $item['purchase_rate'],
                    'batch_no'               => $item['batch_no'],
                    'expiry_date'            => $item['expiry_date'],
                    'mrp'                    => $item['mrp'],
                ]);
            }

          
            if ($request->save_type === 'submit') {
                VendorBill::create([
                    'stock_receiving_id' => $sr->id,
                    'purchase_order_id'  => $po->id,
                    'vendor_id'          => $po->vendor_id,
                    'bill_no'            => $request->bill_no,
                    'bill_date'          => $request->bill_date ?? now(),
                    'subtotal'           => $sr->subtotal,
                    'discount_percent'   => $sr->discount_percent,
                    'tax_amount'        =>  $sr->tax_amount,
                    'delivery_charges'   => $sr->delivery_charges,
                    'grand_total'        => $sr->grand_total,
                    'status'             => 'unpaid',
                ]);
            }

            $srId = $sr->id;
        }); 

        return response()->json([
            'success' => true,
            'redirect_url' => route('admin.stock-receivings.show', $srId)
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}




    public function stock_show($id)
{
    $stockReceiving = StockReceiving::with([
        'items.product',
        'purchaseOrder',
        'vendorBill'
    ])->findOrFail($id);

    return view('admin.stock.show', compact('stockReceiving'));
}

public function stock_convert_to_bill($id)
{
    try {

        DB::transaction(function () use ($id) {

            $grn = StockReceiving::with(['items'])
                ->lockForUpdate()
                ->findOrFail($id);

            // Safety checks
            if ($grn->status !== 'draft') {
                throw new \Exception('Only draft GRN can be converted');
            }

            if ($grn->vendorBill) {
                throw new \Exception('Bill already generated');
            }

            /* ============================
               Generate Bill Number
            ============================ */
            // $lastNumber = VendorBill::lockForUpdate()
            //     ->max(DB::raw("CAST(SUBSTRING(bill_no, 6) AS UNSIGNED)"));

            // $billNo = 'BILL-' . str_pad(($lastNumber ?? 0) + 1, 5, '0', STR_PAD_LEFT);

            /* ============================
               Update GRN (IMPORTANT)
            ============================ */
            $grn->update([
                // 'bill_no' => $billNo,
                'status'  => 'submitted', // ✅ NOT approved, NOT received
            ]);

            $po = PurchaseOrderDetail::lockForUpdate()
                ->find($grn->purchase_order_id);

            if ($po && $po->status !== 'received') {
                $po->update(['status' => 'received']);
            }

            /* ============================
               Create Vendor Bill
            ============================ */
            VendorBill::create([
                'stock_receiving_id' => $grn->id,
                'purchase_order_id'  => $grn->purchase_order_id,
                'vendor_id'          => $grn->vendor_id,
                'bill_no'            => $grn->bill_no,
                'bill_date'          => now(),
                'subtotal'           => $grn->subtotal,
                'discount_percent'   => $grn->discount_percent,
                'tax_amount'         => $grn->tax_amount,
                'delivery_charges'   => $grn->delivery_charges,
                'grand_total'        => $grn->grand_total,
                'status'             => 'unpaid',
            ]);
        });

        return response()->json([
            'success' => true,
            'redirect_url' => route('admin.stock-receivings.index')
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 422);
    }
}

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


// public function liveStock()
// {
//     $stocks = ProductStock::with('product')
//         ->orderBy('updated_at', 'desc')
//         ->get();

//     return view('admin.stock.live-stock', compact('stocks'));
// }


// public function stockLedger()
// {
//     $movements = StockMovement::with('product')
//         ->orderBy('created_at', 'desc')
//         ->get();

//     return view('admin.stock.ledger', compact('movements'));
// }

// public function stockLedger(Request $request)
// {
//     if ($request->ajax()) {
//       $query = StockMovement::query()
//     ->leftJoin('products', 'stock_movements.product_id', '=', 'products.id')

//     ->leftJoin('stock_receivings', function ($join) {
//         $join->on('stock_movements.reference_id', '=', 'stock_receivings.id')
//              ->where('stock_movements.reference_type', '=', 'GRN');
//     })

//     ->leftJoin('vendors', 'stock_receivings.vendor_id', '=', 'vendors.id')

//     ->select([
//         'stock_movements.id',
//         'stock_movements.created_at',
//         'stock_movements.movement_type',
//         'stock_movements.quantity',
//         'stock_movements.unit_cost',
//         'stock_movements.reference_type',
//         'stock_movements.reference_id',
//         'stock_movements.remarks',

//         'products.product_name',
//         'vendors.name as vendor_name',

//         DB::raw("
//             SUM(
//               CASE 
//                     WHEN movement_type IN ('IN', 'IN_FREE') THEN quantity
//                     WHEN movement_type IN ('OUT', 'RETURN', 'PENDING_RETURN') THEN -quantity
//                     ELSE 0
//                 END
//             ) OVER (
//                 PARTITION BY stock_movements.product_id 
//                 ORDER BY stock_movements.created_at ASC
//             ) as total_stock
//         ")
//     ])
//     ->orderBy('created_at', 'desc');

//         return DataTables::of($query)
//             ->addIndexColumn()

//             ->editColumn('created_at', function ($m) {
//                 return \Carbon\Carbon::parse($m->created_at)
//                     ->format('d-m-Y H:i');
//             })

//           ->editColumn('product_name', fn($m) => $m->product_name ?? '-')

//             ->editColumn('vendor_name', fn($m) => $m->vendor_name ?? '-')
            
//             ->editColumn('reference', function ($m) {
//                 return $m->reference_type . ' #' . $m->reference_id;
//             })

//             ->addColumn('type', function ($m) {
//                 $badge = $m->movement_type === 'IN'
//                     ? 'bg-success'
//                     : 'bg-danger';

//                 $btn = '';

//                 if ($m->movement_type === 'PENDING_RETURN') {
//                     $btn = '<button class="btn btn-warning btn-sm return-btn" data-id="'.$m->id.'">
//                                 Mark Returned
//                             </button>';
//                 }

//                 return '<span class="badge '.$badge.'">'.$m->movement_type.'</span> '.$btn;
//             })
            
//             ->addColumn('reference', function ($m) {
//                 return $m->reference_type . ' #' . $m->reference_id;
//             })

//             ->rawColumns(['type'])
//             ->make(true);
//     }

//     return view('admin.stock.ledger');
// }


// public function stockLedger(Request $request)
// {
//     if ($request->ajax()) {

//         $query = StockMovement::with([
//                 'product',
//                 'receiving.vendor'
//             ])

//             ->leftJoin(
//                 'product_stocks',
//                 'product_stocks.product_id',
//                 '=',
//                 'stock_movements.product_id'
//             )

//             ->select([
//                 'stock_movements.*',

//                 DB::raw("
//                 (
//                     COALESCE(product_stocks.total_stock, 0)

//                     -

//                     COALESCE(

//                         SUM(

//                             CASE

//                                 WHEN stock_movements.movement_type = 'IN'
//                                     THEN stock_movements.quantity

//                                 WHEN stock_movements.movement_type = 'OUT'
//                                     THEN -stock_movements.quantity

//                                 ELSE 0

//                             END

//                         ) OVER (

//                             PARTITION BY stock_movements.product_id

//                             ORDER BY stock_movements.created_at DESC

//                             ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING

//                         ),

//                     0)

//                 ) as balance
//                 ")
//             ])

//             ->orderBy('stock_movements.created_at', 'desc');

//         return DataTables::of($query)

//             ->addIndexColumn()

//             ->editColumn('created_at', function ($m) {

//                 return \Carbon\Carbon::parse($m->created_at)
//                     ->format('d-m-Y H:i');
//             })

//             ->addColumn('product_name', function ($m) {

//                 return $m->product->product_name ?? '-';
//             })

//             ->addColumn('vendor_name', function ($m) {

//                 if (
//                     $m->reference_type === 'GRN' &&
//                     $m->receiving &&
//                     $m->receiving->vendor
//                 ) {
//                     return $m->receiving->vendor->name;
//                 }

//                 return '-';
//             })

//             ->addColumn('type', function ($m) {

//                 $badge = 'bg-secondary';

//                 if ($m->movement_type === 'IN') {
//                     $badge = 'bg-success';
//                 }

//                 if ($m->movement_type === 'OUT') {
//                     $badge = 'bg-danger';
//                 }

//                 if ($m->movement_type === 'PENDING_RETURN') {
//                     $badge = 'bg-warning';
//                 }

//                 $btn = '';

//                 if ($m->movement_type === 'PENDING_RETURN') {

//                     $btn = '
//                         <button
//                             class="btn btn-warning btn-sm return-btn"
//                             data-id="'.$m->id.'">
//                             Mark Returned
//                         </button>
//                     ';
//                 }

//                 return '
//                     <span class="badge '.$badge.'">
//                         '.$m->movement_type.'
//                     </span>
//                     '.$btn;
//             })

//             ->editColumn('quantity', function ($m) {

//                 return number_format($m->quantity, 2);
//             })

//             ->addColumn('balance_stock', function ($m) {

//                 return number_format($m->balance, 2);
//             })

//             ->editColumn('unit_cost', function ($m) {

//                 return $m->unit_cost
//                     ? number_format($m->unit_cost, 2)
//                     : '-';
//             })

//             ->editColumn('remarks', function ($m) {

//                 return $m->remarks ?? '-';
//             })

//             ->rawColumns(['type'])

//             ->make(true);
//     }

//     return view('admin.stock.ledger');
// }


public function stockLedger(Request $request)
{
    if ($request->ajax()) {

        $query = StockMovement::query()

            ->leftJoin(
                'products',
                'products.id',
                '=',
                'stock_movements.product_id'
            )

            ->leftJoin(
                'product_stocks',
                'product_stocks.product_id',
                '=',
                'stock_movements.product_id'
            )

            ->leftJoin('stock_receivings', function ($join) {

                $join->on(
                    'stock_movements.reference_id',
                    '=',
                    'stock_receivings.id'
                )
                ->where(
                    'stock_movements.reference_type',
                    '=',
                    'GRN'
                );
            })

            ->leftJoin(
                'vendors',
                'stock_receivings.vendor_id',
                '=',
                'vendors.id'
            )
            
             ->leftJoin('orders', function ($join) {
             
              $join->on(
                    'stock_movements.reference_id',
                    '=',
                    'orders.id'
                )
                ->where(
                    'stock_movements.reference_type',
                    '=',
                    'ORDER'
                );
            })
            ->leftJoin(
                'users',
                'orders.outlet_id',
                '=',
                'users.id'
            )

            ->select([

                'stock_movements.id',
                'stock_movements.created_at',
                'stock_movements.movement_type',
                'stock_movements.quantity',
                'stock_movements.unit_cost',
                'stock_movements.reference_type',
                'stock_movements.reference_id',
                'stock_movements.remarks',

                'products.product_name',

                // 'vendors.name as vendor_name',
                
                 DB::raw("
                    CASE
                        WHEN stock_movements.reference_type = 'GRN' THEN vendors.name
                        WHEN stock_movements.reference_type = 'ORDER' THEN users.outlet_name
                        ELSE NULL
                    END as party_name
                "),


                DB::raw("
                    (
                        COALESCE(product_stocks.total_stock, 0)

                        -

                        COALESCE(

                            SUM(

                                CASE 

                                    WHEN stock_movements.movement_type IN ('IN', 'IN_FREE')
                                        THEN stock_movements.quantity

                                    WHEN stock_movements.movement_type IN ('OUT', 'RETURN', 'PENDING_RETURN')
                                        THEN -stock_movements.quantity

                                    ELSE 0

                                END

                            ) OVER (

                                PARTITION BY stock_movements.product_id

                                ORDER BY stock_movements.created_at DESC

                                ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING

                            ),

                        0)

                    ) as total_stock
                ")
            ])

            ->orderBy('stock_movements.created_at', 'desc');

        return DataTables::of($query)

            ->addIndexColumn()

            ->editColumn('created_at', function ($m) {

                return \Carbon\Carbon::parse($m->created_at)
                    ->format('d-m-Y H:i');
            })

            ->editColumn('product_name', function ($m) {

                return $m->product_name ?? '-';
            })

            ->editColumn('party_name', function ($m) {

                return $m->party_name ?? '-';
            })
            
            ->filterColumn('party_name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('vendors.name', 'like', "%{$keyword}%")
                      ->orWhere('users.outlet_name', 'like', "%{$keyword}%");
                });
            })

            ->addColumn('reference', function ($m) {

                return $m->reference_type . ' #' . $m->reference_id;
            })

            ->addColumn('type', function ($m) {

                $badge = 'bg-secondary';

                if (
                    in_array($m->movement_type, ['IN', 'IN_FREE'])
                ) {
                    $badge = 'bg-success';
                }

                if (
                    in_array($m->movement_type, ['OUT', 'RETURN'])
                ) {
                    $badge = 'bg-danger';
                }

                if ($m->movement_type === 'PENDING_RETURN') {
                    $badge = 'bg-warning';
                }

                $btn = '';

                if ($m->movement_type === 'PENDING_RETURN') {

                    $btn = '
                        <button
                            class="btn btn-warning btn-sm return-btn"
                            data-id="'.$m->id.'">
                            Mark Returned
                        </button>
                    ';
                }

                return '
                    <span class="badge '.$badge.'">
                        '.$m->movement_type.'
                    </span>
                    '.$btn;
            })

            ->editColumn('quantity', function ($m) {

                return number_format($m->quantity, 2);
            })

            ->editColumn('total_stock', function ($m) {

                return number_format($m->total_stock, 2);
            })

            ->editColumn('unit_cost', function ($m) {

                return $m->unit_cost
                    ? number_format($m->unit_cost, 2)
                    : '-';
            })

            ->editColumn('remarks', function ($m) {

                return $m->remarks ?? '-';
            })

            ->rawColumns(['type'])

            ->make(true);
    }

    return view('admin.stock.ledger');
}


// public function stockLedger(Request $request)
// {
//     if ($request->ajax()) {
//         $query = StockMovement::query()
//             ->leftJoin(
//                 'products',
//                 'products.id',
//                 '=',
//                 'stock_movements.product_id'
//             )
//             ->leftJoin(
//                 'product_stocks',
//                 'product_stocks.product_id',
//                 '=',
//                 'stock_movements.product_id'
//             )
//             ->leftJoin('stock_receivings', function ($join) {
//                 $join->on(
//                     'stock_movements.reference_id',
//                     '=',
//                     'stock_receivings.id'
//                 )
//                 ->where(
//                     'stock_movements.reference_type',
//                     '=',
//                     'GRN'
//                 );
//             })
//             ->leftJoin(
//                 'vendors',
//                 'stock_receivings.vendor_id',
//                 '=',
//                 'vendors.id'
//             )
//             ->leftJoin('orders', function ($join) {
//                 $join->on(
//                     'stock_movements.reference_id',
//                     '=',
//                     'orders.id'
//                 )
//                 ->where(
//                     'stock_movements.reference_type',
//                     '=',
//                     'ORDER'
//                 );
//             })
//             ->leftJoin(
//                 'users',
//                 'orders.outlet_id',
//                 '=',
//                 'users.id'
//             )
//             ->select([
//                 'stock_movements.id',
//                 'stock_movements.created_at',
//                 'stock_movements.movement_type',
//                 'stock_movements.quantity',
//                 'stock_movements.unit_cost',
//                 'stock_movements.reference_type',
//                 'stock_movements.reference_id',
//                 'stock_movements.remarks',
//                 'products.product_name',
//                 DB::raw("
//                     CASE
//                         WHEN stock_movements.reference_type = 'GRN' THEN vendors.name
//                         WHEN stock_movements.reference_type = 'ORDER' THEN users.outlet_name
//                         ELSE NULL
//                     END as party_name
//                 "),
//                 DB::raw("
//                     (
//                         COALESCE(product_stocks.total_stock, 0)
//                         -
//                         COALESCE(
//                             SUM(
//                                 CASE 
//                                     WHEN stock_movements.movement_type IN ('IN', 'IN_FREE')
//                                         THEN stock_movements.quantity
//                                     WHEN stock_movements.movement_type IN ('OUT', 'RETURN', 'PENDING_RETURN')
//                                         THEN -stock_movements.quantity
//                                     ELSE 0
//                                 END
//                             ) OVER (
//                                 PARTITION BY stock_movements.product_id
//                                 ORDER BY stock_movements.created_at DESC
//                                 ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING
//                             ),
//                         0)
//                     ) as total_stock
//                 ")
//             ])
//             ->orderBy('stock_movements.created_at', 'desc');

//         return DataTables::of($query)
//             ->addIndexColumn()
//             ->editColumn('created_at', function ($m) {
//                 return \Carbon\Carbon::parse($m->created_at)
//                     ->format('d-m-Y H:i');
//             })
//             ->editColumn('product_name', function ($m) {
//                 return $m->product_name ?? '-';
//             })
//             ->editColumn('party_name', function ($m) {
//                 return $m->party_name ?? '-';
//             })
//             ->filterColumn('party_name', function ($query, $keyword) {
//                 $query->where(function ($q) use ($keyword) {
//                     $q->where('vendors.name', 'like', "%{$keyword}%")
//                       ->orWhere('users.outlet_name', 'like', "%{$keyword}%");
//                 });
//             })
//             ->addColumn('reference', function ($m) {
//                 return $m->reference_type . ' #' . $m->reference_id;
//             })
//             ->addColumn('type', function ($m) {
//                 $badge = 'bg-secondary';
//                 if (
//                     in_array($m->movement_type, ['IN', 'IN_FREE'])
//                 ) {
//                     $badge = 'bg-success';
//                 }
//                 if (
//                     in_array($m->movement_type, ['OUT', 'RETURN'])
//                 ) {
//                     $badge = 'bg-danger';
//                 }
//                 if ($m->movement_type === 'PENDING_RETURN') {
//                     $badge = 'bg-warning';
//                 }
//                 $btn = '';
//                 if ($m->movement_type === 'PENDING_RETURN') {
//                     $btn = '
//                         <button
//                             class="btn btn-warning btn-sm return-btn"
//                             data-id="'.$m->id.'">
//                             Mark Returned
//                         </button>
//                     ';
//                 }
//                 return '
//                     <span class="badge '.$badge.'">
//                         '.$m->movement_type.'
//                     </span>
//                     '.$btn;
//             })
//             ->editColumn('quantity', function ($m) {
//                 return number_format($m->quantity, 2);
//             })
//             ->editColumn('total_stock', function ($m) {
//                 return number_format($m->total_stock, 2);
//             })
//             ->editColumn('unit_cost', function ($m) {
//                 return $m->unit_cost
//                     ? number_format($m->unit_cost, 2)
//                     : '-';
//             })
//             ->editColumn('remarks', function ($m) {
//                 return $m->remarks ?? '-';
//             })
//             ->rawColumns(['type'])
//             ->make(true);
//     }

//     return view('admin.stock.ledger');
// }




public function markReturned($id)
{
    $movement = StockMovement::findOrFail($id);

    if ($movement->movement_type !== 'PENDING_RETURN') {
        return response()->json([
            'status' => false,
            'message' => 'Invalid movement type'
        ]);
    }

    $movement->movement_type = 'RETURN';
    $movement->save();

    return response()->json([
        'status' => true,
        'message' => 'Marked as Returned successfully'
    ]);
}

public function createDebitNote($id)
{
    $movement = StockMovement::with('receiving.vendor')->findOrFail($id);

    if ($movement->movement_type !== 'PENDING_RETURN') {
        return response()->json([
            'status' => false,
            'message' => 'Only pending returns allowed'
        ]);
    }

    if ($movement->debit_note_created) {
        return response()->json([
            'status' => false,
            'message' => 'Debit note already created'
        ]);
    }

    DB::transaction(function () use ($movement) {

        // 👉 Mark as returned
        $movement->movement_type = 'RETURN';
        $movement->save();

        // 👉 OPTIONAL: Create debit note record (if you have table)
        // DebitNote::create([
        //     'vendor_id' => $movement->receiving->vendor_id,
        //     'amount' => ...,
        //     'reference_id' => $movement->id
        // ]);
    });

    return response()->json([
        'status' => true,
        'message' => 'Debit Note created successfully'
    ]);
}

    

    public function stock_receivings_edit($id)
{
    $grn = StockReceiving::with([
        'items.product.brand',
        'purchaseOrder'
    ])->findOrFail($id);

    if ($grn->status !== 'draft') {
        abort(403, 'Only draft stock receiving can be edited');
    }

    return view('admin.stock.edit', compact('grn'));
}

public function stock_receivings_bill_edit(VendorBill $bill)
    {
       $bill->load([
            'stockReceiving.items.product.brand',
            'stockReceiving.purchaseOrder.vendor'
        ]);

        // Only submitted bills can be edited
        if ($bill->stockReceiving->status !== 'submitted') {
            abort(403, 'Only submitted bills can be edited');
        }

        // We pass GRN for UI reuse
        $grn = $bill->stockReceiving;

        return view('admin.stock.bills_edit', compact('bill', 'grn'));
    }

//   public function stock_receivings_bill_update(Request $request, VendorBill $bill)
// {
//     $grn = $bill->stockReceiving;

//     if ($grn->status !== 'submitted') {
//         abort(403, 'Only submitted bills can be edited');
//     }

//     DB::transaction(function () use ($request, $bill, $grn) {

//         $items = json_decode($request->items, true);

      
//         $incomingPoItemIds = collect($items)
//             ->pluck('po_item_id')
//             ->filter()
//             ->values()
//             ->toArray();

      
//         StockReceivingItem::where('stock_receiving_id', $grn->id)
//             ->whereNotIn('purchase_order_item_id', $incomingPoItemIds)
//             ->delete();

       
//         foreach ($items as $item) {

//             StockReceivingItem::where([
//                 'stock_receiving_id'     => $grn->id,
//                 'purchase_order_item_id' => $item['po_item_id'],
//             ])->update([
//                 'actual_qty'    => $item['actual_qty'],
//                 'returned_qty'  => $item['returned_qty'] ?? 0,
//                 'return_reason' => $item['return_reason'] ?? null,
//                 'purchase_rate' => $item['purchase_rate'],
//                 'batch_no'      => $item['batch_no'] ?? null,
//                 'expiry_date'   => !empty($item['expiry_date']) ? $item['expiry_date'] : null,
//                 'mrp'           => $item['mrp'] ?? null,
//             ]);
//         }

       
//         $bill->update([
//             'bill_date'   => $request->bill_date,
//             'subtotal'    => $request->subtotal,
//             'tax_amount'  => $request->tax_amount,
//             'grand_total' => $request->grand_total,
//         ]);

       
//         $grn->update([
//             'subtotal'    => $request->subtotal,
//             'tax_amount'  => $request->tax_amount,
//             'grand_total' => $request->grand_total,
//         ]);
//     });

//     return response()->json([
//         'success' => true,
//         'redirect_url' => route('admin.stock-receivings.bills')
//     ]);
// }


public function stock_receivings_bill_update(Request $request, VendorBill $bill)
{
    $grn = $bill->stockReceiving;

    if ($grn->status !== 'submitted') {
        abort(403, 'Only submitted bills can be edited');
    }

    DB::transaction(function () use ($request, $bill, $grn) {

        $items = json_decode($request->items, true);

        $incomingPoItemIds = collect($items)
            ->pluck('po_item_id')
            ->filter()
            ->values()
            ->toArray();

        StockReceivingItem::where('stock_receiving_id', $grn->id)
            ->whereNotIn('purchase_order_item_id', $incomingPoItemIds)
            ->delete();

        foreach ($items as $item) {

            StockReceivingItem::where([
                'stock_receiving_id'     => $grn->id,
                'purchase_order_item_id' => $item['po_item_id'],
            ])->update([
                'actual_qty'           => $item['actual_qty'],
                'free_quantity'        => $item['freeqty'],
                'returned_qty'         => $item['returned_qty'] ?? 0,
                'return_reason'        => $item['return_reason'] ?? null,

               
                'to_be_return_qty'     => $item['to_be_return_qty'] ?? 0,
                'to_be_return_reason'  => $item['to_be_return_reason'] ?? null,
                
                'short_qty'            => $item['short_qty'] ?? 0,

                'purchase_rate'        => $item['purchase_rate'],
                'batch_no'             => $item['batch_no'] ?? null,
                'expiry_date'          => !empty($item['expiry_date']) ? $item['expiry_date'] : null,
                'mrp'                  => $item['mrp'] ?? null,
            ]);
        }
        
        
         $billFilename = $bill->original_bill;

        if ($request->hasFile('original_bill')) {
            $file = $request->file('original_bill');
            $billFilename = time().'_'.$file->getClientOriginalName();
            $file->move(
                public_path('uploads/stock_bills'),
                $billFilename
            );
        }

        $bill->update([
            'bill_date'   => $request->bill_date,
            'subtotal'    => $request->subtotal,
            'tax_amount'  => $request->tax_amount,
            'grand_total' => $request->grand_total,
            'original_bill' => $billFilename,
        ]);

        $grn->update([
            'subtotal'    => $request->subtotal,
            'tax_amount'  => $request->tax_amount,
            'grand_total' => $request->grand_total,
        ]);
    });

    return response()->json([
        'success' => true,
        'redirect_url' => route('admin.stock-receivings.bills')
    ]);
}


public function stock_receivings_update(Request $request, $id)
{
    $grn = StockReceiving::lockForUpdate()->findOrFail($id);

    if ($grn->status !== 'draft') {
        abort(403, 'Only draft GRN can be updated');
    }

    $items = json_decode($request->items, true);
    // dd($items);
    // dd('test');

    if (!is_array($items) || count($items) === 0) {
        return response()->json([
            'success' => false,
            'message' => 'At least one item is required'
        ], 422);
    }

    DB::transaction(function () use ($request, $grn, $items) {

        // 1. Update GRN header
        $grn->update([
            'receipt_date' => $request->receipt_date,
            'bill_date'    => $request->bill_date,
            'subtotal'     => $request->subtotal,
            'tax_amount'   => $request->tax_amount,
            'grand_total'  => $request->grand_total,
        ]);

        // 2. Remove all existing rows
        StockReceivingItem::where('stock_receiving_id', $grn->id)->delete();

        // 3. Insert fresh rows (this preserves multiple rows per product)
        foreach ($items as $item) {
            StockReceivingItem::create([
                'stock_receiving_id'      => $grn->id,
                'purchase_order_item_id'  => $item['po_item_id'],
                'product_id'              => $item['product_id'],
                'po_qty'                  => $item['po_qty'],
                'free_quantity'           => $item['freeQty'],
                'row_tax'                 => $item['row_tax'] ?? 0,

                'actual_qty'              => $item['actual_qty'],
                'returned_qty'            => $item['returned_qty'],
                'return_reason'           => $item['return_reason'],
                'to_be_return_qty'        => $item['to_be_return_qty'],
                'to_be_return_reason'     => $item['to_be_return_reason'],
                'short_qty'               => $item['short_qty'] ?? 0,

                'purchase_rate'           => $item['purchase_rate'],
                'batch_no'                => $item['batch_no'],
                'expiry_date'             => !empty($item['expiry_date']) ? $item['expiry_date'] : null,
                'mrp'                     => $item['mrp'],
            ]);
        }

        // 4. If submitted
        if ($request->save_type === 'submit') {

            $grn->update(['status' => 'submitted']);

            if (!$grn->vendorBill) {

                // $lastNumber = VendorBill::lockForUpdate()
                //     ->max(DB::raw("CAST(SUBSTRING(bill_no, 6) AS UNSIGNED)"));

                // $billNo = 'BILL-' . str_pad(($lastNumber ?? 0) + 1, 5, '0', STR_PAD_LEFT);

                VendorBill::create([
                    'stock_receiving_id' => $grn->id,
                    'purchase_order_id'  => $grn->purchase_order_id,
                    'vendor_id'          => $grn->vendor_id,
                    'bill_no'            => $grn->bill_no,
                    'bill_date'          => $grn->bill_date ?? now(),
                    'subtotal'           => $grn->subtotal,
                    'discount_percent'   => $grn->discount_percent,
                    'tax_amount'         => $grn->tax_amount,
                    'delivery_charges'   => $grn->delivery_charges,
                    'grand_total'        => $grn->grand_total,
                    'status'             => 'unpaid',
                ]);

                // $grn->update(['bill_no' => $grn->bill_No]);
            }

            $po = PurchaseOrderDetail::lockForUpdate()
                ->find($grn->purchase_order_id);

            if ($po && $po->status !== 'received') {
                $po->update(['status' => 'received']);
            }
        }
    });

    return response()->json([
        'success' => true,
        'redirect_url' => route('admin.stock-receivings.index')
    ]);
}


public function stock_receivings_pending()
{
    $pendingPOs = PurchaseOrderDetail::with(['items'])
        ->whereIn('status', ['approved'])
        ->whereHas('items', function ($q) {
            $q->whereColumn('received_qty', '<', 'quantity');
        })
        ->orderBy('id', 'desc')
        ->get();

    return view('admin.stock.pending', compact('pendingPOs'));
}


public function stock_receivings_bills()
{


    $bills = VendorBill::with([
            'stockReceiving.purchaseOrder',
            'stockReceiving.debitNote', 
            'vendor'
        ])
        ->orderBy('id', 'desc')
        ->get();

        // dd($bills);
    //  TOTAL BASIC AMOUNT (subtotal)
    $overallBasicAmount = VendorBill::sum('subtotal');    

    return view('admin.stock.bills', compact('bills', 'overallBasicAmount'));
}

public function stock_receivings_bill_show($id)
{
    $bill = VendorBill::with([
        'vendor',
        'payments',
        'stockReceiving.purchaseOrder',
        'stockReceiving.items.product'
    ])->findOrFail($id);

    return view('admin.stock.bill-show', compact('bill'));
}

// public function stock_receivings_review_submit(Request $request, $id)
// {

//     // dd($request->all());

//     $grn = StockReceiving::with(['items'])
//         ->lockForUpdate()
//         ->findOrFail($id);

    
//     if ($grn->status !== 'submitted') {
//         abort(403, 'Only submitted bills can be reviewed');
//     }

//     $request->validate([
//         'status' => 'required|in:approved,rejected',
//         'reason' => 'required_if:status,rejected',
//     ]);

//     DB::transaction(function () use ($request, $grn) {

//         if ($request->status === 'rejected') {

//             $grn->update([
//                 'status'           => 'rejected',
//                 'rejection_reason' => $request->reason,
//                 'reviewed_by'      => auth()->id(),
//                 'reviewed_at'      => now(),
//             ]);

//             return;
//         }

      

//         foreach ($grn->items as $item) {

//             $netQty = $item->actual_qty - ($item->returned_qty ?? 0);
//             if ($netQty <= 0) continue;

           
//             StockMovement::create([
//                 'product_id'     => $item->product_id,
//                 'reference_type' => 'GRN',
//                 'reference_id'   => $grn->id,
//                 'movement_type'  => 'IN',
//                 'quantity'       => $netQty,
//                 'unit_cost'      => $item->purchase_rate,
//                 'batch_no'       => $item->batch_no,
//                 'expiry_date'    => $item->expiry_date,
//                 'remarks'        => 'Stock received (Bill Approved)',
//             ]);

           
//             ProductStock::updateOrCreate(
//                 ['product_id' => $item->product_id],
//                 ['total_stock' => DB::raw("total_stock + {$netQty}")]
//             );

          
//             PurchaseOrderItem::where('id', $item->purchase_order_item_id)
//                 ->increment('received_qty', $item->actual_qty);
//         }

       
//         $grn->update([
//             'status'      => 'approved',
//             'reviewed_by' => auth()->id(),
//             'reviewed_at' => now(),
//         ]);

        
//     });

//     return redirect()
//         ->route('admin.stock-receivings.bills')
//         ->with('success', 'Bill reviewed successfully');
// }



// comment on 02-05-26
// private function updateProductCost($product, $newCost, $source = 'manual')
// {
//     $oldCost = (float) ($product->cost_per_item ?? 0);

//     // Skip if no change
//     if ($newCost == $oldCost) {
//         return;
//     }

//     $difference = $newCost - $oldCost;

    
//     $looseMargin  = $product->sale_price_loose_pcs - $oldCost;
//     $cartonMargin = $product->sale_price_carton - $oldCost;

//     $newLoosePrice  = $newCost + $looseMargin;
//     $newCartonPrice = $newCost + $cartonMargin;


//     $minLoosePrice  = $newCost * 1.03;
//     $minCartonPrice = $newCost * 1.03;

//     $newLoosePrice  = max($newLoosePrice, $minLoosePrice);
//     $newCartonPrice = max($newCartonPrice, $minCartonPrice);

//     $product->update([
//         'cost_per_item' => $newCost,

//         'sale_price_loose_pcs' => $newLoosePrice,
//         'sale_price_carton'    => $newCartonPrice,

      
//         'sale_price_loose_pcs_old' => $product->sale_price_loose_pcs,
//         'sale_price_carton_old'    => $product->sale_price_carton,
//     ]);

   
//     // if ($difference > 0) {
//     //     $this->syncCustomerPrices($product->id, $difference);
//     // }

//     if ($difference > 0) {

//     CustomerPriceChangeLog::create([
//         'product_id'    => $product->id,
//         'old_cost'      => $oldCost,
//         'new_cost'      => $newCost,
//         'difference'    => $difference,
//         'supplier_name' => $product->supplier_traced,
//         'status'        => 'pending',
//     ]);
// }

//     Log::info('Product price synced', [
//         'product_id' => $product->id,
//         'old_cost'   => $oldCost,
//         'new_cost'   => $newCost,
//         'difference' => $difference,
//         'source'     => $source,
//         'customer_sync' => $difference > 0 ? 'YES' : 'NO'
//     ]);
// }

// added on 05-05-26
// private function updateProductCost($product, $newCost, $source = 'manual', $extra = [])
// {
//     $oldCost = (float) ($product->cost_per_item ?? 0);
//     $difference = $newCost - $oldCost;

//     DB::transaction(function () use (
//         $product,
//         $newCost,
//         $oldCost,
//         $difference,
//         $source,
//         $extra
//     ) {

      
//         $metaUpdate = [
//             'supplier_traced'   => $extra['supplier_traced'] ?? $product->supplier_traced,
//             'vendor_id'         => $extra['vendor_id'] ?? $product->vendor_id,
//             'last_update_price' => $extra['last_update_price'] ?? now()->format('Y-m-d'),
//         ];

//         $product->update($metaUpdate);

//         Log::info('Product metadata updated (forced)', [
//             'product_id' => $product->id,
//             'vendor_id'  => $metaUpdate['vendor_id'],
//             'source'     => $source,
//         ]);

//         /*
//         |----------------------------------
//         | ONLY HANDLE COST IF CHANGED
//         |----------------------------------
//         */
//         if ($newCost != $oldCost) {

//             $looseMargin  = $product->sale_price_loose_pcs - $oldCost;
//             $cartonMargin = $product->sale_price_carton - $oldCost;

//             $newLoosePrice  = max($newCost + $looseMargin, $newCost * 1.03);
//             $newCartonPrice = max($newCost + $cartonMargin, $newCost * 1.03);

//             $product->update([
//                 'cost_per_item' => $newCost,

//                 'sale_price_loose_pcs_old' => $product->sale_price_loose_pcs,
//                 'sale_price_carton_old'    => $product->sale_price_carton,

//                 'sale_price_loose_pcs' => $newLoosePrice,
//                 'sale_price_carton'    => $newCartonPrice,
//             ]);

//             if ($difference > 0) {
//                 CustomerPriceChangeLog::create([
//                     'product_id'    => $product->id,
//                     'old_cost'      => $oldCost,
//                     'new_cost'      => $newCost,
//                     'difference'    => $difference,
//                     'supplier_name' => $metaUpdate['supplier_traced'],
//                     'status'        => 'pending',
//                 ]);
//             }

//             Log::info('Product price synced', [
//                 'product_id' => $product->id,
//                 'old_cost'   => $oldCost,
//                 'new_cost'   => $newCost,
//                 'difference' => $difference,
//                 'source'     => $source,
//             ]);
//         }
//     });
// }



// added on 15-07-26

private function updateProductCost($product, $newCost, $source = 'manual', $extra = [])
{
    $oldCost = (float) ($product->cost_per_item ?? 0);
    $difference = $newCost - $oldCost;

    DB::transaction(function () use (
        $product,
        $newCost,
        $oldCost,
        $difference,
        $source,
        $extra
    ) {

        $metaUpdate = [
            'supplier_traced'   => $extra['supplier_traced'] ?? $product->supplier_traced,
            'vendor_id'         => $extra['vendor_id'] ?? $product->vendor_id,
            'last_update_price' => $extra['last_update_price'] ?? now()->format('Y-m-d'),
        ];
        $product->update($metaUpdate);

        Log::info('Product metadata updated (forced)', [
            'product_id' => $product->id,
            'vendor_id'  => $metaUpdate['vendor_id'],
            'source'     => $source,
        ]);

        /*
        |----------------------------------
        | ONLY HANDLE COST IF CHANGED
        |----------------------------------
        */
        if ($newCost != $oldCost) {

            $looseMargin  = $product->sale_price_loose_pcs - $oldCost;
            $cartonMargin = $product->sale_price_carton - $oldCost;
            $newLoosePrice  = max($newCost + $looseMargin, $newCost * 1.03);
            $newCartonPrice = max($newCost + $cartonMargin, $newCost * 1.03);

            $product->update([
                'cost_per_item' => $newCost,
                'sale_price_loose_pcs_old' => $product->sale_price_loose_pcs,
                'sale_price_carton_old'    => $product->sale_price_carton,
                'sale_price_loose_pcs' => $newLoosePrice,
                'sale_price_carton'    => $newCartonPrice,
            ]);

            if ($difference > 0) {

                // Prevent duplicate log for the exact same price change,
                // regardless of whether the earlier one is pending, approved, or rejected
                $existingLog = CustomerPriceChangeLog::where('product_id', $product->id)
                    ->where('old_cost', $oldCost)
                    ->where('new_cost', $newCost)
                    ->exists();

                if (!$existingLog) {
                    CustomerPriceChangeLog::create([
                        'product_id'    => $product->id,
                        'old_cost'      => $oldCost,
                        'new_cost'      => $newCost,
                        'difference'    => $difference,
                        'supplier_name' => $metaUpdate['supplier_traced'],
                        'status'        => 'pending',
                    ]);
                } else {
                    Log::info('Duplicate price change log skipped (already exists in any status)', [
                        'product_id' => $product->id,
                        'old_cost'   => $oldCost,
                        'new_cost'   => $newCost,
                        'source'     => $source,
                    ]);
                }
            }

            Log::info('Product price synced', [
                'product_id' => $product->id,
                'old_cost'   => $oldCost,
                'new_cost'   => $newCost,
                'difference' => $difference,
                'source'     => $source,
            ]);
        }
    });
}


private function syncCustomerPrices($productId, $difference)
{
    if ($difference <= 0) {
        return;
    }

    CustomerPrice::where('product_id', $productId)
        ->chunkById(200, function ($rows) use ($difference) {

            foreach ($rows as $row) {

                $newPrice = max(0, $row->product_price + $difference);

                $row->update([
                    'product_price' => $newPrice
                ]);
            }
        });
}

//comment on 02-05-26
// private function syncLowestVendorCostToProducts()
// {
//     Log::info('Vendor cost sync started');

//     $vendorPrices = VendorPriceList::select(
//             'product_id',
//             'vendor_id',
//             'vendor_price'
//         )
//         ->whereNotNull('vendor_price')
//         ->where('vendor_price', '>', 0)
//         ->orderBy('vendor_price', 'asc')
//         ->get()
//         ->groupBy('product_id')
//         ->map(fn ($rows) => $rows->first());

//     if ($vendorPrices->isEmpty()) {
//         Log::warning('No vendor prices found');
//         return;
//     }

//     $vendorIds = $vendorPrices->pluck('vendor_id')->unique();

//     $vendors = Vendor::whereIn('id', $vendorIds)
//         ->pluck('name', 'id');

//     $today = Carbon::now()->format('Y-m-d');

//     Product::whereIn('id', $vendorPrices->keys())
//         ->chunkById(200, function ($products) use ($vendorPrices, $vendors, $today) {

//             foreach ($products as $product) {

//                 $row = $vendorPrices[$product->id];

//                 $newCost = (float) $row->vendor_price;

                
//                 $this->updateProductCost($product, $newCost, 'vendor');

//                 $product->update([
//                     'supplier_traced'   => $vendors[$row->vendor_id] ?? null,
//                     'vendor_id'         => $row->vendor_id,
//                     'last_update_price' => $today,
//                 ]);
//             }
//         });

//     Log::info('Vendor cost sync completed');
// }

//added on 05-05-26
private function syncLowestVendorCostToProducts($productId = null)
{
    Log::info('Vendor cost sync started', ['product_id' => $productId]);

    $query = VendorPriceList::select('product_id', 'vendor_price')
        ->whereNotNull('vendor_price')
        ->where('vendor_price', '>', 0)
        ->orderBy('vendor_price', 'asc');

    if ($productId) {
        $query->where('product_id', $productId);
    }

    $vendorPrices = $query->get()
        ->groupBy('product_id')
        ->map(fn ($rows) => $rows->first());

    if ($vendorPrices->isEmpty()) {
        Log::warning('No vendor prices found');
        return;
    }

    Product::whereIn('id', $vendorPrices->keys())
        ->get()
        ->each(function ($product) use ($vendorPrices) {

            $row = $vendorPrices[$product->id];

            
            $this->updateProductCost(
                $product,
                (float) $row->vendor_price,
                'vendor',
                []
            );
        });

    Log::info('Vendor cost sync completed');
}



//comment on 02-05-26
// private function updateVendorPrice($vendorId, $productId, $price, $source = 'manual')
// {
//     if (!$price || $price <= 0) {
//         return;
//     }

//     $vendorPrice = VendorPriceList::updateOrCreate(
//         [
//             'vendor_id'  => $vendorId,
//             'product_id' => $productId,
//         ],
//         [
//             'vendor_price' => $price,
//         ]
//     );

//     Log::info('Vendor price updated', [
//         'vendor_id'  => $vendorId,
//         'product_id' => $productId,
//         'price'      => $price,
//         'source'     => $source,
//     ]);

//     return $vendorPrice;
// }


//added on 02-05-26

private function updateVendorPrice($vendorId, $productId, $price, $source = 'manual')
{
    if (!$price || $price <= 0) {
        return;
    }

    $vendorPrice = VendorPriceList::updateOrCreate(
        [
            'vendor_id'  => $vendorId,
            'product_id' => $productId,
        ],
        [
            'vendor_price' => $price,
        ]
    );

    $this->syncLowestVendorCostToProducts($productId);

    Log::info('Vendor price updated', [
        'vendor_id'  => $vendorId,
        'product_id' => $productId,
        'price'      => $price,
        'source'     => $source,
    ]);

    return $vendorPrice;
}

public function stock_receivings_review_submit(Request $request, $id)
{
    $grn = StockReceiving::with(['items'])
        ->lockForUpdate()
        ->findOrFail($id);

    if ($grn->status !== 'submitted') {
        abort(403, 'Only submitted bills can be reviewed');
    }

    $request->validate([
       'status' => 'required|in:approved,approved_with_changes,rejected',
       'reason' => 'required_if:status,rejected,approved_with_changes',

    ]);

    DB::transaction(function () use ($request, $grn) {

        /* ----------------------------
           IF REJECTED
        ----------------------------- */
        if ($request->status === 'rejected') {
            $grn->update([
                'status'           => 'rejected',
                'rejection_reason' => $request->reason,
                'reviewed_by'      => auth()->id(),
                'reviewed_at'      => now(),
            ]);
            return;
        }



        /* ----------------------------
           IF APPROVED
           Blade has already ensured:
           actual + returned + to_be_return = PO qty
           usable_qty is already calculated in blade and saved
        ----------------------------- */

        foreach ($grn->items as $item) {

            $actualQty     = $item->actual_qty;
            $returnedQty   = $item->returned_qty ?? 0;
            $toBeReturnQty = $item->to_be_return_qty ?? 0;
            $freeQty = $item->free_quantity ?? 0;
            $totalQty = $actualQty + $freeQty;

          

            /*
             |----------------------------------
             | 1. Physical arrival movement
             |----------------------------------
             */
            if ($actualQty > 0) {
                StockMovement::create([
                    'product_id'     => $item->product_id,
                    'reference_type' => 'GRN',
                    'reference_id'   => $grn->id,
                    'movement_type'  => 'IN',
                    'quantity'       => $actualQty,
                    'unit_cost'      => $item->purchase_rate,
                    'batch_no'       => $item->batch_no,
                    'expiry_date'    => $item->expiry_date,
                    'remarks'        => 'Stock received (Physical arrival)',
                ]);
            }
            
            if ($freeQty > 0) {
                StockMovement::create([
                    'product_id'     => $item->product_id,
                    'reference_type' => 'GRN',
                    'reference_id'   => $grn->id,
                    'movement_type'  => 'IN_FREE',
                    'quantity'       => $freeQty,
                    'unit_cost'      => 0,
                    'batch_no'       => $item->batch_no,
                    'expiry_date'    => $item->expiry_date,
                    'remarks'        => 'Stock received (Free Qty)',
                ]);
            }

            /*
             |----------------------------------
             | 2. Immediate return movement
             |----------------------------------
             */
            if ($returnedQty > 0) {
                StockMovement::create([
                    'product_id'     => $item->product_id,
                    'reference_type' => 'GRN',
                    'reference_id'   => $grn->id,
                    'movement_type'  => 'RETURN',
                    'quantity'       => $returnedQty,
                    'unit_cost'      => $item->purchase_rate,
                    'batch_no'       => $item->batch_no,
                    'expiry_date'    => $item->expiry_date,
                    'remarks'        => 'Stock returned to vendor immediately',
                ]);
            }

            /*
             |----------------------------------
             | 3. Pending return movement
             |----------------------------------
             */
            if ($toBeReturnQty > 0) {
                StockMovement::create([
                    'product_id'     => $item->product_id,
                    'reference_type' => 'GRN',
                    'reference_id'   => $grn->id,
                    'movement_type'  => 'PENDING_RETURN',
                    'quantity'       => $toBeReturnQty,
                    'unit_cost'      => $item->purchase_rate,
                    'batch_no'       => $item->batch_no,
                    'expiry_date'    => $item->expiry_date,
                    'remarks'        => 'Stock marked for return (Pending)',
                ]);
            }


            ProductStock::updateOrCreate(
                ['product_id' => $item->product_id],
                ['total_stock' => DB::raw("total_stock + {$totalQty}")]
            );
            
           
            // ProductStock::updateOrCreate(
            //     ['product_id' => $item->product_id],
            //     ['total_stock' => DB::raw("total_stock + {$actualQty}")]
            // );

            /*
             |----------------------------------
             | 5. Update PO received quantity
             |----------------------------------
             */
            PurchaseOrderItem::where('id', $item->purchase_order_item_id)
                ->increment('received_qty', $totalQty);
                
                //  FIX 1: Vendor Price (INSIDE LOOP)
                    $this->updateVendorPrice(
                        $grn->vendor_id,
                        $item->product_id,
                        $item->purchase_rate,
                        'GRN'
                    );

                    // FIX 2: Product Cost (INSIDE LOOP)
                    $product = Product::find($item->product_id);

                    if ($product && $item->purchase_rate > 0) {

                        $this->updateProductCost(
                            $product,
                            (float) $item->purchase_rate,
                            'GRN',
                            [
                                    'supplier_traced'   => Vendor::where('id', $grn->vendor_id)->value('name'),
                                    'vendor_id'         => $grn->vendor_id,
                                    'last_update_price' => now()->format('Y-m-d'),
                            ]
                        );
                    }
        }

        /*
         |----------------------------------
         | 6. Update GRN status
         |----------------------------------
         */
       $grn->update([
        'status'           => $request->status === 'approved_with_changes' ? 'approved_with_changes' : 'approved',
        'rejection_reason' => $request->status === 'approved_with_changes' ? $request->reason : null,
        'reviewed_by'      => auth()->id(),
        'reviewed_at'      => now(),
    ]);

    });

    return redirect()
        ->route('admin.stock-receivings.bills')
        ->with('success', 'Bill reviewed and stock movements recorded successfully');
}


  

public function stock_receivings_destroy($id)
{
    
}


}
