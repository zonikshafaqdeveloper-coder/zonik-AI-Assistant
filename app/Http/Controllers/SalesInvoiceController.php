<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Pincode;
use App\Models\Holiday;
use App\Models\Order;
use App\Models\DeliveryManagement;
use App\Models\OutstandingStatement;
use App\Models\OrderItem;
use App\Models\CustomerPriceChangeLog;
use App\Models\OrderNotification;
use App\Notifications\NewEnqueryRequestCustomerNotification;
use App\Models\ZoneProcessing;
use App\Models\CustomerPrice;
use App\Models\Payment;
use App\Models\OriginalItem;
use App\Models\BackendSalesOrder;
use Carbon\Carbon;
use App\Services\SmsService;
use App\Models\ProductStock;
use App\Models\DairyPaymentTerm;
use App\Models\PreMaterialShortLog;


class SalesInvoiceController extends Controller
{

  public function index()
{
    $orders = BackendSalesOrder::with([
        'customer',
        'order.outlet',
        'order.latestDelivery'
    ])
    ->latest()
    ->get();

    // dd($orders);

    return view('admin.sales_invoices.index', compact('orders'));
}
  public function invoice_create()
    {

        $customers = User::where('type', 'group')
        ->where('status', 'Active')
        ->select('id', 'name', 'outlet_name' )
        ->get();
        
        $outlets = User::where('type', 'outlet')
        ->where('status', 'Active')
        ->select('id', 'outlet_name')
        ->orderBy('outlet_name')
        ->get();

        return view('admin.sales_invoices.create' , compact('customers','outlets'));
     }

     public function show($id)
    {
      

        $outlet = BackendSalesOrder::findOrFail($id);
        
        $order = Order::with([
            'outlet',
            'user',
            'mainuser',
            'orderItems.product'
        ])->findOrFail($outlet->order_id);

        // dd($order);

        return view('admin.sales_invoices.show', compact('order'));
    }


public function getOutletsByCustomer($customerId)
{
    $outlets = User::where('type', 'outlet')
        ->where('priority', $customerId)
        ->where('status', 'Active')
        ->select('id', 'outlet_name')
        ->orderBy('outlet_name')
        ->get();

    //   dd($outlets->toArray());


    return response()->json($outlets);
}



  public function getOutletDetails($outletId)
      {
        $outlet = User::findOrFail($outletId);

        $kyc = DB::table('k_y_c_documents')
        ->where('user_id', $outletId)
        ->select('outlet_address', 'outlet_pincode')
        ->first();
        
         $customer = User::find($outlet->priority);

        return response()->json([
        'customer_id'   => $customer->id ?? null,
        'customer_name' => $customer->name ?? '',
        'company_name'  => $customer->outlet_name ?? '',
        'location'     => $kyc->outlet_address ?? '',
        'pincode'      => $kyc->outlet_pincode ?? '',
        'verified_status' => $outlet->verified_status ?? 'unverified',
        ]);
      }

      public function getCustomerCredit($customerId)
{
    $customer = User::select(
            'id',
            'credit_status',
            'credit_limit'
        )
        ->where('id', $customerId)
        ->where('status', 'Active')
        ->first();

    if (!$customer) {
        return response()->json([
            'credit_status' => 'Inactive',
            'credit_limit'  => 0
        ]);
    }
    
     $specialCredit = DairyPaymentTerm::where('user_id', $customerId)
        ->where('is_active', 1)
        ->exists();

    return response()->json([
        'credit_status' => $customer->credit_status ?? 'Inactive',
        'credit_limit'  => (float) ($customer->credit_limit ?? 0),
        'special_credit'    => $specialCredit
    ]);
}

