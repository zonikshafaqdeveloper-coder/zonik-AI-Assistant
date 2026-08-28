<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link href="{{ asset('frontweb/assests/css/style.css')}}" rel="stylesheet">
    <title>Dizcover Business</title>
  </head>

  <style>
  /* Example CSS for styling categories and subcategories */
.category-list {
    list-style: none;
    padding: 0;
}

.category-list li {
    margin-bottom: 20px;
}

.category-list h3 {
    font-size: 24px;
    margin: 0;
}

.category-list img {
    max-width: 200px;
    height: auto;
    margin-top: 10px;
}

.subcategory-list {
    list-style: none;
    padding-left: 20px;
    margin-top: 10px;
}

.subcategory-list li {
    margin-bottom: 10px;
}

.subcategory-list h4 {
    font-size: 18px;
    margin: 0;
}

.subcategory-list img {
    max-width: 150px;
    height: auto;
    margin-top: 5px;
}

</style>
  <body>

    <!-- Header Started -->
    <section class="header-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('frontweb/assests/images/logo-name.png') }}" class="logo">
                </div>
                <div class="col-md-6 text-end">
                    <a href="#" class="shadow-inner-btn text-primary">LOGIN / SIGNUP</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Header Ended -->

    <!-- Banner Started -->
    <section class="banner-section">
        
            <div class="row">
                <div class="col-md-6">
                    <div class="banner-left">
                        <h1><span class="text-primary">We connect the most 
                            prestigious leading brands 
                            through</span><span class="fw-600">  our smart supply 
                            chain network</span></h1>
                            <a href="#" class="td-btn mt-4">Buy Now &nbsp;&nbsp; <i class="fa-solid fa-angle-right rih"></i></a>
                    </div>
                </div>
        <div class="col-md-6">
            <div class="banner-img">
            <img src="{{ asset('frontweb/assests/images/banner-left-side.png') }}" class="img-fluid">
        </div>
        </div>
            </div>
        
        
    </section>
    <!-- Banner Ended -->
    <!-- Features Started -->
    <section class="features-section ">
        <div class="container">
            <div class="row gx-5">
                <div class="col-md-3">
                    <div class="features-div">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <img src="{{ asset('frontweb/assests/images/h1.png') }}">
                            </div>
                            <div class="col-md-9">
                                <h6 class="text-primary">Fast Delivery</h6>
                                <p class="text-grey">Same day Delivery</p>
                            </div>
                        </div>
                       
                       
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="features-div">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <img src="{{ asset('frontweb/assests/images/h2.png') }}">
                            </div>
                            <div class="col-md-9">
                                <h6 class="text-primary">Secure Payment</h6>
                                <p class="text-grey">Totally secured</p>
                            </div>
                        </div>
                       
                       
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="features-div">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <img src="{{ asset('frontweb/assests/images/h3.png')}}">
                            </div>
                            <div class="col-md-9">
                                <h6 class="text-primary">Support</h6>
                                <p class="text-grey">Full support</p>
                            </div>
                        </div>
                       
                       
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="features-div">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <img src="{{ asset('frontweb/assests/images/h4.png') }}">
                            </div>
                            <div class="col-md-9">
                                <h6 class="text-primary">Offers</h6>
                                <p class="text-grey">Get offer in Bulk</p>
                            </div>
                        </div>
                       
                       
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Features Ended -->
    <!-- Steps Started -->
    <section class="step-section">
        <div class="">
            <div class="row align-items-center">
                <div class="col-md-12 mb-4">
                    <h1 class="text-center"><span class="text-primary">Advanced</span><span class="fw-600">  Marketplace Platform</span></h1>
                </div>
                <div class="col-md-8 m-auto">
                    <p class="paragraph text-center mb-5">Our platforms provide a vast selection of grocery products with complete B2B pricing confidentiality through our KYC process at the best margin price.</p>
                </div>
                <div class="col-md-5">
                    <img src="{{ asset('frontweb/assests/images/circle-rotate.png')}}" class="img-fluid image">
                    <div class="text-center mob-div">
                    <img src="{{ asset('frontweb/assests/images/aa.png')}}" class="img-fluid">
                </div>
                </div>
                <div class="col-md-7">
                    <div class="points">
                        <ul>
                            <li><span class="num">01</span>&nbsp;&nbsp;&nbsp;&nbsp;Create an account</li>
                            <li><span class="num">02</span>&nbsp;&nbsp;&nbsp;&nbsp;Add items in order list and submit for the prices</li>
                            <li><span class="num">03</span>&nbsp;&nbsp;&nbsp;&nbsp;Get best prices within 24 hours</li>
                            <li><span class="num">04</span>&nbsp;&nbsp;&nbsp;&nbsp;Now , place your order and pay via Net-banking or UPI</li>
                            <li><span class="num">05</span>&nbsp;&nbsp;&nbsp;&nbsp;Get you order at your time and place</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Steps Ended -->
    <!-- Products Started -->



    


 <!-- Product Fruit & Vegetables Section Start -->
 <section class="product-section-3 bg-light padding-100">
        <div class="container-fluid-lg">
        @foreach ($categories as $category)
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="title d-flex bre-img align-items-center">
                    <img src="/uploads/{{ $category->image }}"> <h2>{{ $category->category_name }}</h2>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="#" class="red-btn">View All</a>
                </div>
            </div>
            

            <div class="row">
                <div class="col-12">
                    <div class=" owl-carousel owl-theme" id="owl-carousel1">
                    @foreach ($category->subcategories as $subcategory)
                        <div class="item">
                            <div class="product-box-4 wow fadeInUp">
                                <div class="product-image product-image-2">
                                    <a href="#">
                                        <img src="/uploads/{{ $subcategory->image }}"
                                            class="img-fluid blur-up lazyload" alt="">
                                    </a>

                                    <ul class="option">
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Quick View">
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#view">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
                                            <a href="javascript:void(0)" class="notifi-wishlist">
                                                <i class="fa-regular fa-heart"></i>
                                            </a>
                                        </li>
                                      
                                    </ul>
                                </div>

                                <div class="product-detail">
                                   
                                    <a href="#">
                                        <h5 class="name text-title">{{ $subcategory->name }} </h5>
                                    </a>
                                  

                                    <div class="addtocart_btn">
                                        <button class="add-button addcart-button btn buy-button text-light">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                        <div class="qty-box cart_qty">
                                            <div class="input-group">
                                                <button type="button" class="btn qty-left-minus" data-type="minus"
                                                    data-field="">
                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                </button>
                                                <input class="form-control input-number qty-input" type="text"
                                                    name="quantity" value="1">
                                                <button type="button" class="btn qty-right-plus" data-type="plus"
                                                    data-field="">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach 


                      
                    </div>
                </div>
            </div>
            @endforeach 
        </div>
    </section>
    <!-- Product Fruit & Vegetables Section End -->


    

   


    <!-- Client Logo Started -->
    <section class="padding-100">
    <div class="container">
         <section class="customer-logos slider">
            <div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png')}}"></div>
            <div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png')}}"></div>
            <div class="slide"><img src="{{ asset('frontweb/assests/images/c3.png')}}"></div>
            <div class="slide"><img src="{{ asset('frontweb/assests/images/c4.png')}}"></div>
            <div class="slide"><img src="{{ asset('frontweb/assests/images/c5.png')}}"></div>
            <div class="slide"><img src="{{ asset('frontweb/assests/images/c6.png')}}"></div>
            <div class="slide"><img src="{{ asset('frontweb/assests/images/c1.png')}}"></div>
            <div class="slide"><img src="{{ asset('frontweb/assests/images/c2.png')}}"></div>
            
           
         </section>
      </div>
    </section>
    <!-- Client Logo Ended -->
    <!-- Testimonial Started -->
    <section class="padding-100 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center mb-4"><span class="text-primary">Clients</span><span class="fw-600">  Feedback</span></h1>
                    <div class="testimonial">
                        <div class="owl-carousel owl-theme">
                          <div class="item">
                           <div class="testimonial-div">
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="{{ asset('frontweb/assests/images/t1.png')}}" class="img-fluid">
                                </div>
                                <div class="col-md-8">
                                    <h5>Mr John Deo</h5>
                                    <h6 class="text-primary">SS Cafe</h6>

                                </div>
                                <div class="col-md-2">
                                    <div class="quote">
                                    <img src="{{ asset('frontweb/assests/images/white-quote.png')}}" class="">
                                </div>
                                </div>
                                <div class="col-md-12">
                                    <p class="paragraph mt-3">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,</p>
                                </div>
                            </div>
                           </div>
                          </div>
                          <div class="item">
                            <div class="testimonial-div-red">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="{{ asset('frontweb/assests/images/t1.png')}}" class="img-fluid">
                                    </div>
                                    <div class="col-md-8">
                                        <h5>Mr John Deo</h5>
                                        <h6 class="text-primary">SS Cafe</h6>
    
                                    </div>
                                    <div class="col-md-2">
                                        <div class="quote">
                                        <img src="{{ asset('frontweb/assests/images/bquote.png')}}" class="">
                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                        <p class="paragraph mt-3">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,</p>
                                    </div>
                                </div>
                               </div>
                          </div>
                          <div class="item">
                            <div class="testimonial-div">
                             <div class="row">
                                 <div class="col-md-2">
                                     <img src="{{ asset('frontweb/assests/images/t1.png')}}" class="img-fluid">
                                 </div>
                                 <div class="col-md-8">
                                     <h5>Mr John Deo</h5>
                                     <h6 class="text-primary">SS Cafe</h6>
 
                                 </div>
                                 <div class="col-md-2">
                                     <div class="quote">
                                     <img src="{{ asset('frontweb/assests/images/white-quote.png')}}" class="">
                                 </div>
                                 </div>
                                 <div class="col-md-12">
                                     <p class="paragraph mt-3">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,</p>
                                 </div>
                             </div>
                            </div>
                           </div>
                           <div class="item">
                             <div class="testimonial-div-red">
                                 <div class="row">
                                     <div class="col-md-2">
                                         <img src="{{ asset('frontweb/assests/images/t1.png')}}" class="img-fluid">
                                     </div>
                                     <div class="col-md-8">
                                         <h5>Mr John Deo</h5>
                                         <h6 class="text-primary">SS Cafe</h6>
     
                                     </div>
                                     <div class="col-md-2">
                                         <div class="quote">
                                         <img src="{{ asset('frontweb/assests/images/bquote.png')}}" class="">
                                     </div>
                                     </div>
                                     <div class="col-md-12">
                                         <p class="paragraph mt-3">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,</p>
                                     </div>
                                 </div>
                                </div>
                           </div>
                         
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Ended -->
    <!-- FAQ Started -->
    <section class="faq-section padding-100 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center mb-4"><span class="text-primary">Frequently Asked</span><span class="fw-600">  Questions</span></h1>
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Why is Dizcover good for my restaurant operations ?
                            </button>
                          </h2>
                          <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item's accordion body.</div>
                          </div>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                How can I sign up for a Dizcover account ?
                            </button>
                          </h2>
                          <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this being filled with some actual content.</div>
                          </div>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                I run a restaurant. Can I purchase from Dizcover ?
                            </button>
                          </h2>
                          <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
                          </div>
                        </div>
                   
                      </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ Ended -->
    <!-- Footer Started -->
    <section class="footer-section ">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4 class="footer-heading mb-2">GET IN TOUCH</h4>
                    <div class="hr"></div>
                    <div class="d-flex mt-4 social align-items-center">
                        <i class="fa-solid fa-phone"></i>
                        <p class="paragraph">+91 8597412036 / +91 9857410236</p>
                    </div>
                    <div class="d-flex mt-4 social align-items-center">
                        <i class="fa-solid fa-envelope"></i>
                        <p class="paragraph">support@dizcoverbusiness.com</p>
                    </div>
                    <div class="d-flex mt-4 social align-items-center">
                        <i class="fa-solid fa-location-dot"></i>
                        <p class="paragraph">Mulund , Maharashtra</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <h4 class="footer-heading mb-2">QUICK LINKS</h4>
                    <div class="hr"></div>
                    <div class="links mt-4">
                        <ul>
                            <li ><a href="#" class="text-light"><i class="fa-solid fa-angle-right text-center lir"></i>Login / Signup</a></li>
                            <li class="mt-3"><a href="#" class="text-light"><i class="fa-solid fa-angle-right text-center lir"></i>Catalogue</a></li>
                            <li class="mt-3"><a href="#" class="text-light"><i class="fa-solid fa-angle-right text-center lir "></i>Terms & Conditions</a></li>
                            <li class="mt-3"><a href="#" class="text-light"><i class="fa-solid fa-angle-right text-center lir"></i>Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <h4 class="footer-heading mb-2">FOLLOW US</h4>
                    <div class="hr"></div>
                    <div class="social-links mt-4">
                        <ul>
                            <li class="text-light "><i class="fa-brands fa-instagram color-primary so-l"></i> Instagram</li>
                            <li class="text-light mt-3"><i class="fa-brands fa-square-facebook color-primary so-l"></i>Facebook</li>
                            <li class="text-light mt-3"><i class="fa-brands fa-linkedin color-primary so-l"></i> Lindekin</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <h4 class="footer-heading mb-2">NEWSLETTER</h4>
                    <div class="hr"></div>
                    <p class="paragraph text-light mt-4">You will be notified when somthing new will be appear.</p>
                    <form class="mt-4">
                        <div class="mb-3">
                          <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Email">
                        </div>
                </div>



            </div>
        </div>
        <div class="copyright">
            <div class="div-hr mb-5"></div>
            <div class="col-md-12">
                <div class="copyright-sec d-flex align-items-center justify-content-center">
                    <p class="text-light mr-2" >Copyright @2025</p> <img src="{{ asset('frontweb/assests/images/white-logo.png')}}">
                </div>
            </div>
        </div>
    </section>
    <!-- Footer Ended -->





<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function(){
        $('.customer-logos').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1500,
        arrows: false,
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 3
            }
        }]
    });
});
</script>
<script>
$(function() {
    // Owl Carousel
    var owl = $(".owl-carousel");
    owl.owlCarousel({
      items: 2,
      margin: 30,
     autoplay:true,
      loop: true,
      nav: false,
    });
  });
</script>


<script>
$(document).ready(function() {
    $('.category-tab').click(function() {
        var categoryId = $(this).data('category-id');
        $('.subcategory-items').hide();
        $('.subcategory-items[data-category-id="' + categoryId + '"]').show();
    });
});
</script>






  </body>
</html>