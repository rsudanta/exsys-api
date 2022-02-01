<?php

use App\Http\Controllers\API\MesinInferensiController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::get('gejala', [MesinInferensiController::class, 'getGejala']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('hitung', [MesinInferensiController::class, 'hitungCF']);
    Route::get('riwayat-konsultasi', [UserController::class, 'getRiwayatKonsultasi']);
    Route::post('update/password', [UserController::class, 'updatePassword']);
    Route::post('update/profile', [UserController::class, 'updateProfile']);
});
