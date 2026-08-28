<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\KYCDocument;
use App\Models\User;
use App\Models\Pincode;
use App\Models\ReturnInvoice;
use App\Models\StockMovement;
use App\Models\Payment;
use App\Models\OutstandingStatement;
use App\Models\ProductStock;
use App\Models\PickList;
use App\Models\RackStock;
use App\Models\ReturnInvoiceItem;
use PDF;
use Illuminate\Support\Facades\DB;


class CreditNoteController extends Controller
{

public function index(Request $request)
{
    $orders = Order::with(['user', 'mainuser', 'delivery', 'returnInvoice'])
        ->whereNotNull('invoice_date')
        ->whereHas('delivery', function ($q) {
            $q->where('delivery_status', 'delivered');
        })
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.creditnote.index', compact('orders'));
}

public function create($id)
{
    $order = Order::with(['items.product','user'])
        ->findOrFail($id);

 
    if ($order->returnInvoice) {
        return redirect()->route('creditnote.index')
            ->with('error','Credit note already created for this order.');
    }

    return view('admin.creditnote.create', compact('order'));
}


public function store(Request $request, $id)
{
    
     if (ReturnInvoice::where('order_id', $id)->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Credit note already created for this order.'
        ], 422);
    }
    
    
    // dd($request->all());
    $request->validate([
        'items' => 'required|array',
        'items.*.return_qty' => 'nullable|integer|min:0',
        'items.*.rate' => 'nullable|numeric|min:0',
        'items.*.reason' => 'nullable|string|max:255'
    ]);


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

    DB::transaction(function () use ($request, $id) {

        $order = Order::with('items.product')->findOrFail($id);

        $returnInvoice = ReturnInvoice::create([
            'order_id'       => $id,
            'credit_note_no' => 'CN-' . time(),
            'total_amount'   => 0
        ]);

        $grandTotal = 0;

        foreach ($request->items as $itemId => $data) {

            $returnQty = (int) ($data['return_qty'] ?? 0);

            if ($returnQty <= 0) {
                continue;
            }

            $orderItem = OrderItem::with('product')->findOrFail($itemId);
            if ($returnQty > $orderItem->quantity) {
                throw new \Exception("Return qty exceeds delivered qty");
            }
            
            
             $pickLists = PickList::where('order_id', $order->id)
                ->where('product_id', $orderItem->product_id)
                ->get();

            $remainingReturn = $returnQty;

            foreach ($pickLists as $row) {

                if ($remainingReturn <= 0) break;

                $deduct = min($row->quantity, $remainingReturn);

             
                $rack = RackStock::where('product_id', $orderItem->product_id)
                    ->where('batch_no', $row->batch_no)
                    ->where('rack_no', $row->rack_no)
                    ->where('level_no', $row->level_no)
                    ->where('slot_no', $row->slot_no)
                    ->lockForUpdate()
                    ->first();

                if ($rack) {
                    $rack->quantity += $deduct;
                    $rack->save();
                }

           
                $row->quantity -= $deduct;

                if ($row->quantity <= 0) {
                    $row->delete();
                } else {
                    $row->save();
                }

                $remainingReturn -= $deduct;
            }

        
            ProductStock::where('product_id', $orderItem->product_id)
                ->increment('total_stock', $returnQty);

          
            StockMovement::create([
                'product_id'     => $orderItem->product_id,
                'reference_type' => 'CREDIT_NOTE',
                'reference_id'   => $returnInvoice->id,
                'movement_type'  => 'IN',
                'quantity'       => $returnQty,
                'unit_cost'      => $orderItem->product->cost_per_item ?? 0,
                'batch_no'       => null,
                'expiry_date'    => null,
                'remarks'        => "Returned from Order #{$order->order_id}"
            ]);

           
            // $pretax = $returnQty * $orderItem->offer_price;
            
            $rate = isset($data['rate']) ? (float)$data['rate'] : $orderItem->offer_price;
            
            if ($rate <= 0) {
                throw new \Exception("Invalid rate for item ID {$itemId}");
            }
            
            $pretax = $returnQty * $rate;

            $cgst = $orderItem->product->cgst ?? 0;
            $sgst = $orderItem->product->sgst ?? 0;

            $tax = ($pretax * ($cgst + $sgst)) / 100;

            $total = $pretax + $tax;

            $grandTotal += $total;

          
            ReturnInvoiceItem::create([
                'return_invoice_id' => $returnInvoice->id,
                'order_item_id'    => $itemId,
                'return_qty'       => $returnQty,
                'reason'           => $data['reason'] ?? null,
                'price'            => $rate,
                'tax'              => $tax,
                'total'            => $total
            ]);
        }

       
        $returnInvoice->update([
            'total_amount' => $grandTotal
        ]);

      
        $payment = Payment::where('order_id', $order->id)->first();

        if ($payment) {

           
            $payment->total_amount = max(
                0,
                (float)$payment->total_amount - (float)$grandTotal
            );

            if ($payment->total_paid >= $payment->total_amount) {
                $payment->payment_status = 'paid';
            } elseif ($payment->total_paid > 0) {
                $payment->payment_status = 'partial';
            } else {
                $payment->payment_status = 'unpaid';
            }

            $payment->save();

            $order->payment_status = $payment->payment_status;
            $order->save();
        }
    });

 
    return response()->json([
        'success' => true,
        'message' => 'Credit note created successfully'
    ]);
}






