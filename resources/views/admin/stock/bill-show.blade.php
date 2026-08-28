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
                                
                          <div class="row mb-3">
            <div class="col-md-8">
                <h3>Vendor Bill Details</h3>
                <p class="text-muted mb-0">
                    Bill No: <strong>{{ $bill->bill_no }}</strong>
                </p>
            </div>

            <div class="col-md-4 text-end">
                @if($bill->status === 'paid')
                    <span class="badge bg-success">Paid</span>
                @elseif($bill->status === 'partial')
                    <span class="badge bg-warning text-dark">Partial</span>
                @else
                    <span class="badge bg-danger">Unpaid</span>
                @endif
            </div>
        </div>

        {{-- BILL + PO INFO --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-4">
                        <strong>Vendor</strong><br>
                        {{ $bill->vendor->name ?? '-' }}
                    </div>

                    <div class="col-md-4">
                        <strong>Purchase Order</strong><br>
                        {{ $bill->stockReceiving->purchaseOrder->purchase_order_number ?? '-' }}
                    </div>

                    <div class="col-md-4">
                        <strong>GRN</strong><br>
                        
                        IGGRN-{{ str_pad($bill->stock_receiving_id, 5, '0', STR_PAD_LEFT) }}
                    </div>

                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <strong>Bill Date</strong><br>
                        {{ $bill->bill_date?->format('d-m-Y') }}
                    </div>

                    <div class="col-md-4">
                        <strong>Receipt Date</strong><br>
                        {{ $bill->stockReceiving->receipt_date }}
                    </div>

                    <div class="col-md-4">
                        <strong>GRN Status</strong><br>
                        {{ ucfirst($bill->stockReceiving->status) }}
                    </div>
                </div>
                
<div class="d-flex gap-2 flex-wrap mt-3">

    {{-- Original Stock Bill --}}
    @if(!empty($bill->original_bill))
        <a href="{{ asset('uploads/stock_bills/' . $bill->original_bill) }}"
           target="_blank"
           class="btn btn-sm btn-primary">
            View Original Bill
        </a>
    @endif


    {{-- Original Purchase Order (Stock Receiving) --}}
    @if(!empty($bill->stockReceiving?->original_bill))
        <a href="{{ asset('uploads/stock_receiving_bills/' . $bill->stockReceiving->original_bill) }}"
           target="_blank"
           class="btn btn-sm btn-info">
            View Original PO
        </a>
    @endif

</div>
            </div>
        </div>

        {{-- ITEMS --}}
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="mb-3">Received Items</h5>

                <div class="table-responsive">
                    
                     @php
                        $hasFreeQty = $bill->stockReceiving->items
                            ->pluck('free_quantity')
                            ->filter()
                            ->isNotEmpty();
                    @endphp
                    
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th class="text-end">PO Qty</th>
                                 @if($hasFreeQty)
                                    <th class="text-end">Free Qty</th>
                                @endif
                                <th class="text-end">Received Qty</th>
                                <th class="text-end">Returned Qty</th>
                                <th class="text-end">Return Reason</th>
                                <th class="text-end">To Be Returned Qty</th>
                                <th class="text-end">To Be Return Reason</th>
                                <th class="text-end">Cgst + Sgst</th>
                                <th class="text-end">Rate</th>
                                <th class="text-end">Batch</th>
                                <th class="text-end">Expiry</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($bill->stockReceiving->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->product->product_name ?? '-' }}</td>
                                    <td class="text-end">{{ $item->po_qty }}</td>
                                     @if($hasFreeQty)
                                        <td class="text-end">{{ $item->free_quantity ?? 0 }}</td>
                                    @endif
                                    <td class="text-end">{{ $item->actual_qty }}</td>
                                    <td class="text-end">{{ $item->returned_qty }}</td>
                                    <td class="text-end">
                                        {{ $item->return_reason ?? '-' }}
                                    </td>
                                    <td class="text-end">{{ $item->to_be_return_qty }}</td>
                                    <td class="text-end">
                                        {{ $item->to_be_return_reason ?? '-' }}
                                    </td>
                                    <td class="text-end">{{ $item->row_tax }}</td>
                                    <td class="text-end">₹ {{ number_format($item->purchase_rate, 2) }}</td>
                                    <td class="text-end">{{ $item->batch_no ?? '-' }}</td>
                                    <td class="text-end">{{ $item->expiry_date ?? '-' }}</td>
                                    <!--<td class="text-end">-->
                                    <!-- ₹ {{ number_format($item->actual_qty * $item->purchase_rate, 2) }}-->
                                    <!--</td>-->
                                    @php
                                    $basicAmount = $item->actual_qty * $item->purchase_rate;
                                    $gstAmount = ($basicAmount * $item->row_tax) / 100;
                                    $finalAmount = $basicAmount + $gstAmount;
                                    @endphp
                                    <td class="text-end">
                                        ₹ {{ number_format($finalAmount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TOTALS --}}
        <div class="card mb-3">
            <div class="card-body">
                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th>Subtotal</th>
                                <td class="text-end">₹ {{ number_format($bill->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Discount ({{ $bill->discount_percent, 2 }})%</th>
                                <td class="text-end">
                                    ₹ {{ number_format((($bill->subtotal + $bill->tax_amount + $bill->delivery_charges) * $bill->discount_percent / 100), 2) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Tax</th>
                                <td class="text-end">₹ {{ number_format($bill->tax_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Delivery</th>
                                <td class="text-end">₹ {{ number_format($bill->delivery_charges, 2) }}</td>
                            </tr>
                             <tr class="border-top">
                            <th>Grand Total</th>

                            @php
                                $subtotal = (float) $bill->subtotal;
                                $tax = (float) $bill->tax_amount;
                                $delivery = (float) $bill->delivery_charges;
                                $discountPercent = (float) $bill->discount_percent;

                                $baseAmount = $subtotal + $tax + $delivery;

                                $discountAmount = ($baseAmount * $discountPercent) / 100;

                                $grandTotal = $baseAmount - $discountAmount;
                            @endphp

                            <th class="text-end">
                                ₹ {{ number_format($grandTotal, 2) }}
                            </th>
                        </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

       

        {{-- ACTIONS --}}

        @if($bill->stockReceiving->status === 'rejected' && $bill->stockReceiving->rejection_reason)
        <div class="alert alert-danger">
            <strong>Rejection Reason:</strong><br>
            {{ $bill->stockReceiving->rejection_reason }}
        </div>
    @endif

   

@if($bill->stockReceiving->status === 'submitted')

<div class="card mt-3">
    <div class="card-header">
        <strong>Approve / Reject Bill</strong>
    </div>

    <div class="card-body">
        <form method="POST"
              action="{{ route('admin.stock-receivings.review.submit', $bill->stock_receiving_id) }}">
            @csrf

          <div class="mb-3">
                    <label>Status</label>

                    <select name="status"
                            id="status-rec"
                            class="form-control select2 @error('status') is-invalid @enderror"
                            required>
                        <option value="">Select</option>
                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approve</option>
                        <option value="approved_with_changes"{{ old('status') == 'approved_with_changes' ? 'selected' : '' }}>Approve with Changes </option>
                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Reject</option>
                    </select>

                    @error('status')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                        <div class="mb-3">
                            <label>
                                Reason
                                <span class="text-danger">*</span>
                                <small class="text-muted">(mandatory when rejecting or approving with changes)</small>
                            </label>

                            <textarea name="reason"
                                    class="form-control @error('reason') is-invalid @enderror"
                                    rows="3">{{ old('reason') }}</textarea>

                            @error('reason')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

            <button type="submit" class="btn btn-success">
                Submit Review
            </button>

            <a href="{{ route('admin.stock-receivings.bills') }}"
               class="btn btn-secondary">
                Back
            </a>
        </form>
    </div>
</div>
@else
 <div class="text-end">
  <a href="{{ route('admin.stock-receivings.bills') }}"
               class="btn btn-secondary">
                Back
            </a>
</div>

@endif


          

         

         
          




       
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#status-rec').select2({
        width: '100%',
        placeholder: 'Select Status',
        allowClear: true
    });
});
</script>

@endsection
