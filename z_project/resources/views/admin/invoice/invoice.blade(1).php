<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tax Invoice - IGIN-{{ $orders->first()->invoice_id }}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/invoice.css">
</head>
<body>
    
    <style>
    @font-face {
        font-family: 'Abadi';
        font-weight: normal;
        src: url('{{ public_path('fonts/Abadi/AbadiMT.ttf') }}') format('truetype');
    }

    @font-face {
        font-family: 'Abadi';
        font-weight: bold;
        src: url('{{ public_path('fonts/Abadi/AbadiMT-Bold.ttf') }}') format('truetype');
    }

    @font-face {
        font-family: 'Abadi';
        font-style: italic;
        src: url('{{ public_path('fonts/Abadi/AbadiMT-Italic.ttf') }}') format('truetype');
    }

    @font-face {
        font-family: 'Abadi';
        font-weight: bold;
        font-style: italic;
        src: url('{{ public_path('fonts/Abadi/AbadiMT-BoldItalic.ttf') }}') format('truetype');
    }

    body, table, th, td, p, h1, h2, h3, h4, h5, h6, span, b {
        font-family: 'Abadi', sans-serif !important;
        font-size: 11px !important;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid #000;
        padding: 6px 10px;
        text-align: left;
    }

    .text-center {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }
</style>


    <div class="container1">
        <div class="row">
            <div class="col-md-12 d-flex">
                <div>
   <img src="{{ asset('frontweb/assests/images/Adobe Express - file.png')}}" alt="laravel daily" width="150" />
                </div>
                <div>
                    <h2 class="text-center abadi-font" style="font-family: Arial, sans-serif !important; font-size: 30px !important;">Tax Invoice</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
<td class="abadi-font">
    Invoice Number<br>
    <b>{{ $orders->first()->invoice_id }}</b>
