@extends('admin.layouts.appnew')
@section('content')
<div class="card">
<div class="card-body">
    
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<h4>Edit Payment - Order {{ $order->order_id }}</h4>


<form action="{{ route('order.update', $order->id) }}" method="POST" enctype="multipart/form-data">
@csrf
 @method('PUT')
 
  <input type="hidden" name="from" value="{{ $from }}">
  
<div class="row">
<div class="col-md-4">
<label>Total Amount</label>
<input class="form-control" readonly value="{{ number_format($payment->total_amount ?? $order->total_discount_value,2)}}">
</div>
<div class="col-md-4">
<label>Total Paid</label>
<input class="form-control" readonly value="{{ number_format($payment->total_paid ?? 0,2)}}">
</div>
<div class="col-md-4">
<label>Pay Now (amount)</label>
<input type="number" step="0.01" name="amount_paid" class="form-control" required>
</div>


<div class="col-md-4 mt-2">
<label>Payment Mode</label>
<select name="payment_mode" class="form-control" required>
<option value="">Select Mode</option>
<option value="cash">Cash</option>
<option value="upi">UPI</option>
<option value="card">Card</option>
<option value="razorpay">Razorpay</option>
<option value="cheque">Cheque</option>
<option value="neft">NEFT</option>
<option value="imps">IMPS</option>
</select>
</div>

<div class="col-md-4 mt-2">
<label>Reference</label>
<input type="text" name="reference" class="form-control" required>
</div>


<div class="col-md-4 mt-2">
<label>Upload Documents</label>
<input type="file" name="documents[]" multiple class="form-control">
</div>


<div class="col-md-12 mt-3">
<button class="btn btn-primary">Save Payment</button>
<a href="{{ route('payments.history', $order->id) }}" class="btn btn-secondary">View History</a>

</div>
</div>
</form>


<hr>
<h5>Recent Transactions</h5>
<ul>
@foreach($histories as $h)
<li>{{ $h->created_at->format('d M Y H:i') }} - {{ number_format($h->paid_amount,2) }} - {{ $h->payment_mode }}
@if($h->documents)
| <a target="_blank" href="{{ asset('uploads/payment_docs/' . ($h->documents[0] ?? '')) }}">Proof</a>
@endif
</li>
@endforeach
</ul>


</div>
</div>
        @endsection

       

<script>
document.addEventListener("DOMContentLoaded", function () {
    let totalAmount = {{ $payment->total_amount ?? $order->total_discount_value }};
    let totalPaid   = {{ $payment->total_paid ?? 0 }};
    let remaining   = totalAmount - totalPaid;

    let input = document.querySelector("input[name='amount_paid']");

    input.addEventListener("input", function () {
        if (parseFloat(this.value) > remaining) {
            alert("You cannot pay more than remaining balance: ₹" + remaining.toFixed(2));
            this.value = remaining.toFixed(2);
        }
    });
});
</script>

    
   <!-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="payment_id">Payment ID</label>
                                                <input type="text" class="form-control" name="payment_id">
                                            </div>
                                        </div> -->