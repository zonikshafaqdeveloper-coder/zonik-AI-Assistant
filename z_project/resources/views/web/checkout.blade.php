@extends('web.layouts.app')
@section('content')
<style>
    .modal-body, .color-grey {
    font-family: 'Arial', sans-serif; /* Or use 'Roboto' or 'Segoe UI' */
}

/* For Mobile View Only */
@media (max-width: 768px) {
    /* Style the <select> element */
    .form-select {
        appearance: none; /* Remove default dropdown styling */
        -webkit-appearance: none; /* Safari-specific */
        -moz-appearance: none; /* Firefox-specific */
        background-color: #fff; /* Clean background */
        border: 1px solid #ccc; /* Optional border */
        border-radius: 5px; /* Rounded corners */
        padding: 10px; /* Better touch targets */
        font-size: 14px; /* Adjust font size */
        color: #333; /* Text color */
    }
     .form-select option {
        font-size: 8px; /* Reduced font size for the options */
    }


    /* Ensure no list-style or bullets are applied */
    .form-select option {
        list-style: none; /* Remove bullets */
        font-size:8px;
    }
  
}


</style>
@php

        $subTotalAmt = 0;
        $productDiscount = 0;
        $DiscountValue = 0;
        $totalDiscountValue = 0 + $otherscharges + $packingcharges;

        

        $totalProduct = 0;
        $CGST = 0;
        $SGST = 0;
        $TotalDiscountMainValue  = 0;
        $totalGrandTotal  = 0;
        $totalproductDiscount  = 0;
        $TotalGst = 0;
        $result = 0; $overall_qty = 0;
        $subTotal = 0;
        foreach ($cart as $cart_Items) {
            $subTotal = $cart_Items->total_amt_basic;
            $productDiscount = $cart_Items->product->total_discount > 0 ? ($subTotal * $cart_Items->product->total_discount) / 100 : 0;

            $DiscountValuee =  $subTotal - $productDiscount;
            $DiscountValue =  $subTotal;
            $overall_qty = $cart->sum('total_qty');
            $CGST = $cart_Items->product->cgst;
            $SGST = $cart_Items->product->sgst;
            $TotalGst = $CGST + $SGST;
            $TotalDiscountMainValue +=  $DiscountValuee;

            $SGST_Total;
            $totalGrandTotal += $totalDiscountValue;
            $totalproductDiscount +=  $productDiscount;
            $subTotalAmt +=  $subTotal;
            $totalProduct++;
            
            
        $CGST = $cart_Items->product->cgst;
        $SGST = $cart_Items->product->sgst;
        $TotalGstPerProduct = $CGST + $SGST;
        $productGST = ($subTotal * $TotalGstPerProduct) / 100;
         $result  += $productGST;

        }

       
        
        $totalDiscountValue = $subTotalAmt + $result;

        $totalDiscountValuee = $subTotalAmt + $result + $packingcharges + $singleDeliverycharges;

         if($overall_qty > 24){
            $totalDiscountValue =   $totalDiscountValue + $bulkDeliverycharges + $otherscharges + $packingcharges - $coupn->first()->coupon_discount;
        }else if ($cart_Items->product_types == 1 && $overall_qty <= 24) {
            $totalDiscountValue =   $totalDiscountValue + $singleDeliverycharges + $otherscharges + $packingcharges - $coupn->first()->coupon_discount;

        } elseif ($cart_Items->product_types == 2 && $overall_qty <= 6) {
            $totalDiscountValue =   $totalDiscountValue + $singleDeliverycharges + $otherscharges + $packingcharges - $coupn->first()->coupon_discount;
        }
        else {
            $totalDiscountValue =   $totalDiscountValue + $singleDeliverycharges + $otherscharges + $packingcharges - $coupn->first()->coupon_discount;
        }
    @endphp


<style>
    .view{
        font-weight: 500;
    }
    
   /* CSS */
.custom-dropdown {
  position: relative;
  border: 1px solid #ccc;
  padding: 12px;
  border-radius: 4px;
  cursor: pointer;
  background: white;
  margin: 8px 0;
}

