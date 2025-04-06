<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\isAdmin;
use App\Models\Detail_pemesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// Route Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', isAdmin::class]], function () {

    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('kategori', KategoriController::class);
    Route::resource('wisata', WisataController::class);
    Route::resource('ulasan', UlasanController::class);
    Route::resource('tiket', TiketController::class);
    Route::resource('pemesanan', PemesananController::class);

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
    Route::get('/detail-wisata/{id}', [App\Http\Controllers\UserController::class, 'detailWisata'])->name('detail_wisata');
    });

// Google OAuth Routes
Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);


Route::post('/aktivasi-barcode', function (Request $request) {
    $barcode = $request->barcode;

    $detail = Detail_pemesanan::where('barcode', $barcode)->first();

    if (!$detail) {
        return response()->json(['barcode' => null, 'message' => 'Barcode tidak ditemukan.']);
    }

    if ($detail->barcode) {
        return response()->json(['barcode' => false, 'message' => 'Barcode sudah pernah diaktivasi.']);
    }

    // Aktifkan barcode
    $detail->barcode = True;
    $detail->save();

    return response()->json(['barcode' => true, 'message' => 'Barcode berhasil diaktivasi!']);
});
