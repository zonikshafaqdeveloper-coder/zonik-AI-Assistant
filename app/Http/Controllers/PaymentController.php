<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Exports\PaymentExport;
use Illuminate\Http\Request;
use App\Models\Order;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{

//   public function index()
// {
//     $payments = Payments::with(['user', 'order'])
//                 ->leftJoin('orders', 'payments.order_id', '=', 'orders.id')
//                 ->orderBy('payments.id', 'desc')
//                 ->select('payments.*', 'orders.payment_status')
//                 ->paginate(100);

// // dd($payments);
//     return view('admin.payments.index', compact('payments'));
// }


// public function index()
// {
//     $payments = Payment::with([
//         'order',
//         'histories',
//         'user',
//         'outlet'
//     ])
//     ->orderBy('id', 'desc')
//     ->get();

//     return view('admin.payments.index', compact('payments'));
// }

public function index()
{
    $from = request('from');
    $to   = request('to');

    $payments = Payment::with([
            'order',
            'histories',
            'user',
            'outlet'
        ])
        ->whereHas('order.delivery', function ($q) {
            $q->whereNotIn('delivery_status', ['pending', 'cancelled']);
        })

        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from, $to]);
        })

        ->orderBy('id', 'desc')
        ->get();

    return view('admin.payments.index', compact('payments'));
}

public function update_payments()
{
    $from = request('from');
    $to   = request('to');
    
    $payments = Payment::with([
        'order',
        'histories',
        'user',
        'outlet'
    ])
  ->whereHas('order.delivery', function ($q) {
        $q->whereNotIn('delivery_status', ['pending', 'cancelled']);
    })

    ->when($from && $to, function ($q) use ($from, $to) {
        $q->whereBetween('created_at', [$from, $to]);
    })
        
    ->orderBy('id', 'desc')
    ->get();

    return view('admin.payments.update_payments', compact('payments'));
}

public function history($orderId)
{
$order = Order::findOrFail($orderId);
$payment = Payment::where('order_id', $order->id)->first();
$histories = $payment ? $payment->histories()->latest()->get() : collect();
return view('admin.payments.history', compact('order','payment','histories'));
}



    public function downloadExcel(Request $request)
    {
        // dd($request->all());
        $user = auth()->user();

        // dd($user->id);
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        
        $filename = 'Payment_' . $startDate . '_to_' . $endDate . '.xlsx';
        return Excel::download(new PaymentExport($startDate, $endDate,$user->type,$user->id), $filename);
    }
}