.selected-option::after {
  content: "▼";
  float: right;
  font-size: 0.8em;
  opacity: 0.7;
}

.options-list {
  display: none;
  position: absolute;
  width: 100%;
  left: 0;
  top: calc(100% + 5px);
  background: white;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  z-index: 1000;
  max-height: 250px;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}

.option {
  padding: 12px;
  transition: background-color 0.2s;
  border-bottom: 1px solid #eee;
}

.option:last-child {
  border-bottom: none;
}

.option:hover {
  background: #f8f9fa;
}

.custom-dropdown.active .options-list {
  display: block;
}

.custom-dropdown.active .selected-option::after {
  content: "▲";
}

/* Mobile Optimization */
@media (max-width: 768px) {
  .custom-dropdown {
    font-size: 16px;
    position: relative;
  user-select: none;
  -webkit-tap-highlight-color: transparent;
  }
  
  .options-list {
  position: absolute;
  width: 100%;
  max-height: 250px;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  z-index: 1000;
  background: white;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  touch-action: manipulation;
}

.option {
  padding: 12px 15px;
  border-bottom: 1px solid #eee;
  transition: background 0.2s;
  font-size: 16px; /* Larger touch targets */
}

.option:active {
  background: #f5f5f5;
}

@media (hover: hover) {
  .option:hover {
    background: #f5f5f5;
  }
}

.options-list .option {
  touch-action: manipulation; /* Reduces touch delay */
  -webkit-tap-highlight-color: transparent; /* Removes tap highlight */
}

.custom-dropdown {
  user-select: none; /* Prevent text selection */
  position: relative; 
}
}
</style>

    <section class="pt-50 pb-50">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="checkout-left-side topspacing">
                        <!-- <h4>Choose Delivery Options</h4> -->
                        <div class="row mt-4 align-items-center">
                            <div class="col-md-6">

<!-- Hidden Select (for validation & form submission) -->
<select class="original-select" style="display:none;">
  <option disabled selected value>Select Delivery Date</option>
  @foreach ($deliveryOptions as $option)
    <option class="option" value="{{ $option['date'] }}">
      {{ $option['slot'] }}
    </option>
  @endforeach
</select>

<!-- Custom Dropdown (UI only) -->
<div class="custom-dropdown">
    <div class="selected-option">Select Delivery Date</div>
    <div class="options-list">
        @foreach ($deliveryOptions as $option)
            @php
                $slotType = null;
                if (str_contains($option['slot'], 'Slot 1')) {
                    $slotType = 'slot-1';
                } elseif (str_contains($option['slot'], 'Slot 2')) {
                    $slotType = 'slot-2';
                }
            @endphp

            <div class="option"
                 data-date="{{ $option['date'] }}"
                 data-time="{{ $option['time_only'] }}"
                 data-slot-type="{{ $slotType }}">
                {{ $option['slot'] }}
            </div>
        @endforeach
    </div>
</div>

<input type="hidden" name="delivery_date" id="delivery_date">
<input type="hidden" name="delivery_time_slot" id="delivery_time_slot">
<input type="hidden" name="delivery_slot_type" id="delivery_slot_type">

