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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-11 m-auto">

                    <div class="card">
                        <div class="card-body">

                            <h3 class="mb-4">Rack Receiving – Allocate Stock Location</h3>

                            {{-- GRN Info --}}
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label class="form-label">GRN No</label>
                                    <input type="text" class="form-control"
                                           value="IGGRN-{{ str_pad($grn->id,5,'0',STR_PAD_LEFT) }}"
                                           readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Vendor</label>
                                    <input type="text" class="form-control"
                                           value="{{ $grn->purchaseOrder->vendor->name ?? '' }}"
                                           readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Receipt Date</label>
                                    <input type="date" class="form-control"
                                           value="{{ $grn->receipt_date }}"
                                           readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Bill No</label>
                                    <input type="text" class="form-control"
                                           value="{{ $grn->bill_no }}"
                                           readonly>
                                </div>
                            </div>

                           <form>

                           

                                {{-- Items Table --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle" id="rackTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>Batch</th>
                                                <th>Expiry</th>
                                                <th>Received Qty</th>
                                                <th>Bay</th>
                                                <th>Column (1–8)</th>
                                                <th>Floor (1–5)</th>
                                                <th>Store Qty</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                 <!--//$netQty = $item->actual_qty - ($item->returned_qty ?? 0);-->
                                            @foreach($grn->items as $index => $item)
                                            

                                                @php
                                               
                                                   $netQty = $item->actual_qty + $item->free_quantity;
                                                @endphp
                                                
                                                 @if($netQty <= 0)
                                                    @continue
                                                @endif

                                               <tr data-product="{{ $item->product_id }}"
    data-product-name="{{ $item->product->product_name }}">

                                                    <td>{{ $index+1 }}</td>

                                                    <td>
                                                        {{ $item->product->product_name ?? '' }}
                                                        <input type="hidden"
                                                               name="items[{{ $index }}][product_id]"
                                                               value="{{ $item->product_id }}">
                                                    </td>

                                                    <td>
                                                        {{ $item->batch_no }}
                                                        <input type="hidden"
                                                               name="items[{{ $index }}][batch_no]"
                                                               value="{{ $item->batch_no }}">
                                                    </td>

                                                    <td>
                                                        {{ $item->expiry_date }}
                                                        <input type="hidden"
                                                               name="items[{{ $index }}][expiry_date]"
                                                               value="{{ $item->expiry_date }}">
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-info">
                                                            {{ $netQty }}
                                                        </span>
                                                    </td>

<td>
    <select name="items[{{ $index }}][rack_no]" 
            class="form-control select2 rack-select w-100" required>
        <option value="">Select Rack</option>
         @foreach(['A','B','C','D','F1','F2','F3','F4','F5','F6','F7'] as $rack)
            <option value="{{ $rack }}">{{ $rack }}</option>
        @endforeach
    </select>
</td>

<td>
    <select name="items[{{ $index }}][level_no]" 
            class="form-control select2 level-select w-100" required>
        <option value="">Select Level</option>
        @for($i = 1; $i <= 10; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>
</td>

<td>
    <select name="items[{{ $index }}][slot_no]" 
            class="form-control select2 slot-select w-100" required>
        <option value="">Select Slot</option>
        @for($i = 1; $i <= 10; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>
</td>




                                                 <td>
    <input type="number"
           name="items[{{ $index }}][quantity]"
           class="form-control store-qty"
           data-net-qty="{{ $netQty }}"
           value="0"
           min="0"
           required>
</td>

<td class="text-center">
    <button type="button" class="btn btn-sm btn-success add-row">
        +
    </button>
       <button type="button" class="btn btn-sm btn-danger remove-row">−</button>
</td>

                                                </tr>
                                                 

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Actions --}}
                                <div class="row mt-4">
                                    <div class="col-md-4 offset-md-8 d-grid gap-2">
                                            <button type="button"
                                                    id="saveRackBtn"
                                                    class="btn btn-success">
                                                Save Rack Allocation
                                            </button>

                                        <a href="{{ route('admin.rack.receiving.index') }}"
                                           class="btn btn-secondary">
                                            Back
                                        </a>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
function reinitSelect2($container) {
    $container.find('select').select2({
        width: '100%'
    });
}

$(document).ready(function () {
    reinitSelect2($(document));
});

$(document).on('click', '.add-row', function () {

    const $row = $(this).closest('tr');
    const productId = $row.data('product');
    const productName = $row.data('product-name');

    const productTotalNetQty = productNetQtyMap[productId] || 0;

    let totalAllocated = 0;
    $('#rackTable tbody tr').each(function () {
        if ($(this).data('product') == productId) {
            totalAllocated += parseFloat($(this).find('.store-qty').val()) || 0;
        }
    });

    if (totalAllocated >= productTotalNetQty) {
        Swal.fire(
            'Limit Reached',
            `${productName}: All received quantity (${productTotalNetQty}) is already allocated.`,
            'warning'
        );
        return;
    }

    const $productRows = $('#rackTable tbody tr').filter(function () {
        return $(this).data('product') == productId;
    });

    // Destroy select2
    $productRows.find('select').each(function () {
        if ($(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
    });

    const newRow = $row.clone();

    newRow.find('.store-qty').val(0);
    newRow.find('select').val('');

    $productRows.last().after(newRow);

    const $updatedRows = $('#rackTable tbody tr').filter(function () {
        return $(this).data('product') == productId;
    });

    reinitSelect2($updatedRows);
});



$(document).on('click', '.remove-row', function () {

    const $row = $(this).closest('tr');
    const productId = $row.data('product');
    const productName = $row.data('product-name');

    const $productRows = $('#rackTable tbody tr').filter(function () {
        return $(this).data('product') == productId;
    });

    if ($productRows.length <= 1) {
        Swal.fire(
            'Not Allowed',
            `At least one row must remain for ${productName}.`,
            'warning'
        );
        return;
    }

    $row.remove();
});



$(document).on('input', '.store-qty', function () {

    const $row = $(this).closest('tr');
    const productId = $row.data('product');
    const productName = $row.data('product-name');

    const productTotalNetQty = productNetQtyMap[productId] || 0;

    let totalAllocated = 0;

    $('#rackTable tbody tr').each(function () {
        if ($(this).data('product') == productId) {
            totalAllocated += parseFloat($(this).find('.store-qty').val()) || 0;
        }
    });

    if (totalAllocated > productTotalNetQty) {
        Swal.fire(
            'Invalid Quantity',
            `Total stored quantity for ${productName} cannot exceed ${productTotalNetQty}`,
            'error'
        );
        $(this).val(0);
    }
});

</script>
<script>
    
$(document).on('change', '.rack-select', function () {

    const rack = $(this).val();
    const $row = $(this).closest('tr');

    const $level = $row.find('.level-select');
    const $slot  = $row.find('.slot-select');

    if (['F1','F2','F3','F4','F5','F6'].includes(rack)) {

        
        $level.val('').prop('disabled', true).removeAttr('required');
        $slot.val('').prop('disabled', true).removeAttr('required');

    } else {

      
        $level.prop('disabled', false).attr('required', true);
        $slot.prop('disabled', false).attr('required', true);
    }
});



function buildRackReceivingPayload() {

    let productTotals = {};
    let usedLocations = [];
    let items = [];

$('#rackTable tbody tr').each(function () {

    const $row = $(this);
    const productId = $row.data('product');
    const productName = $row.data('product-name');

    const rack  = $row.find('.rack-select').val();
    const level = $row.find('.level-select').val();
    const slot  = $row.find('.slot-select').val();

    const qty    = parseFloat($row.find('.store-qty').val()) || 0;
    const netQty = parseFloat($row.find('.store-qty').data('net-qty')) || 0;
    
    const isFridge = ['F1','F2','F3','F4','F5','F6'].includes(rack);

  
  if (!rack) {
            throw {
                title: 'Rack Required',
                message: `Please select Rack for Product ${productName}`
            };
        }

      
        if (!isFridge) {

            if (!level) {
                throw {
                    title: 'Level Required',
                    message: `Please select Level for Product ${productName}`
                };
            }

            if (!slot) {
                throw {
                    title: 'Slot Required',
                    message: `Please select Slot for Product ${productName}`
                };
            }
        }

  
    if (qty <= 0) {
        throw {
            title: 'Quantity Required',
            message: `Please enter quantity for Product  ${productName}`
        };
    }

  
        // const key = `${rack}-${level}-${slot}`;

        // if (usedLocations.includes(key)) {
        //     throw {
        //         title: 'Duplicate Location',
        //         message: `Location ${rack}-${level}-${slot} is already assigned. 
        //                 Please choose a different rack position for ${productName}.`
        //     };
        // }

        // usedLocations.push(key);


   if (!productTotals[productId]) {
    productTotals[productId] = {
        total: 0,
        net: productNetQtyMap[productId],
        name: productName
    };
}
    productTotals[productId].total += qty;

    items.push({
        product_id: productId,
        rack_no   : rack,
        level_no  : level,
        slot_no   : slot,
        quantity  : qty,
        batch_no   : $row.find('input[name$="[batch_no]"]').val() || null,
        expiry_date: $row.find('input[name$="[expiry_date]"]').val() || null
    });
});


    Object.keys(productTotals).forEach(pid => {
    if (productTotals[pid].total !== productTotals[pid].net) {
        throw {
            type: 'validation',
            title: 'Quantity Mismatch',
            message: `${productTotals[pid].name}: Received = ${productTotals[pid].net}, Allocated = ${productTotals[pid].total}`
        };
    }
});


    if (items.length === 0) {
        throw {
            type: 'validation',
            title: 'No Data',
            message: 'Please allocate rack quantity for at least one product'
        };
    }

    return {
        items: items
    };
}


$('#saveRackBtn').on('click', function (e) {
    e.preventDefault();

    let payload;

    try {
        payload = buildRackReceivingPayload();
    } catch (err) {
        Swal.fire(err.title || 'Error', err.message || 'Validation failed', 'warning');
        return;
    }
    Swal.fire({
        title: 'Saving Rack Allocation...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });


fetch("{{ route('admin.rack.receiving.store', $grn->id) }}", {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    body: JSON.stringify(payload)
})
.then(async res => {
    const contentType = res.headers.get("content-type");

    if (!contentType || !contentType.includes("application/json")) {
        const text = await res.text();
        throw new Error("Server returned non-JSON:\n" + text);
    }

    const json = await res.json();

    
   
    if (!res.ok && res.status !== 422) {
        throw new Error(json.message || 'Server error');
    }

    return json;
})
.then(res => {
    Swal.close();

    if (res.success) {
        Swal.fire('Success', res.message, 'success')
            .then(() => window.location.href = res.redirect_url);
    } else if (res.already_allocated) {
        Swal.fire('Already Allocated', res.message || 'Rack allocation already saved for this GRN.', 'warning')
            .then(() => {
                window.location.href = res.redirect_url || "{{ route('admin.rack.receiving.index') }}";
            });
    } else {
        Swal.fire('Failed', res.message || 'Unable to save rack allocation', 'error');
    }
})
.catch(err => {
    Swal.close();
    console.error(err);
    Swal.fire('Server Error', 'Backend returned invalid response. Check console.', 'error');
});
   

});

let productNetQtyMap = {};

$('#rackTable tbody tr').each(function () {
    const productId = $(this).data('product');
    const netQty = parseFloat($(this).find('.store-qty').data('net-qty')) || 0;

    if (!productNetQtyMap[productId]) {
        productNetQtyMap[productId] = 0;
    }
    productNetQtyMap[productId] += netQty;
});

</script>
@endsection
