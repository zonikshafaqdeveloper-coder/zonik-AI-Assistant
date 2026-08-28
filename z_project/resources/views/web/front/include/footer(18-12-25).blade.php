<style>
    @media (min-width: 769px) {
    .hide-desktop {
        display: none;
    }
}
@media (min-width: 320px) and (max-width: 767px) {
    .hide-mobile {
        display: none;
    }    
}

/* Disabled state */
.coupon-item.disable {
    pointer-events: none; /* Disable clicks */
    opacity: 0.5; /* Make it visually appear disabled */
    cursor: not-allowed; /* Indicate it's not clickable */
}

/* Enabled state */
.coupon-item.enable {
    pointer-events: auto; /* Enable clicks */
    opacity: 1; /* Reset opacity */
    cursor: pointer; /* Make it clickable */
}
</style>

<!-- Footer Started -->
<section class="footer-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 my-2 ">
                <h4 class="footer-heading mb-2 ul-padding">Zonik (Infigourmet networks pvt ltd)</h4>
                <div class="hr"></div>
                <p class=" text-light mt-3">Zonik is a premier supplier specializing in providing top-quality products to restaurants and outlets. We understand the unique needs of the food service industry and are committed to delivering the freshest and most reliable products to our clients. Whether you run a small cafe or a large restaurant chain, Zonik ensures that you have everything you need to satisfy your customers and enhance their dining experience. Trust Zonik to be your dedicated partner in success, offering exceptional service and dependable supply solutions tailored to your specific requirements.

                </p>

            </div>

            <div class="col-md-2 my-2 " >
                <h4 class="footer-heading mb-2 ul-padding">QUICK LINKS</h4>
                <div class="hr"></div>
               <div class="links mt-3">
                    <ul class="footer-flex">
                        <li><a href="{{ route('customer.logout') }}" class="text-light"><i
                                    class="fa-solid fa-angle-right text-center lir"></i>Login / Signup</a></li>
                        <!--<li class="mt-3"><a href="{{ route('subcateg', ['category_id' => 15]) }}" class="text-light"><i-->
                        <!--            class="fa-solid fa-angle-right text-center lir"></i>Catalogue</a></li>-->
                        
                         <li class="mt-3"><a href="https://zonik.in/shipping-policy" class="text-light"><i
                                    class="fa-solid fa-angle-right text-center lir "></i>Shipping Policy</a>
                        </li>
                        
                        <li class="mt-3"><a href="https://zonik.in/terms-condition" class="text-light"><i
                                    class="fa-solid fa-angle-right text-center lir "></i>Terms & Conditions</a>
                        </li>
                        <li class="mt-3"><a href="https://zonik.in/privacy_policy" class="text-light"><i
                                    class="fa-solid fa-angle-right text-center lir"></i>Privacy Policy</a></li>
                  
                  
                  <li class="mt-3"><a href="https://zonik.in/payments-refunds" class="text-light"><i
                        class="fa-solid fa-angle-right text-center lir"></i>Payments Refunds</a></li>
                        <li class="mt-3"><a href="https://zonik.in/return-replacement" class="text-light"><i
                        class="fa-solid fa-angle-right text-center lir"></i>Return Replacement</a></li>
                  
                  
                  
                  
                  
                  
                  
                  
                    </ul>
                </div>
            </div>
            <div class="col-md-2 my-2 ">
                <h4 class="footer-heading mb-2 ul-padding">FOLLOW US</h4>
                <div class="hr"></div>
                <div class="social-links mt-3">
                    <ul class="footer-flex">
                        <li class="text-light ">
                           <li class="text-light">
                            <a href="https://www.instagram.com/zonik.live" target="_blank" class="text-light"><i class="fa-brands fa-instagram so-l" style="color:#e97457;"></i> Instagram</a>
                        </li>
                        <li class="text-light mt-3">
                            <a href="https://www.facebook.com/people/Zonik-Live/pfbid0YTQN455T4TYfwCzS1uXnTj9njR8Fp9ucL5DxeS3BcGbB8ZnSyPiJ3LVa9Z2miY3bl/"  target="_blank" class="text-light"><i class="fa-brands fa-square-facebook so-l" style="color:#e97457;"></i> Facebook</a>
                        </li><br>
                       
                        <!--<li class="text-light mt-3"><i class="fa-brands fa-linkedin color-primary so-l"></i>-->
                        <!--    Lindekin</li>-->
                    </ul>
                </div>
            </div>

            <div class="col-md-4 my-2">
                <h4 class="footer-heading mb-2">GET IN TOUCH</h4>
                <div class="hr"></div>
                <div class="d-flex mt-3 social align-items-center">
                    <i class="fa-solid fa-phone"></i>
                    <p class="paragraph">+91 8850268043</p>
                </div>
                <div class="d-flex mt-3 social align-items-center">
                    <i class="fa-solid fa-envelope"></i>
                    <p class="paragraph">connect@zonik.in</p>
                </div>
                <div class="d-flex mt-3 social align-items-center">
                    <i class="fa-solid fa-location-dot"></i>
                    <p class="paragraph hide-mobile">Unit B-45 ,Shanti Industrial Estate, Tambe Nagar,Mulund West,Mumbai 400080,India <br>
                    Fssai No. 11525009000305</p>
                    <p class="paragraph hide-desktop">Mulund, Mumbai, Maharashtra</p>

                </div>
                <!-- <div class="d-flex mt-3">
                         <div class="col">
                             <a href="">
                             <img src="{{ asset('frontweb/assets/images/app-store.png') }}"  class="play-store-m play-store">
                             </a>
                         </div>
                         <div class="col">
                             <a href="https://play.google.com/store/apps/details?id=com.infipara.dizcoverapp&pli=1" target='blank'>
                             <img src="{{ asset('frontweb/assets/images/google_play.png') }}"  class="play-store-m play-store">
                             </a>
                         </div>
                     </div> -->
            </div>




        </div>
    </div>
    <div class="copyright">
         <div class="div-hr mb-3"></div>
         <div class="col-md-12">
             <div class="copyright-sec d-flex align-items-center justify-content-center fs-12">
                 <p class="text-light mr-2 text-center">Copyright @2025  Infigourmet networks pvt ltd<span class="color-primary ml-2"></span></p>
                 <!-- <img src="assests/images/white-logo.png"> -->
             </div>
         </div>
     </div>
     <a id="button"></a>
</section>
<script src="{{ asset('frontweb/assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/jquery-ui.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="{{ asset('frontweb/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/bootstrap/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/bootstrap/popper.min.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/feather/feather.min.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/feather/feather-icon.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/lazysizes.min.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/slick/slick.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/slick/slick-animation.min.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/custom-slick-animated.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/slick/custom_slick.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('frontweb/assets/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/auto-height.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/lazysizes.min.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/quantity-2.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/fly-cart.js') }}"></script>
{{--  <script src="{{ asset('frontweb/assets/js/timer1.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/timer2.js') }}"></script>  --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script src="{{ asset('frontweb/assets/js/clipboard.min.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/copy-clipboard.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/wow.min.js') }}"></script>
<script src="{{ asset('frontweb/assets/js/custom-wow.js') }}"></script>
<!--<script src="{{ asset('frontweb/assets/js/script.js') }}"></script>-->
{{--  <script src="{{ asset('frontweb/assets/js/theme-setting.js') }}"></script>  --}}
<script src="{{ asset('frontweb/assets/js/script.js') }}?v={{ time() }}"></script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 1000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }


    });

function boxSelect(tagValue, id) {
 if (tagValue) {
     $("select[id='loose_value" + id + "']").prop('disabled', true);
 } else {
     $("select[id='loose_value" + id + "']").prop('disabled', false);
 }
}

function looseSelect(LooseValue, id) {
 if (LooseValue) {
     $("select[id='box" + id + "']").prop('disabled', true);
 } else {
     $("select[id='box" + id + "']").prop('disabled', false);
 }
}




</script>
<script>
  function copyCouponCode(element) {
    var couponCode = element.getAttribute('data-coupon-code');
    var couponInput = document.querySelector('.couponCode'); // Select the coupon input field

    if (navigator.clipboard && navigator.clipboard.writeText) {
        // Copy to clipboard using Clipboard API
        navigator.clipboard.writeText(couponCode)
            .then(function () {
                console.log('Coupon code copied to clipboard: ' + couponCode);

                // Automatically add the coupon code to the input field
                couponInput.value = couponCode;
                couponInput.dispatchEvent(new Event('input')); // Trigger an input event if necessary
                $(couponInput).keyup(); // Trigger keyup to call the AJAX logic
            })
            .catch(function (err) {
                console.error('Unable to copy coupon code: ', err);
            });
    } else {
        // Fallback for older browsers
        var dummyInput = document.createElement('input');
        dummyInput.setAttribute('value', couponCode);
        document.body.appendChild(dummyInput);
        dummyInput.select();
        document.execCommand('copy');
        document.body.removeChild(dummyInput);

        // Automatically add the coupon code to the input field
        couponInput.value = couponCode;
        couponInput.dispatchEvent(new Event('input')); // Trigger an input event if necessary
        $(couponInput).keyup(); // Trigger keyup to call the AJAX logic
    }
}

// Attach event listeners to all "Copy Code" buttons
var copyCodeElements = document.querySelectorAll('.copy-code');
copyCodeElements.forEach(function (element) {
    element.addEventListener('click', function () {
        copyCouponCode(element);

        // Update the button text to indicate "Applied"
        element.textContent = 'Applied';
        setTimeout(() => {
            element.textContent = 'Applied Code'; // Reset the text after 2 seconds
        }, 2000);
    });
});

// Coupon validation logic
$(document).ready(function () {
    // Prevent form submission on Enter key
    $('.couponCode').keydown(function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
        }
    });

    // Handle coupon code input and validation
    $('.couponCode').keyup(function (event) {
        if (event.keyCode === 13) {
            event.preventDefault();
        }
        var couponCode = $(this).val();

        if (couponCode.length >= 4) {
            $.ajax({
                type: 'POST',
                url: '/coupon-validation',
                data: {
                    coupon_code: couponCode
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (response) {
                    var currencyString = $('.grand-total-value').text(); // Get the current total as a string
                    var numberString = currencyString.replace(/[^\d.-]/g, ''); // Remove currency symbols and other non-numeric characters
                    var initialTotal = parseFloat(numberString); // Parse the initial total as a float

                    // Check if the initial value is stored in localStorage, if not, set it
                    if (!localStorage.getItem('floatValue')) {
                        localStorage.setItem('floatValue', initialTotal);
                    }

                    // Retrieve the float value from localStorage
                    var floatValue = parseFloat(localStorage.getItem('floatValue'));

                    var spanMSG = $('.validationCoupon');
                    var spanMSG2 = $('.validationCoupon2');

                    if (response.status === 'expired') {
                        spanMSG.text('Coupon has expired');
                        spanMSG2.text('');
                    } else if (response.status === 'not_found') {
                        spanMSG.text('Coupon Invalid or Not Found');
                        spanMSG2.text('');
                    } else if (response.status === 'not_applied') {
                        spanMSG.text('');
                        spanMSG2.text('Coupon applied successfully!');
                        var offerPrice = parseFloat(response.offer_price); // Parse the offer price as a float
                        var newTotal = floatValue - offerPrice; // Subtract the offer price from the initial total

                        // Update the total display
                        $('.grand-total-value').text('₹' + newTotal.toFixed(2));
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
    });
});

</script>

<script>
$(function() {
 // Owl Carousel
 var owl = $(".owl-carousel1");
 owl.owlCarousel({
     items: 5,
     margin: 10,
     autoplay: true,
     loop: true,
     nav: true,
 });
});
</script>

<script>
$(function() {
 // Owl Carousel
 var owl = $("#owl-carousel2");
 owl.owlCarousel({
     items: 5,
     margin: 20,
     autoplay: true,
     loop: true,
     nav: true,
 });
});
</script>



<script>
$(document).ready(function() {
 $('#owl-carousel3').owlCarousel({
     items: 6,
     loop: true,
     margin: 10,
     margin: 20,
     autoplay: true,
     nav: true
 });
});
</script>


<script>
    @if($errors->any())
        showToast('error', '{{ $errors->first() }}');
    @endif

    @if(session('success'))
        showToast('success', '{{ session('success') }}');
    @endif

    function showToast(icon, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        Toast.fire({
            icon: icon,
            title: message
        });
    }
</script>


<script>
let quantityData = null;
let productId = null;

// function updateQuantity(productType, Id) {
//  quantityData = productType;
//  productId = Id;

//  // Remove "active" class from all buttons
//  $('.box-btn, .loose-btn').removeClass('active');
//  // Add "active" class to the clicked button and change its background color
//  $(`.${productType.toLowerCase()}-btn`).addClass('active').css('background-color', '#121286');

// }


// function updateQuantity(type, id) {

//     // Remove active from only this product
//     $(`.product-${id} .box-btn, .product-${id} .loose-btn`).removeClass('active');

//     // Highlight clicked button
//     if (type === 'BOX') {
//         $(`.product-${id} .box-btn`).addClass('active');
//         $(`.pcs-${id}`).show(); // SHOW PCS
//     } else {
//         $(`.product-${id} .loose-btn`).addClass('active');
//         $(`.pcs-${id}`).hide(); // HIDE PCS FOR LOOSE
//     }

//     quantityData = type;
//     productId = id;
// }

function updateQuantity(type, id) {


    $(".box-btn, .loose-btn").removeClass('active');


    $(".pcs-box").hide();

   
    if (type === 'BOX') {
        $(`.product-${id} .box-btn`).addClass('active');
        $(`.pcs-${id}`).show(); 
    } else {
        $(`.product-${id} .loose-btn`).addClass('active');
      
    }

    quantityData = type;
    productId = id;
}


function updateQuoteCount() {
 axios.get('/quotes/count') // Assuming this endpoint returns the count
     .then(response => {
         const quoteCountElement = document.getElementById('quoteCount');
         if (quoteCountElement) {
             quoteCountElement.innerText = response.data.count;
         }
     })
     .catch(error => {
         console.error('Error fetching quote count:', error);
     });
}
function updateQuoteCountNew() {
 axios.get('/quotes/count') // Assuming this endpoint returns the count
     .then(response => {
         const quoteCountElement = document.getElementById('quoteCountNew');
         if (quoteCountElement) {
             quoteCountElement.innerText = response.data.count;
         }
     })
     .catch(error => {
         console.error('Error fetching quote count:', error);
     });
}

function submit() {
    // Check if the user has selected a product type (BOX or LOOSE)
    if (!quantityData) {
        Toast.fire({
            icon: "info",
            title: "Please select BOX or LOOSE before adding."
        });
        return; // Exit the function without making the API call
    }

    // Proceed with the API call if a selection is made
    axios.post('/quotes/add', {
        productType: quantityData,
        product_id: productId
    }).then(response => {
        // Check if the response has a success message
        if (response.data.success) {
            Toast.fire({
                icon: "success",
                title: response.data.success
            });
            updateQuoteCount();
            updateQuoteCountNew();

            setTimeout(function() {
                $('.toast').fadeOut();
                // location.reload(); // Uncomment if you want to reload the page
            }, 850);

        // Check if the response has an error message
        } else if (response.data.error) {
            Toast.fire({
                icon: "error",
                title: response.data.error
            });
        }
    }).catch(error => {
        // If the server returns an error response with a message
        if (error.response && error.response.data && error.response.data.error) {
            Toast.fire({
                icon: "error",
                title: error.response.data.error
            });
        } else {
            Toast.fire({
                icon: "error",
                title: "An unexpected error occurred"
            });
        }

        // Hide toast after 2 seconds
        setTimeout(function() {
            $('.toast').fadeOut();
        }, 800);
    });

    // Reset the button color after submission
    $('#submitBtn').css('background-color', '#121286');
}



$(document).ready(function() {
        $(".open-modal").click(function() {
            $("#statementModal").modal('show');
        });
    });



$(document).ready(function() {
 $('.product-box-4').find('.24-pcs').hide(); // Hide all .24-pcs initially

 $('.box-btn').on('click', function() {
     $('.box-btn, .loose-btn').removeClass('active').css({
         'background-color': '',
         'color': '#000'
     });
     $(this).addClass('active').css({
         'background-color': '#121286',
         'color': '#ffff'
     });

     // Show .24-pcs only if its parent product-box-4 has .active box-btn
     $(this).closest('.product-box-4').find('.24-pcs').show();
     $('.product-box-4').not($(this).closest('.product-box-4')).find('.24-pcs').hide();
 });

 $('.loose-btn').on('click', function() {
     $('.box-btn, .loose-btn').removeClass('active').css({
         'background-color': '',
         'color': '#000'
     });
     $(this).addClass('active').css({
         'background-color': '#121286',
         'color': '#ffff'
     });

     // Hide all .24-pcs when loose-btn is clicked
     $('.product-box-4').find('.24-pcs').hide();
 });
});



// Adding an "active" class to an element with the ID 'submitBtn' and changing its color
$('#submitBtn').on('click', function() {
 $(this).addClass('active').css('background-color', '#121286');
});
</script>







<script>
function redirectsubcat(url) {
 window.location.href = url;
}


$(document).ready(function() {
 var owl = $('#owl-carousel');
 owl.owlCarousel({
     loop: true,
     margin: 10,
     nav: true,
     navText: [
         '<i class="fa fa-angle-left"></i>',
         '<i class="fa fa-angle-right"></i>'
     ],
     autoplay: false,
     autoplayTimeout: 5000,
     autoplayHoverPause: true,
     responsive: {
         0: {
             items: 3,
             nav: false
         },
         600: {
             items: 3
         },
         1000: {
             items: 5
         }
     }
 });
});
</script>

<!-- --------------------14/3 Trusted Brands owl-carousel -------------------  -->

<script>
$(document).ready(function() {
 var owl = $('#owl-carousel3');
 owl.owlCarousel({
     loop: true,
     margin: 10,
     nav: true,
     navText: [
         '<i class="fa fa-angle-left"></i>',
         '<i class="fa fa-angle-right"></i>'
     ],
     autoplay: false,
     autoplayTimeout: 5000,
     autoplayHoverPause: true,
     responsive: {
         0: {
             items: 1
         },
         600: {
             items: 3
         },
         1000: {
             items: 5
         }
     }
 });
});
</script>










<script>
$(document).ready(function() {
    // Delay the initialization to ensure all items are rendered
    setTimeout(function() {
        var owl = $('.owl-carousel-offers');
        var itemCount = owl.find('.item').length;

        // Initialize the carousel
        owl.owlCarousel({
            loop: itemCount > 2, // Loop only if there are more than 2 items
            margin: 10,
            nav: true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>'
            ],
            autoplay: true, // Always autoplay
            autoplayTimeout: 5000, // 5 seconds between slides
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 2,  // On small screens show 1 item
                    nav: false
                },
                600: {
                    items: 2,  // On medium screens show 2 items
                    nav: true
                },
                1000: {
                    items: 2,  // On larger screens show 2 items
                    nav: true
                }
            },
            slideBy: 2 // Slide in groups of 2 items
        });
    }, 500); // Adjust the delay as needed (500ms)
});

</script>













<!-- --------------------14/3 quantityMinus owl-carousel ------------------- -->

<script>
$(document).ready(function () {
    $('.owl-carousel7').each(function () {
        var owl = $(this); // Target each carousel instance individually

        owl.owlCarousel({
            autoplay: false,
            loop: false,
            margin: 10,
            nav: true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>'
            ],
            dots: true, // Enable dots
            responsive: {
                0: {
                    items: 2,
                    nav: false
                },
                600: {
                    items: 4
                },
                1000: {
                    items: 5
                }
            },
            onInitialized: function () {
                limitDotsToFive(owl);
            },
            onResized: function () {
                limitDotsToFive(owl);
            },
            onChanged: function () {
                limitDotsToFive(owl);
            }
        });

        // Force dots logic after initialization
        owl.trigger('refresh.owl.carousel');
        limitDotsToFive(owl);

        // Call limitDotsToFive after window resize
        $(window).on('resize', function () {
            setTimeout(function () {
                limitDotsToFive(owl);
            }, 200);
        });
    });

    function limitDotsToFive(owl) {
        var $dotsContainer = owl.find('.owl-dots');
        var $dots = $dotsContainer.find('.owl-dot');

        if ($dots.length > 0) {
            // Hide dots beyond 5
            $dots.each(function (index) {
                if (index >= 5) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        }
    }
});



$(document).ready(function() {
 $('.slide').carousel({
     interval: 2500, // Slide time in milliseconds
     wrap: true, // Loop running
     touch: true, // 
       nav: true,
    navText: [
         '<i class="fa fa-angle-left"></i>',
         '<i class="fa fa-angle-right"></i>'
     ],
 });
});

function quantityMinus(inputId, cart_id, inputFieldId, classField) {
 const input = $(`.${inputFieldId}`);
 let value = parseInt(input.val());
 if (value > 1) {
     value--;
     input.val(value);
     $.ajax({
         url: '/cart/increase/quantity',
         method: 'GET',
         data: {
             quantity_value: value,
             cart_id: cart_id,
         },
         success: function(data) {
             updateCartTotals(data.data);
             updateCouponItems(data.data);
             updateCartItem(classField, data.data);
         },
         error: function(error) {
             console.log(error);
         }
     });
 }
}

function quantityPlus(inputId, cart_id, inputFieldId, classField) {
 const input = $(`.${inputFieldId}`);
 let value = parseInt(input.val());
 value++;
     console.log(value);
 input.val(value);
 $.ajax({
     url: '/cart/update/quantity',
     method: 'GET',
     data: {
         quantity_value: value,
         cart_id: cart_id,
     },
     success: function(data) {
         updateCartTotals(data.data);
         updateCouponItems(data.data);
         updateCartItem(classField, data.data);
     },
     error: function(error) {
         console.log(error);
     }
 });
}


// Minus button
function dynamicQuantityMinus(cartId, offerPrice, isMobile = false) {
    const input = document.getElementById(isMobile ? `quantity__inputMob${cartId}` : `quantity__input${cartId}`);
    if (!input) return;

    let value = parseInt(input.value);
    const countValue = parseInt(input.getAttribute('data-count') || 1);

    if (value > countValue) {
        value -= countValue;
        input.value = value;

        // AJAX to update backend
        $.ajax({
            url: '/cart/update/quantity',
            method: 'GET',
            data: {
                quantity_value: value,
                cart_id: cartId
            },
            success: function(data) {
                updateCartTotals(data.data);
                updateCouponItems();
                updateCartItemDynamic(cartId, data.data, isMobile);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
}

// Plus button
function dynamicQuantityPlus(cartId, offerPrice, countValue = 1, isMobile = false) {
    const input = document.getElementById(isMobile ? `quantity__inputMob${cartId}` : `quantity__input${cartId}`);
    if (!input) return;

    let value = parseInt(input.value);
    value += countValue;
    input.value = value;

    // AJAX to update backend
    $.ajax({
        url: '/cart/update/quantity',
        method: 'GET',
        data: {
            quantity_value: value,
            cart_id: cartId
        },
        success: function(data) {
            updateCartTotals(data.data);
            updateCouponItems();
            updateCartItemDynamic(cartId, data.data, isMobile);
        },
        error: function(error) {
            console.log(error);
        }
    });
}

// Update totals in DOM
function updateCartItemDynamic(cartId, data, isMobile = false) {
    const total = parseFloat(data.total_amt_basic).toFixed(2);
    const totalQty = data.total_qty;

    if (isMobile) {
        document.getElementById(`totalMobQty${cartId}`).innerText = totalQty;
        document.getElementById(`totalMobAmt${cartId}`).innerText = total;
    } else {
        document.getElementById(`total_qty${cartId}`).innerText = totalQty;
        document.getElementById(`total_amt${cartId}`).innerText = `₹${total}`;
    }
}



function updateCouponItems() {
    console.log("Function called");

    // Get the grand total value element
    const grandTotalElement = document.querySelector('.grand-total-value');

    // Ensure the grand total element exists
    if (!grandTotalElement) {
        console.error("Grand total element not found");
        return;
    }

    // Get the numeric value from the grand total element
    const grandTotalValue = parseFloat(
        grandTotalElement.innerText.replace('₹', '').replace(',', '').trim()
    );

    // Select all coupon items
    const couponItems = document.querySelectorAll('.coupon-item');

    // Loop through each coupon item to check conditions
    couponItems.forEach(item => {
        // Get the max price for the coupon
        const maxPrice = parseFloat(item.getAttribute('data-max-price').trim());

        // Add or remove the appropriate class based on the condition
        if (maxPrice > grandTotalValue) {
            item.classList.add('disable'); // Disable the coupon
            item.classList.remove('enable'); // Remove the enabled class
        } else {
            item.classList.add('enable'); // Enable the coupon
            item.classList.remove('disable'); // Remove the disabled class
        }
    });
}

function updateCartItem(classField, newData) {
    $('.' + classField + ' .ct5 span').text('₹' + newData.total_amt_basic);
    
    let amtValueElement = $('.' + classField + ' .newvaluechanged .amt-value');
    
    if (amtValueElement.length) {
        amtValueElement.text('₹' + newData.total_amt_basic);
    } else {
        $('.' + classField + ' .newvaluechanged').html('<b>Total Amt (Basic):</b> ₹' + newData.total_amt_basic);
    }

    $('.' + classField + ' .total_qty_cart').text(newData.total_qty);
    
    updateCartTotals(newData);
}




function updateCartTotals(data) {
 if (data && typeof data.subTotalAmt === 'number') {
     $('.subtotal-value').text('₹' + data.subTotalAmt.toFixed(2));
 } else {
     $('.subtotal-value').text('₹ 0.00');
 }

 if (data && typeof data.productDiscount === 'number') {
     $('.product-discount-value').text('- ₹' + data.productDiscount.toFixed(2));
 } else {
     $('.product-discount-value').text('- ₹ 0.00');
 }

 if (data && typeof data.result === 'number') {
     $('.gst-value').text('+ ₹' + data.result.toFixed(2));
 } else {
     $('.gst-value').text('+ ₹ 0.00');
 }

 if (data && typeof data.totalDiscountValue === 'number') {
     $('.grand-total-value').text('₹ ' + data.totalDiscountValue.toFixed(2));
 } else {
     $('.grand-total-value').text('₹ 0.00');
 }

 if (data && typeof data.productDiscount === 'number') {
     $('.total-savings-value').text('You saved ₹' + data.productDiscount.toFixed(2) + ' in this order');
 } else {
     $('.total-savings-value').text('You saved ₹ 0.00 in this order');
 }
}
</script>

<script>
    // var btn = $('#button');

    // $(window).scroll(function() {
    // if ($(window).scrollTop() > 300) {
    // btn.addClass('show');
    // } else {
    // btn.removeClass('show');
    // }
    // });

    // btn.on('click', function(e) {
    // e.preventDefault();
    // $('html, body').animate({scrollTop:0}, '300');
    // });

 </script>

</body>

</html>
