@extends('admin.layouts.appnew')

@section('content')

<div class="page-body">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">

                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">

                            <div class="card-body">

                                {{-- Success Message --}}
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <!--<h3 class="card-title">Modify Order Quantity</h3>-->
                                    <h3 class="card-title">Modify Price</h3>

                                  
                                </div>


                                <div class="table-responsive">
                                    <table class="table all-package theme-table" id="modifyStockTable">

                                        <thead class="b-shadow">
<tr>
<th>Product</th>
<th>Offer Price</th>
<th>Current Qty</th>
<th>Available Stock</th>
<th>Modify</th>
</tr>
</thead>

<tbody>

@foreach($orderItems as $item)

<tr>

<td>{{ $item->product->product_name }}</td>

<td>
    <input type="number"
           class="form-control price-input"
           data-id="{{ $item->id }}"
           value="{{ $item->offer_price }}"
           style="width:100px">
</td>

<td>
<span class="badge bg-primary">
{{ $item->quantity }}
</span>
</td>

<td>
<span class="stock">
{{ $item->product->stock->total_stock ?? 0 }}
</span>
</td>

<!--Modify qty-->

<td>

<div class="d-flex align-items-center">

<button class="btn btn-danger btn-sm minus-btn"
        data-id="{{ $item->id }}">
    -
</button>

<input type="number"
class="form-control text-center mx-2 qty-input"
data-id="{{ $item->id }}"
data-stock="{{ $item->product->stock->total_stock ?? 0 }}"
value="{{ $item->quantity }}"
style="width:80px">

<button class="btn btn-success btn-sm plus-btn"
        data-id="{{ $item->id }}">
+
</button>

</div>

</td>

</tr>

@endforeach

</tbody>

</table>
</div>

<button class="btn btn-primary" id="saveChanges">
Save Changes
</button>

          </div> {{-- card-body --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

$(document).ready(function(){

    $('.qty-input').each(function(){

        let qty = parseInt($(this).val()) || 0;
        let stock = parseInt($(this).data('stock')) || 0;

        // Store original values
        $(this).data('original-qty', qty);
        $(this).data('original-stock', stock);
    });

    /*
    |------------------------------------------
    | PLUS BUTTON
    |------------------------------------------
    */
    $('.plus-btn').click(function(){

        let id = $(this).data('id');
        let input = $('.qty-input[data-id="'+id+'"]');

        let current = parseInt(input.val()) || 0;
        let originalQty = parseInt(input.data('original-qty'));
        let originalStock = parseInt(input.data('original-stock'));

        // 🔥 Dynamic available stock
        let used = current - originalQty;
        let available = originalStock - used;

        if (available <= 0) {
            Swal.fire(
                'Stock Limit',
                'No more stock available in this rack',
                'warning'
            );
            return;
        }

        input.val(current + 1);
    });

    /*
    |------------------------------------------
    | MINUS BUTTON
    |------------------------------------------
    */
    $('.minus-btn').click(function(){

        let id = $(this).data('id');
        let input = $('.qty-input[data-id="'+id+'"]');

        let current = parseInt(input.val()) || 0;

        if (current <= 0) {
            return;
        }

        input.val(current - 1);
    });

    /*
    |------------------------------------------
    | MANUAL INPUT (SMART VALIDATION)
    |------------------------------------------
    */
    $('.qty-input').on('input', function(){

        let input = $(this);

        let val = parseInt(input.val()) || 0;
        let originalQty = parseInt(input.data('original-qty'));
        let originalStock = parseInt(input.data('original-stock'));

        if (val < 0) {
            input.val(0);
            return;
        }

    
        let maxAllowed = originalQty + originalStock;

        if (val > maxAllowed) {

            Swal.fire(
                'Stock Limit',
                'Max allowed quantity is ' + maxAllowed,
                'error'
            );

            input.val(maxAllowed);
        }
    });

});




</script>
<script>

$('#saveChanges').click(function(){

    let btn = $(this);

    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to update this order.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, save it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if (result.isConfirmed) {

            let items = {};
            let orderId = "{{ $orderItems->first()->order_id }}";

            $('.qty-input').each(function(){

                let orderItemId = $(this).data('id');
                let qty = $(this).val();
                let price = $('.price-input[data-id="'+orderItemId+'"]').val();

                items[orderItemId] = {
                    qty: qty,
                    price: price
                };

            });

            $.ajax({

                url: "{{ route('order.modify.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    order_id: orderId,
                    items: items
                },

                beforeSend: function(){

                    
                    btn.prop('disabled', true).html(`
                        <span class="spinner-border spinner-border-sm"></span>
                        Saving...
                    `);

                },

                success: function(response){

                    if(response.success){

                        Swal.fire(
                            'Saved!',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload();
                        });

                    }

                },

                error: function(xhr){

                    Swal.fire(
                        'Error',
                        xhr.responseJSON?.message || 'Something went wrong',
                        'error'
                    );

                },

                complete: function(){

                    
                    btn.prop('disabled', false).html('Save Changes');

                }

            });

        }

    });

});


//comment on 04-04-26
// $('#saveChanges').click(function(){

//     let items = {};
//     let orderId = "{{ $orderItems->first()->order_id }}";

//     $('.qty-input').each(function(){

//         let orderItemId = $(this).data('id');
//         let qty = $(this).val();

//         items[orderItemId] = qty;

//     });

//     $.ajax({

//         url: "{{ route('order.modify.update') }}",
//         type: "POST",
//         data: {
//             _token: "{{ csrf_token() }}",
//             order_id: orderId,
//             items: items
//         },

//         success: function(response){

//             if(response.success){

//                 Swal.fire(
//                     'Success',
//                     response.message,
//                     'success'
//                 ).then(() => {

//                     location.reload();

//                 });

//             }

//         },

//         error: function(xhr){

//             Swal.fire(
//                 'Error',
//                 xhr.responseJSON.message,
//                 'error'
//             );

//         }

//     });

// });

</script>
@endsection