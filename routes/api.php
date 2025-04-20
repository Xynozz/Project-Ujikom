<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Logout;
// import controller
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DetailWisataController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
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
Route::get('kategori', [KategoriController::class, 'index2'])->middleware('auth:sanctum');

// Beranda
Route::get('home', [UserController::class, 'index2'])->middleware('auth:sanctum');

// Midtrans
Route::post('midtrans/callback', [MidtransController::class, 'paymentCallback'])->name('midtrans.callback');
Route::post('/midtrans/create-transaction', [MidtransController::class, 'createTransaction']);

// API LOKASI
Route::get('/wisata/{id}', [WisataController::class, 'indexApi'])->middleware('auth:sanctum');
Route::post('/wisata', [WisataController::class, 'store'])->middleware('auth:sanctum');
Route::get('/wisata-list', [WisataController::class, 'list'])->middleware('auth:sanctum');
Route::get('/detail-wisata/{id}', [DetailWisataController::class, 'index']);