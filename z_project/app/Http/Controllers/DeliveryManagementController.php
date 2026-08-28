<?php

namespace App\Http\Controllers;

use App\Models\DeliveryManagement;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Payment;
use App\Models\OutstandingStatement;
use App\Services\SmsService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\NewEnqueryRequestCustomerNotification;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DeliveryExport;

class DeliveryManagementController extends Controller
{



   public function exportDelivery()
    {
        return Excel::download(new DeliveryExport, 'delivery.xlsx');
    }


  public function index()
{
    $deliveries = DeliveryManagement::with('user', 'user.kycdocuments', 'order')
        ->where('delivery_status', '!=', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.delivery.index', compact('deliveries'));
}



public function new_index()
{
    $deliveries = DeliveryManagement::with([
            'user:id,name,outlet_name',
            'order:id,order_id,total_discount_value,payment_method,payment_status'
        ])
        ->select('id', 'delivery_person_id', 'order_id', 'delivery_id', 'delivery_status',
                  'delivery_date', 'delivery_notes', 'confirmation_doc', 'updated_at')
        ->where('delivery_status', '!=', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.delivery.new_index', compact('deliveries'));
}



public function index_check()
{
   
    return view('admin.delivery.index_check');
}

// public function index_data(Request $request)
// {
//     $query = DeliveryManagement::with([
//             'user:id,name,outlet_name',
//             'order:id,order_id,total_discount_value,payment_method,payment_status',
//         ])
//         ->select('delivery_management.id', 'delivery_person_id', 'order_id', 'delivery_id',
//                   'delivery_status', 'delivery_date', 'delivery_notes', 'confirmation_doc',
//                   'updated_at', 'created_at')
//         ->where('delivery_status', '!=', 'pending');
//         // ->orderBy('created_at', 'desc');


//     if ($request->filled('status_filter') && $request->status_filter !== 'all') {
//         $status = $request->status_filter;
//         $query->where(function ($q) use ($status) {
//             $q->where('delivery_status', $status);
//             if ($status === 'unpaid') {
//                 $q->orWhereHas('order', fn ($o) => $o->where('payment_status', 'unpaid'));
//             }
//         });
//     }

//     return DataTables::eloquent($query)
//         ->addColumn('order_no', fn ($d) => $d->order->order_id ?? '-')

//         ->addColumn('invoice_no', function ($d) {
//             $url = route('generateInvoiceAndDeliveryCharges.list', ['id' => $d->order->id]);
//             return '<a href="'.$url.'" onclick="window.open(this.href,\'_blank\',\'width=800,height=600\'); return false;" class="font-weight-bold text-dark">'.$d->order->order_id.'</a>';
//         })

//         ->addColumn('delivery_col', function ($d) {
//             if ($d->delivery_status == 'cancelled') {
//                 return e($d->delivery_id);
//             }
//             $updateUrl = route('update.delivery', ['id' => $d->id]);
//             return '<a type="button" class="font-weight-bold text-dark edit-delivery-link" data-toggle="modal" data-target="#editDeliveryModal" data-id="'.$d->id.'" data-status="'.$d->delivery_status.'" data-note="'.e($d->delivery_notes).'" data-update-url="'.$updateUrl.'">'.e($d->delivery_id).'</a>';
//         })

//         ->addColumn('customer_name', fn ($d) => $d->user->name ?? '-')
//         ->addColumn('outlet_name', fn ($d) => $d->user->outlet_name ?? '-')
//         ->addColumn('paid_amount', fn ($d) => '₹ ' . ($d->order->total_discount_value ?? '-'))
//         ->addColumn('payment_mode', fn ($d) => $d->order->payment_method ?? '-')

//         ->addColumn('payment_status_col', function ($d) {
//             $status = $d->order->payment_status ?? '-';
//             $html = e($status);
//             if ($status !== 'paid') {
//                 $url = route('order.edit', ['id' => $d->order->id, 'from' => 'delivery']);
//                 $html .= ' <a href="'.$url.'" class="btn btn-danger text-white mx-1">Update</a>';
//             }
//             return $html;
//         })

//         ->addColumn('docs', function ($d) {
//             if ($d->confirmation_doc && count($d->confirmation_doc)) {
//                 $html = '';
//                 foreach ($d->confirmation_doc as $i => $file) {
//                     $html .= '<a href="'.asset('storage/'.$file).'" target="_blank" class="btn btn-sm btn-primary mb-1">View Bill '.($i + 1).'</a> ';
//                 }
//                 return $html;
//             }
//             return '<span class="text-muted">No confirmation document</span>';
//         })

//         ->addColumn('expected_delivery_date', fn ($d) => $d->delivery_date)

//         ->addColumn('delivery_date_col', function ($d) {
//             if ($d->delivery_status == 'delivered') return $d->updated_at;
//             if ($d->delivery_status == 'cancelled') return '<span class="text-danger">Order Cancelled</span>';
//             return '<span>Not Delivered Yet</span>';
//         })

//         ->addColumn('delivery_notes_col', fn ($d) => $d->delivery_notes)

        
//         ->filterColumn('order_no', fn ($q, $kw) => $q->whereHas('order', fn ($o) => $o->where('order_id', 'like', "%{$kw}%")))
//         ->filterColumn('customer_name', fn ($q, $kw) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$kw}%")))
//         ->filterColumn('outlet_name', fn ($q, $kw) => $q->whereHas('user', fn ($u) => $u->where('outlet_name', 'like', "%{$kw}%")))
//         ->filterColumn('delivery_status', fn ($q, $kw) => $q->where('delivery_status', 'like', "%{$kw}%"))
//         ->filterColumn('payment_status_col', fn ($q, $kw) => $q->whereHas('order', fn ($o) => $o->where('payment_status', 'like', "%{$kw}%")))
//         ->filterColumn('paid_amount', fn ($q, $kw) => $q->whereHas('order', fn ($o) => $o->where('total_discount_value', 'like', "%{$kw}%")))
//         ->filterColumn('payment_mode', fn ($q, $kw) => $q->whereHas('order', fn ($o) => $o->where('payment_method', 'like', "%{$kw}%")))

  
//         ->orderColumn('order_no', function ($q, $dir) {
//             $q->orderBy(Order::select('order_id')->whereColumn('orders.id', 'delivery_management.order_id'), $dir);
//         })

//         ->orderColumn('invoice_no', function ($q, $dir) {
//             $q->orderBy(Order::select('order_id')->whereColumn('orders.id', 'delivery_management.order_id'), $dir);
//         })

//         ->orderColumn('delivery_col', 'delivery_id $1')

//         ->orderColumn('paid_amount', function ($q, $dir) {
//             $q->orderBy(Order::select('total_discount_value')->whereColumn('orders.id', 'delivery_management.order_id'), $dir);
//         })

//         ->orderColumn('payment_mode', function ($q, $dir) {
//             $q->orderBy(Order::select('payment_method')->whereColumn('orders.id', 'delivery_management.order_id'), $dir);
//         })
        
//         // ->orderColumn('customer_name', function ($q, $dir) {
//         //   $q->orderBy(User::select('name')->whereColumn('users.id', 'delivery_management.user_id'), $dir);
//         // })
//         // ->orderColumn('outlet_name', function ($q, $dir) {
//         //     $q->orderBy(User::select('outlet_name')->whereColumn('users.id', 'delivery_management.user_id'), $dir);
//         // })
//         ->orderColumn('payment_status_col', function ($q, $dir) {
//             $q->orderBy(Order::select('payment_status')->whereColumn('orders.id', 'delivery_management.order_id'), $dir);
//         })


//         ->orderColumn('expected_delivery_date', 'delivery_date $1')

//         ->orderColumn('delivery_date_col', 'updated_at $1')

//         ->orderColumn('delivery_notes_col', 'delivery_notes $1')

        
//         ->orderColumn('docs', 'id $1')

//         ->rawColumns(['invoice_no', 'delivery_col', 'payment_status_col', 'docs', 'delivery_date_col'])
//         ->make(true);
// }

public function index_data(Request $request)
{
    $query = DeliveryManagement::with([
            'user:id,name,outlet_name',
            'order:id,order_id,total_discount_value,payment_method,payment_status',
        ])
        ->select('delivery_management.id', 'delivery_person_id', 'order_id', 'delivery_id',
                  'delivery_status', 'delivery_date', 'delivery_notes', 'confirmation_doc',
                  'updated_at', 'created_at')
        ->where('delivery_status', '!=', 'pending')
        ->orderBy('created_at', 'desc');


    if ($request->filled('status_filter') && $request->status_filter !== 'all') {
        $status = $request->status_filter;
        $query->where(function ($q) use ($status) {
            $q->where('delivery_status', $status);
            if ($status === 'unpaid') {
                $q->orWhereHas('order', fn ($o) => $o->where('payment_status', 'unpaid'));
            }
        });
    }

    return DataTables::eloquent($query)
        ->addColumn('order_no', function ($d) {
                if ($d->delivery_status == 'cancelled') {
                    return e($d->order->order_id ?? '-');
                }
            
                 if (!hasPermission('modify_rate')) {
                        return e($d->order->order_id);
                    }
            
                $url = route('order.modify', $d->order->id);
                return e($d->order->order_id) . ' <a href="'.$url.'" class="badge bg-warning text-dark mt-1">Modify Rate</a>';
            })

        ->addColumn('invoice_no', function ($d) {
            $url = route('generateInvoiceAndDeliveryCharges.list', ['id' => $d->order->id]);
            return '<a href="'.$url.'" onclick="window.open(this.href,\'_blank\',\'width=800,height=600\'); return false;" class="font-weight-bold text-dark">'.$d->order->order_id.'</a>';
        })

        ->addColumn('delivery_col', function ($d) {
            if ($d->delivery_status == 'cancelled') {
                return e($d->delivery_id);
            }
            $updateUrl = route('update.delivery', ['id' => $d->id]);
            return '<a type="button" class="font-weight-bold text-dark edit-delivery-link" data-toggle="modal" data-target="#editDeliveryModal" data-id="'.$d->id.'" data-status="'.$d->delivery_status.'" data-note="'.e($d->delivery_notes).'" data-update-url="'.$updateUrl.'">'.e($d->delivery_id).'</a>';
        })

        ->addColumn('customer_name', fn ($d) => $d->user->name ?? '-')
        ->addColumn('outlet_name', fn ($d) => $d->user->outlet_name ?? '-')
        ->addColumn('paid_amount', fn ($d) => '₹ ' . ($d->order->total_discount_value ?? '-'))
        ->addColumn('payment_mode', fn ($d) => $d->order->payment_method ?? '-')

        ->addColumn('payment_status_col', function ($d) {
            $status = $d->order->payment_status ?? '-';
            $html = e($status);
            if ($status !== 'paid') {
                $url = route('order.edit', ['id' => $d->order->id, 'from' => 'delivery']);
                $html .= ' <a href="'.$url.'" class="btn btn-danger text-white mx-1">Update</a>';
            }
            return $html;
        })

        ->addColumn('docs', function ($d) {
            if ($d->confirmation_doc && count($d->confirmation_doc)) {
                $html = '';
                foreach ($d->confirmation_doc as $i => $file) {
                    $html .= '<a href="'.asset('storage/'.$file).'" target="_blank" class="btn btn-sm btn-primary mb-1">View Bill '.($i + 1).'</a> ';
                }
                return $html;
            }
            return '<span class="text-muted">No confirmation document</span>';
        })

        ->addColumn('expected_delivery_date', fn ($d) => $d->delivery_date)

        ->addColumn('delivery_date_col', function ($d) {
            if ($d->delivery_status == 'delivered') return $d->updated_at;
            if ($d->delivery_status == 'cancelled') return '<span class="text-danger">Order Cancelled</span>';
            return '<span>Not Delivered Yet</span>';
        })

        ->addColumn('delivery_notes_col', fn ($d) => $d->delivery_notes)

        
        ->filterColumn('order_no', fn ($q, $kw) => $q->whereHas('order', fn ($o) => $o->where('order_id', 'like', "%{$kw}%")))
        ->filterColumn('customer_name', fn ($q, $kw) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$kw}%")))
        ->filterColumn('outlet_name', fn ($q, $kw) => $q->whereHas('user', fn ($u) => $u->where('outlet_name', 'like', "%{$kw}%")))
        ->filterColumn('delivery_status', fn ($q, $kw) => $q->where('delivery_status', 'like', "%{$kw}%"))
        ->filterColumn('payment_status_col', fn ($q, $kw) => $q->whereHas('order', fn ($o) => $o->where('payment_status', 'like', "%{$kw}%")))
        ->filterColumn('paid_amount', fn ($q, $kw) => $q->whereHas('order', fn ($o) => $o->where('total_discount_value', 'like', "%{$kw}%")))
        ->filterColumn('payment_mode', fn ($q, $kw) => $q->whereHas('order', fn ($o) => $o->where('payment_method', 'like', "%{$kw}%")))

  
        ->orderColumn('order_no', function ($q, $dir) {
            $q->reorder()->orderBy(Order::select('id')->whereColumn('orders.id', 'delivery_management.order_id'), $dir);
        })
        ->orderColumn('invoice_no', function ($q, $dir) {
            $q->reorder()->orderBy(Order::select('id')->whereColumn('orders.id', 'delivery_management.order_id'), $dir);
        })
        ->orderColumn('delivery_status', function ($q, $dir) {
            $q->reorder()->orderBy('delivery_status', $dir);
        })
        ->orderColumn('delivery_col', function ($q, $dir) {
            $q->reorder()->orderBy('delivery_id', $dir);
        })
        ->orderColumn('paid_amount', function ($q, $dir) {
            $q->reorder()->orderBy(Order::select('total_discount_value')->whereColumn('orders.id', 'delivery_management.order_id'), $dir);
        })
        ->orderColumn('payment_mode', function ($q, $dir) {
            $q->reorder()->orderBy(Order::select('payment_method')->whereColumn('orders.id', 'delivery_management.order_id'), $dir);
        })
       
       ->orderColumn('customer_name', function ($q, $dir) {
           $q->reorder()->orderBy(User::select('name')->whereColumn('users.id', 'delivery_management.delivery_person_id'), $dir);
        })
        ->orderColumn('outlet_name', function ($q, $dir) {
            $q->reorder()->orderBy(User::select('outlet_name')->whereColumn('users.id', 'delivery_management.delivery_person_id'), $dir);
        })
       
        ->orderColumn('payment_status_col', function ($q, $dir) {
            $q->reorder()->orderBy(Order::select('payment_status')->whereColumn('orders.id', 'delivery_management.order_id'), $dir);
        })
        ->orderColumn('expected_delivery_date', function ($q, $dir) {
            $q->reorder()->orderBy('delivery_date', $dir);
        })
        ->orderColumn('delivery_date_col', function ($q, $dir) {
            $q->reorder()->orderBy('updated_at', $dir);
        })
        ->orderColumn('delivery_notes_col', function ($q, $dir) {
            $q->reorder()->orderBy('delivery_notes', $dir);
        })
        ->orderColumn('docs', function ($q, $dir) {
            $q->reorder()->orderBy('id', $dir);
        })

        ->filterColumn('order_no', fn ($q, $kw) => $q->whereHas('order', fn ($o) => $o->where('order_id', 'like', "%{$kw}%")))
 

        ->rawColumns(['order_no', 'invoice_no', 'delivery_col', 'payment_status_col', 'docs', 'delivery_date_col'])
        ->make(true);
}


    public function create()
    {
        $existingOrderIds = DeliveryManagement::pluck('order_id');
        $orders = Order::whereNotIn('id', $existingOrderIds)->get();

        return view('admin.delivery.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id|not_in:'.implode(',',DeliveryManagement::pluck('order_id')->toArray()),
            'delivery_date' => 'required|date',
            'delivery_address' => 'required|string',
        ], [
            'order_id.not_in' => 'This order has already been added to delivery.',
        ]);

        $order = Order::findOrFail($request->order_id);
        $outletId = $order->outlet_id;
        $lastDelivery = DeliveryManagement::latest()->first();
        if ($lastDelivery) {
            $lastId = intval(substr($lastDelivery->delivery_id, 4));
            $nextId = $lastId + 1;
        } else {
            $nextId = 1;
        }

        $deliveryId = 'DEL-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        $delivery = new DeliveryManagement();
        $delivery->order_id = $request->order_id;
        $delivery->delivery_id = $deliveryId;
        $delivery->delivery_date = $request->delivery_date;
        $delivery->delivery_status = $request->delivery_status;
        $delivery->delivery_address = $request->delivery_address;
        $delivery->delivery_person_id = $outletId;
        $delivery->save();

        return redirect()->route('delivery.index')->with('success', 'Delivery created successfully');
    }


    public function show(DeliveryManagement $deliveryManagement)
    {
        //
    }


  
    public function edit($id)
    {
        $orders = Order::find($id);
        // dd($orders);
        return view('admin.delivery.edit')->with(compact('orders'));
    }
    
    
    public function deliveryupdate(Request $request, $id)
{
    $order = Order::findOrFail($id);

    $validatedData = $request->validate([
        'payment_status' => 'required|in:paid,unpaid',
    ]);

    // Update order payment status & method
    $order->payment_status = $validatedData['payment_status'];
    $order->payment_method = $request->payment_method;
    $order->save();

    // Check if payment record already exists
    $payment = Payment::where('order_id', $request->order_id)->first();

    if ($payment) {
        // Update existing payment record
        $payment->user_id = $request->user_id;
        $payment->outlet_id = $request->outlet_id;
        $payment->paid_amount = $request->paid_amount;
        $payment->payment_mode = $request->payment_mode;
        $payment->paid_to = $request->paid_to;
        $payment->save();
    } else {
        // Create a new payment record (only if no existing record is found)
        $payment = new Payment();
        $payment->user_id = $request->user_id;
        $payment->outlet_id = $request->outlet_id;
        $payment->order_id = $request->order_id;
        $payment->payment_id = 'PAY-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
        $payment->paid_amount = $request->paid_amount;
        $payment->payment_mode = $request->payment_mode;
        $payment->paid_to = $request->paid_to;
        $payment->save();
    }

    // If payment status is 'paid', delete from OutstandingStatement
    if ($validatedData['payment_status'] === 'paid') {
        OutstandingStatement::where('order_id', $id)->delete();
    }

    return redirect()->route('delivery.index')
                     ->with('success', 'Order details updated successfully');
}


    public function update(SmsService $smsService, Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'delivery_status' => 'required',
            'confirmation_doc' => $request->input('delivery_status') == 'delivered'
                ? 'required|array'
                : '',
            'confirmation_doc.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'confirmation_doc.required' => 'At least one document is required when delivered.',
        ]);
    
