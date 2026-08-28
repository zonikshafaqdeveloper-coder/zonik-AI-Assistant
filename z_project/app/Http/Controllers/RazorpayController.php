<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OutstandingStatement;
use App\Models\DeliveryManagement;
use App\Models\OrderItem;
use App\Models\KYCDocument;
use App\Models\Payment;
use App\Models\PaymentHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\NewEnqueryRequestCustomerNotification;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\DB;
use App\Models\AdminNotification;
use App\Models\OrderNotification;
use Illuminate\Support\Facades\Log;

class RazorpayController extends Controller
{
    protected $razorpay;

    public function __construct(Api $razorpay)
    {
        $this->razorpay = $razorpay;
    }

    public function index()
    {
        return view('razorpay.payment');
    }


    // public function createOrder(Request $request)
    // {
    //     $orderData = [
    //         'receipt'         => 'rcptid_11',
    //         'amount'          => 100, // amount in the smallest currency unit
    //         'currency'        => 'INR',
    //         'payment_capture' => 1 // auto capture
    //     ];

    //     $razorpayOrder = $this->razorpay->order->create($orderData);

        // return response()->json([
        //     'order_id' => $razorpayOrder['id'],
        //     'razorpay_key' => config('services.razorpay.key'),
        // ]);
    // }




