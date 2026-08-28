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
</style>
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="row">
                    <div class="col-sm-12 m-auto">

                        <div class="card">
                            <div class="card-body">
                                
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Purchase Order Details</h4>

        <span class="badge 
            @if($purchaseOrder->status === 'draft') bg-warning
            @elseif($purchaseOrder->status === 'pending') bg-info
            @elseif($purchaseOrder->status === 'completed') bg-success
            @else bg-secondary
            @endif
        ">
            {{ strtoupper($purchaseOrder->status) }}
        </span>
    </div>

    <div class="card-body">

        {{-- =======================
            BASIC DETAILS
        ======================== --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <strong>Purchase Order #</strong><br>
                {{ $purchaseOrder->purchase_order_number }}
            </div>

            <div class="col-md-4">
                <strong>Vendor Name</strong><br>
                {{ $purchaseOrder->vendor->name ?? 'N/A' }}
            </div>

            <div class="col-md-4">
                <strong>Reference #</strong><br>
                {{ $purchaseOrder->reference ?? '-' }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <strong>PO Date</strong><br>
                {{ \Carbon\Carbon::parse($purchaseOrder->po_date)->format('d-m-Y') }}
            </div>

            <div class="col-md-4">
                <strong>Delivery Date</strong><br>
                {{ \Carbon\Carbon::parse($purchaseOrder->delivery_date)->format('d-m-Y') }}
            </div>

            <div class="col-md-4">
                <strong>Payment Method</strong><br>
                {{ ucwords(str_replace('_', ' ', $purchaseOrder->payment_method)) }}
            </div>
        </div>

        <div class="row mb-4">
           
            <!-- <div class="col-md-4">
                <strong>Payment Status</strong><br>
                {{ ucwords(str_replace('_', ' ', $purchaseOrder->payment_status)) }}
            </div> -->

            <div class="col-md-4">
                <strong>Location</strong><br>
                {{ $purchaseOrder->location }}
            </div>

            <div class="col-md-4">
                <strong>Pincode</strong><br>
                {{ $purchaseOrder->pincode }}
            </div>
        </div>

        {{-- =======================
            PRODUCTS TABLE
        ======================== --}}
        <div class="table-responsive">
            
             @php
                $hasFreeQty = $purchaseOrder->items->contains(function ($item) {
                    return $item->free_quantity > 0;
                });
            @endphp
            
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:5%">#</th>
                        <th>Product Name</th>
                        <th class="text-end">Vendor Price</th>
                        <th class="text-end">Cgst + Sgst</th>
                        <th class="text-end">Quantity</th>
                         @if($hasFreeQty)
                            <th class="text-end">Free Qty</th>
                        @endif
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($purchaseOrder->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                {{ $item->product->product_name ?? 'N/A' }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item->vendor_price, 2) }}
                            </td>
                            
                            <td class="text-end">
                                {{ number_format($item->row_tax ?? 0, 2) }}%
                            </td>


                            <td class="text-end">
                                {{ $item->quantity }}
                            </td>
                            
                            @if($hasFreeQty)
                                <td class="text-end">
                                    {{ $item->free_quantity ?? 0 }}
                                </td>
                            @endif

                            <td class="text-end">
                                {{ number_format($item->amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- =======================
            TOTALS
        ======================== --}}
        <div class="row justify-content-end mt-4">
            <div class="col-md-5">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-end">Subtotal</th>
                        <td class="text-end">
                            {{ number_format($purchaseOrder->subtotal_basic, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">Product Discount ( {{ $purchaseOrder->product_discount }} %)</th>
                        <td class="text-end">
                            ₹ {{ number_format(
                                    (($purchaseOrder->subtotal_basic + $purchaseOrder->tax_total + $purchaseOrder->delivery_charges) * $purchaseOrder->product_discount  / 100 ),
                                    2
                                ) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">tax</th>
                        <td class="text-end">
                            {{ $purchaseOrder->tax_total }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-end">Delivery Charges</th>
                        <td class="text-end">
                            {{ number_format($purchaseOrder->delivery_charges, 2) }}
                        </td>
                    </tr>

                    <tr class="table-light">
                        <th class="text-end">Grand Total</th>
                        <td class="text-end fw-bold">
                            {{ number_format($purchaseOrder->grand_total, 2) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

          <div class="mt-4">
   @if ($purchaseOrder->status === 'draft' && $purchaseOrder->rejection_reason)
    <div class="alert alert-danger">
        <strong>Rejection Reason:</strong><br>
        {{ $purchaseOrder->rejection_reason }}
    </div>
@endif
</div>



        {{-- =======================
            ACTIONS
        ======================== --}}
        <div class="mt-4 text-end">
            <a href="{{ route('admin.purchase-orders.index') }}" class="btn btn-secondary">
                Back
            </a>

            @if ($purchaseOrder->status == 'draft')
                <a href="{{ route('admin.purchase-orders.edit', $purchaseOrder->id) }}"
                   class="btn btn-primary">
                    Edit
                </a>
            @endif
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
</div>


@endsection
