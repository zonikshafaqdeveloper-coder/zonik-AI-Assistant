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
                        <h3 class="mb-0">Reorder Point Calculation Report</h3>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="stockTable">

                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Item Name</th>
                                    <th>Brand Name</th>
                                    <th>Vendor Name</th>
                                    <th>Carton Size (Box)</th>
                                    <th>Category</th>
                                    <th>Live Stock Qty</th>
                                    <th>Purchase Price</th>
                                    <th>Last 30 Days Sold QTY</th>
                                    <th>Daily Consumption rate</th>
                                    <th>Supplier lead time</th>
                                    <th>ROP Without Safety Stock</th>
                                    <th>LSS %</th>
                                    <th>ROP with Safety Stock(NOS)</th>
                                    <th>ROP with Safety Stock(BOX)</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($report as $i => $row)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $row['product'] }}</td>
                                    <td>{{ $row['brand'] }}</td>
                                    <td>{{ $row['vendor_name'] }}</td>
                                    <td>{{ $row['carton_size'] }}</td>
                                    <td>{{ $row['category'] }}</td>
                                    <td>{{ $row['stock'] }}</td>
                                    <td>{{ $row['purchase_price'] }}</td>

                                    <td>{{ $row['cq'] }}</td>
                                    <td>{{ $row['adc'] }}</td>
                                    <td>{{ $row['slt'] }}</td>

                                    <td class="rop">{{ $row['rop_without'] }}</td>

                                    <td>
                                        <select class="lss_percent"
                                            data-rop="{{ $row['rop_without_raw'] }}"
                                            data-carton="{{ $row['carton_size'] }}"
                                            data-stock="{{ $row['stock'] }}"
                                            data-adc="{{ $row['adc_raw'] }}"
                                            data-product="{{ $row['id'] }}"
                                            data-vendor="{{ $row['vendor_id'] }}">

                                            @foreach([0,5,10,15,20,30,40,50,75,100] as $val)
                                            <option value="{{ $val }}" {{ ($row['lss_percent'] ?? 0) == $val ? 'selected' : '' }}>
                                                {{ $val }}%
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="nos">{{ $row['nos'] }}</td>
                                    <td class="boxes">{{ $row['boxes'] }}</td>

                                 <td>
                                        @if($row['status'] == 'CRITICAL')
                                            <span class="badge bg-danger">CRITICAL</span>
                                        @elseif($row['status'] == 'REORDER')
                                            <span class="badge" style="background-color:#b02a37;">REORDER</span>
                                        @elseif($row['status'] == 'WATCH')
                                            <span class="badge bg-warning text-dark">WATCH</span>
                                        @elseif($row['status'] == 'CAREFUL')
                                            <span class="badge" style="background-color:#fd7e14;">CAREFUL</span>
                                        @else
                                            <span class="badge bg-success">OK</span>
                                        @endif
                                    </td>

                                    <td class="action">
                                        @if(in_array($row['status'], ['REORDER', 'CRITICAL']))
                                        <button class="btn btn-danger btn-sm addToDraftPo"
                                            data-product="{{ $row['id'] }}"
                                            data-vendor="{{ $row['vendor_id'] }}"
                                            data-qty="{{ $row['nos_raw'] }}">
                                            ADD TO PO
                                        </button>
                                        @endif
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

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.lss_percent').forEach(function(dropdown){

        dropdown.addEventListener('change', function(){

            let row = this.closest("tr");

            let rop = parseFloat(this.dataset.rop);
            let carton = parseFloat(this.dataset.carton);
            let stock = parseFloat(this.dataset.stock);
            let adc = parseFloat(this.dataset.adc);
            let percent = parseFloat(this.value);

            let product_id = this.dataset.product;
            let vendor_id  = this.dataset.vendor;

            let nosRaw = (percent / 100) * rop + rop; // unrounded, matches PHP
            let nos = Math.round(nosRaw);             // rounded, for display

            let boxes = carton > 0 ? Math.floor(nosRaw / carton) : 0;

            function format(val){
                return parseFloat(val.toFixed(2));
            }

            fetch("{{ route('admin.save.lss') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: product_id,
                    vendor_id: vendor_id,
                    lss_percent: percent
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log(data.message);
            });

            row.querySelector(".nos").innerText = format(nos);
            row.querySelector(".boxes").innerText = boxes;

            let statusHTML = '';
            let actionHTML = '';

            if (adc <= 0) {
                statusHTML = '<span class="badge bg-success">OK</span>';
            } else {
                let diffPercent = nosRaw > 0 ? ((stock - nosRaw) / nosRaw) * 100 : 0;

                if (diffPercent > 50) {
                    statusHTML = '<span class="badge bg-success">OK</span>';
                } else if (diffPercent > 20) {
                    statusHTML = '<span class="badge" style="background-color:#fd7e14;">CAREFUL</span>';
                } else if (diffPercent >= 0) {
                    statusHTML = '<span class="badge bg-warning">WATCH</span>';
                } else if (diffPercent >= -20) {
                    statusHTML = '<span class="badge bg-danger">REORDER</span>';
                    actionHTML = `<button class="btn btn-danger btn-sm addToDraftPo"
                        data-product="${product_id}"
                        data-vendor="${vendor_id}"
                        data-qty="${nos}">
                        ADD TO PO
                    </button>`;
                } else {
                    statusHTML = '<span class="badge bg-dark">CRITICAL</span>';
                    actionHTML = `<button class="btn btn-danger btn-sm addToDraftPo"
                        data-product="${product_id}"
                        data-vendor="${vendor_id}"
                        data-qty="${nos}">
                        ADD TO PO
                    </button>`;
                }
            }

            row.querySelector(".status").innerHTML = statusHTML;
            row.querySelector(".action").innerHTML = actionHTML;

        });

    });

});

/* CLICK EVENT (IMPORTANT) */
document.addEventListener('click', function(e){

    if(e.target.classList.contains('addToDraftPo')){

        let btn = e.target;

        let product_id = btn.dataset.product;
        let vendor_id  = btn.dataset.vendor;
        let qty        = btn.dataset.qty;

        Swal.fire({
            title: "Confirm?",
            text: "Add to Draft PO?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes"
        }).then((res)=>{

            if(res.isConfirmed){

                fetch("{{ route('admin.po.addDraftItem') }}",{
                    method:'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'X-CSRF-TOKEN':'{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id,
                        vendor_id,
                        quantity: qty
                    })
                })
                .then(res=>res.json())
                .then(data=>{
                    Swal.fire("Success", data.message, "success")
                        .then(()=>location.reload());
                });

            }

        });

    }

});

</script>

@endsection