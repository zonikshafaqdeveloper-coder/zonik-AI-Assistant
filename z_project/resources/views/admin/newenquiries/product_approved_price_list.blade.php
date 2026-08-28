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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                      <tr>
                                                        <th>Customer Name</th>
                                                        <td>{{ $enquiriesData->first()->user->name }}</td>
                                                      </tr>
                                                      <tr>
                                                        <th>Company Name</th>
                                                        <td>{{ $enquiriesData->first()->user->outlet_name }}</td>

                                                      </tr>
                                                    </table>
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
                                                            <th>Sr.</th>
                                                            <th>Product Name</th>
                                                            <th>Brand</th>
                                                            <th>Unit</th>
                                                            <th>ORDER PATTERN</th>
                                                            <th>APPROVED PRICE (BASIC)</th>
                                                            <th>MRP</th>
                                                            <th>Purchase Price (Basic)</th>
                                                            <th>Profit Margin</th>
                                                            <th>Last updated Price</th>
                                                            <th>Supplier Traced </th>
                                                            <th>Action</th>
                                                        </tr>


                                                    </thead>
                                                    <tbody>
                                                        @foreach ($enquiriesData as $key => $enquiry)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $enquiry->product?->product_name }}</td>

                                                                <td>{{ $enquiry->product->brands }}</td>

                                                                <td>{{ $enquiry->product->unit }}</td>

                                                                <td>

                                                                    @if ($enquiry->product_types == 1)
                                                                        <span style="color:red">
                                                                            Carton Box : 24 Nos.
                                                                        </span>
                                                                    @elseif ($enquiry->product_types == 2)
                                                                        <span style="color: red">
                                                                            Loose (pcs.)
                                                                        </span>
                                                                    @else
                                                                        <span style="color: blue">
                                                                            Loose/Box Not</span>
                                                                    @endif

                                                                </td>
                                                                <td>{{ $enquiry->offer_price }}</td>

                                                        <td>
                                                            {{ $enquiry->mrp }}
                                                        </td> <td>

                                                            <h6 class="color-grey">
                                                                @if ($enquiry->product->cost_per_item)
                                                                    ₹
                                                                    {{ $enquiry->product->cost_per_item }}
                                                            </h6>
                                                @endif
                                                </td>
                                                         
                                                          <td>
                                                        @php
                                                            $cost = $enquiry->product->cost_per_item ?? 0;
                                                            $offer = $enquiry->offer_price ?? 0;

                                                            if ($cost > 0 && $offer > 0) {
                                                                $margin = (($offer - $cost) / $cost) * 100;
                                                                $profitMarginPercentage = round($margin, 2) . '%';
                                                            } else {
                                                                $profitMarginPercentage = '-';
                                                            }
                                                        @endphp

                                                        <span class="{{ $margin ?? 0 > 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ $profitMarginPercentage }}
                                                        </span>
                                                    </td>

                                                        <td>{{ $enquiry->updated_at->format('d/m/Y') }}</td>
                                                        <td>{{ $enquiry->product->supplier_traced }}</td>
                                                        <td style="display:flex; gap: 20px; margin-top :25px;">

                                                            <a href="{{ route('enquiryy.edit', $enquiry->id) }}"
                                                                class="align-items-center btn btn-success d-flex">Edit</a>
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
                </div>
            </div>
        @endsection

        <!-- All User Table Ends-->