<small class="deliveryDateError text-danger"></small>




                            <!-- Your form and other page content -->

                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <button class="btn red-btn topbtncss" type="button" tabindex="0" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">View Order</button>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="checkout-left-side mt-4">
                                <div class="d-flex div-5 pt-3">
                                    <h3>Billing Address</h3>
                                </div>

                                <p class="pb-3">{{ $outletData->name }} <br>{{ $billingAddress }}</p>
                                <h6 class="contact">
                                    @if (strpos($outletData->mobile_number, '+91') === 0)
                                        Contact : {{ $outletData->mobile_number }}
                                    @else
                                        Contact : +91 {{ $outletData->mobile_number }}
                                    @endif
                                </h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="checkout-left-side mt-4">
                                <div class="d-flex div-5 pt-3">
                                    <h3>Shipping Address</h3>
                                    <i class="fa-solid fa-pen-to-square" type="button" tabindex="0" data-bs-toggle="modal"
                                    data-bs-target="#shippingAddress"></i>
                                    <input type="checkbox" class="sameAsBilling" title="Same as billing address">
                                </div>
                                <p class="pb-3 shipping">{{ $outletData->name }} <br>{{ $shippingAddress }}</p>
                                <h6 class="contact">
                                    @if (strpos($outletData->mobile_number, '+91') === 0)
                                        Contact :  {{ $outletData->mobile_number }}
                                    @else
                                        Contact : +91 {{ $outletData->mobile_number }}
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="total-div pt-5">
                        <div class="d-flex div-1">
                            <h6>Subtotal (Basic)</h6>
                            <h5 class="text-end">₹{{ number_format(  $subTotalAmt,2) }}</h5>
                        </div>
                        <div class="mt-4 d-flex div-2 ">
                            <h3>Product Discounts</h3>
                            <h4 class="text-end">₹ {{ number_format($totalproductDiscount,2) }}</h4>
                        </div>

                        <div class="mt-4 d-flex div-2">
                            <h3>CGST + SGST</h3>
                            <h4 class="text-end">+ ₹ {{number_format( $result,2) }}</h4>
                        </div>
                        @if($packingcharges > 0 || $packingcharges > 0.00)
                        <div class="mt-4 d-flex div-2">
                            <h3>Packing Charges</h3>
                            <h4 class="text-end">+ ₹{{ $packingcharges }}</h4>
                        </div>
                        @endif
                        <!--@if ( $cart->first()->coupon_discount)-->
                        <div class="mt-4 d-flex div-2">
                            <h3>Coupon Discount</h3>
                            <h4 class="text-end"> - ₹{{ $coupn->first()->coupon_discount }}</h4>
                        </div>
                        <!--@endif-->
                        @if($otherscharges > 0 || $otherscharges > 0.00)
                        <div class="mt-4 d-flex div-2">
                            <h3>Others Charges</h3>
                            <h4 class="text-end">+ ₹{{ $otherscharges }}</h4>
                        </div>
                        @endif



                        <div class="mt-4 d-flex div-2 b-b">
                            <h3>Delivery Charges</h3>
                           <h4 class="text-end">
                                  @if ($overall_qty > 24)
                                + ₹{{ $bulkDeliverycharges }}
                                @elseif (($cart_Items->product_types == 1 && $cart_Items->total_qty <= 24) || ($cart_Items->product_types == 2 && $cart_Items->total_qty <= 6))
                               + ₹{{ $singleDeliverycharges }}

                                @else

                               + ₹{{ $singleDeliverycharges }}

                                @endif

                            </h4>

                        </div>

                        <div class="my-4 d-flex div-3">
                            <h3>Grand Total ({{ $totalProduct }} Items)</h3>
                            <h4 class="text-end">₹{{number_format( $totalDiscountValue,2) }}</h4>
                        </div>
                            @if(session('not_servicable'))
                                <div class="alert alert-danger">
                                    {{ session('not_servicable') }}
                                </div>
                            @endif

                           @if ($zoneProcessingData 
    && $totalDiscountValue <= $zoneProcessingData->order_above 
    && $zoneProcessingData->pay_on_delivery == 'yes')
    
    <button class="btn red-btn my2 w-100 pay_on_delivery" tabindex="0"
        @if(session('not_servicable')) disabled @endif>
        Pay on Delivery
    </button>
