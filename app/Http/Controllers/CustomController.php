<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;

class CustomController extends Controller
{


    // CustomAuthController.php
    public function categoriess()
    {
        $categories = Category::all();
        return view('web.lay.index', compact('categories'));
    }


    // public function index(Request $request)
    // {
    // $categories = Category::all();
    // $selectedCategoryId = $request->query('category_id');


    //     if ($selectedCategoryId)
    //      {
    //         $selectedCategory = Category::find($selectedCategoryId);
    //         $subcategories = $selectedCategory->subcategories;
    //         $products = Product::whereIn('subcategory_id', $subcategories->pluck('id'))->get();
    //      } else
    //      {
    //         $subcategories = collect();
    //         $products = collect();
    //      }

    //       return view('web.lay.home', compact('categories', 'subcategories', 'products'));

    //     }



    public function index(Request $request)
    {

        $categories = Category::all();
        $selectedCategoryId = $request->query('category_id');
        $selectedBrandCategoryId = $request->query('brand_id');

        if ($selectedCategoryId) {
            $selectedCategory = Category::find($selectedCategoryId);
            $subcategories = $selectedCategory->subcategories;

            if ($selectedBrandCategoryId) {
                $selectedBrandCategory = BrandCategory::find($selectedBrandCategoryId);
                $brands = $selectedBrandCategory->brands;

                $products = Product::whereIn('subcategory_id', $subcategories->pluck('id'))
                    ->whereIn('brand_id', $brands->pluck('id'))
                    ->get();
            } else {
                $brands = collect();
                $products = Product::whereIn('subcategory_id', $subcategories->pluck('id'))->get();
            }
        } else {
            $subcategories = collect();
            $brands = collect();
            $products = collect();
        }

        return view('web.lay.home', compact('categories', 'subcategories', 'brands', 'products'));
    }

    public function indexx(Category $category)
    {
        // Load subcategories for the selected category
        $subcategories = $category->subcategories;

        return view('web.lay.subcategories', compact('subcategories'));
    }


    public function products(Subcategory $subcategory)
    {
        // Load products for the selected subcategory

        $products = $subcategory->products;

        return view('web.lay.products', compact('products'));
    }

    public function filterProducts(Request $request)
    {

        $selectedBrandItems = $request->branditems;
        $selectedCategoryId = $request->selectedCategoryId;
        $selectedSubCategoryId = $request->sub_category_id;


        //  $query = Product::query();
        
        $query = Product::where('status', 'active');

        if ($selectedSubCategoryId == null) {
            $query->where('category_id', $selectedCategoryId);
        } else {
            $query->where('subcategory_id', $selectedSubCategoryId);
        }

        if ($selectedBrandItems) {
            $query->where(function ($query) use ($selectedBrandItems) {
                foreach ($selectedBrandItems as $brand) {
                    $query->orWhere('brands', 'LIKE', "%$brand%");
                }
            });
        }

        $products = $query->get();

        // dd($products);

        return view('web.lay.products', compact('products'));
    }

    public function filterTypeProducts(Request $request)
{
    $selectedTypes = $request->typeitems;
    $selectedCategoryId = $request->selectedCategoryId;
    $selectedTypeSubCategoryId = $request->type_sub_category_id;

    // Fetch all products first
    //  $query = Product::query();

   $query = Product::where('status', 'active');

    if ($selectedTypeSubCategoryId == null) {
        $query->where('category_id', $selectedCategoryId);
    } else {
        $query->where('subcategory_id', $selectedTypeSubCategoryId);
    }

    $allProducts = $query->get();

    // Filter products based on selected types
    if ($selectedTypes) {
        $filteredProducts = $allProducts->filter(function ($product) use ($selectedTypes) {
            foreach ($selectedTypes as $type) {
                if (stripos($product->types, $type) !== false) {
                    return true;
                }
            }
            return false;
        });

        $products = $filteredProducts->values()->all(); // Reset keys for indexed array
    } else {
        $products = $allProducts->all(); // No filtering needed
    }
// dd($products);
    return view('web.lay.products', compact('products'));
}

    public function filterTagProducts(Request $request)
    {


        $selectedTag = $request->tag;
        $selectedsub_category_id = $request->sub_category_id;
        $selectedCategoryId = $request->selectedCategoryId;

        // $products = Product::where('category_id', $selectedCategoryId)->where('subcategory_id', $selectedsub_category_id)
        // ->when($selectedTag, function ($query) use ($selectedTag) {
        //     foreach ($selectedTag as $tag) {
        //         $query->orWhere('tags', 'LIKE', "%$tag%");
        //     }
        // })->get();

        //$query = Product::query();

$query = Product::where('status', 'active');


        if ($selectedsub_category_id == null) {
            $query->where('category_id', $selectedCategoryId);
        } else {
            $query->where('subcategory_id', $selectedsub_category_id);
        }

        if ($selectedTag) {
            $query->where(function ($query) use ($selectedTag) {
                // foreach ($selectedTag as $tag) {
                    $query->orWhere('tags', 'LIKE', "%$selectedTag%");
                // }
            });
        }


        $products = $query->get();
        // dd($products);

        return view('web.lay.products', compact('products'));
    }
}
