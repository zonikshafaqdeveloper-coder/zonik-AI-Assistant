@extends('admin.layouts.appnew')

@section('content')
<style>
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

.edit-btn{
    background:#f3f4f6;
    color:#6b7280;
}

.edit-btn:hover{
    background:#e5e7eb;
    color:#374151;
}

.delete-btn{
    background:#fef2f2;
    color:#ef4444;
}

.delete-btn:hover{
    background:#fee2e2;
    color:#dc2626;
}
#nonRunningTable thead th{
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

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0">Lead Customers</h3>
                                <a href="{{ route('lead-customers.create') }}" class="btn btn-success">
                                    + Add Lead Customer
                                </a>
                            </div>

                            <div class="table-responsive" >
                                <table class="table table-striped table-bordered" id="nonRunningTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Customer Name</th>
                                            <th>Outlet Name</th>
                                            <th>Location Cluster</th>
                                            <th>Area</th>
                                            <th>Address</th>
                                            <th>Mobile Number</th>
                                            <th>Payment Term</th>
                                            <th>Outbound Sales Name</th>
                                            <th>Inbound Account Lead</th>
                                            <th style="width:10rem;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($leadCustomers as $i => $lead)
                                        <tr id="row-{{ $lead->id }}">
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $lead->customer_name }}</td>
                                            <td>{{ $lead->outlet_name }}</td>
                                            <td>{{ $lead->location_cluster }}</td>
                                            <td>{{ $lead->area }}</td>
                                            <td>{{ $lead->address }}</td>
                                            <td>{{ $lead->mobile_number }}</td>
                                            <td>{{ $lead->payment_term }}</td>
                                            <td>{{ $lead->outbound_sale_name }}</td>
                                            <td>{{ $lead->inbound_account_lead }}</td>
                                            <td class="text-center" style="white-space: nowrap;">
                                                <a href="{{ route('lead-customers.edit', $lead->id) }}"
                                                  class="action-btn edit-btn"
                                                  title="Edit">
                                                     <i class="fa fa-pen"></i>
                                                </a>
                                                <button type="button"
                                                        class="action-btn delete-btn delete-lead"
                                                        data-id="{{ $lead->id }}"
                                                        title="Delete">
                                                   <i class="fa fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                No lead customers found.
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).on('click', '.delete-lead', function () {

    const id = $(this).data('id');
    const $row = $('#row-' + id);

    Swal.fire({
        title: 'Are you sure?',
        text: 'This lead customer will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        confirmButtonColor: '#d33'
    }).then((result) => {

        if (result.isConfirmed) {

            fetch(`/admin/lead-customer/${id}`, {
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
                    Swal.fire('Error', data.message || 'Unable to delete', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Server Error', 'Something went wrong', 'error');
            });
        }
    });
});
</script>

@endsection