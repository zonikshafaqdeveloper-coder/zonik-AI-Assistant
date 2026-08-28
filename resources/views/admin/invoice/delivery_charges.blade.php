<!DOCTYPE html>
<html lang="en">

<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<!--    <title>Invoice</title>-->
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">-->
<!--    <link rel="stylesheet" href="css/invoice.css">-->
<!--</head>-->

<body>
    <div class="container1">
        <div class="row">
            <div class="col-md-12 d-flex">
                <div>
                    <img src="{{ asset('frontweb/assests/images/new_logo.png')}}" alt="laravel daily" width="150" />
                </div>
                <div>
                    <h2 class="text-center">Delivery Changes Tax Invoice</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class=" table-bordered">
                        <tbody>
                            <thead>
                                <tr>
                                    <td>Invoice Number<br><b>{{ $orders->first()->invoice_id }}</b></td>
                                    <td>Order No<br><b>{{ $orders->first()->order_id }}</b></td>
                                    <td>Invoice Date<br><b>{{ $orderInvoice->first()->order->invoice_date }}</b></td>
                            <!--         @if(isset($lastpayment) && $lastpayment->first())-->
                            <!--<td>Last Payment Date<br><b>{{ $lastpayment->first()->outstanding_date }}</b></td>-->
                            <!--@else-->
                          
                            <!--@endif-->
                            
                             <td>Last Payment Date<br><b>{{ $orders->first()->payment_status === 'paid' ? $orders->first()->updated_at->format('Y-m-d') : '' }}</b></td>
                            
                                </tr>
                            </thead>
                            <tr>
                                @if(isset($orderInvoice) && $orderInvoice->first() && $orderInvoice->first()->order)
                                <td>Order
                                    Date<br><b>{{ $orderInvoice->first()->order->created_at->format('Y-m-d') }}</b></td>
                                @endif

                                <td>Delivery Time Slot<br><b>10:00 AM to 07:00 PM</b></td>
                                <td>Refference PO<br><b></b></td>
                                <td>Payment Status<br><b>{{ $orders->first()->payment_status }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class=" table-bordered">
                    <tbody>
                        <tr>
                            <td>
                                {{-- <!-- <p><span>Address :</span>{{ $orderInvoice->first()->order->billing_address }}
                                </p> --}}
                                <p><span><b>Bill From : </b></span>INFI-PARA SOLUTIONS INC</p>
                                <p><span> <b>Address :</b> </span>SGround floor B 45, Shanti Industrial Estate, near Tambe
                                    Nagar Mulund (W), Mumbai - 400080, Mumbai</p>
                                <p><span> <b>GSTIN :</b> </span>27AAIFI1935N1ZZ</p>
                                <p><span><b>FSSAI : </b></span>NA</p>

                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class=" table-bordered">
                    <tbody>
                        <tr>
                            <td>
                                <p><span><b>Shipped From : </b></span>INFI-PARA SOLUTIONS INC</p>
                                <p><span><b>Address :</b></span>Ground floor B 45, Shanti Industrial Estate, near Tambe
                                    Nagar Mulund (W), Mumbai - 400080, Mumbai</p>
                                <p><span><b>GSTIN : </b></span>27AAIFI1935N1ZZ</p>
                                <p><span><b>FSSAI : </b></span>NA</p>

                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class=" table-bordered">
                    <thead>
                        <tr  >
                            <th style="width: 50%">Bill To</th>
                            <th style="width: 50%">Ship To</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td >
                                <p><span>Outlet :</span> {{$orders->first()->user?->name }} </p>
                                <p><span>Address : </span>{{ $orderInvoice->first()->order->billing_address }}</p>
                                <p><span>Pincode : </span>{{$orders->first()->user?->pincode }}</p>

                                <p><span>Place Of Supply : </span>{{$orderss->first()->fssai }}  </p>
                                <p><span>GSTIN : </span> {{$orderss->first()->gst_no }}</p>
                            </td>
                            <td>
                                <p><span>Outlet :</span> {{$orders->first()->user?->name }} </p>
                                <p><span>Address : </span>{{ $orderInvoice->first()->order->shipping_address }}</p>
                                <p><span>Pincode : </span>{{$orders->first()->user?->pincode }}</p>

                                <p><span>Place Of Supply : </span>{{$orderss->first()->fssai }} </p>
                                <p><span>GSTIN : </span> {{$orderss->first()->gst_no }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class=" table-bordered">
                    <thead>
                        <tr>
                            <th >SI No.</th>
                            <th >Description of Services</th>
                            <th >HSN</th>
                            <th >Qty Del.</th>
                            <th >Unit Price</th>
                            <th >UOM</th>
                            <th >Pre Tax</th>

                            <th >Total Discount Value</th>
                            <th >Taxable Amount</th>
                            <th >(CGST + SGST + IGST + CESS)%</th>
                            <th >Total Tax Amount</th>
                            <th >Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $totaltaxabletotalamount = 0;
                        $totalcgstamount = 0;
                        $totalsgstamount = 0;
                        $totaligstamount = 0;
                        $totalcessamount = 0;
                        $totaltaxamount = 0;
                        $totalsumamount = 0;
                        $totalpretaxamount = 0;
                        $totaldiscountvalue = 0;

                        $originalDeliveryCharges = $orders->first()->delivery_charges;
                        $discountPercentage = 18;
                        $discountAmount = ($discountPercentage / 100) * $originalDeliveryCharges;
                        $finalDeliveryCharges = $originalDeliveryCharges - $discountAmount;

                        @endphp

                        <tr>
                            <td> 1</td>
                            <td> Delivery charges</td>
                            <td>0</td>
                            <td>1</td>
                            <td>{{ $finalDeliveryCharges }}</td>
                            <td>0</td>
                            <td>{{ $finalDeliveryCharges }}</td>
                            <td>0</td>
                            <td>{{$finalDeliveryCharges}}</td>
                            <td>9+9+0+0</td>
                            {{--@if ($maharashtrian === 'True')
                            <td>9+9+0+0</td>
                            @else
                            <td>0+0+18+0</td>

                            @endif--}}
                            <td>

                                {{ $discountAmount}}</td>
                            <td>{{$orders->first()->delivery_charges }}</td>
                        </tr>
                        <tr>


                            <td colspan="2">TCS u/s 206C(1H) @0% For all eligible merchants</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                            <td><span>0</span></td>

                        </tr>
                        <tr>
                            <th colspan="2">Total </th>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td>{{ $finalDeliveryCharges }}</td>
                            <td>0</td>
                            <td>{{ $finalDeliveryCharges }}</td>
                            <td> </td>
                            <td>{{$discountAmount}}</td>
                            <td>{{$orders->first()->delivery_charges }}</td>
                        </tr>
                        <thead>
                            <tr>
                                <th  colspan="2">Total Taxable Amount (in Rs)</th>
                                <th colspan="2">CGST Amount</th>
                                <th colspan="2">SGST Amount</th>
                                <th colspan="2">IGST Amount</th>
                                <th colspan="2">CESS Amount</th>
                                <th  colspan="2">Total Tax Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td  colspan="2">{{ $finalDeliveryCharges}}</td>
                                <td colspan="2"> {{ number_format($discountAmount/2,1) }}</td>
                                <td colspan="2"> {{ number_format($discountAmount/2,1) }}</td>
                                <td colspan="2"> 0</td>
                                <td colspan="2"> 0</td>
                                <td colspan="2">{{$discountAmount}} </td>

                            </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- <p>Amount Chargeable (in words):</p>
        <p>INR Fifteen Thousand Five Hundred Thirty Rupees and Thirty Four Paisa Only</p>
        <p>E. & O.E</p> -->
                <table class=" table-bordered">
                    <thead>

                    </thead>
                    <tbody>


                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
              {{--  <p>Amount Chargeable (in words):</p>
        <p>INR Fifteen Thousand Five Hundred Thirty Rupees and Thirty Four Paisa Only</p>
        <p>E. & O.E</p>  --}}
                <table class=" table-bordered">


                        <tr>
                            <!-- <td colspan="10">Declaration:</td> -->

                        </tr>
                        <tr>
                            <td colspan="10"><span>Remark :</span>Please stamp the invoice with your internal receiving stamp and provide your name (customers received) with signature & date to ensure it is accepted by you and delivered to you. Give the copy to delivery boy for our records.
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>

</html>
