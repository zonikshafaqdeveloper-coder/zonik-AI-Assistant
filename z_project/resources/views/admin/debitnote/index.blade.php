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
        <table class="table table-striped table-bordered" id="nonRunningTable">
            <thead class="table-dark">
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
    @forelse($rows as $row)
        <tr>
            <td class="text-center">
                {{ $row['type'] === 'grn' ? $row['id'] : 'DN-' . $row['id'] }}
            </td>

            <td class="text-center">
                {{ $row['grn_no'] }}
                @if($row['type'] === 'opening')
                    <span class="badge bg-info">Opening</span>
                @endif
            </td>

            <td class="text-center">{{ $row['po_no'] }}</td>
            <td class="text-center">{{ $row['bill_no'] }}</td>
            <td class="text-center">{{ $row['supplier'] }}</td>

            <td class="text-center">
                {{ \Carbon\Carbon::parse($row['date'])->format('d-m-Y') }}
            </td>

            <td class="text-center">
                @if($row['debit_notes']->count() > 0)
                    @foreach($row['debit_notes'] as $note)
                        <a href="{{ route('debitnote.download', $note->id) }}"
                           class="btn btn-success btn-sm mb-1">
                           Download {{ $note->debit_note_no }}
                        </a><br>
                    @endforeach
                @elseif($row['create_url'])
                    <a href="{{ $row['create_url'] }}" class="btn btn-warning">
                        Create Debit Note
                    </a>
                @else
                    -
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">No debit notes available</td>
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
