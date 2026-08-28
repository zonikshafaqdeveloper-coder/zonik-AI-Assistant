<?php

namespace App\Http\Controllers;

use App\Models\StockReceiving;
use App\Models\StockReceivingItem;
use App\Models\ProductStock;
use App\Models\RackStock;
use App\Models\StockMovement;
use App\Models\DebitNoteItem;
use App\Models\VendorBill;
use App\Models\DebitNote;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DebitNoteController extends Controller
{
//     public function index(Request $request)
// {
//     $receivings = StockReceiving::with([
//         'vendor',
//         'debitNotes',
//         'purchaseOrder',
//         'vendorBill'
//     ])
//         ->where('status', 'approved');

//     if ($request->filled('month')) {
//         $date = Carbon::parse($request->month);

//         $receivings->whereYear('created_at', $date->year)
//                   ->whereMonth('created_at', $date->month);
//     }

//     $receivings = $receivings->latest()->get();

//     return view('admin.debitnote.index', compact('receivings'));
// }


public function index(Request $request)
{
    $receivings = StockReceiving::with([
        'vendor', 'debitNotes', 'purchaseOrder', 'vendorBill'
    ])->where('status', 'approved');

    $openingNotes = DebitNote::with('vendor')
        ->where('is_opening_stock', true);

    if ($request->filled('month')) {
        $date = Carbon::parse($request->month);

        $receivings->whereYear('created_at', $date->year)
                   ->whereMonth('created_at', $date->month);

        $openingNotes->whereYear('created_at', $date->year)
                      ->whereMonth('created_at', $date->month);
    }

    $receivings   = $receivings->latest()->get();
    $openingNotes = $openingNotes->latest()->get();

    $rows = collect();

    foreach ($receivings as $grn) {
        $rows->push([
            'type'        => 'grn',
            'id'          => $grn->id,
            'sort_date'   => $grn->created_at,
            'grn_no'      => 'GRN-' . str_pad($grn->id, 4, '0', STR_PAD_LEFT),
            'po_no'       => $grn->purchaseOrder->purchase_order_number ?? '-',
            'bill_no'     => $grn->vendorBill->bill_no ?? $grn->bill_no ?? '-',
            'supplier'    => $grn->vendor->name ?? '-',
            'date'        => $grn->receipt_date,
            'debit_notes' => $grn->debitNotes,
            'create_url'  => route('debitnote.create', $grn->id),
        ]);
    }

    foreach ($openingNotes as $note) {
        $rows->push([
            'type'        => 'opening',
            'id'          => $note->id,
            'sort_date'   => $note->created_at,
            'grn_no'      => 'Opening Stock',
            'po_no'       => '-',
            'bill_no'     => '-',
            'supplier'    => $note->vendor->name ?? '-',
            'date'        => $note->created_at,
            'debit_notes' => collect([$note]),
            'create_url'  => null,
        ]);
    }

    $rows = $rows->sortByDesc('sort_date')->values();

    return view('admin.debitnote.index', compact('rows'));
}

public function create($id)
    {
        $receiving = StockReceiving::with(['items.product','vendor'])
            ->findOrFail($id);

        if ($receiving->debitNote) {
            return redirect()->route('debitnote.index')
                ->with('error','Debit note already created.');
        }

        return view('admin.debitnote.create', compact('receiving'));
    }

public function store(Request $request, $id)
{
    $request->validate([
        'items' => 'required|array',
        'items.*.return_qty' => 'nullable|integer|min:0',
        'items.*.reason' => 'nullable|string|max:255'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Check at least one return qty entered
    |--------------------------------------------------------------------------
    */
    $hasReturn = false;

    foreach ($request->items as $item) {
        if ((int)($item['return_qty'] ?? 0) > 0) {
            $hasReturn = true;
            break;
        }
    }

    if (!$hasReturn) {
        return response()->json([
            'message' => 'Please enter at least one return quantity.'
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | Validate qty BEFORE transaction
    |--------------------------------------------------------------------------
    */
    foreach ($request->items as $itemId => $data) {

        $returnQty = (int) ($data['return_qty'] ?? 0);
        if ($returnQty <= 0) continue;

        $receivingItem = StockReceivingItem::find($itemId);

        if ($returnQty > $receivingItem->actual_qty) {
            return response()->json([
                'message' => 'Return qty exceeds received qty.'
            ], 422);
        }

        // Prevent negative stock
        $currentStock = ProductStock::where('product_id', $receivingItem->product_id)
                            ->value('total_stock');

        if ($returnQty > $currentStock) {
            return response()->json([
                'message' => 'Insufficient stock available.'
            ], 422);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSACTION START
    |--------------------------------------------------------------------------
    */
    DB::transaction(function () use ($request, $id) {

        $receiving = StockReceiving::with('items.product')
                        ->findOrFail($id);

        $debitNote = DebitNote::create([
            'stock_receiving_id' => $id,
            'vendor_id'         => $receiving->vendor_id,
            'debit_note_no'     => 'DN-' . time(),
            'total_amount'      => 0
        ]);

        $grandTotal = 0;

        foreach ($request->items as $itemId => $data) {

            $returnQty = (int) ($data['return_qty'] ?? 0);
            if ($returnQty <= 0) continue;

            $receivingItem = StockReceivingItem::findOrFail($itemId);

            /*
            |--------------------------------------------------------------------------
            | STOCK DECREMENT
            |--------------------------------------------------------------------------
            */
            ProductStock::where('product_id', $receivingItem->product_id)
                ->decrement('total_stock', $returnQty);
                
                
                
            $rackStocks = RackStock::where('stock_receiving_id', $id)
                ->where('product_id', $receivingItem->product_id)
                ->orderBy('id')
                ->get();

            $remaining = $returnQty;

            foreach ($rackStocks as $rackStock) {

                if ($remaining <= 0) break;
                if ($rackStock->quantity <= 0) continue;

                if ($rackStock->quantity >= $remaining) {
                    $rackStock->quantity -= $remaining;
                    $rackStock->save();
                    $remaining = 0;
                } else {
                    $remaining -= $rackStock->quantity;
                    $rackStock->quantity = 0;
                    $rackStock->save();
                }
            }

            if ($remaining > 0) {
                throw new \Exception(
                    "Insufficient rack stock for product: {$receivingItem->product->product_name}. Short by {$remaining} units."
                );
            }    


            /*
            |--------------------------------------------------------------------------
            | STOCK MOVEMENT (OUT)
            |--------------------------------------------------------------------------
            */
            StockMovement::create([
                'product_id'     => $receivingItem->product_id,
                'reference_type' => 'DEBIT_NOTE',
                'reference_id'   => $debitNote->id,
                'movement_type'  => 'OUT',
                'quantity'       => $returnQty,
                'unit_cost'      => $receivingItem->purchase_rate,
                'batch_no'       => $receivingItem->batch_no,
                'expiry_date'    => $receivingItem->expiry_date,
                'remarks'        => "Returned to Vendor - GRN #{$receiving->id}"
            ]);


            /*
            |--------------------------------------------------------------------------
            | Amount Calculation
            |--------------------------------------------------------------------------
            */
            $pretax = $returnQty * $receivingItem->purchase_rate;
            $tax = ($pretax * ($receivingItem->row_tax ?? 0)) / 100;
            $total = $pretax + $tax;

            $grandTotal += $total;


            /*
            |--------------------------------------------------------------------------
            | Save Debit Note Item
            |--------------------------------------------------------------------------
            */
            DebitNoteItem::create([
                'debit_note_id' => $debitNote->id,
                'stock_receiving_item_id' => $itemId,
                'return_qty' => $returnQty,
                'reason' => $data['reason'] ?? null,
                'price' => $receivingItem->purchase_rate,
                'tax' => $tax,
                'total' => $total
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Debit Note Total
        |--------------------------------------------------------------------------
        */
        $debitNote->update([
            'total_amount' => $grandTotal
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vendor Bill Adjustment (simple version)
        |--------------------------------------------------------------------------
        */
        $vendorBill = VendorBill::where('stock_receiving_id', $receiving->id)->first();

        if ($vendorBill) {
            $vendorBill->grand_total = max(
                0,
                (float)$vendorBill->grand_total - (float)$grandTotal
            );

            $vendorBill->save();
        }

    });

    return response()->json([
        'success' => true,
        'message' => 'Debit note created successfully'
    ]);
}


// public function download($id)
// {
//     $note = DebitNote::with([
//         'items.receivingItem.product',
//         'vendor',
//         'receiving'
//     ])->findOrFail($id);

//     if (!$note->receiving) {
//         dd('Receiving not found for DebitNote ID: '.$note->id);
//     }

//     $pdf = PDF::loadView('admin.debitnote.pdf', compact('note'));

//     return $pdf->stream('debit_note_'.$note->debit_note_no.'.pdf');
// }

public function download($id)
{
    $note = DebitNote::with([
        'items.receivingItem.product',
        'items.product',
        'vendor',
        'receiving'
    ])->findOrFail($id);

    if (!$note->is_opening_stock && !$note->receiving) {
        abort(404, 'Receiving not found for this debit note.');
    }

    $pdf = PDF::loadView('admin.debitnote.pdf', compact('note'));

    return $pdf->stream('debit_note_'.$note->debit_note_no.'.pdf');
}





    public function generateDebitNote($id)
{
    $receiving = StockReceiving::with([
        'vendor',
        'items.product'
    ])->findOrFail($id);

   
    $items = $receiving->items->filter(function ($item) {
        return $item->returned_qty > 0 || $item->to_be_return_qty > 0;
    });

    $view = view('admin.debitnote.debit_note', compact(
        'receiving',
        'items'
    ))->render();

    $pdf = PDF::loadHTML($view);

    return $pdf->stream('debit_note_'.$receiving->id.'.pdf');
}

public function createFromExpiry(Request $request)
{
    // dd($request->all());
    // Find GRN item using product + batch + expiry
$item = StockReceivingItem::with(['product','stockReceiving.vendor'])
    ->where('product_id', $request->product_id)
    ->where('batch_no', $request->batch_no)
    ->whereDate('expiry_date', $request->expiry_date)
    ->orderBy('id','asc')
    ->first();

    // dd($item);


if (!$item) {
    return redirect()->back()
        ->with('error', 'No matching GRN item found for this stock.');
}
       
    // dd($item);    

    // Get rack stock qty
    $rackStock = DB::table('rack_stocks')
        ->where('product_id', $request->product_id)
        ->where('batch_no', $request->batch_no)
        ->where('expiry_date', $request->expiry_date)
        ->first();
        

    return view('admin.debitnote.from_expiry', compact('item','rackStock'));
}

public function storeFromExpiry(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */
    $request->validate([
        'product_id' => 'required',
        'batch_no' => 'required',
        'expiry_date' => 'required|date',
        'return_qty' => 'required|integer|min:1'
    ]);

    /*
    |--------------------------------------------------------------------------
    | FETCH RACK STOCK
    |--------------------------------------------------------------------------
    */
    $rackStock = DB::table('rack_stocks')
        ->where('product_id', $request->product_id)
        ->where('batch_no', $request->batch_no)
        ->whereDate('expiry_date', $request->expiry_date)
        ->first();

    if (!$rackStock) {
        return response()->json([
            'message' => 'Rack stock not found'
        ], 404);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE QTY
    |--------------------------------------------------------------------------
    */
    if ($request->return_qty > $rackStock->quantity) {
        return response()->json([
            'message' => 'Return quantity exceeds available rack stock'
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | FETCH RECEIVING ITEM (FIFO SAFE)
    |--------------------------------------------------------------------------
    */
    $item = StockReceivingItem::with(['stockReceiving'])
        ->where('product_id', $request->product_id)
        ->where('batch_no', $request->batch_no)
        ->whereDate('expiry_date', $request->expiry_date)
        ->orderBy('id', 'asc')
        ->first();

    if (!$item) {
        return response()->json([
            'message' => 'No matching GRN item found'
        ], 404);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATE VENDOR (CRITICAL FIX)
    |--------------------------------------------------------------------------
    */
    if (!$item->stockReceiving || !$item->stockReceiving->vendor_id) {
        return response()->json([
            'message' => 'Vendor not found for this stock. Cannot create debit note.'
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | START TRANSACTION
    |--------------------------------------------------------------------------
    */
    DB::beginTransaction();

    try {

        $returnQty = (int) $request->return_qty;

        /*
        |--------------------------------------------------------------------------
        | CREATE DEBIT NOTE
        |--------------------------------------------------------------------------
        */
        $debitNote = DebitNote::create([
            'stock_receiving_id' => $item->stock_receiving_id,
            'vendor_id' => $item->stockReceiving->vendor_id,
            'debit_note_no' => 'DN-' . time(),
            'total_amount' => 0
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDUCE PRODUCT STOCK
        |--------------------------------------------------------------------------
        */
        ProductStock::where('product_id', $item->product_id)
            ->decrement('total_stock', $returnQty);

        /*
        |--------------------------------------------------------------------------
        | REDUCE RACK STOCK
        |--------------------------------------------------------------------------
        */
        DB::table('rack_stocks')
            ->where('product_id', $request->product_id)
            ->where('batch_no', $request->batch_no)
            ->whereDate('expiry_date', $request->expiry_date)
            ->decrement('quantity', $returnQty);

        /*
        |--------------------------------------------------------------------------
        | STOCK MOVEMENT ENTRY
        |--------------------------------------------------------------------------
        */
        StockMovement::create([
            'product_id'     => $item->product_id,
            'reference_type' => 'DEBIT_NOTE',
            'reference_id'   => $debitNote->id,
            'movement_type'  => 'OUT',
            'quantity'       => $returnQty,
            'unit_cost'      => $item->purchase_rate,
            'batch_no'       => $item->batch_no,
            'expiry_date'    => $item->expiry_date,
            'remarks'        => 'Near Expiry Return'
        ]);

        /*
        |--------------------------------------------------------------------------
        | CALCULATE AMOUNT
        |--------------------------------------------------------------------------
        */
        $pretax = $returnQty * $item->purchase_rate;
        $tax = ($pretax * ($item->row_tax ?? 0)) / 100;
        $total = $pretax + $tax;

        /*
        |--------------------------------------------------------------------------
        | SAVE DEBIT NOTE ITEM
        |--------------------------------------------------------------------------
        */
        DebitNoteItem::create([
            'debit_note_id' => $debitNote->id,
            'stock_receiving_item_id' => $item->id,
            'return_qty' => $returnQty,
            'reason' => $request->reason,
            'price' => $item->purchase_rate,
            'tax' => $tax,
            'total' => $total
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE TOTAL
        |--------------------------------------------------------------------------
        */
        $debitNote->update([
            'total_amount' => $total
        ]);

        /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */
        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Debit note created successfully'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

public function createFromExpired(Request $request)
{
    //   dd($request->all());
    $item = StockReceivingItem::with(['product','stockReceiving.vendor'])
        ->where('product_id', $request->product_id)
        ->where('batch_no', $request->batch_no)
        ->whereDate('expiry_date', $request->expiry_date)
        ->orderBy('id','asc')
        ->first();

       
    // Find GRN item using product + batch + expiry


        // dd($item);
    if (!$item) {
        return back()->with('error', 'No matching GRN item found');
    }

    $rackStock = DB::table('rack_stocks')
        ->where('product_id', $request->product_id)
        ->where('batch_no', $request->batch_no)
        ->whereDate('expiry_date', $request->expiry_date)
        ->first();

    return view('admin.debitnote.fromdata_expired', compact('item','rackStock'));
}

public function storeFromExpired(Request $request)
{
    /*
    |--------------------------------------------------
    | VALIDATION
    |--------------------------------------------------
    */
    $request->validate([
        'product_id'  => 'required',
        'batch_no'    => 'required',
        'expiry_date' => 'required|date',
        'return_qty'  => 'required|integer|min:1'
    ]);

    /*
    |--------------------------------------------------
    | FETCH RACK STOCK
    |--------------------------------------------------
    */
    $rackStock = DB::table('rack_stocks')
        ->where('product_id', $request->product_id)
        ->where('batch_no', $request->batch_no)
        ->whereDate('expiry_date', $request->expiry_date)
        ->first();

    if (!$rackStock) {
        return response()->json([
            'message' => 'Rack stock not found'
        ], 404);
    }

    /*
    |--------------------------------------------------
    | VALIDATE QUANTITY
    |--------------------------------------------------
    */
    if ($request->return_qty > $rackStock->quantity) {
        return response()->json([
            'message' => 'Return quantity exceeds available stock'
        ], 422);
    }

    /*
    |--------------------------------------------------
    | FETCH GRN ITEM (FIFO SAFE)
    |--------------------------------------------------
    */
    $item = StockReceivingItem::with(['stockReceiving'])
        ->where('product_id', $request->product_id)
        ->where('batch_no', $request->batch_no)
        ->whereDate('expiry_date', $request->expiry_date)
        ->orderBy('id', 'asc')
        ->first();

    if (!$item) {
        return response()->json([
            'message' => 'No matching GRN item found'
        ], 404);
    }

    /*
    |--------------------------------------------------
    | VALIDATE VENDOR
    |--------------------------------------------------
    */
    if (!$item->stockReceiving || !$item->stockReceiving->vendor_id) {
        return response()->json([
            'message' => 'Vendor not found for this stock'
        ], 422);
    }

    DB::beginTransaction();

    try {

        $qty = (int) $request->return_qty;

        /*
        |--------------------------------------------------
        | CREATE DEBIT NOTE
        |--------------------------------------------------
        */
        $debitNote = DebitNote::create([
            'stock_receiving_id' => $item->stock_receiving_id,
            'vendor_id'          => $item->stockReceiving->vendor_id,
            'debit_note_no'      => 'DN-' . time(),
            'total_amount'       => 0
        ]);

        /*
        |--------------------------------------------------
        | REDUCE PRODUCT STOCK
        |--------------------------------------------------
        */
        ProductStock::where('product_id', $item->product_id)
            ->decrement('total_stock', $qty);

        /*
        |--------------------------------------------------
        | REDUCE RACK STOCK
        |--------------------------------------------------
        */
        DB::table('rack_stocks')
            ->where('product_id', $request->product_id)
            ->where('batch_no', $request->batch_no)
            ->whereDate('expiry_date', $request->expiry_date)
            ->decrement('quantity', $qty);

        /*
        |--------------------------------------------------
        | STOCK MOVEMENT
        |--------------------------------------------------
        */
        StockMovement::create([
            'product_id'     => $item->product_id,
            'reference_type' => 'DEBIT_NOTE',
            'reference_id'   => $debitNote->id,
            'movement_type'  => 'OUT',
            'quantity'       => $qty,
            'unit_cost'      => $item->purchase_rate,
            'batch_no'       => $item->batch_no,
            'expiry_date'    => $item->expiry_date,
            'remarks'        => 'Expired Return'
        ]);

        /*
        |--------------------------------------------------
        | CALCULATE AMOUNT
        |--------------------------------------------------
        */
        $pretax = $qty * $item->purchase_rate;
        $tax    = ($pretax * ($item->row_tax ?? 0)) / 100;
        $total  = $pretax + $tax;

        /*
        |--------------------------------------------------
        | CREATE DEBIT NOTE ITEM
        |--------------------------------------------------
        */
        DebitNoteItem::create([
            'debit_note_id'          => $debitNote->id,
            'stock_receiving_item_id'=> $item->id,
            'return_qty'             => $qty,
            'reason'                 => $request->reason,
            'price'                  => $item->purchase_rate,
            'tax'                    => $tax,
            'total'                  => $total
        ]);

        /*
        |--------------------------------------------------
        | UPDATE TOTAL
        |--------------------------------------------------
        */
        $debitNote->update([
            'total_amount' => $total
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Debit note created successfully'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}


public function createFromOpeningStock(Request $request)
{
    $request->validate([
        'product_id'  => 'required',
        'batch_no'    => 'required',
        'expiry_date' => 'required|date',
    ]);

    $rackStock = DB::table('rack_stocks')
        ->where('product_id', $request->product_id)
        ->where('batch_no', $request->batch_no)
        ->whereDate('expiry_date', $request->expiry_date)
        ->whereNull('stock_receiving_id')
        ->first();

    if (!$rackStock) {
        return back()->with('error', 'Opening stock record not found');
    }

    $product = Product::find($request->product_id);

    // Adjust the model/relationship name if your vendors table differs
    $vendors = Vendor::orderBy('name')->get();

    return view('admin.debitnote.fromdata_opening_stock', compact('product', 'rackStock', 'vendors'));
}

public function storeFromOpeningStock(Request $request)
{
    $request->validate([
        'vendor_id'   => 'required|exists:vendors,id',
        'product_id'  => 'required',
        'batch_no'    => 'required',
        'expiry_date' => 'required|date',
        'return_qty'  => 'required|integer|min:1',
        'rate'        => 'required|numeric|min:0',
        'tax_percent' => 'nullable|numeric|min:0',
        'reason'      => 'nullable|string',
    ]);

    $rackStock = DB::table('rack_stocks')
        ->where('product_id', $request->product_id)
        ->where('batch_no', $request->batch_no)
        ->whereDate('expiry_date', $request->expiry_date)
        ->whereNull('stock_receiving_id')
        ->first();

    if (!$rackStock) {
        return response()->json(['message' => 'Opening stock record not found'], 404);
    }

    if ($request->return_qty > $rackStock->quantity) {
        return response()->json(['message' => 'Return quantity exceeds available stock'], 422);
    }

    DB::beginTransaction();

    try {
        $qty  = (int) $request->return_qty;
        $rate = (float) $request->rate;

        $debitNote = DebitNote::create([
            'stock_receiving_id' => null,
            'vendor_id'          => $request->vendor_id,
            'debit_note_no'      => 'DN-' . time(),
            'is_opening_stock'   => true,
            'total_amount'       => 0,
        ]);

        ProductStock::where('product_id', $request->product_id)
            ->decrement('total_stock', $qty);

        DB::table('rack_stocks')
            ->where('product_id', $request->product_id)
            ->where('batch_no', $request->batch_no)
            ->whereDate('expiry_date', $request->expiry_date)
            ->whereNull('stock_receiving_id')
            ->decrement('quantity', $qty);

        StockMovement::create([
            'product_id'     => $request->product_id,
            'reference_type' => 'DEBIT_NOTE',
            'reference_id'   => $debitNote->id,
            'movement_type'  => 'OUT',
            'quantity'       => $qty,
            'unit_cost'      => $rate,
            'batch_no'       => $request->batch_no,
            'expiry_date'    => $request->expiry_date,
            'remarks'        => 'Opening Stock Return',
        ]);

        $pretax = $qty * $rate;
        $tax    = ($pretax * ($request->tax_percent ?? 0)) / 100;
        $total  = $pretax + $tax;

        DebitNoteItem::create([
            'debit_note_id'           => $debitNote->id,
            'stock_receiving_item_id' => null,
            'product_id'              => $request->product_id,
            'batch_no'                => $request->batch_no,
            'expiry_date'             => $request->expiry_date,
            'return_qty'              => $qty,
            'reason'                  => $request->reason,
            'price'                   => $rate,
            'tax'                     => $tax,
            'total'                   => $total,
        ]);

        $debitNote->update(['total_amount' => $total]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Debit note created successfully for opening stock',
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
    }
}



}
