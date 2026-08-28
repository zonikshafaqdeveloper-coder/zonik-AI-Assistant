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
                                

                             <div class="d-flex justify-content-between align-items-center mb-3">
               <h4>Purchase Order Details ( {{ ucfirst($po->purchase_order_number) }} ) </h4>
                            @php
                        if ($po->status === 'sent') {
                            $badgeClass = 'bg-warning';
                            $label = 'Pending';
                        } elseif ($po->status === 'approved') {
                            $badgeClass = 'bg-success';
                            $label = 'Approved';
                         }elseif ($po->status === 'received') {
                            $badgeClass = 'bg-success';
                            $label = 'Approved';     
                        } elseif ($po->status === 'draft' && $po->rejection_reason) {
                            $badgeClass = 'bg-danger';
                            $label = 'Rejected';
                        } else {
                            $badgeClass = 'bg-secondary';
                            $label = ucfirst($po->status);
                        }
                    @endphp
                    
                    <span class="badge {{ $badgeClass }}">
                        {{ $label }}
                    </span>
                </div>

    {{-- Vendor Info --}}
    <div class="card mb-4">
        <div class="card-header"><strong>Vendor Information</strong></div>
        <div class="card-body row">
            <div class="col-md-3"><strong>Vendor:</strong> {{ $po->vendor->name }}</div>
            <div class="col-md-3"><strong>Location:</strong> {{ $po->location }}</div>
            <div class="col-md-3"><strong>GST No:</strong> {{ $po->vendor->gst_number }}</div>
             <div class="col-md-3 mt-2">
                <strong>Payment Terms:</strong>
                <span class="badge bg-info">
                    {{ strtoupper($po->payment_method ?? 'N/A') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Line Items --}}
    <div class="card mb-4">
        <div class="card-header"><strong>Line Items</strong></div>
        <div class="card-body table-responsive">
            
        @php
            $hasFreeQty = $po->items->contains(function ($item) {
                return $item->free_quantity > 0;
            });
        @endphp
        
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Qty</th>
                         @if($hasFreeQty)
                            <th>Free Qty</th>
                        @endif
                        <th>Rate</th>
                        <th>Tax %</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($po->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                             @if($hasFreeQty)
                                <td>
                                    {{ $item->free_quantity ?? 0 }}
                                </td>
                            @endif
                            <td>{{ number_format($item->vendor_price, 2) }}</td>
                            <td>{{ $item->row_tax }}%</td>
                            <td>{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Totals --}}
    <div class="card mb-4">
        <div class="card-header"><strong>Totals & Tax</strong></div>
        <div class="card-body row">
            <div class="col-md-3"><strong>Subtotal:</strong> {{ number_format($po->subtotal_basic, 2) }}</div>
            <div class="col-md-2"><strong>Total Tax:</strong> {{ number_format($po->tax_total, 2) }}</div>
            <div class="col-md-2"><strong>Discount ( {{ number_format($po->product_discount ?? 0, 2) }} %):</strong> {{ number_format(
                                    (($po->subtotal_basic + $po->tax_total + $po->delivery_charges) * $po->product_discount  / 100 ),
                                    2
                                ) }} </div>
            <div class="col-md-2"><strong>Delivery Charges:</strong> {{ number_format($po->delivery_charges ?? 0, 2) }}</div>
            <div class="col-md-3"><strong>Grand Total:</strong> {{ number_format($po->grand_total, 2) }}</div>
        </div>
    </div>

    {{-- Rejection Reason --}}
      @if ($po->status === 'draft' && $po->rejection_reason)
        <div class="alert alert-danger">
            <strong>Rejection Reason:</strong><br>
            {{ $po->rejection_reason }}
        </div>
    @endif

    {{-- Approve / Reject Form --}}
    @if ($po->status === 'sent')
        <div class="card">
            <div class="card-header"><strong>Approve / Reject</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.purchase-orders.review.submit', $po->id) }}">
                    @csrf

                   <div class="mb-3">
                    <label>Status</label>

                    <select name="status"
                            id="status-rec"
                            class="form-control select2 @error('status') is-invalid @enderror"
                            required>
                        <option value="">Select</option>
                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approve</option>
                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Reject</option>
                    </select>

                    @error('status')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                        <div class="mb-3" id="reason-wrapper">
                            <label>
                                Reason
                                <span class="text-danger">*</span>
                                <small class="text-muted">(required if rejected)</small>
                            </label>

                            <textarea name="reason"
                                     id="reason"
                                    class="form-control @error('reason') is-invalid @enderror"
                                    rows="3">{{ old('reason') }}</textarea>

                            @error('reason')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                    <button type="submit" class="btn btn-success">Submit Review</button>
                    <a href="{{ route('admin.purchase-orders.approval') }}" class="btn btn-secondary">
                        Back
                    </a>
                </form>
            </div>
        </div>
        @else

          <div class="text-end">
        <a href="{{ route('admin.purchase-orders.approval') }}"
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

    const $reasonWrapper = $('#reason-wrapper');
    const $reasonField   = $('#reason');

    function toggleReason() {
        const status = $('#status-rec').val();

        if (status === 'approved') {
            $reasonWrapper.hide();
            $reasonField.val('').prop('required', false);
        } 
        else if (status === 'rejected') {
            $reasonWrapper.show();
            $reasonField.prop('required', true);
        } 
        else {
            $reasonWrapper.hide();
            $reasonField.prop('required', false);
        }
    }

    
    toggleReason();

    
    $('#status-rec').on('select2:select select2:clear', function () {
        toggleReason();
    });

});
</script>



@endsection
