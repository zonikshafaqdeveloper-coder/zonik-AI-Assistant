@extends('admin.layouts.appnew')
@section('content')
<style>
    span.text-danger, .text-danger span{
        color: #dc3545 !important;
    }
</style>
<div class="page-body">
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper ">
                    <div class="row">

                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">Outstanding List</h3>
                                    </div>
<div class="table-responsive">
    <table class="table">
        <thead class="b-shadow">
            <tr>
                <th>#</th>
                <th class="text-center">Vendor Name</th>
                <th class="text-center">Location</th>
                <th class="text-center">Contact</th>
                <th class="text-center">Total Due Amount</th>
                <th class="text-center">No. of Bills</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($vendorOutstandingList as $key => $vendor)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>

                    <td class="text-center">{{ $vendor->vendor_name }}</td>

                    <td class="text-center">{{ $vendor->location ?? 'N/A' }}</td>

                    <td class="text-center">{{ $vendor->mobile ?? 'N/A' }}</td>

                    <td class="text-center text-danger font-weight-bold">
                        ₹{{ number_format($vendor->total_due_amount, 2) }}
                    </td>

                    <td class="text-center">{{ $vendor->total_bills }}</td>

                    <td class="text-center">
                        <a href="{{ route('vendor.outstanding.pdf', $vendor->vendor_id) }}"
                           class="btn btn-info text-white">
                            View Outstanding
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
</div>
</div>
</div>
 @endsection
