
@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">

    <body>

        <!-- partial -->
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
                                        <h3 class="card-title">Zone Manage</h3>
                                        <a href="{{ route('zoneprocessings.create') }}" type="button"
                                            class="btn btn-primary">Add Zone</a>
                                    </div>


                                    <p class="card-description">
                                        <!-- Add class <code>.table-striped</code> -->
                                    </p>

                                    {{-- <div class="table-responsive">
                                        <table class="table table-striped">
                                            <table class="table all-package theme-table" id="pincode_list">
                                                <thead class="b-shadow">
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Location</th>
                                                        <th>Pincode</th>
                                                        <th>Status</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($pincode as $key => $pin)
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ $pin->location }}</td>
                                                        <td>{{ $pin->pincode }}</td>
                                                        <td>@if ($pin->status == 'Active')
                                                            <form method="POST"
                                                                action="{{ route('pincode.statusUpdate', $pin->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="Inactive">
                                                                <button type="submit"
                                                                    class="btn btn-secondary">Active</button>
                                                            </form>
                                                            @else
                                                            <form method="POST"
                                                                action="{{ route('pincode.statusUpdate', $pin->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="Active">
                                                                <button type="submit"
                                                                    class="btn btn-danger text-white">Inactive</button>
                                                            </form>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('pincode.edit', $pin->id) }}"
                                                                class="align-items-center btn btn-success category-btn">
                                                                <i class="ri-pencil-line"></i>Edit
                                                            </a>
                                                            <form method="POST"
                                                                action="{{ url('delete-pincode/' . $pin->id) }}">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit"
                                                                    class="align-items-center btn btn-danger d-flex">Delete</button>
                                                            </form>
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                    </div> --}}

                                    <div class="table-responsive">
                                        <table class="table table-striped"  id="customer">
                                            <thead class="b-shadow">
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Zone Area</th>
                                                    <th>Processing Time</th>
                                                    <th>Shipping Time</th>
                                                    <th>Delivery Time</th>
                                                     <th>Next Day Time</th>
                                                    <th>Same Day Limit</th>
                                                    <th>Min Order</th>
                                                    <th>Order Below</th>
                                                    <th>Cash On Delivery</th>
                                                    <th>Bulk Delivery Charges</th>
                                                    <th>Single Delivery Charges</th>
                                                    <th>Packing Charge</th>
                                                    <th>Others Charges</th>
                                                    <th>Status</th>
                                                    <th>View</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($zoneProcessings as $key => $zoneProcessing)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $zoneProcessing->zone_name }}</td>
                                                    <td>{{ $zoneProcessing->processing_time }}-days</td>
                                                    <td>{{ $zoneProcessing->shipping_time }}-days</td>
                                                    <td>{{ $zoneProcessing->delivery_time }}-days</td>
                                                    <td>{{ $zoneProcessing->same_day_timing }}</td>
                                                    <td>{{ $zoneProcessing->next_day_timing }}</td>
                                                    <td>{{ $zoneProcessing->min_order }}</td>
                                                    <td>{{ $zoneProcessing->order_above }}</td>
                                                    <td>{{ $zoneProcessing->pay_on_delivery }}</td>
                                                    <td>{{ $zoneProcessing->bulk_delivery_charges }}</td>
                                                    <td>{{ $zoneProcessing->single_delivery_charges }}</td>
                                                    <td>{{ $zoneProcessing->packing_charge }}</td>
                                                    <td>{{ $zoneProcessing->others_charges }}</td>
                                                    <td>
                                                        @if ( $zoneProcessing->status == 'Active')
                                                            <form method="POST" action="{{ route('zoneprocessing.statusUpdate',  $zoneProcessing->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="Inactive">
                                                                <button type="submit" class="btn btn-secondary">Active</button>
                                                            </form>
                                                        @else
                                                            <form method="POST" action="{{ route('zoneprocessing.statusUpdate',  $zoneProcessing->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="Active">
                                                                <button type="submit" class="btn btn-danger text-white">Inactive</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                    <td><a href="{{ route('pincode.index', ['id' => $zoneProcessing->id]) }}" class="btn btn-primary">View</a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('zoneprocessing.edit', $zoneProcessing->id) }}" class="align-items-center btn btn-success category-btn">
                                                            <i class="ri-pencil-line"></i>Edit
                                                        </a>
                                                        <form method="POST" action="{{ url('delete-zoneprocessing/' . $zoneProcessing->id) }}">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="align-items-center btn btn-danger d-flex text-white">Delete</button>
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
            </div>
            @endsection
