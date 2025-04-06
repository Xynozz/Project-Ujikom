<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | HetraTicket</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('user/css/auth.css') }}">
</head>
<body>
    <main class="auth-container">
        <div class="auth-wrapper">
            <div class="auth-card">
                @yield('content')
            </div>
        </div>
    </main>

    <script src="{{ asset('user/js/auth.js') }}"></script>
    @stack('scripts')
</body>
</html>