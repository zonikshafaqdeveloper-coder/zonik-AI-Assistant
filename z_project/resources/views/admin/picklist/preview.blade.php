@extends('admin.layouts.appnew')

@section('content')
<style>
.qty-ok {
    border: 2px solid #28a745 !important; /* green */
}

.qty-low {
    border: 2px solid #fd7e14 !important; /* orange */
}

.qty-zero {
    border: 2px solid #dc3545 !important; /* red */
}
</style>
<div class="page-body">
          <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">

                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif

    <h4 class="mb-2">Pick List Preview</h4>
    <p>
        Order ID: <strong>{{ $order->order_id ?? $order->id }}</strong>
    </p>

    <div class="mb-3">
<button type="button" class="btn btn-primary" onclick="downloadPickListPdf()">
    Download Pick List PDF
</button>


        <button class="btn btn-success" onclick="savePickList()">Mark as Picked</button>
         <a href="{{ route('orderitem.details', $order->id) }}"
       class="btn btn-secondary">
        Back to Invoice
    </a>
    </div>

    <form id="pickForm">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        
        <div class="row mb-3">

    <div class="col-md-4">
        <label>Dispatched Rack</label>
        <input type="text"
               name="dispatched_rack"
               id="dispatched_rack"
               class="form-control"
               placeholder="Enter rack number"
               value="{{ $logistics->dispatched_rack ?? '' }}">
    </div>

    <div class="col-md-4">
        <label>Number of Boxes</label>
        <input type="number"
               name="number_of_boxes"
               id="number_of_boxes"
               class="form-control"
               min="1"
               step="1"
               value="{{ $logistics->number_of_boxes ?? '' }}">
    </div>

</div>

        <div class="table-responsive">
          <table class="table table-bordered theme-table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Bay </th>
            <th>Column </th>
            <th>Floor</th>
            <th>Batch</th>
            <th>Expiry</th>
            <th>Stock in hand Qty</th>
            <th>Original order qty</th>
            <th>Picked order qty</th>
        </tr>
    </thead>
    <tbody>
    @foreach($pickData as $i => $row)
        <tr>
            <td>{{ $row['product'] }}</td>
            <td>{{ $row['rack_no'] }}</td>
            <td>{{ $row['level_no'] }}</td>
            <td>{{ $row['slot_no'] }}</td>
            <td>{{ $row['batch_no'] ?? '-' }}</td>
            <td>{{ $row['expiry'] ?? '-' }}</td>
            <td>{{ number_format($row['available'],2) }}</td>
            <td>
                <span class="badge bg-warning">
                    {{ number_format($row['needed'],2) }}
                </span>
            </td>
            <td>
                <input type="number"
                       step="0.01"
                       class="form-control pick-qty"
                       name="items[{{ $i }}][pick_qty]"
                       value="{{ number_format($row['pick_qty'], 2, '.', '') }}"
                       max="{{ number_format($row['available'], 2, '.', '') }}"
                       data-stock="{{ number_format($row['available'], 2, '.', '') }}">
            </td>

            {{-- hidden fields --}}
            <input type="hidden" name="items[{{ $i }}][product_id]" value="{{ $row['product_id'] }}">
            <input type="hidden" name="items[{{ $i }}][rack_no]" value="{{ $row['rack_no'] }}">
            <input type="hidden" name="items[{{ $i }}][level_no]" value="{{ $row['level_no'] }}">
            <input type="hidden" name="items[{{ $i }}][slot_no]" value="{{ $row['slot_no'] }}">
            <input type="hidden" name="items[{{ $i }}][batch_no]" value="{{ $row['batch_no'] }}">
            <input type="hidden" name="items[{{ $i }}][expiry]" value="{{ $row['expiry'] }}">
            <input type="hidden" name="items[{{ $i }}][needed]" value="{{ $row['needed'] }}">
            
        </tr>
    @endforeach
    </tbody>
</table>

        </div>
    </form>

     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
// console.log('SCRIPT LOADED');

function updateQtyUI(input) {

    let qty = parseFloat(input.val()) || 0;
    let stock = parseFloat(input.attr('data-stock')) || 0;

    console.log('RUNNING:', qty, stock);

    input.removeClass('qty-ok qty-low qty-zero');

    if (stock === 0) {
        input.addClass('qty-zero');

    } else if (qty > stock) {
        input.addClass('qty-low');

    } else {
        input.addClass('qty-ok');
    }
}

$(document).ready(function () {

    $('.pick-qty').each(function () {
        updateQtyUI($(this)); 
    });

});


$(document).on('input', '.pick-qty', function () {
    updateQtyUI($(this)); 
});
</script>
<script>
function savePickList() {
    
    let rack  = $('#dispatched_rack').val();
    let boxes = $('#number_of_boxes').val();

    if (!rack || !boxes || boxes <= 0) {
        Swal.fire('Validation', 'Dispatched rack and number of boxes are required', 'warning');
        return;
    }
    
    Swal.fire({
        title: 'Confirm Pick List',
        text: 'Are you sure all items are picked correctly from racks?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Save & Continue',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('pick.list.preview.save') }}",
                type: "POST",
                data: $('#pickForm').serialize(),
                success: function (res) {
                    if (res.status) {
                        Swal.fire({
                            title: 'Success',
                            text: res.message,
                            icon: 'success'
                        }).then(() => {
                           
                            window.location.href = "{{ route('orderitem.details', $order->id) }}";
                        });
                    } else {
                        Swal.fire('Error', res.message || 'Unable to save pick list', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Server error while saving pick list', 'error');
                }
            });

        }
    });
}

function openPickList(orderId) {
    $.get('/check-pick-list/' + orderId, function (res) {
        if (res.status) {
            Swal.fire({
                title: 'Already Picked',
                text: 'Pick list already exists and order is ready for acceptance.',
                icon: 'info'
            });
        } else {
            window.open('/pick-list-preview/' + orderId, '_blank');
        }
    });
}

function downloadPickListPdf() {
    let form = document.getElementById('pickForm');
    form.action = "{{ route('pick.list.preview.pdf') }}";
    form.method = "POST";
    form.target = "_blank";  
    form.submit();
}


</script>
@endsection


