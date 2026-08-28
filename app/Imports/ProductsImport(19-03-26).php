<?php
namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Tag;
use App\Models\Type;
use App\Models\ProductUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        
             function excelDateToYMD($value)
{
    if (is_numeric($value)) {
        return \Carbon\Carbon::createFromDate(1899, 12, 30)->addDays($value)->format('Y-m-d');
    }
    return $value;
}

        foreach ($rows as $key => $row) {
            if ($key != 0) {
                
                //delete row
                $action = isset($row[27]) ? strtolower(trim((string) $row[27])) : null;

                if ($action === 'delete') {
                    Product::where('id', (int) $row[0])->forceDelete();
                    continue;
                }

                
                $total_with_tax = is_numeric($row[13]) ? (float) $row[13] : 0;
                $hsn_code = $row[1];

                $sale_price_loose_pcs = $row[15];

                $lastRecord = Product::latest()->first();
                $lastReferenceId = $lastRecord ? $lastRecord->unique_reference_id : '';

                $brandFirstThreeLetters = $row[3];
                $categoryFirstThreeLetters = Str::lower(substr($row[10], 0, 3));
                if (Str::startsWith($lastReferenceId, $categoryFirstThreeLetters . '-' . $brandFirstThreeLetters)) {
                    preg_match('/\d+$/', $lastReferenceId, $matches);
                    $lastNumber = $matches ? (int)$matches[0] : 0;

                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }
                $unique_reference_id = $categoryFirstThreeLetters . '-' .$brandFirstThreeLetters . '-' .  $newNumber;

                 $loose_discount_basic = 0; // Default value in case of zero division
                
                if ($total_with_tax != 0) {
                    $loose_discount_basic = (($total_with_tax - $sale_price_loose_pcs) / $total_with_tax) * 100;
                }


                $sale_price_carton_basic = is_numeric($row[16]) ? (float) $row[16] : 0;
                $product = Product::where('id', $row[0])->first();
                $category = Category::where('category_name', $row[10])->first();
                if (!$category) {
                    // Sanitize input to prevent issues with special characters or leading/trailing spaces
                    $categoryName = trim($row[10]);
                    $categorySlug = Str::slug($categoryName);

                    // Create the category if it doesn't exist
                    $category = Category::create([
                        'category_name' => $categoryName,
                        'image' => '1718876959.jpg',
                        'category_slug' => $categorySlug
                    ]);
                }
            // Calculate loose discount percentage
            if ($total_with_tax != 0) {
    $loose_discount_basic = (($total_with_tax - $sale_price_loose_pcs) / $total_with_tax) * 100;
} else {
    $loose_discount_basic = 0; // Set a default value when division by zero would occur
}

           if ($total_with_tax != 0) {
    $carton_discount_basic = (($total_with_tax - $sale_price_carton_basic) / $total_with_tax) * 100;
} else {
    $carton_discount_basic = 0; // Default value when total_with_tax is zero
}

            $total_discount = ($loose_discount_basic + $carton_discount_basic) / 2;




               $subcat = null;
               $subcat = Subcategory::where('name', $row[11])->where('category_id', $category->id)->first();
                if (!$subcat) {
                    $subcat = Subcategory::create([
                        'name' => $row[11],
                        'category_id' => $category->id,
                        'slug' => Str::slug($row[11]),
                        'image' => '1718876959.jpg'
                    ]);
                }

                // if ($category) {
                //     $subcat = Subcategory::where('name', $row[11])->where('category_id', $category->id)->first();
                //     if (!$subcat) {
                //         $subcat = Subcategory::create([
                //             'name' => $row[11],
                //             'category_id' => $category->id,
                //             'slug' => $row[11],
                //             'image' => '1718876959.jpg'
                //         ]);
                //     }
                // } else {
                //     // Category not found, create both category and subcategory
                //     $category = Category::create(['category_name' => $row[10]]);
                //     $subcat = Subcategory::create([
                //         'name' => $row[11],
                //         'category_id' => $category->id,
                //           'image' => '1718876959.jpg'
                //     ]);
                // }
                $product_name = $row[4];
                $product_id = $row[0];
                $units = isset($row[29]) ? explode(',', $row[29]) : [];

                if ($product) {
                    // if ($row[14] && $row[14] !== $product->cost_per_item) {
                    //     $n = $row[14] - $product->cost_per_item;
                    //     Enquiry::where('status', 'accept')
                    //         ->where('product_id', $product_id)
                    //         ->where('product_types', 1)
                    //         ->update([
                    //             'offer_price' => $sale_price_carton_basic,
                    //             'status' => 'submitted',
                    //             'alert' => 'active',
                    //             'mrp' => $row[13]
                    //         ]);
                    //     Enquiry::where('status', 'accept')
                    //         ->where('product_id', $product_id)
                    //         ->where('product_types', 2)
                    //         ->update([
                    //             'offer_price' => $sale_price_loose_pcs,
                    //             'status' => 'submitted',
                    //             'alert' => 'active',
                    //             'mrp' => $row[13]
                    //         ]);
                    // }

                    $product->update([
                        'id' => $row[0],
                        'hsn_code' => $hsn_code,
                        'unique_reference_id' => $unique_reference_id,
                        'brands' => $row[3],
                        'product_name' => $row[4],  // Title as product_name
                        'description' => $row[5],
                        'unit' => $row[6],
                        'product_quantity' => $row[7],
                        'peices_per_pack' => $row[8]||'1' ,
                        'carton_size' => $row[9],
                        'category_id' => $category->id,
                        'subcategory_id' => $subcat->id,
                        'varieties' => $row[12],
                        'product_mrp' => $row[13],
                        'cost_per_item' => $row[14],
                        'gst' => $row[15],
                        'total_with_tax' => $total_with_tax,
                        'sgst' => $row[18],
                        'cgst' => $row[19],
                        'cess' => $row[20],
                        'igst' => $row[21],
                        'sale_price_loose_pcs' => $sale_price_loose_pcs,
                        'sale_price_carton' => $sale_price_carton_basic,
                        'sale_price_carton_old' => $product->product_mrp,
                        'sale_price_loose_pcs_old' => $product->cost_per_item,
                        'product_weight_grams' => $row[22],
                        'supplier_traced' => $row[23],
                        'image' => $row[24],
                        'types' => $row[25],
                        'tags' => $row[26],
                        'loose_discount_basic' => $loose_discount_basic,
                        'carton_discount_basic' => $carton_discount_basic,
                        'total_discount' => $total_discount,
                        'status' => $row[27],
                        'last_update_price' => excelDateToYMD($row[28]),
                        'slug' => Str::slug($row[4]),
                    ]);
                     ProductUnit::where('product_id', $product_id)->delete();
                } else {
                   $product = Product::create([
                        'id' => $row[0],
                        'hsn_code' => $hsn_code,
                        'unique_reference_id' => $unique_reference_id,
                        'brands' => $row[3],
                        'product_name' => $row[4],  // Title as product_name
                        'description' => $row[5],
                        'unit' => $row[6],
                        'product_quantity' => $row[7],
                        'peices_per_pack' => $row[8],
                        'carton_size' => $row[9],
                        'category_id' => $category->id,
                        'subcategory_id' => $subcat->id,
                        'varieties' => $row[12],
                        'product_mrp' => $row[13],
                        'cost_per_item' => $row[14],
                        'gst' => $row[15],
                        'total_with_tax' => $total_with_tax,
                        'sgst' => $row[18],
                        'cgst' => $row[19],
                        'cess' => $row[20],
                        'igst' => $row[21],
                        'sale_price_loose_pcs' => $sale_price_loose_pcs,
                        'sale_price_carton' => $sale_price_carton_basic,
                        'sale_price_carton_old' => null,
                        'sale_price_loose_pcs_old' => null,
                        'product_weight_grams' => $row[22],
                        'supplier_traced' => $row[23],
                        'image' => '1718876959.jpg',
                        'types' => $row[25],
                        'tags' => $row[26],
                        'loose_discount_basic' => $loose_discount_basic,
                        'carton_discount_basic' => $carton_discount_basic,
                        'total_discount' => $total_discount,
                        'status' => 'active',
                        'last_update_price' => excelDateToYMD($row[28]),
                        'slug' => Str::slug($row[4]),
                    ]);
                }
                
                 foreach ($units as $unit) {

                    $unit = trim($unit);

                    if ($unit != '') {

                        ProductUnit::create([
                            'product_id' => $product->id,
                            'unit_name' => $unit,
                        ]);

                    }

                }
            }
        }
    }
}
