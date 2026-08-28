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
                        <h3 class="mb-0">Expired Product Report</h3>
                    </div>



     <div class="table-responsive">
       <table class="table table-striped table-bordered" id="nonRunningTable">
                            <thead class="table-dark">
<tr>
<th>ITEM CODE</th>
<th>ITEM</th>
<th>UNIT</th>
<th>QTY</th>
<th>BRAND</th>
<th>CATEGORY</th>
<th>SUPPLIER NAME</th>
<th>EXPIRY DATE</th>
<th>DAYS PASSED EXPIRY</th>
<th>VIEW LOCATION</th>
<th>Option 1</th>
<th>Option 2</th>
</tr>
</thead>

<tbody>

@foreach($stocks as $stock)

<tr>

<td>{{ $stock->item_code }}</td>

<td>{{ $stock->item }}</td>

<td>{{ $stock->unit }}</td>

<td>{{ $stock->qty }}</td>

<td>{{ $stock->brand }}</td>

<td>{{ $stock->category }}</td>

<td>{{ $stock->supplier_name }}</td>

<td>{{ $stock->expiry_date }}</td>

<td>
<span class="badge bg-danger">
{{ $stock->days_passed_expiry }}
</span>
</td>

<td>
{{ $stock->rack_no }}-{{ $stock->level_no }}-{{ $stock->slot_no }}
</td>

<td>
    @if(is_null($stock->stock_receiving_id))
      <a href="{{ route('admin.debitnote.from.opening', [
            'product_id' => $stock->product_id,
            'batch_no' => $stock->batch_no,
            'expiry_date' => $stock->expiry_date
        ]) }}"
        class="btn btn-sm btn-info">
            Return (Opening Stock)
        </a>
    @else
        <a href="{{ route('admin.debitnote.from.expired', [
            'product_id' => $stock->product_id,
            'batch_no' => $stock->batch_no,
            'expiry_date' => $stock->expiry_date
        ]) }}" 
        class="btn btn-sm btn-warning">
            Return (Debit Note)
        </a>
    @endif
</td>
<td>
<a href="javascript:void(0)" 
   class="btn btn-sm btn-danger dispose-btn"
   data-product_id="{{ $stock->product_id }}"
   data-batch_no="{{ $stock->batch_no }}"
   data-expiry_date="{{ $stock->expiry_date }}">
   Damaged
</a>
</td>

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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}",
            timer: 2000,
            showConfirmButton: false
        });
    @endif

});
</script>
<script>
    $(document).on('click', '.dispose-btn', function () {

    let product_id = $(this).data('product_id');
    let batch_no = $(this).data('batch_no');
    let expiry_date = $(this).data('expiry_date');

    Swal.fire({
        title: 'Dispose Stock',
        input: 'textarea',
        inputLabel: 'Reason (Optional)',
        inputPlaceholder: 'Enter reason for disposal...',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Dispose',
        confirmButtonColor: '#d33',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('admin.disposals.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product_id,
                    batch_no: batch_no,
                    expiry_date: expiry_date,
                    reason: result.value
                },

                success: function (response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Disposed!',
                        text: 'Stock disposed successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                },

                error: function (xhr) {

                    let errorMsg = 'Something went wrong';

                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg
                    });
                }

            });

        }

    });

});
</script>


@endsection