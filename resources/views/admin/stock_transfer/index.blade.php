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
                                    <h3 class="card-title mb-0">Stock Transfer</h3>
                                    <!-- <a href="{{ route('admin.stock-opening') }}" class="btn btn-secondary">
                                        Back
                                    </a> -->
                                </div>

                             {{-- Success Message --}}
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif




  <div class="table-responsive">
                                    <table class="table all-package theme-table" id="stockTable">
                                        <thead class="b-shadow">
<tr>
<th>#</th>
<th>Product</th>
<th>Total Qty</th>
<th>Action</th>
</tr>
</thead>

<tbody>
@foreach($products as $i => $row)
<tr>
<td>{{ $i+1 }}</td>
<td>{{ $row->product->product_name ?? 'N/A' }}</td>
<td>{{ $row->total_qty }}</td>
<td>
<a href="{{ route('admin.stock-transfer.create', $row->product_id) }}"
   class="btn btn-primary btn-sm">
   Transfer
</a>
</td>
</tr>
@endforeach
</tbody>
</table>

</div>
              </div> {{-- card-body --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>




@endsection