<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickList;
use App\Models\ProductStock;
use App\Models\Payment;
use App\Models\Product;
use App\Models\RackStock;
use App\Models\StockMovement;
use App\Models\StockDisposal;
use App\Models\StockReturnRequest;
use App\Models\StockReturnRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseStockReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = StockReturnRequest::with(['order', 'requestedBy', 'items.product'])
            ->orderByDesc('created_at');

        $status = $request->input('status', 'pending');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->get();

        return view('admin.stock_return.warehouse_review', compact('requests', 'status'));
    }

    public function show($id)
    {
        $returnRequest = StockReturnRequest::with(['order', 'requestedBy', 'approvedBy', 'items.product'])
            ->findOrFail($id);

        return view('admin.stock_return.show', compact('returnRequest'));
    }

    /**
     * Approve — branches per item_type:
     *  - 'return'   : add stock back to (possibly new) rack location,
     *                 reduce the original OrderItem qty/price.
     *  - 'addition' : stock was ALREADY deducted + PickList created at
     *                 store() time. Approval here just formalizes it —
     *                 creates (or merges into) an OrderItem so the
     *                 invoice actually reflects the added product.
     * Order totals are recalculated once at the end either way.
     */
   


   
   
   
   public function approve(Request $request, $id)
{
    $validated = $request->validate([
        'items'                     => 'required|array',
        'items.*.item_id'           => 'required|exists:stock_return_request_items,id',
        'items.*.return_stock_type' => 'nullable|in:physical_in,no_physical_in,damaged',
        'items.*.new_rack_no'       => 'nullable|string',
        'items.*.new_level_no'      => 'nullable|string',
        'items.*.new_slot_no'       => 'nullable|string',
        'reason'                    => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {

        // ============================================================
        // RETURN REQUEST
        // ============================================================

        $returnRequest = StockReturnRequest::with('items')
            ->lockForUpdate()
            ->findOrFail($id);

        if ($returnRequest->status !== 'pending') {
            throw new \Exception('This request has already been processed.');
        }


        // ============================================================
        // ORDER
        // ============================================================

        $order = Order::with('items.product')
            ->findOrFail($returnRequest->order_id);


        // ============================================================
        // PAYMENT
        // ============================================================

        $payment = Payment::where(
            'order_id',
            $returnRequest->order_id
        )->first();


        // ============================================================
        // ADMIN
        // ============================================================

        $adminId = auth('admin')->id();

        $adminName = auth('admin')->user()->name ?? 'Warehouse';


        // ============================================================
        // PROCESS ITEMS
        // ============================================================

        foreach ($validated['items'] as $itemInput) {

            $returnItem = StockReturnRequestItem::with('product')
                ->where('stock_return_request_id', $returnRequest->id)
                ->where('id', $itemInput['item_id'])
                ->lockForUpdate()
                ->firstOrFail();


            // ========================================================
            // 1. ADDITION / NEW PRODUCT
            // ========================================================
            //
            // Stock already deducted in store().
            //
            // Here:
            // - Add/update OrderItem
            // - Update CustomerPrice
            // - StockMovement OUT
            //
            // ========================================================

            if ($returnItem->item_type === 'addition') {

                $product = Product::findOrFail(
                    $returnItem->product_id
                );

                $addQty = (int) ($returnItem->return_qty ?? 0);

                if ($addQty <= 0) {
                    throw new \Exception(
                        'Addition quantity must be greater than zero for '
                        . ($product->product_name ?? 'product')
                        . '.'
                    );
                }


                $newPrice = (float) (
                    $returnItem->customer_price ?? 0
                );


                // ----------------------------------------------------
                // Find existing OrderItem
                // ----------------------------------------------------

                $orderItem = OrderItem::where(
                        'order_id',
                        $order->id
                    )
                    ->where(
                        'product_id',
                        $returnItem->product_id
                    )
                    ->lockForUpdate()
                    ->first();


                // ----------------------------------------------------
                // Existing product in invoice
                // ----------------------------------------------------

                if ($orderItem) {

                    $oldPrice = (float) (
                        $orderItem->offer_price ?? 0
                    );


                    $orderItem->quantity =
                        $orderItem->quantity + $addQty;


                    $orderItem->offer_price =
                        $newPrice;


                    $orderItem->price =
                        $orderItem->quantity * $newPrice;


                    $orderItem->save();


                    // ------------------------------------------------
                    // Customer price update
                    // ------------------------------------------------

                    if ((float) $oldPrice !== (float) $newPrice) {

                        \App\Models\CustomerPrice::updateOrCreate(
                            [
                                'outlet_id' =>
                                    $order->outlet_id,

                                'product_id' =>
                                    $returnItem->product_id,
                            ],
                            [
                                'customer_id' =>
                                    $order->user_id,

                                'product_price' =>
                                    $newPrice,
                            ]
                        );
                    }

                } else {

                    // ------------------------------------------------
                    // Create new OrderItem
                    // ------------------------------------------------

                    OrderItem::create([
                        'order_id' =>
                            $order->id,

                        'product_id' =>
                            $returnItem->product_id,

                        'quantity' =>
                            $addQty,

                        'offer_price' =>
                            $newPrice,

                        'price' =>
                            $addQty * $newPrice,

                        'mrp' =>
                            $product->product_mrp ?? 0,
                    ]);


                    // ------------------------------------------------
                    // Customer price
                    // ------------------------------------------------

                    \App\Models\CustomerPrice::updateOrCreate(
                        [
                            'outlet_id' =>
                                $order->outlet_id,

                            'product_id' =>
                                $returnItem->product_id,
                        ],
                        [
                            'customer_id' =>
                                $order->user_id,

                            'product_price' =>
                                $newPrice,
                        ]
                    );
                }


                // ====================================================
                // STOCK LEDGER - NEW PRODUCT OUT
                // ====================================================

                StockMovement::create([
                    'product_id' =>
                        $returnItem->product_id,

                    'reference_type' =>
                        'Revised Invoice',

                    'reference_id' =>
                        $order->id,

                    'movement_type' =>
                        'OUT',

                    'quantity' =>
                        $addQty,

                    'batch_no' =>
                        $returnItem->batch_no,

                    'remarks' =>
                        'Additional product issued through Revised Invoice #'
                        . $returnRequest->id
                        . ' — approved by '
                        . $adminName,
                ]);


                continue;
            }


            // ========================================================
            // 2. EXISTING ORDER ITEM
            // ========================================================

            $orderItem = OrderItem::where(
                    'id',
                    $returnItem->order_item_id
                )
                ->where(
                    'order_id',
                    $order->id
                )
                ->lockForUpdate()
                ->first();


            if (!$orderItem) {

                throw new \Exception(
                    'Order item not found for '
                    . (
                        $returnItem->product->product_name
                        ?? 'product'
                    )
                    . '.'
                );
            }


            // ========================================================
            // VALUES
            // ========================================================

            $returnQty = (int) (
                $returnItem->return_qty ?? 0
            );


            $oldPrice = (float) (
                $orderItem->offer_price ?? 0
            );


            $newPrice = $returnItem->customer_price !== null
                ? (float) $returnItem->customer_price
                : $oldPrice;


            // ========================================================
            // VALIDATE QTY
            // ========================================================

            if ($returnQty < 0) {

                throw new \Exception(
                    'Return quantity cannot be negative for '
                    . (
                        $returnItem->product->product_name
                        ?? 'product'
                    )
                    . '.'
                );
            }


            if ($returnQty > $orderItem->quantity) {

                throw new \Exception(
                    'Return quantity cannot exceed current invoice quantity for '
                    . (
                        $returnItem->product->product_name
                        ?? 'product'
                    )
                    . '.'
                );
            }


            // ========================================================
            // 3. PRICE CHANGE
            // ========================================================
            //
            // Can happen:
            //
            // - Price only
            // - Price + Qty return
            //
            // No StockMovement for price-only change.
            //
            // ========================================================

            $priceChanged =
                (float) $oldPrice !== (float) $newPrice;


            if ($priceChanged) {

                $orderItem->offer_price =
                    $newPrice;


                // ----------------------------------------------------
                // CustomerPrice
                // ----------------------------------------------------

                \App\Models\CustomerPrice::updateOrCreate(
                    [
                        'outlet_id' =>
                            $order->outlet_id,

                        'product_id' =>
                            $returnItem->product_id,
                    ],
                    [
                        'customer_id' =>
                            $order->user_id,

                        'product_price' =>
                            $newPrice,
                    ]
                );
            }


            // ========================================================
            // 4. QUANTITY RETURN
            // ========================================================

            if ($returnQty > 0) {

                $stockType =
                    $itemInput['return_stock_type'] ?? null;


                if (!$stockType) {

                    throw new \Exception(
                        'Please select Physical In, No Physical In or Damaged for '
                        . (
                            $returnItem->product->product_name
                            ?? 'product'
                        )
                        . '.'
                    );
                }


                // Save selected handling type
                $returnItem->return_stock_type =
                    $stockType;


                // ====================================================
                // OPTION A: PHYSICAL IN
                // ====================================================
                //
                // Actual stock physically received.
                //
                // RackStock       +
                // ProductStock    +
                //
                // Movement:
                // IN / Revised Invoice
                //
                // ====================================================

                if ($stockType === 'physical_in') {

                    $newRack =
                        $itemInput['new_rack_no'] ?? null;

                    $newLevel =
                        $itemInput['new_level_no'] ?? null;

                    $newSlot =
                        $itemInput['new_slot_no'] ?? null;


                    // ------------------------------------------------
                    // Location required
                    // ------------------------------------------------

                    if (
                        empty($newRack) ||
                        empty($newLevel) ||
                        empty($newSlot)
                    ) {

                        throw new \Exception(
                            'Rack, Level and Slot are required for Physical In: '
                            . (
                                $returnItem->product->product_name
                                ?? 'product'
                            )
                            . '.'
                        );
                    }


                    // ------------------------------------------------
                    // Save new location
                    // ------------------------------------------------

                    $returnItem->new_rack_no =
                        $newRack;

                    $returnItem->new_level_no =
                        $newLevel;

                    $returnItem->new_slot_no =
                        $newSlot;


                    // =================================================
                    // RACK STOCK +
                    // =================================================

                    $rack = RackStock::firstOrNew([
                        'product_id' =>
                            $returnItem->product_id,

                        'batch_no' =>
                            $returnItem->batch_no,

                        'expiry_date' =>
                            $returnItem->expiry_date,

                        'rack_no' =>
                            $newRack,

                        'level_no' =>
                            $newLevel,

                        'slot_no' =>
                            $newSlot,
                    ]);


                    $rack->quantity =
                        ($rack->quantity ?? 0)
                        +
                        $returnQty;


                    $rack->save();


                    // =================================================
                    // PRODUCT STOCK +
                    // =================================================

                    $productStock = ProductStock::where(
                            'product_id',
                            $returnItem->product_id
                        )
                        ->lockForUpdate()
                        ->first();


                    if ($productStock) {

                        $productStock->total_stock =
                            $productStock->total_stock
                            +
                            $returnQty;


                        $productStock->save();

                    } else {

                        ProductStock::create([
                            'product_id' =>
                                $returnItem->product_id,

                            'total_stock' =>
                                $returnQty,
                        ]);
                    }


                    // =================================================
                    // STOCK MOVEMENT - IN
                    // =================================================

                    StockMovement::create([
                        'product_id' =>
                            $returnItem->product_id,

                        'reference_type' =>
                            'Revised Invoice',

                        'reference_id' =>
                            $order->id,

                        'movement_type' =>
                            'IN',

                        'quantity' =>
                            $returnQty,

                        'batch_no' =>
                            $returnItem->batch_no,

                        'remarks' =>
                            'Physical stock returned through Revised Invoice #'
                            . $returnRequest->id
                            . '. Location: '
                            . $newRack
                            . '/'
                            . $newLevel
                            . '/'
                            . $newSlot
                            . ' — approved by '
                            . $adminName,
                    ]);
                }


                // ====================================================
                // OPTION B: NO PHYSICAL IN
                // ====================================================
                //
                // Stock ledger = IN
                //
                // But no actual physical stock received.
                //
                // Therefore:
                //
                // RackStock       NO CHANGE
                // ProductStock    NO CHANGE
                //
                // ====================================================

                elseif ($stockType === 'no_physical_in') {

                    // ------------------------------------------------
                    // No physical location
                    // ------------------------------------------------

                    $returnItem->new_rack_no =
                        null;

                    $returnItem->new_level_no =
                        null;

                    $returnItem->new_slot_no =
                        null;


                    // =================================================
                    // STOCK MOVEMENT - IN
                    // =================================================
                }


                // ====================================================
                // OPTION C: DAMAGED
                // ====================================================
                //
                // Do NOT add to:
                //
                // RackStock
                // ProductStock
                //
                // Instead:
                //
                // StockDisposal
                // +
                // StockMovement RETURN / DAMAGED
                //
                // ====================================================

                elseif ($stockType === 'damaged') {

                    // ------------------------------------------------
                    // No usable location
                    // ------------------------------------------------

                    $returnItem->new_rack_no =
                        null;

                    $returnItem->new_level_no =
                        null;

                    $returnItem->new_slot_no =
                        null;


                    // ------------------------------------------------
                    // Cost
                    // ------------------------------------------------

                    $unitCost = (float) (
                        $returnItem->purchase_rate ?? 0
                    );


                    $totalValue =
                        $unitCost * $returnQty;


                    // =================================================
                    // STOCK DISPOSAL
                    // =================================================

                    StockDisposal::create([
                        'product_id' =>
                            $returnItem->product_id,

                        'stock_receiving_id' =>
                            null,

                        'batch_no' =>
                            $returnItem->batch_no,

                        'expiry_date' =>
                            $returnItem->expiry_date,

                        'quantity' =>
                            $returnQty,

                        'unit_cost' =>
                            $unitCost,

                        'total_value' =>
                            $totalValue,

                        'stock_type' =>
                            'Revised Invoice Return',

                        'reason' =>
                            'Damaged return from Revised Invoice #'
                            . $returnRequest->id
                            . ' / Order '
                            . (
                                $order->order_id
                                ?? $order->id
                            ),

                        'disposed_by' =>
                            $adminId,
                    ]);


                    // =================================================
                    // STOCK MOVEMENT - DAMAGED
                    // =================================================
                    //
                    // movement_type  = RETURN
                    // reference_type = DAMAGED
                    //
                    // =================================================

                    StockMovement::create([
                        'product_id' =>
                            $returnItem->product_id,

                        'reference_type' =>
                            'DAMAGED',

                        'reference_id' =>
                            $order->id,

                        'movement_type' =>
                            'RETURN',

                        'quantity' =>
                            $returnQty,

                        'batch_no' =>
                            $returnItem->batch_no,

                        'remarks' =>
                            'Damaged stock returned through Revised Invoice #'
                            . $returnRequest->id
                            . '. Qty: '
                            . $returnQty
                            . ', Unit Cost: ₹'
                            . number_format($unitCost, 2)
                            . ', Total Value: ₹'
                            . number_format($totalValue, 2)
                            . '. Added to Stock Disposal'
                            . ' — approved by '
                            . $adminName,
                    ]);
                }


                // ====================================================
                // SAVE RETURN REQUEST ITEM
                // ====================================================

                $returnItem->save();


                // ====================================================
                // REDUCE INVOICE QTY
                // ====================================================
                //
                // Physical In      → reduce
                // No Physical In   → reduce
                // Damaged          → reduce
                //
                // ====================================================

                $orderItem->quantity = max(
                    0,
                    $orderItem->quantity - $returnQty
                );
            }


            // ========================================================
            // UPDATE ORDER ITEM PRICE
            // ========================================================

            $orderItem->price =
                $orderItem->quantity
                *
                $orderItem->offer_price;


            $orderItem->save();
        }


        // ============================================================
        // RELOAD ORDER ITEMS
        // ============================================================

        $order->load('items.product');


        // ============================================================
        // RECALCULATE ORDER TOTAL
        // ============================================================

        $subtotal = 0;
        $productDiscount = 0;
        $cgstSgstTotal = 0;
        $totalAmount = 0;


        foreach ($order->items as $orderItem) {

            $product = $orderItem->product;


            if (!$product) {
                continue;
            }


            // --------------------------------------------------------
            // PRE TAX
            // --------------------------------------------------------

            $pretax =
                $orderItem->quantity
                *
                $orderItem->offer_price;


            $subtotal +=
                $pretax;


            // --------------------------------------------------------
            // DISCOUNT
            // --------------------------------------------------------

            $discount =
                (
                    $pretax
                    *
                    ($product->total_discount ?? 0)
                )
                /
                100;


            $productDiscount +=
                $discount;


            // --------------------------------------------------------
            // CGST
            // --------------------------------------------------------

            $cgstAmount =
                (
                    ($product->cgst ?? 0)
                    *
                    $pretax
                )
                /
                100;


            // --------------------------------------------------------
            // SGST
            // --------------------------------------------------------

            $sgstAmount =
                (
                    ($product->sgst ?? 0)
                    *
                    $pretax
                )
                /
                100;


            $cgstSgstTotal +=
                $cgstAmount
                +
                $sgstAmount;


            // --------------------------------------------------------
            // TOTAL
            // --------------------------------------------------------

            $totalAmount +=
                $pretax
                +
                $cgstAmount
                +
                $sgstAmount;
        }


        // ============================================================
        // EXTRA CHARGES
        // ============================================================

        $totalAmount +=
            ($order->delivery_charges ?? 0)
            +
            ($order->packing_charges ?? 0)
            +
            ($order->others_charges ?? 0);


        // ============================================================
        // UPDATE ORDER
        // ============================================================

        $order->subtotal =
            $subtotal;


        $order->product_discount =
            $productDiscount;


        $order->cgst_sgst =
            $cgstSgstTotal;


        $order->total_discount_value =
            $totalAmount;


        $order->save();


        // ============================================================
        // UPDATE PAYMENT
        // ============================================================

        if ($payment) {

            $payment->total_amount =
                $totalAmount;

            $payment->save();
        }


        // ============================================================
        // APPROVE REQUEST
        // ============================================================

        $returnRequest->update([
            'status' =>
                'approved',

            'reject_reason' =>
                $validated['reason'] ?? null,

            'approved_by' =>
                $adminId,

            'approved_at' =>
                now(),
        ]);


        // ============================================================
        // COMMIT
        // ============================================================

        DB::commit();


        return response()->json([
            'success' => true,
            'message' =>
                'Revised invoice approved. Invoice, stock, disposal and stock ledger updated successfully.',
        ]);

    } catch (\Exception $e) {

        DB::rollBack();


        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}

    /**
     * Reject — reason is COMPULSORY.
     *  - 'return'   items: nothing changes (matches original behaviour).
     *  - 'addition' items: MUST be reversed — stock was already deducted
     *    and a PENDING PickList created at store() time. On rejection we
     *    give the stock back and cancel those PickList rows, so nothing
     *    is left hanging for warehouse to pick.
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:5',
        ]);

        DB::beginTransaction();

        try {
            $returnRequest = StockReturnRequest::with('items')->findOrFail($id);

            if ($returnRequest->status !== 'pending') {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Already processed.'], 422);
            }

            foreach ($returnRequest->items as $returnItem) {

                if ($returnItem->item_type !== 'addition') {
                    continue;
                }

                // Reverse every PickList row created for this addition item
                $pickLists = PickList::where('stock_return_request_id', $returnRequest->id)
                    ->where('product_id', $returnItem->product_id)
                    ->where('status', 'PENDING')
                    ->get();

                foreach ($pickLists as $pickList) {

                    $rack = RackStock::where('product_id', $pickList->product_id)
                        ->where('batch_no', $pickList->batch_no)
                        ->where('rack_no', $pickList->rack_no)
                        ->where('level_no', $pickList->level_no)
                        ->where('slot_no', $pickList->slot_no)
                        ->lockForUpdate()
                        ->first();

                    if ($rack) {
                        $rack->quantity += $pickList->quantity;
                        $rack->save();
                    } else {
                        RackStock::create([
                            'product_id' => $pickList->product_id,
                            'batch_no'   => $pickList->batch_no,
                            'rack_no'    => $pickList->rack_no,
                            'level_no'   => $pickList->level_no,
                            'slot_no'    => $pickList->slot_no,
                            'quantity'   => $pickList->quantity,
                        ]);
                    }

                    $productStock = ProductStock::where('product_id', $pickList->product_id)
                        ->lockForUpdate()
                        ->first();

                    if ($productStock) {
                        $productStock->total_stock += $pickList->quantity;
                        $productStock->save();
                    }

                    $pickList->update(['status' => 'CANCELLED']);
                }
            }

            $returnRequest->update([
                'status'        => 'rejected',
                'reject_reason' => $validated['reason'],
                'approved_by'   => auth('admin')->id(),
                'approved_at'   => now(),
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Request rejected.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}