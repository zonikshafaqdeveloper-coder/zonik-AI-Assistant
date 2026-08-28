@extends('admin.layouts.appnew')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
table {
    table-layout: fixed;
    width: 100%;
}

td {
    overflow: hidden;
}

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

                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="card-title mb-0">Add Opening Stock</h3>
                                    <!-- <a href="{{ route('admin.stock-opening') }}" class="btn btn-secondary">
                                        Back
                                    </a> -->
                                </div>

                            

                                   <div class="table-responsive">
    <table class="table table-bordered align-middle" id="openingStockTable">
        <thead class="table-light">
            <tr>
                <th style="width:26rem;">Product <span class="text-danger">*</span></th>
                <th>Batch No <span class="text-danger">*</span></th>
                <th>Expiry Date <span class="text-danger">*</span></th>
                <th>Quantity <span class="text-danger">*</span></th>
                <th>Cost Price <span class="text-danger">*</span></th>
                <th>Bay <span class="text-danger">*</span></th>
                <th>Column <span class="text-danger">*</span></th>
                <th>Floor <span class="text-danger">*</span></th>
                <th width="120" class="text-center">
                    <button type="button" class="btn btn-sm" id="addRow"  style="background: #e97457; color: #fff;">
                        + Add Row
                    </button>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                  <select name="items[0][product_id]" 
        class="form-control select2 product-select" required>
    <option value="">Select Product</option>
    @foreach($products as $product)
        <option value="{{ $product->id }}"
                data-cost="{{ $product->cost_per_item }}">
            {{ $product->product_name }}
        </option>
    @endforeach
</select>


                </td>
                  

                <td>
                    <input type="text" name="items[0][batch_no]" class="form-control">
                </td>

                <td>
                    <input type="date" name="items[0][expiry_date]" class="form-control">
                </td>

                <td>
                    <input type="number" step="0.01" name="items[0][quantity]" 
                           class="form-control text-end" required>
                </td>

                 <td>
    <input type="number" step="0.01"
           name="items[0][unit_cost]"
           class="form-control text-end cost-price"
           required>
</td>


                {{-- Rack --}}
                <td>
                    <select name="items[0][rack_no]" 
                            class="form-control select2 rack-select" required>
                        <option value="">Select Bay</option>
                         @foreach(['A','B','C','D','F1','F2','F3','F4','F5','F6','F7'] as $rack)
                            <option value="{{ $rack }}">{{ $rack }}</option>
                        @endforeach
                    </select>
                </td>

                {{-- Level --}}
                <td>
                    <select name="items[0][level_no]" 
                            class="form-control select2 level-select" required>
                        <option value="">Select Column</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </td>

                {{-- Slot --}}
                <td>
                    <select name="items[0][slot_no]" 
                            class="form-control select2 slot-select" required>
                        <option value="">Select Floor</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </td>

                <td class="text-center">
                    <button type="button" class="btn btn-sm remove-row"  style="background: red; color: #fff;">
                       <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="mt-3 d-flex justify-content-end gap-2">
    
    <button type="button" id="disposeOpeningStock" class="btn btn-danger">
        Damaged Stock
    </button>

    <button type="submit" id="saveOpeningStock" class="btn btn-primary">
        Save Opening Stock
    </button>

</div>


                                

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
let rowIndex = 1;

function isFlatRack(rack) {
    return ['F1','F2','F3','F4','F5','F6'].includes(rack);
}

$(document).ready(function () {

    /* ============================
       INIT SELECT2
    ============================ */
    initSelect2();

    /* ============================
       PRODUCT CHANGE → AUTO COST
    ============================ */
    $(document).on('change', '.product-select', function () {

        let row = $(this).closest('tr');
        let selected = $(this).find(':selected');

        let cost = parseFloat(selected.data('cost')) || 0;

        row.find('.cost-price').val(cost.toFixed(2));
    });

});


/* ============================
   RACK CHANGE → HANDLE LEVEL/SLOT
============================ */
$(document).on('change', '[name*="[rack_no]"]', function () {

    let row = $(this).closest('tr');
    let rack = $(this).val();

    let levelField = row.find('[name*="[level_no]"]');
    let slotField  = row.find('[name*="[slot_no]"]');

    if (isFlatRack(rack)) {
        levelField.prop('required', false).val('');
        slotField.prop('required', false).val('');

        levelField.closest('td').hide();
        slotField.closest('td').hide();
    } else {
        levelField.prop('required', true);
        slotField.prop('required', true);

        levelField.closest('td').show();
        slotField.closest('td').show();
    }
});

