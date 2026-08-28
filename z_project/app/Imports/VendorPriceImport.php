<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorPriceList;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VendorPriceImport implements ToCollection, WithHeadingRow
{
    private function normalize($string)
    {
        return strtolower(preg_replace('/[^A-Za-z0-9]/', '', $string));
    }

    public function collection(Collection $rows)
    {
        
        $products = Product::all()->mapWithKeys(function ($p) {
            $key = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $p->product_name));
            return [$key => $p->id];
        });

       
        $vendors = Vendor::pluck('id', 'name');

        foreach ($rows as $row) {

           
            if (
                empty($row['vendor_name']) ||
                empty($row['product_name']) ||
                empty($row['vendor_price'])
            ) {
                continue;
            }

           
            $vendorName  = trim($row['vendor_name']);
            $productName = trim($row['product_name']);
            $cleanProductName = $this->normalize($productName);

            
            $vendorId = $vendors[$vendorName] ?? null;

            if (!$vendorId) {
                $vendor = Vendor::create([
                    'name' => $vendorName
                ]);

                $vendorId = $vendor->id;
                $vendors[$vendorName] = $vendorId;
            }

            
            $productId = $products[$cleanProductName] ?? null;

           
            if (!$productId) {
                foreach ($products as $key => $id) {
                    if (str_contains($key, $cleanProductName)) {
                        $productId = $id;
                        break;
                    }
                }
            }

          
            if (!$productId) {
                Log::warning('Product not found during import', [
                    'excel_name' => $productName
                ]);
                continue;
            }

           
            $price = $row['vendor_price'];

            if (!is_numeric($price) || $price <= 0) {
                continue;
            }

          
            VendorPriceList::updateOrCreate(
                [
                    'vendor_id'  => $vendorId,
                    'product_id' => $productId,
                ],
                [
                    'vendor_price' => $price,
                ]
            );
        }
    }
}