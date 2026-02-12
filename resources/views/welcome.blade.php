<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Humanity Foundation</title>
    <script>
        // Redirect to login page
        window.location.href = "{{ route('login') }}";
    </script>
</head>

<body>
    <p>Redirecting to login...</p>
    <p><a href="{{ route('login') }}">Click here if not redirected</a></p>
</body>

</html>