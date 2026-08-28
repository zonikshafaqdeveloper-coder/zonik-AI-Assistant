<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Subcategory;


class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();
        return view('admin.banner.index')->with(compact('banners'));
    }

    public function create()
    {
        
        $subcategories = Subcategory::all(); 
        $categories = Category::all();
        
        return view('admin.banner.create', compact('subcategories', 'categories'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'banner_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
        }
    
        $banner = new Banner();
        $banner->banner_name = $request->input('banner_name');
        $banner->banner_image = $imageName; 
        $category = Category::find($request->input('category_id'));
        if ($category) {
            $banner->category()->associate($category);
        }
        $subcategory = Subcategory::find($request->input('subcategory_id'));
        if ($subcategory) {
            $banner->subcategory()->associate($subcategory);
        }
        $banner->save();    
        return redirect()->route('banners.index')->with('success', 'Banner added successfully.');
    }
    
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('admin.banner.edit', compact('banner','categories' ,'subcategories'));
    }


   public function update(Request $request, $id)
{
    $banner = Banner::findOrFail($id);
    $request->validate([
        'banner_name'    => 'required|string|max:255',
        'category_id'    => 'required|exists:categories,id',
        'subcategory_id' => 'required|exists:subcategories,id',
        'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('image')) {

        if ($banner->banner_image && file_exists(public_path('uploads/'.$banner->banner_image))) {
            unlink(public_path('uploads/'.$banner->banner_image));
        }

        $image = $request->file('image');
        $imageName = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('uploads'), $imageName);

        $banner->banner_image = $imageName;
    }

    $banner->banner_name    = $request->banner_name;
    $banner->category_id    = $request->category_id;
    $banner->subcategory_id = $request->subcategory_id;
    $banner->save();

    return redirect()->route('banners.index')
        ->with('success', 'Banner updated successfully.');
}

    


    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Banner deleted successfully.');
    }

}
