<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\UserNotification;
use App\Models\OrderNotification;
use App\Item;
use App\Constants\Status;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index(Request $request)
    {
        // dd($request->session());
        if ($request->session()->has('ADMIN_LOGIN')) {
            return view('admin.pages.dashboard');
        } else {
            return view('admin.login.login');
        }
        // return view('admin.login.login');
    }


    public function auth(Request $request)
    {
        $email = $request->post('email');
        $password = $request->post('password');

        $ordersCount = Enquiry::where('status','pending')->count();
        $request->session()->put('ordersCount', $ordersCount);

        $ordersCount = Order::where('')->count();

        // $result=Admin::where(['email'=>$email,'password'=>$password])->get();
        $result = Admin::where(['email' => $email])->first();
        if ($result) {
            if ($request->post('password') === $result->password) {
                $request->session()->put('ADMIN_LOGIN', true);
                $request->session()->put('ADMIN_ID', $result->id);
                $request->session()->put('role', $result->role);
                return redirect('dashboard');
            } else {

                $request->session()->flash('success', 'Please enter correct password');
                return redirect('admin');
            }
        } else {
            $request->session()->flash('success', 'Please enter valid login details');
            return redirect('admin');
        }
    }


    public function user()
    {
        return view('admin.login.user');
    }


    public function store(Request $request)
    {
        $user = new Admin();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->role = $request->input('role');

        $user->save();
        return redirect()->route('admin.store')->with('success', 'User added successfully.');
    }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {


        if ($request->session()->has('ADMIN_LOGIN')) {
            return view('admin.pages.dashboard');
        } else {
            return view('admin.login.login');
        }
    }

    public function notificationRead($id){
        $notification = AdminNotification::findOrFail($id);
        $notification->is_read = Status::YES;
        $notification->save();
        $url = $notification->click_url;
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }
    public function usernotificationRead($id){
        // dd($id);
        $notification = UserNotification::findOrFail($id);
        // dd($notification);
        $notification->is_read = Status::YES;
        $notification->save();
        $url = $notification->click_url;
        // dd($url);
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }
    public function ordernotificationRead($id){
        // dd($id);
        $notification = OrderNotification::findOrFail($id);
        // dd($notification);
        $notification->is_read = Status::YES;
        $notification->save();
        $url = $notification->click_url;
        // dd($url);
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }

    public function products()
    {
        return view('admin.pages.product');
    }



    public function logout()
    {
        Session::forget('ADMIN_LOGIN');
        // Session::forget('ADMIN_ID');

        // return view('admin.login.login');

        return redirect('admin')->with('success', 'Logout successfully.');
    }

    /**
     * Show the my users page.
     *
     * @return \Illuminate\Http\Response
     */
    // public func()
    // {
    //     return view('users');
    // }


}
