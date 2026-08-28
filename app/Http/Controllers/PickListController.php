<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PickList;
use App\Models\OriginalItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\RackStock;
use App\Models\OrderItem;
use App\Models\LogisticDispatchedRackBox;
use App\Models\ProductStock;
use App\Models\DeliveryManagement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PickListController extends Controller
{
 public function index()
{
    $orders = Order::with([
        'logistics',
        'latestDelivery',
        'outlet'
    ])
    ->where('status', '!=', 'draft')
    ->orderBy('created_at','desc')
    ->get();

    return view('admin.picklist.index', compact('orders'));
}

//comment on 28-03-26
// private function buildPickData($orderId)
// {
//     $order = Order::with(['items.product','originalItems'])->findOrFail($orderId);

//     $delivery = DeliveryManagement::where('order_id', $orderId)->first();

//     $productRequired = $order->originalItems
//         ->pluck('quantity','product_id')
//         ->toArray();

//     $pickData = [];

//     foreach ($order->items as $item) {

//         $requiredQty = $item->quantity;

//         $racks = RackStock::where('product_id', $item->product_id)
//             ->where('quantity','>',0)
//             ->orderByRaw("expiry_date IS NULL, expiry_date ASC")
//             ->orderBy('created_at','ASC')
//             ->get();

//         foreach ($racks as $rack) {

//             if ($requiredQty <= 0) break;

//             $pick = min($rack->quantity, $requiredQty);

//             $pickData[] = [
//                 'product_id' => $item->product_id,
//                 'product'    => $item->product->product_name,
//                 'rack_no'    => $rack->rack_no,
//                 'level_no'   => $rack->level_no,
//                 'slot_no'    => $rack->slot_no,
//                 'batch_no'   => $rack->batch_no,
//                 'expiry'     => $rack->expiry_date,
//                 'available'  => $rack->quantity,
//                 'pick_qty'   => $pick,
//                 'needed'     => $productRequired[$item->product_id] ?? 0
//             ];

//             $requiredQty -= $pick;
//         }
//     }

//     $logistics = LogisticDispatchedRackBox::where('order_id',$orderId)->first();

//     return compact('order','pickData','delivery','logistics');
// }

private function buildPickData($orderId)
{
    $order = Order::with(['items.product','originalItems'])->findOrFail($orderId);
    // dd($order);

    $delivery = DeliveryManagement::where('order_id', $orderId)->first();

    $productRequired = $order->originalItems
        ->pluck('quantity','product_id')
        ->toArray();

    $pickData = [];

    foreach ($order->items as $item) {

        $requiredQty = $item->quantity;

    //   $racks = RackStock::where('product_id', $item->product_id)
    // ->where('quantity', '>', 0)
    // ->where(function ($query) {
    //     $query->where('is_available_for_sale', true)
    //         ->orWhere(function ($q) {
    //             $q->where('is_available_for_sale', false)
    //               ->where('expiry_date', '>=', now()->addDays(60));
    //         });
    // })

   
    // ->orderByDesc('is_available_for_sale')

    
    // ->orderBy('expiry_date', 'ASC')

   
    // ->orderByRaw("
    //     CASE 
    //         WHEN rack_no LIKE 'B%' THEN 1
    //         WHEN rack_no LIKE 'C%' THEN 2
    //         WHEN rack_no LIKE 'A%' THEN 3
    //         WHEN rack_no LIKE 'D%' THEN 4
    //         ELSE 5
    //     END
    // ")

  
    // ->orderBy('created_at', 'ASC')

    // ->get();


$racks = RackStock::where('product_id', $item->product_id)
    ->where('quantity', '>', 0)

   
    ->whereDate('expiry_date', '>=', now())

    ->orderByDesc('is_available_for_sale')
    ->orderBy('expiry_date', 'ASC')
    ->orderByRaw("
        CASE 
            WHEN rack_no LIKE 'B%' THEN 1
            WHEN rack_no LIKE 'C%' THEN 2
            WHEN rack_no LIKE 'A%' THEN 3
            WHEN rack_no LIKE 'D%' THEN 4
            ELSE 5
        END
    ")
    ->orderBy('created_at', 'ASC')
    ->get();

        foreach ($racks as $rack) {

            if ($requiredQty <= 0) break;

            $pick = min($rack->quantity, $requiredQty);

            $pickData[] = [
                'product_id' => $item->product_id,
                'product'    => $item->product->product_name,
                'rack_no'    => $rack->rack_no,
                'level_no'   => $rack->level_no,
                'slot_no'    => $rack->slot_no,
                'batch_no'   => $rack->batch_no,
                'expiry'     => $rack->expiry_date,
                'available'  => $rack->quantity,
                'pick_qty'   => $pick,
                'needed'     => $productRequired[$item->product_id] ?? 0,
                'is_priority'=> $rack->is_available_for_sale ? 1 : 0
            ];

            $requiredQty -= $pick;
        }

        if ($requiredQty > 0) {
            $pickData[] = [
                'product_id' => $item->product_id,
                'product'    => $item->product->product_name,
                'rack_no'    => null,
                'level_no'   => null,
                'slot_no'    => null,
                'batch_no'   => null,
                'expiry'     => null,
                'available'  => 0,
                'pick_qty'   => 0,
                'needed'     => $requiredQty,
                'status'     => 'SHORTAGE',
                'is_priority'=> 0
            ];
        }
    }

    $logistics = LogisticDispatchedRackBox::where('order_id',$orderId)->first();

    return compact('order','pickData','delivery','logistics');
}

    public function view($orderId)
{
    return view(
        'admin.picklist.view',
        $this->buildPickData($orderId)
    );
}


public function edit($orderId)
{
    return view(
        'admin.picklist.edit',
        $this->buildPickData($orderId)
    );
}

    public function markPicked($id)
    {
        $pick = PickList::findOrFail($id);
        $pick->status = 'PICKED';
        $pick->save();

        return response()->json([
            'status' => true,
            'message' => 'Item marked as picked successfully'
        ]);
    }
// comment on 28-03-26
//   public function preview($orderId)
// {
//     $order = Order::with('items.product')->findOrFail($orderId);

//     $delivery = DeliveryManagement::where('order_id', $orderId)->first();

  
//     if ($delivery && in_array($delivery->delivery_status, ['in_progress', 'completed'])) {
//         abort(403, 'Pick list cannot be modified after order is accepted.');
//     }

//     $pickData = [];

//       $productRequired = [];
//     foreach ($order->items as $item) {
//         $productRequired[$item->product_id] = $item->quantity;
//     }

//     foreach ($order->items as $item) {
//         $requiredQty = $item->quantity;

//         $racks = RackStock::where('product_id', $item->product_id)
//             ->where('quantity', '>', 0)
//             ->orderByRaw("expiry_date IS NULL, expiry_date ASC")
//             ->orderBy('created_at', 'ASC')
//             ->get();

//         foreach ($racks as $rack) {
//             if ($requiredQty <= 0) break;

//             $pick = min($rack->quantity, $requiredQty);

//             $pickData[] = [
//                 'order_id'   => $orderId,
//                 'product_id' => $item->product_id,
//                 'product'    => $item->product->product_name,
//                 'rack_no'    => $rack->rack_no,
//                 'level_no'   => $rack->level_no,
//                 'slot_no'    => $rack->slot_no,
//                 'batch_no'   => $rack->batch_no,
//                 'expiry'     => $rack->expiry_date,
//                 'available'  => $rack->quantity,
//                 'pick_qty'   => $pick,
//                 'needed'     => $requiredQty 
//             ];

//             $requiredQty -= $pick;
//         }
//     }
    
//     $logistics = LogisticDispatchedRackBox::where('order_id', $orderId)->first();

//     return view('admin.picklist.preview', compact('order', 'pickData','logistics'));
// }


public function preview($orderId)
{
    $order = Order::with('items.product')->findOrFail($orderId);

    $delivery = DeliveryManagement::where('order_id', $orderId)->first();

    if ($delivery && in_array($delivery->delivery_status, ['in_progress', 'completed'])) {
        abort(403, 'Pick list cannot be modified after order is accepted.');
    }

    $pickData = [];

    $productRequired = OriginalItem::where('order_id', $orderId)
        ->pluck('quantity', 'product_id')
        ->toArray();

    foreach ($order->items as $item) {

        $requiredQty = $item->quantity;

       
        $today = Carbon::today();
        $max = Carbon::today()->addDays(60);
        
    //   $racks = RackStock::where('product_id', $item->product_id)
    // ->where('quantity', '>', 0)
    // ->where(function ($query) {
    //     $query->where('is_available_for_sale', true)
    //         ->orWhere(function ($q) {
    //             $q->where('is_available_for_sale', false)
    //               ->where('expiry_date', '>=', now()->addDays(60));
    //         });
    // })

   
    // ->orderByDesc('is_available_for_sale')

    
    // ->orderBy('expiry_date', 'ASC')

   
    // ->orderByRaw("
    //     CASE 
    //         WHEN rack_no LIKE 'B%' THEN 1
    //         WHEN rack_no LIKE 'C%' THEN 2
    //         WHEN rack_no LIKE 'A%' THEN 3
    //         WHEN rack_no LIKE 'D%' THEN 4
    //         ELSE 5
    //     END
    // ")

  
    // ->orderBy('created_at', 'ASC')

    // ->get();



$racks = RackStock::where('product_id', $item->product_id)
    ->where('quantity', '>', 0)

    ->whereDate('expiry_date', '>=', now())

    ->orderByDesc('is_available_for_sale')
    ->orderBy('expiry_date', 'ASC')
    ->orderByRaw("
        CASE 
            WHEN rack_no LIKE 'B%' THEN 1
            WHEN rack_no LIKE 'C%' THEN 2
            WHEN rack_no LIKE 'A%' THEN 3
            WHEN rack_no LIKE 'D%' THEN 4
            ELSE 5
        END
    ")
    ->orderBy('created_at', 'ASC')
    ->get();
       

        foreach ($racks as $rack) {

            if ($requiredQty <= 0) break;

            $pickQty = min($rack->quantity, $requiredQty);

            $pickData[] = [
                'order_id'   => $orderId,
                'product_id' => $item->product_id,
                'product'    => $item->product->product_name,
                'rack_no'    => $rack->rack_no,
                'level_no'   => $rack->level_no,
                'slot_no'    => $rack->slot_no,
                'batch_no'   => $rack->batch_no,
                'expiry'     => $rack->expiry_date,
                'available'  => $rack->quantity,
                'pick_qty'   => $pickQty,
                'needed'     => $productRequired[$item->product_id] ?? 0,
                'is_priority'=> $rack->is_available_for_sale ? 1 : 0
            ];

            $requiredQty -= $pickQty;
        }

      
        if ($requiredQty > 0) {
            $pickData[] = [
                'order_id'   => $orderId,
                'product_id' => $item->product_id,
                'product'    => $item->product->product_name,
                'rack_no'    => null,
                'level_no'   => null,
                'slot_no'    => null,
                'batch_no'   => null,
                'expiry'     => null,
                'available'  => 0,
                'pick_qty'   => 0,
                'needed'     => $requiredQty,
                'is_priority'=> 0,
                'status'     => 'SHORTAGE'
            ];
        }
    }

    $logistics = LogisticDispatchedRackBox::where('order_id', $orderId)->first();

    return view('admin.picklist.preview', compact('order', 'pickData', 'logistics'));
}


//  comment on 06-04-26   
//   public function storePreview(Request $request)
// {
//     $request->validate([
//         'order_id' => 'required',
//         'items'    => 'required|array|min:1',
//     ]);

//     DB::beginTransaction();

//     try {

//         // Delete old pick list if exists
//         PickList::where('order_id', $request->order_id)->delete();

//         $productTotals = []; // product_id => total picked qty

//         foreach ($request->items as $item) {

//             $pickQty = (float)$item['pick_qty'];
//             if ($pickQty <= 0) continue;

//             // Save Pick List Row
//             PickList::create([
//                 'order_id'    => $request->order_id,
//                 'product_id'  => $item['product_id'],
//                 'rack_no'     => $item['rack_no'],
//                 'level_no'    => $item['level_no'],
//                 'slot_no'     => $item['slot_no'],
//                 'batch_no'    => $item['batch_no'],
//                 'expiry_date' => $item['expiry'],
//                 'quantity'    => $pickQty,
//                 'status'      => 'PICKED'
//             ]);

//             // Sum per product
//             if (!isset($productTotals[$item['product_id']])) {
//                 $productTotals[$item['product_id']] = 0;
//             }
//             $productTotals[$item['product_id']] += $pickQty;
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | Update main OrderItem quantities from Pick List
//         |--------------------------------------------------------------------------
//         */
//         foreach ($productTotals as $productId => $totalQty) {
//             OrderItem::where('order_id', $request->order_id)
//                 ->where('product_id', $productId)
//                 ->update([
//                     'quantity' => $totalQty
//                 ]);
//         }
        
//         LogisticDispatchedRackBox::updateOrCreate(
//             ['order_id' => $request->order_id],
//             [
//                 'dispatched_rack' => $request->dispatched_rack,
//                 'number_of_boxes' => $request->number_of_boxes,
//             ]
//         );

//         DB::commit();

//         return response()->json([
//             'status'  => true,
//             'message' => 'Pick list saved. Order item quantities updated from pick list.'
//         ]);

//     } catch (\Exception $e) {

//         DB::rollBack();

//         return response()->json([
//             'status'  => false,
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }


   public function storePreview(Request $request)
{
    $request->validate([
        'order_id' => 'required',
        'items'    => 'required|array|min:1',
    ]);

    DB::beginTransaction();

    try {

        // Delete old pick list if exists
        PickList::where('order_id', $request->order_id)->delete();

        $productTotals = []; // product_id => total picked qty
        
         foreach ($request->items as $item) {
            $productTotals[$item['product_id']] = 0;
        }

        foreach ($request->items as $item) {

            $pickQty = (float)$item['pick_qty'];
            if ($pickQty <= 0) continue;

            // Save Pick List Row
            PickList::create([
                'order_id'    => $request->order_id,
                'product_id'  => $item['product_id'],
                'rack_no'     => $item['rack_no'],
                'level_no'    => $item['level_no'],
                'slot_no'     => $item['slot_no'],
                'batch_no'    => $item['batch_no'],
                'expiry_date' => $item['expiry'],
                'quantity'    => $pickQty,
                'status'      => 'PICKED'
            ]);

            // Sum per product
             $productTotals[$item['product_id']] += $pickQty;
        }

        /*
        |--------------------------------------------------------------------------
        | Update main OrderItem quantities from Pick List
        |--------------------------------------------------------------------------
        */
        foreach ($productTotals as $productId => $totalQty) {
            OrderItem::where('order_id', $request->order_id)
                ->where('product_id', $productId)
                ->update([
                    'quantity' => $totalQty
                ]);
        }
        
        LogisticDispatchedRackBox::updateOrCreate(
            ['order_id' => $request->order_id],
            [
                'dispatched_rack' => $request->dispatched_rack,
                'number_of_boxes' => $request->number_of_boxes,
            ]
        );

        DB::commit();

        return response()->json([
            'status'  => true,
            'message' => 'Pick list saved. Order item quantities updated from pick list.'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'status'  => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function updatePickList(Request $request)
{
    $request->validate([
        'order_id' => 'required',
        'items'    => 'required|array|min:1',
        'dispatched_rack' => 'required',
        'number_of_boxes' => 'required|numeric|min:1'
    ]);

    DB::transaction(function () use ($request) {

        /*
        -------------------------------------
        Delete old picks (reset)
        -------------------------------------
        */
        PickList::where('order_id', $request->order_id)->delete();

        $productTotals = [];

        /*
        -------------------------------------
        Save new pick rows
        -------------------------------------
        */
        foreach ($request->items as $item) {

            $pickQty = (float) $item['pick_qty'];
            if ($pickQty <= 0) continue;

            PickList::create([
                'order_id'    => $request->order_id,
                'product_id'  => $item['product_id'],
                'rack_no'     => $item['rack_no'],
                'level_no'    => $item['level_no'],
                'slot_no'     => $item['slot_no'],
                'batch_no'    => $item['batch_no'],
                'expiry_date' => $item['expiry'],
                'quantity'    => $pickQty,
                'status'      => 'PICKED'
            ]);

            $productTotals[$item['product_id']] =
                ($productTotals[$item['product_id']] ?? 0) + $pickQty;
        }

        /*
        -------------------------------------
        Update Order Items (actual picked qty)
        -------------------------------------
        */
        foreach ($productTotals as $productId => $qty) {
            OrderItem::where('order_id', $request->order_id)
                ->where('product_id', $productId)
                ->update(['quantity' => $qty]);
        }

        /*
        -------------------------------------
        Save Logistics
        -------------------------------------
        */
        LogisticDispatchedRackBox::updateOrCreate(
            ['order_id' => $request->order_id],
            [
                'dispatched_rack' => $request->dispatched_rack,
                'number_of_boxes' => $request->number_of_boxes,
            ]
        );
    });

    return response()->json([
        'status'  => true,
        'message' => 'Pick list updated successfully.'
    ]);
}



public function checkPickList($orderId)
{
    $pickLists = PickList::where('order_id', $orderId)->get();

    // 1. Pick list must exist
    if ($pickLists->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'Pick list is not created yet.'
        ]);
    }

    // 2. All pick list items must be PICKED
    if ($pickLists->where('status', '!=', 'PICKED')->count() > 0) {
        return response()->json([
            'status' => false,
            'message' => 'Some pick list items are not marked as PICKED.'
        ]);
    }

    // 3. Picked quantity must be >= order item quantity
    $orderItems = OrderItem::where('order_id', $orderId)->get();

    foreach ($orderItems as $item) {

        $pickedQty = $pickLists
            ->where('product_id', $item->product_id)
            ->sum('quantity');

        if ($item->quantity > $pickedQty) {
            return response()->json([
                'status' => false,
                'message' => "Picked quantity is less than order quantity for product: {$item->product->product_name}. 
                              Picked: {$pickedQty}, Order: {$item->quantity}"
            ]);
        }
    }

    return response()->json([
        'status' => true
    ]);
}

// public function printPreviewPdf(Request $request)
// {
//     $order = Order::findOrFail($request->order_id);

//     $pickData = [];

//     foreach ($request->items as $item) {
//         $pickData[] = [
//             'product'  => Product::find($item['product_id'])->product_name,
//             'rack_no'  => $item['rack_no'],
//             'level_no' => $item['level_no'],
//             'slot_no'  => $item['slot_no'],
//             'batch_no' => $item['batch_no'],
//             'expiry'   => $item['expiry'],
//             'pick_qty' => $item['pick_qty'],
//         ];
//     }

//     $pdf = Pdf::loadView('admin.picklist.pdf', compact('order', 'pickData'))
//         ->setPaper('A4', 'portrait');

//     return $pdf->stream("pick_list_order_{$order->id}.pdf");
// }



public function printPreviewPdf(Request $request)
{
    $order = Order::with('outlet')->findOrFail($request->order_id);
    // dd($order);


   
    $dispatch = LogisticDispatchedRackBox::where('order_id', $order->id)->first();

    $remark = $request->remark;

    $pickData = [];

    foreach ($request->items as $item) {

        $product = Product::find($item['product_id']);

        $stock = ProductStock::where('product_id', $item['product_id'])->value('total_stock');

        $pickData[] = [
            'product'       => $product?->product_name,
            'rack_no'       => $item['rack_no'],
            'level_no'      => $item['level_no'],
            'slot_no'       => $item['slot_no'],
            'batch_no'      => $item['batch_no'],
            'expiry'        => $item['expiry'],
            'pick_qty'      => $item['pick_qty'],
            'needed'        => $item['needed'],
            'stock_in_hand' => $stock ?? 0,
        ];
    }

    $pdf = Pdf::loadView('admin.picklist.pdf', compact(
        'order',
        'pickData',
        'dispatch',
        'remark'
    ))->setPaper('A4', 'portrait');

    return $pdf->stream("pick_list_order_{$order->id}.pdf");
}


}
