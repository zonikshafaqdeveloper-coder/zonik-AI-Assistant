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
                                    <h3 class="card-title">Stock Ledger</h3>

<!--<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">-->
<!--                        Back-->
<!--                    </a>-->

                                    
                                </div>

                           
<div class="table-responsive">
                    <table class="table all-package theme-table" id="stock_table">
                        <thead class="b-shadow ">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Unit Cost</th>
                                <th>Reference</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $index => $m)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($m->created_at)->format('d-m-Y H:i') }}</td>
                                    <td>{{ $m->product->product_name ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $m->movement_type === 'IN' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $m->movement_type }}
                                        </span>
                                          @if($m->movement_type === 'PENDING_RETURN')
                                    <button 
                                            class="btn btn-warning btn-sm return-btn"
                                            data-id="{{ $m->id }}">
                                            Mark Returned
                                        </button>
                                        @endif
                                    </td>
                                    <td>{{ $m->quantity }}</td>
                                    <td>{{ $m->unit_cost }}</td>
                                    <td>{{ $m->reference_type }} #{{ $m->reference_id }}</td>
                                    <td>{{ $m->remarks }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        No stock movements found
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
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).on('click', '.return-btn', function () {

    let id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This will mark the item as Returned",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, return it!'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('stock.markReturned', '') }}/" + id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {

                    if (res.status) {
                        Swal.fire('Success!', res.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }
            });

        }
    });
});
</script>




@endsection
