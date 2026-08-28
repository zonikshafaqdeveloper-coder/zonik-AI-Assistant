@extends('admin.layouts.appnew')
@section('content')
    <div class="page-body">

        <!-- New Product Add Start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 my-5">
                    <div class="row">
                        <div class="col-sm-8 m-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>Edit Customer Price</h5>
                                    </div>
                                   
                                    <form action="{{ route('customer.price.update', $outlet->id) }}" method="POST">
@csrf
@method('PUT')

<!--<div class="card">-->
<!--<div class="card-body">-->

<h5 class="mb-3">
    Edit Prices – <strong style="font-size: 20px;">{{ $outlet->outlet_name }}</strong>
</h5>

<div class="row mb-3">
    <div class="col-md-4">
        <input type="text" id="productSearch"
               class="form-control"
               placeholder="Search product...">
    </div>

    <div class="col-md-4 offset-md-4">
        <button type="submit" class="btn btn-primary w-100">
            Update Prices
        </button>
    </div>
</div>

<div class="table-responsive" style="max-height:600px; overflow:auto;">
<table class="table table-bordered table-hover" id="productTable">
<thead class="table-dark sticky-top">
<tr>
    <th>Product</th>
    <th>Cost Per Item</th>
    <th>Customer Price</th>
    <th>Profit Margin</th>
</tr>
</thead>
<tbody>

@foreach($products as $product)
@php
    $isLocked = isset($lockedProducts[$product->id]);
    $offerPrice = $isLocked ? ($lockedProducts[$product->id]->offer_price ?? null) : null;
@endphp

<tr data-product-id="{{ $product->id }}" data-locked="{{ $isLocked ? '1' : '0' }}">
    <td>{{ $product->product_name }}</td>
    <td>{{ number_format($product->cost_per_item, 2) }}</td>
    <td>
        <input type="number"
               step="0.01"
               class="form-control price-input"
               name="prices[{{ $product->id }}]"
               value="{{ $customerPrices[$product->id] ?? '' }}"
               data-cost="{{ $product->cost_per_item }}"
               placeholder="Enter price">
                 @if($isLocked)
            <small class="price-note text-danger fw-bold d-block mt-1">
                (already in price list)
                @if($offerPrice && $offerPrice > 0)
                    (offer price: {{ $offerPrice }})
                @else
                    (offer price not set)
                @endif
            </small>
               @endif
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
        <script>
document.getElementById('productSearch').addEventListener('keyup', function () {
    let value = this.value.toLowerCase();
    document.querySelectorAll('#productTable tbody tr')
        .forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value)
                ? ''
                : 'none';
        });
});
</script>

<script>
function calculateMargin(input) {
    const cost = parseFloat(input.dataset.cost);
    const price = parseFloat(input.value);

    const profitCell = input
        .closest('tr')
        .querySelector('.profit-margin');

    if (isNaN(price) || isNaN(cost) || cost <= 0) {
        profitCell.textContent = '0.00%';
        return;
    }

    const margin = ((price - cost) / cost) * 100;
    profitCell.textContent = margin.toFixed(2) + '%';

  
    profitCell.style.color = margin >= 0 ? 'green' : 'red';
}


document.addEventListener('input', function (e) {
    if (e.target.classList.contains('price-input')) {
        calculateMargin(e.target);
    }
});


document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.price-input').forEach(input => {
        if (input.value !== '') {
            calculateMargin(input);
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

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

    const rowsWithPrice = [];
    const rowsWithoutPrice = [];

   document.querySelectorAll('#productTable tbody tr').forEach(row => {

        const priceInput = row.querySelector('.price-input');
        const priceValue = priceInput.value;
        const isLocked   = row.dataset.locked === '1';

        
        if (isLocked) {
            priceInput.readOnly = false;
            priceInput.classList.add('bg-light');

            
            if (!row.querySelector('.price-note')) {
                const note = document.createElement('small');
                note.className = 'price-note text-danger fw-bold d-block mt-1';
                note.innerText = '(already in price list)';
                priceInput.closest('td').appendChild(note);
            }
        }

        if ((priceValue && parseFloat(priceValue) > 0) || isLocked) {
            calculateMargin(row);
            rowsWithPrice.push(row);
        } else {
            rowsWithoutPrice.push(row);
        }
    });

    
    tbody.innerHTML = '';
    rowsWithPrice.forEach(row => tbody.appendChild(row));
    rowsWithoutPrice.forEach(row => tbody.appendChild(row));

  
    document.querySelectorAll('.price-input:not([readonly])').forEach(input => {
        input.addEventListener('input', function () {
            calculateMargin(this.closest('tr'));
        });
    });

});
</script>


        <!-- New Product Add End -->
    @endsection
