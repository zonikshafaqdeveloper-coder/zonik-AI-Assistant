@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="row">
                    <div class="col-sm-12 m-auto">

                        <div class="card">
                                    <div class="card-header">
                                        <h4>  Short Materials — Order #{{ $items->first()->order_id ?? '' }}</h4>
                                    </div>

                            <div class="card-body">


<div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
            <tr>
                <th>Product</th>
                <th>Brand</th>
                <th>Ordered Qty</th>
                <th>Supplied Qty</th>
                <th>Short Qty</th>
            </tr>
        </thead>

        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->brand }}</td>
                <td>{{ $item->ordered_qty }}</td>
                <td>{{ $item->supplied_qty }}</td>
                <td class="text-danger fw-bold">
                    {{ $item->short_qty }}
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>

<div class="mt-3 d-flex justify-content-end">
    <a href="{{ route('short.material.log') }}" class="btn btn-secondary">
        ← Back
    </a>
</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection