<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Enquiry;
use App\Models\Product;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Coupon;
use App\Notifications\NewEnqueryRequestCustomerNotification;

class CartController extends Controller
{


public function create(Request $request)
{
    $existingCartItem = Cart::where('user_id', auth()->id())
        ->where('product_id', $request->product_id)
        ->where('product_types', $request->product_types)
        ->first();

    if ($existingCartItem) {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'already_in_cart' => true, // ✅ no update, just flag
                'cart_item' => $existingCartItem
            ]);
        }

        return redirect()->back()->with('info', 'Item already exists in cart');
    }

    // First time add
    $total_qty = $request->offer_price * $request->quantity;

    $cartItem = Cart::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id,
        'quantity' => $request->quantity,
        'count_value' => null,
        'offer_price' => $request->offer_price,
        'mrp' => $request->mrp,
        'discount' => $request->discount,
        'expected_price_value' => $request->expected_price_value ?: null,
        'product_types' => $request->product_types,
        'monthlyconsumption' => $request->monthlyconsumption ?: null,
        'total_amt_basic' => $total_qty,
        'total_qty' => $request->quantity,
    ]);

    if ($request->ajax()) {
        $cartItem->load('product');

        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $subTotalAmt = 0;
        $totalproductDiscount = 0;
        $totalGST = 0;

        foreach ($cartItems as $item) {
            $subTotal = $item->total_amt_basic;

            $productDiscount = $item->product->total_discount > 0
                ? ($subTotal * $item->product->total_discount) / 100
                : 0;

            $DiscountValue = $subTotal - $productDiscount;

            $CGST = $item->product->cgst;
            $SGST = $item->product->sgst;
            $TotalGstPerProduct = $CGST + $SGST;
            $productGST = ($subTotal * $TotalGstPerProduct) / 100;

            $totalGST += $productGST;
            $totalproductDiscount += $productDiscount;
            $subTotalAmt += $subTotal;
        }

        $totalDiscountValue = $subTotalAmt + $totalGST;

        return response()->json([
            'success' => true,
            'already_in_cart' => false,
            'cart_item' => $cartItem,
            'calculated' => [
                'subtotal' => $subTotalAmt,
                'discount' => $totalproductDiscount,
                'gst' => $totalGST,
                'grand_total' => $totalDiscountValue
            ]
        ]);
    }

    return redirect()->back()->with('success', 'Item added to cart successfully');
}


//changed on 15-09-25
// public function create(Request $request)
// {
//     $existingCartItem = Cart::where('user_id', auth()->id())
//         ->where('product_id', $request->product_id)
//         ->where('product_types', $request->product_types)
//         ->first();

//     if ($existingCartItem) {
//         if ($request->ajax()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Item already exists in the cart'
//             ]);
//         }
//         return redirect()->back()->with('error', 'Item already exists in the cart');
//     }

//     $total_qty = $request->offer_price * $request->quantity;

//     $cartItem = Cart::create([
//         'user_id' => auth()->id(),
//         'product_id' => $request->product_id,
//         'quantity' => $request->quantity,
//         'count_value' => null,
//         'offer_price' => $request->offer_price,
//         'mrp' => $request->mrp,
//         'discount' => $request->discount,
//         'expected_price_value' => $request->expected_price_value ?: null,
//         'product_types' => $request->product_types,
//         'monthlyconsumption' => $request->monthlyconsumption ?: null,
//         'total_amt_basic' => $total_qty,
//         'total_qty' => $request->quantity,
//     ]);

//     if ($request->ajax()) {
//         $cartItem->load('product');

//         // 🔑 Recalculate cart totals like in Blade
//         $cartItems = Cart::with('product')
//             ->where('user_id', auth()->id())
//             ->get();

//         $subTotalAmt = 0;
//         $totalproductDiscount = 0;
//         $totalGST = 0;
//         $TotalDiscountMainValue = 0;
//         $totalGrandTotal = 0;

//         foreach ($cartItems as $item) {
//             $subTotal = $item->total_amt_basic;

//             $productDiscount = $item->product->total_discount > 0
//                 ? ($subTotal * $item->product->total_discount) / 100
//                 : 0;

//             $DiscountValue = $subTotal - $productDiscount;

//             $CGST = $item->product->cgst;
//             $SGST = $item->product->sgst;
//             $TotalGstPerProduct = $CGST + $SGST;
//             $productGST = ($subTotal * $TotalGstPerProduct) / 100;

//             $TotalDiscountMainValue += $DiscountValue;
//             $totalGST += $productGST;
//             $totalproductDiscount += $productDiscount;
//             $subTotalAmt += $subTotal;
//             $totalGrandTotal += $DiscountValue;
//         }

//         // Final calculation
//         $totalDiscountValue = $subTotalAmt + $totalGST;

//         return response()->json([
//             'success' => true,
//             'cart_item' => $cartItem,
//             'calculated' => [
//                 'subtotal' => $subTotalAmt,
//                 'discount' => $totalproductDiscount,
//                 'gst' => $totalGST,
//                 'grand_total' => $totalDiscountValue
//             ]
//         ]);
//     }

