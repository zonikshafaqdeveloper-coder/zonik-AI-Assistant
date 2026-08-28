<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;

class SubcategorynewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {
    
        $subcategories = Subcategory::with('category')->latest()->get();

 
        return view('admin.newsubcategories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all(); // Fetch categories from the database
        return view('admin.newsubcategories.create', compact('categories'));
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
            'subcategory' => 'required|string|max:255',
            'subcategory_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'required' => 'The :attribute field is required.',
            'subcategory_id.exists' => 'The :attribute field is required.',
        ], [
            'subcategory_id' => 'category name',
        ]);
        
        
        

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('uploads/', $filename);
            $image = $filename;
        }

        Subcategory::create([
            'name' => $request->subcategory,
            'category_id' => $request->subcategory_id,
            'image' => $image,
            'slug' =>  \Str::slug($request->subcategory, '-'),
        ]);

        return redirect()->route('subcategoriess.index')->with('success', 'Subcategory added successfully.');
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
        $subcategory = Subcategory::find($id);
        $categories = Category::all();
        return view('admin.newsubcategories.edit', compact('subcategory', 'categories'));
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
        $subcategory = Subcategory::find($id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/', $filename);
            $subcategory->image = $filename;
        }

        $subcategory->name = $request->subcategory;
        $subcategory->category_id = $request->category_id;
        $subcategory->slug = \Str::slug($subcategory->name, '-');
        $subcategory->save();

        return redirect()->route('subcategoriess.index')->with('success', 'Subcategory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subcategory = Subcategory::find($id);
        $subcategory->delete();
        return redirect()->route('subcategoriess.index')->with('success', 'Subcategory Deleted Successfully.');
    }
}