    public function createOrder(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        // Calculate the total amount in paise
         $amount =  $request->totalDiscountValue;
        //  $amount = number_format($amount1);
        //  $amountInPaise = $amount * 100;
   $data = $request->all();
//   dd($data);
     session(['order_data' => $data]);
     
    $amountInPaise = intval(round($amount * 100));
    //   dd($amountInPaise1);
     
    //  $amountInPaise1 = 1459;
    // $amountInPaise = $amountInPaise1 * 100;
    // dd($amountInPaise);
        $order = $api->order->create([
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'receipt' => 'receipt_order_' . uniqid()
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'amount' => $order['amount'],
            'razorpay_key' => env('RAZORPAY_KEY'),
        ]);
    }



public function handlePaymentSuccess(Request $request)
{
    // Verify payment signature
    $signatureStatus = $this->verifySignature($request->all());

    if ($signatureStatus === true) {
        // Payment successful, handle order creation
        $orderData = session('order_data');
        
        try {
             $user = auth()->user();
            $userData = User::where('priority', auth()->id())->first();

            if (!$userData) {
                // Handle the case when no user is found
                $userData = User::where('id', auth()->id())->first();
            }
            
          // Initialize Razorpay API
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $paymentDetails = $api->payment->fetch($request->input('razorpay_payment_id'));

            // Extract payment details
            $paidAmount = $paymentDetails->amount / 100; 
            $paymentMode = $paymentDetails->method ?? 'unknown';

        //  dd($paidAmount);
         
           

        
            $deliveryDate = $orderData['deliveryDate'];
            $delivery_time_slot = $orderData['delivery_time_slot'];
            $delivery_slot_type = $orderData['delivery_slot_type'];
            $billingAddress = $orderData['billingAddress'];
            $shippingAddress = $orderData['shippingAddress'];
            $subtotal = $orderData['subtotal'];
            $productDiscount = $orderData['productDiscount'];
            $cgstSgst = $orderData['cgstSgst'];
            $packingCharges = $orderData['packingCharges'];
            $othersCharges = $orderData['othersCharges'];
            $shipping_pincode = $orderData['shipping_pincode'];
            $deliveryCharges = $orderData['deliveryCharges'];
            $totalDiscountValue = $orderData['totalDiscountValue'];
            $payment_status = $orderData['payment_status'];
            $cart = $orderData['cart'];

            // Set coupon_discount to null initially
            $coupon_discount = null;

            // Loop through the cart and get the first cart item's coupon_discount
            foreach ($cart as $cartItem) {
                $coupon_discount = $cartItem['coupon_discount']; // Assuming 'coupon_discount' is a property of the Cart object
                break; // Exit the loop after getting the first cart item's coupon discount
            }

            // If coupon_discount is null, set it to 0
            $coupon_discount = $coupon_discount ?? 0;

            // Generate Order ID and Invoice ID
            $latestOrderId = Order::max('id');
            $orderIncrement = $latestOrderId + 1;
            $orderFormattedId = 'ORD-00' . str_pad($orderIncrement, 2, '0', STR_PAD_LEFT);
            $invoiceFormattedId = 'INV-00' . str_pad($orderIncrement, 2, '0', STR_PAD_LEFT);

            // Create Order
            $order = new Order();
            $order->delivery_date = $deliveryDate;
            $order->delivery_time_slot = $delivery_time_slot;
            $order->delivery_slot_type  =  $delivery_slot_type;
            $order->outlet_id = $userData->id;
            $order->user_id = $user->id;
            $order->billing_address = $billingAddress;
            $order->shipping_address = $shippingAddress;
            $order->subtotal = $subtotal;
            $order->product_discount = $productDiscount;
            $order->cgst_sgst = $cgstSgst;
            $order->shipping_pincode = $shipping_pincode;
            $order->packing_charges = $packingCharges;
            $order->coupon_discount = $coupon_discount; // Set coupon_discount to 0 if not provided
            $order->others_charges = $othersCharges;
            $order->delivery_charges = $deliveryCharges;
            $order->total_discount_value = $totalDiscountValue;
            $order->payment_method = $payment_status;
            $order->payment_status = $payment_status == 'credit' || $payment_status == 'pay_on_delivery' ? 'unpaid' : $payment_status;
            $order->status = 'sent';
            $order->order_id = $orderFormattedId;
            $order->invoice_id = $invoiceFormattedId;
            $order->save();


            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->user_id = $user->id;
            $payment->outlet_id = $userData->id;
            $payment->total_amount = $order->total_discount_value;
            $payment->total_paid = $order->total_discount_value;
            $payment->payment_method = $paymentMode;
            $payment->payment_status = 'paid';
            $payment->payment_id = $request->razorpay_payment_id ?? 'PAY-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            $payment->save();
            
            $paymenthistory = new PaymentHistory();
            $paymenthistory->payment_id = $payment->id;
            $paymenthistory->paid_amount = $order->total_discount_value;
            $paymenthistory->payment_mode = $paymentMode;
            $paymenthistory->source = 'razorpay';
            
            $paymenthistory->save();
            
            // Create Delivery Management record
            $lastDelivery = DeliveryManagement::latest()->first();
            $nextId = $lastDelivery ? intval(substr($lastDelivery->delivery_id, 4)) + 1 : 1;
            $deliveryId = 'DEL-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            $delivery = new DeliveryManagement();
            $delivery->order_id = $order->id;
            $delivery->delivery_id = $deliveryId;
            $delivery->delivery_status = 'pending';
            $delivery->delivery_address = $shippingAddress;
            $delivery->delivery_person_id = $user->id;
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
        
            // Save Order Items
            foreach ($cart as $cartItem) {
                if (is_array($cartItem)) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $cartItem['product_id'];
                    $orderItem->qty_type = $cartItem['product_types'] == '1' ? 'box' : 'loose';
                    $orderItem->quantity = $cartItem['total_qty'];
                    $orderItem->price = $cartItem['total_amt_basic'];
                    $orderItem->save();
                    Cart::where('id', $cartItem['id'])->delete();
                }
            }

                $notificationMessage = "Your order has been submitted successfully! Your order ID is " . $orderFormattedId;
                auth()->user()->notify(new NewEnqueryRequestCustomerNotification(auth()->user()->id, $notificationMessage));
            
        
        
                    // Admin Notification
            $adminNotification = new OrderNotification();
            $adminNotification->user_id = $user->id;
            $adminNotification->title = 'New Order ' . $order->order_id;
        
            // Generate URL with order details route
            $adminNotification->click_url = route('orderitem.details', ['id' => $order->id],false);
        
            $adminNotification->save();
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->order_id,
                    'tracking_code' => $delivery->delivery_id,
                ]);
            }
            return redirect()->route('orders')->with('success', 'Payment successful. Order ID: ' . $orderFormattedId);

        } catch (\Exception $e) {
            // Handle exception and log it
            Log::error('Order creation failed: ' . $e->getMessage());
            if ($request->expectsJson() || $request->ajax()) return response()->json(['message' => 'Payment verification failed'], 422);
            return redirect()->route('orders')->with('error', 'Payment verification failed');
        }
    } else {
        // Payment verification failed
        if ($request->expectsJson() || $request->ajax()) return response()->json(['message' => 'Payment verification failed'], 422);
        return redirect()->route('orders')->with('error', 'Payment verification failed');
    }
}


