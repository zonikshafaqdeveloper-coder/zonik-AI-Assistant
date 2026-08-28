<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Type;
use App\Models\Product;
use App\Models\subcategory;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::all();
    return view('admin.type.index')->with(compact(['types']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subcategories = Subcategory::all();
        return view('admin.type.create',compact('subcategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $type = new Type;
        $type->subcategory_id = $request->input('subcategory_id');
        $type->type_name = $request->input('type_name');
        $type->save();
        return redirect()->route('types.index')->with('success', 'type added successfully.');
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
        $type = type::find($id);
        $subcategories = Subcategory::all();
        return view('admin.type.edit', compact('brand', 'subcategories'));
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
        $type = type::find($id);
        $type->name = $request->input('brand_name');
        $type->subcategory_id = $request->input('subcategory_id');
        $type->update();
        return redirect()->route('brands.index')->with('success', 'type Category updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = Brand::find($id);
        $type->delete();
        return redirect()->route('brands.index')->with('success', 'Brand Category Deleted Successfully.');
    }
}
