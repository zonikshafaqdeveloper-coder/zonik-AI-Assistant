<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrderDetail;
use App\Models\Vendor;
use App\Models\StockReceiving;
use App\Models\VendorPayment;
use App\Models\VendorPaymentTerm;
use App\Models\VendorBill;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;


class VendorOutstandingController extends Controller
{
   public function index()
{
    $vendorOutstandingList = StockReceiving::query()
        ->select(
            'vendors.id as vendor_id',
            'vendors.name as vendor_name',
            'vendors.mobile',
            'vendors.location',

            DB::raw('COUNT(stock_receivings.id) as total_bills'),

            DB::raw('
                SUM(
                    stock_receivings.grand_total
                    - IFNULL((
                        SELECT SUM(vendor_payments.amount)
                        FROM vendor_payments
                        WHERE vendor_payments.vendor_bill_id = stock_receivings.id
                    ), 0)
                ) as total_due_amount
            '),

            DB::raw('MAX(stock_receivings.created_at) as latest_created_at')
        )
        ->join('vendors', 'stock_receivings.vendor_id', '=', 'vendors.id')

        // only valid vendor bills
        ->whereIn('stock_receivings.status', ['approved', 'approved_with_changes'])

        // optional: ignore fully paid bills if you track it
        // ->whereIn('stock_receivings.payment_status', ['unpaid', 'partial'])

        ->groupBy(
            'vendors.id',
            'vendors.name',
            'vendors.mobile',
            'vendors.location'
        )
        ->having('total_due_amount', '>', 0) // IMPORTANT: show only vendors with due
        ->orderBy('latest_created_at', 'desc')
        ->get();
        // dd($vendorOutstandingList);

    return view('admin.vendor_outstanding.index', compact('vendorOutstandingList'));
}


public function vendorOutstandingPdf($vendorId)
{
    $vendor = Vendor::findOrFail($vendorId);

    /* -------- Payment Term -------- */
    $paymentTerm = VendorPaymentTerm::where('vendor_id', $vendorId)
        ->where('credit_status', 'Active')
        ->first();

    $hasCustomPaymentTerm = false;
    $creditDays = 0;
    $displayCreditText = null;

    if ($paymentTerm) {
        if (!empty($paymentTerm->custom_payment_term)) {

            $parts = [];

            if (!empty($paymentTerm->from_range)) {
                $parts[] = (int) $paymentTerm->from_range;
            }
            if (!empty($paymentTerm->to_range)) {
                $parts[] = (int) $paymentTerm->to_range;
            }
            if (!empty($paymentTerm->days)) {
                $parts[] = (int) $paymentTerm->days;
            }

            $creditDays = array_sum($parts);
            $displayCreditText = implode(' + ', $parts) . ' = ' . $creditDays;
            $hasCustomPaymentTerm = true;
        } else {
            $creditDays = (int) $paymentTerm->due_limit_days;
        }
    }

    /* -------- Vendor Bills -------- */
$bills = VendorBill::where('vendor_id', $vendorId)
    ->orderBy('bill_date', 'asc')
    ->get();

        
       
    $bills->transform(function ($bill) use ($creditDays) {

        $paid = VendorPayment::where('vendor_bill_id', $bill->id)->sum('amount');

        $bill->total_paid = $paid;
        $bill->balance_amount = $bill->grand_total - $paid;

        $receiptDate = \Carbon\Carbon::parse($bill->receipt_date);
        $dueDate = $receiptDate->copy()->addDays($creditDays);
        $today = \Carbon\Carbon::now();

        $daysDiff = $today->diffInDays($dueDate->copy()->addDay(), false);

        if ($daysDiff < 0) {
            $bill->days_text = 'Overdue by ' . abs($daysDiff) . ' days';
            $bill->color = 'red';
        } elseif ($daysDiff > 0) {
            $bill->days_text = 'Due in ' . $daysDiff . ' days';
            $bill->color = ($daysDiff <= 3 ? 'red' : 'orange');
        } else {
            $bill->days_text = 'Today';
            $bill->color = 'green';
        }

        $bill->due_date = $dueDate;

        return $bill;
    });

    $totalOutstanding = $bills->sum('balance_amount');

    /* -------- Generate PDF -------- */
    $pdf = PDF::loadView(
        'admin.vendor_outstanding.pdf',
        compact(
            'vendor',
            'bills',
            'paymentTerm',
            'creditDays',
            'hasCustomPaymentTerm',
            'displayCreditText',
            'totalOutstanding'
        )
    );

    return $pdf->stream('vendor_outstanding.pdf');
}

}
