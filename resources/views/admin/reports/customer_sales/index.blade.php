@extends('admin.layouts.appnew')

@section('content')


<div class="page-body">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">

                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">

                             {{-- Success Message --}}
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="mb-4">Customer Sales Report</h3>
                                </div>



<form method="GET" class="mb-3">

    <div style="display:flex; gap:10px; align-items:end;">

        <div>
            <label>From Date</label>
            <input type="date" name="from" value="{{ $from ?? '' }}" class="form-control">
        </div>

        <div>
            <label>To Date</label>
            <input type="date" name="to" value="{{ $to ?? '' }}" class="form-control">
        </div>

        <div>
            <button type="submit" class="btn btn-success">
                Filter
            </button>

            <a href="{{ route('customer.sales.report') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>

    </div>

</form>

 <div class="table-responsive">
<table class="table all-package theme-table customer-sales-table" id="">
  <thead class="b-shadow">

        <tr>

            <th>ID</th>
            <th>Customer Name</th>
            <th>Outlet</th>
            <th>Total Orders</th>

            <th>{{ $currentMonth->format('F') }}</th>
            <th>{{ $month3->format('F') }}</th>
            <th>{{ $month2->format('F') }}</th>
            <th>{{ $month1->format('F') }}</th>

            <th>Total Amount</th>

            <th>Last Invoice Date</th>
            <th>Total Overdue</th>

        

        </tr>

    </thead>

   <tbody>
    @foreach($data as $row)
        <tr>
            <td>{{ $row->id }}</td>
            <td>{{ $row->customer_name }}</td>
            <td>{{ $row->outlet_name ?? '-' }}</td>
            <td>{{ $row->order_count }}</td>

            <td data-order="{{ $row->current_month_sales }}">
                ₹ {{ number_format($row->current_month_sales,2) }}
            </td>

            <td data-order="{{ $row->month3_sales }}">
                ₹ {{ number_format($row->month3_sales,2) }}
            </td>

            <td data-order="{{ $row->month2_sales }}">
                ₹ {{ number_format($row->month2_sales,2) }}
            </td>

            <td data-order="{{ $row->month1_sales }}">
                ₹ {{ number_format($row->month1_sales,2) }}
            </td>

            <td data-order="{{ $row->total_amount }}">
                ₹ {{ number_format($row->total_amount,2) }}
            </td>

            <td data-order="{{ $row->last_invoice_date ? \Carbon\Carbon::parse($row->last_invoice_date)->timestamp : 0 }}">
                {{ $row->last_invoice_date ? \Carbon\Carbon::parse($row->last_invoice_date)->format('d-m-Y') : '-' }}
            </td>

            <td class="{{ $row->total_overdue > 0 ? 'text-danger fw-bold' : '' }}" data-order="{{ $row->total_overdue }}">
                ₹ {{ number_format($row->total_overdue, 2) }}
            </td>
        </tr>
    @endforeach
    </tbody>

</table>
</div>


     <div class="mt-4 p-3 bg-light border rounded">

    <h5>
        Overall Orders :
        <strong>{{ $overallTotalOrders }}</strong>
    </h5>

    <h5>
        Overall Basic Sales :
        <strong>₹ {{ number_format($overallTotalAmount,2) }}</strong>
    </h5>

    <h5>
        Overall Sales Including GST :
        <strong>₹ {{ number_format($overallTotalWithGST,2) }}</strong>
    </h5>

</div>

                            </div> {{-- card-body --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection