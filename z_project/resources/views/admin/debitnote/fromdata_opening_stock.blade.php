@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="row">
                    <div class="col-sm-12 m-auto">

                    <div class="card">
                        <div class="card-body">

<form id="debitNoteForm">
    @csrf

    <input type="hidden" id="store_url" value="{{ route('admin.debitnote.store.from.opening') }}">
    <input type="hidden" name="product_id" value="{{ $rackStock->product_id }}">
    <input type="hidden" name="batch_no" value="{{ $rackStock->batch_no }}">
    <input type="hidden" name="expiry_date" value="{{ $rackStock->expiry_date }}">

    <h5>Opening Stock Return (Debit Note)</h5>

    <p><strong>Product:</strong> {{ $product->product_name }}</p>
    <p><strong>Batch:</strong> {{ $rackStock->batch_no }}</p>
    <p><strong>Expiry:</strong> {{ $rackStock->expiry_date }}</p>
    <p><strong>Available Qty:</strong> {{ $rackStock->quantity }}</p>

    <div class="mb-3">
        <label>Vendor</label>
        <select name="vendor_id" class="form-control" required>
            <option value="">-- Select Vendor --</option>
            @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Return Qty</label>
        <input type="number" name="return_qty" id="return_qty"
               class="form-control" max="{{ $rackStock->quantity }}" required>
    </div>

    <div class="mb-3">
        <label>Rate (per unit)</label>
        <input type="number" step="0.01" name="rate" id="rate"
               class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tax %</label>
        <input type="number" step="0.01" name="tax_percent" class="form-control" value="0">
    </div>

    <div class="mb-3">
        <label>Reason</label>
        <input type="text" name="reason" class="form-control" placeholder="Reason (optional)">
    </div>

    <button type="button" id="saveDebitNote" class="btn btn-primary">
        Create Debit Note
    </button>

    <a href="{{ route('admin.expired-products') }}" class="btn btn-secondary">Back</a>
</form>

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
document.getElementById('saveDebitNote').addEventListener('click', function () {

    let qty  = document.getElementById('return_qty').value;
    let rate = document.getElementById('rate').value;

    if (!qty || parseInt(qty) <= 0) {
        Swal.fire('Error', 'Please enter return quantity', 'error');
        return;
    }
    if (!rate || parseFloat(rate) < 0) {
        Swal.fire('Error', 'Please enter a valid rate', 'error');
        return;
    }

    Swal.fire({
        title: 'Create Debit Note?',
        text: 'Opening stock will be returned to selected vendor',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Continue'
    }).then((result) => {
        if (!result.isConfirmed) return;

        let form = document.getElementById('debitNoteForm');
        let url  = document.getElementById('store_url').value;
        let formData = new FormData(form);

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name=_token]').value
            },
            body: formData
        })
        .then(res => { if (!res.ok) throw res; return res.json(); })
        .then(data => {
            Swal.fire('Success', data.message, 'success')
                .then(() => { window.location.href = "{{ route('admin.expired-products') }}"; });
        })
        .catch(async err => {
            let msg = "Something went wrong";
            if (err.json) {
                let e = await err.json();
                if (e.message) msg = e.message;
            }
            Swal.fire('Error', msg, 'error');
        });
    });
});
</script>

@endsection