@endif


                            <button class="btn red-btn my-2 w-100 checkout_pay" id="rzp-button1" tabindex="0" @if(session('not_servicable')) disabled @endif>
                                Pay Now &nbsp;<h4 class="text-data"> ₹{{ number_format($totalDiscountValue, 2) }}</h4>
                               
                            </button>

                            @if ($outletData->credit_status == 'Active' && ($totalDiscountValue + $totalDueAmount) <= $outletData->credit_limit)
                                <button class="btn red-btn my-2 w-100 credit_pay" tabindex="0"
                                    @if(session('not_servicable')) disabled @endif>
                                    Place Order on Credit
                                </button>
                            @endif


                    </div>


                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content viewdetail">
                <div class="modal-header">
                    <h5 class="modal-title order-h5" id="exampleModalLabel">Orders </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($cart->isEmpty())
                    <p>Your cart is empty.</p>
                @else
                <div class="row row1">

                      <div class="table-responsive">
                        <table class="table cart_table tablemodel">
                            <tbody>
                                <tr>
                                    <th class="ct1 view">Sr.No.</th>
                                    <th class="ct2 view">Product Name</th>
                                    <th class="ct3 view">Approved unit price</th>
                                    <th class="ct5 view">Total QTY</th>
                                    <th class="ct6 view">Total Amt (Basic)</th>
                                </tr>
                                @if ($cart->isEmpty())
                                <tr>
                                    <td colspan="5">No items in cart</td>
                                </tr>
                                @endif
                                @foreach ($cart as $key => $cart_Items)
                                <tr class="cart-row{{ $key + 1 }}">
                                    <td class="ct1">{{ $key + 1 }}</td>
                                    <td class="ct2 ">
                                        <div class="d-flex align-items-center">
                                            <img src="../uploads/{{ $cart_Items->product->image }}" class="enquiry-img">
                                            <p class="modeltext">{{ $cart_Items->product->product_name }}</p>
                                        </div>
                                    </td>
                                    <td class="ct3">
                                    <h6 class="color-grey">
                                        @if ($cart_Items->offer_price)
                                        ₹{{ $cart_Items->offer_price }}
                                        @endif
                                    </h6>

                                        @if ($cart_Items->product_types == 1)
                                        <span class="newstyle" style="color:red">
                                            Carton Box : 24 Nos.
                                        </span>
                                        @elseif ($cart_Items->product_types == 2)
                                        <span class="newstyle" style="color: red">
                                            Loose {{ $cart_Items->quantity }} Pcs
                                        </span>
                                        @else
                                        <span class="newstyle" style="color: blue">
                                            Loose (pcs.)
                                            @endif
                                          
                                    </td>

                                    <td class="ct5 view">{{ $cart_Items->total_qty }}</td>
                                    <td class="ct6">
                                        <span class="view total-amt-basic{{$key + 1}}">
                                            @if ($cart_Items->total_amt_basic)
                                            ₹{{ $cart_Items->total_amt_basic }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                      </div>


                </div>
                @endif
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="shippingAddress" tabindex="-1" aria-labelledby="shippingAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title order-h5" id="exampleModalLabel">Shipping Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="shippingForm" action="{{ route('update_shipping_address') }}" method="post">
                        @csrf
                        <div class="row my-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Outlet Name</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ $outletData->name }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="outlet_contact">Outlet Contact</label>
                                    <input type="text" class="form-control" name="outlet_contact" id="outlet_contact" value="{{ $outletData->mobile_number }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="outlet_email">Outlet Email</label>
                                    <input type="text" class="form-control" name="outlet_email" id="outlet_email" value="{{ $outletData->email }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="shippingAddress"><b>Shipping Address</b></label>
                                    <textarea type="text" name="shippingAddress" id="shippingAddress" class="form-control">{{ $mainshippingAddress }}</textarea>
                                </div>
                            </div>
                            <input type="hidden" name="outlet_id" value="{{ $outletID }}">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pincode">Pincode</label>
                                    <input type="text" class="form-control" name="pincode" id="pincode" value="{{ $mainshippingPincode }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn red-btn mt-5 mb-4 ">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <style>
        .form-group label{
            font-weight: 600
        }
    </style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.querySelector('.sameAsBilling');
    const shippingAddressEl = document.querySelector('.checkout-left-side .shipping');
    const shippingAddressEdit = document.querySelector('.checkout-left-side .fa-pen-to-square');

    // ✅ Safe Laravel variables
    const outletName = @json($outletData->name);
    const billingAddress = @json($billingAddress);
    const shippingAddress = @json($shippingAddress);

    checkbox.addEventListener('change', function () {
        if (this.checked) {
            shippingAddressEl.innerHTML = `${outletName} <br>${billingAddress}`;
            
            shippingAddressEdit.disabled = true;
            shippingAddressEdit.dataset.bsTarget = '';
            shippingAddressEdit.style.color = 'grey';

        } else {
            shippingAddressEl.innerHTML = `${outletName} <br>${shippingAddress}`;
            
            shippingAddressEdit.disabled = false;
            shippingAddressEdit.dataset.bsTarget = '#shippingAddress';
            shippingAddressEdit.style.color = '#942525';
        }
    });
});
</script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
   <script>
