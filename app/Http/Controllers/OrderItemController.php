<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\DeliveryManagement;
use App\Models\OutstandingStatement;
use App\Models\KYCDocument;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderNotification;
use App\Services\SmsService;
use App\Models\Pincode;
use App\Models\Admin;
use App\Models\Payment;
use App\Models\PickList;
use App\Models\ProductStock;
use App\Models\RackStock;
use App\Models\OriginalItem;
use App\Models\StockMovement;
use App\Models\ZoneProcessing;
use Illuminate\Support\Facades\Log;
use App\Models\BackendSalesOrder;
use App\Notifications\NewEnqueryRequestCustomerNotification;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{


public function index(Request $request, $id)
{
    
     $order = Order::with(['deliveries'])->findOrFail($id);

    $nonPendingDelivery = $order->deliveries->first(function ($delivery) {
        return $delivery->delivery_status !== 'pending';
    });

    if ($nonPendingDelivery) {
        return redirect()->route('order.details')
            ->with('error', 'This order is already ' . ucfirst(str_replace('_', ' ', $nonPendingDelivery->delivery_status)) . ' and cannot be modified.');
    }
    
    $orderItems = OrderItem::with([
        'product',
        'order.user',
        'product.stock'
    ])->where('order_id', $id)->get();

    
    $originalItems = OriginalItem::where('order_id', $id)
        ->get()
        ->keyBy(function($item) {
            return $item->product_id; 
        });
        
    $delivery = DeliveryManagement::where('order_id', $id)
                    ->latest()
                    ->first();

    $deliveryStatus = $delivery->delivery_status ?? 'pending';   
    $isBackendOrder = BackendSalesOrder::where('order_id', $id)->exists();
    $orderSource = $isBackendOrder ? 'Backend' : 'Online';
    $redirectRoute = $isBackendOrder ? 'order.backend.details' : 'order.details';

    if ($orderItems->isEmpty()) {
        DeliveryManagement::where('order_id', $id)
            ->update(['delivery_status' => 'cancelled']);
    }

    if ($request->notification_id) {
        DB::table('order_notifications')
            ->where('id', $request->notification_id)
            ->update([
                'is_read' => 1,
                'updated_at' => now(),
            ]);
    }

    return view('admin.orderitem.index', compact('orderItems', 'originalItems', 'deliveryStatus', 'orderSource','redirectRoute'));
}

// public function index(Request $request, $id)
// {
//     $orderItems = OrderItem::with([
//         'product',
//         'order.user',
//         'product.stock' 
//     ])->where('order_id', $id)->get();

//     if ($orderItems->isEmpty()) {
//         DeliveryManagement::where('order_id', $id)
//             ->update(['delivery_status' => 'cancelled']);
//     }

//     if ($request->notification_id) {
//         DB::table('order_notifications')
//             ->where('id', $request->notification_id)
//             ->update([
//                 'is_read' => 1,
//                 'updated_at' => now(),
//             ]);
//     }

//     return view('admin.orderitem.index', compact('orderItems'));
// }


//     public function index(Request $request,$id)
//     {
//         $orderItems = OrderItem::with('product','order.user')->where('order_id', $id)->get();
//         if ($orderItems->isEmpty()) {
//             DeliveryManagement::where('order_id', $id)
//                 ->where('order_id', $id)
//                 ->update(['delivery_status' => 'cancelled']);
//         }
//         // dd($orderItems);

//              if ($request->notification_id) {
//         DB::table('order_notifications')
//         ->where('id', $request->notification_id)
//         ->update([
//             'is_read' => 1,
//             'updated_at' => now(),
//         ]);
// }

    //     return view('admin.orderitem.index', compact('orderItems'));
    // }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $orderItem = OrderItem::find($request->order_item_id);
        if ($orderItem) {
            $orderItem->quantity = $request->quantity;
            $orderItem->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    // public function invoice($id)
    // {

    //     $orderInvoice = OrderItem::with('product')->where('order_id', $id)->get();


    //     $pdf = PDF::loadView('admin.invoice.invoice_page1', compact('orderInvoice'));
    //   return $pdf->stream('invoice.pdf');
    // }

    public function invoice($id)
    {
        $orderInvoice1 = OrderItem::with('product')->where('order_id', $id)->get();
        $orderInvoice = OrderItem::where('order_id', $id)->get();
        $orderNoInvoice = OrderItem::where('order_id', $id)->where('in_invoice', 'no')->get();

        $outletIds = $orderInvoice->pluck('order.outlet_id')->unique()->toArray();

        $orders = Order::with('user')->where('id',$id)->get();

        $orderss = KYCDocument::where('user_id', $outletIds)
      ->get();


      $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        $latestOrderCreatedAt = $orders->first()->created_at;

    // Fetch the last payment excluding specific order_id and created before or at the latest order's creation date
    $lastpayment = OutstandingStatement::where('created_at', '<=', $latestOrderCreatedAt)
                                       ->whereNotIn('order_id', [$id])
                                       ->get(); 
// dd($lastpayment);
      $pdf = PDF::loadView('admin.invoice.invoice', compact('orderInvoice' ,'orderss', 'orders', 'orderNoInvoice', 'orderInvoice1', 'lastpayment'));
      return $pdf->stream('invoice.pdf');

    //    return view('admin.invoice.invoice', compact('orderInvoice' ,'orderss', 'orders', 'orderNoInvoice', 'orderInvoice1', 'lastpayment'));
    }


     public function deliverycharges($id)
    {
        $orderInvoice1 = OrderItem::with('product')->where('order_id', $id)->get();
        $orderInvoice = OrderItem::where('order_id', $id)->get();
        $orderNoInvoice = OrderItem::where('order_id', $id)->where('in_invoice', 'no')->get();
        $outletIds = $orderInvoice->pluck('order.outlet_id')->unique()->toArray();
        $orders = Order::with('user')->where('id',$id)->get();
        // dd($orderNoInvoice);

        $orderss = KYCDocument::where('user_id', $outletIds)
      ->get();

      $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
      $lastpayment = OutstandingStatement::where('order_id', $id);
      $pdf = PDF::loadView('admin.invoice.delivery_charges', compact('orderInvoice' ,'orderss', 'orders', 'orderNoInvoice', 'orderInvoice1', 'lastpayment'));

      return $pdf->stream('delivery_charges.pdf');
    //   return view('admin.invoice.delivery_charges', compact('orderInvoice' ,'orderss', 'orders', 'orderNoInvoice', 'orderInvoice1', 'lastpayment'));
    }



    public function generateInvoiceAndDeliveryCharges($id)
    
    {
        // dd($id);
        $maharashtrian = '';
        $orderInvoice1 = OrderItem::with('product')->where('order_id', $id)->get();
        $orderInvoice = OrderItem::where('order_id', $id)->get();
        // dd($orderInvoice);
        $orderNoInvoice = OrderItem::where('order_id', $id)->where('in_invoice', 'no')->get();
        $outletIds = $orderInvoice->pluck('order.outlet_id')->unique()->toArray();

        $orders = Order::with('user')->where('id', $id)->get();
        $orderss = KYCDocument::whereIn('user_id', $outletIds)->get();
        $shipping_pincode = $orders->first()->shipping_pincode;
        $pincodes = Pincode::where('pincode', $shipping_pincode)->get();
       if($pincodes){
        $maharashtrian = 'True';
       }else{
        $response = file_get_contents("https://api.postalpincode.in/pincode/$shipping_pincode");
        $data = json_decode($response, true);
        if($data[0]['PostOffice'][0]['State'] === 'Maharashtra'){
            $maharashtrian = 'True';
        }else{
            $maharashtrian = 'False';
        }
       }
         $latestOrderCreatedAt = $orders->first()->created_at;
         
        $order_company = Order::with('user')->where('id', $id)->first();
        // dd($order_company->user_id);
        $company_name = User::where('id', $order_company->user_id)
            ->select('outlet_name')
            ->first();
            
            
        $company_name1 = $company_name->outlet_name ?? 'N/A';  
        // dd($company_name1);
 
        $lastpayment = OutstandingStatement::where('user_id', $outletIds)->where('outstanding_date', '>=',  $latestOrderCreatedAt)->get();
        $invoiceView = view('admin.invoice.invoice', compact('orderInvoice', 'orderss', 'orders', 'orderNoInvoice', 'orderInvoice1', 'lastpayment', 'maharashtrian','company_name1'))->render();
        $deliveryChargesView = view('admin.invoice.delivery_charges', compact('orderInvoice', 'orderss', 'orders', 'orderNoInvoice', 'orderInvoice1', 'lastpayment','maharashtrian'))->render();

        $pdf = PDF::loadHTML($invoiceView);
        return $pdf->stream('combined_invoice_and_delivery_charges.pdf');
    }





    public function update(Request $request, $id)
    {
        $request->validate([
            'in_invoice' => 'required|in:yes,no',
        ]);
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->update([
            'in_invoice' => $request->in_invoice,
        ]);

        return redirect()->back()->with('success', 'Order item updated successfully');
    }

    public function destroy($id)
{
    try {
        DB::transaction(function () use ($id) {

            $orderItem = OrderItem::findOrFail($id);

            $orderId       = $orderItem->order_id;
            
             $delivery = DeliveryManagement::where('order_id', $orderId)
                ->latest()
                ->first();

            if ($delivery && $delivery->delivery_status !== 'pending') {
                throw new \Exception(
                    'This order is already ' .
                    ucfirst(str_replace('_', ' ', $delivery->delivery_status)) .
                    ' and items cannot be deleted.'
                );
            }
            
            
            
            $productPrice = $orderItem->price;

            Log::info("Deleting OrderItem", [
                'order_item_id' => $id,
                'order_id'      => $orderId,
                'price'         => $productPrice,
            ]);

            // Update Order total
            $order = Order::find($orderId);
            if ($order) {
                $oldDiscount = $order->total_discount_value;

                $order->total_discount_value = max(0, $order->total_discount_value - $productPrice);
                $order->save();

                Log::info("Order updated after item delete", [
                    'order_id' => $orderId,
                    'old_total_discount' => $oldDiscount,
                    'new_total_discount' => $order->total_discount_value,
                ]);
            } else {
                Log::warning("Order not found while deleting order item", [
                    'order_id' => $orderId
                ]);
            }

            // Update Payment total
            $payment = Payment::where('order_id', $orderId)->first();
            if ($payment) {
                $oldAmount = $payment->total_amount;

                $payment->total_amount = max(0, $payment->total_amount - $productPrice);
                $payment->save();

                Log::info("Payment updated after item delete", [
                    'order_id' => $orderId,
                    'old_total_amount' => $oldAmount,
                    'new_total_amount' => $payment->total_amount,
                ]);
            } else {
                Log::warning("Payment not found while deleting order item", [
                    'order_id' => $orderId
                ]);
            }

            // Delete Order Item
            $orderItem->delete();

            Log::info("OrderItem deleted successfully", [
                'order_item_id' => $id
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Order item deleted successfully'
        ]);

    } catch (\Exception $e) {

        Log::error("Failed to delete OrderItem", [
            'order_item_id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong while deleting order item'
        ], 500);
    }
}




// public function acceptOrder(SmsService $smsService, $orderId)
// {
//     try {
//         $order = Order::with('user', 'items.product')->findOrFail($orderId);
//         $user = User::find($order->user_id); 
//         $delivery = DeliveryManagement::where('order_id', $orderId)->first();
//         $payment = Payment::where('order_id', $orderId)->first();
//         if (!$delivery) {
//             // Log::error("No delivery record found for order ID {$orderId}.");
//             return response()->json(['error' => 'Delivery record not found'], 404);
//         }
//         $maharashtrian = 'False';
//         $shipping_pincode = $order->shipping_pincode;
//         if ($shipping_pincode) {
//             $pincodeRecord = Pincode::where('pincode', $shipping_pincode)->exists();
//             if ($pincodeRecord) {
//                 $maharashtrian = 'True';
//             } else {
//                 $response = file_get_contents("https://api.postalpincode.in/pincode/$shipping_pincode");
//                 $data = json_decode($response, true);
//                 if (!empty($data[0]['PostOffice'][0]['State']) && $data[0]['PostOffice'][0]['State'] === 'Maharashtra') {
//                     $maharashtrian = 'True';
//                 }
//             }
//         }
//         $delivery->delivery_status = 'in_progress';
//         $delivery->save();
//         $order->invoice_date = now();

//         $totalAmount = 0;
//         $totalcgstamount = 0;
//         $totalsgstamount = 0;
//         $totaligstamount = 0;
//         $totalcessamount = 0;

//         $subtotal          = 0;
//         $productDiscount   = 0;
//         $couponDiscountTot = 0;
//         $cgstSgstTotal     = 0;
//         $totalAmount       = 0;

//         $totalQty = $order->items->sum('quantity');

//         $deliveryCharge = 0;
//       $packingCharge  = 0;
//       $otherCharge    = 0;


//         if (!empty($order->delivery_charges) && $order->delivery_charges > 0) {
//             $deliveryCharge = $order->delivery_charges;
//         } else {
//             if ($order->shipping_pincode) {
//                 $pincodeData = Pincode::where('pincode', $order->shipping_pincode)->first();

//                 if ($pincodeData) {
//                     $zone = ZoneProcessing::where('id', $pincodeData->zone_id)
//                         ->where('status', 'Active')
//                         ->first();

//                     if ($zone) {
//                         if ($totalQty > 24) {
//                             $deliveryCharge = $zone->bulk_delivery_charges ?? 0;
//                         } else {
//                             $deliveryCharge = $zone->single_delivery_charges ?? 0;
//                         }
//                     }
//                 }
//             }
//         }

//         $packingCharge = !empty($order->packing_charges) ? $order->packing_charges : 0;
//         $otherCharge   = !empty($order->others_charges)   ? $order->others_charges : 0;


//             foreach ($order->items ?? [] as $orderItem) {

//             $product = $orderItem->product ?? null;
//             if (!$product) continue;

//             $pretax = (float) $orderItem->quantity * (float) $orderItem->offer_price;
//             $subtotal += $pretax;

        
//             $productDiscountPct = $product->total_discount ?? 0;
//             $rowProductDiscount = $productDiscountPct > 0
//                 ? ($pretax * $productDiscountPct) / 100
//                 : 0;

//             $productDiscount += $rowProductDiscount;
//             // dd($productDiscount);

           
//             $cess = 0;
//             if ($maharashtrian === 'False') {
//                 $igst = $product->igst ?? 0;
//                 $sgst = 0;
//                 $cgst = 0;
//             } else {
//                 $sgst = $product->sgst ?? 0;
//                 $cgst = $product->cgst ?? 0;
//                 $igst = 0;
//             }

//             $cgstAmount = ($cgst * $pretax) / 100;
//             $sgstAmount = ($sgst * $pretax) / 100;
//             $igstAmount = ($igst * $pretax) / 100;

//             $cgstSgstTotal += ($cgstAmount + $sgstAmount + $igstAmount);

           
//             $couponDiscount = $orderItem->coupon_discount ?? 0;
//             $couponDiscountTot += $couponDiscount;

         
//             $lineTotal = $pretax
//                 - $couponDiscount
//                 + $cgstAmount
//                 + $sgstAmount
//                 + $igstAmount;

//             $totalAmount += $lineTotal;
//         }

//          $totalAmount += $deliveryCharge;
//          $totalAmount += $packingCharge;
//          $totalAmount += $otherCharge;

//         $order->subtotal            = $subtotal;
//         $order->product_discount    = $productDiscount;
//         $order->coupon_discount     = $couponDiscountTot;
//         $order->cgst_sgst           = $cgstSgstTotal;
        
//         $order->delivery_charges    = $deliveryCharge;
//         $order->packing_charges      = $packingCharge;
//         $order->others_charges        = $otherCharge;
        
//         $order->total_discount_value = $totalAmount;
//         $order->save();


//         $payment->total_amount = $totalAmount;
//         $payment->save();


//         // Log::info("Order ID {$orderId} updated: Total Discount Value (Total Amount) = ₹{$totalAmount}");
//         $user = User::find($order->user_id);
//         $notificationMessage = "Your order (ID: {$order->order_id}) is now in progress. Your total amount after discount is ₹" . number_format($totalAmount, 2);
//         // $user->notify(new NewEnqueryRequestCustomerNotification($user->id, $notificationMessage));

//         $data = [
//             'order' => $order,
//             'delivery' => $delivery,
//         ];
//         // $smsService->sendOrder($data);
//         // Log::info("SMS sent to user {$order->user->id} for order {$orderId}.");
//         return response()->json([
//             'success' => true,
//             'message' => 'Order accepted, values updated, and customer notified successfully.'
//         ]);
//     } catch (\Exception $e) {
//     Log::error("Error accepting order: " . $e->getMessage(), [
//         'trace' => $e->getTraceAsString()
//     ]);

//     return response()->json([
//         'success' => false,
//         'message' => 'An error occurred while processing the order.'
//     ], 500);
// }

// }



public function acceptOrder(SmsService $smsService, $orderId)
{
    
    // dd('hey');
    DB::beginTransaction();

    try {

        $order    = Order::with('items.product')->findOrFail($orderId);
        $delivery = DeliveryManagement::where('order_id', $orderId)->firstOrFail();
        
        
        if ($delivery->delivery_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This order is already ' . ucfirst(str_replace('_', ' ', $delivery->delivery_status)) . ' and cannot be accepted again.'
            ], 422);
        }
        
        
        $payment = Payment::where('order_id', $orderId)->first();
        
        if (!$payment) {
            throw new \Exception("Payment not found for this order.");
        }
        
              $blockedStatuses = [
            'in_progress',
            'ready_for_dispatch',
            'final_check_done',
            'dispatched',
            'hold'
        ];
        
           /*
        |--------------------------------------------------------------------------
        | 30 hrs condition added for ujala
        |--------------------------------------------------------------------------
        */
        
        
        $existingBlockedOrders = DB::table('orders')
            ->join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
            ->where('orders.outlet_id', $order->outlet_id)
            ->where('orders.id', '!=', $orderId)
            ->whereIn('delivery_management.delivery_status', $blockedStatuses)
            ->where('orders.created_at', '<=', now()->subHours(30))
            ->select(
                'orders.id',
                'orders.order_id',
                'delivery_management.delivery_status',
                'orders.created_at'
            )
            ->get();
        
        if ($existingBlockedOrders->isNotEmpty()) {
        
            $count = $existingBlockedOrders->count();
        
            $orderList = $existingBlockedOrders->map(function ($o) {
                return $o->order_id . ' (' . ucfirst(str_replace('_', ' ', $o->delivery_status)) . ')';
            })->implode(', ');
        
            return response()->json([
                'success' => false,
                'message' => "Cannot accept order. {$count} order(s) already in progress for more than 30 hours for this outlet: {$orderList}. Please ensure previous deliveries are completed first."
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | 1. VALIDATE PICK LIST
        |--------------------------------------------------------------------------
        */
        $pickLists = PickList::with('product')
            ->where('order_id', $orderId)
            ->get();

        if ($pickLists->isEmpty()) {
            throw new \Exception("Pick list not created. Please create pick list first.");
        }

        // Ensure all are picked
        if ($pickLists->where('status', '!=', 'PICKED')->count() > 0) {
            throw new \Exception("Some pick list items are not marked as PICKED.");
        }
        
        
        
        
        /*
            |--------------------------------------------------------------------------
            | 1b. VALIDATE PICK LIST ↔ ORDER ITEMS ARE IN SYNC (BOTH DIRECTIONS)
            |--------------------------------------------------------------------------
            */
            $orderProductIds = $order->items->pluck('product_id')->unique();
            $pickListProductIds = $pickLists->pluck('product_id')->unique();
            
            // (a) Pick list has a product that's no longer on the order (e.g. removed via cancel/delete item)
            $staleProductIds = $pickListProductIds->diff($orderProductIds);
            
            if ($staleProductIds->isNotEmpty()) {
            
                $staleNames = $pickLists
                    ->whereIn('product_id', $staleProductIds)
                    ->pluck('product.product_name')
                    ->unique()
                    ->implode(', ');
            
                throw new \Exception(
                    "Pick list contains removed product(s): {$staleNames}. "
                    . "Please recreate the pick list before accepting this order."
                );
            }
            
            // (b) Order item quantity has changed since pick list was created
            foreach ($order->items as $orderItem) {
            
                $pickedQtyForItem = $pickLists
                    ->where('product_id', $orderItem->product_id)
                    ->sum('quantity');
            
                if ((int) $pickedQtyForItem !== (int) $orderItem->quantity) {
                    throw new \Exception(
                        "Pick list quantity ({$pickedQtyForItem}) for product '{$orderItem->product->product_name}' "
                        . "does not match the current processed quantity ({$orderItem->quantity}). "
                        . "Please recreate the pick list before accepting this order."
                    );
                }
            }
        

        /*
        |--------------------------------------------------------------------------
        | 2. DEDUCT STOCK USING PICK LIST
        |--------------------------------------------------------------------------
        */
        $productTotals = []; // product_id => total qty to deduct

        foreach ($pickLists as $pick) {

            // Lock the rack stock
        // $rack = RackStock::where('product_id', $pick->product_id)
        //     ->whereRaw('LOWER(batch_no) = ?', [strtolower($pick->batch_no)])
        //     ->where('rack_no', $pick->rack_no)
        //     ->where('level_no', $pick->level_no)
        //     ->where('slot_no', $pick->slot_no)
        //     ->lockForUpdate()
        //     ->first();
            
        //     // dd($rack)
        
        // if (!$rack) {
        //     throw new \Exception("Rack stock not found.");
        // }
        
        // if ($pick->quantity <= 0) {
        //     throw new \Exception("Quantity must be greater than 0.");
        // }
        
        // if ($rack->quantity < $pick->quantity) {
        //     throw new \Exception(
        //         "Insufficient stock in Rack {$rack->rack_no}/{$rack->level_no}/{$rack->slot_no}"
        //     );
        // }
        //     // Deduct from rack
        //     $rack->quantity -= $pick->quantity;
        //     $rack->save();
        
        
    $racks = RackStock::where('product_id', $pick->product_id)
    ->whereRaw('LOWER(batch_no) = ?', [strtolower($pick->batch_no)])
    ->where('rack_no', $pick->rack_no)
    ->where('level_no', $pick->level_no)
    ->where('slot_no', $pick->slot_no)
    ->lockForUpdate()
    ->get();


$totalQty = $racks->sum('quantity');

if ($totalQty < $pick->quantity) {
    throw new \Exception(
        "Insufficient stock in Rack {$pick->rack_no}/{$pick->level_no}/{$pick->slot_no}"
    );
}


$remaining = $pick->quantity;

foreach ($racks as $rack) {

    if ($rack->quantity <= 0) continue;

    if ($rack->quantity >= $remaining) {
        $rack->quantity -= $remaining;
        $rack->save();
        break;
    }

    $remaining -= $rack->quantity;
    $rack->quantity = 0;
    $rack->save();
}

            // Track total per product
            if (!isset($productTotals[$pick->product_id])) {
                $productTotals[$pick->product_id] = 0;
            }
            $productTotals[$pick->product_id] += $pick->quantity;

            // Stock Movement
            StockMovement::create([
                'product_id'     => $pick->product_id,
                'reference_type' => 'ORDER',
                'reference_id'   => $orderId,
                'movement_type'  => 'OUT',
                'quantity'       => $pick->quantity,
                'unit_cost'      => $pick->product->cost_per_item ?? 0,
                'batch_no'       => $pick->batch_no,
                'expiry_date'    => $pick->expiry_date,
                'remarks'        => "Picked for Order #{$orderId}"
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. DEDUCT FROM MASTER PRODUCT STOCK
        |--------------------------------------------------------------------------
        */
        foreach ($productTotals as $productId => $qty) {

            $productStock = ProductStock::where('product_id', $productId)
                ->lockForUpdate()
                ->first();

            if (!$productStock || $productStock->total_stock < $qty) {
                throw new \Exception("Product stock mismatch for product ID {$productId}");
            }

            $productStock->total_stock -= $qty;
            $productStock->save();
        }

        /*
        |--------------------------------------------------------------------------
        | 4. UPDATE DELIVERY & ORDER STATUS
        |--------------------------------------------------------------------------
        */
        $delivery->delivery_status = 'in_progress';
        $delivery->save();

        $order->invoice_date = now();

        /*
        |--------------------------------------------------------------------------
        | 5. BILLING / TAX CALCULATION  (YOUR EXISTING CODE STAYS SAME)
        |--------------------------------------------------------------------------
        */
        $maharashtrian = 'False';
        $shipping_pincode = $order->shipping_pincode;

        if ($shipping_pincode) {
            $pincodeRecord = Pincode::where('pincode', $shipping_pincode)->exists();
            if ($pincodeRecord) {
                $maharashtrian = 'True';
            } else {
                $response = file_get_contents("https://api.postalpincode.in/pincode/$shipping_pincode");
                $data = json_decode($response, true);
                if (!empty($data[0]['PostOffice'][0]['State']) &&
                    $data[0]['PostOffice'][0]['State'] === 'Maharashtra') {
                    $maharashtrian = 'True';
                }
            }
        }

        $subtotal = 0;
        $productDiscount = 0;
        $couponDiscountTot = 0;
        $cgstSgstTotal = 0;
        $totalAmount = 0;

        $totalQty = $order->items->sum('quantity');

        $deliveryCharge = $order->delivery_charges ?? 0;
        $packingCharge  = $order->packing_charges ?? 0;
        $otherCharge    = $order->others_charges ?? 0;

        foreach ($order->items as $orderItem) {

            $product = $orderItem->product;
            if (!$product) continue;

            $pretax = (float)$orderItem->quantity * (float)$orderItem->offer_price;
            $subtotal += $pretax;

            $productDiscountPct = $product->total_discount ?? 0;
            $rowProductDiscount = $productDiscountPct > 0
                ? ($pretax * $productDiscountPct) / 100
                : 0;

            $productDiscount += $rowProductDiscount;

            if ($maharashtrian === 'False') {
                $igst = $product->igst ?? 0;
                $sgst = 0;
                $cgst = 0;
            } else {
                $sgst = $product->sgst ?? 0;
                $cgst = $product->cgst ?? 0;
                $igst = 0;
            }

            $cgstAmount = ($cgst * $pretax) / 100;
            $sgstAmount = ($sgst * $pretax) / 100;
            $igstAmount = ($igst * $pretax) / 100;

            $cgstSgstTotal += ($cgstAmount + $sgstAmount + $igstAmount);

            $couponDiscount = $orderItem->coupon_discount ?? 0;
            $couponDiscountTot += $couponDiscount;

            $lineTotal = $pretax
                - $couponDiscount
                + $cgstAmount
                + $sgstAmount
                + $igstAmount;

            $totalAmount += $lineTotal;
        }

        $totalAmount += ($deliveryCharge + $packingCharge + $otherCharge);

        $order->subtotal = $subtotal;
        $order->product_discount = $productDiscount;
        $order->coupon_discount = $couponDiscountTot;
        $order->cgst_sgst = $cgstSgstTotal;
        $order->total_discount_value = $totalAmount;
        $order->save();

        $payment->total_amount = $totalAmount;
        $payment->save();
        
         //    Log::info("Order ID {$orderId} updated: Total Discount Value (Total Amount) = ₹{$totalAmount}");
        $user = User::find($order->user_id);
        $notificationMessage = "Your order (ID: {$order->order_id}) is now in progress. Your total amount after discount is ₹" . number_format($totalAmount, 2);
        $user->notify(new NewEnqueryRequestCustomerNotification($user->id, $notificationMessage));

        $data = [
            'order' => $order,
            'delivery' => $delivery,
        ];
        $smsService->sendOrder($data);
        // Log::info("SMS sent to user {$order->user->id} for order {$orderId}.");

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Order accepted successfully. Pick list committed, stock deducted, and billing updated.'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error("Accept Order Error: ".$e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}






        


    

    public function cancelOrder($orderId)
{
 
    $delivery = DeliveryManagement::where('order_id', $orderId)->latest()->first();
 
     if ($delivery && $delivery->delivery_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This order is already ' . ucfirst(str_replace('_', ' ', $delivery->delivery_status)) . ' and cannot be cancelled.'
            ], 422);
        }
     
 
    DeliveryManagement::where('order_id', $orderId)->update(['delivery_status' => 'cancelled']);
    $order = Order::find($orderId);
    if ($order) {
        // Log::info("Order {$order->order_id} has been cancelled. Delivery status updated.");
      
        $notificationMessage = "Your order (ID: {$order->order_id}) has been cancelled. If you have any questions, please contact support.";
      
        // $cancelingUser = session('ADMIN_NAME');
        // if (!$cancelingUser) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Unauthorized action'
        //     ], 401);
        // }
        $customer = User::find($order->user_id);
        if ($customer) {
            $customer->notify(new NewEnqueryRequestCustomerNotification($customer->id, $notificationMessage));
            // Log::info("Notification sent to user {$customer->id} for cancelled order {$order->order_id}.");
        } else {
            // Log::error("User not found for order {$order->order_id}. Cannot send cancellation notification.");
        }
        return response()->json([
        'success' => true,    
        'message' => 'Order cancelled and customer notified successfully']);
    } else {
        // Log::error("Order with ID {$orderId} not found.");
        return response()->json(['error' => 'Order not found'], 404);
    }
}

    


    public function downloadExcel(Request $request)
{
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');

    $payments = Payment::whereBetween('date', [$startDate, $endDate])->get();

    $excelData = [];
    $headers = [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => 'attachment; filename="payments.xlsx"',
    ];
    return response()->stream(function () use ($excelData) {
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(\PhpOffice\PhpSpreadsheet\IOFactory::createWriter(new \PhpOffice\PhpSpreadsheet\Spreadsheet(), 'Xlsx'));
        $writer->setPreCalculateFormulas(false);
        $writer->setIncludeCharts(true);
        $writer->setIncludeCharts(true);
        $writer->save('php://output');
    }, 200, $headers);
}


function numberToWords($n) {
    $n = (float)$n;

    $units = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten"];
    $teens = ["Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen", "Twenty"];
    $tens = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
    $othersIntl = ["Thousand", "Million", "Billion", "Trillion"];

    $getBelowHundred = function ($n) use ($units, $teens, $tens) {
        if ($n >= 100) {
            return "greater than or equal to 100";
        }
        if ($n <= 10) {
            return $units[$n];
        }
        if ($n <= 20) {
            return $teens[$n - 11];
        }
        $unit = $n % 10;
        $ten = (int) ($n / 10);
        $tenWord = ($ten > 0 ? ($tens[$ten] . " ") : '');
        $unitWord = ($unit > 0 ? $units[$unit] : '');
        return $tenWord . $unitWord;
    };

    $getBelowThousand = function ($n) use ($units, $getBelowHundred) {
        if ($n >= 1000) {
            return "greater than or equal to 1000";
        }
        $word = $getBelowHundred($n % 100);
        $hun = (int) ($n / 100);
        $word = ($hun > 0 ? ($units[$hun] . " Hundred ") : '') . $word;
        return $word;
    };

    $word = '';
    $val;
    $word2 = '';
    $val2;
    $b = explode(".", $n);
    $n = $b[0];
    $d = isset($b[1]) ? $b[1] : '';
    $d = (int) $d;
    $d = substr($d, 0, 2);

    $val = $n % 1000;
    $n = (int) ($n / 1000);

    $val2 = (int)$d % 1000;

    $d = (int)($d / 1000);


    $word = $getBelowThousand($val);
    $word2 = $getBelowThousand($val2);

    $othersArr = $othersIntl;
    $divisor = 1000;
    $func = $getBelowThousand;

    $i = 0;
    while ($n > 0) {
        if ($i == count($othersArr) - 1) {
            $word = numberToWords($n) . " " . $othersArr[$i] . " " . $word;
            break;
        }
        $val = $n % $divisor;
        $n = (int) ($n / $divisor);
        if ($val != 0) {
            $word = $func($val) . " " . $othersArr[$i] . " " . $word;
        }
        $i++;
    }

    $i = 0;
    while ($d > 0) {
        if ($i == count($othersArr) - 1) {
            $word2 = numberToWords($d) . " " . $othersArr[$i] . " " . $word2;
            break;
        }
        $val2 = $d % $divisor;
        $d = (int) ($d / $divisor);
        if ($val2 != 0) {
            $word2 = $func($val2) . " " . $othersArr[$i] . " " . $word2;
        }
        $i++;
    }
    if ($word != '') $word = $word . ' Rupees';
    if ($word2 != '') $word2 = ' And ' . $word2 . ' Paise';
    return $word . $word2;
}





}


