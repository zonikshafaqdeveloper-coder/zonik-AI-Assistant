<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CustomerPrice;
use Illuminate\Http\Request;

class MobileCartController extends Controller
{
      public function add(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|integer|min:1',
        'price'      => 'required|numeric|min:0',
    ]);

    $outletId = auth()->user()->selected_outlet_id;
    // dd($outletId);

    $product = Product::select('id', 'product_mrp', 'sale_price_loose_pcs', 'sale_price_carton')->findOrFail($validated['product_id']);

    $outletPrice = CustomerPrice::where('outlet_id', $outletId)
        ->where('product_id', $product->id)->value('product_price');
    $cataloguePrice = (float) ($product->sale_price_loose_pcs ?: $product->sale_price_carton ?: $product->product_mrp);
    $offerPrice = (float) ($outletPrice ?? $cataloguePrice);
    if ($offerPrice <= 0) {
        return response()->json(['success' => false, 'message' => 'Price is unavailable for this product.'], 422);
    }
    $mrp = (float) ($product->product_mrp ?: $offerPrice);
    $discount = $mrp > 0 ? round((($mrp - $offerPrice) / $mrp) * 100, 2) : 0;
    $totalQty = $validated['quantity'];

    $cart = Cart::updateOrCreate(
        [
            'user_id'    => auth()->id(),
            'outlet_id'  => $outletId,
            'product_id' => $validated['product_id'],
        ],
        [
            'quantity'         => $validated['quantity'],
            'count_value'      => $totalQty,
            'total_qty'        => $totalQty,
            'offer_price'      => $offerPrice,
            'mrp'              => $mrp,
            'discount'         => $discount,
            'coupon_discount'  => 0,
            'total_amt_basic'  => round($offerPrice * $totalQty, 2),
        ]
    );

    return response()->json([
        'success'    => true,
        'cart_id'    => $cart->id,
        'line_total' => $cart->total_amt_basic,
    ]);
}

public function updateQuantity(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|integer|min:1',
    ]);

    $outletId = auth()->user()->selected_outlet_id;

    $cart = Cart::where('user_id', auth()->id())
        ->where('outlet_id', $outletId)
        ->where('product_id', $validated['product_id'])
        ->firstOrFail();

    $totalQty = $validated['quantity'];

    $cart->update([
        'quantity'        => $totalQty,
        'count_value'     => $totalQty,
        'total_qty'       => $totalQty,
        'total_amt_basic' => round($cart->offer_price * $totalQty, 2),
    ]);

    return response()->json([
        'success'    => true,
        'line_total' => $cart->total_amt_basic,
    ]);
}

public function remove(Request $request)
{
    $validated = $request->validate([
        'cart_id' => 'required|exists:carts,id',
    ]);

    Cart::where('id', $validated['cart_id'])
        ->where('user_id', auth()->id())
        ->delete();

    return response()->json(['success' => true]);
}

}
