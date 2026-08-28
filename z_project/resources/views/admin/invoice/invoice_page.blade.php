<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">


    <link href="{{ asset('css/invoice.css') }}" rel="stylesheet" media="all">


    <!------ Include the above in your HEAD tag ---------->


</head>
<style>
        /* Define your CSS styles here */
        body {
            font-family: Arial, sans-serif;
        }
        .invoice-header {
            background-color: #f2f2f2;
            padding: 20px;
            margin-bottom: 20px;
        }
        .invoice-item {
            margin-bottom: 10px;
        }
        /* Add more styles as needed */
    </style>

<body>


    <div class="container p-5 ">
    
        <div class="row">

            <div class="col-md-12">

                <div class="">
                    <table width="100%">
                        <tr>
                            <td width="10%">
                                
                                <div class=" invoice-title">
                                <img src="{{ asset('frontweb/assests/images/new_logo.png')}}"  width="150px">

                                   
                                </div>
                            </td>
                            <td width="90%" class="p-2">
                                <div class=" invoice-title">
                                    <table>
                                        <tr>
             <td class="head"> <h3><b>Infi-para solution inc</b></h3>

                                           

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="subhead">
                                         
<h5 class="pt-4">Office no.125 Ecstacy Business Park
JSD Road, Near East west Mulund flyover
Mumbai Maharashtra 400080,
India,

</h5>
                                         
                                            
                                                
                                            </td>
                                        </tr>


 <tr>

<td><span><b>GSTIN :
</b>27AAIFI1935N1ZZ</span>
</td>


    <td><span><b>Mobile : </b>8657516534 </span>&nbsp; &nbsp; <span><b>GSTIN :
    </b>27ARCPJ2417M1ZR</span>
  
    </td> 



</tr>

     </table>



                </div>
                </td>
                </tr>
                </table>

            </div>

            <div class="boder-line"></div>
            <div class="row">
            <div class="col-md-12">
                <div class="bg-light p-2">
                    <table width="100%">
                        <tr>
                            <td width="33.33%">
                                <span><b>Outstanding Id</b>: {{ $orderInvoice->first()->order->id }}</span>
                            </td>
                            <td width="33.33%" class="float-right">
                                <span><b>Outstanding Date</b>: {{ $orderInvoice->first()->order->created_at }}</span>
                            </td>
                            
                        </tr>
                    </table>
                </div>
            </div>
          
        </div>
    </div>      <div class="col-md-12">



                <table width="100%">
         <tr>
        <td width="33.33%" style="vertical-align: top;">
            <address class="mt-3">
                <b>BILL TO</b><br><br>
            
                
                <p class="pl"><b>{{ $orderInvoice->first()->order->billing_address }} </b>: </p>

                
            </address>
        </td>

        <td width="33.33%" style="vertical-align: top;">
            <address class="mt-3">
                <b>SHIP TO</b><br><br>
              
                <p class="pl"><b>{{ $orderInvoice->first()->order->shipping_address }}</b>: </p>
              
                
            </address>
        </td>



        
            </tr>
</table>


                </div>


            </div>

        </div>
    </div>
    <div class="boder-line"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table  pb-5">
                            <thead>
                                <tr class="bg-light">
                                    <td><strong>Sr </strong></td>
                                    <td class="text-center"><strong>Product Name</strong></td>
                                    <td class="text-center"><strong>Qty type </strong></td>
                                    <td class="text-center"><strong>price</strong></td>
                                    <td class="text-center"><strong>total QTY</strong></td>
                                    <td class="text-center"><strong>Total price</strong></td>
                                  
                                
                                </tr>
                            </thead>
                            <tbody>
                            @php $serialNumber = 1 @endphp
                            @foreach($orderInvoice as $orderItem)
                                <tr>
                                    <td></td>
                                    <td class="text-center">{{ $serialNumber }}</td>
                                    <td class="text-center">{{ $orderItem->product->product_name }}</td>
                                   
                                    <td class="text-center">{{ $orderItem->price }}</td>
                                    <td class="text-center">{{ $orderItem->quantity }}</td>
                                    <!-- <td class="text-center">{{ $orderItem->price }}</td> -->

                                  
                                    <td class="text-right">{{ $orderItem->price }}<br><br></td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <br>
                        <br>
                        <div class="p-2 bg-light">
                            <table width="100%">

                                 
                                <tr>
                                    <td width="50%">
                                        <span>TOTAL AMOUNT</span>
                                    </td>
                                    <td width="50%" class="float-right">
                                        <span>{{ $orderItem->price }}</span>
                                    </td>
                                    
                                </tr>

                                
                            </table>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="boder-line"></div>
    <div class="">
        <div class="table-responsive">
            <table width="100%">
                <tr>
                    <td width="60%">
                        <div class="col-md-8">
                            <h6 class="mt-3"><strong>BANK DETAILS</strong> </h6>

                           <table class="bank-details">
                                <tr>
                                    <td width="60%">Name</td>
                                    <td>  </td>
                                </tr>
                                <tr>
                                    <td>IFSC Code: </td>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <td>Account No:</td>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <td>Bank & Branch: </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>SWIFT code </td>
                                    <td></td>
                                </tr>
                            </table>

   


                        </div>
                    </td>
                    <td width="40%">
                        <div class="">
                            <table class="table table-bordered table-striped table-sun-total" width="100%">
                                <tr>
                                    <td>TOTAL AMOUNT</td>
                                    <td><span>{{ $orderItem->price }}</span></td>
                                </tr>
                                <tr class="bg-light ">
                                    <td><strong>GST</strong></td>
                                    <td><span></span></td>
                                </tr>
                                <tr>
                                    <td>TOTAL BILLING </td>
                                    <td><span>{{ $orderItem->price }}</span></td>
                                </tr>



                            </table>
                            
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
    <br><br>
    
</div>
</div>
</div>





<div id="absolute-element-footer2">
        <div class="container p-0 pl-20">
        <table width="100%">
                            <tr >
                                
                                <td class="thankyouUnder" width="50%">
                                    <h3 class="mb-0 float-right" ><b>THANK YOU</b>  </h3> 
                                </td>
 <td class="float-right" width="50%">
    <img src="{{ asset('frontweb/assests/images/new_logo.png')}}" class="img-fluid" width="20%"> 
     <h6>AUTHORISED SIGNATORY FOR</h6> 
    <span style="font-size:10px;" class="mb-0">ZONIK</span></td>


    <!-- <td class="float-right" width="50%"><span style="font-size:10px; "  class="mb-0" >www.primehealthpharma.com</span></td> -->
</tr> 
                           
            </table>  
            <hr class="hr-line">                  
       
 <table width="100%">
    <tr>
  
          <td width="70%" style="vertical-align: top;">
            <!-- Terms and conditions section -->
            <table>
                <tr>
                    <td> <b>Terms and conditions</b> </td>
                </tr>
                <tr>
                    <td style="font-size:12px">
                        <ul>
                            <li>Goods once sold will not be taken back or exchanged</li>
                            <li>All disputes are subject to MUMBAI jurisdiction only</li>
                        </ul>
                    </td>
                </tr>
                
            </table>
        </td>
               
        </td>
    </tr>

   



</table>























</body>

</html>