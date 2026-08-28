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
                                        <table class="table" id="category"  >
                                            <thead class="b-shadow">
                                                <tr>
                                                    <th>#</th>
                                                    <th class="text-center">Outlet Person</th>
                                                    <th class="text-center">Outlet Name</th>
                                                    <th class="text-center">Contact Number</th>
                                                    <th class="text-center">Total Due Amount</th>
                                                    <th class="text-center">No. of Orders</th>

                                                    <th>View Outstanding Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($outstandingList as $key => $outstanding)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td class="text-center">{{ $outstanding->user_name }}</td>
                                                        <td class="text-center">{{ $outstanding->outlet_name }}</td>
                                                        <td class="text-center">{{ $outstanding->mobile_number }}</td>
                                                        <td class="text-danger font-weight-bold text-center"> <span>₹{{ $outstanding->total_due_amount }}</span></td>
                                                        <td class="text-center">{{ $outstanding->num_statements }}</td>
                                                        <td class="text-center">
                                                            <a href="/order/invoice/{{ $outstanding->outlet_id }}" class="btn btn-info text-white my-2"> View Outstanding</a>
                                                            <a href="/over_due/invoice/{{ $outstanding->outlet_id }}" class="btn btn-danger text-white my-2"> Over Due</a>
                                                            <a href="/order/detailss/{{ $outstanding->outlet_id }}" class="btn btn-secondary my-2">View Orders</a>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        </html>
                        @endsection
