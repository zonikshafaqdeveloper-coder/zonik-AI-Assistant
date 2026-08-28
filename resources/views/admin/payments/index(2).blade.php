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
                                        <h3 class="card-title">Payment History</h3>
                                    </div>
                                   <div class="table-responsive">
    <table class="table table-bordered" id="payments-table">
        <thead class="b-shadow">
        <tr>
            <th>ID</th>
            <th class="text-center">User ID</th>
            <th class="text-center">Outlet Name</th>
            <th class="text-center">Outlet Person</th>
            <th class="text-center">Order ID</th>
            <th class="text-center">Payment ID</th>
            <th class="text-center">Paid Amount</th>
            <th class="text-center">Payment Status</th>
            <th class="text-center">Payment Mode</th>
            <th class="text-center">Date of Payment</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($payments as $payment)
            <tr>
                <td class="text-center">{{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}</td>

                <td class="text-center">{{ $payment->user->outlet_name ?? '' }}</td>

                <td class="text-center">{{ $payment->outlet->outlet_name ?? '' }}</td>

                <td class="text-center">
                    <a class="text-dark font-weight-bold" href="{{ route('order.detailsid', ['id' => $payment->outlet_id]) }}">
                        {{ $payment->outlet->name ?? '' }}
                    </a>
                </td>

                <td class="text-center">{{ $payment->order_id }}</td>
                <td class="text-center">{{ $payment->payment_id }}</td>

                <td class="text-center">
                    <span class="font-weight-bold">₹ {{ $payment->order->total_discount_value ?? '' }}</span>
                </td>

                <td class="text-center">{{ $payment->order->payment_status ?? 'Not Available' }}</td>
                <td class="text-center">{{ $payment->payment_mode }}</td>
                <td class="text-center">{{ $payment->updated_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
   <div class="d-flex justify-content-center mt-3">
        {{ $payments->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
                                </div>
                            </div>
                        </div>
                        </html>
                        @endsection
