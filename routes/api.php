<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Logout;
// import controller
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\WisataController;

Route::get('/profile', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Kategori
Route::get('kategori', [KategoriController::class, 'index'])->middleware('auth:sanctum');
Route::post('tiket', [TiketController::class, 'store'])->middleware('auth:sanctum');

// Route::group(['middleware' => 'auth:sanctum'], function () {
//     Route::get('kategori', [KategoriController::class, 'index']);
//     Route::post('kategori', [KategoriController::class, 'store']);
//     Route::get('kategori/{id}', [KategoriController::class, 'show']);
//     Route::put('kategori/{id}', [KategoriController::class, 'update']);
//     Route::delete('kategori/{id}', [KategoriController::class, 'destroy']);
// });

Route::post('midtrans/callback', [MidtransController::class, 'paymentCallback'])->name('midtrans.callback');
Route::post('/midtrans/create-transaction', [MidtransController::class, 'createTransaction']);

