<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Exception;
use App\Http\Controllers\Controller;
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

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            $findUser = User::where('google_id', $user->id)->first();

            if($findUser) {
                Auth::login($findUser);
                return redirect()->intended('user/home');
            } else {
                $newUser = User::updateOrCreate(['email' => $user->email], [
                    'username' => $user->username,
                    'google_id' => $user->id,
                    'password' => bcrypt('123456dummy')
                ]);

                Auth::login($newUser);
                return redirect()->intended('user/home');
            }
        } catch (Exception $e) {
            return redirect('login')->with('error', 'Terjadi kesalahan saat login dengan Google');
        }
    }

    // public function logout()
    // {
    //     Auth::logout();
    //     return redirect('/login');
    // }
}
