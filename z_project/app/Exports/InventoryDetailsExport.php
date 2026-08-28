<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryDetailsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $details;
    protected $reportType;
    protected $pageTitle;

    public function __construct($details, $reportType, $pageTitle)
    {
        $this->details    = $details;
        $this->reportType = $reportType;
        $this->pageTitle  = $pageTitle;
    }

    public function collection()
    {
        return collect($this->details);
    }

    public function headings(): array
    {
        if ($this->reportType === 'reorder') {
            return [
                'Product', 'Brand', 'Category', 'Vendor', 'Carton Size',
                'Current Stock', 'Scheme', 'Last 30 Days Sales', 'Daily Consumption',
                'ROP (Nos)', 'ROP (Boxes)', 'Status', 'ROQ (Nos)', 'ROQ (Boxes)',
                'Investment (₹)', 'Price (₹)',
            ];
        }

        // 'stock' layout — dynamic headers derived from the first row's keys,
        // since expired/near_expiry/non_moving builders aren't visible here.
        // Update this list once you confirm the exact fields those methods return.
        $first = collect($this->details)->first();
        return $first ? array_map(fn($k) => ucwords(str_replace('_', ' ', $k)), array_keys((array) $first)) : ['No Data'];
    }

    public function map($row): array
    {
        $row = (array) $row;

        if ($this->reportType === 'reorder') {
            return [
                $row['product'] ?? '',
                $row['brand'] ?? '',
                $row['category'] ?? '',
                $row['vendor_name'] ?? '',
                $row['carton_size'] ?? '',
                $row['stock'] ?? 0,
                $row['scheme'] ?? '',
                $row['last_30_days'] ?? 0,
                $row['daily_consumption'] ?? 0,
                $row['rop_nos'] ?? 0,
                $row['rop_boxes'] ?? 0,
                $row['status'] ?? '',
                $row['roq_nos'] ?? 0,
                $row['roq_boxes'] ?? 0,
                number_format($row['investment'] ?? 0, 2),
                number_format($row['price'] ?? 0, 2),
            ];
        }

      
        return array_values($row);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}