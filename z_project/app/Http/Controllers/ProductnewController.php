<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Brand;
use App\Models\Product_subcat_brand;
use App\Models\Tag;
use App\Models\Type;
use App\Models\Vendor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Cache;

class ProductnewController extends Controller
{
    
    //added on 10-05-26
    // public function index()
    // {
    //     $products = Product::with('category', 'subcategory', 'brand', 'type', 'tag', 'vendor')
    //     ->latest()
    //     // ->where('status', 'active') 
    //     // ->whereNull('deleted_at')
    //     ->select('*', 'updated_at')
    //     ->get();
    //     return view('admin.newproductss.index', compact('products'));
    // }
    
    public function index(Request $request)
{
    if ($request->ajax()) {

        $query = Product::with([
            'category:id,category_name',
            'subcategory:id,name',
             'vendor:id,name' 
        ])->select([
    'id',
    'product_name',
    'category_id',
    'subcategory_id',
    'vendor_id',
    'unit',
    'product_quantity',
    'peices_per_pack',
    'carton_size',
    'product_mrp',
    'cost_per_item',
    'gst',
    'sgst',
    'cgst',
    'igst',
    'cess',
    'sale_price_loose_pcs',
    'sale_price_carton',
    'product_weight_grams',
    'carton_discount_basic',
    'loose_discount_basic',
    'brands',
    'types',
    'tags',
    'status',
    'image',
    'updated_at',
    'total_with_tax'
])->orderByDesc('updated_at'); 

        return DataTables::of($query)

            ->addIndexColumn()

            // 🖼️ Product Image
            ->addColumn('image', function ($p) {
                if ($p->image) {
                    return '<img src="'.asset('uploads/'.$p->image).'" width="50">';
                }
                return '-';
            })

            // 📦 Relations
           ->addColumn('category_name', fn($p) => $p->category->category_name ?? '-')
            ->addColumn('subcategory_name', fn($p) => $p->subcategory->name ?? '-')
            ->addColumn('brand_name', fn($p) => $p->brands ?? '-')
            ->addColumn('type_name', fn($p) => $p->types ?? '-')
            ->addColumn('tag_name', fn($p) => $p->tags ?? '-')
            ->addColumn('total_cost_with_tax', fn($p) => $p->total_with_tax ?? '-')
            ->addColumn('supplier', function ($p) {
                    return $p->vendor ? $p->vendor->name : 'N/A';
                })

          

            // 📅 Date
            ->editColumn('updated_at', function ($p) {
                return $p->updated_at->format('d-m-Y H:i');
            })

            // 🔴 Status
           ->addColumn('status', function ($p) {
    return $p->status === 'active'
        ? '<span class="badge bg-success">Active</span>'
        : '<span class="badge bg-danger">Inactive</span>';
})

            // ⚙️ Action (Edit + Delete)
            ->addColumn('action', function ($p) {
                return '
                    <a href="'.route('productss.edit', $p->id).'" class="btn btn-sm btn-primary">Edit</a>

                    <button class="btn btn-sm btn-danger delete-btn"
                        data-id="'.$p->id.'">
                        Delete
                    </button>
                ';
            })

            ->rawColumns(['image', 'status', 'action'])
            ->make(true);
    }

    return view('admin.newproductss.index');
}





