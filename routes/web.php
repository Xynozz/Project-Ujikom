<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\WisataController;
use App\Http\Middleware\isAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    // Route::resource('pembayaran', PembayaranController::class);

});

Route::group(['prefix' => 'user'], function () {

    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::get('test', function() {
    return view('layouts.app');
});

// Route::post('/create-transaction', [PembayaranController::class, 'createTransaction']);
// Route::post('/midtrans-notification', [PembayaranController::class, 'handleNotification']);
// Route::post('/update-pemesanan-status/{orderId}', [PembayaranController::class, 'updatePemesananStatus']);
// Route::post('/midtrans/webhook', [PembayaranController::class, 'handleMidtransWebhook']);

Route::post('/pembayaran/{pemesananId}', [PembayaranController::class, 'createPayment'])->name('payment.notification')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Routes untuk Midtrans callbacks
Route::post('payment/finish', [PembayaranController::class, 'handleFinish'])->name('payment.finish');
Route::post('payment/error', [PembayaranController::class, 'handleError'])->name('payment.error');
Route::post('payment/pending', [PembayaranController::class, 'handlePending'])->name('payment.pending');
Route::post('payment/notification', [PembayaranController::class, 'handleNotification'])->name('payment.notification');
