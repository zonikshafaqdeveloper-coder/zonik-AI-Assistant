<?php

namespace App\Exports;

use App\Models\Vendor;
use App\Models\VendorPriceList;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class VendorPriceExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $vendorId;
    protected $vendor;

    public function __construct($vendorId)
    {
        $this->vendorId = $vendorId;
        $this->vendor   = Vendor::findOrFail($vendorId);
    }

    public function collection()
    {
        return VendorPriceList::with('product')
            ->where('vendor_id', $this->vendorId)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Purchase Item',
            'Vendor Price',
            'Profit Margin (%)',
        ];
    }

    public function map($row): array
    {
        $cost  = (float) ($row->product->cost_per_item ?? 0);
        $price = (float) ($row->vendor_price ?? 0);

        $margin = 0;

        if ($cost > 0 && $price > 0) {
            $margin = (($price - $cost) / $cost) * 100;
        }

        return [
            $row->product->product_name ?? '',
            number_format($cost, 2, '.', ''),
            number_format($price, 2, '.', ''),
            number_format($margin, 2, '.', '') . '%',
        ];
    }

    public function title(): string
    {
        return 'Price List - ' . $this->vendor->name;
    }
}
