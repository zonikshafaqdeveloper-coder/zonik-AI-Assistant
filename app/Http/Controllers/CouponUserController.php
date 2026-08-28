<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CouponUser;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;
use App\Models\Cart;

class CouponUserController extends Controller
{
    // Store a new coupon user record
    public function store(Request $request)
    {
        $request->validate([
            'coupon_id' => 'required|exists:coupons,id',
            'user_id' => 'required|exists:users,id',
            'coupon_code' => 'required|string',
        ]);

        CouponUser::create($request->all());
        return redirect()->back()->with('success', 'Coupon user record created successfully');
    }


   public function couponValidation(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);
    
        $userId = Auth::id(); // Get the current user
        $couponCode = $request->coupon_code;
    
        // Find the coupon by code
        $coupon = Coupon::where('coupon_code', $couponCode)->first();
    
        // Get the user's cart
        $cart = Cart::where('user_id', $userId)->first();
    
        if (!$coupon) {
            return response()->json(['status' => 'not_found']); // Coupon not found
        }
    
        // Check if the coupon has expired
        $currentDate = now();
        if ($currentDate->greaterThan($coupon->end_date)) {
            return response()->json(['status' => 'expired', 'message' => 'Coupon has expired']);
        }
    
        // Apply the coupon logic
        if ($cart) {
            // Apply the coupon discount to the cart
            $cart->coupon_discount = $coupon->discount_amount;
            $cart->save();
        }
    
        // Create a new entry in the CouponUser table
        $couponUser = CouponUser::create([
            'user_id' => $userId,
            'coupon_code' => $couponCode,
            'coupon_id' => $coupon->id, // Ensure this field is included
        ]);
    
        return response()->json([
            'status' => 'not_applied',
            'offer_price' => $coupon->discount_amount,
            'message' => 'Coupon applied successfully!',
        ]);
    }
    


}
