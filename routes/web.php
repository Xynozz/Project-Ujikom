<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DetailPemesananController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\WisataController;
use App\Http\Middleware\isAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

// Route Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', isAdmin::class]], function () {

    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class);
    Route::resource('wisata', WisataController::class);
    Route::resource('ulasan', UlasanController::class);
    Route::resource('tiket', TiketController::class);
    Route::resource('pemesanan', PemesananController::class);
    Route::resource('detail_pemesanan', DetailPemesananController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/midtrans/create-transaction', [MidtransController::class, 'createTransaction']);
        Route::post('/midtrans/callback', [MidtransController::class, 'paymentCallback']);
    });

    // Laporan Routes
    Route::prefix('laporan')->group(function () {
        Route::get('/user', [LaporanController::class, 'userReport'])->name('laporan.user');
        Route::get('/user/export', [LaporanController::class, 'exportUserPDF'])->name('laporan.user.export');
        Route::get('/pemesanan', [LaporanController::class, 'pemesananReport'])->name('laporan.pemesanan');
        Route::get('/pemesanan/export', [LaporanController::class, 'exportPDF'])->name('laporan.pemesanan.export');
        Route::get('/laporan/user/excel', [LaporanController::class, 'exportExcel'])->name('laporan.user.excel');
        Route::get('/laporan/pemesanan/excel', [LaporanController::class, 'exportPemesananExcel'])->name('laporan.pemesanan.excel');
        Route::get('/pendapatan', [LaporanController::class, 'pendapatanReport'])->name('laporan.pendapatan');
    });

});

// Route User
Route::group(['prefix' => '/'], function () {
    Route::get('', [App\Http\Controllers\UserController::class, 'index']);

    // Detail Wisata
    Route::get('/detail-wisata/{id}', [App\Http\Controllers\DetailWisataController::class, 'index'])->name('detail-wisata');
    Route::post('/detail-wisata/{id}', [App\Http\Controllers\UlasanController::class, 'store1'])->name('detail-wisata');

    // Destinasi
    Route::get('/destinasi', [App\Http\Controllers\DestinasiController::class, 'index'])->name('user.destinasi');

    // Pemesanan User
    Route::post('/detail-wisata/{id}', [PemesananController::class, 'storeUser'])->name('pemesanan.storeUser');

    // Pembayaran
    Route::get('/detail-pembayaran/{id}', [PembayaranController::class, 'index'])->name('user.detail_pembayaran');

    // Midtrans Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/midtrans/create-transaction', [MidtransController::class, 'createTransaction']);
        Route::post('/midtrans/callback', [MidtransController::class, 'paymentCallback']);
    });
    Route::get('/pembayaran/success/{order_id}', [PembayaranController::class, 'success']);

});

// Google OAuth Routes

Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::get('/qr/activate/url', [DetailPemesananController::class, 'activateQrFromUrl'])->name('qr.activate.url');
