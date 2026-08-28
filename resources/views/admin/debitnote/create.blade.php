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

                            {{-- Header --}}
                            <div class="card-header-2 mb-3">
                               <h4>
                                   Create Debit Note - GRN #{{ str_pad($receiving->id,4,'0',STR_PAD_LEFT) }}
                               </h4>
                            </div>


                            {{-- FORM --}}
                            <form id="debitNoteForm">
                                @csrf

                                <input type="hidden"
                                       id="store_url"
                                       value="{{ route('debitnote.store',$receiving->id) }}">

                                <table class="table table-bordered">

                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Received Qty</th>
                                        <th>Return Qty</th>
                                        <th>Reason</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    @foreach($receiving->items as $item)

                                        <tr>
                                            {{-- Product --}}
                                            <td>
                                                {{ $item->product->product_name }}
                                            </td>

                                            {{-- Received Qty --}}
                                            <td>
                                                {{ $item->actual_qty }}
                                            </td>

                                            {{-- Return Qty --}}
                                            <td style="width:120px">
                                                <input type="number"
                                                       name="items[{{ $item->id }}][return_qty]"
                                                       class="form-control returnQty"
                                                       min="0"
                                                       max="{{ $item->actual_qty }}"
                                                       value="0">
                                            </td>

                                            {{-- Reason --}}
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


                                {{-- Buttons --}}
                                <button type="button"
                                        id="saveDebitNote"
                                        class="btn btn-primary"
                                        disabled>
                                    Save Debit Note
                                </button>

                                <a href="{{ route('debitnote.index') }}"
                                   class="btn btn-secondary">
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

{{-- JS SAME AS CREDIT NOTE --}}

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const saveBtn = document.getElementById('saveDebitNote');
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

    qtyInputs.forEach(el => {
        if (parseInt(el.value) > 0) hasReturn = true;
    });

    if (!hasReturn) {
        Swal.fire('Error','Please enter at least one return quantity','error');
        return;
    }

    Swal.fire({
        title: 'Create Debit Note?',
        text: 'Vendor return will be recorded.',
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
                    window.location.href = "{{ route('debitnote.index') }}";
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
