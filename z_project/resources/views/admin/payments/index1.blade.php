<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <h1>Razorpay Payment Gateway</h1>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <button id="payButton">Pay Now</button>

    <form id="paymentForm" method="POST" action="{{ route('payment.success') }}" style="display: none;">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>

    <script>
        document.getElementById('payButton').onclick = function(e) {
            e.preventDefault();

            fetch('{{ route('payment.createOrder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => response.json()).then(data => {
                var options = {
                    "key": data.razorpay_key,
                    "amount": 10000,
                    "currency": "INR",
                    "name": "Acme Corp1",
                    "description": "Test Transaction",
                    "order_id": data.order_id,
                    "handler": function (response) {
                        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                        document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                        document.getElementById('razorpay_signature').value = response.razorpay_signature;
                        document.getElementById('paymentForm').submit();
                    }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
            });
        }
    </script>
</body>
</html>