    public function create()
    {
        //
        $subcategories = Subcategory::all(); // Fetch categories from the database
        $categories = Category::all();
        $brands = Brand::all();
        $types = Type::all();
        $tags = Tag::all();
         $vendors = Vendor::all();

        return view('admin.newproductss.create', compact('subcategories', 'categories', 'brands', 'types', 'brands', 'tags', 'vendors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {

    //     $request->validate([
    //         'product_name' => 'required|string|max:255',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust mime types and size as needed
    //     ]);


    //     // $selectedCategoryIds = $request->input('brands');
    //     $data['total_discount'] = $request->discount;
    //     $data = $request->all();

    //     if ($request->hasfile('image')) {
    //         $file = $request->file('image');
    //         $extenstion = $file->getClientOriginalExtension();
    //         $filename = time() . '.' . $extenstion;
    //         $file->move('uploads/', $filename);
    //         $data['image'] = $filename;
    //     }


    //     Product::create($data);
        // $product->save();

        // foreach ($selectedCategoryIds as $a)
        // {
        //     // Assuming you have a model named CategorySelection to store the selections
        //     Product_subcat_brand::create([
        //         'product_id' => $product->id, // If you have user authentication
        //         'brand_id' => $categoryId,
        //     ]);
        // }

        // return redirect()->route('products.index')->with('success', 'Product added successfully.');
    // }
    
    
     public function store(Request $request)
{
    // Validation with rules matching your database structure
    $validated = $request->validate([
        // Required fields
        'product_name' => 'required|string|max:255',
        'unique_reference_id' => 'required|string|max:255|unique:products,unique_reference_id',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'category_id' => 'required|exists:categories,id',
        'subcategory_id' => 'required|exists:subcategories,id',
        // 'slug' => 'required|string|unique:products,slug',
        'product_mrp' => 'required|numeric|min:0',
        'sale_price_loose_pcs' => 'required|numeric|min:0',
        'status' => 'required|in:active,inactive',
        
        // Numeric fields (nullable)
        'product_quantity' => 'nullable|numeric|min:0',
        'hsn_code' => 'nullable|string|max:250',
        'cost_per_item' => 'nullable|numeric|min:0',
        'gst' => 'nullable|string|max:11',
        'cgst' => 'nullable|string|max:255',
        'sgst' => 'nullable|string|max:255',
        'igst' => 'nullable|string|max:255',
        'cess' => 'nullable|string|max:255',
        'total_with_tax' => 'nullable|string|max:255',
        'sale_price_carton' => 'nullable|numeric|min:0',
        'product_weight_grams' => 'nullable|numeric|min:0',
        'peices_per_pack' => 'nullable|integer|min:0',
        'carton_size' => 'nullable|numeric|min:0',
        'total_discount' => 'nullable|string|max:255',
        
        // String fields (nullable)
        'description' => 'nullable|string',
        'unit' => 'nullable|string|max:255',
        'varieties' => 'nullable|string|max:255',
        'types' => 'nullable|string|max:255',
        'tags' => 'nullable|string|max:255',
        'brands' => 'nullable|string|max:255',
        // 'supplier_traced' => 'nullable|string|max:255',
        'vendor_id' => 'nullable|exists:vendors,id',
        'new_vendor' => 'nullable|string|max:255',
    ]);

    try {
        // Set default values for NOT NULL fields
        $validated['peices_per_pack'] = $validated['peices_per_pack'] ?? 1;
        $validated['brand_id'] = 1; // Default as per your table
        $validated['loose_discount_basic'] = 0;
        $validated['carton_discount_basic'] = 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Create uploads directory if it doesn't exist
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0755, true);
            }
            
            $image->move(public_path('uploads'), $filename);
            $validated['image'] = $filename;
        } else {
            $validated['image'] = 'default Image.jpg'; // Default as per your table
        }

        // Calculate total_with_tax if not provided
        if (empty($validated['total_with_tax']) && !empty($validated['cost_per_item'])) {
            $baseAmount = $validated['cost_per_item'];
            $cgst = floatval($validated['cgst'] ?? 0);
            $sgst = floatval($validated['sgst'] ?? 0);
            $igst = floatval($validated['igst'] ?? 0);
            $gstRate = $cgst + $sgst + $igst;
            $validated['total_with_tax'] = $baseAmount + (($baseAmount * $gstRate) / 100);
        }
        
        
                $vendorId = null;
        $supplierName = null;

           
        if ($request->vendor_id) {

            $vendor = Vendor::find($request->vendor_id);

            if ($vendor) {
                $vendorId = $vendor->id;
                $supplierName = $vendor->name; 
            }
        }

        // CASE 2: New vendor typed
        elseif ($request->new_vendor) {

            $vendor = Vendor::firstOrCreate(
                ['name' => trim($request->new_vendor)],
                [
                    'lead_time' => 0,
                    'moq_type' => 'LOOSE'
                ]
            );

            $vendorId = $vendor->id;
            $supplierName = $vendor->name;
        }

        $validated['vendor_id'] = $vendorId;
        $validated['supplier_traced'] = $supplierName;

        // Create product
        $product = Product::create($validated);
        
         if ($request->units) {

            foreach ($request->units as $unit) {

                $unit = trim($unit);

                if ($unit != '') {

                    ProductUnit::create([
                        'product_id' => $product->id,
                        'unit_name' => $unit,
                    ]);

                }

            }

        }

        return redirect()
            ->route('productss.index')
            ->with('success', 'Product added successfully.');

    } catch (\Exception $e) {
        // Delete uploaded image if product creation fails
        if (isset($filename) && file_exists(public_path('uploads/' . $filename))) {
            unlink(public_path('uploads/' . $filename));
        }

        Log::error('Product creation failed: ' . $e->getMessage());
        
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Failed to create product: ' . $e->getMessage());
    }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $subcategories = Subcategory::all();
        $categories = Category::all();
        $brands = Brand::all();
        $types = Type::all();
        $tags = Tag::all();
        $vendors = Vendor::all();

        return view('admin.newproductss.edit', compact('product', 'subcategories', 'categories', 'brands', 'types', 'tags', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function update(Request $request, $productId)
{
    // Fetch the product by ID
    $product = Product::findOrFail($productId);

    

    // Prepare the data to update
    $data = $request->except(['_token', '_method']); // Exclude unnecessary fields

    // Handle image upload if a new image is provided
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename); // Move the uploaded file to a publicly accessible directory
        $data['image'] = $filename; // Update the 'image' field in the data array with the new filename
    }

    // Debugging purpose
    // dd($product, $data);
    
  $vendorId = null;
$supplierName = null;


if (!empty(trim($request->new_vendor))) {

    $vendor = Vendor::firstOrCreate(
        ['name' => trim($request->new_vendor)],
        [
            'lead_time' => 0,
            'moq_type' => 'LOOSE'
        ]
    );

    $vendorId = $vendor->id;
    $supplierName = $vendor->name;
}


elseif (!empty($request->vendor_id)) {

    $vendor = Vendor::find($request->vendor_id);

    if ($vendor) {
        $vendorId = $vendor->id;
        $supplierName = $vendor->name;
    }
}

// Assign
$data['vendor_id'] = $vendorId;
$data['supplier_traced'] = $supplierName;


        // Update the product with the new data
        $product->update($data);
        
          ProductUnit::where('product_id',$product->id)->delete();

    if ($request->units) {

        foreach ($request->units as $unit) {

            $unit = trim($unit);

            if ($unit != '') {

                ProductUnit::create([
                    'product_id' => $product->id,
                    'unit_name' => $unit
                ]);

            }

        }
    }
    
Cache::forget('categories_with_products_v2');
Cache::forget('all_products');
Cache::forget('subcategories_with_active_products');
Cache::forget('active_brands');
Cache::forget('active_banners');
Cache::forget('active_festival_and_offers');

        // Redirect back with a success message
        return redirect()->route('productss.index')->with('success', 'Product updated successfully.');
   
}




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 public function destroy($id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found'
        ], 404);
    }

    $product->delete();

    return response()->json([
        'success' => true,
        'message' => 'Product deleted successfully'
    ]);
}

    public function productImportFiles(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        Storage::disk('public')->put($request->file->getClientOriginalName(), file_get_contents($request->file));

        Excel::import(new ProductsImport, $request->file->getClientOriginalName(), 'public');

        File::delete(storage_path('app/public/' . $request->file->getClientOriginalName()));

        return redirect()->route('productss.index')->with('success', 'Product Multiple added successfully.');
    }
}
