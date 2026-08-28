@extends('admin.layouts.appnew')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Pre Short Material Log</h3>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="stockTable">
                            
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Product</th>
                                    <th>Outlet</th>
                                    <th>Ordered Qty</th>
                                    <th>Live Stock</th>
                                    <th>Short Qty</th>
                                    <th>Date</th>
                                    <th>Comment</th>
                                    <th>Expected Lost Value</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($logs as $index => $log)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>

                                    <td>#ORD-00{{ $log->order_id }}</td>

                                    <td>
                                        {{ $log->product->product_name ?? '-' }}
                                    </td>
                                    
                                    <td>
                                       {{ $log->order->outlet->outlet_name ?? '-' }}
                                    </td>
                                   

                                    <td class="text-center">
                                        {{ $log->required_qty }}
                                    </td>

                                    <td class="text-center text-danger fw-bold">
                                        <!--{{ $log->available_stock }}-->
                                        {{ $log->stock->total_stock ?? 0 }}
                                    </td>
                                    
                                    <td class="text-center text-warning fw-bold">
                                        {{ max(0, $log->required_qty - $log->available_stock) }}
                                    </td>

                                    <td>
                                        {{ $log->created_at->format('d M Y, h:i A') }}
                                    </td>
                                    
                                    <td>
                                        <input type="text" 
                                            class="form-control comment-input" 
                                            data-id="{{ $log->id }}"
                                            value="{{ $log->comment }}">
                                    </td>

                                    <td>
                                        <input type="number" step="0.01"
                                            class="form-control lost-value-input"
                                            data-id="{{ $log->id }}"
                                            value="{{ $log->lost_value }}">
                                    </td>
                                    
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        No Short Material Logs Found
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

@endsection
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    function saveData(id) {
        let comment = document.querySelector(`.comment-input[data-id='${id}']`).value;
        let lost_value = document.querySelector(`.lost-value-input[data-id='${id}']`).value;

        fetch("{{ route('pre.short.log.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                id: id,
                comment: comment,
                lost_value: lost_value
            })
        });
    }

    document.querySelectorAll(".comment-input, .lost-value-input")
        .forEach(input => {
            input.addEventListener("change", function () {
                let id = this.dataset.id;
                saveData(id);
            });
        });

});
</script>
