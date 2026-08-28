<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use App\Models\VendorPriceList;
use App\Models\VendorPaymentTerm;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\ProductStock;
use App\Models\StockReceivingItem;
use App\Models\RackStock;


class PurchaseOrderController extends Controller
{

public function index()
{
    $purchaseOrders = PurchaseOrderDetail::with('vendor')
        ->latest()
        ->get();
    //Add total basic value function:
      $overallBasicAmount = $purchaseOrders->sum('subtotal_basic'); 

    return view('admin.purchase_orders.index', compact('purchaseOrders','overallBasicAmount'));
}

    public function create()
{
    $lastPo = PurchaseOrderDetail::latest('id')->first();
    if ($lastPo) {
        $lastNumber = (int) str_replace('IGPO-', '', $lastPo->purchase_order_number);
        $nextNumber = $lastNumber + 1;
    } else {
        $nextNumber = 1;
    }
    $purchaseOrderNumber = 'IGPO-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    $vendors = Vendor::orderBy('name')->get();

    return view('admin.purchase_orders.create', compact('purchaseOrderNumber','vendors'));
}


// public function checkProductAllocation($productId)
// {
//     $grnReceivingIds = StockReceivingItem::whereHas('stockReceiving', function ($q) {
//             $q->whereIn('status', ['approved', 'approved_with_changes']);
//         })
//         ->where('product_id', $productId)
//         ->whereRaw('actual_qty > returned_qty')
//         ->whereRaw('(actual_qty + free_quantity) > 0')
//         ->pluck('stock_receiving_id')
//         ->toArray();

//     // if (empty($grnReceivingIds)) {
//     //     return response()->json([
//     //         'allocated' => false,
//     //         'message'   => 'This product has no approved GRN entries with available stock.'
//     //     ]);
//     // }

//     $allocatedIds = RackStock::where('product_id', $productId)
//         ->pluck('stock_receiving_id')
//         ->toArray();

//     $unallocated = array_diff($grnReceivingIds, $allocatedIds);

//     if (!empty($unallocated)) {
//     $latestUnallocated = max(array_values($unallocated));

//     $grnNumbers = array_map(fn($id) => 'IGGRN-' . str_pad($id, 5, '0', STR_PAD_LEFT), array_values($unallocated));
//     $grnList    = implode(', ', $grnNumbers);

//     return response()->json([
//         'allocated'              => false,
//         'latest_unallocated_id'  => $latestUnallocated,
//         'message'                => "Product has unallocated stock in: {$grnList}. Please complete rack allocation before adding to PO."
//     ]);
// }

//     return response()->json([
//         'allocated' => true,
//         'message'   => 'Product is rack allocated.'
//     ]);
// }



public function checkAnyPendingRackAllocation()
{
    $receivingItems = StockReceivingItem::whereHas('stockReceiving', function ($q) {
            $q->whereIn('status', ['approved', 'approved_with_changes']);
        })
        ->whereRaw('actual_qty > returned_qty')
        ->whereRaw('(actual_qty + free_quantity) > 0')
        ->get(['stock_receiving_id', 'product_id']);

    if ($receivingItems->isEmpty()) {
        return response()->json(['allocated' => true, 'message' => 'No pending rack allocation.']);
    }

    // Existence of a rack_stocks row for this (receiving_id, product_id) pair = it was allocated,
    // regardless of current quantity (qty can legitimately be 0 once sold out).
    $allocatedPairs = RackStock::whereIn('stock_receiving_id', $receivingItems->pluck('stock_receiving_id')->unique())
        ->get(['stock_receiving_id', 'product_id'])
        ->map(fn ($r) => $r->stock_receiving_id . '-' . $r->product_id)
        ->unique()
        ->flip();

    $unallocated = $receivingItems->reject(
        fn ($item) => isset($allocatedPairs[$item->stock_receiving_id . '-' . $item->product_id])
    );

    if ($unallocated->isEmpty()) {
        return response()->json(['allocated' => true, 'message' => 'No pending rack allocation.']);
    }

    $latestUnallocated = $unallocated->max('stock_receiving_id');

    $grnList = $unallocated->pluck('stock_receiving_id')->unique()
        ->map(fn ($id) => 'IGGRN-' . str_pad($id, 5, '0', STR_PAD_LEFT))
        ->implode(', ');

    return response()->json([
        'allocated'             => false,
        'latest_unallocated_id' => $latestUnallocated,
        'message'               => "There is pending rack allocation in: {$grnList}. Please complete rack allocation before adding any product to PO.",
    ]);
}




public function details($vendorId)
{
     $vendor = Vendor::with('paymentTerm')->findOrFail($vendorId);

    return response()->json([
        'location' => $vendor->location,
        'pincode'  => $vendor->pincode,
        'gst_no'  => $vendor->gst_number,
        'verified_status' => $vendor->paymentTerm->verified_status ?? 'unverified',
    ]);
}

public function products($vendorId)
{
    $products = VendorPriceList::with(['product' => function ($query) {
        $query->where('status', 'active');
    }])
    ->where('vendor_id', $vendorId)
    ->whereHas('product', function ($query) {
        $query->where('status', 'active');
    })
    ->get()
    ->map(function ($row) {

        $product = $row->product;

        $stock = ProductStock::where('product_id', $product->id)
                    ->value('total_stock') ?? 0;

        return [
            'product_id'   => $product->id,
            'product_name' => $product->product_name,
            'stock'        => $stock,
            'cost_per_item'=> $product->cost_per_item ?? 0,
            'carton_size'  => $product->carton_size ?? 0,
            'vendor_price' => $row->vendor_price ?? 0,
            'gst_percent'  => ($product->cgst ?? 0) + ($product->sgst ?? 0),
        ];
    })
    ->values();

    return response()->json($products);
}
public function creditEligibility($vendorId)
{
    $term = VendorPaymentTerm::where('vendor_id', $vendorId)
        ->where('credit_status', 'active')
        ->where('verified_status', 'verified')
        ->first();

    if (!$term) {
        return response()->json([
            'eligible' => false
        ]);
    }

    return response()->json([
        'eligible'     => true,
        'credit_limit'=> (float) $term->credit_limit
    ]);
}

public function place_purchaseorder(Request $request)
{
    // $data = $request->all();
    // dd($data);


     $request->validate([
        'vendor_id'             => 'required|exists:vendors,id',
        'purchase_order_number' => 'required|string|unique:purchase_order_details,purchase_order_number',
        'po_date'               => 'required|date',
        'delivery_date'         => 'required|date',
        'payment_term'          => 'required|string',
        'items'                 => 'required|array|min:1',
        'items.*.product_id'    => 'required|exists:products,id',
        'items.*.quantity'      => 'required|numeric|min:1',
        'items.*.vendor_price'  => 'required|numeric|min:0',
    ]);

    try {

        DB::beginTransaction();

        /* =========================
           SAVE PURCHASE ORDER
        ========================== */
        $purchaseOrder = PurchaseOrderDetail::create([
            'vendor_id'             => $request->vendor_id,
            'purchase_order_number' => $request->purchase_order_number,
            'reference'             => $request->reference,
            'po_date'               => $request->po_date,
            'delivery_date'         => $request->delivery_date,
            'location'              => $request->location,
            'pincode'               => $request->pincode,
            'subtotal_basic'        => $request->subtotal_basic,
            'product_discount'      => $request->product_discount,
            'tax_total'             => $request->tax_total,
            'delivery_charges'      => $request->delivery_charges,
            'grand_total'           => $request->grand_total,
            'payment_method'          => $request->payment_term,
            'payment_status'          => 'unpaid',
            'save_type'             => $request->save_type ?? 'draft',
            'status'                => $request->save_type === 'sent'
                                        ? 'sent'
                                        : 'draft',
        ]);

        /* =========================
           SAVE PO ITEMS
        ========================== */
        foreach ($request->items as $item) {

            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id'        => $item['product_id'],
                'quantity'          => $item['quantity'],
                'free_quantity'     => $item['free_quantity'] ?? 0,
                'mrp'               => $item['mrp'] ?? 0,
                'vendor_price'      => $item['vendor_price'],
                'amount'            => $item['amount'],
                'row_tax'            => $item['row_tax'],
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Purchase Order saved successfully',
            'redirect_url' => route('admin.purchase-orders.index')
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        \Log::error('Purchase Order Save Failed', [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to save Purchase Order'
        ], 500);

 }
}

public function show_purchaseorder($id)
{
    $purchaseOrder = PurchaseOrderDetail::with([
        'vendor',
        'items.product'
    ])->findOrFail($id);

    // return view('admin.purchase_orders.index', compact('purchaseOrders'));
    return view('admin.purchase_orders.show', compact('purchaseOrder'));
}

public function edit_purchaseorder($id)
{
    $purchaseOrder = PurchaseOrderDetail::with([
        'items.product',
        'vendor'
    ])->findOrFail($id);

    $vendors = Vendor::orderBy('name')->get();

    return view('admin.purchase_orders.edit', compact('purchaseOrder', 'vendors'));
}

public function update_purchaseorder(Request $request, $id)
{
    //  $data = $request->all();
    // dd($data);

    $request->validate([
        'vendor_id'            => 'required|exists:vendors,id',
        'po_date'              => 'required|date',
        'delivery_date'        => 'required|date',
        'payment_term'         => 'required|string',
        'items'                => 'required|array|min:1',
        'items.*.product_id'   => 'required|exists:products,id',
        'items.*.quantity'     => 'required|numeric|min:1',
        'items.*.vendor_price' => 'required|numeric|min:0',
    ]);

    try {

        DB::beginTransaction();

        $purchaseOrder = PurchaseOrderDetail::findOrFail($id);

        /* =====================
           UPDATE PO HEADER
        ====================== */
        $purchaseOrder->update([
            'vendor_id'        => $request->vendor_id,
            'reference'        => $request->reference,
            'po_date'          => $request->po_date,
            'delivery_date'    => $request->delivery_date,
            'location'         => $request->location,
            'pincode'          => $request->pincode,
            'subtotal_basic'   => $request->subtotal_basic,
            'product_discount' => $request->product_discount,
            'tax_total'        => $request->tax_total,
            'delivery_charges' => $request->delivery_charges,
            'grand_total'      => $request->grand_total,
            'payment_method'   => $request->payment_term,
            'payment_status'   => 'unpaid',
            'save_type'        => $request->save_type ?? 'draft',
            'status'           => $request->save_type === 'sent'
                                        ? 'sent'
                                        : 'draft',
        ]);

        /* =====================
           REPLACE PO ITEMS
        ====================== */
        $purchaseOrder->items()->delete();

        foreach ($request->items as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id'        => $item['product_id'],
                'quantity'          => $item['quantity'],
                'free_quantity'          => $item['free_quantity'],
                'vendor_price'      => $item['vendor_price'],
                'mrp'               => $item['mrp'],
                'amount'            => $item['amount'],
                'row_tax'            => $item['row_tax'],
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Purchase Order updated successfully',
            'redirect_url' => route('admin.purchase-orders.index')
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        \Log::error('PO Update Failed', ['error' => $e->getMessage()]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to update Purchase Order'
        ], 500);
    }
}

public function destroy_purchaseorder($id)
{
    try {

        DB::beginTransaction();
        $purchaseOrder = PurchaseOrderDetail::with('items')->findOrFail($id);
        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();
        DB::commit();
        return redirect()
            ->route('admin.purchase-orders.index')
            ->with('success', 'Purchase Order deleted successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Purchase Order Delete Failed', [
            'po_id' => $id,
            'error' => $e->getMessage()
        ]);
        return redirect()
            ->back()
            ->with('error', 'Failed to delete Purchase Order.');
    }
}


public function forStockReceiving(PurchaseOrderDetail $purchaseOrder)
{
    $purchaseOrder->load([
        'vendor:id,name',
        'items.product:id,product_name,brands,unit'
    ]);

    return response()->json([
        'po_number'   => $purchaseOrder->purchase_order_number,
        'po_date'     => $purchaseOrder->po_date,
        'vendor_name' => $purchaseOrder->vendor->name,
        'vendor_id' => $purchaseOrder->vendor_id,

        'grand_total'   => $purchaseOrder->grand_total ?? 0,
        'subtotal_basic'   => $purchaseOrder->subtotal_basic ?? 0,
        'product_discount' => $purchaseOrder->product_discount ?? 0,
        'delivery_charges' => $purchaseOrder->delivery_charges ?? 0,
        'gst'              => $purchaseOrder->tax_total ?? 0,

        'items' => $purchaseOrder->items->map(function ($item) {
            return [
                'po_item_id'    => $item->id,
                'product_id'    => $item->product_id,
                'product_name'  => $item->product->product_name,
                'brand'         => $item->product->brands ?? '-',
                'uom'           => $item->product->unit ?? '-',
                'purchase_rate' => $item->vendor_price,
                'po_qty'        => $item->quantity,
                'free_qty'        => $item->free_quantity?? 0,
                'mrp'           =>  $item->mrp?? 0,
                'po_cgst_sgst'  => $item->row_tax,
            ];
        })
    ]);
}

public function approval_purchaseorder()
{
    $purchaseOrders = PurchaseOrderDetail::with('vendor')
        ->where(function ($q) {
            $q->where('status', 'sent')       
              ->orWhere('status', 'approved') 
              ->orWhere('status', 'received') 
              ->orWhere(function ($q2) {
                  $q2->where('status', 'draft')
                     ->whereNotNull('rejection_reason');
              });
        })
        ->latest()
        ->get();

    $overallBasicTotal = PurchaseOrderDetail::where(function ($q) {
    $q->where('status', 'sent')       
      ->orWhere('status', 'approved') 
      ->orWhere('status', 'received') 
      ->orWhere(function ($q2) {
          $q2->where('status', 'draft')
             ->whereNotNull('rejection_reason');
      });
})->sum('subtotal_basic');

    return view('admin.purchase_orders.approval', compact('purchaseOrders','overallBasicTotal'));
}


       public function details_purchaseorder($id)
{
    $po = PurchaseOrderDetail::with([
            'vendor',
            'items.product'
        ])
        ->findOrFail($id);

    if (
        !(
            $po->status === 'sent' ||
            $po->status === 'approved' ||
            $po->status === 'received' ||
            ($po->status === 'draft' && !empty($po->rejection_reason))
        )
    ) {
        abort(403, 'Purchase Order not available for review');
    }

    return view('admin.purchase_orders.approval_review', compact('po'));
}

      public function submitReview_purchaseorder(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:approved,rejected',
        'reason' => 'required_if:status,rejected',
    ]);

