<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickList;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\RackStock;
use App\Models\StockReturnRequest;
use App\Models\StockReturnRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = StockReturnRequest::with(['order', 'requestedBy'])
            ->orderByDesc('created_at');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(20);

        return view('admin.stock_return.index', compact('requests'));
    }

    public function show($id)
    {
        $returnRequest = StockReturnRequest::with(['order', 'requestedBy', 'approvedBy', 'items.product'])
            ->findOrFail($id);

        return view('admin.stock_return.show', compact('returnRequest'));
    }

    /**
     * Edit — only allowed while the request is still 'pending'. Once
     * warehouse has approved/rejected it, editing no longer makes sense
     * since stock/invoice changes may have already been applied.
     */
    public function edit($id)
    {
        $returnRequest = StockReturnRequest::with(['order', 'items.product'])->findOrFail($id);

        if ($returnRequest->status !== 'pending') {
            return redirect()
                ->route('stock-return.show', $id)
                ->with('error', 'Only pending requests can be edited.');
        }

        // customer_id / outlet_id — needed to scope the product Select2
        // to only what's actually assigned to this customer via
        // customer_prices/accepted enquiries (same source as backend pricing).
        $customerId = $returnRequest->order->user_id;
        $outletId   = $returnRequest->order->outlet_id;

        return view('admin.stock_return.edit', compact('returnRequest', 'customerId', 'outletId'));
    }

    /**
     * AJAX: products this customer is actually assigned pricing for
     * (customer_prices + accepted enquiries), same source used for
     * backend price assignment elsewhere — powers the Select2 product
     * picker on the edit page.
     */
    public function getProductsByCustomer($customerId, $outletId)
    {
        $customerPriceIds = DB::table('customer_prices')
            ->where('customer_id', $customerId)
            ->where('outlet_id', $outletId)
            ->pluck('product_id')
            ->toArray();

        $enquiryIds = DB::table('enquiries')
            ->where('user_id', $customerId)
            ->where('status', 'accept')
            ->pluck('product_id')
            ->toArray();

        $productIds = array_unique(array_merge($customerPriceIds, $enquiryIds));

        if (empty($productIds)) {
            return response()->json([]);
        }

        $products = Product::where('products.status', 'active')
            ->whereIn('products.id', $productIds)
            ->leftJoin('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->select(
                'products.id',
                'products.product_name',
                'products.cost_per_item',
                'products.carton_size',
                'products.total_discount',
                'products.cgst',
                'products.sgst',
                'products.product_mrp',
                DB::raw('COALESCE(product_stocks.total_stock, 0) as stock')
            )
            ->orderBy('products.product_name')
            ->get();

        return response()->json($products);
    }

    public function update(Request $request, $id)
    {
        $returnRequest = StockReturnRequest::findOrFail($id);

        if ($returnRequest->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending requests can be edited.'], 422);
        }

        $validated = $request->validate([
            'items'                  => 'required|array|min:1',
            'items.*.item_id'        => 'required|exists:stock_return_request_items,id',
            'items.*.product_id'     => 'required|exists:products,id',
            'items.*.purchase_rate'  => 'required|numeric|min:0',
            'items.*.return_qty'     => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['items'] as $itemInput) {
                $item = StockReturnRequestItem::where('id', $itemInput['item_id'])
                    ->where('stock_return_request_id', $returnRequest->id)
                    ->firstOrFail();

                $item->update([
                    'product_id'    => $itemInput['product_id'],
                    'purchase_rate' => $itemInput['purchase_rate'],
                    'return_qty'    => $itemInput['return_qty'],
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Request updated successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        return view('admin.stock_return.create');
    }

    /**
     * AJAX: Select2 search — only orders that are NOT cancelled and NOT
     * pending delivery (matching your "not cancelled and pending" rule).
     */
    public function searchOrders(Request $request)
    {
        $term = $request->input('term');

        $orders = Order::join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
            ->whereNotIn('delivery_management.delivery_status', ['pending', 'cancelled'])
            ->where('orders.order_id', 'like', "%{$term}%")
            ->select('orders.id', 'orders.order_id')
            ->distinct()
            ->limit(20)
            ->get()
            ->map(fn ($o) => ['id' => $o->id, 'text' => $o->order_id]);

        return response()->json($orders);
    }

    /**
     * AJAX: fetch all items for the selected order — product, purchase
     * rate, qty, and current rack location (rack/level/slot + batch +
     * expiry), pulled from PickList + RackStock.
     */
    public function getOrderItems($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        $rows = [];

        foreach ($order->items as $item) {

            // Skip items with nothing left to return (already fully
            // returned in a previous approved request) — only show
            // items that still have remaining quantity > 0.
            if ($item->quantity <= 0) {
                continue;
            }

            $pickList = PickList::where('order_id', $orderId)
                ->where('product_id', $item->product_id)
                ->where('status', 'PICKED')
                ->first();

            $expiryDate = null;

            if ($pickList) {
                $rackStock = RackStock::where('product_id', $item->product_id)
                    ->where('batch_no', $pickList->batch_no)
                    ->where('rack_no', $pickList->rack_no)
                    ->where('level_no', $pickList->level_no)
                    ->where('slot_no', $pickList->slot_no)
                    ->first();

                $expiryDate = $rackStock->expiry_date ?? null;
            }

           $rows[] = [
                'order_item_id' => $item->id,
                'product_id'    => $item->product_id,
                'product_name'  => $item->product->product_name ?? 'N/A',
                'sale_rate'     => $item->offer_price ?? 0,
                'max_qty'       => $item->quantity,
                'rack_no'       => $pickList->rack_no ?? null,   // was '-'
                'level_no'      => $pickList->level_no ?? null,  // was '-'
                'slot_no'       => $pickList->slot_no ?? null,   // was '-'
                'batch_no'      => $pickList->batch_no ?? null,  // was '-'
                'expiry_date'   => $expiryDate ? Carbon::parse($expiryDate)->format('Y-m-d') : null,
            ];
        }

        return response()->json([
            'order_id'    => $order->order_id,
            'customer_id' => $order->user_id,
            'outlet_id'   => $order->outlet_id,
            'items'       => $rows,
        ]);
    }

    /**
     * Save the pending revise request. NOTHING on the original order,
     * stock, or rack locations changes here — purely a request record
     * awaiting warehouse approval/rejection.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id'                  => 'required|exists:orders,id',
            'items'                     => 'required|array|min:1',
            'items.*.item_type'         => 'required|in:return,addition',
            'items.*.order_item_id'     => 'required_if:items.*.item_type,return|nullable|exists:order_items,id',
            'items.*.product_id'        => 'required|exists:products,id',
            'items.*.purchase_rate'     => 'required|numeric|min:0',
            'items.*.customer_price'    => 'nullable|numeric|min:0', // only meaningful for 'addition' items
            'items.*.return_qty'        => 'required|integer|min:0', // qty being returned OR qty being added
            'items.*.rack_no'           => 'nullable|string',
            'items.*.level_no'          => 'nullable|string',
            'items.*.slot_no'           => 'nullable|string',
            'items.*.batch_no'          => 'nullable|string',
            'items.*.expiry_date'       => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $returnRequest = StockReturnRequest::create([
                'order_id'     => $validated['order_id'],
                'requested_by' => auth('admin')->id(),
                'status'       => 'pending',
            ]);

            foreach ($validated['items'] as $item) {
                StockReturnRequestItem::create([
                    'stock_return_request_id' => $returnRequest->id,
                    'item_type'               => $item['item_type'],
                    'order_item_id'           => $item['order_item_id'] ?? null,
                    'product_id'              => $item['product_id'],
                    'purchase_rate'           => $item['purchase_rate'],
                    'customer_price'          => $item['customer_price'] ?? null,
                    'return_qty' => $item['return_qty'] ?? 0,
                    'rack_no'                 => $item['rack_no'] ?? null,
                    'level_no'                => $item['level_no'] ?? null,
                    'slot_no'                 => $item['slot_no'] ?? null,
                    'batch_no'                => $item['batch_no'] ?? null,
                    'expiry_date'             => $item['expiry_date'] ?? null,
                ]);
            }

            // ===== MAJOR: create PickList entries for newly ADDED products
            // right away, so warehouse can start picking this additional
            // stock even before the request is formally approved. This
            // mirrors the "HANDLE INCREASE" branch from the order modify()
            // flow — deduct from RackStock, create a PENDING PickList row,
            // reduce ProductStock. =====
            foreach ($validated['items'] as $item) {

                if ($item['item_type'] !== 'addition') {
                    continue;
                }

                $needQty = $item['return_qty'];

                $racks = RackStock::where('product_id', $item['product_id'])
                    ->where('quantity', '>', 0)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                foreach ($racks as $rack) {

                    if ($needQty <= 0) break;

                    $deduct = min($rack->quantity, $needQty);

                    $rack->quantity -= $deduct;
                    $rack->save();

                    PickList::create([
                        'order_id'      => $validated['order_id'],
                        'product_id'    => $item['product_id'],
                        'quantity'      => $deduct,
                        'batch_no'      => $rack->batch_no,
                        'rack_no'       => $rack->rack_no,
                        'level_no'      => $rack->level_no,
                        'slot_no'       => $rack->slot_no,
                        'status'        => 'PENDING',
                        'is_revised'    => 1,
                        'revision_note' => 'Added via Revise Invoice #' . $returnRequest->id,
                    ]);

                    $productStock = ProductStock::where('product_id', $item['product_id'])->lockForUpdate()->first();
                    if ($productStock) {
                        $productStock->total_stock -= $deduct;
                        $productStock->save();
                    }

                    $needQty -= $deduct;
                }

                if ($needQty > 0) {
                    throw new \Exception("Not enough rack stock available for the newly added product.");
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Revise invoice request submitted for warehouse approval.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
