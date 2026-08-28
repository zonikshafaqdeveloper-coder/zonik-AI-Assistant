@extends('web.layouts.app')

@section('content')





<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="message-box _success">
                     <i class="fa fa-check-circle" aria-hidden="true"></i>
                    <h2> Your payment was successful </h2>
                   <p> Thank you for your payment. we will <br>
be in contact with more details shortly </p>
            </div>
        </div>
    </div>
    <hr>

</div>
<style>
    ._failed {
        border-bottom: solid 4px red !important;
    }

    ._failed i {
        color: red !important;
    }

    ._success {
        box-shadow: 0 15px 25px #00000019;
        padding: 45px;
        width: 100%;
        text-align: center;
        margin: 40px auto;
        border-bottom: solid 4px #28a745;
    }

    ._success i {
        font-size: 55px;
        color: #28a745;
    }

    ._success h2 {
        margin-bottom: 12px;
        font-size: 40px;
        font-weight: 500;
        line-height: 1.2;
        margin-top: 10px;
    }

    ._success p {
        margin-bottom: 0px;
        font-size: 18px;
        color: #495057;
        font-weight: 500;
    }
</style>

    <script>
        $(document).ready(function() {
            var jsonData = localStorage.getItem('orderData');
            var storedData = JSON.parse(jsonData);
            var deliveryDate = storedData.deliveryDate;
            var billingAddress = storedData.billingAddress;
            var shippingAddress = storedData.shippingAddress;
            var subtotal = storedData.subtotal;
            var user_id = storedData.user_id;
            var productDiscount = storedData.productDiscount;
            var cgstSgst = storedData.cgstSgst;
            var packingCharges = storedData.packingCharges;
            var othersCharges = storedData.othersCharges;
            var deliveryCharges = storedData.deliveryCharges;
            var totalDiscountValue = storedData.totalDiscountValue;
            var payment_status = storedData.payment_status;
            var cart = storedData.cart;
            var data = {
                deliveryDate: deliveryDate,
                billingAddress: billingAddress,
                shippingAddress: shippingAddress,
                subtotal: subtotal,
                user_id: user_id,
                productDiscount: productDiscount,
                cgstSgst: cgstSgst,
                packingCharges: packingCharges,
                othersCharges: othersCharges,
                deliveryCharges: deliveryCharges,
                totalDiscountValue: totalDiscountValue,
                payment_status: payment_status,
                cart: cart
            };

            console.log(data);

                $.ajax({
                    url: '/insert-order',
                    method: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                    //  console.log(response);
                    // window.location.href = response;
                    localStorage.removeItem('orderData');

                         Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.success,
                            showConfirmButton: true,
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('homepage') }}';
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
    </script>

@endsection

