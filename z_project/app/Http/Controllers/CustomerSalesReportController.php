<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\OutletPaymentTerm;
use App\Models\DairyPaymentTerm;
use DB;
use PDF;
use Carbon\Carbon;

class CustomerSalesReportController extends Controller
{
 
// ================= INDEX PAGE =================
// public function index()
// {
//     $from = request('from');
//     $to   = request('to');

//     $query = DB::table('orders as o')
//         ->join('users as u', 'u.id', '=', 'o.user_id')
//         ->leftJoin('users as outlet', 'outlet.id', '=', 'o.outlet_id')
//         ->join('delivery_management as dm', 'dm.order_id', '=', 'o.id')
//         ->where('u.type', 'group')
//         ->whereNotIn('dm.delivery_status', ['pending', 'cancelled'])
//         ->select(
//             'u.id',
//             'u.name as customer_name',
//             'outlet.outlet_name as outlet_name',
//             DB::raw('COUNT(o.id) as order_count'),
//             // Add this for basic amount:
//           DB::raw('SUM(o.subtotal) as total_amount'),
//         // Add this for basic amount with gst:
//         DB::raw('SUM(o.total_discount_value) as total_with_gst')
//         )
//         ->groupBy('u.id', 'u.name', 'outlet.outlet_name');

//     //  Apply filter ONLY if provided
//     if ($from && $to) {
//         $query->whereBetween('o.created_at', [$from, $to]);
//     }

//     $data = $query->get();
//     // ADD THIS HERE (after $data)
//     // ADD THIS PART HERE
//     $overallTotalOrders = $data->sum('order_count');
//     $overallTotalAmount = $data->sum('total_amount');
//     $overallTotalWithGST = $data->sum('total_with_gst');

//     return view('admin.reports.customer_sales.index', compact(
//         'data',
//         'from',
//         'to',
//         'overallTotalOrders',
//         'overallTotalAmount',
//         'overallTotalWithGST'
//     ));


// }




// ================= INDEX PAGE =================
public function index()
{
    $from = request('from');
    $to   = request('to');

    $currentMonth = Carbon::now();
    $month1 = Carbon::now()->subMonth();
    $month2 = Carbon::now()->subMonths(2);
    $month3 = Carbon::now()->subMonths(3);

    $query = DB::table('orders as o')
        ->join('users as u', 'u.id', '=', 'o.user_id')
        ->leftJoin('users as outlet', 'outlet.id', '=', 'o.outlet_id')
        ->join('delivery_management as dm', 'dm.order_id', '=', 'o.id')
        ->where('u.type', 'group')
        ->whereNotIn('dm.delivery_status', ['pending', 'cancelled'])
        ->select(
            'u.id',
            'u.name as customer_name',
            'outlet.id as outlet_id',
            'outlet.outlet_name as outlet_name',

            DB::raw('COUNT(o.id) as order_count'),
            DB::raw('SUM(o.subtotal) as total_amount'),
            DB::raw('SUM(o.total_discount_value) as total_with_gst'),
            DB::raw('MAX(o.created_at) as last_invoice_date'),

            DB::raw("
                SUM(
                    CASE
                        WHEN MONTH(o.created_at) = {$currentMonth->month}
                        AND YEAR(o.created_at) = {$currentMonth->year}
                        THEN o.subtotal
                        ELSE 0
                    END
                ) as current_month_sales
            "),

            DB::raw("
                SUM(
                    CASE
                        WHEN MONTH(o.created_at) = {$month1->month}
                        AND YEAR(o.created_at) = {$month1->year}
                        THEN o.subtotal
                        ELSE 0
                    END
                ) as month1_sales
            "),

            DB::raw("
                SUM(
                    CASE
                        WHEN MONTH(o.created_at) = {$month2->month}
                        AND YEAR(o.created_at) = {$month2->year}
                        THEN o.subtotal
                        ELSE 0
                    END
                ) as month2_sales
            "),

            DB::raw("
                SUM(
                    CASE
                        WHEN MONTH(o.created_at) = {$month3->month}
                        AND YEAR(o.created_at) = {$month3->year}
                        THEN o.subtotal
                        ELSE 0
                    END
                ) as month3_sales
            ")
        )
        ->groupBy(
            'u.id',
            'u.name',
            'outlet.id',
            'outlet.outlet_name'
        );

    if ($from && $to) {
        $query->whereBetween('o.created_at', [
            $from . ' 00:00:00',
            $to . ' 23:59:59'
        ]);
    }

    $data = $query->get();

    // ===== Attach Total Overdue per outlet, using the exact same
    // per-order due-date logic already proven correct elsewhere =====
    $overdueByOutlet = $this->computeOverdueByOutlet();

    $data = $data->map(function ($row) use ($overdueByOutlet) {
        $row->total_overdue = $overdueByOutlet[$row->outlet_id] ?? 0;
        return $row;
    });

    $overallTotalOrders  = $data->sum('order_count');
    $overallTotalAmount  = $data->sum('total_amount');
    $overallTotalWithGST = $data->sum('total_with_gst');
    $overallTotalOverdue = $data->sum('total_overdue');

    return view('admin.reports.customer_sales.index', compact(
        'data',
        'from',
        'to',
        'overallTotalOrders',
        'overallTotalAmount',
        'overallTotalWithGST',
        'overallTotalOverdue',
        'currentMonth',
        'month1',
        'month2',
        'month3'
    ));
}

/**
 * Computes total overdue balance per outlet_id, using the exact
 * priority-based due-date logic already used for the overdue dashboard.
 */
private function computeOverdueByOutlet()
{
    $orders = Order::join('outstanding_statements', 'orders.id', '=', 'outstanding_statements.order_id')
        ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
        ->join('users', 'users.id', '=', 'orders.outlet_id')
        ->whereIn('orders.payment_status', ['unpaid', 'partial'])
        ->where('delivery_management.delivery_status', 'delivered')
        ->select('orders.*')
        ->get();

    $today = now()->startOfDay();
    $overdueByOutlet = [];

    foreach ($orders as $order) {

        $payment = Payment::where('order_id', $order->id)->first();

        $totalAmount   = $order->total_discount_value;
        $totalPaid     = $payment->total_paid ?? 0;
        $balanceAmount = $totalAmount - $totalPaid;

        if ($balanceAmount <= 0) {
            continue;
        }

        $deliveryDate = Carbon::parse($order->delivery_date);

        $paymentTerm = OutletPaymentTerm::where('user_id', $order->outlet_id)
            ->where('is_active', 1)
            ->first();

        $hasNewPaymentTerm = $paymentTerm ? true : false;

        $userData = User::where('id', $order->outlet_id)
            ->select('due_days_limit')
            ->first();

        $due_days_limit = $userData->due_days_limit ?? 0;

        $dueDate = null;

        if ($order->payment_method === 'special_credit') {

            $dairyTerm = DairyPaymentTerm::where('user_id', $order->outlet_id)
                ->where('is_active', 1)
                ->first();

            $customDueDays = ($dairyTerm && $dairyTerm->due_limit_days !== null)
                ? (int) $dairyTerm->due_limit_days
                : $due_days_limit;

            $dueDate = $deliveryDate->copy()->addDays($customDueDays)->startOfDay();

        } elseif ($hasNewPaymentTerm) {

            $deliveryDateStart = $deliveryDate->copy()->startOfDay();
            $dueDay = (int) $paymentTerm->days ?: 1;

            $dueDate = $deliveryDateStart->copy()
                ->addMonthNoOverflow()
                ->day($dueDay)
                ->startOfDay();

        } else {
            $dueDate = $deliveryDate->copy()->addDays($due_days_limit)->startOfDay();
        }

        $daysUntilDue = $today->diffInDays($dueDate, false);
        $isOverdue    = $daysUntilDue < 0;

        if (!$isOverdue) {
            continue;
        }

        $outletId = $order->outlet_id;

        $overdueByOutlet[$outletId] = ($overdueByOutlet[$outletId] ?? 0) + $balanceAmount;
    }

    return $overdueByOutlet;
}

    // ================= PDF DOWNLOAD =================

// comment by ujala yadav on 06-06-26
// public function downloadPdf($id)
// {
//     $customer = User::where('type', 'group')->findOrFail($id);

//     $from = request('from');
//     $to   = request('to');

//     $query = Order::where('user_id', $id)
//         ->join('delivery_management as dm', 'dm.order_id', '=', 'orders.id')

//         ->whereNotIn('dm.delivery_status', ['pending', 'cancelled']);

//     if ($from && $to) {
//         $query->whereBetween('created_at', [$from, $to]);
//     }

// $data = $query->selectRaw('
//     COUNT(DISTINCT orders.id) as invoice_count,
//     SUM(subtotal) as sales,
//     SUM(total_discount_value) as sales_with_tax,
//     SUM(total_discount_value) as invoice_amount
// ')->first();


//     if (!$data || $data->invoice_count == 0) {
//         abort(404, 'No sales found');
//     }

//     $totals = [
//         'invoice_count' => $data->invoice_count,
//         'sales' => $data->sales,
//         'sales_with_tax' => $data->sales_with_tax,
//         'invoice_amount' => $data->invoice_amount,
//     ];

//     $pdf = PDF::loadView(
//         'admin.reports.customer_sales.pdf',
//         compact('customer', 'data', 'totals', 'from', 'to')
//     )->setPaper('A4', 'portrait');

//     return $pdf->stream('customer_sales_' . $customer->id . '.pdf');
// }


public function downloadPdf($id)
{
    $customer = User::where('type', 'group')->findOrFail($id);

    $from = request('from');
    $to   = request('to');

    $query = Order::where('orders.user_id', $id)
        ->join('delivery_management as dm', 'dm.order_id', '=', 'orders.id')
        ->whereNotIn('dm.delivery_status', ['pending', 'cancelled']);

    if (!empty($from) && !empty($to)) {
        $query->whereBetween('orders.created_at', [
            $from . ' 00:00:00',
            $to . ' 23:59:59'
        ]);
    }

    $data = $query->selectRaw('
        COUNT(DISTINCT orders.id) as invoice_count,
        COALESCE(SUM(orders.subtotal), 0) as sales,
        COALESCE(SUM(orders.total_discount_value), 0) as sales_with_tax,
        COALESCE(SUM(orders.total_discount_value), 0) as invoice_amount
    ')->first();

    if (!$data || $data->invoice_count == 0) {
        abort(404, 'No sales found');
    }

    $totals = [
        'invoice_count' => $data->invoice_count,
        'sales' => $data->sales,
        'sales_with_tax' => $data->sales_with_tax,
        'invoice_amount' => $data->invoice_amount,
    ];

    $pdf = PDF::loadView(
        'admin.reports.customer_sales.pdf',
        compact('customer', 'data', 'totals', 'from', 'to')
    )->setPaper('A4', 'portrait');

    return $pdf->stream('customer_sales_' . $customer->id . '.pdf');
}

}