</td>

                            <td>Order No<br><b>{{ $orders->first()->order_id }}</b></td>
                            <!--<td colspan="2"> Invoice Date<br><b>{{ $orderInvoice->first()->order->invoice_date }}</b></td>-->
                            <td colspan="2"> Invoice Date<br><b>{{ $orders->first()->invoice_date ? \Carbon\Carbon::parse($orders->first()->invoice_date)->format('d-m-Y') : '' }}</b></td>
                            <!--@if(isset($lastpayment) && $lastpayment->first())-->
                            <!--<td>Last Payment Date<br><b>{{ $lastpayment->first()->outstanding_date }}</b></td>-->
                            <!--@else-->
                            <!--<td>-->
                            <!--</td>-->
                            <!--@endif-->
                           <td>Delivery Date<br><b>{{ $orders->first()->delivery_date ? \Carbon\Carbon::parse($orders->first()->delivery_date)->format('d-m-Y') : '' }}</b></td>



                        </tr>
                        <tr>
                            <td>Order Date<br><b>{{ $orderInvoice->first()->order->created_at->format('Y-m-d') }}</b>
                            </td>
                            <td>Delivery Time Slot<br><b> {{ $orders->first()->delivery_time_slot 
    ? preg_replace_callback('/(\d{1,2}:\d{2}\s*)(am|pm)\s*to\s*(\d{1,2}:\d{2}\s*)(am|pm)/i', 
        function($matches) {
            return $matches[1] . strtoupper($matches[2]) . ' - ' . $matches[3] . strtoupper($matches[4]);
        }, $orders->first()->delivery_time_slot) 
    : '10:00 AM - 07:00 PM' }}
    </b></td>
                            <td>
                                No of Pending invoices <br> <b>{{ $lastpayment->count() }}</b>
                            </td>
                            <td>Outstanding Till date<br><b> {{ $lastpayment->sum('total_due_amount') }}</b></td>

                            <td>Payment Status<br><b>{{ $orders->first()->payment_status }}</b></td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <!-- <p><span>Address :</span>{{ $orderInvoice->first()->order->billing_address }}</p>
                                    <p><span>GSTIN : </span>{{ $orderss->first()->gst_no }} </p>
                                    <p><span>FSSAI : </span></p> -->
                                <p><span><b>Bill From : </b></span>Infigourmet networks private limited</p>
                                <p><span>Address : </span> Ground floor B 45, Shanti Industrial Estate, near Tambe
                                    Nagar Mulund (W), Mumbai - 400080, Mumbai</p>
                                <p><span>GSTIN : </span>27AAICI2086H1ZE</p>
                                <p><span>FSSAI : </span>11525009000305</p>

                            </td>

                        </tr>
                        <tr>
                            <td colspan="5">
                                <p><span><b>Shipped From : </b></span>Infigourmet networks private limited</p>
                                <p><span>Address : </span> Ground floor B 45, Shanti Industrial Estate, near Tambe
                                    Nagar Mulund (W), Mumbai - 400080, Mumbai</p>
                                <p><span>GSTIN : </span>27AAICI2086H1ZE</p>
                                <p><span>FSSAI : </span>11525009000305</p>

                            </td>

                        </tr>
                        <tr>
                            <th colspan="2">Bill To</th>
                            <th colspan="3">Ship To</th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><span>Company Name :</span> {{ $company_name1 ?? 'N/A' }} </p>
                                <p><span>User :</span> {{$orders->first()->user?->name }} </p>
                                <p><span>Outlet :</span> {{$orders->first()->user?->outlet_name }} </p>
                                <p><span>Address : </span>{{ $orderInvoice->first()->order->billing_address }}</p>
                                <p><span>Pincode : </span>{{$orders->first()->user?->pincode }}</p>
                                <p><span>Phone Number : </span>{{$orders->first()->user?->mobile_number ?? '' }}</p>

                                <p><span>Place Of Supply : </span></span>{{ $orderss->first()->fssai ? $orderss->first()->fssai : 'NA' }} </p>
                               <p><span>GSTIN : </span> {{ $orderss->first()->gst_no ? $orderss->first()->gst_no : 'NA' }}</p>

                            </td>
                            <td colspan="3">
                                <p><span>Company Name :</span> {{ $company_name1 ?? 'N/A' }} </p>
                                <p><span>User :</span> {{$orders->first()->user?->name }} </p>
                                <p><span>Outlet :</span> {{$orders->first()->user?->outlet_name }} </p>
                                <p><span>Address : </span>{{ $orderInvoice->first()->order->shipping_address }}</p>
                                <p><span>Pincode : </span>{{$orderInvoice->first()->order->shipping_pincode }}</p>
                                <p><span>Phone Number : </span>{{$orders->first()->user->mobile_number ?? '' }}</p>
                                <p><span>Place Of Supply : </span>{{ $orderss->first()->fssai ? $orderss->first()->fssai : 'NA' }} </p>
                               <p><span>GSTIN : </span> {{ $orderss->first()->gst_no ? $orderss->first()->gst_no : 'NA' }}</p>

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
                            <th width="">Sr No.</th>
                            <th>Description of Goods</th>
                            <th colspan="2" width="50px">HSN</th>
                            <th width="50px">Qty.</th>
                            <th width="50px">Unit Price (Basic)</th>
                            <!--<th width="50px">UOM</th>-->
                            <th colspan="2" width="50px">Pre Tax</th>

                            <th width="200px">Total Discount Value</th>
                            <!--<th width="100px">Coupon Discount</th>-->
                            <!--<th width="100px">Taxable Amount</th>-->
                            <th colspan="2">(CGST + SGST + IGST + CESS)%</th>
                            <th width="50px">Total Tax Amount</th>
                            <th width="50px">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        
 
        
                        @php
                        $words = '';
                        $serialNumber = 1;
                        $totaltaxabletotalamount = 0;
                        $totalcgstamount = 0;
                        $totalsgstamount = 0;
                        $totaligstamount = 0;
                        $totalcessamount = 0;
                        $totaltaxamount = 0;
                        $totalsumamount = 0;
                        $totalpretaxamount = 0;
                        $totaldiscountvalue = 0;
                        $totalproductDiscount = 0;
                        $lastamount = 0;
                        $finalamount = 0;
                        $correctamount = 0;
                      

                         
                         
                        @endphp
                        
                          @php
                        $deliveryCharges = $orders->first()->delivery_charges ?? 0;
                        @endphp
                        
                        @foreach($orderInvoice as $orderItem)
                        
          @php
    // Calculate GST rate
    $gstRate = $orderItem->product->cgst + $orderItem->product->sgst;

    // Calculate selling price per item including GST
    $sellingPriceWithGst = $orderItem->offer_price * (1 + ($gstRate / 100));

    // Calculate discount (ensure it does not result in a negative value)
    $discount = ($orderItem->mrp - $sellingPriceWithGst) * $orderItem->quantity;
    $discount = $discount > 0 ? $discount : 0;
    $subTotal = $orderItem->price;
    $productDiscount = $orderItem->product->total_discount > 0 ? ($subTotal * $orderItem->product->total_discount) / 100 : 0;
    $totalproductDiscount += $productDiscount;
