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

                                        <div class="row gx-4">
                                            <div class="col-md-4 bgg-t">
                                                <div class="title-header option-title">
                                                    <h5>All Enquiries</h5>

                                                    <form method="GET" action="{{ url('/enquiry/indexx/') }}"
                                                        class="">
                                                        <div class="">
                                                            <div class=""> <select name="status" id="status"
                                                                    class="form-control">
                                                                    <option value="">Select All</option>
                                                                    <option value="submitted"
                                                                        {{ $status === 'submitted' ? 'selected' : '' }}>
                                                                        submitted</option>
                                                                    <option value="accept"
                                                                        {{ $status === 'accept' ? 'selected' : '' }}>Accept
                                                                    </option>
                                                                    <option value="rejected"
                                                                        {{ $status === 'rejected' ? 'selected' : '' }}>
                                                                        Rejected
                                                                    </option>
                                                                    <option value="pending"
                                                                        {{ $status === 'pending' ? 'selected' : '' }}>
                                                                        Pending
                                                                    </option>
                                                                </select></div>
                                                            <div class="">
                                                                <div class="d-flex mt-3 " style="gap:30px;">
                                                                    <button type='submit'
                                                                        class="align-items-center btn btn-theme btn-success  btn-user d-flex">Go
                                                                    </button>
                                                                    <a href="/enquiry/index" class=" btn-cancel td-none">
                                                                        Cancel</a>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </form>



                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="enquire-box">
                                                    <form action="{{ route('enquiry.import') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf

                                                        <div class="form-group row">
                                                            <div class="col-sm-12  mb-sm-0">
                                                                <span style="color:red;">*</span>File
                                                                Input(Datasheet)</label>
                                                                <input type="file"
                                                                    class="form-control form-control-user @error('file') is-invalid @enderror"
                                                                    id="exampleFile" name="file"
                                                                    value="{{ old('file') }}">

                                                                @error('file')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>

                                                        </div>




                                                    </form>
                                                    <div class="d-flex gap-4">
                                                        <button type="submit"
                                                            class="btn btn-success btn-user align-end">Import Enquiries
                                                            Products</button>
                                                        <div class="w-full">
                                                            <a href="{{ route('enquiry.export') }}"
                                                                class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i> Export To Excel
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>



                                            {{-- <div class="col-md-3">
                                                <h5 class="h5 mt-4">
                                                    @if ($enquiriesDataPanding->count() != 0)
                                                        <a href="{{ route('status.update', ['status' => 'pending']) }}"
                                                            class="align-items-center btn btn-success d-flex">
                                                            {{ $enquiriesDataPanding->count() }}
                                                            Enquiries Data Pending
                                                        </a>
                                                    @endif
                                                </h5>
                                            </div> --}}
                                        </div>


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

                                        </div>

                                        <br>

                                        <div class="table-responsive category-table">
                                            <div>
                                                <table class="table table-bordered all-package theme-table" id="enquiries">
                                                    <thead class="b-shadow">
                                                        <tr>
                                                            <th>Sr.</th>
                                                            <th class="text-center">Enq No.</th>
                                                            <th class="text-center">Enq Date</th>
                                                            <th class="text-center">Customer Name</th>
                                                            <th class="text-center">Contact No.</th>
                                                            <th class="text-center">Location</th>
                                                            <th class="text-center">Outlet Name</th>
                                                            <th class="text-center">No Of Items</th>
                                                            <th class="text-center">Status</th>
                                                            <th class="text-center">Submitted Date</th>
                                                        </tr>
                                                    </thead>
                                                  <tbody>
    @foreach ($enquiriesData as $key => $enquiry)
        @if ($enquiry->status == 'pending' && $enquiry->offer_check != '1')
            @php
                $enquiries = DB::table('enquiries')
                    ->where('user_id', '=', $enquiry->user_id)
                    ->where('enquiry_no', $enquiry->enquiry_no)
                    ->get();
            @endphp
            
             @php
            // Build the key that matches controller's ->keyBy()
            $url = "/customer/product/detailss/" . $enquiry->user_id . "?enquiry_no=" . $enquiry->enquiry_no;
            $notifyKey = $enquiry->user_id . '_' . $url;

            // Fetch actual notification
            $notification = $notifications[$notifyKey] ?? null;
        @endphp

            @if ($enquiries->count() == 0)
                <tr>
                    <td colspan="8" class="col-span-full"
                        style="text-align: center;color: red">
                        {{ $status . ' not found!!' }}
                    </td>
                </tr>
            @else
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                       <a class="link-d"
                          href="{{ route('customer.product.detailss', ['user' => $enquiry->user_id]) }}
    ?enquiry_no={{ $enquiry->enquiry_no }}
    &notification_id={{ $notification->id ?? '' }}"
>
                            {{ $enquiry->enquiry_no }}
                        </a>
                    </td>
                    <td>{{ $enquiry->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        @if ($enquiry->user_id)
                            <a class="link-d"
                               href="{{ route('customer.product.detailss', ['user' => $enquiry->user_id]) }}">
                                {{ $enquiry->user?->name }}
                            </a>
                        @endif
                    </td>
                    <td>{{ $enquiry->user?->mobile_number }}</td>
                    <td class="text-center">
                        @if ($enquiry->user_id)
                            <a class="link-d"
                               href="{{ route('customer.product.detailss', ['user' => $enquiry->user_id]) }}">
                                {{ $enquiry->user?->location }}
                            </a>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($enquiry->user_id)
                            <a class="link-d"
                               href="{{ route('customer.product.detailss', ['user' => $enquiry->user_id]) }}">
                                {{ $enquiry->user?->outlet_name }}
                            </a>
                        @endif
                    </td>
                    <td class="text-center">{{ $enquiries->count() }}</td>
                    <td style="color: purple !important;">New Enquiry</td>
                    <td>{{ $enquiry->updated_at->format('d/m/Y') }}</td>
                </tr>
            @endif
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
@endsection

<!-- All User Table Ends-->
