@extends('layouts.admin.frontend.auth')

@section('content')
<section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center form-bg-image"
            data-background-lg="{{asset('admin/assets/img/illustrations/signin.svg')}}">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                    <div class="text-center text-md-center mb-4 mt-md-0">
                        <h1 class="mb-0 h3">Create Account </h1>
                    </div>
                    <form action="{{ route('register') }}" method="POST" class="mt-4">
                        @csrf
                        <!-- Form -->
                        <div class="row">
                            <div class="col-6 mb-4">
                                <label for="email">Username</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Masukan Username" id="username"
                                        name="username" autofocus required>
                                </div>
                            </div>
                            <div class="col-6 mb-4">
                                <label for="email">Nama Lengkap</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Masukan Nama Lengkap"
                                        id="nama_lengkap" name="nama_lengkap" required>
                                </div>
                            </div>
                            <div class="col-6 mb-4">
                                <label for="email">Nomor Telepon</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="088-1234-5678"
                                        id="telepon" name="no_hp" maxlength="14" required>
                                </div>
                            </div>
                            <div class="col-6 mb-4">
                                <label for="email">Alamat</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Masukan Alamat" id="alamat"
                                        name="alamat" required>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <label for="email">Your Email</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">
                                        <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                            </path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                    </span>
                                    <input type="email" class="form-control" placeholder="example@company.com"
                                        id="email" name="email" autofocus required>
                                </div>
                            </div>
                            <!-- End of Form -->
                            <div class="col">
                                <!-- Form -->
                                <div class="col mb-4">
                                    <label for="password">Your Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon2">
                                            <svg class="icon icon-xs text-gray-600" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                        <input type="password" placeholder="Password" class="form-control" id="password"
                                            name="password" required>
                                    </div>
                                </div>
                                <!-- End of Form -->
                                <!-- Form -->
                                <div class="col mb-4">
                                    <label for="confirm_password">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon2">
                                            <svg class="icon icon-xs text-gray-600" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                        <input type="password" placeholder="Confirm Password" class="form-control"
                                            id="confirm_password" name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-gray-500">Sign up</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
document.getElementById('telepon').addEventListener('input', function (e) {
    // Hapus semua karakter selain angka
    let value = this.value.replace(/\D/g, '');

    // Tambahkan tanda '-' di posisi yang sesuai
    if (value.length > 4 && value.length <= 8) {
        value = value.replace(/(\d{4})(\d+)/, '$1-$2'); // Format XXX-XXX
    } else if (value.length > 8) {
        value = value.replace(/(\d{4})(\d{4})(\d+)/, '$1-$2-$3'); // Format XXX-XXX-XXXX
    }

    // Masukkan kembali hasil format ke input
    this.value = value;
});
</script>
@endsection
