@extends('admin.layouts.appnew')

@section('content')

<div class="container-fluid">

    

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Products For Urgent Sale</h3>
                    </div>



     <div class="table-responsive">
   
 <table class="table table-striped table-bordered text-center align-middle" id="stockTable">

    <thead class="table-dark">
        <tr>
            <th>Code</th>
            <th>Item</th>
            <th>Unit</th>
            <th>Qty</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Supplier</th>
            <th>Expiry</th>
            <th>Days Left</th>
            <th>Location</th>
            <th>Pick List</th>
            <th>Return</th>
        </tr>
    </thead>

    <tbody>
    @foreach($stocks as $stock)
        <tr>
            <td>{{ $stock->item_code }}</td>
            <td class="text-start">{{ $stock->item }}</td>
            <td>{{ $stock->unit }}</td>
            <td>
                <span class="badge bg-warning text-dark">
                    {{ $stock->qty }}
                </span>
            </td>
            <td>{{ $stock->brand }}</td>
            <td>{{ $stock->category }}</td>
            <td>{{ $stock->supplier_name }}</td>

            <td>{{ \Carbon\Carbon::parse($stock->expiry_date)->format('d-m-Y') }}</td>

            <td>
                @if($stock->days_to_expiry <= 30)
                    <span class="badge bg-danger">
                        {{ $stock->days_to_expiry }} days
                    </span>
                @else
                    <span class="badge bg-warning text-dark">
                        {{ $stock->days_to_expiry }} days
                    </span>
                @endif
            </td>

            <td>
                <span class="badge bg-info">
                    {{ $stock->rack_no }}-{{ $stock->level_no }}-{{ $stock->slot_no }}
                </span>
            </td>

            <!-- ✅ Pick List Toggle -->
            <td>
                <div class="form-check form-switch d-flex justify-content-center">
                    <input class="form-check-input sale-toggle"
                           type="checkbox"
                           data-id="{{ $stock->id }}"
                           {{ $stock->is_available_for_sale ? 'checked' : '' }}>
                </div>
            </td>

            <!-- ✅ Return Button -->
            <td>
                <form id="returnForm-{{ $loop->index }}" action="{{ route('admin.remove-from-sale') }}" method="POST">
                    @csrf

                    <input type="hidden" name="product_id" value="{{ $stock->product_id }}">
                    <input type="hidden" name="batch_no" value="{{ $stock->batch_no }}">
                    <input type="hidden" name="expiry_date" value="{{ $stock->expiry_date }}">
                    <input type="hidden" name="id" value="{{ $stock->id }}">

                    <button type="button"
                            class="btn btn-sm btn-outline-secondary return-btn"
                            data-form="returnForm-{{ $loop->index }}">
                        Return
                    </button>
                </form>
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
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.return-btn').forEach(button => {
        button.addEventListener('click', function () {

            let formId = this.getAttribute('data-form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This product will be moved back to Near Expiry!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6c757d',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, return'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });

        });
    });

});
</script>
<script>
$(document).on('change', '.sale-toggle', function () {

    let toggle = $(this);

    let id = toggle.data('id');
    let status = toggle.is(':checked') ? 1 : 0;

    let actionText = status 
        ? 'add this item to Pick List' 
        : 'remove this item from Pick List';

    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to ${actionText}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, proceed',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('admin.toggle-pick-list') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: status
                },

                success: function (res) {

                    if (res.success) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.message,
                            timer: 1200,
                            showConfirmButton: false
                        });

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message
                        });

                        toggle.prop('checked', !status);
                    }
                },

                error: function () {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong'
                    });

                    toggle.prop('checked', !status);
                }
            });

        } else {
            // revert if cancelled
            toggle.prop('checked', !status);
        }
    });

});
</script>

@endsection