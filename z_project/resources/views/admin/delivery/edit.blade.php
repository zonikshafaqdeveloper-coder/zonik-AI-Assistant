@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper ">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Edit Order Datails</h4>
                                <p class="card-description">
                                </p>
                                <form class="forms-sample" action="{{ route('delivery.update-pay', $orders->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="order_id">Order ID</label>
                                                <input type="text" class="form-control" id="order_id" value="{{ $orders->id }}" readonly>
                                                <input type="hidden" class="form-control" name="paid_amount" value="{{ $orders->total_discount_value }}" readonly>
                                                <input type="hidden" class="form-control" name="user_id" value="{{ $orders->user_id }}" readonly>
                                                <input type="hidden" class="form-control" name="outlet_id" value="{{ $orders->outlet_id }}" readonly>
                                                <input type="hidden" class="form-control" name  ="order_id" value="{{ $orders->id }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payment_method">Payment Method</label>
                                                <input type="text" class="form-control" id="payment_method" name="payment_method" value="{{ $orders->payment_method }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payment_status">Payment Status</label>
                                                <select class="form-control form-select" id="payment_status" name="payment_status">
                                                    <option value="paid" @if($orders->payment_status == 'paid') selected @endif>Paid</option>
                                                    <option value="unpaid" @if($orders->payment_status == 'unpaid') selected @endif>Unpaid</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payment_mode">Payment Mode</label>
                                                <select class="form-control form-select" id="payment_mode" name="payment_mode">
                                                    <option value="debit">Debit Card</option>
                                                    <option value="credit">Credit Card</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="upi">UPI</option>
                                                    <option value="cheque">Cheque</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payment_id">Payment ID</label>
                                                <input type="text" class="form-control" name="payment_id">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" name="submit" class="btn btn-primary me-2">Submit</button>
                                    <a href="{{ route('order.details') }}" class="btn btn-light">Cancel</a>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        @endsection
