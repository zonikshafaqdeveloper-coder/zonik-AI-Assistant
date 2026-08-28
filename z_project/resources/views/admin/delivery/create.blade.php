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
                                <h4 class="card-title">Create New Delivery</h4>
                                <p class="card-description">
                                    <!-- Add Category -->
                                </p>
                                <form method="POST" action="{{ route('delivery.store') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="order_id">Order ID</label>
                                                <select class="form-control" id="order_id" name="order_id">
                                                    <option value="">Select Order Id</option>
                                                    @foreach ($orders as $order)
                                                        <option value="{{ $order->id }}">{{ $order->order_id }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="delivery_date">Delivery Date</label>
                                                <input type="text" class="form-control" id="delivery_date" name="delivery_date" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="delivery_address">Delivery Address</label>
                                                <input type="text" class="form-control" id="delivery_address" name="delivery_address" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="delivery_status">Delivery Status</label>
                                                <select class="form-control" id="delivery_status" name="delivery_status">
                                                    <option value="pending">Pending</option>
                                                    <option value="in_progress">In Progress</option>
                                                    <option value="delivered">Delivered</option>
                                                    <option value="cancelled">Cancelled</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <button type="submit" class="btn btn-primary">Create Delivery</button>
                                </form>


                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('order_id').addEventListener('change', function () {
                    var orderId = this.value;
                    fetch(`/orders-data/${orderId}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('delivery_date').value = data.delivery_date;
                            document.getElementById('delivery_address').value = data.shipping_address;
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        </script>

        @endsection
