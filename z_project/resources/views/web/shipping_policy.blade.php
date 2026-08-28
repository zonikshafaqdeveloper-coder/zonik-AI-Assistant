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
         <h2 class="text-center">Shipping Policy & Delivery System</h2>
<div class="col-md-12 terms">
    <h3><strong>Zonik has 2 types of Delivery System.</strong></h3>
    <!-- <p class="para">
    	All Payments can be done via Following methods.
    </p> -->


     <ol type="A">
<li>Slot Wise Delivery – Quick Service within 10 Km Radius Only
<ol type="a">

<li>Slot 1 : Order before : 5:30 pm to get delivery , Next day between 11 am to 2 pm</li>
<li>Slot 2 : Order before : 12 pm to get delivery , Same day between 3 pm to 8 pm</li>
<li>c.	10 km radius from Zonik Quick service store – For now Currently operating in Mulund only – Covering Thane , Mulund , Bhandup, Airoli.</li>
</ol>
</li>

<li>Fixed Slot Delivery – <strong> Anywhere in MMR – Mumbai  , Navi Mumbai , Thane & around region </strong>

<ol type="a">
<li>All materials will be delivered between 10 am to 8 pm </li>
<li>10 km radius from Zonik Quick service store – For now Currently operating in Mulund only – Covering Thane , Mulund , Bhandup, Airoli.</li>
</ol>

</li>
</ol>

          
    
</div>
              <div class="col-md-12 terms">

               <h3><strong>Terms of Delivery: </strong> </h3>
              
<p class="para">
    	All items post delivery , customer needs to give acceptance with stamp and sign . If any issue with material delivered in terms of price , quality or any issue, it has to be shown to delivering person and marked in Invoice before accepting the material.
Later on no material will be accepted or invoice will be changed due to any reason whatsoever. 

    </p>

            </div>
    










        </div>
        </div>
       
    </div>
</div>
</section>

        @endsection