$(document).ready(function () {

    const checkbox = document.querySelector('.sameAsBilling');

    // ✅ SAFE Laravel variables
    const billingAddress = @json($billingAddress);
    let shippingAddress = @json($shippingAddress);
    const subtotal = Number(@json($subTotalAmt));
    const productDiscount = Number(@json($totalproductDiscount));
    const cgstSgst = Number(@json($result));
    const packingCharges = Number(@json($packingcharges));
    const user_id = @json($outletID);
    const othersCharges = Number(@json($otherscharges));
    const deliveryCharges = Number(@json($overall_qty > 24 ? $bulkDeliverycharges : $singleDeliverycharges));
    const totalDiscountValue = Number(@json($totalDiscountValue));
    const shipping_pincode = @json($mainshippingPincode);
    const cart = @json($cart);

    // ✅ Handle checkbox logic safely
    if (checkbox && checkbox.checked) {
        shippingAddress = billingAddress;
    }

    $('.checkout_pay').click(function (e) {
        e.preventDefault();

        const deliveryDate = $('#delivery_date').val();
        const delivery_time_slot = $('#delivery_time_slot').val();
        const delivery_slot_type = $('#delivery_slot_type').val();

        if (!deliveryDate) {
            Swal.fire({
                text: "Select Date for Delivery",
                icon: "info",
                confirmButtonText: "OK"
            });
            return;
        }

        const data = {
            deliveryDate,
            delivery_time_slot,
            delivery_slot_type,
            billingAddress,
            shippingAddress,
            subtotal,
            user_id,
            productDiscount,
            cgstSgst,
            packingCharges,
            othersCharges,
            deliveryCharges,
            totalDiscountValue,
            shipping_pincode,
            payment_status: 'paid',
            cart
        };

        Swal.fire({
            title: 'Placing Order...',
            text: 'Please wait while we process your order',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('/create-order', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': @json(csrf_token())
            }
        })
        .then(res => res.json())
        .then(responseData => {

            Swal.close();

            if (responseData.error) {
                Swal.fire({
                    title: "Error",
                    text: responseData.error,
                    icon: "error"
                });
                return;
            }

            const options = {
                key: @json(env('RAZORPAY_KEY')),
                amount: responseData.amount,
                currency: "INR",
                name: "Zonik",
                description: "Infipara Solutions",
                order_id: responseData.order_id,
                callback_url: @json(route('razorpay.payment.success')),
                prefill: {
                    name: @json($outletData->name),
                    email: @json($outletData->email),
                    contact: @json($outletData->mobile_number)
                },
                theme: { color: "#a558c8" }
            };

            new Razorpay(options).open();
        })
        .catch(error => {
            Swal.close();
            console.error(error);

            Swal.fire({
                title: "Error",
                text: "Something went wrong",
                icon: "error"
            });
        });
    });





            $('.credit_pay').click(function() {
             
            var deliveryDate = $('#delivery_date').val();
            var delivery_time_slot = $('#delivery_time_slot').val();
            var delivery_slot_type = $('#delivery_slot_type').val();
            
                    if (deliveryDate == null) {
                        console.log(deliveryDate);
                        Swal.fire({
                            // title: "Error",
                            text: "Select Date for Delivery",
                            icon: "info",
                            confirmButtonText: "OK"
                        });
                        return;
                    } else {
                        console.log(deliveryDate);
                    }

                var data = {
                    deliveryDate: deliveryDate,
                    delivery_time_slot: delivery_time_slot,
                    delivery_slot_type: delivery_slot_type,
                    billingAddress: billingAddress,
                    shippingAddress: shippingAddress,
                    subtotal: subtotal,
                    user_id: user_id,
                    productDiscount: productDiscount,
                    cgstSgst: cgstSgst,
                    packingCharges: packingCharges,
                    othersCharges: othersCharges,
                    deliveryCharges: deliveryCharges,
                    shipping_pincode: shipping_pincode,
                    totalDiscountValue: totalDiscountValue,
                    payment_status: 'credit',
                    cart: cart

                };
                
                 Swal.fire({
                    title: 'Placing Order...',
                    text: 'Please wait while we process your order',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });


                $.ajax({
                    url: '/insert-order',
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        
                        Swal.close();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Order Successfully Placed!',
                            text: response.success,
                            showConfirmButton: true,
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('orders') }}';

                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        
                        Swal.close();
                        
                        console.error(error);
                    }
                });
            });

            $('.pay_on_delivery').click(function() {
            
            var deliveryDate = $('#delivery_date').val();
            var delivery_time_slot = $('#delivery_time_slot').val();
            var delivery_slot_type = $('#delivery_slot_type').val();
                    //  alert(deliveryDate);

                   if (deliveryDate == null) {

                        Swal.fire({
                            // title: "Error",
                            text: "Select Date for Delivery",
                            icon: "info",
                            confirmButtonText: "OK"
                        });
                        return;
                    }

                var data = {
                    deliveryDate: deliveryDate,
                    delivery_time_slot: delivery_time_slot,
                    billingAddress: billingAddress,
                    shippingAddress: shippingAddress,
                    subtotal: subtotal,
                    user_id: user_id,
                    productDiscount: productDiscount,
                    shipping_pincode: shipping_pincode,
                    cgstSgst: cgstSgst,
                    packingCharges: packingCharges,
                    othersCharges: othersCharges,
                    deliveryCharges: deliveryCharges,
                    totalDiscountValue: totalDiscountValue,
                    payment_status: 'pay_on_delivery',
                    cart: cart

                };
                
                 Swal.fire({
                    title: 'Placing Order...',
                    text: 'Please wait while we process your order',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });


                $.ajax({
                    url: '/insert-order',
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        
                        Swal.close();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Order Successfully Placed!',
                            text: response.success,
                            showConfirmButton: true,
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('orders') }}';

                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        
                        Swal.close();
                        console.error(error);
                    }
                });
            });
        });
        
