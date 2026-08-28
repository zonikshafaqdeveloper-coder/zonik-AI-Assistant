<?php

namespace App\Exports;

use App\Models\VendorPriceList;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VendorBulkExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return VendorPriceList::query()
            ->with(['vendor:id,name', 'product:id,product_name'])
            ->select('vendor_id', 'product_id', 'vendor_price');
    }

    public function headings(): array
    {
        return [
            'vendor_name',
            'product_name',
            'vendor_price',
        ];
    }

    public function map($row): array
    {
        return [
            $row->vendor->name ?? '',
            $row->product->product_name ?? '',
            $row->vendor_price,
        ];
    }
}