<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\KYCDocument;
use App\Models\DairyPaymentTerm;
use App\Models\OutletPaymentTerm;
use App\Exports\OverdueOutletExport;
use Illuminate\Http\Request;
use PDF;

class OverdueOutletController extends Controller
{
    private function buildOverdueOutletData($id)
    {
        $orderInvoice = Order::join('outstanding_statements', 'orders.id', '=', 'outstanding_statements.order_id')
            ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
            ->where('orders.outlet_id', $id)
            ->whereIn('orders.payment_status', ['unpaid', 'partial'])
            ->where('delivery_management.delivery_status', 'delivered')
            ->orderBy('orders.created_at', 'asc')
            ->select('orders.*')
            ->get();

        $orderInvoice->transform(function ($order) {

            $payment = Payment::where('order_id', $order->id)->first();

            $order->total_amount   = $order->total_discount_value;
            $order->total_paid     = $payment->total_paid ?? 0;
            $order->balance_amount = $order->total_amount - $order->total_paid;

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

        $userData = User::where('id', $id)
            ->select('credit_limit', 'location', 'mobile_number', 'due_days_limit', 'name', 'outlet_name', 'priority')
            ->first();

        $company_name1 = 'N/A';

        if ($userData && $userData->priority) {
            $company = User::where('id', $userData->priority)->select('outlet_name')->first();
            $company_name1 = $company->outlet_name ?? 'N/A';
        }

        $creditLimit    = $userData->credit_limit ?? 0;
        $location       = $userData->location ?? 'N/A';
        $mobileNumber   = $userData->mobile_number ?? 'N/A';
        $due_days_limit = $userData->due_days_limit ?? 0;

        $orderss = KYCDocument::where('user_id', $id)->get();

        $paymentTerm = OutletPaymentTerm::where('user_id', $id)
            ->where('is_active', 1)
            ->first();

        $hasNewPaymentTerm = $paymentTerm ? true : false;

        return compact(
            'orderInvoice', 'orderss', 'creditLimit', 'location', 'mobileNumber',
            'due_days_limit', 'paymentTerm', 'hasNewPaymentTerm', 'company_name1'
        );
    }

    public function overdueOutletDetail($id)
    {
        $data = $this->buildOverdueOutletData($id);

        return view('admin.reports.overdue_outlet_detail', $data);
    }

    public function overdueOutletDetailPdf($id)
{
    $data = $this->buildOverdueOutletData($id);

    $pdf = PDF::loadView('admin.reports.overdue_outlet_pdf', $data);

    return $pdf->stream('overdue-outlet-' . $id . '.pdf');
}

public function overdueOutletDetailExcel($id)
{
    $data = $this->buildOverdueOutletData($id);

    $outletName = $data['orderInvoice']->first()->user?->outlet_name ?? 'outlet-' . $id;
    $filename   = 'overdue-' . str_replace(' ', '-', strtolower($outletName)) . '.xlsx';

    return \Maatwebsite\Excel\Facades\Excel::download(
        new OverdueOutletExport($data),
        $filename
    );
}
}
