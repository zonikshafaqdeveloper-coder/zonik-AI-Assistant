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
                                    <h3 class="card-title mb-0">Stock Transfer — {{ $product->product_name }}</h3>
                                    <!-- <a href="{{ route('admin.stock-opening') }}" class="btn btn-secondary">
                                        Back
                                    </a> -->
                                </div>


                                <div class="table-responsive">

<table class="table table-bordered align-middle" id="openingStockTable">
        <thead class="table-light">


<tr>
<th>FROM Bay</th>
<th>FROM Column</th>
<th>FROM Floor</th>
<th>Available Qty</th>

<th>TO Bay</th>
<th>TO Column</th>
<th>TO Floor</th>

<th>Transfer Qty</th>
<th>Remarks</th>
</tr>
</thead>

<tbody>

@foreach($rackStocks as $row)
<tr>

{{-- FROM LOCATION (READ ONLY) --}}
<td><strong>{{ $row->rack_no }}</strong></td>
<td><strong>{{ $row->level_no }}</strong></td>
<td><strong>{{ $row->slot_no }}</strong></td>

<td>{{ number_format($row->quantity, 2) }}</td>


{{-- TO BAY --}}
<td>
<select class="form-control select2 to_rack" required>
    <option value="">Select Bay</option>
    @foreach(['A','B','C','D','F1','F2','F3','F4','F5','F6','F7'] as $rack)
        <option value="{{ $rack }}">{{ $rack }}</option>
    @endforeach
</select>
</td>


{{-- TO COLUMN --}}
<td>
<select class="form-control select2 to_level" required>
    <option value="">Select Column</option>
    @for($i=1;$i<=10;$i++)
        <option value="{{ $i }}">{{ $i }}</option>
    @endfor
</select>
</td>


{{-- TO FLOOR --}}
<td>
<select class="form-control select2 to_slot" required>
    <option value="">Select Floor</option>
    @for($i=1;$i<=10;$i++)
        <option value="{{ $i }}">{{ $i }}</option>
    @endfor
</select>
</td>


{{-- QTY --}}
<td>
<input type="number"
       class="qty form-control"
       max="{{ $row->quantity }}"
       step="0.01"
       placeholder="Qty">
</td>


{{-- REMARKS --}}
<td>
<input type="text"
       class="remarks form-control"
       placeholder="Optional">
</td>


{{-- Hidden source --}}
<input type="hidden" class="rack_stock_id" value="{{ $row->id }}">
<input type="hidden" class="from_rack"  value="{{ $row->rack_no }}">
<input type="hidden" class="from_level" value="{{ $row->level_no }}">
<input type="hidden" class="from_slot"  value="{{ $row->slot_no }}">

</tr>
@endforeach

</tbody>
</table>
</div>


<div class="text-end mt-3">
<button id="saveTransfer" class="btn btn-success">
Save Transfer
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


{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(document).ready(function () {

    $('.select2').select2({
        width: '100%',
        placeholder: 'Select'
    });

});



$(document).off('click', '#saveTransfer')
.on('click', '#saveTransfer', function () {

    let items = [];
    let hasError = false;

    $('#openingStockTable tbody tr').each(function () {

        let qty = parseFloat($(this).find('.qty').val()) || 0;

        // Skip rows with no qty
        if (qty <= 0) return true;

        let toRack  = $(this).find('.to_rack').val();
        let toLevel = $(this).find('.to_level').val();
        let toSlot  = $(this).find('.to_slot').val();

        if (!toRack || !toLevel || !toSlot) {

            Swal.fire({
                icon: 'warning',
                title: 'Destination Required',
                text: 'Please select Bay / Column / Floor.'
            });

            hasError = true;
            return false; // stop loop
        }

        items.push({
            rack_stock_id: $(this).find('.rack_stock_id').val(),
            from_rack:  $(this).find('.from_rack').val(),
            from_level: $(this).find('.from_level').val(),
            from_slot:  $(this).find('.from_slot').val(),
            to_rack:    toRack,
            to_level:   toLevel,
            to_slot:    toSlot,
            quantity:   qty,
            remarks:    $(this).find('.remarks').val()
        });

    });

    if (hasError) return;

    if (items.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'Nothing to Transfer',
            text: 'Enter quantity to transfer.'
        });
        return;
    }

    Swal.fire({
        title: 'Confirm Transfer',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Transfer'
    }).then((result) => {

        if (!result.isConfirmed) return;

        $.ajax({
            url: "{{ route('admin.stock-transfer.store') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: "{{ $product->id }}",
                items: items
            },

            beforeSend: function () {
                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },

            success: function (res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Transfer Completed',
                    text: res.message
                }).then(() => location.reload());
            },

            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Transfer Failed',
                    text: xhr.responseJSON?.message || 'Error'
                });
            }
        });

    });

});
</script>

@endsection