// JavaScript
document.addEventListener('DOMContentLoaded', function() {
       document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
        const select = dropdown.previousElementSibling;
        const selected = dropdown.querySelector('.selected-option');
        const optionsList = dropdown.querySelector('.options-list');
    
        // --- Toggle Dropdown ---
        const toggleDropdown = (e) => {
            if (e.cancelable) e.preventDefault();
            e.stopPropagation();
    
            const isActive = dropdown.classList.contains('active');
            closeAllDropdowns();
            if (!isActive) dropdown.classList.add('active');
        };
    
        dropdown.addEventListener('click', toggleDropdown);
    
        // --- Handle Option Selection ---
        optionsList.querySelectorAll('.option').forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
    
                // UI label
                selected.textContent = option.textContent.trim();
    
                // Set original select value (delivery_date)
                select.value = option.dataset.date;
    
                // Set hidden inputs
                const dateInput  = document.getElementById('delivery_date');
                const timeInput  = document.getElementById('delivery_time_slot');
                const slotTypeInput = document.getElementById('delivery_slot_type');
    
                if (dateInput)     dateInput.value = option.dataset.date;
                if (timeInput)     timeInput.value = option.dataset.time;
                if (slotTypeInput) slotTypeInput.value = option.dataset.slotType || '';
    
                closeAllDropdowns();
                triggerChangeEvent(select);
            });
        });
    });

    // --- Close Dropdowns when clicking outside ---
    document.addEventListener('click', closeAllDropdowns);

    // --- Close on scroll (optional, better UX on mobile) ---
    window.addEventListener('scroll', function(e) {
        document.querySelectorAll('.custom-dropdown.active').forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });
    }, true);

    // --- Helpers ---
    function closeAllDropdowns() {
        document.querySelectorAll('.custom-dropdown.active').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    }

    function triggerChangeEvent(element) {
        const event = new Event('change', { bubbles: true });
        element.dispatchEvent(event);
    }
});


    </script>
    <script>
    </script>
@endsection

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
