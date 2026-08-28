<!DOCTYPE html>
<html>
<head>
    <title>Grocery App</title>
    <!-- Include your CSS and other head elements -->
</head>
<body>

@php
$headerView = app('App\Http\Controllers\LayoutAdminController')->header();
$footerView = app('App\Http\Controllers\LayoutAdminController')->footer();
@endphp


    @include('admin.includes.header')

    <div class="content">
        @yield('content')
        @yield('js')
    </div>

    @include('admin.includes.footer')

    <!-- Include your JavaScript and other footer elements -->
</body>
</html>
