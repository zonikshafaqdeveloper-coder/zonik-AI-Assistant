<?php

namespace App\Imports;

use App\Models\Enquiry;
use App\Models\Product;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class EnquiryImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        // dd($rows);
        foreach ($rows as $key => $row) {
            if ($key != 0 && empty(!$row)) {
                
                if (preg_match('/\d+/', $row[9], $matches)) {
                    // Extracted number is in $matches[0]
                    $number = (int) $matches[0];
                } else {
                    dd("No numbers found in the string");
                }
                $Enquiry = Enquiry::find($row[23]);
                if ($Enquiry) {
                    $Enquiry->update([
                        'unique_reference_id' => $row[0],
                        'enquiry_no' => $row[1],
                        'created_at' => $row[2],
                        'outlet_name' => $row[3],
                        'user_id' => User::where('name', $row[4])->pluck('id')->first(),
                        'contact_no' => $row[5],
                        'product_id' => Product::where('product_name', $row[6])->pluck('id')->first(),
                        'unit' => $row[7],
                        'brands' => $row[8],
                        'quantity' => $number,
                        'monthlyconsumption' => $row[10],
                        'mrp' => $row[11],
                        'gst' => $row[12],
                        'cost_per_item' => $row[13],
                        'sale_price_carton' =>  $row[14],
                        'profit_margin' => $row[15],
                        'carton_discount_basic' => $row[16],
                        'updated_at' => $row[17],
                        'supplier_traced' => $row[18],
                        'Rejected' => $row[19],
                        'Rejected Customer Comment' => $row[20],
                        'status' => $row[21],
                        'product_types' => $row[22] == 'Box' ? 1 : 2,
                        'id' => $row[23],

                    ]);
                }
            }
        }
    }
}
