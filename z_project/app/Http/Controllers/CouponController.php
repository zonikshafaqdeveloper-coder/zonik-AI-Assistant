<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{

    public function index()
    {
        $coupons = Coupon::all();
        return view('admin.coupon.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupon.create');
    }



    public function store(Request $request)
    {
        $request->validate([
            'coupon_name' => 'required',
            'max_price' => 'required|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'discount_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'required|in:Active,Inactive',
        ]);



        $coupon = new Coupon();
        $coupon->coupon_name = $request->coupon_name;
        $coupon->max_price = $request->max_price;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->discount_amount = $request->discount_amount;
        $coupon->description = $request->description;
        $coupon->is_active = $request->is_active;
        $coupon->coupon_code = $this->generateCouponCode();
        // dd($coupon  );
        $coupon->save();

        return redirect()->back()->with('success', 'Coupon created successfully');
    }

    private function generateCouponCode() {
        return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {



        $coupon = Coupon::findOrFail($id);
        $coupon->coupon_name = $request->coupon_name;
        $coupon->max_price = $request->max_price;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->discount_amount = $request->discount_amount;
        $coupon->description = $request->description;
        $coupon->is_active = $request->is_active;
        $coupon->coupon_code = $request->coupon_code;

        $coupon->save();

        return redirect()->back()->with('success', 'Coupon updated successfully');
    }



    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json(['message' => 'Coupon deleted successfully']);
    }
}
