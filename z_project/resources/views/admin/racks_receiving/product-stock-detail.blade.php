@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-11 m-auto">

                    <div class="card">
                        <div class="card-body">

                            <h3 class="mb-4"> {{ $product->product_name ?? '' }} – Stock Locations</h3>
 <div class="table-responsive">
                           <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                     <th>Vendor</th>
                    <th>GRN Date</th>
                    <th>Bill Date</th>
                    <th>Batch</th>
                    <th>Expiry</th>
                    <th>Bay</th>
                    <th>Column</th>
                    <th>Floor</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($stocks as $i => $row)
                    @php $total += $row->quantity; @endphp
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>
                            {{ $row->vendor_name ?? 'Opening Stock' }}
                        </td>
                         <td>
                            {{ $row->receipt_date ? \Carbon\Carbon::parse($row->receipt_date)->format('d-m-Y') : '-' }}
                        </td>

                        <td>
                            {{ $row->bill_date ? \Carbon\Carbon::parse($row->bill_date)->format('d-m-Y') : '-' }}
                        </td>
                        <td>{{ $row->batch_no ?? '-' }}</td>
                        <td>{{ $row->expiry_date ?? '-' }}</td>
                        <td>{{ $row->rack_no }}</td>
                        <td>{{ $row->level_no }}</td>
                        <td>{{ $row->slot_no }}</td>
                        <td>{{ $row->quantity }}</td>
                    </tr>
                @endforeach
                <tr class="fw-bold">
                    <td colspan="9" class="text-end">Total</td>
                    <td>{{ $total }}</td>
                </tr>
            </tbody>
        </table>
         </div>
                         


                            {{-- Back --}}
                            <div class="row mt-4">
                                <div class="col-md-4 offset-md-8 d-grid">
                                    <a href="{{ route('admin.rack.live-location') }}"
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
