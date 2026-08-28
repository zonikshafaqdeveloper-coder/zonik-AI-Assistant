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
                                    <h4 class="mb-0">Price Change Logs</h4>
                                </div>

                                {{-- TABLE --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="stock_table">

                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>Old Cost</th>
                                                <th>New Cost</th>
                                                <th>Difference</th>
                                                <th>Supplier</th>
                                                <th>Status</th>
                                                <th width="220">Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($logs as $index => $log)
                                                <tr>

                                                    <td>{{ $index + 1 }}</td>

                                                    <td>
                                                        <strong>{{ $log->product->product_name ?? '-' }}</strong>
                                                    </td>

                                                    <td>₹{{ number_format($log->old_cost, 2) }}</td>

                                                    <td>₹{{ number_format($log->new_cost, 2) }}</td>

                                                    <td class="text-success">
                                                        +₹{{ number_format($log->difference, 2) }}
                                                    </td>

                                                    <td>{{ $log->supplier_name ?? '-' }}</td>

                                                    <td>
                                                        @if($log->status == 'pending')
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        @elseif($log->status == 'approved')
                                                            <span class="badge bg-success">Approved</span>
                                                        @else
                                                            <span class="badge bg-danger">Rejected</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if($log->status == 'pending')

                                                            <div class="d-flex gap-1">

                                                                {{-- Flat --}}
                                                                <button 
                                                                    class="btn btn-success btn-sm btn-flat"
                                                                    data-id="{{ $log->id }}"
                                                                    data-url="{{ route('admin.price.logs.approve.flat', $log->id) }}"
                                                                >
                                                                    Flat
                                                                </button>

                                                                {{-- Edit --}}
                                                                <a href="{{ route('admin.price.logs.edit', $log->id) }}"
                                                                    class="btn btn-primary btn-sm">
                                                                    Edit
                                                                </a>

                                                                {{-- Reject --}}
                                                                <button 
                                                                    class="btn btn-danger btn-sm btn-reject"
                                                                    data-id="{{ $log->id }}"
                                                                    data-url="{{ route('admin.price.logs.reject', $log->id) }}"
                                                                >
                                                                    Reject
                                                                </button>

                                                            </div>

                                                        @else
                                                            <span class="text-muted">No actions</span>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        No price change logs found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                    </table>
                                </div>

                                {{-- Pagination --}}
                                

                            </div>

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
    
  //    Add filter on # head column
    let sortDirection = {};

    $('#stock_table thead th').click(function(){

    let index = $(this).index();
    sortDirection[index] = !sortDirection[index];

        let rows = $('#stock_table tbody tr').get();
    rows.sort(function(a, b){

    let A = $(a).children('td').eq(index).text().trim();
    let B = $(b).children('td').eq(index).text().trim();

    // clean values first
    let cleanA = A.replace(/[₹,+\s]/g,'');
    let cleanB = B.replace(/[₹,+\s]/g,'');

    let isNumeric = !isNaN(cleanA) && !isNaN(cleanB);

    if (isNumeric) {
        let numA = parseFloat(cleanA);
        let numB = parseFloat(cleanB);
        return sortDirection[index] ? numA - numB : numB - numA;
    }

    return sortDirection[index]
        ? A.localeCompare(B)
        : B.localeCompare(A);
    });

        $.each(rows, function(i, row){
            $('#stock_table tbody').append(row);
        });

    });

    /*
    |--------------------------------------------------------------------------
    | FLAT INCREASE
    |--------------------------------------------------------------------------
    */
    $('.btn-flat').click(function(){

        let url = $(this).data('url');

        Swal.fire({
            title: 'Apply Flat Increase?',
            text: 'All customer prices will be increased.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Apply'
        }).then((result) => {

            if (!result.isConfirmed) return;

            fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => {
                if (!res.ok) throw res;
                return res.json();
            })
            .then(data => {

                Swal.fire('Success', data.message ?? 'Price updated', 'success')
                    .then(() => location.reload());

            })
            .catch(async err => {

                let msg = "Something went wrong";

                if (err.json) {
                    let e = await err.json();
                    if (e.message) msg = e.message;
                }

                Swal.fire('Error', msg, 'error');
            });

        });

    });


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */
    $('.btn-reject').click(function(){

        let url = $(this).data('url');

        Swal.fire({
            title: 'Reject this change?',
            text: 'This action cannot be undone',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Reject'
        }).then((result) => {

            if (!result.isConfirmed) return;

            fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => {
                if (!res.ok) throw res;
                return res.json();
            })
            .then(data => {

                Swal.fire('Rejected', data.message ?? 'Rejected successfully', 'success')
                    .then(() => location.reload());

            })
            .catch(async err => {

                let msg = "Something went wrong";

                if (err.json) {
                    let e = await err.json();
                    if (e.message) msg = e.message;
                }

                Swal.fire('Error', msg, 'error');
            });

        });

    });

});
</script>

@endsection