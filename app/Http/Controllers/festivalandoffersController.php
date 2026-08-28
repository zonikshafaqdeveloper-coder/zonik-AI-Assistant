<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FestivalandOffers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class festivalandoffersController extends Controller
{
    public function index()
    {
        $festivalandoffers = FestivalandOffers::latest()->get();
        return view('admin.festivalandoffers.index')->with(compact('festivalandoffers'));
    }

    public function statusUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Active,Inactive',
        ]);

        $festival = FestivalandOffers::findOrFail($id);
        $festival->status = $request->input('status');
        $festival->save();

        return Redirect::back();
    }


    public function create()
    {
        $festivalandoffers = FestivalandOffers::where('status', 'Active')->get();
        return view('admin.festivalandoffers.create', compact('festivalandoffers'));
    }

    public function edit($id)
    {
        $festivalandoffers = FestivalandOffers::findOrFail($id);;
        return view('admin.festivalandoffers.edit', compact('festivalandoffers'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'festival_offier_name' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $festivalandoffers = new FestivalandOffers();
        $festivalandoffers->festival_offier_name = $request->input('festival_offier_name');
        $festivalandoffers->status = $request->input('status');

        $festivalandoffers->save();

        return redirect()->route('festivalandoffers.index')->with('success', 'Festival and offers created successfully.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'festival_offier_name' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $festivalandoffers = FestivalandOffers::findOrFail($id);
        $festivalandoffers->festival_offier_name = $request->input('festival_offier_name');
        $festivalandoffers->status = $request->input('status');

        $festivalandoffers->save();

        return redirect()->route('festivalandoffers.index')->with('success', 'Festival and offers updated successfully.');
    }

    public function destroy($id)
    {
        $festivalandoffers = FestivalandOffers::findOrFail($id);
        $festivalandoffers->delete();

        return redirect()->route('festivalandoffers.index')->with('success', 'Banner deleted successfully.');
    }
}