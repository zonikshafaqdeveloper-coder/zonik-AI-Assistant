<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use App\Models\OutstandingStatement;
use App\Models\DeliveryManagement;
use App\Models\OrderItem;
use App\Models\KYCDocument;
use App\Models\Payment;
use App\Models\PaymentHistory;
use App\Models\OutletPaymentTerm;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\NewEnqueryRequestCustomerNotification;
use App\Models\Cart;
use App\Models\User;
use App\Models\PickList;
use App\Models\StockMovement;
use App\Models\RackStock;
use App\Models\ProductStock;
use App\Models\DairyPaymentTerm;
use App\Models\OriginalItem;
use App\Services\SmsService;
use App\Models\AdminNotification;
use App\Models\OrderNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// use Maatwebsite\Excel\Concerns\FromCollection;    //insteand of this use below code.
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DeliveryExport;

class OrderController extends Controller
{

// Add export to excel in delivery-management: 
public function exportDelivery()
{
    return Excel::download(new DeliveryExport, 'delivery.xlsx');
}
// end....
    public function insertOrder(Request $request,SmsService $smsService)
    {
        $user = auth()->user();
        $assistantToken = trim((string) $request->input('assistant_order_token', ''));
        $completedKey = $assistantToken !== '' ? 'assistant_order_done:' . $user->id . ':' . hash('sha256', $assistantToken) : null;
        $processingKey = $assistantToken !== '' ? 'assistant_order_processing:' . $user->id . ':' . hash('sha256', $assistantToken) : null;
        if ($completedKey && Cache::has($completedKey)) return response()->json(Cache::get($completedKey));
        if ($processingKey && !Cache::add($processingKey, true, now()->addMinutes(2))) {
            return response()->json(['message' => 'Order is already being processed.'], 409);
        }

        $deliveryDate = $request->deliveryDate;
        $delivery_time_slot = $request->delivery_time_slot;
        $delivery_slot_type = $request->delivery_slot_type;
        $billingAddress = $request->billingAddress;
        $shippingAddress = $request->shippingAddress;
        $subtotal = $request->subtotal;
        $productDiscount = $request->productDiscount;
        $cgstSgst = $request->cgstSgst;
        $packingCharges = $request->packingCharges;
        $othersCharges = $request->othersCharges;
        $shipping_pincode = $request->shipping_pincode;
        $deliveryCharges = $request->deliveryCharges;
        $user_id = $request->user_id;
        $totalDiscountValue = $request->totalDiscountValue;
        $payment_status = $request->payment_status;
        $cart = $request->cart;
        $coupon_discount = null;
        foreach ($cart as $cartItem) {
            $coupon_discount = $cartItem['coupon_discount']; // Assuming 'coupon_discount' is a property of the Cart object
            break; // Exit the loop after getting the first cart item's coupon discount
        }


        $userData = User::where('id', $user_id)->get();
        $latestOrderId = Order::max('id');
        $orderIncrement = $latestOrderId + 1;
        $orderFormattedId = str_pad($orderIncrement, 2, '0', STR_PAD_LEFT);
        $orderFormattedId = 'ORD-00' . $orderFormattedId;

        $order = new Order();
        $order->delivery_date = $deliveryDate;
        $order->delivery_time_slot = $delivery_time_slot;
        $order->delivery_slot_type = $delivery_slot_type;
        $order->outlet_id = $user_id;
        $order->user_id = $user->id;
        $order->billing_address = $billingAddress;
        $order->shipping_address = $shippingAddress;
        $order->subtotal = $subtotal;
        $order->product_discount = $productDiscount;
        $order->cgst_sgst = $cgstSgst;
        $order->shipping_pincode = $shipping_pincode;
        $order->packing_charges = $packingCharges;
        $order->coupon_discount = $coupon_discount;
        $order->others_charges = $othersCharges;
        $order->delivery_charges = $deliveryCharges;
        $order->total_discount_value = $totalDiscountValue;
        $order->payment_method = $payment_status;
        $order->status = 'sent';
        $order->payment_status = ($payment_status == 'credit' || $payment_status == 'pay_on_delivery') ? 'unpaid' : $payment_status;

        $order->save();
        $invoiceFormattedId = str_pad($orderIncrement, 2, '0', STR_PAD_LEFT);
        $invoiceFormattedId = 'INV-00' . $invoiceFormattedId;
        $order->invoice_id = $invoiceFormattedId;
        $order->order_id = $orderFormattedId;
        $order->save();

        $lastDelivery = DeliveryManagement::latest()->first();
        if ($lastDelivery) {
            $lastId = intval(substr($lastDelivery->delivery_id, 4));
            $nextId = $lastId + 1;
        } else {
            $nextId = 1;
        }

        $deliveryId = 'DEL-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        $delivery = new DeliveryManagement();
        $delivery->order_id = $order->id;
        $delivery->delivery_id = $deliveryId;
        $delivery->delivery_status = 'pending';
        $delivery->delivery_address = $shippingAddress;
        $delivery->delivery_person_id = $user_id;
        $delivery->delivery_notes = '';
        $delivery->delivery_date = $deliveryDate;
        $delivery->save();

        $previousOrders = Order::whereNull('order_id')->orWhereNull('invoice_id')->get();
        foreach ($previousOrders as $previousOrder) {
            $invoiceFormattedId = 'INV-00' . $previousOrder->id;
            $orderFormattedId = 'ORD-00' .  $previousOrder->id;
            $previousOrder->order_id = $orderFormattedId;
            $previousOrder->invoice_id = $invoiceFormattedId;
            $previousOrder->save();
        }



        $currentDate = date('Y-m-d');
        $userData = User::where('id', $user_id)->first();

        if ($userData) {
            $outstandingDate = date('Y-m-d', strtotime($currentDate . ' + ' . $userData->due_days_limit . ' days'));
        }


        if ($payment_status == 'credit' || $payment_status == 'pay_on_delivery') {
            $outstandingStmt = new OutstandingStatement();
            $outstandingStmt->user_id = $user_id;
            $outstandingStmt->order_id = $order->id;
            $outstandingStmt->outlet_id = $user_id;
            $outstandingStmt->total_due_amount = $totalDiscountValue;
            $outstandingStmt->outstanding_date = $outstandingDate;
            $outstandingStmt->save();
        }


        foreach ($request->cart as $cartItem) {
            if (is_array($cartItem)) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $cartItem['product_id'];

                if($cartItem['product_types'] == '1'){
                    $orderItem->qty_type = 'box';

                }else{
                    $orderItem->qty_type = 'loose';
                }
                $orderItem->quantity = $cartItem['total_qty'];
                $orderItem->price = $cartItem['total_amt_basic'];
                $orderItem->offer_price = $cartItem['offer_price'];
                $orderItem->mrp = $cartItem['mrp'];
                $orderItem->coupon_discount = $cartItem['coupon_discount'];
                $orderItem->save();
                
                OriginalItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['total_qty'],
                'price' => $cartItem['total_amt_basic'],
                'offer_price' => $cartItem['offer_price'],
                'mrp' => $cartItem['mrp'],
                'product_name' => $cartItem['product_name'] ?? null,
                'sku' => $cartItem['sku'] ?? null,
            ]);
        
