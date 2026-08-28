<!DOCTYPE html>
<html lang="en" dir="ltr">


<!-- Mirrored from themes.pixelstrap.com/fastkart/back-end/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 29 Jul 2023 08:42:03 GMT -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Fastkart admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Fastkart admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{!! asset('images/favicon.png')!!}" type="image/x-icon">
    <link rel="shortcut icon" href="{!! asset('images/favicon.png')!!}" type="image/x-icon">
    <title></title>

    <!-- Google font-->
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">

    <!-- Linear Icon css -->
    <link rel="stylesheet" href="{{ asset('css/linearicon.css')}}">

    <!-- fontawesome css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendors/font-awesome.css')}}">

    <!-- Themify icon css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendors/themify.css')}}">

    <!-- ratio css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/ratio.css')}}">

    <!-- remixicon css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/remixicon.css')}}">

    <!-- Feather icon css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendors/feather-icon.css')}}">

    <!-- Plugins css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendors/scrollbar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendors/animate.css')}}">

    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vendors/bootstrap.css')}}">

    <!-- vector map css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/vector-map.css')}}">

    <!-- Slick Slider Css -->
    <link rel="stylesheet" href="{{ asset('css/vendors/slick.css')}}">

    <!-- App css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css')}}">
</head>    

    <!-- Breadcrumb Section Start -->
    <!-- <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2 class="mb-2">Log In</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="index.html">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Log In</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- Breadcrumb Section End -->

    <!-- log in section start -->
    <section class="log-in-section background-image-2 section-b-space text-center">
        <div class="container-fluid-lg w-100">
            <div class="row">
                <div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
                    <div class="image-contain">
                        <img src="../assets/images/inner-page/log-in.png" class="img-fluid" alt="">
                    </div>
                </div>

                <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                    <div class="log-in-box">
                   
                        <div class="log-in-title">
                            <h3>Welcome To Dizcover</h3>
                            <h4>Admin Login</h4>
                          
                        </div>

                        <div class="input-box">
                                  @if(session()->has('error'))
                                   <div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
                                    {{session('error')}}  
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                    </button>
                                    </div> 
                                    @endif 	

                                    

                                    @if(session('success'))
                                <div class="alert alert-success">
                                {{ session('success') }}
                                </div>
                                @endif



                         
                            <form action="{{route('admin.auth')}}" method="post" class="row g-4">
                            @csrf
                                <div class="col-12">
                                    <div class="form-floating theme-form-floating log-in-form">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                                        <label for="email">Email Address</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating theme-form-floating log-in-form">
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Password">
                                        <label for="password">Password</label>
                                    </div>
                                </div>

                              
                                <div class="col-12">
                                    <button class="btn btn-animation w-100 justify-content-center" type="submit">Log
                                        In</button>

                                   

                                </div>
                            </form>
                        </div>

                      
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- log in section end -->
  
    


     <!-- latest js -->
     <script src="{{ asset('js/jquery-3.6.0.min.js')}}"></script>

<!-- Bootstrap js -->
<script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js')}}"></script>

<!-- feather icon js -->
<script src="{{ asset('js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{ asset('js/icons/feather-icon/feather-icon.js')}}"></script>

<!-- scrollbar simplebar js -->
<script src="{{ asset('js/scrollbar/simplebar.js')}}"></script>
<script src="{{ asset('js/scrollbar/custom.js')}}"></script>

<!-- Sidebar jquery -->
<script src="{{ asset('js/config.js')}}"></script>

<!-- tooltip init js -->
<script src="{{ asset('js/tooltip-init.js')}}"></script>

<!-- Plugins JS -->
<script src="{{ asset('js/sidebar-menu.js')}}"></script>
<script src="{{ asset('js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('js/notify/index.js')}}"></script>


<!-- slick slider js -->
<script src="{{ asset('js/slick.min.js')}}"></script>
<script src="{{ asset('js/custom-slick.js')}}"></script>

<!-- customizer js -->
<script src="{{ asset('js/customizer.js')}}"></script>

<!-- ratio js -->
<script src="{{ asset('js/ratio.js')}}"></script>

<!-- sidebar effect -->
<script src="{{ asset('js/sidebareffect.js')}}"></script>

<!-- Theme js -->
<script src="{{ asset('js/script.js')}}"></script>
</body>


<!-- Mirrored from themes.pixelstrap.com/fastkart/back-end/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 29 Jul 2023 08:42:22 GMT -->
</html>
            

