@extends('web.layouts.app')
@section('content')

<style>
    .para
    {
        margin-top: 20px;
        font-size: 15px;
        margin-bottom: 0.5em;
    }
     .terms
     {
         margin-top: 2em;
     }  
     ol {
            margin-top: 1em;
           
        }

    ol li
    {
    margin-bottom: 0.5em; /* Add some spacing between list items */
    font-size: 15px;
    }
    ol li 
    {
        margin-bottom: 0.5em; /* Add some spacing between list items */
        display: list-item; /* Ensure each list item is displayed as a block */
        }
        p, ul {
            margin-top: 0.5em;
            margin-bottom: 0.5em;
        }
        ul {
    list-style-type: disc;
    padding-left: 20px;
}
        ul li {
            margin-bottom: 10px; /* Adjust margin between list items */
        }

        .step-section1 {
    padding: 44px 0px;
}
*{
    font-weight: normal;
}
</style>


<section class="step-section1">
<div class="container">
    <div class="row">
         <h2 class="text-center">Payments & Refunds</h2>
<div class="col-md-12 terms">
    <h3><strong>Cancellation</strong></h3>
    <p class="para">
    	All Payments can be done via Following methods.
    </p>

          <ol>
            <li><strong>Online </strong>
                <ol>	
                    <li>Credit Card</li>
                    <li>Debit Card</li>
                    <li>UPI</li>
                    <li>Scanner Code</li>
                </ol>
            </li>
            <li><strong>Cod – Applicable only on certain locations </li></strong>
          </ol>
    
</div>
              <div class="col-md-12 terms">

               <h3><strong>Credit terms </strong> </h3>
                <!--</p>-->


                <ol>	
                    <li>We provide credit term to Selective customers only based on their Credit background and experience with our businesses.</li>

<li>If Overdue are continuing and poor payments as per due dates are done then the same will be revoked or reduced in terms of days</li>

<li>Note more the credit terms the impact on prices according too</li>

<li>Overdue payments will be open for Interest of 24% per annum claimable from customer</li>

<li>Note we are MSME registered hence any issues of payment crossing beyond certain mark will have other legal implications</li>

                </ol>
            </li>
            </div>



              <div class="col-md-12 terms">

               <h3><strong>Refund will be only issued in case you as below :  </strong> </h3>
                <!--</p>-->


                <ol>	
                    <li>You have prepaid the order while booking and either order completely OR partially is cancelled by you before PO is generated.</li>

<li>Wrong item delivered or damaged or expired during delivery</li>

<li>Out of stock from our end during order confirmation done by our team while accepting the order when order is prepaid.</li>

                    
                </ol>
                 <p class="para">
    	Refund will be issued within 3 to 5 working days depending on the bank and settlement time. Be rest assured the refund will be issues in complete transparency once the issue is resolved by our customer care team with the customer.
    </p>
            </li>
            </div>
    

<!-- <div class="col-md-12 terms">

               <h3><strong>Shipping Policy</strong> </h3>
                </p>


                <ol>	
                    <li>Shipping will be done as per the selection criteria set post checkout from Order Cart. We offer same day delivery to selected areas and other than that you can select upto 4 days in future date as per your requirements.</li>
                    <li>Please not the cut off time during ordering as post the cut off time you wont be getting that dates delivery.</li>
                    <li>During delivery itself if any problem with the product needs to be raised by customer as post delivery no liability or no acceptance of any claims will be considered.</li>
                    <li>Post delivery Sign and stamp is Mandatory to be given on invoice as received to our Delivery partner as a proof of acceptance and final closure of that particular order.</li>
                </ol>
            </li>
            </div> -->









        </div>
        </div>
       
    </div>
</div>
</section>

        @endsection