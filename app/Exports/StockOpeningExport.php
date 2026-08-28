<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\RackStock;
use App\Models\StockMovement;

class StockOpeningExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $rackStocks = RackStock::with('product')
            ->where('quantity', '>', 0)
            ->get();

$movements = StockMovement::whereIn('product_id', $rackStocks->pluck('product_id'))
    ->get()
    ->groupBy(function ($item) {
        return $item->product_id . '_' . $item->batch_no;
    });

return $rackStocks->map(function ($row) use ($movements) {

    $key = $row->product_id . '_' . $row->batch_no;

    $movement = isset($movements[$key])
        ? $movements[$key]->sortByDesc('created_at')->first()
        : null;

    return [
        'product_name' => $row->product->product_name ?? '',
        'batch_no'     => $row->batch_no,
        'expiry_date'  => optional($row->expiry_date)->format('Y-m-d'),
        'quantity'     => $row->quantity,
        'cost_price'   => $movement->unit_cost ?? ($row->product->cost_per_item ?? 0),
        'rack'         => $row->rack_no,
        'level'        => $row->level_no,
        'slot'         => $row->slot_no,
    ];
});

    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Batch No',
            'Expiry Date',
            'Quantity',
            'Cost Price',
            'Rack',
            'Level',
            'Slot'
        ];
    }
}
