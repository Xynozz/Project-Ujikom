<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function redirectTo()
    {
        if (Auth::user()->is_admin === 1) {
            return 'admin/dashboard';
        } else {
            return '/';
        }
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        // Jangan pakai ->stateless() kalau ini bukan API
        $googleUser = Socialite::driver('google')->user();

        // Cek isi data dari Google
        logger()->info('Google User Data', [
            'id'     => $googleUser->getId(),
            'username'   => $googleUser->getName(),
            'email'  => $googleUser->getEmail(),
            'avatar' => $googleUser->getAvatar(), // avatar asli
        ]);

        // Buat atau ambil user
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'username'     => $googleUser->getName(),
                'avatar'   => $googleUser->getAvatar(), // simpan avatar URL
                'password' => bcrypt(Str::random(16)),  // dummy password
            ]
        );

        Auth::login($user);

        return redirect('/')->with('success', 'Login Berhasil');
    }
}
