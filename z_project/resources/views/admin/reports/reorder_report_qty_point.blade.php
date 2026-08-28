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
                        <h3 class="mb-0">REORDER POINT AND QTY REPORT</h3>
                    </div>

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped" id="stockTable">

                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Item Name</th>
                                    <th>Brand Name</th>
                                    <th>Vendor Name</th>
                                    <th>Carton Size (Box)</th>
                                    <th>Category</th>
                                    <th>Live Stock Qty</th>

                                    <th>Last 30 Days</th>
                                    <th>Daily Consumption</th>

                                    <th>NOS (ROP with Safety Stock)</th>
                                    <th>BOX (ROP with Safety Stock)</th>
                                    <th>Condition</th>

                                    <th>NOS (ROQ)</th>
                                    <th>BOX (ROQ)</th>
                                    <th>Investment</th>

                                    <th>Action</th>
                                    <th>Scheme</th>
                                    <th>Live Stock Location</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($report as $row)
                                <tr>
                                    <td>{{ $row['id'] }}</td>
                                    <td>{{ $row['product'] }}</td>
                                    <td>{{ $row['brand'] }}</td>
                                    <td>{{ $row['vendor_name'] }}</td>
                                    <td>{{ $row['carton_size'] }}</td>
                                    <td>{{ $row['category'] }}</td>
                                    <td>{{ $row['stock'] }}</td>

                                    <td>{{ $row['last_30_days'] }}</td>
                                    <td>{{ $row['daily_consumption'] }}</td>

                                    <td>{{ $row['rop_nos'] }}</td>
                                    <td>{{ $row['rop_boxes'] }}</td>

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

                                    <td>{{ $row['roq_nos'] }}</td>
                                    <td>{{ $row['roq_boxes'] }}</td>

                                    <td>₹{{ number_format($row['investment'],2) }}</td>

                                    <td class="action">
                                        @if(in_array($row['status'], ['REORDER', 'CRITICAL']))
                                        <button class="btn btn-danger btn-sm addToDraftPo"
                                            data-product="{{ $row['product_id'] }}"
                                            data-vendor="{{ $row['vendor_id'] }}"
                                            data-qty="{{ $row['roq_nos'] }}">
                                            ADD TO PO
                                        </button>
                                        @endif
                                    </td>

                                    <td>
                                        <input type="text"
                                            class="form-control schemeInput"
                                            placeholder="Enter scheme"
                                            value="{{ $row['scheme'] }}"
                                            data-product="{{ $row['product_id'] }}"
                                            data-vendor="{{ $row['vendor_id'] }}">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.stock.product.detail', $row['product_id']) }}"
                                           class="btn btn-sm btn-info"
                                           target="_blank">
                                            VIEW
                                        </a>
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
document.querySelectorAll('.addToDraftPo').forEach(function(button){

    button.addEventListener('click', function(){

        let btn = this;
        let product_id = btn.dataset.product;
        let vendor_id  = btn.dataset.vendor;
        let qty        = btn.dataset.qty;

        Swal.fire({
            title: "Are you sure?",
            text: "Add this item to Draft Purchase Order?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Add",
        }).then((result)=>{

            if(result.isConfirmed){

                btn.disabled = true;

                fetch("{{ route('admin.po.addDraftItem') }}",{
                    method:'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'X-CSRF-TOKEN':'{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id:product_id,
                        vendor_id:vendor_id,
                        quantity:qty
                    })
                })
                .then(res=>res.json())
                .then(data=>{
                    Swal.fire({
                        icon:'success',
                        title:'Success',
                        text:data.message
                    }).then(()=>{
                        location.reload();
                    });
                })
                .catch(err => {
                    btn.disabled = false;
                    Swal.fire({
                        icon:'error',
                        title:'Error',
                        text:'Something went wrong!'
                    });
                });

            }

        });

    });

});

document.querySelectorAll('.schemeInput').forEach(input => {

    input.addEventListener('keyup', function(){

        let product_id = this.dataset.product;
        let vendor_id  = this.dataset.vendor;
        let scheme     = this.value;

        fetch("{{ route('admin.scheme.save') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: product_id,
                vendor_id: vendor_id,
                scheme: scheme
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log('Saved:', data);
        })
        .catch(err => {
            console.error('Error:', err);
        });

    });

});
</script>
@endsection