/* ============================
   INIT SELECT2 FUNCTION
============================ */
function initSelect2(context = document) {
    $(context).find('.select2').each(function () {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
                width: '100%',
                placeholder: 'Select'
            });
        }
    });
}


/* ============================
   ADD NEW ROW
============================ */
$(document).on('click', '#addRow', function () {

    let row = `
    <tr>
        <td>
            <select name="items[${rowIndex}][product_id]"
                    class="form-control select2 product-select w-100" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                            data-cost="{{ $product->cost_per_item }}">
                        {{ $product->product_name }}
                    </option>
                @endforeach
            </select>
        </td>

        <td>
            <input type="text" name="items[${rowIndex}][batch_no]" class="form-control">
        </td>

        <td>
            <input type="date" name="items[${rowIndex}][expiry_date]" class="form-control">
        </td>

        <td>
            <input type="number" step="0.01" name="items[${rowIndex}][quantity]"
                   class="form-control text-end" required>
        </td>

        <td>
            <input type="number" step="0.01" name="items[${rowIndex}][unit_cost]"
                   class="form-control cost-price text-end" required>
        </td>

        <td>
            <select name="items[${rowIndex}][rack_no]" class="form-control select2 w-100" required>
                <option value="">Select Rack</option>
                 @foreach(['A','B','C','D','F1','F2','F3','F4','F5','F6','F7'] as $rack)
                    <option value="{{ $rack }}">{{ $rack }}</option>
                @endforeach
            </select>
        </td>

        <td>
            <select name="items[${rowIndex}][level_no]" class="form-control select2 w-100" required>
                <option value="">Select Level</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </td>

        <td>
            <select name="items[${rowIndex}][slot_no]" class="form-control select2 w-100" required>
                <option value="">Select Slot</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </td>

        <td class="text-center">
            <button type="button" class="btn btn-sm remove-row" style="background: red; color: #fff;" >
               <i class="fa fa-trash"></i>
            </button>
        </td>
    </tr>
    `;

    $('#openingStockTable tbody').append(row);

    initSelect2('#openingStockTable tbody tr:last');

    rowIndex++;
});


