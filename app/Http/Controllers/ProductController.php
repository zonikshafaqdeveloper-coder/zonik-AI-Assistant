<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Product_subcat_brand;
use App\Models\Tag;
use App\Models\Type;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category', 'subcategory', 'brand', 'type', 'tag')->latest()->get(); // Fetch categories from the database
        return view('admin.products.index', compact('products'));
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $subcategories = Subcategory::all(); // Fetch categories from the database
        $categories = Category::all();
        $brands = Brand::all();
        $types = Type::all();
        $tags = Tag::all();

        return view('admin.products.create', compact('subcategories', 'categories', 'brands', 'types', 'brands', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $request->validate([
            'product_name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust mime types and size as needed
        ]);



        // $selectedCategoryIds = $request->input('brands');
        $data = $request->all();
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('uploads/', $filename);
            $data['image'] = $filename;
        }



        Product::create($data);
        // $product->save();

        // foreach ($selectedCategoryIds as $a)
        // {
        //     // Assuming you have a model named CategorySelection to store the selections
        //     Product_subcat_brand::create([
        //         'product_id' => $product->id, // If you have user authentication
        //         'brand_id' => $categoryId,
        //     ]);
        // }

        return redirect()->route('products.index')->with('success', 'Product added successfully.');
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

        return view('admin.products.edit', compact('product', 'subcategories', 'categories', 'brands', 'types', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_quantity' => 'nullable|numeric',
            'product_mrp' => 'nullable|numeric',
            'offer_price' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'subcategory_id' => 'required|exists:subcategories,id',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'nullable|string|max:255',
            'peices_per_pack' => 'nullable|string|max:255',
            'carton_size' => 'nullable|string|max:255',
            'varieties' => 'nullable|string|max:255',
            'cost_per_item' => 'nullable|numeric',
            'gst' => 'nullable|numeric',
            'sgst' => 'nullable|numeric',
            'cgst' => 'nullable|numeric',
            'igst' => 'nullable|numeric',
            'cess' => 'nullable|numeric',
            'total_with_tax' => 'nullable|numeric',
            'sale_price_loose_pcs' => 'nullable|numeric',
            'sale_price_carton' => 'nullable|numeric',
            'product_weight_grams' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
            'types' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'brands' => 'nullable|string|max:255',
            'product_slug' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($id);
        $product->fill($request->except(['_token', '_method']));

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

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
        $product->delete();
        return redirect()->route('productss.index')->with('success', 'Product Deleted Successfully.');
    }

    public function productImportFiles(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        Storage::disk('public')->put($request->file->getClientOriginalName(), file_get_contents($request->file));

        Excel::import(new ProductsImport, $request->file->getClientOriginalName(), 'public');

        File::delete(storage_path('app/public/' . $request->file->getClientOriginalName()));

        return redirect()->route('products.index')->with('success', 'Product Multiple added successfully.');
    }
}
