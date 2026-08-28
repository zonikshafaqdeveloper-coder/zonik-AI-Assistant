@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">

    <!-- New Product Add Start -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-8 m-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header-2">
                                    <h5>Add Coupon</h5>
                                </div>

                                <form class="theme-form theme-form-2 mega-form" action="{{ route('coupons.store') }}" method="POST">
                                    {{ csrf_field() }}

                                    @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif

                                    <div class="mb-4 row align-items-center">
                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Coupon Name</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text" name="coupon_name" placeholder="Coupon Name">
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0"> Coupon above Price   </label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="number" name="max_price" placeholder="Price Above Discount">
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Discount Amount</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="number" name="discount_amount" placeholder="Discount Amount">
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Start Date</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="date" name="start_date">
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">End Date</label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="date" name="end_date">
                                            </div>
                                        </div>



                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Description</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="description" placeholder="Description"></textarea>
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Is Active</label>
                                            <div class="col-sm-9">
                                                <select class="form-select" name="is_active">
                                                    <option value="Active">Active</option>
                                                    <option value="inActive">Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <input type="hidden" name="coupon_code" value="{{ substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10) }}">

                                        <button type="submit" name="submit" class="btn btn-primary ms-auto mt-4">Save</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- New Product Add End -->
    @endsection
