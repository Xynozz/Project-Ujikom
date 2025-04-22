<!-- filepath: /c:/laragon/www/Hetra_Pemesanan_Tiket/resources/views/layouts/user/template.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ config('app.name') }}</title>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/home.css') }}">
    <!-- Page CSS -->
    @stack('css')

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    @include('layouts.user.backend.header')

    <main>
    <!-- Main Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    {{-- @include('layouts.user.partials.footer') --}}

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Page JS -->
    @stack('scripts')
</body>
</html>