      public function getProducts()
    {
        $products = Product::where('status', 'active')
            ->select('id', 'product_name', 'cost_per_item','carton_size','total_discount','cgst','sgst','product_mrp')
            ->orderBy('product_name')
            ->get();

        

        return response()->json($products);
    }
    
// comment on 02-04-26    
//       public function getProductsByCustomer($customerId, $outletId)
// {
//     $products = Product::where('status', 'active')
//         ->whereIn('id', function ($query) use ($customerId, $outletId) {

//             $query->select('product_id')
//                 ->from('customer_prices')
//                 ->where('customer_id', $customerId)
//                 ->where('outlet_id', $outletId)

//                 ->union(

//                     \DB::table('enquiries')
//                         ->select('product_id')
//                         ->where('user_id', $customerId)
//                         ->where('status', 'accept')
//                 );
//         })
//         ->select(
//             'id',
//             'product_name',
//             'cost_per_item',
//             'carton_size',
//             'total_discount',
//             'cgst',
//             'sgst',
//             'product_mrp'
//         )
//         ->orderBy('product_name')
//         ->get();

//         // dd($products);

//     return response()->json($products);
// }

// public function getProductsByCustomer($customerId, $outletId)
// {
//     $products = Product::where('products.status', 'active')

//         ->whereIn('products.id', function ($query) use ($customerId, $outletId) {

//             $query->select('product_id')
//                 ->from('customer_prices')
//                 ->where('customer_id', $customerId)
//                 ->where('outlet_id', $outletId)

//                 ->union(
//                     \DB::table('enquiries')
//                         ->select('product_id')
//                         ->where('user_id', $customerId)
//                         ->where('status', 'accept')
//                 );
//         })

//         ->leftJoin('product_stocks', 'products.id', '=', 'product_stocks.product_id')

//         ->select(
//             'products.id',
//             'products.product_name',
//             'products.cost_per_item',
//             'products.carton_size',
//             'products.total_discount',
//             'products.cgst',
//             'products.sgst',
//             'products.product_mrp',
//             \DB::raw('COALESCE(product_stocks.total_stock, 0) as stock')
//         )

//         ->orderBy('products.product_name')
//         ->get();

//     return response()->json($products);
// }



public function getProductsByCustomer($customerId, $outletId)
{
    // Get product IDs from customer_prices
    $customerPriceIds = DB::table('customer_prices')
        ->where('customer_id', $customerId)
        ->where('outlet_id', $outletId)
        ->pluck('product_id')
        ->toArray();

    // Get product IDs from enquiries
    $enquiryIds = DB::table('enquiries')
        ->where('user_id', $customerId)
        ->where('status', 'accept')
        ->pluck('product_id')
        ->toArray();

    // Merge and unique
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

public function getDeliveryCharges($pincode)
{
   
    $pincodeData = Pincode::where('pincode', $pincode)->first();

    $zone = ZoneProcessing::where('id', $pincodeData->zone_id)
        ->where('status', 'Active')
        ->first();

    return response()->json([
        'success' => true,
        'bulk_delivery_charges'   => $zone->bulk_delivery_charges ?? 0,
        'single_delivery_charges' => $zone->single_delivery_charges ?? 0,
        'packing_charges'         => $zone->packing_charge ?? 0,
        'other_charges'           => $zone->others_charges ?? 0,
    ]);
}

 
public function getDeliverySlots($pincode)
{
    $holidays = Holiday::all();
    $pincodeData = Pincode::where('pincode', $pincode)->first();

    if (!$pincodeData || !$pincodeData->zone_id) {
        return response()->json([
            'success' => false,
            'not_servicable' => true,
            'message' => 'Service not available on this location.'
        ]);
    }

    $zoneProcessingData = ZoneProcessing::find($pincodeData->zone_id);

    if (!$zoneProcessingData || $zoneProcessingData->status !== 'Active') {
        return response()->json([
            'success' => false,
            'not_servicable' => true,
            'message' => 'Service not available on this location.'
        ]);
    }


    $deliveryOptions = [];
    
    //     if ($zoneProcessingData->regular_days) {

    //     $today = Carbon::today();
    //     $weekDaySlot = $zoneProcessingData->week_day_slot;

    //     $daysPrinted = 0;
    //     $startDate = $today->copy();

    //     while ($daysPrinted < 7) {

    //         $date = $startDate->toDateString();
    //         $isHoliday = $holidays->contains('holiday_date', $date);

    //         if (!$isHoliday) {
    //             $deliveryOptions[] = [
    //                 'date' => $date,
    //                 'slot' => Carbon::parse($date)->format('jS M y') . " - {$weekDaySlot}",
    //                 'time_only' => $weekDaySlot
    //             ];
    //             $daysPrinted++;
    //         }

    //         $startDate->addDay();
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'delivery_options' => $deliveryOptions
    //     ]);
    // }
    
    
    if ($zoneProcessingData->regular_days) {

        $weekDaySlot = $zoneProcessingData->week_day_slot;
        $deliveryDays = $zoneProcessingData->delivery_days;

        $dayMap = [
            'sunday'    => 0,
            'monday'    => 1,
            'tuesday'   => 2,
            'wednesday' => 3,
            'thursday'  => 4,
            'friday'    => 5,
            'saturday'  => 6,
        ];

        $tomorrow = Carbon::tomorrow();
        $daysPrinted = 0;
        $startDate = $tomorrow->copy();

        if (!empty($deliveryDays)) {
            $allowedDayNumbers = array_map(fn($d) => $dayMap[strtolower($d)], $deliveryDays);

            while ($daysPrinted < count($deliveryDays)) { 
                $date = $startDate->toDateString();
                $isHoliday = $holidays->contains('holiday_date', $date);
                $dayOfWeek = $startDate->dayOfWeek;

                if (!$isHoliday && in_array($dayOfWeek, $allowedDayNumbers)) {
                    $deliveryOptions[] = [
                        'date'      => $date,
                        'slot'      => Carbon::parse($date)->format('jS M y') . " - {$weekDaySlot} (" .Carbon::parse($date)->format('l') . ")",
                        'time_only' => $weekDaySlot
                    ];
                    $daysPrinted++;
                }

                $startDate->addDay();

               
                if ($startDate->diffInDays($tomorrow) > 14) {
                    break;
                }
            }

        } else {
            $startDate = Carbon::today();

            while ($daysPrinted < 7) {
                $date = $startDate->toDateString();
                $isHoliday = $holidays->contains('holiday_date', $date);

                if (!$isHoliday) {
                    $deliveryOptions[] = [
                        'date'      => $date,
                        'slot'      => Carbon::parse($date)->format('jS M y') . " - {$weekDaySlot} (" .Carbon::parse($date)->format('l') . ")",
                        'time_only' => $weekDaySlot
                    ];
                    $daysPrinted++;
                }

                $startDate->addDay();
            }
        }

        return response()->json([
            'success'          => true,
            'delivery_options' => $deliveryOptions
        ]);
    }

$now              = Carbon::now();
$today            = Carbon::today();
$tomorrow         = Carbon::tomorrow();

$morningCutoff    = Carbon::parse($zoneProcessingData->same_day_timing);      
$afternoonCutoff  = Carbon::parse($zoneProcessingData->next_day_timing);      

$slot1Time = $zoneProcessingData->next_day_slot;   
$slot2Time = $zoneProcessingData->same_day_slot; 
$weekDaySlot = $zoneProcessingData->week_day_slot;


// ----------------------------------------------------
//               APPLY NEW DELIVERY LOGIC
// ----------------------------------------------------
if ($now->lt($morningCutoff)) {

    // CASE 1: Before morning cutoff → Both same day
    $deliveryOptions[] = [
        'date' => $today->toDateString(),
        'slot' => "Slot 1 : " . $today->format('jS M y') . " - {$slot1Time}",
        'time_only' => $slot1Time
    ];

    $deliveryOptions[] = [
        'date' => $today->toDateString(),
        'slot' => "Slot 2 : " . $today->format('jS M y') . " - {$slot2Time}",
        'time_only' => $slot2Time
    ];

    $slot1Date = $today->copy();

}
elseif ($now->gte($morningCutoff) && $now->lt($afternoonCutoff)) {

    // CASE 2: After morning cutoff but before afternoon cutoff
    // Slot 1 → tomorrow
    $deliveryOptions[] = [
        'date' => $tomorrow->toDateString(),
        'slot' => "Slot 1 : " . $tomorrow->format('jS M y') . " - {$slot1Time}",
        'time_only' => $slot1Time
    ];

    // Slot 2 → today
    $deliveryOptions[] = [
        'date' => $today->toDateString(),
        'slot' => "Slot 2 : " . $today->format('jS M y') . " - {$slot2Time}",
        'time_only' => $slot2Time
    ];

    $slot1Date = $tomorrow->copy();
}
else {

    // CASE 3: After afternoon cutoff → All next day
    $deliveryOptions[] = [
        'date' => $tomorrow->toDateString(),
        'slot' => "Slot 1 : " . $tomorrow->format('jS M y') . " - {$slot1Time}",
        'time_only' => $slot1Time
    ];

    $deliveryOptions[] = [
        'date' => $tomorrow->toDateString(),
        'slot' => "Slot 2 : " . $tomorrow->format('jS M y') . " - {$slot2Time}",
        'time_only' => $slot2Time
    ];

    $slot1Date = $tomorrow->copy();
}


// ----------------------------------------------------
//                REST OF THE WEEK SLOTS
// ----------------------------------------------------

$daysPrinted = 0;
$startDate   = $slot1Date->copy()->addDay();

while ($daysPrinted < 7) {

    $date = $startDate->toDateString();
    $isHoliday = $holidays->contains('holiday_date', $date);

    if (!$isHoliday) {
        $deliveryOptions[] = [
            'date' => $date,
            'slot' => Carbon::parse($date)->format('jS M y') . " - {$weekDaySlot} (" .Carbon::parse($date)->format('l') . ")",
            'time_only' => $weekDaySlot
        ];
        $daysPrinted++;
    }

    $startDate->addDay();
}


    return response()->json([
        'success' => true,
        'delivery_options' => $deliveryOptions
    ]);
}


 public function getCustomerProductPrice($customerId, $outletId, $productId)
{
    
    $price = CustomerPrice::where('customer_id', $customerId)
        ->where('outlet_id', $outletId)
        ->where('product_id', $productId)
        ->value('product_price');

    
    if ($price === null) {
        $price = \DB::table('enquiries')
            ->where('user_id', $customerId)
            ->where('product_id', $productId)
            ->where('status', 'accept')
            ->value('offer_price');
    }

    return response()->json([
        'product_price' => $price
    ]);
}


public function checkPriceChangeLog($productId)
{

    $pending = CustomerPriceChangeLog::where('product_id', $productId)
        ->where('status', 'pending')
        ->exists();

        return response()->json([
        'pending' => $pending
    ]);
}



protected function handleStockShortage($order)
{
    \Log::info('Entered handleStockShortage', ['order_id' => $order->id]);

    PreMaterialShortLog::where('order_id', $order->id)->delete();

    $stocks = ProductStock::whereIn(
        'product_id',
        $order->items->pluck('product_id')
    )->get()->keyBy('product_id');

    foreach ($order->items as $item) {

        $stock = $stocks[$item->product_id] ?? null;
        $available = $stock ? $stock->total_stock : 0;

        $shortQty = max(0, $item->quantity - $available);

        if ($shortQty > 0) {

            $lostValue = $shortQty * ($item->offer_price ?? 0);

            \Log::info('SHORTAGE DETECTED', [
                'product_id' => $item->product_id,
                'short_qty'  => $shortQty,
                'lost_value' => $lostValue
            ]);

            PreMaterialShortLog::create([
                'product_id'      => $item->product_id,
                'order_id'        => $order->id,
                'required_qty'    => $item->quantity,
                'available_stock' => $available,
                'lost_value'      => $lostValue,
            ]);
        }
    }
}

// protected function handleStockShortage($order)
// {
//     \Log::info('Entered handleStockShortage', ['order_id' => $order->id]);

//     PreMaterialShortLog::where('order_id', $order->id)->delete();

//     $stocks = ProductStock::whereIn(
//         'product_id',
//         $order->items->pluck('product_id')
//     )->get()->keyBy('product_id');

//     \Log::info('Stocks Loaded', [
//         'products' => $order->items->pluck('product_id')
//     ]);

//     foreach ($order->items as $item) {

//         \Log::info('Checking Item', [
//             'product_id' => $item->product_id,
//             'qty'        => $item->quantity
//         ]);

//         $stock = $stocks[$item->product_id] ?? null;
//         $available = $stock ? $stock->total_stock : 0;

//         \Log::info('Stock Found', [
//             'available' => $available
//         ]);

//         if ($available <= 0 || $available < $item->quantity) {

//             \Log::info('SHORTAGE DETECTED', [
//                 'product_id' => $item->product_id
//             ]);

//             PreMaterialShortLog::create([
//                 'product_id'      => $item->product_id,
//                 'order_id'        => $order->id,
//                 'required_qty'    => $item->quantity,
//                 'available_stock' => $available,
//             ]);
//         }
//     }
// }

public function placeOrder(Request $request,SmsService $smsService)
{
    $data = $request->all();
    
// $blockedStatuses = [
//     'pending',
//     'in_progress',
//     'ready_for_dispatch',
//     'final_check_done',
//     'dispatched',
//     'hold'
// ];

// $existingBlockedOrders = DB::table('orders')
//     ->join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
//     ->where('orders.outlet_id', $request->outlet_id)
//     ->whereIn('delivery_management.delivery_status', $blockedStatuses)
//     ->where('orders.created_at', '<=', now()->subHours(24))
//     ->select(
//         'orders.id',
//         'orders.order_id',
//         'delivery_management.delivery_status',
//         'orders.created_at'
//     )
//     ->get();

// if ($existingBlockedOrders->isNotEmpty()) {

//     $count = $existingBlockedOrders->count();

//     $orderList = $existingBlockedOrders->map(function ($o) {
//         return $o->order_id . ' (' . ucfirst(str_replace('_', ' ', $o->delivery_status)) . ')';
//     })->implode(', ');

//     return response()->json([
//         'success' => false,
//         'message' => "Cannot place new order. {$count} undelivered order(s) pending for more than 24 hours: {$orderList}. Please ensure delivery is completed before placing a new order."
//     ]);
// }
    
    $user = User::findOrFail($request->customer_id);
    // dd($user);
    // dd($data);
    $deliveryDate = $request->delivery_date;
    $delivery_time_slot = $request->deliveryTime;
    $delivery_slot_type = $request->delivery_slot_type;
    $billingAddress = $request->billing_address;
    $shippingAddress = $request->shipping_address;
    $subtotal = $request->subtotal;
    $productDiscount = $request->product_discount;
    $cgstSgst = $request->tax_total;
    $packingCharges = $request->packing_charges;
    $othersCharges = $request->other_charges;
    $shipping_pincode = $request->shipping_pincode;
    $deliveryCharges = $request->delivery_charges;
    $user_id = $request->outlet_id;
    $customerId = $request->customer_id;
    $totalDiscountValue = $request->grand_total;
    $payment_status = 'unpaid';
    $payment_method = $request->payment_term;
    $saveType = $request->save_type ?? 'draft';
    $cart = $request->cart;

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
    $order->coupon_discount = '0';
    $order->others_charges = $othersCharges;
    $order->delivery_charges = $deliveryCharges;
    $order->total_discount_value = $totalDiscountValue;
    $order->payment_method = $payment_method;
    $order->payment_status = $payment_status;
    $order->status = $saveType === 'draft' ? 'draft' : 'sent';
    $order->save();

    $invoiceFormattedId = str_pad($orderIncrement, 2, '0', STR_PAD_LEFT);
    $invoiceFormattedId = 'INV-00' . $invoiceFormattedId;
    $order->invoice_id = $invoiceFormattedId;
    $order->order_id = $orderFormattedId;
    $order->save();

    $backend_sale_order = new BackendSalesOrder();
    $backend_sale_order->order_id = $order->id;
    $backend_sale_order->customer_id = $order->user_id;
    $backend_sale_order->invoice_number = $order->invoice_id; 
    $backend_sale_order->save();

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

if ($saveType === 'sent') {
    $previousOrders = Order::whereNull('order_id')->orWhereNull('invoice_id')->get();
    foreach ($previousOrders as $previousOrder) {
        $invoiceFormattedId = 'INV-00' . $previousOrder->id;
        $orderFormattedId = 'ORD-00' .  $previousOrder->id;
        $previousOrder->order_id = $orderFormattedId;
        $previousOrder->invoice_id = $invoiceFormattedId;
        $previousOrder->save();
    }
}
    

    $currentDate = date('Y-m-d');
    $userData = User::where('id', $user_id)->first();

    if ($userData) {
        $outstandingDate = date('Y-m-d', strtotime($currentDate . ' + ' . $userData->due_days_limit . ' days'));
    }


if ($saveType === 'sent') {
    if (in_array($payment_method, ['credit', 'special_credit', 'pay_on_delivery'])) {
        $outstandingStmt = new OutstandingStatement();
        $outstandingStmt->user_id = $user_id;
        $outstandingStmt->order_id = $order->id;
        $outstandingStmt->outlet_id = $user_id;
        $outstandingStmt->total_due_amount = $totalDiscountValue;
        $outstandingStmt->outstanding_date = $outstandingDate;
        $outstandingStmt->save();
    }
}

    foreach ($request->cart as $cartItem) {
    if (is_array($cartItem)) {
        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->product_id = $cartItem['product_id'];
        $orderItem->quantity = $cartItem['quantity'];
        $orderItem->price = $cartItem['amount'];
        $orderItem->offer_price = $cartItem['offer_price'];
        $orderItem->mrp = $cartItem['product_mrp'];
        $orderItem->save();

      
        OriginalItem::create([
            'order_id' => $orderItem->order_id,
            'product_id' => $orderItem->product_id,
            'quantity' => $orderItem->quantity,
            'price' => $orderItem->price,
            'offer_price' => $orderItem->offer_price,
            'mrp' => $orderItem->mrp,
        ]);
    }
}

    if ($saveType === 'sent') {
    
        \Log::info('Calling handleStockShortage');
    
        $order->load('items');
    
        \Log::info('Order Items Count:', ['count' => $order->items->count()]);
    
        $this->handleStockShortage($order);
    }
    
    if ($saveType === 'sent' && isset($delivery)) {
            $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' =>  $order->user_id,
            'outlet_id' => $user_id,
            'total_amount' => $order->total_discount_value,
            'total_paid' => 0,
            'payment_method' => $payment_method,
            'payment_status' => $payment_status,
            'payment_id' => 'PAY-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
            ]);
    
    

     $notificationMessage = "Your order has been submitted successfully! Your order ID is " . $orderFormattedId;
    //  auth()->user()->notify(new NewEnqueryRequestCustomerNotification(auth()->user()->id, $notificationMessage));
            $customer = User::find($request->customer_id);
        
        if ($customer) {
            $customer->notify(
                new NewEnqueryRequestCustomerNotification($customer->id, $notificationMessage)
            );
            
        $adminNotification = new OrderNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New Order ' . $order->order_id;
        $adminNotification->click_url = route('orderitem.details', ['id' => $order->id],false);
        $adminNotification->save();
        
        }
        
        $data = [
            'delivery' => DeliveryManagement::findOrFail($delivery->id), 
            'order' => Order::where('id', $delivery->order_id)->with('user')->first(),
        ];
        $response = $smsService->sendOrder($data);
}
        return response()->json([
            'success' => true,
             'message' => $saveType === 'draft'
            ? 'Draft saved successfully'
            : 'Order placed successfully',
        'redirect_url' => route('admin.invoice')
        ]);
  }


