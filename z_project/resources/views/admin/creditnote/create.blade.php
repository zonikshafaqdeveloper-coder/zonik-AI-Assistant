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
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-body">

                                <div class="card-header-2 mb-3">
                                   <h4>Create Credit Note - Order #{{ $order->order_id }}</h4>
                                </div>



<form id="creditNoteForm">
@csrf
<input type="hidden" id="store_url" value="{{ route('creditnote.store',$order->id) }}">

<table class="table table-bordered">

<thead>
<tr>
    <th>Product</th>
    <th>Rate</th>
    <th>Delivered Qty</th>
    <th>Return Qty</th>
    <th>Reason</th>
</tr>
</thead>

<tbody>

@foreach($order->items as $item)

<tr>
<td>{{ $item->product->product_name }}</td>

<td style="width:140px">
    <input type="number"
           name="items[{{ $item->id }}][rate]"
           class="form-control"
           min="0"
           step="0.01"
           value="{{ $item->offer_price }}">
</td>

<td>{{ $item->quantity }}</td>

<td style="width:120px">
    <input type="number"
           name="items[{{ $item->id }}][return_qty]"
           class="form-control returnQty"
           min="0"
           max="{{ $item->quantity }}"
           value="0">
</td>

<td>
    <input type="text"
           name="items[{{ $item->id }}][reason]"
           class="form-control"
           placeholder="Reason">
</td>
</tr>

@endforeach

</tbody>
</table>

<button type="button" id="saveCreditNote" class="btn btn-primary" disabled>
    Save Credit Note
</button>
<a href="{{ route('creditnote.index') }}" class="btn btn-secondary">Back</a>

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
const saveBtn = document.getElementById('saveCreditNote');
const qtyInputs = document.querySelectorAll('.returnQty');

function checkQty() {
    let hasReturn = false;

    qtyInputs.forEach(el => {
        if (parseInt(el.value) > 0) hasReturn = true;
    });

    saveBtn.disabled = !hasReturn;
}

qtyInputs.forEach(el => el.addEventListener('input', checkQty));

checkQty();

saveBtn.addEventListener('click', function () {

    let hasReturn = false;
    let invalidRate = false;

    qtyInputs.forEach(el => {
        if (parseInt(el.value) > 0) hasReturn = true;
    });

    if (!hasReturn) {
        Swal.fire('Error','Please enter at least one return quantity','error');
        return;
    }
    
    document.querySelectorAll('input[name*="[rate]"]').forEach(el => {
    if (parseFloat(el.value) <= 0) invalidRate = true;
    });

    if (invalidRate) {
        Swal.fire('Error','Rate must be greater than 0','error');
        return;
    }

    Swal.fire({
        title: 'Create Credit Note?',
        text: 'Stock and payment will be updated.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Continue'
    }).then((result) => {

        if (!result.isConfirmed) return;

        let form = document.getElementById('creditNoteForm');
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
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "{{ route('creditnote.index') }}";
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
