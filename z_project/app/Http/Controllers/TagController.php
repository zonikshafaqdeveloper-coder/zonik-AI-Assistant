<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tag;
use App\Models\Subcategory;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return view('admin.tag.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $subcategories = Subcategory::all();
        return view('admin.tag.create', compact('subcategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = new Tag;
        $tag->subcategory_id = $request->input('subcategory_id');
        $tag->tag_name = $request->input('tag_name');
        $tag->save();
        return redirect()->route('tags.index')->with('success', 'Tag added successfully.');
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
        $tag = Tag::find($id);
        $subcategories = Subcategory::all();
        return view('admin.tag.edit', compact('tag', 'subcategories'));
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
    $tag = Tag::find($id);
    $tag->tag_name = $request->input('tag_name');
    $tag->subcategory_id = $request->input('subcategory_id');
    
    $tag->update();
    return redirect()->route('tags.index')->with('success', 'Product updated successfully.');
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        $tag->delete();
        return redirect()->route('tags.index')->with('success', 'Tag Category Deleted Successfully.');
    }
}
