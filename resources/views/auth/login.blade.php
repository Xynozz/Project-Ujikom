@extends('layouts.user.frontend.auth')
@section('content')
<div class="auth-header">
    <img src="{{ asset('user/image/logo.png') }}" alt="Logo" class="auth-logo">
    <h1>Welcome Back!</h1>
    <p>Sign in to continue to HetraTicket</p>
</div>

<form class="auth-form" action="{{ route('login') }}" method="POST">
    @csrf
    <div class="form-group">
        <div class="input-group">
            <i class='bx bx-envelope'></i>
            <input type="email" id="email" name="email" placeholder="Enter your email"
                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
        </div>
        @error('email')
        <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <div class="input-group">
            <i class='bx bx-lock-alt'></i>
            <input type="password" id="password" name="password" placeholder="Enter your password"
                class="form-control @error('password') is-invalid @enderror" required>
            <i class='bx bx-hide toggle-password'></i>
        </div>
        @error('password')
        <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-options">
        <label class="remember-me">
            <input type="checkbox" name="remember" id="remember">
            <span>Remember me</span>
        </label>
        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
    </div>

    <button type="submit" class="btn-login">
        Sign in
    </button>
</form>

<div class="auth-separator">
    <span>or continue with</span>
</div>

<div class="social-login">
    <a href="{{ route('auth.google') }}" class="btn-google">
        <img src="{{ asset('user/image/google.svg') }}" alt="Google">
        <span>Google</span>
    </a>
</div>

<div class="auth-footer">
    <p>Don't have an account? <a href="{{ route('register') }}">Create an account</a></p>
</div>
@endsection