@endphp

                        <tr>
                            <td> {{ $serialNumber }}.</td>
                            <td> {{ $orderItem->product->product_name }}</td>
                            <td colspan="2">{{ $orderItem->product->hsn_code }}</td>
                            <td>{{ $orderItem->quantity }}</td>
                            
                            <td>{{ number_format($orderItem->offer_price) }}.00</td>

                            <!--<td>{{ $orderItem->unit}}</td>-->

                            <td colspan="2">
                                @php
                                if($orderItem->qty_type == 'box'){
                                $pretax = (float) $orderItem->quantity * (float) $orderItem->offer_price;
                                $totalpretaxamount += $pretax;
                                }else{
                                $pretax = (float) $orderItem->quantity * (float) $orderItem->offer_price;
                                $totalpretaxamount += $pretax;
                                }

                                @endphp {{ $pretax }}.00</td>

                            <td>
                              
@php
                                $taxableAmount = 0;
                                $taxableAmountTotal = 0;
                                $taxableAmountTotal1 = 0;
                                $couponDiscount = 0;
                                $totaldiscount = 0;
                                if($orderItem->product->total_discount <= 0){ $totaldiscount=0; }else{

                                    if($orderInvoice->first()->order->coupon_discount){

                                        $couponDiscount = $orderInvoice->first()->order->coupon_discount;
                                    }


                                    $totaldiscount = $orderItem->product->total_discount;
                                        $totaldiscount = $totaldiscount ;
                                    }

                                    $totaldiscountvalue += $totaldiscount;

                                  

                                     $taxableAmountTotal = $pretax;
                                    $totaltaxabletotalamount += $taxableAmountTotal;

                                    @endphp
                                    {{ number_format($productDiscount,2)}}                                 
                            </td>
                             <!--<td>{{ $orderItem->coupon_discount ?? 0.00 }} </td>-->
                            <!--<td>-->
                              
                            <!--</td>-->

                            <td colspan="2">
                                @php
                                
                                  
                                $taxableAmount = 0;
                                $taxableAmountTotal = 0;
                                $taxableAmountTotal1 = 0;
                                $couponDiscount = 0;
                                $totaldiscount = 0;
                                if($orderItem->product->total_discount <= 0){ $totaldiscount=0; }else{

                                    if($orderInvoice->first()->order->coupon_discount){

                                        $couponDiscount = $orderInvoice->first()->order->coupon_discount;
                                    }


                                    $totaldiscount = $orderItem->product->total_discount;
                                        $totaldiscount = $totaldiscount ;
                                    }

                                    $totaldiscountvalue += $totaldiscount;

                                  

                                     $taxableAmountTotal = $pretax;
                                    $totaltaxabletotalamount += $taxableAmountTotal;


                                    
                                    
                                    
                                $cess = 0;
                                if($maharashtrian === 'False'){
                                    $igst = $orderItem->product->igst;
                                    $sgst = 0;
                                    $cgst = 0;
                                }else{
                                    $sgst = $orderItem->product->sgst;
                                    $cgst = $orderItem->product->cgst;
                                    $igst = 0;
                                }

                                $totalTaxes = $sgst + $cgst + $igst + $cess;

                                $totalcgstamount += (($cgst * $taxableAmountTotal)/100);
                                $totalsgstamount += (($sgst * $taxableAmountTotal)/100);
                                $totaligstamount += (($igst * $taxableAmountTotal)/100);
                                $totalcessamount += (($cess * $taxableAmountTotal)/100);

                                @endphp

                                {{ $sgst }}+{{ $cgst }}+{{ $igst }}+{{ $cess }}
                            </td>

                            <td>
                                @php
                                $taxableAmountTotal1 = ($taxableAmountTotal * $totalTaxes)/100;
                                $totaltaxamount += $taxableAmountTotal1;

                                $totalamount = $pretax + $taxableAmountTotal1;
                                $totalsumamount += $totalamount;

                                @endphp
                                {{ number_format($taxableAmountTotal1,2) }}
                            </td>

                            <td>
                                @php
                                    $lastamount = ($totalamount - $orderItem->coupon_discount);
                                    $finalamount += $lastamount;
                                    
                                    $correctamount += $totalamount;
                                    
                                @endphp
                                {{ number_format($totalamount, 2) }}
                            </td>
        
                        </tr>

                        @php $serialNumber++ @endphp
                        @endforeach
                        <tr>
                            <!--<td colspan="13"><span></span></td>-->
                            <td colspan="13"><span>Others Charges</span></td>
                        </tr>
                        <!--<tr>-->
                        <!--    <td colspan="2" width="277px"><span>TCS u/s 206C(1H) @0% For all eligible merchants</span>-->
                        <!--    </td>-->
                        <!--    <td></td>-->
                        <!--    <td></td>-->
                        <!--    <td></td>-->
                        <!--    <td></td>-->
                        <!--    <td colspan="3"></td>-->
                            <!--<td></td>-->
                            <!--<td></td>-->
                        <!--    <td colspan="2"></td>-->
                            <!--<td></td>-->
                        <!--    <td></td>-->
                        <!--    <td><span>0</span></td>-->

                        <!--</tr>-->
                        
                        
                            <tr>

                            <td colspan="2"><span>Total</span></td>
                            <td colspan="2"></td>
                            <!--<td> </td>-->
                            <td> </td>
                            <td> </td>
                            <td colspan="2">{{ number_format($totalpretaxamount,2) }}</td>
                            <!--<td></td>-->
                            <td>{{ number_format($totalproductDiscount,2)}}</td>
                            <!--<td>{{ $orderItem->coupon_discount }} </td>-->

                            <!--<td>{{ number_format($totaltaxabletotalamount, 2) }}</td>-->
                            <td colspan="2"></td>
                            <td></td>
                            @php
                                $totalsumamount = $totalsumamount - $couponDiscount;
                            @endphp
                            <td>{{number_format( $correctamount,2) }}</td>
                        </tr>
                        
                    
                    <!--<tr>-->
                    <!--    <td colspan="9" ></td>-->
                    <!--    <td colspan="3">Total Discount Value</td>-->
                    <!--    <td>{{ number_format($totalproductDiscount,2)}}</td>-->
                    <!--</tr>-->
                    
                     <tr>
                        <td colspan="9"></td>
                        <td colspan="3">Coupon Discount</td>
                        <td> {{$orderItem->coupon_discount ?? 0.00}}</td>
                    </tr>
                   
                    <tr>
                    <td colspan="9"></td>
                    <td colspan="3">Delivery Charges</td>
                    <td>{{ number_format($deliveryCharges, 2) }}</td>
                    </tr>

                     <tr>
                        <td colspan="9"></td>
                        <td colspan="3">Total Chargeable Value</td>
                         @php
                                $totalsumamount = $totalsumamount - $couponDiscount;
                            @endphp
                            <td>{{number_format( $finalamount,2) }}</td>
                    </tr>
                    
                    
                        {{--  @php
                        use App\Http\Controllers\OrderItemController;

                        $controller = new OrderItemController();
                        $number = $totalsumamount;
                        $words = $controller->numberToWords(number$);
                        @endphp  --}}

                          @php
    $words = app('App\Http\Controllers\OrderController')->numberToWords($finalamount);
