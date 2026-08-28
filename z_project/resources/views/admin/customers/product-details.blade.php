@extends('admin.layouts.app')
@section('content')
    <!-- Container-fluid starts-->
    <div class="page-body">
        <!-- All User Table Start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">

                            {{-- @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif --}}

                            <div class="title-header option-title d-flex justify-content-between">
                                <h5>All Enquiries</h5>
                                <h5>

                                    @if ($enquiriesDataPanding->count() != 0)
                                        <a href="{{ route('status.update', ['status' => 'pending', 'id' => $enquiriesDataPanding[0]->user_id]) }}"
                                            class="align-items-center btn btn-success d-flex">
                                            {{ $enquiriesDataPanding->count() }}
                                            Enquiries Data Pending
                                        </a>
                                </h5>
                                @endif
                            </div>
                            {{-- {{$enquirie}} --}}
                            Customer Name :{{ $enquirie->user->name }}<br>
                            Customer No : {{ $enquirie->user->mobile_number }} <br>
                            Enquery No :{{ $enquirie->enquiry_no }} <br>
                            Enquery Date :{{ $enquirie->created_at->format('d/m/Y') }} <br>
                            Outlet Name:{{ $enquirie->user->mobile_number }} <br>



                            <div class="table-responsive category-table">
                                <div>
                                    <form action="{{ route('admin.enquiry.offerPrice.store') }}" method="POST">
                                        @csrf
                                        <table class="table all-package theme-table" id="table_id">
                                            <thead>
                                                <tr class="text-capitalize">
                                                    <th>Sr.</th>
                                                    <th>Product Name</th>
                                                    <th>Unit</th>
                                                    <th>Brand</th>
                                                    <th>Order Qty (Pattern)</th>
                                                    <th>Monthly Consumption</th>
                                                    <th>MRP</th>
                                                    <th>Cost Par item (Basic)</th>
                                                    <th>Total GST (%)</th>
                                                    <th>carton sale price (Basic)</th>
                                                    <th>Profit Margin</th>
                                                    <th>Last updated Price</th>
                                                    <th>Supplier Traced</th>
                                                    <th>Rejected</th>
                                                    <th>Rejected Customer Comment</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @foreach ($enquiriesData as $key => $enquiry)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $enquiry->product?->product_name }}</td>
                                                        <td>{{ $enquiry->product->unit }}</td>
                                                        <td>{{ $enquiry->product->brands }}</td>
                                                        <td>Carton ({{ $enquiry->product_types == '1' ? 'Box' : 'Loose' }})
                                                            {{ $enquiry->quantity }} pcs</td>
                                                        <td>{{ $enquiry->monthlyconsumption }}</td>
                                                        <td>{{ $enquiry->mrp }}</td>
                                                        <td>{{ $enquiry->product->cost_per_item }}</td>


                                                        <td>{{ $enquiry->product->gst }}%</td>
                                                        <td>
                                                            <input type="number" class="offer-input" onkeyup="EnquiryOfferPrice(this.value, {{ $enquiry->product->cost_per_item }}, 'result{{ $key + 1 }}')" name="offer_price{{ $key + 1 }}" data-cost-per-item="{{ $enquiry->product->cost_per_item }}" name="offer_price{{ $key + 1 }}" value="{{ $enquiry->offer_price }}">
                                                            
                                                            <input type="hidden" name="id{{ $key + 1 }}"
                                                                value="{{ $enquiry->id }}">
                                                        </td>
                                                        {{-- @php

                                                            if ($enquiry->offer_price) {
                                                                $costPerItem = $enquiry->product->cost_per_item;
                                                                $offerPrice = $enquiry->offer_price;

                                                                $profitMargin = ($offerPrice - $costPerItem) / $costPerItem;
                                                                $profitMarginPercentage = round($profitMargin * 100, 2) . '%';
                                                            } else {
                                                                $profitMarginPercentage = '';
                                                            }

                                                        @endphp --}}
                                                        <td>
                                                            <span class="result" id="result{{ $key + 1 }}"></span>
                                                        </td>
                                                        <td>{{ $enquiry->updated_at->format('d/m/Y') }}</td>
                                                        <td>{{ $enquiry->product->supplier_traced }}</td>
                                                        <td>{{ $enquiry->rejected }}</td>
                                                        <td>{{ $enquiry->rejected_customer_comment }}</td>
                                                        <td
                                                            style="color: @if ($enquiry->status == 'pending') red @elseif($enquiry->status == 'submitted') green @elseif($enquiry->status == 'accept') blue @elseif($enquiry->status == 'rejected') orange @endif">
                                                            {{ ucfirst($enquiry->status) }}
                                                        </td>


                                                        <td>
                                                            <ul>

                                                                <li>
                                                                    <a href="{{ route('enquiry.edit', $enquiry->id) }}"
                                                                        class="align-items-center btn btn-success d-flex">edit</a>
                                                                </li>

                                                                <li>
                                                                    <form method="POST"
                                                                        action="{{ url('delete-enquiry/' . $enquiry->id) }}">
                                                                        @csrf
                                                                        <button type='submit'
                                                                            class="align-items-center btn btn-theme d-flex">delete

                                                                        </button>
                                                                    </form>


                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        <button type="submit" class="btn btn-primary bg-primary">Reoffer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal location-modal fade theme-modal" id="locationModal" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-full screen-sm-down vh-100">
                <div class="modal-content" id="mobileBox">
                    <div class="modal-header">
                        <h5 class="modal-title indexh5 mb-2" id="exampleModalLabel">Edit
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <!-- <i class="fa-solid fa-xmark"></i> -->
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="location-list">
                            <p class="mt-1 mb-4 text-content"></p>
                            <div class="search-input">
                                <input type="number" name="offer_price" id="offer_price" class="form-control mb-4">
                                <input type="hidden" name="enquiry_id" id="enquiry_id" class="form-control mb-4"
                                    placeholder="Enter Your Name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success waves-effect"
                                onclick="offerPriceUpdate()">Update</button>
                            <button type="button" class="btn btn-info waves-effect" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('js')
        <script type="text/javascript">
         window.onload = function() {
            // Retrieve and process values for each row on page load
            const inputs = document.querySelectorAll('.offer-input');
            inputs.forEach((input, index) => {
                const value = input.value;
                const costPerItem = input.dataset.costPerItem;
                const resultId = 'result' + (index + 1);
                EnquiryOfferPrice(value, costPerItem, resultId);
            });
        };

        function EnquiryOfferPrice(value, costPerItem, resultId) {
            const profitMargin = (value - costPerItem) / costPerItem;
            const profitMarginPercentage = (profitMargin * 100).toFixed(2) + '%';
            document.getElementById(resultId).innerHTML = profitMarginPercentage;
            console.log(profitMarginPercentage);
        }


            function offerPrice(id, offer_price) {
                $('#enquiry_id').val(id);
                $('#offer_price').val(offer_price);
            }

            function offerPriceUpdate() {
                var id = $('#enquiry_id').val();
                var offer_price = $('#offer_price').val();
                var token = '{{ csrf_token() }}';

                $.ajax({
                        url: '{{ route('admin.enquiry.offerPrice.store') }}',
                        type: 'post',
                        data: {
                            _token: token,
                            id: id,
                            offer_price: offer_price
                        },
                    })
                    .done(function(data) {
                        console.log(data);
                        $('#printoffer' + id).html(offer_price);
                        $('#locationModal').modal('hide');
                        toastr.success('Offer Price Update', 'Success');
                        location.reload()
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                    });
            }
        </script>
    @endsection
    <!-- All User Table Ends-->
