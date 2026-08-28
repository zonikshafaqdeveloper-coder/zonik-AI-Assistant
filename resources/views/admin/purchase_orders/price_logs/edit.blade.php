@extends('admin.layouts.appnew')

@section('content')

<div class="page-body">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">

                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="mb-0">Edit Customer Prices</h4>

                                    {{-- Back Button --}}
                                    <button class="btn btn-secondary" id="backBtn">
                                        ← Back
                                    </button>
                                </div>

                                <form id="priceForm">
                                    @csrf

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">

                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Customer</th>
                                                    <th>Price (₹)</th>
                                                    <th>Customer Price</th>
                                                    <th>Cost Per Item</th>
                                                    <th>Profit Margin</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($customers as $index => $row)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>

                                                    <td>
                                                        {{ $row->outlet->outlet_name ?? '-' }}
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            step="0.01"
                                                            class="form-control price-input"
                                                            name="prices[{{ $row->id }}]"
                                                            value="{{ $row->product_price }}"
                                                            required>
                                                    </td>

                                                     <td>
                                                   <input type="number"
                                                   step="0.01"
                                                   class="form-control selling-price"
                                                   name="prices[{{ $row->id }}]"
                                                   placeholder="Enter price">
                                                   </td>

                                                    <td>
                                                    <input type="number"
                                                    step="0.01"
                                                    class="form-control cost-price"
                                                    value="{{ $newCost }}"
                                                    readonly>
                                                    <input type="hidden"
                                                    name="costs[{{ $row->id }}]"
                                                    value="{{ $newCost }}">

                                                    </td>

                                                  

                                                  <td>
                                                  <span class="margin-text text-success">0.00%</span>
                                                  </td>
                                                </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>

                                    <div class="mt-3 d-flex justify-content-end">
                                        <button type="button" class="btn btn-success" id="saveBtn">
                                            Save Changes
                                        </button>
                                    </div>

                                </form>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const saveBtn = document.getElementById('saveBtn');
const backBtn = document.getElementById('backBtn');
const form = document.getElementById('priceForm');

/*
|--------------------------------------------------------------------------
| MARGIN CALCULATION
|--------------------------------------------------------------------------
*/
const rows = document.querySelectorAll('tbody tr');

rows.forEach(row => {

    const costInput = row.querySelector('.cost-price');
    const priceInput = row.querySelector('.selling-price');
    const marginText = row.querySelector('.margin-text');

    if (!costInput || !priceInput || !marginText) return;

    const calculateMargin = () => {

        const cost = Number(costInput.value) || 0;
        const price = Number(priceInput.value) || 0;

        marginText.classList.remove('text-success', 'text-danger');

        if (cost > 0 && price > 0) {
            const margin = ((price - cost) / cost) * 100;

            marginText.innerText = margin.toFixed(2) + '%';

            if (margin < 0) {
                marginText.classList.add('text-danger');
            } else {
                marginText.classList.add('text-success');
            }

        } else {
            marginText.innerText = '0.00%';
            marginText.classList.add('text-danger');
        }
    };

    priceInput.addEventListener('input', calculateMargin);
    costInput.addEventListener('input', calculateMargin);

    calculateMargin();
});


/*
|--------------------------------------------------------------------------
| SAVE BUTTON
|--------------------------------------------------------------------------
*/
saveBtn.addEventListener('click', function () {

    let invalid = false;

    document.querySelectorAll('.selling-price').forEach(el => {
        if (!el.value || parseFloat(el.value) <= 0) invalid = true;
    });

    if (invalid) {
        Swal.fire('Error', 'Customer price must be greater than 0', 'error');
        return;
    }

    Swal.fire({
        title: 'Save Changes?',
        text: 'Customer prices will be updated.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Save'
    }).then((result) => {

        if (!result.isConfirmed) return;

        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        let url = "{{ url('/price-log/update/'.$log->id) }}";
        let formData = new FormData(form);

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": window.Laravel.csrfToken,
                "Accept": "application/json"
            },
            body: formData
        })
   .then(async (res) => {

    if (!res.ok) {
        const errorText = await res.text();
        throw new Error(errorText || "Server Error");
    }

    return res.json();
    })
        .then(data => {
            Swal.fire('Success', data.message, 'success')
                .then(() => {
                    window.location.href = "{{ route('admin.price.logs') }}";
                });
        })
        .catch(err => {
            Swal.fire('Error', err.message, 'error');
        });

    });

});


/*
|--------------------------------------------------------------------------
| BACK BUTTON
|--------------------------------------------------------------------------
*/
backBtn.addEventListener('click', function () {

    Swal.fire({
        title: 'Discard changes?',
        text: 'Unsaved changes will be lost',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Go Back'
    }).then((result) => {

        if (result.isConfirmed) {
            window.location.href = "{{ route('admin.price.logs') }}";
        }

    });

});
</script>

@endsection