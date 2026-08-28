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

                                {{-- Success Message --}}
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="card-title">Live Stock</h3>

<!--<a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">-->
<!--                        Back-->
<!--                    </a>-->

                                    
                                </div>

                           
<div class="table-responsive">
                    <table class="table all-package theme-table" id="stock_table">
                        <thead class="b-shadow ">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Current Stock</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stocks as $index => $stock)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $stock->product_name ?? '-' }}</td>
                                    <td>{{ $stock->brands ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $stock->total_stock }}
                                        </span>
                                    </td>
                                   <td>{{ \Carbon\Carbon::parse($stock->updated_at)->format('d-m-Y H:i') }}</td>
                                   <td>
                    <a href="{{ route('admin.stock.product.detail', $stock->product_id) }}"
                       class="btn btn-sm btn-info">
                        View Locations
                    </a>
                </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No stock available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                              

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>





@endsection
