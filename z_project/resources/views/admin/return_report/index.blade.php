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
                                    <h3 class="card-title">Return & Pending Return Report</h3>
                                </div>




    {{-- FILTER --}}
    <form method="GET" class="mb-3">
        <select name="type" class="form-control w-25 d-inline">
            <option value="">All</option>
            <option value="RETURN" {{ request('type')=='RETURN'?'selected':'' }}>Return</option>
            <option value="PENDING_RETURN" {{ request('type')=='PENDING_RETURN'?'selected':'' }}>Pending Return</option>
        </select>

        <button class="btn btn-primary">Filter</button>
    </form>

<div class="table-responsive">
                                    <table class="table all-package theme-table" id="stock_table">
                                        <thead class="b-shadow">
        <thead>
            <tr>
                <th>GRN NO</th>
                <th>TYPE</th>
                <th>ITEM</th>
                <th>UNIT</th>
                <th>QTY</th>
                <th>BRAND</th>
                <th>SUPPLIER NAME</th>
                <th>BILL NO</th>
                <th>BILL DATE</th>
                <th>PAYMENT STATUS</th>
                <th>ACTION</th>
            </tr>
        </thead>

        <tbody>
        @forelse($movements as $move)

            @php
                $receiving = $move->receiving;
                $product = $move->product;
            @endphp

            <tr>

                {{-- GRN --}}
                <td>
                    GRN-{{ str_pad($receiving->id ?? 0,4,'0',STR_PAD_LEFT) }}
                </td>
                <td>
    @if($move->movement_type == 'RETURN')
        <span class="badge bg-success">Returned</span>
    @elseif($move->movement_type == 'PENDING_RETURN')
        <span class="badge bg-warning">To Be Returned</span>
    @endif
</td>

                {{-- ITEM --}}
                <td>{{ $product->product_name ?? '-' }}</td>

                {{-- UNIT --}}
                <td>{{ $product->unit ?? '-' }}</td>

                {{-- QTY --}}
                <td>{{ $move->quantity }}</td>

                {{-- BRAND --}}
                <td>{{ $product->brands ?? '-' }}</td>

                {{-- SUPPLIER --}}
                <td>{{ $receiving->vendor->name ?? '-' }}</td>

                {{-- BILL NO --}}
                <td>{{ $receiving->bill_no ?? '-' }}</td>

                {{-- BILL DATE --}}
                <td>{{ $receiving->bill_date ?? '-' }}</td>

                {{-- PAYMENT STATUS --}}
                <td>{{ $receiving->vendorBill->status ?? '-' }}</td>

                {{-- ACTION --}}
                <td>
                   <td>
    @if($move->movement_type === 'RETURN')

       <a href="{{ route('admin.debit-note.download.single', $move->id) }}"
   class="btn btn-success btn-sm">
    Download Debit Note
</a>

    @elseif($move->movement_type === 'PENDING_RETURN')

<button 
    class="btn btn-warning btn-sm create-debit-btn"
    data-id="{{ $move->id }}">
    Create Debit Note
</button>

    @endif
</td>
                </td>

            </tr>

        @empty
            <tr>
                <td colspan="11" class="text-center">
                    No records found
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
$(document).on('click', '.create-debit-btn', function () {

    let id = $(this).data('id');

    Swal.fire({
        title: 'Create Debit Note?',
        text: "This will generate a debit note for this return",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, create it!'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('stock.createDebitNote', '') }}/" + id,
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