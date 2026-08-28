@extends('admin.layouts.appnew')

@section('content')

<div class="page-body">
<div class="container-fluid page-body-wrapper">
<div class="main-panel">
<div class="content-wrapper">

<div class="card">
<div class="card-body">

<h4 class="mb-2">Pick List (View Only)</h4>
<p>Order ID: <strong>{{ $order->order_id }}</strong></p>

<div class="mb-3">
   
    <a href="{{ route('pick.list') }}" class="btn btn-secondary">Back</a>
</div>

{{-- Dispatch Info --}}

<div class="row mb-3">

<div class="col-md-4">
    <label>Dispatched Rack</label>
    <input class="form-control"
           value="{{ $logistics->dispatched_rack ?? '-' }}"
           readonly>
</div>

<div class="col-md-4">
    <label>Number of Boxes</label>
    <input class="form-control"
           value="{{ $logistics->number_of_boxes ?? '-' }}"
           readonly>
</div>
```

</div>

{{-- Table --}}

<div class="table-responsive">
<table class="table table-bordered theme-table">

<thead>
<tr>
    <th>Product</th>
    <th>Bay</th>
    <th>Column </th>
    <th>Floor</th>
    <th>Batch</th>
    <th>Expiry</th>
    <th>Stock</th>
    <th>Ordered</th>
    <th>Picked</th>
</tr>
</thead>

<tbody>
@foreach($pickData as $row)
<tr>
    <td>{{ $row['product'] }}</td>
    <td>{{ $row['rack_no'] }}</td>
    <td>{{ $row['level_no'] }}</td>
    <td>{{ $row['slot_no'] }}</td>
    <td>{{ $row['batch_no'] ?? '-' }}</td>
    <td>{{ $row['expiry'] ?? '-' }}</td>
    <td>{{ number_format($row['available'],2) }}</td>
    <td>{{ number_format($row['needed'],2) }}</td>
    <td>{{ number_format($row['pick_qty'],2) }}</td>
</tr>
@endforeach
</tbody>

</table>
</div>

</div>
</div>
</div>
</div>
</div>
@endsection
