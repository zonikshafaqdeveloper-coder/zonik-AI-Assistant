@extends('admin.layouts.appnew')

@section('content')
<div class="container-fluid">

   
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Near Expiry Stock Report</h3>
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
                <th>DAYS TO EXPIRY</th>
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

                    @if($stock->days_to_expiry <= 30)
                        <span class="badge bg-danger">
                            {{ $stock->days_to_expiry }}
                        </span>
                    @else
                        <span class="badge bg-warning">
                            {{ $stock->days_to_expiry }}
                        </span>
                    @endif

                </td>

                <td>
                    {{ $stock->rack_no }}-
                    {{ $stock->level_no }}-
                    {{ $stock->slot_no }}
                </td>

                            <td>
    @if(is_null($stock->stock_receiving_id))
        <span class="badge bg-info">Opening Stock</span>
    @else
        <a href="{{ route('admin.debitnote.from.expiry', [
            'product_id' => $stock->product_id,
            'batch_no' => $stock->batch_no,
            'expiry_date' => $stock->expiry_date
        ]) }}" 
        class="btn btn-sm btn-warning">
            Debit Note
        </a>
    @endif
</td>

                <td>
    <form id="saleForm-{{ $loop->index }}" action="{{ route('admin.put-on-sale') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $stock->product_id }}">
        <input type="hidden" name="batch_no" value="{{ $stock->batch_no }}">
        <input type="hidden" name="expiry_date" value="{{ $stock->expiry_date }}">
        <input type="hidden" name="id" value="{{ $stock->id }}">

        <button type="button" 
                class="btn btn-sm btn-success sale-btn"
                data-form="saleForm-{{ $loop->index }}">
            Put On Sale
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

    document.querySelectorAll('.sale-btn').forEach(button => {
        button.addEventListener('click', function () {

            let formId = this.getAttribute('data-form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This product will be moved to urgent sale!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, put on sale'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });

        });
    });

});
</script>

@endsection