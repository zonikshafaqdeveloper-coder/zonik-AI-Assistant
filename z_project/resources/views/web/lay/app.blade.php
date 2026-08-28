<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your App</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add your custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Add more CSS links if needed -->
    @yield('styles') <!-- Include additional styles if required -->
</head>
<body>
    <div class="container">
        @yield('content') <!-- Display content from individual pages -->
    </div>

    <!-- Add jQuery library -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Add Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Add your JavaScript scripts here -->
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Add more scripts if needed -->
    @yield('scripts') <!-- Include additional scripts if required -->
</body>
</html>
