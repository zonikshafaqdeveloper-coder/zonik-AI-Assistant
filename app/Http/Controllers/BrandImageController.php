<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandImage;
use App\Models\Category;
use App\Models\FestivalandOffers;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;



class BrandImageController  extends Controller
{
    public function index()
    {
        $brandimage = BrandImage::latest()->get();
        return view('admin.brandsimage.index')->with(compact('brandimage'));
    }

    public function listing($festivalandoffersid)
    {
        $brandimage = BrandImage::where('festival_and_offer', $festivalandoffersid)->get();

        return view('admin.brandsimage.index', compact('brandimage'));
    }

    public function create()
    {
        $subcategories = Subcategory::all();
        $categories = Category::all();
        $festivalandofffers = FestivalandOffers::where('status', 'Active')->get();
        return view('admin.brandsimage.create', compact('subcategories', 'categories', 'festivalandofffers'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:Active,Inactive', // Assuming status is a valid field
            'search_by' => 'required|in:category,brand', // Assuming status is a valid field
            'festivalandofffers' => 'required', // Add validation for festival and offers
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
        }

        // Create a new brand image instance and fill in the data
        $brandimage = new BrandImage();
        $brandimage->brand_name = $request->input('brand_name');
        $brandimage->brand_image = $imageName;
        $brandimage->category_id = $request->input('category_id');
        $brandimage->status = $request->input('status');
        $brandimage->search_by = $request->input('search_by');
        $brandimage->festival_and_offer = $request->input('festivalandofffers');
        $category = Category::find($request->input('category_id'));
        if ($category) {
            $brandimage->category()->associate($category);
        }

        $brandimage->save();

        return redirect()->back()->with('success', 'Brand image added successfully.');
    }


    public function edit($id)
    {
        $brandsimage = BrandImage::findOrFail($id);
        $categories = Category::all();
        $festivalandofffers  = FestivalandOffers::all();
        return view('admin.brandsimage.edit', compact('brandsimage', 'categories', 'festivalandofffers'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'brand_name' => 'required|string|max:255',
            'brand_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'search_by' => 'required|in:category,brand',
        ]);

        $brandimage = BrandImage::findOrFail($id);
        $brandimage->brand_name = $request->input('brand_name');
        $brandimage->search_by = $request->input('search_by');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $brandimage->brand_image = $imageName;
        }

        $brandimage->category_id = $request->input('category_id');

        $brandimage->save();

        return redirect()->back()->with('success', 'Brand image updated successfully.');
    }


    public function statusUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Active,Inactive',
        ]);

        $brandsimage = BrandImage::findOrFail($id);
        $brandsimage->status = $request->input('status');
        $brandsimage->save();

        return Redirect::back()->with('success', 'Status updated successfully.');
    }




    public function destroy($id)
    {
        $brandimage = BrandImage::findOrFail($id);
        $brandimage->delete();

        return redirect()->route('brandsimage.index')->with('success', 'Banner deleted successfully.');
    }
}
