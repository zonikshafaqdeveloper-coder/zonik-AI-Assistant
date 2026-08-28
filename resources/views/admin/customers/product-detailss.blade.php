@extends('admin.layouts.appnew')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="page-body">

        <body>

            <!-- partial -->
            <div class="container page-body-wrapper">
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="row">

                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="col-md-4 bgg-t">
                                        <div class="title-header option-title">
                                            <h5>All Enquiries</h5>
                                            @php
                                                $actual_link = $_SERVER['REQUEST_URI'];
                                                if (isset($_GET['enquiry_no'])) {
                                                    $enquiry_no = $_GET['enquiry_no'];

                                                    $url_components = parse_url($actual_link);

                                                    parse_str($url_components['query'], $query_params);

                                                    // Remove the 'status' parameter if it exists
                                                    if (isset($query_params['status'])) {
                                                        unset($query_params['status']);
                                                    }

                                                    // Rebuild the query string
                                                    $new_query_string = http_build_query($query_params);

                                                    // Reconstruct the URL
                                                    $new_url = isset($url_components['scheme'])
                                                        ? $url_components['scheme'] . '://'
                                                        : '';
                                                    $new_url .= isset($url_components['host'])
                                                        ? $url_components['host']
                                                        : '';
                                                    $new_url .= isset($url_components['path'])
                                                        ? $url_components['path']
                                                        : '';
                                                    if (!empty($new_query_string)) {
                                                        $new_url .= '?' . $new_query_string;
                                                    }
                                                    if (isset($url_components['fragment'])) {
                                                        echo $new_url .= '#' . $url_components['fragment'];
                                                    }
                                                } else {
                                                    $url_components = parse_url($actual_link);

                                                    // Check if the URL has a query string
                                                    if (isset($url_components['query'])) {
                                                        parse_str($url_components['query'], $query_params);

                                                        // Remove the 'status' parameter if it exists
                                                        if (isset($query_params['status'])) {
                                                            unset($query_params['status']);
                                                        }

                                                        // Rebuild the query string
                                                        $new_query_string = http_build_query($query_params);
                                                    } else {
                                                        // If there's no query string, initialize an empty array
                                                 $query_params = [];
                                                $new_query_string = '';
                                            }

                                                // Reconstruct the URL
                                                $new_url = isset($url_components['scheme'])
                                                    ? $url_components['scheme'] . '://'
                                                    : '';
                                                $new_url .= isset($url_components['host'])
                                                    ? $url_components['host']
                                                    : '';
                                                $new_url .= isset($url_components['path'])
                                                    ? $url_components['path']
                                                    : '';
                                                if (!empty($new_query_string)) {
                                                    $new_url .= '?' . $new_query_string;
                                                }
                                                if (isset($url_components['fragment'])) {
                                                    $new_url .= '#' . $url_components['fragment'];
                                                                                            }


                                                }

                                            @endphp

                                            <form method="GET" action="{{ $actual_link }}" class="">
                                                <div class="">
                                                    <div class="">
                                                        @if (isset($_GET['enquiry_no']))
                                                            <input type="hidden" name="enquiry_no" id="enquiry_no"
                                                                value="{{ $enquiry_no }}">
                                                        @endif

                                                        <select name="status" id="status" class="form-control">
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
                                                        </select>
                                                    </div>
                                                    <div class="">
                                                        <div class="d-flex mt-3 " style="gap:30px;">
                                                            <button type='submit'
                                                                class="align-items-center btn btn-theme btn-success  btn-user d-flex">Go
                                                            </button>
                                                            @if (isset($_GET['enquiry_no']))
                                                                <a href="{{ $new_url }}" class=" btn-cancel td-none">
                                                                    Cancel</a>
                                                            @else
                                                                <a href="{{$new_url}}" class=" btn-cancel td-none">
                                                                    Cancel</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>



                                            </form>



                                        </div>
                                    </div>
                                    <div class="card-body">
                                        {{ csrf_field() }}

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <!-- <strong>Whoops!</strong> There were some problems with your input.<br><br> -->
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <h5 class="card-title">OFFER WORK SHEET</h5>
                                        <div class="mb-3">
                                        <a href="{{ route('enquiry.export', request()->all()) }}" class="btn btn-success">
                                       ✔ Export To Excel
                                         </a>
                                        </div>
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
                                    <div class="margin-left">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Customer Name</th>
                                                    <td>{{ $enquirie->user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Customer No</th>
                                                    <td>{{ $enquirie->user->mobile_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Enquiry No</th>
                                                    <td>{{ $enquirie->enquiry_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Enquiry Date</th>
                                                    <td>{{ $enquirie->created_at->format('d/m/Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Outlet Name</th>
                                                    <td>{{ $enquirie->user->mobile_number }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>



                                    <div class="table-responsive category-table">
                                        <div class="margin-all">
                                            <form action="{{ route('admin.enquiry.offerPrice.store') }}" method="POST">
                                                @csrf
                                                <table class="table all-package theme-table" id="table_id">
                                                    <thead class="b-shadow">
                                                        <tr class="text-capitalize">
                                                            <th>Sr.</th>
                                                            <th>Product Name</th>
                                                            <th>Unit</th>
                                                            <th>Brand</th>
                                                            <th>Order Qty (Pattern)</th>
                                                            <th>Monthly Consumption</th>
                                                            <th>MRP</th>
                                                            <th>Cost Per item (Basic)</th>
                                                            <th>Total GST (%)</th>
                                                            <th>Sale Price (Basic)</th>
                                                            <th>Profit Margin</th>
                                                            <th>Last Updated Price</th>
                                                            <th>Supplier Traced</th>
                                                            <th colspan="2">Customer Comment</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                         <tbody>
    @php
        $totalgst = 0;
    @endphp

    @foreach ($enquiriesData as $key => $enquiry)
        @if ($enquiry->status != 'accept') 
            @php
                $totalgst = $enquiry->product?->cgst + $enquiry->product?->sgst;
            @endphp

            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $enquiry->product?->product_name ?? 'N/A' }}</td>
                <td>{{ $enquiry->product?->unit ?? 'N/A' }}</td>
                <td>{{ $enquiry->product?->brands ?? 'N/A' }}</td>
                <td>Carton ({{ $enquiry->product_types == '1' ? 'Box' : 'Loose' }}) {{ $enquiry->quantity }} pcs</td>
                <td>{{ $enquiry->monthlyconsumption }}</td>
                <td>{{ $enquiry->mrp }}</td>
                <td>{{ $enquiry->product?->cost_per_item ?? 'N/A' }}</td>
                <td>{{ $totalgst ?? 'N/A' }}%</td>

                <td>
                    <input type="number" class="offer-input"
                        onkeyup="validateDecimal(this); EnquiryOfferPrice(this.value, Number('{{ $enquiry->product?->cost_per_item ?? 0 }}'), 'result{{ $key + 1 }}')"
                        name="offer_price{{ $key + 1 }}"
                        data-cost-per-item="{{ $enquiry->product->cost_per_item ?? 0 }}"
                        value="{{ number_format($enquiry->offer_price, 2, '.', '') }}">
                    <input type="hidden" name="id{{ $key + 1 }}" value="{{ $enquiry->id }}">
                </td>

                <td>
                    <span class="result" id="result{{ $key + 1 }}"></span>
                </td>
                <td>{{ $enquiry->updated_at->format('d/m/Y') }}</td>
                <td>{{ $enquiry->product?->supplier_traced ?? 'N/A' }}</td>
                <td>{{ $enquiry->counter_comment }}</td>
                <td>{{ $enquiry->expected_price_value }}</td>

@php
    $statusColor = '';

    if ($enquiry->status == 'pending' && $enquiry->reoffer_count == 1) {
        $statusColor = '#007bff';
    } elseif ($enquiry->status == 'pending' && $enquiry->offer_check == '1') {
        $statusColor = 'red';
    } elseif ($enquiry->status == 'pending' && $enquiry->offer_check != '1') {
        $statusColor = 'purple';
    } elseif ($enquiry->status == 'submitted') {
        $statusColor = 'green';
    } elseif ($enquiry->status == 'rejected') {
        $statusColor = 'orange';
    }
@endphp

<td>
    @if ($enquiry->status == 'pending' && $enquiry->reoffer_count == 1)
        New Offer Request
    @elseif ($enquiry->status == 'pending' && $enquiry->offer_check == '1')
        Reoffer Request
    @elseif($enquiry->status == 'pending' && $enquiry->offer_check != '1')
        New Enquiry
    @elseif($enquiry->status == 'rejected')
        Customer Cancel
    @else
        {{ ucfirst($enquiry->status) }}
    @endif
</td>

                <td style="display:flex; gap: 20px; margin-top: 25px;">
                    <a href="{{ route('enquiryy.edit', $enquiry->id) }}" class="align-items-center btn btn-success d-flex">Edit</a>
                    <button type="button" class="align-items-center btn btn-danger d-flex delete-enquiry" data-id="{{ $enquiry->id }}">
                        Delete
                    </button>
                </td>
            </tr>
        @endif
    @endforeach
</tbody>
                                                </table>
                                                  <button type="submit" class="btn btn-primary bg-primary">Submit
                                                    Offer</button>
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
                                    <button type="button" class="btn btn-info waves-effect"
                                        data-bs-dismiss="modal">Close</button>
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
                                url: "{{ route('admin.enquiry.offerPrice.store') }}",
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


                <script>
                    function validateDecimal(input) {
                        let value = input.value;
                        let parts = value.split('.');

                        if (parts.length > 1 && parts[1].length > 2) {
                            value = parseFloat(value).toFixed(2);
                        }

                        input.value = value;
                    }
                    </script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.delete-enquiry').forEach(button => {
        button.addEventListener('click', function () {
            let enquiryId = this.getAttribute('data-id');
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'); // Optional chaining to prevent errors
            
            if (!csrfToken) {
                console.error("CSRF token not found.");
                return;
            }

            if (confirm('Are you sure you want to delete this enquiry?')) {
                fetch(`/delete-enquiry/${enquiryId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Enquiry deleted successfully.');
                        document.querySelector(`button[data-id="${enquiryId}"]`).closest('tr').remove();
                    } else {
                        alert('Failed to delete enquiry.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});

</script>

            @endsection
            <!-- All User Table Ends-->

