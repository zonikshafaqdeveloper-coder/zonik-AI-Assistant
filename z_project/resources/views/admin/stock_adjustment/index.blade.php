@extends('admin.layouts.appnew')
@section('content')

<style>
.top {
    margin-bottom: 10px;
}

.bottom {
    margin-top: 10px;
}

.search-box {
    margin-top: 10px;
}

.search-box .dataTables_filter {
    text-align: left !important;
}

.search-box input {
    width: 250px;
    padding: 6px;
}  

.dataTables_filter {
    text-align: left !important;
}

.dataTables_filter input {
    width: 250px;
    border-radius: 6px;
    padding: 6px;
}   
</style>

<div class="page-body">
<div class="container-fluid page-body-wrapper">
<div class="main-panel">
<div class="content-wrapper">

<div class="card">
<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="card-title">Stock Adjustment</h3>
</div>

<div class="table-responsive">
<table class="table all-package theme-table" id="stockadjustment">
    <thead class="b-shadow">
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Total Quantity</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->product->product_name ?? 'N/A' }}</td>
                <td>
                    <span class="badge bg-success">
                        {{ number_format($row->total_qty, 2) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.stock-adjustment.create', $row->product_id) }}"
                       class="btn btn-sm btn-primary">
                        Adjust
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">
                    No rack stock available for adjustment.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>

</div>
</div>

</div>
</div>
</div>
</div>

@endsection
