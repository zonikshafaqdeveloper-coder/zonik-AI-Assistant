@extends('admin.layouts.appnew')
@section('content')


<style>
    .active-btn {
    background-color: #0d6efd !important;
    border-color: #0a58ca !important;
    font-weight: bold;
    box-shadow: 0 0 5px rgba(0,0,0,0.4);
}

</style>

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
                               @php
                                                $rejected = DB::table('enquiries')->where('status', '=', 'rejected')->get();
                                                $submitted = DB::table('enquiries')
                                                    ->whereIn('status', ['pending', 'rejected'])
                                                    ->where('offer_check', '=', '1')
                                                    ->get();
                                                $accept = DB::table('enquiries')->where('status', '=', 'accept')->get();
                                                $submitted1 = DB::table('enquiries')->where('status', '=', 'submitted')->get();
                                                $pending = DB::table('enquiries')
                                                    ->where('status', 'pending')
                                                    ->where('offer_check', '!=', '1')
                                                    ->get();
                                                $reoffer = DB::table('enquiries')->where('status', '=', 'reoffer')->get();

                                                $currentStatus = request()->status;
                                                $currentRoute = Route::currentRouteName();
                                            @endphp


                                                                                                <div>

                                                {{-- NEW ENQUIRY --}}
                                                <a href="{{ route('enquiry.indexx') }}">
                                                    <button type="button"
                                                        class="btn btn-primary {{ $currentRoute == 'enquiry.indexx' ? 'active-btn' : '' }}">
                                                        New Enquiry ({{ $pending->count() }})
                                                    </button>
                                                </a>

                                                {{-- SUBMITTED --}}
                                                <a href="{{ route('submitted.all') }}">
                                                    <button type="button"
                                                        class="btn btn-primary {{ $currentRoute == 'submitted.all' ? 'active-btn' : '' }}">
                                                        Submitted ({{ $submitted1->count() }})
                                                    </button>
                                                </a>

                                                {{-- OFFER & REOFFER --}}
                                                <a href="{{ route('submitted.view', ['status' => 'submitted']) }}">
                                                    <button type="button"
                                                        class="btn btn-primary {{ $currentStatus == 'submitted' ? 'active-btn' : '' }}">
                                                        OFFER & REOFFER ({{ $submitted->count() }})
                                                    </button>
                                                </a>

                                                {{-- ACCEPTED --}}
                                                <a href="{{ route('enquirystatus.view', ['status' => 'accept']) }}">
                                                    <button type="button"
                                                        class="btn btn-primary {{ $currentStatus == 'accept' ? 'active-btn' : '' }}">
                                                        ACCEPTED ({{ $accept->count() }})
                                                    </button>
                                                </a>

                                                {{-- REJECTED --}}
                                                <a href="{{ route('enquirystatus.view', ['status' => 'rejected']) }}">
                                                    <button type="button"
                                                        class="btn btn-primary {{ $currentStatus == 'rejected' ? 'active-btn' : '' }}">
                                                        REJECTED ({{ $rejected->count() }})
                                                    </button>
                                                </a>

                                            </div>
                            </div>

                            <div class="table-responsive category-table">
                                <div class="m-4">
                                    <form action="{{route('admin.enquiry.offerPrice.store')}}" method="POST">
                                        @csrf
                                        <table class="table all-package theme-table" id="enquiries_view">
                                            <thead class="b-shadow">
                                                <tr class="text-capitalize">
                                                    <th>Sr.</th>
                                                    <th>Customer Name</th>
                                                    <th>Contact No.</th>
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
                                                    <!--<th>Action</th>-->
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($enquiriesData as $key => $enquiry)
                                                    <tr class="table-f">
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            @if ($enquiry->user_id)
                                                                <a class="link-d"
                                                                    href="{{ route('customer.product.detailss', ['user' => $enquiry->user_id]) }}">
                                                                    {{ $enquiry->user?->name }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td>{{ $enquiry->user?->mobile_number }}</td>
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
                                                            <input type="number" name="offer_price{{ $key + 1 }}" value="{{ $enquiry->offer_price }}">
                                                            <input type="hidden" name="id{{ $key + 1 }}"  value="{{ $enquiry->id }}">
                                                        </td>
                                                        @php
                                                            if ($enquiry->offer_price) {
                                                                $profitMargin = ($enquiry->offer_price - $enquiry->product->cost_per_item) / $enquiry->product->cost_per_item;
                                                                $profitMarginPercentage = round($profitMargin * 100, 2) . '%';
                                                            } else {
                                                                $profitMarginPercentage = '';
                                                            }

                                                        @endphp
                                                        <td>{{ $profitMarginPercentage }}</td>
                                                        <td>{{ $enquiry->updated_at->format('d/m/Y') }}</td>
                                                        <td>{{ $enquiry->product->supplier_traced }}</td>
                                                        <td>{{ $enquiry->rejected }}</td>
                                                        <td>{{ $enquiry->rejected_customer_comment }}</td>
                                                        <td
                                                            style="color: @if ($enquiry->status == 'pending') red @elseif($enquiry->status == 'submitted') green @elseif($enquiry->status == 'accept') blue @elseif($enquiry->status == 'rejected') orange @endif">
                                                            {{ ucfirst($enquiry->status) }}
                                                        </td>


                                                        <!--<td class="action-btn">-->

                                                        <!--            <a href="{{ route('enquiryy.edit', $enquiry->id) }}"-->
                                                        <!--                class="align-items-center btn btn-success d-flex">Edit</a>-->

                                                        <!--            <form method="POST"-->
                                                        <!--                action="{{ url('delete-enquiries/' . $enquiry->id) }}">-->
                                                        <!--                @csrf-->

                                                        <!--                <button type='submit'-->
                                                        <!--                    class="align-items-center btn btn-danger d-flex">Delete-->

                                                        <!--                </button>-->
                                                        <!--            </form>-->



                                                        <!--</td>-->

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <a href="{{ route('enquiry.indexx') }}" rel="noopener noreferrer" class="mx-2">
                                                            <button type="button" class="btn btn-primary active">
                                                               Back
                                                            </button>
                                                        </a>
                                                        
                                        <!--<button type="submit" class="btn btn-success btn-user align-end ">Reoffer</button>-->
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
