<?php

namespace App\Exports;

use App\Models\CustomerPrice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class CustomerPriceBulkExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return CustomerPrice::query()
            ->join('users as outlets', 'outlets.id', '=', 'customer_prices.outlet_id')
            ->join('products', 'products.id', '=', 'customer_prices.product_id')

            ->orderByDesc('customer_prices.id')

                ->select([
                    'outlets.outlet_name as outlet_name',
                    'products.product_name as product_name',
                    'products.cost_per_item',
                    'customer_prices.product_price as customer_price',

                    
                    \DB::raw('(customer_prices.product_price - products.cost_per_item) as profit_amount'),

                  
                    \DB::raw("
                        CASE 
                            WHEN products.cost_per_item > 0 
                            THEN ROUND(
                                ((customer_prices.product_price - products.cost_per_item) 
                                / products.cost_per_item) * 100, 2
                            )
                            ELSE 0
                        END as profit_margin
                    ")
                ])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Outlet Name',
            'Product Name',
            'Cost Per Item',
            'Customer Price',
            'Profit Amount',
            'Profit Margin (%)'
        ];
    }
}