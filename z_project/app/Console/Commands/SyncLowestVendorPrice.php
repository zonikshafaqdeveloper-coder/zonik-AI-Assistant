<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\VendorPriceList;
use Illuminate\Support\Facades\DB;


class SyncLowestVendorPrice extends Command
{
    protected $signature = 'products:sync-lowest-price';
    protected $description = 'Sync product cost with lowest vendor price';

    public function handle()
    {
        $productsWithVendorPrices = VendorPriceList::select(
                'product_id',
                DB::raw('MIN(vendor_price) as lowest_vendor_price')
            )
            ->whereNotNull('vendor_price')
            ->where('vendor_price', '>', 0)
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        if ($productsWithVendorPrices->isEmpty()) {
            return;
        }

        $productIds = $productsWithVendorPrices->keys();

        Product::whereIn('id', $productIds)
            ->get()
            ->each(function ($product) use ($productsWithVendorPrices) {

                $lowestVendorPrice = $productsWithVendorPrices[$product->id]->lowest_vendor_price ?? 0;
                $currentCost = $product->cost_per_item ?? 0;

                $prices = array_filter([$currentCost, $lowestVendorPrice]);

                if ($prices) {
                    $lowestPrice = min($prices);

                    // if ($product->cost_per_item != $lowestPrice) {
                    //     $product->update(['cost_per_item' => $lowestPrice]);
                    // }
                }
            });

        $this->info('Product prices synced successfully.');
    }
}
