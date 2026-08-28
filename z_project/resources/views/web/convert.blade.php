<!-- resources/views/convert.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convert Number to Words</title>
</head>
<body>
    <h1>Convert Number to Words</h1>
    <form method="post" action="{{ route('convert') }}">
        @csrf
        <label for="number">Enter Number:</label><br>
        <input type="text" id="number" name="number"><br><br>
        <button type="submit">Convert</button>
    </form>
    @if(isset($result))
    <p>{{ $result }}</p>
    @endif
</body>
</html>
