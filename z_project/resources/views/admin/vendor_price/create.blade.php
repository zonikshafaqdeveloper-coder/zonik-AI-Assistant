@extends('admin.layouts.appnew')
@section('content')
<div class="page-body">





    <!-- New Product Add Start -->
    <div class="container-fluid">

    
        <div class="row">
            <div class="col-12 my-5">
                <div class="row">
                    <div class="col-sm-8 m-auto">

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
                                    <h3>Create Vendor Price</h3>
                                </div>

<form action="{{ route('vendor.price.store') }}" method="POST">
@csrf

<div class="card">
<div class="card-body">

<div class="row mb-3">
    <div class="col-md-4">
        <label>Select Vendors</label>
        <select name="vendor_id" id="vendorSelect" class="form-control" required>
            <option value="">-- Select Vendors --</option>
            @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label>Search Product</label>
        <input type="text" id="productSearch" class="form-control" placeholder="Search product...">
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">
            Save Prices
        </button>
    </div>
</div>

<div class="table-responsive" style="max-height:600px; overflow:auto;">
<table class="table table-bordered table-hover" id="productTable">
<thead class="table-dark sticky-top">
<tr>
    <th>Product Name</th>
    <!--<th>Purchase Item</th>-->
    <th>Vendor Price</th>
    <!--<th>Profit Margin</th>-->
</tr>
</thead>
<tbody>
@foreach($products as $product)
<tr data-product-id="{{ $product->id }}">
    <td>{{ $product->product_name }}</td>

    <!--<td>{{ number_format($product->cost_per_item, 2) }}</td>-->

    <td>
        <input type="number"
               step="0.01"
               class="form-control price-input"
               name="prices[{{ $product->id }}]"
               data-cost="{{ $product->cost_per_item }}"
               placeholder="Enter price">
    </td>

    <td style="display:none">
        <span class="profit-margin">0.00%</span>
    </td>
</tr>
@endforeach
</tbody>

</table>
</div>

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
    

</div>
<script>
document.getElementById('productSearch').addEventListener('keyup', function () {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll('#productTable tbody tr');

    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(value)
            ? ''
            : 'none';
    });
});
</script>

<script>
document.addEventListener('input', function (e) {
    if (!e.target.classList.contains('price-input')) return;

    const priceInput = e.target;
    const cost = parseFloat(priceInput.dataset.cost);
    const price = parseFloat(priceInput.value);

    const profitCell = priceInput
        .closest('tr')
        .querySelector('.profit-margin');

    if (isNaN(price) || isNaN(cost) || cost <= 0) {
        profitCell.textContent = '0.00%';
        return;
    }

    const margin = ((price - cost) / cost) * 100;
    profitCell.textContent = margin.toFixed(2) + '%';
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const vendorSelect = document.getElementById('vendorSelect');
    const tbody = document.querySelector('#productTable tbody');

    function calculateMargin(row) {
        const cost  = parseFloat(row.querySelector('.price-input')?.dataset.cost) || 0;
        const price = parseFloat(row.querySelector('.price-input')?.value) || 0;

        let marginText = '0.00%';

        if (cost > 0 && price > 0) {
            marginText = (((price - cost) / cost) * 100).toFixed(2) + '%';
        }

        row.querySelector('.profit-margin').innerText = marginText;
    }

    vendorSelect.addEventListener('change', function () {

        const vendorId = this.value;


        document.querySelectorAll('#productTable tbody tr').forEach(row => {
            const input = row.querySelector('.price-input');
            input.value = '';
            row.querySelector('.profit-margin').innerText = '0.00%';
        });

        if (!vendorId) return;

        fetch(`/admin/vendor-price-locks/${vendorId}`)
            .then(res => res.json())
            .then(({ prices }) => {

                const rowsWithPrice = [];
                const rowsWithoutPrice = [];

                document.querySelectorAll('#productTable tbody tr').forEach(row => {

                    const productId = row.dataset.productId;
                    const input = row.querySelector('.price-input');

                    if (prices[productId] !== undefined) {
                        input.value = prices[productId];
                        calculateMargin(row);
                        rowsWithPrice.push(row);
                    } else {
                        rowsWithoutPrice.push(row);
                    }
                });

    
                tbody.innerHTML = '';
                rowsWithPrice.forEach(r => tbody.appendChild(r));
                rowsWithoutPrice.forEach(r => tbody.appendChild(r));
            });
    });

    
    document.querySelectorAll('.price-input').forEach(input => {
        input.addEventListener('input', function () {
            calculateMargin(this.closest('tr'));
        });
    });

});
</script>

@endsection






