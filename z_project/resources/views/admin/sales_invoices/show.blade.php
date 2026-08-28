@extends('admin.layouts.appnew')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.select2-container .select2-selection--single {
    height: 44px !important;
    display: flex !important;
    align-items: center !important;
    background-color: #e9ecef !important;
    opacity: 1 !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 44px !important;
    padding-left: 12px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 44px !important;
}

.select2-container--default .select2-selection--single .select2-selection__clear{
    display:none;
}

#invoiceTable tfoot {
    border-top: 3px solid #dee2e6;
}

#invoiceTable tfoot tr:first-child td {
    padding-top: 20px;
}
.form-check .form-check-input{
    float: left;
    margin-left: 0;
}
#invoiceTable tfoot input {
    background-color: #f8f9fa;
    cursor: not-allowed;
}

</style>


<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="row">
                    <div class="col-sm-12 m-auto">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header-2 mb-3 d-flex justify-content-between">
                                    <h3>Order View</h3>

                                    <a href="{{ route('admin.invoice') }}" class="btn btn-secondary">
                                        ← Back
                                    </a>
                                </div>


                                <div class="row">

                        <div class="col-md-4 mb-3">
                            <label>Customer Name</label>
                            <input type="text" class="form-control" value="{{ $order->mainuser->name }}" readonly>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Outlet Name</label>
                            <input type="text" class="form-control" value="{{ $order->outlet->outlet_name }}" readonly>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Company Name</label>
                            <input type="text" class="form-control" value="{{ $order->mainuser->outlet_name ?? '' }}" readonly>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Delivery Slot</label>
                            <input type="text"
                                class="form-control"
                                value="{{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }} - {{ $order->delivery_time_slot }}"
                                readonly>
                        </div>

                    </div>
 <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="invoiceTable">
                                    <thead class="table-light">
<tr>
    <th>Sr</th>
    <th>Product</th>
    <!--<th>MRP</th>-->
    <th>Offer Price</th>
    <th>Qty</th>
    <th>Amount</th>
</tr>
</thead>
<tbody>
@foreach($order->orderItems as $index => $item)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $item->product->product_name ?? 'N/A' }}</td>
    <!--<td>{{ number_format($item->mrp, 2) }}</td>-->
    <td>{{ number_format($item->offer_price, 2) }}</td>
    <td>{{ $item->quantity }}</td>
    <td>{{ number_format($item->price, 2) }}</td>
</tr>
@endforeach
</tbody>
<tfoot>
<tfoot>
<tr>
    <td colspan="4"></td>
    <td class="fw-bold text-end">Subtotal</td>
    <td>
        <input class="form-control form-control-sm text-end fw-bold"
               style="max-width: 140px"
               value="{{ number_format($order->subtotal, 2) }}"
               readonly>
    </td>
</tr>

<tr>
    <td colspan="4"></td>
    <td class="fw-bold text-end">Product Discount</td>
    <td>
        <input class="form-control form-control-sm text-end"
               style="max-width: 140px"
               value="{{ number_format($order->product_discount, 2) }}"
               readonly>
    </td>
</tr>

<tr>
    <td colspan="4"></td>
    <td class="fw-bold text-end">CGST + SGST</td>
    <td>
        <input class="form-control form-control-sm text-end"
               style="max-width: 140px"
               value="{{ number_format($order->cgst_sgst, 2) }}"
               readonly>
    </td>
</tr>

<tr>
    <td colspan="4"></td>
    <td class="fw-bold text-end">Delivery Charges</td>
    <td>
        <input class="form-control form-control-sm text-end"
               style="max-width: 140px"
               value="{{ number_format($order->delivery_charges, 2) }}"
               readonly>
    </td>
</tr>

<tr>
    <td colspan="4"></td>
    <td class="fw-bold text-end">Packing Charges</td>
    <td>
        <input class="form-control form-control-sm text-end"
               style="max-width: 140px"
               value="{{ number_format($order->packing_charges, 2) }}"
               readonly>
    </td>
</tr>
<tr>
    <td colspan="4"></td>
    <td class="fw-bold text-end">Other Charges</td>
    <td>
        <input class="form-control form-control-sm text-end"
               style="max-width: 140px"
               value="{{ number_format($order->others_charges, 2) }}"
               readonly>
    </td>
</tr>

<tr>
    <td colspan="4"></td>
    <td class="fw-bold text-end">Grand Total</td>
    <td>
        <input class="form-control form-control-sm text-end fw-bold"
               style="max-width: 140px"
               value="{{ number_format($order->total_discount_value, 2) }}"
               readonly>
    </td>
</tr>
<tr>
    <td colspan="4"></td>
    <td class="fw-bold text-end align-middle">Payment Term</td>
    <td>
        <div class="form-check">
            <input class="form-check-input"
                   type="checkbox"
                   disabled
                   {{ $order->payment_method === 'pay_on_delivery' ? 'checked' : '' }}>
            <label class="form-check-label">
                Pay on Delivery
            </label>
        </div>

        <div class="form-check mt-1">
            <input class="form-check-input"
                   type="checkbox"
                   disabled
                   {{ $order->payment_method === 'credit' ? 'checked' : '' }}>
            <label class="form-check-label">
                Place Order on Credit
            </label>
        </div>
        
         <div class="form-check mt-1">
            <input class="form-check-input"
                   type="checkbox"
                   disabled
                   {{ $order->payment_method === 'special_credit' ? 'checked' : '' }}>
            <label class="form-check-label">
                Place on Credit (Special Items)
            </label>
        </div>
        
    </td>
</tr>
</tfoot>

</tfoot>

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
