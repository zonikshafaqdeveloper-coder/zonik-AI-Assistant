@extends('admin.layouts.appnew')
@section('content')
<style>
.payment-export-btn{
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #6c757d;
    transition: all 0.3s ease;
}

.payment-export-btn:hover{
    background: #198754;
    border-color: #198754;
    color: #fff !important;
}

.payment-export-btn:hover i{
    color: #fff !important;
}
</style>
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
                                    
                                    <h3 class="card-title">Vendors</h3>                                       
                                     

                                    <a href="{{ route('vendors.create') }}"
                                       class="btn btn-primary">
                                        + Add Vendor
                                    </a>
                                </div>
                                    <!-- Custom Export -->
 <a href="{{ route('vendors.export.payment') }}"
   class="btn btn-sm rounded-pill px-3"
   style="background:#f8f9fa; border:1px solid #dee2e6; color:#198754;">
    <i class="fa-solid fa-wallet me-1"></i> Payment Export
</a>
                                <div class="table-responsive">
                                    <table class="table all-package theme-table" id="vendorTable">

                                        <thead class="b-shadow">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Location</th>
                                                <th>Pincode</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($vendors as $vendor)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $vendor->name }}</td>
                                                    <td>{{ $vendor->mobile }}</td>
                                                    <td>{{ $vendor->email }}</td>
                                                    <td>{{ $vendor->location }}</td>
                                                    <td>{{ $vendor->pincode }}</td>
                                                    <td>
                                                        @if(optional($vendor->paymentTerm)->verified_status === 'verified')
                                                            <span class="badge bg-success">Active</span>
                                                        @elseif(optional($vendor->paymentTerm)->verified_status === 'unverified')
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @else
                                                            <span class="badge bg-secondary">Not Set</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        
                                                        <a href="{{ route('vendors.payment_term', $vendor->id) }}"
                                                           class="btn btn-sm btn-primary">
                                                            Payment Terms
                                                        </a>
                                                        
                                                        <a href="{{ route('vendors.edit', $vendor->id) }}"
                                                           class="btn btn-sm btn-warning ms-1">
                                                            Edit
                                                        </a>

                                                        <button type="button"
                                                                class="btn btn-sm btn-danger ms-1"
                                                                onclick="confirmDelete('{{ $vendor->id }}')">
                                                            Delete
                                                        </button>

                                                        <form id="delete-form-{{ $vendor->id }}"
                                                              action="{{ route('vendors.destroy', $vendor->id) }}"
                                                              method="POST"
                                                              style="display:none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">
                                                        No vendors found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                    </table>
                                </div>

                                

                            </div> {{-- card-body --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This vendor will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>

@endsection
