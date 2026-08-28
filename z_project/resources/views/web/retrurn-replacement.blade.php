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
         <h2 class="text-center">Return, Replacement & Cancellation</h2>
<div class="col-md-12 terms">
    <h3><strong>CANCELLATION</strong></h3>
    <p class="para">
        Orders can only be cancelled by calling our customer care on the same day of order placed before 5 pm. Post that orders placed cannot be cancelled in any way. Cancellation can be done by calling customer care @ +919163411489 or Whatsapp, the order will be considered cancelled. Payment will be returned as per Refund Policy.
    </p>
    <p class="para">
        In the event of an item on your Order being unavailable, we will contact you on the phone number provided to us at the time of placing the Order and inform you of such unavailability. You can either replace with some other brand or you will be entitled to cancel the Part Order or full Order and shall be entitled to a refund in accordance with our refund policy again within the above mentioned window only.
    </p>
</div>
<div class="col-md-12 terms">
        <h3><strong>RETURNS / REPLACEMENT</strong></h3>
        <p class="para">
            Returns or Replacement can be done at the time of delivery only, later on any claims or any issues won't be considered for return.
        </p>
        <p class="para">
            Items can be only returned on the following grounds:
        </p>
        <ol>
            <li>Damaged</li>
            <li>Expired</li>
            <li>Different from what was ordered</li>
        </ol>
        <p class="para">
            All returns have to be initiated by calling our customer care team at the time of delivery only. Based on our team's review and verification, return can be processed.
        </p>
    </div>

    <div class="col-md-12 terms">
        <h3><strong>Other Cancellation Terms</strong></h3>
        <p class="para">
        We reserve the sole right to cancel your Order in the following circumstances as follows:
        </p>
        <ol>
            <li>In the event of the designated address falls outside the delivery zone offered by us</li>
            <li>Failure to contact you by phone or email at the time of confirming the Order booking</li>
            <li>Failure to deliver your Order due to lack of information, direction or authorization from you at the time of delivery</li>
            <li>Unavailability of all the items Ordered by you at the time of booking the Order</li>
            <li>In case the delivery person is not allowed to deliver the goods.</li>
        </ol>
        <p class="para">
        We ensure 100% customer satisfaction with our easy returns and refund processes so that our customer can have full trust on us and our platform..
        </p>
    </div>












        </div>
        </div>
       
    </div>
</div>
</section>

        @endsection