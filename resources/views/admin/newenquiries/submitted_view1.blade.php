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

    <div class="page-body">
        @php
        $text = '';
        $customerdetailss = '';
        @endphp
        <body>

            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">

                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif


                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-between text-capitalize">
                                                                                   @php
                                                $rejected = DB::table('enquiries')->where('status', '=', 'rejected')->get();
                                                   $submitted = DB::table('enquiries')
                                                    ->whereIn('status', ['pending', 'rejected'])
                                                    ->where('offer_check', '=', 1)
                                                    ->distinct()
                                                    ->count('enquiry_no');
                                                $accept = DB::table('enquiries')->where('status', '=', 'accept')->get();
                                               
                                                $submitted1 =DB::table('enquiries')
                                                            ->where('status', '=', 'submitted')
                                                            ->distinct()
                                                            ->count('enquiry_no');
                                                
                                                $pending = DB::table('enquiries')
                                                    ->where('status', 'pending')
                                                    ->where('offer_check', '!=', '1')
                                                    ->distinct()
                                                    ->count('enquiry_no');


                                                $reoffer = DB::table('enquiries')->where('status', '=', 'reoffer')->get();

                                                $currentStatus = request()->status;
                                                $currentRoute = Route::currentRouteName();
                                            @endphp
                                                                                                <div>

                                                {{-- NEW ENQUIRY --}}
                                                <a href="{{ route('enquiry.indexx') }}">
                                                    <button type="button"
                                                        class="btn btn-primary {{ $currentRoute == 'enquiry.indexx' ? 'active-btn' : '' }}">
                                                        New Enquiry ({{ $pending }})
                                                    </button>
                                                </a>

                                                {{-- SUBMITTED --}}
                                                <a href="{{ route('submitted.all') }}">
                                                    <button type="button"
                                                        class="btn btn-primary {{ $currentRoute == 'submitted.all' ? 'active-btn' : '' }}">
                                                        Submitted ({{ $submitted1 }})
                                                    </button>
                                                </a>

                                                {{-- OFFER & REOFFER --}}
                                                <a href="{{ route('submitted.view', ['status' => 'submitted']) }}">
                                                    <button type="button"
                                                        class="btn btn-primary {{ $currentStatus == 'submitted' ? 'active-btn' : '' }}">
                                                        OFFER & REOFFER ({{ $submitted }})
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

                                        <br>

                                        <div class="table-responsive category-table">
                                            <div>
                                                <table class="table table-bordered all-package theme-table" id="enquiries">
                                                    <thead class="b-shadow">
                                                        <tr>
                                                            <th class="text-center">Sr.</th>
                                                            <th class="text-center">Enq No.</th>
                                                            <th class="text-center">Enq Date</th>
                                                            <th class="text-center">Customer Name</th>
                                                            <th class="text-center">Contact No.</th>
                                                            <th class="text-center">Location</th>
                                                            <th class="text-center">Outlet Name</th>
                                                            <th class="text-center">No Of Items</th>
                                                            <th class="text-center">Status</th>
                                                            <th class="text-center">Attempt</th>
                                                            <th class="text-center">Submitted Date</th>
                                                        </tr>
                                                    </thead>
                                                   <tbody>
    @php $previousEnquiryNo = null; @endphp

    @foreach ($enquiriesData as $key => $enquiry)
      
            @php
                $text = '';
                $enquiries = DB::table('enquiries')
    ->where('user_id', $enquiry->user_id)
    ->where('enquiry_no', $enquiry->enquiry_no)
    ->whereIn('status', ['submitted',]) 
    ->get();


                if ($enquiry->user_id) {
                    $customerdetailss = route('customer.product.detailss.new', ['user' => $enquiry->user_id]);
                }

                $showRow = $enquiry->enquiry_no !== $previousEnquiryNo;
                $previousEnquiryNo = $enquiry->enquiry_no;
            @endphp

            @if ($showRow)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <a class="link-d" href="{{ $customerdetailss }}?enquiry_no={{ $enquiry->enquiry_no }}">
                            {{ $enquiry->enquiry_no }}
                        </a>
                    </td>
                    <td>{{ optional($enquiry->created_at)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $enquiry->user?->name }}</td>
                    <td class="text-center">{{ $enquiry->user?->mobile_number }}</td>
                    <td class="text-center">{{ $enquiry->user?->location }}</td>
                    <td class="text-center">{{ $enquiry->user?->outlet_name }}</td>
                    <td class="text-center">{{ $enquiries->count() }}</td>
                    <td>Submitted</td>
                    <td class="text-center">{{ $enquiry->reoffer_count }}</td>
                    <td>{{ optional($enquiry->updated_at)->format('d/m/Y') }}</td>
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

                </div>
            </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".dropdownMenuButtonResponded").hover(function() {
                $(".dropdown-menu-Responded").addClass("show");
                $(this).attr("aria-expanded", "true");
            }, function() {

                $(this).attr("aria-expanded", "false");
            });

            // Hide dropdown menu on click outside
            $(document).on("click", function(event) {
                var dropdown = $(".dropdown-menu-Responded");
                if (!dropdown.is(event.target) && dropdown.has(event.target).length === 0 && !$(".dropdownMenuButtonResponded").is(event.target)) {
                    dropdown.removeClass("show");
                    $(".dropdownMenuButtonResponded").attr("aria-expanded", "false");
                }
            });
        });
    </script>
@endsection

<!-- All User Table Ends-->