public function generateCreditNote($id)
    {
     
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
        $company_name = User::where('id', $order_company->user_id)
            ->select('outlet_name')
            ->first();
        $company_name1 = $company_name->outlet_name ?? 'N/A';  
 
        $lastpayment = OutstandingStatement::where('user_id', $outletIds)->where('outstanding_date', '>=',  $latestOrderCreatedAt)->get();
        $invoiceView = view('admin.creditnote.credit_note', compact('orderInvoice', 'orderss', 'orders', 'orderNoInvoice', 'orderInvoice1', 'lastpayment', 'maharashtrian','company_name1'))->render();
        // $deliveryChargesView = view('admin.invoice.delivery_charges', compact('orderInvoice', 'orderss', 'orders', 'orderNoInvoice', 'orderInvoice1', 'lastpayment','maharashtrian'))->render();

        $pdf = PDF::loadHTML($invoiceView);
        return $pdf->stream('combined_invoice_and_delivery_charges.pdf');
    }

// public function download($orderId)
// {
//     $returnInvoice = ReturnInvoice::with([
//         'items.orderItem.product',
//         'order.user'
//     ])
//     ->where('order_id', $orderId)
//     ->firstOrFail();

//     $order = $returnInvoice->order;
//     $orderInvoice = $returnInvoice->items;
//     $pdf = PDF::loadView(
//         'admin.creditnote.credit_note_pdf',
//         compact('returnInvoice', 'order', 'orderInvoice')
//     );

//     return $pdf->stream(
//         'credit-note-' . $returnInvoice->credit_note_no . '.pdf'
//     );
// }

public function download($orderId)
{
    $returnInvoice = ReturnInvoice::with([
        'items.orderItem.product',
        'order.user'
    ])->where('order_id', $orderId)
      ->firstOrFail();

    $order = $returnInvoice->order;
    $user  = $order->user;
    $company_name1 = $user->outlet_name ?? 'N/A';
    $gst = KYCDocument::where('user_id', $user->id)->first();
    $shipping_pincode = $order->shipping_pincode;
    $pincode = Pincode::where('pincode', $shipping_pincode)->first();
    $maharashtrian = false;
    if ($pincode) {
        $maharashtrian = true;
    } else {
        $response = @file_get_contents("https://api.postalpincode.in/pincode/$shipping_pincode");
        $data = json_decode($response, true);

        if (
            isset($data[0]['PostOffice'][0]['State']) &&
            $data[0]['PostOffice'][0]['State'] === 'Maharashtra'
        ) {
            $maharashtrian = true;
        }
    }

    $pdf = PDF::loadView(
        'admin.creditnote.credit_note_pdf',
        compact(
            'returnInvoice',
            'order',
            'company_name1',
            'gst',
            'maharashtrian'
        )
    );

    return $pdf->stream(
        'credit-note-' . $returnInvoice->credit_note_no . '.pdf'
    );
}

}
