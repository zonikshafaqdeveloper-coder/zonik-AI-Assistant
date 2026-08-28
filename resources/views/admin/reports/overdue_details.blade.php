@extends('admin.layouts.appnew')
@section('content')

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 my-5">
                <div class="col-sm-12 m-auto">

                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0">{{ $pageTitle }}</h3>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Back to Dashboard</a>
                            </div>

                            <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="nonRunningTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Outlet Name</th>
                                            <th>Customer Name</th>
                                            <th>Value</th>
                                            <th>Last Paid Invoice Date</th>
                                            <th>Last Paid Invoice No</th>
                                            
                                             @if($type === 'overdue_till_date')
                                                <th>Payment Date Committed</th>
                                                <th>Follow-up Feedback</th>
                                                <th>Follow-up Date</th>
                                            @endif
                                            
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($details as $i => $row)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $row['outlet_name'] ?? 'N/A' }}</td>
                                            <td>{{ $row['customer_name'] ?? 'N/A' }}</td>
                                            <td>₹{{ number_format($row['value'], 2) }}</td>
                                            <td>{{ $row['last_paid_invoice_date'] ? \Carbon\Carbon::parse($row['last_paid_invoice_date'])->format('d-m-Y') : 'N/A' }}</td>
                                            <td>{{ $row['last_paid_invoice_no'] ?? 'N/A' }}</td>
                                            
                                             @php $f = $followups[$row['outlet_id']] ?? null; @endphp
                                            @if($type === 'overdue_till_date')
                                            <td><input type="date" class="form-control form-control-sm payment-date" value="{{ $f->payment_date_committed ?? '' }}" data-outlet="{{ $row['outlet_id'] }}"></td>
                                            <td><input type="text" class="form-control form-control-sm followup-feedback" value="{{ $f->followup_feedback ?? '' }}" data-outlet="{{ $row['outlet_id'] }}"></td>
                                            <td><input type="date" class="form-control form-control-sm followup-date" value="{{ $f->followup_date ?? '' }}" data-outlet="{{ $row['outlet_id'] }}"></td>
                                            @endif
                                            
                                            <td>
                                            <a href="{{ route('admin.reports.overdue-outlet-detail', $row['outlet_id']) }}" class="btn btn-sm btn-info" target="_blank">
                                                View
                                            </a>
                                           </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">No records found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light">
                                            <td colspan="4" class="text-end"><strong>Total</strong></td>
                                            <td><strong>₹{{ number_format($totalAmount, 2) }}</strong></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $('.payment-date, .followup-feedback, .followup-date').on('change', function() {
    const row = $(this).closest('tr');
    $.post('{{ route("admin.reports.overdue-followup.save") }}', {
        _token: '{{ csrf_token() }}',
        outlet_id: $(this).data('outlet'),
        payment_date_committed: row.find('.payment-date').val(),
        followup_feedback: row.find('.followup-feedback').val(),
        followup_date: row.find('.followup-date').val(),
    }, function(res) {
        if (res.success) toastr.success('Saved');
    });
});
</script>
@endsection