                Cart::where('id', $cartItem['id'])->delete();
            }
        }
        
        
//  if ($payment_status == 'credit') {
//     $order->payment_status = 'unpaid';
//     $order->save();

//     $payment = new Payment();
//     $payment->user_id = $user->id;
//     $payment->outlet_id = $user_id;
//     $payment->order_id = $order->id;
//     $payment->payment_id = 'PAY-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
//     $payment->paid_amount = $totalDiscountValue;
//     $payment->payment_mode = 'Credit Pay';
//     $payment->paid_to = null;
//     $payment->save();
// } elseif ($payment_status == 'pay_on_delivery') {
//     $order->payment_status = 'unpaid';
//     $order->save();

//     $payment = new Payment();
//     $payment->user_id = $user->id;
//     $payment->outlet_id = $user_id;
//     $payment->order_id = $order->id;
//     $payment->payment_id = 'PAY-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
//     $payment->paid_amount = $totalDiscountValue;
//     $payment->payment_mode = 'Pay on Delivery';
//     $payment->paid_to = null;
//     $payment->save();
// }


         $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'outlet_id' => $user_id,
            'total_amount' => $order->total_discount_value,
            'total_paid' => 0,
            'payment_method' => $payment_status, // credit / pay_on_delivery / prepaid
            'payment_status' => ($payment_status == 'credit' || $payment_status == 'pay_on_delivery') ? 'unpaid' : 'paid',
            'payment_id' => 'PAY-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
            ]);
            

        $notificationMessage = "Your order has been submitted successfully! Your order ID is " . $orderFormattedId;
        auth()->user()->notify(new NewEnqueryRequestCustomerNotification(auth()->user()->id, $notificationMessage));
    


            // Admin Notification
    $adminNotification = new OrderNotification();
    $adminNotification->user_id = $user->id;
    $adminNotification->title = 'New Order ' . $order->order_id;

    // Generate URL with order details route
    $adminNotification->click_url = route('orderitem.details', ['id' => $order->id],false);

    $adminNotification->save();

    $data = [
        'delivery' => DeliveryManagement::findOrFail($delivery->id), 
        'order' => Order::where('id', $delivery->order_id)->with('user')->first(),
    ];
    
    // dd($data);
    //   Send SMS
      $response = $smsService->sendOrder($data);

    // return back()->with('success', 'Order Placed Successfully..!!');
    //    dd($response);
        $orderResponse = [
            'success' => 'Order Placed Successfully..!!',
            'order_id' => $order->order_id,
            'tracking_code' => $delivery->delivery_id,
        ];
        if ($completedKey) {
            Cache::put($completedKey, $orderResponse, now()->addDay());
            Cache::put(
                'ai-assistant:completed:' . $user->id . ':' . hash('sha256', $assistantToken),
                true,
                now()->addDays(30)
            );
        }
        if ($processingKey) Cache::forget($processingKey);
        return response()->json($orderResponse);
    }

    // public function index()
    // {
    //     $orders = Order::with(['user', 'outstanding', 'deliveries'])
    //         ->where('status', '!=', 'draft')
    //         ->orderBy('orders.created_at', 'desc')
    //         ->get()
    //         ->filter(function ($order) {
    //             // Check if the order has any deliveries with "delivered" or "cancelled" status
    //             foreach ($order->deliveries as $delivery) {
    //                 if (in_array($delivery->delivery_status, ['delivered', 'cancelled'])) {
    //                     return false; // Exclude this order
    //                 }
    //             }
    //             return true; // Include this order
    //         });

    //         $users = User::select('id', 'name' ,'outlet_name')->get();

    //         // dd($users);

    //     foreach ($orders as $order) {
    //         $outstandings = OutstandingStatement::where('order_id', $order->id)->get();
    //         $payment = Payment::where('order_id', $order->id)->first();
    //         $order->payment = $payment;
    //         $order->has_docs = ($payment && !empty($payment->documents));
    //         $order->outstandings = $outstandings;
    //         $orderItemsExist = OrderItem::where('order_id', $order->id)->exists();
    //         $order->orderItemsExist = $orderItemsExist;
    //         $deliveriesExist = $order->deliveries->isNotEmpty();
    //         $order->deliveriesExist = $deliveriesExist;
    //         if (!$orderItemsExist) {
    //             DeliveryManagement::where('order_id', $order->id)->update(['delivery_status' => 'cancelled']);
    //         }
    //         $deliveryStatuses = [];
    //         foreach ($order->deliveries as $delivery) {
    //             $status = $delivery->delivery_status;
    //             $color = '';
    //             $fontWeight = '';

    //             switch ($status) {
    //                 case 'pending':
    //                     $color = 'orange';
    //                     $fontWeight = 'bold';
    //                     break;
    //                 case 'in_progress':
    //                     $color = 'blue';
    //                     $fontWeight = 'bold';
    //                     break;
    //                 case 'ready_for_dispatch':
    //                     $color = 'green';
    //                     $fontWeight = 'bold';
    //                     break;
    //                 case 'delivered':
    //                     $color = 'purple';
    //                     $fontWeight = 'bold';
    //                     break;
    //                 case 'cancelled':
    //                     $color = 'red';
    //                     $fontWeight = 'bold';
    //                     break;
    //                 default:
    //                     $color = 'black';
    //                     $fontWeight = 'normal';
    //             }

    //             $deliveryStatuses[] = [
    //                 'status' => $status,
    //                 'color' => $color,
    //                 'fontWeight' => $fontWeight
    //             ];
    //         }
    //         $order->deliveryStatuses = $deliveryStatuses;
    //     }
        
    //       $notifications = DB::table('order_notifications')
    // ->where('is_read', 0)
    // ->get()
    // ->keyBy(function($item) {
    //     return $item->user_id . '_' . $item->click_url;
    // });
    
    

    //     return view('admin.order.index', compact('orders','users','notifications'));
    // }
    

    // public function ujala()
    // {

    //     return view('admin.order.ujala');


    // }
    
    
    
    
    
