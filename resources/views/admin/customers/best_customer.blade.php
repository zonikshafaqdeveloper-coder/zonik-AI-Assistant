@extends('admin.layouts.appnew')
@section('content')
<style>
    .disabled {
    pointer-events: none;
    opacity: 0.6;
    cursor: not-allowed;
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
                                        <h4 class="card-title">Best Customer Management (Month Wise)</h4>


 <!--<a href="{{ route('customer.indexx') }}" class="btn mx-1 btn-primary">Outlet</a>-->
 <!--<a href="{{ route('customer.indexx1') }}" class="btn mx-1 btn-primary">Group</a>-->


                                        <p class="card-description">
                                            <!-- Add class <code>.table-striped</code> -->
                                        </p>


                                        <form method="GET" action="{{ route('customer.best_customer') }}" class="float-right">
    <div class="form-group">
        <label for="month">Select Month:</label>    
        <select name="month" class="form-control" onchange="this.form.submit()">
            <option value="" {{ empty($selectedMonth) ? 'selected' : '' }}>All Months</option>
            @foreach($months as $key => $month)
                <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>
                    {{ $month }}
                </option>
            @endforeach
        </select>
    </div>
</form>


                        

                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <table class="table all-package theme-table" id="customer">


                                                    <thead class="b-shadow">
                                                    <tr>
                                                        <th>Sr.</th>
                                                        <th>Customer Name</th>
                                                        <th>Outlet Name</th>
                                                        <th>Mobile Number</th>
                                                        <th>Number of Orders</th>
                                                        <th>Total</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                            @foreach ($bestCustomers as $key => $customer)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $customer?->name }}</td>
                                                    <td>{{ $customer?->outlet_name }}</td>
                                                    <td>{{ $customer?->mobile_number }}</td>
                                                    <td>{{ $customer->order_count }}</td> 
                                                    <td>{{ $customer?->total_amount }}</td>
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
