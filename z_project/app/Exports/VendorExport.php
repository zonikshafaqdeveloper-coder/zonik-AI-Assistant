<?php

namespace App\Exports;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VendorExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Vendor::select(
            'name',
            'mobile',
            'email',
            'location',
            'pincode',
            'lead_time',
            'moq_type',
            'pan_number',
            'gst_number',
            'fssai_number'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Vendor Name',
            'Mobile',
            'Email',
            'Location',
            'Pincode',
            'Lead Time',
            'MOQ Type',
            'PAN Number',
            'GST Number',
            'FSSAI Number'
        ];
    }
}