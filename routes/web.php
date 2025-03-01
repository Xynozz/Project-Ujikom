<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\isAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::group(['prefix' => 'admin', 'middleware' => ['auth', isAdmin::class]], function () {

    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class);
    Route::resource('wisata', WisataController::class);
    Route::resource('ulasan', UlasanController::class);
    Route::resource('tiket', TiketController::class);
    Route::resource('pemesanan', PemesananController::class);

    // Laporan Routes
    Route::prefix('laporan')->group(function () {
        Route::get('/user', [LaporanController::class, 'userReport'])->name('laporan.user');
        Route::get('/user/export', [LaporanController::class, 'exportUserPDF'])->name('laporan.user.export');
        Route::get('/pemesanan', [LaporanController::class, 'pemesananReport'])->name('laporan.pemesanan');
        Route::get('/pemesanan/export', [LaporanController::class, 'exportPemesananPDF'])->name('laporan.pemesanan.export');
        Route::get('/laporan/user/excel', [LaporanController::class, 'exportExcel'])->name('laporan.user.excel');
        Route::get('/laporan/pemesanan/excel', [LaporanController::class, 'exportPemesananExcel'])->name('laporan.pemesanan.excel');
        Route::get('/pendapatan', [LaporanController::class, 'pendapatanReport'])->name('laporan.pendapatan');
    });
});

Route::group(['prefix' => 'user'], function () {

    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::get('test', function() {
    return view('layouts.app');
});

Route::post('/pembayaran/{pemesananId}', [PembayaranController::class, 'createPayment'])->name('payment.notification')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Routes untuk Midtrans callbacks
Route::post('payment/finish', [PembayaranController::class, 'handleFinish'])->name('payment.finish');
Route::post('payment/error', [PembayaranController::class, 'handleError'])->name('payment.error');
Route::post('payment/pending', [PembayaranController::class, 'handlePending'])->name('payment.pending');
Route::post('payment/notification', [PembayaranController::class, 'handleNotification'])->name('payment.notification');

// Google OAuth Routes
Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);
