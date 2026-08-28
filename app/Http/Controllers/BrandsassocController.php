<?php

namespace App\Http\Controllers;

use App\Models\Brandsassoc;
use Illuminate\Http\Request;

class BrandsassocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brandsassoc = Brandsassoc::all();
        return view('admin.brands_associative_logo.index')->with(compact(['brandsassoc']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
        return view('admin.brands_associative_logo.create');
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
          'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $brandsassoc = new Brandsassoc;
      
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('uploads/', $filename);
            $brandsassoc->image = $filename;
        }

        $brandsassoc->save();
        return redirect()->route('brandsassoc.index')->with('success', 'Brand logo added successfully.');
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

        $brandsassoc = Brandsassoc::find($id);
        return view('admin.brands_associative_logo.edit', compact('brandsassoc'));
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

        $brandsassoc = Brandsassoc::find($id);
        $data  = $request->all();

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('uploads/', $filename);
            $data['image'] = $filename;
        } else {
            $data['image'] = $brandsassoc->image;
        }
      
        $brandsassoc->update($data);
        return redirect()->route('brandsassoc.index')->with('success', 'Brand Logo updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $brandsassoc = Brandsassoc::find($id);
        $brandsassoc->delete();
        return redirect()->route('brandsassoc.index')->with('success', 'Brand Logo Deleted Successfully.');
    }
}