/* ============================
   REMOVE ROW
============================ */
$(document).on('click', '.remove-row', function () {

    let totalRows = $('#openingStockTable tbody tr').length;

    if (totalRows <= 1) {
        Swal.fire({
            title: 'Action Not Allowed',
            text: 'At least one row is required.',
            icon: 'warning'
        });
        return;
    }

    $(this).closest('tr').remove();
});
</script>
<script>
/* ============================
   SAVE OPENING STOCK
============================ */
$('#saveOpeningStock').on('click', function () {
    
      let invalidField = null;

    $('#openingStockTable tbody')
       .find('input[required], select[required]').each(function () {

    let name = $(this).attr('name');
    let row = $(this).closest('tr');
    let rack = row.find('[name*="[rack_no]"]').val();

    if (isFlatRack(rack) && (name.includes('[level_no]') || name.includes('[slot_no]'))) {
        return true; // skip validation
    }

    if (!$(this).val()) {
        invalidField = $(this);
        return false;
    }
});

    if (invalidField) {
        Swal.fire('Validation Error', 'Please fill all required fields.', 'error');
        invalidField.focus();
        return;
    }


    let isValid = true;
    let rackMap = {};
    let items = [];
    let errorMsg = '';

    $('#openingStockTable tbody tr').each(function (index) {

        let product = $(this).find('[name*="[product_id]"]').val();
        let batch   = $(this).find('[name*="[batch_no]"]').val();
        let expiry  = $(this).find('[name*="[expiry_date]"]').val();
        let qty     = parseFloat($(this).find('[name*="[quantity]"]').val()) || 0;
        let cost    = parseFloat($(this).find('[name*="[unit_cost]"]').val()) || 0;
        let rack    = $(this).find('[name*="[rack_no]"]').val();
        let level   = $(this).find('[name*="[level_no]"]').val();
        let slot    = $(this).find('[name*="[slot_no]"]').val();

        function isFlatRack(rack) {
    return ['F1','F2','F3','F4','F5','F6'].includes(rack);
}

/* Required validation */
if (!product || !rack) {
    isValid = false;
    errorMsg = `Row ${index + 1}: Product and Rack are required.`;
    return false;
}

/* Conditional validation */
if (!isFlatRack(rack) && (!level || !slot)) {
    isValid = false;
    errorMsg = `Row ${index + 1}: Level and Slot are required.`;
    return false;
}
       
        /* Required field validation */
        // if (!product || !rack || !level || !slot) {
        //     isValid = false;
        //     errorMsg = `Row ${index + 1}: Product, Bay, Column and Floor are required.`;
        //     return false;
        // }

        if (qty <= 0) {
            isValid = false;
            errorMsg = `Row ${index + 1}: Quantity must be greater than 0.`;
            return false;
        }

        if (cost <= 0) {
            isValid = false;
            errorMsg = `Row ${index + 1}: Cost Price must be greater than 0.`;
            return false;
        }

        /* Duplicate Rack-Level-Slot validation */
        // let rackKey = rack + '-' + level + '-' + slot;
        // if (rackMap[rackKey]) {
        //     isValid = false;
        //     errorMsg = `Duplicate Bay location found: Bay ${rack}, Column ${level}, Floor ${slot}.`;
        //     return false;
        // }
        // rackMap[rackKey] = true;

        items.push({
            product_id: product,
            batch_no: batch,
            expiry_date: expiry,
            quantity: qty,
            unit_cost: cost,
            rack_no: rack,
            level_no: level,
            slot_no: slot
        });
    });

    if (!isValid) {
        Swal.fire('Validation Error', errorMsg, 'error');
        return;
    }

    Swal.fire({
        title: 'Confirm Save',
        text: 'Do you want to save this opening stock?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Save'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('admin.stock-opening.store') }}",
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
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
                        .then(() => {
                            if (res.redirect_url) {
                                window.location.href = res.redirect_url;
                            } else {
                                location.reload();
                            }
                        });
                },
                error: function (xhr) {
                    Swal.fire(
                        'Error',
                        xhr.responseJSON?.message || 'Something went wrong while saving.',
                        'error'
                    );
                }
            });

        }

    });

});
</script>
<script>
    /* ============================
   DISPOSE OPENING STOCK
============================ */
$('#disposeOpeningStock').on('click', function () {

    let invalidField = null;

    $('#openingStockTable tbody')
        .find('input[required], select[required]')
        .each(function () {
            if (!$(this).val()) {
                invalidField = $(this);
                return false;
            }
        });

    if (invalidField) {
        Swal.fire('Validation Error', 'Please fill all required fields.', 'error');
        invalidField.focus();
        return;
    }

    let isValid = true;
    let rackMap = {};
    let items = [];
    let errorMsg = '';

    $('#openingStockTable tbody tr').each(function (index) {

        let product = $(this).find('[name*="[product_id]"]').val();
        let batch   = $(this).find('[name*="[batch_no]"]').val();
        let expiry  = $(this).find('[name*="[expiry_date]"]').val();
        let qty     = parseFloat($(this).find('[name*="[quantity]"]').val()) || 0;
        let cost    = parseFloat($(this).find('[name*="[unit_cost]"]').val()) || 0;
        let rack    = $(this).find('[name*="[rack_no]"]').val();
        let level   = $(this).find('[name*="[level_no]"]').val();
        let slot    = $(this).find('[name*="[slot_no]"]').val();

        if (!product || !rack || !level || !slot) {
            isValid = false;
            errorMsg = `Row ${index + 1}: Product, Rack, Level and Slot are required.`;
            return false;
        }

        if (qty <= 0) {
            isValid = false;
            errorMsg = `Row ${index + 1}: Quantity must be greater than 0.`;
            return false;
        }

        if (cost <= 0) {
            isValid = false;
            errorMsg = `Row ${index + 1}: Cost Price must be greater than 0.`;
            return false;
        }

        // let rackKey = rack + '-' + level + '-' + slot;
        // if (rackMap[rackKey]) {
        //     isValid = false;
        //     errorMsg = `Duplicate rack location: ${rackKey}`;
        //     return false;
        // }
        // rackMap[rackKey] = true;

        items.push({
            product_id: product,
            batch_no: batch,
            expiry_date: expiry,
            quantity: qty,
            unit_cost: cost,
            rack_no: rack,
            level_no: level,
            slot_no: slot
        });
    });

    if (!isValid) {
        Swal.fire('Validation Error', errorMsg, 'error');
        return;
    }

    // 🔥 Ask Reason
    Swal.fire({
        title: 'Dispose Stock',
        input: 'textarea',
        inputLabel: 'Reason (Optional)',
        inputPlaceholder: 'Enter reason for disposal...',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Dispose',
        confirmButtonColor: '#d33'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('admin.disposals.bulkOpeningDispose') }}",
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    items: items,
                    reason: result.value
                },

                beforeSend: function () {
                    Swal.fire({
                        title: 'Processing...',
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
                        xhr.responseJSON?.error || 'Something went wrong',
                        'error'
                    );
                }

            });

        }

    });

});
</script>





@endsection

