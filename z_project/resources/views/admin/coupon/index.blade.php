@extends('admin.layouts.appnew')
@section('content')

<!-- Container-fluid starts-->
   <div class="page-body">
                <!-- All User Table Start -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-table">
                                <div class="card-body">

                                @if(session('success'))
                                <div class="alert alert-success">
                                {{ session('success') }}
                                </div>
                                @endif

                                    <div class="title-header option-title d-flex justify-content-between">
                                        <h5>All coupons</h5>
                                        <form class="d-inline-flex">
                                            <a href="{{ route('coupons.create') }}"
                                                class="align-items-center btn btn-success d-flex">
                                                <i data-feather="plus-square"></i>Add coupon
                                            </a>
                                        </form>
                                    </div>

                                    <div class="table-responsive category-table">
                                        <div>
                                            <table class="table all-package theme-table" id="category">
                                                <thead>
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th class="text-center">Coupon Name</th>
                                                        <th class="text-center">Coupon Code</th>
                                                        <th class="text-center">Coupon Expiry Date</th>
                                                        <th class="text-center">Coupon Discount Amount</th>
                                                         <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                @foreach($coupons as $coupon)
                                                    <tr>
                                                        <td class="text-center">{{ $coupon->id}}</td>
                                                        <td class="text-center">{{ $coupon->coupon_name}}</td>
                                                        <td class="text-center">{{ $coupon->coupon_code}}</td>
                                                        <td class="text-center">{{ $coupon->end_date}}</td>
                                                        <td class="text-center">{{ $coupon->discount_amount}}</td>

                                                         <td class="d-flex">
                                                                <a href="{{ route('coupons.edit', $coupon->id) }}"  class=" mx-2 align-items-center btn btn-success" >edit</a>

                                                                <form method="POST" action="{{ url('delete-coupons/'.$coupon->id) }}">
                                                                    @csrf
                                                                    @method('delete')
                                                                   <button type='submit' class="align-items-center btn btn-danger text-white">delete </button>
                                                                </form>

                                                                </li>
                                                            </ul>
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

                <!-- All User Table Ends-->
