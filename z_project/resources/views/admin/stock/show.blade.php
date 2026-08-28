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
                                    <div class="card-header">
                                        <h4>Stock Receiving (GRN)</h4>
                                    </div>

                            <div class="card-body">
                                
  {{-- HEADER --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>GRN ID:</strong> #{{ $stockReceiving->id }}
                </div>
                <div class="col-md-4">
                    <strong>PO No:</strong>
                    {{ $stockReceiving->purchaseOrder->purchase_order_number }}
                </div>
                <div class="col-md-4">
                    <strong>Status:</strong>
                    <span class="badge bg-success">
                        {{ ucfirst($stockReceiving->status) }}
                    </span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Vendor:</strong>
                    {{ $stockReceiving->purchaseOrder->vendor->name ?? '-' }}
                </div>
                <div class="col-md-4">
                    <strong>Receipt Date:</strong>
                    {{ $stockReceiving->receipt_date }}
                </div>
                <div class="col-md-4">
                    <strong>Bill No:</strong>
                    {{ $stockReceiving->bill_no ?? '—' }}
                </div>
            </div>

            {{-- ORIGINAL BILL --}}
            @if($stockReceiving->original_bill)
                <div class="mb-3">
                    <a href="{{ asset('uploads/stock_receiving_bills/'.$stockReceiving->original_bill) }}"
                       target="_blank"
                       class="btn btn-sm btn-primary">
                        View Original Po
                    </a>
                </div>
            @endif

            <hr>

            {{-- ITEMS TABLE --}}
            <div class="table-responsive">
                @php
                $hasFreeQty = $stockReceiving->items->contains(function ($item) {
                    return ($item->free_quantity ?? 0) > 0;
                });
            @endphp
            
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>PO Qty</th>
                              @if($hasFreeQty)
                                <th>Free Qty</th>
                            @endif
                            <th>Cgst + Sgst</th>
                            <th>Received Qty</th>
                            <th>Returned Qty</th>
                            <th>To Be Returned Qty</th>
                            <th>Rate</th>
                            <th>Item Total</th>
                            <th>Batch</th>
                            <th>Expiry</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockReceiving->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->product->product_name ?? '-' }}</td>
                                <td>{{ $item->po_qty }}</td>
                                 @if($hasFreeQty)
                                        <td>{{ $item->free_quantity ?? 0 }}</td>
                                    @endif
                                <td>{{ $item->row_tax }}</td>
                                <td>{{ $item->actual_qty }}</td>
                                <td>{{ $item->returned_qty }}</td>
                                <td>{{ $item->to_be_return_qty }}</td>
                                <td>₹ {{ number_format($item->purchase_rate, 2) }}</td>
                                <!--<td>₹ {{ number_format($item->actual_qty * $item->purchase_rate, 2) }}</td>-->
                                
                                 @php
                                 $basicAmount = $item->actual_qty * $item->purchase_rate;
                                 $gstAmount = ($basicAmount * ($item->row_tax ?? 0)) / 100;
                                 $itemTotal = $basicAmount + $gstAmount;
                                 @endphp
                                <td>₹ {{ number_format($itemTotal, 2) }}</td>
                                
                                <td>{{ $item->batch_no ?? '-' }}</td>
                                <td>{{ $item->expiry_date ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- TOTALS --}}
            <div class="row mt-4">
                <div class="col-md-4 offset-md-8">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th class="text-end">Subtotal</th>
                            <td class="text-end">₹ {{ number_format($stockReceiving->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="text-end">Discount ({{ $stockReceiving->discount_percent }}%)</th>
                            <td class="text-end">
                                ₹ {{ number_format(
                                    (($stockReceiving->subtotal + $stockReceiving->tax_amount + $stockReceiving->delivery_charges) * $stockReceiving->discount_percent / 100),
                                    2
                                ) }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-end">Tax</th>
                            <td class="text-end">
                                ₹ {{
                                    number_format($stockReceiving->tax_amount)
                                }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-end">Delivery Charges</th>
                            <td class="text-end">₹ {{ number_format($stockReceiving->delivery_charges, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="text-end">Grand Total</th>
                            <th class="text-end">₹ {{ number_format($stockReceiving->grand_total, 2) }}</th>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- VENDOR BILL --}}
            @if($stockReceiving->vendorBill)
                <hr>
                <div class="mt-3">
                    <strong>Vendor Bill:</strong>
                    {{ $stockReceiving->vendorBill->bill_no }}
                    <span class="badge bg-warning">
                        {{ ucfirst($stockReceiving->vendorBill->status) }}
                    </span>
                </div>
            @endif


        <div class="mt-4 text-end">
            <a href="{{ route('admin.stock-receivings.index') }}" class="btn btn-secondary">
                Back
            </a>
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
