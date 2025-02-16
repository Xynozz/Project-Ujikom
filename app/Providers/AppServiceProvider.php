<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Config::$serverKey    = 'YOUR_SERVER_KEY';
        Config::$isProduction = false; // Set ke true untuk produksi
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }
}
