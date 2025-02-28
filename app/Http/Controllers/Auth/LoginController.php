<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function redirectTo()
    {
        if (Auth::user()->is_admin === 1) {
            return 'admin/dashboard';
        } else {
            return 'user/home';
        }
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // public function redirectToGoogle()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // public function handleGoogleCallback()
    // {
    //     try {
    //         // Ambil data pengguna dari Google
    //         $googleUser = Socialite::driver('google')->user();

    //         // Cari atau buat user baru
    //         $user = User::updateOrCreate([
    //             'email' => $googleUser->email, // Gunakan email sebagai kunci unik
    //         ], [
    //             'username'             => $googleUser->name, // Simpan nama dari Google sebagai username
    //             'nama_lengkap'         => $googleUser->name, // Simpan nama dari Google sebagai nama_lengkap
    //             'google_id'            => $googleUser->id,
    //             'google_token'         => $googleUser->token,
    //             'google_refresh_token' => $googleUser->refreshToken,
    //         ]);

    //         // Login pengguna
    //         Auth::login($user);

    //         // Redirect ke halaman yang sesuai
    //         return redirect()->intended('/home')->with('success', 'Login berhasil.');
    //     } catch (\Exception $e) {
    //         // Tangani error
    //         return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google: ' . $e->getMessage());
    //     }
    // }

    // public function logout()
    // {
    //     Auth::logout();
    //     return redirect('/login');
    // }
}