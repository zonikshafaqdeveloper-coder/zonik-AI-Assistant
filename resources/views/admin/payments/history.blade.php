@extends('admin.layouts.appnew')
@section('content')
<style>
    span.text-danger, .text-danger span{
        color: #dc3545 !important;
    }
</style>
<div class="page-body">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif

                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="card-title">Payment History - Order {{ $order->order_id }}</h3>
                                </div>

                                @if(!$payment || $histories->isEmpty())
                                <div class="alert alert-warning" role="alert">
                                    <i class="fa fa-exclamation-triangle"></i> No payment records found for this order.
                                </div>
                                @else
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="payments-table">
                                        <thead class="b-shadow">
                                            <tr>
                                                <th>#</th>
                                                <th>Amount</th>
                                                <th>Mode</th>
                                                <th>Source</th>
                                                <th>Reference</th>
                                                <th>Proof</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($histories as $h)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>₹{{ number_format($h->paid_amount, 2) }}</td>
                                                <td>{{ $h->payment_mode }}</td>
                                                <td>
                                                    @if($h->source === 'razorpay')
                                                        <span class="badge badge-primary">Razorpay</span>
                                                    @else
                                                        <span class="badge badge-primary">Backend</span>
                                                    @endif
                                                </td>
                                                 <td>{{ $h->reference ?? 'N/A'}}</td>
                                                <td>
                                                    @if($h->documents)
                                                        @foreach($h->documents as $doc)
                                                            <a href="{{ asset('storage/' . $doc) }}" target="_blank" class="btn btn-sm btn-info mb-1">
                                                                <i class="fa fa-file"></i> View Document {{ $loop->iteration }}
                                                            </a>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($h->created_at)->format('d M Y, h:i A') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection