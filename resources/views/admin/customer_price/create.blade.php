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
                                    <h3>Create Customer Price</h3>
                                </div>


                                <form action="{{ route('customer.price.store') }}" method="POST">
@csrf

<!--<div class="card">-->
<!--<div class="card-body">-->

<div class="row mb-3">
    <div class="col-md-4">
        <label>Select Outlet Name</label>
       <select name="outlet_id" id="outletSelect" class="form-control" required>
            <option value="">-- Select Outlet --</option>
            @foreach($outlets as $outlet)
                <option value="{{ $outlet->id }}"
                        data-customer="{{ $outlet->priority }}">
                {{ $outlet->outlet_name }}

            @if($outlet->parentCustomer)
                ({{ $outlet->parentCustomer->outlet_name }})
            @endif

                </option>
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
    <th>Cost Per Item</th>
    <th>Customer Price</th>
    <th>Profit Margin</th>
</tr>
</thead>
<tbody>
@foreach($products as $product)
<tr data-product-id="{{ $product->id }}">
 
    <td>{{ $product->product_name }}</td>
    <td>{{ number_format($product->cost_per_item, 2) }}</td>
    <td>
        <input type="number"
               step="0.01"
               class="form-control price-input"
               name="prices[{{ $product->id }}]"
               data-cost="{{ $product->cost_per_item }}"
               placeholder="Enter price">
    </td>
    <td>
        <span class="profit-margin">0.00%</span>
    </td>
</tr>
@endforeach
</tbody>
</table>
</div>

<!--</div>-->
<!--</div>-->
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

    // const customerSelect = document.getElementById('customerSelect');
    const outletSelect = document.getElementById('outletSelect');
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

    outletSelect.addEventListener('change', function () {

        // const customerId = this.value;
        
        const selectedOption = this.options[this.selectedIndex];
        const customerId = selectedOption.dataset.customer;

        
        document.querySelectorAll('#productTable tbody tr').forEach(row => {
            const input = row.querySelector('.price-input');
            input.value = '';
            input.readOnly = false;
            input.classList.remove('bg-light');

            row.querySelector('.profit-margin').innerText = '0.00%';

            const note = row.querySelector('.price-note');
            if (note) note.remove();
        });

        if (!customerId) return;

        fetch(`/admin/customer-price-locks/${customerId}`)
            .then(res => res.json())
            .then(({ prices, lockedProducts }) => {

                const rowsWithPrice = [];
                const rowsWithoutPrice = [];

                document.querySelectorAll('#productTable tbody tr').forEach(row => {

                    const productId = parseInt(row.dataset.productId);
                    const input = row.querySelector('.price-input');

                    const customerPrice = prices[productId];
                    const hasPrice = customerPrice !== undefined && parseFloat(customerPrice) > 0;
                    const isLocked = lockedProducts[productId] !== undefined;

                
                    if (hasPrice || isLocked) {
                        rowsWithPrice.push(row);
                    } else {
                        rowsWithoutPrice.push(row);
                    }

                
                     if (hasPrice) {
                        input.value = customerPrice;
                        calculateMargin(row);
                    } else {
                        input.value = '';
                        row.querySelector('.profit-margin').innerText = '0.00%';
                    }

                
                    if (isLocked) {
                        input.readOnly = false;
                        input.classList.add('bg-light');

                        const offerPrice = lockedProducts[productId].offer_price;

                        if (!row.querySelector('.price-note')) {
                            const note = document.createElement('small');
                            note.className = 'price-note text-danger fw-bold d-block mt-1';

                            let noteText = `(already in price list)`;

                            if (offerPrice && parseFloat(offerPrice) > 0) {
                                noteText += ` (offer price: ${offerPrice})`;
                            } else {
                                noteText += ` (offer price not set)`;
                            }

                            note.innerText = noteText;
                            input.closest('td').appendChild(note);
                        }
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






