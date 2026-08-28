<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\Product;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Exports\OrderExport;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Role;

class UsernewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $admins = Admin::latest()->get();
        return view('admin.usernew.index')->with(compact(['admins']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
{
    $roles = Role::all();
    return view('admin.usernew.create', compact('roles'));
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
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:admins,email',
        'password' => 'required|min:6',
        'role_id'  => 'required|exists:roles,id',
    ]);

    $admin = new Admin();
    $admin->name = $request->name;
    $admin->email = $request->email;
    $admin->password = Hash::make($request->password);   // IMPORTANT
    $admin->role_id = $request->role_id;                 // IMPORTANT
    $admin->save();

    return redirect()->route('users.index')->with('success', 'User added successfully.');
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
    $admin = Admin::findOrFail($id);
    $roles = Role::all();
    return view('admin.usernew.edit', compact('admin', 'roles'));
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
    $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email|unique:admins,email,' . $id,
        'role_id' => 'required|exists:roles,id',
        'password'=> 'nullable|min:6',   // password optional
    ]);

    $admin = Admin::findOrFail($id);
    $admin->name  = $request->name;
    $admin->email = $request->email;
    $admin->role_id = $request->role_id;

    // Only update password if user entered a new one
    if ($request->filled('password')) {
        $admin->password = Hash::make($request->password);
    }

    $admin->save();

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
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

    public function newexportproduct()
    {
        $timestamp = now()->format('Y-m-d:H:i:s');
        $filename = $timestamp . 'products.xlsx';
        return Excel::download(new ProductsExport, $filename);
    }

    public function newexportorder()
    {
        $timestamp = now()->format('Y-m-d:H:i:s');
        $filename = $timestamp . 'Orders.xlsx';
        return Excel::download(new OrderExport, $filename);
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
        return redirect()->route('users.index')->with('success', 'User Deleted Successfully.');
    }
}
