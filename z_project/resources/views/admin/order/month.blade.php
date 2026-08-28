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

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="card-title mb-0">This Month's Sales Orders</h3>

                                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                        Back
                                    </a>
                                </div>

                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="table-responsive">

                                    <table class="table table-bordered table-striped">

                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Invoice No.</th>
                                                <th>Order No.</th>
                                                <th>Customer</th>
                                                <th>Outlet</th>
                                                <th>Delivery Date</th>
                                                <th>Grand Value</th>
                                                <th>Payment</th>
                                                <th>Order Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                        @forelse($orders as $order)

                                            @php
                                                $outlet = $users->firstWhere('id',$order->outlet_id);
                                            @endphp

                                            <tr>

                                                <td>{{ $loop->iteration }}</td>

                                                <td>{{ $order->invoice_id }}</td>

                                                <td>{{ $order->order_id }}</td>

                                                <td>
                                                    {{ $outlet->name ?? '-' }}
                                                </td>

                                                <td>
                                                    {{ $outlet->outlet_name ?? '-' }}
                                                </td>

                                                <td>
                                                    {{ $order->delivery_date }}
                                                </td>

                                                <td>
                                                    ₹ {{ number_format($order->total_discount_value,2) }}
                                                </td>

                                                <td>
                                                    {{ $order->payment_status }}
                                                </td>

                                                <td>
                                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y h:i A') }}
                                                </td>

                                                <td>

                                                    @if(isset($order->deliveryStatuses))

                                                        @foreach($order->deliveryStatuses as $status)

                                                            <span class="badge bg-success">
                                                                {{ ucfirst($status['status']) }}
                                                            </span>

                                                        @endforeach

                                                    @else

                                                        <span class="badge bg-success">
                                                            Delivered
                                                        </span>

                                                    @endif

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    No Today's Sales Found
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
</div>

@endsection