<!DOCTYPE html>
<html>
<head>
    <title>Zonik Business</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
/* body.loading { overflow: hidden !important; padding-right: var(--scrollbar-width, 0px) !important; } html.loading { overflow: hidden !important; } .loader { position: fixed; inset: 0; width: 100vw; height: 100vh; background-color: #ffffff; z-index: 999999; display: flex; align-items: center; justify-content: center; overflow: hidden; } .loader img { max-width: 230px; max-height: 330px; mix-blend-mode: multiply; display: block; }*/
</style>
</head>
<body>

<!--<div class="loader" id="loader">-->
<!--    <img src="/frontweb/assets/images/loader.png" alt="Loading...">-->
<!--</div>-->

@php
    $headerView = app('App\Http\Controllers\LayoutController')->header();
    $footerView = app('App\Http\Controllers\LayoutController')->footer();
@endphp

{!! $headerView !!} <!-- Include the header view -->

<div class="content">
    @yield('content')
</div>

{!! $footerView !!}

<!--@if (session('success'))-->
<!--    <script type="text/javascript">-->
<!--        toastr.success("{{ session('success') }}", 'Success');-->
<!--    </script>-->
<!--@endif-->

<!--@foreach ($errors->all() as $error)-->
<!--    <script type="text/javascript">-->

<!--    </script>-->
<!--@endforeach-->

<script>
    @if(session('error'))
        showToast('error', '{{ session('error') }}');
    @endif

    @if(session('success'))
        showToast('success', '{{ session('success') }}');
    @endif

    function showToast(icon, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-start",
            showConfirmButton: false,
            timer: 1000,
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


// document.addEventListener("DOMContentLoaded", function () {

//     // Disable scroll but DO NOT hide scrollbar
//     document.documentElement.style.overflowY = "scroll"; // scrollbar always visible
//     document.body.style.overflow = "hidden"; // disable scroll movement

//     setTimeout(() => {
//         const loader = document.getElementById("loader");
//         loader.style.transition = "opacity 0.7s";
//         loader.style.opacity = "0";

//         setTimeout(() => {
//             loader.style.display = "none";

//             // Enable scrolling again
//             document.body.style.overflow = "";
//         }, 700);

//     }, 2);
// });


// let inactivityTimeout;

// function resetInactivityTimer() {
//     // Skip reloading if the user is on the checkout or orders page
//     const excludedPages = ['/checkout', '/orders'];
//     if (excludedPages.some(path => window.location.pathname.startsWith(path))) {
//         return;
//     }

//     // Clear the existing timeout
//     clearTimeout(inactivityTimeout);

//     // Set a new timeout (30 seconds)
//     inactivityTimeout = setTimeout(function() {
//         location.reload(); // Reload the page after 30 seconds of inactivity
//     }, 30000); // 30,000 milliseconds = 30 seconds
// }

// // Reset the inactivity timer whenever there is user interaction
// document.addEventListener('mousemove', resetInactivityTimer); // Mouse move
// document.addEventListener('keydown', resetInactivityTimer);   // Key press
// document.addEventListener('click', resetInactivityTimer);     // Click event
// document.addEventListener('scroll', resetInactivityTimer);    // Scroll event

// // Initialize the inactivity timer when the page loads
// resetInactivityTimer();


</script>
<script>
function updateHeaderCounts() {
    fetch("{{ route('header.counts') }}")
        .then(response => response.json())
        .then(data => {
           
            const setTextAll = (id, value) => {
                document.querySelectorAll(`#${id}`).forEach(el => {
                    el.innerText = value;
                });
            };

          
            setTextAll('quoteCount', data.quoteCounts);

          
            let offerValue =
             data.offerListCount > 0 ? data.offerListCount :
            (data.reofferListCount > 0 ? data.reofferListCount : data.offerListCount);



            ['quoteCountNewofferDesktop', 'quoteCountNewofferMobile'].forEach(id => {
                let el = document.getElementById(id);
                if (el) el.innerText = offerValue;
            });



            ['myPricelistDesktop', 'myPricelistMobile'].forEach(id => {
                let el = document.getElementById(id);
                if (el) el.innerText = data.mypricelist;
            });



            ['cartCountDesktop', 'cartCountMobile'].forEach(id => {
                let el = document.getElementById(id);
                if (el) el.innerText = data.cart;
            });


           
            setTextAll('notificationCount', data.notification);

           
            let ul = document.querySelector('.notifications-ul');
            if (ul) {
                ul.innerHTML = '';
                if (Array.isArray(data.notifications)) {
                    data.notifications.forEach(n => {
                        if (n.tag === 'Customer') {
                            ul.innerHTML += `
                                <li>
                                    <div class="notification-item">
                                        <div class="notification-icon">
                                            <div class="icon-circle">
                                                <i class="far fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <div class="notification-content">
                                            <div class="notification-text">${n.text}</div>
                                            <div class="notification-date">${n.date}</div>
                                        </div>
                                    </div>
                                </li>
                            `;
                        }
                    });
                }
            }
        })
        .catch(err => console.error("Header counts fetch error:", err));
}


setInterval(updateHeaderCounts, 5000);


updateHeaderCounts();


function refreshHeaderCountsNow() {
    updateHeaderCounts();
}
</script>



</body>
</html>
