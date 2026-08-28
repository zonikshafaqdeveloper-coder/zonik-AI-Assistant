@extends('admin.layouts.appnew')
@section('content')

<style>
    /* Common Action Button */
.action-btn{
    width:36px;
    height:36px;
    border:none;
    border-radius:50%;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:0;
    margin:0 2px;
    transition:all .2s ease;
    text-decoration:none;
}

.action-btn i{
    font-size:14px;
}

/* View */
.view-btn{
    background:#eef2ff;
    color:#4f46e5;
}
.view-btn:hover{
    background:#e0e7ff;
    color:#4338ca;
}

/* Invoice */
.invoice-btn{
    background:#fff7ed;
    color:#ea580c;
}
.invoice-btn:hover{
    background:#ffedd5;
    color:#c2410c;
}


/* Edit */
.edit-btn{
    background:#f3f4f6;
    color:#6b7280;
}
.edit-btn:hover{
    background:#e5e7eb;
    color:#374151;
}

/* Delete */
.delete-btn{
    background:#fef2f2;
    color:#ef4444;
}
.delete-btn:hover{
    background:#fee2e2;
    color:#dc2626;
}

table.dataTable thead th,
table.dataTable thead td,
table.dataTable tbody th,
table.dataTable tbody td{
    text-align: center !important;
    vertical-align: middle !important;
}
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-12 m-auto">

                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0">Quotations</h3>
                                <a href="{{ route('quotations.create') }}" class="btn btn-success">+ Create Quotation</a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="nonRunningTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Quotation No</th>
                                            <th>Outlet</th>
                                            <th>Customer</th>
                                            <th>Mobile</th>
                                            <th>Date</th>
                                            <th>Total Items</th>
                                            <th style="width:160px; white-space:nowrap;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($quotations as $i => $q)
                                        <tr id="row-{{ $q->id }}">
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $q->quotation_number }}</td>
                                            <td>{{ $q->leadCustomer->outlet_name ?? '-' }}</td>
                                            <td>{{ $q->leadCustomer->customer_name ?? '-' }}</td>
                                            <td>{{ $q->leadCustomer->mobile_number ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($q->quotation_date)->format('Y-m-d') }}</td>
                                            <td>{{ $q->items->count() }}</td>

                                            <td class="text-center">
                                                <a href="{{ route('quotations.show', $q->id) }}"  class="action-btn view-btn" title="View" title="View"> <i class="fa fa-eye"></i></a>
                                                <a href="{{ route('quotations.invoice', $q->id) }}" target="_blank" class="action-btn invoice-btn" title="Invoice"><i class="fa fa-arrow-up"></i></a>
                                                <a href="{{ route('quotations.edit', $q->id) }}" class="action-btn edit-btn" title="Edit"> <i class="fa fa-pen"></i></a>
                                                <button type="button" class="action-btn delete-btn delete-quotation" data-id="{{ $q->id }}" title="Delete"><i class="fa fa-times"></i></button>
                                            </td>

                                        </tr>
                                        @empty
                                        <tr><td colspan="8" class="text-center text-muted py-4">No quotations found.</td></tr>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).on('click', '.delete-quotation', function () {

    const id = $(this).data('id');
    const $row = $('#row-' + id);

    Swal.fire({
        title: 'Are you sure?',
        text: 'This quotation will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/quotations/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', data.message, 'success');
                    $row.fadeOut(300, function () { $(this).remove(); });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }
    });
});
</script>

@endsection