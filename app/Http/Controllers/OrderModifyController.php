<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\DeliveryManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\ProductStock;
use App\Models\PickList;
use App\Models\Payment;
use App\Models\RackStock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewEnqueryRequestCustomerNotification;

class OrderModifyController extends Controller
{
    public function index($id)
    {
        $orderItems = OrderItem::with([
            'product',
            'product.stock',
            'order.user'
        ])->where('order_id', $id)->get();

        $delivery = DeliveryManagement::where('order_id', $id)
                        ->latest()
                        ->first();

        $deliveryStatus = $delivery->delivery_status ?? 'pending';

        // if ($deliveryStatus == 'delivered' || $deliveryStatus == 'cancelled') {
        //     abort(403, 'Order modification not allowed.');
        // }

        return view('admin.order.modify', compact('orderItems','deliveryStatus'));
    }


//comment on 01-04-26
//   public function update(Request $request)
// {
//     DB::beginTransaction();

//     try {

//         $orderId = $request->order_id;
//         $items   = $request->items;

//         $order = Order::with('items.product')->findOrFail($orderId);

//         $delivery = DeliveryManagement::where('order_id',$orderId)->latest()->first();
//         $payment  = Payment::where('order_id',$orderId)->first();

//         if ($delivery && $delivery->delivery_status == 'delivered') {
//             throw new \Exception("Order already delivered. Modification not allowed.");
//         }

//         foreach ($items as $orderItemId => $newQty) {

//             $orderItem = OrderItem::with('product')
//                 ->lockForUpdate()
//                 ->findOrFail($orderItemId);

//             $oldQty = $orderItem->quantity;

//             if ($oldQty == $newQty) {
//                 continue;
//             }

//             $productId = $orderItem->product_id;

//             $productStock = ProductStock::where('product_id',$productId)
//                 ->lockForUpdate()
//                 ->first();

//             if (!$productStock) {
//                 throw new \Exception("Product stock missing");
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | PICKLIST DATA
//             |--------------------------------------------------------------------------
//             */

//             $pickLists = PickList::where('order_id',$orderId)
//                 ->where('product_id',$productId)
//                 ->get();

//             $pickedQty  = $pickLists->where('status','PICKED')->sum('quantity');
//             $pendingQty = $pickLists->where('status','PENDING')->sum('quantity');

//             /*
//             |--------------------------------------------------------------------------
//             | HANDLE REDUCTION BELOW PICKED
//             |--------------------------------------------------------------------------
//             */

//             if ($newQty < $pickedQty) {

//                 $returnQty = $pickedQty - $newQty;

//                 $pickedRows = PickList::where('order_id',$orderId)
//                     ->where('product_id',$productId)
//                     ->where('status','PICKED')
//                     ->orderBy('id','desc')
//                     ->get();

//                 foreach ($pickedRows as $row) {

//                     if ($returnQty <= 0) break;

//                     $deduct = min($row->quantity, $returnQty);

//                     $row->quantity -= $deduct;

//                     if ($row->quantity <= 0) {
//                         $row->delete();
//                     } else {
//                         $row->save();
//                     }

//                     $productStock->total_stock += $deduct;

//                     StockMovement::create([
//                         'product_id'     => $productId,
//                         'reference_type' => 'ORDER',
//                         'reference_id'   => $orderId,
//                         'movement_type'  => 'IN',
//                         'quantity'       => $deduct,
//                         'unit_cost'      => $orderItem->product->cost_per_item ?? 0,
//                         'remarks'        => 'Order qty reduced after picking'
//                     ]);

//                     $returnQty -= $deduct;
//                 }

//                 $productStock->save();
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | HANDLE INCREASE
//             |--------------------------------------------------------------------------
//             */

//             $diff = $newQty - $oldQty;

//             if ($diff > 0) {

//                 if ($productStock->total_stock < $diff) {
//                     throw new \Exception(
//                         "Insufficient stock for {$orderItem->product->product_name}"
//                     );
//                 }

//                 $productStock->total_stock -= $diff;

//                 StockMovement::create([
//                     'product_id'     => $productId,
//                     'reference_type' => 'ORDER',
//                     'reference_id'   => $orderId,
//                     'movement_type'  => 'OUT',
//                     'quantity'       => $diff,
//                     'unit_cost'      => $orderItem->product->cost_per_item ?? 0,
//                     'remarks'        => 'Order qty increased'
//                 ]);

//                 $productStock->save();
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | UPDATE ORDER ITEM
//             |--------------------------------------------------------------------------
//             */

//             $orderItem->quantity = $newQty;
//             $orderItem->save();

//             /*
//             |--------------------------------------------------------------------------
//             | PICKLIST REVISION
//             |--------------------------------------------------------------------------
//             */

//             $pickLists = PickList::where('order_id',$orderId)
//                 ->where('product_id',$productId)
//                 ->get();

//             $pickedQty  = $pickLists->where('status','PICKED')->sum('quantity');
//             $pendingQty = $pickLists->where('status','PENDING')->sum('quantity');

//             $currentPickQty = $pickedQty + $pendingQty;

//             $pickDiff = $newQty - $currentPickQty;

//             if ($pickDiff > 0) {

//                 PickList::create([
//                     'order_id'      => $orderId,
//                     'product_id'    => $productId,
//                     'quantity'      => $pickDiff,
//                     'status'        => 'PENDING',
//                     'is_revised'    => 1,
//                     'revision_note' => 'Picklist revised: quantity increased',
//                     'revised_at'    => now(),
//                     'created_at'    => now(),
//                     'updated_at'    => now()
//                 ]);

//             } elseif ($pickDiff < 0) {

//                 $reduce = abs($pickDiff);

//                 $pendingRows = PickList::where('order_id',$orderId)
//                     ->where('product_id',$productId)
//                     ->where('status','PENDING')
//                     ->orderBy('id','desc')
//                     ->get();

//                 foreach ($pendingRows as $row) {

//                     if ($reduce <= 0) break;

//                     if ($row->quantity > $reduce) {

//                         $row->quantity -= $reduce;
//                         $row->save();
//                         $reduce = 0;

//                     } else {

//                         $reduce -= $row->quantity;
//                         $row->delete();
//                     }
//                 }
//             }

//         }

//         /*
//         |--------------------------------------------------------------------------
//         | BILLING RECALCULATION
//         |--------------------------------------------------------------------------
//         */

//         $order->load('items.product');

//         $subtotal = 0;
//         $productDiscount = 0;
//         $couponDiscountTot = 0;
//         $cgstSgstTotal = 0;
//         $totalAmount = 0;

//         foreach ($order->items as $orderItem) {

//             $product = $orderItem->product;
//             if (!$product) continue;

//             $pretax = $orderItem->quantity * $orderItem->offer_price;
//             $subtotal += $pretax;

//             $discountPct = $product->total_discount ?? 0;
//             $discount = ($pretax * $discountPct) / 100;

//             $productDiscount += $discount;

//             $sgst = $product->sgst ?? 0;
//             $cgst = $product->cgst ?? 0;

//             $cgstAmount = ($cgst * $pretax) / 100;
//             $sgstAmount = ($sgst * $pretax) / 100;

//             $cgstSgstTotal += ($cgstAmount + $sgstAmount);

//             $couponDiscountTot += $orderItem->coupon_discount ?? 0;

//             $lineTotal = $pretax
//                 - ($orderItem->coupon_discount ?? 0)
//                 + $cgstAmount
//                 + $sgstAmount;

//             $totalAmount += $lineTotal;
//         }

//         $totalAmount += (
//             $order->delivery_charges +
//             $order->packing_charges +
//             $order->others_charges
//         );

//         $order->subtotal = $subtotal;
//         $order->product_discount = $productDiscount;
//         $order->coupon_discount = $couponDiscountTot;
//         $order->cgst_sgst = $cgstSgstTotal;
//         $order->total_discount_value = $totalAmount;
//         $order->save();

//         if ($payment) {
//             $payment->total_amount = $totalAmount;
//             $payment->save();
//         }

//         DB::commit();
        
//          /*
//         |-----------------------------------------
//         | ✅ NOTIFICATION AFTER SUCCESS
//         |-----------------------------------------
//         */
        
//         $user = $order->mainuser;

//         if ($user) {

//             $message = "Your order (ID: {$order->order_id}) has been updated. Please check the latest details.";

//             // App notification
//             $user->notify(new NewEnqueryRequestCustomerNotification($user->id, $message));

         

//             Log::info("Order modified notification sent to user ID: {$user->id}");
//         }

//         return response()->json([
//             'success' => true,
//             'message' => 'Order modified successfully.'
//         ]);

//     } catch (\Exception $e) {

//         DB::rollBack();

//         return response()->json([
//             'success' => false,
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }


   public function update(Request $request)
{
    DB::beginTransaction();

    try {

        $orderId = $request->order_id;
        $items   = $request->items;

        $order = Order::with('items.product')->findOrFail($orderId);

        $delivery = DeliveryManagement::where('order_id',$orderId)->latest()->first();
        $payment  = Payment::where('order_id',$orderId)->first();

        // if ($delivery && $delivery->delivery_status == 'delivered') {
        //     throw new \Exception("Order already delivered. Modification not allowed.");
        // }

         foreach ($items as $orderItemId => $data) {
             
        $newQty = $data['qty'];
        $newPrice = $data['price'];

            $orderItem = OrderItem::with('product')
                ->lockForUpdate()
                ->findOrFail($orderItemId);

            $oldQty = $orderItem->quantity;
             $oldPrice = $orderItem->offer_price;


            if ($oldQty == $newQty && $oldPrice == $newPrice) {
                continue;
            }

            $productId = $orderItem->product_id;

            $productStock = ProductStock::where('product_id',$productId)
                ->lockForUpdate()
                ->first();

            if (!$productStock) {
                throw new \Exception("Product stock missing");
            }

            /*
            |--------------------------------------------------------------------------
            | PICKLIST DATA
            |--------------------------------------------------------------------------
            */

            $pickLists = PickList::where('order_id',$orderId)
                ->where('product_id',$productId)
                ->get();

            $pickedQty  = $pickLists->where('status','PICKED')->sum('quantity');
            $pendingQty = $pickLists->where('status','PENDING')->sum('quantity');

            /*
            |--------------------------------------------------------------------------
            | HANDLE REDUCTION BELOW PICKED
            |--------------------------------------------------------------------------
            */

            if ($newQty < $oldQty) {

        $returnQty = $oldQty - $newQty;

        foreach ($pickLists as $row) {

            if ($returnQty <= 0) break;

            $deduct = min($row->quantity, $returnQty);

            
            $rack = RackStock::where('product_id', $productId)
                ->where('batch_no', $row->batch_no)
                ->where('rack_no', $row->rack_no)
                ->where('level_no', $row->level_no)
                ->where('slot_no', $row->slot_no)
                ->lockForUpdate()
                ->first();

            if ($rack) {
                $rack->quantity += $deduct;
                $rack->save();
            }

            
            $row->quantity -= $deduct;

            if ($row->quantity <= 0) {
                $row->delete();
            } else {
                $row->save();
            }

           
            $productStock->total_stock += $deduct;

            
            StockMovement::create([
                'product_id' => $productId,
                'reference_type' => 'ORDER_MODIFY',
                'reference_id' => $orderId,
                'movement_type' => 'IN',
                'quantity' => $deduct,
                'batch_no' => $row->batch_no,
                'remarks' => 'Qty reduced - returned to same rack'
            ]);

            $returnQty -= $deduct;
        }
    }

            /*
            |--------------------------------------------------------------------------
            | HANDLE INCREASE
            |--------------------------------------------------------------------------
            */

              if ($newQty > $oldQty) {

        $needQty = $newQty - $oldQty;

       
        $racks = RackStock::where('product_id', $productId)
            ->where('quantity','>',0)
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        foreach ($racks as $rack) {

            if ($needQty <= 0) break;

            $deduct = min($rack->quantity, $needQty);

            
            $rack->quantity -= $deduct;
            $rack->save();

           
            PickList::create([
                'order_id'   => $orderId,
                'product_id' => $productId,
                'quantity'   => $deduct,
                'batch_no'   => $rack->batch_no,
                'rack_no'    => $rack->rack_no,
                'level_no'   => $rack->level_no,
                'slot_no'    => $rack->slot_no,
                'status'     => 'PENDING',
                'is_revised' => 1
            ]);

        
            $productStock->total_stock -= $deduct;

         
            StockMovement::create([
                'product_id' => $productId,
                'reference_type' => 'ORDER_MODIFY',
                'reference_id' => $orderId,
                'movement_type' => 'OUT',
                'quantity' => $deduct,
                'batch_no' => $rack->batch_no,
                'remarks' => 'Qty increased - taken from rack'
            ]);

            $needQty -= $deduct;
        }

        if ($needQty > 0) {
            throw new \Exception("Not enough rack stock available");
        }
    }

    $productStock->save();

            /*
            |--------------------------------------------------------------------------
            | UPDATE ORDER ITEM
            |--------------------------------------------------------------------------
            */

            $orderItem->quantity = $newQty;
            $orderItem->offer_price = $newPrice;
            $orderItem->price = $newQty * $newPrice;
            $orderItem->save();
            
            
            
            if ($oldPrice != $newPrice) {

                \App\Models\CustomerPrice::updateOrCreate(
                    [
                        'outlet_id'   => $order->outlet_id,
                        'product_id'  => $productId,
                    ],
                    [
                        'customer_id'    => $order->user_id,
                        'product_price'  => $newPrice,
                    ]
                );

    
                StockMovement::create([
                    'product_id'     => $productId,
                    'reference_type' => 'ORDER_MODIFY',
                    'reference_id'   => $orderId,
                    'movement_type'  => 'IN', 
                    'quantity'       => 0,
                    'batch_no'       => null,
                    'remarks'        => "Price changed on order modify: ₹{$oldPrice} → ₹{$newPrice}",
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | PICKLIST REVISION
            |--------------------------------------------------------------------------
            */

            $pickLists = PickList::where('order_id',$orderId)
                ->where('product_id',$productId)
                ->get();

            $pickedQty  = $pickLists->where('status','PICKED')->sum('quantity');
            $pendingQty = $pickLists->where('status','PENDING')->sum('quantity');

            $currentPickQty = $pickedQty + $pendingQty;

            $pickDiff = $newQty - $currentPickQty;

            if ($pickDiff > 0) {

                PickList::create([
                    'order_id'      => $orderId,
                    'product_id'    => $productId,
                    'quantity'      => $pickDiff,
                    'status'        => 'PENDING',
                    'is_revised'    => 1,
                    'revision_note' => 'Picklist revised: quantity increased',
                    'revised_at'    => now(),
                    'created_at'    => now(),
                    'updated_at'    => now()
                ]);

            } elseif ($pickDiff < 0) {

                $reduce = abs($pickDiff);

                $pendingRows = PickList::where('order_id',$orderId)
                    ->where('product_id',$productId)
                    ->where('status','PENDING')
                    ->orderBy('id','desc')
                    ->get();

                foreach ($pendingRows as $row) {

                    if ($reduce <= 0) break;

                    if ($row->quantity > $reduce) {

                        $row->quantity -= $reduce;
                        $row->save();
                        $reduce = 0;

                    } else {

                        $reduce -= $row->quantity;
                        $row->delete();
                    }
                }
            }

        }

        /*
        |--------------------------------------------------------------------------
        | BILLING RECALCULATION
        |--------------------------------------------------------------------------
        */

        $order->load('items.product');

        $subtotal = 0;
        $productDiscount = 0;
        $couponDiscountTot = 0;
        $cgstSgstTotal = 0;
        $totalAmount = 0;

        foreach ($order->items as $orderItem) {

            $product = $orderItem->product;
            if (!$product) continue;

            $pretax = $orderItem->quantity * $orderItem->offer_price;
            $subtotal += $pretax;

            $discountPct = $product->total_discount ?? 0;
            $discount = ($pretax * $discountPct) / 100;

            $productDiscount += $discount;

            $sgst = $product->sgst ?? 0;
            $cgst = $product->cgst ?? 0;

            $cgstAmount = ($cgst * $pretax) / 100;
            $sgstAmount = ($sgst * $pretax) / 100;

            $cgstSgstTotal += ($cgstAmount + $sgstAmount);

            $couponDiscountTot += $orderItem->coupon_discount ?? 0;

            $lineTotal = $pretax
                - ($orderItem->coupon_discount ?? 0)
                + $cgstAmount
                + $sgstAmount;

            $totalAmount += $lineTotal;
        }

        $totalAmount += (
            $order->delivery_charges +
            $order->packing_charges +
            $order->others_charges
        );

        $order->subtotal = $subtotal;
        $order->product_discount = $productDiscount;
        $order->coupon_discount = $couponDiscountTot;
        $order->cgst_sgst = $cgstSgstTotal;
        $order->total_discount_value = $totalAmount;
        $order->save();

        if ($payment) {
            $payment->total_amount = $totalAmount;
            $payment->save();
        }

        DB::commit();
        
         /*
        |-----------------------------------------
        | ✅ NOTIFICATION AFTER SUCCESS
        |-----------------------------------------
        */
        
        $user = $order->mainuser;

        if ($user) {

            $message = "Your order (ID: {$order->order_id}) has been updated. Please check the latest details.";

            // App notification
            $user->notify(new NewEnqueryRequestCustomerNotification($user->id, $message));

         

            Log::info("Order modified notification sent to user ID: {$user->id}");
        }

        return response()->json([
            'success' => true,
            'message' => 'Order modified successfully.'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
