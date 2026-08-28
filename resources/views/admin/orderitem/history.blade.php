@extends('admin.layouts.appnew')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-11 m-auto">

                    <div class="card">
                        <div class="card-body">

<h4 class="mb-2">Rack Stock History</h4>
<p class="text-muted mb-4">
    Product : <strong>{{ $product->product_name ?? 'N/A' }}</strong>
</p>




 <div class="table-responsive">
     <table class="table table-bordered">
      <thead class="table-light">
        <thead>
            <tr>
                <th>Batch No</th>
                <th>Expiry Date</th>
                <th>Quantity</th>
                <th>Rack No</th>
                <th>Level No</th>
                <th>Slot No</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rackStocks as $stock)
                <tr>
                    <td>{{ $stock->batch_no ?? '-' }}</td>
                    <td>{{ $stock->expiry_date ?? '-' }}</td>
                    <td>{{ number_format($stock->quantity,2) }}</td>
                    <td>{{ $stock->rack_no }}</td>
                    <td>{{ $stock->level_no }}</td>
                    <td>{{ $stock->slot_no }}</td>
                    <td>{{ $stock->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No rack stock history found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
 </div>
                         


                            {{-- Back --}}
                            <div class="row mt-4">
                                <div class="col-md-4 offset-md-8 d-grid">
                                    <a href="{{ route('orderitem.details', $orderId) }}"
                                        class="btn btn-secondary">
                                            Back
                                        </a>

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