//     return redirect()->back()->with('success', 'Item added to cart successfully');
// }


    

    public function remove(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);


        $cartItem = Cart::findOrFail($request->cart_id);
        $cartItem->delete();
        $subTotalAmt = 0;
        $productDiscount = 0;
        $result = 0;

        $data = [
            'subTotalAmt' => $subTotalAmt,
            'productDiscount' => $productDiscount,
            'result' => $result,
        ];
        return response()->json(['success' => 'Cart updated successfully!', 'data' => $data]);
    }



    public function updateQty(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        $product = Product::find($cart->product_id);

        $enquiry = Enquiry::find($cart->enquiry_id);
        $quantity = $cart->quantity * $request->quantity_value;
        $total_value = $cart->offer_price * $quantity;
        $cart->update([
            'total_amt_basic' => $total_value,
            'count_value' => $request->quantity_value,
            'total_qty' => $quantity,
        ]);


        $subTotalAmt = Cart::where('user_id', auth()->user()->id)->sum('total_amt_basic');

        $productDiscount1 = (float)Cart::where('user_id', auth()->user()->id)
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->whereNotNull('products.total_discount')
        ->pluck('products.total_discount')
        ->first();

        if($productDiscount1 <= 0){
            $productDiscount1 = 0;
        }else{
         $productDiscount1 = (float)$productDiscount1;
        }


        // dd($subTotalAmt);

        $productDiscount = (($subTotalAmt *  $productDiscount1)/100);
        $discountValue = $subTotalAmt - (($subTotalAmt *  $productDiscount1)/100);
        $CGST = (float)$product->cgst;
        $SGST = (float)$product->sgst;   
        $cess = $product->cess;  
        $TotalGst = $CGST + $SGST + $cess;
        $result = ($discountValue * $TotalGst )/100;
        // $totalDiscountValue = $discountValue + $result;
// dd($totalDiscountValue);
          $totalDiscountValue = $subTotalAmt + $result;
        
        $data = [
            'cart' => $request->cart_id,
            'subTotalAmt' => (float)$subTotalAmt,
            'productDiscount' => (float)$productDiscount,
            'result' => (float)$result,
            'totalDiscountValue' => (float)$totalDiscountValue,
            'total_amt_basic' => (float)$total_value,
            'count_value' => (int)$request->quantity_value,
            'total_qty' => $quantity,
        ];



        return response()->json(['success' => 'Cart updated successfully!', 'data' => $data]);
    }

    public function quantityMinus(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        if (!$cart) {
            return response()->json(['error' => 'Cart item not found.'], 404);
        }
        $product = Product::find($cart->product_id);
        if (!$product) {
            return response()->json(['error' => 'Product not found.'], 404);
        }

        $quantity = $cart->total_qty - $cart->quantity;
        $total = $cart->offer_price * $quantity;

        $cart->update([
            'total_amt_basic' => $total,
            'count_value' => $request->quantity_value,
            'total_qty' => $quantity,
        ]);



        $subTotalAmt = Cart::where('user_id', auth()->user()->id)->sum('total_amt_basic');

        $productDiscount1 = (float)Cart::where('user_id', auth()->user()->id)
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->whereNotNull('products.total_discount')
        ->pluck('products.total_discount')
        ->first();
        if($productDiscount1 <= 0){
            $productDiscount1 = 0;
        }else{
         $productDiscount1 = (float)$productDiscount1;
        }



        $productDiscount = (($subTotalAmt *  $productDiscount1)/100);
        $discountValue = $subTotalAmt - (($subTotalAmt *  $productDiscount1)/100);
        // dd($discountValue);
        $CGST = (float)$product->cgst;
        $SGST = (float)$product->sgst;
          $cess = $product->cess;  
        $TotalGst = $CGST + $SGST + $cess;
        // $TotalGst = $CGST + $SGST;
        $result = ($discountValue * $TotalGst )/100;
        // $totalDiscountValue = $discountValue + $result;
      $totalDiscountValue = $subTotalAmt + $result;
      
        $data = [
            'cart' => $request->cart_id,
            'subTotalAmt' => (float)$subTotalAmt,
            'productDiscount' => (float)$productDiscount,
            'result' => (float)$result,
            'totalDiscountValue' => (float)$totalDiscountValue,
            'total_amt_basic' => (float)$total,
            'count_value' => (int)$request->quantity_value,
            'total_qty' => (int)$quantity,
        ];
        return response()->json(['success' => 'Cart updated successfully!', 'data' => $data]);
    }



    public function cartValue()
    {
        $cart = Cart::with('enquery.product')->where('user_id', auth()->user()->id)->get();

        return view('web.cart.view', compact('cart'));
    }



    public function updateDiscount(Request $request, $couponCode)
    {
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        $coupon = Coupon::where('coupon_code', $couponCode)->first();

        $cart->coupon_discount = $coupon->discount_amount;
        $cart->save();

        return response()->json(['cart' => $cart]);
    }


}
