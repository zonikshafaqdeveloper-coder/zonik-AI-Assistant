@extends('admin.layouts.appnew')

@section('content')

<div class="container-fluid">

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Reorder Quantity Calculation Report</h3>
                    </div>


 <div class="table-responsive">
<table class="table table-bordered table-striped" id="stockTable">

    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Item Name</th>
            <th>Brand Name</th>
            <th>Vendor Name</th>
            <th>Category</th>
            <th>Carton Size (Box)</th>
            <th>Purchase Price</th>

            <th>Last 30 Days Sold QTY</th>
            <th>Daily Consumption rate</th>
            <th>Weekly Consumption</th>

            <th>Stock Purchase Plan ( Weeks)</th>
            <th>ROQ Without Safety Stock</th>

            <th>OSS %</th>
            <th>NOS (ROQ)</th>

            <th>Boxes (ROQ)</th>
            <th>Investment ₹</th>
        </tr>
    </thead>

    <tbody>
        @foreach($report as $row)
        <tr 
            data-product="{{ $row['product_id'] ?? '' }}"
            data-last30="{{ $row['last30'] }}"
            data-carton="{{ $row['carton_size'] }}"
            data-price="{{ $row['price'] }}"
        >

            <td>{{ $row['index'] }}</td>
            <td>{{ $row['product'] }}</td>
            <td>{{ $row['brand'] }}</td>
            <td>{{ $row['vendor_name'] }}</td>
            <td>{{ $row['category'] }}</td>
            <td>{{ $row['carton_size'] }}</td>
            <td>₹{{ number_format($row['price'],2) }}</td>

            <td>{{ $row['last30'] }}</td>

            <td class="dcr">{{ $row['dcr'] }}</td>
            <td class="weekly">{{ $row['weekly'] }}</td>

            <td>
              <select class="form-control spp">
                @for($i=1; $i<=10; $i++)
                <option value="{{ $i }}" {{ $row['spp'] == $i ? 'selected' : '' }}>
                    {{ $i }} Week
                </option>
                @endfor
                </select>
            </td>

            <td class="roq_wo_ss">{{ $row['roq_wo_ss'] }}</td>

            <td>
               <select class="form-control oss_percent">
                @foreach([0,5,10,15,20,30,40,50,75,100] as $val)
                <option value="{{ $val }}" {{ $row['oss_percent'] == $val ? 'selected' : '' }}>
                    {{ $val }}%
                </option>
                @endforeach
                </select>
            </td>

            <td class="nos">{{ $row['nos'] }}</td>

            <td class="boxes">{{ $row['boxes'] }}</td>

            <td class="investment">
                ₹{{ number_format($row['investment'],2) }}
            </td>

        </tr>
        @endforeach
    </tbody>

</table>

</div>

                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    const rows = document.querySelectorAll("tbody tr");

    rows.forEach(function(row){

        const spp = row.querySelector(".spp");
        const oss = row.querySelector(".oss_percent");

        function calculateAndSave(){

            let product_id = row.dataset.product;

            let last30 = parseFloat(row.dataset.last30) || 0;
            let carton = parseFloat(row.dataset.carton) || 0;
            let price  = parseFloat(row.dataset.price) || 0;

            let dcr = last30 / 30;
            let weekly = dcr * 7.5;

            let sppVal = parseFloat(spp.value) || 0;
            let ossPercent = parseFloat(oss.value) || 0;

            let roq_wo_ss = weekly * sppVal;
            let nos = roq_wo_ss + (roq_wo_ss * ossPercent / 100);

            // let boxes = carton > 0 ? Math.ceil(nos / carton) : 0;
            let boxes = carton > 0 ? Math.floor(nos / carton) : 0;
            let investment = nos * price;

            row.querySelector(".dcr").innerText = Math.round(dcr);
            row.querySelector(".weekly").innerText = Math.round(weekly);
            row.querySelector(".roq_wo_ss").innerText = Math.round(roq_wo_ss);
            row.querySelector(".nos").innerText = Math.round(nos);
            row.querySelector(".boxes").innerText = boxes;
            row.querySelector(".investment").innerText = investment.toFixed(2);

            fetch("{{ route('admin.save.reorder.setting') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: product_id,
                    spp: sppVal,
                    oss_percent: ossPercent
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log("Saved:", data.message);
            })
            .catch(err => {
                console.error("Save failed:", err);
            });

        }

        spp.addEventListener("change", calculateAndSave);
        oss.addEventListener("change", calculateAndSave);

    });

});
</script>
@endsection