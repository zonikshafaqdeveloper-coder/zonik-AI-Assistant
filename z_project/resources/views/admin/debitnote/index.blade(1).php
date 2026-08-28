@extends('admin.layouts.appnew')
@section('content')
<style>
    td{
        text-transform: capitalize;

    }
    td:nth-child(9){
        white-space: nowrap;
    }
</style>
<div class="page-body">
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper ">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif
                                    <div class="d-flex justify-content-between">
                                     
       
        <h4>Debit Notes (Vendor Returns)</h4>
                                    </div>

                                    <form method="GET" class="mb-3 d-flex gap-2">

                                    <input type="month"
                                        name="month"
                                        value="{{ request('month') }}"
                                        class="form-control"
                                        style="max-width:200px;">

                                    <button type="submit" class="btn btn-primary">Filter</button>

                                    <a href="{{ route('debitnote.index') }}" class="btn btn-secondary">Reset</a>

                                </form>



 <div class="table-responsive">
        <table class="table table-bordered" id="category">
            <thead class="b-shadow">
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">GRN No</th>
                    <th class="text-center">PO No</th>
                    <th class="text-center">Bill No</th>
                    <th class="text-center">Supplier Name</th>
                    <th class="text-center">Receipt Date</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($receivings as $grn)
                    <tr>
                        <td class="text-center">{{ $grn->id }}</td>

                        <td class="text-center">
                            GRN-{{ str_pad($grn->id, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        
                        <td class="text-center">
                            {{ $grn->purchaseOrder->purchase_order_number ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $grn->vendorBill->bill_no ?? $grn->bill_no ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ $grn->vendor->name ?? '-' }}
                        </td>

                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($grn->receipt_date)->format('d-m-Y') }}
                        </td>

                       <td class="text-center">
    @if($grn->debitNotes->count() > 0)

        @foreach($grn->debitNotes as $note)
            <a href="{{ route('debitnote.download', $note->id) }}"
               class="btn btn-success btn-sm mb-1">
               Download {{ $note->debit_note_no }}
            </a><br>
        @endforeach

    @else
        <a href="{{ route('debitnote.create', $grn->id) }}"
           class="btn btn-warning">
           Create Debit Note
        </a>
    @endif
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            No debit notes available
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
</div>

 

@endsection
