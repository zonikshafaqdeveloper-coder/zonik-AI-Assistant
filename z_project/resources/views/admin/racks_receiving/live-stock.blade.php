@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-11 m-auto">
                
                    <div class="card">
                        <div class="card-body">

                          

<h3 class="mb-4">Live Stock (Current Warehouse Position)</h3>

<div class="table-responsive">
    <table class="table table-bordered" id="stock_table">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Vendor</th>
            <th>Batch</th>
            <th>Expiry</th>
            <th>Bay</th>
            <th>Column</th>
            <th>Floor</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp

        @forelse($stocks as $i => $row)
            @php $total += $row->quantity; @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="fw-bold">
                    {{ $row->product->product_name ?? '-' }}
                </td>
                <td>{{ $row->vendor_name ?? 'Opening Stock' }}</td>
                <td>{{ $row->batch_no ?? '-' }}</td>
                <td>{{ $row->expiry_date ?? '-' }}</td>
                <td>{{ $row->rack_no }}</td>
                <td>{{ $row->level_no }}</td>
                <td>{{ $row->slot_no }}</td>
                <td class="fw-bold">{{ $row->quantity }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center text-muted">
                    No stock available in warehouse
                </td>
            </tr>
        @endforelse
    </tbody>

    <!-- @if($stocks->count())
    <tfoot class="fw-bold">
        <tr>
            <td colspan="8" class="text-end">Total Quantity</td>
            <td>{{ $total }}</td>
        </tr>
    </tfoot>
    @endif -->
</table>
</div>


                       
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
