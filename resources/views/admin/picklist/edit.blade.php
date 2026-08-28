@extends('admin.layouts.appnew')

@section('content')

<div class="page-body">
<div class="container-fluid page-body-wrapper">
<div class="main-panel">
<div class="content-wrapper">

<div class="card">
<div class="card-body">

<h4 class="mb-2">Pick List (Edit)</h4>
<p>Order ID: <strong>{{ $order->order_id }}</strong></p>

<div class="mb-3">
  
   <button class="btn btn-success" onclick="updatePickList()">
    Update Pick List
</button>

    <a href="{{ route('pick.list') }}" class="btn btn-secondary">Back</a>
</div>

<form id="pickForm">
@csrf
<input type="hidden" name="order_id" value="{{ $order->id }}">

{{-- Dispatch Info --}}

<div class="row mb-3">


<div class="col-md-4">
    <label>Dispatched Rack</label>
    <input type="text"
           name="dispatched_rack"
           id="dispatched_rack"
           class="form-control"
           value="{{ $logistics->dispatched_rack ?? '' }}">
</div>

<div class="col-md-4">
    <label>Number of Boxes</label>
    <input type="number"
           name="number_of_boxes"
           id="number_of_boxes"
           class="form-control"
           value="{{ $logistics->number_of_boxes ?? '' }}">
</div>


</div>

{{-- Table --}}

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
    <th>Stock</th>
    <th>Ordered</th>
    <th>Pick Qty</th>
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
    <td>{{ number_format($row['needed'],2) }}</td>


<td>
    <input type="number"
           step="0.01"
           class="form-control"
           name="items[{{ $i }}][pick_qty]"
           value="{{ number_format($row['pick_qty'],2,'.','') }}"
           max="{{ number_format($row['available'],2,'.','') }}">
</td>

<input type="hidden" name="items[{{ $i }}][product_id]" value="{{ $row['product_id'] }}">
<input type="hidden" name="items[{{ $i }}][rack_no]" value="{{ $row['rack_no'] }}">
<input type="hidden" name="items[{{ $i }}][level_no]" value="{{ $row['level_no'] }}">
<input type="hidden" name="items[{{ $i }}][slot_no]" value="{{ $row['slot_no'] }}">
<input type="hidden" name="items[{{ $i }}][batch_no]" value="{{ $row['batch_no'] }}">
<input type="hidden" name="items[{{ $i }}][expiry]" value="{{ $row['expiry'] }}">


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

<script>
    function updatePickList() {

    let rack  = $('#dispatched_rack').val();
    let boxes = $('#number_of_boxes').val();

    if (!rack || !boxes || boxes <= 0) {
        Swal.fire('Validation', 'Dispatched rack and number of boxes are required', 'warning');
        return;
    }

    Swal.fire({
        title: 'Confirm Update',
        text: 'Save changes to pick list?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Update'
    }).then((result) => {

        if (!result.isConfirmed) return;

        $.ajax({
            url: "{{ route('pick.list.update') }}",
            type: "POST",
            data: $('#pickForm').serialize(),

            success: function (res) {
                Swal.fire('Success', res.message, 'success')
                    .then(() => {
                       window.location.href = "{{ route('pick.list') }}";
                    });
            },

            error: function () {
                Swal.fire('Error', 'Update failed', 'error');
            }
        });
    });
}

</script>
@endsection