public function index()
{
    return $this->buildOrderList('admin.order.index', false);
}

public function backendIndex()
{
    return $this->buildOrderList('admin.order.backend_index', true);
}

private function buildOrderList(string $view, bool $backendOnly)
{
    $query = Order::with(['user', 'outstanding', 'deliveries', 'backendSalesOrder'])
        ->where('status', '!=', 'draft')
        ->orderBy('orders.created_at', 'desc');

    if ($backendOnly) {
        $query->whereHas('backendSalesOrder');
    } else {
        $query->whereDoesntHave('backendSalesOrder');
    }

    $orders = $query->get()
        ->filter(function ($order) {
            foreach ($order->deliveries as $delivery) {
                if (in_array($delivery->delivery_status, ['delivered', 'cancelled'])) {
                    return false;
                }
            }
            return true;
        });

    $users = User::select('id', 'name', 'outlet_name')->get();

    foreach ($orders as $order) {
        $outstandings = OutstandingStatement::where('order_id', $order->id)->get();
        $payment = Payment::where('order_id', $order->id)->first();
        $order->payment = $payment;
        $order->has_docs = ($payment && !empty($payment->documents));
        $order->outstandings = $outstandings;

        $orderItemsExist = OrderItem::where('order_id', $order->id)->exists();
        $order->orderItemsExist = $orderItemsExist;

        $deliveriesExist = $order->deliveries->isNotEmpty();
        $order->deliveriesExist = $deliveriesExist;

        if (!$orderItemsExist) {
            DeliveryManagement::where('order_id', $order->id)->update(['delivery_status' => 'cancelled']);
        }

        $deliveryStatuses = [];
        foreach ($order->deliveries as $delivery) {
            $status = $delivery->delivery_status;
            $color = '';
            $fontWeight = '';

            switch ($status) {
                case 'pending': $color = 'orange'; $fontWeight = 'bold'; break;
                case 'in_progress': $color = 'blue'; $fontWeight = 'bold'; break;
                case 'ready_for_dispatch': $color = 'green'; $fontWeight = 'bold'; break;
                case 'delivered': $color = 'purple'; $fontWeight = 'bold'; break;
                case 'cancelled': $color = 'red'; $fontWeight = 'bold'; break;
                default: $color = 'black'; $fontWeight = 'normal';
            }

            $deliveryStatuses[] = [
                'status' => $status,
                'color' => $color,
                'fontWeight' => $fontWeight,
            ];
        }
        $order->deliveryStatuses = $deliveryStatuses;
    }

    $notifications = DB::table('order_notifications')
        ->where('is_read', 0)
        ->get()
        ->keyBy(function ($item) {
            return $item->user_id . '_' . $item->click_url;
        });

    return view($view, compact('orders', 'users', 'notifications'));
}    
    
    
    
    
    