public function updatepaymethod(Request $request)
{
    Log::info('updatepaymethod called', $request->all());

    $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

    // Store request data in session with new name
    session(['payment_order_data' => $request->all()]);

    // Convert amount to paise
    $amountInPaise = intval(round($request->totalDiscountValue * 100));

    // Create Razorpay order
    $order = $api->order->create([
        'amount' => $amountInPaise,
        'currency' => 'INR',
        'receipt' => 'receipt_order_' . uniqid()
    ]);

    return response()->json([
        'order_id' => $order['id'],
        'amount' => $order['amount'],
        'razorpay_key' => env('RAZORPAY_KEY'),
    ]);
}

public function handlePaymentUpdate(Request $request)
{
    Log::info('handlePaymentUpdate initiated', $request->all());

    if (!$this->verifySignature($request->all())) {
        return redirect()->route('orders')->with('error', 'Payment verification failed.');
    }

    try {
        $user = auth()->user();

        // Retrieve order data from session
        $orderData = session('payment_order_data');

        if (!$orderData || !isset($orderData['order_id'])) {
            return redirect()->route('orders')->with('error', 'Session expired. Order data missing.');
        }

        // Find existing order
        $order = Order::where('id', $orderData['order_id'])->first();

        if (!$order) {
            return redirect()->route('orders')->with('error', 'Order not found.');
        }

        // Update order
        $this->updateOrder($order, $orderData, $request);

        return redirect()->route('orders')->with('success', 'Order updated successfully.');
    } catch (\Exception $e) {
        Log::error('Order update failed: ' . $e->getMessage());
        return redirect()->route('orders')->with('error', 'Payment update failed.');
    }
}


private function updateOrder($order, $orderData, $request)
{
    Log::info('Updating order:', ['order_id' => $order->id]);

    $order->update([
        'payment_status' => 'paid',
    ]);

    Log::info('Order updated successfully.');

    // Update Payment
    $payment = Payment::where('order_id', $order->id)->first();
    
    $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
    
    $paymentDetails = $api->payment->fetch($request->razorpay_payment_id);
    $paymentMode = $paymentDetails->method ?? 'unknown';
    
    if ($payment) {
        // Update existing payment record
        $payment->update([
        'payment_id' => $request->razorpay_payment_id,
        'total_paid' => $orderData['totalDiscountValue'],
        'payment_status' => 'paid'
            
        ]);
        
    $history = PaymentHistory::create([
        'payment_id'   => $payment->id,
        'paid_amount'  => $orderData['totalDiscountValue'],
        'payment_mode' => $paymentMode,
         'source'       => 'razorpay',
    ]);
    
    } else {
        Log::warning('No payment record found for order_id: ' . $order->id);
    }

    Log::info('Payment record updated.');

    // Notify admin
  

    Log::info('Admin notified for order update.');
}




    private function verifySignature($attributes)
    {
        $order_id = $attributes['razorpay_order_id'];
        $payment_id = $attributes['razorpay_payment_id'];
        $signature = $attributes['razorpay_signature'];

        try {
            $this->razorpay->utility->verifyPaymentSignature([
                'razorpay_order_id' => $order_id,
                'razorpay_payment_id' => $payment_id,
                'razorpay_signature' => $signature
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Razorpay signature verification failed: ' . $e->getMessage());
            return false;
        }
    }
}
