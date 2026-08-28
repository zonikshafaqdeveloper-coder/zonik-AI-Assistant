@extends('admin.layouts.appnew')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.select2-container {
    width: 100% !important;
}

.select2-container--default .select2-selection--single {
    height: 44px !important;
    display: flex !important;
    align-items: center !important;
    background-color: #e9ecef !important;
    box-sizing: border-box;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 44px !important;
    padding-left: 12px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 44px !important;
}

.select2-container--default .select2-selection--single .select2-selection__clear {
    display: none;
}

</style>
<div class="page-body">
<div class="container-fluid page-body-wrapper">
<div class="main-panel">
<div class="content-wrapper">

<div class="card">
<div class="card-body">

<h3 class="card-title mb-3">
    Stock Adjustment – {{ $product->product_name }}
</h3>

<div class="table-responsive">
<table class="table table-bordered align-middle" id="adjustmentTable">
    <thead class="table-light">
        <tr>
            <th>Bay</th>
            <th>Column</th>
            <th>Floor</th>
            <th>Current Qty</th>
            <th>Adjustment Type</th>
            <th>Adjustment Qty</th>
            <th>Remarks</th>
           <th width="80">Remove</th>

        </tr>
    </thead>
    <tbody>
        @foreach($rackStocks as $row)
       <tr 
  data-id="{{ $row->id }}"
  data-rack="{{ $row->rack_no }}"
  data-level="{{ $row->level_no }}"
  data-slot="{{ $row->slot_no }}"
>

            
            <td>
            <select class="form-control select2 rack" disabled>
                @foreach(['A','B','C','D','F1','F2','F3','F4','F5','F6','F7'] as $rack)
                    <option value="{{ $rack }}"
                        {{ $row->rack_no == $rack ? 'selected' : '' }}>
                        {{ $rack }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" class="rack-hidden" value="{{ $row->rack_no }}">
        </td>
        
        <td>
            <select class="form-control select2 level" disabled>
                @for($i=1; $i<=10 ;$i++)
                    <option value="{{ $i }}"
                        {{ $row->level_no == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
            <input type="hidden" class="level-hidden" value="{{ $row->level_no }}">
        </td>
        
        <td>
            <select class="form-control select2 slot" disabled>
                @for($i=1; $i<=10; $i++)
                    <option value="{{ $i }}"
                        {{ $row->slot_no == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
            <input type="hidden" class="slot-hidden" value="{{ $row->slot_no }}">
        </td>

            <td>
                <input type="text" class="form-control current-qty"
                       value="{{ $row->quantity }}" readonly>
            </td>

            <td>
                <select class="form-control select2  adjustment-type">
                    <option value="">Select</option>
                    <option value="IN">Increase (IN)</option>
                    <option value="OUT">Decrease (OUT)</option>
                </select>
            </td>

            <td>
                <input type="number" step="0.01" class="form-control adjustment-qty">
            </td>

            <td>
                <input type="text" class="form-control remarks" placeholder="Reason">
            </td>

            <td class="text-center">
    <button type="button" class="btn btn-sm remove-row" style="background: red; color: #fff;">
         <i class="fa fa-trash"></i>
    </button>
</td>

        </tr>
        @endforeach
    </tbody>
</table>

<div class="text-end mt-3">
    <button type="button" id="saveAllAdjustments" class="btn btn-primary">
        Save Adjustments
    </button>
</div>

</div>

</div>
</div>

</div>
</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {

    // Init Select2
    $('.select2').select2({
        width: '100%',
        placeholder: 'Select'
    });

});


/* Remove Row */
$(document).on('click', '.remove-row', function () {

    let $row = $(this).closest('tr');
    let totalRows = $('#adjustmentTable tbody tr').length;

    if (totalRows <= 1) {
        Swal.fire({
            title: 'Action Not Allowed',
            text: 'At least one row must remain.',
            icon: 'warning'
        });
        return;
    }

    // Destroy Select2 before removing
    $row.find('select').each(function () {
        if ($(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
    });

    $row.remove();
});


/* Save All Adjustments */
$('#saveAllAdjustments').on('click', function () {

    let items = [];
    let isValid = true;
    let errorMsg = '';

    $('#adjustmentTable tbody tr').each(function (index) {

        let $row = $(this);
         let rackStockId = $row.data('id');
         let rack  = $row.find('.rack').prop('disabled')
           ? $row.find('.rack-hidden').val()
           : $row.find('.rack').val();

        let level = $row.find('.level').prop('disabled')
                ? $row.find('.level-hidden').val()
                : $row.find('.level').val();

        let slot  = $row.find('.slot').prop('disabled')
                ? $row.find('.slot-hidden').val()
                : $row.find('.slot').val();
        // Old values from blade (important)
        let oldRack  = $row.data('rack');
        let oldLevel = $row.data('level');
        let oldSlot  = $row.data('slot');

        let current = parseFloat($row.find('.current-qty').val()) || 0;

        let type    = $row.find('.adjustment-type').val();
        let qtyRaw  = $row.find('.adjustment-qty').val();
        let qty     = parseFloat(qtyRaw) || 0;
        let remarks = $row.find('.remarks').val().trim();

        // Detect changes
        let locationChanged = (rack != oldRack || level != oldLevel || slot != oldSlot);
        let qtyChanged      = (type || qtyRaw || remarks);

        // Nothing changed at all → ignore
        if (!locationChanged && !qtyChanged) {
            return true;
        }

        // If quantity adjustment is used, validate all 3
        if (qtyChanged) {

            if (!type || !qtyRaw || !remarks) {
                isValid = false;
                errorMsg = `Row ${index + 1}: Adjustment Type, Quantity and Remarks are all required.`;
                return false;
            }

            if (qty <= 0) {
                isValid = false;
                errorMsg = `Row ${index + 1}: Quantity must be greater than 0.`;
                return false;
            }

            if (type === 'OUT' && qty > current) {
                isValid = false;
                errorMsg = `Row ${index + 1}: Quantity cannot exceed Current Quantity.`;
                return false;
            }

        } else {
            // Only location change, no quantity change
            type = 'TRANSFER';
            qty  = 0;

            if (!remarks) {
                remarks = 'Rack/Slot location changed';
            }
        }

        items.push({
            rack_stock_id: rackStockId,
            rack_no: rack,
            level_no: level,
            slot_no: slot,

            old_rack_no: oldRack,
            old_level_no: oldLevel,
            old_slot_no: oldSlot,

            adjustment_type: type,
            quantity: qty,
            remarks: remarks
        });

    });

    if (!isValid) {
        Swal.fire('Validation Error', errorMsg, 'error');
        return;
    }

    if (items.length === 0) {
        Swal.fire({
            title: 'Nothing to Save',
            text: 'No adjustment or location changes detected.',
            icon: 'info'
        });
        return;
    }

    Swal.fire({
        title: 'Confirm Save',
        text: 'Do you want to save all stock adjustments?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Save'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('admin.stock-adjustment.store') }}",
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    product_id: "{{ $product->id }}",
                    items: items
                },
                beforeSend: function () {
                    Swal.fire({
                        title: 'Saving...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },
                success: function (res) {
                    Swal.fire('Success', res.message, 'success')
                        .then(() => location.reload());
                },
                error: function (xhr) {
                    Swal.fire(
                        'Error',
                        xhr.responseJSON?.message || 'Something went wrong',
                        'error'
                    );
                }
            });

        }

    });

});
</script>



@endsection
