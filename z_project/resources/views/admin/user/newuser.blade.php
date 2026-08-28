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
                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        <h4 class="card-title">Customer Management</h4>


 <!--<a href="{{ route('customer.indexx') }}" class="btn mx-1 btn-primary">Outlet</a>-->
 <!--<a href="{{ route('customer.indexx1') }}" class="btn mx-1 btn-primary">Group</a>-->


                                        <p class="card-description">
                                            <!-- Add class <code>.table-striped</code> -->
                                        </p>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <table class="table all-package theme-table" id="customer">


                                                    <thead class="b-shadow">
                                                        <tr>
                                                            <th class="text-center">Sr.</th>
                                                            <th class="text-center">Customer Name</th>
                                                            <th class="text-center">Outlet Name</th>
                                                            <th class="text-center">Customer Number</th>
                                                            <th class="text-center">Email</th>
                                                            <th class="text-center">Customer Type</th>
                                                            <th class="text-center">Credit Status</th>
                                                            <th class="text-center">Credit Limit</th>
                                                            <th class="text-center">Due Max Days</th>
                                                            <th class="text-center">Registerd at</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($customers as $key => $customer)
                                                            <tr>
                                                                <td><b>{{ ++$key }}</b></td>
                                                                <td class="text-center">{{ $customer?->name }}</td>
                                                                <td class="text-center">{{ $customer?->outlet_name }}</td>
                                                                <td class="text-center">{{ $customer?->mobile_number }}</td>
                                                                <td class="text-center">{{ $customer?->email }}</td>
                                                                <td class="text-center" style="text-transform: capitalize">{{ $customer?->type }}</td>
                                                                <td class="text-center" style="text-transform: capitalize">{{ $customer?->credit_status }}</td>
                                                                <td class="text-center" style="text-transform: capitalize">{{ $customer?->credit_limit }}</td>
                                                                <td class="text-center" style="text-transform: capitalize">{{ $customer?->due_days_limit }}</td>
                                                                <td class="text-center">{{ optional($customer->created_at)->format('Y-m-d') }}</td>
                                                                <td class="d-flex justify-content-center">
                                                                    {{-- @if ($customer && $customer->type == 'outlet') --}}
                                                                        <a href="{{ url('edit-customer/' . $customer->id) }}" class="btn mx-1 btn-secondary">Edit</a>
                                                                   {{--  @endif  --}}

                                                                    <form method="POST"
                                                                        action="{{ url('delete-customer/' . $customer->id) }}">
                                                                        @csrf
                                                                        @method('delete')

                                                                        <button type='submit'
                                                                            class="align-items-center btn btn-danger d-flex">Delete
                                                                        </button>
                                                                    </form>


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

            <!-- All customer Table Ends-->
