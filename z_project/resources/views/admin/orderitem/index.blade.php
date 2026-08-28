@extends('admin.layouts.appnew')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    td{
        text-transform: capitalize;

    }
    td:nth-child(9){
        white-space: nowrap;
    }
    .box{
border: 1px solid #f3f3f3;
border-collapse: collapse;
padding: 5px 5px;
    }

    .box p{
        margin: 5px auto !important;
    }
    .btn-danger{
        color: #ffffff;
    }
</style>
<div class="page-body">
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper ">
                    <div class="row">
                        @if(!$orderItems->isEmpty())
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif
                                    <div class="order-details border p-2">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="box">
                                                    <p><strong>Order ID:</strong> {{ $orderItems->first()->order->id ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="box">
                                                    <p><strong>User Name:</strong> {{$orderItems->first()->order->user->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="box">
                                                    <p><strong>Order Date:</strong> {{ $orderItems->first()->order->created_at ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="box">
                                                    <p><strong>Outlet Name:</strong> {{$orderItems->first()->order->user->outlet_name ?? 'N/A' }}</p>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="box">
                                                    <p><strong>Payment Status:</strong> {{ $orderItems->first()->order->payment_status ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="box">
                                                    <p><strong>Contact:</strong> {{$orderItems->first()->order->user->mobile_number ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="box">
                                                    <p><strong>Billing Address:</strong> {{ $orderItems->first()->order->billing_address ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="box">
                                                    <p><strong>Shipping Address:</strong> {{ $orderItems->first()->order->shipping_address ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="stock_table">
                                            <thead class="b-shadow">
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Product Name</th>
                                                    <th>Price</th>
                                                    <th>Original Quantity</th>
                                                    <th style="width:165px;">Processed Quantity</th>
                                                    <th>Available Stock</th>
                                                    <th>Profit Margin</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                 @php
                                                 $totalQty = 0;
                                                 $totalPrice = 0;
                                                 @endphp
                                            
                                                @foreach($orderItems as $orderItem)

                                                @php
                                                   $availableStock = $orderItem->product->stock->total_stock ?? 0;
                                                    $originalQty = $originalItems[$orderItem->product_id]->quantity ?? $orderItem->quantity;

       
                                                    $price = $orderItem->offer_price;
                                                    $cost  = $orderItem->product->cost_per_item ?? 0;

                                                    if ($cost > 0 && $price > 0) {
                                                        $margin = (($price - $cost) / $cost) * 100;
                                                        $marginText = number_format($margin, 2) . ' %';
                                                    } else {
                                                        $marginText = '0.00 %';
                                                    }
                                            

                                                     $totalQty += $originalQty;
                                                     $totalPrice += $orderItem->price;
                                                

                                                @endphp
                                                <tr>
                                                    <td>{{ $orderItem->order->id }}</td>
                                                    <td>{{ $orderItem->product->product_name }}</td>
                                                    <td>₹{{ $orderItem->price }}</td>
                                                    <td>
                                                        <span class="badge bg-primary fs-14">{{ $originalQty }}</span>
                                                    </td>
                                                        <td>
                                                            <input type="number"
                                                                class="form-control quantity-input"
                                                                data-order-item-id="{{ $orderItem->id }}"
                                                                data-available="{{ $availableStock }}"
                                                                value="{{ $orderItem->quantity }}"
                                                                id="quantity_{{ $orderItem->id }}" />
                                                        </td>
                                                       <!--<td>-->
                                                       <!--     <span class="available-stock" data-stock="{{ $orderItem->product->stock->total_stock ?? 0 }}"> {{ $orderItem->product->stock->total_stock ?? 0 }} </span>-->
                                                       <!-- </td>-->
                                                       
                                                        <td>
                                                         @php
                                                            $stock = $availableStock;
                                                            $qty   = $orderItem->quantity;

                                                            $colorClass = 'text-success'; // default green

                                                            if ($stock == 0) {
                                                                $colorClass = 'text-danger';
                                                            } elseif ($qty > $stock) {
                                                                $colorClass = 'text-warning';
                                                            }
                                                        @endphp
                                                            <span class="available-stock {{ $colorClass }}" data-stock="{{ $orderItem->product->stock->total_stock ?? 0 }}">  {{ $stock }}</span>
                                                        </td>

                                                        <td>
                                                            <span>
                                                                {{ $marginText }}
                                                            </span>
                                                        </td>

                                            <td class="text-center">
                                            <!-- @if($availableStock > 0)
                                            <a href="{{ route('rack.stock.history', ['product_id' => $orderItem->product->id, 'order_id' => $orderItem->order->id]) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-info px-2 py-1 me-1"
                                        title="View Rack Stock">
                                            <i class="mdi mdi-eye fs-16"></i>
                                        </a>

                                            @endif -->

                                            <a href="#" 
                                            onclick="confirmDelete('{{ $orderItem->id }}')" 
                                            class="btn btn-sm btn-danger px-2 py-1"
                                            title="Remove Item">
                                                <i class="mdi mdi-trash-can-outline fs-16"></i>
                                            </a>
                                        </td>


                                        </tr>
                                        @endforeach

                                    </tbody>
                                    
                                </table>

                                <!-- Add for sum of qty and price -->
                                      <tr style="background:#f5f5f5; font-weight:bold;">
                                       <td class="text-end">Total amount of price => ₹ {{ number_format($totalPrice, 2) }}</td>
                                        </br>
                                        <td>
                                        <span class="text-end">Total Original quantity  => {{ $totalQty }}</span>
                                        </td>
                                      </tr>
                                       <!-- Add for sum of qty and price -->

                                <div class="mt-4">
                                    @if($deliveryStatus === 'hold')

                                        <div class="alert alert-warning">
                                            This order is currently <strong>On Hold</strong>. 
                                            No action can be performed until hold is removed.
                                        </div>

                                        <button class="btn btn-primary" disabled>Create Pick List</button>
                                        <button class="btn btn-success" disabled>Accept Order</button>
                                        <button class="btn btn-danger" disabled>Cancel Order</button>

                                    @else
                                    
                                      <button class="btn btn-primary" id="createPickListBtn"
                                        onclick="openPickList('{{ $orderItems->first()->order->id }}')">
                                        Create Pick List
                                    </button>
                                    <button class="btn btn-success" onclick="acceptOrder('{{ $orderItems->first()->order->id }}')">Accept Order</button>
                                    <button class="btn btn-danger" onclick="cancelOrder('{{ $orderItems->first()->order->id }}')">Cancel Order</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               @else
             <div class="row">
            <div class="col-lg-12  stretch-card">
                <h2>No Data Found</h2>
            </div>
        </div>
        @endif
</div></div></div></div></div>

<script>
    const orderListUrl = "{{ route($redirectRoute) }}";
</script>

<script>
function confirmDelete(orderItemId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to delete this order item. This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteOrderItem(orderItemId);
        }
    });
}

function deleteOrderItem(orderItemId) {
    var token = "{{ csrf_token() }}";

    $.ajax({
        url: '/delete-order-item/' + orderItemId,
        type: 'DELETE',
        data: { _token: token },
        success: function (response) {

            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: response.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: response.message || 'Unable to delete order item'
                });
            }
        },
        error: function (xhr) {
            let msg = 'Something went wrong while deleting the item';

            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg
            });
        }
    });
}

function validateStockForPickList() {
    let isValid = true;

    $('#stock_table tbody tr').each(function () {
        const stock = parseFloat($(this).find('.available-stock').data('stock')) || 0;
        const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;

        if (quantity <= 0 || quantity > stock) {
            isValid = false;
        }
    });

    // if (!isValid) {
    //     $('#createPickListBtn')
    //         .prop('disabled', true)
    //         .addClass('btn-secondary')
    //         .removeClass('btn-primary');
    // } else {
    //     $('#createPickListBtn')
    //         .prop('disabled', false)
    //         .addClass('btn-primary')
    //         .removeClass('btn-secondary');
    // }
}


function acceptOrder(orderId) {

    
    validateStockForPickList();
    if ($('#createPickListBtn').prop('disabled')) {
        Swal.fire(
            'Stock Issue',
            'Stock is not sufficient for one or more items. Please correct before accepting order.',
            'warning'
        );
        return;
    }

   
    $.get('/check-pick-list/' + orderId, function (pickRes) {

        if (!pickRes.status) {
            Swal.fire({
                title: 'Pick List Issue',
                text: pickRes.message,
                icon: 'warning'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'Pick list is confirmed and stock is valid. Do you want to accept this order?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, accept it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                
                 Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we accept the order',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                    
                });
                
                $.ajax({
                    url: '/accept-order/' + orderId,
                    type: 'POST',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success')
                                .then(() => {
                                    window.location.href = orderListUrl;
                                });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                   error: function (xhr) {
                        let errorMessage = 'Something went wrong while accepting order';
                    
                        if (xhr.status === 0) {
                            errorMessage = 'Network error. Please check your internet connection.';
                        } else if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                    
                            if (xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors)
                                    .map(err => err[0])
                                    .join('<br>');
                            }
                        }
                    
                        Swal.fire({
                            title: 'Error',
                            html: errorMessage,
                            icon: 'error'
                        });
                    }
                });
            }
        });

    }).fail(function () {
        Swal.fire('Error', 'Unable to verify pick list status', 'error');
    });
}


function cancelOrder(orderId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This will cancel the order permanently!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            var token = "{{ csrf_token() }}";

            $.ajax({
                url: '/cancel-order/' + orderId,
                type: 'POST',
                data: { _token: token },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Success', response.message, 'success')
                            .then(() => {
                                window.location.href = orderListUrl;
                            });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Something went wrong while cancelling order', 'error');
                }
            });
        }
    });
}                        
</script>
<script>
$(document).on('input', '.quantity-input', function () {

    const input = $(this);
    const orderItemId = input.data('order-item-id');
    const available = parseFloat(input.data('available')) || 0;

    let val = parseFloat(input.val()) || 0;

    // Prevent negative
    if (val < 0) {
        input.val(0);
        return;
    }

    // Prevent exceeding stock
    if (val > available) {
        Swal.fire(
            'Stock Not Available',
            'Entered quantity exceeds available stock (' + available + ')',
            'error'
        );
        input.val(available);
        val = available;
    }

    // Validate pick list button
    validateStockForPickList();

    // Now safe to save
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: "{{ route('orderItem.updateQuantity') }}",
        type: "POST",
        data: {
            _token: csrfToken,
            quantity: val,
            order_item_id: orderItemId,
        },
        success: function (response) {
            // Optional success feedback
            // console.log("Quantity updated");
        },
        error: function () {
            Swal.fire('Error', 'Failed to update quantity', 'error');
        }
    });
});


</script>

<script>
 function openPickList(orderId) {
    window.location.href = '/pick-list-preview/' + orderId;
}

$(document).ready(function () {
    validateStockForPickList();
});


</script>
@endsection