    $po = PurchaseOrderDetail::findOrFail($id);

    if ($po->status !== 'sent') {
        abort(403, 'Purchase Order already reviewed.');
    }

    if ($request->status === 'rejected') {

        $po->update([
            'status'           => 'draft',
            'rejection_reason' => $request->reason,
            'reviewed_by'      => auth()->id(),
            'reviewed_at'      => now(),
        ]);

    } else {

        $po->update([
            'status'           => 'approved',
            'rejection_reason' => null,
            'reviewed_by'      => auth()->id(),
            'reviewed_at'      => now(),
        ]);
    }

    return redirect()
        ->route('admin.purchase-orders.approval')
        ->with('success', 'Purchase Order reviewed successfully.');
}

    public function downloadPdf($id)
    {
        $po = PurchaseOrderDetail::with([
            'vendor',
            'items.product'
        ])->findOrFail($id);
    
    
        $pdf = Pdf::loadView('admin.purchase_orders.pdf', compact('po'))
            ->setPaper('A4', 'portrait');
    
        return $pdf->stream("purchase_order_{$po->purchase_order_number}.pdf");
    }
    
     public function addDraftItem(Request $request)
{
    $product_id = $request->product_id;
    $vendor_id  = $request->vendor_id;
    $qty        = $request->quantity;

    /*
    |------------------------------------------
    | Check existing Draft PO for supplier
    |------------------------------------------
    */

    $draftPo = PurchaseOrderDetail::where('vendor_id',$vendor_id)
                ->where('save_type','draft')
                ->first();

    $message = '';

    /*
    |------------------------------------------
    | Create Draft PO if not exists
    |------------------------------------------
    */

    if(!$draftPo){

        /*
        |------------------------------------------
        | Generate PO Number
        |------------------------------------------
        */

        $lastPo = PurchaseOrderDetail::latest('id')->first();

        if ($lastPo) {

            $lastNumber = (int) str_replace('IGPO-', '', $lastPo->purchase_order_number);
            $nextNumber = $lastNumber + 1;

        } else {

            $nextNumber = 1;

        }

        $purchaseOrderNumber = 'IGPO-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        /*
        |------------------------------------------
        | Create Draft PO
        |------------------------------------------
        */

        $draftPo = PurchaseOrderDetail::create([

            'purchase_order_number' => $purchaseOrderNumber,
            'vendor_id'             => $vendor_id,
            'location'              => 'Mumbai',
            'pincode'               => '400074',

            'po_date'               => now(),
            'delivery_date'         => now(),

            'subtotal_basic'        => 0,
            'product_discount'      => 0,
            'tax_total'             => 0,
            'delivery_charges'      => 0,
            'grand_total'           => 0,

            'payment_method'        => 'pay_on_delivery',
            'payment_status'        => 'unpaid',

            'save_type'             => 'draft',
            'status'                => 'draft',

            'created_at'            => now(),
            'updated_at'            => now()

        ]);

        $message = "New Draft PO {$purchaseOrderNumber} created and item added.";

    }else{

        $message = "Item added to existing Draft PO {$draftPo->purchase_order_number}.";

    }

    /*
    |------------------------------------------
    | Check if item already exists in PO
    |------------------------------------------
    */

    $item = PurchaseOrderItem::where('purchase_order_id',$draftPo->id)
            ->where('product_id',$product_id)
            ->first();

    if($item){

        $item->quantity += $qty;
        $item->save();

    }else{

        PurchaseOrderItem::create([

            'purchase_order_id' => $draftPo->id,
            'product_id'        => $product_id,
            'quantity'          => $qty,

            'cost_per_item'     => 0,
            'vendor_price'      => 0,
            'profit_margin'     => 0,

            'row_tax'           => 0,
            'received_qty'      => 0,
            'amount'            => 0

        ]);

    }

    return response()->json([
        'message' => $message
    ]);
}


}
