@extends('admin.layouts.appnew')

@section('content')

<div class="container-fluid">

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Post Short Material Log</h3>
                    </div>

                    {{--  SINGLE TABLE START --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle text-center" id="stockTable">

                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Outlet</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Live Stock</th>
                                    <th>Brand</th>
                                    <th>Ordered</th>
                                    <th>Supplied</th>
                                    <th>Short</th>
                                    <th>Comment</th>
                                    <th>Lost Value</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php $count = 1; @endphp

                                @foreach($items as $orderId => $orderItems)
                                    @foreach($orderItems as $item)
                                    <tr>
                                        <td>{{ $count++ }}</td>

                                        <td>#ORD-00{{ $orderId }}</td>

                                        <td>{{ $item->outlet_name }}</td>

                                        <td>{{ date('d-m-Y', strtotime($item->order_date)) }}</td>

                                        <td class="text-start">
                                            {{ $item->product_name }}
                                        </td>
                                        
                                        <td class="fw-bold text-primary">
                                            {{ $item->live_stock }}
                                        </td>

                                        <td>{{ $item->brand }}</td>

                                        <td>{{ $item->ordered_qty }}</td>

                                        <td>{{ $item->supplied_qty }}</td>

                                        <td class="text-danger fw-bold">
                                            {{ $item->short_qty }}
                                        </td>

                                        <td>
                                            <input type="text"
                                                class="form-control comment-input"
                                                data-order="{{ $item->order_id }}"
                                                data-product="{{ $item->product_id }}"
                                                value="{{ $item->comment }}">
                                        </td>

                                        <td>
                                            <input type="number" step="0.01"
                                                class="form-control lost-value-input"
                                                data-order="{{ $item->order_id }}"
                                                data-product="{{ $item->product_id }}"
                                                value="{{ $item->lost_value }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                    {{--  SINGLE TABLE END --}}

                </div>
            </div>

        </div>
    </div>

</div>

@endsection

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    function saveRow(order_id, product_id) {

        let row = document.querySelector(
            `.comment-input[data-order='${order_id}'][data-product='${product_id}']`
        ).closest('tr');

        let comment = row.querySelector('.comment-input').value;
        let lost_value = row.querySelector('.lost-value-input').value;
        let short_qty = row.querySelector('.text-danger').innerText.trim();

        fetch("{{ route('post.short.material.save') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                order_id: order_id,
                product_id: product_id,
                comment: comment,
                lost_value: lost_value,
                short_qty: short_qty
            })
        });
    }

    document.querySelectorAll(".comment-input, .lost-value-input")
        .forEach(input => {
            input.addEventListener("change", function () {
                saveRow(this.dataset.order, this.dataset.product);
            });
        });

});
</script>   