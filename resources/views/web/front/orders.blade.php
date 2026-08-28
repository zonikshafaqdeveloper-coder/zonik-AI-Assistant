@extends('web.layouts.app')

@section('content')

<!-- --------------order dropdown----------------- -->

<div class="container top-css mb-5">

    <div class="row">
        @if(auth()->user())
        @if(auth()->user()->type == 'group')
        <div class="col-md-6">
            {{-- <form action="">
            <div class="form-group" style="width:168px;border-radius:5px;">
                <select class="form-control" id="filterOrders">
                    <option value="all">All</option>
                    <option value="paid">Paid Orders</option>
                    <option value="unpaid">Unpaid Orders</option>
                </select>
            </div>
        </form>  --}}
        
        

        </div>
        @endif
        @endif
        
        <div class="mt-4">
  <h4 class="fw-bold mb-1" style="font-size:xx-large; font-weight:800;">
    Your <span class="color-primary">Orders</span>
  </h4>
  <!--<small class="text-muted" style="font-size:x-large">Track and manage your recent purchases</small>-->
</div>


    </div>

    <div id="orderContainer" class="row mt-3 top-css">
        <div class="col-lg-10 col-md-12 row">
            <button class="btn col filter_btn main_filter_btn my-2 mx-1 active" data-status="all">All</button>
            <button class="btn col filter_btn  main_filter_btn my-2 mx-1" data-status="pending">In Review</button>
            <button class="btn col filter_btn  main_filter_btn my-2 mx-1" data-status="in_progress">In Progress</button>
            <button class="btn col filter_btn  main_filter_btn my-2 mx-1" data-status="ready_for_dispatch">Ready For
                Dispatch</button>
            <button class="btn col filter_btn main_filter_btn  my-2 mx-1" data-status="delivered">Delivered</button>
            <button class="btn col filter_btn main_filter_btn  my-2 mx-1" data-status="cancelled">Cancelled</button>
        </div>
       <!-- Original form (for desktop view) -->
<div class="row d-none d-md-block">
    <div class="col-lg-8">
        <form id="orderFiltersForm" method="POST" action="{{ route('orders_filter') }}">
            @csrf
            <div class="row align-items-center">
                <div class="col-md-3 col-sm-6 my-2">
                    <label for="orderIdFilter" class="form-label">Outlet:</label>
                    <div class="input-group">
                        <select name="outlet_name" id="outlet_name" class="outlet_name">
                            <option value="">Select Outlet</option>
                            @foreach ($userData as $user)
                            <option value="{{ $user->id }}" @if($user->id == $outlet) selected @endif>{{ $user->outlet_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 my-2">
                    <label for="orderIdFilter" class="form-label">Filter by Order ID:</label>
                    <div class="input-group">
                        @if ($orderId)
                        <input type="text" class="form-control" placeholder="Enter Order ID" value="{{ $orderId }}" name="orderId" id="orderIdFilter">
                        @else
                        <input type="text" class="form-control" placeholder="Enter Order ID" name="orderId" id="orderIdFilter">
                        @endif
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 my-2">
                    <label for="month" class="form-label">Filter by Month:</label>
                    <div class="input-group">
                        @if ($month)
                        <input type="month" class="form-control" name="month" id="month" value="{{ $month }}">
                        @else
                        <input type="month" class="form-control" name="month" id="month">
                        @endif
                    </div>
                </div>

                <div class="col-md-3 my-2 mb-0 d-flex">
                    <button type="submit" class="btn main_filter_btn active">Apply</button>
                    @if ($month || $orderId || $outlet)
                    <a href="{{ route('orders') }}" class="btn btn-danger main_filter_btn text-white">
                        <span class="text-white">Reset Filter</span>
                    </a>
                    @else
                    <button class="btn btn-danger main_filter_btn text-white">
                        <span class="text-white">Reset Filter</span>
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- New form (for mobile view) -->
<div class="d-md-none p-2">
    <form id="orderFiltersFormMobile" method="POST" action="{{ route('orders_filter') }}">
        @csrf
        <div class="d-flex gap-1 flex-wrap align-items-end">
            <!-- Outlet -->
            <div class="d-flex flex-column" style="flex: 1 1 30%; box-sizing: border-box;">
                <label for="outlet_name" class="form-label mb-1" style="font-size:11px;">Outlet:</label>
                <select name="outlet_name" id="outlet_name" class="form-select" style="height:32px; font-size:11px; text-align:center;">
                    <option value="">Select Outlet</option>
                    @foreach ($userData as $user)
                        <option value="{{ $user->id }}" @if($user->id == $outlet) selected @endif>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Order ID -->
            <div class="d-flex flex-column" style="flex: 1 1 30%; box-sizing: border-box;">
                <label for="orderIdFilter" class="form-label mb-1" style="font-size:11px;">Order ID:</label>
                <input type="text" name="orderId" id="orderIdFilter" placeholder="Enter Order ID" value="{{ $orderId ?? '' }}" class="form-control" style="height:32px; font-size:11px;">
            </div>

            <!-- Month -->
            <div class="d-flex flex-column" style="flex: 1 1 30%; box-sizing: border-box;">
                <label for="month" class="form-label mb-1" style="font-size:11px;">Month:</label>
                <input type="month" name="month" id="month" value="{{ $month ?? '' }}" class="form-control" style="height:32px; font-size:11px;">
            </div>
        </div>

        <!-- Buttons -->
        <div class="d-flex gap-1 mt-2">
            <button type="submit" class="btn main_filter_btn active">Apply</button>
                    @if ($month || $orderId || $outlet)
                    <a href="{{ route('orders') }}" class="btn btn-danger main_filter_btn text-white">
                        <span class="text-white">Reset Filter</span>
                    </a>
                    @else
                    <button class="btn btn-danger main_filter_btn text-white">
                        <span class="text-white">Reset Filter</span>
                    </button>
                    @endif
        </div>
    </form>
</div>



@if ($OrderData->isNotEmpty())
    @foreach ($OrderData as $order)
        @foreach($order->deliveries as $delivery)
            <div class="col-12 col-md-6 mb-4 order-item" data-status="{{ $delivery->delivery_status }}">
                <div class="order-card">
                    <div class="inner-h inner-h1 justify-content-between flex-wrap">
                        <div class="inner-h2">
                            <img src="{{ asset('frontweb/assests/images/checkout.png') }}" class="inner-h-img">
                            <div class="inner-h-left">
                                @if ($delivery->delivery_status == 'delivered')
                                    <p>Delivered on {{ \Carbon\Carbon::parse($order->delivery_date)->format('d-m-Y') }}</p>
                                @else
                                    <p>Delivery on {{ \Carbon\Carbon::parse($order->delivery_date)->format('d-m-Y') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-end flex-wrap gap-2 mt-2 mt-md-0">
                            <h6 class="inner-h-right1" style="background-color: {{ $delivery->background_color }}; color: {{ $delivery->text_color }}; border-radius: {{ $delivery->borderradis }}; border: 1px solid {{ $delivery->border_color }};">
                                {{ $delivery->status_text }}
                            </h6>

                            @if ($order->payment_status == 'paid')
                                <h6 class="inner-h-right2 paid">Paid</h6>
                            @else
                                <h6 class="inner-h-right2 unpaid">Unpaid</h6>
                            @endif

                            @if ($delivery->delivery_status === 'pending' || $delivery->delivery_status === 'in_progress')
                                <span data-title="Cancel your order.">
                                    <i onclick="cancelOrder('{{$order->id }}')" class="fa-solid fa-circle-xmark text-danger fs-22 newcss" style="cursor: pointer"></i>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-3 d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div>
                                <h5 class="fw-800 mb-1">Order ID : {{ $order->order_id }}</h5>
                                <p><strong>Outlet Name:</strong> {{ $order?->user->outlet_name }}</p>
                            </div>

                            <!--@if ($order->payment_status == 'unpaid' && $delivery->delivery_status != 'cancelled')-->
                            <!--    <button class="btn red-btn checkout_pay" -->
                            <!--        data-order-id="{{ $order->id }}"-->
                            <!--        data-amount="{{ $order->total_discount_value }}"-->
                            <!--        @if(session('not_servicable')) disabled @endif>-->
                            <!--        Pay Now &nbsp; ₹ {{ $order->total_discount_value }}-->
                            <!--    </button>-->
                            <!--@endif-->
                            
                             @php
    $payment = \App\Models\Payment::where('order_id', $order->id)->first();
    $totalAmount = (float)$order->total_discount_value;
    $paidAmount = $payment ? (float)$payment->total_paid : 0;
    $remainingAmount = max(0, $totalAmount - $paidAmount);
@endphp


    @if (in_array($order->payment_status, ['unpaid', 'partial']) 
     && $delivery->delivery_status != 'cancelled')

   <button  class="btn red-btn checkout_pay" 
        id="rzp-button1" 
        tabindex="0"
        data-order-id="{{ $order->id }}"
        data-amount="{{ $remainingAmount }}"
        @if(session('not_servicable')) disabled @endif>
        Pay Now &nbsp;<h4 class="text-data"> ₹ {{ number_format($remainingAmount, 2) }}</h4>
    </button>

@endif

                        </div>

                        <div class="d-flex justify-content-between flex-wrap">
                            <p><strong>Order Item Count:</strong> {{ $order->order_items_count }}</p>

                            @if (in_array($delivery->delivery_status, ['delivered','ready_for_dispatch','in_progress']))
                                <a href="{{ route('generateInvoiceAndDeliveryCharges.list',['id' => $order->id]) }}"
                                    onclick="window.open(this.href,'_blank','width=800,height=600'); return false;"
                                    class="view-invoice">View Invoice <i class="fa-solid fa-angle-right"></i></a>
                            @endif
                        </div>

                        <p><strong>Ordered:</strong> {{ $order->created_at->format('D, M d, h:i A') }}</p>

                     <h6 class="toggle-trigger" data-toggle-id="{{ $order->id }}">View Order <i class="fa-solid fa-angle-right"></i></h6>

<div class="table-respond toggle-content" id="orderDetails_{{ $order->id }}" style="display: none;">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($order->order_items as $item)
                <tr>
                    <td>{{ $item['product']['product_name'] ?? 'N/A' }}</td>
                    <td>{{ $item['quantity'] ?? 'N/A' }}</td>
                    <td>{{ $item['price'] ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No order items found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
@else
    <h3 class="text-center bordered p-3">No Orders Found</h3>
@endif

 @if ($OrderData && $OrderData->count() > 10)
            <div id="viewMoreButtonContainer" class="text-center mb-4">
                <button id="viewMoreButton" class="btn viewMoreButton">View More</button>
            </div>
        @endif




    </div>
</div>

<style>
 .order-card {
    border: 1px solid #ebebeb;
    display: flex;
    flex-direction: column;
    position: relative;
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    border-radius: 8px;
    height: auto;
}

.inner-h {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    background-color: #f5f5f5;
    padding: 10px;
    gap: 10px;
}

.inner-h2 {
    display: flex;
    align-items: center;
    gap: 6px;
}

.inner-h-img {
    width: 24px;
    height: 24px;
}

.inner-h-right1, .inner-h-right2 {
    font-weight: 700;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 4px;
}

.inner-h-right2.paid {
    background-color: rgb(229, 243, 243);
    color: rgb(17, 145, 153);
    border: 1px solid rgb(182, 222, 224);
}

.inner-h-right2.unpaid {
    background-color: rgb(229, 243, 243);
    color: rgb(153, 17, 17);
    border: 1px solid rgb(250, 171, 171);
}

.inner-h-left {
    color: rgb(54, 54, 54);
    font-weight: 500;
    font-size: 14px;
}

.view-invoice {
    color: #121286;
    /*color: #a558c8;*/
    font-weight: 600;
    text-decoration: none;
}

.table-respond {
    overflow-x: auto;
    width: 100%;
}

@media (max-width: 767px) {
    /*.inner-h, .d-flex {*/
    /*    flex-direction: column !important;*/
    /*    align-items: flex-start !important;*/
    /*}*/

    .checkout_pay {
        width: 100%;
        margin-top: 5px;
    }

    .view-invoice {
        margin-top: 5px;
        display: inline-block;
    }
}

</style>

<script>
    function cancelOrder(orderId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var token = "{{ csrf_token() }}";
                $.ajax({
                    url: '/cancel-order/' + orderId,
                    type: 'POST',
                    data: {
                        '_token': token
                    },
                    success: function(response) {
                        Swal.fire(
                            'Cancelled!',
                            'Your order has been Cancelled.',
                            'success'
                        ).then(() => {
                            window.location.href = '{{ route("orders") }}';
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'Something went wrong.',
                            'error'
                        );
                    }
                });
            }
        });
    }
    $(document).ready(function() {
        var numVisibleItems = 10;
        var totalItems = {{ $OrderData->count() }};

        function showAdditionalItems() {
            $('.order-item:hidden').slice(0, 10).slideDown();
            numVisibleItems += 10;
            if (numVisibleItems >= totalItems) {
                $('#viewMoreButtonContainer').hide();
            }
        }

        $('#viewMoreButton').click(function() {
            var button = $(this);
            var spinner = '<span class="spinner"></span>';
            if (!button.hasClass('loading')) {
                button.addClass('loading').html(spinner);
            } else {
                button.removeClass('loading').html("View More");
            }
            setTimeout(function() {
                showAdditionalItems();
                button.removeClass('loading').html("View More");
            }, 850);
        });

        // Initially hide items beyond the first 10
        $('.order-item:gt(9)').hide();
    });

</script>
<script>
    // document.addEventListener('DOMContentLoaded', function() {
    //     const toggleTriggers = document.querySelectorAll('.toggle-trigger');
    //     toggleTriggers.forEach(function(toggleTrigger) {
    //         toggleTrigger.addEventListener('click', function() {
    //             const toggleId = this.getAttribute('data-toggle-id');
    //             const toggleContent = document.getElementById('orderDetails_' + toggleId);
    //             if (toggleContent) {
    //                 toggleContent.style.display = toggleContent.style.display === 'none' ?
    //                     'block' : 'none';
    //             }
    //         });
    //     });
    // });
    
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-trigger').forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            var orderId = this.getAttribute('data-toggle-id');
            var content = document.getElementById('orderDetails_' + orderId);

            if (content.style.display === "none") {
                content.style.display = "block";
            } else {
                content.style.display = "none";
            }
        });
    });
});

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>

    document.addEventListener("DOMContentLoaded", function() {
        const filterBtns = document.querySelectorAll('.main_filter_btn');
        const orderItems = document.querySelectorAll('.order-item');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const status = btn.dataset.status;
                filterOrders(status);
                toggleActiveClass(btn);
            });
        });

        function filterOrders(status) {
            orderItems.forEach(item => {
                if (status === 'all' || item.dataset.status === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function toggleActiveClass(clickedBtn) {
            filterBtns.forEach(btn => {
                btn.classList.remove('active');
            });
            clickedBtn.classList.add('active');
        }
    });
    
window.onload = function () {
    $('.checkout_pay').click(function (e) {
        e.preventDefault();

        let orderId = $(this).attr('data-order-id');
        let totalDiscountValue = $(this).attr('data-amount');

        console.log("🛒 Selected Order ID:", orderId);
        console.log("💰 Selected Amount:", totalDiscountValue);

        let data = {
            order_id: orderId ? orderId : null,
            payment_status: 'paid',
            totalDiscountValue: totalDiscountValue
        };

        fetch('/updatepay-order', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(responseData => {
            console.log("✅ Razorpay Response:", responseData);

            if (!responseData.order_id || !responseData.amount) {
                Swal.fire({
                    title: "Error",
                    text: "Invalid payment details. Please try again.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
                return;
            }

            var options = {
                "key": responseData.razorpay_key,
                "amount": responseData.amount,
                "currency": "INR",
                "name": "Zonik",
                "description": "Order Payment",
                "image": "https://example.com/your_logo",
                "order_id": responseData.order_id,
                "callback_url": "/handle-payment-update",
                
                "theme": { "color": "#a558c8" }
            };

            console.log("🚀 Initializing Razorpay with options:", options);

            if (typeof Razorpay !== "undefined") {
                var rzp1 = new Razorpay(options);
                rzp1.open();
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Payment gateway failed to load. Please refresh the page.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: "Error",
                text: "An error occurred. Please try again.",
                icon: "error",
                confirmButtonText: "OK"
            });
        });
    });
};



</script>
@endsection


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

