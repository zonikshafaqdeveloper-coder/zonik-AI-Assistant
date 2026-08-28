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

                                {{-- SUCCESS MESSAGE --}}
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                {{-- HEADER --}}
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="card-title mb-0">Customer Price List</h3>

                                    <a href="{{ route('admin.customer.price.create') }}"
                                       class="btn btn-primary">
                                        + Create Customer Price
                                    </a>
                                </div>

                                {{-- 🔥 BULK IMPORT / EXPORT --}}
                                <div class="row mb-4">
                                    <div class="col-md-12">

                                        <form action="{{ route('customer.price.bulk.import') }}"
                                              method="POST"
                                              enctype="multipart/form-data"
                                              class="d-flex flex-wrap gap-2 align-items-center">

                                            @csrf

                                            <input type="file"
                                                   name="file"
                                                   class="form-control w-auto"
                                                   required>

                                            <button class="btn btn-success">
                                                Import Prices
                                            </button>

                                            <a href="{{ route('customer.price.bulk.export') }}"
                                               class="btn btn-dark">
                                                Export All Prices
                                            </a>

                                              <a href="{{ route('customer.price.sample') }}"
                                                class="btn btn-info">
                                                    Download Sample Import
                                                </a>

                                        </form>

                                    </div>
                                </div>

                                {{-- TABLE --}}
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="customer">

                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Outlet Name</th>
                                                <th>Company Name</th>
                                                <th>Location</th>
                                                <th width="250">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @if($outlets->count())

                                                @foreach($outlets as $index => $row)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>

                                                        <td>{{ $row->outlet->outlet_name ?? 'N/A' }}</td>

                                                        <td>{{ $row->customer->outlet_name ?? 'N/A' }}</td>

                                                        <td>{{ $row->outlet->location ?? 'N/A' }}</td>

                                                        <td class="d-flex gap-2 flex-wrap">

                                                            <a href="{{ route('customer.price.edit', $row->outlet_id) }}"
                                                               class="btn btn-sm btn-warning">
                                                                Edit
                                                            </a>

                                                            <button type="button"
                                                                    class="btn btn-sm btn-danger"
                                                                    onclick="confirmCustomerPriceDelete({{ $row->outlet_id }})">
                                                                Delete
                                                            </button>

                                                            <form id="delete-form-{{ $row->outlet_id }}"
                                                                  action="{{ route('customer.price.delete', $row->outlet_id) }}"
                                                                  method="POST"
                                                                  style="display:none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>

                                                            <a href="{{ route('customer.price.export', $row->outlet_id) }}"
                                                               class="btn btn-sm btn-success">
                                                                Export
                                                            </a>

                                                        </td>
                                                    </tr>
                                                @endforeach

                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        No outlet pricing found
                                                    </td>
                                                </tr>
                                            @endif
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

{{-- DELETE CONFIRM --}}
<script>
function confirmCustomerPriceDelete(customerId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'All prices for this customer will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + customerId).submit();
        }
    });
}
</script>

@endsection