public function cancel_Order($orderId)
{
    DB::beginTransaction();

    try {

        Log::info("Cancel Order Started", ['order_id' => $orderId]);

        $order = Order::with('items.product')->findOrFail($orderId);

        /*
        |--------------------------------------------------------------------------
        | 1. VALIDATION
        |--------------------------------------------------------------------------
        */
        if ($order->status === 'cancelled') {
            throw new \Exception("Order already cancelled.");
        }

        if ($order->status === 'delivered') {
            throw new \Exception("Delivered orders cannot be cancelled.");
        }

        /*
        |--------------------------------------------------------------------------
        | 2. GET PICK LIST
        |--------------------------------------------------------------------------
        */
        $pickLists = PickList::where('order_id', $orderId)->get();

        if ($pickLists->isEmpty()) {
            throw new \Exception("Pick list not found.");
        }

        $productTotals = [];

        /*
        |--------------------------------------------------------------------------
        | 3. RESTORE RACK STOCK
        |--------------------------------------------------------------------------
        */
        foreach ($pickLists as $pick) {

            $racks = RackStock::where('product_id', $pick->product_id)
                ->whereRaw('LOWER(batch_no) = ?', [strtolower($pick->batch_no)])
                ->where('rack_no', $pick->rack_no)
                ->where('level_no', $pick->level_no)
                ->where('slot_no', $pick->slot_no)
                ->lockForUpdate()
                ->get();

            if ($racks->isEmpty()) {
                Log::warning("Rack not found", [
                    'product_id' => $pick->product_id
                ]);
                continue;
            }

            // Restore to first matching rack
            $rack = $racks->first();

            Log::info("Restoring Rack Stock", [
                'product_id' => $pick->product_id,
                'before'     => $rack->quantity,
                'restore'    => $pick->quantity,
                'after'      => $rack->quantity + $pick->quantity
            ]);

            $rack->quantity += $pick->quantity;
            $rack->save();

            // Track total per product
            $productTotals[$pick->product_id] =
                ($productTotals[$pick->product_id] ?? 0) + $pick->quantity;

            /*
            |--------------------------------------------------------------------------
            | STOCK MOVEMENT (IN)
            |--------------------------------------------------------------------------
            */
            StockMovement::create([
                'product_id'     => $pick->product_id,
                'reference_type' => 'ORDER_CANCEL',
                'reference_id'   => $orderId,
                'movement_type'  => 'IN',
                'quantity'       => $pick->quantity,
                'batch_no'       => $pick->batch_no,
                'remarks'        => "Order Cancelled #{$orderId}"
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. RESTORE PRODUCT STOCK
        |--------------------------------------------------------------------------
        */
        foreach ($productTotals as $productId => $qty) {

            $productStock = ProductStock::where('product_id', $productId)
                ->lockForUpdate()
                ->first();

            if ($productStock) {

                Log::info("Restoring Product Stock", [
                    'product_id' => $productId,
                    'before'     => $productStock->total_stock,
                    'restore'    => $qty,
                    'after'      => $productStock->total_stock + $qty
                ]);

                $productStock->total_stock += $qty;
                $productStock->save();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 5. UPDATE DELIVERY STATUS
        |--------------------------------------------------------------------------
        */
        DeliveryManagement::where('order_id', $orderId)
            ->update(['delivery_status' => 'cancelled']);

    
        /*
        |--------------------------------------------------------------------------
        | 7. SEND NOTIFICATION
        |--------------------------------------------------------------------------
        */
        $notificationMessage = "Your order (ID: {$order->order_id}) has been cancelled.";

        $customer = User::find($order->user_id);

        if ($customer) {
            $customer->notify(
                new NewEnqueryRequestCustomerNotification(
                    $customer->id,
                    $notificationMessage
                )
            );
        }

        DB::commit();

        Log::info("Cancel Order Completed", ['order_id' => $orderId]);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled and stock restored successfully'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error("Cancel Order Failed", [
            'order_id' => $orderId,
            'message'  => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
    
      public function docsView($id)
{
    $payment = Payment::where('order_id', $id)->first();

    return view('admin.order.docs', compact('payment'));
}


    public function indexName($id)
    {
        $orders = Order::with(['user', 'deliveries'])
        ->where('outlet_id', $id)
        ->where('payment_status', '!=', 'paid')
        ->whereHas('outstanding', function($query) {
            // $query->where('outstanding_date', '<=', now());
        })
        ->orderBy('created_at', 'desc')
        ->get();

          // Fetch all users to match with outlet_id
          $users = User::select('id', 'name' ,'outlet_name')->get();



        // dd($orders);
        foreach ($orders as $order) {
            $orderItemsExist = OrderItem::where('order_id', $order->id)->exists();
            $outstandings = OutstandingStatement::where('order_id', $order->id)
                // ->where('outstanding_date', '<=', now())
                ->get();
            $order->outstandings = $outstandings;
            $order->orderItemsExist = $orderItemsExist;
            $deliveriesExist = $order->deliveries->isNotEmpty();
            $order->deliveriesExist = $deliveriesExist;
            if (!$orderItemsExist) {
                DeliveryManagement::where('order_id', $order->id)->update(['delivery_status' => 'cancelled']);
            }
            $deliveryStatuses = [];
            foreach ($order->deliveries as $delivery) {
                $status = $delivery->delivery_status;
                $color = '';
                $fontWeight = '';

                switch ($status) {
                    case 'pending':
                        $color = 'orange';
                        $fontWeight = 'bold';
                        break;
                    case 'in_progress':
                        $color = 'blue';
                        $fontWeight = 'bold';
                        break;
                    case 'ready_for_dispatch':
                        $color = 'green';
                        $fontWeight = 'bold';
                        break;
                    case 'delivered':
                        $color = 'purple';
                        $fontWeight = 'bold';
                        break;
                    case 'cancelled':
                        $color = 'red';
                        $fontWeight = 'bold';
                        break;
                    default:
                        $color = 'black';
                        $fontWeight = 'normal';
                }

                $deliveryStatuses[] = [
                    'status' => $status,
                    'color' => $color,
                    'fontWeight' => $fontWeight
                ];
            }
            $order->deliveryStatuses = $deliveryStatuses;
        }

        return view('admin.order.index', compact('orders' ,'users'));
    }




    public function indexID($id)
    {
        // $orders = Order::where('outlet_id', $id)
        //                 ->orderBy('created_at', 'desc')
        //                 ->get();


                        $orders = Order::with(['user', 'deliveries'])
                        ->where('outlet_id', $id)
                        ->where('payment_status', 'unpaid')
                        ->whereHas('outstanding', function($query) {
                            $query->where('outstanding_date', '<=', now());
                        })
                        ->orderBy('created_at', 'desc')
                        ->get();
                
                          // Fetch all users to match with outlet_id
                          $users = User::select('id', 'name' ,'outlet_name')->get();

                
                

        foreach ($orders as $order) {
            $orderItemsExist = OrderItem::where('order_id', $order->id)->exists();
            $order->orderItemsExist = $orderItemsExist;
            $deliveriesExist = $order->deliveries->isNotEmpty();
            $order->deliveriesExist = $deliveriesExist;
            if (!$orderItemsExist) {
                DeliveryManagement::where('order_id', $order->id)->update(['delivery_status' => 'cancelled']);
            }
            $deliveryStatuses = [];
            foreach ($order->deliveries as $delivery) {
                $status = $delivery->delivery_status;
                $color = '';
                $fontWeight = '';

                switch ($status) {
                    case 'pending':
                        $color = 'orange';
                        $fontWeight = 'bold';
                        break;
                    case 'in_progress':
                        $color = 'blue';
                        $fontWeight = 'bold';
                        break;
                    case 'ready_for_dispatch':
                        $color = 'green';
                        $fontWeight = 'bold';
                        break;
                    case 'delivered':
                        $color = 'purple';
                        $fontWeight = 'bold';
                        break;
                    case 'cancelled':
                        $color = 'red';
                        $fontWeight = 'bold';
                        break;
                    default:
                        $color = 'black';
                        $fontWeight = 'normal';
                }

                $deliveryStatuses[] = [
                    'status' => $status,
                    'color' => $color,
                    'fontWeight' => $fontWeight
                ];
            }
            $order->deliveryStatuses = $deliveryStatuses;
        }

        return view('admin.order.index', compact('orders','users'));
    }




    public function invoiceList()
    {
        $orders = Order::with(['user', 'outstanding','deliveries'])
                ->orderBy('created_at', 'desc')
                ->get();

        foreach ($orders as $order) {
            $outstandings = OutstandingStatement::where('order_id', $order->id)->get();
            $order->outstandings = $outstandings;
        }

        return view('admin.invoice.list', compact('orders'));
    }








    // public function edit($id)
    // {
    //     $orders = Order::find($id);
    //     $payment_detail = Payment::where('order_id', $id)->first();
    //     // dd($payment_detail);
    //     return view('admin.order.edit')->with(compact('orders','payment_detail'));
    // }
    
     public function edit($id)
    {
     $order = Order::find($id);
     $from  = request()->query('from', 'orders');
      $payment = Payment::firstOrCreate(
            ['order_id' => $order->id],
            [
            'user_id' => $order->user_id,
            'outlet_id' => $order->outlet_id,
            'total_amount' => $order->total_discount_value,
            'total_paid' => 0,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status ?? 'unpaid',
            'payment_id' => 'PAY-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
            ]
            );


            $histories = $payment->histories()->latest()->get();
        // dd($payment_detail);
        return view('admin.order.edit')->with(compact('order','payment','histories','from'));
    }


    // public function update(Request $request, $id)
    // {
    //     $order = Order::findOrFail($id);

    //     $validatedData = $request->validate([
    //         'payment_status' => 'required|in:paid,unpaid',
    //     ]);


    //     $order->payment_status = $validatedData['payment_status'];
    //     $order->payment_method = $request->payment_method;
    //     $order->save();

    //     $payment = new Payment();
    //     $payment->user_id = $request->user_id;
    //     $payment->Outlet_id = $request->outlet_id;
    //     $payment->Order_id = $request->order_id;
    //     $payment->payment_id = 'PAY-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
    //     $payment->Paid_amount = $request->paid_amount;
    //     $payment->payment_mode = $request->payment_mode;
    //     $payment->paid_to = $request->paid_to;
    //     // dd($payment);
    //     // exit;

    //     $payment->save();

    //     if ($validatedData['payment_status'] === 'paid') {
    //         OutstandingStatement::where('order_id', $id)->delete();
    //     }

    //     return redirect()->route('order.detailsid',[$order->outlet_id])->with('success', 'Order details updated successfully');
    // }
    
//     public function update(Request $request, $id)
// {
//     $order = Order::findOrFail($id);

//     $validatedData = $request->validate([
//         'payment_status' => 'required|in:paid,unpaid',
//     ]);

//     // Update order payment status & method
//     $order->payment_status = $validatedData['payment_status'];
//     $order->payment_method = $request->payment_method;
//     $order->save();

//     // Check if payment record already exists
//     $payment = Payment::where('order_id', $request->order_id)->first();

//     if ($payment) {
//         // Update existing payment record
//         $payment->user_id = $request->user_id;
//         $payment->outlet_id = $request->outlet_id;
//         $payment->paid_amount = $request->paid_amount;
//         $payment->payment_mode = $request->payment_mode;
//         $payment->paid_to = $request->paid_to;
//         $payment->save();
//     } else {
//         // Create a new payment record (only if no existing record is found)
//         $payment = new Payment();
//         $payment->user_id = $request->user_id;
//         $payment->outlet_id = $request->outlet_id;
//         $payment->order_id = $request->order_id;
//         $payment->payment_id = 'PAY-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
//         $payment->paid_amount = $request->paid_amount;
//         $payment->payment_mode = $request->payment_mode;
//         $payment->paid_to = $request->paid_to;
//         $payment->save();
//     }

//     // If payment status is 'paid', delete from OutstandingStatement
//     if ($validatedData['payment_status'] === 'paid') {
//         OutstandingStatement::where('order_id', $id)->delete();
//     }

//     return redirect()->route('order.details')
//                      ->with('success', 'Order details updated successfully');
// }



// public function update(Request $request, $id)
// {
//     $order = Order::findOrFail($id);

//     $validatedData = $request->validate([
//         'payment_status' => 'required|in:paid,unpaid,partial',
//         'documents.*'    => 'mimes:jpg,jpeg,png,pdf|max:4096',
//     ]);

//     // Update order table
//     $order->payment_status = $validatedData['payment_status'];
//     $order->payment_method = $request->payment_method;
//     $order->save();

   
//     $payment = Payment::firstOrCreate(
//         ['order_id' => $request->order_id],
//         [
//             'user_id'     => $request->user_id,
//             'outlet_id'   => $request->outlet_id,
//             'paid_amount' => $request->paid_amount,   
//             'payment_mode'=> $request->payment_mode,
//             'paid_to'     => $request->paid_to,
//             'amount_paid' => $request->amount_paid,
//             'payment_id'  => 'PAY-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
//         ]
//     );

    
//     $payment->user_id      = $request->user_id;
//     $payment->outlet_id    = $request->outlet_id;
//     $payment->paid_amount  = $request->paid_amount;  
//     $payment->payment_mode = $request->payment_mode;
//     $payment->paid_to      = $request->paid_to;
//     $payment->amount_paid      = $request->amount_paid;


  
//     $uploadedDocs = [];

//     if ($request->hasFile('documents')) {
//         foreach ($request->file('documents') as $file) {
//             $name = time() . '_' . $file->getClientOriginalName();
//             $file->move(public_path('uploads/payment_docs'), $name);
//             $uploadedDocs[] = $name;
//         }
//     }

//     if ($uploadedDocs) {
//         $existingDocs = $payment->documents ?? [];
//         $payment->documents = array_merge($existingDocs, $uploadedDocs);
//     }

//     $payment->save();

//     // if full paid → remove outstanding
//     if ($validatedData['payment_status'] === 'paid') {
//         OutstandingStatement::where('order_id', $id)->delete();
//     }

//     return redirect()->route('order.details')
//                      ->with('success', 'Order details updated successfully');
// }


public function update(Request $request, $orderId)
{
    
$order = Order::findOrFail($orderId);


$validated = $request->validate([
'amount_paid' => 'nullable|numeric|min:0',
'payment_mode' => 'nullable|string',
'reference' => 'required|string|max:255',
'documents.*' => 'nullable|mimes:jpg,jpeg,png,pdf|max:4096',
'payment_status' => 'nullable|in:paid,partial,unpaid'
]);


$payment = Payment::firstOrCreate(
    ['order_id' => $order->id],

    [
        'user_id'       => $order->user_id,
        'outlet_id'     => $order->outlet_id,
        'total_amount'  => $order->total_discount_value,
        'total_paid'    => 0,
        'payment_method'=> $order->payment_method,
        'payment_status'=> $order->payment_status,
        'payment_id'    => 'PAY-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
    ]
);



$amount = round((float) ($validated['amount_paid'] ?? 0), 2);

$remaining = round($payment->total_amount - $payment->total_paid, 2);

if ($amount > $remaining) {
    return back()->withErrors([
        'amount_paid' => 'Amount cannot exceed remaining balance: ₹' . number_format($remaining, 2)
    ])->withInput();
}

$historyDocs = [];


if ($request->hasFile('documents')) {
foreach ($request->file('documents') as $file) {
$name = time() . '_' . $file->getClientOriginalName();
$file->move(public_path('uploads/payment_docs'), $name);
$historyDocs[] = $name;
}
}


if ($amount > 0) {
PaymentHistory::create([
'payment_id' => $payment->id,
'reference' => $request->reference,
'paid_amount' => $amount,
'payment_mode' => $request->payment_mode,
'source'       => 'backend',
'paid_to' => $request->paid_to ?? null,
'documents' => $historyDocs ?: null,
]);


$payment->total_paid = (float) $payment->total_paid + $amount;
}


// Optionally allow admin to set status manually (but prefer auto)
if ($request->filled('payment_status')) {
$payment->payment_status = $request->payment_status;
} else {
// auto determine
if ($payment->total_paid >= (float) $payment->total_amount) {
$payment->payment_status = 'paid';
OutstandingStatement::where('order_id', $order->id)->delete();
} elseif ($payment->total_paid > 0) {
$payment->payment_status = 'partial';
} else {
$payment->payment_status = 'unpaid';
}
}


// update other fields
$payment->payment_method = $request->payment_method ?? $payment->payment_method;
$payment->save();


// mirror back to order
$order->payment_status = $payment->payment_status;
$order->save();

$from = $request->input('from', 'orders');

// if ($from === 'delivery') {
//         return redirect()->route('delivery.index')
//                          ->with('success', 'Payment updated successfully');
//     } elseif ($from === 'update_payments') {
//         return redirect()->route('payments.update_payments')
//                          ->with('success', 'Payment updated successfully');
//     }

//     return redirect()->route('order.details')
//                      ->with('success', 'Payment updated successfully');

return redirect()->route('order.edit', [
    'id' => $order->id,
    'from' => $from
])->with('success', 'Payment updated successfully');

// return redirect()->route('order.details')->with('success', 'Payment updated successfully');
}







//     public function invoiceID($id)
//     {
//       $orderInvoice = Order::join('outstanding_statements', 'orders.id', '=', 'outstanding_statements.order_id')
//     ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id') 
//     ->where('orders.outlet_id', $id)
//     ->where(function ($query) {
//         $query->where(function ($q) {
//             $q->where('orders.payment_status', 'unpaid');
              
//         });
        
//     })
//     // ->where('outstanding_date', '<=', now())
//     ->orderBy('orders.created_at', 'desc')
//     ->get();

// // Debugging
// // dd($orderInvoice);

        
//         // dd($orderInvoice);

//       // Retrieve credit_limit, location, and mobile_number
// $userData = User::where('id', $id)
//     ->where('credit_status', 'Active')
//     ->select('credit_limit', 'location', 'mobile_number' ,'due_days_limit')
//     ->first();

// $creditLimit = $userData->credit_limit ?? 0;
// $location = $userData->location ?? 'N/A';
// $mobileNumber = $userData->mobile_number ?? 'N/A';
// $name = $userData->name ?? 'N/A';
// $outletname = $userData->outlet_name ?? 'N/A';
// $due_days_limit = $userData->due_days_limit ?? '0';



        
//                         $orderss = KYCDocument::where('user_id', $id)
//                         ->get();

//                         $outstandingList = OutstandingStatement::
//                             select(
//                                 'outstanding_statements.outlet_id',
//                                 \DB::raw('SUM(outstanding_statements.total_due_amount) AS total_due_amount'),
//                                 \DB::raw('COUNT(*) AS num_statements'),
//                                 'users.name as user_name',
//                                 \DB::raw('MAX(outstanding_statements.created_at) AS latest_created_at')
//                             )
//                             ->join('users', 'outstanding_statements.user_id', '=', 'users.id')
//                             ->groupBy('outstanding_statements.outlet_id', 'users.name')
//                             ->orderBy('latest_created_at', 'desc')
//                             ->get();
// // dd($orderInvoice);

//         // dd($orderss);
//         // return view('admin.order.invoice1', compact('orderInvoice', 'orderss', 'outstandingList'));


//         $pdf = PDF::loadView('admin.order.invoice1', compact('orderInvoice', 'orderss', 'outstandingList','creditLimit','location','mobileNumber','due_days_limit'));
//         return $pdf->stream('outstandingdetails.pdf');



//     }


// public function invoiceID($id)
// {
//     $orderInvoice = Order::join('outstanding_statements', 'orders.id', '=', 'outstanding_statements.order_id')
//         ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
//         ->where('orders.outlet_id', $id)
//         ->where(function ($query) {
//             $query->where('orders.payment_status', 'unpaid');
//         })
//         ->where('delivery_management.delivery_status', '!=', 'cancelled') // Exclude cancelled orders
//         ->orderBy('orders.created_at', 'asc')
//         ->get();

//     // Retrieve credit_limit, location, and mobile_number
//     $userData = User::where('id', $id)
//         ->where('credit_status', 'Active')
//         ->select('credit_limit', 'location', 'mobile_number', 'due_days_limit', 'name', 'outlet_name')
//         ->first();

//     $creditLimit = $userData->credit_limit ?? 0;
//     $location = $userData->location ?? 'N/A';
//     $mobileNumber = $userData->mobile_number ?? 'N/A';
//     $name = $userData->name ?? 'N/A';
//     $outletname = $userData->outlet_name ?? 'N/A';
//     $due_days_limit = $userData->due_days_limit ?? '0';

//     // Fetch KYC documents
//     $orderss = KYCDocument::where('user_id', $id)->get();

//     // Fetch outstanding list
//     $outstandingList = OutstandingStatement::select(
//             'outstanding_statements.outlet_id',
//             \DB::raw('SUM(outstanding_statements.total_due_amount) AS total_due_amount'),
//             \DB::raw('COUNT(*) AS num_statements'),
//             'users.name as user_name',
//             \DB::raw('MAX(outstanding_statements.created_at) AS latest_created_at')
//         )
//         ->join('users', 'outstanding_statements.user_id', '=', 'users.id')
//         ->groupBy('outstanding_statements.outlet_id', 'users.name')
//         ->orderBy('latest_created_at', 'desc')
//         ->get();
        
//         $paymentTerm = OutletPaymentTerm::where('user_id', $id)
//       ->where('is_active', 1)
//       ->first();

//         $hasNewPaymentTerm = $paymentTerm ? true : false;

//     // Generate PDF
//     $pdf = PDF::loadView('admin.order.invoice1', compact(
//         'orderInvoice', 'orderss', 'outstandingList', 'creditLimit', 'location', 'mobileNumber', 'due_days_limit','paymentTerm','hasNewPaymentTerm'
//     ));

//     return $pdf->stream('outstandingdetails.pdf');
// }


public function invoiceID($id)
{
    $orderInvoice = Order::join('outstanding_statements', 'orders.id', '=', 'outstanding_statements.order_id')
        ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
        ->where('orders.outlet_id', $id)
        ->whereIn('orders.payment_status', ['unpaid', 'partial']) 
        ->where('delivery_management.delivery_status', 'delivered')
        // ->where('delivery_management.delivery_status', '!=', 'cancelled')
        ->orderBy('orders.created_at', 'asc')
         ->select('orders.*') 
        ->get();

        $orderInvoice->transform(function ($order) {

        $payment = Payment::where('order_id', $order->id)->first();

        $order->total_amount     = $order->total_discount_value;
        $order->total_paid       = $payment->total_paid ?? 0;
        $order->balance_amount   = $order->total_amount - $order->total_paid;
        
        
        
       if ($order->payment_method === 'special_credit') {

    $dairyTerm = DairyPaymentTerm::where('user_id', $order->outlet_id)
        ->where('is_active', 1)
        ->first();

    if ($dairyTerm && $dairyTerm->due_limit_days !== null) {
        $order->custom_due_days = (int) $dairyTerm->due_limit_days;
    }
}
       
    


        return $order;
    });
    
    // dd($orderInvoice);

    // Retrieve credit_limit, location, and mobile_number
    $userData = User::where('id', $id)
        ->where('credit_status', 'Active')
        ->select('credit_limit', 'location', 'mobile_number', 'due_days_limit', 'name', 'outlet_name' ,'priority')
        ->first();
        
       $company_name1 = 'N/A';

if ($userData && $userData->priority) {
    $company = User::where('id', $userData->priority)
        ->select('outlet_name')
        ->first();

    $company_name1 = $company->outlet_name ?? 'N/A';
}     
                    
                    // dd($userData);
                    
    $company_name1 = $company_name->outlet_name ?? 'N/A';                 

    $creditLimit = $userData->credit_limit ?? 0;
    $location = $userData->location ?? 'N/A';
    $mobileNumber = $userData->mobile_number ?? 'N/A';
    $name = $userData->name ?? 'N/A';
    $outletname = $userData->outlet_name ?? 'N/A';
    $due_days_limit = $userData->due_days_limit ?? '0';

    // Fetch KYC documents
    $orderss = KYCDocument::where('user_id', $id)->get();

    // Fetch outstanding list
    $outstandingList = OutstandingStatement::select(
            'outstanding_statements.outlet_id',
            \DB::raw('SUM(outstanding_statements.total_due_amount) AS total_due_amount'),
            \DB::raw('COUNT(*) AS num_statements'),
            'users.name as user_name',
            \DB::raw('MAX(outstanding_statements.created_at) AS latest_created_at')
        )
        ->join('users', 'outstanding_statements.user_id', '=', 'users.id')
        ->groupBy('outstanding_statements.outlet_id', 'users.name')
        ->orderBy('latest_created_at', 'desc')
        ->get();
        
        $paymentTerm = OutletPaymentTerm::where('user_id', $id)
       ->where('is_active', 1)
       ->first();

        $hasNewPaymentTerm = $paymentTerm ? true : false;

    // Generate PDF
    $pdf = PDF::loadView('admin.order.invoice1', compact(
        'orderInvoice', 'orderss', 'outstandingList', 'creditLimit', 'location', 'mobileNumber', 'due_days_limit','paymentTerm','hasNewPaymentTerm','company_name1',
    ));

    return $pdf->stream('outstandingdetails.pdf');
}


public function over_due($id)
{
    $orderInvoice = Order::join('outstanding_statements', 'orders.id', '=', 'outstanding_statements.order_id')
        ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
        ->where('orders.outlet_id', $id)
        ->whereIn('orders.payment_status', ['unpaid', 'partial']) 
        ->where('delivery_management.delivery_status', 'delivered')
        // ->where('delivery_management.delivery_status', '!=', 'cancelled')
        ->orderBy('orders.created_at', 'asc')
         ->select('orders.*') 
        ->get();

        $orderInvoice->transform(function ($order) {

        $payment = Payment::where('order_id', $order->id)->first();

        $order->total_amount     = $order->total_discount_value;
        $order->total_paid       = $payment->total_paid ?? 0;
        $order->balance_amount   = $order->total_amount - $order->total_paid;
        
          if ($order->payment_method === 'special_credit') {

        $dairyTerm = DairyPaymentTerm::where('user_id', $order->outlet_id)
            ->where('is_active', 1)
            ->first();

        if ($dairyTerm && $dairyTerm->due_limit_days !== null) {
            $order->custom_due_days = (int) $dairyTerm->due_limit_days;
        }
    }

        return $order;
    });
    

    // Retrieve credit_limit, location, and mobile_number
    $userData = User::where('id', $id)
        ->where('credit_status', 'Active')
        ->select('credit_limit', 'location', 'mobile_number', 'due_days_limit', 'name', 'outlet_name' ,'priority')
        ->first();
        
       $company_name1 = 'N/A';

if ($userData && $userData->priority) {
    $company = User::where('id', $userData->priority)
        ->select('outlet_name')
        ->first();

    $company_name1 = $company->outlet_name ?? 'N/A';
}     
                    
                    // dd($userData);
                    
    $company_name1 = $company_name->outlet_name ?? 'N/A';                 

    $creditLimit = $userData->credit_limit ?? 0;
    $location = $userData->location ?? 'N/A';
    $mobileNumber = $userData->mobile_number ?? 'N/A';
    $name = $userData->name ?? 'N/A';
    $outletname = $userData->outlet_name ?? 'N/A';
    $due_days_limit = $userData->due_days_limit ?? '0';

    // Fetch KYC documents
    $orderss = KYCDocument::where('user_id', $id)->get();

    // Fetch outstanding list
    $outstandingList = OutstandingStatement::select(
            'outstanding_statements.outlet_id',
            \DB::raw('SUM(outstanding_statements.total_due_amount) AS total_due_amount'),
            \DB::raw('COUNT(*) AS num_statements'),
            'users.name as user_name',
            \DB::raw('MAX(outstanding_statements.created_at) AS latest_created_at')
        )
        ->join('users', 'outstanding_statements.user_id', '=', 'users.id')
        ->groupBy('outstanding_statements.outlet_id', 'users.name')
        ->orderBy('latest_created_at', 'desc')
        ->get();
        
        $paymentTerm = OutletPaymentTerm::where('user_id', $id)
       ->where('is_active', 1)
       ->first();

        $hasNewPaymentTerm = $paymentTerm ? true : false;

    // Generate PDF
    $pdf = PDF::loadView('admin.order.over_due', compact(
        'orderInvoice', 'orderss', 'outstandingList', 'creditLimit', 'location', 'mobileNumber', 'due_days_limit','paymentTerm','hasNewPaymentTerm','company_name1',
    ));

    return $pdf->stream('over_due_outstandingdetails.pdf');
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

     // Set margins
    $pdf->setOptions([
    'margin_top' => 10,
    'margin_right' => 10,
    'margin_bottom' => 10,
    'margin_left' => 10,
    ]);

      return $pdf->stream('delivery_charges.pdf');

      // return view('admin.invoice.delivery_charges', compact('orderInvoice' ,'orderss', 'orders', 'orderNoInvoice', 'orderInvoice1', 'lastpayment'));
    }

public function numberToWords($number)
    {
        // Split the number into integer and decimal parts
        $integerPart = floor($number);
        $decimalPart = round(($number - $integerPart) * 100);

        // Convert the integer part to words
        $numberFormatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
        $integerWords = ucfirst($numberFormatter->format($integerPart));

        // Convert the decimal part to words
        $decimalWords = '';
        if ($decimalPart > 0) {
            $decimalWords = ' point ' . $numberFormatter->format($decimalPart);
        }

        // Combine integer and decimal parts
        return $integerWords . $decimalWords;
    }

}
