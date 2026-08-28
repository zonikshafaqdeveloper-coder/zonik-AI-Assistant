<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use App\Models\category;
use App\Models\product;
use App\Models\subcategory;

use Illuminate\Support\Facades\Auth;

class CustomAuthController extends Controller
{
    //
    public function index() {
        $categories = Category::all();
        $products = Product::with('subcategory')->get();
        $subcategories = Subcategory::with('products')->get();
        return view('web.home1', compact('categories', 'products','subcategories'));
     }


     public function index2(Subcategory $subcategory) {
        $products = $subcategory->products;
        return view('web.singledemo', compact('subcategory', 'products'));
     }




    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }

    public function dashboard1()
    {
        if(Auth::check()){
            return redirect('home');
        }

        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function home()
    {
        $categories = Category::all();
        $selectedCategory = null;
        return view('web.home')->with(compact(['categories','selectedCategory']));
    }



    public function showSubcategories($categoryId)
    {
        $category = Category::with('subcategories')->findOrFail($categoryId);
        dd($category);
        return view('web.singledemo', compact('category'));
    }

    public function index1()
    {
        $categories = Category::with('subcategories')->get();
        $selectedCategory = null;


        return view('web.home', compact('categories', 'selectedCategory'));
    }

    public function indexx(Category $category,Subcategory $subcategory) {
        $subcategories = $category->subcategories;
        $products = $subcategory->products;
        return view('web.singledemo', compact('category', 'subcategories', 'products'));
     }


     public function products(Category $category,Subcategory $subcategory) {
        $subcategories = $category->subcategories;
        $products = $subcategory->products;

        return view('web.singledemo', compact('category','subcategory', 'products','subcategories'));
     }


     public function sideproducts(Subcategory $subcategory) {
        $selectedSubcategory = $subcategory;
        $products = $subcategory->products;

        return view('web.singledemo', compact('selectedSubcategory', 'products'));
    }




    public function showCategory(Category $category)
    {
        $selectedCategory = $category->load('subcategories.products');
        $categories = Category::with('subcategories')->get();


        return view('web.singledemo', compact('categories', 'selectedCategory'));
    }


    // SubcategoryController.php
public function show(Subcategory $subcategory)
{
    dd($subcategory);
    return view('web.singledemo', compact('subcategory'));
}
}



