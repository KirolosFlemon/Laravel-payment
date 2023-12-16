<!-- layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- In the head section of your Blade template -->
<meta name="access-token" content="{{ auth()->user()->token }}">

    
</head>
<body>
    <div id="app">
        <!-- Navbar and other common elements go here -->

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts and other scripts go here -->
    <!-- Bootstrap JS and Popper.js -->
<script src="{{ asset('js/app.js') }}" defer></script>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Include jQuery -->

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

</body>
</html>
