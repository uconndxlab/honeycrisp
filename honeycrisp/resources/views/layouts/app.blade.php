<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Your App')</title>
    <!-- Include your CSS stylesheets, scripts, etc. -->
</head>
<body>

    <!-- Include the header -->
    @include('partials.header')

    <!-- Content section -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Include your footer or other common elements if needed -->

    <!-- Include your JavaScript scripts if needed -->
</body>
</html>
