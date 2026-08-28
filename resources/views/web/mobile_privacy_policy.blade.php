@extends('mobile.mobile-app')
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
            list-style-type: disc; /* Ensure list items have bullets */
            padding-left: 20px; /* Adjust padding for better alignment */
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
         <h2 class="text-center">Privacy Policy </h2>
        <div class="col-md-12 terms">
        <h4><strong>YOUR CONSENT</strong></h4>
        <p class="para">By using the ZONIK Platform and the Services, you agree and consent to the collection, transfer, use, storage, disclosure and sharing of your information as described and collected by us in accordance with this Policy. If you do not agree with the Policy, please do not use or access the ZONIK Platform.
        </p>
        </div>


        <div class="col-md-12 terms">
        <h4><strong>POLICY CHANGES</strong></h4>
        <p class="para">We may occasionally update this Policy and such changes will be posted on this page. If we make any significant changes to this Policy we will endeavor to provide you with reasonable notice of such changes, such as via prominent notice on the ZONIK Platform or to your email address on record and where required by applicable law, we will obtain your consent. To the extent permitted under the applicable law, your continued use of our Services after we publish or send a notice about our changes to this Policy shall constitute your consent to the updated Policy.
        </p>
        </div>

     

        <div class="col-md-12 terms">
        <h4><strong>LINKS TO OTHER WEB PLATFORMS</strong></h4>
        <p class="para">Our Service may contain links to third-party web Platforms or services that are not owned or controlled by ZONIKS
        </p>
        <p class="para">ZONIK has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party web Platforms or services. You further acknowledge and agree that ZONIKS shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by or in connection with use of or reliance on any such content, goods or services available on or through any such web Platforms or services. We strongly advise you to read the terms and conditions and privacy policies of any third-party web Platforms or services that you visit.
        </p>
        </div>


      
        <div class="col-md-12 terms">
       <h4> <strong>INFORMATION WE COLLECT FROM YOU  :</strong></h4><br></br>
       <h4><strong>PERSONAL IDENTIFICATION INFORMATION</strong></h4>
        <p class="para">We may collect personal identification information from Users in a variety of ways, including, but not limited to, when Users visit our Platform, register on the Platform, post requirement, subscribe to the newsletter, respond to a survey, fill out a form, and in connection with other activities, services, features or resources we make available on our Platform. Users may be asked for, as appropriate, name, email address, qualification certificate copy, mailing address, phone number, credit and debit card information. Users may, however, visit our Platform anonymously. We will collect personal identification information from Users only if they voluntarily submit such information to us. Users can always refuse to supply personally identification information, except that it may prevent them from engaging in certain Platform related activities
        </p>
        </div>
        <div class="col-md-12 terms">
        <h4><strong>NON-PERSONAL IDENTIFICATION INFORMATION</strong></h4>
        <p class="para">We may collect non-personal identification information about Users whenever they interact with our Platform. Non-personal identification information may include the browser name, the type of computer and technical information about Users means of connection to our Platform, such as the operating system and the Internet service providers utilized including IP address and other similar information. 
        </p>
        </div>
        <div class="col-md-12 terms">
        <h4><strong>HOW WE USE COLLECTED INFORMATION?</strong></h4>
        <ol>
            <li>ZONIK collects and uses Users' personal information for the following purposes:</li>

<li>To personalize user experience: We may use information in the aggregate to understand how our Users as a group use the services and resources provided on our Platform.</li>

<li>To improve our Platform: We continually strive to improve our web Platform offerings based on the information and feedback we receive from you.</li>

<li>To improve customer service: Your information helps us to more effectively respond to your customer service requests and support needs.</li>

<li>To process transactions: We may use the information Users provide about themselves when placing an order only to provide service to that order. We do not share this information with outside parties except to the extent necessary to provide the service by our registered and premium members.</li>

<li>To administer content, promotion, survey, or other Platform features: To send Users information they agreed to receive about topics we think will be of interest to them.</li>

<li>To send periodic emails: The email address Users provide for registering with ZONIK will only be used to send them information and updates pertaining to their order. It may also be used to respond to their inquiries, and/or other requests or questions. If User decides to opt-in to our mailing list, they will receive emails that may include company news, updates, offers, related product or service information, etc. If at any time the User would like to unsubscribe from receiving future emails, they can submit an unsubscribe request. We've included an unsubscribe option at the bottom of each email.</li>

        </ol>
        </div>






        <div class="col-md-12 terms">
         <h4><strong>HOW WE PROTECT YOUR INFORMATION?</strong></h4>
        <p class="para">We adopt appropriate data collection, storage and processing practices and security measures to protect against unauthorized access, alteration, disclosure or destruction of your personal information, username, password, transaction information and data stored on our Platform.</p>
         </div>


         <div class="col-md-12 terms">
         <h4><strong>SHARING YOUR PERSONAL INFORMATION</strong></h4>