@endphp

<tr>
    <td colspan="2"><b>Amount Chargeable (in words):</b></td>
    <td colspan="11" id="amountInWords">{{ $words }}</td>
</tr>
                        <tr>
                            <th colspan="2">Total Taxable Amount (in Rs)</th>
                            <th colspan="2">CGST Amount</th>
                            <th colspan="3">SGST Amount</th>
                            <th colspan="2">IGST Amount</th>
                            <th colspan="2">CESS Amount</th>
                            <th colspan="2">Total Tax Amount</th>
                        </tr>
                        <tr>
                            <td colspan="2">{{ number_format($totalpretaxamount, 2) }}</td>
                            <td colspan="2"> {{ number_format($totalcgstamount, 2) }}</td>
                            <td colspan="3"> {{ number_format($totalsgstamount, 2) }}</td>
                            <td colspan="2"> {{ number_format($totaligstamount, 2) }}</td>
                            <td colspan="2"> {{ number_format($totalcessamount, 2) }}</td>
                            <td colspan="2">{{ number_format($totaltaxamount, 2) }} </td>
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
                <table class="table table-bordered">
                    <thead>

                    </thead>
                    <tbody>

                        <tr>
                            <td colspan="10"><span>Terms &amp; Conditions :</span> Please give acceptance with Stamp and Sign once Material received at the store. Check the material before accepting for Damages, Rates or any issues; later, once stamp received, no changes will be made to the invoice.
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="10">
                                
                                 <div style="border-radius:8px; padding:12px; font-size:13px; overflow:hidden;">
        <strong>BANK DETAILS</strong><br/>
        IFSC: IDFB0040511<br/>
        ACCOUNT NO: 59869612317<br/>
        CUSTOMER NAME: INFIGOURMET NETWORKS PRIVATE LIMITED<br/>
        BANK: IDFC FIRST BANK
      </div>
                            </td>
                        </tr>
                        
                       

                    </tbody>
                </table>
              
               
            </div>
        </div>
    
    
     

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

</body>

</html>
