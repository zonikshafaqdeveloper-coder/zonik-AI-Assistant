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
                                <h3 class="card-title mb-0">Opening Stock</h3>
                            
                                <a href="{{ route('admin.stock-opening.create') }}"
                                   class="btn btn-primary">
                                    + Add Stock Opening
                                </a>
                            </div>
                            
                          
                            <div class="row mb-4">
                                <div class="col-md-12">
                            
                               
                            
                                        {{-- Export Button --}}
                                        <a href="{{ route('admin.stock-opening.export') }}"
                                           class="btn btn-dark">
                                            Export Stock
                                        </a>
                            
                                
                            
                                </div>
                            </div>


                                <div class="table-responsive">
                                    <table class="table all-package theme-table" id="stockTable">
                                        <thead class="b-shadow">
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Product</th>
                                                <th>Movement Type</th>
                                                <th class="text-end">Quantity</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($openings as $index => $row)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $row->created_at->format('d-m-Y') }}</td>
                                                    <td>{{ $row->product->product_name ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            {{ $row->reference_type }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        {{ number_format($row->quantity, 2) }}
                                                    </td>
                                                    <td>{{ $row->remarks }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        No opening stock records found.
                                                    </td>
                                                </tr>
                                            @endforelse
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
