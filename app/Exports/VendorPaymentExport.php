<?php

namespace App\Exports;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VendorPaymentExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Vendor::leftJoin('vendor_payment_terms', 'vendors.id', '=', 'vendor_payment_terms.vendor_id')
            ->select(
                'vendors.id',
                'vendors.name',
                'vendors.mobile',
                'vendors.email',
                'vendors.location',
                'vendors.pincode',

                // Payment Terms
                'vendor_payment_terms.credit_status',
                'vendor_payment_terms.credit_limit',
                'vendor_payment_terms.due_limit_days',
                'vendor_payment_terms.verified_status',
                'vendor_payment_terms.from_range',
                'vendor_payment_terms.to_range',
                'vendor_payment_terms.days',
                
            )
            ->get()
            ->map(function ($vendor) {

               

                return $vendor;
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Vendor Name',
            'Mobile',
            'Email',
            'Location',
            'Pincode',

            'Credit Status',
            'Credit Limit',
            'Due Limit Days',
            'Verification Status',
            'From Amount',
            'To Amount',
            'Payment Days',
        
        ];
    }
}