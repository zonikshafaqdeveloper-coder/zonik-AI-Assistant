<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<style>
    .text-danger td{
        color: red;
    }
    .text-danger{
        text-decoration: line-through; color: red
    }
</style>
</head>

<body>
    <table class="w-full">
        <tr>
            <td class="w-half">
                <img src="{{ asset('frontweb/assests/images/new_logo.png')}}" alt="laravel daily" width="200" />
            </td>
            <td class="w-half">

            </td>
            <td class="w-half">
                <h4>Invoice ID: {{ $orders->first()->invoice_id }}</h4>
                <h4>Invoice Date: {{ $orderInvoice->first()->order->created_at->format('Y-m-d') }}</h4><br>

            </td>
        </tr>
    </table>

    <div class="margin-top">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div>
                        <h4>Billing To:</h4>
                    </div><br>
                    <div><b>Outlate name </b> :
                        {{$orderInvoice->first()->user?->name }}</div>

                    <div> <b>Outlet Contact </b>: {{ $orderss->first()->phone }} </div>
                    <div> <b>GST No </b>: {{ $orderss->first()->gst_no }} </div>
                    <div><b>Address </b>:{{ $orderInvoice->first()->order->billing_address }}</div>
                </td>
                <td class="w-half">
                    <div>
                        <h4>Shipping to:</h4>
                    </div><br>
                    <div><b>Outlate name </b> : {{$orderInvoice->first()->user?->name }}</div>
                    <div> <b>Outlet Contact </b>: {{ $orderss->first()->phone }} </div>
                    <div> <b>GST No </b>: {{ $orderss->first()->gst_no }} </div>

                    <div><b>Address</b> :{{ $orderInvoice->first()->order->shipping_address }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="margin-top">
        <table class="products">
            <tr>
                <th>Sr.</th>
                <th>Product name</th>
                <th>Price</th>
                <th>Total qty</th>
                <th>Total price</th>
            </tr>

            @php $serialNumber = 1;
            $totalPrice = 0;
             @endphp
            @foreach($orderInvoice as $orderItem)
          @if ($orderItem->in_invoice == 'no')
          <tr class="items text-danger">

            <td>
                {{ $serialNumber }}
            </td>
            <td>
                {{ $orderItem->product->product_name }}
            </td>
            <td>
                {{ $orderItem->price }}
            </td>
            <td>
                {{ $orderItem->quantity }}
            </td>
            <td>{{ $orderItem->price }}
            </td>

        </tr>
          @else
          <tr class="items">

            <td>
                {{ $serialNumber }}
            </td>
            <td>
                {{ $orderItem->product->product_name }}
            </td>
            <td>
                {{ $orderItem->price }}
            </td>
            <td>
                {{ $orderItem->quantity }}
            </td>
            <td>{{ $orderItem->price }}
            </td>

        </tr>
          @endif

            @php $serialNumber++ @endphp
            @endforeach
        </table>
    </div>

    <div class="row ">
        <div class="col-md-8"></div>
        <div class="col-md-3 " >
            <table class="total">
                <tr>
                    <td><b>SubTotal : </b></td>
                    <td>{{ $orders->first()->subtotal }}</td>
                </tr>
                <tr>
                    <td><b>Product Discount :</b></td>
                    <td>{{ $orders->first()->product_discount }}</td>
                </tr>
                <tr>
                    <td><b>CGST/SGST :</b></td>
                    <td>{{ $orders->first()->cgst_sgst }}</td>
                </tr>
                <tr>
                    <td><b>Delivery/Packing charges :</b></td>
                    <td>{{ $orders->first()->packing_charges + $orders->first()->delivery_charges }}</td>
                </tr>
                <tr>
                    <td><b>Grand Total :</b></td>
                    <td>{{ $orders->first()->total_discount_value }}</td>
                </tr>
                <tr>
                    @php
                        $allNoInvoice = true;
                        $totalPrice = 0; // Initialize total price
                    @endphp
                    @foreach($orderInvoice as $orderItem)
                        @if ($orderItem->in_invoice !== 'no')
                            @php
                                $allNoInvoice = false;
                            @endphp
                        @endif
                        @if ($orderItem->in_invoice == 'no')
                            @php
                                if ($allNoInvoice) {
                                    $totalPrice = $orders->first()->total_discount_value;
                                } else {
                                    $totalPrice += $orderItem->price;
                                }
                            @endphp
                        @endif
                    @endforeach
                    @if ($allNoInvoice)
                    $totalPrice = $orders->first()->total_discount_value;
                    @endif
                    <td><b>Refund :</b></td>
                    <td>
                        {{ number_format($totalPrice, 2) }}
                    </td>
                </tr>




            </table>
        </div>
    </div>

    <div class="my-2 row footer">
        <div>Terms and conditions</div>
        <div>&copy; Goods once sold will not be taken back or exchanged</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>
