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
                                     
        <h4 class="mb-3">Credit Note List</h4>
                                    </div>

                                    <form method="GET" class="mb-3 d-flex gap-2">

                                        <input type="month"
                                            name="month"
                                            value="{{ request('month') }}"
                                            class="form-control"
                                            style="max-width:200px;">

                                        <button type="submit" class="btn btn-primary">
                                            Filter
                                        </button>

                                        <a href="{{ route('creditnote.index') }}" class="btn btn-secondary">
                                            Reset
                                        </a>

                                    </form>

                                   
                                    <div class="table-responsive">
                                        <table id="category" class="table table-bordered">
                                            <thead class="b-shadow">
                                                 <tr>
                                                    <th class="text-center">Invoice No</th>
                                                    <th class="text-center">Invoice Date</th>
                                                    <th class="text-center">Customer Name</th>
                                                    <th class="text-center">Company Name</th>
                                                    <th class="text-center">Outlet Name</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>

                                               <tbody>
                @forelse($orders as $order)
                    <tr>

                        {{-- Invoice No --}}
                        <td class="text-center">
                            {{ $order->invoice_id }}
                        </td>
                        
                        <td class="text-center">
                            {{ $order->invoice_date }}
                        </td>

                        {{-- User Name --}}
                                   <td class="text-center">

                                {{ $order->user ? $order->user->name : 'Unknown User' }}
                            
                        </td>
                        <td class="text-center">

                                {{ $order->user ? $order->mainuser->outlet_name : 'Unknown User' }}
                            
                        </td>

                    <td class="text-center">{{ $order->user->outlet_name ?? 'N/A' }}</td>

                        {{-- Download Button --}}
                        <td class="text-center">
                           @if($order->returnInvoice)

                                {{-- Download only if created --}}
                                <a href="{{ route('creditnote.download', $order->id) }}"
                                class="btn btn-success" target="_blank">
                                    Download PDF
                                </a>

                            @else

                                {{-- Create only if not created --}}
                                <a href="{{ route('creditnote.create', $order->id) }}"
                                class="btn btn-warning">
                                    Create Credit Note
                                </a>

                            @endif

                                                    </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No credit notes available
                        </td>
                    </tr>
                @endforelse
            </tbody>

                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        </html>
                        @endsection
