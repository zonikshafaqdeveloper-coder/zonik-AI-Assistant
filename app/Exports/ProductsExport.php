<?php namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $ProductsData = collect([
            [
                'ID' => 'ID',
                'HSN Code' => 'HSN Code',
                'Unique Reference ID' => 'Unique Reference ID',
                'Brand' => 'Brand',
                'Title' => 'Title',  // Title as product_name
                'Description' => 'Description',
                'Unit' => 'Unit',
                'Pack (Qty.)' => 'Pack (Qty.)',
                'peices_per_pack' => 'peices_per_pack',
                'Carton Size' => 'Carton Size',
                'Main Category' => 'Main Category',
                'Sub Category' => 'Sub Category',
                'Varieties' => 'Varieties',
                'MRP' => 'MRP',
                'Cost per item (Basic)' => 'Cost per item (Basic)',
                'Loose Price' => 'Loose Price',
                'Cartoon Price' => 'Cartoon Price',
                'Total GST (%)' => 'Total GST (%)',
                'SGST (%)' => 'SGST (%)',
                'CGST (%)' => 'CGST (%)',
                'cess' => 'cess',
                'IGST (%)' => 'IGST (%)',
                'Product Weight (Grams)' => 'Product Weight (Grams)',
                'Supplier Traced' => 'Supplier Traced',
                'Image' => 'Image',
                'types' => 'types',
                'tags' => 'tags',
                'Status' => 'Status',
                'Last Update Price' => 'Last Update Price Date',
                'Units' => 'Units'
            ]
        ]);

        $products = Product::with('category', 'subcategory', 'brand', 'type', 'tag', 'units')->get();

        foreach ($products as $product) {
            
            $units = $product->units->pluck('unit_name')->implode(',');
            
            $ProductsData->push([
                'ID' => $product->id,
                'HSN Code' => $product->hsn_code,
                'Unique Reference ID' => $product->unique_reference_id,
                'Brand' => $product->brands,
                'Title' => $product->product_name,  // Title as product_name
                'Description' => $product->description,
                'Unit' => $product->unit,
                'Pack (Qty.)' => $product->product_quantity,
                'peices_per_pack' => $product->peices_per_pack,
                'Carton Size' => $product->carton_size,
                // 'Main Category' => $product->category->category_name,
                // 'Sub Category' => $product->subcategory->name,
                'Main Category' => $product->category ? $product->category->category_name : null,
                'Sub Category' => $product->subcategory ? $product->subcategory->name : null,
                'Varieties' => $product->varieties,
                'MRP' => $product->product_mrp,
                'Cost per item (Basic)' => $product->cost_per_item,
                'Loose Price' => $product->sale_price_loose_pcs,
                'Cartoon Price' => $product->sale_price_carton,
                'Total GST (%)' => $product->gst,
                'SGST (%)' => $product->sgst,
                'CGST (%)' => $product->cgst,
                'cess' => $product->cess,
                'IGST (%)' => $product->igst,
                'Product Weight (Grams)' => $product->product_weight_grams,
                'Supplier Traced' => $product->supplier_traced,
                'Image' => $product->image,
                'types' => $product->types,
                'tags' => $product->tags,
                'Status' => $product->status,
                'Last Update Price Date' => $product->last_update_price,
                 'Units' => $units
            ]);
        }
        return $ProductsData;
    }
}
