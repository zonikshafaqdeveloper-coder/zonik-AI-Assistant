<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Product;
use App\Models\CustomerPrice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CustomerPriceImport implements ToCollection
{
public function collection(Collection $rows)
{
    $rows = $rows->skip(1);

    foreach ($rows as $row) {

        $outletName  = trim($row[0] ?? '');
        $productName = trim($row[1] ?? '');
        $price       = $row[2] ?? null;

        if (!$outletName || !$productName || !is_numeric($price)) {
            continue;
        }

        $outlet = User::where('type', 'outlet')
            ->where('outlet_name', 'LIKE', "%{$outletName}%")
            ->first();

            $cleanExcelName = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $productName));

            $product = Product::all()->first(function ($p) use ($cleanExcelName) {
                $dbName = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $p->product_name));
                return $dbName === $cleanExcelName;
            });

        if (!$outlet || !$product) {
            continue;
        }

        $customerId = $outlet->priority;

        if (!$customerId) {
            continue;
        }

        CustomerPrice::updateOrCreate(
            [
                'customer_id' => $customerId,
                'outlet_id'   => $outlet->id,
                'product_id'  => $product->id,
            ],
            [
                'product_price' => $price,
            ]
        );
    }
}
}