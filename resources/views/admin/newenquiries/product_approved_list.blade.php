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
                                        <h5>APPROVED PRICE LIST</h5>

 <div class="row display:flex align-items-center enquire-box w-500">
                                                <div class="col">
                                                    <div class="">
                                                                <div class="col-md-6">
                                                                    <a href="{{ route('aprroved_list.export') }}"
                                                                        class="btn btn-sm btn-success m-19">
                                                                        <i class="fas fa-check"></i> Export To Excel
                                                                    </a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        </div>
                                        <br>
                                        {{--  <div class="margin-left">
                                            Customer Name : {{ $user->name }}<br>
                                            Company Name : {{ $user->company_name }} <br>
                                            Outlet Name: {{ $user->outlet_name }} <br>
                                        </div>  --}}

                                        <div class="table-responsive category-table">
                                            <div>

                                                <table class="table table-bordered all-package theme-table" id="enquiries">
                                                    <thead class="b-shadow">

                                                        <tr class="text-capitalize w-full">
                                                            <th class="text-center">Sr.</th>
                                                            <th class="text-center">Company Name</th>
                                                            <th class="text-center">Customer Name</th>
                                                            <th class="text-center">Contact Number</th>
                                                            <th class="text-center">Location</th>
                                                            <th class="text-center">No Of Item</th>
                                                        </tr>


                                                    </thead>
                                                    <tbody>
                                                        @foreach ($enquiriesData as $key => $enquiry)
                                                            <tr>
                                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                                <td class="text-center">{{ $enquiry->user?->outlet_name }}</td>
                                                                <td class="text-center"><a href="{{ route('approved.customer', ['user_id' => $enquiry->user_id]) }}">{{ $enquiry->user?->name }}</a></td>
                                                                <td class="text-center">{{ $enquiry->user?->mobile_number }}</td>
                                                                <td class="text-center">{{ $enquiry->user?->location }}</td>
                                                                <td class="text-center">{{ $enquiry->enquiriescount }}</td>
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
                </div>
            </div>
        @endsection

        <!-- All User Table Ends-->