<p class="para">We do not sell, trade, or rent Users personal identification information to any 3rd party. We may share generic aggregated demographic information not linked to any personal identification information regarding visitors and users with our business partners, trusted affiliates and advertisers for the purposes outlined above.</p></div>

<div class="col-md-12 terms">
<h4><strong>External links disclaimer for MOBILE APPS</strong></h4>
<p class="para">The Platform and our Mobile application may contain links to other web Platforms or content belonging to or originating from third parties or links to other web Platforms and features in banners or other advertising. Such external links are not investigated, monitored, or checked for accuracy , reliability ,adequacy, validity or completeness by us.</p></div> 

<div class="col-md-12 terms">
<h4><strong>COOKIES</strong></h4>
<p class="para">Our ZONIK Platform and third parties with whom we partner, may use cookies, pixel tags, web beacons, mobile device IDs, “flash cookies” and similar files or technologies to collect and store information with respect to your use of the Services and third-party web platforms.</p>

<p class="para">Cookies are small files that are stored on your browser or device by web platforms, apps, online media and advertisements. We use cookies and similar technologies for purposes such as:</p>
    
    <ol>
   
   <li>Authenticating users;</li>

<li>Remembering user preferences and settings;</li>

<li>Determining the popularity of content;</li>

<li>Delivering and measuring the effectiveness of advertising campaigns;</li>

<li>Analyzing Platform traffic and trends, and generally understanding the online behaviors and interests of people who interact with our services.</li>


    </ol>
    </div>



<!-- 
    <div class="col-md-12 terms">
        <h4></h4>
        <p class="para">
        </p>
        </div> -->

        


        <div class="col-md-12 terms">
        <h4> <strong>  THIRD PARTY WEB PLATFORMS</strong></h4>
        <p class="para">Users may find advertising or other content on our Platform that link to the Platforms and services of our partners, institutes, advertisers, sponsors and other third parties. We do not control the content or links that appear on these Platforms and are not responsible for the practices employed by web Platforms linked to or from our Platform. In addition, these Platforms or services, including their content and links, may be constantly changing. These Platforms and services may have their own privacy policies and customer service policies. Browsing and interaction on any other web Platform, including web Platforms which have a link to our Platform, is subject to that web Platform's own terms and policies.</p></div>
       </div>


       <div class="col-md-12 terms">
       <h4><strong> ADVERTISING</strong></h4>
       <p class="para">Advertisements appearing on our Platform may be delivered to Users by advertising partners, who may set cookies. These cookies allow the ad server to recognize your computer each time they send you an online advertisement to compile non-personal identification information about you or others who use your computer. This information allows ad networks to, among other things, deliver targeted advertisements that they believe will be of most interest to you. This privacy policy does not cover the use of cookies by any advertisers.</p></div>
       

    <div class="col-md-12 terms">
        <h4><strong>GOOGLE ADSENSE</strong></h4>
        <p class="para">Some of the ads may be served by Google. Google's use of the DART cookie enables it to serve ads to Users based on their visit to our Platform and other Platforms on the Internet. DART uses "non personally identifiable information" and does NOT track personal information about you, such as your name, email address, physical address, etc. You may opt out of the use of the DART cookie by visiting the Google ad and content network privacy policy at www.google.com/privacy_ads.html</p>
    </div>

    <div class="col-md-12 terms">
        <h4><strong>CHANGES TO THIS PRIVACY POLICY</strong></h4>
        <p class="para">ZONIK has the discretion to update this privacy policy at any time. We encourage Users to frequently check this page for any changes to stay informed about how we are helping to protect the personal information we collect. You acknowledge and agree that it is your responsibility to review this privacy policy periodically and become aware of modifications.</p>
    </div>

    <div class="col-md-12 terms">
        <h4><strong>YOUR ACCEPTANCE OF THESE TERMS</strong></h4>
        <p class="para">By using this Platform, you signify your acceptance of this policy. If you do not agree to this policy, please do not use our Platform. Your continued use of the Platform following the posting of changes to this policy will be deemed your acceptance of those changes.</p>
    </div>

    <div class="col-md-12 terms">
        <h4><strong>GRIEVANCE REDRESSAL</strong></h4>
        <p class="para">Any complaints, abuse or concerns with regards to content and or comment or breach of these terms shall be immediately informed to the designated Grievance Officer as mentioned below via in writing or through email signed with the electronic signature to connect@dizcover.in</p>
    </div>

    <div class="col-md-12 terms">
        <h4><strong>CONTACTING US</strong></h4>
        <p class="para">If you have any questions about this Privacy Policy, the practices of this Platform, or your dealings with this Platform, please contact us at: connect@zonik.in</p>
    </div>








































        </div>
        </div>
       
    </div>
</div>
</section>

        @endsection