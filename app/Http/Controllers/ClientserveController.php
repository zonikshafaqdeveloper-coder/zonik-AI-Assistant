<?php

namespace App\Http\Controllers;

use App\Models\Clientserve;
use Illuminate\Http\Request;

class ClientserveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientserve = Clientserve::all();
        return view('admin.clients_we_serve.index')->with(compact(['clientserve']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
        return view('admin.clients_we_serve.create');
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
         
         $clientserve = new Clientserve;
       
         if ($request->hasfile('image')) {
             $file = $request->file('image');
             $extenstion = $file->getClientOriginalExtension();
             $filename = time() . '.' . $extenstion;
             $file->move('uploads/', $filename);
             $clientserve->image = $filename;
         }
 
         $clientserve->save();
         return redirect()->route('clientsserve.index')->with('success', 'Client Serve Logo added successfully.');
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
        $clientserve = Clientserve::find($id);
        return view('admin.clients_we_serve.edit', compact('clientserve'));
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

        $clientserve = Clientserve::find($id);
        $data  = $request->all();

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('uploads/', $filename);
            $data['image'] = $filename;
        } else {
            $data['image'] = $clientserve->image;
        }
      
        $clientserve->update($data);
        return redirect()->route('clientsserve.index')->with('success', 'Client Serve Logo updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $clientserve = Clientserve::find($id);
        $clientserve->delete();
        return redirect()->route('clientsserve.index')->with('success', 'Client Serve Logo Deleted Successfully.');
    }
}
