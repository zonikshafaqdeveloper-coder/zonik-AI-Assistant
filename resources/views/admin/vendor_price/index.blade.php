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

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                 <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="card-title mb-0">Vendor Price List</h3>

                                <a href="{{ route('vendor.price.create') }}" class="btn btn-primary">
                                    + Create Vendor Price
                                </a>
                            </div>

                           
                            <div class="card mb-4">
                                <div class="card-body py-3">

                                  <div class="d-flex flex-wrap gap-2 align-items-center">

                                        {{-- Import --}}
                                        <form action="{{ route('vendor.price.bulk.import') }}"
                                            method="POST"
                                            enctype="multipart/form-data"
                                            class="d-flex gap-2">
                                            @csrf
                                            <input type="file" name="file" class="form-control form-control-sm" required>
                                            <button class="btn btn-success btn-sm">
                                                Import
                                            </button>
                                        </form>

                                        {{-- Export --}}
                                        <a href="{{ route('vendor.price.bulk.export') }}"
                                        class="btn btn-dark btn-sm">
                                            Export
                                        </a>

                                        {{-- Sample --}}
                                        <a href="{{ route('vendor.price.sample') }}"
                                        class="btn btn-info btn-sm">
                                            Download Sample Import
                                        </a>

                                    </div>

                                </div>
                            </div>

                                <div class="table-responsive">
                                    <table class="table all-package theme-table" id="vendorTable">
                                        <thead class="b-shadow">
                                            <tr>
                                                <th>#</th>
                                                <th>Vendor Name</th>
                                                <th>Location</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($vendors as $row)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $row->vendor->name ?? 'N/A' }}</td>
                                                    <td>{{ $row->vendor->location ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('vendor.price.edit', $row->vendor_id) }}"
                                                           class="btn btn-sm btn-warning">
                                                            Edit
                                                        </a>

                                                      <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="confirmVendorPriceDelete({{ $row->vendor_id }})">
                                                        Delete
                                                    </button>

                                                    <form id="delete-form-{{ $row->vendor_id }}"
                                                        action="{{ route('vendor.price.destroy', $row->vendor_id) }}"
                                                        method="POST"
                                                        style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                    <a href="{{ route('vendor.price.export', $row->vendor_id) }}"
                                                    class="btn btn-sm btn-success">
                                                        Export
                                                    </a>


                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">
                                                        No vendor price list found
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

<script>
function confirmVendorPriceDelete(vendorId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'All prices for this vendor will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + vendorId).submit();
        }
    });
}
</script>

@endsection
