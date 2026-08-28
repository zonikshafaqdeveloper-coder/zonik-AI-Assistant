@extends('admin.layouts.appnew')
@section('content')
<style>
    td{
        text-transform: capitalize;

    }
    td:nth-child(9){
        white-space: nowrap;
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
                                        <h3 class="card-title">Invoice List</h3>
                                    </div>
                                    <p class="card-description">
                                        <!-- Add class <code>.table-striped</code> -->
                                    </p>
                                    <div class="table-responsive">
                                        <table id="category" class="table table-bordered">
                                            <thead class="b-shadow">
                                                <tr>
                                                    <th class="text-center">Invoice Number</th>
                                                    <th class="text-center">Order Number</th>
                                                    <th class="text-center">Customer Name</th>
                                                     <th class="text-center">Company Name</th>
                                                    <th class="text-center">Outlet Name</th>
                                                    <th class="text-center">Payment Status</th>
                                                    <th class="text-center">Delivery Status</th>
                                                    <th class="text-center">Invoice Date</th>
                                                    <th class="text-center">Order Date</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($orders as $key => $order)
                                                @if ($order->invoice_date !== Null)
                                                        <tr>
                                                            <td class="text-center"><a class="text-dark font-weight-bold" href="{{ route('generateInvoiceAndDeliveryCharges.list',['id' => $order->id]) }}" onclick="window.open(this.href,'_blank','width=800,height=600'); return false;">{{ $order->invoice_id }}</a></td>

                                                            <td class="text-center">{{ $order->order_id }}</td>
                                                            <td class="text-center">

                                                                    {{ $order->user ? $order->user->name : 'Unknown User' }}
                                                                
                                                            </td>
                                                              <td class="text-center">

                                                                    {{ $order->user ? $order->mainuser->outlet_name : 'Unknown User' }}
                                                                
                                                            </td>

                                                            <td class="text-center">{{ $order->user->outlet_name ?? 'N/A' }}</td>
                                                            <td class="text-center">{{ $order->payment_status ?? 'N/A' }}</td>
                                                            <td class="text-center">
                                                            @if($order->deliveries->isNotEmpty())
                                                            {{ $order->deliveries->first()->delivery_status }}
                                                            @else
                                                             N/A
                                                             @endif
                                                            </td>
                                                            <td class="text-center">{{ $order->invoice_date }}</td>
                                                            <td class="text-center">{{ $order->created_at }}</td>
                                                        </tr>
                                                @endif
                                            @endforeach

                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        </html>
                        @endsection
