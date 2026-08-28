<?php

namespace App\Exports;

use App\Models\CustomerPrice;
use App\Models\User;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerPriceExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $outletId;
    protected $customerId;
    protected $outlet;
    protected $customerPrices;
    protected $lockedProducts;
    protected $productIds;

    public function __construct($outletId)
    {
        $this->outletId = $outletId;

     
        $this->outlet = User::where('id', $outletId)
            ->where('type', 'outlet')
            ->firstOrFail();

        
        $this->customerId = $this->outlet->priority;

      
        $this->customerPrices = CustomerPrice::where('outlet_id', $outletId)
            ->pluck('product_price', 'product_id')
            ->toArray();

        
        $this->lockedProducts = \DB::table('enquiries')
            ->where('user_id', $this->customerId)
            ->where('status', 'accept')
            ->pluck('offer_price', 'product_id')
            ->toArray();

      
        $this->productIds = array_unique(array_merge(
            array_keys($this->customerPrices),
            array_keys($this->lockedProducts)
        ));
    }

    public function collection()
    {
        return Product::whereIn('id', $this->productIds)
            ->orderBy('product_name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Cost Per Item',
            'Customer Price',
            'Profit Margin (%)',
        ];
    }

    public function map($product): array
    {
        $id   = $product->id;
        $cost = (float) $product->cost_per_item;

       
        if (isset($this->lockedProducts[$id])) {
            $price = (float) $this->lockedProducts[$id];
        } else {
            $price = (float) ($this->customerPrices[$id] ?? 0);
        }

        $margin = 0;
        if ($cost > 0 && $price > 0) {
            $margin = (($price - $cost) / $cost) * 100;
        }

        return [
            $product->product_name,
            number_format($cost, 2, '.', ''),
            number_format($price, 2, '.', ''),
            number_format($margin, 2, '.', '') . '%',
        ];
    }

    public function title(): string
    {
        return 'Price List - ' . $this->outlet->outlet_name;
    }
}
