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

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                        
                        <div class="row ">
                            <div class="col-md-4">
                                                                <form action="{{ route('enquiry.import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <div class="col-sm-12 mb-3 mt-3 mb-sm-0">
                                                <span style="color:red;">*</span>File Input(Datasheet)</label>
                                                <input type="file"
                                                    class="form-control form-control-user @error('file') is-invalid @enderror"
                                                    id="exampleFile" name="file" value="{{ old('file') }}">

                                                @error('file')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>

                                    

                                </form>

                            </div>
                            
                            <div class="col-md-4"><div class="card-footer">
                                        <button type="submit" class="btn btn-success btn-user float-right mb-3">Import
                                            Enquiries Products</button>
                                        <!-- <a class="btn btn-primary float-right mr-3 mb-3" href="">Cancel</a> -->
                                    </div></div>
                                    
                            <div class="col-md-4"><a href="{{ route('enquiry.export') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> Export To Excel
                                </a></div>
                        </div>
                        
                            <div class="title-header option-title">
                                <h5>All Enquiries</h5>
                                <h5>

                                    @if ($enquiriesDataPanding->count() != 0)
                                        <a href="{{ route('status.update', ['status' => 'pending']) }}"
                                            class="align-items-center btn btn-success d-flex">
                                            {{ $enquiriesDataPanding->count() }}
                                            Enquiries Data Pending
                                        </a>
                                    @endif
                                </h5>
                                <form method="GET" action="{{ url('/enquiry/index/') }}">

                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select All</option>
                                        <option value="submitted"
                                            {{ $status === 'submitted' ? 'selected' : '' }}> submitted</option>
                                        <option value="accept" {{ $status === 'accept' ? 'selected' : '' }}>Accept</option>
                                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected
                                        </option>
                                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                    </select>
                                    <div class="d-flex mt-3">
                                    <button type='submit' class="align-items-center btn btn-theme d-flex">Go
                                    </button>
                                    <a href="/enquiry/index"> Cancel</a>
                                    </div>
                                </form>


                                
                            </div>
                            <div class="d-flex">
                                @php
                                    $rejected = DB::table('enquiries')
                                        ->where('status', '=', 'rejected')
                                        ->get();
                                    $submitted = DB::table('enquiries')
                                        ->where('status', '=', 'submitted')
                                        ->get();
                                    $accept = DB::table('enquiries')
                                        ->where('status', '=', 'accept')
                                        ->get();
                                    $pending = DB::table('enquiries')
                                        ->where('status', '=', 'pending')
                                        ->get();
                                @endphp
                                <a href="{{ route('status.view', ['status' => 'submitted']) }}"
                                    rel="noopener noreferrer">
                                    <button type="button" class="btn btn-primary">submitted
                                        ({{ $submitted->count() }})</button>
                                </a>
                                &nbsp; &nbsp;
                                <a href="{{ route('status.view', ['status' => 'accept']) }}" rel="noopener noreferrer">
                                    <button type="button" class="btn btn-primary">Accept ({{ $accept->count() }})</button>
                                </a>
                                &nbsp; &nbsp;
                                <a href="{{ route('status.view', ['status' => 'pending']) }}" rel="noopener noreferrer">
                                    <button type="button" class="btn btn-primary">Pending
                                        ({{ $pending->count() }})</button>
                                </a>
                                &nbsp; &nbsp;
                                <a href="{{ route('status.view', ['status' => 'rejected']) }}" rel="noopener noreferrer">
                                    <button type="button" class="btn btn-primary">Rejected
                                        ({{ $rejected->count() }})</button>
                                </a>
                            </div>
                            <br>

                            <div class="table-responsive category-table">
                                <div>
                                    <table class="table all-package theme-table" id="table_id">
                                        <thead>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Enq No.</th>
                                                <th>Enq Date</th>
                                                <th>Customer Name</th>
                                                <th>Contact No.</th>
                                                <th>Location</th>
                                                <th>Outlet Name</th>
                                                <th>No Of Items</th>
                                                {{-- <th>Product Name</th>
                                                <th>Unit</th>
                                                <th>Brand</th>
                                                <th>Quantity</th>
                                                <th>Monthly Consumption</th>
                                                <th>Offer Price</th>
                                                <th>MRP</th>
                                                <th>Cost Par item</th>
                                                <th>total GST</th>
                                                <th>carton sale price</th>
                                                <th>lata update price</th>
                                                <th>Discount</th>
                                                <th>Expected Price Value</th> --}}
                                                {{-- <th>Rejected customer comment</th> --}}
                                                <th>Status</th>
                                                <th>Submitted Date</th>
                                                {{-- <th>Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>


                                            @foreach ($enquiriesData as $key => $enquiry)
                                                @php
                                                    $enquiries = DB::table('enquiries')
                                                        ->where('user_id', '=', $enquiry->user_id)
                                                        ->where('enquiry_no', $enquiry->enquiry_no)
                                                        ->get();
                                                @endphp
                                                @if ($enquiry->count() == 0)
                                                    <tr>
                                                        <td colspan="8" class="col-span-full"
                                                            style="text-align: center;color: red">
                                                            {{ $status . ' not found!!' }}
                                                        </td>
                                                    </tr>
                                                @else
                                                    {{-- {{ $enquiry->user_id }} --}}
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                         <a href="{{ route('customer.product.details', ['user' => $enquiry->user_id]) }}?enquiry_no={{$enquiry->enquiry_no}}">
                                                                {{ $enquiry->enquiry_no }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $enquiry->created_at->format('d/m/Y') }}</td>
                                                        <td>
                                                            @if ($enquiry->user_id)
                                                                <a
                                                                    href="{{ route('customer.product.details', ['user' => $enquiry->user_id]) }}">
                                                                    {{ $enquiry->user?->name }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td>{{ $enquiry->user?->mobile_number }}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{ $enquiries->count() }}</td>
                                                        <td
                                                            style="color: @if ($enquiry->status == 'pending') red 
                                                    @elseif($enquiry->status == 'submitted') green 
                                                    @elseif($enquiry->status == 'accept') blue @elseif($enquiry->status == 'rejected') 
                                                    orange @endif">
                                                            {{ ucfirst($enquiry->status) }}</td>
                                                        <td>{{ $enquiry->updated_at->format('d/m/Y') }}</td>

                                                        {{-- <td>
                                                            <ul>

                                                                <li>
                                                                    <a href="{{ route('enquiry.edit', $enquiry->id) }}"
                                                                        class="align-items-center btn btn-success d-flex">edit</a>
                                                                </li>

                                                                <li>
                                                                    <form method="POST"
                                                                        action="{{ url('delete-enquiry/' . $enquiry->id) }}">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button type='submit'
                                                                            class="align-items-center btn btn-theme d-flex">delete
                                                                        </button>
                                                                    </form>


                                                                </li>
                                                            </ul>
                                                        </td> --}}

                                                    </tr>
                                                @endif
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

        {{-- <div class="modal location-modal fade theme-modal" id="locationModal" tabindex="-1"
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
        </div> --}}
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
