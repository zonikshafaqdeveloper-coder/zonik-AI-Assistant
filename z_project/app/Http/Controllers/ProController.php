<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Product;

class ProController extends Controller
{




    public function index()
    {
        $categories = Category::all();
        return view('web.demo.category', ['categories' => $categories]);
    }





    public function getCategoryProducts($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $products = $category->subcategories->flatMap(function ($subcategory) {
            return $subcategory->products;
        });
      return view('web.demo.product', ['products' => $products]);
    }


    public function getSubcategoryProducts($subcategoryId)
        {
            $subcateg = Subcategory::findOrFail($subcategoryId);
           
            $products = $subcateg->products; // Assuming you have a 'products' relationship in Subcategory model
           
            return view('web.demo.product', ['products' => $products, 'subcateg' => $subcateg]);
        }
    

        public function getBrandCategoryProducts($brandCategoryId)
        {
            $brandCategory = BrandCategory::findOrFail($brandCategoryId);
            $products = $brandCategory->products; // Assuming you have a 'products' relationship in BrandCategory model
            return view('web.demo.product', ['products' => $products]);
        }


    }
    


