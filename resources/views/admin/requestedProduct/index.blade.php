@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">

    <body>
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

                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">Requested Product</h3>
                                    </div>




                                    <p class="card-description">
                                        <!-- Add class <code>.table-striped</code> -->
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <table class="table all-package theme-table" id="pincode_list">
                                                <thead class="b-shadow">
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th class="text-center">User Name</th>
                                                        <th class="text-center">Product Name</th>
                                                        <th class="text-center">Product Details</th>
                                                        <th class="text-center">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ( $requestedProducts as $key => $product)
                                                    <tr>
                                                        <td class="text-center">{{ ++$key }}</td>
                                                        <td class="text-center">{{  $product->user?->name }} - {{  $product->user?->outlet_name }}</td>
                                                        <td class="text-center">{{  $product->product_name }}</td>
                                                        <td class="text-center">{{  $product->product_details }}</td>
                                                        <td>
                                                            @if ( $product->status == 'accepted')
                                                                <form method="POST" action="{{ route('requestedProducts.statusUpdate',  $product->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="decline">
                                                                    <button type="submit" class="btn btn-secondary">Accepted</button>
                                                                </form>
                                                            @else
                                                                <form method="POST" action="{{ route('requestedProducts.statusUpdate',  $product->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="status" value="accepted">
                                                                    <button type="submit" class="btn btn-danger text-white">Declined</button>
                                                                </form>
                                                            @endif
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
            @endsection
