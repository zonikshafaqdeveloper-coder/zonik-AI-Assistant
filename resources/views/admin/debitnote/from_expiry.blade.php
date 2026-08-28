@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="row">
                    <div class="col-sm-12 m-auto">


                    {{-- Alerts --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif


                    <div class="card">
                        <div class="card-body">



<form id="debitNoteForm">
    @csrf

    <input type="hidden" id="store_url" 
           value="{{ route('admin.debitnote.store.from.expiry') }}">

    <input type="hidden" name="product_id" value="{{ $item->product_id }}">
    <input type="hidden" name="batch_no" value="{{ $item->batch_no }}">
    <input type="hidden" name="expiry_date" value="{{ $item->expiry_date }}">

    <h5>Product: {{ $item->product->product_name }}</h5>
    <p>Vendor: {{ $item->stockReceiving->vendor->name ?? '' }}</p>
    <p>Batch: {{ $item->batch_no }}</p>
    <p>Expiry: {{ $item->expiry_date }}</p>
    <p>Available Qty: {{ $rackStock->quantity }}</p>

    <div class="mb-3">
        <label>Return Qty</label>
        <input type="number"
               name="return_qty"
               id="return_qty"
               class="form-control"
               max="{{ $rackStock->quantity }}"
               required>
    </div>

    <div class="mb-3">
        <label>Reason</label>
        <input type="text"
               name="reason"
               class="form-control"
               placeholder="Reason">
    </div>

    <button type="button" id="saveDebitNote" class="btn btn-primary">
        Create Debit Note
    </button>

    <a href="{{ route('debitnote.index') }}" class="btn btn-secondary">
        Back
    </a>
</form>
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
const saveBtn = document.getElementById('saveDebitNote');

saveBtn.addEventListener('click', function () {

    let qty = document.getElementById('return_qty').value;

    if (!qty || parseInt(qty) <= 0) {
        Swal.fire('Error','Please enter return quantity','error');
        return;
    }

    Swal.fire({
        title: 'Create Debit Note?',
        text: 'Stock will be returned to vendor',
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
        .then(res => {
            if (!res.ok) throw res;
            return res.json();
        })
        .then(data => {
            Swal.fire('Success', data.message, 'success')
                .then(() => {
                    window.location.href = "{{ route('admin.near-expiry-stock') }}";
                });
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