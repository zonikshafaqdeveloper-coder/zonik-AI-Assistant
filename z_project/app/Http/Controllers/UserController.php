<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\Product;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $admins = Admin::all();
        return view('admin.user.index')->with(compact(['admins']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function userlist()
     {
         // Fetch users where 'new_user' is 'false'
         $customers = User::where('new_user', 'false')->latest()->get();
         
         // Debug the result (optional)
        //  dd($customers);
     
         // Return the view with filtered customers
         return view('admin.user.newuser', compact('customers'));
     }
     

    public function create()
    {
        //
        $admins = Admin::all();
        return view('admin.user.create')->with(compact(['admins']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $admin = new Admin();
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->password = $request->input('password');
        $admin->role = $request->input('role');

        $admin->save();
        return redirect()->route('user.index')->with('success', 'User added successfully.');
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
        $admin = Admin::find($id);
        return view('admin.user.edit', compact('admin'));
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
        $admin = Admin::find($id);
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->password = $request->input('password');
        $admin->role = $request->input('role');

        $admin->update();
        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function importUsers()
    {
        return view('web.import');
    }



    public function homelog()
    {
        //  $users = User::with('roles')->paginate(10);
        $users = User::all();
        return view('admin.user.homelog', ['users' => $users]);
    }



    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function exportproduct()
    {
        $timestamp = now()->format('Y-m-d:H:i:s');
        $filename = $timestamp . 'products.xlsx';
        return Excel::download(new ProductsExport, $filename);
    }


    public function uploadUsers(Request $request)
    {
        Excel::import(new UsersImport, $request->file);
        return redirect()->route('users.homelog')->with('success', 'User Imported Successfully');
    }


    public function uploadProducts(Request $request)
    {
        $productData = Excel::import(new ProductsImport, $request->file);
        // Assuming 'tags' is the column name in Excel
        return redirect()->route('products.index')->with('success', 'User Imported Successfully');
    }

    public function destroy($id)
    {
        $admin = Admin::find($id);
        $admin->delete();
        return redirect()->route('user.index')->with('success', 'User Deleted Successfully.');
    }
    
    public function updateUserInfo(Request $request)
{
    $fieldName = $request->input('fieldName');
    $newValue = $request->input('newValue');

    // Retrieve the authenticated user
    $user = User::find(auth()->id());

    // Determine the field to update based on the fieldName
   if ($fieldName === 'name') {
        $user->name = $newValue;
    } elseif ($fieldName === 'outlet_name') {
        $user->outlet_name = $newValue;
    } elseif ($fieldName === 'email') {
        $user->email = $newValue;
    } elseif ($fieldName === 'password') {
        $user->password = Hash::make($newValue);
    }elseif ($fieldName === 'location') {
        $user->location = $newValue;
    }

    // Save the updated user information
    $user->save();

    return response()->json(['success' => true]);
}

    
    
}
