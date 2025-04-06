@extends('layouts.user.frontend.auth')

@section('content')
<div class="auth-header">
    <h1>Create Account</h1>
    <p>Join us to explore amazing destinations</p>
</div>

<form action="{{ route('register') }}" method="POST" class="auth-form">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-6">
            <div class="input-group">
                <i class='bx bx-user'></i>
                <input type="text" class="form-control @error('username') is-invalid @enderror" name="username"
                    placeholder="Username" value="{{ old('username') }}" required>
            </div>
            @error('username')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <div class="input-group">
                <i class='bx bx-id-card'></i>
                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" name="nama_lengkap"
                    placeholder="Full Name" value="{{ old('nama_lengkap') }}" required>
            </div>
            @error('nama_lengkap')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <div class="input-group">
                <i class='bx bx-phone'></i>
                <input type="tel" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" id="telepon"
                    placeholder="0888-8888-8888" required>
            </div>
            @error('no_hp')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <div class="input-group">
                <i class='bx bx-home'></i>
                <input type="text" class="form-control @error('alamat') is-invalid @enderror" name="alamat"
                    placeholder="Address" value="{{ old('alamat') }}" required>
            </div>
            @error('alamat')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <div class="input-group">
            <i class='bx bx-envelope'></i>
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                placeholder="Email address" value="{{ old('email') }}" required>
        </div>
        @error('email')
        <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <div class="input-group">
            <i class='bx bx-lock-alt'></i>
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                placeholder="Password" required>
            <i class='bx bx-hide toggle-password'></i>
        </div>
        @error('password')
        <span class="error-message">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <div class="input-group">
            <i class='bx bx-lock-alt'></i>
            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm password"
                required>
            <i class='bx bx-hide toggle-password'></i>
        </div>
    </div>

    <button type="submit" class="btn-login">
        Create Account <i class='bx bx-right-arrow-alt'></i>
    </button>
</form>

<div class="auth-footer">
    <p>Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputTelepon = document.getElementById("telepon");

        function formatPhoneNumber(value) {
            // Hapus semua karakter kecuali angka
            value = value.replace(/\D/g, '');

            // Format nomor dengan "-"
            if (value.length > 4) {
                value = value.substring(0, 4) + '-' + value.substring(4);
            }
            if (value.length > 8) {
                value = value.substring(0, 9) + '-' + value.substring(9);
            }
            return value.substring(0, 14); // Batasi maksimal 12 karakter
        }

        inputTelepon.addEventListener("input", function(e) {
            let oldValue = this.value;
            let cursorPosition = this.selectionStart;

            this.value = formatPhoneNumber(this.value);

            // Sesuaikan posisi kursor saat format berubah
            if (this.value.length !== oldValue.length) {
                cursorPosition += (this.value.length - oldValue.length);
            }
            this.setSelectionRange(cursorPosition, cursorPosition);
        });

        inputTelepon.addEventListener("keypress", function(e) {
            if (!/\d/.test(e.key)) {
                e.preventDefault(); // Mencegah karakter non-angka
            }
        });

        inputTelepon.addEventListener("paste", function(e) {
            e.preventDefault();
            let pastedText = (e.clipboardData || window.clipboardData).getData("text");
            this.value = formatPhoneNumber(pastedText);
        });

        inputTelepon.addEventListener("drop", function(e) {
            e.preventDefault();
        });
    });
</script>
@endpush