{{--  <!DOCTYPE html>
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
                    "amount": 50000,
                    "currency": "INR",
                    "name": "Acme Corp",
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
</html>  --}}

<!-- resources/views/payment.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <button id="rzp-button1">Pay</button>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    document.getElementById('rzp-button1').onclick = function(e) {
        fetch('/create-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            var options = {
                "key": "{{ env('RAZORPAY_KEY') }}", // Enter the Key ID generated from the Dashboard
                "amount": data.amount, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise or INR 500.
                "currency": "INR",
                "name": "Infipara Solution", // Your business name
                "description": "Infipara Soolutions ",
                "image": "https://example.com/your_logo", // URL of your logo
                "order_id": data.id, // Order ID obtained from your server
                "callback_url": "https://zonik.in/orders", // URL to handle the callback from Razorpay
                "prefill": { // Prefill customer information to improve conversion rates
                    "name": "Gaurav Kumar", // Customer's name
                    "email": "gaurav.kumar@example.com", // Customer's email
                    "contact": "9000090000" // Customer's phone number
                },
                "notes": {
                    "address": "Razorpay Corporate Office" // Additional notes or customer address
                },
                "theme": {
                    "color": "#a558c8" // Theme color of the payment window
                }
            };

            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault(); // Prevent default action to ensure Razorpay checkout opens
        })
        .catch(error => console.error('Error:', error));
    }
    </script>
</body>
</html>