        // Find the delivery record by ID
        $delivery = DeliveryManagement::findOrFail($id);
        
        // Update the delivery status and delivery notes
        $delivery->update([
            'delivery_status' => $request->input('delivery_status'),
            'delivery_notes' => $request->input('note')
        ]);
    
        // If a confirmation document is uploaded, store it
       if ($request->hasFile('confirmation_doc')) {
            $paths = [];

            foreach ($request->file('confirmation_doc') as $file) {
                $paths[] = $file->store('uploads', 'public');
            }

            // Save as JSON
           if (!empty($delivery->confirmation_doc)) {

    foreach ($delivery->confirmation_doc as $oldFile) {

        Storage::disk('public')->delete($oldFile);
    }
}

$delivery->confirmation_doc = $paths;
            $delivery->save();
        }
    
        // Get the order and associated user (customer)
        $order = Order::where('id', $delivery->order_id)->with('user')->first();
    
        // Check the delivery status and send the appropriate notification
        if ($order) {
            $user = User::find($order->user_id); // Get the user (customer) who placed the order
            
            if ($user) {
                // Generate notification message based on delivery status
                $notificationMessage = '';
    
                switch ($delivery->delivery_status) {
                    case 'pending':
                        $notificationMessage = "Your order (ID: {$order->order_id}) is still pending. We will update you once it's processed.";
                        break;
                    case 'in_progress':
                        $notificationMessage = "Your order (ID: {$order->order_id}) is now in progress. We are working on it!";
                        break;
                    case 'dispatched':
                        $notificationMessage = "Your order (ID: {$order->order_id}) is ready for dispatch. It will be shipped shortly.";
                        break;
                    case 'delivered':
                        $notificationMessage = "Your order (ID: {$order->order_id}) has been delivered successfully. Thank you for shopping with us!";
                        break;
                    case 'cancelled':
                        $notificationMessage = "Your order (ID: {$order->order_id}) has been cancelled. Please contact support for assistance.";
                        break;
                    default:
                        $notificationMessage = "Your order (ID: {$order->order_id}) status has been updated.";
                        break;
                }
    
                // Send notification to the user
                $user->notify(new NewEnqueryRequestCustomerNotification($user->id, $notificationMessage));
    
                // Log the action for tracking purposes
                Log::info("Order status updated for order ID: {$order->order_id}. Notification sent to user ID: {$user->id}.");
            }
        }
    
        // Send SMS notification using the SmsService
        $data = [
            'delivery' => $delivery,
            'order' => $order,
        ];
    // dd($data);
        // Send SMS
        $response = $smsService->sendOrder($data);
    
        // Return success response
        return back()->with('success', 'Delivery information updated successfully');
    }
    

    public function getOrderData($orderId)
    {
        $order = Order::findOrFail($orderId);

        return response()->json([
            'delivery_date' => $order->delivery_date,
            'shipping_address' => $order->shipping_address,
        ]);
    }
}
