<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\OrderableProductValidator;
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

    $authorization = app(OrderableProductValidator::class)->validate(auth()->user(), $outletId, (int) $validated['product_id']);
    if (!$authorization['approved']) return response()->json([
        'success' => false, 'code' => $authorization['reason'],
        'message' => 'This product is not approved for the selected outlet.',
    ], 422);
    $product = $authorization['product'];
    $offerPrice = (float) $authorization['price'];
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

    $authorization = app(OrderableProductValidator::class)->validate(auth()->user(), $outletId, (int) $cart->product_id);
    if (!$authorization['approved']) return response()->json([
        'success' => false, 'code' => $authorization['reason'],
        'message' => 'This product is no longer approved for the selected outlet.',
    ], 422);

    $totalQty = $validated['quantity'];

    $cart->update([
        'quantity'        => $totalQty,
        'count_value'     => $totalQty,
        'total_qty'       => $totalQty,
        'offer_price'     => (float) $authorization['price'],
        'total_amt_basic' => round((float) $authorization['price'] * $totalQty, 2),
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