public function edit($id)
{
    $order = Order::with(['items.product', 'outlet', 'user', 'latestDelivery'])
        ->findOrFail($id);

    // 🔒 Restrict edit if not pending
    if ($order->latestDelivery && $order->latestDelivery->delivery_status !== 'pending') {
        abort(403, 'Order cannot be edited');
    }

    $customers = User::where('type', 'group')
        ->where('status', 'Active')
        ->select('id', 'name', 'outlet_name')
        ->get();

    $outlets = User::where('type', 'outlet')
        ->where('status', 'Active')
        ->select('id', 'outlet_name')
        ->orderBy('outlet_name')
        ->get();

    return view('admin.sales_invoices.edit', compact('order', 'customers', 'outlets'));
}


public function update(Request $request, $id, SmsService $smsService)
{

// dd($request->all());
    $order = Order::findOrFail($id);
    $oldData = [
    'delivery_date' => $order->delivery_date,
    'delivery_time' => $order->delivery_time_slot,
    'total'         => $order->total_discount_value,
    'address'       => $order->shipping_address,
];
   



    $saveType = $request->save_type ?? 'draft';

    $order->delivery_date       = $request->delivery_date;
    $order->delivery_time_slot  = $request->deliveryTime;
    $order->delivery_slot_type  = $request->delivery_slot_type;
    $order->outlet_id           = $request->outlet_id;
    $order->billing_address     = $request->billing_address;
    $order->shipping_address    = $request->shipping_address;
    $order->subtotal            = $request->subtotal;
    $order->product_discount    = $request->product_discount;
    $order->cgst_sgst           = $request->tax_total;
    $order->shipping_pincode    = $request->shipping_pincode;
    $order->packing_charges     = $request->packing_charges;
    $order->others_charges      = $request->other_charges;
    $order->delivery_charges    = $request->delivery_charges;
    $order->total_discount_value= $request->grand_total;
    $order->payment_method      = $request->payment_term;
    $order->status              = $saveType;

    $order->save();

    /* =============================
       DELETE OLD ITEMS
    ==============================*/
    OrderItem::where('order_id', $order->id)->delete();
    OriginalItem::where('order_id', $order->id)->delete();

    /* =============================
       SAVE NEW CART
    ==============================*/


       foreach ($request->cart as $cartItem) {
    if (is_array($cartItem)) {
        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->product_id = $cartItem['product_id'];
        $orderItem->quantity = $cartItem['quantity'];
        $orderItem->price = $cartItem['amount'];
        $orderItem->offer_price = $cartItem['offer_price'];
        $orderItem->mrp = $cartItem['product_mrp'];
        $orderItem->save();

      
        OriginalItem::create([
            'order_id' => $orderItem->order_id,
            'product_id' => $orderItem->product_id,
            'quantity' => $orderItem->quantity,
            'price' => $orderItem->price,
            'offer_price' => $orderItem->offer_price,
            'mrp' => $orderItem->mrp,
        ]);
    }
}


       if ($saveType === 'sent') {
            \Log::info('Status changed → running shortage');

            $order->load('items');

            $this->handleStockShortage($order);
        }


$importantChanged =
    $oldData['delivery_date'] != $order->delivery_date ||
    $oldData['delivery_time'] != $order->delivery_time_slot ||
    $oldData['total'] != $order->total_discount_value ||
    $oldData['address'] != $order->shipping_address;


    /* =============================
       IF SENT → PROCESS FULL FLOW
    ==============================*/
if ($saveType === 'sent') {
//  dd('INSIDE IF');

      
        if (!$order->latestDelivery) {

            $lastDelivery = DeliveryManagement::latest()->first();
            $nextId = $lastDelivery
                ? intval(substr($lastDelivery->delivery_id, 4)) + 1
                : 1;

            $delivery = DeliveryManagement::create([
                'order_id'           => $order->id,
                'delivery_id'        => 'DEL-' . str_pad($nextId, 3, '0', STR_PAD_LEFT),
                'delivery_status'    => 'pending',
                'delivery_address'   => $request->shipping_address,
                'delivery_person_id' => $request->outlet_id,
                'delivery_date'      => $request->delivery_date,
            ]);
        } else {
            $delivery = $order->latestDelivery;
        }

// dd($delivery);
     
        if (in_array($request->payment_term, ['credit', 'special_credit', 'pay_on_delivery'])) {

            $userData = User::find($request->outlet_id);
            $outstandingDate = now()->addDays($userData->due_days_limit ?? 0);

            OutstandingStatement::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'user_id'          => $request->outlet_id,
                    'outlet_id'        => $request->outlet_id,
                    'total_due_amount' => $order->total_discount_value,
                    'outstanding_date' => $outstandingDate,
                ]
            );
        }

     
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id'        => $order->user_id,
                'outlet_id'      => $request->outlet_id,
                'total_amount'   => $order->total_discount_value,
                'total_paid'     => 0,
                'payment_method' => $request->payment_term,
                'payment_status' => 'unpaid',
                'payment_id'     => 'PAY-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
            ]
        );

       
        $customer = User::find($order->user_id);
        // dd($customer);

        if ($customer) {
            $message = "Your order has been submitted successfully! Order ID: {$order->id}";

            $customer->notify(
                new NewEnqueryRequestCustomerNotification($customer->id, $message)
            );
            
        $adminNotification = new OrderNotification();
        $adminNotification->user_id = $order->user_id;
        $adminNotification->title = 'New Order ' . $order->id;
        $adminNotification->click_url = route('orderitem.details', ['id' => $order->id],false);
        $adminNotification->save();
        }
        
        $delivery = DeliveryManagement::findOrFail($delivery->id);
        // dd($delivery);

        
       
        
        $data = [
            'delivery' => DeliveryManagement::findOrFail($delivery->id), 
            'order' => Order::where('id', $delivery->order_id)->with('user')->first(),
        ];
        $response = $smsService->sendOrder($data);
            // dd($response);
            
        
    }

    return response()->json([
        'success' => true,
        'message' => $saveType === 'draft'
            ? 'Draft updated successfully'
            : 'Order updated & sent successfully',
        'redirect_url' => route('admin.invoice')
    ]);
}


 public function destroy($id)
{
    DB::beginTransaction();

    try {

        $order = Order::findOrFail($id);

        OrderItem::where('order_id', $order->id)->delete();
        DeliveryManagement::where('order_id', $order->id)->delete();
        OutstandingStatement::where('order_id', $order->id)->delete();
        BackendSalesOrder::where('order_id', $order->id)->delete();
        Payment::where('order_id', $order->id)->delete();

        OrderNotification::where(
            'click_url',
            'like',
            "%/orderitem/details/{$order->id}%"
        )->delete();

        $order->delete();

        DB::commit();

        return redirect()
            ->back()
            ->with('success', 'Invoice and all related records deleted successfully.');

    } catch (\Exception $e) {

        DB::rollBack();

        \Log::error('Order delete failed', [
            'order_id' => $id,
            'error'    => $e->getMessage()
        ]);

        return redirect()
            ->back()
            ->with('error', 'Failed to delete invoice. Please try again.');
    }
}


  

}
