<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EnquiryExport implements FromCollection, WithHeadings
{
    protected $enquiry_no;

    public function __construct($filters = [])
    {
        $this->enquiry_no = $filters['enquiry_no'] ?? null;
    }

    public function headings(): array
    {
        return [
            'Sr.',
            'Customer Name',
            'Customer No',
            'Enquiry No',
            'Enquiry Date',
            'Outlet Name',
            'Product Name',
            'Unit',
            'Brand',
            'Order Qty (Pattern)',
            'Monthly Consumption',
            'MRP',
            'Cost Per item (Basic)',
            'Total GST (%)',
            'Sale Price (Basic)',
            'Profit Margin',
            'Last Updated Price',
            'Supplier Traced',
            'Customer Comment',
            'Status',
        ];
    }

 public function collection()
{
    $enquiries = \App\Models\Enquiry::with('product', 'user')
        ->orderByDesc('id')
        ->get();

    if ($enquiries->isEmpty()) {
        return collect([['No Data Found']]);
    }

    $data = [];

    foreach ($enquiries as $key => $enquiry) {

        $product = $enquiry->product;
        $user = $enquiry->user;

        if (!$product) continue;

        $totalgst = ($product->cgst ?? 0) + ($product->sgst ?? 0);
        $cost = $product->cost_per_item ?? 1;
        $sale = $enquiry->offer_price ?? 0;

        $profit = $cost > 0 ? (($sale - $cost) / $cost) * 100 : 0;

        $data[] = [
            $key + 1,
            $user->name ?? '',
            $user->mobile_number ?? '',
            $enquiry->enquiry_no ?? '',
            optional($enquiry->created_at)->format('d/m/Y'),
            $user->mobile_number ?? '', // outlet name
            $product->product_name ?? '',
            $product->unit ?? '',
            $product->brands ?? '',
            'Carton (' . ($enquiry->product_types == 1 ? 'Box' : 'Loose') . ') ' . $enquiry->quantity . ' pcs',
            $enquiry->monthlyconsumption ?? '',
            $enquiry->mrp ?? '',
            $cost,
            $totalgst . '%',
            $sale,
            round($profit, 2) . '%',
            optional($enquiry->updated_at)->format('d/m/Y'),
            $product->supplier_traced ?? '',
            $enquiry->counter_comment ?? '',
            $enquiry->status ?? '',
        ];
    }

    return collect($data);
}
}